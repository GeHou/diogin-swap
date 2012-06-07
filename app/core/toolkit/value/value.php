<?php
/**
 * 值类型抽象
 *
 * @copyright Copyright (c) 2009-2012 Jingcheng Zhang <diogin@gmail.com>. All rights reserved.
 * @license   See "LICENSE" file bundled with this distribution.
 */

namespace swap;

//  [类型] 值
abstract class value {
    abstract public function is_valid();
    
    const int_type = 'int';
    const str_type = 'str';
    const arr_type = 'arr';
    const float_type = 'float';
    const email_type = 'email';
    const ip_type = 'ip';
    const url_type = 'url';
    const dsn_type = 'dsn';
    const date_type = 'date';
    const time_type = 'time';
    const mobile_type = 'mobile';
    
    public function __construct($value) {
        $this->value = $value;
    }
    
    protected $value = null;
}
