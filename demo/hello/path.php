<?php

define('swap\app_dir',        DIRECTORY_SEPARATOR === '\\' ? str_replace('\\', '/', __DIR__) : __DIR__);

define('swap\config_dir',     swap\app_dir . '/config');
define('swap\controller_dir', swap\app_dir . '/controller');
define('swap\core_dir',       swap\app_dir . '/../../app/core');
define('swap\web_dir',        swap\app_dir . '/web');
