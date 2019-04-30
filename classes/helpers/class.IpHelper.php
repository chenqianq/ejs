<?php
/**

 */

if( class_exists('Ip') ){
    return true;
}

class Ip {

	/**
	 * 白名单ip列表
	 */
	private $ipWhiteList = array('118.178.58.81');
	/**
	 * 国家-省地图
	 */
	private $stateMap = array();
	/**
	 * 移动设备判断
	 * @var Mobile_Detect
	 */
	private $mobileDetectHelper = array();
	
	/**
	 * 构造方法初始化白名单列表
	*/
	public function __construct(){

		$ipWhiteList = require(DIR_FS_CLASSES . 'helpers/localip/ip_white_list.inc');
		$this -> ipWhiteList = array_merge($this -> ipWhiteList, $ipWhiteList);
		
		if(empty($this->stateMap)){
			$this->stateMap = require(DIR_FS_CLASSES . 'helpers/localip/geoipregionvars.php');
		}
		if(empty($this->mobileDetectHelper)){
			require_once( DIR_FS_CLASSES . 'helpers/extension/class.MobileDetectHelper.php' );
			$this->mobileDetectHelper = new Mobile_Detect();
		}
	}

   /**
	*    获得用户的真实IP地址
	*  @return   string	用户的IP地址
	*  +-------------------------+
  */
	public static function clientIp() {

        // +-------------------------------- 测试用的后门，Begin ---------------------------------------+

		//为了便于测试不同国家的IP，可以通过Cookie模拟用户IP
		if ($_COOKIE['mock_ip']) {
    		return $_COOKIE['mock_ip'];
    	}
    	 // +-------------------------------- 测试用的后门，End ----------------------------------------+


    	static $realip = NULL;

    	if ($realip !== NULL) {
    		return $realip;
    	}

    	if (isset ( $_SERVER )) {
    		if (isset ( $_SERVER ['HTTP_X_FORWARDED_FOR'] )) {
    			$arr = explode ( ',', $_SERVER ['HTTP_X_FORWARDED_FOR'] );

    			// 取X-Forwarded-For中第一个非unknown的有效IP字符串
    			foreach ( $arr as $ip ) {
    				$ip = trim ( $ip );
    				if ($ip != 'unknown') {
    					$realip = $ip;
    					break;
    				}
    			}
    		} elseif (isset ( $_SERVER ['HTTP_CLIENT_IP'] )) {
    			$realip = $_SERVER ['HTTP_CLIENT_IP'];
    		} else {
    			if (isset ( $_SERVER ['REMOTE_ADDR'] )) {
    				$realip = $_SERVER ['REMOTE_ADDR'];
    			} else {
    				$realip = '0.0.0.0';
    			}
    		}
    	} else {
    		if (getenv ( 'HTTP_X_FORWARDED_FOR' )) {
    			$realip = getenv ( 'HTTP_X_FORWARDED_FOR' );
    		} elseif (getenv ( 'HTTP_CLIENT_IP' )) {
    			$realip = getenv ( 'HTTP_CLIENT_IP' );
    		} else {
    			$realip = getenv ( 'REMOTE_ADDR' );
    		}
    	}

    	preg_match ( "/[\d\.]{7,15}/", $realip, $onlineip );
    	$realip = ! empty ( $onlineip [0] ) ? $onlineip [0] : '0.0.0.0';

    	return $realip;
    }


 
	/**
     * 获取当前的url
     * @param bool $urlencode 是否对当前的url进行转义
     * @return string $crruen url
     */
	public function getCurUrl($qeruy_string = false,$urlencode = false) {
		if (! empty ( $_SERVER ["REQUEST_URI"] )) {
			$scriptName = $_SERVER ["REQUEST_URI"];
			$nowurl = $scriptName;
		} else {
			$scriptName = $_SERVER ["PHP_SELF"];
			if (empty ( $_SERVER ["QUERY_STRING"] ) || $qeruy_string) {
				$nowurl = $scriptName;
			} else {
				$nowurl = $scriptName . "?" . $_SERVER ["QUERY_STRING"];
			}
		}
		if($qeruy_string){
			$nowurl = $_SERVER['REDIRECT_URL'];
		}

		if ($urlencode == true) {
			return urlencode ( $nowurl );
		} else {
			return $nowurl;
		}
	}
	  
