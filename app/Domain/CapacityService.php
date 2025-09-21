<?php

namespace App\Domain;

use App\Models\Service;
use App\Models\AvailabilityRule;
use App\Models\Booking;
use App\Models\Resource;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Capacity Service
 * 
 * Universal availability engine that works for all business verticals
 * Handles table combinations, staff allocation, and room assignments
 */
class CapacityService
{
    /**
     * Get available time slots for a service on a specific date
     * 
     * @param Service $service The service to check availability for
     * @param Carbon $date The date to check availability
     * @param int|null $partySize Party size for restaurants
     * @param int|null $locationId Specific location to check
     * @return Collection Available time slots
     */
    public function getAvailableSlots(
        Service $service, 
        Carbon $date, 
        ?int $partySize = null, 
        ?int $locationId = null
    ): Collection {
        // Get availability rules for the date
        $rules = $this->getAvailabilityRules($service, $date, $locationId);
        
        if ($rules->isEmpty()) {
            return collect();
        }

        // Generate time slots based on service duration and buffers
        $slots = $this->generateTimeSlots($rules, $service);
        
        // Filter out unavailable slots
        $availableSlots = $slots->filter(function ($slot) use ($service, $partySize, $locationId) {
            return $this->isSlotAvailable($slot, $service, $partySize, $locationId);
        });

        return $availableSlots->values();
    }

    /**
     * Check if a specific time slot is available
     * 
     * @param array $slot Time slot with start and end times
     * @param Service $service The service
     * @param int|null $partySize Party size for restaurants
     * @param int|null $locationId Specific location
     * @return bool
     */
    public function isSlotAvailable(
        array $slot, 
        Service $service, 
        ?int $partySize = null, 
        ?int $locationId = null
    ): bool {
        $startTime = Carbon::parse($slot['start']);
        $endTime = Carbon::parse($slot['end']);

        // Check for overlapping bookings
        $overlappingBookings = $this->getOverlappingBookings($startTime, $endTime, $locationId);
        
        if ($overlappingBookings->isEmpty()) {
            return true;
        }

        // Check availability based on service type
        return match (true) {
            $service->isRestaurantService() => $this->checkRestaurantAvailability($overlappingBookings, $partySize, $locationId),
            $service->isStaffService() => $this->checkStaffAvailability($overlappingBookings, $service, $locationId),
            $service->isDentalService() => $this->checkDentalAvailability($overlappingBookings, $service, $locationId),
            default => false
        };
    }

    /**
     * Get availability rules for a service on a specific date
     */
    private function getAvailabilityRules(Service $service, Carbon $date, ?int $locationId): Collection
    {
        $dayOfWeek = $date->dayOfWeek;
        
        return AvailabilityRule::query()
            ->where('tenant_id', $service->tenant_id)
            ->where('day_of_week', $dayOfWeek)
            ->where('active', true)
            ->get()
            ->filter(function ($rule) use ($date) {
                return $rule->appliesToDate($date);
            });
    }

    /**
     * Generate time slots based on availability rules and service duration
     */
    private function generateTimeSlots(Collection $rules, Service $service): Collection
    {
        $slots = collect();
        
        foreach ($rules as $rule) {
            $startTime = Carbon::parse($rule->start_time);
            $endTime = Carbon::parse($rule->end_time);
            $slotDuration = $service->total_duration; // includes buffers
            
            $currentTime = $startTime->copy();
            
            while ($currentTime->addMinutes($slotDuration)->lte($endTime)) {
                $slots->push([
                    'start' => $currentTime->copy()->subMinutes($slotDuration)->toISOString(),
                    'end' => $currentTime->toISOString(),
                ]);
            }
        }
        
        return $slots;
    }

    /**
     * Get overlapping bookings for a time period
     */
    private function getOverlappingBookings(Carbon $startTime, Carbon $endTime, ?int $locationId): Collection
    {
        $query = Booking::query()
            ->where('status', 'confirmed')
            ->where(function ($q) use ($startTime, $endTime) {
                $q->whereBetween('start_at', [$startTime, $endTime])
                  ->orWhereBetween('end_at', [$startTime, $endTime])
                  ->orWhere(function ($q2) use ($startTime, $endTime) {
                      $q2->where('start_at', '<=', $startTime)
                         ->where('end_at', '>=', $endTime);
                  });
            });

        if ($locationId) {
            $query->whereHas('service.resources.location', function ($q) use ($locationId) {
                $q->where('id', $locationId);
            });
        }

        return $query->with(['resources', 'service'])->get();
    }

