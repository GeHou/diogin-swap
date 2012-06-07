<?php
/**
 * captcha 图片生成器
 *
 * @copyright Copyright (c) 2009-2012 Jingcheng Zhang <diogin@gmail.com>. All rights reserved.
 * @license   See "LICENSE" file bundled with this distribution.
 */

namespace swap;

captcha::__init__();

// [实体] captcha 图片生成器
abstract class captcha {
    public static function set_image_type($image_type) {
        self::$image_type = $image_type;
    }
    public static function set_font_file($font_file) {
        self::$font_file = $font_file;
    }
    public static function set_size($width, $height) {
        self::$width = $width;
        self::$height = $height;
    }
    public static function set_color(array $back_color, array $fore_color) {
        self::$back_color = $back_color;
        self::$fore_color = $fore_color;
    }
    public static function set_text($text) {
        self::$text = $text;
    }
    public static function generate() {
        if (self::$text === '') {
            throw new developer_error('you should provide text to generate captcha');
        }
        return call_user_func(array(self::$captcha, 'do_generate'));
    }
    public static function __init__() {
        self::$captcha = 'swap\gd_captcha';
        self::$font_file = core_dir . '/share/font/vera.ttf';
    }
    
    protected static $captcha = '';
    protected static $image_type = 'png';
    protected static $font_file = '';
    protected static $width = 120;
    protected static $height = 60;
    protected static $back_color = array(0xFF, 0xFF, 0xFF);
    protected static $fore_color = array(0x20, 0x40, 0xA0);
    protected static $text = '';
}
