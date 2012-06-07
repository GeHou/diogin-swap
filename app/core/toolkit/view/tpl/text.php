<?php
/**
 * 供 tpl 文件使用文本小工具
 *
 * @copyright Copyright (c) 2009-2012 Jingcheng Zhang <diogin@gmail.com>. All rights reserved.
 * @license   See "LICENSE" file bundled with this distribution.
 */

use swap\url_manager;

function show_text($text, $escape_html = false) {
    echo nl2br(str_replace(' ', '&nbsp;', $escape_html ? swap\html::escape($text) : $text));
}
function show_ubb($text, $escape_html = false) {
    $text = str_replace(' ', '&nbsp;', $escape_html ? swap\html::escape($text) : $text);
    $text = nl2br($text);
    $text = preg_replace('/\[upload_img\](.*?)\[\/upload_img\]/e', '"<img src=\"" . swap\view_rendor::upload_url(\'$1\', false) . "\" />"', $text);
    echo $text;
}
function form_tag() {
    echo '<form></form>';
}
function link_to($target, $text) {
    echo '<a href="' . url_manager::build_url($target, false, true) . '">' . $text . '</a>';
}
