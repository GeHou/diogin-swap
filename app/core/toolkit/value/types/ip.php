<?php
/**
 * IP 地址类型的值
 *
 * @copyright Copyright (c) 2009-2012 Jingcheng Zhang <diogin@gmail.com>. All rights reserved.
 * @license   See "LICENSE" file bundled with this distribution.
 */

namespace swap;

/**
 * [类型] IP 地址值
 */
class ip_value extends str_value {

    public function is_valid() {
        return self::is_valid_ip($this->value);
    }
    
    public static function is_valid_ip($ip) {
        $long = ip2long($ip);
        if ($long === false) {
            return false;
        }
        return long2ip($long) === $ip;
    }
}
