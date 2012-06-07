<?php
/**
 * 关系数据库抽象
 *
 * @copyright Copyright (c) 2009-2012 Jingcheng Zhang <diogin@gmail.com>. All rights reserved.
 * @license   See "LICENSE" file bundled with this distribution.
 */

namespace swap;

rdb::__init__();

// [实体] 关系数据库 API
interface rdb_api {
    static function get($table_name, array $keyvalues, array $order_limit = array(array(), 0, 0), $use_master = false);
    static function get_one($table_name, array $keyvalues, $use_master = false);
    static function get_where($table_name, $where, array $args = array(), array $order_limit = array(array(), 0, 0), $use_master = false);
    static function get_by_id($table_name, $id, $use_master = false);
    static function get_by_ids($table_name, array $ids, array $order_limit = array(array(), 0, 0), $use_master = false);
    static function get_in($table_name, $field_name, array $values, array $order_limit = array(array(), 0, 0), $use_master = false);
    static function get_all($table_name, array $order_limit = array(array(), 0, 0), $use_master = false);
    
    static function get_for_fields($field_names, $table_name, array $keyvalues, array $order_limit = array(array(), 0, 0), $use_master = false);
    static function get_one_for_fields($field_names, $table_name, array $keyvalues, $use_master = false);
    static function get_where_for_fields($field_names, $table_name, $where, array $args = array(), array $order_limit = array(array(), 0, 0), $use_master = false);
    static function get_by_id_for_fields($field_names, $table_name, $id, $use_master = false);
    static function get_by_ids_for_fields($field_names, $table_name, array $ids, array $order_limit = array(array(), 0, 0), $use_master = false);
    static function get_in_for_fields($field_names, $table_name, $field_name, array $values, array $order_limit = array(array(), 0, 0), $use_master = false);
    static function get_all_for_fields($field_names, $table_name, array $order_limit = array(array(), 0, 0), $use_master = false);
    
    static function pager($table_name, array $keyvalues, array $order_limit = array(array(), 0, 0), $use_master = false);
    static function pager_where($table_name, $where, array $args = array(), array $order_limit = array(array(), 0, 0), $use_master = false);
    static function pager_by_ids($table_name, array $ids, array $order_limit = array(array(), 0, 0), $use_master = false);
    static function pager_in($table_name, $field_name, array $values, array $order_limit = array(array(), 0, 0), $use_master = false);
    static function pager_all($table_name, array $order_limit = array(array(), 0, 0), $use_master = false);
    
    static function pager_with_count($record_count, $table_name, array $keyvalues, array $order_limit = array(array(), 0, 0), $use_master = false);
    static function pager_where_with_count($record_count, $table_name, $where, array $args = array(), array $order_limit = array(array(), 0, 0), $use_master = false);
    static function pager_by_ids_with_count($record_count, $table_name, array $ids, array $order_limit = array(array(), 0, 0), $use_master = false);
    static function pager_in_with_count($record_count, $table_name, $field_name, array $values, array $order_limit = array(array(), 0, 0), $use_master = false);
    static function pager_all_with_count($record_count, $table_name, array $order_limit = array(array(), 0, 0), $use_master = false);
    
    static function count($table_name, array $keyvalues, $use_master = false);
    static function count_where($table_name, $where, array $args = array(), $use_master = false);
    static function count_by_ids($table_name, array $ids, $use_master = false);
    static function count_in($table_name, $field_name, array $values, $use_master = false);
    static function count_all($table_name, $use_master = false);
    
    static function set($table_name, array $keyvalues, array $conditions);
    static function set_where($table_name, array $keyvalues, $where, array $args = array());
    static function set_by_id($table_name, array $keyvalues, $id);
    static function set_by_ids($table_name, array $keyvalues, array $ids);
    static function set_all($table_name, array $keyvalues);
    
    static function add($table_name, array $keyvalues);
    static function add_many($table_name, array $keyvalues_list);
    
    static function del($table_name, array $keyvalues);
    static function del_where($table_name, $where, array $args = array());
    static function del_by_id($table_name, $id);
    static function del_by_ids($table_name, array $ids);
    
    static function rep($table_name, array $keyvalues);
    static function rep_many($table_name, array $keyvalues_list);
    
    static function inc($table_name, array $keyvalues, array $conditions);
    static function inc_by_id($table_name, array $keyvalues, $id);
    static function inc_by_ids($table_name, array $keyvalues, array $ids);
    
    static function set_and_inc($table_name, array $sets, array $incs, array $conditions);
    static function set_and_inc_by_id($table_name, array $sets, array $incs, $id);
    static function set_and_inc_by_ids($table_name, array $sets, array $incs, array $ids);
    
    static function dec($table_name, array $keyvalues, array $conditions);
    static function dec_by_id($table_name, array $keyvalues, $id);
    static function dec_by_ids($table_name, array $keyvalues, array $ids);
    
    static function fetch($table_name, $sql, array $args = array(), $use_master = false);
    static function fetch_one($table_name, $sql, array $args = array(), $use_master = false);
    static function modify($table_name, $sql, array $args = array());
    static function create($table_name, $sql, array $args = array());
    static function remove($table_name, $sql, array $args = array());
    static function change($table_name, $sql, array $args = array());
    
    static function select(/* ... */);
    static function select_one(/* ... */);
    static function update(/* ... */);
    static function insert(/* ... */);
    static function delete(/* ... */);
    static function replace(/* ... */);
    
    static function begin(/* ... */);
    static function commit(/* ... */);
    static function rollback(/* ... */);
}
// [实体] 关系数据库
abstract class rdb /* implements rdb_api */ {
    public static function __callStatic($method_name, array $args) {
        return call_user_func_array(array(self::$rdb, $method_name), $args);
    }
    public static function __init__() {
        $rdb = 'swap\\' . config::get('technique.repo.rdb.type', 'distributed') . '_rdb';
        if (!class_exists($rdb, true)) {
            throw new developer_error("rdb: '{$rdb}' does not exist");
        }
        self::$rdb = $rdb;
    }
    
    protected static $rdb = '';
}
