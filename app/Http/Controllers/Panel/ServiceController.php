<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Panel Service Controller
 * 
 * Handles CRUD operations for services in the tenant panel
 * Includes translation support and resource type configuration
 */
class ServiceController extends Controller
{
    /**
     * Display a listing of services
     */
    public function index()
    {
        $services = Service::where('tenant_id', tenant('id'))
            ->orderBy('name')
            ->get();

        return view('panel.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new service
     */
    public function create()
    {
        $resourceTypes = [
            'TABLE' => __('panel.services.resource_types.TABLE'),
            'STAFF' => __('panel.services.resource_types.STAFF'),
            'ROOM' => __('panel.services.resource_types.ROOM'),
            'EQUIPMENT' => __('panel.services.resource_types.EQUIPMENT'),
        ];

        return view('panel.services.create', compact('resourceTypes'));
    }

    /**
     * Store a newly created service
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_es' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'name_nl' => 'required|string|max:255',
            'duration_min' => 'required|integer|min:1|max:1440',
            'buffer_before_min' => 'required|integer|min:0|max:120',
            'buffer_after_min' => 'required|integer|min:0|max:120',
            'price_cents' => 'nullable|integer|min:0',
            'required_resource_types' => 'required|array|min:1',
            'required_resource_types.*' => 'in:TABLE,STAFF,ROOM,EQUIPMENT',
            'active' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            $service = Service::create([
                'tenant_id' => tenant('id'),
                'name' => [
                    'es' => $request->name_es,
                    'en' => $request->name_en,
                    'nl' => $request->name_nl,
                ],
                'duration_min' => $request->duration_min,
                'buffer_before_min' => $request->buffer_before_min,
                'buffer_after_min' => $request->buffer_after_min,
                'price_cents' => $request->price_cents ?: null,
                'required_resource_types' => $request->required_resource_types,
                'active' => $request->boolean('active', true),
            ]);

            DB::commit();

            return redirect()->route('panel.services.show', $service)
                           ->with('success', __('panel.services.created_successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withErrors(['error' => $e->getMessage()])
                       ->withInput();
        }
    }

    /**
     * Display the specified service
     */
    public function show(Service $service)
    {
        $this->authorize('view', $service);

        // Get bookings count for this service
        $bookingsCount = $service->bookings()->count();
        $activeBookingsCount = $service->bookings()->where('status', 'confirmed')->count();

        return view('panel.services.show', compact('service', 'bookingsCount', 'activeBookingsCount'));
    }

    /**
     * Show the form for editing the specified service
     */
    public function edit(Service $service)
    {
        $this->authorize('update', $service);

        $resourceTypes = [
            'TABLE' => __('panel.services.resource_types.TABLE'),
            'STAFF' => __('panel.services.resource_types.STAFF'),
            'ROOM' => __('panel.services.resource_types.ROOM'),
            'EQUIPMENT' => __('panel.services.resource_types.EQUIPMENT'),
        ];

        return view('panel.services.edit', compact('service', 'resourceTypes'));
    }

    /**
     * Update the specified service
     */
    public function update(Request $request, Service $service)
    {
        $this->authorize('update', $service);

        $request->validate([
            'name_es' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'name_nl' => 'required|string|max:255',
            'duration_min' => 'required|integer|min:1|max:1440',
            'buffer_before_min' => 'required|integer|min:0|max:120',
            'buffer_after_min' => 'required|integer|min:0|max:120',
            'price_cents' => 'nullable|integer|min:0',
            'required_resource_types' => 'required|array|min:1',
            'required_resource_types.*' => 'in:TABLE,STAFF,ROOM,EQUIPMENT',
            'active' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            $service->update([
                'name' => [
                    'es' => $request->name_es,
                    'en' => $request->name_en,
                    'nl' => $request->name_nl,
                ],
                'duration_min' => $request->duration_min,
                'buffer_before_min' => $request->buffer_before_min,
                'buffer_after_min' => $request->buffer_after_min,
                'price_cents' => $request->price_cents ?: null,
                'required_resource_types' => $request->required_resource_types,
                'active' => $request->boolean('active', true),
            ]);

            DB::commit();

            return redirect()->route('panel.services.show', $service)
                           ->with('success', __('panel.services.updated_successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withErrors(['error' => $e->getMessage()])
                       ->withInput();
        }
    }

    /**
     * Remove the specified service
     */
    public function destroy(Service $service)
    {
        $this->authorize('delete', $service);

        try {
            // Check if service has active bookings
            $activeBookings = $service->bookings()
                ->where('status', 'confirmed')
                ->where('start_at', '>', now())
                ->count();

            if ($activeBookings > 0) {
                return back()->withErrors(['error' => __('panel.services.cannot_delete_with_bookings')]);
            }

            $service->delete();

            return redirect()->route('panel.services.index')
                           ->with('success', __('panel.services.deleted_successfully'));

        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Toggle service active status
     */
    public function toggle(Service $service)
    {
        $this->authorize('update', $service);

        $service->update(['active' => !$service->active]);

        $message = $service->active 
            ? __('panel.services.activated_successfully')
            : __('panel.services.deactivated_successfully');

        return back()->with('success', $message);
    }
}