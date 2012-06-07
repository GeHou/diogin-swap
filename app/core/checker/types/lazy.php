<?php
/**
 * 懒惰型数据校验器
 *
 * @copyright Copyright (c) 2009-2012 Jingcheng Zhang <diogin@gmail.com>. All rights reserved.
 * @license   See "LICENSE" file bundled with this distribution.
 */

namespace swap;

// [类型] 懒惰型数据校验器
class lazy_checker extends checker {
    public function failed($prop_name, $reason) {
        $e = new check_failed();
        $e->set_reason($prop_name, $reason);
        throw $e;
    }
}
// [类型] 校验失败时的结果
class check_failed extends \Exception {
    public function set_reason($prop_name, $reason) {
        $this->reasons[$prop_name] = $reason;
    }
    public function get_reasons() {
        return $this->reasons;
    }
    protected $reasons = array();
}
