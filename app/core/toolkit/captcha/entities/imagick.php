<?php
/**
 * 使用 imagick 库生成 captcha 图片
 *
 * @copyright Copyright (c) 2009-2012 Jingcheng Zhang <diogin@gmail.com>. All rights reserved.
 * @license   See "LICENSE" file bundled with this distribution.
 */

namespace swap;

if (!extension_loaded('imagick')) throw new environment_error('imagick is not loaded');

// [内部][实体]
class imagick_captcha extends captcha {
    protected static function do_generate() {}
}
