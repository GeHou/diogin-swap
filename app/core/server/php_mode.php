<?php
/**
 * 控制器，控制助手，过滤器，分派器
 *
 * @copyright Copyright (c) 2009-2012 Jingcheng Zhang <diogin@gmail.com>. All rights reserved.
 * @license   See "LICENSE" file bundled with this distribution.
 */

namespace swap;

use Exception;

dispatcher::__init__();

// [实体] 模板渲染器
abstract class tpl_rendor extends view_rendor {
    public static /* @php */ function set_page_skeleton($page_skeleton) {
        parent::$page_skeleton = $page_skeleton;
    }
    public static /* @php */ function render_tpl($_tpl_file, array $_args = array(), $_escape_args = true) {
        if ($_escape_args) {
            $_args = html::escape($_args);
        }
        extract($_args);
        ob_start();
        try {
            require view_dir . '/' . ltrim($_tpl_file, '/');
            return ob_get_clean();
        } catch (developer_error $e) {
            ob_get_clean();
            throw $e;
        }
    }
    public static /* @php */ function helper_set($key, $value) {
        self::$helper_args[$key] = $value;
    }
    protected static /* @tpl */ function use_tpllet($tpllet_name) {
        loader::load_file(core_dir . '/toolkit/view/tpl/' . $tpllet_name . '.php');
    }
    protected static /* @tpl */ function use_app_tpllet($tpllet_name) {
        $tpllet_file = view_dir . '/tool/tpl/' . $tpllet_name . '.php';
        if (is_readable($tpllet_file)) {
            loader::load_file($tpllet_file);
        }
    }
    protected static /* @tpl */ function link_style($style_name, $in_place = false) {
        if ($in_place) {
            self::echo_css_link(parent::style_url($style_name . '.css', null, false));
        } else if (!array_key_exists($style_name, self::$linked_styles)) {
            self::$linked_styles[$style_name] = $style_name;
        }
    }
    protected static /* @tpl */ function link_script($script_name, $in_place = false, $at_top = false) {
        if ($in_place) {
            self::echo_js_link(parent::script_url($script_name . '.js', null, false));
        } else {
            $linked_scripts =& self::$linked_scripts[$at_top ? 'top' : 'bottom'];
            if (!array_key_exists($script_name, $linked_scripts)) {
                $linked_scripts[$script_name] = $script_name;
            }
        }
    }
    protected static /* @tpl */ function link_third_style($style_name, $in_place = false) {
        if ($in_place) {
            self::echo_css_link(parent::third_url($style_name . '.css', null, false));
        } else if (!array_key_exists($style_name, self::$linked_third_styles)) {
            self::$linked_third_styles[$style_name] = $style_name;
        }
    }
    protected static /* @tpl */ function link_third_script($script_name, $in_place = false, $at_top = false) {
        if ($in_place) {
            self::echo_js_link(parent::third_url($script_name . '.js', null, false));
        } else {
            $linked_third_scripts =& self::$linked_third_scripts[$at_top ? 'top' : 'bottom'];
            if (!array_key_exists($script_name, $linked_third_scripts)) {
                $linked_third_scripts[$script_name] = $script_name;
            }
        }
    }
    protected static /* @tpl */ function link_pss($pss_name, $in_place = false) {
        if ($in_place) {
            self::echo_css_link(parent::cjs_url('style', 'pss.php?link=' . $pss_name, null, false));
        } else if (!array_key_exists($pss_name, self::$linked_psses)) {
            self::$linked_psses[$pss_name] = $pss_name;
        }
    }
    protected static /* @tpl */ function link_pjs($pjs_name, $in_place = false, $at_top = false) {
        if ($in_place) {
            self::echo_js_link(parent::cjs_url('script', 'pjs.php?link=' . $pjs_name, null, false));
        } else {
            $linked_pjses =& self::$linked_pjses[$at_top ? 'top' : 'bottom'];
            if (!array_key_exists($pjs_name, $linked_pjses)) {
                $linked_pjses[$pjs_name] = $pjs_name;
            }
        }
    }
    protected static /* @tpl */ function include_block($block_name, array $alias = array()) {
        if (!in_array($block_name, self::$block_psses)) {
            self::$block_psses[] = $block_name;
            self::$block_pjses[] = $block_name;
        }
        helper::reset();
        helper::set_block_name($block_name);
        $helper_file = helper_dir . '/' . $block_name . '_helper.php';
        $helper = str_replace('/', '_', $block_name) . '_helper';
        if (is_readable($helper_file)) {
            loader::load_file($helper_file);
            $helper::run(array_merge(context::get_primary($alias), self::$helper_args));
        } else {
            helper::set_alias($alias);
            helper::render();
        }
    }
    protected static /* @tpl */ function csrf_url($csrf_role, $target, $for_html = null, $echo = true) {
        if ($for_html === null) {
            $for_html = !framework::is_cjs_mode();
        }
        return url_manager::build_csrf_url($csrf_role, $target, $echo, $for_html);
    }
    protected static /* @tpl */ function csrf_arg($csrf_role, $echo = true) {
        $csrf_arg = visitor::has_role($csrf_role) ? (config::get('technique.csrf_key', url_manager::default_csrf_key) . '=' . visitor::get_role_secret($csrf_role)) : '';
        if ($echo) {
            echo $csrf_arg;
        } else {
            return $csrf_arg;
        }
    }
    protected static /* @tpl */ function csrf_field($csrf_role) {
        if (visitor::has_role($csrf_role)) {
            $csrf_key = config::get('technique.csrf_key', url_manager::default_csrf_key);
            $csrf_value = visitor::get_role_secret($csrf_role);
            echo '<input type="hidden" name="' . $csrf_key . '" value="' . $csrf_value . '" />';
        }
    }
    
