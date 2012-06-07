<?php

use swap\visitor;

class browser_filter extends swap\before_filter {
    public static function run() {
        if (visitor::is_ie6()) {
            self::forward_406('我们不支持 IE6 浏览器，请升级您的浏览器再访问本站。');
        }
    }
}
