<?php
if( !defined('IS_ACCESS') ){
    die('Illegal Access');
    exit;
}

class SmsLogDao
{
    private $db;
    private $dbExtendHelper;

    public function __construct()
    {
        $this->db = Zc::getDb();
        $this->dbExtendHelper = HelperFactory::getDbExtendHelper();
    }

    /**
     * 新增短信记录
     * @param $data
     * @return bool|int
     */
    public function insertSmsLog($data)
    {
        if (!$data || !is_array($data)) {
            return false;
        }
        $res = $this->db->insert(TableConst::TABLE_SMS_LOG, $data);
        if (!$res) {
            return false;
        }
        return $this->db->lastInsertId();
    }

    /**
     * 根据手机号和验证码获取短信记录条数
     * @param $mobile
     * @param $code
     * @param bool $isValid // 获取有效的短信记录
     * @return bool|int
     */
    public function getCountSmsLogByMobileAndCode($mobile, $code, $isValid=true)
    {
        if (!$mobile || !$code ) {
            return false;
        }
        $where = ' log_phone = "' . $mobile . '" and log_captcha = "' . $code . '"';
        // 如果是查询有效的短信记录
        if ($isValid) {
            $where .= ' and (unix_timestamp(now()) - unix_timestamp(gmt_create)) < "' . EjsConst::countTimeRefreshTime . '"';
        }
        $sql = 'select count(sms_log_id) count from ' . TableConst::TABLE_SMS_LOG . ' where ' .  $where;
        $res = $this->db->getRow($sql);
        if (!$res) {
            return 0;
        }
        return $res['count'] ? : 0;
    }
}