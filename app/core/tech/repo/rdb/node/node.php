<?php
/**
 * 关系数据库节点抽象。一个节点对应一个关系数据库服务器实例
 *
 * @copyright Copyright (c) 2009-2012 Jingcheng Zhang <diogin@gmail.com>. All rights reserved.
 * @license   See "LICENSE" file bundled with this distribution.
 */

namespace swap;

// [类型] 关系数据库节点
abstract class rdb_node {
    abstract public function get($table_name, array $keyvalues, array $order_limit = array(array(), 0, 0));
    abstract public function get_one($table_name, array $keyvalues);
    abstract public function get_where($table_name, $where, array $args = array(), array $order_limit = array(array(), 0, 0));
    abstract public function get_by_id($table_name, $id);
    abstract public function get_by_ids($table_name, array $ids, array $order_limit = array(array(), 0, 0));
    abstract public function get_in($table_name, $field_name, array $values, array $order_limit = array(array(), 0, 0));
    abstract public function get_all($table_name, array $order_limit = array(array(), 0, 0));
    
    abstract public function get_for_fields($field_names, $table_name, array $keyvalues, array $order_limit = array(array(), 0, 0));
    abstract public function get_one_for_fields($field_names, $table_name, array $keyvalues);
    abstract public function get_where_for_fields($field_names, $table_name, $where, array $args = array(), array $order_limit = array(array(), 0, 0));
    abstract public function get_by_id_for_fields($field_names, $table_name, $id);
    abstract public function get_by_ids_for_fields($field_names, $table_name, array $ids, array $order_limit = array(array(), 0, 0));
    abstract public function get_in_for_fields($field_names, $table_name, $field_name, array $values, array $order_limit = array(array(), 0, 0));
    abstract public function get_all_for_fields($field_names, $table_name, array $order_limit = array(array(), 0, 0));
    
    abstract public function count($table_name, array $keyvalues);
    abstract public function count_where($table_name, $where, array $args = array());
    abstract public function count_by_ids($table_name, array $ids);
    abstract public function count_in($table_name, $field_name, array $values);
    abstract public function count_all($table_name);
    
    abstract public function pager($table_name, array $keyvalues, array $order_limit = array(array(), 0, 0));
    abstract public function pager_where($table_name, $where, array $args = array(), array $order_limit = array(array(), 0, 0));
    abstract public function pager_by_ids($table_name, array $ids, array $order_limit = array(array(), 0, 0));
    abstract public function pager_in($table_name, $field_name, array $values, array $order_limit = array(array(), 0, 0));
    abstract public function pager_all($table_name, array $order_limit = array(array(), 0, 0));
    
    abstract public function pager_with_count($record_count, $table_name, array $keyvalues, array $order_limit = array(array(), 0, 0));
    abstract public function pager_where_with_count($record_count, $table_name, $where, array $args = array(), array $order_limit = array(array(), 0, 0));
    abstract public function pager_by_ids_with_count($record_count, $table_name, array $ids, array $order_limit = array(array(), 0, 0));
    abstract public function pager_in_with_count($record_count, $table_name, $field_name, array $values, array $order_limit = array(array(), 0, 0));
    abstract public function pager_all_with_count($record_count, $table_name, array $order_limit = array(array(), 0, 0));
    
    abstract public function set($table_name, array $keyvalues, array $conditions);
    abstract public function set_where($table_name, array $keyvalues, $where, array $args = array());
    abstract public function set_by_id($table_name, array $keyvalues, $id);
    abstract public function set_by_ids($table_name, array $keyvalues, array $ids);
    abstract public function set_all($table_name, array $keyvalues);
    
    abstract public function add($table_name, array $keyvalues);
    abstract public function add_many($table_name, array $keyvalues_list);
    
    abstract public function del($table_name, array $keyvalues);
    abstract public function del_where($table_name, $where, array $args = array());
    abstract public function del_by_id($table_name, $id);
    abstract public function del_by_ids($table_name, array $ids);
    
    abstract public function rep($table_name, array $keyvalues);
    abstract public function rep_many($table_name, array $keyvalues_list);
    
    abstract public function inc($table_name, array $keyvalues, array $conditions);
    abstract public function inc_by_id($table_name, array $keyvalues, $id);
    abstract public function inc_by_ids($table_name, array $keyvalues, array $ids);
    
