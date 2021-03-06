<?php

/**
*控制台
*/

class AdminLimitController extends ZcController{

    private $links;

    private $adminLimit;

    /**
     * @var AdminService
     *
     */
    private $adminService;

    private $adminInfo;

    private $urlHelper;

    private $encryptionHelper;

    /**
     * @var ReturnMessage
     */
    private $returnMessageHelper;




    public function __construct($route)
    {
        parent::__construct($route);

        $this -> adminLimit = AdminLimit::$limit;
        $this-> urlHelper = HelperFactory::getUrlHelper();
        $this -> adminService = ServiceFactory::getAdminService();
        $this -> encryptionHelper = HelperFactory::getEncryptionHelper();
        $this -> returnMessageHelper = HelperFactory::getReturnMessageHelper();

        $this->adminInfo = $this -> adminService -> getAdminInfo();
        if (!$this->adminInfo) {
            $this->redirect(Zc::url(YfjRouteConst::login));
        }

        $this -> links = array(
            array('route'=>YfjRouteConst::adminLimitAdmin,'name'=>'管理员'),
            array('route'=>YfjRouteConst::adminLimitAdminAdd,'name'=>'添加管理员'),
            array('route'=>YfjRouteConst::adminLimitGroup,'name'=>'用户组'),
        );
        if ($this->adminInfo['is_allow_group']){
            array_push($this -> links,array('route'=>YfjRouteConst::adminLimitGroupAdd,'name'=>'添加用户组'));
        }


    }

    /**
     * 管理员列表
     */
    public function adminList()
    {

        $formSubmit = $this->urlHelper->getValue('form_submit');
        $adminIdArray = $this->urlHelper->getValue('del_id');

        if ($formSubmit == 'ok' && !empty($adminIdArray)) {
            $rs = $this->adminService->deleteAdminByAdminIdArray($adminIdArray);
            if (!$rs) {
                $this->returnMessageHelper->incomingMessage('删除失败');
                exit;
            }

            foreach ($adminIdArray as $adminId) {
                $this->adminService->deleteAdminToGroupByCondition(['admin_id' => $adminId]);
            }

            $this->returnMessageHelper->incomingMessage('删除成功');
            exit;
        }

        if ($this->adminInfo['sp'] != '1') {
            $groupIdArray = $this->adminService->getAdminGroupIdArrayByParentId($this->adminInfo['adminGid']);
            $adminArray = $this->adminService->getAdminByAdminGroupIdArray($groupIdArray);
            $allAdminNum = $this->adminService->getAdminByAdminCountGroupIdArray($groupIdArray);
        } else {
            $adminArray = $this->adminService->getAdminByCondition([], true);
            $allAdminNum = $this->adminService->getAdminCountByCondition([]);
        }
        $adminGroupArray = $this->adminService->getAdminGroupByCondition([]);
        $pageHtml = $this->getPage($allAdminNum);

        $data = [];
        $data['adminList'] = $adminArray;
        $data['adminGroupArray'] = $adminGroupArray;
        $data['topLink'] = $this->sublink($this->links, YfjRouteConst::adminLimitAdmin);
        $data['pageHtml'] = $pageHtml;
        $this->render($data);
    }


