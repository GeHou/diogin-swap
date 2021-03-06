<?php
/**
 * PostgreSQL 关系数据库节点
 *
 * @copyright Copyright (c) 2009-2012 Jingcheng Zhang <diogin@gmail.com>. All rights reserved.
 * @license   See "LICENSE" file bundled with this distribution.
 */

namespace swap;

// [类型] postgresql 关系数据库节点
abstract class pgsql_rdb_node extends rdb_node {
    public function get($table_name, array $keyvalues, array $order_limit = array(array(), 0, 0)) {
    }
    public function get_one($table_name, array $keyvalues) {
    }
    public function get_where($table_name, $where, array $args = array(), array $order_limit = array(array(), 0, 0)) {
    }
    public function get_by_id($table_name, $id) {
    }
    public function get_by_ids($table_name, array $ids, array $order_limit = array(array(), 0, 0)) {
    }
    public function get_in($table_name, $field_name, array $values, array $order_limit = array(array(), 0, 0)) {
    }
    public function get_all($table_name, array $order_limit = array(array(), 0, 0)) {
    }
    
    public function get_for_fields($field_names, $table_name, array $keyvalues, array $order_limit = array(array(), 0, 0)) {
    }
    public function get_one_for_fields($field_names, $table_name, array $keyvalues) {
    }
    public function get_where_for_fields($field_names, $table_name, $where, array $args = array(), array $order_limit = array(array(), 0, 0)) {
    }
    public function get_by_id_for_fields($field_names, $table_name, $id) {
    }
    public function get_by_ids_for_fields($field_names, $table_name, array $ids, array $order_limit = array(array(), 0, 0)) {
    }
    public function get_in_for_fields($field_names, $table_name, $field_name, array $values, array $order_limit = array(array(), 0, 0)) {
    }
    public function get_all_for_fields($field_names, $table_name, array $order_limit = array(array(), 0, 0)) {
    }
    
    public function count($table_name, array $keyvalues) {
    }
    public function count_where($table_name, $where, array $args = array()) {
    }
    public function count_by_ids($table_name, array $ids) {
    }
    public function count_in($table_name, $field_name, array $values) {
    }
    public function count_all($table_name) {
    }
    
    public function pager($table_name, array $keyvalues, array $order_limit = array(array(), 0, 0)) {
    }
    public function pager_where($table_name, $where, array $args = array(), array $order_limit = array(array(), 0, 0)) {
    }
    public function pager_by_ids($table_name, array $ids, array $order_limit = array(array(), 0, 0)) {
    }
    public function pager_in($table_name, $field_name, array $values, array $order_limit = array(array(), 0, 0)) {
    }
    public function pager_all($table_name, array $order_limit = array(array(), 0, 0)) {
    }
    
    public function pager_with_count($record_count, $table_name, array $keyvalues, array $order_limit = array(array(), 0, 0)) {
    }
    public function pager_where_with_count($record_count, $table_name, $where, array $args = array(), array $order_limit = array(array(), 0, 0)) {
    }
    public function pager_by_ids_with_count($record_count, $table_name, array $ids, array $order_limit = array(array(), 0, 0)) {
    }
    public function pager_in_with_count($record_count, $table_name, $field_name, array $values, array $order_limit = array(array(), 0, 0)) {
    }
    public function pager_all_with_count($record_count, $table_name, array $order_limit = array(array(), 0, 0)) {
    }
    
    public function select($sql, array $args = array()) {
    }
    public function select_one($sql, array $args = array()) {
    }
    
    protected function do_get($field_name_list, $table_name, array $keyvalues, array $order_limit = array(array(), 0, 0)) {
    }
    protected function do_get_where($field_name_list, $table_name, $where, array $args = array(), array $order_limit = array(array(), 0, 0)) {
    }
    protected function do_get_by_id($field_name_list, $table_name, $id) {
    }
    protected function do_get_in($field_name_list, $table_name, $field_name, array $values, array $order_limit = array(array(), 0, 0)) {
    }
    protected function do_get_all($field_name_list, $table_name, array $order_limit = array(array(), 0, 0)) {
    }
    
    protected function do_count($sql) {
    }
    
    protected function do_pager($record_count, $table_name, array $keyvalues, array $order_limit = array(array(), 0, 0)) {
    }
    protected function do_pager_where($record_count, $table_name, $where, array $args = array(), array $order_limit = array(array(), 0, 0)) {
    }
    protected function do_pager_by_ids($record_count, $table_name, array $ids, array $order_limit = array(array(), 0, 0)) {
    }
    protected function do_pager_in($record_count, $table_name, $field_name, array $values, array $order_limit = array(array(), 0, 0)) {
    }
    protected function do_pager_all($record_count, $table_name, array $order_limit = array(array(), 0, 0)) {
    }
    
    protected function get_first_record($records) {
    }
    protected function query_and_fetch_records($sql, $index_by_id) {
    }
    
