<?php
/**
 * URL 解析器和构建器
 *
 * @copyright Copyright (c) 2009-2012 Jingcheng Zhang <diogin@gmail.com>. All rights reserved.
 * @license   See "LICENSE" file bundled with this distribution.
 */

namespace swap;

url_manager::__init__();

/**
 * [实体] URL 管理器
 */
class url_manager {
    public static function build_url($target, $echo = true, $for_html = true, $is_absolute = true) {
        if (!$target instanceof target) {
            $target = new target($target);
        }
        if ($target->has_module()) {
            $target_module = $target->get_module_name();
            if (!$is_absolute && isset(self::$module_domains[$target_module])) {
                throw new developer_error($target_module . '模块有自己的独立域名，只能生成绝对URL，不能生成相对URL');
            }
        }
        $and_char = $for_html ? '&amp;' : '&';
        list($base_url, $query_char) = self::build_base_url($target, $and_char, $is_absolute, self::$rewrite_enabled);
        $use_normal_left_url = true;
        if (self::$rewrite_enabled) {
            $left_url = self::build_mapped_left_url($target);
            if ($left_url !== null) {
                $use_normal_left_url = false;
            }
        }
        if ($use_normal_left_url) {
            $left_url = self::build_normal_left_url($target, $query_char, $and_char, self::$rewrite_enabled);
        }
        $url = $base_url . $left_url;
        if ($echo) {
            echo $url;
        } else {
            return $url;
        }
    }
    public static function build_csrf_url($csrf_role, $target, $echo = true, $for_html = true, $is_absolute = true) {
        static $csrf_key = '';
        if (!$target instanceof target) {
            $target = new target($target);
        }
        if (visitor::has_role($csrf_role)) {
            $role_secret = visitor::get_role_secret($csrf_role);
            if ($csrf_key === '') {
                $csrf_key = config::get('technique.csrf_key', self::default_csrf_key);
            }
            $target->set_param($csrf_key, $role_secret);
        }
        return self::build_url($target, $echo, $for_html, $is_absolute);
    }
    public static function parse_uri($request_uri, $request_host) {
        return self::$rewrite_enabled ? self::parse_rewrited_uri($request_uri, $request_host) : self::parse_standard_uri($request_uri, $request_host);
    }
    
    /* @core */ const default_target_key = 'target';
    /* @core */ const default_csrf_key = 'role_secret';
    /* @core */ const default_controller_name = 'site';
    /* @core */ const default_action_name = 'index';
    /* @core */ const lazy_unit_pattern = '([^/]*?)';
    /* @core */ const greedy_unit_pattern = '([^/]*)';
    /* @core */ const default_version_key = 'v';
    
