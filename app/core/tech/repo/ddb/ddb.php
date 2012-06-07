<?php
/**
 * 文档数据库抽象
 *
 * @copyright Copyright (c) 2009-2012 Jingcheng Zhang <diogin@gmail.com>. All rights reserved.
 * @license   See "LICENSE" file bundled with this distribution.
 */

namespace swap;

use Mongo;
use MongoConnnectionException;

if (!extension_loaded('mongo')) throw new environment_error('cannot use ddb: mongo extension does not exist');

// [实体]
interface ddb_api {
    static function find($collection_name, array $query = array(), array $fields = array());
    static function insert($collection_name, array $document, array $options = array());
    static function batch_insert($collection_name, array $documents, array $options = array());
    static function count($collection_name, array $query = array(), $limit = 0, $skip = 0);
    static function ensure_index($collection_name, array $keys, array $options = array());
    static function drop($collection_name);
}
// [实体]
class ddb implements ddb_api {
    public static function find($collection_name, array $query = array(), array $fields = array()) {
        
    }
    public static function insert($collection_name, array $document, array $options = array()) {
        
    }
    public static function batch_insert($collection_name, array $documents, array $options = array()) {
        
    }
    public static function count($collection_name, array $query = array(), $limit = 0, $skip = 0) {
        
    }
    public static function ensure_index($collection_name, array $keys, array $options = array()) {
        
    }
    public static function drop($collection_name) {
        
    }
}
// [实体]
class ddb_conn_pool {
    public static function get_conn_from_collection_name($collection_name) {
        static $conns = array();
        if (!isset($conns[$collection_name])) {
            $conns[$collection_name] = self::get_conn_from_source_name(self::get_source_name_from_collection_name($collection_name));
        }
        return $conns[$collection_name];
    }
    public static function get_conn_from_source_name($source_name) {
        static $conns = array();
        if (!isset($conns[$source_name])) {
            $config = config::get('technique.repo.ddb.sources.' . $source_name);
            $options = array();
            if ($config['type'] === 'replica_set') {
                $options['replicaSet'] = true;
            }
            try {
                $conn = new Mongo($config['server'], $options);
                $conns[$source_name] = $conn;
            } catch (MongoConnnectionException $e) {
                throw new remote_except('cannot connect to ddb source: ' . $source_name);
            }
        }
        return $conns[$source_name];
    }
    protected static function get_source_name_from_collection_name($collection_name) {
        $source_name = config::get('technique.repo.ddb.collections.' . $collection_name, null);
        if ($source_name === null) {
            $source_name = config::get('technique.repo.ddb.collections.*');
        }
        return $source_name;
    }
}