    /**
     * 管理员编辑
     * @return bool
     */
    public function adminEdit(){
        $adminId = $this->urlHelper->getValue('admin_id');
        $formSubmit = $this->urlHelper->getValue('form_submit');
        $newpPw = $this->urlHelper->getValue('new_pw');
        $groupId = $this->urlHelper->getValue('gid');
        $isAllowGroup = $this ->urlHelper->getValue('add_group');
        $isSuperAdmin = $this ->urlHelper->getValue('super_admin');

        if((int)$adminId <=0 || (int)$adminId != $adminId){
            return false;
        }
        $adminArray = $this -> adminService ->getAdminByCondition(['admin_id'=>$adminId]);
        $adminInfo = $adminArray[$adminId];

        if(!$adminInfo){
            $this -> returnMessageHelper -> incomingMessage('用户不存在');exit;
        }
        if($formSubmit == 'ok' && (int)$groupId >0 && (int)$groupId == $groupId) {
            $data = [];
            $data['admin_id'] = $adminId;
            $data['admin_group_permission_id'] = $groupId;
            $data['is_allow_group'] = 0;
            if ($isAllowGroup) {
                $data['is_allow_group'] = 1;
            }
            if ($newpPw != '') {
                $data['admin_password'] = md5($newpPw);
            }
            $isAdmin = 0;
            if($isSuperAdmin){
                $isAdmin = 1;
            }
            $data['admin_is_super'] = $isAdmin;
            $rs = $this->adminService->updateAdminByAdminId($data);
            if (!$rs) {
                $this->returnMessageHelper->incomingMessage('修改失败');
                exit;
            }
            if ((int)$adminInfo['admin_group_permission_id'] != $groupId) {

                $this->adminService->updateAdminToGroupByWhere(['admin_group_id' => $groupId], " admin_id=$adminId");

            }

            $this->returnMessageHelper->incomingMessage('修改成功', Zc::url(YfjRouteConst::adminLimitAdmin));
            exit;

        }


        $permissionArray = $this -> adminService ->getAdminGroupListByCondition([]);

        $list = [];
        if($this->adminInfo['sp'] != '1'){
            $idArray = $this -> adminService ->getAdminGroupIdArrayByParentId($this->adminInfo['adminGid']);

            if(!empty($idArray)){

                foreach($permissionArray as $key =>$value){
                    if(in_array($key,$idArray)){
                        $list[] = $value;
                    }

                }


            }


        }else{

            $list = $permissionArray;
        }


        $data =[];
        $data['adminInfo'] = $adminInfo;
        $data['gadmin'] = $list;
        $data['topLink'] = $this -> sublink($this -> links,YfjRouteConst::adminLimitAdminEdit);
        $this->render ($data);
    }

    /**
     * 管理员删除
     * @return bool
     */
    public function adminDelete()
    {
        $adminId = $this->urlHelper->getValue('admin_id');
        if((int)$adminId <=0 || (int)$adminId != $adminId){
            return false;
        }
        $rs = $this -> adminService ->deleteAdminByAdminIdArray([$adminId]);

        if(!$rs){
            $this -> returnMessageHelper -> incomingMessage('删除失败');exit;
        }

        $this -> adminService -> deleteAdminToGroupByCondition(['admin_id'=>$adminId]);
        $this -> returnMessageHelper -> incomingMessage('删除成功');exit;

    }

    /**
     * 分页html
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
     * 管理员添加
     */
    public function adminAdd(){
        $formSubmit = $this->urlHelper->getValue('form_submit');
        $adminName = $this->urlHelper->getValue('admin_name');
        $groupId = $this->urlHelper->getValue('gid');
        $adminPassword = $this->urlHelper->getValue('admin_password');
        $isAllowGroup = $this ->urlHelper->getValue('add_group');


        if($formSubmit == 'ok'){
            if(!$groupId){
                $this -> returnMessageHelper -> incomingMessage('请选择分配权限组');exit;
            }
            $param = [];
            $param['admin_name'] = $adminName;
            $param['admin_password'] = md5($adminPassword);
            $param['admin_group_permission_id'] = $groupId;
            if(!empty($isAllowGroup)) {
                $param['is_allow_group'] = 1;
            }
            $rs = $this -> adminService -> insertAdmin($param);


            if(!$rs){
                $this -> returnMessageHelper -> incomingMessage('添加失败');exit;
            }

            $data = [];
            $data['admin_id'] = $rs;
            $data['admin_group_id'] = $groupId;
            $data['gmd_add'] = date('Y-m-d H:i:s',time());

            $this -> adminService -> insertAdminToGroup($data);


            $this ->redirect(Zc::url(YfjRouteConst::adminLimitAdmin));
            exit;

        }

        $permissionArray = $this -> adminService ->getAdminGroupListByCondition([]);

        $list = [];
        if($this->adminInfo['sp'] != '1'){
            $idArray = $this -> adminService ->getAdminGroupIdArrayByParentId($this->adminInfo['adminGid']);

            if(!empty($idArray)){

                foreach($permissionArray as $key =>$value){
                    if(in_array($key,$idArray)){
                        $list[] = $value;
                    }

                }
            }
        }else{

            $list = $permissionArray;
        }

        $data =[];
        $data['gadmin'] = $list;
        $data['topLink'] = $this -> sublink($this -> links,YfjRouteConst::adminLimitAdminAdd);
        $this->render ($data);
    }

