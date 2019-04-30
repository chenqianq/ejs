<?php
if( !defined('IS_ACCESS') ){
    die('');
}

//对外公开的域名
$g_public_sites = array (
    'G_WWW_ERJIASAN_SYS_COM_DOMAIN' => 'www.ejs.com' ,
    'G_WX_ERJIASAN_DOMAIN' => 'wx.ejsxcx.com' ,
);


//secure domain
$g_public_secure_sites = array (
    'G_SECURE_YIFANJIE_COM_DOMAIN' => 'www.cbnew.com',
    'G_SECURE_M_YIFANJIE_COM_DOMAIN' => 'm.cbnew.com',
    'G_SECURE_IOS_YIFANJIE_COM_DOMAIN' => '192.168.99.158',
    'G_SECURE_ANDROID_YIFANJIE_COM_DOMAIN' => '192.168.99.158',
    'G_SECURE_WEIXIN_YIFANJIE_COM_DOMAIN' => 'weixin.cbnew.com',
);

$g_admin_sites = $g_public_sites;

 
//合并对外域名
$g_public_sites = array_merge($g_public_sites, $g_public_secure_sites);

//内部使用的域名
$g_protected_sites = array (
    'G_JUIT_YIFANJIE_COM' => 'juit.cbnew.com' ,
);

 
/** 定义域名常 */
$g_sites = array_merge($g_public_sites, $g_protected_sites);
foreach ( $g_sites as $k=>$v ) {
    define($k, $v);
}

/** 安全域名的映射关系 */
$g_public_secure_maps = array (
    'G_SECURE_YIFANJIE_COM_DOMAIN' => array (
        'G_WWW_YIFANJIE_COM_DOMAIN', 
    ), 
);


/**
 * 获取当前域名
 * @return string
 */
function init_get_current_domain() {
    global $g_sites, $g_public_xing_sites;
    $support_domain_array = array_values($g_sites);
     
    $host_name = (isset ( $_SERVER ['HTTP_X_FORWARDED_HOST'] ) ? $_SERVER ['HTTP_X_FORWARDED_HOST'] : $_SERVER ['HTTP_HOST']);
     
    
    $host_name = htmlspecialchars ( $host_name, ENT_COMPAT, 'UTF-8' );

    if(in_array($host_name, $support_domain_array)) {
        return $host_name;
    } else {
        return G_WWW_YIFANJIE_COM_DOMAIN;
    }
}

/**
 * 获取当前域名常量
 * @param unknown_type $current_domain
 * @return mixed|string
 */
function init_get_current_domain_const_name( $current_domain ) {
    global $g_sites;
    $current_domain = empty($current_domain)? init_get_current_domain() : $current_domain;
    $current_const_name = array_search($current_domain, $g_sites);
    //如果是正常域名则放回域名常量
    if( $current_const_name && ($current_const_name != 'G_WWW_YIFANJIE_COM_DOMAIN')) {
        return $current_const_name;
    }else{
        return 'G_WWW_YIFANJIE_COM_DOMAIN';
    }
}

/**
 * 根据当前域名常量获取安全域名
 * @param string $current_domain_const_name
 */
function init_get_current_secure_domain($current_domain_const_name) {
    global $g_public_secure_maps, $g_public_secure_sites;
    foreach ($g_public_secure_maps as $const_name => $lookup) {
        if(in_array($current_domain_const_name, $lookup) || $const_name == $current_domain_const_name) {
            return $g_public_secure_sites[$const_name] ? $g_public_secure_sites[$const_name] : G_SECURE_YIFANJIE_COM_DOMAIN;
        }
    }

    return G_SECURE_YIFANJIE_COM_DOMAIN;
}

define('G_CURRENT_DOAMIN', init_get_current_domain());
define('G_CURRENT_DOAMIN_CONST_NAME',  init_get_current_domain_const_name(G_CURRENT_DOAMIN) );
define('G_CURRENT_SECURE_DOMAIN', init_get_current_secure_domain(G_CURRENT_DOAMIN_CONST_NAME));

define('DIR_FS_CLASSES', DIR_FS_DOCUMENT_ROOT . 'classes/');

$g_config_redis_servers = array (
         
        'master' => array (
                "host" => "127.0.0.1",
                "port" => 6379
        ),
          
        'slave' => array (
                array (
                        "host" => "127.0.0.1",
                        "port" => 6380
                )
        ) 
);

$g_config_database_servers = array (
    'master' => array(
                        'db_id' => 'db',
                        'dbms' => 'mysql',
                         'hostname' => '192.168.99.240',
//                        'hostname' => '127.0.0.1',
                        'port' => '3306',
                        'username' => 'root',
                        'password' => 'root',
                        'pconnect' => false,
                        'charset' => 'utf8',
                        'database' => 'erjiasan_system',
                        'read_weight' => 100
                    ) 
 
);

// 二加三小程序数据库
define('DB_ID_ERJIASAN_BASE_XCX','ejs_xcx_db');
//define('DB_SERVER_ERJIASAN_BASE_XCX','127.0.0.1');
 define('DB_SERVER_ERJIASAN_BASE_XCX','192.168.99.240');
