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
}
