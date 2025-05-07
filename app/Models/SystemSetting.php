<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'value',
        'description',
    ];

    /**
     * Get a setting by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getSetting(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting by key
     *
     * @param string $key
     * @param mixed $value
     * @param string|null $description
     * @return SystemSetting
     */
    public static function setSetting(string $key, $value, ?string $description = null)
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'description' => $description ?? null,
            ]
        );

        return $setting;
    }

    /**
     * Check if maintenance mode is enabled
     *
     * @return bool
     */
    public static function isMaintenanceMode(): bool
    {
        // Force cast to boolean to ensure proper type comparison
        $maintenanceMode = self::getSetting('maintenance_mode', false);
        return $maintenanceMode === '1' || $maintenanceMode === 1 || $maintenanceMode === true;
    }

    /**
     * Get maintenance message
     *
     * @return string
     */
    public static function getMaintenanceMessage(): string
    {
        return self::getSetting('maintenance_message', 'The system is currently under maintenance. Please check back later.');
    }

    /**
     * Get maintenance end time
     *
     * @return string|null
     */
    public static function getMaintenanceEndTime(): ?string
    {
        return self::getSetting('maintenance_end_time', null);
    }

    /**
     * Get maintenance duration in minutes
     *
     * @return int|null
     */
    public static function getMaintenanceDuration(): ?int
    {
        $duration = self::getSetting('maintenance_duration', null);
        return $duration !== null ? (int)$duration : null;
    }

    /**
     * Check if maintenance is past end time
     *
     * @return bool
     */
    public static function isMaintenancePastEndTime(): bool
    {
        $endTime = self::getMaintenanceEndTime();
        if (!$endTime) {
            return false;
        }

        try {
            $endDateTime = new \DateTime($endTime);
            $now = new \DateTime();
            return $now > $endDateTime;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get remaining maintenance time in minutes
     *
     * @return int|null
     */
    public static function getMaintenanceRemainingMinutes(): ?int
    {
        $endTime = self::getMaintenanceEndTime();
        if (!$endTime) {
            return null;
        }

        try {
            $endDateTime = new \DateTime($endTime);
            $now = new \DateTime();

            if ($now > $endDateTime) {
                return 0;
            }

            $diff = $now->diff($endDateTime);
            return ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;
        } catch (\Exception $e) {
            return null;
        }
    }
}
