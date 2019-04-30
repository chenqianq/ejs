<?php
/** crm
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-28
 * Time: 11:16
 */
class CrmController extends ZcController
{
	/**
	 * @var CrmService
	 */
	private $crmService;
	/**
	 * @var Url
	 */
	private $urlHelper;
	private $adminInfo;
	private $sdOrderService;
	private $adminService;
	
	public function __construct($route)
	{
		parent::__construct($route);
		$this -> crmService = ServiceFactory::getCrmService();
		$this -> urlHelper = HelperFactory::getUrlHelper();
		$this -> adminService = ServiceFactory::getAdminService();
		$this -> adminInfo = $this -> adminService -> getAdminInfo();
		
	}
	
	/**
	 * 客户管理列表
	 */
	public function clientList()
	{
		
		$searchArr = $this->clientListSearch();
		//var_dump($searchArr);
		list($list, $count) = $this->crmService->getClientListAndCount($searchArr);
		$bmList = $this->crmService->getBmNameAndId();
		$data = [];
		$data['status'] = $this->crmService->clientSatus();
		$data['list'] = $list;
		$data['bmList'] = $bmList;
		$data['pageHtml'] = $this->getPage($count);
		
		$this->render($data);
	}
	
	
	/**
	 * 获取分页
	 * @param $total
	 * @return mixed
	 */
	private function getPage($total)
	{
		$pageTool = new PageSplit();
		$pageTool->total = $total;
		$pageTool->render();
		
		return $pageTool->page_html;
	}
	
	
	/**
	 * 客户管理列表的搜索
	 */
	private function clientListSearch()
	{
		$clientName = trim($this->urlHelper->getValue('client_name'));
		$contactPhone = trim($this->urlHelper->getValue('contact_phone'));
		$clientId = trim($this->urlHelper->getValue('client_id'));
		$status = trim($this->urlHelper->getValue('status'));
		$searchArr = [];
		if ($clientName) {
			$searchArr['client_name'] = $clientName;
		}
		
		if ($contactPhone) {
			$searchArr['contact_phone'] = $contactPhone;
		}
		
		if ($clientId) {
			$searchArr['client_id'] = ltrim($clientId, '0');
		}
		
		if ($status) {
			$searchArr['status'] = $status;
		}
		
		if($this -> adminInfo['business_manager_id']){
			$searchArr['business_manager_id'] = $this -> adminInfo['business_manager_id'];
		}
		
		return $searchArr;
	}
	
	/**
	 * 新增客户
	 */
	public function addClient()
	{
		$this->generalClientShow();
	}
	
	/**
	 * 编辑客户
	 */
	public function editClient()
	{
		$clientId = $this->urlHelper->getValue('client_id');
		$this->generalClientShow($clientId);
	}
	
	/**
	 * 展示编辑和新增客户的页面
	 * @param int $clientId
	 */
	private function generalClientShow($clientId = 0)
	{
		
		$statusList = $this->crmService->clientSatus();
		$bmList = $this->crmService->getBmNameAndId();
		
		if ($clientId > 0) {
			$clientInfo = $this->crmService->getClientInfoByClientId($clientId);
		}
		
		$merchantNameArr = $this ->  crmService -> getMerchantNameByClientId($clientId);
		$merchantNameStr = implode(array_column($merchantNameArr,'merchant_name'),',');
		$data = [];
		$data['status'] = $statusList;
		$data['clientInfo'] = $clientInfo ?: '';
		$data['merchantNameStr'] = $merchantNameStr ?: '';
		$data['bmList'] = $bmList;
		$data['bmId'] = $this -> adminInfo['business_manager_id'];
		$this->render($data, 'crm/crm/edit_client');
	}
	
	/**
	 * 保存新增的客户
	 */
	public function saveAddClient()
	{
		$postData = $this->checkPostClientData();
		
		//进行信息的保存
		$insertData = [];
		$insertData['client_name'] = $postData['client_name'];
		$insertData['status'] = $postData['status'];
		$insertData['business_manager_id'] = $postData['business_manager_id'] ?: 0;
		$insertData['contact_name'] = $postData['contact_name'];
		$insertData['contact_phone_a'] = $postData['contact_phone1'];
		$insertData['contact_phone_b'] = $postData['contact_phone2'];
		$insertData['contact_phone_c'] = $postData['contact_phone3'];
		$insertData['remark'] = $postData['remark'];
		
		$insertData['operator'] = $this->adminInfo['name'];
		$insertData['gmt_create'] = date('Y-m-d H:i:s');
		$insertData['gmt_modified'] = date('Y-m-d H:i:s');
		$res = $this->crmService->insertClient($insertData);
		
		if (!$res) {
			$this->renderJSON(['status' => 'failed', 'msg' => '保存失败！']);
		}
		$this->renderJSON(['status' => 'success', 'msg' => '保存成功！']);
	}
	
