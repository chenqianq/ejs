<?php
if( !defined('IS_ACCESS') ){
	die('Illegal Access');
	exit;
}
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-03-07
 * Time: 16:08
 */
class CrmService {
	/**
	 * @var
	 */
	private $crmDao;
	private $adminDao;
	public function __construct()
	{
		$this -> crmDao = DaoFactory::getCrmDao();
		$this -> adminDao = DaoFactory::getAdminDao();
	}
	
	
	/** 客户列表及数量
	 * @param array $searchArr
	 */
	public function getClientListAndCount($searchArr =[]){
		list($list,$count) = $this -> crmDao -> getClientListAndCount($searchArr);
		return [$list,$count];
	}
	
	/**
	 * 客户状态
	 */
	public function clientSatus(){
		return ['1'=>'潜在','2' => '意向','3' => '签约'];
	}
	
	/**插入一条客户信息
	 * @param $insertData
	 */
	public function insertClient($insertData){
		if(!$insertData){
			return false;
		}
		return $this -> crmDao -> insertClient($insertData);
	}
	
	/**获得客户Id获得客户 的相关信息
	 * @param $clientId
	 */
	public function getClientInfoByClientId($clientId){
		if(!$clientId){
			return false;
		}
		return $this -> crmDao -> getClientInfoByClientId($clientId);
	}
	
	/**根据客户Id更新客户信息
	 * @param $updateData
	 * @param $clientId
	 */
	public function updateClientByClientId($updateData,$clientId){
		if(!$updateData || !$clientId || !is_array($updateData)){
			return false;
		}
		return $this -> crmDao -> updateClientByClientId($updateData,$clientId);
	}
	
	/**获得商务管理员的列表
	 * @param $searchArr
	 */
	public function getBusinessManagerListAndCount($searchArr=[]){
		list($list,$num) = $this -> crmDao -> getBusinessManagerListAndCount($searchArr);
		//获得对应的关联客户，关联的商户数量
		$clientNumArr = $this -> crmDao -> getClientNumGroupByBmId();
		//获得关联的商户数量
		$merchantNumArr = $this -> crmDao -> getMerchantNumGroupByBmId();
		
		foreach ($list as &$bmInfo){
			$bmInfo['client_num'] = $clientNumArr[$bmInfo['business_manager_id']]?:0;
			$bmInfo['marchat_num'] = $merchantNumArr[$bmInfo['business_manager_id']]?:0;
		}
		
		return [$list,$num];
	}
	
	/**根据商务Id获得商务信息
	 * @param $bmId
	 */
	public function getBusinessManagerByBmId($bmId){
		if(!$bmId){
			return false;
		}
		
		return $this -> crmDao -> getBusinessManagerByBmId($bmId);
	}
	
	/**插入一条商务经理
	 * @param $insertData
	 */
	public function insertBusinessManager($insertData){
		if(!$insertData || !is_array($insertData)){
			return false;
		}
		
		return $this -> crmDao -> insertBusinessManager($insertData);
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
		return $this -> crmDao -> updateBusinessManagerByBmId($updateData,$bmId);
	}
	
	/**检查登录名称
	 * @param $bmName
	 */
	public function checkBmNameHasExit($bmName){
		if(!$bmName){
			return false;
		}
		return $this -> crmDao -> checkBmNameHasExit($bmName);
		
	}
	
	
	
	
	/**获得归属商务的列表
	 * @return mixed
	 */
	public function getBmNameAndId(){
		return $this -> crmDao -> getBmNameAndId();
	}
	
	
	
	
	
	
	
	/**获得商户列表
	 * @param array $searchArr
	 * @return mixed
	 */
	public function getMerchantListAndCountBySearch($searchArr=[]){
		return $this -> crmDao -> getMerchantListAndCountBySearch($searchArr);
	}
	
	/**
	 *获得客户列表
	 */
	public function getClientNameAndId(){
		return $this -> crmDao -> getClientNameAndId();
	}
	
	/**对商户名称和仓库ID进行查重，除了$merchantId
	 * @param $merchantId
	 */
	public function checkWarehouseRepeatOrMerchantNameExceptMerchantId($warehouseId=0,$merchantName = '',$merchantId=0){
		return $this -> crmDao -> checkWarehouseRepeatOrMerchantNameExceptMerchantId($warehouseId,$merchantName,$merchantId);
	}
	
	/**检查商户code是否唯一
	 * @param $code
	 */
	public function checkMercodeIsUnique($code){
		if(!$code){
			return false;
		}
		return $this -> crmDao -> checkMercodeIsUnique($code);
	}
	