    public static /* @core */ function parse_cjs_uri($request_uri) {
        // 请求格式：/cjs.php?page=one-two/three&skin=four&layout=five&link=six,seven/eight&block=nine,ten/zero&v=2
        $target_name = '';
        $mark_pos = strpos($request_uri, '?');
        if ($mark_pos === false) {
            return new target($target_name);
        }
        $query_str = substr($request_uri, $mark_pos + 1);
        parse_str($query_str, $dirty_params);
        if (isset($dirty_params['page']) && is_string($dirty_params['page'])) {
            $page = $dirty_params['page'];
            if (substr_count($page, '/') === 1) {
                $has_module = false;
                if (in_string('-', $page)) {
                    list($module_name, $page) = explode('-', $page, 2);
                    if (is_identifier($module_name)) {
                        $has_module = true;
                    }
                }
                list($controller_name, $action_name) = explode('/', $page);
                if (is_identifier($controller_name) && is_identifier($action_name)) {
                    $target_name = $controller_name . '/' . $action_name;
                    if ($has_module) {
                        $target_name = $module_name . '-' . $target_name;
                    }
                }
            }
        }
        $params = array();
        foreach (array('skin', 'layout') as $arg_name) {
            if (isset($dirty_params[$arg_name]) && is_string($dirty_params[$arg_name])) {
                $arg = $dirty_params[$arg_name];
                if (is_identifier_path($arg)) {
                    $params[$arg_name] = $arg;
                }
            }
        }
        foreach (array('link', 'block') as $arg_name) {
            if (isset($dirty_params[$arg_name]) && is_string($dirty_params[$arg_name])) {
                $arg = $dirty_params[$arg_name];
                $args = in_string(',', $arg) ? explode(',', $arg) : array($arg);
                $params[$arg_name] = array();
                foreach ($args as $arg) {
                    if (is_identifier_path($arg)) {
                        $params[$arg_name][] = $arg;
                    }
                }
            }
        }
        $version_key = config::get('technique.version_key', self::default_version_key);
        if (isset($dirty_params[$version_key]) && is_numeric($dirty_params[$version_key])) {
            $params[$version_key] = $dirty_params[$version_key];
        }
        return new target(array($target_name, $params));
    }
    public static /* @core */ function www_url($path) {
        $www_url = self::$path_prefix;
        if ($path !== '') {
            $www_url .= '/' . ltrim($path, '/');
        }
        if (self::$www_domain !== '') {
            $www_url = (framework::is_https() ? 'https://' : 'http://') . self::$www_domain . $www_url;
        }
        return $www_url;
    }
    public static /* @core */ function __init__() {
        self::$www_domain = config::get('technique.www_domain', '');
        self::$module_domains = config::get('technique.module_domains', array());
        self::$domain_modules = array_flip(self::$module_domains);
        self::$rewrite_enabled = config::get('technique.rewrite_enabled', false);
        self::$target_key = config::get('technique.target_key', self::default_target_key);
        self::$path_prefix = visitor::get_path_prefix();
        // cjs 模式也可能需要使用路由规则来生成 url
        $uri_maps = config::get('technique.uri_maps', array());
        if (is_array($uri_maps)) {
            self::$uri_maps = $uri_maps;
            self::$flipped_uri_maps = array_flip($uri_maps);
        }
    }
    
