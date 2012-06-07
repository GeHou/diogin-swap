<?php
return array(
    'business' => array(
        'default_title'       => 'Swap Web 应用开发框架',
        'default_keywords'    => 'swap, web, application, framework, php, mvc, oop',
        'default_description' => 'Swap 是一个现代的 Web 应用开发框架。',
        'default_author'      => 'Jingcheng Zhang',
    ),
    'technique' => array(
        'www_domain'          => '',
        'module_domains'      => array(),
        'rewrite_enabled'     => true,
        'media_domain'        => '',
        'style_domain'        => '',
        'script_domain'       => '',
        'third_domain'        => '',
        'upload_domain'       => '',
        'static_version'      => 1,
        'page_skeleton'       => 'xhtml',
        'send_tpl_with_cjs'   => true,
        'secret_key'          => '##secret_key##',
        'site_closed'         => false,
        'default_https'       => false,
        'is_debug'            => true,
        'error_reporting'     => E_ALL | E_STRICT,
        'display_errors'      => true,
        'log_errors'          => true,
        'log_rotate_method'   => 'day',
        'log_execute_time'    => true,
        'send_x_powered_by'   => true,
        'minify_cjs'          => true,
        'cache_cjs_in_client' => false,
        'cache_cjs_in_server' => false,
        'locale'              => 'zh_cn',
        'time_zone'           => 'Asia/Shanghai',
        'csrf_key'            => 'role_secret',
        'version_key'         => 'v',
        'target_key'          => 'target',
        'global_filters' => array(
            'before' => array(),
            'after'  => array(),
        ),
        'uri_maps' => array(
            '/about'         => 'site/about',
            '/download'      => 'site/download',
            '/demo'          => 'site/demo',
            '/documentation' => 'site/doc',
            '/support'       => 'site/support',
            '/license'       => 'site/license',
        ),
        'visitor' => array(
            'cookie_domain' => '',
            'roles' => array(
                'user' => array(
                    'sid_name'              => 'user_sid',
                    'default_alive_seconds' => 3600,
                    'trace_last_active'     => false,
                    'session_dsn'           => 'sqlite://' . swap\var_dir . '/session/session.db/user_session',
                ),
                'admin' => array(
                    'sid_name'              => 'admin_sid',
                    'default_alive_seconds' => 600,
                    'trace_last_active'     => false,
                    'session_dsn'           => 'sqlite://' . swap\var_dir . '/session/session.db/admin_session',
                ),
            ),
        ),
        'repo' => array(
            'rdb' => array(
                'sources' => array(
                    'default' => array(
                        'master' => 'sqlite://' . swap\var_dir . '/rdb.db',
                        'slaves' => array(
                            'sqlite://' . swap\var_dir . '/rdb.db',
                            'sqlite://' . swap\var_dir . '/rdb.db',
                        ),
                    ),
                ),
                'tables' => array(
                    '*' => 'default',
                ),
            ),
            'ddb' => array(),
            'kvdb' => array(),
        ),
        'cache' => array(
            'demo' => array(
                'dsn' => '',
            ),
        ),
        'mailer' => array(),
        'mover' => array(),
        'queue' => array(),
        'search' => array(),
    ),
    'third' => array(),
);