	/**
	 * 保存编辑的客户
	 */
	public function saveEditClient()
	{
		
		$postData = $this->checkPostClientData();
		//进行信息的保存
		$updateData = [];
		$updateData['client_name'] = $postData['client_name'];
		$updateData['status'] = $postData['status'];
		$updateData['contact_name'] = $postData['contact_name'];
		$updateData['contact_phone_a'] = $postData['contact_phone1'];
		$updateData['contact_phone_b'] = $postData['contact_phone2'];
		$updateData['contact_phone_c'] = $postData['contact_phone3'];
		$updateData['remark'] = $postData['remark'];
		$updateData['operator'] = $this->adminInfo['name'];
		$updateData['gmt_modified'] = date('Y-m-d H:i:s');
		$res = $this->crmService->updateClientByClientId($updateData, $postData['client_id']);
		if (!$res) {
			$this->renderJSON(['status' => 'failed', 'msg' => '保存失败！']);
		}
		$this->renderJSON(['status' => 'success', 'msg' => '保存成功！']);
	}
	
	/**
	 * 检查提交的信息
	 */
	private function checkPostClientData()
	{
		$clientName = trim($this->urlHelper->getValue('client_name'));
		$status = trim($this->urlHelper->getValue('status'));
		$bmId = trim($this->urlHelper->getValue('business_manager_id'));
		$contactName = trim($this->urlHelper->getValue('contact_name'));
		$contactPhone1 = trim($this->urlHelper->getValue('contact_phone1'));
		$contactPhone2 = trim($this->urlHelper->getValue('contact_phone2'));
		$contactPhone3 = trim($this->urlHelper->getValue('contact_phone3'));
		$remark = trim($this->urlHelper->getValue('remark'));
		$clientId = trim($this->urlHelper->getValue('client_id'));
		
		$postData = [];
		$postData['client_id'] = $clientId;
		$postData['remark'] = $remark;
		$postData['contact_phone3'] = $contactPhone3;
		$postData['contact_phone2'] = $contactPhone2;
		$postData['contact_phone1'] = $contactPhone1;
		$postData['contact_name'] = $contactName;
		$postData['business_manager_id'] = $bmId;
		$postData['status'] = $status;
		$postData['client_name'] = $clientName;
		
		return $postData;
	}
	
	/**
	 * 删除客户
	 */
	public function deleteClient()
	{
		$clientId = $this->urlHelper->getValue('client_id');
		if (!$clientId) {
			$this->renderJSON(['status' => 'failed', 'msg' => '参数错误，删除失败']);
		}
		
		$updateData = [];
		$updateData['is_delete'] = 1;
		$updateData['operator'] = $this->adminInfo['name'];
		$updateData['gmt_modified'] = date('Y-m-d H:i:s');
		$res = $this->crmService->updateClientByClientId($updateData, $clientId);
		if (!$res) {
			$this->renderJSON(['status' => 'failed', 'msg' => '删除失败！']);
		}
		$this->renderJSON(['status' => 'success', 'msg' => '删除成功！']);
	}
	
	/**
	 * 更新客户绑定的商务
	 */
	public function updateClientBm(){
		$clientId = $this -> urlHelper -> getValue('client_id');
		if(!$clientId){
			$this->renderJSON(['status' => 'failed', 'msg' => '参数错误！']);
		}
		$bmId = $this -> urlHelper -> getValue('business_manager_id');
		if(!$bmId || $bmId <0){
			$this->renderJSON(['status' => 'failed', 'msg' => '参数错误！']);
		}
		$updateData = [];
		$updateData['business_manager_id'] = $bmId;
		$updateData['operator'] = $this->adminInfo['name'];
		$updateData['gmt_modified'] = date('Y-m-d H:i:s');
		$res = $this -> crmService -> updateClientByClientId($updateData,$clientId);
		if (!$res) {
			$this->renderJSON(['status' => 'failed', 'msg' => '更新失败！']);
		}
		$this->renderJSON(['status' => 'success', 'msg' => '更新成功！']);
		
		
		
	}
	
	
	
	
	
	
	
	/**
	 * 商务经理列表
	 */
	public function businessManagerList()
	{
		$loginName = trim($this->urlHelper->getValue('login_name'));
		$bmName = trim($this->urlHelper->getValue('bm_name'));
		$bmContactPhone = trim($this->urlHelper->getValue('bm_contact_phone'));
		$searchArr = [];
		$loginName ? $searchArr['login_name'] = $loginName : '';
		$bmName ? $searchArr['bm_name'] = $bmName : '';
		$bmContactPhone ? $searchArr['bm_contact_phone'] = $bmContactPhone : '';
		
		list($list, $num) = $this->crmService->getBusinessManagerListAndCount($searchArr);
		
		$data = [];
		$data['list'] = $list;
		$data['list'] = $list;
		$data['pageHtml'] = $this->getPage($num);
		$this->render($data);
	}
	
	/**
	 * 新增商务经理
	 */
	public function addBusinessManager()
	{
		$this->generalBusinessShow();
	}
	
	/**
	 * 编辑商务经理
	 */
	public function editBusinessManager()
	{
		$bmId = $this->urlHelper->getValue('business_manager_id');
		$this->generalBusinessShow($bmId);
	}
	
	/**
	 * 展示编辑和新增商务的页面
	 * @param int $bmId
	 */
	private function generalBusinessShow($bmId = 0)
	{
		if ($bmId > 0) {
			$businessInfo = $this->crmService->getBusinessManagerByBmId($bmId);
		}
		
		$data = [];
		$data['businessInfo'] = $businessInfo;
		$this->render($data, 'crm/crm/edit_business_manager');
	}
	
