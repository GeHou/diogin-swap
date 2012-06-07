<?php

use swap\visitor;

class site_controller extends swap\controller {
    public static $layout = 'main';
    public static function index_action() {
        self::show_page();
    }
}
