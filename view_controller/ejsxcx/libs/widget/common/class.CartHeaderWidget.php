<?php
class CartHeaderWidget extends ZcWidget {
	
    private $userService;
    private $shoppingCartService;
	
    public function __construct(){
        $this -> userService = ServiceFactory::getUserService();
        $ssid = ZcSession::getSessionId();
        $cartType = 'ds';
        $customersID = $_SESSION[SessionConst::customerId];
        $this -> shoppingCartService = ServiceFactory::getShoppingCartService($ssid,$customersID,$cartType);
    }
    
	public function render($data = '') {
        $data['getToken'] = $this ->getToken();
        $data['getNchash'] = $this -> getNchash('login','index');
        // $data['userBaseMessageArray'] = $this -> userService -> getUserBaseMessageByUid($_SESSION['member_id']);
	    // $data['userBaseMessageArray'] = $this -> userService -> getShowNameByUid($_SESSION['member_id']);
        $data['cartQty'] = $this -> shoppingCartService -> getCartQty();
        $data['memberInfo'] = $this -> userService -> getShowNameByUid($_SESSION['member_id']);
		return $this->renderFile('common/cart_header', $data);
	}

    /**
     * 取得令牌内容
     * 自动输出html 隐藏域
     *
     * @param
     * @return string 字符串形式的返回结果
     */
    private function getToken(){
        $token = HelperFactory::getEncryptionHelper()->encrypt(time(),md5(MD5_KEY));
        return "<input type='hidden' name='formhash' value='". $token ."' />";

    }

    /**
     * 取验证码hash值
     *
     * @param
     * @return string 字符串类型的返回结果
     */
    private function getNchash($act = '', $op = ''){
        $act = $act ? $act : $_GET['act'];
        $op = $op ? $op : $_GET['op'];
        return substr(md5(Zc::C('shop.site.url').$act.$op),0,8);
    }
}