    /**
     * ajax操作
     */
    public function ajax(){
        $branch = $this->urlHelper->getValue('branch');
        $adminName = $this->urlHelper->getValue('admin_name');
        $oldAdminName = $this->urlHelper->getValue('old_admin_name');
        switch($branch){
            case 'check_admin_name':
                $rs = $this -> adminService -> getAdminByCondition(['admin_name'=>$adminName]);

                if(!$rs){

                    exit('true');
                }else{
                    exit('false');
                }

                break;
            case 'check_gadmin_name':
                if($adminName == $oldAdminName){
                    exit('true');
                }

                $rs = $this -> adminService -> getAdminGroupByCondition(['admin_group_name'=>$adminName]);

                if(!$rs){

                    exit('true');
                }else{
                    exit('false');
                }
                break;

        }


    }

    /**
     * 用户组列表
     */
    public function groupList(){
        $data =[];
        $groupArray = $this -> adminService ->getAdminGroupListByCondition([]);

        if(!$groupArray){
            $data['topLink'] = $this -> sublink($this -> links,YfjRouteConst::adminLimitGroup);
            $this->render ($data,null,false,'children');
            exit;
        }
        $list = [];
        if($this->adminInfo['sp'] != '1'){
            $idArray = $this -> adminService ->getAdminGroupIdArrayByParentId($this->adminInfo['adminGid']);

            if(!empty($idArray)){

                foreach($groupArray as $key =>$value){
                    if(in_array($key,$idArray)){
                        $list[] = $value;
                    }

                }


            }
        }else{

            $list = $groupArray;
        }


        $data['list'] = $list;
        $data['topLink'] = $this -> sublink($this -> links,YfjRouteConst::adminLimitGroup);
        $this->render ($data);

    }

    /**
     * 添加用户组
     */
    public function groupAdd(){
        $formSubmit = $this->urlHelper->getValue('form_submit');
        $gname = $this->urlHelper->getValue('gname');
        $permissionIds = $this->urlHelper->getValue('permission_id');


        if($formSubmit == 'ok' && !empty($gname) ){

            if (empty($permissionIds)) {
                $this -> returnMessageHelper -> incomingMessage('请至少选择一个权限添加到该用户');exit;
            }

            $data = [];

            $time =date('Y-m-d H:i:s',time());

            $data['admin_group_name'] = $gname;
            $data['gmt_add']                     = $time;

            $rs = $this -> adminService ->insertAdminGroup($data);
            if(!$rs){
                $this -> returnMessageHelper -> incomingMessage('添加失败');exit;
            }

            foreach($permissionIds as $value){
                if((int)$value <=0 ||(int)$value != $value){
                    $this -> returnMessageHelper -> incomingMessage('参数错误');exit;
                }
                $groupToPermissionData = [];
                $groupToPermissionData['admin_group_id'] = $rs;
                $groupToPermissionData['admin_permission_id'] = (int)$value;
                $groupToPermissionData['gmt_add']                = $time;
                $rs1 = $this -> adminService ->insertAdminGroupToPermission($groupToPermissionData);
            }


            if($this->adminInfo['sp'] != '1'){
                $hierarchyData= [];
                $hierarchyData['admin_group_id']         = $this->adminInfo['adminGid'];
                $hierarchyData['admin_group_sub_id']   = $rs;
                $hierarchyData['gmt_add']                = $time;

                $rs2 = $this -> adminService ->insertAdminGroupHierarchy($hierarchyData);
            }

            $this -> returnMessageHelper -> incomingMessage('添加成功',Zc::url(YfjRouteConst::adminLimitGroup));
            exit;
        }

        $data =[];

        $data['limitArray'] = $this -> getPermission();

        $data['topLink'] = $this -> sublink($this -> links,YfjRouteConst::adminLimitGroupAdd);
        $this->render ($data,null,false,'children');


    }

