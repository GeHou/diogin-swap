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
    public static function method_denied_action($e) {
        visitor::set_status(405, 'Method Not Allowed');
        self::only_page();
    }
    public static function browser_denied_action($e) {
        visitor::set_status(406, 'Browser Not Allowed');
        self::set_page_skeleton('xhtml');
        self::only_page();
    }
    /*
    public static function server_except_action($e) {
        visitor::set_status(500, 'Internal Server Error');
        self::only_page();
    }
    */
    public static function site_closed_action($e) {
        visitor::set_status(503, 'Service Unavailable');
        self::only_page();
    }
}