    protected static function parse_standard_uri($request_uri, $request_host) {
        # scheme://www.swap.com[path_prefix]/                                          -> site/index
        # scheme://www.swap.com[path_prefix]/?%FF=%EE&%EE=%FF                          -> site/index?a=b&b=a
        # scheme://www.swap.com[path_prefix]/?target=post/index                        -> post/index
        # scheme://www.swap.com[path_prefix]/?target=post/index&%FF=%EE&%EE=%FF        -> post/index?a=b&b=a
        
        # scheme://www.swap.com[path_prefix]/?target=admin-                            -> admin-site/index
        # scheme://www.swap.com[path_prefix]/?target=admin-&%FF=%EE&%EE=%FF            -> admin-site/index?a=b&b=a
        # scheme://www.swap.com[path_prefix]/?target=admin-user/index                  -> admin-user/index
        # scheme://www.swap.com[path_prefix]/?target=admin-user/index&%FF=%EE&%EE=%FF  -> admin-user/index?a=b&b=a
        
        # scheme://admin.swap.com[path_prefix]/                                        -> admin-site/index
        # scheme://admin.swap.com[path_prefix]/?%FF=%EE&%EE=%FF                        -> admin-site/index?a=b&b=a
        # scheme://admin.swap.com[path_prefix]/?target=user/index                      -> admin-user/index
        # scheme://admin.swap.com[path_prefix]/?target=user/index&%FF=%EE&%EE=%FF      -> admin-user/index?a=b&b=a
        $target_name = 'site/index';
        $params = array();
        return new target(array($target_name, $params));
    }
    protected static function parse_rewrited_uri($request_uri, $request_host) {
        # scheme://www.swap.com[path_prefix]/                                  -> site/index
        # scheme://www.swap.com[path_prefix]/?%FF=%EE&%EE=%FF                  -> site/index?a=b&b=a
        # scheme://www.swap.com[path_prefix]/post/index                        -> post/index
        # scheme://www.swap.com[path_prefix]/post/index?%FF=%EE&%EE=%FF        -> post/index?a=b&b=a
        
        # scheme://www.swap.com[path_prefix]/admin-                            -> admin-site/index
        # scheme://www.swap.com[path_prefix]/admin-?%FF=%EE&%EE=%FF            -> admin-site/index?a=b&b=a
        # scheme://www.swap.com[path_prefix]/admin-user/index                  -> admin-user/index
        # scheme://www.swap.com[path_prefix]/admin-user/index?%FF=%EE&%EE=%FF  -> admin-user/index?a=b&b=a
        
        # scheme://admin.swap.com[path_prefix]/                                -> admin-site/index
        # scheme://admin.swap.com[path_prefix]/?%FF=%EE&%EE=%FF                -> admin-site/index?a=b&b=a
        # scheme://admin.swap.com[path_prefix]/user/index                      -> admin-user/index
        # scheme://admin.swap.com[path_prefix]/user/index?%FF=%EE&%EE=%FF      -> admin-user/index?a=b&b=a
        
        // 查看 host，确定是否为模块请求，如果是，确定所请求的模块名称
        $is_module_request = false;
        if (isset(self::$domain_modules[$request_host])) {
            $is_module_request = true;
            $request_module = self::$domain_modules[$request_host];
        }
        
        // 解析 request_uri 并将查询字符串分离
        if (in_string('?', $request_uri)) {
            list($request_uri, $query_str) = explode('?', $request_uri, 2);
            parse_str($query_str, $params);
        } else {
            $params = array();
        }
        
        // 接下来进行 uri 解析。首先尝试匹配路由规则
        foreach (self::$uri_maps as $match_pattern => $target_pattern) {
            // 如果当前请求是模块请求，则跳过非模块类的规则和不是当前请求模块的规则
            if ($is_module_request) {
                if (!in_string('-', $match_pattern)) {
                    continue;
                }
                list($match_module, $match_pattern) = explode('-', $match_pattern, 2);
                if (ltrim($match_module, '/') !== $request_module) {
                    continue;
                }
            }
            $match_pattern = str_replace('.', '\\.', $match_pattern); # “.” 号特殊处理
            // 将形式正则改成实际正则
            if (in_string('*', $match_pattern)) {
                $match_pattern = str_replace('*', self::lazy_unit_pattern, $match_pattern);
                $match_pattern = substr_replace($match_pattern, self::greedy_unit_pattern, strrpos($match_pattern, self::lazy_unit_pattern), strlen(self::lazy_unit_pattern));
            }
            $match_pattern = '!^' . $match_pattern . '$!';
            // 检查该正则是否匹配 request_uri ？
            if (preg_match($match_pattern, $request_uri, $matches)) {
                // 匹配。接着使用真实值替换预定义的 ${N} 参数
                $match_count = count($matches);
                $args = array();
                if ($match_count > 1) {
                    for ($i = 1; $i < $match_count; $i++) {
                        $args['$' . $i] = urldecode($matches[$i]);
                    }
                }
                // 构建 target 并返回
                $target = new target($target_pattern);
                foreach ($target->get_params() as $key => $value) {
                    $params[$key] = isset($args[$value]) ? $args[$value] : $value;
                }
                return new target(array($target->get_target_name(), $params));
            }
        }
        // 没有找到匹配的路由规则。那么我们使用普通规则
        if (!$is_module_request && $request_uri !== '/' && in_string('-', $request_uri)) {
            $uri_parts = explode('-', $request_uri, 2);
            $uri_parts[0] = ltrim($uri_parts[0], '/');
            if (is_identifier($uri_parts[0])) {
                $request_module = $uri_parts[0];
                $request_uri = '/' . $uri_parts[1];
                $is_module_request = true;
            }
        }
        $request_uri = substr($request_uri, 1);
        if ($request_uri === '') {
            $target_name = self::default_controller_name . '/' . self::default_action_name;
        } else {
            $uri_parts = explode('/', $request_uri, 2);
            if (count($uri_parts) === 1) {
                $controller_name = is_identifier($request_uri) ? $request_uri : self::default_controller_name;
                $action_name = self::default_action_name;
            } else {
                $controller_name = is_identifier($uri_parts[0]) ? $uri_parts[0] : self::default_controller_name;
                $action_name = is_identifier($uri_parts[1]) ? $uri_parts[1] : self::default_action_name;
            }
            $target_name = $controller_name . '/' . $action_name;
        }
        if ($is_module_request) {
            $target_name = $request_module . '-' . $target_name;
        }
        return new target(array($target_name, $params));
    }
    
