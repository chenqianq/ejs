<?php
/**
 * 入口
 *
 */

define('IS_ACCESS',true);
define('DIR_FS_DOCUMENT_ROOT', realpath(dirname(__FILE__)).'/');
error_reporting(0);
error_reporting(E_ERROR);
// error_reporting(E_ALL);
require_once './configure/configure.php';

$_GET['route'] = empty($_GET['route']) ? 'front/home/index' : trim($_GET['route']);
define('DIR_FS_CLASSES', DIR_FS_DOCUMENT_ROOT . 'classes/');
session_start();
include DIR_FS_DOCUMENT_ROOT . 'zc-framework/zc.php' ;
require_once DIR_FS_DOCUMENT_ROOT . 'classes/logs/init_monitor.php';
$appDir = 'view_controller/ejsxcx/';
Zc::init(DIR_FS_DOCUMENT_ROOT, $appDir);
Zc::runMVC();

exit;