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

class SettingLogService {


     /**
      *
      * @var SettingLogDao
      */
     private $settingLogDao;

    /**
     * 构造函数初始化ID
     */
    public function __construct() {

        //Dao
        $this -> settingLogDao = DaoFactory::getSettingLogDao();
    }

    /**
     * 新增一条设置日志
     * @param array $data 
     * @return int
     */
    public function insertSettingLog($data) {
        return $this -> settingLogDao -> insertSettingLog($data);
    }

    /**
     * 根据系统变量名称返回日志
     * @param string $name 系统变量名称
     * @return array
     */
    public function getSettingLogByName($name) {
        return $this -> settingLogDao -> getSettingLogByName($name);
    }

    /**
     * 根据系统变量名称数组返回日志
     * @param array $nameArray 系统变量名称数组
     * @param bool   $isPage 是否分页
     * @return array
     */
    public function getSettingLogByNameArray($nameArray, $limit='', $isPage = false) {

        return $this -> settingLogDao -> getSettingLogByNameArray($nameArray, $limit, $isPage);
    }

    /**
     * 根据系统变量名称数组返回日志数量
     * @param array $nameArray 系统变量名称数组
     * @return array
     */
    public function getSettingLogCountByNameArray($nameArray) {
        return $this -> settingLogDao -> getSettingLogCountByNameArray($nameArray);
    }

    /**
     * 新增一条日志记录
     * @param $settingName
     * @param $settingValue
     * @param $originSettingInfo
     * @return bool
     */
    public function createSettingLog($settingName, $settingValue, $settingTitle, $adminName)
    {
        $settingLogData = [
            'name' => $settingName,
            'value' => $settingValue,
            'content' => $this->getSettingLogContent($settingValue, $settingTitle),
            'gmt_create' => date('Y-m-d H:i:s'),
            'operator' => $adminName,
        ];
        $res = $this->settingLogDao->insertSettingLog($settingLogData);
        if (!$res) {
            return false;
        }
        return true;
    }

    public function getSettingLogContent($settingValue, $settingTitle)
    {
        $content = '修改了【' . $settingTitle . '】至' . $settingValue . '；';
        return $content;
    }

    
}