    protected static function build_base_url(target $target, $and_char, $is_absolute, $rewrite_enabled) {
        $target_has_module = $target->has_module();
        if ($target_has_module) {
            $target_module = $target->get_module_name();
        }
        $query_char = '?';
        if ($is_absolute) {
            $scheme = framework::is_https() ? 'https://' : 'http://';
            if ($target_has_module && isset(self::$module_domains[$target_module])) {
                $base_url = $scheme . self::$module_domains[$target_module] . self::$path_prefix . '/';
            } else {
                $base_url = self::$path_prefix . '/';
                if ($target_has_module) {
                    if ($rewrite_enabled) {
                        $base_url .= $target_module . '-';
                    } else {
                        $base_url .= $query_char . self::$target_key . '=' . $target_module . '-';
                        $query_char = $and_char;
                    }
                }
                if (self::$www_domain !== '') {
                    $base_url = $scheme . self::$www_domain . $base_url;
                }
            }
        } else {
            $base_url = '/';
            if ($target_has_module) {
                if ($rewrite_enabled) {
                    $base_url .= $target_module . '-';
                } else {
                    $base_url .= $query_char . self::$target_key . '=' . $target_module . '-';
                    $query_char = $and_char;
                }
            }
        }
        return array($base_url, $query_char);
    }
    protected static function build_normal_left_url(target $target, $query_char, $and_char, $rewrite_enabled) {
        $left_url = '';
        $controller_name = $target->get_controller_name();
        $action_name = $target->get_action_name();
        $is_default = $controller_name === self::default_controller_name && $action_name === self::default_action_name;
        if (!$is_default) {
            $target_path = $controller_name . '/' . $action_name;
            if ($rewrite_enabled) {
                $left_url .= $target_path;
            } else {
                $left_url .= $query_char . self::$target_key . '=' . $target_path;
                $query_char = $and_char;
            }
        }
        $params = array();
        foreach ($target->get_params() as $key => $value) {
            $params[] = urlencode($key) . '=' . urlencode($value);
        }
        if ($params !== array()) {
            $left_url .= $query_char . implode($and_char, $params);
        }
        return $left_url;
    }
    protected static function build_mapped_left_url(target $target) {
        $target_params = $target->get_params();
        $target_name = $target->get_target_name();
        $target_has_module = $target->has_module();
        if ($target_params === array()) {
            if (isset(self::$flipped_uri_maps[$target_name])) {
                $result_pattern = self::$flipped_uri_maps[$target_name];
                return $target_has_module ? substr($result_pattern, strpos($result_pattern, '-') + 1) : ltrim($result_pattern, '/');
            }
        } else {
            $target_param_keys = $target->get_param_keys();
            foreach (self::$flipped_uri_maps as $match_token => $result_pattern) {
                $match_target = new target($match_token);
                if ($match_target->get_target_name() === $target_name && $match_target->get_param_keys() === $target_param_keys) {
                    $args = array();
                    $match = true;
                    foreach ($match_target->get_params() as $key => $value) {
                        if ($value[0] === '$') {
                            $number = substr($value, 1);
                            $args[$number] = $target_params[$key];
                        } else if ($value !== $target_params[$key]) {
                            $match = false;
                            break;
                        }
                    }
                    if ($match) {
                        ksort($args);
                        if ($target_has_module) {
                            $result_pattern = substr($result_pattern, strpos($result_pattern, '-') + 1);
                        }
                        $request_parts = explode('*', $result_pattern);
                        $result_uri = '';
                        for ($i = 0, $n = count($args); $i < $n; $i++) {
                            $result_uri .= $request_parts[$i] . urlencode($args[$i + 1]);
                        }
                        $result_uri .= $request_parts[$i];
                        return ltrim($result_uri, '/');
                    }
                }
            }
        }
        return null;
    }
    
