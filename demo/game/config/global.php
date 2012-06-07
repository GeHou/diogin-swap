<?php
return array(
    'business' => array(
        'default_title'       => 'Browser game written in HTML5, CSS3 and JavaScript',
        'default_keywords'    => '',
        'default_description' => '',
        'default_author'      => '',
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
        'page_skeleton'       => 'html5',
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
        'minify_cjs'          => false,
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
        'uri_maps' => array(),
        'visitor' => array(
            'cookie_domain' => '',
            'roles' => array(
                'user' => array(
                    'sid_name'              => 'user_sid',
                    'default_alive_seconds' => 3600,
                    'trace_last_active'     => false,
                    'session_dsn'           => 'sqlite://' . swap\var_dir . '/session/session.db/user_session',
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