    /**
     * 删除用户组
     */
    public function groupDelete()
    {
        $groupId = $this->urlHelper->getValue('gid');

        if((int)$groupId <=0 || (int)$groupId != $groupId ){
            $this -> returnMessageHelper -> incomingMessage('参数错误');exit;
        }

        $idArray = $this -> adminService ->getAdminGroupIdArrayByParentId($groupId);
        $idArray[] = $groupId;

        $this -> adminService ->deleteAdminGroupByIdArray($idArray);
         $this -> adminService ->deleteAdminGroupHierarchyByIdArray($idArray);
        $this -> adminService ->deleteAdminGroupToPermissionByIdArray($idArray);
        $this -> loginOutAdminByGroupIdArray($idArray);

        $this ->redirect(Zc::url(YfjRouteConst::adminLimitGroup));
        exit;
    }

    /**
     * 下线用户组下的用户
     * @param $groupIdArray
     * @return bool
     */
    private function loginOutAdminByGroupIdArray($groupIdArray)
    {
        if(empty($groupIdArray)){
            return false;
        }

        $adminArray = $this -> adminService ->getAdminByAdminGroupIdArray($groupIdArray);
        if(!$adminArray){
            return true;
        }
        $redisObj = CacheFactory::getRedisCache();
        foreach($adminArray as $adminId => $admin){

            $adminSessionIdsKey = CacheKeyBuilder::bulidCategoriesTree($adminId);
            $redisObj -> set($adminSessionIdsKey,'' , -3600);

        }
        return true;
    }

    /**
     * 用户组编辑
     * @return bool
     */
    public function groupEdit()
    {
        $groupId = $this->urlHelper->getValue('gid');
        $formSubmit = $this->urlHelper->getValue('form_submit');
        $gname = $this->urlHelper->getValue('gname');
        $permissionIds = $this->urlHelper->getValue('permission_id');

        if((int)$groupId <=0 || (int)$groupId != $groupId){
            return false;
        }

        $groupArray = $this -> adminService ->getAdminGroupByCondition(['admin_group_id'=>$groupId]);
        $groupInfo = $groupArray[$groupId];
       if(!$groupInfo){
           $this ->redirect(Zc::url(YfjRouteConst::adminLimitGroup));
       }
        $limit = $this -> adminService ->getAdminPermissionListByAdminGroupId($groupId);

        $limitIds = array_keys($limit);


        if($formSubmit == 'ok' && !empty($gname))
        {
            if (empty($permissionIds)) {
                $this -> returnMessageHelper -> incomingMessage('请至少选择一个权限添加到该用户');exit;
            }

            $subIdArray = $this -> adminService ->getAdminGroupIdArrayByParentId($groupId);
            $idArray = $subIdArray;
            $idArray[] = $groupId;

            $removedPermissionIds = array();
            $addPermissionIds = array();
            if(trim($gname) != $groupInfo['admin_group_name']) {
                $this->adminService->updateAdminGroupByAdminGroupId($groupId,['admin_group_name' => trim($gname)]);
            }
            foreach($limitIds as $id){
                if(!in_array($id,$permissionIds)){
                    $removedPermissionIds[] = $id;
                }

            }

            foreach($permissionIds as $id){
                if(!in_array($id,$limitIds)){
                    $addPermissionIds[] = $id;
                }

            }

            foreach($removedPermissionIds as $value)
            {
                $rs = $this -> adminService -> deleteAdminGroupToPermissionByGroupIdsAndPermissionId($idArray,$value);

            }

            foreach($addPermissionIds as $value){
                $data =[];
                $data['admin_group_id']         = $groupId;
                $data['admin_permission_id']    = $value;
                $data['gmt_add']                = date('Y-m-d H:i:s',time());

                $rs = $this -> adminService ->insertAdminGroupToPermission($data);

            }

            $this -> loginOutAdminByGroupIdArray($idArray);

            $this ->redirect(Zc::url(YfjRouteConst::adminLimitGroup));
            exit;

        }

        $data['groupInfo'] = $groupArray[$groupId];
        $data['limitIds'] = $limitIds;
        $data['limitArray'] = $this -> getPermission();
        // var_dump($this -> getPermission());exit;

        $data['topLink'] = $this -> sublink($this -> links,YfjRouteConst::adminLimitGroupEdit);
        $this->render ($data,null,false,'children');
    }

