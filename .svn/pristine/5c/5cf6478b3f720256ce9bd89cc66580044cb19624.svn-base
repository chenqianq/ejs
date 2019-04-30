<?php
/**
 * 
 * 基于redis的cache实现
 * Redis 操作，支持 Master/Slave 的负载集群
 * 
 * @author kinsly.xu 2014-12-08 15:28:00
 *
 */

class ZcCacheRedis extends ZcAbstractCache {

	// 是否使用 M/S 的读写集群方案
	private $_isUseCluster = false;

	// Slave 句柄标记
	private $_sn = 0;

	// 服务器连接句柄
	private $_linkHandle = array (
			'master' => null, // 只支持一台 Master
			'slave' => array ()  // 可以有多台 Slave
	);

	/**
	 * 构造函数
	 *
	 * @param boolean $isUseCluster
	 *        	是否采用 M/S 方案
	*/
	public function __construct($timestamp, $options = '', $isUseCluster = true) {
	    
		if (!extension_loaded('redis')) {
			$this->conntected = false;
			return;
		}
		
		$this->_isUseCluster = $isUseCluster;
		
		$this->log = Zc::getLog('cache/redis_cache.log');
		
		$this->timestamp = $timestamp;
		
		if (empty($options)) {
			$serverConfig = array (
					'master' => array (
							'host' => '127.0.0.1',
							'port' => 6379
					)
			);
		} else {
			$serverConfig = $options;
		}
		 
		//$this -> setCacheType();
		$this -> connect($serverConfig);
		
	}
	
	/**
	 * 设置当前的cache 类型
	 * @return string
	 */
	public function setCacheType(){
		$this -> cacheType = 'ZcCacheRedis';
	}
	
	/**
	 * 返回当前的cache 类型
	 * @return string
	 */
	public function getCacheType(){
		return $this -> cacheType;
	}
	
	

	/**
	 * 连接服务器,注意：这里使用长连接，提高效率，但不会自动关闭
	  *
	 * @param array $config
	 *        	Redis服务器配置
	 * @param boolean $isMaster
	 *        	当前添加的服务器是否为 Master 服务器
	 * @return boolean
	 */
	public function connect($configArray = array('master' => array('host'=>'127.0.0.1','port'=>6379))) {
		if( empty($configArray) ){
			return false;
		}
		
		foreach ( $configArray as $redisType => $config ){
			// default port
			/* if (! isset ( $config ['port'] )) {
				$config ['port'] = 6379;
			} */
			
			// 设置 Master 连接
			if ( 'master' == $redisType ){
				$this->_linkHandle['master'] = new Redis();
				$this->_linkHandle['master'] -> pconnect($config ['host'], $config ['port']);
				if( $this->_linkHandle['master'] === false ) {
					$this->log->error("$config[host] : $config[port] master pconnect Failed");
				}
			}
			else{
				// 多个 Slave 连接
				foreach ( $config as $cf ){
					
					$this->_linkHandle ['slave'] [$this->_sn] = new Redis ();
					
					$this->_linkHandle ['slave'] [$this->_sn]->pconnect ( $cf ['host'], $cf ['port'] );
					
					if( $this->_linkHandle ['slave'] [$this->_sn] === false ) {
						$this->log->error("$cf[host] : $cf[port] slave pconnect Failed");
					}
					++ $this->_sn;
				}
				
			}
		}
		
		register_shutdown_function(array($this, 'close'));
		
		if ( $this->_linkHandle['master'] ) {
			$this->conntected = true;
			return $this->_linkHandle;
		}
		
		return false;
	}
	
	/**
	 * 尝试3 次握手， 查看连接是否失效
	 * @param unknown $linkHandle
	 * @return boolean
	 */
	private function ping($linkHandle){
		if( !$linkHandle ){
			return false;
		}
		
		for ( $i = 0 ; $i < 3 ; $i++ ){ 
			if( $linkHandle -> socket ){
				return $linkHandle;
			}
		}
		
		return false;
	}

	/**
	 * 关闭连接
	 *
	 * @param int $flag
	 *        	关闭选择 0:关闭 Master 1:关闭 Slave 2:关闭所有
	 * @return boolean
	 */
	public function close($flag = 2) {
		switch ($flag) {
			// 关闭 Master
			case 0 :
				$linkHandle = $this->getRedis ();
				if( !$linkHandle ){
					return false;
				}
				
				$linkHandle -> close ();
				break;
				// 关闭 Slave
			case 1 :
				for($i = 0; $i < $this->_sn; ++ $i) {
					if( !$this -> ping($this->_linkHandle ['slave'] [$i]) ){
						continue;
					}
					
					$this->_linkHandle ['slave'] [$i]->close ();
				}
				break;
				// 关闭所有
			case 2 :
				$linkHandle = $this->getRedis ();
				if( !$linkHandle ){
					return false;
				}
				
				$linkHandle -> close ();
				for($i = 0; $i < $this->_sn; ++ $i) {
					if( !$this -> ping($this->_linkHandle ['slave'] [$i]) ){
						continue;
					}
					
					$this->_linkHandle ['slave'] [$i]->close ();
				}
				break;
		}
		return true;
	}

