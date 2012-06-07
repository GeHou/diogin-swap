<?php
/**
 * 基于关系数据库的领域模型
 *
 * @copyright Copyright (c) 2009-2012 Jingcheng Zhang <diogin@gmail.com>. All rights reserved.
 * @license   See "LICENSE" file bundled with this distribution.
 */

namespace swap;

/**
 * [类型] 关系模型接口
 */
interface rdb_model_api extends model_api {
    
    static function get_where($where, $args = array(), array $order_limit = array(array(), 0, 0));
    static function get_all(array $order_limit = array(array(), 0, 0));
    
    static function pager(array $keyvalues, array $order_limit = array(array(), 0, 0));
    static function pager_where($where, array $args = array(), array $order_limit = array(array(), 0, 0));
    static function pager_by_ids(array $ids, array $order_limit = array(array(), 0, 0));
    static function pager_in($field_name, array $values, array $order_limit = array(array(), 0, 0));
    static function pager_all(array $order_limit = array(array(), 0, 0));
    
    static function pager_with_count($count, array $keyvalues, array $order_limit = array(array(), 0, 0));
    static function pager_where_with_count($count, $where, array $args = array(), array $order_limit = array(array(), 0, 0));
    static function pager_by_ids_with_count($count, array $ids, array $order_limit = array(array(), 0, 0));
    static function pager_in_with_count($count, $field_name, array $values, array $order_limit = array(array(), 0, 0));
    static function pager_all_with_count($count, array $order_limit = array(array(), 0, 0));
    
    static function count(array $keyvalues);
    static function count_where($where, array $args = array());
    static function count_by_ids(array $ids);
    static function count_in($field_name, array $values);
    static function count_all();
    
    static function set(array $keyvalues, array $conditions);
    static function set_where(array $keyvalues, $where, array $args = array());
    static function set_by_id(array $keyvalues, $id);
    static function set_by_ids(array $keyvalues, array $ids);
    static function set_all(array $keyvalues);
    
    static function add(array $keyvalues);
    static function add_many(array $keyvalues_list);
    
    static function del(array $keyvalues);
    static function del_where($where, array $args = array());
    static function del_by_id($id);
    static function del_by_ids(array $ids);
    
    static function rep(array $keyvalues);
    static function rep_many(array $keyvalues_list);
    
    static function inc(array $keyvalues, array $conditions);
    static function inc_by_id(array $keyvalues, $id);
    static function inc_by_ids(array $keyvalues, array $ids);
    
    static function set_and_inc(array $sets, array $incs, array $conditions);
    static function set_and_inc_by_id(array $sets, array $incs, $id);
    static function set_and_inc_by_ids(array $sets, array $incs, array $ids);
    
    static function dec(array $keyvalues, array $conditions);
    static function dec_by_id(array $keyvalues, $id);
    static function dec_by_ids(array $keyvalues, array $ids);
    
    static function fetch($sql, array $args = array());
    static function fetch_one($sql, array $args = array());
    static function modify($sql, array $args = array());
    static function create($sql, array $args = array());
    static function remove($sql, array $args = array());
    static function change($sql, array $args = array());
}
/**
 * [类型] 关系模型
 */
abstract class rdb_model extends model implements rdb_model_api {
    public function __get($key) {
        return isset($this->_current_props[$key]) ? $this->_current_props[$key] : null;
    }
    public function __set($key, $value) {
        $this->_current_props[$key] = $value;
        if ($this->_is_new) {
            $this->_original_props[$key] = $value;
        }
    }
    public function __isset($key) {
        return isset($this->_current_props[$key]);
    }
    public function __construct($is_new = true) {
        if (!is_bool($is_new)) {
            throw new developer_error('bad constructor argument, should be bool');
        }
        $this->_is_new = $is_new;
        if ($is_new) {
            $this->_model_name = strip_suffix(get_class($this));
        }
    }
    public function get_props() {
        $props = $this->_current_props;
        foreach ($props as $key => $value) {
            if ($value instanceof model_api) {
                $props[$key] = $value->get_props();
            }
        }
        return $props;
    }
    public function set_props(array $props) {
        $this->_current_props = $props;
        if ($this->_is_new) {
            $this->_original_props = $this->_current_props;
        }
    }
    public function add_props(array $props) {
        $this->_current_props = array_merge($this->_current_props, $props);
        if ($this->_is_new) {
            $this->_original_props = $this->_current_props;
        }
    }
    public function save() {
        $current_props = $this->_current_props;
        if ($this->_is_new) {
            unset($current_props['id']);
            $id = rdb::add($this->_model_name, $current_props);
            $this->_current_props['id'] = $id;
            $this->_original_props['id'] = $id;
            $this->_is_new = false;
        } else {
            $original_props = $this->_original_props;
            if ($current_props === $original_props) {
                return;
            }
            $id = $original_props['id'];
            unset($current_props['id']);
            unset($original_props['id']);
            $props_diff = array_diff_assoc($current_props, $original_props);
            if ($props_diff !== array()) {
                rdb::set_by_id($this->_model_name, $props_diff, $id);
            }
        }
    }
    public function html_escape() {
        $that = clone $this;
        $that->_current_props = html::escape($that->_current_props);
        return $that;
    }
    public function html_unescape() {
        $that = clone $this;
        $that->_current_props = html::unescape($that->_current_props);
        return $that;
    }
    
