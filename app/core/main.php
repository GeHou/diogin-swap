<?php
/**
 * 框架的基本功能与控制
 *
 * @copyright Copyright (c) 2009-2012 Jingcheng Zhang <diogin@gmail.com>. All rights reserved.
 * @license   See "LICENSE" file bundled with this distribution.
 */

namespace swap;

use Exception;
use ErrorException;
use RuntimeException;

// [常量] 框架版本
const version = 'current';

// [函数] 检查字符串是否为符合框架要求的标志符
function is_identifier($str) {
    return is_string($str) && preg_match('/^[a-z][0-9a-z]*(_[0-9a-z]+)*$/', $str);
}

// [函数] 检查字符串是否为标志符构成的路径，例如：one/two/three
function is_identifier_path($str, $slash_count = -1) {
    if (!is_string($str)) {
        return false;
    }
    $parts = explode('/', $str);
    if ($slash_count !== -1 && count($parts) !== ($slash_count + 1)) {
        return false;
    }
    foreach ($parts as $part) {
        if (!is_identifier($part)) {
            return false;
        }
    }
    return true;
}

// [函数] 生成一个随机的 sha1 字符串
function random_sha1() {
    static $secret_key = '';
    if ($secret_key === '') {
        $secret_key = config::get('technique.secret_key');
    }
    $random_str = '';
    foreach (array('REMOTE_ADDR', 'REMOTE_PORT', 'HTTP_USER_AGENT') as $key) {
        if (isset($_SERVER[$key])) {
            $random_str .= $_SERVER[$key];
        }
    }
    $random_str .= microtime(true) . mt_rand() . uniqid($secret_key, true);
    return sha1($random_str . $secret_key);
}

// [函数] 检查 $small_str 是否在 $big_str 内
function in_string($small_str, $big_str) {
    return strpos($big_str, $small_str) !== false;
}

// [函数] 检查 $big_str 是否以 $small_str 开头
function starts_with($small_str, $big_str) {
    return strpos($big_str, $small_str) === 0;
}

// [函数] 检查 $big_str 是否以 $small_str 结尾
function ends_with($small_str, $big_str) {
    return strpos(strrev($big_str), strrev($small_str)) === 0;
}

// [函数] 计算字符串的字节数
function str_bytes($str) {
    return strlen($str);
}

// [函数] 计算字符串的字符数
function str_chars($str, $encoding = 'UTF-8') {
    return iconv_strlen($str, $encoding);
}

// [函数] 将字符串拆成一个个字符构成的数组
function str_units($str, $encoding = 'UTF-8') {
    if ($encoding !== 'UTF-8') {
        throw new developer_error('目前只支持 UTF-8 编码的字符串');
    }
    preg_match_all('/./su', $str, $chars);
    return $chars[0];
}

// [类型] 硬错误
abstract class error extends ErrorException {
    public function set_context(array $context) {
        $this->context = $context;
    }
    public function get_context() {
        return $this->context;
    }
    
    protected $context = array();
}

// [类型] 开发者代码编写错误
class developer_error extends error {}

// [类型] 代码运行时环境错误
class environment_error extends error {}

// [类型] 运行时异常
abstract class except extends RuntimeException {}

// [类型] 运行时来访者异常
class visitor_except extends except {
    public function set_value($key, $value) {
        $this->values[$key] = $value;
    }
    public function get_value($key) {
        return $this->values[$key];
    }
    
    protected $values = array();
}

// [类型] 运行时服务器异常
class server_except extends except {}

// [类型] 运行时本地服务器异常
class local_except extends server_except {}

// [类型] 运行时远程服务器异常
class remote_except extends server_except {}

// [实体] 框架门面
class framework {
    public static function serve_php_request() {
        self::$serve_mode = self::php_mode;
        try {
            self::init_web_environment();
            dispatcher::dispatch_target();
            self::send_php_response();
        } catch (visitor_except $e) {
            self::show_exception($e);
        }
    }
    public static function serve_pss_request() {
        self::serve_cjs_request(self::pss_mode);
    }
    public static function serve_pjs_request() {
        self::serve_cjs_request(self::pjs_mode);
    }
    public static function init_cli_environment() {
        self::$serve_mode = self::cli_mode;
        self::init_swap_environment();
    }
    public static function is_https($true_or_false = null) {
        if ($true_or_false === null) {
            return self::$is_https;
        } else {
            self::$is_https = $true_or_false;
        }
    }
    
