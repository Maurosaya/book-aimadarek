<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Panel Customer Controller
 * 
 * Handles CRUD operations for customers in the tenant panel
 * Includes search functionality and booking history
 */
class CustomerController extends Controller
{
    /**
     * Display a listing of customers with search
     */
    public function index(Request $request)
    {
        $query = Customer::where('tenant_id', tenant('id'));

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Apply GDPR filter
        if ($request->filled('gdpr_optin')) {
            $query->where('gdpr_optin', $request->gdpr_optin === '1');
        }

        $customers = $query->orderBy('name')->paginate(20);

        return view('panel.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new customer
     */
    public function create()
    {
        return view('panel.customers.create');
    }

    /**
     * Store a newly created customer
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:1000',
            'gdpr_optin' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            $customer = Customer::create([
                'tenant_id' => tenant('id'),
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'notes' => $request->notes,
                'gdpr_optin' => $request->boolean('gdpr_optin', false),
            ]);

            DB::commit();

            return redirect()->route('panel.customers.show', $customer)
                           ->with('success', __('panel.customers.created_successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withErrors(['error' => $e->getMessage()])
                       ->withInput();
        }
    }

    /**
     * Display the specified customer with booking history
     */
    public function show(Customer $customer)
    {
        $this->authorize('view', $customer);

        // Get customer's booking history
        $bookings = $customer->bookings()
            ->with(['service', 'resources'])
            ->orderBy('start_at', 'desc')
            ->paginate(10);

        // Get booking statistics
        $stats = [
            'total_bookings' => $customer->bookings()->count(),
            'confirmed_bookings' => $customer->bookings()->where('status', 'confirmed')->count(),
            'cancelled_bookings' => $customer->bookings()->where('status', 'cancelled')->count(),
            'no_show_bookings' => $customer->bookings()->where('status', 'no_show')->count(),
            'total_spent' => $customer->bookings()
                ->where('status', 'confirmed')
                ->join('services', 'bookings.service_id', '=', 'services.id')
                ->sum('services.price_cents') / 100,
        ];

        return view('panel.customers.show', compact('customer', 'bookings', 'stats'));
    }

    /**
     * Show the form for editing the specified customer
     */
    public function edit(Customer $customer)
    {
        $this->authorize('update', $customer);

        return view('panel.customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer
     */
    public function update(Request $request, Customer $customer)
    {
        $this->authorize('update', $customer);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:1000',
            'gdpr_optin' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            $customer->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'notes' => $request->notes,
                'gdpr_optin' => $request->boolean('gdpr_optin', false),
            ]);

            DB::commit();

            return redirect()->route('panel.customers.show', $customer)
                           ->with('success', __('panel.customers.updated_successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withErrors(['error' => $e->getMessage()])
                       ->withInput();
        }
    }

    /**
     * Remove the specified customer
     */
    public function destroy(Customer $customer)
    {
        $this->authorize('delete', $customer);

        try {
            // Check if customer has bookings
            $bookingsCount = $customer->bookings()->count();

            if ($bookingsCount > 0) {
                return back()->withErrors(['error' => __('panel.customers.cannot_delete_with_bookings')]);
            }

            $customer->delete();

            return redirect()->route('panel.customers.index')
                           ->with('success', __('panel.customers.deleted_successfully'));

        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Get customer suggestions for autocomplete
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $customers = Customer::where('tenant_id', tenant('id'))
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%")
                  ->orWhere('phone', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get(['id', 'name', 'email', 'phone']);

        return response()->json($customers);
    }

    /**
     * Export customers data
     */
    public function export(Request $request)
    {
        $customers = Customer::where('tenant_id', tenant('id'))
            ->with(['bookings' => function ($query) {
                $query->select('customer_id', 'status')
                      ->selectRaw('COUNT(*) as total_bookings')
                      ->selectRaw('SUM(CASE WHEN status = "confirmed" THEN 1 ELSE 0 END) as confirmed_bookings')
                      ->groupBy('customer_id', 'status');
            }])
            ->orderBy('name')
            ->get();

        $csvData = [];
        $csvData[] = ['Name', 'Email', 'Phone', 'GDPR Opt-in', 'Total Bookings', 'Confirmed Bookings', 'Created At'];

        foreach ($customers as $customer) {
            $totalBookings = $customer->bookings->sum('total_bookings');
            $confirmedBookings = $customer->bookings->sum('confirmed_bookings');

            $csvData[] = [
                $customer->name,
                $customer->email,
                $customer->phone,
                $customer->gdpr_optin ? 'Yes' : 'No',
                $totalBookings,
                $confirmedBookings,
                $customer->created_at->format('Y-m-d H:i:s'),
            ];
        }

        $filename = 'customers_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($csvData) {
            $file = fopen('php://output', 'w');
            
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}