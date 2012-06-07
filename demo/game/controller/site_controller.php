<?php

use swap\visitor;
use swap\clock;

class site_controller extends swap\controller {
    public static function index_action() {
        self::show_page();
    }
    public static function now_action() {
        self::send(clock::get_datetime());
    }
}
