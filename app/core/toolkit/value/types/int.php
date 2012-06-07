<?php
/**
 * 整数类型的值
 *
 * @copyright Copyright (c) 2009-2012 Jingcheng Zhang <diogin@gmail.com>. All rights reserved.
 * @license   See "LICENSE" file bundled with this distribution.
 */

namespace swap;

/**
 * [类型] 整数值
 */
class int_value extends value {

    public function is_valid() {
        return is_int($this->value);
    }
}
