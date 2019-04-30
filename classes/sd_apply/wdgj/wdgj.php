<?php
/**网店管家的接口
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/8
 * Time: 15:35
 */
class wdgj{
	/**系统参数
	 * @var string
	 */
	//测试的
	/*private $appkey = "71005530";
	private $appSecret  = "n800m9iaei27l1n3b5eh7ko87gfl2k0g";
	private $accessToken  = "191167eb31f242bfa4a089359b77f5e4";*/
	
	private $appkey = "06216632";
	private $appSecret  = "36fh2a138h803lk3m4m7g7531l1la9og";
	private $accessToken  = "7e1d85ca34774943974586e4d0e18616";
	
	private $format  = "json";
	private $versions = "1.1";

	private $url = "http://api.wdgj.com/wdgjcloud/api";
	
	private $curl;
	
	public function __construct()
	{
		$this -> curl = HelperFactory::getCurlHelper();
	}
	
	/**系统级的参数
	 * @return mixed
	 */
	public function getSystemConfig(){
		$param['appkey'] = $this -> appkey;
		$param['appSecret'] = $this -> appSecret;
		$param['accesstoken'] =  $this -> accessToken;
		$param['format'] = $this -> format ;
		$param['versions'] = $this -> versions;
		$param['timestamp'] = (string)time();
		return $param;
	}
	
	
	
	/**获得签名
	 * @param $param 这是传过去的数组
	 */
	public function getSign($param){
		
		
		$filtered_array = $this -> para_filter($param);
		$sort_array = $this->arg_sort($filtered_array);
		
		$arg = '';
		while (list ($key, $val) = each ($sort_array)) {
			$arg .= $val;
		}
		
		$needArg = $param['appSecret'].$arg.$param['appSecret'];
		
		return md5($needArg);
	}
	
	/**获得所有的仓库
	 * @param $codeArr
	 * @return mixed
	 */
	public function getAllWarehouse($codeArr=[]){
		
		$redisObj = CacheFactory::getRedisCache();
		$key = CacheKeyBuilder::buildSdWarehouseKey();
		
		if(!($redisObj -> get($key))){
		
			$param = $this -> getSystemConfig();
			$param['pageno'] = '1';
			$param['pagesize'] = '100';
			$param['method'] = 'wdgj.warehouse.list.get';
			$param['sign'] = $this -> getSign($param);
		
			$res = $this -> create_post($param);
			
			$return = $this -> curl -> post($this -> url, [$res], '', '', true);
			//将返回的信息存入log中
			$date = date('Y-m-d-H');
			$logName = 'wharehouselist-'.md5($date)."-".date('Y-m-d-H');
			
			$logObj = LogFactory::getBizLog($logName);
		
			$logObj->log('==================仓库的数组=======================' . "\n");
		
			$logObj->log(print_r($param, true) . "\n");
		
			$logObj->log('==================仓库的post=======================' . "\n");
			$logObj->log(print_r($res, true) . "\n");
			$logObj->log('==================仓库的返回=======================' . "\n");
			$logObj->log(print_r($return, true));
			$logObj->log('==================结速=======================' . "\n");
			
			$result= $this -> processWarehouseJson($return,$codeArr);
			//var_dump($bondedWarehouseIdArr);
			//访问接口并且处理后存入redis
			$warehouseArr = $result;
			$value = json_encode($warehouseArr);
			
			//一个小时的过期 时间
			$redisObj -> set($key,$value,3600);
		}
		
		$warehouse = $redisObj -> get($key);
		
		return json_decode($warehouse,true);
	}
	

	
	/**
	 * @param $res
	 */
	private function processWarehouseJson($res){
		$result = json_decode($res,true);
		
		$retrunArray = [];
		$bondedWarehouseIdArr = [];
		foreach ($result['datalist'] as $k => $warehouse){
			if($warehouse['warehouseid'] == 1008){//总仓不用出现
				continue;
			}
			
			$retrunArray[$warehouse['warehouseid']] = $warehouse['warehousename'];
		}
		
		return $retrunArray;
	
	}
	
	
	/**
	 * 获得店铺的Id
	 */
	public function getShopList(){
		
		$redisObj = CacheFactory::getRedisCache();
		$key = CacheKeyBuilder::buildSdShopKey();
		
		
		if(!($redisObj -> get($key))){
			$param = $this -> getSystemConfig();
			$param['pageno'] = '1';
			$param['pagesize'] = '100';
			$param['method'] = 'wdgj.shop.list.get';
			//$param['searchname'] = 'test23';
			$param['sign'] = $this -> getSign($param);
			$res = $this -> create_post($param);
			
			$return = $this -> curl -> post($this -> url, [$res], '', '', true);
			
			//将返回的信息存入log中
			$date = date('Y-m-d H:i:s');
			$logName = 'shopList-'.md5($date);
			
			$logObj = LogFactory::getBizLog($logName);
			$logObj->log('==================cart products begin=======================' . "\n");
			$logObj->log(print_r($return, true));
			$logObj->log('==================cart products end=======================' . "\n");
			
			//echo $return;
			$result = $this -> processShopJson($return);
			
			//访问接口并且处理后存入redis
			$value = json_encode($result);
			
			//一个小时的过期 时间
			$redisObj -> set($key,$value,$expire=3600);
		}
		$shopnoJson = $redisObj -> get($key);
		
		return json_decode($shopnoJson,true);
		
	}
	
	public function getUrl(){
		return $this -> url;
	}
	
	/**处理商铺列表的Xml
	 * @param $json
	 * @return bool array('仓库id'=>'店铺id');
	 */
	private function processShopJson($json){
		$return = json_decode($json,true);
	
		$retrunArray = [];
		foreach ($return['datalist'] as $data){
			if($data['warehouseid']<=0){
				continue;
			}
			$retrunArray[$data['warehouseid']] = $data['shopid'];
		}
	
		return $retrunArray?:false;
	}
	
	
	
	
	
	/**处理提交过去的参数
	 * @param $params
	 * @return bool|string
	 */
	public function create_post($params){
		
		$arg = "";
		while (list ($key, $val) = each ($params)) {
			$arg.=$key."=".urlencode($val)."&";
		}
		$arg = substr($arg,0,-1);
		return $arg;
	}
	
	public function getReturnJsonByApi($data){
		return  $this -> curl -> post($this -> url, [$data], '', '', true);
	}
	
	/**将数组的值进行排序
	 * @param $filtered_array
	 */
	private function arg_sort($filtered_array){
		
		asort($filtered_array,SORT_STRING );
		
		reset($array);
		return $filtered_array;
	}
	
	
	/**
	 * 除去数组中的空值和签名模式
	 *
	 * @param array $parameter
	 * @return array
	 */
	private function para_filter($parameter) {
		$para = array();
		while (list ($key, $val) = each ($parameter)) {
			if($key == "sign"||$val='' || $key=="appSecret")continue;
			else	$para[$key] = $parameter[$key];
		}
		
		return $para;
	}
	
	
	public function getHeaderXml(){
		return '<?xml version="1.0" encoding="utf-8"?>';
	}

	public function getOutXml($str,$param){
		return '<'.trim($str).'>'.$param.'</'.trim($str).'>';
		
		
		
	}
	public function getInnerXmlByArr($arr){
		$arg = '';
		foreach ($arr as $key => $val){
			$arg.="<".$key.">".$val."</".$key.">";
		}
		
		return $arg;
		
	}
	
	
	
}