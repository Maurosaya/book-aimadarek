<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WebhookEndpoint extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'url',
        'events',
        'secret',
        'active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'events' => 'array',
        'active' => 'boolean',
    ];

    /**
     * Get the webhook logs for this endpoint
     */
    public function logs(): HasMany
    {
        return $this->hasMany(WebhookLog::class, 'endpoint_id');
    }

    /**
     * Scope to get only active endpoints
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Check if this endpoint listens for a specific event
     */
    public function listensFor(string $event): bool
    {
        return in_array($event, $this->events ?? []);
    }

    /**
     * Get the events this endpoint listens for as a comma-separated string
     */
    public function getEventsListAttribute(): string
    {
        return implode(', ', $this->events ?? []);
    }
}