    protected $_model_name = '';
    protected $_is_new = false;
    protected $_current_props = array();
    protected $_original_props = array();
    
    public static function get(array $keyvalues, array $order_limit = array(array(), 0, 0)) {
        $model_name = self::get_model_name();
        return self::create_models($model_name, rdb::get($model_name, $keyvalues, $order_limit));
    }
    public static function get_one(array $keyvalues) {
        $model_name = self::get_model_name();
        return self::create_model($model_name, rdb::get_one($model_name, $keyvalues));
    }
    public static function get_where($where, $args = array(), array $order_limit = array(array(), 0, 0)) {
        $model_name = self::get_model_name();
        return self::create_models($model_name, rdb::get_where($model_name, $where, $args, $order_limit));
    }
    public static function get_by_id($id) {
        $model_name = self::get_model_name();
        return self::create_model($model_name, rdb::get_by_id($model_name, $id));
    }
    public static function get_by_ids(array $ids, array $order_limit = array(array(), 0, 0)) {
        $model_name = self::get_model_name();
        return self::create_models($model_name, rdb::get_by_ids($model_name, $ids, $order_limit));
    }
    public static function get_in($field_name, array $values, array $order_limit = array(array(), 0, 0)) {
        $model_name = self::get_model_name();
        return self::create_models($model_name, rdb::get_in($model_name, $field_name, $values, $order_limit));
    }
    public static function get_all(array $order_limit = array(array(), 0, 0)) {
        $model_name = self::get_model_name();
        return self::create_models($model_name, rdb::get_all($model_name, $order_limit));
    }
    
    public static function pager(array $keyvalues, array $order_limit = array(array(), 0, 0)) {
        $model_name = self::get_model_name();
        list($pager, $records) = rdb::pager($model_name, $keyvalues, $order_limit);
        return array($pager, self::create_models($model_name, $records));
    }
    public static function pager_where($where, array $args = array(), array $order_limit = array(array(), 0, 0)) {
        $model_name = self::get_model_name();
        list($pager, $records) = rdb::pager_where($model_name, $where, $args, $order_limit);
        return array($pager, self::create_models($model_name, $records));
    }
    public static function pager_by_ids(array $ids, array $order_limit = array(array(), 0, 0)) {
        $model_name = self::get_model_name();
        list($pager, $records) = rdb::pager_by_ids($model_name, $ids, $order_limit);
        return array($pager, self::create_models($model_name, $records));
    }
    public static function pager_in($field_name, array $values, array $order_limit = array(array(), 0, 0)) {
        $model_name = self::get_model_name();
        list($pager, $records) = rdb::pager_in($model_name, $field_name, $values, $order_limit);
        return array($pager, self::create_models($model_name, $records));
    }
    public static function pager_all(array $order_limit = array(array(), 0, 0)) {
        $model_name = self::get_model_name();
        list($pager, $records) = rdb::pager_all($model_name, $order_limit);
        return array($pager, self::create_models($model_name, $records));
    }
    
    public static function pager_with_count($count, array $keyvalues, array $order_limit = array(array(), 0, 0)) {
        $model_name = self::get_model_name();
        list($pager, $records) = rdb::pager_with_count($count, $model_name, $keyvalues, $order_limit);
        return array($pager, self::create_models($model_name, $records));
    }
    public static function pager_where_with_count($count, $where, array $args = array(), array $order_limit = array(array(), 0, 0)) {
        $model_name = self::get_model_name();
        list($pager, $records) = rdb::pager_where_with_count($count, $model_name, $where, $args, $order_limit);
        return array($pager, self::create_models($model_name, $records));
    }
    public static function pager_by_ids_with_count($count, array $ids, array $order_limit = array(array(), 0, 0)) {
        $model_name = self::get_model_name();
        list($pager, $records) = rdb::pager_by_ids_with_count($count, $model_name, $ids, $order_limit);
        return array($pager, self::create_models($model_name, $records));
    }
    public static function pager_in_with_count($count, $field_name, array $values, array $order_limit = array(array(), 0, 0)) {
        $model_name = self::get_model_name();
        list($pager, $records) = rdb::pager_in_with_count($count, $model_name, $field_name, $values, $order_limit);
        return array($pager, self::create_models($model_name, $records));
    }
    public static function pager_all_with_count($count, array $order_limit = array(array(), 0, 0)) {
        $model_name = self::get_model_name();
        list($pager, $records) = rdb::pager_all_with_count($count, $model_name, $order_limit);
        return array($pager, self::create_models($model_name, $records));
    }
    
    public static function count(array $keyvalues) {
        return rdb::count(self::get_model_name(), $keyvalues);
    }
    public static function count_where($where, array $args = array()) {
        return rdb::count_where(self::get_model_name(), $where, $args);
    }
    public static function count_by_ids(array $ids) {
        return rdb::count_by_ids(self::get_model_name(), $ids);
    }
    public static function count_in($field_name, array $values) {
        return rdb::count_in(self::get_model_name(), $field_name, $values);
    }
    public static function count_all() {
        return rdb::count_all(self::get_model_name());
    }
    
