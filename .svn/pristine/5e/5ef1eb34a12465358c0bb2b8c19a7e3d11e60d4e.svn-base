<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-28
 * Time: 11:18
 */

class CrmDao{
	/**
	 * @var ZcDb
	 */
	private $db;
	/**
	 * @var DbExtend
	 */
	private $dbExtendHelper;
	/**
	 * @var Captcha
	 */
	private $pageHelper;
	
	private $yfjId;
	
	public function __construct()
	{
		$this -> db  = Zc::getDb();
		$this -> dbExtendHelper = HelperFactory::getDbExtendHelper();
		$this -> pageHelper = HelperFactory::getPageHelper();
		$this -> yfjId = DB_ID_SUB;
		
	}
	
	/** 客户列表及数量
	 * @param array $searchArr
	 */
	public function getClientListAndCount($searchArr =[]){
		
		$where = " where is_delete = 0 ";
		if($searchArr['client_name']){
			$where .= " and client_name like '%".$searchArr['client_name']."%'";
		}
		
		if($searchArr['contact_phone']){
			$phone = $searchArr['contact_phone'];
			$where .= " and (contact_phone1 = '$phone' or contact_phone2 = '$phone' or contact_phone3 = '$phone') ";
		}
		
		if($searchArr['client_id']){
			$where .= " and client_id = '".$searchArr['client_id']."' ";
		}
		
		if($searchArr['status']){
			$where .= " and status = '".$searchArr['status']."' ";
		}
		if($searchArr['business_manager_id']){
			$where .= " and business_manager_id = '".$searchArr['business_manager_id']."' ";
		}
		$sql1 = " select * from ".TableConst::TABLE_EJS_CLIENT.$where;
		
		$this -> pageHelper -> init();
		$sql1 = $this -> pageHelper -> getLimit($sql1);
		$list = $this -> db -> getRows($sql1);
		
		$sql2 = "select count(*) num from ".TableConst::TABLE_EJS_CLIENT.$where;
		$count = $this -> db -> getRow($sql2);
		return [$list,$count['num']];
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/**插入一条客户信息
	 * @param $insertData
	 */
	public function insertClient($insertData){
		if(!$insertData || !is_array($insertData)){
			return false;
		}
		return $this -> db -> insert(TableConst::TABLE_EJS_CLIENT,$insertData);
	}
	
	/**获得客户Id获得客户 的相关信息
	 * @param $clientId
	 */
	public function getClientInfoByClientId($clientId){
		if(!$clientId){
			return false;
		}
		$sql1 = " select * from ".TableConst::TABLE_EJS_CLIENT." where client_id = $clientId and is_delete = 0";
		return $this -> db -> getRow($sql1);
	}
	
	/**根据客户Id更新客户信息
	 * @param $updateData
	 * @param $clientId
	 */
	public function updateClientByClientId($updateData,$clientId){
		if(!$updateData || !$clientId || !is_array($updateData)){
			return false;
		}
		$where = " client_id = $clientId ";
		return $this -> db -> update(TableConst::TABLE_EJS_CLIENT,$updateData,$where);
	}
	
	/**获得商务管理员的列表
	 * @param $searchArr
	 */
	public function getBusinessManagerListAndCount($searchArr=[]){
		
		$where = " where is_delete = 0 ";
		if($searchArr['login_name']){
			$where .= " and login_name like  '%".$searchArr['login_name']."%'";
		}
		if($searchArr['bm_name']){
			$where .= " and bm_name like  '%".$searchArr['bm_name']."%'";
		}
		if($searchArr['bm_contact_phone']){
			$where .= " and bm_contact_phone =  '".$searchArr['bm_contact_phone']."'";
		}
		
		$sql = " select * from ".TableConst::TABLE_EJS_BUSINESS_MANAGER.$where;
		$this -> pageHelper -> init();
		$sql = $this -> pageHelper -> getLimit($sql);
		$list = $this -> db -> getRows($sql);
		$sql2 = " select count(*) num from ".TableConst::TABLE_EJS_BUSINESS_MANAGER.$where;
		$num = $this -> db -> getRow($sql2);
		return [$list,$num['num']];
		
	}
	
	/**根据商务Id获得商务信息
	 * @param $bmId
	 */
	public function getBusinessManagerByBmId($bmId){
		if(!$bmId){
			return false;
		}
		$sql = " select * from ".TableConst::TABLE_EJS_BUSINESS_MANAGER." where  is_delete = 0 and business_manager_id = $bmId";
		return $this -> db -> getRow($sql);
		
	}
	
	
	/**插入一条商务经理
	 * @param $insertData
	 */
	public function insertBusinessManager($insertData){
		if(!$insertData || !is_array($insertData)){
			return false;
		}
		
		$res =  $this -> db -> insert(TableConst::TABLE_EJS_BUSINESS_MANAGER,$insertData);
		if($res){
			return $this -> db -> lastInsertId();
		}else{
			return false;
		}
	}
	/**更新商务信息
	 * @param $updateData
	 * @param $bmId
	 * @return bool|false|int
	 */
	public function updateBusinessManagerByBmId($updateData,$bmId){
		if(!$updateData || !$bmId || !is_array($updateData)){
			return false;
		}
		$where = " business_manager_id = $bmId ";
		return $this -> db -> update(TableConst::TABLE_EJS_BUSINESS_MANAGER,$updateData,$where);
	}
	
	/**
	 * 获得商务经理关联的客户数量
	 */
	public function getClientNumGroupByBmId(){
		$sql = " select count(client_id) num,business_manager_id from ".TableConst::TABLE_EJS_CLIENT." where is_delete = 0 group by business_manager_id  ";
		$datas = $this -> db -> getRows($sql,3600);
		$return = [];
		foreach ($datas as $value){
			$return[$value['business_manager_id']] = $value['num'];
		}
		return $return;
	}
	
	/**
	 * 获得商务经理的名称的iD
	 */
	public function getBmNameAndId(){
		$sql = " select business_manager_id,bm_name from ".TableConst::TABLE_EJS_BUSINESS_MANAGER." where is_delete = 0 ";
		$list = $this -> db -> getRows($sql);
		$return = [];
		foreach ($list as $value){
			$return[$value['business_manager_id']] = $value['bm_name'];
		}
		return $return;
		
	}
	/**
	 * 获得商务经理的关联商户数量
	 */
	public function getMerchantNumGroupByBmId(){
		$sql = " select count(merchant_id) num,business_manager_id from ".TableConst::TABLE_EJS_MERCHANT." where is_delete = 0 group by business_manager_id  ";
		$datas = $this -> db -> getRows($sql,3600);
		$return = [];
		foreach ($datas as $value){
			$return[$value['business_manager_id']] = $value['num'];
		}
		return $return;
		
	}
	
	/**检查登录名称
	 * @param $bmName
	 */
	public function checkBmNameHasExit($bmName){
		if(!$bmName){
			return false;
		}
		$sql = " select business_manager_id from ".TableConst::TABLE_EJS_BUSINESS_MANAGER." where is_delete = 0 and bm_name = '".addslashes($bmName)."'";
	
		$datas = $this -> db -> getRow($sql,3600);
		
		return $datas['business_manager_id']?:0;
		
	}
	
	
	
	
	
	/**获得商户列表
	 * @param array $searchArr
	 * @return mixed
	 */
	public function getMerchantListAndCountBySearch($searchArr=[]){
		$where = " where is_delete =0 ";
		if($searchArr['merchant_code']){
			$where .= " and  merchant_code = '".$searchArr['merchant_code']."'";
		}
		
		if($searchArr['merchant_name']){
			$where .= " and  merchant_name = '".$searchArr['merchant_name']."'";
		}
		if($searchArr['merchant_contact_phone']){
			$where .= " and  merchant_contact_phone = '".$searchArr['merchant_contact_phone']."'";
		}
		if($searchArr['status']){
			$where .= " and  merchant_status = '".$searchArr['status']."'";
		}
		if($searchArr['business_manager_id']){
			$where .= " and  business_manager_id = '".$searchArr['business_manager_id']."'";
		}
		
		$sql1 = " select merchant_id,merchant_name,merchant_code,merchant_contact_name,merchant_contact_phone,allow_bonded,allow_direct,business_manager_id,merchant_status,warehouse_id,is_registered,allow_xcx,is_wechat from ".TableConst::TABLE_EJS_MERCHANT.$where;
		
		$this -> pageHelper -> init();
		$sql1 = $this -> pageHelper -> getLimit($sql1);
		$list = $this -> db -> getRows($sql1);
		$sql2 = "select count(*) num from ".TableConst::TABLE_EJS_MERCHANT.$where;
		$count = $this -> db -> getRow($sql2);
		return [$list,$count['num']];
		
	}
	
	/**
	 * 获得客户列表
	 */
	public function getClientNameAndId(){
		$sql = " select client_id,client_name from ".TableConst::TABLE_EJS_CLIENT." where is_delete = 0 ";
		$list = $this -> db -> getRows($sql,3600);
		$return = [];
		foreach ($list as $value){
			$return[$value['client_id']] = $value['client_name'];
		}
		return $return;
	}
	/**对商户名称和仓库ID进行查重，除了$merchantId
	 * @param $merchantId
	 */
	public function checkWarehouseRepeatOrMerchantNameExceptMerchantId($warehouseId=0,$merchantName = '',$merchantId=0){
		
		if(!$warehouseId && !$merchantName){
			return false;
		}
		if($warehouseId){
			$where = " where warehouse_id= '".$warehouseId."'";
		}
		if($merchantName){
			$where = " where merchant_name= '".$merchantName."'";
		}
		
		if($merchantId){
			$where.= " and  merchant_id != $merchantId ";
		}
		$where .= " and is_delete = 0 ";
		$sql = " select 1 from ".TableConst::TABLE_EJS_MERCHANT.$where;
		
		$res = $this -> db -> getRow($sql);
		//echo $sql;
		if($res){
			return true;
		}else{
			return false;
		}
	}
	
	
	/**检查商户code是否唯一
	 * @param $code
	 */
	public function checkMercodeIsUnique($code){
		if(!$code){
			return false;
		}
		$sql = " select 1 from ".TableConst::TABLE_EJS_MERCHANT." where merchant_code = '$code' ";
		$res = $this -> db -> getRow($sql);
		if($res){
			return false;
		}else{
			return true;
		}
	}
	
	/**插入一条商户
	 * @param $insertData
	 * @return bool
	 */
	public function insertMerchant($insertData){
		if(!is_array($insertData)){
			return false;
		}
		$res =  $this -> db -> insert(TableConst::TABLE_EJS_MERCHANT,$insertData);
		if($res){
			return $this -> db -> lastInsertId();
		}else {
			return false;
		}
		
		
	}
	/**获得用户的相关信息
	 * @param $merchantId
	 */
	public function getMerchantInfoByMerchantId($merchantId){
		if(!$merchantId){
			return false;
		}
		
		$sql = " select merchant_id,merchant_name,merchant_code,warehouse_id,merchant_contact_name,merchant_contact_phone,business_manager_id,client_id,company_name,remark,login_name,allow_bonded,allow_direct,allow_xcx,merchant_status,xcx_appid,xcx_app_secret,xcx_merc_id,xcx_service_cate,xcx_industry_label,xcx_name,is_wechat from  ".TableConst::TABLE_EJS_MERCHANT." where is_delete = 0 and merchant_id = $merchantId";
		
		return $this -> db -> getRow($sql);
		
	}
	
	/**根据merchantId更新商户信息
	 * @param $updateData
	 * @param $merchantId
	 */
	public function updateMerchantByMerchantId($updateData,$merchantId){
		if(!$merchantId || !$updateData || !is_array($updateData)){
			return false;
		}
		$where = " merchant_id =  $merchantId";
		return $this -> db -> update(TableConst::TABLE_EJS_MERCHANT,$updateData,$where);
		
	}
	
	
	/**获得用户列表
	 * @param $searchArr
	 */
	public function getSdUserListAndCountBySearch($searchArr=[]){
		$where = '';
		
		if($searchArr['admin_id']){
			$where .= ' and admin_id ='.$searchArr['admin_id'];
		}
		if($searchArr['admin_name']){
			$where .= " and admin_name like '%".$searchArr['admin_name']."%'";
		}
		
		$sql1 = " select admin_id,admin_name,admin_password,admin_group_permission_id,admin_login_num,admin_login_time from ".TableConst::TABLE_ADMIN." where site = 'G_C_QINKAINT_COM_DOMAIN' and base_transfer_id = '".SdConst::sdBaseTransferId."'".$where;
		$this -> pageHelper -> init();
		$sql1 = $this -> pageHelper -> getLimit($sql1);
		$list = $this -> db -> useDbIdOnce($this -> yfjId) ->  getRows($sql1);
		$sql2 = "select count(*) num from ".TableConst::TABLE_ADMIN." where site = 'G_C_QINKAINT_COM_DOMAIN' and base_transfer_id = '".SdConst::sdBaseTransferId."'".$where;
		$num = $this -> db -> useDbIdOnce($this -> yfjId) -> getRow($sql2);
		return [$list,$num['num']];
	}
	
	/**
	 * 获得该仓库是否有订单
	 */
	public function checkHaveSdOrderByWarehouseId($warehouseId){
		if(!$warehouseId){
			return false;
		}
		$sql = "select 1 from ".TableConst::TABLE_SD_ORDER." where  goods_warehouse_no = $warehouseId";
		return $this -> db -> getRow($sql);
		
	}
	
	/**
	 * 按仓库Id排序
	 */
	public function getMarchatList($warehouseIdArr=[]){
		if($warehouseIdArr){
			$warehouseIdStr = $this -> dbExtendHelper -> getSqlInFollow($warehouseIdArr);
			$where = " and warehouse_id in ($warehouseIdStr)";
		}
		
		$sql = "select merchant_id,merchant_name,merchant_code,warehouse_id,allow_bonded,allow_direct,status from ".TableConst::TABLE_EJS_MERCHANT." where is_delete =0 ".$where;
		$merchantList = $this -> db -> getRows($sql);
		$return = [];
		foreach($merchantList as $merchant){
			$return[$merchant['warehouse_id']] = $merchant;
		}
		return $return;
	}
	
	
	/**插入商户操作日志
	 * @param $insertData
	 */
	public function insertMerchantLog($insertData){
		if(!$insertData){
			return false;
		}
		
		return $this -> db -> insert(TableConst::TABLE_EJS_MERCHANT_LOG,$insertData);
	}
	
	/**获得商户的操作日志
	 * @param $merchantId
	 */
	public function getMerchantLogByMerchantId($merchantId){
		if(!$merchantId){
			return false;
		}
		
		$sql =  " select * from ".TableConst::TABLE_EJS_MERCHANT_LOG." where merchant_id =$merchantId order by gmt_create desc";
		return $this -> db -> getRows($sql);
	}
	
	/**
	 * 根据客户Id获得商户的信息
	 */
	public function getMerchantNameByClientId($clientId){
		if(!$clientId){
			return false;
		}
		$sql = " select merchant_name from ".TableConst::TABLE_EJS_MERCHANT." where client_id = $clientId and is_delete = 0 ";
		return $this -> db -> getRows($sql);
	}
	
	/**根据登录用户的用户名或的用户信息
	 * @param $loginName
	 * @param int $cache
	 */
	public function getMerchantInfoByLoginName($loginName,$cache = 0){
		if(!$loginName){
			return false;
		}
		
		$sql = " select merchant_id,merchant_name,merchant_code,warehouse_id,allow_bonded,allow_direct,business_manager_id,status,login_name,merchant_contact_name,merchant_contact_phone,company_name from ".TableConst::TABLE_EJS_MERCHANT." where login_name = '".$loginName."' and is_delete = 0 ";
		
		if($cache>0){
			return $this -> db -> getRow($sql,$cache);
		}else{
			return $this -> db -> getRow($sql);
		}
	}
	/**根据登录用户的商务经理名
	 * @param $loginName
	 * @param int $cache
	 */
	
	public function getBmInfoByLoginName($loginName,$cache = 0){
		if(!$loginName){
			return false;
		}
		$sql = " select business_manager_id,bm_name,login_name,bm_contact_phone from ".TableConst::TABLE_EJS_BUSINESS_MANAGER." where login_name = '".$loginName."' and is_delete = 0 ";
		
		if($cache>0){
			return $this -> db -> getRow($sql,$cache);
		}else{
			return $this -> db -> getRow($sql);
		}
	}
	/**获得该商务经理账单下的商户仓库Id
	 * @param $bmId
	 */
	public function getMerchantWarehouseIdArrByBmId($bmId){
		if(!$bmId){
			return false;
		}
		$sql = "select warehouse_id,merchant_code from ".TableConst::TABLE_EJS_MERCHANT." where business_manager_id = $bmId ";
		return $this -> db -> getRows($sql,3600);
	}
	/**更新商户的小程序信息
	 * @param $updateData
	 * @param $merchantId
	 */
	public function updateMerchantXcxInfoByMerchantId($updateData,$merchantId){
		if(!$updateData || !is_array($updateData) || !$merchantId){
			return false;
		}
		
		$where = " merchant_id = '$merchantId' ";
		
		return $this -> db -> update(TableConst::TABLE_EJS_MERCHANT,$updateData,$where);
	}
	
	
}