    /**
     * 取得所有权限项
     *
     * @return array
     */
    private function getPermission() {


        if($this->adminInfo['sp'] != '1'){
            $adminGid = $this->adminInfo['adminGid'];
            $permissionArray = $this -> adminService ->getAdminPermissionListByAdminGroupId($adminGid);

        }else{

            $permissionArray = $this -> adminService ->getAdminPermissionListByAdminGroupId();
        }

        $permissionArray = $this -> getAssociatedLimitByOrigin($permissionArray);

        return $permissionArray;

    }

    /**
     * 取得顶部小导航
     * @param array $links
     * @param string $activeRoute
     * @return string
     */
    private function sublink($links = array(), $activeRoute = ''){
        $linkstr = '';
        foreach($links as$key=> $value){
            if($this->adminInfo == '1' && $value['route'] == YfjRouteConst::permissionIndex){
                continue;
            }

            $href = $value['route'] == $activeRoute?null:"href=\"".Zc::url($value['route'])."\"";
            $class = $value['route'] == $activeRoute ? "class=\"current\"" : null;
            $lang = $value['name'];
            $linkstr .= sprintf('<li><a %s %s><span>%s</span></a></li>',$href,$class,$lang);
        }

        return "<ul class=\"tab-base\">{$linkstr}</ul>";


    }

    /**
     * 组装权限
     * @param $permissionArray
     * @return array|bool
     */
    private function getAssociatedLimitByOrigin($permissionArray)
    {
        if (!is_array($permissionArray) || empty($permissionArray)) {
            return [];
        }

        $return = [];
        foreach ($permissionArray as $key => $value) {

            if (!empty($value['module_name'])) {
                $siteName = $this->getSiteNameBySite($value['site']);
                $return[$siteName][$value['module_name']]['topName'] = $value['module_desc'];
                $return[$siteName][$value['module_name']]['child'][$value['class_desc']][$value['admin_permission_id']] = [
                    'id' => $value['admin_permission_id'],
                    'route' => $value['admin_permission_name'],
                    'desc' => $value['admin_permission_desc']
                ];
            }
        }
        return $return;
    }

    private function getSiteNameBySite($site)
    {
        $allSiteNameArray = $this->getAllSiteNameArray();
        return isset($allSiteNameArray[$site]) ? $allSiteNameArray[$site] : '其他';
    }

    private function getAllSiteNameArray()
    {
        return array(
            'G_WWW_ERJIASAN_SYS_COM_DOMAIN' => '二加三后台',
        );
    }

    /**
     * 首次登陆修改密码
     */
    public function initAdminPassword()
    {
        if ($this->urlHelper->getValue('form_submit') == 'ok') {

            $newPassword = $this->urlHelper->getValue('new_pw');
            $repeatPassword = $this->urlHelper->getValue('new_pw2');

            $validate = HelperFactory::getValidateHelper();
            $validate->setCustomValidate($newPassword, "/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,12}$/","不可以为空");
            $validate->setCustomValidate($newPassword,'==', $repeatPassword,"重复密码输入错误");
            if ($error = $validate->validate()) {
                $this->returnMessageHelper->incomingMessage($error);
            }

            $data = [];
            $data['admin_password'] = md5($newPassword);
            $data['is_new_account'] = EjsConst::AdminNotNewAccount;
            $data['admin_id'] = $this->adminInfo['id'];

            $rs = $this->adminService->updateAdminByAdminId($data);
            if (!$rs) {
                $this->returnMessageHelper->incomingMessage('修改失败');
                exit;
            }

            $this->returnMessageHelper->incomingMessage('初始化密码修改成功', Zc::url(YfjRouteConst::index));
            exit;
        }

        $renderData = [
            'adminInfo' => $this->adminInfo
        ];
        $this->render($renderData);
    }

