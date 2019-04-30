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

class AdminService {


	/**
	 * user dao
	 * @var AdminDao
	 */
	private $adminDao;

	/**
	 *
	 * @var Ip
	 */
	private $ipHelper;

	private $encryptionHelper;

	/**
	 * 构造函数初始化ID
	 */
	public function __construct() {

	    $this -> ipHelper = HelperFactory::getIpHelper();

		//Dao
		$this -> adminDao = DaoFactory::getAdminDao();

		$this -> encryptionHelper = HelperFactory::getEncryptionHelper();

	}


   /*  public function getUserBaseMessageByUids($userIdArray){
        if( !$userIdArray || !is_array($userIdArray) ){
            return false;
        }

        return $this -> userDao -> getUserBaseMessageByUids($userIdArray);
    }

    public function getUserBaseMessageByUid($userId){
        if( (int)$userId <= 0 || (int)$userId != $userId ){
            return false;
        }

        $userMessage = $this -> userDao -> getUserBaseMessageByUids(array($userId));
        if( !$userMessage ){
            return false;
        }

        return $userMessage[$userId];
    } */



    public function checkAdminByMixNameAndPassword($adminName,$adminPassword){
        if( empty($adminName) || empty($adminPassword) ){
            return false;
        }

        return $this -> adminDao -> checkAdminByMixNameAndPassword($adminName, $adminPassword);
    }

	public function updateAdminByAdminId($param){
		
	    if(empty($param)){
		   return false;
	    }

	    if(is_array($param)){

		   $temp = $param;

		   return $this -> adminDao -> updateAdmin($temp,$param['admin_id']);

	    }else{
		   return false ;
	    }
	}

    /**
	 * 系统后台 会员登录后 将内容写入对应cookie跟redis
	 *
	 * @param array $user 用户信息
	 */
	public final function systemSetKey($userInfo){
		HelperFactory::getCookieHelper() -> set('sys_key','',HelperFactory::getEncryptionHelper()->encrypt(serialize($userInfo),"MD5_KEY"),time()+3600*5);

		$data = [];
		$sessionId = session_id();
		$redisObj = CacheFactory::getRedisCache();
		$adminSessionIdsKey = CacheKeyBuilder::bulidCategoriesTree($userInfo['id']);

		$SessionIdArray = $redisObj -> get($adminSessionIdsKey);
		if(!empty($SessionIdArray)){
			$data = json_decode($SessionIdArray);
		}
		if(!in_array($sessionId,$data)){
			$data[] = $sessionId;
			$val = $redisObj -> set($adminSessionIdsKey,json_encode($data) , 3600*5);

		}



	}

	/**
	 * 系统后台登录验证
	 *
	 * @param
	 * @return array 数组类型的返回结果
	 */
	public final function systemLogin(){
		//取得cookie内容，解密，和系统匹配
		$user = unserialize(HelperFactory::getEncryptionHelper()->decrypt(HelperFactory::getCookieHelper() -> get('sys_key'),MD5_KEY));

		$redisObj = CacheFactory::getRedisCache();
		$adminSessionIdsKey = CacheKeyBuilder::bulidCategoriesTree($user['id']);
		$SessionIdArray = $redisObj -> get($adminSessionIdsKey);

		if (!key_exists('gid',(array)$user) || !isset($user['sp']) || (empty($user['name']) || empty($user['id'])) || empty($SessionIdArray)){
			@header('Location: '.Zc::url(YfjRouteConst::login));exit;
		}
		return $user;
	}

	/**
	 * 获取管理员信息
	 * @return mixed
	 */
	public final function getAdminInfo(){

		$user = unserialize(HelperFactory::getEncryptionHelper()->decrypt(HelperFactory::getCookieHelper() -> get('sys_key'),MD5_KEY));

		if(is_array($user) and !empty($user)) {
			$this -> systemSetKey($user); // 防止操作过程中退出后台登陆
		}
		return $user;

	}




	/**
	 * 插入管理员表
	 * @param $data
	 * @return bool
	 */
	public function insertAdmin($data){
		if (empty($data)) {
			return false;
		}

		return $this -> adminDao -> insertAdmin($data);
	}

	/**
	 * 插入用户组权限表
	 * @param $data
	 * @return bool
	 */
	public function insertAdminGroupPermission($data){
		if (empty($data)) {
			return false;
		}

		return $this ->adminDao ->insertAdminGroupPermission($data);


	}

