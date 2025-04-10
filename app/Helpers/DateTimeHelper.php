<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateTimeHelper
{
    /**
     * Convert a datetime to Philippine Time (Asia/Manila)
     *
     * @param mixed $dateTime The datetime to convert
     * @param string $format The format to return (null for Carbon instance)
     * @return mixed Carbon instance or formatted string
     */
    public static function toPHTime($dateTime, $format = null)
    {
        if (!$dateTime) {
            return null;
        }
        
        $carbon = $dateTime instanceof Carbon 
            ? $dateTime->copy()->setTimezone('Asia/Manila')
            : Carbon::parse($dateTime)->setTimezone('Asia/Manila');
            
        return $format ? $carbon->format($format) : $carbon;
    }
    
    /**
     * Get current time in Philippine Time
     *
     * @param string $format The format to return (null for Carbon instance)
     * @return mixed Carbon instance or formatted string
     */
    public static function nowPHTime($format = null)
    {
        $now = Carbon::now()->setTimezone('Asia/Manila');
        return $format ? $now->format($format) : $now;
    }
    
    /**
     * Format a date for display
     *
     * @param mixed $dateTime The datetime to format
     * @param string $format The format to use
     * @return string Formatted date
     */
    public static function formatDate($dateTime, $format = 'F d, Y')
    {
        return self::toPHTime($dateTime, $format);
    }
    
    /**
     * Format a datetime for display
     *
     * @param mixed $dateTime The datetime to format
     * @param string $format The format to use
     * @return string Formatted datetime
     */
    public static function formatDateTime($dateTime, $format = 'F d, Y h:i A')
    {
        return self::toPHTime($dateTime, $format);
    }
}
