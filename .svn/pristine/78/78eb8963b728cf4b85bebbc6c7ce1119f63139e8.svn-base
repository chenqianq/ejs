<?php
 
class alipay{
	/**
	 *支付宝网关地址（新）
	 */
	private $alipay_gateway_new = 'https://mapi.alipay.com/gateway.do?';
	/**
	 * 消息验证地址
	 *
	 * @var string
	 */
	private $alipay_verify_url = 'https://mapi.alipay.com/gateway.do?service=notify_verify&';
	/**
	 * 支付接口标识
	 *
	 * @var string
	 */
    private $code      = 'alipay';
    /**
	 * 支付接口配置信息
	 *
	 * @var array
	 */
    private $paymentConfig;
     /**
	 * 订单信息
	 *
	 * @var array
	 */
    private $order;
    /**
	 * 发送至支付宝的参数
	 *
	 * @var array
	 */
    private $parameter;
    /**
     * 订单类型
     * @var unknown
     */
    private $order_type;
    
    private $inputCharset;

    public function __construct($paymentInfo = array(),$paySn = '', $orderType = 'co'){
    	if (!extension_loaded('openssl')) {
    	    $this->alipay_verify_url = 'http://notify.alipay.com/trade/notify_query.do?';
    	}
    	
    	$this -> inputCharset = 'UTF-8';
    	if( !empty($paymentInfo) ){
    	    $this -> paymentConfig	= $paymentInfo; 
    	}
    	
    	if( !empty($paymentInfo) && !empty($paySn) ){ 
    		if( $paySn ){
    		    //$this -> order	= ServiceFactory::getOrderService() -> getMainOrdersByPaySn($paySn);
    		}
    		
    		if ($orderType == 'ds') {
    		    $this->order = ServiceFactory::getOrderService()->getMergePayment($paySn); // b端合并支付信息
    		    $this->order['notify_url'] = Zc::url(YfjRouteConst::alipayNotify);
    		    $this->order['return_url'] = Zc::url(YfjRouteConst::alipayReturn);
    		} else if($orderType == 'lock') { 
                $this->order = ServiceFactory::getLockOrderService()->getLockOrderByPaySn($paySn);//锁定商品支付信息
                $this->order['notify_url'] = Zc::url(YfjRouteConst::alipayLockNotify);
    		    $this->order['return_url'] = Zc::url(YfjRouteConst::alipayLockReturn);
    		    $this -> order['order_amount'] = 0.01;
            } else {
    		    // $this->order = ServiceFactory::getOrderService()->getMainOrdersByPaySn($paySn);
    		    // if ( !$this->order ) {
    		    //     $this->order = ServiceFactory::getOrderService() -> getOrdersByPaySn($paySn);
    		    // }
                $this->order = ServiceFactory::getOrderService()->getMergePayment($paySn); // c端合并支付信息
    		}
    		
    		//$this -> order['order_amount'] = 0.01;
    		$this->orderType = $orderType;
    	}
    }
    
	private function createLinkstrings($para) {
		ksort($para);  
        reset($para); 
		$arg  = "";
		while (list ($key, $val) = each ($para)) {
			$arg.=$key."=".$val."&";
		}
		//去掉最后一个&字符
		$arg = substr($arg,0,-1);

		//如果存在转义字符，那么去掉转义
		if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}