	/**
	 * 得到 Redis 原始对象可以有更多的操作
	 *
	 * @param boolean $isMaster
	 *        	返回服务器的类型 true:返回Master false:返回Slave
	 * @param boolean $slaveOne
	 *        	返回的Slave选择 true:负载均衡随机返回一个Slave选择 false:返回所有的Slave选择
	 * @return redis object
	 */
	public function getRedis($isMaster = true, $slaveOne = true) {
		// 只返回 Master
		
		if ($isMaster) {
			$linkHandle = $this->_linkHandle ['master'];
		} else {
			$linkHandle = $slaveOne ? $this->_getSlaveRedis () : $this->_linkHandle ['slave'];
		}
		
		if( !$this -> ping($linkHandle) ){
			return false;
		}
			
		return $linkHandle;
	}

	/**
	 * 写缓存
	 *
	 * @param string $key
	 *        	组存KEY
	 * @param string $value
	 *        	缓存值
	 * @param int $expire
	 *        	过期时间， 0:表示无过期时间
	 */
	public function set($key, $value, $expire = 3600) {
		
		$value = serialize($value);
		$linkHandle = $this->getRedis ();
		
		if( !$linkHandle ){
			return false;
		}
		
		// 永不超时
		if ($expire == 0) {
			$ret = $linkHandle -> set ( $key, $value );
		} else {
			$ret = $linkHandle -> setex ( $key, $expire, $value );
		}
		//var_dump($ret);exit;
		return $ret;
	}

	/**
	 * 读缓存
	 *
	 * @param string $key
	 *        	缓存KEY,支持一次取多个 $key = array('key1','key2')
	 * @return string || boolean 失败返回 false, 成功返回字符串
	 */
	public function get($key) {
		// 是否一次取多个值
		$func = is_array ( $key ) ? 'mGet' : 'get';
		
		// 没有使用M/S
		if (! $this->_isUseCluster ) {
			$linkHandle = $this->getRedis ();
			if( !$linkHandle ){
				return false;
			}
			
			$value = $linkHandle -> {$func} ( $key );
			return unserialize($value);
		}
		
		$linkHandle = $this->_getSlaveRedis ();
		
		if( !$linkHandle ){
			return false;
		}
		
		// 使用了 M/S
		$value = $linkHandle -> {$func} ( $key );
		return unserialize($value);
	}
	 

	/*
	 * // magic function public function __call($name,$arguments){ return call_user_func($name,$arguments); }
	*/
	/**
	 * 条件形式设置缓存，如果 key 不存时就设置，存在时设置失败
	 *
	 * @param string $key
	 *        	缓存KEY
	 * @param string $value
	 *        	缓存值
	 * @return boolean
	 */
	public function setnx($key, $value) {
		$linkHandle = $this->getRedis ();
		if( !$linkHandle ){
			return false;
		}
		
		return $linkHandle -> setnx ( $key, $value );
	}

	/**
	 * 删除缓存
	 *
	 * @param
	 *        	string || array $key 缓存KEY，支持单个健:"key1" 或多个健:array('key1','key2')
	 * @return int 删除的健的数量
	 */
	public function delete($key) {
		// $key => "key1" || array('key1','key2')
		$linkHandle = $this->getRedis ();
		if( !$linkHandle ){
			return false;
		}
		
		return $linkHandle -> delete ( $key );
	}

	/**
	 * 值加加操作,类似 ++$i ,如果 key 不存在时自动设置为 0 后进行加加操作
	 *
	 * @param string $key
	 *        	缓存KEY
	 * @param int $default
	 *        	操作时的默认值
	 * @return int
	 */
	public function incr($key, $default = 1) {
		$linkHandle = $this->getRedis ();
		if( !$linkHandle ){
			return false;
		}
		
		if ($default == 1) {
			return $linkHandle -> incr ( $key );
		} else {
			return $linkHandle -> incrBy ( $key, $default );
		}
	}

	/**
	 * 值减减操作,类似 --$i ,如果 key 不存在时自动设置为 0 后进行减减操作
	 *
	 * @param string $key
	 *        	缓存KEY
	 * @param int $default
	 *        	操作时的默认值
	 * @return int
	 */
	public function decr($key, $default = 1) {
		$linkHandle = $this->getRedis ();
		if( !$linkHandle ){
			return false;
		}
		
		if ($default == 1) {
			return $linkHandle -> decr ( $key );
		} else {
			return $linkHandle -> decrBy ( $key, $default );
		}
	}

