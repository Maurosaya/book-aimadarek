<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class MessageTemplate extends Model
{
    use HasTranslations;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'channel',
        'slug',
        'subject',
        'content',
        'active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * The attributes that are translatable
     *
     * @var array<int, string>
     */
    public array $translatable = ['subject', 'content'];

    /**
     * Channel constants
     */
    public const CHANNEL_EMAIL = 'EMAIL';
    public const CHANNEL_SMS = 'SMS';
    public const CHANNEL_WHATSAPP = 'WHATSAPP';

    /**
     * Common template slugs
     */
    public const SLUG_BOOKING_CONFIRMED = 'booking_confirmed';
    public const SLUG_BOOKING_CANCELLED = 'booking_cancelled';
    public const SLUG_BOOKING_REMINDER = 'booking_reminder';
    public const SLUG_BOOKING_NO_SHOW = 'booking_no_show';

    /**
     * Scope to get only active templates
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope to get templates for a specific channel
     */
    public function scopeForChannel($query, string $channel)
    {
        return $query->where('channel', $channel);
    }

    /**
     * Scope to get templates by slug
     */
    public function scopeBySlug($query, string $slug)
    {
        return $query->where('slug', $slug);
    }

    /**
     * Get the channel label
     */
    public function getChannelLabelAttribute(): string
    {
        return match($this->channel) {
            self::CHANNEL_EMAIL => 'Email',
            self::CHANNEL_SMS => 'SMS',
            self::CHANNEL_WHATSAPP => 'WhatsApp',
            default => $this->channel,
        };
    }

    /**
     * Get the subject in the current locale with fallback
     */
    public function getSubjectForLocale(string $locale): string
    {
        $subject = $this->getTranslation('subject', $locale);
        
        if (!$subject) {
            $subject = $this->getTranslation('subject', $this->getFallbackLocale());
        }
        
        if (!$subject) {
            $subject = $this->getTranslation('subject', 'en');
        }
        
        return $subject ?: 'No subject';
    }

    /**
     * Get the content in the current locale with fallback
     */
    public function getContentForLocale(string $locale): string
    {
        $content = $this->getTranslation('content', $locale);
        
        if (!$content) {
            $content = $this->getTranslation('content', $this->getFallbackLocale());
        }
        
        if (!$content) {
            $content = $this->getTranslation('content', 'en');
        }
        
        return $content ?: 'No content';
    }

    /**
     * Get the fallback locale for this tenant
     */
    private function getFallbackLocale(): string
    {
        if (tenancy()->initialized) {
            $tenant = tenancy()->tenant;
            if ($tenant && isset($tenant->default_locale)) {
                return $tenant->default_locale;
            }
        }
        
        return config('app.fallback_locale', 'en');
    }
}
