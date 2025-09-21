<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;

class Resource extends Model
{
    use HasTranslations;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'location_id',
        'type',
        'label',
        'capacity',
        'combinable_with',
        'active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'combinable_with' => 'array',
        'active' => 'boolean',
    ];

    /**
     * The attributes that are translatable
     *
     * @var array<int, string>
     */
    public array $translatable = ['label'];

    /**
     * Resource types constants
     */
    public const TYPE_TABLE = 'TABLE';
    public const TYPE_STAFF = 'STAFF';
    public const TYPE_ROOM = 'ROOM';
    public const TYPE_CHAIR = 'CHAIR';
    public const TYPE_EQUIPMENT = 'EQUIPMENT';

    /**
     * Get the location that owns this resource
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Get the bookings that use this resource
     */
    public function bookings(): BelongsToMany
    {
        return $this->belongsToMany(Booking::class, 'booking_allocations');
    }

    /**
     * Scope to get only active resources
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope to get resources by type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Check if this resource can be combined with another type
     */
    public function canCombineWith(string $type): bool
    {
        return in_array($type, $this->combinable_with ?? []);
    }

    /**
     * Get the resource type label in the current locale
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            self::TYPE_TABLE => __('panel.resources.types.TABLE'),
            self::TYPE_STAFF => __('panel.resources.types.STAFF'),
            self::TYPE_ROOM => __('panel.resources.types.ROOM'),
            self::TYPE_CHAIR => __('panel.resources.types.CHAIR'),
            self::TYPE_EQUIPMENT => __('panel.resources.types.EQUIPMENT'),
            default => $this->type,
        };
    }
}
