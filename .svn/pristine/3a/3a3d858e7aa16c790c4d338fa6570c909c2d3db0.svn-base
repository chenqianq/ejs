<?php
/**
 *首页
 *
 *一个public 就是一个action 
 *
 *helper 是工具类 这个可以全局去使用，采用的都是工厂单体模式。
 */
class HomeController extends ZcController{

	private $cookieHelper;
	/**
	 * 构造函数
	 *
	 * @param string $route
	 *        	router
	 */
	public function __construct($route) {
		parent::__construct ( $route );

		$this -> cookieHelper = HelperFactory::getCookieHelper();
		if(!$this->cookieHelper->is_set('sys_key')){
			$this -> redirect(Zc::url(YfjRouteConst::login));
			exit;
		}
	}
	
	/**
	 * 注意 一个function  就是一个 action 哦 ， 然 对应的 page 页面就有该文件名的试图存在，具体参数请参考  render 对象函数中的参数定义。
	 * 
	 */
	public function index () {
		$data = [];
		$this->render ($data,null,false,"init");
	}
	
	public function loginOut()
    {
        setcookie("now_location_nav", "", time() - 1);
        setcookie("now_location_act", "", time() - 1);
        setcookie("now_location_op", "", time() - 1);
        unset($_SESSION['isEditor']);
        unset($_SESSION['modify_presale_status_permission']);
        $this->cookieHelper->delete('sys_key');
        $this->redirect(Zc::url(YfjRouteConst::login));
        exit;
    }
	
}

?>