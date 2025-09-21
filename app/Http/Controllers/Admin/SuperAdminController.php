<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Stancl\Tenancy\Database\Models\Domain;

class SuperAdminController extends Controller
{
    public function __construct()
    {
        // No middleware needed here, we handle auth manually in the routes
    }

    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Verificar credenciales de super admin
        if ($credentials['email'] === 'admin@book.aimadarek.com' && 
            $credentials['password'] === 'SuperAdmin!2025') {
            
            $request->session()->put('super_admin', true);
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'Credenciales incorrectas.',
        ]);
    }

    public function logout(Request $request)
    {
        $request->session()->forget('super_admin');
        return redirect()->route('admin.login');
    }

    public function dashboard()
    {
        $stats = [
            'total_tenants' => Tenant::count(),
            'total_users' => User::count(),
            'total_bookings' => $this->getTotalBookings(),
            'active_tenants' => Tenant::where('active', true)->count(),
        ];

        $recent_tenants = Tenant::latest()->take(5)->get();
        $tenant_stats = $this->getTenantStats();

        return view('admin.dashboard', compact('stats', 'recent_tenants', 'tenant_stats'));
    }

    public function tenants()
    {
        $tenants = Tenant::with('domains')->paginate(10);
        return view('admin.tenants.index', compact('tenants'));
    }

    public function createTenant()
    {
        return view('admin.tenants.create');
    }

    public function storeTenant(Request $request)
    {
        $request->validate([
            'id' => ['required', 'string', 'unique:tenants,id', 'max:255'],
            'brand_name' => ['required', 'string', 'max:255'],
            'default_locale' => ['required', 'string', 'in:es,en,nl'],
            'supported_locales' => ['required', 'array'],
            'supported_locales.*' => ['string', 'in:es,en,nl'],
            'timezone' => ['required', 'string'],
            'owner_email' => ['required', 'email'],
            'owner_name' => ['required', 'string', 'max:255'],
        ]);

        try {
            // Crear tenant
            $tenant = Tenant::create([
                'id' => $request->input('id'),
                'brand_name' => $request->input('brand_name'),
                'default_locale' => $request->input('default_locale'),
                'supported_locales' => $request->input('supported_locales'),
                'timezone' => $request->input('timezone'),
                'active' => true,
            ]);

            // Crear dominio
            $domain = "{$request->input('id')}.book.aimadarek.com";
            Domain::create([
                'domain' => $domain,
                'tenant_id' => $tenant->id,
            ]);

            // Crear usuario owner
            User::create([
                'name' => $request->input('owner_name'),
                'email' => $request->input('owner_email'),
                'password' => Hash::make('Demo!1234'),
                'tenant_id' => $tenant->id,
                'role' => User::ROLE_OWNER,
                'active' => true,
            ]);

            // Crear base de datos del tenant
            $tenant->run(function () {
                // Ejecutar migraciones del tenant
                \Artisan::call('migrate', ['--force' => true]);
                // Ejecutar seeders básicos
                \Artisan::call('db:seed', ['--class' => 'TenantSeeder']);
            });

            return redirect()->route('admin.tenants.index')
                ->with('success', "Empresa '{$tenant->brand_name}' creada exitosamente.");

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al crear la empresa: ' . $e->getMessage()]);
        }
    }

    public function showTenant(Tenant $tenant)
    {
        $tenant->load('domains');
        $users = User::where('tenant_id', $tenant->id)->get();
        
        $booking_stats = $this->getTenantBookingStats($tenant->id);
        
        return view('admin.tenants.show', compact('tenant', 'users', 'booking_stats'));
    }

    public function editTenant(Tenant $tenant)
    {
        return view('admin.tenants.edit', compact('tenant'));
    }

    public function updateTenant(Request $request, Tenant $tenant)
    {
        $request->validate([
            'brand_name' => ['required', 'string', 'max:255'],
            'default_locale' => ['required', 'string', 'in:es,en,nl'],
            'supported_locales' => ['required', 'array'],
            'supported_locales.*' => ['string', 'in:es,en,nl'],
            'timezone' => ['required', 'string'],
            'active' => ['boolean'],
        ]);

        $tenant->update([
            'brand_name' => $request->input('brand_name'),
            'default_locale' => $request->input('default_locale'),
            'supported_locales' => $request->input('supported_locales'),
            'timezone' => $request->input('timezone'),
            'active' => $request->boolean('active'),
        ]);

        return redirect()->route('admin.tenants.index')
            ->with('success', 'Empresa actualizada exitosamente.');
    }

    public function destroyTenant(Tenant $tenant)
    {
        try {
            // Eliminar dominio
            $tenant->domains()->delete();
            
            // Eliminar usuarios
            User::where('tenant_id', $tenant->id)->delete();
            
            // Eliminar tenant y su base de datos
            $tenant->delete();

            return redirect()->route('admin.tenants.index')
                ->with('success', 'Empresa eliminada exitosamente.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al eliminar la empresa: ' . $e->getMessage()]);
        }
    }

    private function getTotalBookings()
    {
        $total = 0;
        foreach (Tenant::all() as $tenant) {
            $tenant->run(function () use (&$total) {
                $total += \App\Models\Booking::count();
            });
        }
        return $total;
    }

    private function getTenantStats()
    {
        $stats = [];
        foreach (Tenant::all() as $tenant) {
            $tenant->run(function () use (&$stats, $tenant) {
                $stats[$tenant->id] = [
                    'bookings' => \App\Models\Booking::count(),
                    'services' => \App\Models\Service::count(),
                    'customers' => \App\Models\Customer::count(),
                ];
            });
        }
        return $stats;
    }

    private function getTenantBookingStats($tenantId)
    {
        $tenant = Tenant::find($tenantId);
        if (!$tenant) return [];

        $stats = [];
        $tenant->run(function () use (&$stats) {
            $stats = [
                'total_bookings' => \App\Models\Booking::count(),
                'today_bookings' => \App\Models\Booking::whereDate('start_at', today())->count(),
                'this_month' => \App\Models\Booking::whereMonth('start_at', now()->month)->count(),
                'confirmed' => \App\Models\Booking::where('status', 'confirmed')->count(),
                'pending' => \App\Models\Booking::where('status', 'pending')->count(),
                'cancelled' => \App\Models\Booking::where('status', 'cancelled')->count(),
            ];
        });

        return $stats;
    }

    // ==================== USERS MANAGEMENT ====================

    /**
     * Display a listing of all users across all tenants
     */
    public function users()
    {
        $users = User::with('tenant')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function createUser()
    {
        $tenants = Tenant::where('active', true)->get();
        return view('admin.users.create', compact('tenants'));
    }

    /**
     * Store a newly created user
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'tenant_id' => ['required', 'exists:tenants,id'],
            'role' => ['required', 'string', 'in:owner,admin,staff'],
            'active' => ['boolean'],
        ]);

        try {
            User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'tenant_id' => $request->input('tenant_id'),
                'role' => $request->input('role'),
                'active' => $request->boolean('active', true),
            ]);

            return redirect()->route('admin.users.index')
                ->with('success', 'Usuario creado exitosamente.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al crear el usuario: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified user
     */
    public function showUser(User $user)
    {
        $user->load('tenant');
        $booking_stats = $this->getUserBookingStats($user->id);
        
        return view('admin.users.show', compact('user', 'booking_stats'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function editUser(User $user)
    {
        $tenants = Tenant::where('active', true)->get();
        return view('admin.users.edit', compact('user', 'tenants'));
    }

    /**
     * Update the specified user
     */
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'tenant_id' => ['required', 'exists:tenants,id'],
            'role' => ['required', 'string', 'in:owner,admin,staff'],
            'active' => ['boolean'],
        ]);

        $updateData = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'tenant_id' => $request->input('tenant_id'),
            'role' => $request->input('role'),
            'active' => $request->boolean('active'),
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $request->validate(['password' => ['string', 'min:8']]);
            $updateData['password'] = Hash::make($request->input('password'));
        }

        $user->update($updateData);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Remove the specified user
     */
    public function destroyUser(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('admin.users.index')
                ->with('success', 'Usuario eliminado exitosamente.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al eliminar el usuario: ' . $e->getMessage()]);
        }
    }

    /**
     * Toggle user active status
     */
    public function toggleUser(User $user)
    {
        $user->update(['active' => !$user->active]);
        
        $status = $user->active ? 'activado' : 'desactivado';
        return redirect()->back()
            ->with('success', "Usuario {$status} exitosamente.");
    }

    /**
     * Get booking statistics for a specific user
     */
    private function getUserBookingStats($userId)
    {
        $user = User::find($userId);
        if (!$user || !$user->tenant) return [];

        $stats = [];
        $user->tenant->run(function () use (&$stats, $userId) {
            $stats = [
                'total_bookings' => \App\Models\Booking::where('created_by', $userId)->count(),
                'today_bookings' => \App\Models\Booking::where('created_by', $userId)
                    ->whereDate('start_at', today())->count(),
                'this_month' => \App\Models\Booking::where('created_by', $userId)
                    ->whereMonth('start_at', now()->month)->count(),
            ];
        });

        return $stats;
    }

    // ==================== SYSTEM CONFIGURATION ====================

    /**
     * Display system settings
     */
    public function settings()
    {
        $system_stats = [
            'total_tenants' => Tenant::count(),
            'active_tenants' => Tenant::where('active', true)->count(),
            'total_users' => User::count(),
            'active_users' => User::where('active', true)->count(),
            'total_bookings' => $this->getTotalBookings(),
            'system_uptime' => $this->getSystemUptime(),
            'disk_usage' => $this->getDiskUsage(),
            'memory_usage' => $this->getMemoryUsage(),
        ];

        return view('admin.settings.index', compact('system_stats'));
    }

    /**
     * Update system settings
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'maintenance_mode' => ['boolean'],
            'max_tenants' => ['integer', 'min:1'],
            'backup_frequency' => ['string', 'in:daily,weekly,monthly'],
            'email_notifications' => ['boolean'],
        ]);

        // Here you would typically update configuration files or database settings
        // For now, we'll just show a success message
        
        return redirect()->route('admin.settings.index')
            ->with('success', 'Configuración del sistema actualizada exitosamente.');
    }

    /**
     * Display backup management page
     */
    public function backup()
    {
        $backups = $this->getBackupList();
        return view('admin.settings.backup', compact('backups'));
    }

    /**
     * Create a new backup
     */
    public function createBackup(Request $request)
    {
        try {
            // Here you would implement actual backup creation
            // For now, we'll simulate it
            
            $backup_name = 'backup_' . now()->format('Y_m_d_H_i_s') . '.sql';
            
            return redirect()->route('admin.settings.backup')
                ->with('success', "Backup '{$backup_name}' creado exitosamente.");

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al crear el backup: ' . $e->getMessage()]);
        }
    }

    /**
     * Get system uptime information
     */
    private function getSystemUptime()
    {
        try {
            $uptime = shell_exec('uptime -p');
            return $uptime ? trim($uptime) : 'No disponible';
        } catch (\Exception $e) {
            return 'No disponible';
        }
    }

    /**
     * Get disk usage information
     */
    private function getDiskUsage()
    {
        try {
            $bytes = disk_free_space('/');
            $total_bytes = disk_total_space('/');
            $used_bytes = $total_bytes - $bytes;
            
            return [
                'used' => $this->formatBytes($used_bytes),
                'free' => $this->formatBytes($bytes),
                'total' => $this->formatBytes($total_bytes),
                'percentage' => round(($used_bytes / $total_bytes) * 100, 2)
            ];
        } catch (\Exception $e) {
            return ['used' => 'N/A', 'free' => 'N/A', 'total' => 'N/A', 'percentage' => 0];
        }
    }

    /**
     * Get memory usage information
     */
    private function getMemoryUsage()
    {
        try {
            $meminfo = file_get_contents('/proc/meminfo');
            preg_match('/MemTotal:\s+(\d+)/', $meminfo, $total);
            preg_match('/MemAvailable:\s+(\d+)/', $meminfo, $available);
            
            if (isset($total[1]) && isset($available[1])) {
                $total_kb = $total[1];
                $available_kb = $available[1];
                $used_kb = $total_kb - $available_kb;
                
                return [
                    'used' => $this->formatBytes($used_kb * 1024),
                    'available' => $this->formatBytes($available_kb * 1024),
                    'total' => $this->formatBytes($total_kb * 1024),
                    'percentage' => round(($used_kb / $total_kb) * 100, 2)
                ];
            }
        } catch (\Exception $e) {
            // Fallback for systems without /proc/meminfo
        }
        
        return ['used' => 'N/A', 'available' => 'N/A', 'total' => 'N/A', 'percentage' => 0];
    }

    /**
     * Get list of available backups
     */
    private function getBackupList()
    {
        // This would typically read from a backup directory
        // For now, we'll return a mock list
        return [
            [
                'name' => 'backup_2024_01_15_10_30_00.sql',
                'size' => '2.5 MB',
                'created_at' => '2024-01-15 10:30:00',
            ],
            [
                'name' => 'backup_2024_01_14_10_30_00.sql',
                'size' => '2.3 MB',
                'created_at' => '2024-01-14 10:30:00',
            ],
        ];
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