	/**插入一条商户
	 * @param $insertData
	 * @return bool
	 */
	public function insertMerchant($insertData){
		if(!is_array($insertData)){
			return false;
		}
		return $this -> crmDao -> insertMerchant($insertData);
	}
	
	/**获得用户的相关信息
	 * @param $merchantId
	 */
	public function getMerchantInfoByMerchantId($merchantId,$adminMobile=false){
		if(!$merchantId){
			return false;
		}
		
		$merchantInfo =  $this -> crmDao -> getMerchantInfoByMerchantId($merchantId);
		if($adminMobile){
			$adminInfo = $this -> adminDao -> getAdminInfoByAdminName($merchantInfo['login_name']);
			$merchantInfo['admin_mobile'] = $adminInfo['admin_mobile'];
			
		}
		return $merchantInfo;
		
	}
	
	/**根据merchantId更新商户信息
	 * @param $updateData
	 * @param $merchantId
	 */
	public function updateMerchantByMerchantId($updateData,$merchantId){
		if(!$merchantId || !$updateData || !is_array($updateData)){
			return false;
		}
		
		return $this -> crmDao -> updateMerchantByMerchantId($updateData,$merchantId);
	}
	
	/**获得用户列表
	 * @param $searchArr
	 */
	public function getSdUserListAndCountBySearch($searchArr=[]){
		return $this -> crmDao ->  getSdUserListAndCountBySearch($searchArr);
	}
	
	/**
	 * 获得权限的列表
	 */
	public function getPermissionIdList(){
		return $this -> crmDao -> getPermissionIdList();
	}
	
	/**检测登录名是否已存在
	 * @param $loginName
	 */
	public function checkUserLoginName($loginName){
		if(!$loginName){
			return false;
		}
		return $this -> crmDao -> checkUserLoginName($loginName);
	}
	

	/**
	 * 获得该仓库是否有订单
	 */
	public function checkHaveSdOrderByWarehouseId($warehouseId){
		if(!$warehouseId){
			return false;
		}
		return $this -> crmDao -> checkHaveSdOrderByWarehouseId($warehouseId);
		
	}
	
	
	
	/**根据商户姓名或code获得用户的
	 * @param $merchantName
	 * @param $merchantCode
	 */
	public function getMerchantCodeByNameAndCode($merchantName,$merchantCode){
		if(!$merchantName && !$merchantCode){
			return false;
		}
	}
	
	/**
	 * 获得商户列表
	 */
	public function getMarchatList($warehouseIdArr =[]){
		return $this -> crmDao -> getMarchatList($warehouseIdArr);
	}
	
	/**插入商户操作日志
	 * @param $insertData
	 */
	public function insertMerchantLog($insertData){
		if(!$insertData){
			return false;
		}
		
		return $this -> crmDao -> insertMerchantLog($insertData);
	}
	
	/**获得商户的操作日志
	 * @param $merchantId
	 */
	public function getMerchantLogByMerchantId($merchantId){
		if(!$merchantId){
			return false;
		}
		
		return $this -> crmDao ->  getMerchantLogByMerchantId($merchantId);
		
	}
	/**
	 * 根据客户Id获得商户的信息
	 */
	public function getMerchantNameByClientId($clientId){
		if(!$clientId){
			return false;
		}
		return $this -> crmDao -> getMerchantNameByClientId($clientId);
	}
	
	/**根据登录用户的用户名或的用户信息
	 * @param $loginName
	 * @param int $cache
	 */
	
	public function getMerchantInfoByLoginName($loginName,$cache = 0){
		if(!$loginName){
			return false;
		}
		return $this -> crmDao -> getMerchantInfoByLoginName($loginName,$cache);
	}
	/**根据登录用户的商务经理名
	 * @param $loginName
	 * @param int $cache
	 */
	
	public function getBmInfoByLoginName($loginName,$cache = 0){
		if(!$loginName){
			return false;
		}
		return $this -> crmDao -> getBmInfoByLoginName($loginName,$cache);
	}
	
	/**获得该商务经理账单下的商户仓库Id
	 * @param $bmId
	 */
	public function getMerchantWarehouseIdArrByBmId($bmId){
		if(!$bmId){
			return false;
		}
		return $this -> crmDao -> getMerchantWarehouseIdArrByBmId($bmId);
	}
	
	/**更新商户的小程序信息
	 * @param $updateData
	 * @param $merchantId
	 */
	public function updateMerchantXcxInfoByMerchantId($updateData,$merchantId){
		if(!$updateData || !is_array($updateData) || !$merchantId){
			return false;
		}
		return $this -> crmDao ->  updateMerchantXcxInfoByMerchantId($updateData,$merchantId);
	}
	
	
	
}