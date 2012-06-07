<?php
/**
 * 来访者，是对客户端用户的抽象。来访者请求服务，并且带回响应
 *
 * @copyright Copyright (c) 2009-2012 Jingcheng Zhang <diogin@gmail.com>. All rights reserved.
 * @license   See "LICENSE" file bundled with this distribution.
 */

namespace swap;

visitor::__init__();

// [实体] 来访者
class visitor {
    public static function g_has($key) {
        return self::r_has('gets', $key);
    }
    public static function g_int($key, $default_value = 0) {
        return self::r_int('gets', $key, $default_value);
    }
    public static function g_str($key, $default_value = '') {
        return self::r_str('gets', $key, $default_value);
    }
    public static function g_arr($key, array $default_value = array()) {
        return self::r_arr('gets', $key, $default_value);
    }
    public static function g($key = '', $default_value = null) {
        return self::r('gets', $key, $default_value);
    }
    public static function p_has($key) {
        return self::r_has('posts', $key);
    }
    public static function p_int($key, $default_value = 0) {
        return self::r_int('posts', $key, $default_value);
    }
    public static function p_str($key, $default_value = '') {
        return self::r_str('posts', $key, $default_value);
    }
    public static function p_arr($key, array $default_value = array()) {
        return self::r_arr('posts', $key, $default_value);
    }
    public static function p($key = '', $default_value = null) {
        return self::r('posts', $key, $default_value);
    }
    public static function c_has($key) {
        return self::r_has('cookies', $key);
    }
    public static function c_int($key, $default_value = 0) {
        return self::r_int('cookies', $key, $default_value);
    }
    public static function c_str($key, $default_value = '') {
        return self::r_str('cookies', $key, $default_value);
    }
    public static function c_arr($key, array $default_value = array()) {
        return self::r_arr('cookies', $key, $default_value);
    }
    public static function c($key = '', $default_value = null) {
        return self::r('cookies', $key, $default_value);
    }
    public static function has_file() {
        return self::$request['files'] !== array();
    }
    public static function f_has($key) {
        return array_key_exists($key, self::$request['files']);
    }
    public static function f($key = '', $default_value = null) {
        return $key === '' ? self::$request['files'] : (array_key_exists($key, self::$request['files']) ? self::$request['files'][$key] : $default_value);
    }
    public static function request_method() {
        return $_SERVER['REQUEST_METHOD'];
    }
    public static function is_get() {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }
    public static function is_post() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    public static function is_put() {
        return $_SERVER['REQUEST_METHOD'] === 'PUT';
    }
    public static function is_delete() {
        return $_SERVER['REQUEST_METHOD'] === 'DELETE';
    }
    public static function is_head() {
        return $_SERVER['REQUEST_METHOD'] === 'HEAD';
    }
    public static function request_uri() {
        return self::$request['request_uri'];
    }
    public static function request_host() {
        return isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
    }
    public static function has_referer() {
        return isset($_SERVER['HTTP_REFERER']);
    }
    public static function referer($default_value = '') {
        return self::has_referer() ? $_SERVER['HTTP_REFERER'] : $default_value;
    }
    public static function is_xhr() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }
    public static function is_https_request() {
        return isset($_SERVER['HTTPS']) || $_SERVER['SERVER_PORT'] === '443';
    }
    public static function agent() {
        return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    }
    public static function is_ie() {
        return stripos(self::agent(), 'MSIE') !== false;
    }
    public static function is_ie6() {
        // Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)
        return stripos(self::agent(), 'MSIE 6.0') !== false;
    }
    public static function is_ie7() {
        return stripos(self::agent(), 'MSIE 7.0') !== false;
    }
    public static function is_ie8() {
        return stripos(self::agent(), 'MSIE 8.0') !== false;
    }
    public static function is_ie9() {
        return stripos(self::agent(), 'MSIE 9.0') !== false;
    }
    public static function is_firefox() {
        // Firefox: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13
        return stripos(self::agent(), 'Firefox') !== false;
    }
    public static function is_webkit() {
        return stripos(self::agent(), 'AppleWebKit') !== false;
    }
    public static function is_chrome() {
        // Chrome: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/534.10 (KHTML, like Gecko) Chrome/8.0.552.224 Safari/534.10
        return stripos(self::agent(), 'Chrome') !== false;
    }
    public static function is_safari() {
        // Safari: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/533.19.4 (KHTML, like Gecko) Version/5.0.3 Safari/533.19.4
        return self::is_webkit() && !self::is_chrome();
    }
    public static function is_opera() {
        // Opera: Opera/9.80 (Windows NT 5.1; U; en) Presto/2.7.62 Version/11.00
        return stripos(self::agent(), 'Opera') !== false;
    }
    public static function ip() {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $clients_list = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if (strpos($clients_list, ', ') !== false) {
                $clients = explode(', ', $clients_list);
                $ip = $clients[0];
            } else {
                $ip = $clients_list;
            }
            if (ip_value::is_valid_ip($ip)) {
                return $ip;
            }
        }
        return $_SERVER['REMOTE_ADDR'];
    }
    public static function port() {
        return (int)$_SERVER['REMOTE_PORT'];
    }
    public static function get_target() {
        return self::$request['target'];
    }
    public static function get_target_name() {
        return self::$request['target']->get_target_name();
    }
    public static function get_target_path() {
        return self::$request['target']->get_target_path();
    }
    public static function get_controller_name() {
        return self::$request['target']->get_controller_name();
    }
    public static function get_action_name() {
        return self::$request['target']->get_action_name();
    }
    public static function get_path_prefix() {
        return self::$request['path_prefix'];
    }
    
    public static function set_status($code, $phrase) {
        self::$response['status'] = array(
            'code' => $code,
            'phrase' => $phrase,
        );
    }
    public static function set_content_type($content_type) {
        self::$response['content_type'] = $content_type;
    }
    public static function set_content($content) {
        self::$response['content'] = $content;
    }
    public static function add_content($content) {
        self::$response['content'] .= $content;
    }
    public static function set_header($name, $value) {
        if (strtolower($name) === 'set-cookie') {
            throw new developer_error('you should use set_cookie() instead of set_header() to set cookie');
        }
        self::$response['headers'][$name] = $value;
    }
    public static function set_cookie($name, $value, $seconds = 0, $path = '/', $domain = '', $secure = false, $http_only = false) {
        if ($seconds !== 0) {
            $seconds = clock::get_stamp() + $seconds;
        }
        $path = self::$request['path_prefix'] . $path;
        if ($domain === '') {
            $domain = config::get('technique.visitor.cookie_domain', '');
        }
        $cookie = array('name' => $name, 'value' => $value, 'expire' => $seconds, 'path' => $path, 'domain' => $domain, 'secure' => $secure, 'http_only' => $http_only);
        self::$response['cookies'][] = $cookie;
    }
    public static function del_cookie($name, $path = '/', $domain = '', $secure = false, $http_only = false) {
        self::set_cookie($name, false, -86400 * 365 * 10, $path, $domain, $secure, $http_only);
    }
    public static function cache_seconds($seconds) {
        self::set_header('Cache-Control', 'max-age=' . (int)$seconds);
    }
    public static function no_cache() {
        self::set_header('Cache-Control', 'no-cache');
    }
    public static function redirect_to_url($url) {
        self::set_header('Location', $url);
    }
    
    public static function is_anonymous() {
        return self::$is_anonymous;
    }
    public static function set_role($role_name, $role_id, $login_seconds, array $role_vars) {
        $role_sid = session_manager::new_sid();
        $login_seconds = (int)$login_seconds;
        $session = session_manager::create_session($role_name, $role_id, $role_sid, $login_seconds, $role_vars);
        $role_config = self::$configs['roles'][$role_name];
        $role_sid_name = $role_config['sid_name'];
        self::set_cookie($role_sid_name, $role_sid, $login_seconds, '/', '', false, true);
        self::$session_records[$role_name]['sid'] = $role_sid;
        self::$session_records[$role_name]['session'] = $session;
    }
    public static function del_role($role_name) {
        if (!self::has_role($role_name)) {
            return;
        }
        $role_sid = self::$session_records[$role_name]['sid'];
        session_manager::remove_session($role_name, $role_sid);
        $role_config = self::$configs['roles'][$role_name];
        $role_sid_name = $role_config['sid_name'];
        self::del_cookie($role_sid_name, '/', '', false, true);
        self::$session_records[$role_name]['sid'] = null;
        self::$session_records[$role_name]['session'] = null;
    }
    public static function has_role($role_name) {
        return array_key_exists($role_name, self::$session_records) && self::$session_records[$role_name]['sid'] !== null && self::$session_records[$role_name]['session'] !== null;
    }
    public static function get_sid($role_name) {
        return self::get_role_session($role_name)->get_sid();
    }
    public static function get_role_id($role_name) {
        return self::get_role_session($role_name)->get_role_id();
    }
    public static function get_role_secret($role_name) {
        return self::get_role_session($role_name)->get_role_secret();
    }
    public static function has_role_var($role_name, $key) {
        return self::get_role_session($role_name)->has($key);
    }
    public static function get_role_var($role_name, $key, $default_value = null) {
        return self::get_role_session($role_name)->get($key, $default_value);
    }
    public static function get_role_vars($role_name) {
        return self::get_role_session($role_name)->get_all();
    }
    public static function set_role_var($role_name, $key, $value) {
        self::get_role_session($role_name)->set($key, $value);
    }
    public static function set_role_vars($role_name, array $keyvalues) {
        self::get_role_session($role_name)->set_many($keyvalues);
    }
    public static function del_role_var($role_name, $key) {
        self::get_role_session($role_name)->del($key);
    }
    public static function del_role_vars($role_name) {
        self::get_role_session($role_name)->del_all();
    }
    public static function restore_role_with_sid($role_name, $role_sid) {
        $session = session_manager::fetch_session($role_name, $role_sid);
        if ($session !== null) {
            self::$is_anonymous = false;
            self::$session_records[$role_name] = array('sid' => $role_sid, 'session' => $session);
        }
    }
    
    public static function set_value($key, $value) {
        self::$values[$key] = $value;
    }
    public static function has_value($key) {
        return array_key_exists($key, self::$values);
    }
    public static function get_value($key, $default_value = null) {
        return self::has_value($key) ? self::$values[$key] : $default_value;
    }
    
    public static /* @core */ function __init__() {
        self::$configs = config::get('technique.visitor', array('cookie_domain' => '', 'roles' => array()));
        self::init_path_prefix();
        self::init_request_uri();
        self::$request['gets'] = $_GET;
        if (framework::is_php_mode()) {
            self::$request['posts'] = $_POST;
            self::$request['cookies'] = $_COOKIE;
            self::init_uploaded_files();
        }
    }
    public static /* @core */ function set_target(target $target) {
        self::$request['gets'] = array_merge(self::$request['gets'], $target->get_params());
        self::$request['target'] = $target;
    }
    public static /* @core */ function forward_cookies() {
        if (self::$response['cookies'] !== array()) {
            foreach (self::$response['cookies'] as $response_cookie) {
                $cookie_name = $response_cookie['name'];
                $cookie_value = $response_cookie['value'];
                if ($cookie_value === false) {
                    unset(self::$request['cookies'][$cookie_name]);
                } else {
                    self::$request['cookies'][$cookie_name] = $cookie_value;
                }
            }
        }
    }
    public static /* @core */ function get_response() {
        return self::$response;
    }
    public static /* @core */ function restore_roles() {
        $role_configs = self::$configs['roles'];
        $is_anonymous = true;
        foreach ($role_configs as $role_name => $role_config) {
            $role_sid = self::c_str($role_config['sid_name'], '');
            if ($role_sid === '') {
                $session_record = array('sid' => null, 'session' => null);
            } else {
                $session = session_manager::fetch_session($role_name, $role_sid);
                if ($session !== null) {
                    $is_anonymous = false;
                }
                $session_record = array('sid' => $role_sid, 'session' => $session);
            }
            self::$session_records[$role_name] = $session_record;
        }
        self::$is_anonymous = $is_anonymous;
    }
    public static /* @core */ function persist_roles() {
        foreach (self::$session_records as $session_record) {
            if ($session_record['session'] instanceof session) {
                $session_record['session']->persist();
            }
        }
    }
    
    protected static function init_path_prefix() {
        // 路径前缀可以是 ''，'/hello'，'/hello/world' 等格式。注意结尾没有斜杠
        $path_prefix = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        if ($path_prefix === '/' || $path_prefix === '.') {
            $path_prefix = '';
        }
        self::$request['path_prefix'] = $path_prefix;
    }
    protected static function init_request_uri() {
        // 请求 uri 总是以“/”开头
        self::$request['request_uri'] = '/' . ltrim(substr($_SERVER['REQUEST_URI'], strlen(self::$request['path_prefix'])), '/');
    }
    protected static function r_has($gpc_type, $key) {
        return array_key_exists($key, self::$request[$gpc_type]);
    }
    protected static function r_int($gpc_type, $key, $default_value = 0) {
        return array_key_exists($key, self::$request[$gpc_type]) && is_numeric(self::$request[$gpc_type][$key]) ? (int)self::$request[$gpc_type][$key] : $default_value;
    }
    protected static function r_str($gpc_type, $key, $default_value = '') {
        return array_key_exists($key, self::$request[$gpc_type]) && is_string(self::$request[$gpc_type][$key]) ? self::$request[$gpc_type][$key] : $default_value;
    }
    protected static function r_arr($gpc_type, $key, array $default_value = array()) {
        return array_key_exists($key, self::$request[$gpc_type]) && is_array(self::$request[$gpc_type][$key]) ? self::$request[$gpc_type][$key] : $default_value;
    }
    protected static function r($gpc_type, $key = '', $default_value = null) {
        return $key === '' ? self::$request[$gpc_type] : (array_key_exists($key, self::$request[$gpc_type]) ? self::$request[$gpc_type][$key] : $default_value);
    }
    protected static function init_uploaded_files() {
        if ($_FILES === array()) {
            return;
        }
        $files = array();
        $default_mime_type = 'application/octet-stream';
        foreach ($_FILES as $file_key => $file_info) {
            if (is_array($file_info['error'])) {
                $file = array();
                foreach ($file_info['error'] as $index => $error) {
                    $file[] = array(
                        'name' => $file_info['name'][$index],
                        'type' => isset($file_info['type'][$index]) ? $file_info['type'][$index] : $default_mime_type,
                        'size' => $file_info['size'][$index],
                        'tmp_name' => $file_info['tmp_name'][$index],
                        'error' => $error,
                    );
                }
            } else {
                if (!isset($file_info['type'])) {
                    $file_info['type'] = $default_mime_type;
                }
                $file = $file_info;
            }
            $files[$file_key] = $file;
        }
        self::$request['files'] = $files;
    }
    protected static function get_role_session($role_name) {
        $session = self::$session_records[$role_name]['session'];
        if ($session === null) {
            throw new developer_error("visitor doesn't have role '{$role_name}', cannot perform role var operations on this role");
        }
        return $session;
    }
    
    protected static $configs = array();
    protected static $request = array(
        'path_prefix' => '',
        'request_uri' => '',
        'target' => null,
        'gets' => array(),
        'posts' => array(),
        'cookies' => array(),
        'files' => array(),
    );
    protected static $response = array(
        'status' => array(
            'code' => 0,
            'phrase' => '',
        ),
        'headers' => array(),
        'cookies' => array(),
        'content_type' => 'text/html; charset=utf-8',
        'content' => '',
    );
    protected static $session_records = array();
    protected static $is_anonymous = true;
    protected static $values = array();
}