	/**
	 * 插入用户组表
	 * @param $data
	 * @return bool
	 */
	public function insertAdminGroup($data){
		if (empty($data)) {
			return false;
		}

		return $this ->adminDao ->insertAdminGroup($data);


	}

	/**
	 * 更新用户组权限表
	 * @param $data
	 * @param $where
	 * @return bool
	 */
	public function updateAdminGroupPermission($data,$where){

		if(empty($data) || !$where){
			return false;
		}

		return $this -> adminDao ->updateAdminGroupPermission($data,$where);
	}

	/**
	 * 获取用户组表
	 * @param $condition
	 * @return bool
	 */
	public function getAdminGroupListByCondition($condition){

		if(!is_array($condition)){
			return false;
		}

		$groupArray =  $this -> adminDao -> getAdminGroupListByCondition($condition);


		return $groupArray;


	}

	/**
	 * 获取管理员权限表
	 * @param null $adminGroupId
	 * @return mixed
	 */
	public function getAdminPermissionListByAdminGroupId($adminGroupId =null){

		return $this -> adminDao -> getAdminPermissionListByAdminGroupId($adminGroupId);

	}

    /**
     * 通过用户组id获取权限
     * @param $groupId
     */
	public function getPermissionByGroupId($groupId)
    {
        return $this -> adminDao -> getPermissionByGroupId($groupId);
    }

	/**
	 * 检查权限是否已经被翻译过
	 * @param string $permission 权限的名字
	 * @param array $menuTranslateArray 已经翻译的权限数组
	 * @return array|bool
	 */
	public function checkPermissionTranslate($permission,$menuTranslateArray) {
		$topKey = explode('/',$permission);
		if(array_key_exists($topKey[0],$menuTranslateArray)){
			return array('nameLangKey'=>$topKey[0],'topName' => $menuTranslateArray[$topKey[0]]);
		}
		return false;

	}

    /**
     * 检查权限是否已经被翻译过
     * @param string $permission 权限的名字
     * @param array $menuTranslateArray 已经翻译的权限数组
     * @return array|bool
     */
    public function checkLeftMenuPermissionTranslate($permission,$leftmenuTranslateArray) {
        $topKey = explode('/',$permission);
        krsort($topKey);
        $route = implode(",",$topKey);
        foreach ($leftmenuTranslateArray as $childMenuArray){
            foreach ($childMenuArray["list"] as $childMenu){
                if($route == $childMenu){
                    return array('leftMenuName' => $childMenuArray["name"]);
                }
            }
        }
        return false;

    }

	public function getAssociatedInfo($menuArray)
	{
		if(!is_array($menuArray) || empty($menuArray)){
			return [];
		}

		$returnMenu = [];

		foreach($menuArray as $key => $value){

			$returnMenu[$value['args']] = $value['text'];


		}

		return $returnMenu;


	}

    /**
     * 获取关联菜单
     * @param $menuArray
     */
	public function getContextMenu($menuArray)
    {
        if (!is_array($menuArray) || empty($menuArray)) {
            return [];
        }
        $returnMenu = [];

        foreach ($menuArray as $key => $value) {
            $returnMenu[$value['args']] = $value['text'];
        }

        return $returnMenu;
    }

	/**
	 * 插入用户组层次表
	 * @param $data
	 * @return bool
	 */
	public function insertAdminGroupHierarchy($data){
		if (empty($data)) {
			return false;
		}

		return $this ->adminDao ->insertAdminGroupHierarchy($data);


	}


	/**
	 * 获取子组列表
	 * @param int $parentId
	 * @return array|bool
	 */
	public function  getAdminGroupIdArrayByParentId($parentId){
		if((int)$parentId <=0 || (int)$parentId != $parentId){
			return false;
		}

		$groupHierarchyArray = $this ->adminDao ->getAdminGroupHierarchyByCondition([]);
		if(!$groupHierarchyArray){
			return false;
		}


		$idArray = [];
		$parentIdArray = [$parentId];
		foreach($groupHierarchyArray as $value){

			if(in_array($value['admin_group_id'],$parentIdArray)){
				$parentIdArray[] = $value['admin_group_sub_id'];
				$idArray[] = $value['admin_group_sub_id'];
			}

		}

		return $idArray;




	}

	/**
	 * 获取用户组跟权限关系表
	 * @param $condition
	 * @return bool
	 */
	public function getAdminGroupToPermissionByCondition($condition)
	{
		if(!is_array($condition)){
			return false;
		}

		return $this -> adminDao -> getAdminGroupToPermissionByCondition($condition);
	}

