<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Panel Resource Controller
 * 
 * Handles CRUD operations for resources in the tenant panel
 * Includes combination management for tables and capacity configuration
 */
class ResourceController extends Controller
{
    /**
     * Display a listing of resources
     */
    public function index(Request $request)
    {
        $query = Resource::with('location')
            ->where('tenant_id', tenant('id'));

        // Filter by type if provided
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by location if provided
        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        $resources = $query->orderBy('type')->orderBy('label')->get();

        // Get filter options
        $locations = Location::where('tenant_id', tenant('id'))
            ->where('active', true)
            ->orderBy('name')
            ->get();

        $resourceTypes = [
            Resource::TYPE_TABLE => __('panel.resources.types.TABLE'),
            Resource::TYPE_STAFF => __('panel.resources.types.STAFF'),
            Resource::TYPE_ROOM => __('panel.resources.types.ROOM'),
            Resource::TYPE_EQUIPMENT => __('panel.resources.types.EQUIPMENT'),
        ];

        return view('panel.resources.index', compact('resources', 'locations', 'resourceTypes'));
    }

    /**
     * Show the form for creating a new resource
     */
    public function create()
    {
        $locations = Location::where('tenant_id', tenant('id'))
            ->where('active', true)
            ->orderBy('name')
            ->get();

        $resourceTypes = [
            Resource::TYPE_TABLE => __('panel.resources.types.TABLE'),
            Resource::TYPE_STAFF => __('panel.resources.types.STAFF'),
            Resource::TYPE_ROOM => __('panel.resources.types.ROOM'),
            Resource::TYPE_EQUIPMENT => __('panel.resources.types.EQUIPMENT'),
        ];

        $combinationTypes = [
            Resource::TYPE_TABLE => __('panel.resources.types.TABLE'),
            Resource::TYPE_STAFF => __('panel.resources.types.STAFF'),
            Resource::TYPE_ROOM => __('panel.resources.types.ROOM'),
        ];

        return view('panel.resources.create', compact('locations', 'resourceTypes', 'combinationTypes'));
    }

    /**
     * Store a newly created resource
     */
    public function store(Request $request)
    {
        $request->validate([
            'location_id' => 'required|exists:locations,id',
            'type' => 'required|in:' . implode(',', [
                Resource::TYPE_TABLE,
                Resource::TYPE_STAFF,
                Resource::TYPE_ROOM,
                Resource::TYPE_EQUIPMENT,
            ]),
            'label_es' => 'required|string|max:255',
            'label_en' => 'required|string|max:255',
            'label_nl' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1|max:100',
            'combinable_with' => 'nullable|array',
            'combinable_with.*' => 'in:' . implode(',', [
                Resource::TYPE_TABLE,
                Resource::TYPE_STAFF,
                Resource::TYPE_ROOM,
            ]),
            'active' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            $resource = Resource::create([
                'tenant_id' => tenant('id'),
                'location_id' => $request->location_id,
                'type' => $request->type,
                'label' => [
                    'es' => $request->label_es,
                    'en' => $request->label_en,
                    'nl' => $request->label_nl,
                ],
                'capacity' => $request->capacity,
                'combinable_with' => $request->combinable_with ?? [],
                'active' => $request->boolean('active', true),
            ]);

            DB::commit();

            return redirect()->route('panel.resources.show', $resource)
                           ->with('success', __('panel.resources.created_successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withErrors(['error' => $e->getMessage()])
                       ->withInput();
        }
    }

    /**
     * Display the specified resource
     */
    public function show(Resource $resource)
    {
        $this->authorize('view', $resource);

        $resource->load('location');

        // Get bookings count for this resource
        $bookingsCount = $resource->bookings()->count();
        $activeBookingsCount = $resource->bookings()
            ->whereHas('booking', function ($query) {
                $query->where('status', 'confirmed')
                      ->where('start_at', '>', now());
            })
            ->count();

        return view('panel.resources.show', compact('resource', 'bookingsCount', 'activeBookingsCount'));
    }

    /**
     * Show the form for editing the specified resource
     */
    public function edit(Resource $resource)
    {
        $this->authorize('update', $resource);

        $resource->load('location');

        $locations = Location::where('tenant_id', tenant('id'))
            ->where('active', true)
            ->orderBy('name')
            ->get();

        $resourceTypes = [
            Resource::TYPE_TABLE => __('panel.resources.types.TABLE'),
            Resource::TYPE_STAFF => __('panel.resources.types.STAFF'),
            Resource::TYPE_ROOM => __('panel.resources.types.ROOM'),
            Resource::TYPE_EQUIPMENT => __('panel.resources.types.EQUIPMENT'),
        ];

        $combinationTypes = [
            Resource::TYPE_TABLE => __('panel.resources.types.TABLE'),
            Resource::TYPE_STAFF => __('panel.resources.types.STAFF'),
            Resource::TYPE_ROOM => __('panel.resources.types.ROOM'),
        ];

        return view('panel.resources.edit', compact('resource', 'locations', 'resourceTypes', 'combinationTypes'));
    }

    /**
     * Update the specified resource
     */
    public function update(Request $request, Resource $resource)
    {
        $this->authorize('update', $resource);

        $request->validate([
            'location_id' => 'required|exists:locations,id',
            'type' => 'required|in:' . implode(',', [
                Resource::TYPE_TABLE,
                Resource::TYPE_STAFF,
                Resource::TYPE_ROOM,
                Resource::TYPE_EQUIPMENT,
            ]),
            'label_es' => 'required|string|max:255',
            'label_en' => 'required|string|max:255',
            'label_nl' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1|max:100',
            'combinable_with' => 'nullable|array',
            'combinable_with.*' => 'in:' . implode(',', [
                Resource::TYPE_TABLE,
                Resource::TYPE_STAFF,
                Resource::TYPE_ROOM,
            ]),
            'active' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            $resource->update([
                'location_id' => $request->location_id,
                'type' => $request->type,
                'label' => [
                    'es' => $request->label_es,
                    'en' => $request->label_en,
                    'nl' => $request->label_nl,
                ],
                'capacity' => $request->capacity,
                'combinable_with' => $request->combinable_with ?? [],
                'active' => $request->boolean('active', true),
            ]);

            DB::commit();

            return redirect()->route('panel.resources.show', $resource)
                           ->with('success', __('panel.resources.updated_successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withErrors(['error' => $e->getMessage()])
                       ->withInput();
        }
    }

    /**
     * Remove the specified resource
     */
    public function destroy(Resource $resource)
    {
        $this->authorize('delete', $resource);

        try {
            // Check if resource has active bookings
            $activeBookings = $resource->bookings()
                ->whereHas('booking', function ($query) {
                    $query->where('status', 'confirmed')
                          ->where('start_at', '>', now());
                })
                ->count();

            if ($activeBookings > 0) {
                return back()->withErrors(['error' => __('panel.resources.cannot_delete_with_bookings')]);
            }

            $resource->delete();

            return redirect()->route('panel.resources.index')
                           ->with('success', __('panel.resources.deleted_successfully'));

        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Toggle resource active status
     */
    public function toggle(Resource $resource)
    {
        $this->authorize('update', $resource);

        $resource->update(['active' => !$resource->active]);

        $message = $resource->active 
            ? __('panel.resources.activated_successfully')
            : __('panel.resources.deactivated_successfully');

        return back()->with('success', $message);
    }
}