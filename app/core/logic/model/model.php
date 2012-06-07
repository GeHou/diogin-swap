<?php
/**
 * 基于实体联系模型的领域模型
 *
 * @copyright Copyright (c) 2009-2012 Jingcheng Zhang <diogin@gmail.com>. All rights reserved.
 * @license   See "LICENSE" file bundled with this distribution.
 */

namespace swap;

/**
 * [内部][函数] 去除最后一个分隔符及其后面的字符串后缀
 */
function strip_suffix($str, $separator = '_') {
    $last_pos = strrpos($str, $separator);
    if ($last_pos !== false) {
        $str = substr($str, 0, $last_pos);
    }
    return $str;
}

/**
 * [类型] 模型接口
 */
interface model_api {
    
    function __construct($is_new = true);
    function __get($key);
    function __set($key, $value);
    function __isset($key);
    function get_props();
    function set_props(array $props);
    function add_props(array $props);
    function save();
    
    static function get(array $keyvalues, array $order_limit = array(array(), 0, 0));
    static function get_one(array $keyvalues);
    static function get_by_id($id);
    static function get_by_ids(array $ids, array $order_limit = array(array(), 0, 0));
    static function get_in($field_name, array $values, array $order_limit = array(array(), 0, 0));
}
/**
 * [类型] 模型
 */
abstract class model implements model_api, html_escapable {}

/**
 * [实体] 关联关系绑定器
 */
class binder {
    public static function bind($model_arg, $assoc_type_name, $assoc_model_name /*, ... */) {
        if ($model_arg === null) {
            return;
        }
        if (is_array($model_arg)) {
            $model_type = 'multiple_models';
            $model = current($model_arg);
        } else {
            $model_type = 'single_model';
            $model = $model_arg;
        }
        if (!$model instanceof model) {
            throw new developer_error('bad model, expect object, but get ' . gettype($model));
        }
        $binder = 'bind_' . $model_type . '_with_assoc_model_of_' . $assoc_type_name;
        $assoc_class_name = $assoc_model_name . '_model';
        $model_class_name = get_class($model);
        $model_name = strip_suffix($model_class_name);
        $func_args = func_get_args();
        switch ($assoc_type_name) {
            case 'points_to':
            case 'belongs_to':
            case 'has_one': {
                $refer_field_name = array_key_exists(3, $func_args) ? $func_args[3] : $assoc_model_name . '_id';
                $as_field_name = array_key_exists(4, $func_args) ? $func_args[4] : $assoc_model_name;
                self::$binder($model_arg, $assoc_class_name, $refer_field_name, $as_field_name);
                break;
            }
            case 'has_many': {
                $order_limit = array_key_exists(3, $func_args) ? $func_args[3] : 0;
                $refer_field_name = array_key_exists(4, $func_args) ? $func_args[4] : $assoc_model_name . '_id';
                $as_field_name = array_key_exists(5, $func_args) ? $func_args[5] : $assoc_model_name;
                self::$binder($model_arg, $assoc_class_name, $order_limit, $refer_field_name, $as_field_name);
                break;
            }
            case 'many_many': {
                $through = $func_args[3];
                $through_field_names = array_key_exists(4, $func_args) ? $func_args[4] : array($model_name . '_id', $assoc_model_name . '_id');
                $as_field_name = array_key_exists(5, $func_args) ? $func_args[5] : $through[0];
                self::$binder($model_arg, $assoc_class_name, $through, $through_field_names, $as_field_name);
                break;
            }
            default: {
                throw new developer_error('未知的关联类型：' . $assoc_type_name);
            }
        }
    }
    
