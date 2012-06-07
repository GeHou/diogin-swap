<?php

use swap\visitor;

class except_controller extends swap\controller {
    public static function access_denied_action($e) {
        self::only_page();
    }
    public static function target_missing_action($e) {
        self::only_page();
    }
}
