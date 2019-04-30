<?php 
if( !defined('IS_ACCESS') ){
    die('Illegal Access');
    exit;
}

/**
 * dao 文件 就负责 与数据库交互，不做任何的业务逻辑处理。
 * @author Administrator
 *
 */
class SettingLogDao {

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

    private $pageHelper;
    
    /**
     * 构造函数
     */
    public function __construct () {
        $this -> db =  Zc::getDb(); 
        //helper
        $this -> dbExtendHelper = HelperFactory::getDbExtendHelper();
        $this -> pageHelper = HelperFactory::getPageHelper();
    }
     
    /**
     * 新增一条设置日志
     * @param array $data 
     * @return bool|int
     */
    public function insertSettingLog($data) {
        
        if ( !$data || !is_array($data) ) {
            return false;
        }

        $rs = $this -> db -> insert(TableConst::TABLE_SETTING_LOG, $data);

        if ( !$rs ) {
            return false;
        }

        return $this -> db -> lastInsertId();
    }

    /**
     * 根据系统变量名称返回日志
     * @param string $name 系统变量名称
     * @return array
     */
    public function getSettingLogByName($name) {
        if ( !$name ) {
            return false;
        }

        $sql = "select * from " . TableConst::TABLE_SETTING_LOG . " where name = '" . $name . "' order by gmt_create desc";
        
        $settingLogList = $this -> db -> getRows($sql);

        if ( !$settingLogList ) {
            return false;
        }

        return $settingLogList;
    }

    /**
     * 根据系统变量名称数组返回日志
     * @param string $nameArray 系统变量名称数组
     * @param string $nameArray 读取条目数
     * @param bool   $isPage 是否分页
     * @return array
     */
    public function getSettingLogByNameArray($nameArray, $limit='', $isPage = false) {
        
        if ( !$nameArray || !is_array($nameArray) ) {
            return false;
        }

        $limitSql = '';
        if ($limit) {
            $limitSql = " limit 0, $limit ";
        }

        $names = $this -> dbExtendHelper -> getSqlInfollow($nameArray);

        $sql = "select * from " . TableConst::TABLE_SETTING_LOG . " where name in(" . $names . ") order by gmt_create desc" . $limitSql;

        if ( $isPage ) {
            $this -> pageHelper -> init();
            $sql = $this -> pageHelper -> getLimit($sql);
        }

        $settingLogList = $this -> db -> getRows($sql);

        if ( !$settingLogList ) {
            return false;
        }

        return $settingLogList;
    }

    /**
     * 根据系统变量名称数组返回日志
     * @param array $nameArray 系统变量名称数组
     * @return array
     */
    public function getSettingLogCountByNameArray($nameArray) {
        
        if ( !$nameArray || !is_array($nameArray) ) {
            return 0;
        }

        $names = $this -> dbExtendHelper -> getSqlInfollow($nameArray);

        $sql = "select count(*) cnt from " . TableConst::TABLE_SETTING_LOG . " where name in(" . $names . ")";
        
        $row = $this -> db -> getRow($sql);

        return $row['cnt'] ?: 0;
    }
}
    