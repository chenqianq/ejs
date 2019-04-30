<?php

/**
 * 这个是过滤器
 * @author Administrator
 *
 */
class AuthController extends ZcController{

	private $urlHelper;

	/**
     * @var AdminService
     */
	private $adminService;

	private $adminLimit;

	private $adminInfo;

	private $redisCache;

	private $allHaveLimits; // 不需登录,可以直接访问地址列表
	private $allHaveAuthorize; // 需要登陆,所有人都有权限地址列表

	/**
	 * 
	 * @var SystemService
	 */
	private $systemService;

    /**
     * @var Cookie
     */
    private $cookieHelper;

    private $notFilterIpRoute;

	public function __construct($route)
    {
        parent::__construct($route);

        $this->urlHelper = HelperFactory::getUrlHelper();

        $this->adminService = ServiceFactory::getAdminService();

        $this->systemService = ServiceFactory::getSystemService();

        $this->adminLimit = AdminLimit::$limit;

        $this->adminInfo = $this->adminService->getAdminInfo();

        $this->redisCache = CacheFactory::getRedisCache();

        $this->notFilterIpRoute = array(
            YfjRouteConst::putIp,
        );

        $this->allHaveLimits = [
            YfjRouteConst::index,
            YfjRouteConst::loginOut,
            YfjRouteConst::login,
            YfjRouteConst::makeCode,
        ];

        // 需要登陆,所有人都有权限地址列表
        $this->allHaveAuthorize = [
            YfjRouteConst::initAdminPassword
        ];
        $this->cookieHelper = HelperFactory::getCookieHelper();
        if (!$this->cookieHelper->is_set('sys_key') && !stristr($this->urlHelper->getValue('route'), 'login') && !in_array($this->urlHelper->getValue('route'), $this->allHaveLimits)) {
            $this->redirect(Zc::url(YfjRouteConst::login));
            exit;
        }
        $this->systemsMgLog();
    }
	
	public function index() {

		$route = $this->urlHelper->getValue('route');
		
		if(!$route){
			return false;
		}
		
		if( G_CURRENT_DOAMIN_CONST_NAME == 'G_ADMIN_YIFANJIE_COM_DOMAIN' ){
		    if( $_SERVER['SERVER_PORT'] != 443 ){
		        $this -> redirect('https://' . G_CURRENT_DOAMIN . $_SERVER['REQUEST_URI']  );
		        EXIT;
		    }
		}

        if ( !in_array( $route , $this -> notFilterIpRoute ) && $_SERVER['SERVER_PORT'] != 443 ) {
            if ( HelperFactory::getIpHelper() -> filterIp() ) {
                //$this -> redirect('http://' . G_CURRENT_DOAMIN );
                //exit;
            }
        }

		$rs = $this -> checkAccess($this->adminInfo,$route);

		if($rs === false){
			die('该页面不存在或者你没有权限访问该页面， 有问题请联系管理员');
		}
        $this -> getSystemSetting();
       
	}

	private function checkAccess($admin,$route){


		if(in_array($route,$this ->allHaveLimits) || in_array($route, $this->allHaveAuthorize)){
			return true;
		}

		if($admin['sp'] != '1'){
			$permissionArray = $this -> adminService -> getAdminPermissionListByAdminGroupId($admin['adminGid']);
		}else{
			return true;

		}

		$routeArray = array_column($permissionArray,'admin_permission_name');

		if(!in_array($route,$routeArray)){
			return false;
		}
		return true;
	}

    /**
     * 系统管理日志
     */
    private function systemsMgLog()
    {
        $param = $_POST;
        if (empty($param)) {
            $request_type = "GET";
        } else {
            $request_type = "POST";
        }
        $content = serialize($param);
        $adminInfo = $this->adminInfo;
        if (empty($adminInfo)) {
            return false;
        }
        if(!isset($_SESSION[SessionConst::adminName])) {
            $_SESSION[SessionConst::adminName] = $adminInfo['name'];
        }
        if ($this->urlHelper->getCurrentUrlInfo()) {
            $url = $this->urlHelper->getCurrentUrlInfo()['uri'];
        } else {
            return false;
        }
        $ip = Ip::clientIp();
        $logArr = [
            'content' => $content,
            'gmt_create' => date('Y-m-d H:i:s', time()),
            'admin_name' => $adminInfo['name'],
            'admin_id' => $adminInfo['id'],
            'ip' => $ip,
            'route' => $url,
            'request_type' => $request_type,
        ];
        $this->adminService->insertSystemMgLog($logArr);
    }
    
    private function getSystemSetting(){
        $this -> systemService -> getAllSetting();
    }

}
?>