    abstract public function set_and_inc($table_name, array $sets, array $incs, array $conditions);
    abstract public function set_and_inc_by_id($table_name, array $sets, array $incs, $id);
    abstract public function set_and_inc_by_ids($table_name, array $sets, array $incs, array $ids);
    
    abstract public function dec($table_name, array $keyvalues, array $conditions);
    abstract public function dec_by_id($table_name, array $keyvalues, $id);
    abstract public function dec_by_ids($table_name, array $keyvalues, array $ids);
    
    abstract public function select($sql, array $args = array());
    abstract public function select_one($sql, array $args = array());
    abstract public function update($sql, array $args = array());
    abstract public function insert($sql, array $args = array());
    abstract public function delete($sql, array $args = array());
    abstract public function replace($sql, array $args = array());
    
    abstract public function begin();
    abstract public function commit();
    abstract public function rollback();
    
    public function __construct(rdb_conn $conn) {
        $this->conn = $conn;
    }
    
    protected $conn = null;
}
// [实体] 关系数据库节点池
class rdb_node_pool {
    public static function get_rdb_node($node_mode, $dsn) {
        static $rdb_nodes = array(
            'master' => array(),
            'slave' => array(),
        );
        if (!isset($rdb_nodes[$node_mode][$dsn])) {
            list($rdb_type, $rdb_conn) = rdb_conn_pool::get_rdb_type_and_conn_from_dsn($dsn);
            $rdb_node_class = 'swap\\' . $rdb_type . '_' . $node_mode . '_rdb_node';
            $rdb_node = new $rdb_node_class($rdb_conn);
            $rdb_nodes[$node_mode][$dsn] = $rdb_node;
        }
        return $rdb_nodes[$node_mode][$dsn];
    }
}
// [内部][实体] 关系数据库节点工具
abstract class rdb_node_util {
    public static function restrict_order_limit(array &$order_limit, $record_count) {
        $page_size = $order_limit[2];
        $page_count = ceil($record_count / $page_size);
        if ($page_count <= 0) {
            $page_count = 1;
        }
        $page = $order_limit[1];
        if ($page > $page_count) {
            $page = $page_count;
        } else if ($page < 1) {
            $page = 1;
        }
        $order_limit[1] = $page;
    }
    public static function build_pager_data($record_count, array $order_limit = array(array(), 0, 0)) {
        $page_size = $order_limit[2];
        if ($page_size === 0) {
            throw new developer_error('page_size cannot be zero');
        }
        if ($record_count <= 0) {
            $page_count = 1;
            $current_page = 1;
        } else {
            $page_count = ceil($record_count / $page_size);
            $current_page = $order_limit[1];
        }
        return array('record_count' => $record_count, 'page_count' => $page_count, 'current_page' => $current_page, 'page_size' => $page_size);
    }
    public static function localize_where($where) {
        return $where;
        /*
        $tokens = rdb_sql_lexer::tokenize_where($where);
        $values = array();
        foreach ($tokens as $token) {
            list($key, $value) = each($token);
            if ($key === rdb_sql_lexer::token_word) {
                if (strpos($value, '.') !== false) {
                    list($table_name, $field_name) = explode('.', $value, 2);
                    $value = static::build_table_name($table_name) . '.' . static::build_field_name($field_name);
                } else {
                    $value = static::build_field_name($value);
                }
            }
            $values[] = $value;
        }
        return static::join_token_values($values);
        */
    }
}
// [内部][实体] 关系数据库 SQL 解析器
class rdb_sql_lexer {
    # field_name, table_name.field_name
    const token_word = 0;
    # (
    const token_left = 1;
    # )
    const token_right = 2;
    # >
    const token_more = 3;
    # <
    const token_less = 4;
    # &&
    const token_and = 5;
    # ||
    const token_or = 6;
    # !
    const token_bang = 7;
    # ?
    const token_holder = 8;
    # =
    const token_equal = 9;
    # >=
    const token_more_equal = 10;
    # <=
    const token_less_equal = 11;
    # !=
    const token_not_equal = 12;
    # &
    const token_amp = 13;
    # |
    const token_bar = 14;
    # +
    const token_add = 15;
    # -
    const token_sub = 16;
    # *
    const token_mul = 17;
    # /
    const token_div = 18;
    # ,
    const token_comma = 19;
    # 12, 0.123
    const token_number = 20;
    
