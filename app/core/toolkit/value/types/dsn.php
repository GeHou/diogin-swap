<?php
/**
 * 数据源类型的值
 *
 * @copyright Copyright (c) 2009-2012 Jingcheng Zhang <diogin@gmail.com>. All rights reserved.
 * @license   See "LICENSE" file bundled with this distribution.
 */

namespace swap;

/**
 * [类型] 数据源值
 */
class dsn_value extends str_value {

    const separator = '://';
    
    public function is_valid() {
        return self::is_valid_dsn($this->value);
    }
    public function __construct($value) {
        parent::__construct($value);
        if ($this->is_valid()) {
            list($this->scheme, $this->detail) = explode(self::separator, $value, 2);
        }
    }
    public function get_scheme() {
        return $this->scheme;
    }
    public function get_detail() {
        return $this->detail;
    }
    public static function is_valid_dsn($dsn) {
        return is_string($dsn) && strpos($dsn, self::separator) !== false;
    }
    
    protected $scheme = '';
    protected $detail = '';
}
