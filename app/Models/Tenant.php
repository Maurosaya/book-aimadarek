<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Database\Models\Domain;

class Tenant extends BaseTenant
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'brand_name',
        'default_locale',
        'supported_locales',
        'timezone',
        'settings',
        'webhook_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'supported_locales' => 'array',
        'settings' => 'array',
    ];

    /**
     * Get the default locale for this tenant
     */
    public function getDefaultLocaleAttribute(): string
    {
        return $this->attributes['default_locale'] ?? 'en';
    }

    /**
     * Get the supported locales for this tenant
     */
    public function getSupportedLocalesAttribute(): array
    {
        $locales = $this->attributes['supported_locales'] ?? '["en"]';
        
        // Always decode from JSON string
        $decoded = json_decode($locales, true);
        
        return is_array($decoded) ? $decoded : ['en'];
    }

    /**
     * Check if a locale is supported by this tenant
     */
    public function supportsLocale(string $locale): bool
    {
        return in_array($locale, $this->supported_locales);
    }

    /**
     * Get the timezone for this tenant
     */
    public function getTimezoneAttribute(): string
    {
        return $this->attributes['timezone'] ?? 'UTC';
    }

    /**
     * Get webhook secret with fallback to global config
     */
    public function getWebhookSecret(): string
    {
        return $this->webhook_secret ?? config('app.webhook_fallback_secret', 'default-webhook-secret');
    }

    /**
     * Generate a new webhook secret
     */
    public function generateWebhookSecret(): string
    {
        $secret = 'whsec_' . bin2hex(random_bytes(32));
        $this->update(['webhook_secret' => $secret]);
        return $secret;
    }

    /**
     * Get the domains for this tenant
     */
    public function domains()
    {
        return $this->hasMany(Domain::class);
    }
}
