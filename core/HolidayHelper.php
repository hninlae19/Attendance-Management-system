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
}
