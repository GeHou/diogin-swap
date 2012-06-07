<?php
/**
 * 收集和渲染动态的 pss 和 pjs 文件
 *
 * @copyright Copyright (c) 2009-2012 Jingcheng Zhang <diogin@gmail.com>. All rights reserved.
 * @license   See "LICENSE" file bundled with this distribution.
 */

namespace swap;

/**
 * [实体] pss, pjs 渲染器
 */
class cjs_rendor extends view_rendor {
    protected static /* cjs */ function use_cjslet($cjslet_name) {
        loader::load_file(core_dir . '/toolkit/view/cjs/' . $cjslet_name . '.php');
    }
    protected static /* pss */ function use_psslet($psslet_name) {
        loader::load_file(core_dir . '/toolkit/view/cjs/pss/' . $psslet_name . '.php');
    }
    protected static /* pjs */ function use_pjslet($pjslet_name) {
        loader::load_file(core_dir . '/toolkit/view/cjs/pjs/' . $pjslet_name . '.php');
    }
    protected static /* cjs */ function use_app_cjslet($cjslet_name) {
        $cjslet_file = view_dir . '/tool/cjs/' . $cjslet_name . '.php';
        if (is_readable($cjslet_file)) {
            loader::load_file($cjslet_file);
        }
    }
    protected static /* pss */ function use_app_psslet($psslet_name) {
        $psslet_file = view_dir . '/tool/cjs/pss/' . $psslet_name . '.php';
        if (is_readable($psslet_file)) {
            loader::load_file($psslet_file);
        }
    }
    protected static /* pjs */ function use_app_pjslet($pjslet_name) {
        $pjslet_file = view_dir . '/tool/cjs/pjs/' . $pjslet_name . '.php';
        if (is_readable($pjslet_file)) {
            loader::load_file($pjslet_file);
        }
    }
    
    public static /* @core */ function render_for(target $target) {
        visitor::set_target($target);
        parent::set_skin($target->get_param('skin', ''));
        self::use_cjslet('base');
        parent::use_app_viewlet('base');
        self::use_app_cjslet('base');
        return framework::is_pss_mode() ? self::render_pss_for($target) : self::render_pjs_for($target);
    }
    
