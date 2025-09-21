<?php

namespace App\Domain;

use App\Models\Booking;
use App\Models\Service;
use App\Models\Customer;
use App\Models\Resource;
use App\Events\BookingCreated;
use App\Events\BookingCancelled;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Booking Service
 * 
 * Handles booking creation, cancellation, and resource allocation
 * Implements transaction safety and concurrency control
 */
class BookingService
{
    public function __construct(
        private CapacityService $capacityService,
        private TableAllocator $tableAllocator
    ) {}

    /**
     * Create a new booking with resource allocation
     * 
     * @param array $bookingData Booking data
     * @param array $customerData Customer data
     * @return Booking Created booking
     * @throws \Exception If booking fails
     */
    public function createBooking(array $bookingData, array $customerData): Booking
    {
        return DB::transaction(function () use ($bookingData, $customerData) {
            // Validate service exists and belongs to current tenant
            $service = Service::findOrFail($bookingData['service_id']);
            $this->validateServiceAccess($service);

            // Parse start time
            $startTime = Carbon::parse($bookingData['start']);
            $endTime = $startTime->copy()->addMinutes($service->total_duration);

            // Check availability before creating booking
            $this->validateAvailability($service, $startTime, $endTime, $bookingData['party_size'] ?? null);

            // Create or find customer
            $customer = $this->createOrFindCustomer($customerData);

            // Create booking
            $booking = Booking::create([
                'service_id' => $service->id,
                'start_at' => $startTime,
                'end_at' => $endTime,
                'party_size' => $bookingData['party_size'] ?? null,
                'status' => Booking::STATUS_CONFIRMED,
                'source' => $bookingData['source'] ?? 'api',
                'notes' => $bookingData['notes'] ?? null,
                'customer_id' => $customer->id,
                'created_by' => auth()->id(),
            ]);

            // Allocate resources
            $allocatedResources = $this->allocateResources($booking, $service, $bookingData['party_size'] ?? null);

            // Attach resources to booking
            $booking->resources()->attach($allocatedResources->pluck('id'));

            // Dispatch booking created event
            event(new BookingCreated($booking));

            Log::info('Booking created successfully', [
                'booking_id' => $booking->id,
                'service_id' => $service->id,
                'customer_id' => $customer->id,
                'allocated_resources' => $allocatedResources->pluck('id')->toArray(),
            ]);

            return $booking->load(['service', 'customer', 'resources']);
        });
    }

    /**
     * Cancel an existing booking
     * 
     * @param Booking $booking Booking to cancel
     * @param string|null $reason Cancellation reason
     * @return Booking Updated booking
     */
    public function cancelBooking(Booking $booking, ?string $reason = null): Booking
    {
        if ($booking->isCancelled()) {
            throw new \Exception(__('api.booking_already_cancelled'));
        }

        $booking->update([
            'status' => Booking::STATUS_CANCELLED,
            'notes' => $booking->notes . ($reason ? "\nCancellation reason: {$reason}" : ''),
        ]);

        // Dispatch booking cancelled event
        event(new BookingCancelled($booking));

        Log::info('Booking cancelled', [
            'booking_id' => $booking->id,
            'reason' => $reason,
        ]);

        return $booking->fresh();
    }

