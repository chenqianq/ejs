<?php
global $g_config_redis_servers,$g_config_database_servers;
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
		    'UtilFactory' => DIR_FS_DOCUMENT_ROOT . 'classes/utils/class.UtilFactory.php', 

		),
		ZcConfigConst::UrlHandler => array (
						'class' => 'YfjUrlHandler',
						'file' => '/libs/tools/class.YfjUrlHandler.php'
		),
        ZcConfigConst::DbConfig => array(
            'db_cache' => array(
                'biz_name' => 'zc_db_cache',
                'cache_type' => 'redis',
                'timestamp' => '20161129',
                'options' => array(array('host' => $g_config_redis_servers['master']['host'], 'port' => $g_config_redis_servers['master']['port'])),
            ),
            'error_mode' => 'bool', // bool or exception
            'default_group' => 'htl',
            'connections' => array(
                'htl' => $g_config_database_servers , 
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
                        'database' => DB_DATABASE_LOG,
                        'read_weight' => 100
                    )
                ) 
            )
        ),

        ZcConfigConst::MonitorDbServer => DB_SERVER_LOG,
        ZcConfigConst::MonitorDbUsername => DB_SERVER_USERNAME_LOG,
        ZcConfigConst::MonitorDbPassword => DB_SERVER_PASSWORD_LOG,
        ZcConfigConst::MonitorDbDatabase => DB_DATABASE_LOG,
        ZcConfigConst::MonitorExitOnDbError => true,  
        ZcConfigConst::LogDir => LOG_DIR,

        ZcConfigConst::LogHandlerLogstashredisHost => LOGHANDLERLOGSTASHREDISHOST,//
        ZcConfigConst::LogHandlerLogstashredisPort => LOGHANDLERLOGSTASHREDISPORT,
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

        ZcConfigConst::RedisConfig => $g_config_redis_servers,

        'helpers.dir.fs' => DIR_FS_DOCUMENT_ROOT . 'classes/helpers/',
    'utils.dir.fs' => DIR_FS_DOCUMENT_ROOT . 'classes/utils/',
        'services.dir.fs' => DIR_FS_DOCUMENT_ROOT . 'classes/services/',
        'dao.dir.fs' => DIR_FS_DOCUMENT_ROOT . 'classes/dao/',
        'upload.dir.fs' => DIR_FS_DOCUMENT_ROOT . 'data/upload/',
        'upload.common.dir.fs' => DIR_FS_DOCUMENT_ROOT.'data/upload/shop/common/',
        'upload.common.goods.dir.fs' => DIR_FS_DOCUMENT_ROOT.'data/upload/shop/store/goods/',
	//上传文件配置
	"thumb.cut_type"=>'gd',
	'thumb.impath'=>'',
	'upload.avatar.dir.fs'=>DIR_FS_DOCUMENT_ROOT . 'data/upload/shop/avatar/',
	'upload.authinfo.dir.fs'=>DIR_FS_DOCUMENT_ROOT . 'data/upload/shop/authinfo/',
	'upload.refund.dir.fs'=>DIR_FS_DOCUMENT_ROOT . 'data/upload/shop/refund/',
	'upload.store.dir.fs'=>DIR_FS_DOCUMENT_ROOT . 'data/upload/shop/store/',
	'default.dir'=>'shop/',

    /////数据库配置
        'sms.host.type' => 4,
        'mobile_key' => '6fb6f9591b4d84708c7c644275945279',
        'mobile_signature' => '一番街' ,
        'app.banner.id' => 1052 ,
        'md5_key' => 'c14ad33a4a8e284007cac795977d0b03',
        
    
		'masspay.server' => MASSPAYSERVER, //sandbox live
		ZcConfigConst::LanguageCurrent => 'chinese',
		'app.base.route' => 'yfj_business.php',
		'app.default.lang' => 1,
		'app.enable.ssl' => ENABLE_SSL,//(ENABLE_SSL == 'false') ? false : true,
		//'app.default.iso' => $_SESSION[SessionConst::shippingCountryCode],
		//'app.default.lang.code' => $_SESSION[SessionConst::currentLangCode],
		//'app.default.currency.code' => $_SESSION[SessionConst::userSettingCurrency],
		ZcConfigConst::Domain => DOMAIN,
        'const.doamin' => G_CURRENT_DOAMIN_CONST_NAME,
		'app.https.domain' => 'https://'.G_CURRENT_DOAMIN.'/',
		'app.http.domain' => 'https://'.G_CURRENT_DOAMIN.'/',
        'app.https.img_cdn1' => CDNIMAGEDOMAIN1,
        'app.https.img_cdn2' => CDNIMAGEDOMAIN2,
        'app.https.img_cdn3' => CDNIMAGEDOMAIN3,
		'app.https.img_cdn4' => CDNIMAGEDOMAIN4,
        ///这个是旧版本的
        'shop.templates.url'  => 'http://'.G_CURRENT_DOAMIN.'/shop/templates/yifanjie',
        'resource.site.url'  => 'http://'.G_CURRENT_DOAMIN.'/data/resource',
    
        'shop.site.url'  => 'http://'.G_CURRENT_DOAMIN.'/shop',
        'goods.images.width'  => '65,130,240,420',
        'goods.images.height'  => '65,130,240,420',
        'goods.images.ext'  => '_65,_130,_240,_420',

        'avatar.images.width'  => '60,100,120,160,240,360,500',
        'avatar.images.height'  => '60,100,120,160,240,360,500',
        'avatar.images.ext'  => '_60,_100,_120,_160,_240,_360,_500',
        'zto.url' => 'http://www.zto.com/',
    'app.https.common.demo.fs' => $https . '://'.G_CURRENT_DOAMIN.'/view_controller/common/demo/',
);