    public function adminChangePassword()
    {
        // var_dump($this->adminInfo);
        if ($this->urlHelper->getValue('form_submit') == 'ok') {

            $oldPassword = $this->urlHelper->getValue('old_pw');
            $newPassword = $this->urlHelper->getValue('new_pw');
            $repeatPassword = $this->urlHelper->getValue('new_pw2');

            // 检查旧密码是否错误
            $res = $this->adminService->checkAdminPassword($this->adminInfo['id'], $oldPassword);
            if (!$res) {
                $this->returnMessageHelper->incomingMessage('旧密码输入错误');
            }

            $validate = HelperFactory::getValidateHelper();
            $validate->setCustomValidate($newPassword, "/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,12}$/","不可以为空");
            $validate->setCustomValidate($newPassword,'==', $repeatPassword,"重复密码输入错误");
            if ($error = $validate->validate()) {
                $this->returnMessageHelper->incomingMessage($error);
            }

            $data = [];
            $data['admin_password'] = md5($newPassword);
            $data['is_new_account'] = EjsConst::AdminNotNewAccount;
            $data['admin_id'] = $this->adminInfo['id'];

            $rs = $this->adminService->updateAdminByAdminId($data);
            if ($rs === false) {
                $this->returnMessageHelper->incomingMessage('修改失败');
            }

            $this->returnMessageHelper->incomingMessage('初始化密码修改成功', Zc::url(YfjRouteConst::adminChangePassword));
        }

        $renderData = [
            'adminInfo' => $this->adminInfo
        ];
        $this->render($renderData);
    }

    /**
     * 绑定手机
     */
    public function adminBindMobile()
    {
        $sendMobileSubmit = $this->urlHelper->getValue('send_mobile_submit');
        $formSubmit = $this->urlHelper->getValue('form_submit');

        // 发送短信回执操作
        if ($sendMobileSubmit == 'ok') {

            $mobile = $this->urlHelper->getValue('mobile');

            $validate = HelperFactory::getValidateHelper();
            $validate->setDefaultValidate($mobile, 'mobile', '手机号格式错误');
            if ($error = $validate->validate()) {
                $this->renderJSON(['status' => 'failed', 'msg' => $error]);
            }

            $smsHelper = HelperFactory::getSmsHelper();

            $code = mt_rand(1000, 9999);
            $sendType = EjsConst::SmsTypeAdminBindMobile;
            $content = $smsHelper->createSmsContent($code, $sendType);

            // 发送短信
            $result = $smsHelper->send($mobile, $content);
            if (!$result) {
                $this->renderJSON(['status' => 'failed', 'msg' => '短信发送错误,请稍后再试', 'longMsg' => $result]);
            }

            $smsLogId = $this->adminService->insertSmsLog($mobile, $code, $content, $sendType);
            if (!$smsLogId) {
                $this->renderJSON(['status' => 'failed', 'msg' => '短信发送错误,请稍后再试']);
            }

            $this->renderJSON(['status' => 'success', 'msg' => '发送成功', 'code' => $code]);
        }
        // 提交绑定手机
        else if ($formSubmit == 'ok') {

            // 检查验证码是否正确
            $logCaptcha = $this->urlHelper->getValue('log_captcha');
            $mobile = $this->urlHelper->getValue('mobile');

            $validate = HelperFactory::getValidateHelper();
            $validate->setDefaultValidate($mobile, 'mobile', '手机号格式错误');
            $validate->setLengthValidate($logCaptcha, '4', '', '验证码长度4位');
            if ($error = $validate->validate()) {
                $this->renderJSON(['status' => 'failed', 'msg' => $error]);
            }

            $res = $this->adminService->getCountSmsLogByMobileAndCode($mobile, $logCaptcha);
            if (!$res) {
                $this->renderJSON(['status' => 'failed', 'msg' => '短信验证码错误']);
            }

            $data = [
                'admin_mobile' => $mobile,
                'admin_id' => $this->adminInfo['id'],
                'gmt_modified' => date('Y-m-d H:i:s')
            ];

            $res = $this->adminService->updateAdminByAdminId($data);
            if (!$res) {
                $this->renderJSON(['status' => 'failed', 'msg' => '绑定手机错误']);
            }
            $this->renderJSON(['status' => 'success', 'msg' => '绑定手机成功']);
        }

        $renderData = [
            'adminInfo' => $this->adminInfo,
            'countTimeRefreshTime' => EjsConst::countTimeRefreshTime
        ];
        $this->render($renderData);

    }
}


?>