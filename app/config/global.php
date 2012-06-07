<?php
/**
 * 全局默认配置文件
 */

return array(
    'business' => array(
        'default_title'       => 'web 应用程序标题',
        'default_keywords'    => 'web, 应用, 程序, 关键字',
        'default_description' => 'web 应用程序描述。',
        'default_author'      => 'web 应用程序作者',
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
        'uri_maps' => array(
            '/'      => 'site/index',
            '/about' => 'site/about',
        ),
        'visitor' => array(
            'cookie_domain' => '',
            'roles' => array(
                'user' => array(
                    'sid_name'              => 'user_sid',
                    'default_alive_seconds' => 3600,
                    'trace_last_active'     => true,
                    // 'session_dsn'        => 'cookie://session?record_key=user_sess&hmac_key=sess_hmac
                    // 'session_dsn'        => 'memcached://ip:port',
                    // 'session_dsn'        => array('memcached://ip1:port1', 'memcached://ip2:port2'),
                    // 'session_dsn'        => 'mysql://user:pass@ip:port/db_name/table_name',
                    // 'session_dsn'        => 'pgsql://user:pass@ip:port/db_name/table_name',
                    'session_dsn'           => 'sqlite://' . swap\var_dir . '/session/session.db/user_session',
                ),
                'admin' => array(
                    'sid_name'              => 'admin_sid',
                    'default_alive_seconds' => 3600,
                    'trace_last_active'     => false,
                    'session_dsn'           => 'sqlite://' . swap\var_dir . '/session/session.db/admin_session',
                ),
            ),
        ),
        'repo' => array(
            'rdb' => array(
                'type' => 'distributed',
                'sources' => array(
                    'default' => array(
                        // 'master' => 'mysql://user:pass@ip:port/db_name',
                        // 'master' => 'pgsql://user:pass@ip:port/db_name',
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
            'ddb' => array(
                'sources' => array(
                    'a_node' => array(
                        'type' => 'node',
                        // 指定 mongod 进程地址
                        'server' => 'mongo://user:pass@ip:port/db_name',
                    ),
                    'a_replica_set' => array(
                        'type' => 'replica_set',
                        // 指定 mongod 进程地址
                        'servers' => array(
                            'mongo://user:pass@ip:port/db_name',
                            'mongo://user:pass@ip:port/db_name',
                            'mongo://user:pass@ip:port/db_name',
                        ),
                    ),
                    'a_shard' => array(
                        'type' => 'shard',
                        // 指定 mongos 进程地址。mongod 进程无需在此设置，由 config servers 维护
                        'servers' => array(
                            'mongo://user:pass@ip:port/db_name',
                            'mongo://user:pass@ip:port/db_name',
                            'mongo://user:pass@ip:port/db_name',
                        ),
                    ),
                ),
                'collections' => array(
                    '*' => 'a_replica_set',
                    'user' => 'a_shard',
                ),
            ),
            'kvdb' => array(
                'demo' => array(
                    'master' => 'redis://ip:port',
                    'slaves' => array(
                        'redis://ip:port',
                        'redis://ip:port',
                    ),
                ),
                'demo2' => array(
                    'master' => 'membase://ip2:port2',
                    'slaves' => array(
                        'membase://ip2:port2',
                        'membase://ip2:port2',
                    ),
                ),
            ),
        ),
        'cache' => array(
            'demo' => array(
                'memcached://ip1:port1',
                'memcached://ip2:port2',
            ),
            'demo2' => array(
                'redis://ip3:port3',
                'redis://ip4:port4',
            ),
        ),
        'mailer' => array(),
        'mover' => array(
            'demo' => 'filesys://' . swap\web_dir . '/upload/demo',
        ),
        'queue' => array(),
        'search' => array(),
    ),
    'third' => array(),
);
