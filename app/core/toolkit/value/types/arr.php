<?php
/**
 * 数组类型的值
 *
 * @copyright Copyright (c) 2009-2012 Jingcheng Zhang <diogin@gmail.com>. All rights reserved.
 * @license   See "LICENSE" file bundled with this distribution.
 */

namespace swap;

/**
 * [类型] 数组值
 */
class arr_value extends value {

    public function is_valid() {
        return is_array($this->value);
    }
    
    public static function pad_zero_range($from, $to) {
        if ($from === $to) {
            return array((string)$from);
        } else {
            $from_len = strlen($from);
            $to_len = strlen($to);
            $range = array();
            if ($from < $to) {
                for ($i = $from; $i <= $to; $i++) {
                    $range[] = str_pad((string)$i, $to_len, '0', STR_PAD_LEFT);
                }
            } else {
                for ($i = $from; $i >= $to; $i--) {
                    $range[] = str_pad((string)$i, $from_len, '0', STR_PAD_LEFT);
                }
            }
            return $range;
        }
    }
}
