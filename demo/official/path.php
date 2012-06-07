<?php

define('swap\app_dir',        DIRECTORY_SEPARATOR === '\\' ? str_replace('\\', '/', __DIR__) : __DIR__);

define('swap\config_dir',     swap\app_dir . '/config');
define('swap\controller_dir', swap\app_dir . '/controller');
define('swap\core_dir',       swap\app_dir . '/../../app/core');
define('swap\log_dir',        swap\app_dir . '/data/log');
define('swap\tmp_dir',        swap\app_dir . '/data/tmp');
define('swap\var_dir',        swap\app_dir . '/data/var');
define('swap\share_dir',      swap\app_dir . '/data/share');
define('swap\filter_dir',     swap\app_dir . '/filter');
define('swap\helper_dir',     swap\app_dir . '/helper');
define('swap\model_dir',      swap\app_dir . '/model');
define('swap\service_dir',    swap\app_dir . '/service');
define('swap\library_dir',    swap\app_dir . '/library');
define('swap\third_dir',      swap\app_dir . '/library/third');
define('swap\view_dir',       swap\app_dir . '/view');
define('swap\web_dir',        swap\app_dir . '/web');