    /* @core */ const php_mode = 'php';
    /* @core */ const pss_mode = 'pss';
    /* @core */ const pjs_mode = 'pjs';
    /* @core */ const cli_mode = 'cli';
    
    public static /* @core */ function is_php_mode() {
        return self::$serve_mode === self::php_mode;
    }
    public static /* @core */ function is_pss_mode() {
        return self::$serve_mode === self::pss_mode;
    }
    public static /* @core */ function is_pjs_mode() {
        return self::$serve_mode === self::pjs_mode;
    }
    public static /* @core */ function is_cjs_mode() {
        return self::$serve_mode === self::pss_mode || self::$serve_mode === self::pjs_mode;
    }
    public static /* @core */ function is_cli_mode() {
        return self::$serve_mode === self::cli_mode;
    }
    public static /* @core */ function get_serve_mode() {
        return self::$serve_mode;
    }
    // 将 php 汇报的错误转成异常抛出
    public static /* @core */ function error_handler($error_level, $error_msg, $error_file, $error_line, array $error_context) {
        if (error_reporting() !== 0) {
            $error = new developer_error($error_msg, 500, $error_level, $error_file, $error_line);
            $error->set_context($error_context);
            throw $error;
        }
    }
    // 任何未被捕获的异常，最终将被该函数捕获
    public static /* @core */ function exception_handler(Exception $e) {
        if (config::get('technique.log_errors', true) && $e->getCode() !== 503) { # 503 的意思是关站维护中，因此不记日志
            logger::log_error($e->getMessage() . ' in file: ' . $e->getFile() . ' in line: ' . $e->getLine() . ' with trace: ' . var_export($e->getTrace(), true));
        }
        self::show_exception($e);
    }
    