define('DB_SERVER_USERNAME_ERJIASAN_BASE_XCX','root');
define('DB_SERVER_PASSWORD_ERJIASAN_BASE_XCX','root');
define('DB_DATABASE_ERJIASAN_BASE_XCX','erjiasan_xcx');

// 报关中心
define('DB_ID_QINKAINT','qinkaint_db');
define('DB_SERVER_QINKAINT','127.0.0.1');
define('DB_SERVER_USERNAME_QINKAINT','root');
define('DB_SERVER_PASSWORD_QINKAINT','root');
define('DB_DATABASE_QINKAINT','dcsystem');

//define('DB_SERVER_LOG','127.0.0.1');
define('DB_SERVER_LOG','192.168.99.240');
define('DB_SERVER_USERNAME_LOG','root');
define('DB_SERVER_PASSWORD_LOG','root');
define('DB_DATABASE_LOG','monitor_log');
  
// define('LOGHANDLERLOGSTASHREDISHOST','192.168.99.240');
define('LOGHANDLERLOGSTASHREDISHOST','127.0.0.1');

define('LOGHANDLERLOGSTASHREDISPORT',6379);

define('ENABLE_SSL' , false);

define('LOG_DIR',DIR_FS_DOCUMENT_ROOT .'logs/');


define('DOMAIN','drpthi.com');

///真实支付， 后期 测试会在沙盒上测试
define('MASSPAYSERVER','live');

define('CDNIMAGEDOMAIN1','http://'.G_CURRENT_DOAMIN.'/');
define('CDNIMAGEDOMAIN2','http://'.G_CURRENT_DOAMIN.'/');
define('CDNIMAGEDOMAIN3','http://'.G_CURRENT_DOAMIN.'/');


define('RELATIVE_UPLOAD_AVATAR_DIR',  '/upload/avatar/'.date('Y-m') . '/');

define('UPLOAD_AVATAR_DIR',(DIR_FS_DOCUMENT_ROOT) . RELATIVE_UPLOAD_AVATAR_DIR);
if( !file_exists(UPLOAD_AVATAR_DIR) ){
    @mkdir(UPLOAD_AVATAR_DIR,0777,true);
}

define('RELATIVE_UPLOAD_EVALUATE_DIR',  '/upload/evaluate/'.date('Y-m') . '/');

define('UPLOAD_EVALUATE_DIR',(DIR_FS_DOCUMENT_ROOT) . RELATIVE_UPLOAD_EVALUATE_DIR);

if( !file_exists(UPLOAD_EVALUATE_DIR) ){
    @mkdir(UPLOAD_EVALUATE_DIR,0777,true);
}


define('RELATIVE_PRESALE_UPLOAD_DIR',  '/upload/pre-sale/'.date('Y-m') . '/');

define('UPLOAD_PRESALE_DIR',(DIR_FS_DOCUMENT_ROOT) . RELATIVE_PRESALE_UPLOAD_DIR);
if( !file_exists(UPLOAD_PRESALE_DIR) ){
    @mkdir(UPLOAD_PRESALE_DIR,0777,true);
}

define('RELATIVE_TMP_UPLOAD_PRESALE_DIR',  '/upload/pre-sale/tmp/'.date('Y-m') . '/');

define('TMP_UPLOAD_PRESALE_DIR',(DIR_FS_DOCUMENT_ROOT) . RELATIVE_TMP_UPLOAD_PRESALE_DIR);
if( !file_exists(TMP_UPLOAD_PRESALE_DIR) ){
    @mkdir(TMP_UPLOAD_PRESALE_DIR,0777,true);
}

define('RELATIVE_UPLOAD_ACTIVITY_DIR',  '/upload/activity/');

define('UPLOAD_ACTIVITY_DIR',(DIR_FS_DOCUMENT_ROOT) . RELATIVE_UPLOAD_ACTIVITY_DIR);

if( !file_exists(UPLOAD_ACTIVITY_DIR) ){
    @mkdir(UPLOAD_ACTIVITY_DIR,0777,true);
}

define('APP_UPLOAD_DIR',  '/upload/APP/');
define('RELATIVE_APP_UPLOAD_DIR',(DIR_FS_DOCUMENT_ROOT) . APP_UPLOAD_DIR);


define( 'SOLR_HOST' , '192.168.99.240' );
define( 'SOLR_PORT' , '8983' );
define( 'SOLR_PATH' , '/solr/new_core/' );

define('WECHAT_PAY_UPLOAD_DIR',  '/upload/wechat_pay/');
define('RELATIVE_WECHAT_PAY_UPLOAD_DIR',(DIR_FS_DOCUMENT_ROOT) . WECHAT_PAY_UPLOAD_DIR);

if( !file_exists(RELATIVE_WECHAT_PAY_UPLOAD_DIR) ){
	
	@mkdir(RELATIVE_WECHAT_PAY_UPLOAD_DIR,0777,true);
}