    // binder::bind($user, 'points_to', 'tweet', 'last_tweet_id', 'last_tweet');
    protected static function bind_single_model_with_assoc_model_of_points_to(model $model, $assoc_class_name, $refer_field_name, $as_field_name) {
        self::bind_single_model_with_assoc_model_of_belongs_to($model, $assoc_class_name, $refer_field_name, $as_field_name);
    }
    // binder::bind($users, 'points_to', 'tweet', 'last_tweet_id', 'last_tweet');
    protected static function bind_multiple_models_with_assoc_model_of_points_to(array &$models, $assoc_class_name, $refer_field_name, $as_field_name) {
        self::bind_multiple_models_with_assoc_model_of_belongs_to($models, $assoc_class_name, $refer_field_name, $as_field_name);
    }
    // binder::bind($comment, 'belongs_to', 'post', 'post_id', 'post');
    protected static function bind_single_model_with_assoc_model_of_belongs_to(model $model, $assoc_class_name, $refer_field_name, $as_field_name) {
        $model->$as_field_name = $assoc_class_name::get_by_id($model->$refer_field_name);
    }
    // binder::bind($comments, 'belongs_to', 'post', 'post_id', 'post');
    protected static function bind_multiple_models_with_assoc_model_of_belongs_to(array &$models, $assoc_class_name, $refer_field_name, $as_field_name) {
        self::init_field_value_for($models, $as_field_name, null);
        $assoc_model_ids = self::get_assoc_model_ids_from($models, $refer_field_name);
        $assoc_models = $assoc_class_name::get_by_ids($assoc_model_ids);
        if ($assoc_models === null) {
            return;
        }
        foreach ($models as &$model) {
            $assoc_model_id = $model->$refer_field_name;
            if (array_key_exists($assoc_model_id, $assoc_models)) {
                $model->$as_field_name = $assoc_models[$assoc_model_id];
            }
        }
        unset($model);
        reset($models);
    }
    // binder::bind($user, 'has_one', 'info', 'user_id', 'info');
    protected static function bind_single_model_with_assoc_model_of_has_one(model $model, $assoc_class_name, $refer_field_name, $as_field_name) {
        $model->$as_field_name = $assoc_class_name::get_one(array($refer_field_name => $model->id));
    }
    // binder::bind($users, 'has_one', 'info', 'user_id', 'info');
    protected static function bind_multiple_models_with_assoc_model_of_has_one(array &$models, $assoc_class_name, $refer_field_name, $as_field_name) {
        self::init_field_value_for($models, $as_field_name, null);
        $model_ids = self::get_model_ids_from($models);
        $assoc_models = $assoc_class_name::get_in($refer_field_name, $model_ids);
        if ($assoc_models === null) {
            return;
        }
        foreach ($assoc_models as $assoc_model) {
            $model_id = $assoc_model->$refer_field_name;
            $models[$model_id]->$as_field_name = $assoc_model;
        }
    }
    // binder::bind($user, 'has_many', 'comment', 0, 'user_id', 'comments');
    protected static function bind_single_model_with_assoc_model_of_has_many(model $model, $assoc_class_name, $order_limit, $refer_field_name, $as_field_name) {
        $assoc_models = $assoc_class_name::get(array($refer_field_name => $model->id), $order_limit === 0 ? array(array(), 0, 0) : $order_limit);
        $model->$as_field_name = $assoc_models === null ? array() : $assoc_models;
    }
    // binder::bind($users, 'has_many', 'comment', 0, 'user_id', 'comments');
    protected static function bind_multiple_models_with_assoc_model_of_has_many(array &$models, $assoc_class_name, $order_limit, $refer_field_name, $as_field_name) {
        if ($order_limit === 0) {
            self::init_field_value_for($models, $as_field_name, array());
            $model_ids = self::get_model_ids_from($models);
            $assoc_models = $assoc_class_name::get_in($refer_field_name, $model_ids);
            if ($assoc_models === null) {
                return;
            }
            $model_id_to_assoc_models = array();
            foreach ($assoc_models as $assoc_model) {
                $model_id = $assoc_model->$refer_field_name;
                if (!isset($model_id_to_assoc_models[$model_id])) {
                    $model_id_to_assoc_models[$model_id] = array();
                }
                $model_id_to_assoc_models[$model_id][$assoc_model->id] = $assoc_model;
            }
            foreach ($model_id_to_assoc_models as $model_id => $assoc_models) {
                $models[$model_id]->$as_field_name = $assoc_models;
            }
        } else {
            foreach ($models as $model) {
                self::bind_single_model_with_assoc_model_of_has_many($model, $assoc_class_name, $order_limit, $refer_field_name, $as_field_name);
            }
            reset($models);
        }
    }
    // binder::bind($user, 'many_many', 'board', array('board_manager', 0), array('user_id', 'board_id'), 'board_managers');
    // binder::bind($user, 'many_many', 'user', array('follow', 0), array('follower_id', 'followee_id'), 'stars');
    protected static function bind_single_model_with_assoc_model_of_many_many(model $model, $assoc_class_name, array $through, array $through_field_names, $as_field_name) {
        $model->$as_field_name = array();
        list($through_model_name, $order_limit) = $through;
        $through_class_name = $through_model_name . '_model';
        list($from_field_name, $to_field_name) = $through_field_names;
        $model_id = $model->id;
        $through_models = $through_class_name::get(array($from_field_name => $model_id), $order_limit === 0 ? array(array(), 0, 0) : $order_limit);
        if ($through_models === null) {
            return;
        }
        $assoc_model_ids = array();
        foreach ($through_models as $through_model) {
            $assoc_model_ids[] = $through_model->$to_field_name;
        }
        $assoc_models = $assoc_class_name::get_by_ids($assoc_model_ids);
        if ($assoc_models !== null) {
            $model->$as_field_name = $assoc_models;
        }
    }
    // binder::bind($users, 'many_many', 'board', array('board_manager', 0), array('user_id', 'board_id'), 'board_managers');
    // binder::bind($users, 'many_many', 'user', array('follow', 0), array('follower_id', 'followee_id'), 'stars');
    protected static function bind_multiple_models_with_assoc_model_of_many_many(array &$models, $assoc_class_name, array $through, array $through_field_names, $as_field_name) {
        list($through_model_name, $order_limit) = $through;
        if ($order_limit === 0) {
            self::init_field_value_for($models, $as_field_name, array());
            $through_class_name = $through_model_name . '_model';
            list($from_field_name, $to_field_name) = $through_field_names;
            $model_ids = self::get_model_ids_from($models);
            $through_models = $through_class_name::get_in($from_field_name, $model_ids);
            if ($through_models === null) {
                return;
            }
            $assoc_model_ids = array();
            foreach ($through_models as $through_model) {
                $assoc_model_ids[] = $through_model->$to_field_name;
            }
            $assoc_models = $assoc_class_name::get_by_ids($assoc_model_ids);
            if ($assoc_models === null) {
                return;
            }
            $model_id_to_assoc_models = array();
            foreach ($through_models as $through_model) {
                $model_id = $through_model->$from_field_name;
                $assoc_model_id = $through_model->$to_field_name;
                if (!isset($assoc_models[$assoc_model_id])) {
                    continue;
                }
                if (!isset($model_id_to_assoc_models[$model_id])) {
                    $model_id_to_assoc_models[$model_id] = array();
                }
                $model_id_to_assoc_models[$model_id][$assoc_model_id] = $assoc_models[$assoc_model_id];
            }
            foreach ($model_id_to_assoc_models as $model_id => $assoc_models) {
                $models[$model_id]->$as_field_name = $assoc_models;
            }
        } else {
            foreach ($models as $model) {
                self::bind_single_model_with_assoc_model_of_many_many($model, $assoc_class_name, $through, $through_field_names, $as_field_name);
            }
            reset($models);
        }
    }
    protected static function get_model_ids_from(array $models) {
        $model_ids = array();
        foreach ($models as $model) {
            $model_ids[] = $model->id;
        }
        return $model_ids;
    }
    protected static function get_assoc_model_ids_from(array $models, $refer_field_name) {
        $assoc_model_ids = array();
        foreach ($models as $model) {
            $assoc_model_ids[] = $model->$refer_field_name;
        }
        return array_unique($assoc_model_ids);
    }
    protected static function init_field_value_for(array &$models, $field_name, $value) {
        foreach ($models as &$model) {
            $model->$field_name = $value;
        }
        unset($model);
        reset($models);
    }
}
