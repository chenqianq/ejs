<?php
HelperFactory::getSafeHelper();

/**
 +--------------------------------------+
 * Cookie管理类
 +--------------------------------------+
 +--------------------------------------+
 */
class Cookie {
    /**
     * 判断Cookie是否存在
     * @param $primary  主键值
     * @param $secondary  次键值，可以不设置
     */ 
    static function is_set($primary,$secondary='') {
    	//键值不存在或者值为空，则结果为false;
    	$value = !empty($secondary) ? $_COOKIE[$primary][$secondary] : $_COOKIE[$primary];
    	if(!empty($value) || is_numeric($value) ){
    		return true;
    	}else{
    		return false;
    	}
    }

    /**
     * 获取某个Cookie值
     * @param $primary  主键值
     * @param $secondary  次键值，可以不设置
     * @param $decode  是否需要解密
     */ 
    static function get($primary,$secondary='',$decode=true) {
        $value = !empty($secondary) ? $_COOKIE[$primary][$secondary] : $_COOKIE[$primary] ;
        if($decode) $value= Safe::decode($value);
        return $value;
    }

    /**
     * 设置某个Cookie值
     * @param $primary  主键值
     * @param $secondary  次键值，可以不设置
     * @param $value  
     * @param $expire  //默认设置为会话过期,
     * @param $path  
     * @param $domain  
     * @param $encode  是否需要加密
     */ 
    static function set($primary,$secondary='',$value,$expire=0,$encode=true,$path='/',$domain='') {

    	if(empty($domain)){
    		$domain = Zc::C(ZcConfigConst::Domain);
    	}

        $value =  $encode && $value ? Safe::encode($value) : $value; 
        if(!empty($secondary)){
	        setcookie($primary.'['.$secondary.']', $value,$expire,$path,$domain);
	        $_COOKIE[$primary][$secondary] = $value;
        }else{
	        setcookie($primary, $value,$expire,$path,$domain);
	        $_COOKIE[$primary] = $value;
        }
    }

    /**
     * 删除某个Cookie值
     * @param $primary  主键值
     * @param $secondary  次键值，可以不设置
     */ 
    static function delete($primary,$secondary='') {
    	if(!empty($secondary)){
	        Cookie::set($primary,$secondary,'',time()-100);
	        unset($_COOKIE[$primary][$secondary]);
    	}else{
	        Cookie::set($primary,'','',time()-100);
	        unset($_COOKIE[$primary]);
    	}
    }

    /**
     * 清空Cookie值
     */ 
    static function clear() {
    	$_COOKIE = array();
        unset($_COOKIE);
    }
}