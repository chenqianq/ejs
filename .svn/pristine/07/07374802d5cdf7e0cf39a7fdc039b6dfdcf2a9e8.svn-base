<?php
/**
 * 入口
 *
 */
define('IS_ACCESS',true);
define('DIR_FS_DOCUMENT_ROOT', realpath(dirname(__FILE__)).'/');
if( isset($_GET['test']) ){
	
	//error_reporting(E_ALL);
}
else{
    // error_reporting(0);
}

error_reporting(E_ALL);
// error_reporting(E_ERROR);
error_reporting(0);
define('PAGE_PARSE_START_TIME',microtime());
require_once './configure/configure.php';
$site_url = strtolower('http://'.$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/index.php')).'/shop/index.php');
//@header('Location: '.$site_url);
   
define('DIR_FS_CLASSES', DIR_FS_DOCUMENT_ROOT . 'classes/');
$_GET['route'] = empty($_GET['route']) ? 'front/home/index' : trim($_GET['route']);


$routeArray = array(
    'buy-step1' => 'checkout/confirmation/index',
	'app_down-index' => 'front/downapp/down_load',
);

$act = $_GET['act'] ? $_GET['act'] : $_POST['act'];
$op = $_GET['op'] ? $_GET['op'] : $_POST['op'];
if( array_key_exists($act . '-' .$op , $routeArray) ){
    $_GET['route'] = $routeArray[$act . '-' .$op];
}   

require_once 'classes/helpers/class.IpHelper.php';

$ipHelper = new Ip();
 
 
 
///M 端
if (G_CURRENT_DOAMIN_CONST_NAME == 'G_M_YIFANJIE_COM_DOMAIN') { 
    $token_str = empty($_POST ["app_token"]) ? trim($_GET ["app_token"]) : trim($_POST ["app_token"]);
    $app_source = empty($_POST['source']) ? trim($_GET['source']) : trim($_POST['source']);
   
    if( $token_str ){
        $helper_path = realpath (dirname (__FILE__) . '/') . '/' . 'classes/helpers/';
        
        if (file_exists ($helper_path . 'class.SafeHelper.php')) {
            include_once ($helper_path . 'class.SafeHelper.php');
        }
        
        $token = Safe::decode (rawurldecode ($token_str));
        
        list ( $zenid, $mobilePhone, $pwd ) = explode ('##', $token);
       
        if(trim($zenid)) {
            $_GET ['PHPSESSID'] = trim($zenid);
            session_id(trim($zenid));
        }
    }
    session_start();
    
    include DIR_FS_DOCUMENT_ROOT . 'zc-framework/zc.php' ;
    
    
     
    
    require_once DIR_FS_DOCUMENT_ROOT . 'classes/logs/init_monitor.php';
    $appDir = 'view_controller/yifanjie-mobile/';
    Zc::init(DIR_FS_DOCUMENT_ROOT, $appDir);
    if( $token_str ){
        //unset($_GET ["app_token"],$_POST ["app_token"]);
         
    
        if( !$_SESSION[member_id] && $mobilePhone && $pwd ){
            $sql = "select * from yfj_member where   member_mobile = '" . addslashes($mobilePhone) . "' and member_passwd = '".md5($pwd)."' ";
    
            $loginRs = Zc::getDb() -> getRow($sql);
             
            // ------------ 修复手机验证码登陆app不能正常访问映射的订单 临时解决方案 start --------------------
            if (empty($loginRs)) { // 解密出来的pwd是md5加密的
                $sql = "select * from yfj_member where   member_mobile = '" . addslashes($mobilePhone) . "' and member_passwd = '$pwd' ";
                $loginRs = Zc::getDb() -> getRow($sql);
    
            }
    
            // ------------ 临时解决方案 end --------------------
            if( $loginRs ){
                $userName = $loginRs['member_mobile']?$loginRs['member_mobile']:$loginRs['member_name'];
                $_SESSION['member_id'] = $loginRs['member_id'];
                $_SESSION['user_name'] = $userName;
            }
        }
         
    }
    $action = Zc::runMVC('','404');
    if($action == "404"){
       /*  header("Status: 404 Not Found");
        //header("HTTP/1.1 404 Not Found");
        header('Location: http://m.yifanjie.com/404.html'); */
        @header("http/1.1 404 not found");
        @header("status: 404 not found");
        include("http://m.yifanjie.com/404.html");//跳转到某一个页面，推荐使用这种方法 
        
        exit();
    }
    
    
    $hostname = php_uname ( 'n' );
    $db_selects = Zc::getDb() -> getStats();
    $stat_str = 'ExecStats : <i><b>DB</b></i> ' . $db_selects['execStats']['db']['readCount'] . ' (' . $db_selects['execStats']['db']['readTime'] .')';
    $time_start = explode ( ' ', PAGE_PARSE_START_TIME );
    $time_end = explode ( ' ', microtime () );
    $parse_time = number_format ( ($time_end [1] + $time_end [0] - ($time_start [1] + $time_start [0])), 3 );
    $stat_str .= " --- Parse Time: <b style='color:green;'> $parse_time </b> - <b style='color:blue'> $hostname </b>" ;
    if( $_GET['yfj_need_parse_time'] ){    
        var_dump(Zc::G());
        echo '<div align="center"> ' . $stat_str . '</div>' ;
    }
    
    if( $stat_str &&  extension_loaded('redis') ){
        $redis = new Redis();
    
        try {
            $isSupportedRedis = $redis->connect(Zc::C(ZcConfigConst::LogHandlerLogstashredisHost), Zc::C(ZcConfigConst::LogHandlerLogstashredisPort), 1);
    
            $redis->rPush('parse-times-m', '[' . $_GET['route'] . '] ' . $stat_str);
        } catch (Exception $ex) {
            $isSupportedRedis = false;
            //parent::log(print_r($ex, true));
        }
    }
    exit;
     
}


///PC 端
if (G_CURRENT_DOAMIN_CONST_NAME == 'G_WWW_YIFANJIE_COM_DOMAIN' && strrpos($_SERVER['REQUEST_URI'],'shop/') === false) { 
    $routeArray = array(
        'erpPort-index' => 'timers/download_order/index',
        'erpPort-' => 'timers/download_order/index',
        'timers/download_order/index' => 'timers/download_order/index',
        'erpPort-' => 'timers/download_order/index',
        'erpPort-index' => 'timers/download_order/index',
    );
    $act = $_GET['act'] ? $_GET['act'] : $_POST['act'];
    $op = $_GET['op'] ? $_GET['op'] : $_POST['op'];
    
    if( array_key_exists($act . '-' .$op , $routeArray) ){
        $_GET['route'] = $routeArray[$act . '-' .$op];
    }

    session_start();
    include DIR_FS_DOCUMENT_ROOT . 'zc-framework/zc.php' ;
    require_once DIR_FS_DOCUMENT_ROOT . 'classes/logs/init_monitor.php';
    $appDir = 'view_controller/yifanjie-pc/';
    Zc::init(DIR_FS_DOCUMENT_ROOT, $appDir);
    Zc::runMVC();
	
	
    $hostname = php_uname ( 'n' );
    
    
    $db_selects = Zc::getDb() -> getStats();
    
    $stat_str = 'ExecStats : <i><b>DB</b></i> ' . $db_selects['execStats']['db']['readCount'] . ' (' . $db_selects['execStats']['db']['readTime'] .')';
  
    $time_start = explode ( ' ', PAGE_PARSE_START_TIME );
    $time_end = explode ( ' ', microtime () );
    $parse_time = number_format ( ($time_end [1] + $time_end [0] - ($time_start [1] + $time_start [0])), 3 );
    $stat_str .= " --- Parse Time: <b style='color:green;'> $parse_time </b> - <b style='color:blue'> $hostname </b>" ;
    if( $_GET['yfj_need_parse_time']||1 ){
        Zc::dump(Zc::G(),false);
        echo '<div align="center"> ' . $stat_str . '</div>' ;
    }
    
    if( $stat_str && extension_loaded('redis') ){
        $redis = new Redis();
    
        try {
            $isSupportedRedis = $redis->connect(Zc::C(ZcConfigConst::LogHandlerLogstashredisHost), Zc::C(ZcConfigConst::LogHandlerLogstashredisPort), 1);
    
            $redis->rPush('parse-times-pc', '[' . $_GET['route'] . '] ' . $stat_str);
        } catch (Exception $ex) {
            $isSupportedRedis = false;
            //parent::log(print_r($ex, true));
        }
    }
    exit;
} 

   

///B 端
if (G_CURRENT_DOAMIN_CONST_NAME == 'G_B_YIFANJIE_COM_DOMAIN' || G_CURRENT_DOAMIN_CONST_NAME == 'G_XINRITAO_COM_DOMAIN') { 
    $routeArray = array(
        'erpPort-index' => 'timers/download_order/index',
        'erpPort-' => 'timers/download_order/index',
        'timers/download_order/index' => 'timers/download_order/index',
        'erpPort-' => 'timers/download_order/index',
        'erpPort-index' => 'timers/download_order/index',
    );
    $act = $_GET['act'] ? $_GET['act'] : $_POST['act'];
    $op = $_GET['op'] ? $_GET['op'] : $_POST['op'];
    
    if( array_key_exists($act . '-' .$op , $routeArray) ){
        $_GET['route'] = $routeArray[$act . '-' .$op];
    }

    session_start();
    include DIR_FS_DOCUMENT_ROOT . 'zc-framework/zc.php' ;
    require_once DIR_FS_DOCUMENT_ROOT . 'classes/logs/init_monitor.php';
    $appDir = 'view_controller/yifanjie-business/';
    Zc::init(DIR_FS_DOCUMENT_ROOT, $appDir);
    Zc::runMVC();
    exit;

}     


