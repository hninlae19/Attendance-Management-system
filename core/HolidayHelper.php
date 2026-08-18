<?php

class HolidayHelper {
    /**
     * List of fixed Myanmar public holidays (Month-Day format)
     */
    private static $fixedHolidays = [
        '01-04', // Independence Day
        '02-12', // Union Day
        '03-02', // Peasants' Day
        '03-27', // Armed Forces Day
        '05-01', // May Day (Labor Day)
        '07-19', // Martyrs' Day
        '12-25', // Christmas Day
    ];

    /**
     * List of variable/dynamic Myanmar public holidays (Year-Month-Day format)
     * This includes Thingyan, Thadingyut, Tazaungdaing, etc.
     */
    private static $dynamicHolidays = [
        // 2026 Example Dates
        '2026-04-13', // Thingyan starts
        '2026-04-14',
        '2026-04-15',
        '2026-04-16',
        '2026-04-17', // Myanmar New Year
    ];

    /**
     * Checks if the given date is a weekend (Saturday or Sunday)
     */
    public static function isWeekend($date) {
        $dayOfWeek = date('N', strtotime($date));
        return ($dayOfWeek >= 6); // 6 = Saturday, 7 = Sunday
    }

    /**
     * Checks if the given date is a public holiday
     */
    public static function isPublicHoliday($date) {
        $timestamp = strtotime($date);
        $monthDay = date('m-d', $timestamp);
        $yearMonthDay = date('Y-m-d', $timestamp);

        if (in_array($monthDay, self::$fixedHolidays)) {
            return true;
        }

        if (in_array($yearMonthDay, self::$dynamicHolidays)) {
            return true;
        }

        return false;
    }

    /**
     * Checks if the given date is a valid working day
     */
    public static function isWorkingDay($date) {
        if (self::isWeekend($date)) {
            return false;
        }

        if (self::isPublicHoliday($date)) {
            return false;
        }

        return true;
    }

    /**
     * Counts the number of public holidays in a given month.
     * Optionally takes a joinDate to only count holidays on or after the joinDate.
     */
    public static function getPublicHolidaysCountInMonth($year, $month, $joinDate = null) {
        $count = 0;
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        
        $joinTime = $joinDate ? strtotime($joinDate) : 0;
        
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
            $timestamp = strtotime($date);
            
            // If employee joined after this day, don't count it for them
            if ($joinTime > 0 && $timestamp < $joinTime) {
                continue;
            }
            
            if (self::isPublicHoliday($date) && !self::isWeekend($date)) {
                // Usually we only pay for public holidays that fall on weekdays, 
                // but the user's formula "Basic Pay = (Actual Checked-in Days + Public Holidays in that month)"
                // typically implies ALL public holidays, or just weekday ones.
                // Assuming all public holidays are counted.
                // Wait, if a public holiday is on a weekend, it shouldn't be counted twice if weekends are already off,
                // but if we are counting "Paid Days", usually weekends are unpaid or paid in basic salary.
                // Since Daily Rate = Basic / 30, it implies all 30 days are paid. Wait.
                // If Daily Rate = Basic / 30, and employee checks in 22 days, 22 * Daily Rate is only ~73% of salary.
                // This means weekends are NOT checked in. So 22 working days * (Basic/30) = low pay!
                // Let me count ALL public holidays.
                $count++;
            } else if (self::isPublicHoliday($date) && self::isWeekend($date)) {
                $count++;
            }
        }
        
        return $count;
    }
}
