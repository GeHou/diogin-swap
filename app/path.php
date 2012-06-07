<?php
/**
 * Web 应用程序各类组件所在的位置定义。可以任意安排各组件的位置。
 */

# Web 应用根目录
define('swap\app_dir',        DIRECTORY_SEPARATOR === '\\' ? str_replace('\\', '/', __DIR__) : __DIR__);

# 配置文件
define('swap\config_dir',     swap\app_dir . '/config');
# 控制器
define('swap\controller_dir', swap\app_dir . '/controller');
# 框架核心目录
define('swap\core_dir',       swap\app_dir . '/core');
# 日志文件
define('swap\log_dir',        swap\app_dir . '/data/log');
# 临时文件
define('swap\tmp_dir',        swap\app_dir . '/data/tmp');
# 程序运行时操作的数据文件
define('swap\var_dir',        swap\app_dir . '/data/var');
# 共享数据文件
define('swap\share_dir',      swap\app_dir . '/data/share');
# 过滤器
define('swap\filter_dir',     swap\app_dir . '/filter');
# 控制器助手
define('swap\helper_dir',     swap\app_dir . '/helper');
# 通用库
define('swap\library_dir',    swap\app_dir . '/library');
# 三方库
define('swap\third_dir',      swap\app_dir . '/library/third');
# 领域模型
define('swap\model_dir',      swap\app_dir . '/model');
# 领域服务
define('swap\service_dir',    swap\app_dir . '/service');
# 视图文件
define('swap\view_dir',       swap\app_dir . '/view');
# Web 应用公共目录
define('swap\web_dir',        swap\app_dir . '/web');