	/**
	 * 获取用户的user agent
	 * @return mixed|multitype:string unknown
	 */
	public function getUerAgent() {
		$u_agent = $_SERVER ['HTTP_USER_AGENT'];
		$bname = 'Unknown';
		$platform = 'Unknown';
		$version = "";
		// First get the platform?
		if (preg_match ( '/linux/i', $u_agent )) {
			$platform = 'linux';
		} elseif (preg_match( '/iPad|iPhone|Android/i', $u_agent)){
			$platform = 'pad';
		} elseif (preg_match ( '/macintosh|mac os x/i', $u_agent )) {
			$platform = 'mac';
		} elseif (preg_match ( '/windows|win32/i', $u_agent )) {
			$platform = 'windows';
		}

		// Next get the name of the useragent yes seperately and for good reason
		if (preg_match ( '/MSIE/i', $u_agent ) && ! preg_match ( '/Opera/i', $u_agent )) {
			$bname = 'Internet Explorer';
			$ub = "MSIE";
		} elseif (preg_match ( '/Firefox/i', $u_agent )) {
			$bname = 'Mozilla Firefox';
			$ub = "Firefox";
		} elseif (preg_match ( '/Chrome/i', $u_agent )) {
			$bname = 'Google Chrome';
			$ub = "Chrome";
		} elseif (preg_match ( '/Safari/i', $u_agent )) {
			$bname = 'Apple Safari';
			$ub = "Safari";
		} elseif (preg_match ( '/Opera/i', $u_agent )) {
			$bname = 'Opera';
			$ub = "Opera";
		} elseif (preg_match ( '/Netscape/i', $u_agent )) {
			$bname = 'Netscape';
			$ub = "Netscape";
		} elseif (preg_match( '/Slurp|bot/i', $u_agent)) {
			$bname = 'sebot';
			$ub = "sebot";
		}

		// finally get the correct version number
		$known = array (
				'Version',
				$ub,
				'other'
		);
		$pattern = '#(?<browser>' . join ( '|', $known ) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		preg_match_all ( $pattern, $u_agent, $matches );
		// see how many we have
		$i = count ( $matches ['browser'] );
		if ($i != 1) {
			// we will have two since we are not using 'other' argument yet
			// see if version is before or after the name
			if (strripos ( $u_agent, "Version" ) < strripos ( $u_agent, $ub )) {
				$version = $matches ['version'] [0];
			} else {
				$version = $matches ['version'] [1];
			}
		} else {
			$version = $matches ['version'] [0];
		}

		// check if we have a number
		if ($version == null || $version == "") {
			$version = "?";
		}

		return array (
				'userAgent' => $u_agent,
				'name' => $bname,
				'version' => $version,
				'platform' => $platform
		);
	}
	
	/*
	 * 判断是否是mobile移动设备，mobile移动设备中又包括平板table和手机phone
	 * 调用extension的class.MobileDetectHelper.php类，来判断是哪种设备

	 */
	
	public function isMobile(){
	    if( $this -> checkWap() ){
	        return true;
	    }
	     
		if ( $this->mobileDetectHelper ->isMobile() ){
			if ( $this->mobileDetectHelper ->isTablet() ){
				return false;
			}else{
				return true;
			}
		}else{
			return false;			
		}
	}
	
	/**
	 * 判断是手机访问pc访问
	 */
	private function checkWap() {
	    if (isset($_SERVER['HTTP_VIA'])) {
	        return true;
	    }
	    if (isset($_SERVER['HTTP_X_NOKIA_CONNECTION_MODE'])) {
	        return true;
	    }
	    if (isset($_SERVER['HTTP_X_UP_CALLING_LINE_ID'])) {
	        return true;
	    }
	    if (strpos(strtoupper($_SERVER['HTTP_ACCEPT']), "VND.WAP.WML") > 0) {
	        // Check whether the browser/gateway says it accepts WML.
	        $br = "WML";
	    } else {
	        $browser = isset($_SERVER['HTTP_USER_AGENT']) ? trim($_SERVER['HTTP_USER_AGENT']) : '';
	        if (empty($browser)) {
	            return true;
	        }
	        $mobile_os_list = array('Google Wireless Transcoder', 'Windows CE', 'WindowsCE', 'Symbian', 'Android', 'armv6l', 'armv5', 'Mobile', 'CentOS', 'mowser', 'AvantGo', 'Opera Mobi', 'J2ME/MIDP', 'Smartphone', 'Go.Web', 'Palm', 'iPAQ');
	
	        $mobile_token_list = array('Profile/MIDP', 'Configuration/CLDC-', '160×160', '176×220', '240×240', '240×320', '320×240', 'UP.Browser', 'UP.Link', 'SymbianOS', 'PalmOS', 'PocketPC', 'SonyEricsson', 'Nokia', 'BlackBerry', 'Vodafone', 'BenQ', 'Novarra-Vision', 'Iris', 'NetFront', 'HTC_', 'Xda_', 'SAMSUNG-SGH', 'Wapaka', 'DoCoMo', 'iPhone', 'iPod');
	
	        $found_mobile = $this -> checkSubstrs($mobile_os_list, $browser) || $this -> checkSubstrs($mobile_token_list, $browser);
	        if ($found_mobile) {
	            $br = "WML";
	        } else {
	            $br = "WWW";
	        }
	    }
	    if ($br == "WML") {
	        return true;
	    } else {
	        return false;
	    }
	}
	