	/**
	 * 保存新增的商务
	 */
	public function saveAddBusiness()
	{
		$postData = $this->checkPostBusinessData();
		//进行信息的保存
		$insertData = [];
		$insertData['bm_name'] = $postData['bm_name'];
		$insertData['login_name'] = $postData['login_name'];
		$insertData['bm_contact_phone'] = $postData['bm_contact_phone'];
		$insertData['operator'] = $this->adminInfo['name'];
		$insertData['gmt_create'] = date('Y-m-d H:i:s');
		$insertData['gmt_modified'] = date('Y-m-d H:i:s');
		$bmId = $this->crmService->insertBusinessManager($insertData);
		if (!$bmId) {
			$this->renderJSON(['status' => 'failed', 'msg' => '保存失败！']);
		}
		
		$adminPassword = HelperFactory::getCommonHelper()-> makeSixCode();
		$param = [];
		$param['admin_name'] =  $postData['login_name'];
		$param['admin_password'] = md5($adminPassword);
		$param['admin_mobile'] = $postData['bm_contact_phone'];
		$param['admin_group_permission_id'] = EjsConst::groupOfBusinessManager;
		$param['business_manager_id'] = $bmId;
		
		$param['is_allow_group'] = 0;
		
		$rs = $this -> adminService -> insertAdmin($param);
		//插入一条商务经理的登录信息
		$this->renderJSON(['status' => 'success', 'msg' => "登录名：".$postData['login_name']."<br>初始密码：$adminPassword"]);
	}
	
	
	/**
	 * 保存编辑的商务
	 */
	public function saveEditBusiness()
	{
		
		$postData = $this->checkPostBusinessData();
		//进行信息的保存
		$updateData = [];
		$updateData['bm_name'] = $postData['bm_name'];
		$updateData['bm_contact_phone'] = $postData['bm_contact_phone'];
		$updateData['operator'] = $this->adminInfo['name'];
		$updateData['gmt_modified'] = date('Y-m-d H:i:s');
		
		$res = $this->crmService->updateBusinessManagerByBmId($updateData, $postData['business_manager_id']);
		if (!$res) {
			$this->renderJSON(['status' => 'failed', 'msg' => '保存失败！']);
		}
		$this->renderJSON(['status' => 'success', 'msg' => '保存成功！']);
	}
	
	/**
	 * 检查提交的信息
	 */
	private function checkPostBusinessData()
	{
		
		$bmName = trim($this->urlHelper->getValue('bm_name'));
		$loginName = trim($this->urlHelper->getValue('login_name'));
		$bmContactPhone = trim($this->urlHelper->getValue('bm_contact_phone'));
		$bmId = trim($this->urlHelper->getValue('business_manager_id'));
		
		//检验登录名是否存在
		if(!$bmId){
			//进行登录名的查重
			$res = $this -> adminService -> getAdminInfoByAdminName($loginName);
			if($res){
				$this->renderJSON(['status' => 'falied', 'msg' => '！登录名重复']);
			}
		}
		//进行商务名称的防重
		$bmNameId = $this -> crmService -> checkBmNameHasExit($bmName);
	
		if($bmNameId>0 && $bmNameId != $bmId){
			$this->renderJSON(['status' => 'falied', 'msg' => '！商务经理名称重复']);
		}
		
		$postData = [];
		$postData['bm_name'] = $bmName;
		$postData['login_name'] = $loginName;
		$postData['bm_contact_phone'] = $bmContactPhone;
		$postData['business_manager_id'] = $bmId;
		
		return $postData;
	}
	
	/**
	 * 删除商务经理
	 */
	public function deleteBusinessManager()
	{
		$bmId = $this->urlHelper->getValue('business_manager_id');
		if (!$bmId) {
			$this->renderJSON(['status' => 'failed', 'msg' => '参数错误，删除失败']);
		}
		$businessInfo = $this -> crmService -> getBusinessManagerByBmId($bmId);
		if(!$businessInfo){
			$this->renderJSON(['status' => 'failed', 'msg' => '没有这条信息，删除失败']);
		}
		
		$updateData = [];
		$updateData['is_delete'] = 1;
		$updateData['operator'] = $this->adminInfo['name'];
		$updateData['gmt_modified'] = date('Y-m-d H:i:s');
		$res = $this->crmService->updateBusinessManagerByBmId($updateData, $bmId);
		
		if (!$res) {
			$this->renderJSON(['status' => 'failed', 'msg' => '删除失败！']);
		}
		
		$this -> adminService -> deleteAdminByAdminName($businessInfo['login_name']);
		$this->renderJSON(['status' => 'success', 'msg' => '删除成功！']);
	}
	
	
	/**
	 * 商户管理列表
	 */
	public function merchantList()
	{
		
		$merchantCode = trim($this->urlHelper->getValue('merchant_code'));
		$merchantName = trim($this->urlHelper->getValue('merchant_name'));
		$merchantContactPhone = trim($this->urlHelper->getValue('merchant_contact_phone'));
		$status = trim($this->urlHelper->getValue('status'));
		$searchArr = [];
		$merchantCode ? $searchArr['merchant_code'] = $merchantCode : '';
		$merchantName ? $searchArr['merchant_name'] = $merchantName : '';
		$merchantContactPhone ? $searchArr['merchant_contact_phone'] = $merchantContactPhone : '';
		$status ? $searchArr['status'] = $status : '';
		$this -> adminInfo['business_manager_id'] ? $searchArr['business_manager_id'] = $this -> adminInfo['business_manager_id'] : '';
		list($list, $count) = $this->crmService->getMerchantListAndCountBySearch($searchArr);
		//获得商务经理列表
		$bmList = $this->crmService->getBmNameAndId();
		//获得仓库列表
		require_once DIR_FS_DOCUMENT_ROOT . '/classes/sd_apply/wdgj/wdgj.php';
		$wdgj = new wdgj();
		$warehoseList =$wdgj -> getAllWarehouse();
		
		$data = [];
		$data['list'] = $list;
		$data['bmList'] = $bmList;
	
		$data['warehouseList'] = $warehoseList;
		$data['status'] = [EjsConst::merchantStatusOfFreeze=>'冻结',EjsConst::merchantStatusOfNomal=>'正常'];
		$data['pageHtml'] = $this->getPage($count);
		
		$this->render($data);
	}
	
