<?php
/**
 * 使用 gd 库生成 captcha 图片
 *
 * @copyright Copyright (c) 2009-2012 Jingcheng Zhang <diogin@gmail.com>. All rights reserved.
 * @license   See "LICENSE" file bundled with this distribution.
 */

namespace swap;

if (!extension_loaded('gd')) throw new environment_error('gd is not loaded');

// [内部][实体]
class gd_captcha extends captcha {
    protected static function do_generate() {
        $width = parent::$width;
        $height = parent::$height;
        $back_color = parent::$back_color;
        $fore_color = parent::$fore_color;
        $font_file = parent::$font_file;
        $text = parent::$text;
        $offset = -2;
        $padding = 2;
        
        $image = imagecreatetruecolor($width, $height);
        $color = imagecolorallocate($image, $back_color[0], $back_color[1], $back_color[2]);
        imagefilledrectangle($image, 0, 0, $width, $height, $color);
        imagecolordeallocate($image, $color);
        $color = imagecolorallocate($image, $fore_color[0], $fore_color[1], $fore_color[2]);
        $text_len = strlen($text);
        $box = imagettfbbox(30, 0, $font_file, $text);
        $w = $box[4] - $box[0] + $offset * ($text_len - 1);
        $h = $box[1] - $box[5];
        $scale = min(($width - $padding * 2) / $w, ($height - $padding * 2) / $h);
        $x = 10;
        $y = round($height * 27 / 40);
        for ($i = 0; $i < $text_len; $i++) {
            $font_size = intval(rand(26, 32) * $scale * 0.8);
            $angle = rand(-10, 10);
            $char = $text[$i];
            $box = imagettftext($image, $font_size, $angle, $x, $y, $color, $font_file, $char);
            $x = $box[2] + $offset;
        }
        imagecolordeallocate($image, $color);
        ob_start();
        imagepng($image);
        imagedestroy($image);
        return ob_get_clean();
    }
}
