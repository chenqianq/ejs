<?php

/**
 *首页
 *
 *一个public 就是一个action
 *
 *helper 是工具类 这个可以全局去使用，采用的都是工厂单体模式。
 */
class LoginController extends ZcController
{
    /**
     *
     * @var Captcha
     */
    private $CaptchaHelper;


    /**
     * @var AdminService
     */
    private $adminService;
    /**
     * @var Cookie
     */
	private $cookieHelper;
    /**
     * @var Encryption
     */
	private $encryptionHelper;
    /**
     * @var ReturnMessage
     */
	private $returnMessageHelper;

    /**
     * 构造函数
     *
     * @param string $route
     *            router
     */
    public function __construct($route)
    {
        parent::__construct($route);
        //对业务层的service 进行初始化 ， 初始化为工厂单体模式。

        $this->cookieHelper = HelperFactory::getCookieHelper();
        $this->encryptionHelper = HelperFactory::getEncryptionHelper();
        $this->CaptchaHelper = HelperFactory::getCaptchaHelper(["captcha_type"=>"yfj_admin_captchaCode"]);
        $this->returnMessageHelper = HelperFactory::getReturnMessageHelper();
        $this->adminService = ServiceFactory::getAdminService();

        $result = $this->checkSubmit(true);
        if ($result) {
            if ($result === -1) {

               $this->returnMessageHelper->incomingMessage('验证码错误');
               exit;
            }
            if (empty($_POST['user_name']) || empty($_POST['password'])) {

                $this->returnMessageHelper->incomingMessage('用户名或密码不能为空');
                exit;
            }

            $array = array();
            $array['admin_name'] = $_POST['user_name'];
            $array['admin_password'] = md5(trim($_POST['password']));

            $admin_info = $this->adminService->checkAdminByMixNameAndPassword($array['admin_name'], $array['admin_password']);
            if (!empty($admin_info) && is_array($admin_info)) {

                if($admin_info['admin_is_super'] != '1') {
                    $adminGroupInfo = $this->adminService->getAdminGroupByCondition(['admin_group_id' => $admin_info['admin_group_permission_id']]);
                    if (!$adminGroupInfo || !array_key_exists($admin_info['admin_group_permission_id'], $adminGroupInfo)) {
                        $this->returnMessageHelper->incomingMessage('管理员组不存在');
                        exit;
                    }
                }
                $this->adminService->systemSetKey(array(
                    'name' => $admin_info['admin_name'],
                    'id' => $admin_info['admin_id'],
                    'gid' => $admin_info['admin_gid'],
                    'sp' => $admin_info['admin_is_super'],
                    'level' => $admin_info['admin_level'],
                    'adminGid' => $admin_info['admin_group_permission_id'],
                    'is_allow_group' => $admin_info['is_allow_group'],
                    'business_manager_id' => $admin_info['business_manager_id'],
                    'merchant_id' => $admin_info['merchant_id'],
                    'admin_login_num' => $admin_info['admin_login_num'],
                    'is_new_account' => $admin_info['is_new_account'],
                    )
                );
                $update_info = array(
                    'admin_id' => $admin_info['admin_id'],
                    'admin_login_num' => ($admin_info['admin_login_num'] + 1),
                    'admin_login_time' => time()
                );

                $result2 = $this->adminService->updateAdminByAdminId($update_info);
                $_SESSION[SessionConst::adminName] = $admin_info['admin_name'];
	            $_SESSION[SessionConst::adminGroupPermissionId] = $admin_info['admin_group_permission_id'];
                //添加登陆日志

                if ($admin_info['is_new_account'] == EjsConst::AdminIsNewAccount) {
                    $this->redirect(Zc::url(YfjRouteConst::initAdminPassword));
                }
                $this->redirect(Zc::url(YfjRouteConst::index));
            } else {
                $this->returnMessageHelper->incomingMessage('用户名或密码错误');
                exit;
            }
        }
    }

    /**
     * 注意 一个function  就是一个 action 哦 ， 然 对应的 page 页面就有该文件名的试图存在，具体参数请参考  render 对象函数中的参数定义。
     *
     */
    public function index()
    {
        $data = [];
        $this->render($data, null, false, 'login');
    }

    public function makeCode()
    {
        $this->CaptchaHelper->CreateImage();
    }


    private function checkSubmit($check_captcha = false)
    {
        $submit = isset($_POST['form_submit']) ? $_POST['form_submit'] : $_GET['form_submit'];
        if ($submit != 'ok') return false;
        if ($check_captcha) {
            if ($this->cookieHelper->get('yfj_admin_captchaCode') !== $_POST['captcha']) return -1;
            $this->cookieHelper->delete('yfj_admin_captchaCode');
        }
        $this->cookieHelper->delete('yfj_admin_captchaCode');
        return true;
    }
}

?>