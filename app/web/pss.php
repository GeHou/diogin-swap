<?php
/**
 * PSS 请求入口
 */

require_once __DIR__ . '/../path.php';
require_once swap\core_dir . '/main.php';

swap\framework::serve_pss_request();
