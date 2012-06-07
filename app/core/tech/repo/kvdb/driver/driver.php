<?php
/**
 * 键值数据库连接抽象
 *
 * @copyright Copyright (c) 2009-2012 Jingcheng Zhang <diogin@gmail.com>. All rights reserved.
 * @license   See "LICENSE" file bundled with this distribution.
 */

namespace swap;

/* type */ abstract class kvdb_conn {

    abstract public function __construct($dsn);
    abstract public function get($key);
    abstract public function get_many(array $keys);
    abstract public function set($key, $value);
    abstract public function set_many(array $keyvalues);
}

/* entity */ class kvdb_conn_pool {

    public static function get_kvdb_conn_from_dsn($dsn) {
        
    }
}
