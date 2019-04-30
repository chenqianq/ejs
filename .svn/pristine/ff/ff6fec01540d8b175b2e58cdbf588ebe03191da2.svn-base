<?php
/**
 * 
 * 获取Log对象的工厂
 * 
 * @author kinsly 2016-10-19 16:42
 *
 */
require_once DIR_FS_CLASSES . 'logs/init_monitor.php';

class LogFactory {
	
	/**
	 * 返回Log对象
	 * 
	 * log文件的位置：
	 * 1， $log_name没有以.log结尾，对于日志文件，会自动加上.log
	 * 2， $log_name后面会自动加上.今天的日期，比如.2012_02_12
	 * 3, 可以用/来自动创建目录，
	 * 
	 * 比如调用LogFactory.getBizLog('ss/register');那么日志的位置是：常量BIZ_LOG/ss/register.log.2016_10_19
	 * 
	 * @param  $log_name log名字
	 * @param $defaultLevel 定义在Log的常量，只有高于默认log级别的log，才会被记录下来
	 * @param $echo 是否输出，如果设置为true，那么当记log的时候，同时会echo到页面中。仅供不懂得用tail -f查看log的懒人调试用，并用于开发环境
	 * @return Log
	 */
	public static function getBizLog($log_name = '', $defaultLevel = ZcLog::INFO, $echo = false) {	
		$log_name = trim($log_name);
		
		if (empty($log_name)) {
			$log_name = 'yfj'; 
		}
		
		$log = Zc::getLog($log_name, $defaultLevel, $echo, ZcLog::LOG_HANDLER_LOGSTASH_REDIS);
		return $log;
	}
	
	
	/**
	 * 
	 * 返回admin的log，骨子里其实是调用getBizLog方法，位置就是admin/admin_log/$log_name.log.2012_02_12
	 * 
	 * @param unknown_type $log_name
	 * @param unknown_type $defaultLevel
	 * @param unknown_type $echo
	 */
	public static function getYfjAdminLog($log_name = '', $defaultLevel = ZcLog::INFO, $echo = false) {
		$log_name = trim($log_name);
		if (empty($log_name)) {
			$log_name = 'admin'; 
		}
		
		$log_name = 'yfjadmin/' . $log_name;
		
		return Zc::getLog($log_name, $defaultLevel, $echo, ZcLog::LOG_HANDLER_LOGSTASH_REDIS);
	}
	
	/**
	 *
	 * app的访问log
	 *
	 * @param unknown_type $log_name
	 * @param unknown_type $defaultLevel
	 * @param return log
	 */
	public static function getAppLog($log_name = '', $defaultLevel = ZcLog::INFO, $echo = false) {
		$log_name = trim($log_name);
		if (empty($log_name)) {
			$log_name = 'app';
		}
		
		$log_name = 'app/' . $log_name;
		
		return Zc::getLog($log_name, $defaultLevel, $echo, ZcLog::LOG_HANDLER_LOGSTASH_REDIS);
	}
}