	/**
	 * 新增商户经理
	 */
	public function addMerchant()
	{
		$this->generalMerchantShow();
	}
	
	/**
	 * 编辑商户经理
	 */
	public function editMerchant()
	{
		$merchantId = $this->urlHelper->getValue('merchant_id');
		$this->generalMerchantShow($merchantId);
	}
	
	/**展示编辑和新增商户的页面
	 * @param int $merchantId
	 */
	private function generalMerchantShow($merchantId = 0)
	{
		if ($merchantId > 0) {
			$merchantInfo = $this -> crmService -> getMerchantInfoByMerchantId($merchantId,true);
		}
		//获得商务经理列表
		$bmList = $this->crmService->getBmNameAndId();
		
		//获得仓库列表
		require_once DIR_FS_DOCUMENT_ROOT . '/classes/sd_apply/wdgj/wdgj.php';
		$wdgj = new wdgj();
		$warehoseList =$wdgj -> getAllWarehouse();
		//获得客户列表
		$clientList = $this->crmService->getClientNameAndId();
		
		//获得日志的列表
		$logList = $this -> crmService -> getMerchantLogByMerchantId($merchantId);
		
		$data = [];
		$data['merchantInfo'] = $merchantInfo;
		$data['bmList'] = $bmList;
		$data['warehouseList'] = $warehoseList;
		$data['clientList'] = $clientList;
		$data['logList'] = $logList;
		$this->render($data, 'crm/crm/edit_merchant');
	}
	
	/**
	 * 保存新增的客户
	 */
	public function saveAddMerchant()
	{
		$postData = $this->checkPostMerchantData();
		
		//生成唯一的六位数的Id
		$code = $this->createMerchantCode();
		
		
		//进行信息的保存
		$insertData = [];
		$insertData['merchant_name'] = $postData['merchant_name'];
		$insertData['merchant_code'] = $code;
		$insertData['warehouse_id'] = $postData['warehouse_id'];
		$insertData['merchant_contact_name'] = $postData['merchant_contact_name'];
		$insertData['merchant_contact_phone'] = $postData['merchant_contact_phone'];
		$insertData['business_manager_id'] = $postData['business_manager_id'];
		$insertData['company_name'] = $postData['company_name'];
		$insertData['client_id'] = $postData['client_id'];
		$insertData['remark'] = $postData['remark'];
		$insertData['login_name'] = $postData['login_name'];
		$insertData['operator'] = $this->adminInfo['name'];
		$insertData['gmt_create'] = date('Y-m-d H:i:s');
		$insertData['gmt_modified'] = date('Y-m-d H:i:s');
		
		$merchantId = $this->crmService->insertMerchant($insertData);
		//注意登录的信息还没有插入
		if($merchantId){
			$adminPassword = HelperFactory::getCommonHelper()-> makeSixCode();
			$param = [];
			$param['admin_name'] =  $postData['login_name'];
			$param['admin_password'] = md5($adminPassword);
			$param['admin_mobile'] = $postData['admin_mobile'];
			$param['admin_group_permission_id'] = EjsConst::groupOfMerchant;
			$param['merchant_id'] = $merchantId;
			$param['is_allow_group'] = 0;
			$rs = $this -> adminService -> insertAdmin($param);
			
		}
		
		if (!$merchantId) {
			$this->renderJSON(['status' => 'failed', 'msg' => '保存失败！']);
		}
		$this->renderJSON(['status' => 'success', 'msg' => "登录名：".$postData['login_name']."<br>初始密码：$adminPassword"]);
	}
	
	
	/**
	 * 保存编辑的商户
	 */
	public function saveEditMerchant()
	{
		
		$postData = $this->checkPostMerchantData();
		if(!$postData['merchant_id']){
			$this->renderJSON(['status' => 'failed', 'msg' => '没有找到该用户！']);
		}
		$oldMerchantInfo = $this -> crmService -> getMerchantInfoByMerchantId($postData['merchant_id']);
		if(!$oldMerchantInfo){
			$this->renderJSON(['status' => 'failed', 'msg' => '没有找到该用户！']);
		}
		//进行信息的保存
		$updateData = [];
		$updateData['merchant_name'] = $postData['merchant_name'];
		$updateData['merchant_contact_name'] = $postData['merchant_contact_name'];
		$updateData['merchant_contact_phone'] = $postData['merchant_contact_phone'];
		$updateData['company_name'] = $postData['company_name'];
		$updateData['client_id'] = $postData['client_id'];
		$updateData['remark'] = $postData['remark'];
		$updateData['operator'] = $this->adminInfo['name'];
		$updateData['gmt_modified'] = date('Y-m-d H:i:s');
		
		$res = $this->crmService->updateMerchantByMerchantId($updateData, $postData['merchant_id']);
		$this -> checkModifyMerchantInfoAndInsertMerchantLog($oldMerchantInfo,$postData);
		
		
		if (!$res) {
			$this->renderJSON(['status' => 'failed', 'msg' => '保存失败！']);
		}
		$this->renderJSON(['status' => 'success', 'msg' => '保存成功！']);
	}
	