    protected static function serve_cjs_request($serve_mode) {
        self::$serve_mode = $serve_mode;
        try {
            self::init_web_environment();
            $request_uri = visitor::request_uri();
            $target = url_manager::parse_cjs_uri($request_uri);
            if (config::get('technique.cache_cjs_in_server', false)) {
                $cache_dir = var_dir . '/cache/' . $serve_mode . '/' . $target->get_param(config::get('technique.version_key', url_manager::default_version_key), '0');
                $cache_file = $cache_dir . '/' . sha1($request_uri) . '.cache';
                if (is_readable($cache_file)) {
                    $content = file_get_contents($cache_file);
                } else {
                    $content = cjs_rendor::render_for($target);
                    if (!is_dir($cache_dir)) {
                        @mkdir($cache_dir, 0777, true);
                    }
                    @file_put_contents($cache_file, $content);
                }
            } else {
                $content = cjs_rendor::render_for($target);
            }
            visitor::set_content($content);
            self::send_cjs_response();
        } catch (visitor_except $e) {}
    }
    protected static function send_php_response() {
        ob_end_clean(); # 丢弃开发者无意间 echo 出的内容
        visitor::persist_roles();
        $response = visitor::get_response();
        $status = $response['status'];
        if ($status['code'] !== 0) {
            header('HTTP/1.1 ' . $status['code'] . ' ' . $status['phrase']);
        }
        foreach ($response['headers'] as $key => $value) {
            header($key . ': ' . $value);
        }
        foreach ($response['cookies'] as $cookie) {
            setcookie($cookie['name'], $cookie['value'], $cookie['expire'], $cookie['path'], $cookie['domain'], $cookie['secure'], $cookie['http_only']);
        }
        $content_type = $response['content_type'];
        self::send_response(false, $content_type, $response['content'], $content_type === 'text/html; charset=utf-8');
    }
    protected static function send_cjs_response() {
        ob_end_clean(); # 丢弃开发者无意间 echo 出的内容
        $response = visitor::get_response();
        $content_type = self::$serve_mode === self::pss_mode ? 'text/css' : 'application/javascript';
        self::send_response(true, $content_type, $response['content'], true);
    }
    protected static function send_response($is_cjs_mode, $content_type, $content, $gzippable = true) {
        header('Content-Type: ' . $content_type);
        if (config::get('technique.send_x_powered_by', true)) {
            header('X-Powered-By: swap-' . version);
        }
        if ($is_cjs_mode) {
            if (config::get('technique.cache_cjs_in_client', false)) {
                header('Cache-Control: max-age=2592000');
            } else {
                header('Cache-Control: max-age=0');
            }
        }
        if (self::$log_execute_time) {
            $execute_time = microtime(true) - self::$begin_microtime;
            header('X-Execute-Time: ' . substr($execute_time * 1000, 0, 5) . ' ms');
        }
        if ($gzippable && extension_loaded('zlib') && !ini_get('zlib.output_compression')) {
            ob_start('ob_gzhandler');
        } else {
            ob_start();
        }
        echo $content;
        ob_end_flush();
        if (is_debug) {
            debug::save_required_files();
        }
    }
    protected static function show_exception(Exception $e = null) {
        if (self::is_cli_mode()) {
            echo $e->getMessage();
        } else {
            ob_end_clean(); # 丢弃开发者无意间 echo 出的内容
            header('Content-Type: text/html; charset=utf-8');
            $_tpl_file = core_dir . '/view/error/' . $e->getCode() . '.tpl';
            if (!is_readable($_tpl_file)) {
                $_tpl_file = core_dir . '/view/error/500.tpl';
            }
            require $_tpl_file;
        }
    }
    protected static function init_web_environment() {
        self::init_swap_environment();
        ob_start(); # 确保不会在无意中 echo 出内容
        ob_implicit_flush(false); # 不许隐式输出内容
    }
    protected static function init_swap_environment() {
        clock::stop();
        config::load();
        self::$is_https = self::is_cjs_mode() ? (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] === '443') : config::get('technique.default_https', false);
        self::$log_execute_time = config::get('technique.log_execute_time', true);
        if (self::$log_execute_time) {
            self::$begin_microtime = clock::get_micro_stamp();
        }
        define('swap\is_debug', config::get('technique.is_debug', false));
        $secret_key = 'technique.secret_key';
        if (!config::has($secret_key)) {
            trigger_error('you should provide at least config key: ' . $secret_key . ' in config file', E_USER_ERROR);
        }
        $locale = config::get('technique.locale', null);
        if (is_string($locale)) {
            i18n::set_locale($locale); # 可以在 Web 应用代码里改变 locale
        }
        clock::set_timezone(config::get('technique.time_zone', 'Asia/Shanghai')); # 可以在 Web 应用代码里改变时区
        logger::init();
        ini_set('display_errors', config::get('technique.display_errors', is_debug));
        set_exception_handler(array(__CLASS__, 'exception_handler'));
        set_error_handler(array(__CLASS__, 'error_handler'), config::get('technique.error_reporting', is_debug ? E_ALL | E_STRICT : E_ALL & ~E_NOTICE));
        loader::set_include_pathes();
        spl_autoload_register(array('swap\loader', 'load_swap_entity'));
        spl_autoload_register(array('swap\loader', 'load_webapp_entity'));
    }
    
    protected static $serve_mode = self::php_mode;
    protected static $is_https = false;
    protected static $log_execute_time = false;
    protected static $begin_microtime = 0.000;
}

// [实体] 组件加载器
class loader {
    public static function set_include_pathes() {
        $include_pathes = implode(PATH_SEPARATOR, config::get('technique.include_pathes', array()));
        if ($include_pathes !== '') {
            set_include_path(get_include_path() . PATH_SEPARATOR . $include_pathes);
        }
    }
    public static function load_library($filename) {
        self::load_file(library_dir . '/' . $filename . '.php');
    }
    public static function load_third($file) {
        self::load_file(third_dir . '/' . ltrim($file, '/'));
    }
    public static function load_file($_file) {
        require_once $_file;
    }
    
    public static /* @core */ function load_webapp_entity($entity) {
        if (!in_string('_', $entity)) {
            return;
        }
        if (ends_with('_model', $entity)) {
            self::load_file(model_dir . '/' . $entity . '.php');
        } else if (ends_with('_service', $entity)) {
            self::load_file(service_dir . '/' . $entity . '.php');
        } else if (ends_with('_filter', $entity)) {
            $filter_file = filter_dir . '/' . $entity . '.php';
            if (is_readable($filter_file)) {
                self::load_file($filter_file);
            }
        }
    }
    public static /* @core */ function load_swap_entity($entity) {
        static $entities_loaded = array();
        if (strpos($entity, 'swap\\') !== 0) {
            return;
        }
        if (!isset($entities_loaded[$entity])) {
            if (!isset(self::$swap_entities[$entity])) {
                throw new developer_error("swap entity: '{$entity}' 不存在");
            }
            $entities_loaded[$entity] = true;
            self::load_file(core_dir . '/' . self::$swap_entities[$entity]);
        }
        return $entity;
    }
    
