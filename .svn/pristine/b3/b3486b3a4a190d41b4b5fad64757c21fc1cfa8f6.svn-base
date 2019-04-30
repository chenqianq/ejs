<?php

/**
 * 这个是过滤器
 * @author Administrator
 *
 */
class AuthController extends ZcController{
	/**
	 *
	 * @var SystemService
	 */
	private $systemService;

	private $urlHelper;
	private $curHelper;

	private $bUserService;

	private $requestTime;
	private $cookieHelper;

	public function __construct($route) {
		parent::__construct($route);
		$this -> systemService = ServiceFactory::getSystemService();

        $this->urlHelper = HelperFactory::getUrlHelper();
        $this->curlHelper = HelperFactory::getCurlHelper();
        $this->cookieHelper = HelperFactory::getCookieHelper();
        $this->codeMessageHelper = HelperFactory::getCodeMessageHelper();

		$this -> bUserService = ServiceFactory::getBUserService();

		$this->requestTime = time();

	}

	public function index() {

        // if (!strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')) {
         //    exit;
        // }
        // var_dump($_SESSION);
        // var_dump($_GET);
        // $this->checkUserLogin();
//	    $this -> getSystemSetting();

	}

    private function checkUserLogin()
    {
        $route = $this -> urlHelper -> getValue('route');
        // var_dump($route);

        // 允许直接访问路径
        $allowRouteArray = [
            YfjRouteConst::getWxUserLogin,
            YfjRouteConst::getWxAppError
        ];
	    $allowRouteArrayTwo = [];
	    //大驼峰和下划线的装换
        foreach ($allowRouteArray as $value){
	        $allowRouteArrayTwo[] = strtolower(preg_replace('/(?<=[a-z])([A-Z])/', '_$1', $value));
        }
        
        // 小程序内嵌页面路径
        $wxAppWebViewRouteArray = [
            YfjRouteConst::winningGameIndex,
            YfjRouteConst::getWinningGameResult,
            YfjRouteConst::updateUserWinningReceiveInfo,
            YfjRouteConst::userWinningList,
        ];
        // var_dump($wxAppWebViewRouteArray);

        // 针对小程序访问的用户,进行openid确认
        if (!in_array($route,$allowRouteArray) && !in_array($route,$allowRouteArrayTwo) && !in_array($route, $wxAppWebViewRouteArray) && (!$_SESSION['openid'] || !$_SESSION['session_key'] || !$_SESSION[SessionConst::bWxBuyerid])) {
            $output = $this -> codeMessageHelper -> getCodeMessageByCode(ErrorCodeConst::needLoginDataCode, $this->requestTime);
            $this->renderJSONAPI($output);
        }

        // 小程序访问的webView验证,网页内容
        if (in_array($route,$wxAppWebViewRouteArray)) {

            // 进行环境的基本验证
            // //MicroMessenger 是android/iphone版微信所带的
            // //Windows Phone 是winphone版微信带的 (这个标识会误伤winphone普通浏览器的访问)
            $ua = $_SERVER['HTTP_USER_AGENT'];
            if(strpos($ua, 'MicroMessenger') == false && strpos($ua, 'Windows Phone') == false){
                $this->renderJSON(['status'=>'failed', '请使用微信浏览器打开该页面']);
            }

            $openid = $this->urlHelper->getValue('openid');
            if (!$openid) {
                $this->renderJSON(['status'=>'failed', '未获取用户的openid,请重试']);
            }


            // $wxNickName = $this->urlHelper->getValue('nick_name');
            // $wxAvatarUrl = $this->urlHelper->getValue('avatar_url');
            // if (!$wxNickName || !$wxAvatarUrl) {
            //     $this->render(['status'=>'failed', '未获取用户的微信信息,请重试']);
            // }
        }

    }

    /**
     * 用户访问小程序授权登录获取用户信息
     * 存储用户openid到session中
     */
    private function getWxUserLogin()
    {

        $data = json_encode([
            'code' => '023Brk0k0s4Iim11itWj0Iqd0k0Brk0X',
        ]);
        $data = $this->urlHelper->getValue('data');
        $dataArray = json_decode($data, true);

        if (!$dataArray || !$dataArray['code']) {
            $output = $this -> codeMessageHelper -> getCodeMessageByCode(ErrorCodeConst::needLoginDataCode, $this->requestTime);
            $this->renderJSONAPI($output);
        }

        $setParam = [];
        $setParam['appid'] = 'wx68d0a7a0f21c3c85';
        $setParam['secret'] = 'e192f4ab826b2418cbdf2de19c3399f7';
        $setParam['js_code'] = $dataArray['code'];
        $setParam['grant_type'] = 'authorization_code';
        $url = 'https://api.weixin.qq.com/sns/jscode2session';

        $resultJson = $this->curlHelper->get($url, $setParam);
        $resultData = json_decode($resultJson, true);
        // var_dump($resultJson);exit;
        if (isset($resultData['openid'])) {
            $userData = [];
            $userData['openid'] = $resultData['openid'];
            $bWxUserInfo = $this->bUserService->getBWxUserInfoByOpenid($userData['openid']);

        } else {
            $output = $this -> codeMessageHelper -> getCodeMessageByCode(ErrorCodeConst::getUserOpenidFailed, $this->requestTime);
            $this->renderJSONAPI($output);
        }

        if (!$bWxUserInfo) {
            $userData['gmt_create'] = date('Y-m-d');
            $userData['gmt_modified'] = date('Y-m-d');
            $bWxUserId = $this->bUserService->insertBWxUserInfo($userData);
            if (!$bWxUserId) {
                $output = $this -> codeMessageHelper -> getCodeMessageByCode(ErrorCodeConst::updateUserInfoFailed, $this->requestTime);
                $this->renderJSONAPI($output);
            }
        } else {
            $bWxUserId = $bWxUserInfo['b_wx_user_id'];
        }

        $_SESSION['openid'] = $resultData['openid'];
        $_SESSION['session_key'] = $resultData['session_key'];
        $_SESSION[SessionConst::bWxBuyerid] = $bWxUserId;

        $returnData = [];
        $returnData['session_id'] = session_id();

        $output = $this -> codeMessageHelper -> getCodeMessageByCode(AppConst::requestSuccess, $this->requestTime, $returnData);
        $this->renderJSONAPI($output);
    }

	private function getCurrentUrl()
	{
	    $url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	    if (strpos($url, '?') > -1) {
	        $url = substr($url, 0, strpos($url, '?'));
	    }
	    return $url;
	}

	private function getSystemSetting(){
	    $this -> systemService -> getAllSetting();
	}
	/**
	 * 获得小程序的错误报告
	 */
	public function getWxAppError(){
		$errorMsg = $this -> urlHelper -> getValue('err');
		if(!$errorMsg){
			$output = $this -> codeMessageHelper -> getCodeMessageByCode(ErrorCodeConst::customizeMsg, $this->requestTime,'未收到错误信息');
			$this->renderJSONAPI($output);
		}
		
		$res = $this -> bUserService -> saveWxAppError($errorMsg);
		if($res){
			$output = $this -> codeMessageHelper -> getCodeMessageByCode(ErrorCodeConst::customizeMsg, $this->requestTime,'插入成功');
			$this->renderJSONAPI($output);
		}
	}

}