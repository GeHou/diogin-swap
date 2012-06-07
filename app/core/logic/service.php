<?php
/**
 * 领域服务
 *
 * @copyright Copyright (c) 2009-2012 Jingcheng Zhang <diogin@gmail.com>. All rights reserved.
 * @license   See "LICENSE" file bundled with this distribution.
 */

namespace swap;

// [类型] 服务异常
class service_except extends \LogicException {
    public function set_prop_msg($prop_name, $msg) {
        $this->msgs[$prop_name] = $msg;
    }
    public function get_prop_name() {
        $msgs = $this->msgs;
        reset($msgs);
        list($prop_name, ) = each($msgs);
        return $prop_name;
    }
    public function get_msg() {
        $msgs = $this->msgs;
        reset($msgs);
        list(, $msg) = each($msgs);
        return $msg;
    }
    public function get_msgs() {
        return $this->msgs;
    }
    
    public static function throw_prop_msg($prop_name, $msg) {
        $e = new static();
        $e->set_prop_msg($prop_name, $msg);
        throw $e;
    }
    
    protected $msgs = array();
}

// [实体] 领域服务
abstract class service {
    
}
