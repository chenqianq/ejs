<?php
if (!defined('IS_ACCESS')) {
    die('Illegal Access');
    exit;
}

/**
 * dao 文件 就负责 与数据库交互，不做任何的业务逻辑处理。
 * @author Administrator
 *
 */
class SettingDao
{

    /**
     *
     * @var db
     */
    private $db;

    /**
     * DbExtend
     * @var DbExtend
     */
    private $dbExtendHelper;

    private $yfjRedis;

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->db = Zc::getDb();
        //helper
        $this->dbExtendHelper = HelperFactory::getDbExtendHelper();

        $this->pageHelper = HelperFactory::getPageHelper();
        //$this->yfjRedis = new YfjCacheRedis();
    }

    /**
     * 获取配置信息
     * @param $fieldName
     * @return mixed|string
     */
    public function getSettingByName($fieldName, $cacheTime =43200)
    {
        $sql = "select * from " . TableConst::TABLE_SETTING . ' where name=' . "'$fieldName'";
        // echo $sql. "<br>";
        $setting_row = $this->db->getRow($sql, $cacheTime);
        if($this->is_serialized($setting_row['value'])){
            $value =$setting_row['value']? unserialize($setting_row['value']):'';
        }else{
            $value =$setting_row['value']?$setting_row['value']:'';
        }
        return $value;
    }

   private function is_serialized($data)
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
     * 获取系统的所有配置
     * @return boolean|unknown[]
     */
    public function getAllSetting(){
        $sql = "select name , value from " . TableConst::TABLE_SETTING ;
        $settingArray = $this -> db -> getRows($sql,3600);
        if( !$settingArray ){
            return false;
        }
        
        $return = [];
        foreach ( $settingArray as $setting ){
            $return[$setting['name']] = $setting['value'];
        }
        
        return $return;
    }


    /**
     * 获取系统所有配置信息
     */

    public  function getAllSystemList(){

        $sql  =  'select * from ' . TableConst::TABLE_SETTING . "  where type  != 2 or type is NUll order by create_time desc";

        $this-> pageHelper ->init();

        $sql =  $this->pageHelper -> getLimit($sql);

        $settingList = $this -> db -> getRows($sql);

        return $settingList;
    }

    /**
     * 根据系统设置名获得信息
     */
    public  function  getSettingInfoByName($settingName){

        if(empty($settingName)){

            return false;

        }

        $sql = 'select * from ' . TableConst::TABLE_SETTING . " where name = '$settingName'";

        $settingInfo = $this -> db -> getRow($sql);

        return $settingInfo;
    }
    /**
     * 保存系统配置
     */

    public  function saveSettingInfo($data){

        if(empty($data)){

            return false;

        }

        $sql = "update " . TableConst::TABLE_SETTING . " set title = '$data[settingtTitle]',value = '$data[settingValue]' where name = '$data[settingName]'";

        $result = $this -> db -> exec($sql);

        return $result;
    }

    /**
     * 根据系统配置名称修改配置的值
     * @param array  $data array('title'=>xxxx,'value'=>xxxx)
     * @param string $name 系统配置名称
     */
    public function updateSettingInfoByName($data, $name) {
        
        if ( !$name || !$data || !is_array($data) ) {
            return false;
        }

        $where = " name = '". $name . "'";

        return $this -> db -> update(TableConst::TABLE_SETTING, $data, $where);

    }    

    /**
     * 保存App系统配置
     */

    public  function saveAppSettingInfo($data){

        if(empty($data)){

            return false;

        }

        $sql = "update " . TableConst::TABLE_SETTING . " set title = '$data[settingtTitle]',value = '$data[settingValue]',modife_time='$data[modifeTime]',start_time=' $data[startTime]',end_time='$data[endTime]' where name = '$data[settingName]'";

        $result = $this -> db -> exec($sql);

        return $result;
    }


    /**
     * 验证添加的系统设置是否存在
     */
    public  function checkSettingName($name){

        if(empty($name)){

            return false;

        }

        $sql = " select count(*) num from " . TableConst::TABLE_SETTING . " where name = '$name'";

        $result = $this -> db -> getRow($sql);

        return $result['num'];
    }

    /**
     * 添加系统设置 
     * @param array $data
     * @return bool
     */
    public function insertSetting($data) {

        if ( !$data || !is_array($data) ) {
            return false;
        }

        return $this -> db -> insert(TableConst::TABLE_SETTING, $data);
        
    }

    /**
     * 根据名称获得配置信息
     */

    public  function getSettingInfoByNameArray($name){

        if(empty($name)){
            return false;
        }


        $sql = 'select * from ' . TableConst::TABLE_SETTING . " where name in $name";

        $result = $this -> db -> getRows($sql);

        return $result;

    }

    /**
     * 根据名称数据返回配置信息
     * @param array $nameArray
     * @return array 
     */
    public function getSettingListByNameArray($nameArray) {
        
        if ( !$nameArray || !is_array($nameArray) ) {
            return false;
        }

        $names = $this -> dbExtendHelper -> getSqlInfollow($nameArray);

        $sql = "select * from " . TableConst::TABLE_SETTING . " where name in (" . $names . ") order by field(name, " . $names . ")";

        $settingList = $this -> db -> getRows($sql);

        if ( !$settingList ) {
            return false;
        }

        return $settingList;
    }

    /**
     * 获得错误日志
     */
    public function getError(){

        $sql = "select * from monitor_log";

        $this-> pageHelper ->init();

        $sql =  $this->pageHelper -> getLimit($sql);

        $data = $this -> db -> getRows($sql, 0 ,'log' );

        return $data;
    }

    /**
     * 获取错误总条数
     */

    public function getErrorNum(){
        $sql = "select count(*) num from monitor_log";

        $data = $this -> db -> getRow($sql, 0 ,'log' );
        if(!$data){
            return false;
        }
        return $data['num'];
    }
    /**
     * 插入错误信息
     */
    public function insertMonitorLog($data){
        if(empty($data) || !is_array($data)){
            return false;
        }
        $sql = " insert into monitor_log(error_level_id,error_level_value,error_contents,is_notify,gmt_create,client)values('$data[errorLevelId]','$data[errorLevelValue]','$data[errorContents]','$data[isNotify]','$data[gmtCreate]','$data[client]')";
        $result = $this -> db -> useDbIdOnce('log') -> exec($sql);
        return $result;
    }

    /**
     * 根据设置名称获取搜索栏文案
     */
    public function getSearchBar($data){
        if(empty($data)){
            return false;
        }
        $sql = " select value from " . TableConst::TABLE_SETTING . " where name = '$data'";
        return $this -> db -> getRow($sql);
    }

    /**
     * 插入汇率日志
     * @param $data
     */
    public function insertExchangeRateLog($data)
    {

        if (empty($data) || !is_array($data)) {
            return false;
        }
        $rs = $this->db->insert(TableConst::TABLE_SETTING_LOG, $data);
        if (!$rs) {
            return false;
        }
        return $rs;
    }
}
