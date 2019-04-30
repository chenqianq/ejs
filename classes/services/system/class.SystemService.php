<?php
if( !defined('IS_ACCESS') ){
    die('Illegal Access');
    exit;
}
/**
 * 这个是样例 ,业务逻辑都在这个层次， 切记。
 * @author Administrator
 *
 */

class SystemService {


	 /**
	  *
	  * @var SettingDao
	  */
	 private $settingDao;

	/**
	 * 构造函数初始化ID
	 */
	public function __construct() {

		//Dao
		$this -> settingDao = DaoFactory::getSettingDao();
	}

	/**
     * 获取系统的所有配置
     * @return boolean|unknown[]
     */
    public function getAllSetting()
    {
        $settingArray = $this->settingDao->getAllSetting();
        if (!$settingArray) {
            return false;
        }

        foreach ( $settingArray as $key => $setting ) {
            if (strtoupper($key) == "MD5_KEY") {
                continue;
            }
            define(strtoupper($key), $setting);
        }

        return true;
    }

    private function isSerialized($data)
    {
        $data = trim($data);
        if ('N;' == $data) return true;
        if (!preg_match('/^([adObis]):/', $data, $badions)) return false;
        switch ($badions[1]) {
            case 'a' :
            case 'O' :
            case 's' :
                if (preg_match("/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data)) return true;
                break;
            case 'b' :
            case 'i' :
            case 'd' :
                if (preg_match("/^{$badions[1]}:[0-9.E-]+;\$/", $data)) return true;
                break;
        }
        return false;
    }

    /**
     * 根据KEY 获取系统常量
     * @param unknown $key
     * @return boolean|unknown|string|mixed
     */
    public function getSettingByKey($key){
        if( !$key ){
            return false;
        }

        if( defined(strtoupper($key)) ){
            $setting = constant(strtoupper($key));
            if( $this -> isSerialized($setting) ){
                return unserialize($setting);
            }

            return $setting;
        }

        return $this -> settingDao -> getSettingByName($key);
    }

    /**
     * 获取系统设置信息
     */
    public  function getAllSystemList(){
        return $this -> settingDao -> getAllSystemList();
    }

    /**
     * 根据配置名获得配置信息
     */

    public  function getSettingInfoByName($systemName){
        return $this -> settingDao -> getSettingInfoByName($systemName);
    }

    /**
     * 保存系统配置
     */

    public  function saveSettingInfo($data){

        return $this -> settingDao -> saveSettingInfo($data);

    }

    /**
     * 根据系统配置名称修改配置的值
     * @param array  $data array('title'=>xxxx,'value'=>xxxx)
     * @param string $name 系统配置名称
     */
    public function updateSettingInfoByName($data, $name) {
        return $this -> settingDao -> updateSettingInfoByName($data, $name);
    }


    /**
     * 保存App系统配置
     */

    public  function saveAppSettingInfo($data){

        return $this -> settingDao -> saveAppSettingInfo($data);

    }

    /**
     * 验证添加的系统设置是否存在
     */
    public  function checkSettingName($name){

        return $this -> settingDao -> checkSettingName($name);

    }

    /**
     * 添加系统设置
     * @param array $data
     * @return bool
     */
    public function insertSetting($data) {
        return $this -> settingDao -> insertSetting($data);
    }

    /**
     * 根据名称数组获得配置信息
     */

    public  function getSettingInfoByNameArray($nameArray){

        if(empty($nameArray) || !is_array($nameArray)){
            return false;
        }
        $sql = "(";
        foreach ($nameArray as $item) {
            $sql .= "'" . $item . "'" . ",";
       }
        $sql .= ")";
        $sql = substr($sql,0,-2).")";

        $result = $this -> settingDao -> getSettingInfoByNameArray($sql);

        foreach ($result as $item) {
            $return[$item['name']] = array('title' => $item['title']?$item['title']:'','subTitle'=> $item['value']?$item['value']:'');
        }

        return $return;
    }
    /**
     * 获得错误日志
     */
    public function getError(){

        return $this -> settingDao -> getError();

    }

    /**
     * @return错误日志条数
     */
    public function getErrorNum(){
        return $this -> settingDao -> getErrorNum();
    }
    /**
     * 插入错误日志
     */
    public function insertMonitorLog($data){
        if(empty($data) || !is_array($data)){
            return false;
        }

        $this -> settingDao -> insertMonitorLog($data);
    }

    /**
     * 根据设置名称获取搜索栏文案
     */
    public function getSearchBar($data){
        if(empty($data)){
            return false;
        }

        return $this -> settingDao -> getSearchBar($data);

    }

    /**
     * 插入汇率日志信息
     * @param $data
     */
    public function insertExchangeRateLog($data){
          if(empty($data) || !is_array($data)){
              return false;
          }
         $rs =  $this -> settingDao -> insertExchangeRateLog($data);
          if(!$rs){
              return false;
          }
          return $rs;
    }

    /**
     * 根据名称数据返回配置信息
     * @param array $nameArray
     * @return array 
     */
    public function getSettingListByNameArray($nameArray) {
        return $this -> settingDao -> getSettingListByNameArray($nameArray);
    }
}
