<?php
/**
 * 
 * 缓存封装
 * AbstractCache 缓存的抽象类
 *    -- FileCache 基于文件的缓存实现
 *    -- MemCache  基于Memcaced的缓存实现
 * CacheFactory  缓存对象的工厂类
 * 
 * @author kinsly 2016-10-20
 *
 */
if( !defined('IS_ACCESS') ){
    die('Illegal Access');
    exit;
}

if (!class_exists('LogFactory')) {
	require_once DIR_FS_CLASSES . 'logs/class.LogFactory.php';
}

class YfjCacheMemcached extends ZcCacheMemcached {
    private static $requestCache = array();
    
    /**
     * 是否在本地有保存
     */
    private static function localCached($key){
        return array_key_exists($key, YfjCacheMemcached::$requestCache);
    }
    
    /**
     * 获取本地的缓存数据
     */
    private static function localGet($key){
        return self::$requestCache[$key];
    }
    
    /**
     * 设置保存本地的缓存数据
     */
    private static function localSet($key, $value){
        self::$requestCache[$key] = $value;
    }
    
	public function get($key) {
		if ($_GET['refresh_cached']) {
			Zc::dump($key);
			return false;
		}
		
        if(self::localCached($key)){
            return self::localGet($key);
        }
        else {
            $value = parent::get($key);
            self::localSet($key, $value);
            return $value;
        }
		//return $requestCache[$key];
	}
}

class YfjCacheRedis extends ZcCacheRedis {
	public function get($key) {
		if ($_GET['refresh_cache']) { 
			Zc::dump('redis-'.date('Y-m-d H:i:s').$key);
			return false;
		}
		return parent::get($key);
	}
}

class YfjCacheFile extends ZcCacheFile {
    public function get($key) {
        if ($_GET['refresh_cache']) {
            Zc::dump('file-'.date('Y-m-d H:i:s').$key);
            return false;
        }
        return parent::get($key);
    }
}

class CacheFactory {
	/**
	 * 缓存一些小的业务数据：比如业务数据、Widget的静态页面
	 * @return CacheMemcache
	 */
	public static function getSmallCache() {
		global $g_config_memcached_servers;
		$timestamp = '20161019';
		$cacheInstanceKey = 'small_cache' . $timestamp;
		
		//debu模式
		if (defined('G_RUNTIME_MODE') && G_RUNTIME_MODE == 'dev') {
			return Zc::getCache($cacheInstanceKey, 'debug');
		}
		return Zc::getCache($cacheInstanceKey, '', '', $g_config_memcached_servers, 'YfjCacheMemcached');
	}
	
	/**
	 * 缓存业务数据，大数据都可以往这边走
	 * @return getRedisCache
	 */
	public static function getRedisCache(){
	     
		$g_config_redis_servers = Zc::C(ZcConfigConst::RedisConfig);
	    
		$timestamp = '20161019';
		$cacheInstanceKey = 'redis_cache' . $timestamp;
		$cacheInstanceKey = 'file_cache' . $timestamp;
		// return Zc::getCache($cacheInstanceKey, '', '', $g_config_redis_servers, 'YfjCacheFile');
		if (!extension_loaded('redis')) {
		    $cacheInstanceKey = 'file_cache' . $timestamp;
		    return Zc::getCache($cacheInstanceKey, '', '', $g_config_redis_servers, 'YfjCacheFile');
		}
		
		//debu模式
		if (defined('G_RUNTIME_MODE') && G_RUNTIME_MODE == 'dev') {
			return Zc::getCache($cacheInstanceKey, 'debug');
		}
		
		$redisCacheObj = Zc::getCache($cacheInstanceKey, '', '', $g_config_redis_servers, 'YfjCacheRedis'); 
		
		if( $redisCacheObj -> set('test-caches-classs-set', 'test') ){
		    return $redisCacheObj;
		}
		
		return Zc::getCache($cacheInstanceKey, '', '', $g_config_redis_servers, 'YfjCacheFile',true);
	}
}

?>