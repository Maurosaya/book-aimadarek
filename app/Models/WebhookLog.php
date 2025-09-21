<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebhookLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'endpoint_id',
        'event',
        'payload',
        'response_code',
        'response_body',
        'signature',
        'delivered_at',
        'retries',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'payload' => 'array',
        'delivered_at' => 'datetime',
    ];

    /**
     * Get the webhook endpoint for this log
     */
    public function endpoint(): BelongsTo
    {
        return $this->belongsTo(WebhookEndpoint::class, 'endpoint_id');
    }

    /**
     * Scope to get only successful deliveries
     */
    public function scopeSuccessful($query)
    {
        return $query->where('response_code', '>=', 200)
                    ->where('response_code', '<', 300);
    }

    /**
     * Scope to get only failed deliveries
     */
    public function scopeFailed($query)
    {
        return $query->where(function ($q) {
            $q->where('response_code', '<', 200)
              ->orWhere('response_code', '>=', 300)
              ->orWhereNull('response_code');
        });
    }

    /**
     * Check if this log represents a successful delivery
     */
    public function isSuccessful(): bool
    {
        return $this->response_code >= 200 && $this->response_code < 300;
    }

    /**
     * Check if this log represents a failed delivery
     */
    public function isFailed(): bool
    {
        return !$this->isSuccessful();
    }

    /**
     * Get the status label for this log
     */
    public function getStatusLabelAttribute(): string
    {
        if ($this->isSuccessful()) {
            return 'Success';
        }

        if ($this->retries > 0) {
            return 'Retrying';
        }

        return 'Failed';
    }
}