    /**
     * Get booking details with relationships
     * 
     * @param string $bookingId Booking UUID
     * @return Booking
     */
    public function getBooking(string $bookingId): Booking
    {
        return Booking::with(['service', 'customer', 'resources.location'])
            ->findOrFail($bookingId);
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

    /**
     * Validate availability before creating booking
     */
    private function validateAvailability(Service $service, Carbon $startTime, Carbon $endTime, ?int $partySize): void
    {
        $slot = [
            'start' => $startTime->toISOString(),
            'end' => $endTime->toISOString(),
        ];

        if (!$this->capacityService->isSlotAvailable($slot, $service, $partySize)) {
            throw new \Exception(__('api.no_availability'));
        }
    }

    /**
     * Create or find customer
     */
    private function createOrFindCustomer(array $customerData): Customer
    {
        // Try to find existing customer by email or phone
        $customer = null;
        
        if (!empty($customerData['email'])) {
            $customer = Customer::where('email', $customerData['email'])->first();
        }
        
        if (!$customer && !empty($customerData['phone'])) {
            $customer = Customer::where('phone', $customerData['phone'])->first();
        }

        // Create new customer if not found
        if (!$customer) {
            $customer = Customer::create([
                'name' => $customerData['name'],
                'email' => $customerData['email'] ?? null,
                'phone' => $customerData['phone'] ?? null,
                'gdpr_optin' => false, // Default to false, can be updated later
            ]);
        } else {
            // Update existing customer data if needed
            $customer->update([
                'name' => $customerData['name'],
                'email' => $customerData['email'] ?? $customer->email,
                'phone' => $customerData['phone'] ?? $customer->phone,
            ]);
        }

        return $customer;
    }

    /**
     * Allocate resources for the booking
     */
    private function allocateResources(Booking $booking, Service $service, ?int $partySize): \Illuminate\Support\Collection
    {
        $allocatedResources = collect();

        // Allocate resources based on service requirements
        foreach ($service->required_resource_types as $resourceType) {
            $resources = $this->allocateResourceType($resourceType, $service, $partySize, $booking);
            $allocatedResources = $allocatedResources->merge($resources);
        }

        return $allocatedResources;
    }

    /**
     * Allocate specific resource type
     */
    private function allocateResourceType(string $resourceType, Service $service, ?int $partySize, Booking $booking): \Illuminate\Support\Collection
    {
        return match ($resourceType) {
            Resource::TYPE_TABLE => $this->allocateTables($partySize, $service),
            Resource::TYPE_STAFF => $this->allocateStaff($service),
            Resource::TYPE_ROOM => $this->allocateRooms($service),
            Resource::TYPE_CHAIR => $this->allocateChairs($service),
            Resource::TYPE_EQUIPMENT => $this->allocateEquipment($service),
            default => collect()
        };
    }

    /**
     * Allocate tables for restaurant bookings
     */
    private function allocateTables(?int $partySize, Service $service): \Illuminate\Support\Collection
    {
        if (!$partySize) {
            return collect();
        }

        $availableTables = Resource::query()
            ->where('type', Resource::TYPE_TABLE)
            ->where('active', true)
            ->where('location_id', $service->location_id ?? null)
            ->get();

        // Use table allocator to find optimal combination
        return $this->tableAllocator->allocateTables($availableTables, $partySize);
    }

    /**
     * Allocate staff for barber/beauty/dental services
     */
    private function allocateStaff(Service $service): \Illuminate\Support\Collection
    {
        $availableStaff = Resource::query()
            ->where('type', Resource::TYPE_STAFF)
            ->where('active', true)
            ->where('location_id', $service->location_id ?? null)
            ->get();

        // Return first available staff member
        return $availableStaff->take(1);
    }

    /**
     * Allocate rooms for dental services
     */
    private function allocateRooms(Service $service): \Illuminate\Support\Collection
    {
        $availableRooms = Resource::query()
            ->where('type', Resource::TYPE_ROOM)
            ->where('active', true)
            ->where('location_id', $service->location_id ?? null)
            ->get();

        // Return first available room
        return $availableRooms->take(1);
    }

    /**
     * Allocate chairs for beauty services
     */
    private function allocateChairs(Service $service): \Illuminate\Support\Collection
    {
        $availableChairs = Resource::query()
            ->where('type', Resource::TYPE_CHAIR)
            ->where('active', true)
            ->where('location_id', $service->location_id ?? null)
            ->get();

        // Return first available chair
        return $availableChairs->take(1);
    }

    /**
     * Allocate equipment for services
     */
    private function allocateEquipment(Service $service): \Illuminate\Support\Collection
    {
        $availableEquipment = Resource::query()
            ->where('type', Resource::TYPE_EQUIPMENT)
            ->where('active', true)
            ->where('location_id', $service->location_id ?? null)
            ->get();

        // Return first available equipment
        return $availableEquipment->take(1);
    }
}
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
