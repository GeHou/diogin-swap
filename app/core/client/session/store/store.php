<?php
/**
 * 会话存储源
 *
 * @copyright Copyright (c) 2009-2012 Jingcheng Zhang <diogin@gmail.com>. All rights reserved.
 * @license   See "LICENSE" file bundled with this distribution.
 */

namespace swap;

/**
 * [内部][类型] 会话存储源
 */
abstract class session_store {
    abstract public function __construct($dsn);
    abstract public function is_role_id_online($role_id);
    abstract public function online_count();
    abstract public function clean();
    abstract public function fetch($sid);
    abstract public function modify($sid, $expire_time, $last_active, $role_id, $role_secret, array $role_vars);
    abstract public function create($sid, $expire_time, $last_active, $role_id, $role_secret, array $role_vars);
    abstract public function remove($sid);
}

/**
 * [内部][实体] 会话存储源池
 */
class session_store_pool {
    public static function get_session_store($dsn) {
        static $session_stores = array();
        $dsn_is_array = is_array($dsn);
        $dsn_as_key = $dsn_is_array ? implode('', $dsn) : $dsn;
        if (!array_key_exists($dsn_as_key, $session_stores)) {
            list($session_store_type, ) = explode('://', $dsn_is_array ? current($dsn) : $dsn, 2);
            $session_store_class = __NAMESPACE__ . '\\' . $session_store_type . '_session_store';
            $session_store = new $session_store_class($dsn);
            $session_stores[$dsn_as_key] = $session_store;
        }
        return $session_stores[$dsn_as_key];
    }
}
