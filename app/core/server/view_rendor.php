<?php
/**
 * 视图（view，包括 tpl/pss/pjs）渲染器
 *
 * @copyright Copyright (c) 2009-2012 Jingcheng Zhang <diogin@gmail.com>. All rights reserved.
 * @license   See "LICENSE" file bundled with this distribution.
 */

namespace swap;

view_rendor::__init__();

/**
 * [实体] 视图渲染器
 */
abstract class view_rendor {
    public static /* @php */ function set_skin($skin) {
        self::$skin = $skin;
    }
    public static /* @view */ function use_app_viewlet($viewlet_name) {
        $viewlet_file = view_dir . '/tool/' . $viewlet_name . '.php';
        if (is_readable($viewlet_file)) {
            loader::load_file($viewlet_file);
        }
    }
    public static /* @view */ function media_url($media_file, $for_html = null, $echo = true) {
        $media_file = ltrim($media_file, '/');
        return self::static_url('media', $media_file, $for_html, $echo);
    }
    public static /* @view */ function skin_media_url($media_file, $for_html = null, $echo = true) {
        $media_file = ltrim($media_file, '/');
        if (self::$skin !== '') {
            $media_file = 'skin/' . self::$skin . '/' . $media_file;
        }
        return self::media_url($media_file, $for_html, $echo);
    }
    public static /* @view */ function style_url($style_file, $for_html = null, $echo = true) {
        return self::static_url('style', ltrim($style_file, '/'), $for_html, $echo);
    }
    public static /* @view */ function script_url($script_file, $for_html = null, $echo = true) {
        return self::static_url('script', ltrim($script_file, '/'), $for_html, $echo);
    }
    public static /* @view */ function third_url($third_file, $for_html = null, $echo = true) {
        return self::static_url('third', ltrim($third_file, '/'), $for_html, $echo);
    }
    public static /* @view */ function pss_url($pss_name, $for_html = null, $echo = true) {
        return self::cjs_url('style', 'pss.php?link=' . $pss_name, $for_html, $echo);
    }
    public static /* @view */ function pjs_url($pjs_name, $for_html = null, $echo = true) {
        return self::cjs_url('script', 'pjs.php?link=' . $pjs_name, $for_html, $echo);
    }
    public static /* @view */ function php_url($target, $for_html = null, $echo = true) {
        list($target, $for_html) = self::regularize($target, $for_html);
        return url_manager::build_url($target, $echo, $for_html);
    }
    public static /* @view */ function php_uri($target, $for_html = null, $echo = true) {
        list($target, $for_html) = self::regularize($target, $for_html);
        return url_manager::build_url($target, $echo, $for_html, false);
    }
    public static /* @view */ function upload_url($upload_file, $echo = true) {
        if (self::$upload_domain === '') {
            $upload_url = url_manager::www_url('upload');
        } else {
            $upload_url = (framework::is_https() ? 'https://' : 'http://') . self::$upload_domain . '/upload';
        }
        $upload_url .= '/' . ltrim($upload_file, '/');
        if ($echo) {
            echo $upload_url;
        } else {
            return $upload_url;
        }
    }
    
    public static /* @core */ function __init__() {
        self::$media_domain = config::get('technique.media_domain', '');
        self::$style_domain = config::get('technique.style_domain', '');
        self::$script_domain = config::get('technique.script_domain', '');
        self::$third_domain = config::get('technique.third_domain', '');
        self::$static_version = config::get('technique.static_version', 0);
        self::$version_key = config::get('technique.version_key', url_manager::default_version_key);
        self::$upload_domain = config::get('technique.upload_domain', '');
    }
    
    protected static function reset() {
        self::$page_skeleton = config::get('technique.page_skeleton', 'xhtml');
        self::$skin = '';
    }
    protected static function static_url($static_type, $static_file, $for_html = null, $echo = true) {
        $static_domain = self::${$static_type . '_domain'};
        if ($static_domain === '') {
            $static_url = url_manager::www_url($static_type);
        } else {
            $static_url = (framework::is_https() ? 'https://' : 'http://') . $static_domain . '/' . $static_type;
        }
        $static_url .= '/' . ltrim($static_file, '/');
        if (self::$static_version !== 0) {
            $and_char = self::get_and_char($for_html);
            $static_url .= (strpos($static_url, '?') === false ? '?' : $and_char) . self::$version_key . '=' . self::$static_version;
        }
        if ($echo) {
            echo $static_url;
        } else {
            return $static_url;
        }
    }
    protected static function cjs_url($cjs_type, $cjs_uri, $for_html = null, $echo = true) {
        $static_domain = self::${$cjs_type . '_domain'};
        if ($static_domain === '') {
            $cjs_url = url_manager::www_url(''); # 包含 path_prefix
        } else {
            $cjs_url = (framework::is_https() ? 'https://' : 'http://') . $static_domain; # 独立域名不包含 path_prefix
        }
        $cjs_url .= '/' . ltrim($cjs_uri, '/');
        $and_char = self::get_and_char($for_html);
        if (self::$skin !== '') {
            $cjs_url .= $and_char . 'skin=' . self::$skin;
        }
        $cjs_url .= $and_char . self::$version_key . '=' . self::$static_version;
        if ($echo) {
            echo $cjs_url;
        } else {
            return $cjs_url;
        }
    }
    protected static function regularize($target, $for_html) {
        if ($for_html === null) { # 如果是 null，则根据当前模式自动判断
            $for_html = !framework::is_cjs_mode();
        }
        if ($for_html) {
            if ($target instanceof target) {
                $target = $target->as_array();
            }
            $target = html::unescape($target);
        }
        return array($target, $for_html);
    }
    protected static function get_and_char($for_html) {
        if ($for_html === null) {
            return framework::is_cjs_mode() ? '&' : '&amp;';
        } else {
            return $for_html ? '&amp;' : '&';
        }
    }
    
    // 只读的属性
    protected static $media_domain = '';
    protected static $style_domain = '';
    protected static $script_domain = '';
    protected static $third_domain = '';
    protected static $static_version = 0;
    protected static $version_key = '';
    protected static $upload_domain = '';
    // 可重设的属性
    protected static $page_skeleton = 'xhtml';
    protected static $skin = '';
}