    /**
     * Check restaurant availability (table combinations)
     */
    private function checkRestaurantAvailability(Collection $bookings, ?int $partySize, ?int $locationId): bool
    {
        if (!$partySize) {
            return false;
        }

        // Get all available tables
        $availableTables = Resource::query()
            ->where('type', Resource::TYPE_TABLE)
            ->where('active', true)
            ->when($locationId, function ($q) use ($locationId) {
                $q->where('location_id', $locationId);
            })
            ->get();

        // Get tables already allocated in overlapping bookings
        $allocatedTableIds = $bookings->flatMap(function ($booking) {
            return $booking->resources->where('type', Resource::TYPE_TABLE)->pluck('id');
        });

        // Get available tables
        $freeTables = $availableTables->whereNotIn('id', $allocatedTableIds);

        // Check if we can accommodate the party size
        return $this->canAccommodatePartySize($freeTables, $partySize);
    }

    /**
     * Check staff availability for barber/beauty services
     */
    private function checkStaffAvailability(Collection $bookings, Service $service, ?int $locationId): bool
    {
        // Get all available staff
        $availableStaff = Resource::query()
            ->where('type', Resource::TYPE_STAFF)
            ->where('active', true)
            ->when($locationId, function ($q) use ($locationId) {
                $q->where('location_id', $locationId);
            })
            ->get();

        // Get staff already allocated in overlapping bookings
        $allocatedStaffIds = $bookings->flatMap(function ($booking) {
            return $booking->resources->where('type', Resource::TYPE_STAFF)->pluck('id');
        });

        // Check if there's at least one free staff member
        return $availableStaff->whereNotIn('id', $allocatedStaffIds)->isNotEmpty();
    }

    /**
     * Check dental availability (staff + room)
     */
    private function checkDentalAvailability(Collection $bookings, Service $service, ?int $locationId): bool
    {
        // Check staff availability
        $staffAvailable = $this->checkStaffAvailability($bookings, $service, $locationId);
        
        if (!$staffAvailable) {
            return false;
        }

        // Check room availability
        $availableRooms = Resource::query()
            ->where('type', Resource::TYPE_ROOM)
            ->where('active', true)
            ->when($locationId, function ($q) use ($locationId) {
                $q->where('location_id', $locationId);
            })
            ->get();

        $allocatedRoomIds = $bookings->flatMap(function ($booking) {
            return $booking->resources->where('type', Resource::TYPE_ROOM)->pluck('id');
        });

        $freeRooms = $availableRooms->whereNotIn('id', $allocatedRoomIds);

        return $freeRooms->isNotEmpty();
    }

    /**
     * Check if available tables can accommodate party size
     */
    private function canAccommodatePartySize(Collection $tables, int $partySize): bool
    {
        $totalCapacity = $tables->sum('capacity');
        
        if ($totalCapacity < $partySize) {
            return false;
        }

        // Try to find optimal table combination
        return $this->findOptimalTableCombination($tables, $partySize) !== null;
    }

    /**
     * Find optimal table combination for party size
     */
    private function findOptimalTableCombination(Collection $tables, int $partySize): ?Collection
    {
        // Sort tables by capacity (ascending)
        $sortedTables = $tables->sortBy('capacity');
        
        // Try to find exact match first
        $exactMatch = $sortedTables->firstWhere('capacity', $partySize);
        if ($exactMatch) {
            return collect([$exactMatch]);
        }

        // Try combinations of 2 tables
        foreach ($sortedTables as $table1) {
            foreach ($sortedTables->where('id', '!=', $table1->id) as $table2) {
                if ($table1->capacity + $table2->capacity >= $partySize) {
                    return collect([$table1, $table2]);
                }
            }
        }

        // Try combinations of 3 tables
        foreach ($sortedTables as $table1) {
            foreach ($sortedTables->where('id', '!=', $table1->id) as $table2) {
                foreach ($sortedTables->whereNotIn('id', [$table1->id, $table2->id]) as $table3) {
                    if ($table1->capacity + $table2->capacity + $table3->capacity >= $partySize) {
                        return collect([$table1, $table2, $table3]);
                    }
                }
            }
        }

        return null;
    }
}
