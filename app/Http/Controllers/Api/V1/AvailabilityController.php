<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\AvailabilityRequest;
use App\Models\Service;
use App\Domain\CapacityService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

/**
 * Availability Controller
 * 
 * Handles availability checking for services
 * Returns available time slots based on service requirements and constraints
 */
class AvailabilityController extends Controller
{
    public function __construct(
        private CapacityService $capacityService
    ) {}

    /**
     * Get available time slots for a service
     * 
     * @param AvailabilityRequest $request
     * @return JsonResponse
     */
    public function index(AvailabilityRequest $request): JsonResponse
    {
        try {
            // Get validated data
            $validated = $request->validated();
            
            // Find service
            $service = Service::findOrFail($validated['service_id']);
            
            // Validate service belongs to current tenant
            $this->validateServiceAccess($service);
            
            // Parse date
            $date = Carbon::parse($validated['date']);
            
            // Get available slots
            $slots = $this->capacityService->getAvailableSlots(
                $service,
                $date,
                $validated['party_size'] ?? null,
                $validated['location_id'] ?? null
            );
            
            // Format response
            $response = [
                'service_id' => $service->id,
                'date' => $date->format('Y-m-d'),
                'slots' => $slots->map(function ($slot) {
                    return [
                        'start' => $slot['start'],
                        'end' => $slot['end'],
                    ];
                })->toArray(),
                'locale' => app()->getLocale(),
            ];
            
            return response()->json($response);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'locale' => app()->getLocale(),
            ], 400);
        }
    }
    
    /**
     * Validate that service belongs to current tenant
     */
    private function validateServiceAccess(Service $service): void
    {
        if (!tenancy()->initialized) {
            throw new \Exception(__('api.invalid_tenant'));
        }
        
        $currentTenant = tenancy()->tenant;
        if ($service->tenant_id !== $currentTenant->id) {
            throw new \Exception(__('api.invalid_service'));
        }
    }
}
