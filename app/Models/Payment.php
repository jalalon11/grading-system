<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'school_id',
        'user_id',
        'amount',
        'payment_date',
        'payment_method',
        'status',
        'billing_cycle',
        'subscription_start_date',
        'subscription_end_date',
        'reference_number',
        'notes',
        'admin_notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
        'subscription_start_date' => 'datetime',
        'subscription_end_date' => 'datetime',
    ];

    /**
     * Get the school this payment belongs to
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the user (teacher admin) who made this payment
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calculate the remaining time for the subscription
     */
    public function getRemainingTimeAttribute()
    {
        if (!$this->subscription_end_date) {
            return null;
        }

        $now = now();
        if ($now->gt($this->subscription_end_date)) {
            return 'Expired';
        }

        $diff = $now->diff($this->subscription_end_date);

        if ($diff->days > 0) {
            return $diff->days . ' ' . ($diff->days == 1 ? 'day' : 'days');
        }

        if ($diff->h > 0) {
            return $diff->h . ' ' . ($diff->h == 1 ? 'hour' : 'hours');
        }

        // Make sure we have at least 1 minute to display
        $minutesToShow = max(1, $diff->i);
        return $minutesToShow . ' ' . ($minutesToShow == 1 ? 'minute' : 'minutes');
    }
}
