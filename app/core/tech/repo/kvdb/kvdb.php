<?php

namespace swap;

kvdb::__init__();

interface kvdb_api {
    static function get($store_name, $key);
    static function get_many($store_name, array $keys);
    static function set($store_name, $key, $value);
    static function set_many($store_name, array $keyvalues);
}

abstract class kvdb /* implements kvdb_api */ {

    public static function __callStatic($method_name, array $args) {
        return call_user_func_array(array(self::$kvdb, $method_name), $args);
    }
    public static function __init__() {
        /*
        $kvdb = 'swap\\' . config::get('technique.repo.kvdb.use', 'distributed') . '_kvdb';
        if (!class_exists($kvdb, true)) {
            throw new developer_error("kvdb: '{$kvdb}' does not exist");
        }
        self::$kvdb = $kvdb;
        */
    }
    
    protected static $kvdb = '';
}
