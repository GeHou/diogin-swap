<?php
return array(
    'business' => array(
        'default_title'       => 'forum',
        'default_keywords'    => 'forum, bbs',
        'default_description' => 'This is forum.',
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
        'secret_key'          => 'CR7quxZLrzjJ1E8pl6aOK0cDMSU5oFTedgvnN4Xi',
        'locale'              => 'zh_cn',
        'time_zone'           => 'Asia/Shanghai',
        'site_closed'         => false,
        'default_https'       => false,
        'is_debug'            => true,
        'log_execute_time'    => true,
        'send_x_powered_by'   => true,
        'minify_cjs'          => false,
        'cache_cjs_in_client' => false,
        'cache_cjs_in_server' => false,
        'error_reporting'     => E_ALL | E_STRICT,
        'display_errors'      => true,
        'log_errors'          => true,
        'log_rotate_method'   => 'day',
        'csrf_key'            => 'role_secret',
        'version_key'         => 'v',
        'target_key'          => 'target',
        'global_filters' => array(
            'before' => array(),
            'after'  => array(),
        ),
        'uri_maps' => array(
            '/about'    => 'site/about',
            '/login'    => 'site/login',
            '/register' => 'user/register',
        ),
        'visitor' => array(
            'cookie_domain' => '',
            'roles' => array(
                'user' => array(
                    'sid_name'              => 'user_sid',
                    'default_alive_seconds' => 3600,
                    'trace_last_active'     => true,
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
                        'master' => 'sqlite://' . swap\var_dir . '/repo/rdb.db',
                        'slaves' => array(
                            'sqlite://' . swap\var_dir . '/repo/rdb.db',
                            'sqlite://' . swap\var_dir . '/repo/rdb.db',
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
            'misc' => array(
                'filesys://' . swap\var_dir . '/cache/data/misc',
            ),
        ),
        'mover' => array(
            'avatar' => 'filesys://' . swap\web_dir . '/upload/avatar',
        ),
    ),
    'third' => array(),
);