    protected static /* @core */ function reset() {
        self::use_tpllet('base');
        parent::use_app_viewlet('base');
        self::use_app_tpllet('base');
        parent::reset();
        self::$target = visitor::get_target();
        self::$linked_styles = array();
        self::$linked_scripts = array('top' => array(), 'bottom' => array());
        self::$linked_third_styles = array();
        self::$linked_third_scripts = array('top' => array(), 'bottom' => array());
        self::$linked_psses = array();
        self::$linked_pjses = array('top' => array(), 'bottom' => array());
        self::$layout_pss = '';
        self::$layout_pjs = '';
        self::$page_pss = '';
        self::$page_pjs = '';
        self::$block_psses = array();
        self::$block_pjses = array();
        self::$helper_args = array();
    }
    protected static /* @core */ function echo_css_link($href) {
        echo '<link rel="stylesheet" href="' . $href . '" type="text/css" media="all" />' . "\n";
    }
    protected static /* @core */ function echo_js_link($src) {
        echo '<script type="text/javascript" src="' . $src . '"></script>' . "\n";
    }
    protected static /* @core */ function change_target_to(target $target) {
        self::$target = $target;
    }
    
    // 可重置的属性
    protected static $target = null;
    protected static $linked_styles = array();
    protected static $linked_scripts = array('top' => array(), 'bottom' => array());
    protected static $linked_third_styles = array();
    protected static $linked_third_scripts = array('top' => array(), 'bottom' => array());
    protected static $linked_psses = array();
    protected static $linked_pjses = array('top' => array(), 'bottom' => array());
    protected static $layout_pss = '';
    protected static $layout_pjs = '';
    protected static $page_pss = '';
    protected static $page_pjs = '';
    protected static $block_psses = array();
    protected static $block_pjses = array();
    protected static $helper_args = array();
}
// [实体] 控制器
abstract class controller extends tpl_rendor {
    public static /* @action */ function dump(/* ... */) {
        ob_start();
        call_user_func_array('var_dump', func_get_args());
        self::send(ob_get_clean());
    }
    public static /* @action */ function method($request_method) {
        if (visitor::request_method() !== strtoupper($request_method)) {
            $except = new visitor_except('method not allowed', 405);
            $except->set_value('allow_list', $request_method);
            throw $except;
        }
    }
    public static /* @action */ function role($role, $method = 'redirect_to', $target = '') {
        if (!visitor::has_role($role)) {
            if ($target === '') {
                throw new visitor_except('role forbidden', 403);
            } else {
                if ($method === 'redirect_to') {
                    self::redirect_to($target);
                } else {
                    self::forward_to($target);
                }
            }
        }
    }
    public static /* @action */ function csrf($csrf_role) {
        if (visitor::has_role($csrf_role)) {
            $csrf_key = config::get('technique.csrf_key', url_manager::default_csrf_key);
            $role_secret = null;
            if (visitor::p_has($csrf_key)) {
                $role_secret = visitor::p_str($csrf_key);
            } else if (visitor::g_has($csrf_key)) {
                $role_secret = visitor::g_str($csrf_key);
            }
            if ($role_secret !== visitor::get_role_secret($csrf_role)) {
                throw new visitor_except('csrf attack', 403);
            }
        }
    }
    public static /* @action */ function set($key, $value) {
        context::set($key, $value);
    }
    /**
     * 把 page.tpl 嵌入 layout.tpl 呈现。
     * 例子：
     *     self::show_page();
     *     self::show_page('page_name');
     *     self::show_page('', 'layout_name');
     *     self::show_page('page_name', 'layout_name');
     */
    public static /* @action */ function show_page(/* ... */) {
        $num_args = func_num_args();
        $func_args = func_get_args();
        $layout_name = null;
        if ($num_args === 0) {
            $target_name = self::get_target_name_from_page_name();
        } else if ($num_args === 1) {
            $target_name = self::get_target_name_from_page_name($func_args[0]);
        } else {
            $target_name = self::get_target_name_from_page_name($func_args[0]);
            $layout_name = $func_args[1];
        }
        parent::$page_pss = parent::$page_pjs = $target_name;
        parent::change_target_to(new target($target_name));
        self::show_with_layout($layout_name);
    }
    /**
     * 把 block.tpl 嵌入 layout.tpl 呈现。
     * 例子：
     *     self::show_block('block_name');
     *     self::show_block('block_name', $alias);
     *     self::show_block('block_name', 'layout_name');
     *     self::show_block('block_name', 'layout_name', $alias);
     */
    public static /* @action */ function show_block(/* ... */) {
        $num_args = func_num_args();
        $func_args = func_get_args();
        $target_block = array('name' => $func_args[0], 'alias' => array());
        $layout_name = null;
        if ($num_args === 1) {
            /* do nothing */
        } else if ($num_args === 2) {
            if (is_array($func_args[1])) {
                $target_block['alias'] = $func_args[1];
            } else {
                $layout_name = $func_args[1];
            }
        } else if ($num_args === 3) {
            if (is_array($func_args[2])) {
                $target_block['alias'] = $func_args[2];
                $layout_name = $func_args[1];
            } else {
                throw new developer_error();
            }
        } else {
            throw new developer_error();
        }
        self::$target_block = $target_block;
        self::show_with_layout($layout_name);
    }
    /**
     * 把 page.tpl 当做 <body>...</body> 呈现。
     * 例子：
     *     self::only_page();
     *     self::only_page('page_name');
     */
    public static /* @action */ function only_page(/* ... */) {
        self::show_page(func_num_args() === 0 ? '' : func_get_arg(0), false);
    }
    /**
     * 把 block.tpl 当做 <body>...</body> 呈现。
     * 例子：
     *     self::only_block('block_name');
     *     self::only_block('block_name', $alias);
     */
    public static /* @action */ function only_block(/* ... */) {
        self::show_block(func_get_arg(0), func_num_args() === 2 ? func_get_arg(1) : array(), false);
    }
    /**
     * 把 page.tpl 的渲染结果当做响应内容发出去。
     * 例子：
     *     self::send_page();
     *     self::send_page('page_name');
     *     self::send_page('page_name', $with_cjs = true);
     */
    public static /* @action */ function send_page(/* ... */) {
        $_num_args = func_num_args();
        $_func_args = func_get_args();
        $_with_cjs = config::get('technique.send_tpl_with_cjs', true);
        if ($_num_args === 0) {
            $_target_name = self::get_target_name_from_page_name();
        } else if ($_num_args === 1) {
            if (is_bool($_func_args[0])) {
                $_target_name = self::get_target_name_from_page_name();
                $_with_cjs = $_func_args[0];
            } else {
                $_target_name = self::get_target_name_from_page_name($_func_args[0]);
            }
        } else {
            if (is_bool($_func_args[1])) {
                $_target_name = self::get_target_name_from_page_name($_func_args[0]);
                $_with_cjs = $_func_args[1];
            } else {
                throw new developer_error();
            }
        }
        $_target = new target($_target_name);
        $_html = parent::render_tpl('page/' . $_target->get_target_file() . '.tpl', context::get_escaped(), false);
        if ($_with_cjs) {
            ob_start();
            parent::echo_css_link(parent::cjs_url('style', 'pss.php?page=' . $_target->get_target_name(), null, false));
            echo $_html;
            parent::echo_js_link(parent::cjs_url('script', 'pjs.php?page=' . $_target->get_target_name(), null, false));
            self::send(ob_get_clean());
        } else {
            self::send($_html);
        }
    }
    /**
     * 把 block.tpl 的渲染结果当做响应内容发出去。
     * 例子：
     *     self::send_block('block_name');
     *     self::send_block('block_name', $alias);
     *     self::send_block('block_name', $with_cjs = true);
     *     self::send_block('block_name', $with_cjs = true, $alias);
     */
    public static /* @action */ function send_block(/* ... */) {
        $num_args = func_num_args();
        $func_args = func_get_args();
        $block_name = $func_args[0];
        $alias = array();
        $with_cjs = config::get('technique.send_tpl_with_cjs', true);
        if ($num_args === 1) {
            /* do nothing */
        } else if ($num_args === 2) {
            if (is_array($func_args[1])) {
                $alias = $func_args[1];
            } else if (is_bool($func_args[1])) {
                $with_cjs = $func_args[1];
            } else {
                throw new developer_error();
            }
        } else if ($num_args === 3) {
            $alias = $func_args[2];
            if (is_bool($func_args[1])) {
                $with_cjs = $func_args[1];
            } else {
                throw new developer_error();
            }
        } else {
            throw new developer_error();
        }
        ob_start();
        parent::include_block($block_name, $alias);
        $html = ob_get_clean();
        if ($with_cjs) {
            ob_start();
            parent::echo_css_link(parent::cjs_url('style', 'pss.php?block=' . $block_name, null, false));
            echo $html;
            parent::echo_js_link(parent::cjs_url('script', 'pjs.php?block=' . $block_name, null, false));
            self::send(ob_get_clean());
        } else {
            self::send($html);
        }
    }
    public static /* @action */ function json_result($result, $msg = '', $code = 0, $extra = '') {
        self::send_json(array('result' => $result, 'msg' => $msg, 'code' => $code, 'extra' => $extra));
    }
    public static /* @action */ function send_json($value) {
        self::send(json_encode($value), visitor::is_ie() ? 'text/plain; charset=utf-8' : 'application/json');
    }
    public static /* @action */ function puts($str) {
        visitor::add_content($str);
    }
    public static /* @action */ function send($content, $content_type = 'text/html; charset=utf-8') {
        visitor::set_content_type($content_type);
        visitor::set_content($content);
        throw new action_return();
    }
    public static /* @action */ function send_file($file, $filename_ext = '') {
        if ($filename_ext === '') {
            $filename_ext = basename($file);
        }
        if (visitor::is_ie()) {
            $filename_ext = urlencode($filename_ext);
            $content_type = 'application/force-download';
        } else {
            $content_type = 'application/octet-stream';
        }
        visitor::set_header('Content-Disposition', 'attachment; filename=' . $filename_ext);
        visitor::set_header('Content-Transfer-Encoding', 'binary');
        $content = file_get_contents($file);
        visitor::set_header('Content-Length', strlen($content));
        self::send($content, $content_type);
    }
    public static /* @action */ function redirect_to($target) {
        self::redirect_to_url(url_manager::build_url($target, false, false));
    }
    public static /* @action */ function redirect_to_url($url) {
        visitor::redirect_to_url($url);
        throw new action_return();
    }
    public static /* @action */ function forward_to($target, $deny_self = true) {
        if (!$target instanceof target) {
            $target = new target($target);
        }
        if ($deny_self && $target->get_target_name() === parent::$target->get_target_name()) {
            throw new developer_error('cannot forward to self');
        }
        $forward = new action_forward();
        $forward->set_target($target);
        throw $forward;
    }
    public static /* @action */ function forward_404($msg = '') {
        throw new visitor_except($msg, 404);
    }
    public static /* @action */ function forward_404_if($condition, $msg = '') {
        if ($condition) {
            throw new visitor_except($msg, 404);
        }
    }
    public static /* @action */ function set_title($title, $escape_html = true) {
        self::$title = $escape_html ? html::escape($title) : $title;
    }
    public static /* @action */ function set_keywords($keywords, $escape_html = true) {
        self::$keywords = $escape_html ? html::escape($keywords) : $keywords;
    }
    public static /* @action */ function set_description($description, $escape_html = true) {
        self::$description = $escape_html ? html::escape($description) : $description;
    }
    public static /* @action */ function set_author($author, $escape_html = true) {
        self::$author = $escape_html ? html::escape($author) : $author;
    }
    public static /* @action */ function set_refresh_to($target, $seconds = 2) {
        self::$refresh_to = array(url_manager::build_url($target, false, true), $seconds);
    }
    
