<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'phone',
        'gdpr_optin',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'gdpr_optin' => 'boolean',
    ];

    /**
     * Get the bookings for this customer
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Scope to get customers with GDPR opt-in
     */
    public function scopeGdprOptedIn($query)
    {
        return $query->where('gdpr_optin', true);
    }

    /**
     * Get the customer's full contact information
     */
    public function getContactInfoAttribute(): string
    {
        $info = [$this->name];
        
        if ($this->email) {
            $info[] = $this->email;
        }
        
        if ($this->phone) {
            $info[] = $this->phone;
        }
        
        return implode(' - ', $info);
    }
}