    protected static $swap_entities = array(
    
        'swap\visitor'                 => 'client/visitor.php',
        'swap\session'                 => 'client/session/session.php',
        'swap\session_manager'         => 'client/session/session.php',
        'swap\session_store'           => 'client/session/store/store.php',
        'swap\session_store_pool'      => 'client/session/store/store.php',
        'swap\cookie_session_store'    => 'client/session/store/types/cookie.php',
        'swap\memcached_session_store' => 'client/session/store/types/memcached.php',
        'swap\redis_session_store'     => 'client/session/store/types/redis.php',
        'swap\mysql_session_store'     => 'client/session/store/types/mysql.php',
        'swap\pgsql_session_store'     => 'client/session/store/types/pgsql.php',
        'swap\sqlite_session_store'    => 'client/session/store/types/sqlite.php',
        
        'swap\url_manager'             => 'server/url_manager.php',
        'swap\target'                  => 'server/url_manager.php',
        'swap\view_rendor'             => 'server/view_rendor.php',
        'swap\cjs_rendor'              => 'server/cjs_mode.php',
        'swap\tpl_rendor'              => 'server/php_mode.php',
        'swap\controller'              => 'server/php_mode.php',
        'swap\helper'                  => 'server/php_mode.php',
        'swap\context'                 => 'server/php_mode.php',
        'swap\before_filter'           => 'server/php_mode.php',
        'swap\after_filter'            => 'server/php_mode.php',
        'swap\autoload_filter'         => 'server/php_mode.php',
        'swap\dispatcher'              => 'server/php_mode.php',
        'swap\action_return'           => 'server/php_mode.php',
        'swap\action_forward'          => 'server/php_mode.php',
        
        'swap\checker'                 => 'checker/checker.php',
        'swap\check_failed'            => 'checker/checker.php',
        'swap\lazy_checker'            => 'checker/types/lazy.php',
        'swap\greedy_checker'          => 'checker/types/greedy.php',
        'swap\instant_checker'         => 'checker/types/instant.php',
        
        'swap\service'                 => 'logic/service.php',
        'swap\service_except'          => 'logic/service.php',
        'swap\binder'                  => 'logic/model/model.php',
        'swap\model_api'               => 'logic/model/model.php',
        'swap\model'                   => 'logic/model/model.php',
        'swap\rdb_model_api'           => 'logic/model/types/rdb.php',
        'swap\rdb_model'               => 'logic/model/types/rdb.php',
        'swap\ddb_model_api'           => 'logic/model/types/ddb.php',
        'swap\ddb_model'               => 'logic/model/types/ddb.php',
        'swap\kvdb_model_api'          => 'logic/model/types/kvdb.php',
        'swap\kvdb_model'              => 'logic/model/types/kvdb.php',
        
        'swap\cache'                   => 'tech/cache/cache.php',
        'swap\cache_pool'              => 'tech/cache/cache.php',
        'swap\filesys_cache'           => 'tech/cache/types/filesys.php',
        'swap\memcached_cache'         => 'tech/cache/types/memcached.php',
        'swap\redis_cache'             => 'tech/cache/types/redis.php',
        
        'swap\rdb'                     => 'tech/repo/rdb/rdb.php',
        'swap\centered_rdb'            => 'tech/repo/rdb/entities/centered.php',
        'swap\distributed_rdb'         => 'tech/repo/rdb/entities/distributed.php',
        'swap\rdb_node'                => 'tech/repo/rdb/node/node.php',
        'swap\rdb_node_pool'           => 'tech/repo/rdb/node/node.php',
        'swap\mysql_rdb_node'          => 'tech/repo/rdb/node/types/mysql.php',
        'swap\mysql_master_rdb_node'   => 'tech/repo/rdb/node/types/mysql.php',
        'swap\mysql_slave_rdb_node'    => 'tech/repo/rdb/node/types/mysql.php',
        'swap\mysql_rdb_node_util'     => 'tech/repo/rdb/node/types/mysql.php',
        'swap\pgsql_rdb_node'          => 'tech/repo/rdb/node/types/pgsql.php',
        'swap\pgsql_master_rdb_node'   => 'tech/repo/rdb/node/types/pgsql.php',
        'swap\pgsql_slave_rdb_node'    => 'tech/repo/rdb/node/types/pgsql.php',
        'swap\pgsql_rdb_node_util'     => 'tech/repo/rdb/node/types/pgsql.php',
        'swap\sqlite_rdb_node'         => 'tech/repo/rdb/node/types/sqlite.php',
        'swap\sqlite_master_rdb_node'  => 'tech/repo/rdb/node/types/sqlite.php',
        'swap\sqlite_slave_rdb_node'   => 'tech/repo/rdb/node/types/sqlite.php',
        'swap\sqlite_rdb_node_util'    => 'tech/repo/rdb/node/types/sqlite.php',
        'swap\rdb_conn'                => 'tech/repo/rdb/driver/driver.php',
        'swap\rdb_result'              => 'tech/repo/rdb/driver/driver.php',
        'swap\rdb_conn_pool'           => 'tech/repo/rdb/driver/driver.php',
        'swap\mysql_rdb_conn'          => 'tech/repo/rdb/driver/types/mysql.php',
        'swap\mysql_rdb_result'        => 'tech/repo/rdb/driver/types/mysql.php',
        'swap\pgsql_rdb_conn'          => 'tech/repo/rdb/driver/types/pgsql.php',
        'swap\pgsql_rdb_result'        => 'tech/repo/rdb/driver/types/pgsql.php',
        'swap\sqlite_rdb_conn'         => 'tech/repo/rdb/driver/types/sqlite.php',
        'swap\sqlite_rdb_result'       => 'tech/repo/rdb/driver/types/sqlite.php',
        
        'swap\mover'                   => 'tech/mover/mover.php',
        'swap\mover_pool'              => 'tech/mover/mover.php',
        'swap\filesys_mover'           => 'tech/mover/types/filesys.php',
        'swap\ftp_mover'               => 'tech/mover/types/ftp.php',
        'swap\http_mover'              => 'tech/mover/types/http.php',
        
        'swap\value'                   => 'toolkit/value/value.php',
        'swap\int_value'               => 'toolkit/value/types/int.php',
        'swap\str_value'               => 'toolkit/value/types/str.php',
        'swap\arr_value'               => 'toolkit/value/types/arr.php',
        'swap\float_value'             => 'toolkit/value/types/float.php',
        'swap\email_value'             => 'toolkit/value/types/email.php',
        'swap\url_value'               => 'toolkit/value/types/url.php',
        'swap\ip_value'                => 'toolkit/value/types/ip.php',
        'swap\dsn_value'               => 'toolkit/value/types/dsn.php',
        'swap\date_value'              => 'toolkit/value/types/date.php',
        'swap\time_value'              => 'toolkit/value/types/time.php',
        'swap\mobile_value'            => 'toolkit/value/types/mobile.php',
        
        'swap\filesys'                 => 'toolkit/filesys.php',
        'swap\crypt'                   => 'toolkit/crypt.php',
        'swap\html'                    => 'toolkit/html.php',
        'swap\html_escapable'          => 'toolkit/html.php',
        'swap\image'                   => 'toolkit/image.php',
        'swap\captcha'                 => 'toolkit/captcha/captcha.php',
        'swap\gd_captcha'              => 'toolkit/captcha/entities/gd.php',
        'swap\imagick_captcha'         => 'toolkit/captcha/entities/imagick.php',
    );
}