    protected static /* @core */ function render_pss_for(target $_target) {
        self::use_psslet('base');
        self::use_app_psslet('base');
        ob_start();
        foreach ($_target->get_param('link', array()) as $_linked_name) {
            $_file = 'pss/' . $_linked_name . '.pss';
            $_pss_file = view_dir . '/' . $_file;
            if (is_readable($_pss_file)) {
                if (is_debug) {
                    echo "\n/******** {$_file} ********/\n\n";
                }
                require $_pss_file;
            }
        }
        $_layout_name = $_target->get_param('layout', '');
        if ($_layout_name !== '') {
            $_file = 'layout/' . $_layout_name . '.pss';
            $_pss_file = view_dir . '/' . $_file;
            if (is_readable($_pss_file)) {
                if (is_debug) {
                    echo "\n/******** {$_file} ********/\n\n";
                }
                require $_pss_file;
            }
        }
        foreach ($_target->get_param('block', array()) as $_block_name) {
            $_file = 'block/' . $_block_name . '.pss';
            $_pss_file = view_dir . '/' . $_file;
            if (is_readable($_pss_file)) {
                if (is_debug) {
                    echo "\n/******** {$_file} ********/\n\n";
                }
                require $_pss_file;
            }
        }
        if ($_target->get_target_name() !== '') {
            $_file = 'page/' . $_target->get_target_file('.pss');
            $_pss_file = view_dir . '/' . $_file;
            if (is_readable($_pss_file)) {
                if (is_debug) {
                    echo "\n/******** {$_file} ********/\n\n";
                }
                require $_pss_file;
            }
        }
        if (parent::$skin !== '') {
            foreach ($_target->get_param('link', array()) as $_linked_name) {
                $_file = 'skin/' . parent::$skin . '/pss/' . $_linked_name . '.pss';
                $_pss_file = view_dir . '/' . $_file;
                if (is_readable($_pss_file)) {
                    if (is_debug) {
                        echo "\n/******** {$_file} ********/\n\n";
                    }
                    require $_pss_file;
                }
            }
            $_layout_name = $_target->get_param('layout', '');
            if ($_layout_name !== '') {
                $_file = 'skin/' . parent::$skin . '/layout/' . $_layout_name . '.pss';
                $_pss_file = view_dir . '/' . $_file;
                if (is_readable($_pss_file)) {
                    if (is_debug) {
                        echo "\n/******** {$_file} ********/\n\n";
                    }
                    require $_pss_file;
                }
            }
            foreach ($_target->get_param('block', array()) as $_block_name) {
                $_file = 'skin/' . parent::$skin . '/block/' . $_block_name . '.pss';
                $_pss_file = view_dir . '/' . $_file;
                if (is_readable($_pss_file)) {
                    if (is_debug) {
                        echo "\n/******** {$_file} ********/\n\n";
                    }
                    require $_pss_file;
                }
            }
            if ($_target->get_target_name() !== '') {
                $_file = 'skin/' . parent::$skin . '/page/' . $_target->get_target_file('.pss');
                $_pss_file = view_dir . '/' . $_file;
                if (is_readable($_pss_file)) {
                    if (is_debug) {
                        echo "\n/******** {$_file} ********/\n\n";
                    }
                    require $_pss_file;
                }
            }
        }
        $_pss = ob_get_clean();
        if (config::get('technique.minify_cjs', false)) {
            $_pss = self::minify_pss($_pss);
        }
        return $_pss;
    }
    protected static /* @core */ function render_pjs_for(target $_target) {
        self::use_pjslet('base');
        self::use_app_pjslet('base');
        ob_start();
        foreach ($_target->get_param('link', array()) as $_linked_name) {
            $_file = 'pjs/' . $_linked_name . '.pjs';
            $_pjs_file = view_dir . '/' . $_file;
            if (is_readable($_pjs_file)) {
                if (is_debug) {
                    echo "\n/******** {$_file} ********/\n\n";
                }
                require $_pjs_file;
            }
        }
        $_layout_name = $_target->get_param('layout', '');
        if ($_layout_name !== '') {
            $_file = 'layout/' . $_layout_name . '.pjs';
            $_pjs_file = view_dir . '/' . $_file;
            if (is_readable($_pjs_file)) {
                if (is_debug) {
                    echo "\n/******** {$_file} ********/\n\n";
                }
                require $_pjs_file;
            }
        }
        foreach ($_target->get_param('block', array()) as $_block_name) {
            $_file = 'block/' . $_block_name . '.pjs';
            $_pjs_file = view_dir . '/' . $_file;
            if (is_readable($_pjs_file)) {
                if (is_debug) {
                    echo "\n/******** {$_file} ********/\n\n";
                }
                require $_pjs_file;
            }
        }
        if ($_target->get_target_name() !== '') {
            $_file = 'page/' . $_target->get_target_file('.pjs');
            $_pjs_file = view_dir . '/' . $_file;
            if (is_readable($_pjs_file)) {
                if (is_debug) {
                    echo "\n/******** {$_file} ********/\n\n";
                }
                require $_pjs_file;
            }
        }
        $_pjs = ob_get_clean();
        if (config::get('technique.minify_cjs', false)) {
            $_pjs = self::minify_pjs($_pjs);
        }
        return $_pjs;
    }
    protected static /* @core */ function minify_pss($pss, $line_break_pos = -1) {
        /**
         * Original author: Julien Lecomte - http://www.julienlecomte.net/
         * Copyright (c) 2011 Yahoo! Inc. All rights reserved.
         * The copyrights embodied in the content of this function are licensed
         * by Yahoo! Inc. under the BSD (revised) open source license.
         *
         * PHP version ported by: Lazyboy Wu <lazyboy_wu [at] gmail.com>
         */
        $start_index = $end_index = $i = $max = 0;
        $preserved_tokens = $comments = array();
        $token = $placeholder = '';
        $total_len = strlen($pss);
        while (($start_index = strpos($pss, '/*', $start_index)) !== false) {
            $end_index = strpos($pss, '*/', $start_index + 2);
			if ($end_index < 0) {
                $end_index = $total_len;
            }
            $token = substr($pss, $start_index + 2, $end_index - $start_index - 2);			
            $comments[] = $token;
            $pss = substr($pss, 0, $start_index + 2) . '___YUICSSMIN_PRESERVE_CANDIDATE_COMMENT_' . (count($comments) - 1) . '___' . substr($pss, $end_index);
            $start_index += 2;
        }
        $pss = preg_replace_callback("/(\"([^\\\\\"]|\\\\.|\\\\)*\")|(\'([^\\\\\']|\\\\.|\\\\)*\')/", function ($matches) use (&$preserved_tokens, &$comments) {
            $match = $matches[0];
            $quote = $match[0];
            $match = substr($match, 1, -1);
            if (strpos($match, '___YUICSSMIN_PRESERVE_CANDIDATE_COMMENT_') !== false) {
                for ($i = 0, $max = count($comments); $i < $max; $i++) {
                    $match = str_replace('___YUICSSMIN_PRESERVE_CANDIDATE_COMMENT_' . $i . '___', $comments[$i], $match);
                }
            }
            $match = preg_replace('/progid:DXImageTransform\.Microsoft\.Alpha\(Opacity=/i', 'alpha(opacity=', $match);
            $preserved_tokens[] = $match;
            return $quote . '___YUICSSMIN_PRESERVED_TOKEN_' . (count($preserved_tokens) - 1) . '___' . $quote;
        }, $pss);
        for ($i = 0, $max = count($comments); $i < $max; $i++) {
            $token = $comments[$i];
            $placeholder = '___YUICSSMIN_PRESERVE_CANDIDATE_COMMENT_' . $i . '___';
            if ($token && $token[0] === '!') {
                $preserved_tokens[] = $token;
                $pss = str_replace($placeholder, '___YUICSSMIN_PRESERVED_TOKEN_' . (count($preserved_tokens) - 1) . '___', $pss);
                continue;
            }
            if ($token && $token[strlen($token) - 1] === '\\') {
                $preserved_tokens[] = '\\';
                $pss = str_replace($placeholder, '___YUICSSMIN_PRESERVED_TOKEN_' . (count($preserved_tokens) - 1) . '___', $pss);
                $i++;
                $preserved_tokens[] = '';
                $pss = str_replace('___YUICSSMIN_PRESERVE_CANDIDATE_COMMENT_' . $i . '___', '___YUICSSMIN_PRESERVED_TOKEN_' . (count($preserved_tokens) - 1) . '___', $pss);
                continue;
            }
            if (strlen($token) === 0) {
                $start_index = (int)strpos($pss, $placeholder);
                if ($start_index > 2) {
                    if ($pss[$start_index - 3] === '>') {
                        $preserved_tokens[] = '';
                        $pss = str_replace($placeholder, '___YUICSSMIN_PRESERVED_TOKEN_' . (count($preserved_tokens) - 1) . '___', $pss);
                    }
                }
            }
            $pss = str_replace('/*' . $placeholder . '*/', '', $pss);

        }
        $pss = preg_replace('/\s+/', ' ', $pss);
        $pss = preg_replace_callback('/(^|\\})(([^\\{:])+:)+([^\\{]*\\{)/', function ($matches) {
			return str_replace(':', '___YUICSSMIN_PSEUDOCLASSCOLON___', $matches[0]);
        }, $pss);		
        $pss = preg_replace('/\s+([!{};:>+\(\)\],])/', '$1', $pss);
        $pss = str_replace('___YUICSSMIN_PSEUDOCLASSCOLON___', ':', $pss);
        $pss = preg_replace('/:first-(line|letter)(\{|,)/', ':first-$1 $2', $pss);
        $pss = preg_replace('/\*\/ /', '*/', $pss);
        $pss = preg_replace('/^(.*)(@charset "[^"]*";)/i', '$2$1', $pss);
        $pss = preg_replace('/^(\s*@charset [^;]+;\s*)+/i', '$1', $pss);
        $pss = preg_replace('/\band\(/i', 'and (', $pss);
        $pss = preg_replace('/([!{}:;>+\(\[,])\s+/', '$1', $pss);
        $pss = preg_replace('/;+\}/', '}', $pss);
        $pss = preg_replace('/([\s:])(0)(px|em|%|in|cm|mm|pc|pt|ex)/i', '$1$2', $pss);
        $pss = preg_replace('/:0 0 0 0(;|\})/', ':0$1', $pss);
        $pss = preg_replace('/:0 0 0(;|\})/', ':0$1', $pss);
        $pss = preg_replace('/:0 0(;|\})/', ':0$1', $pss);
        $pss = preg_replace_callback('/(background-position|transform-origin|webkit-transform-origin|moz-transform-origin|o-transform-origin|ms-transform-origin):0(;|\})/i', function ($matches) {
            return strtolower($matches[1]) . ':0 0' . $matches[2];
        }, $pss);
        $pss = preg_replace('/(:|\s)0+\.(\d+)/', '$1.$2', $pss);
        $pss = preg_replace_callback('/rgb\s*\(\s*([0-9,\s]+)\s*\)/i', function ($matches) {
            $rgb_colors = explode(',', $matches[1]);
            for ($i = 0, $max = count($rgb_colors); $i < $max; $i++) {
                $rgb_colors[$i] = dechex((int)$rgb_colors[$i]);
                if (strlen($rgb_colors[$i]) === 1) {
                    $rgb_colors[$i] = '0' . $rgb_colors[$i];
                }
            }
            return '#' . implode('', $rgb_colors);
        }, $pss);
        $pss = preg_replace_callback('/([^"\'=\s])(\s*)#([0-9a-f])([0-9a-f])([0-9a-f])([0-9a-f])([0-9a-f])([0-9a-f])/i', function ($matches) {
            $group = $matches;
			if ($group[1] === '}') {
                return $group[0];
			} else if (strtolower($group[3]) === strtolower($group[4]) && strtolower($group[5]) === strtolower($group[6]) && strtolower($group[7]) === strtolower($group[8])) {
                return strtolower($group[1] . $group[2] . '#' . $group[3] . $group[5] . $group[7]);
            } else {
                return strtolower($group[0]);
            }
        }, $pss);
        $pss = preg_replace_callback('/(border|border-top|border-right|border-bottom|border-right|outline|background):none(;|\})/i', function ($matches) {
            return strtolower($matches[1]) . ':0' . $matches[2];
        }, $pss);
        $pss = preg_replace('/progid:DXImageTransform\.Microsoft\.Alpha\(Opacity=/i', 'alpha(opacity=', $pss);
        $pss = preg_replace('/[^\};\{\/]+\{\}/', '', $pss);
        if ($line_break_pos >= 0) {
            $start_index = 0;
            $i = 0;
            $max = strlen($pss);
            while ($i < $max) {
                $i++;
                if ($pss[$i - 1] === '}' && $i - $start_index > $line_break_pos) {
                    $pss = substr($pss, 0, $i) . "\n" . substr($pss, $i);
                    $start_index = $i;
                }
            }
        }
        $pss = preg_replace('/;;+/', ';', $pss);
        for ($i = 0, $max = count($preserved_tokens); $i < $max; $i++) {
            $pss = str_replace('___YUICSSMIN_PRESERVED_TOKEN_' . $i . '___', $preserved_tokens[$i], $pss);
        }
        $pss = preg_replace('/^\s+|\s+$/', '', $pss);
        return $pss;
    }
    protected static /* @core */ function minify_pjs($pjs) {
        // @todo: 翻译 jsmin.c
        return $pjs;
    }
}
