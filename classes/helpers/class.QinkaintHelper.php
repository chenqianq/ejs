<?php

class Qinkaint
{
    private $db;
    private $selectDbId;

    public function __construct()
    {
        $this->db = Zc::getDb();
        $this->selectDbId = DB_ID_QINKAINT;
    }

    public function createGuid($ebpCode)
    {//10
        if (strlen($ebpCode) < 10) {
            $i = 10 - strlen($ebpCode);
            for ($j = 1; $i >= $j; $j++) {
                $ebpCode .= '0';
            }
        }

        $arr       = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'X', 'Y', 'W', 'V');
        $arrString = Array(
            0 => $arr[rand(0, 24)], 1 => $arr[rand(0, 24)], 2 => $arr[rand(0, 24)], 3 => $arr[rand(0, 24)], 4 => $arr[rand(0, 24)], 5 => $arr[rand(0, 24)], 6 => $arr[rand(0, 24)], 7 => $arr[rand(0, 24)],
        );//8

        $arrStringTwo = Array(
            0 => $arr[rand(0, 24)], 1 => $arr[rand(0, 24)], 2 => $arr[rand(0, 24)], 3 => $arr[rand(0, 24)], 4 => $arr[rand(0, 24)], 5 => $arr[rand(0, 24)], 6 => $arr[rand(0, 24)]
        );//7
        $time         = date('Ymd', time());//8 + 3
        //查看结果 20
        $guid = $ebpCode . '-' . $time . '-' . implode('', $arrString) . '-' . implode('', $arrStringTwo);

        while ($this->checkDcGuidExists($guid)) {
            $guid = $this->createGuid($ebpCode);
        }

        $this->insertGuid($guid);

        return $guid;
    }

    private function checkDcGuidExists($guid){
        if( !$guid ){
            return false;
        }
        return $this->getGuidByGuid($guid);
    }

    /**
     * 获取guid信息
     * @param $guid
     * @return Ambigous|bool
     */
    public function getGuidByGuid($guid)
    {
        if (!$guid) {
            return false;
        }
        $sql = "select * from " . TableConst::TABLE_DC_GUID . " WHERE guid = '$guid' ";
        $guidArray = $this->db->useDbIdOnce($this->selectDbId)->getRow($sql);
        if (!$guidArray) {
            return false;
        }
        return $guidArray;
    }


    /**
     * @param $data
     * @return bool|int]
     */
    public function insertGuid($guid){
        if( !$guid ){
            return false;
        }

        $data = [
            'guid' => $guid,
            'gmt_create' => date('Y-m-d H:i:s')
        ];

        return $this->db->useDbIdOnce($this->selectDbId)->insert(TableConst::TABLE_DC_GUID,$data);
    }

}