<?php

use swap\framework;

require_once __DIR__ . '/../../path.php';
require_once swap\core_dir . '/main.php';
framework::init_cli_environment();

// 至此，已经在命令行下进入框架。接下来在这下面写你的代码

// 比如，输出某个配置项
echo swap\config::get('technique.secret_key') . "\n";