    protected static /* @tpl */ function echo_html() {
        if (self::$target_block === array()) { /* 渲染 page */
            echo parent::render_tpl('page/' . parent::$target->get_target_file() . '.tpl', context::get_escaped(), false);
        } else { /* 渲染 block */
            parent::include_block(self::$target_block['name'], self::$target_block['alias']);
        }
    }
    
    public static /* @core */ function reset() {
        parent::reset();
        self::$title = config::get('business.default_title', '');
        self::$keywords = config::get('business.default_keywords', '');
        self::$description = config::get('business.default_description', '');
        self::$author = config::get('business.default_author', '');
        self::$refresh_to = array();
        self::$target_block = array();
    }
    
    protected static /* @core */ function get_target_name_from_page_name($page_name = null) {
        if ($page_name === null || $page_name === '') {
            $target_name = parent::$target->get_target_name();
        } else if (in_string('-', $page_name)) {
            $target_name = $page_name;
        } else if (in_string('/', $page_name)) {
            if (parent::$target->has_module()) {
                $target_name = parent::$target->get_module_name() . '-' . $page_name;
            } else {
                $target_name = $page_name;
            }
        } else {
            $target_name = parent::$target->get_controller_name() . '/' . $page_name;
            if (parent::$target->has_module()) {
                $target_name = parent::$target->get_module_name() . '-' . $target_name;
            }
        }
        return $target_name;
    }
    protected static /* @core */ function show_with_layout($_layout_name) {
        if ($_layout_name === null) {
            $_controller = get_called_class();
            if (property_exists($_controller, 'layout')) {
                if ($_controller::$layout === null) {
                    throw new developer_error('default layout cannot be null(which itself means use default layout...)');
                }
                $_layout_name = $_controller::$layout;
            } else {
                $_layout_name = false;
            }
        }
        ob_start();
        if ($_layout_name === false) {
            self::echo_html();
        } else {
            parent::$layout_pss = parent::$layout_pjs = $_layout_name;
            // layout tpl 里会调 self::echo_html()
            echo parent::render_tpl('layout/' . $_layout_name . '.tpl', context::get_escaped(), false);
        }
        $_html = ob_get_clean();
        ob_start();
        // skeleton tpl 里会 echo $_html;
        require core_dir . '/view/page/' . parent::$page_skeleton . '.tpl';
        self::send(ob_get_clean());
    }
    protected static /* @core */ function echo_top_links() {
        // link 进来的 third css
        foreach (parent::$linked_third_styles as $style_name) {
            parent::echo_css_link(parent::third_url($style_name . '.css', null, false));
        }
        // link 进来的 css
        foreach (parent::$linked_styles as $style_name) {
            parent::echo_css_link(parent::style_url($style_name . '.css', null, false));
        }
        // link 进来的 pss
        $psses = array();
        if (parent::$linked_psses === array()) {
            $psses[] = 'link=global';
        } else {
            $linked_psses = parent::$linked_psses;
            if (!array_key_exists('global', $linked_psses)) {
                array_unshift($linked_psses, 'global');
            }
            $psses[] = 'link=' . implode(',', $linked_psses);
        }
        if (parent::$layout_pss !== '') {
            $psses[] = 'layout=' . parent::$layout_pss;
        }
        if (parent::$page_pss !== '') {
            $psses[] = 'page=' . parent::$page_pss;
        }
        if (parent::$block_psses !== array()) {
            $psses[] = 'block=' . implode(',', parent::$block_psses);
        }
        parent::echo_css_link(parent::cjs_url('style', 'pss.php?' . implode('&amp;', $psses), null, false));
        // link 进来的放在顶部的 third js
        foreach (parent::$linked_third_scripts['top'] as $script_name) {
            parent::echo_js_link(parent::third_url($script_name . '.js', null, false));
        }
        // link 进来的放在顶部的 js
        foreach (parent::$linked_scripts['top'] as $script_name) {
            parent::echo_js_link(parent::script_url($script_name . '.js', null, false));
        }
        // link 进来的放在顶部的 pjs
        if (parent::$linked_pjses['top'] !== array()) {
            $linked_top_pjses = parent::$linked_pjses['top'];
            if (!array_key_exists('global', $linked_top_pjses)) {
                array_unshift($linked_top_pjses, 'global');
            }
            parent::echo_js_link(parent::cjs_url('script', 'pjs.php?link=' . implode(',', $linked_top_pjses), null, false));
        }
    }
    protected static /* @core */ function echo_bottom_links() {
        // link 进来的放在底部的 third js
        foreach (parent::$linked_third_scripts['bottom'] as $script_name) {
            parent::echo_js_link(parent::third_url($script_name . '.js', null, false));
        }
        // link 进来的放在底部的 js
        foreach (parent::$linked_scripts['bottom'] as $script_name) {
            parent::echo_js_link(parent::script_url($script_name . '.js', null, false));
        }
        // link 进来的放在底部的 pjs
        $pjses = array();
        if (parent::$linked_pjses['bottom'] === array()) {
            if (parent::$linked_pjses['top'] === array()) {
                $pjses[] = 'link=global';
            }
        } else {
            $linked_bottom_pjses = parent::$linked_pjses['bottom'];
            if (parent::$linked_pjses['top'] === array() && !array_key_exists('global', $linked_bottom_pjses)) {
                array_unshift($linked_bottom_pjses, 'global');
            }
            $pjses[] = 'link=' . implode(',', $linked_bottom_pjses);
        }
        if (parent::$layout_pjs !== '') {
            $pjses[] = 'layout=' . parent::$layout_pjs;
        }
        if (parent::$page_pjs !== '') {
            $pjses[] = 'page=' . parent::$page_pjs;
        }
        if (parent::$block_pjses !== array()) {
            $pjses[] = 'block=' . implode(',', parent::$block_pjses);
        }
        if ($pjses !== array()) {
            parent::echo_js_link(parent::cjs_url('script', 'pjs.php?' . implode('&amp;', $pjses), null, false));
        }
    }
    
