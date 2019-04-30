<?php
global $g_config_redis_servers,$g_config_database_servers,$g_public_sites;
if( $_SERVER['SERVER_PORT'] == 443 ){
    $https = 'https';
}
else{
    $https = 'http';
}

return array (
		ZcConfigConst::AutoloadDirsFs => array (
				'tools' => dirname ( dirname ( __FILE__ ) ) . '/libs/tools/',
		    'common' => dirname(dirname ( dirname ( __FILE__ ) )) . '/common/',
		        'constants' => DIR_FS_DOCUMENT_ROOT . 'classes/constants/',
		    'cache' => DIR_FS_DOCUMENT_ROOT . 'classes/caches/',
		),
		ZcConfigConst::AutoloadClassFileMapping => array (
				'HelperFactory' => DIR_FS_DOCUMENT_ROOT . 'classes/helpers/class.HelperFactory.php',
				'DaoFactory' => DIR_FS_DOCUMENT_ROOT . 'classes/dao/class.DaoFactory.php',
				'ServiceFactory' => DIR_FS_DOCUMENT_ROOT . 'classes/services/class.ServiceFactory.php',
		    'LogFactory' => DIR_FS_DOCUMENT_ROOT . 'classes/logs/class.LogFactory.php',

		),
		ZcConfigConst::UrlHandler => array (
						'class' => 'YfjUrlHandler',
						'file' => '/libs/tools/class.YfjUrlHandler.php'
		),
        ZcConfigConst::DbConfig => array(
            'error_mode' => 'bool', // bool or exception
            'default_group' => 'htl',
            'connections' => array(
                'htl' => $g_config_database_servers ,
                'db' => array(
                    'master' => array(
                        'db_id' => 'm_db',
                        'dbms' => 'mysql',
                        'hostname' => DB_SERVER_LOG,
                        'port' => '3306',
                        'username' => DB_SERVER_USERNAME_LOG,
                        'password' => DB_SERVER_PASSWORD_LOG,
                        'pconnect' => false,
                        'charset' => 'utf8',
                        'database' => DB_DATABASE_LOG,
                        'read_weight' => 100
                    )
                ),
                'log' => array(
                    'master' => array(
                        'db_id' => 'log_db',
                        'dbms' => 'mysql',
                        'hostname' => DB_SERVER_LOG,
                        'port' => '3306',
                        'username' => DB_SERVER_USERNAME_LOG,
                        'password' => DB_SERVER_PASSWORD_LOG,
                        'pconnect' => false,
                        'charset' => 'utf8',
                        'database' => 'monitor_log',
                        'read_weight' => 100
                    )
                ),
	            'qinkaint' => array(
						'master' => array(
							'db_id' => DB_ID_QINKAINT,
							'dbms' => 'mysql',
							'hostname' => DB_SERVER_QINKAINT,
							'port' => '3306',
							'username' => DB_SERVER_USERNAME_QINKAINT,
							'password' => DB_SERVER_PASSWORD_QINKAINT,
							'pconnect' => false,
							'charset' => 'utf8',
							'database' => DB_DATABASE_QINKAINT,
							'read_weight' => 100
						)
					)
            )
        ),

    /* ZcConfigConst::MonitorDbServer => '127.0.0.1',
    ZcConfigConst::MonitorDbUsername => 'root',
    ZcConfigConst::MonitorDbPassword => 'root',
    ZcConfigConst::MonitorDbDatabase => 'yifanjie',
    ZcConfigConst::MonitorExitOnDbError => true,   */
    ZcConfigConst::LogDir => DIR_FS_DOCUMENT_ROOT .'logs/',

        ZcConfigConst::LogHandlerLogstashredisHost => '192.168.99.240',//
        ZcConfigConst::LogHandlerLogstashredisPort => 6379,
		// 过滤器
		ZcConfigConst::Filters => array (
				 array (
						'route.pattern' => '/(.*)/',
						'route' => 'filter/auth/index'
				),
	 			/*array (
						'route.pattern' => '/catalog\/report\/payment_details/i',
						'route' => 'filter/auth/payment'
				),  */
		),

        ZcConfigConst::RedisConfig => array(
            'master' => array (
                "host" => "127.0.0.1",
                "port" => 6379
            ),
            /// 10.12.175.194 ts2 从库
            /*  'slave' => array (
             array (
                 "host" => "10.12.175.194",
                 "port" => 6378
             )
            ) */
        ),

        'helpers.dir.fs' => DIR_FS_DOCUMENT_ROOT . 'classes/helpers/',
        'services.dir.fs' => DIR_FS_DOCUMENT_ROOT . 'classes/services/',
        'dao.dir.fs' => DIR_FS_DOCUMENT_ROOT . 'classes/dao/',
    'upload.dir.fs' => DIR_FS_DOCUMENT_ROOT . 'data/upload/',
    'upload.common.dir.fs' => DIR_FS_DOCUMENT_ROOT.'data/upload/shop/common/',
    'upload.common.goods.dir.fs' => DIR_FS_DOCUMENT_ROOT.'data/upload/shop/store/goods/',
    'upload.common.fanmi.dir.fs' => 'data/upload/fanmi/',
	'goods.dir'=>'shop/store/goods/1/',
    'integral.goods.dir'=>'integral/goods/',
    'g_public_sites' => $g_public_sites,

    /////数据库配置
        'sms.host.type' => 4,
    'mobile_key' => '6fb6f9591b4d84708c7c644275945279',
    'mobile_signature' => '一番街' ,
    'app.banner.id' => 1052 ,
    'md5_key' => 'c14ad33a4a8e284007cac795977d0b03',

		'masspay.server' => 'live', //sandbox live
		ZcConfigConst::LanguageCurrent => 'english',
		'app.base.route' => 'index.php',
		'app.default.lang' => 1,
		'app.enable.ssl' => false,//(ENABLE_SSL == 'false') ? false : true,
		//'app.default.iso' => $_SESSION[SessionConst::shippingCountryCode],
		//'app.default.lang.code' => $_SESSION[SessionConst::currentLangCode],
		//'app.default.currency.code' => $_SESSION[SessionConst::userSettingCurrency],
		ZcConfigConst::Domain => G_CURRENT_DOAMIN,
    'const.doamin' => 'WWW_YFJ_COM',
	'app.https.domain' => $https . '://'.G_CURRENT_DOAMIN.'/',
	'app.http.domain' => $https . '://'.G_CURRENT_DOAMIN.'/',
    'app.https.img_cdn1' => $https . '://'.G_CURRENT_DOAMIN.'/',
    'app.https.img_cdn2' => $https . '://'.G_CURRENT_DOAMIN.'/',
    'app.https.img_cdn3' => $https . '://'.G_CURRENT_DOAMIN.'/',
	'app.https.img_cdn4' => $https . '://'.G_CURRENT_DOAMIN.'/',
	
	'app.https.product' => $https . '://'.G_CURRENT_DOAMIN.'/product',
	'admin.static.url'  => $https . '://'.G_CURRENT_DOAMIN.'/view_controller/new-admin/views/static',
    ///这个是旧版本的
	 'web.site.url'  => $https . '://'.G_CURRENT_DOAMIN.'',
    'shop.templates.url'  => $https . '://'.G_CURRENT_DOAMIN.'/shop/templates/yifanjie',
    'resource.site.url'  => $https . '://'.G_CURRENT_DOAMIN.'/data/resource',
	'admin.templates.url'  => $https . '://'.G_CURRENT_DOAMIN.'/admin/templates/default',
	'admin.site.url'  => $https . '://'.G_CURRENT_DOAMIN.'/admin',
    'shop.site.url'  => $https . '://'.G_CURRENT_DOAMIN.'/shop',
    'goods.images.width'  => '60,65,130,240,360,420,1280',
    'goods.images.height'  => '60,65,130,240,360,420,1280',
    'goods.images.ext'  => '_60,_65,_130,_240,_360,_420,_1280',
    'avatar.images.width'  => '60,100,120,160,240,360,500',
    'avatar.images.height'  => '60,100,120,160,240,360,500',
    'avatar.images.ext'  => '_60,_100,_120,_160,_240,_360,_500',
    'app.https.common.demo.fs' => $https . '://'.G_CURRENT_DOAMIN.'/view_controller/common/demo/',
    'admin.http.domain' => $https . '://'.G_CURRENT_DOAMIN.'/ejs-admin/',
);