	/**插入商户的修改日志
	 * @param $oldMerchantInfo
	 * @param $postData
	 */
	private function checkModifyMerchantInfoAndInsertMerchantLog($oldMerchantInfo,$postData){
		
		foreach ($postData as $field => $value){
			
			if($value != $oldMerchantInfo[$field]){
				$content = '';
				switch ($field){
					case 'merchant_name':
						$content = "修改商户名称【".$oldMerchantInfo[$field]."】至【".$value."】";
						break;
					case 'merchant_contact_name':
						$content = "修改联系人【".$oldMerchantInfo[$field]."】至【".$value."】";
						break;
					case 'merchant_contact_phone':
						$content = "修改手机号【".$oldMerchantInfo[$field]."】至【".$value."】";
						break;
					case 'company_name':
						$content = "修改公司全称【".$oldMerchantInfo[$field]."】至【".$value."】";
						break;
					case 'client_id':
						$clientList = $this -> crmService -> getClientNameAndId();
						$content = "修改关联客户【".$clientList[$oldMerchantInfo[$field]]."】至【".$clientList[$value]."】";
						break;
					case 'allow_bonded':
						if($value>0){
							$content = "修改保税申报【禁用】至【开启】";
						}else{
							$content = "修改保税申报【开启】至【禁用】";
						}
						break;
					case 'allow_direct':
						if($value>0){
							$content = "修改直邮申报【禁用】至【开启】";
						}else{
							$content = "修改直邮申报【开启】至【禁用】";
						}
						break;
					case 'merchant_status':
						if($value == EjsConst::merchantStatusOfNomal){
							$content = "修改商户状态【冻结】至【正常】";
						}else{
							$content = "修改商户状态【正常】至【冻结】";
						}
						break;
					case 'business_manager_id':
						$bmList = $this -> crmService -> getBmNameAndId();
						if($bmList[$oldMerchantInfo[$field]] && $bmList[$value]){
							$content = "修改商务经理【".$bmList[$oldMerchantInfo[$field]]."】至【".$bmList[$value]."】";
						}
						break;
				}
				if($content){
					//说明发生了改变
					$insertData =[];
					$insertData['merchant_id'] = $oldMerchantInfo['merchant_id'];
					$insertData['operator'] = $this -> adminInfo['name'];
					$insertData['gmt_create'] = date('Y-m-d H:i:s');
					$insertData['content'] = $content;
					$this ->  crmService -> insertMerchantLog($insertData);
				}
				
			}
		}
		
		
		
	}
	
	
	
	/**
	 * 检查提交的信息
	 */
	private function checkPostMerchantData()
	{
		
		$merchantName = trim($this->urlHelper->getValue('merchant_name'));
		$warehouseId = trim($this->urlHelper->getValue('warehouse_id'));
		$companyName = trim($this->urlHelper->getValue('company_name'));
		$clientId = trim($this->urlHelper->getValue('client_id'));
		$bmId = trim($this->urlHelper->getValue('business_manager_id'));
		$merchantContactName = trim($this->urlHelper->getValue('merchant_contact_name'));
		$merchantContactPhone = trim($this->urlHelper->getValue('merchant_contact_phone'));
		$remark = trim($this->urlHelper->getValue('remark'));
		$loginName = trim($this->urlHelper->getValue('login_name'));
		$merchantId = trim($this->urlHelper->getValue('merchant_id'));
		$adminMobile = trim($this -> urlHelper -> getValue('admin_mobile'));//注册手机号
		
		//进行必填的验证
		if(!$merchantName){
			$this->renderJSON(['status' => 'falied', 'msg' => '请输入商户名称']);
		}
		if(!$merchantId){
			if(!$warehouseId){
				$this->renderJSON(['status' => 'falied', 'msg' => '请选择绑定仓库']);
			}
			if(!$loginName){
				$this->renderJSON(['status' => 'falied', 'msg' => '请输入登录名']);
			}
			if(!$adminMobile || !is_numeric($adminMobile)){
				$this->renderJSON(['status' => 'falied', 'msg' => '请输入正确的注册手机号']);
			}
			
		}
		
		
		if(!$merchantContactName){
			$this->renderJSON(['status' => 'falied', 'msg' => '请输入联系人']);
		}
		if(!$merchantContactPhone || !is_numeric($merchantContactPhone)){
			$this->renderJSON(['status' => 'falied', 'msg' => '请输入正确的手机号']);
		}
	
		
		//进行仓库查重
		$res = $this->crmService->checkWarehouseRepeatOrMerchantNameExceptMerchantId($warehouseId, '', $merchantId);
		
		if ($res) {
			$this->renderJSON(['status' => 'falied', 'msg' => '！该仓库已绑定其他店铺']);
		}
		
		//进行商户名称的查重
		$res = $this->crmService->checkWarehouseRepeatOrMerchantNameExceptMerchantId(0, $merchantName, $merchantId);
		
		if ($res) {
			$this->renderJSON(['status' => 'falied', 'msg' => '！商户名称重复']);
		}
		if(!$merchantId){
			//进行登录名的查重
			$res = $this -> adminService -> getAdminInfoByAdminName($loginName);
			if($res){
				$this->renderJSON(['status' => 'falied', 'msg' => '！登录名重复']);
			}
			
		}
		
		$postData = [];
		$postData['merchant_name'] = $merchantName;
		$postData['warehouse_id'] = $warehouseId;
		$postData['merchant_contact_name'] = $merchantContactName;
		$postData['merchant_contact_phone'] = $merchantContactPhone;
		$postData['business_manager_id'] = $bmId;
		$postData['company_name'] = $companyName;
		$postData['client_id'] = $clientId;
		$postData['remark'] = $remark;
		$postData['login_name'] = $loginName;
		$postData['merchant_id'] = $merchantId;
		$postData['admin_mobile'] = $adminMobile;
		
		return $postData;
	}
	
