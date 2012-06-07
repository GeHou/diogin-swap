<?php
/**
 * URL 类型的值
 *
 * @copyright Copyright (c) 2009-2012 Jingcheng Zhang <diogin@gmail.com>. All rights reserved.
 * @license   See "LICENSE" file bundled with this distribution.
 */

namespace swap;

/**
 * [类型] URL 值
 */
class url_value extends str_value {

    public function is_valid() {
        return self::is_valid_url($this->value);
    }
    
    public static function is_valid_url($url) {
        return false;
    }
}
