<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

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
        return $this->attributes['supported_locales'] ?? ['en'];
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
}
