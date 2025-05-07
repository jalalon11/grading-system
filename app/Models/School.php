<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class School extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'address',
        'principal',
        'logo_path',
        'grade_levels',
        'school_division_id',
        'is_active',
        'trial_ends_at',
        'subscription_status',
        'subscription_ends_at',
        'billing_cycle',
        'monthly_price',
        'yearly_price',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'grade_levels' => 'array',
        'is_active' => 'boolean',
        'trial_ends_at' => 'datetime',
        'subscription_ends_at' => 'datetime',
        'monthly_price' => 'decimal:2',
        'yearly_price' => 'decimal:2',
    ];

    /**
     * Get the full URL for the school logo
     */
    public function getLogoUrlAttribute()
    {
        if (!$this->logo_path) {
            return null;
        }

        try {
            // Use our proxy route to serve the image
            return route('image.proxy', ['path' => $this->logo_path]);
        } catch (\Exception $e) {
            Log::error('Error getting logo URL: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get the school division that this school belongs to
     */
    public function schoolDivision(): BelongsTo
    {
        return $this->belongsTo(SchoolDivision::class);
    }

    /**
     * Get the sections in this school
     */
    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }

    /**
     * Get the teachers in this school
     */
    public function teachers(): HasMany
    {
        return $this->hasMany(User::class)->where('role', 'teacher');
    }

    /**
     * Get all users in this school
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the payments for this school
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the support tickets for this school
     */
    public function supportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }

    /**
     * Check if the school is on trial
     */
    public function onTrial(): bool
    {
        // If trial_ends_at is null, it means unlimited trial
        if ($this->subscription_status === 'trial' && $this->trial_ends_at === null) {
            return true;
        }

        return $this->subscription_status === 'trial' &&
               $this->trial_ends_at &&
               now()->lt($this->trial_ends_at);
    }

    /**
     * Check if the school's trial has expired
     */
    public function trialExpired(): bool
    {
        // If trial_ends_at is null, trial never expires
        if ($this->trial_ends_at === null) {
            return false;
        }

        return $this->subscription_status === 'trial' &&
               $this->trial_ends_at &&
               now()->gt($this->trial_ends_at);
    }

    /**
     * Check if the school has an active subscription
     */
    public function hasActiveSubscription(): bool
    {
        // If subscription_ends_at is null, it means unlimited subscription
        if ($this->subscription_status === 'active' && $this->subscription_ends_at === null) {
            return true;
        }

        return $this->subscription_status === 'active' &&
               $this->subscription_ends_at &&
               now()->lt($this->subscription_ends_at);
    }

    /**
     * Check if the school's subscription has expired
     */
    public function subscriptionExpired(): bool
    {
        // If subscription_status is explicitly set to 'expired'
        if ($this->subscription_status === 'expired') {
            return true;
        }

        // If subscription_ends_at is null, subscription never expires
        if ($this->subscription_status === 'active' && $this->subscription_ends_at === null) {
            return false;
        }

        return $this->subscription_status === 'active' &&
               $this->subscription_ends_at &&
               now()->gt($this->subscription_ends_at);
    }

    /**
     * Get the current price based on billing cycle
     */
    public function getCurrentPriceAttribute(): float
    {
        return $this->billing_cycle === 'yearly' ? $this->yearly_price : $this->monthly_price;
    }

    /**
     * Get the remaining days in trial
     */
    public function getRemainingTrialDaysAttribute()
    {
        // If trial_ends_at is null, it means unlimited trial
        if ($this->trial_ends_at === null && $this->subscription_status === 'trial') {
            return 'Unlimited';
        }

        if (!$this->trial_ends_at || $this->subscription_status !== 'trial') {
            return 0;
        }

        $now = now();
        if ($now->gt($this->trial_ends_at)) {
            return 0;
        }

        // Get the difference in minutes for more precise calculations
        $diffInMinutes = $now->diffInMinutes($this->trial_ends_at, false);
        $hoursRemaining = floor($diffInMinutes / 60);
        $minutesRemaining = $diffInMinutes % 60;
        $daysRemaining = floor($hoursRemaining / 24);
        $hoursRemaining = $hoursRemaining % 24; // Hours remaining after removing days

        // Format based on the remaining time
        if ($daysRemaining > 0) {
            // If more than a day remains, show days
            return $daysRemaining . ' ' . ($daysRemaining == 1 ? 'day' : 'days');
        } elseif ($hoursRemaining > 0) {
            // If less than a day but more than an hour remains, show hours
            if ($minutesRemaining > 0) {
                // Include minutes if there are any
                return $hoursRemaining . ' ' . ($hoursRemaining == 1 ? 'hour' : 'hours') .
                       ' and ' . $minutesRemaining . ' ' . ($minutesRemaining == 1 ? 'minute' : 'minutes');
            } else {
                return $hoursRemaining . ' ' . ($hoursRemaining == 1 ? 'hour' : 'hours');
            }
        } else {
            // If less than an hour remains, show minutes
            // Make sure we have at least 1 minute to display and no decimals
            $minutesToShow = max(1, (int)$diffInMinutes);
            return $minutesToShow . ' ' . ($minutesToShow == 1 ? 'minute' : 'minutes');
        }
    }

    /**
     * Get the remaining time for the subscription
     */
    public function getRemainingSubscriptionTimeAttribute()
    {
        // If subscription_ends_at is null, it means unlimited subscription
        if ($this->subscription_ends_at === null && $this->subscription_status === 'active') {
            return 'Unlimited';
        }

        if (!$this->subscription_ends_at || $this->subscription_status !== 'active') {
            return 0;
        }

        $now = now();
        if ($now->gt($this->subscription_ends_at)) {
            return 0;
        }

        // Get the difference in minutes for more precise calculations
        $diffInMinutes = $now->diffInMinutes($this->subscription_ends_at, false);
        $hoursRemaining = floor($diffInMinutes / 60);
        $minutesRemaining = $diffInMinutes % 60;
        $daysRemaining = floor($hoursRemaining / 24);
        $hoursRemaining = $hoursRemaining % 24; // Hours remaining after removing days

        // Format based on the remaining time
        if ($daysRemaining > 0) {
            // If more than a day remains, show days
            return $daysRemaining . ' ' . ($daysRemaining == 1 ? 'day' : 'days');
        } elseif ($hoursRemaining > 0) {
            // If less than a day but more than an hour remains, show hours
            if ($minutesRemaining > 0) {
                // Include minutes if there are any
                return $hoursRemaining . ' ' . ($hoursRemaining == 1 ? 'hour' : 'hours') .
                       ' and ' . $minutesRemaining . ' ' . ($minutesRemaining == 1 ? 'minute' : 'minutes');
            } else {
                return $hoursRemaining . ' ' . ($hoursRemaining == 1 ? 'hour' : 'hours');
            }
        } else {
            // If less than an hour remains, show minutes
            // Make sure we have at least 1 minute to display and no decimals
            $minutesToShow = max(1, (int)$diffInMinutes);
            return $minutesToShow . ' ' . ($minutesToShow == 1 ? 'minute' : 'minutes');
        }
    }
}
