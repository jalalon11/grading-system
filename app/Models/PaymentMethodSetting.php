<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethodSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'method',
        'enabled',
        'message',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'enabled' => 'boolean',
    ];

    /**
     * Get all payment method settings
     * 
     * @return array
     */
    public static function getSettings()
    {
        // Define standard payment methods
        $standardMethods = ['bank_transfer', 'gcash', 'paymaya', 'other'];
        
        // Get all settings from the database
        $settings = self::all()->keyBy('method')->toArray();
        
        // Prepare the result array with default values for all standard methods
        $result = [];
        foreach ($standardMethods as $method) {
            $result[$method] = [
                'enabled' => isset($settings[$method]) ? (bool)$settings[$method]['enabled'] : true,
                'message' => isset($settings[$method]) ? $settings[$method]['message'] : '',
            ];
        }
        
        return $result;
    }

    /**
     * Check if a payment method is enabled
     * 
     * @param string $method
     * @return bool
     */
    public static function isEnabled(string $method)
    {
        $setting = self::where('method', $method)->first();
        
        // If no setting exists, the method is considered enabled by default
        if (!$setting) {
            return true;
        }
        
        return (bool)$setting->enabled;
    }

    /**
     * Get the disabled message for a payment method
     * 
     * @param string $method
     * @return string|null
     */
    public static function getDisabledMessage(string $method)
    {
        $setting = self::where('method', $method)->first();
        
        if (!$setting || $setting->enabled) {
            return null;
        }
        
        return $setting->message;
    }
}