	/**
	 * 删除商户
	 */
	public function deleteMerchant()
	{
		
		$merchantId = $this->urlHelper->getValue('merchant_id');
		
		if (!$merchantId) {
			$this->renderJSON(['status' => 'failed', 'msg' => '参数错误，删除失败']);
		}
		//查询该用户有没有订单，在确定是否可以删除
		$merchantInfo = $this -> crmService -> getMerchantInfoByMerchantId($merchantId);
		if(!$merchantInfo){
			$this->renderJSON(['status' => 'failed', 'msg' => '该商户不存在！']);
		}
		
		$warehouseId = $merchantInfo['warehouse_id'];
		
		//后面看下怎么改
		/*$haveOrder =  $this -> crmService -> checkHaveSdOrderByWarehouseId($warehouseId);
		if($haveOrder){
			$this->renderJSON(['status' => 'failed', 'msg' => '该商户已经有订单了,不允许删除！']);
		}*/
		
		$updateData = [];
		$updateData['is_delete'] = 1;
		$updateData['operator'] = $this->adminInfo['name'];
		$updateData['gmt_modified'] = date('Y-m-d H:i:s');
		$res = $this->crmService->updateMerchantByMerchantId($updateData, $merchantId);
		
		if (!$res) {
			$this->renderJSON(['status' => 'failed', 'msg' => '删除失败！']);
		}
		//进行对应用户登录的删除
		$this -> adminService -> deleteAdminByAdminName($merchantInfo['login_name']);
		$this->renderJSON(['status' => 'success', 'msg' => '删除成功！']);
	}
	
	/**
	 * 生成六位数的Id
	 */
	private function createMerchantCode()
	{
		$code =  HelperFactory::getCommonHelper()->makeInviteCode();
		//检查Code是否唯一
		$unique = $this->crmService->checkMercodeIsUnique($code);
		
		while (!$unique) {
			$code = HelperFactory::getCommonHelper()-> makeInviteCode();
			$unique = $this->crmService->checkMercodeIsUnique($code);
			if ($unique) {
				break;
			}
		}
		
		return $code;
	}
	
	/**
	 * 更新用户的保税申报状态；
	 */
	public function updateMerchantAllowBonded(){
		$merchantId = $this -> urlHelper -> getValue('merchant_id');
		if(!$merchantId){
			$this->renderJSON(['status' => 'failed', 'msg' => '参数错误！']);
		}
		$status = $this -> urlHelper -> getValue('status');
		
		if(!in_array($status,[0,1])){
			$this->renderJSON(['status' => 'failed', 'msg' => '参数错误！']);
		}
		$merchantInfo = $this -> crmService -> getMerchantInfoByMerchantId($merchantId);
		if($merchantInfo['allow_bonded'] ==$status){
			$this->renderJSON(['status' => 'failed', 'msg' => '请刷新重试！']);
		}
		if($merchantInfo['status'] == EjsConst::merchantStatusOfFreeze){
			$this->renderJSON(['status' => 'failed', 'msg' => '冻结状态不允许修改']);
		}
		
		
		$updateData = [];
		$updateData['allow_bonded'] = $status;
		$updateData['operator'] = $this->adminInfo['name'];
		$updateData['gmt_modified'] = date('Y-m-d H:i:s');
		$res = $this -> crmService -> updateMerchantByMerchantId($updateData,$merchantId);
		$this -> checkModifyMerchantInfoAndInsertMerchantLog($merchantInfo,$updateData);
		if (!$res) {
			$this->renderJSON(['status' => 'failed', 'msg' => '更新失败！']);
		}
		$this->renderJSON(['status' => 'success', 'msg' => '更新成功！']);
	}
	
