<?php
return array(
    'business' => array(
        'default_title'       => 'blog',
        'default_keywords'    => 'blog, wordpress',
        'default_description' => 'This is blog.',
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
        'secret_key'          => 'M01BXwRghOWp4Ksjq5GnJT8Co2kNUylDESv6QuIi',
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
            'before' => array(
                'browser_filter' => null,
            ),
            'after'  => array(),
        ),
        'uri_maps' => array(
            '/login'             => 'site/login',
            '/about.html'        => 'site/about',
            '/page/*'            => 'post/index?page=$1',
            '/post/category/*'   => 'post/index?category_id=$1',
            '/post/*.html'       => 'post/show?id=$1',
            '/post/*/page/*'     => 'post/show?id=$1&page=$2',
            '/post/tag/*'        => 'post/index?tag=$1',
            '/post/tag/*/page/*' => 'post/index?tag=$1&page=$2',
        ),
        'visitor' => array(
            'cookie_domain' => '',
            'roles' => array(
                'member' => array(
                    'sid_name'              => 'member_sid',
                    'default_alive_seconds' => 3600,
                    'trace_last_active'     => false,
                    'session_dsn'           => 'sqlite://' . swap\var_dir . '/session/session.db/member_session',
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
            'setting' => array(
                'filesys://' . swap\var_dir . '/cache/data/setting',
            ),
        ),
        'mailer' => array(),
        'mover' => array(),
        'queue' => array(),
        'search' => array(),
    ),
    'third' => array(),
);
