<?php
/**
 * 基于 Cookie 的会话存储源
 *
 * @copyright Copyright (c) 2009-2012 Jingcheng Zhang <diogin@gmail.com>. All rights reserved.
 * @license   See "LICENSE" file bundled with this distribution.
 */

namespace swap;

cookie_session_store::__init__();

/**
 * [内部][类型] Cookie 会话存储源
 */
class cookie_session_store extends session_store {
    public function __construct($dsn) {
        $url_parts = parse_url($dsn);
        extract($url_parts, EXTR_SKIP);
        list($record_config, $hmac_config) = explode('&', $query);
        list(, $this->record_key) = explode('=', $record_config);
        list(, $this->hmac_key) = explode('=', $hmac_config);
    }
    public function is_role_id_online($role_id) {
        throw new developer_error(__CLASS__ . " does not support method: '" . __FUNCTION__ . "'");
    }
    public function online_count() {
        throw new developer_error(__CLASS__ . " does not support method: '" . __FUNCTION__ . "'");
    }
    public function clean() {
        // 啥也不做
    }
    public function fetch($sid) {
        $encoded_record = visitor::c_str($this->record_key);
        $hmac = visitor::c_str($this->hmac_key);
        if ($hmac !== self::hmac($sid . $encoded_record)) {
            return null;
        }
        $record = unserialize(crypt::decrypt(base64_decode($encoded_record), self::$secret_key));
        if (is_debug) {
            debug::save('session', 'cookie get: key -> ' . $sid . ', record -> ' . var_export($record, true) . ', hmac -> ' . var_export($hmac, true));
        }
        $record['sid'] = $sid;
        return $record;
    }
    public function modify($sid, $expire_time, $last_active, $role_id, $role_secret, array $role_vars) {
        $this->create($sid, $expire_time, $last_active, $role_id, $role_secret, $role_vars);
    }
    public function create($sid, $expire_time, $last_active, $role_id, $role_secret, array $role_vars) {
        $record = array(
            'expire_time' => $expire_time,
            'last_active' => $last_active,
            'role_id' => $role_id,
            'role_secret' => $role_secret,
            'role_vars' => $role_vars,
        );
        $encoded_record = base64_encode(crypt::encrypt(serialize($record), self::$secret_key));
        $hmac = self::hmac($sid . $encoded_record);
        $seconds = $expire_time - $last_active;
        visitor::set_cookie($this->record_key, $encoded_record, $seconds);
        visitor::set_cookie($this->hmac_key, $hmac, $seconds);
        if (is_debug) {
            debug::save('session', 'cookie set: key -> ' . $sid . ', record -> ' . var_export($record, true) . ', hmac -> ' . var_export($hmac, true));
        }
    }
    public function remove($sid) {
        visitor::del_cookie($this->record_key);
        visitor::del_cookie($this->hmac_key);
        if (is_debug) {
            debug::save('session', 'cookie del: key -> ' . $sid);
        }
    }
    
    protected $record_key = '';
    protected $hmac_key = '';
    
    public static function __init__() {
        self::$secret_key = config::get('technique.secret_key');
    }
    
    protected static function hmac($msg) {
        return sha1($msg . self::$secret_key);
    }
    
    protected static $secret_key = '';
}