	/**
	 * 更新用户的直邮申报状态；
	 */
	public function updateMerchantAllowDirect(){
		$merchantId = $this -> urlHelper -> getValue('merchant_id');
		if(!$merchantId){
			$this->renderJSON(['status' => 'failed', 'msg' => '参数错误！']);
		}
		$status = $this -> urlHelper -> getValue('status');
		
		if(!in_array($status,[0,1])){
			$this->renderJSON(['status' => 'failed', 'msg' => '参数错误！']);
		}
		$merchantInfo = $this -> crmService -> getMerchantInfoByMerchantId($merchantId);
		if($merchantInfo['allow_direct'] ==$status){
			$this->renderJSON(['status' => 'failed', 'msg' => '请刷新重试！']);
		}
		if($merchantInfo['status'] == EjsConst::merchantStatusOfFreeze){
			$this->renderJSON(['status' => 'failed', 'msg' => '冻结状态不允许修改']);
		}
		$updateData = [];
		$updateData['allow_direct'] = $status;
		$updateData['operator'] = $this->adminInfo['name'];
		$updateData['gmt_modified'] = date('Y-m-d H:i:s');
		$res = $this -> crmService -> updateMerchantByMerchantId($updateData,$merchantId);
		$this -> checkModifyMerchantInfoAndInsertMerchantLog($merchantInfo,$updateData);
		if (!$res) {
			$this->renderJSON(['status' => 'failed', 'msg' => '更新失败！']);
		}
		$this->renderJSON(['status' => 'success', 'msg' => '更新成功！']);
	}
	
	/**
	 * 更新小程序状态
	 */
	public function updateMerchantAllowXcx(){
		$merchantId = $this -> urlHelper -> getValue('merchant_id');
		if(!$merchantId){
			$this->renderJSON(['status' => 'failed', 'msg' => '参数错误！']);
		}
		$status = $this -> urlHelper -> getValue('status');
		
		if(!in_array($status,[0,1])){
			$this->renderJSON(['status' => 'failed', 'msg' => '参数错误！']);
		}
		$merchantInfo = $this -> crmService -> getMerchantInfoByMerchantId($merchantId);
		if($merchantInfo['allow_xcx'] ==$status){
			$this->renderJSON(['status' => 'failed', 'msg' => '请刷新重试！']);
		}
		if($merchantInfo['status'] == EjsConst::merchantStatusOfFreeze){
			$this->renderJSON(['status' => 'failed', 'msg' => '冻结状态不允许修改']);
		}
		$updateData = [];
		$updateData['allow_xcx'] = $status;
		$updateData['operator'] = $this->adminInfo['name'];
		$updateData['gmt_modified'] = date('Y-m-d H:i:s');
		$res = $this -> crmService -> updateMerchantByMerchantId($updateData,$merchantId);
		$this -> checkModifyMerchantInfoAndInsertMerchantLog($merchantInfo,$updateData);
		if (!$res) {
			$this->renderJSON(['status' => 'failed', 'msg' => '更新失败！']);
		}
		$this->renderJSON(['status' => 'success', 'msg' => '更新成功！']);
	}
	
	
	/**
	 * 更新商户状态
	 */
	public function updateMerchantStatus(){
		$merchantId = $this -> urlHelper -> getValue('merchant_id');
		if(!$merchantId){
			$this->renderJSON(['status' => 'failed', 'msg' => '参数错误！']);
		}
		
		$status = $this -> urlHelper -> getValue('status');
		
		if(!in_array($status,[1,2])){
			$this->renderJSON(['status' => 'failed', 'msg' => '参数错误！']);
		}
		$merchantInfo = $this -> crmService -> getMerchantInfoByMerchantId($merchantId);
		if($merchantInfo['merchant_status'] ==$status){
			$this->renderJSON(['status' => 'failed', 'msg' => '请刷新重试！']);
		}
		$updateData = [];
		$updateData['merchant_status'] = $status;
		$updateData['operator'] = $this->adminInfo['name'];
		$updateData['gmt_modified'] = date('Y-m-d H:i:s');
		$res = $this -> crmService -> updateMerchantByMerchantId($updateData,$merchantId);
		$this -> checkModifyMerchantInfoAndInsertMerchantLog($merchantInfo,$updateData);
		if (!$res) {
			$this->renderJSON(['status' => 'failed', 'msg' => '更新失败！']);
		}
		$this->renderJSON(['status' => 'success', 'msg' => '更新成功！']);
	}
	
	/**
	 * 绑定商务经理
	 */
	public function updateMerchantBm(){
		$merchantId = $this -> urlHelper -> getValue('merchant_id');
		if(!$merchantId){
			$this->renderJSON(['status' => 'failed', 'msg' => '参数错误！']);
		}
		$bmId = $this -> urlHelper -> getValue('business_manager_id');
		if(!$bmId || $bmId <0){
			$this->renderJSON(['status' => 'failed', 'msg' => '参数错误！']);
		}
		$updateData = [];
		$updateData['business_manager_id'] = $bmId;
		$updateData['operator'] = $this->adminInfo['name'];
		$updateData['gmt_modified'] = date('Y-m-d H:i:s');
		$res = $this -> crmService -> updateMerchantByMerchantId($updateData,$merchantId);
		if (!$res) {
			$this->renderJSON(['status' => 'failed', 'msg' => '更新失败！']);
		}
		$this->renderJSON(['status' => 'success', 'msg' => '更新成功！']);
		
	}
	