	/**
	 * 添空当前数据库
	 *
	 * @return boolean
	 */
	public function clear() {
		$linkHandle = $this->getRedis ();
		if( !$linkHandle ){
			return false;
		}
		
		return $linkHandle -> flushDB ();
	}

	/* =================== 以下私有方法 =================== */

	/**
	 * 随机 HASH 得到 Redis Slave 服务器句柄,如果slave  都挂了， 那么读主库
	 *
	 * @return redis object
	 */
	private function _getSlaveRedis() {
		// 就一台 Slave 机直接返回
		
		if ($this->_sn <= 1) {
			$linkHandle = $this->_linkHandle ['slave'] [0];
			
			if( $linkHandle && $this -> ping($linkHandle) ){
				return $linkHandle;
			}
			///如果就一个slave 那么就直接返回主库查询
			$linkHandle = $this->getRedis (true,false);
			
			if( !$this -> ping($linkHandle) ){
				return false;
			}
		}
		else{
			// 随机 Hash 得到 Slave 的句柄
			$hash = $this->_hashId ( mt_rand (), $this->_sn );
			$linkHandle = $this->_linkHandle ['slave'] [$hash];
			if( !$this -> ping($linkHandle) ){
				unset($this->_linkHandle ['slave'] [$hash]);
				$this->_sn--;
				
				//如果还有slave 那么对$slave 进行重新排序，进行2次的分配slave
				if( $this->_linkHandle ['slave'] ){
					$tmpSlaveArray = array();
					foreach ( $this->_linkHandle ['slave'] as $slave ){
						$tmpSlaveArray[] = $slave;
					}
					
					$this->_linkHandle ['slave'] = $tmpSlaveArray;
				}
				
				$linkHandle = $this -> _getSlaveRedis();
			}
			
			if( !$linkHandle ){
				return false;
			}
		}
		
		return $linkHandle;
	}

	/**
	 * 根据ID得到 hash 后 0～m-1 之间的值
	 *
	 * @param string $id
	 * @param int $m
	 * @return int
	 */
	private function _hashId($id, $m = 10) {
		// 把字符串K转换为 0～m-1 之间的一个值作为对应记录的散列地址
		$k = md5 ( $id );
		$l = strlen ( $k );
		$b = bin2hex ( $k );
		$h = 0;
		for($i = 0; $i < $l; $i ++) {
			// 相加模式HASH
			$h += substr ( $b, $i * 2, 2 );
		}
		$hash = ($h * 1) % $m;
		return $hash;
	}

	/**
	 * lpush
	 */
	public function lpush($key, $value) {
		$linkHandle = $this->getRedis ();
		if( !$linkHandle ){
			return false;
		}
		
		return $linkHandle -> lpush ( $key, $value );
	}

	/**
	 * add lpop
	 */
	public function lpop($key) {
		$linkHandle = $this->getRedis ();
		if( !$linkHandle ){
			return false;
		}
		
		return $linkHandle -> lpop ( $key );
	}
	/**
	 * lrange
	 */
	public function lrange($key, $start, $end) {
		$linkHandle = $this->getRedis ();
		if( !$linkHandle ){
			return false;
		}
		
		return $linkHandle -> lrange ( $key, $start, $end );
	}

	/**
	 * set hash opeation
	 */
	public function hset($name, $key, $value) {
		$linkHandle = $this->getRedis ();
		if( !$linkHandle ){
			return false;
		}
		
		if (is_array ( $value )) {
			return $linkHandle -> hset ( $name, $key, serialize ( $value ) );
		}
		return $linkHandle -> hset ( $name, $key, $value );
	}
	/**
	 * get hash opeation
	 */
	public function hget($name, $key = null, $serialize = true) {
		$linkHandle = $this->getRedis ();
		if( !$linkHandle ){
			return false;
		}
		
		if ($key) {
			$row = $linkHandle -> hget ( $name, $key );
			if ($row && $serialize) {
				unserialize ( $row );
			}
			return $row;
		}
		return $linkHandle -> hgetAll ( $name );
	}

	/**
	 * delete hash opeation
	 */
	public function hdel($name, $key = null) {
		$linkHandle = $this->getRedis ();
		if( !$linkHandle ){
			return false;
		}
		
		if ($key) {
			return $linkHandle -> hdel ( $name, $key );
		}
		
		return $linkHandle -> hdel ( $name );
	}
	/**
	 * Transaction start
	 */
	public function multi() {
		$linkHandle = $this->getRedis ();
		if( !$linkHandle ){
			return false;
		}
		
		return $linkHandle -> multi ();
	}
	/**
	 * Transaction send
	 */
	public function exec() {
		$linkHandle = $this->getRedis ();
		if( !$linkHandle ){
			return false;
		}
		
		return $linkHandle -> exec ();
	}
	
}




?>