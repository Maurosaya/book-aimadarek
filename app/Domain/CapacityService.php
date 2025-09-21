<?php

namespace App\Domain;

use App\Models\AvailabilityRule;
use App\Models\Booking;
use App\Models\Location;
use App\Models\Resource;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Service responsible for calculating available time slots
 * based on availability rules, existing bookings, and resource constraints
 */
class CapacityService
{
    /**
     * Get available time slots for a service on a specific date
     *
     * @param Service $service The service to check availability for
     * @param Carbon $date The date to check availability for
     * @param int|null $partySize The party size (for restaurant services)
     * @param Location|null $location The specific location to check
     * @return Collection<array> Available time slots
     */
    public function getAvailableSlots(
        Service $service,
        Carbon $date,
        ?int $partySize = null,
        ?Location $location = null
    ): Collection {
        // Get availability rules for the date
        $rules = $this->getAvailabilityRules($date, $location);
        
        if ($rules->isEmpty()) {
            return collect();
        }

        // Generate time slots based on rules and service duration
        $slots = $this->generateTimeSlots($rules, $service, $date);
        
        // Filter out occupied slots
        $availableSlots = $this->filterAvailableSlots($slots, $service, $date, $partySize, $location);
        
        return $availableSlots;
    }

    /**
     * Get availability rules for a specific date and location
     */
    private function getAvailabilityRules(Carbon $date, ?Location $location): Collection
    {
        $dayOfWeek = $date->dayOfWeek;
        
        return AvailabilityRule::active()
            ->forDay($dayOfWeek)
            ->forLocation($location?->id)
            ->get()
            ->filter(function ($rule) use ($date) {
                return $rule->appliesToDate($date->toDateTime());
            });
    }

    /**
     * Generate time slots based on availability rules and service duration
     */
    private function generateTimeSlots(Collection $rules, Service $service, Carbon $date): Collection
    {
        $slots = collect();
        
        foreach ($rules as $rule) {
            $startTime = Carbon::parse($date->format('Y-m-d') . ' ' . $rule->start_time);
            $endTime = Carbon::parse($date->format('Y-m-d') . ' ' . $rule->end_time);
            
            // Generate slots every 15 minutes
            $current = $startTime->copy();
            while ($current->addMinutes(15)->lte($endTime->subMinutes($service->duration_min))) {
                $slotStart = $current->copy();
                $slotEnd = $slotStart->copy()->addMinutes($service->duration_min);
                
                $slots->push([
                    'start' => $slotStart,
                    'end' => $slotEnd,
                    'rule' => $rule,
                ]);
            }
        }
        
        return $slots;
    }

    /**
     * Filter out occupied slots based on existing bookings and resource availability
     */
    private function filterAvailableSlots(
        Collection $slots,
        Service $service,
        Carbon $date,
        ?int $partySize,
        ?Location $location
    ): Collection {
        return $slots->filter(function ($slot) use ($service, $date, $partySize, $location) {
            // Check if the slot conflicts with existing bookings
            if ($this->hasBookingConflict($slot, $service, $date, $location)) {
                return false;
            }
            
            // Check resource availability based on service type
            if (!$this->hasResourceAvailability($slot, $service, $partySize, $location)) {
                return false;
            }
            
            return true;
        })->map(function ($slot) {
            return [
                'start' => $slot['start']->toISOString(),
                'end' => $slot['end']->toISOString(),
            ];
        });
    }

    /**
     * Check if a slot conflicts with existing bookings
     */
    private function hasBookingConflict(array $slot, Service $service, Carbon $date, ?Location $location): bool
    {
        $conflictingBookings = Booking::confirmed()
            ->whereHas('service', function ($query) use ($service) {
                $query->where('id', $service->id);
            })
            ->inTimeRange($slot['start'], $slot['end'])
            ->when($location, function ($query) use ($location) {
                $query->whereHas('resources.location', function ($q) use ($location) {
                    $q->where('id', $location->id);
                });
            })
            ->exists();
            
        return $conflictingBookings;
    }

    /**
     * Check if resources are available for the slot
     */
    private function hasResourceAvailability(
        array $slot,
        Service $service,
        ?int $partySize,
        ?Location $location
    ): bool {
        $requiredTypes = $service->required_resource_types ?? [];
        
        foreach ($requiredTypes as $type) {
            if (!$this->isResourceTypeAvailable($slot, $type, $partySize, $location)) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Check if a specific resource type is available
     */
    private function isResourceTypeAvailable(
        array $slot,
        string $type,
        ?int $partySize,
        ?Location $location
    ): bool {
        $query = Resource::active()
            ->ofType($type)
            ->when($location, function ($q) use ($location) {
                $q->where('location_id', $location->id);
            });
            
        // For restaurant services, check table capacity
        if ($type === Resource::TYPE_TABLE && $partySize) {
            $availableTables = $query->get()->filter(function ($table) use ($slot) {
                return !$this->isTableOccupied($table, $slot);
            });
            
            // Check if we have enough capacity
            $totalCapacity = $availableTables->sum('capacity');
            return $totalCapacity >= $partySize;
        }
        
        // For staff/room services, check if any resource is available
        $availableResources = $query->get()->filter(function ($resource) use ($slot) {
            return !$this->isResourceOccupied($resource, $slot);
        });
        
        return $availableResources->isNotEmpty();
    }

    /**
     * Check if a table is occupied during the slot
     */
    private function isTableOccupied(Resource $table, array $slot): bool
    {
        return Booking::confirmed()
            ->whereHas('resources', function ($query) use ($table) {
                $query->where('resource_id', $table->id);
            })
            ->inTimeRange($slot['start'], $slot['end'])
            ->exists();
    }

    /**
     * Check if a resource is occupied during the slot
     */
    private function isResourceOccupied(Resource $resource, array $slot): bool
    {
        return Booking::confirmed()
            ->whereHas('resources', function ($query) use ($resource) {
                $query->where('resource_id', $resource->id);
            })
            ->inTimeRange($slot['start'], $slot['end'])
            ->exists();
    }
}