	/**
	 * 插入用户组跟权限关系表
	 * @param $data
	 * @return bool
	 */
	public function insertAdminGroupToPermission($data)
	{
		if (empty($data)) {
			return false;
		}

		return $this -> adminDao ->insertAdminGroupToPermission($data);

	}

	/**
	 * 通过用户组ids跟权限id删除用户组跟权限关系表
	 * @param $groupIds
	 * @param $permissionId
	 * @return bool
	 */
	public function deleteAdminGroupToPermissionByGroupIdsAndPermissionId($groupIds,$permissionId)
	{
		if(empty($groupIds) || (int)$permissionId <=0 || (int)$permissionId != $permissionId){
			return false;
		}

		return $this -> adminDao -> deleteAdminGroupToPermissionByGroupIdsAndPermissionId($groupIds,$permissionId);

	}


	/**
	 * 删除用户组表
	 * @param $condition
	 * @return bool
	 */
	public function deleteAdminGroupByIdArray($idArray){
		if (!$idArray || !is_array($idArray)) {
			return false;
		}

		return $this -> adminDao -> deleteAdminGroupByIdArray($idArray);


	}

	/**
	 * 删除用户组层次表
	 * @param $condition
	 * @return bool
	 */
	public function deleteAdminGroupHierarchyByIdArray($idArray){
		if (!$idArray || !is_array($idArray)) {
			return false;
		}

		return $this -> adminDao -> deleteAdminGroupHierarchyByIdArray($idArray);

	}

    /**
     * 删除用户组对应的权限
     * @param $idArray
     * @return bool
     */
	public function deleteAdminGroupToPermissionByIdArray($idArray){
        if (!$idArray || !is_array($idArray)) {
            return false;
        }

        return $this -> adminDao -> deleteAdminGroupToPermissionByIdArray($idArray);
    }

	/**
	 *获取管理员信息
	 * @param $condition
	 * @return bool
	 */
	public function getAdminByCondition($condition,$isPage=false)
	{
		if (!is_array($condition) || !is_bool($isPage)) {
			return false;
		}

		return $this -> adminDao -> getAdminByCondition($condition,$isPage);

	}


	/**
	 * 通过adminid数组删除用户
	 * @param $adminIdArray
	 * @return bool
	 */
	public function deleteAdminByAdminIdArray($adminIdArray)
	{
		if(empty($adminIdArray)){
			return false;
		}
		return $this -> adminDao ->deleteAdminByAdminIdArray($adminIdArray);

	}
	
	/**
	 * 通过登录名进行删除用户
	 * @param $adminName
	 */
	public function deleteAdminByAdminName($adminName){
		if(!$adminName){
			return false;
		}
		
		return $this -> adminDao -> deleteAdminByAdminName($adminName);
	}
	
	
	
	
	/**
	 * 获取管理员信息数量
	 * @param $condition
	 * @return bool|int
	 */
	public function getAdminCountByCondition($condition)
	{
		if(!is_array($condition) ){
			return false;
		}

		$return = $this -> adminDao -> getAdminCountByCondition($condition);
		if(!$return){
			return 0;
		}

		return $return;

	}


	/**
	 * 通过用户组ids获取用户
	 * @param $adminGroupIdArray
	 * @return bool
	 */
	public function getAdminByAdminGroupIdArray($adminGroupIdArray,$isPage=false)
	{
		if(empty($adminGroupIdArray) || !is_bool($isPage)){
			return false;
		}
		return $this -> adminDao -> getAdminByAdminGroupIdArray($adminGroupIdArray,$isPage);

	}

	/**
	 * 通过用户组ids获取用户数量
	 * @param $adminGroupIdArray
	 * @return bool|int
	 */
	public function getAdminByAdminCountGroupIdArray($adminGroupIdArray)
	{
		if(empty($adminGroupIdArray) ){
			return false;
		}

		$return = $this -> adminDao -> getAdminByAdminCountGroupIdArray($adminGroupIdArray);
		if(!$return){
			return 0;
		}

		return $return;

	}

	/**
	 *获取用户组信息
	 * @param $condition
	 * @return bool
	 */
	public function getAdminGroupByCondition($condition)
	{
		if (!is_array($condition)) {
			return false;
		}

		return $this -> adminDao -> getAdminGroupByCondition($condition);

	}

	/**
	 * 插入用户跟用户组关系表
	 * @param $data
	 * @return bool
	 */
	public function insertAdminToGroup($data)
	{
		if (empty($data)) {
			return false;
		}

		return $this -> adminDao -> insertAdminToGroup($data);

	}

