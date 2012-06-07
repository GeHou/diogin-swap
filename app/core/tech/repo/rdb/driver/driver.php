<?php
/**
 * 关系数据库连接和结果集抽象
 *
 * @copyright Copyright (c) 2009-2012 Jingcheng Zhang <diogin@gmail.com>. All rights reserved.
 * @license   See "LICENSE" file bundled with this distribution.
 */

namespace swap;

// [类型] 关系数据库连接
abstract class rdb_conn {
    abstract public function __construct($dsn);
    abstract public function select($sql);
    abstract public function execute($sql);
    abstract public function insert_id();
    abstract public function affected_rows();
    abstract public function escape($value);
    abstract public function begin();
    abstract public function commit();
    abstract public function rollback();
}
// [类型] 关系数据库结果集
abstract class rdb_result {
    abstract public function fetch_record();
    abstract public function num_rows();
    abstract public function free();
}
// [实体] 关系数据库连接池
class rdb_conn_pool {
    public static function get_rdb_type_and_conn_from_dsn($dsn) {
        static $rdb_conns = array();
        if (!isset($rdb_conns[$dsn])) {
            list($rdb_type, ) = explode('://', $dsn, 2);
            $rdb_conn_class = 'swap\\' . $rdb_type . '_rdb_conn';
            $rdb_conn = new $rdb_conn_class($dsn);
            $rdb_conns[$dsn] = array($rdb_type, $rdb_conn);
        }
        return $rdb_conns[$dsn];
    }
}