    protected static $www_domain = '';
    protected static $module_domains = array();
    protected static $domain_modules = array();
    protected static $rewrite_enabled = false;
    protected static $target_key = '';
    protected static $path_prefix = '';
    protected static $uri_maps = array();
    protected static $flipped_uri_maps = array();
}

/**
 * [类型] 目标
 *
 * target_token 有两种格式，一种字符串，一种数组。
 * 两种又区分带参数与不带参数。
 *
 * 举例：
 *
 *   'site/login'
 *   'admin-user/delete?arg1=int1&arg2=int2'
 *   ['site/login']
 *   ['admin-user/delete', ['&' => '>', '<' => '?', '?' => '=']]
 */
class target implements html_escapable {
    public function __construct($target_token) {
        if (is_array($target_token)) {
            $target_name = $target_token[0];
            if (isset($target_token[1]) && is_array($target_token[1])) {
                $this->params = $target_token[1];
            }
        } else {
            $params = array();
            if (in_string('?', $target_token)) {
                list($target_name, $params_str) = explode('?', $target_token, 2);
                parse_str($params_str, $params);
            } else {
                $target_name = $target_token;
            }
            $this->params = $params;
        }
        $this->target_name = $target_name;
        if ($target_name !== '') {
            if (in_string('-', $target_name)) {
                $this->target_file = str_replace('-', '/', $target_name);
                list($module_name, $target_path) = explode('-', $target_name, 2);
                if (is_identifier($module_name)) {
                    $this->has_module = true;
                    $this->module_name = $module_name;
                }
            } else {
                $this->target_file = $target_name;
                $target_path = $target_name;
            }
            $this->target_path = $target_path;
            list($this->controller_name, $this->action_name) = explode('/', $target_path, 2);
            $this->target_pair = array($this->controller_name, $this->action_name);
        }
    }
    public function html_escape() {
        $that = clone $this;
        $that->params = html::escape($that->params);
        return $that;
    }
    public function html_unescape() {
        $that = clone $this;
        $that->params = html::unescape($that->params);
        return $that;
    }
    public function as_array() {
        return array($this->target_name, $this->params);
    }
    public function has_module() {
        return $this->has_module;
    }
    public function get_module_name() {
        return $this->module_name;
    }
    public function get_target_name() {
        return $this->target_name;
    }
    public function get_target_path() {
        return $this->target_path;
    }
    public function get_target_pair() {
        return $this->target_pair;
    }
    public function get_target_file($file_ext = '') {
        return $this->target_file . $file_ext;
    }
    public function get_controller_name() {
        return $this->controller_name;
    }
    public function get_action_name() {
        return $this->action_name;
    }
    public function has_params() {
        return $this->params === array();
    }
    public function get_params() {
        return $this->params;
    }
    public function set_params(array $params) {
        $this->params = $params;
    }
    public function get_param($key, $default_value = null) {
        return array_key_exists($key, $this->params) ? $this->params[$key] : $default_value;
    }
    public function set_param($key, $value) {
        $this->params[$key] = $value;
    }
    public function get_param_keys() {
        return array_keys($this->params);
    }
    
    protected $has_module = false;    # true/false
    protected $module_name = '';      # admin
    protected $target_name = '';      # site/login, admin-user/delete
    protected $target_path = '';      # site/login, user/delete
    protected $target_pair = array(); # ['site', 'login'], ['user', 'delete']
    protected $target_file = '';      # site/login, admin/user/delete
    protected $controller_name = '';  # site, user
    protected $action_name = '';      # login, delete
    protected $params = array();      # ['a' => 'b', '?' => '&', '=' => '#']
}
