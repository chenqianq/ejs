<?php

/**
 * String的便捷方法
 * 
 * @author kinsly 2013-10-9 上午11:42:13
 *
 */
class ZcStringHelper {

	/**
	 * 生成指定长度和字母表的随机字符串，如果不指定字母表，默认用大小写字母加数字
	 *
	 * @param 长度 $length        	
	 * @param 字母表 $chars        	
	 * @return string
	 */
	public static function genRandomStr($length = 7, $chars = '') {
		if (empty($chars)) {
			$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		}
		$len = strlen($chars) - 1;
		
		$str = '';
		for ($i = 0; $i < $length; $i++) {
			$str .= $chars[mt_rand(0, $len)];
		}
		return $str;
	}

	/**
	 * 验证一个字符串是否是个合法的时间 <br/>
	 * http://www.php.net/manual/en/function.checkdate.php 给了个很棒的的方法来验证，但是需PHP 5.3以上
	 *
	 * @param unknown $date        	
	 * @param string $format        	
	 * @return boolean
	 */
	public static function validateDate($date, $format = '%Y-%m-%d %H:%M:%S') {
		$dateArray = strptime($date, $format);
		return $dateArray && checkdate($dateArray['tm_mon'] + 1, $dateArray['tm_mday'], $dateArray['tm_year'] + 1900) && ($dateArray['tm_hour'] <= 23) && ($dateArray['tm_min'] <= 59) && ($dateArray['tm_sec'] <= 59);
	}

	/**
	 * 获取客户IP
	 * 
	 * @return 客户IP地址
	 */
	public static function getClientIp() {
		static $realip = NULL;
		
		if ($realip !== NULL) {
			return $realip;
		}
		
		if (isset($_SERVER)) {
			if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				$arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
				
				// 取X-Forwarded-For中第一个非unknown的有效IP字符串
				foreach ($arr as $ip) {
					$ip = trim($ip);
					if ($ip != 'unknown') {
						$realip = $ip;
						break;
					}
				}
			} elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
				$realip = $_SERVER['HTTP_CLIENT_IP'];
			} else {
				if (isset($_SERVER['REMOTE_ADDR'])) {
					$realip = $_SERVER['REMOTE_ADDR'];
				} else {
					$realip = '0.0.0.0';
				}
			}
		} else {
			if (getenv('HTTP_X_FORWARDED_FOR')) {
				$arr = explode(',', getenv('HTTP_X_FORWARDED_FOR'));
				
				// 取X-Forwarded-For中第一个非unknown的有效IP字符串
				foreach ($arr as $ip) {
					$ip = trim($ip);
					if ($ip != 'unknown') {
						$realip = $ip;
						break;
					}
				}
			} elseif (getenv('HTTP_CLIENT_IP')) {
				$realip = getenv('HTTP_CLIENT_IP');
			} else {
				$realip = getenv('REMOTE_ADDR');
			}
		}
		
		return $realip;
	}
}