    // 可重置的属性
    protected static $title = '';
    protected static $keywords = '';
    protected static $description = '';
    protected static $author = '';
    protected static $refresh_to = array();
    protected static $target_block = array();
}
// [实体] block 模板控制器助手
abstract class helper extends tpl_rendor {
    // abstract public static function run(array $context);
    
    public static /* @run */ function set($key, $value, $to_context = false) {
        if ($to_context) {
            context::set($key, $value);
        } else {
            self::$vars[$key] = html::escape($value);
        }
    }
    public static /* @run */ function render() {
        $args = context::get_escaped(self::$alias);
        foreach (self::$vars as $name => $value) {
            $args['_' . $name] = $value;
        }
        echo parent::render_tpl('block/' . self::$block_name . '.tpl', $args, false);
    }
    
    public static /* @core */ function set_block_name($block_name) {
        self::$block_name = $block_name;
    }
    public static /* @core */ function set_alias($alias) {
        self::$alias = $alias;
    }
    public static /* @core */ function reset() {
        self::$vars = array();
    }
    
    // 可重置的属性
    protected static $block_name = '';
    protected static $alias = array();
    protected static $vars = array();
}
// [实体] 前置拦截过滤器
abstract class before_filter {
    // abstract public static function run($args);
    
    protected static /* @run */ function forward_to($target) {
        $forward = new action_forward();
        if (!$target instanceof target) {
            $target = new target($target);
        }
        $forward->set_target($target);
        throw $forward;
    }
    protected static /* @run */ function forward_403($msg = '') {
        throw new visitor_except($msg, 403);
    }
    protected static /* @run */ function forward_404($msg = '') {
        throw new visitor_except($msg, 404);
    }
    protected static /* @run */ function forward_405($msg = '') {
        throw new visitor_except($msg, 405);
    }
    protected static /* @run */ function forward_406($msg = '') {
        throw new visitor_except($msg, 406);
    }
}
// [实体] 后置拦截过滤器
abstract class after_filter {
    // abstract public static function run($args);
}
// [实体] 自动加载拦截过滤器
class autoload_filter extends before_filter {
    public static function run(array $_files) {
        // array(swap\library_dir . '/library_one.php', swap\library_dir . '/library_two.php')
        foreach ($_files as $_file) {
            loader::load_file($_file);
        }
    }
}