		return $arg;
	}
	private function paraFilter($para) {
		$para_filter = array();
		while (list ($key, $val) = each ($para)) {
			if( ($key == "sign"  || $val == "") )continue;
			else	$para_filter[$key] = $para[$key];
		}
			return $para_filter;
	}

	public function getAppParamsbak(){
	    $param['app_id'] = '2017022705930571';
	    $param['method'] = 'alipay.trade.app.pay';
	    $param['format'] = 'JSON';
	    $param['charset'] = $this->inputCharset;
	    $param['sign_type'] = 'RSA2';
	    $param['timestamp'] = date('Y-m-d H:i:s');
	    $param['version'] = '1.0';
	    $param['notify_url'] = Zc::url(YfjRouteConst::alipayNotifyApp);
	    $param['biz_content'] = '';//
	    
	    $serviceParameters['body'] = $this->order['pay_sn'];
	    $serviceParameters['subject'] = '一番街订单：' . $this->order['pay_sn'];
	    $serviceParameters['out_trade_no'] = $this -> order['pay_sn'];
	    $serviceParameters['total_amount'] = $this -> order['order_amount'];//$this -> order['order_amount'];
	    $serviceParameters['product_code'] = 'QUICK_MSECURITY_PAY';
	    
	    $appVersion = "0.0.0";
	    if($_SERVER['HTTP_APPVERSION']) {
	        $appVersion = $_SERVER['HTTP_APPVERSION'];
	    }
	    
	    $orderClient = G_CURRENT_DOAMIN_CONST_NAME;
	    $serviceParameters["passback_params"] = json_encode(['order_type'=>$this->orderType,"app_version"=>$appVersion,"order_client"=>$orderClient]);                           //附加字段   选填
	    	  
	    //$serviceParameters['passback_params'] = 'RSA2';///返回给商户的信息
	    $serviceParameters['enable_pay_channels'] = 'balance,moneyFund,pcredit,creditCard,creditCardExpress,credit_group,debitCardExpress,bankPay';///返回给商户的信息
	    $param['biz_content'] = json_encode($serviceParameters);

        $str = $this -> createLinkstrings( $this -> paraFilter($param) ); //待签名字符串
		//var_dump(htmlspecialchars($str)); 
		//echo '<br><br>';
	    require_once DIR_FS_DOCUMENT_ROOT . '/classes/payment/AppAlipay/lib/alipay_rsa.function.php';
	    $param['sign'] = (rsaSign($str, DIR_FS_DOCUMENT_ROOT . '/classes/payment/AppAlipay/key/rsa_private_key.pem','RSA2'));

		//var_dump ($param['sign']);exit;
		$ss = rsaVerify($str, DIR_FS_DOCUMENT_ROOT . '/classes/payment/AppAlipay/key/alipay_public_key.pem',$param['sign'],'RSA2');

		//$param['sign'] = urlencode(md5($str.$this -> paymentConfig['alipay_key']));
		$arg  = "";
		while (list ($key, $val) = each ($param)) {
			$arg.=$key."=".urlencode($val)."&";
		}
		
		$arg = substr($arg, 0,-1);
	    $str = $this -> createLinkstrings($param);
		 
	    return $arg;
	}
	/**
	 * 
	 */
    public function getAppParamsbak1(){
		$param['service'] = 'mobile.securitypay.pay';
		$param['partner'] = $this->paymentConfig['alipay_partner'];
		$param['_input_charset'] = $this->inputCharset;
		$param['sign_type'] ='RSA';
		$param['sign'] = '';
		$param['notify_url'] = Zc::url(YfjRouteConst::alipayNotifyApp);
//            $param['app_id'] = '';
//            $param['appenv'] = '';
		$param['out_trade_no'] = $this -> order['pay_sn'];
		$param['subject'] = '一番街订单：' . $this->order['order_sn'];
		$param['payment_type'] = 1;
		$param['seller_id'] = $this -> paymentConfig['alipay_partner'];
		$param['total_fee'] = $this -> order['order_amount'];
		$param['body'] = $this->order['pay_sn'];
//            $param['it_b_pay'] = '';
//            $param['extern_token'] = '';
		$str = $this -> createLinkstrings($this->paraFilter($param)); //待签名字符串
		require_once DIR_FS_DOCUMENT_ROOT . '/classes/payment/AppAlipay/lib/alipay_rsa.function.php';
		$param['sign'] = urlencode(rsaSign($str, DIR_FS_DOCUMENT_ROOT . '/classes/payment/AppAlipay/key/rsa_private_key.pem'));
		//$param['sign'] = urlencode(md5($str.$this -> paymentConfig['alipay_key']));
		$str = $this->createLinkstrings($param);
		return $str;
	}
	
	public function getAppParams(){
		$param['service'] = 'mobile.securitypay.pay';
		$param['partner'] =$this->paymentConfig['alipay_partner'];
		$param['_input_charset'] = $this->inputCharset;
		$param['sign_type'] ='RSA';
		$param['sign'] = '';
		$param['notify_url'] = Zc::url(YfjRouteConst::alipayNotifyApp);
//            $param['app_id'] = '';
//            $param['appenv'] = '';
		$param['out_trade_no'] = $this -> order['pay_sn'];
		$param['subject'] = '一番街订单：' . $this->order['pay_sn'];
		$param['payment_type'] = 1;
		$param['seller_id'] = $this -> paymentConfig['alipay_partner'];
		$param['total_fee'] = $this -> order['order_amount'];
		$param['body'] = $this->order['pay_sn'];
		$str =$param;
		return $str;
	}
    /**
     * 获取支付接口的请求地址
     *
     * @return string
     */
    public function getPayurl(){
        $isMobile = HelperFactory::getIpHelper() -> isMobile();
        
        if( $isMobile ){
            $this->parameter = array(
                'service'		    => 'alipay.wap.create.direct.pay.by.user',	//服务名
                'partner'		    => $this -> paymentConfig['alipay_partner'],	//合作伙伴ID
                //'key'               => $this -> paymentConfig['alipay_key'],
                
                '_input_charset'	=> $this -> inputCharset,					//网站编码
                'sign_type'		    => 'MD5',				//签名方式
                'notify_url'	    => Zc::url(YfjRouteConst::alipayNotify),	//通知URL
                'return_url'	    => Zc::url(YfjRouteConst::alipayReturn),	//返回URL
                
                'out_trade_no'	    => $this -> order['pay_sn'],		//外部交易编号
                'subject'		    => '一番街订单：' . $this->order['pay_sn'],	//商品名称
                'total_fee'         => $this -> order['order_amount'],//,//该笔订单的资金总额
                'seller_id' => $this -> paymentConfig['alipay_partner'],//卖家支付宝账号对应的支付宝唯一用户号。
                'payment_type'	    => 1,//支付类型。仅支持：1（商品购买）。
                'show_url' => Zc::url(YfjRouteConst::pay,'pay_sn='.$this->order['pay_sn']),//用户付款中途退出返回商户网站的地址。
                'body'			    => $this->order['pay_sn'],	//商品描述
                'app_pay' => 'Y',
                'enable_paymethod' => 'balance,moneyFund,pcredit,creditCard,creditCardExpress,debitCardExpress',
               
            );
            
        }
        else{
            $this->parameter = array(
                'service'		    => 'create_direct_pay_by_user',	//服务名
                'partner'		    => $this -> paymentConfig['alipay_partner'],	//合作伙伴ID
                //'key'               => $this -> paymentConfig['alipay_key'],
                '_input_charset'	=> $this -> inputCharset,					//网站编码
                'notify_url'	    => $this->order['notify_url'],	//通知URL
                'sign_type'		    => 'MD5',				//签名方式
                'return_url'	    => $this->order['return_url'],	//返回URL
                
                'out_trade_no'	    => $this -> order['pay_sn'],		//外部交易编号 
                'subject'		    => '一番街订单：' . $this->order['pay_sn'],	//商品名称
                'payment_type'	    => 1,							//支付类型
                'total_fee'         => $this -> order['order_amount'],//$this -> order['order_amount'],//物流配送费用
                'seller_id' => $this -> paymentConfig['alipay_partner'],
                'seller_email'		=> $this -> paymentConfig['alipay_account'],	//卖家邮箱
                //'seller_account_name' => ,
                //'price'             => $this -> order['order_amount'],//订单总价
                //'quantity'          => 1,//商品数量
                'body'			    => $this->order['pay_sn'],	//商品描述
                'enable_paymethod' => 'directPay^bankPay^creditCardExpress^debitCardExpress^',
                
                'extra_common_param'=> $this -> order['pay_sn'],  //回传的数据
                
               /*  'logistics_type'    => 'EXPRESS',					//物流配送方式：POST(平邮)、EMS(EMS)、EXPRESS(其他快递)
                'logistics_payment'	=> 'BUYER_PAY',				     //物流费用付款方式：SELLER_PAY(卖家支付)、BUYER_PAY(买家支付)、BUYER_PAY_AFTER_RECEIVE(货到付款)
                'receive_name'		=> $_SESSION[SessionConst::userName],//收货人姓名
                'receive_address'	=> 'N',	//收货人地址
                'receive_zip'		=> 'N',	//收货人邮编
                'receive_phone'		=> 'N',//收货人电话
                'receive_mobile'	=> 'N',//收货人手机
                'extend_param'      => "isv^sh32", */
            );
        }
        //var_dump($this->parameter);exit;
        $this->parameter['sign']	= $this->sign($this->parameter);
        //var_dump($this->parameter);exit;
        return $this->create_url();
    }


	/**
	 * 通知地址验证
	 *
	 * @return bool
	 */
	public function notify_verify($param) {
	    unset($param['route']);
		//$param['key']	= $this -> paymentConfig['alipay_key'];
		$veryfy_url = $this->alipay_verify_url. "partner=" .$this -> paymentConfig['alipay_partner']. "&notify_id=".$param["notify_id"];
		$veryfy_result  = $this->getHttpResponse($veryfy_url);
		$sign = $param["sign"];
		unset($param["sign"]);
		$mysign = $this->sign($param);//preg_match("/true$/i",$veryfy_result) && 
		if ($mysign == $sign)  {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 返回地址验证
	 *
	 * @return bool
	 */
	public function return_verify($param) { 
		unset($param['route']);
		//$param['key']	= $this -> paymentConfig['alipay_key'];
		$veryfy_url = $this->alipay_verify_url. "partner=" .$this -> paymentConfig['alipay_partner']. "&notify_id=".mb_convert_encoding($param["notify_id"], $this -> inputCharset);
		/* $urlarr     = parse_url($veryfy_url);
		  
		$params['port'] = 443;
		$params['ssl'] = true; 
		$params['userAgent'] .= 'Content-type: application/x-www-form-urlencoded\r\n"';
		$params['userAgent'] .= "Content-length: ".strlen($urlarr["query"])."\r\n";
		$params['userAgent'] .= 'Connection: close\r\n\r\n'; */
		/* fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
		fputs($fp, "Content-length: ".strlen($urlarr["query"])."\r\n");
		fputs($fp, "Connection: close\r\n\r\n"); */
		
	/* 	$curlHelper = HelperFactory::getCurlHelper($params);
		$data = $curlHelper -> post($veryfy_url);  */
		$veryfy_result  = $this->getHttpResponse($veryfy_url);
		$sign = $param["sign"];
		unset($param["sign"]);
        
		$mysign = $this->sign($param);//preg_match("/true$/i",$veryfy_result) && 
		// var_dump($veryfy_result,$veryfy_url,$mysign,$sign);exit;
		if ( $mysign == $sign )  {
            return true;
		} else {
			return false;
		}
	}

	/**
	 * 
	 * 取得订单支付状态，成功或失败
	 * @param array $param
	 * @return array
	 */
	public function getPayResult($param){
		return $param['trade_status'] == 'TRADE_SUCCESS';
	}

	/**
	 * 
	 *
	 * @param string $name
	 * @return 
	 */
	public function __get($name){
	    return $this->$name;
	}

	/**
	 * 远程获取数据
	 * $url 指定URL完整路径地址
	 * @param $time_out 超时时间。默认值：60
	 * return 远程输出的数据
	 */
	private function getHttpResponse($url,$time_out = "60") {
		$urlarr     = parse_url($url);
		$errno      = "";
		$errstr     = "";
		$transports = "";
		$responseText = "";
		if($urlarr["scheme"] == "https") {
			$transports = "ssl://";
			$urlarr["port"] = "443";
		} else {
			$transports = "tcp://";
			$urlarr["port"] = "80";
		}
		$fp=@fsockopen($transports . $urlarr['host'],$urlarr['port'],$errno,$errstr,$time_out);
		
		if(!$fp) {
			die("ERROR: $errno - $errstr<br />\n");
		} else {
			if (trim(CHARSET) == '') {
				fputs($fp, "POST ".$urlarr["path"]." HTTP/1.1\r\n");
			} else {
				fputs($fp, "POST ".$urlarr["path"].'?_input_charset='.$this -> inputCharset." HTTP/1.1\r\n");
			}
			fputs($fp, "Host: ".$urlarr["host"]."\r\n");
			fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
			fputs($fp, "Content-length: ".strlen($urlarr["query"])."\r\n");
			fputs($fp, "Connection: close\r\n\r\n");
			fputs($fp, $urlarr["query"] . "\r\n\r\n");
			while(!feof($fp)) {
				$responseText .= @fgets($fp, 1024);
			}
			fclose($fp);
			$responseText = trim(stristr($responseText,"\r\n\r\n"),"\r\n");
			return $responseText;
		}
	}

    /**
     * 制作支付接口的请求地址
     *
     * @return string
     */
    private function create_url() {
		$url        = $this->alipay_gateway_new;
		$filtered_array	= $this->para_filter($this->parameter);
		$sort_array = $this->arg_sort($filtered_array);
		$arg        = "";
		while (list ($key, $val) = each ($sort_array)) {
			$arg.=$key."=".urlencode($val)."&";
		}
		$url.= $arg."sign=" .$this->parameter['sign'] ."&sign_type=".$this->parameter['sign_type'];
		
		return $url;
	}

	/**
	 * 取得支付宝签名
	 *
	 * @return string
	 */
	private function sign($parameter) {
		$mysign = "";
		
		$filtered_array	= $this->para_filter($parameter);
		$sort_array = $this->arg_sort($filtered_array);
		$arg = "";
        while (list ($key, $val) = each ($sort_array)) {
			$arg	.= $key."=".$this->charset_encode($val,(empty($parameter['_input_charset'])?"UTF-8":$parameter['_input_charset']),(empty($parameter['_input_charset'])?"UTF-8":$parameter['_input_charset']))."&";
		}
		//var_dump($arg);exit;
		$prestr = substr($arg,0,-1);  //去掉最后一个&号
		//$prestr	.= $parameter['key'];
        if($parameter['sign_type'] == 'MD5') {
			$mysign = md5($prestr.$this -> paymentConfig['alipay_key']);
		}elseif($parameter['sign_type'] =='DSA') {
			//DSA 签名方法待后续开发
			die("DSA 签名方法待后续开发，请先使用MD5签名方式");
		}else {
			die("支付宝暂不支持".$parameter['sign_type']."类型的签名方式");
		}
		return $mysign;

	}

	/**
	 * 除去数组中的空值和签名模式
	 *
	 * @param array $parameter
	 * @return array
	 */
	private function para_filter($parameter) {
		$para = array();
		while (list ($key, $val) = each ($parameter)) {
			if($key == "sign" || $key == "sign_type" || $key == "key" || $val == "")continue;
			else	$para[$key] = $parameter[$key];
		}
		return $para;
	}

	/**
	 * 重新排序参数数组
	 *
	 * @param array $array
	 * @return array
	 */
	private function arg_sort($array) {
		ksort($array);
		reset($array);
		return $array;

	}

	/**
	 * 实现多种字符编码方式
	 */
	private function charset_encode($input,$_output_charset,$_input_charset="UTF-8") {
		$output = "";
		if(!isset($_output_charset))$_output_charset  = $this->parameter['_input_charset'];
		if($_input_charset == $_output_charset || $input == null) {
			$output = $input;
		} elseif (function_exists("mb_convert_encoding")){
			$output = mb_convert_encoding($input,$_output_charset,$_input_charset);
		} elseif(function_exists("iconv")) {
			$output = iconv($_input_charset,$_output_charset,$input);
		} else die("sorry, you have no libs support for charset change.");
		return $output;
	}
}