    public static function tokenize_where($where) {
        // a > 1 + 2 AND !b || c<=d.e && f != ? || (!g OR h > ?)
        $tokens = array();
        $state  = 0;
        for ($pos = 0, $len = strlen($where); $pos <= $len; $pos++) {
            $char = $pos === $len ? null : $where[$pos];
            // start
            if ($state === 0) {
                if ($char === null || $char === ' ' || $char === "\t" || $char === "\r" || $char === "\n") {
                    continue;
                } else if ($char >= 'a' && $char <= 'z') {
                    $anchor = $pos;
                    $state = 1;
                } else if ($char >= 'A' && $char <= 'Z') {
                    $anchor = $pos;
                    $state = 7;
                } else if ($char === '!') {
                    $state = 2;
                } else if ($char === '&') {
                    $state = 3;
                } else if ($char === '|') {
                    $state = 4;
                } else if ($char === '<') {
                    $state = 5;
                } else if ($char === '>') {
                    $state = 6;
                } else if ($char === '(') {
                    $tokens[] = array(self::token_left => '(');
                    $state = 0;
                } else if ($char === ')') {
                    $tokens[] = array(self::token_right => ')');
                    $state = 0;
                } else if ($char === '=') {
                    $tokens[] = array(self::token_equal => '=');
                    $state = 0;
                } else if ($char === '?') {
                    $tokens[] = array(self::token_holder => '?');
                    $state = 0;
                } else {
                    throw new developer_error("sql_lexer error: unknown char '" . $char . "'\n");
                }
            }
            // in_word
            else if ($state === 1) {
                if (!($char >= 'a' && $char <= 'z' || $char === '.' || $char === '_')) {
                    $tokens[] = array(self::token_word => substr($where, $anchor, $pos - $anchor));
                    $pos--;
                    $state = 0;
                }
            }
            // in_bang
            else if ($state === 2) {
                if ($char === '=') {
                    $tokens[] = array(self::token_not_equal => '!=');
                } else {
                    $tokens[] = array(self::token_bang => 'NOT');
                    $pos--;
                }
                $state = 0;
            }
            // in_amp
            else if ($state === 3) {
                if ($char === '&') {
                    $tokens[] = array(self::token_and => 'AND');
                } else {
                    $tokens[] = array(self::token_amp => '&');
                    $pos--;
                }
                $state = 0;
            }
            // in_bar
            else if ($state === 4) {
                if ($char === '|') {
                    $tokens[] = array(self::token_or => 'OR');
                } else {
                    $tokens[] = array(self::token_bar => '|');
                    $pos--;
                }
                $state = 0;
            }
            // in_less
            else if ($state === 5) {
                if ($char === '=') {
                    $tokens[] = array(self::token_less_equal => '<=');
                } else if ($char === '>') {
                    $tokens[] = array(self::token_not_equal => '!=');
                } else {
                    $tokens[] = array(self::token_less => '<');
                    $pos--;
                }
                $state = 0;
            }
            // in_more
            else if ($state === 6) {
                if ($char === '=') {
                    $tokens[] = array(self::token_more_equal => '>=');
                } else {
                    $tokens[] = array(self::token_more => '>');
                    $pos--;
                }
                $state = 0;
            }
            // in_logic
            else if ($state === 7) {
                if (!($char >= 'A' && $char <= 'Z')) {
                    $logic = substr($where, $anchor, $pos - $anchor);
                    if (!isset(self::$logic_tokens[$logic])) {
                        throw new developer_error("unknown logic operator: '{$logic}'");
                    }
                    $tokens[] = array(self::$logic_tokens[$logic] => $logic);
                    $pos--;
                    $state = 0;
                }
            }
            else {
                throw new developer_error('unknown state: ' . $state . "\n");
            }
        }
        return $tokens;
    }
    public static function tokenize_equal_list($equal_list) {
        // topic_num = topic_num + ?, today_num = today_num + ?, last_post_time = ?
        $tokens = array();
        $state = 0;
        for ($pos = 0, $len = strlen($equal_list); $pos <= $len; $pos++) {
            $char = $pos === $len ? null : $where[$pos];
        }
    }
    
    protected static $logic_tokens = array(
        'AND' => self::token_and,
        'OR'  => self::token_or,
        'NOT' => self::token_bang,
    );
}