// [实体] 配置参数维护器
class config {
    public static function get($key, $default_value = null) {
        $config = self::$configs;
        if (strpos($key, '.') !== false) {
            $keys = explode('.', $key);
            foreach ($keys as $next_key) {
                if (!isset($config[$next_key])) {
                    return $default_value;
                }
                $config = $config[$next_key];
            }
        } else if (!isset($config[$key])) {
            $config = $default_value;
        } else {
            $config = $config[$key];
        }
        return $config;
    }
    public static function has($key) {
        return self::get($key, null) !== null;
    }
    
    public static /* @core */ function load() {
        $configs = require config_dir . '/global.php';
        $local_config_file = config_dir . '/local.php';
        if (is_readable($local_config_file)) {
            $local_configs = require $local_config_file;
            if (is_array($local_configs)) {
                $configs = array_replace_recursive($configs, $local_configs);
            }
        }
        self::$configs = $configs;
    }
    
    protected static $configs = array();
}

// [实体] 当前时钟
class clock {
    public static function set_timezone($timezone) {
        date_default_timezone_set($timezone);
    }
    public static function get_timezone() {
        return date_default_timezone_get();
    }
    public static function get($format = 'Y-m-d H:i:s') {
        return date($format, (int)self::$micro_stamp);
    }
    public static function get_date() {
        return date('Y-m-d', (int)self::$micro_stamp);
    }
    public static function get_time() {
        return date('H:i:s', (int)self::$micro_stamp);
    }
    public static function get_datetime() {
        return date('Y-m-d H:i:s', (int)self::$micro_stamp);
    }
    public static function get_stamp() {
        return (int)self::$micro_stamp;
    }
    public static function get_micro_stamp() {
        return self::$micro_stamp;
    }
    
