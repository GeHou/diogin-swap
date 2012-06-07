<?php
/**
 * 时间类型的值
 *
 * @copyright Copyright (c) 2009-2012 Jingcheng Zhang <diogin@gmail.com>. All rights reserved.
 * @license   See "LICENSE" file bundled with this distribution.
 */

namespace swap;

/**
 * [类型] 时间值
 */
class time_value extends value {

    public function is_valid() {
        return self::is_valid_time($this->value);
    }
    
    public static function is_valid_time($time) {
        return false;
    }
}
