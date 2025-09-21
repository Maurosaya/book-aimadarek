<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AvailabilityRule extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'location_id',
        'day_of_week',
        'start_time',
        'end_time',
        'exceptions',
        'max_covers_slot',
        'active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'exceptions' => 'array',
        'active' => 'boolean',
    ];

    /**
     * Get the location for this rule
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Scope to get only active rules
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope to get rules for a specific day of week
     */
    public function scopeForDay($query, int $dayOfWeek)
    {
        return $query->where('day_of_week', $dayOfWeek);
    }

    /**
     * Scope to get rules for a specific location
     */
    public function scopeForLocation($query, ?int $locationId)
    {
        return $query->where(function ($q) use ($locationId) {
            $q->where('location_id', $locationId)
              ->orWhereNull('location_id');
        });
    }

    /**
     * Check if this rule applies to a specific date
     */
    public function appliesToDate(\DateTime $date): bool
    {
        // Check if the day of week matches
        if ($this->day_of_week !== (int) $date->format('w')) {
            return false;
        }

        // Check if the date is in exceptions
        $dateString = $date->format('Y-m-d');
        if (in_array($dateString, $this->exceptions ?? [])) {
            return false;
        }

        return true;
    }

    /**
     * Get the day name for this rule
     */
    public function getDayNameAttribute(): string
    {
        return match($this->day_of_week) {
            0 => __('panel.availability.days.0'),
            1 => __('panel.availability.days.1'),
            2 => __('panel.availability.days.2'),
            3 => __('panel.availability.days.3'),
            4 => __('panel.availability.days.4'),
            5 => __('panel.availability.days.5'),
            6 => __('panel.availability.days.6'),
            default => 'Unknown',
        };
    }
}
