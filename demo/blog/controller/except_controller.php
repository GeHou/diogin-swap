<?php

use swap\visitor;

class except_controller extends swap\controller {
    public static function access_denied_action($e) {
        visitor::set_status(403, 'Forbidden');
        self::only_page();
    }
    public static function target_missing_action($e) {
        visitor::set_status(404, 'Not Found');
        self::only_page();
    }
    public static function browser_denied_action($e) {
        visitor::set_status(406, 'Browser Not Allowed');
        self::set_page_skeleton('xhtml');
        self::set('msg', $e->getMessage());
        self::only_page();
    }
}