// [内部][类型] 分派过程返回标志
class dispatch_return extends Exception {}
// [内部][类型] action 返回标志
class action_return extends Exception {}
// [内部][类型] action 转移标志
class action_forward extends Exception {
    public function set_target(target $target) {
        $this->target = $target;
    }
    public function get_target() {
        return $this->target;
    }
    
    protected $target = null;
}

// [内部][实体] 变量上下文容器
class context {
    public static function set($key, $value) {
        self::$primary[$key] = $value;
        self::$escaped[$key] = html::escape($value);
    }
    public static function get_primary(array $alias = array()) {
        return self::get_property('primary', $alias);
    }
    public static function get_escaped(array $alias = array()) {
        return self::get_property('escaped', $alias);
    }
    
    protected static function get_property($property_name, array $alias) {
        $context = self::${$property_name};
        foreach ($alias as $new_name => $original_name) {
            $context[$new_name] = array_key_exists($original_name, $context) ? $context[$original_name] : null;
        }
        return $context;
    }
    
    protected static $primary = array();
    protected static $escaped = array();
}
// [内部][实体] 分派器
class dispatcher {
    const max_forward_times = 8;
    const except_controller_name = 'except';
    
    public static function __init__() {
        self::$global_filters = config::get('technique.global_filters', null);
    }
    public static function dispatch_target() {
        self::load_global_file();
        $target = url_manager::parse_uri(visitor::request_uri(), visitor::request_host());
        $forward_times = 0;
        while (true) {
            if ($forward_times >= self::max_forward_times) {
                throw new developer_error('too many forwards');
            }
            try {
                self::dispatch_to($target);
                break;
            } catch (action_forward $forward) {
                $target = $forward->get_target();
                visitor::forward_cookies();
                $forward_times++;
                continue;
            } catch (dispatch_return $return) {
                return;
            }
        }
    }
    