    protected function get_full_table_name($table_name) {
        return pgsql_rdb_node_util::build_table_name($table_name);
    }
}
// [类型] postgresql 关系数据库主节点
class pgsql_master_rdb_node extends pgsql_rdb_node {
    public function set($table_name, array $keyvalues, array $conditions) {
    }
    public function set_where($table_name, array $keyvalues, $where, array $args = array()) {
    }
    public function set_by_id($table_name, array $keyvalues, $id) {
    }
    public function set_by_ids($table_name, array $keyvalues, array $ids) {
    }
    public function set_all($table_name, array $keyvalues) {
    }
    public function update($sql, array $args = array()) {
    }
    public function add($table_name, array $keyvalues) {
    }
    public function add_many($table_name, array $keyvalues_list) {
    }
    public function insert($sql, array $args = array()) {
    }
    public function del($table_name, array $keyvalues) {
    }
    public function del_where($table_name, $where, array $args = array()) {
    }
    public function del_by_id($table_name, $id) {
    }
    public function del_by_ids($table_name, array $ids) {
    }
    public function delete($sql, array $args = array()) {
    }
    public function rep($table_name, array $keyvalues) {
    }
    public function rep_many($table_name, array $keyvalues_list) {
    }
    public function replace($sql, array $args = array()) {
    }
    public function inc($table_name, array $keyvalues, array $conditions) {
    }
    public function inc_by_id($table_name, array $keyvalues, $id) {
    }
    public function inc_by_ids($table_name, array $keyvalues, array $ids) {
    }
    public function set_and_inc($table_name, array $sets, array $incs, array $conditions) {
    }
    public function set_and_inc_by_id($table_name, array $sets, array $incs, $id) {
    }
    public function set_and_inc_by_ids($table_name, array $sets, array $incs, array $ids) {
    }
    public function dec($table_name, array $keyvalues, array $conditions) {
    }
    public function dec_by_id($table_name, array $keyvalues, $id) {
    }
    public function dec_by_ids($table_name, array $keyvalues, array $ids) {
    }
    public function begin() {
    }
    public function commit() {
    }
    public function rollback() {
    }
    
    protected function execute($sql) {
    }
    protected function insert_id() {
    }
    protected function affected_rows() {
    }
}
// [类型] postgresql 关系数据库从节点
class pgsql_slave_rdb_node extends pgsql_rdb_node {
    public function set($table_name, array $keyvalues, array $conditions) {
        throw new developer_error('slave is not allowed to update');
    }
    public function set_where($table_name, array $keyvalues, $where, array $args = array()) {
        throw new developer_error('slave is not allowed to update');
    }
    public function set_by_id($table_name, array $keyvalues, $id) {
        throw new developer_error('slave is not allowed to update');
    }
    public function set_by_ids($table_name, array $keyvalues, array $ids) {
        throw new developer_error('slave is not allowed to update');
    }
    public function set_all($table_name, array $keyvalues) {
        throw new developer_error('slave is not allowed to update');
    }
    public function update($sql, array $args = array()) {
        throw new developer_error('slave is not allowed to update');
    }
    public function add($table_name, array $keyvalues) {
        throw new developer_error('slave is not allowed to insert');
    }
    public function add_many($table_name, array $keyvalues_list) {
        throw new developer_error('slave is not allowed to insert');
    }
    public function insert($sql, array $args = array()) {
        throw new developer_error('slave is not allowed to insert');
    }
    public function del($table_name, array $keyvalues) {
        throw new developer_error('slave is not allowed to delete');
    }
    public function del_where($table_name, $where, array $args = array()) {
        throw new developer_error('slave is not allowed to delete');
    }
    public function del_by_id($table_name, $id) {
        throw new developer_error('slave is not allowed to delete');
    }
    public function del_by_ids($table_name, array $ids) {
        throw new developer_error('slave is not allowed to delete');
    }
    public function delete($sql, array $args = array()) {
        throw new developer_error('slave is not allowed to delete');
    }
    public function rep($table_name, array $keyvalues) {
        throw new developer_error('slave is not allowed to replace');
    }
    public function rep_many($table_name, array $keyvalues_list) {
        throw new developer_error('slave is not allowed to replace');
    }
    public function replace($sql, array $args = array()) {
        throw new developer_error('slave is not allowed to replace');
    }
    public function inc($table_name, array $keyvalues, array $conditions) {
        throw new developer_error('slave is not allowed to inc');
    }
    public function inc_by_id($table_name, array $keyvalues, $id) {
        throw new developer_error('slave is not allowed to inc');
    }
    public function inc_by_ids($table_name, array $keyvalues, array $ids) {
        throw new developer_error('slave is not allowed to inc');
    }
    public function set_and_inc($table_name, array $sets, array $incs, array $conditions) {
        throw new developer_error('slave is not allowed to set_and_inc');
    }
    public function set_and_inc_by_id($table_name, array $sets, array $incs, $id) {
        throw new developer_error('slave is not allowed to set_and_inc');
    }
    public function set_and_inc_by_ids($table_name, array $sets, array $incs, array $ids) {
        throw new developer_error('slave is not allowed to set_and_inc');
    }
    public function dec($table_name, array $keyvalues, array $conditions) {
        throw new developer_error('slave is not allowed to dec');
    }
    public function dec_by_id($table_name, array $keyvalues, $id) {
        throw new developer_error('slave is not allowed to dec');
    }
    public function dec_by_ids($table_name, array $keyvalues, array $ids) {
        throw new developer_error('slave is not allowed to dec');
    }
    public function begin() {
        throw new developer_error('slave is not allowed to perform transaction');
    }
    public function commit() {
        throw new developer_error('slave is not allowed to perform transaction');
    }
    public function rollback() {
        throw new developer_error('slave is not allowed to perform transaction');
    }
}
// [实体] postgresql 关系数据库节点工具
class pgsql_rdb_node_util extends rdb_node_util {
    public static function build_order_limit_sql(array $order_limit) {
    }
    public static function build_field_name_list($field_names) {
    }
    public static function build_value_list(array $values) {
    }
    public static function build_equal_list(array $keyvalues, $separator) {
    }
    public static function build_inc_dec_list(array $keyvalues, $operator) {
    }
    public static function replace_sql_args($sql, array $args) {
    }
    public static function build_table_name($table_name) {
        return '"' . $table_name . '"';
    }
    public static function build_field_name($field_name) {
        return '"' . $field_name . '"';
    }
    
    protected static function join_token_values(array $values) {
        return implode(' ', $values);
    }
}
