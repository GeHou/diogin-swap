<?php
/**
 * 分布式关系数据库抽象
 *
 * @copyright Copyright (c) 2009-2012 Jingcheng Zhang <diogin@gmail.com>. All rights reserved.
 * @license   See "LICENSE" file bundled with this distribution.
 */

namespace swap;

// [内部][实体] 分布式关系数据库
class distributed_rdb extends rdb {
    public static function get($table_name, array $keyvalues, array $order_limit = array(array(), 0, 0), $use_master = false) {
        $rdb_node = $use_master ? distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name) : distributed_rdb_node_pool::get_slave_rdb_node_from_table_name($table_name);
        return $rdb_node->get($table_name, $keyvalues, $order_limit);
    }
    public static function get_one($table_name, array $keyvalues, $use_master = false) {
        $rdb_node = $use_master ? distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name) : distributed_rdb_node_pool::get_slave_rdb_node_from_table_name($table_name);
        return $rdb_node->get_one($table_name, $keyvalues);
    }
    public static function get_where($table_name, $where, array $args = array(), array $order_limit = array(array(), 0, 0), $use_master = false) {
        $rdb_node = $use_master ? distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name) : distributed_rdb_node_pool::get_slave_rdb_node_from_table_name($table_name);
        return $rdb_node->get_where($table_name, $where, $args, $order_limit);
    }
    public static function get_by_id($table_name, $id, $use_master = false) {
        $rdb_node = $use_master ? distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name) : distributed_rdb_node_pool::get_slave_rdb_node_from_table_name($table_name);
        return $rdb_node->get_by_id($table_name, $id);
    }
    public static function get_by_ids($table_name, array $ids, array $order_limit = array(array(), 0, 0), $use_master = false) {
        $rdb_node = $use_master ? distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name) : distributed_rdb_node_pool::get_slave_rdb_node_from_table_name($table_name);
        return $rdb_node->get_by_ids($table_name, $ids, $order_limit);
    }
    public static function get_in($table_name, $field_name, array $values, array $order_limit = array(array(), 0, 0), $use_master = false) {
        $rdb_node = $use_master ? distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name) : distributed_rdb_node_pool::get_slave_rdb_node_from_table_name($table_name);
        return $rdb_node->get_in($table_name, $field_name, $values, $order_limit);
    }
    public static function get_all($table_name, array $order_limit = array(array(), 0, 0), $use_master = false) {
        $rdb_node = $use_master ? distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name) : distributed_rdb_node_pool::get_slave_rdb_node_from_table_name($table_name);
        return $rdb_node->get_all($table_name, $order_limit);
    }
    
    public static function get_for_fields($field_names, $table_name, array $keyvalues, array $order_limit = array(array(), 0, 0), $use_master = false) {
        $rdb_node = $use_master ? distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name) : distributed_rdb_node_pool::get_slave_rdb_node_from_table_name($table_name);
        return $rdb_node->get_for_fields($field_names, $table_name, $keyvalues, $order_limit);
    }
    public static function get_one_for_fields($field_names, $table_name, array $keyvalues, $use_master = false) {
        $rdb_node = $use_master ? distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name) : distributed_rdb_node_pool::get_slave_rdb_node_from_table_name($table_name);
        return $rdb_node->get_one_for_fields($field_names, $table_name, $keyvalues);
    }
    public static function get_where_for_fields($field_names, $table_name, $where, array $args = array(), array $order_limit = array(array(), 0, 0), $use_master = false) {
        $rdb_node = $use_master ? distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name) : distributed_rdb_node_pool::get_slave_rdb_node_from_table_name($table_name);
        return $rdb_node->get_where_for_fields($field_names, $table_name, $where, $args, $order_limit);
    }
    public static function get_by_id_for_fields($field_names, $table_name, $id, $use_master = false) {
        $rdb_node = $use_master ? distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name) : distributed_rdb_node_pool::get_slave_rdb_node_from_table_name($table_name);
        return $rdb_node->get_by_id_for_fields($field_names, $table_name, $id);
    }
    public static function get_by_ids_for_fields($field_names, $table_name, array $ids, array $order_limit = array(array(), 0, 0), $use_master = false) {
        $rdb_node = $use_master ? distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name) : distributed_rdb_node_pool::get_slave_rdb_node_from_table_name($table_name);
        return $rdb_node->get_by_ids_for_fields($field_names, $table_name, $ids, $order_limit);
    }
    public static function get_in_for_fields($field_names, $table_name, $field_name, array $values, array $order_limit = array(array(), 0, 0), $use_master = false) {
        $rdb_node = $use_master ? distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name) : distributed_rdb_node_pool::get_slave_rdb_node_from_table_name($table_name);
        return $rdb_node->get_in_for_fields($field_names, $table_name, $field_name, $values, $order_limit);
    }
    public static function get_all_for_fields($field_names, $table_name, array $order_limit = array(array(), 0, 0), $use_master = false) {
        $rdb_node = $use_master ? distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name) : distributed_rdb_node_pool::get_slave_rdb_node_from_table_name($table_name);
        return $rdb_node->get_all_for_fields($field_names, $table_name, $order_limit);
    }
    
    public static function pager($table_name, array $keyvalues, array $order_limit = array(array(), 0, 0), $use_master = false) {
        $rdb_node = $use_master ? distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name) : distributed_rdb_node_pool::get_slave_rdb_node_from_table_name($table_name);
        return $rdb_node->pager($table_name, $keyvalues, $order_limit);
    }
    public static function pager_where($table_name, $where, array $args = array(), array $order_limit = array(array(), 0, 0), $use_master = false) {
        $rdb_node = $use_master ? distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name) : distributed_rdb_node_pool::get_slave_rdb_node_from_table_name($table_name);
        return $rdb_node->pager_where($table_name, $where, $args, $order_limit);
    }
    public static function pager_by_ids($table_name, array $ids, array $order_limit = array(array(), 0, 0), $use_master = false) {
        $rdb_node = $use_master ? distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name) : distributed_rdb_node_pool::get_slave_rdb_node_from_table_name($table_name);
        return $rdb_node->pager_by_ids($table_name, $ids, $order_limit);
    }
    public static function pager_in($table_name, $field_name, array $values, array $order_limit = array(array(), 0, 0), $use_master = false) {
        $rdb_node = $use_master ? distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name) : distributed_rdb_node_pool::get_slave_rdb_node_from_table_name($table_name);
        return $rdb_node->pager_in($table_name, $field_name, $values, $order_limit);
    }
    public static function pager_all($table_name, array $order_limit = array(array(), 0, 0), $use_master = false) {
        $rdb_node = $use_master ? distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name) : distributed_rdb_node_pool::get_slave_rdb_node_from_table_name($table_name);
        return $rdb_node->pager_all($table_name, $order_limit);
    }
    
    public static function pager_with_count($record_count, $table_name, array $keyvalues, array $order_limit = array(array(), 0, 0), $use_master = false) {
        $rdb_node = $use_master ? distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name) : distributed_rdb_node_pool::get_slave_rdb_node_from_table_name($table_name);
        return $rdb_node->pager_with_count($record_count, $table_name, $keyvalues, $order_limit);
    }
    public static function pager_where_with_count($record_count, $table_name, $where, array $args = array(), array $order_limit = array(array(), 0, 0), $use_master = false) {
        $rdb_node = $use_master ? distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name) : distributed_rdb_node_pool::get_slave_rdb_node_from_table_name($table_name);
        return $rdb_node->pager_where_with_count($record_count, $table_name, $where, $args, $order_limit);
    }
    public static function pager_by_ids_with_count($record_count, $table_name, array $ids, array $order_limit = array(array(), 0, 0), $use_master = false) {
        $rdb_node = $use_master ? distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name) : distributed_rdb_node_pool::get_slave_rdb_node_from_table_name($table_name);
        return $rdb_node->pager_by_ids_with_count($record_count, $table_name, $ids, $order_limit);
    }
    public static function pager_in_with_count($record_count, $table_name, $field_name, array $values, array $order_limit = array(array(), 0, 0), $use_master = false) {
        $rdb_node = $use_master ? distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name) : distributed_rdb_node_pool::get_slave_rdb_node_from_table_name($table_name);
        return $rdb_node->pager_in_with_count($record_count, $table_name, $field_name, $values, $order_limit);
    }
    public static function pager_all_with_count($record_count, $table_name, array $order_limit = array(array(), 0, 0), $use_master = false) {
        $rdb_node = $use_master ? distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name) : distributed_rdb_node_pool::get_slave_rdb_node_from_table_name($table_name);
        return $rdb_node->pager_all_with_count($record_count, $table_name, $order_limit);
    }
    
    public static function count($table_name, array $keyvalues, $use_master = false) {
        $rdb_node = $use_master ? distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name) : distributed_rdb_node_pool::get_slave_rdb_node_from_table_name($table_name);
        return $rdb_node->count($table_name, $keyvalues);
    }
    public static function count_where($table_name, $where, array $args = array(), $use_master = false) {
        $rdb_node = $use_master ? distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name) : distributed_rdb_node_pool::get_slave_rdb_node_from_table_name($table_name);
        return $rdb_node->count_where($table_name, $where, $args);
    }
    public static function count_by_ids($table_name, array $ids, $use_master = false) {
        $rdb_node = $use_master ? distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name) : distributed_rdb_node_pool::get_slave_rdb_node_from_table_name($table_name);
        return $rdb_node->count_by_ids($table_name, $ids);
    }
    public static function count_in($table_name, $field_name, array $values, $use_master = false) {
        $rdb_node = $use_master ? distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name) : distributed_rdb_node_pool::get_slave_rdb_node_from_table_name($table_name);
        return $rdb_node->count_in($table_name, $field_name, $values);
    }
    public static function count_all($table_name, $use_master = false) {
        $rdb_node = $use_master ? distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name) : distributed_rdb_node_pool::get_slave_rdb_node_from_table_name($table_name);
        return $rdb_node->count_all($table_name);
    }
    
    public static function set($table_name, array $keyvalues, array $conditions) {
        return distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name)->set($table_name, $keyvalues, $conditions);
    }
    public static function set_where($table_name, array $keyvalues, $where, array $args = array()) {
        return distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name)->set_where($table_name, $keyvalues, $where, $args);
    }
    public static function set_by_id($table_name, array $keyvalues, $id) {
        return distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name)->set_by_id($table_name, $keyvalues, $id);
    }
    public static function set_by_ids($table_name, array $keyvalues, array $ids) {
        return distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name)->set_by_ids($table_name, $keyvalues, $ids);
    }
    public static function set_all($table_name, array $keyvalues) {
        return distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name)->set_all($table_name, $keyvalues);
    }
    
    public static function add($table_name, array $keyvalues) {
        return distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name)->add($table_name, $keyvalues);
    }
    public static function add_many($table_name, array $keyvalues_list) {
        return distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name)->add_many($table_name, $keyvalues_list);
    }
    
    public static function del($table_name, array $keyvalues) {
        return distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name)->del($table_name, $keyvalues);
    }
    public static function del_where($table_name, $where, array $args = array()) {
        return distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name)->del_where($table_name, $where, $args);
    }
    public static function del_by_id($table_name, $id) {
        return distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name)->del_by_id($table_name, $id);
    }
    public static function del_by_ids($table_name, array $ids) {
        return distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name)->del_by_ids($table_name, $ids);
    }
    
    public static function rep($table_name, array $keyvalues) {
        return distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name)->rep($table_name, $keyvalues);
    }
    public static function rep_many($table_name, array $keyvalues_list) {
        return distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name)->rep_many($table_name, $keyvalues_list);
    }
    
    public static function inc($table_name, array $keyvalues, array $conditions) {
        return distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name)->inc($table_name, $keyvalues, $conditions);
    }
    public static function inc_by_id($table_name, array $keyvalues, $id) {
        return distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name)->inc_by_id($table_name, $keyvalues, $id);
    }
    public static function inc_by_ids($table_name, array $keyvalues, array $ids) {
        return distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name)->inc_by_ids($table_name, $keyvalues, $ids);
    }
    
    public static function set_and_inc($table_name, array $sets, array $incs, array $conditions) {
        return distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name)->set_and_inc($table_name, $sets, $incs, $conditions);
    }
    public static function set_and_inc_by_id($table_name, array $sets, array $incs, $id) {
        return distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name)->set_and_inc_by_id($table_name, $sets, $incs, $id);
    }
    public static function set_and_inc_by_ids($table_name, array $sets, array $incs, array $ids) {
        return distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name)->set_and_inc_by_ids($table_name, $sets, $incs, $ids);
    }
    
    public static function dec($table_name, array $keyvalues, array $conditions) {
        return distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name)->dec($table_name, $keyvalues, $conditions);
    }
    public static function dec_by_id($table_name, array $keyvalues, $id) {
        return distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name)->dec_by_id($table_name, $keyvalues, $id);
    }
    public static function dec_by_ids($table_name, array $keyvalues, array $ids) {
        return distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name)->dec_by_ids($table_name, $keyvalues, $ids);
    }
    
    public static function fetch($table_name, $sql, array $args = array(), $use_master = false) {
        $rdb_node = $use_master ? distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name) : distributed_rdb_node_pool::get_slave_rdb_node_from_table_name($table_name);
        return $rdb_node->select($sql, $args);
    }
    public static function fetch_one($table_name, $sql, array $args = array(), $use_master = false) {
        $rdb_node = $use_master ? distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name) : distributed_rdb_node_pool::get_slave_rdb_node_from_table_name($table_name);
        return $rdb_node->select_one($sql, $args);
    }
    public static function modify($table_name, $sql, array $args = array()) {
        return distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name)->update($sql, $args);
    }
    public static function create($table_name, $sql, array $args = array()) {
        return distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name)->insert($sql, $args);
    }
    public static function remove($table_name, $sql, array $args = array()) {
        return distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name)->delete($sql, $args);
    }
    public static function change($table_name, $sql, array $args = array()) {
        return distributed_rdb_node_pool::get_master_rdb_node_from_table_name($table_name)->replace($sql, $args);
    }
    
    public static function select($source_name, $sql, array $args = array(), $use_master = false) {
        $rdb_node = $use_master ? distributed_rdb_node_pool::get_master_rdb_node_from_source_name($source_name) : distributed_rdb_node_pool::get_slave_rdb_node_from_source_name($source_name);
        return $rdb_node->select($sql, $args);
    }
    public static function select_one($source_name, $sql, array $args = array(), $use_master = false) {
        $rdb_node = $use_master ? distributed_rdb_node_pool::get_master_rdb_node_from_source_name($source_name) : distributed_rdb_node_pool::get_slave_rdb_node_from_source_name($source_name);
        return $rdb_node->select_one($sql, $args);
    }
    public static function update($source_name, $sql, array $args = array()) {
        return distributed_rdb_node_pool::get_master_rdb_node_from_source_name($source_name)->update($sql, $args);
    }
    public static function insert($source_name, $sql, array $args = array()) {
        return distributed_rdb_node_pool::get_master_rdb_node_from_source_name($source_name)->insert($sql, $args);
    }
    public static function delete($source_name, $sql, array $args = array()) {
        return distributed_rdb_node_pool::get_master_rdb_node_from_source_name($source_name)->delete($sql, $args);
    }
    public static function replace($source_name, $sql, array $args = array()) {
        return distributed_rdb_node_pool::get_master_rdb_node_from_source_name($source_name)->replace($sql, $args);
    }
    
    public static function begin($source_name) {
        return distributed_rdb_node_pool::get_master_rdb_node_from_source_name($source_name)->begin();
    }
    public static function commit($source_name) {
        return distributed_rdb_node_pool::get_master_rdb_node_from_source_name($source_name)->commit();
    }
    public static function rollback($source_name) {
        return distributed_rdb_node_pool::get_master_rdb_node_from_source_name($source_name)->rollback();
    }
}
// [内部][实体] 分布式关系数据库节点池
class distributed_rdb_node_pool extends rdb_node_pool {
    public static function get_master_rdb_node_from_table_name($table_name) {
        static $master_rdb_nodes_by_table_name = array();
        if (!array_key_exists($table_name, $master_rdb_nodes_by_table_name)) {
            $master_rdb_nodes_by_table_name[$table_name] = self::get_master_rdb_node_from_source_name(self::get_source_name_from_table_name($table_name));
        }
        return $master_rdb_nodes_by_table_name[$table_name];
    }
    public static function get_slave_rdb_node_from_table_name($table_name) {
        static $slave_rdb_nodes_by_table_name = array();
        if (!array_key_exists($table_name, $slave_rdb_nodes_by_table_name)) {
            $slave_rdb_nodes_by_table_name[$table_name] = self::get_slave_rdb_node_from_source_name(self::get_source_name_from_table_name($table_name));
        }
        return $slave_rdb_nodes_by_table_name[$table_name];
    }
    public static function get_master_rdb_node_from_source_name($source_name) {
        static $master_rdb_nodes_by_source_name = array();
        if (!array_key_exists($source_name, $master_rdb_nodes_by_source_name)) {
            $dsn = config::get('technique.repo.rdb.sources.' . $source_name . '.master');
            try {
                $master_rdb_nodes_by_source_name[$source_name] = parent::get_rdb_node('master', $dsn);
            } catch (server_except $except) {
                throw new server_except("cannot connect to dsn: '{$dsn}'");
            }
        }
        return $master_rdb_nodes_by_source_name[$source_name];
    }
    public static function get_slave_rdb_node_from_source_name($source_name) {
        static $slave_rdb_nodes_by_source_name = array();
        if (!array_key_exists($source_name, $slave_rdb_nodes_by_source_name)) {
            $dsns = config::get('technique.repo.rdb.sources.' . $source_name . '.slaves', array());
            if ($dsns === array()) {
                $dsns = array(config::get('technique.repo.rdb.sources.' . $source_name . '.master'));
            }
            shuffle($dsns);
            $all_attempts_failed = true;
            foreach ($dsns as $dsn) {
                try {
                    $slave_rdb_nodes_by_source_name[$source_name] = parent::get_rdb_node('slave', $dsn);
                    $all_attempts_failed = false;
                    break;
                } catch (server_except $except) {
                    logger::log_error("cannot connect to dsn: '{$dsn}', maybe failed?");
                }
            }
            if ($all_attempts_failed) {
                throw new server_except("cannot connect to all slave dsns of source '{$source_name}'");
            }
        }
        return $slave_rdb_nodes_by_source_name[$source_name];
    }
    
    protected static function get_source_name_from_table_name($table_name) {
        $source_name = config::get('technique.repo.rdb.tables.' . $table_name, null);
        if ($source_name === null) {
            $source_name = config::get('technique.repo.rdb.tables.*');
        }
        return $source_name;
    }
}
