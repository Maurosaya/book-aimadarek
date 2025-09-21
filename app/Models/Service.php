<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Service extends Model
{
    use HasTranslations;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'name',
        'duration_min',
        'buffer_before_min',
        'buffer_after_min',
        'price_cents',
        'required_resource_types',
        'active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'required_resource_types' => 'array',
        'active' => 'boolean',
    ];

    /**
     * The attributes that are translatable
     *
     * @var array<int, string>
     */
    public array $translatable = ['name'];

    /**
     * Get the bookings for this service
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Scope to get only active services
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Get the total duration including buffers
     */
    public function getTotalDurationAttribute(): int
    {
        return $this->duration_min + $this->buffer_before_min + $this->buffer_after_min;
    }

    /**
     * Get the price in a formatted way
     */
    public function getFormattedPriceAttribute(): ?string
    {
        if (!$this->price_cents) {
            return null;
        }

        return number_format($this->price_cents / 100, 2);
    }

    /**
     * Check if this service requires a specific resource type
     */
    public function requiresResourceType(string $type): bool
    {
        return in_array($type, $this->required_resource_types ?? []);
    }

    /**
     * Check if this service is for restaurants (requires TABLE)
     */
    public function isRestaurantService(): bool
    {
        return $this->requiresResourceType(Resource::TYPE_TABLE);
    }

    /**
     * Check if this service is for barber/beauty (requires STAFF)
     */
    public function isStaffService(): bool
    {
        return $this->requiresResourceType(Resource::TYPE_STAFF);
    }

    /**
     * Check if this service is for dental (requires STAFF + ROOM)
     */
    public function isDentalService(): bool
    {
        return $this->requiresResourceType(Resource::TYPE_STAFF) && 
               $this->requiresResourceType(Resource::TYPE_ROOM);
    }
}
