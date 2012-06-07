<?php
/**
 * 日期类型的值
 *
 * @copyright Copyright (c) 2009-2012 Jingcheng Zhang <diogin@gmail.com>. All rights reserved.
 * @license   See "LICENSE" file bundled with this distribution.
 */

namespace swap;

// [类型] 日期值
class date_value extends value {
    public function is_valid() {
        return self::is_valid_date($this->value);
    }
    
    public static function is_valid_date($date, $separator = '-') {
        return false;
    }
    public static function is_valid_year($year) {
        return preg_match('/^[12][0-9]{3}$/', $year);
    }
    public static function is_valid_month($month) {
        return in_array($month, self::get_months());
    }
    public static function is_valid_day($day, $month, $year) {
        return in_array($day, self::get_month_days($month, $year));
    }
    
    public static function is_leap_year($year) {
        if ($year % 100 === 0) {
            if ($year % 400 === 0) {
                return true;
            }
        } else if ($year % 4 === 0) {
            return true;
        }
        return false;
    }
    public static function get_years($from_year, $to_year) {
        return range($from_year, $to_year);
    }
    public static function get_months() {
        return array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
    }
    public static function get_month_days($month, $year) {
        if ($month === '02') {
            return self::get_second_month_days($year);
        } else if (self::is_big_month($month)) {
            return self::get_big_month_days();
        } else {
            return self::get_small_month_days();
        }
    }
    public static function is_big_month($month) {
        return in_array($month, array('01', '03', '05', '07', '08', '10', '12'));
    }
    public static function is_small_month($month) {
        return in_array($month, array('04', '06', '09', '11'));
    }
    public static function get_small_month_days() {
        return array(
            '01', '02', '03', '04', '05', '06', '07', '08', '09', '10',
            '11', '12', '13', '14', '15', '16', '17', '18', '19', '20',
            '21', '22', '23', '24', '25', '26', '27', '28', '29', '30',
        );
    }
    public static function get_big_month_days() {
        return array(
            '01', '02', '03', '04', '05', '06', '07', '08', '09', '10',
            '11', '12', '13', '14', '15', '16', '17', '18', '19', '20',
            '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31',
        );
    }
    public static function get_second_month_days($year) {
        if (self::is_leap_year($year)) {
            return array(
                '01', '02', '03', '04', '05', '06', '07', '08', '09', '10',
                '11', '12', '13', '14', '15', '16', '17', '18', '19', '20',
                '21', '22', '23', '24', '25', '26', '27', '28', '29',
            );
        } else {
            return array(
                '01', '02', '03', '04', '05', '06', '07', '08', '09', '10',
                '11', '12', '13', '14', '15', '16', '17', '18', '19', '20',
                '21', '22', '23', '24', '25', '26', '27', '28',
            );
        }
    }
}
