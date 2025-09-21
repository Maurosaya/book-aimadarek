<?php

namespace App\Domain;

use App\Events\BookingCancelled;
use App\Events\BookingCreated;
use App\Models\Booking;
use App\Models\BookingAllocation;
use App\Models\Customer;
use App\Models\Location;
use App\Models\Resource;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Service responsible for managing booking operations
 * Handles creation, cancellation, and resource allocation
 */
class BookingService
{
    public function __construct(
        private CapacityService $capacityService,
        private TableAllocator $tableAllocator
    ) {}

    /**
     * Create a new booking
     *
     * @param Service $service The service to book
     * @param Carbon $startTime The start time for the booking
     * @param array $customerData Customer information
     * @param int|null $partySize Party size (for restaurant services)
     * @param string|null $notes Additional notes
     * @param string|null $source Booking source (e.g., 'flowise', 'widget')
     * @param Location|null $location Specific location
     * @return Booking The created booking
     * @throws \Exception If booking cannot be created
     */
    public function createBooking(
        Service $service,
        Carbon $startTime,
        array $customerData,
        ?int $partySize = null,
        ?string $notes = null,
        ?string $source = null,
        ?Location $location = null
    ): Booking {
        return DB::transaction(function () use (
            $service,
            $startTime,
            $customerData,
            $partySize,
            $notes,
            $source,
            $location
        ) {
            // Validate availability
            $this->validateAvailability($service, $startTime, $partySize, $location);
            
            // Create or find customer
            $customer = $this->createOrFindCustomer($customerData);
            
            // Calculate end time
            $endTime = $startTime->copy()->addMinutes($service->duration_min);
            
            // Create booking
            $booking = Booking::create([
                'tenant_id' => tenant('id'),
                'service_id' => $service->id,
                'start_at' => $startTime,
                'end_at' => $endTime,
                'party_size' => $partySize,
                'status' => Booking::STATUS_CONFIRMED,
                'source' => $source,
                'notes' => $notes,
                'customer_id' => $customer->id,
            ]);
            
            // Allocate resources
            $allocatedResources = $this->allocateResources($service, $startTime, $endTime, $partySize, $location);
            
            // Create booking allocations
            foreach ($allocatedResources as $resource) {
                BookingAllocation::create([
                    'booking_id' => $booking->id,
                    'resource_id' => $resource->id,
                ]);
            }
            
            // Dispatch event
            event(new BookingCreated($booking));
            
            Log::info('Booking created', [
                'booking_id' => $booking->id,
                'service_id' => $service->id,
                'customer_id' => $customer->id,
                'start_at' => $startTime,
                'party_size' => $partySize,
            ]);
            
            return $booking;
        });
    }

    /**
     * Cancel a booking
     *
     * @param Booking $booking The booking to cancel
     * @param string|null $reason Reason for cancellation
     * @return Booking The cancelled booking
     * @throws \Exception If booking cannot be cancelled
     */
    public function cancelBooking(Booking $booking, ?string $reason = null): Booking
    {
        if ($booking->isCancelled()) {
            throw new \Exception(__('api.booking.already_cancelled'));
        }

        return DB::transaction(function () use ($booking, $reason) {
            $booking->update([
                'status' => Booking::STATUS_CANCELLED,
                'notes' => $booking->notes . ($reason ? "\nCancellation reason: " . $reason : ''),
            ]);
            
            // Dispatch event
            event(new BookingCancelled($booking));
            
            Log::info('Booking cancelled', [
                'booking_id' => $booking->id,
                'reason' => $reason,
            ]);
            
            return $booking;
        });
    }

    /**
     * Mark a booking as no-show
     *
     * @param Booking $booking The booking to mark as no-show
     * @return Booking The updated booking
     */
    public function markAsNoShow(Booking $booking): Booking
    {
        return DB::transaction(function () use ($booking) {
            $booking->update(['status' => Booking::STATUS_NO_SHOW]);
            
            // Dispatch event
            event(new BookingCancelled($booking));
            
            Log::info('Booking marked as no-show', [
                'booking_id' => $booking->id,
            ]);
            
            return $booking;
        });
    }

    /**
     * Validate that the requested time slot is available
     */
    private function validateAvailability(
        Service $service,
        Carbon $startTime,
        ?int $partySize,
        ?Location $location
    ): void {
        $availableSlots = $this->capacityService->getAvailableSlots(
            $service,
            $startTime->copy()->startOfDay(),
            $partySize,
            $location
        );
        
        $requestedSlot = [
            'start' => $startTime->toISOString(),
            'end' => $startTime->copy()->addMinutes($service->duration_min)->toISOString(),
        ];
        
        $isAvailable = $availableSlots->contains(function ($slot) use ($requestedSlot) {
            return $slot['start'] === $requestedSlot['start'] && $slot['end'] === $requestedSlot['end'];
        });
        
        if (!$isAvailable) {
            throw new \Exception(__('api.booking.conflict'));
        }
    }

    /**
     * Create or find a customer
     */
    private function createOrFindCustomer(array $customerData): Customer
    {
        $customer = Customer::where('tenant_id', tenant('id'))
            ->where('email', $customerData['email'])
            ->first();
            
        if (!$customer) {
            $customer = Customer::create([
                'tenant_id' => tenant('id'),
                'name' => $customerData['name'],
                'email' => $customerData['email'],
                'phone' => $customerData['phone'] ?? null,
                'gdpr_optin' => $customerData['gdpr_optin'] ?? false,
            ]);
        } else {
            // Update customer information if needed
            $customer->update([
                'name' => $customerData['name'],
                'phone' => $customerData['phone'] ?? $customer->phone,
            ]);
        }
        
        return $customer;
    }

    /**
     * Allocate resources for the booking
     */
    private function allocateResources(
        Service $service,
        Carbon $startTime,
        Carbon $endTime,
        ?int $partySize,
        ?Location $location
    ): Collection {
        $allocatedResources = collect();
        $requiredTypes = $service->required_resource_types ?? [];
        
        foreach ($requiredTypes as $type) {
            $resources = $this->allocateResourcesOfType(
                $type,
                $startTime,
                $endTime,
                $partySize,
                $location
            );
            
            $allocatedResources = $allocatedResources->merge($resources);
        }
        
        return $allocatedResources;
    }

    /**
     * Allocate resources of a specific type
     */
    private function allocateResourcesOfType(
        string $type,
        Carbon $startTime,
        Carbon $endTime,
        ?int $partySize,
        ?Location $location
    ): Collection {
        $availableResources = Resource::active()
            ->ofType($type)
            ->when($location, function ($query) use ($location) {
                $query->where('location_id', $location->id);
            })
            ->get()
            ->filter(function ($resource) use ($startTime, $endTime) {
                return !$this->isResourceOccupied($resource, $startTime, $endTime);
            });
            
        if ($availableResources->isEmpty()) {
            throw new \Exception(__('api.availability.no_slots'));
        }
        
        // For restaurant services, use table allocator
        if ($type === Resource::TYPE_TABLE && $partySize) {
            return $this->tableAllocator->allocateTables($availableResources, $partySize);
        }
        
        // For other services, just take the first available resource
        return collect([$availableResources->first()]);
    }

    /**
     * Check if a resource is occupied during a time period
     */
    private function isResourceOccupied(Resource $resource, Carbon $startTime, Carbon $endTime): bool
    {
        return Booking::confirmed()
            ->whereHas('resources', function ($query) use ($resource) {
                $query->where('resource_id', $resource->id);
            })
            ->inTimeRange($startTime, $endTime)
            ->exists();
    }
}
