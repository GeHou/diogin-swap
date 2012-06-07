<?php
/**
 * 实时型数据校验器
 *
 * @copyright Copyright (c) 2009-2012 Jingcheng Zhang <diogin@gmail.com>. All rights reserved.
 * @license   See "LICENSE" file bundled with this distribution.
 */

namespace swap;

// [类型] 实时型数据校验器
class instant_checker extends checker {
    public function choose(array $prop_rules) {
        foreach (array_keys($this->props) as $prop_name) {
            if (!array_key_exists($prop_name, $prop_rules)) {
                continue;
            }
            $rules = $prop_rules[$prop_name];
            $this->do_check($prop_name, $this->props, $rules);
        }
    }
}