	/**
	 * 编辑小程序
	 */
	public function editMerchantXcx(){
		$merchantId = $this -> urlHelper -> getValue('merchant_id');
		if(!$merchantId){
			return false;
		}
		
		$merchantInfo = $this -> crmService -> getMerchantInfoByMerchantId($merchantId);
		
		if(!$merchantInfo){
			return false;
		}
		$data =[];
		$data['info'] = $merchantInfo;
		$this -> render($data);
		
	}
	
	
	/**
	 * 保存编辑小程序
	 */
	public function saveEditMerchantXcx(){
		
		$merchantId = $this -> urlHelper -> getValue('merchant_id');
		
		if($merchantId<0){
			
			$this -> renderJSON(['status'=> 'failed','msg' => '请传入商户ID']);
		}
		//然后进行文件的上传下载
		$merchantInfo = $this -> crmService -> getMerchantInfoByMerchantId($merchantId);
		if(!$merchantInfo){
			return false;
		}
		
		if(($_FILES['apiclient_cert']['tmp_name'] && !$_FILES['apiclient_key']['tmp_name']) ||(!$_FILES['apiclient_cert']['tmp_name'] && $_FILES['apiclient_key']['tmp_name']) ){
			$this -> renderJSON(['status' => 'failed','msg' => '请同时上传apiclient_cert.pem 和 apiclient_key.pem']);
		}
		$isWechat = 0;
		if($_FILES['apiclient_cert']['tmp_name']  && $_FILES['apiclient_key']['tmp_name'] ){
			//开始文件的存入
			$certFile = $_FILES['apiclient_cert']['tmp_name'];
			$keyFile = $_FILES['apiclient_key']['tmp_name'];
			
			//先判断目录
			if(!file_exists(RELATIVE_WECHAT_PAY_UPLOAD_DIR)){
				$this -> renderJSON(['status' => 'failed','msg' => '目录创建失败']);
			}
			
			$merchantCode = $merchantInfo['merchant_code'];
			$merchantDir = RELATIVE_WECHAT_PAY_UPLOAD_DIR.$merchantCode.'/';
			//先判断目录
			if(!file_exists($merchantDir)){
				@mkdir($merchantDir,0777,true);
			}
			
			unlink($merchantDir.'apiclient_cert.pem');
			unlink($merchantDir.'apiclient_key.pem');
			
			//进行文件的上传
			$certFileContent = file_get_contents($certFile);
			if(!$certFileContent){
				$this -> renderJSON(['status' => 'failed','msg' => '没有获得apiclient_cert.pem文件的内容']);
			}
			$res = file_put_contents($merchantDir.'apiclient_cert.pem',$certFileContent);
			
			if($res === false){
				$this -> renderJSON(['status' => 'failed','msg' => 'apiclient_cert.pem文件上传失败']);
			}
			//进行文件的上传
			$keyFileContent = file_get_contents($keyFile);
			if(!$keyFileContent){
				$this -> renderJSON(['status' => 'failed','msg' => '没有获得apiclient_key.pem文件的内容']);
			}
			$res = file_put_contents($merchantDir.'apiclient_key.pem',$certFileContent);
			
			if($res === false){
				$this -> renderJSON(['status' => 'failed','msg' => 'apiclient_key.pem文件上传失败']);
			}
			$isWechat = 1;
		}
		
		
		//入参的检查
		$xcxAppid = trim($this -> urlHelper -> getValue('xcx_appid'));
		$xcxAppSecret = trim($this -> urlHelper -> getValue('xcx_app_secret'));
		$xcxMercId = trim($this -> urlHelper -> getValue('xcx_merc_id'));
		$xcxName = trim($this -> urlHelper -> getValue('xcx_name'));
		$xcxIndustryLabel = trim($this -> urlHelper -> getValue('xcx_industry_label'));
		
		$updateData = [];
		$updateData['xcx_appid'] = $xcxAppid;
		$updateData['xcx_app_secret'] = $xcxAppSecret;
		$updateData['xcx_merc_id'] = $xcxMercId;
		$updateData['xcx_name'] = $xcxName;
		$updateData['xcx_industry_label'] = $xcxIndustryLabel;
		$updateData['is_registered'] = 1;
		$updateData['is_wechat'] = $isWechat;
		$updateData['gmt_modified'] = date('Y-m-d H:i:s');
		$updateData['operator'] = $this -> adminInfo['name'];
		$res = $this -> crmService -> updateMerchantXcxInfoByMerchantId($updateData,$merchantId);
		if($res === false){
			$this -> renderJSON(['status'=> 'failed','msg' => '保存失败']);
		}
		$this -> renderJSON(['status'=> 'success','msg' => '保存成功']);
	}
	
	/**
	 * 商户信息
	 */
	public function merchantDetail(){
		$merchantId = $this -> urlHelper -> getValue('merchant_id');
		if(!$merchantId){
			return false;
		}
		$merchantInfo = $this -> crmService -> getMerchantInfoByMerchantId($merchantId,true);
		$bmInfo = $this -> crmService -> getBusinessManagerByBmId($merchantInfo['business_manager_id']);
		
		$data =[];
		$data['merchantInfo'] = $merchantInfo;
		$data['bmInfo'] = $bmInfo;
		$this -> render($data);
		
	}
	
	/**
	 * 小程序账号的详情
	 */
	public function merchantXcxDetail(){
		$merchantId = $this -> urlHelper -> getValue('merchant_id');
		if(!$merchantId){
			return false;
		}
		$merchantXcxInfo = $this -> crmService -> getMerchantInfoByMerchantId($merchantId);
		
		$data = [];
		$data['merchantXcxInfo'] = $merchantXcxInfo;
		$this -> render($data);
		
	}
	
	/**
	 * 保存小程序的信息
	 */
	public function saveMerchantXcxInfo(){
		var_dump($_POST);
		var_dump($_FILES);
	
	
	
	}
	
	
	
	
}
