<?php
/**
 * 字符串类型的值
 *
 * @copyright Copyright (c) 2009-2012 Jingcheng Zhang <diogin@gmail.com>. All rights reserved.
 * @license   See "LICENSE" file bundled with this distribution.
 */

namespace swap;

/**
 * [类型] 字符串值
 */
class str_value extends value {

    public function is_valid() {
        return is_string($this->value);
    }
}
