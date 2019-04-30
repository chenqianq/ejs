<?php 
/**
 * 

 *
 */
class UtilFactory {
	
	//缓存对象仓库
	private static $object_repos = array();
	
	/**
	 * 获取Util类对象
	 * +-------------------+
	 * @param	$class		引入的类名
	 * @param	$moduleDir	目录名，默认是 utils 目录(目录允许自定义，允许传入子目录)
	 * @throws	Object		实例化后的对象
	 */

	private static function innerGetUtil( $class, $moduleDir = 'utils', $singleton = true) {  
		// 保证目录的最后面有一个斜线
		$moduleDir = rtrim( $moduleDir, '/' ) . '/';
		//	获取类名(在实例化的时候也有用到)
		$className = trim( $class ).'Util';
		$php_path = Zc::C('utils.dir.fs') . "/class." . $className . ".php";
		//$php_path = DIR_FS_CATALOG . DIR_WS_CLASSES .$moduleDir . 'class.' . $className . '.php';

		//定义单例的key
		$php_path_key = md5($php_path);
		
		//如果是单例的，并且已经缓存该对象了，直接返回
		if($singleton && isset(self::$object_repos[$php_path_key])) {
			return self::$object_repos[$php_path_key];
		} 

		require_once($php_path);
		
		$obj =  new $className();
        //如果是单例的，需要缓存对象
        if ($singleton) {
            self::$object_repos[$php_path_key] = $obj;
        }

        return $obj;
		
		
	}
	     
      

	/**
	 * 获取SearchUtil类对象
	 * @return SearchUtil
	 * +-------------------+
	 * @param	$class_name
	 * @param	$singleton
	 * @throws	Exception
	 */
	public static function getSearchUtil(){
		return self::innerGetUtil('Search');
	}
	 

}
?>