    public static function set(array $keyvalues, array $conditions) {
        return rdb::set(self::get_model_name(), $keyvalues, $conditions);
    }
    public static function set_where(array $keyvalues, $where, array $args = array()) {
        return rdb::set_where(self::get_model_name(), $keyvalues, $where, $args);
    }
    public static function set_by_id(array $keyvalues, $id) {
        return rdb::set_by_id(self::get_model_name(), $keyvalues, $id);
    }
    public static function set_by_ids(array $keyvalues, array $ids) {
        return rdb::set_by_ids(self::get_model_name(), $keyvalues, $ids);
    }
    public static function set_all(array $keyvalues) {
        return rdb::set_all(self::get_model_name(), $keyvalues);
    }
    
    public static function add(array $keyvalues) {
        return rdb::add(self::get_model_name(), $keyvalues);
    }
    public static function add_many(array $keyvalues_list) {
        return rdb::add_many(self::get_model_name(), $keyvalues_list);
    }
    
    public static function del(array $keyvalues) {
        return rdb::del(self::get_model_name(), $keyvalues);
    }
    public static function del_where($where, array $args = array()) {
        return rdb::del_where(self::get_model_name(), $where, $args);
    }
    public static function del_by_id($id) {
        return rdb::del_by_id(self::get_model_name(), $id);
    }
    public static function del_by_ids(array $ids) {
        return rdb::del_by_ids(self::get_model_name(), $ids);
    }
    
    public static function rep(array $keyvalues) {
        return rdb::rep(self::get_model_name(), $keyvalues);
    }
    public static function rep_many(array $keyvalues_list) {
        return rdb::rep_many(self::get_model_name(), $keyvalues_list);
    }
    
    public static function inc(array $keyvalues, array $conditions) {
        return rdb::inc(self::get_model_name(), $keyvalues, $conditions);
    }
    public static function inc_by_id(array $keyvalues, $id) {
        return rdb::inc_by_id(self::get_model_name(), $keyvalues, $id);
    }
    public static function inc_by_ids(array $keyvalues, array $ids) {
        return rdb::inc_by_ids(self::get_model_name(), $keyvalues, $ids);
    }
    
    public static function set_and_inc(array $sets, array $incs, array $conditions) {
        return rdb::set_and_inc(self::get_model_name(), $sets, $incs, $conditions);
    }
    public static function set_and_inc_by_id(array $sets, array $incs, $id) {
        return rdb::set_and_inc_by_id(self::get_model_name(), $sets, $incs, $id);
    }
    public static function set_and_inc_by_ids(array $sets, array $incs, array $ids) {
        return rdb::set_and_inc_by_ids(self::get_model_name(), $sets, $incs, $ids);
    }
    
    public static function dec(array $keyvalues, array $conditions) {
        return rdb::dec(self::get_model_name(), $keyvalues, $conditions);
    }
    public static function dec_by_id(array $keyvalues, $id) {
        return rdb::dec_by_id(self::get_model_name(), $keyvalues, $id);
    }
    public static function dec_by_ids(array $keyvalues, array $ids) {
        return rdb::dec_by_ids(self::get_model_name(), $keyvalues, $ids);
    }
    
    public static function fetch($sql, array $args = array()) {
        $model_name = self::get_model_name();
        return self::create_models($model_name, rdb::fetch($model_name, $sql, $args));
    }
    public static function fetch_one($sql, array $args = array()) {
        $model_name = self::get_model_name();
        return self::create_model($model_name, rdb::fetch_one($model_name, $sql, $args));
    }
    public static function modify($sql, array $args = array()) {
        return rdb::modify(self::get_model_name(), $sql, $args);
    }
    public static function create($sql, array $args = array()) {
        return rdb::create(self::get_model_name(), $sql, $args);
    }
    public static function remove($sql, array $args = array()) {
        return rdb::remove(self::get_model_name(), $sql, $args);
    }
    public static function change($sql, array $args = array()) {
        return rdb::change(self::get_model_name(), $sql, $args);
    }
    
    protected static function get_model_name() {
        return strip_suffix(get_called_class());
    }
    protected static function create_model($model_name, $record) {
        if ($record === null) {
            return null;
        }
        return self::do_create_model($model_name, $record);
    }
    protected static function create_models($model_name, $records) {
        if ($records === null) {
            return null;
        }
        $models = array();
        foreach ($records as $model_id => $record) {
            $models[$model_id] = self::do_create_model($model_name, $record);
        }
        return $models;
    }
    protected static function do_create_model($model_name, array $record) {
        $class_name = $model_name . '_model';
        $model = new $class_name(false);
        $model->_model_name = $model_name;
        foreach ($record as $prop_name => $prop_value) {
            $model->_current_props[$prop_name] = $prop_value;
        }
        $model->_original_props = $model->_current_props;
        return $model;
    }
}
