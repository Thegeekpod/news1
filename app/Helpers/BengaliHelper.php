<?php

namespace App\Helpers;

use Carbon\Carbon;

class BengaliHelper
{
    private static $numerals = [
        '0' => '০',
        '1' => '১',
        '2' => '২',
        '3' => '৩',
        '4' => '৪',
        '5' => '৫',
        '6' => '৬',
        '7' => '৭',
        '8' => '৮',
        '9' => '৯'
    ];

    private static $days = [
        'Sunday' => 'রবিবার',
        'Monday' => 'সোমবার',
        'Tuesday' => 'মঙ্গলবার',
        'Wednesday' => 'বুধবার',
        'Thursday' => 'বৃহস্পতিবার',
        'Friday' => 'শুক্রবার',
        'Saturday' => 'শনিবার'
    ];

    private static $months = [
        'January' => 'জানুয়ারি',
        'February' => 'ফেব্রুয়ারি',
        'March' => 'মার্চ',
        'April' => 'এপ্রিল',
        'May' => 'মে',
        'June' => 'জুন',
        'July' => 'জুলাই',
        'August' => 'আগস্ট',
        'September' => 'সেপ্টেম্বর',
        'October' => 'অক্টোবর',
        'November' => 'নভেম্বর',
        'December' => 'ডিসেম্বর'
    ];

    /**
     * Convert western numerals to Bengali numerals.
     */
    public static function toBengaliNumerals($number)
    {
        $str = (string) $number;
        $result = '';
        $length = strlen($str);
        
        for ($i = 0; $i < $length; $i++) {
            $char = $str[$i];
            $result .= self::$numerals[$char] ?? $char;
        }
        
        return $result;
    }

    /**
     * Convert standard timestamp / carbon date to Bengali Date.
     * E.g., "Monday, 2 June 2026" -> "সোমবার, ২ জুন ২০২৬"
     */
    public static function toBengaliDate($date)
    {
        if (!$date) return '';
        
        $carbon = $date instanceof Carbon ? $date : Carbon::parse($date);
        
        $dayNameEn = $carbon->format('l');
        $monthNameEn = $carbon->format('F');
        
        $dayNameBn = self::$days[$dayNameEn] ?? $dayNameEn;
        $monthNameBn = self::$months[$monthNameEn] ?? $monthNameEn;
        
        $dayNumBn = self::toBengaliNumerals($carbon->format('j'));
        $yearBn = self::toBengaliNumerals($carbon->format('Y'));
        
        return "{$dayNameBn}, {$dayNumBn} {$monthNameBn} {$yearBn}";
    }

    /**
     * Convert standard timestamp / carbon time to Bengali format.
     * E.g., "10:30" morning -> "সকাল ১০:৩০"
     */
    public static function toBengaliTime($time)
    {
        if (!$time) return '';
        
        $carbon = $time instanceof Carbon ? $time : Carbon::parse($time);
        
        $hour = (int) $carbon->format('H');
        $timeStr = $carbon->format('g:i'); // 12-hour format without leading zero
        
        $period = '';
        if ($hour >= 4 && $hour < 6) {
            $period = 'ভোর';
        } elseif ($hour >= 6 && $hour < 12) {
            $period = 'সকাল';
        } elseif ($hour >= 12 && $hour < 15) {
            $period = 'দুপুর';
        } elseif ($hour >= 15 && $hour < 18) {
            $period = 'বিকাল';
        } elseif ($hour >= 18 && $hour < 20) {
            $period = 'সন্ধ্যা';
        } else {
            $period = 'রাত';
        }
        
        $timeBn = self::toBengaliNumerals($timeStr);
        
        return "{$period} {$timeBn}";
    }

    /**
     * Estimate reading time for Bengali content.
     */
    public static function estimateReadTime($content)
    {
        if (!$content) return self::toBengaliNumerals(1) . ' মিনিট পড়ুন';
        
        // Strip tags
        $cleanContent = strip_tags($content);
        
        // Match words by splitting on spaces/punctuation
        // For Bengali, words can be split by whitespace or common delimiters
        $words = preg_split('/\s+/u', $cleanContent);
        $wordCount = count($words);
        
        // Bengali reading speed is around 180 words per minute
        $wpm = 180;
        $minutes = max(1, (int) ceil($wordCount / $wpm));
        
        return self::toBengaliNumerals($minutes) . ' মিনিট পড়ুন';
    }
}