    protected static function load_global_file() {
        if (defined('swap\library_dir')) {
            $global_file = library_dir . '/global.php';
            if (is_readable($global_file)) {
                loader::load_file($global_file);
            }
        }
    }
    protected static function dispatch_to(target $target) {
        try {
            if (config::get('technique.site_closed', false)) {
                throw new server_except('site closed', 503);
            }
            visitor::restore_roles();
            visitor::set_target($target);
            list($controller_name, $action_name) = $target->get_target_pair();
            $controller = $controller_name . '_controller';
            $controller_file = controller_dir . '/';
            if ($target->has_module()) {
                $controller_file .= $target->get_module_name() . '/';
            }
            $controller_file .= $controller . '.php';
            if (!is_readable($controller_file)) {
                throw new visitor_except('controller "' . $controller_name . '" does not exist', 404);
            }
            loader::load_file($controller_file);
            $action = $action_name . '_action';
            if (!is_callable(array($controller, $action), false)) {
                throw new visitor_except('action "' . $action_name . '" does not exist', 404);
            }
            self::run_action($controller_name, $controller, $action, null, true);
        } catch (Exception $e) {
            self::dispatch_except($e);
        }
    }
    protected static function dispatch_except(Exception $e) {
        if ($e instanceof except || $e instanceof error) {
            $except_code = $e->getCode();
            if (!($e instanceof visitor_except) && $except_code !== 503) {
                $except_code = 500;
            }
            if (isset(self::$except_handlers[$except_code])) {
                $controller_name = self::except_controller_name;
                $controller = $controller_name . '_controller';
                $controller_file = controller_dir . '/' . $controller_name . '_controller.php';
                if (is_readable($controller_file)) {
                    loader::load_file($controller_file);
                    $action_name = self::$except_handlers[$except_code];
                    $action = $action_name . '_action';
                    if (is_callable(array($controller, $action), false)) {
                        visitor::set_target(new target($controller_name . '/' . $action_name));
                        self::run_action($controller_name, $controller, $action, $e, false);
                        return;
                    }
                }
            }
        }
        throw $e;
    }
    protected static function run_action($controller_name, $controller, $action, $arg, $run_filters) {
        controller::reset();
        if ($run_filters) {
            $have_global_filters = self::$global_filters !== null;
            if ($have_global_filters) {
                self::run_global_filters('before');
            }
            self::run_controller_filters('before', $controller_name, $action);
        }
        try {
            if (is_callable(array($controller, 'before_run'), false)) {
                $controller::before_run();
            }
            $controller::$action($arg);
            if (is_callable(array($controller, 'after_run'), false)) {
                $controller::after_run();
            }
        } catch (action_return $return) {}
        if ($run_filters) {
            self::run_controller_filters('after', $controller_name, $action);
            if ($have_global_filters) {
                self::run_global_filters('after');
            }
        }
    }
    protected static function run_global_filters($filter_type) {
        if (isset(self::$global_filters[$filter_type]) && is_array(self::$global_filters[$filter_type])) {
            foreach (self::$global_filters[$filter_type] as $filter => $filter_arg) {
                self::run_filter($filter_type, $filter, $filter_arg);
            }
        }
    }
    protected static function run_controller_filters($filter_type, $controller_name, $action) {
        $args_getter = $filter_type . '_filters';
        $controller = $controller_name . '_controller';
        if (!is_callable(array($controller, $args_getter))) {
            return;
        }
        $filter_args = $controller::$args_getter();
        if (!is_array($filter_args)) {
            return;
        }
        foreach ($filter_args as $filter => $action_to_arg) {
            if (!is_array($action_to_arg)) {
                throw new developer_error('filter arg should be an assoc array with action as key, config as value');
            }
            if (isset($action_to_arg[$action])) {
                $filter_arg = $action_to_arg[$action];
            } else if (isset($action_to_arg['*'])) {
                $filter_arg = $action_to_arg['*'];
            } else {
                continue;
            }
            self::run_filter($filter_type, $filter, $filter_arg);
        }
    }
    protected static function run_filter($filter_type, $filter, $filter_arg) {
        if (!class_exists($filter, true)) {
            $filter = 'swap\\' . $filter;
            if (!class_exists($filter, true)) {
                throw new developer_error("cannot find filter: {$filter}");
            }
        }
        if (!is_subclass_of($filter, 'swap\\' . $filter_type . '_filter')) {
            throw new developer_error("filter: {$filter} is not a " . $filter_type . ' filter');
        }
        $filter::run($filter_arg);
    }
    
    protected static $except_handlers = array(
        403 => 'access_denied',
        404 => 'target_missing',
        405 => 'method_denied',
        406 => 'browser_denied',
        500 => 'server_except',
        503 => 'site_closed',
    );
    protected static $global_filters = null;
}