    public static /* @core */ function stop() {
        self::$micro_stamp = microtime(true);
    }
    
    protected static $micro_stamp = 0.000;
}

// [实体] 调试器
class debug {
    public static function dump(/* ... */) {
        ob_start();
        call_user_func_array('var_dump', func_get_args());
        $content = ob_get_clean();
        $file = log_dir . '/debug/dump.log';
        @file_put_contents($file, '[' . clock::get_datetime() . '] ' . $content . "\n", FILE_APPEND);
    }
    public static function save($filename, $msg) {
        static $request_uri = '';
        if ($request_uri === '' && !framework::is_cli_mode()) {
            $request_uri = visitor::request_uri();
        }
        $file = log_dir . '/debug/' . $filename . '.log';
        @file_put_contents($file, '[' . clock::get_datetime() . '][' . $request_uri . '] - ' . $msg . "\n", FILE_APPEND);
    }
    
    public static /* @core */ function save_required_files() {
        $msg = var_export(get_included_files(), true);
        if (DIRECTORY_SEPARATOR === '\\') {
            $msg = str_replace('\\\\', '/', $msg);
        }
        self::save('require_' . framework::get_serve_mode(), $msg);
    }
}

// [实体] 运行时变量寄存器
class deposit {
    public static function store($key, $value) {
        self::$datum[$key] = $value;
    }
    public static function fetch($key, $default_value = null) {
        return self::has($key) ? self::$datum[$key] : $default_value;
    }
    public static function has($key) {
        return array_key_exists($key, self::$datum);
    }
    
    protected static $datum = array();
}

// [实体] 国际化信息获取器
class i18n {
    public static function get($key, $default_value = '') {
        return array_key_exists($key, self::$texts) ? self::$texts[$key] : $default_value;
    }
    public static function set_locale($locale) {
        if ($locale !== self::$locale) {
            self::$locale = $locale;
            $i18n_file = share_dir . '/lang/' . $locale . '.php';
            if (is_readable($i18n_file)) {
                self::$texts = require $i18n_file;
            }
        }
    }
    
    protected static $locale = '';
    protected static $texts = array();
}

// [实体] 日志记录器
class logger {
    const notice = 'NOTICE';
    const warning = 'WARNING';
    const error = 'ERROR';
    public static function init() {
        self::$rotate_method = config::get('technique.log_rotate_method', '');
    }
    public static function log_error($msg) {
        @file_put_contents(self::get_log_file_for('error'), '[' . clock::get_datetime() . '] ' . $msg . "\n", FILE_APPEND);
    }
    public static function log($filename, $msg, $level = self::notice) {
        @file_put_contents(self::get_log_file_for($filename), '[' . clock::get_datetime() . '][' . $level . '] ' . $msg . "\n", FILE_APPEND);
    }
    protected static function get_log_file_for($filename) {
        if (self::$rotate_method === 'day') {
            $log_file = log_dir . '/' . $filename . '-' . clock::get('Y-m-d') . '.log';
        } else if ($rotate_method === 'hour') {
            $log_file = log_dir . '/' . $filename . '-' . clock::get('Y-m-d-H') . '.log';
        } else {
            $log_file = log_dir . '/' . $filename . '.log';
        }
        return $log_file;
    }
    protected static $rotate_method = '';
}