	/**
	 * 判断手机访问， pc访问
	 */
	private function checkSubstrs($list, $str) {
	    $flag = false;
	    for ($i = 0; $i < count($list); $i++) {
	        if (strpos($str, $list[$i]) > 0) {
	            $flag = true;
	            break;
	        }
	    }
	    return $flag;
	}
	
	/*
	 * 判断是否是iPhone移动设备
	* 调用extension的class.MobileDetectHelper.php类，来判断是哪种设备 
	*/
	
	public function isIPhone(){
		if ($this->mobileDetectHelper->isMobile()){
			if ($this->mobileDetectHelper ->isIPhone()){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	/**
	 * 获取当前设备
	 * @return string
	 */
	public function setSiteType(){
		
		if( $this -> isMobile() ){
			if( $this->mobileDetectHelper ->isTablet()  ){
				return 't';//平板
			}
			else{
				return 'm';//异动
			}
		}
		
		return 'd';
	}
	     
	/**
	 * 判断是否是内部IP
	 * @return boolean
	 */
	public function isInternalIp () {
		$ipAddress = $this->clientIp();
		if(in_array($ipAddress, $this->ipWhiteList) || substr($ipAddress, 0, 7) == '192.168'){
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * 判断时间是否属于该区间
	 * @param unknown_type $time
	 * @param unknown_type $startTime
	 * @param unknown_type $endTime
	 */
	public function compareTimeSection($time = NULL, $startTime = NULL, $endTime = NULL) {
		$time = strtotime($time);
		$startTime = strtotime($startTime);
		$endTime = strtotime($endTime);
		return (($time > $startTime) && ($time < $endTime));
	}
	 
	
	/**
     * 过滤非白名单ip
     */
    public function filterIp() {

        if (!in_array(self::clientIp(), $this -> ipWhiteList)) {
            
            return true;
            
        }

        return false;
    }


    /**
     */	
    public function getIp() {
    	return $this -> ipWhiteList;
    }

    /**
     * 频繁访问网站的IP,3小时内禁止访问
     * @param string $route 访问路径
     */
    public function blacklistIPProhibitedAccess($route) {

    	if ( !$route ) {
    		exit('');
    	}

        $clientIp = $this -> clientIp();

    	$blackIp = require(DIR_FS_CLASSES . 'helpers/localip/black_ip.inc');
    	
    	if ( in_array($clientIp, $blackIp) ) {
    		exit('');
    	}

    	$whiteRoute = array(
    		'checkout/confirmation/wx_pay_notify',
    		'checkout/confirmation/alipay_notify',
    	);

    	if ( in_array($route, $whiteRoute) ) {
    		return false;
    	}

        $route = str_replace(array('/','\\'), '_', $route);

        $redisObj = CacheFactory::getRedisCache();

        
        $key = $clientIp.'_'. $route;

        $filterClientIpArray = array();

        $filterClientIpArray = json_decode($redisObj-> get('filter_black_ip_array'),true);

        if ( $filterClientIpArray[$key] && (time() - $filterClientIpArray[$key] < 3600 * 3) ) {
            exit('');
        }

        $count = $redisObj -> get($key);
        
        $count = $count ? $count : 0;
        if ( $count >= 15 ) {

            $filterClientIpArray = array();
            
            $filterClientIpArray = json_decode($redisObj -> get('filter_black_ip_array'), true);
            
            $filterClientIpArray[$key] = time();

            $redisObj -> set('filter_black_ip_array',json_encode($filterClientIpArray));
            exit('');
        }

        $redisObj-> set($key,++$count,10);  

        return true;
    }
}