<?php
/**
 * 邮箱地址类型的值
 *
 * @copyright Copyright (c) 2009-2012 Jingcheng Zhang <diogin@gmail.com>. All rights reserved.
 * @license   See "LICENSE" file bundled with this distribution.
 */

namespace swap;

/**
 * [类型] 邮箱地址值
 */
class email_value extends str_value {

    public function is_valid() {
        return self::is_valid_email($this->value);
    }
    
    public static function is_valid_email($email) {
        $pattern = '/^([^@\s]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i';
        return preg_match($pattern, $email);
    }
}
