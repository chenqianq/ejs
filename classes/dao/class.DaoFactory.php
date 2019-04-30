<?php
 
if( !defined('IS_ACCESS') ){
    die('Illegal Access');
    exit;
}

 
class DaoFactory {
	
	//缓存对象仓库
	private static $object_repos = array();
	
	/**
	 * 获取Service类对象
	 * @param  $module
	 * @param  $service_class
	 * @throws Exception
	 */
	private static function innerGetService($module, $class_name, $singleton) {
	   
		$php_path = Zc::C('dao.dir.fs');
		if(!empty($module)){
			$php_path .= $module . '/';
		}
		$php_path .= "class." . $class_name . ".php";
		
		//定义单例的key
		$php_path_key = md5($php_path);
		
		//如果是单例的，并且已经缓存该对象了，直接返回
		if($singleton && isset(self::$object_repos[$php_path_key])) {
			return self::$object_repos[$php_path_key];
		} 

		if (require_once ($php_path)) {
 	 	  
            $obj =  new $class_name;
          
            //如果是单例的，需要缓存对象
            if ($singleton) {
            	self::$object_repos[$php_path_key] = $obj;
            }
            return $obj;
        } else {
            throw new Exception ($class_name . ' not found');
        }
	}
	
	
	/**
	 * @param bool $singleton
	 * @return AdminDao
	 */
	public static function getAdminDao($singleton = true){
	    return self::innerGetService('', 'AdminDao', $singleton);
	}
	
	/**
	 * @param bool $singleton
	 * @return SettingDao
	 */
	public static function getSettingDao($singleton = true){
		return self::innerGetService('', 'SettingDao', $singleton);
	}
	/**
	 * @param bool $singleton
	 * @return SettingLogDao
	 */
	public static function getSettingLogDao($singleton = true){
		return self::innerGetService('', 'SettingLogDao', $singleton);
	}
	
	/**crm
	 * @param bool $singleton
	 * @return crmDao
	 */
	public static function getCrmDao($singleton = true){
		return self::innerGetService('', 'crmDao', $singleton);
	}

    /**
     * SmsLogDao
     * @param bool $singleton
     * @return SmsLogDao
     */
    public static function getSmsLogDao($singleton = true) {
        return self::innerGetService('', 'SmsLogDao', $singleton);
    }

    /**
     * GoodsDao
     * @param bool $singleton
     * @return GoodsDao
     */
    public static function getGoodsDao($singleton = true) {
        return self::innerGetService('goods', 'GoodsDao', $singleton);
    }
}