	/**
	 * 更新用户跟用户组关系表
	 * @param $data
	 * @param $where
	 * @return bool
	 */
	public function updateAdminToGroupByWhere($data,$where)
	{
		if(empty($data) || !$where){
			return false;
		}

		return $this -> adminDao -> updateAdminToGroupByWhere($data,$where);

	}

	/**
	 * 删除用户跟用户组关系表
	 * @param $condition
	 * @return bool
	 */
	public function deleteAdminToGroupByCondition($condition)
	{
		if (empty($condition) || !is_array($condition)) {
			return false;
		}

		return $this -> adminDao -> deleteAdminToGroupByCondition($condition);

	}





   /*  public function insertMember($data){
	    if( empty($data) ){
	        return false;
	    }

	    if( $data['inviter_code'] ){
	        $inviterMessage = $this -> userDao -> checkInviterCodeByCode($data['inviter_code']);
	        if( !$inviterMessage ){
	            return ErrorCodeConst::InviterCode;
	        }

	        $data['inviter_id'] = $inviterMessage['member_id'];
	    }

	    $data['inviter_code'] = $this -> createInvitedCode();
	    $data['member_name'] = $this -> createMemberName();
	    $memberId = $this -> userDao -> insertMember($data);

	    if( !$memberId ){
	        return false;
	    }

	    $this -> getRegisterVoucher($memberId, $data['member_name']);
	    return $memberId;
	} */

    /**
     * @param $logArr
     */
    public function insertSystemMgLog($logArr)
    {
        if(empty($logArr) || !is_array($logArr)){
            return false;
        }
        return $this -> adminDao -> insertSystemMgLog($logArr);
    }

    /**
     * 获取权限数量
     * @param string $site
     * @return int
     */
    public function getPermissionNum($site=''){
        return $this -> adminDao -> getPermissionNum($site);
    }

    /**
     * 更新用户权限组
     * @param $adminGroupId
     * @param $data
     * @return bool|false|int
     */
    public function updateAdminGroupByAdminGroupId($adminGroupId,$data)
    {
        if ((int)$adminGroupId <= 0 || (int)$adminGroupId != $adminGroupId || empty($data)) {
            return false; 
        }
        $rs = $this->adminDao->updateAdminGroupByAdminGroupId($adminGroupId, $data);
        if (!$rs) {
            return false;
        }
        return $rs;
    }

    /**
     * 根据管理员名称返回管理员信息
     * @param string $adminName 管理员名称
     * @return array
     */
    public function getAdminInfoByAdminName($adminName) {
        return $this -> adminDao -> getAdminInfoByAdminName($adminName);
    }
	
	/**
	 * 检测用户密码是否正确
	 * @param int     $adminId
	 * @param string  $password
	 * @return bool
	 */
	public function checkAdminPassword($adminId, $password) {
		return $this -> adminDao -> checkAdminPassword($adminId, $password);
	}

    /**
     * 新增短信发送记录
     * @param $mobile
     * @param $code
     * @param $content
     * @param $sendType
     */
	public function insertSmsLog($mobile, $code, $content, $sendType=EjsConst::SmsTypeAdminBindMobile)
    {
        if (!$mobile || !$code || !$content) {
            return false;
        }
        $smsLogDao = DaoFactory::getSmsLogDao();

        $data['log_phone'] = $mobile;
        $data['log_captcha'] = $code;
        $data['log_ip'] = Ip::clientIp();
        $data['log_msg'] = $content;
        $data['log_type'] = $sendType;
        $data['gmt_create'] = date('Y-m-d H:i:s');
        $data['gmt_modified'] = date('Y-m-d H:i:s');
        $data['operator'] = 'system';

        $smsLogId = $smsLogDao->insertSmsLog($data);
        if (!$smsLogId) {
            return false;
        }
        return $smsLogId;
    }

    /**
     * 根据手机号和验证码获取有效短信记录条数
     * @param $mobile
     * @param $code
     * @return bool|int
     */
    public function getCountSmsLogByMobileAndCode($mobile, $code)
    {
        if (!$mobile || !$code) {
            return false;
        }

        $smsLogDao = DaoFactory::getSmsLogDao();
	    $count = $smsLogDao->getCountSmsLogByMobileAndCode($mobile, $code, true);
	    if ($count <= 0) {
	        return false;
        }

        return true;
    }

}
