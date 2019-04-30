<?php
/**
 * 微信端支付类
 * @author Administrator
 *
 */
class wxpay
{

    var $parameters; // cft 参数
    var $payment; // 配置信息
    private $paymentConfig;
    
    private $notifyUrl;
    
    private $order;
     
    
    static  $wxPayDebug = false; 

    private $tradeType; // 支付类型  JSAPI--公众号支付、NATIVE--原生扫码支付、APP--app支付

    private $orderType; // co:C端訂單,dsB端訂單,lock b端锁定商品订单
    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function __construct($paymentConfig,$paySn = '', $orderType = 'co', $tradeType = 'NATIVE')
    {
       $this -> notifyUrl = Zc::url(YfjRouteConst::wxPayNotify);
       $this -> tradeType = $tradeType;
       $this -> orderType = $orderType;
       if( $paymentConfig ){
           $this -> paymentConfig = $paymentConfig;
       }
      
       if( !empty($paymentConfig) && !empty($paySn) ){ 
            if ( $this -> orderType == 'ds' ) {
                
                $this -> order = ServiceFactory::getOrderService() -> getMergePayment($paySn); // b端合并支付信息
                
               
            } else if($orderType == 'lock') {

                $this -> notifyUrl = Zc::url(YfjRouteConst::wxLockPayNotify);


                $this -> order = ServiceFactory::getLockOrderService() -> getLockOrderByPaySn($paySn);

            } else {

                // $this -> order	= ServiceFactory::getOrderService() -> getMainOrdersByPaySn($paySn);

                $this -> order = ServiceFactory::getOrderService() -> getMergePayment($paySn); // c端合并支付信息

            }
       }
     
    }

    /**
     * 生成支付代码
     * @param unknown $order
     * @param unknown $payment
     * @return string
     */
    function getCode($openId = '')
    { 
        $charset = 'utf-8';
        $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
        $js = '';
        ///非微信端支付
        if( !preg_match('/micromessenger/', $ua)){
            /* $js .= '<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>';
            $this->setParameter("openid", 'o3X7NwKxmrnm2rvR6kLiCO7I5P_s');
             */
            ///return '<div class="pay-btn"><a class="sub-btn btnRadius" type="button" disabled>'.$GLOBALS['_LANG']["wxpay_not_wx_button"].'</a></div>';
            // $this->setParameter("trade_type", "NATIVE"); // 交易类型

            $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
            // var_dump($this -> notifyUrl);exit;
            $timeStamp = time();
            $apiObj = array();
            $apiObj["appid"] = $this -> paymentConfig['appid']; // 公众账号ID
            $apiObj["mch_id"] = $this -> paymentConfig['mchid']; // 商户号
            $apiObj["nonce_str"] = $this->createNoncestr();
            $apiObj["body"] = $this -> order['pay_sn'];
            $appVersion = "0.0.0";
            if($_SERVER['HTTP_APPVERSION']) {
                $appVersion = $_SERVER['HTTP_APPVERSION'];
            }
            
            $orderClient = G_CURRENT_DOAMIN_CONST_NAME;
            $attachArray = json_encode(['order_type'=>$this->orderType,"app_version"=>$appVersion,"order_client"=>$orderClient]);                           //附加字段   选填
            
            
            $apiObj["attach"] = json_encode($attachArray); // 自定义参数
            $apiObj["out_trade_no"] = $this -> order['pay_sn'];
            $apiObj["total_fee"] = $this -> order['order_amount'] * 100;
            $apiObj["spbill_create_ip"] = Ip::clientIp(); // 终端ip
            $apiObj["notify_url"] = $this -> notifyUrl; // 通知地址
            $apiObj["trade_type"] = $this -> tradeType; // 交易类型  JSAPI--公众号支付、NATIVE--原生扫码支付、APP--app支付
            $apiObj["sign"] = $this->getSign($apiObj);
            // print_R($apiObj);exit;
            $xml = $this -> arrayToXml($apiObj);
            // print_r($xml);exit;
            $response = $this->postXmlCurl($xml, $url, 30);
            
            // var_dump($response);exit;
            $result =$this->xmlToArray($response);
           
            return $result;
        } else {
            if( empty($openId) ){
                return false;
            }
            
            $this->setParameter("openid", $openId); // open id
            $this->setParameter("trade_type", "JSAPI"); // 交易类型
        }
          
        //为respond做准备 
        $charset = strtoupper($charset);
        $attachArray = [
            'pay_sn' => $this -> order['pay_sn'],
            'order_type' => $this -> order['order_type'],
        ];
          //var_dump($this -> order);exit;
        $this->setParameter("attach", json_encode($attachArray));
        $this->setParameter("body", $this -> order['pay_sn']); // 商品描述
        $this->setParameter("out_trade_no", $this -> order['pay_sn']); // 商户订单号$this -> order['order_sn'].
        $this->setParameter("total_fee", $this -> order['order_amount'] * 100); // 总金额 $this -> order['order_amount']
        $this->setParameter("notify_url", $this -> notifyUrl); // 通知地址
        
        //$this->setParameter("input_charset", $charset);
        
        $logObj = LogFactory::getBizLog('wx_translation-data' . $this -> order['order_sn']);
        $logObj -> log('=============prepayId data start=================');
        $logObj -> log(print_r($this->parameters,true));
        $logObj -> log('=============prepayId data end=================');
        $prepayId = $this -> getPrepayId();
        $logObj -> log('=============prepayId:'.$prepayId.'=================');
        
        if(empty($prepayId)){
            return false;
        }
        
        $jsApiParameters = $this->getParameters($prepayId); 
         
        $jsApiObj["appId"] = $this -> paymentConfig['appid'];
        $timeStamp = time();
        $jsApiObj["timeStamp"] = "$timeStamp";
        $jsApiObj["nonceStr"] = $this->createNoncestr();
        $jsApiObj["package"] = "prepay_id=$prepayId";
        $jsApiObj["signType"] = "MD5";
        $jsApiObj["paySign"] = $this->getSign($jsApiObj);
        $logObj -> log('=============prepayId:'.$prepayId.' 发起支付=================');
        $logObj -> log(print_r($jsApiObj,true));
        $js .= '
            <script type="text/javascript">
                function onBridgeReady(){  
                    WeixinJSBridge.invoke(  
                       \'getBrandWCPayRequest\', {  
                           "appId":"'.$this -> paymentConfig['appid'].'",      
                           "timeStamp":"'.$timeStamp.'",          
                           "nonceStr":"'.$jsApiObj["nonceStr"].'",  
                           "package" : "prepay_id='.$prepayId.'",       
                           "signType" : "MD5",         
                           "paySign" : "'.$jsApiObj["paySign"].'"  
                       },  
                       function(res){       
                          ///alert(res.err_code+res.err_desc+res.err_msg);
                          //alert( res.err_msg);
                           if(res.err_msg == "get_brand_wcpay_request:ok" ) {
                               window.location.href="'.Zc::url(YfjRouteConst::success,'order_sn='.$this -> order['order_sn']).'";
                           }
                           else if(res.err_msg == "get_brand_wcpay_request:cancel" ) {
                               window.location.href="'.Zc::url(YfjRouteConst::pay,'pay_sn='.$this -> order['pay_sn']).'";
                           }
                           else{
                               window.location.href="'.Zc::url(YfjRouteConst::payFailed,'pay_sn='.$this -> order['pay_sn']).'";
                           }
                       }  
                   );   
                }   
                           
                if (typeof WeixinJSBridge == "undefined"){  
                   if( document.addEventListener ){  
                       document.addEventListener(\'WeixinJSBridgeReady\', onBridgeReady, false);  
                   }else if (document.attachEvent){  
                       document.attachEvent(\'WeixinJSBridgeReady\', onBridgeReady);   
                       document.attachEvent(\'onWeixinJSBridgeReady\', onBridgeReady);  
                   }  
                }else{  
                   onBridgeReady();  
                }  
             </script> 
               ';
       
        return $js; 
    }

    /**
     * 生成日志
     * @param string $word
     * @param array $var
     * @return boolean
     */
    public function logResult($word = '',$var=array()) {
         
        
        $output= strftime("%Y%m%d %H:%M:%S", time()) . "\n" ;
        $output .= $word."\n" ;
        if(!empty($var)){
            $output .= print_r($var, true)."\n";
        }
        $output.="\n";

        $log_path= "/data/log/";
        if(!is_dir($log_path)){
            @mkdir($log_path, 0777, true);
        }

        file_put_contents($log_path."wx.txt", $output, FILE_APPEND | LOCK_EX);
    }
 
    /**
     * 响应操作
     * @param unknown $data
     * @return boolean
     */
    public function respond($data) {
         
    }

    /**
     * 将xml转为array
     * @param unknown $xml
     * @return mixed
     */
    public function xmlToArray($xml)
    {
        //将XML转为array
        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $array_data;
    }
    /**
     * array转xml
     * @param unknown $arr
     * @return string
     */
    public function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key=>$val)
        {
            if (is_numeric($val))
            {
                $xml.="<".$key.">".$val."</".$key.">";

            }
            else
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
        }
        $xml.="</xml>";
        return $xml;
    }

    /**
     * 
     * @param unknown $value
     */
    private function trimString($value)
    {
        $ret = null;
        if (null != $value) {
            $ret = $value;
            if (strlen($ret) == 0) {
                $ret = null;
            }
        }
        return $ret;
    }

    /**
     * 生随机字符串，不长于32位
     * @param number $length
     * @return string
     */
    public function createNoncestr($length = 32)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i ++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * 设置请求参数
     * @param unknown $parameter
     * @param unknown $parameterValue
     */
    public function setParameter($parameter, $parameterValue)
    {
        $this->parameters[$this->trimString($parameter)] = $this->trimString($parameterValue);
    }

    /**
     * 生成签名
     * @param unknown $Obj
     * @return string
     */
    public function getSign($Obj)
    {
        foreach ($Obj as $k => $v) {
            $Parameters[$k] = $v;
        }
        // 签名步骤一：按字典序排序参数
        ksort($Parameters);
        $buff = "";
        foreach ($Parameters as $k => $v) {
            $buff .= $k . "=" . $v . "&";
        }
        $String="";
        if (strlen($buff) > 0) {
            $String = substr($buff, 0, strlen($buff) - 1);
        }
        // echo '【string1】'.$String.'</br>';
        // 签名步骤二：在string后加入KEY
        $String = $String . "&key=" . $this -> paymentConfig['key'];
        // print_r($String);exit;
        // echo "【string2】".$String."</br>";
        // 签名步骤三：MD5加密
        $String = md5($String);
        // echo "【string3】 ".$String."</br>";
        // 签名步骤四：所有字符转为大写
        $result_ = strtoupper($String);
        // echo "【result】 ".$result_."</br>";
        return $result_;
    }

    /**
     * 以post方式提交xml到对应的接口url
     * @param unknown $xml
     * @param unknown $url
     * @param number $second
     */
    public function postXmlCurl($xml,$url,$second=30)
    {
        //初始化curl
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        //这里设置代理，如果有的话
        //curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        
        if(!$data){
            $error = curl_errno($ch);
            $this->logResult("error::postXmlCurl::curl出错，错误码:$error,http://curl.haxx.se/libcurl/c/libcurl-errors.html 错误原因查询");

        }
        curl_close($ch);
        return $data;
    }
    
    /**
     * 获取prepay_id
     * @return mixed
     */
    public function getPrepayId()
    {
        // 设置接口链接
        $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
        //var_dump($this->parameters,'ddddd');exit;
        if ($this->parameters["out_trade_no"] == null) {
            return false; 
        } elseif ($this->parameters["body"] == null) {
            return false; 
        } elseif ($this->parameters["total_fee"] == null) {
            return false; 
        } elseif ($this->parameters["notify_url"] == null) {
            return false; 
        } elseif ($this->parameters["trade_type"] == null) {
            return false; 
        } elseif ($this->parameters["trade_type"] == "JSAPI" && $this->parameters["openid"] == NULL) {
            return false; 
        }
        
        
        $this->parameters["appid"] = $this -> paymentConfig['appid']; // 公众账号ID
        $this->parameters["mch_id"] = $this -> paymentConfig['mchid']; // 商户号
        $this->parameters["nonce_str"] = $this -> createNoncestr(); // 随机字符串
        $this->parameters["spbill_create_ip"] = Ip::clientIp(); // 终端ip
        
        $this->parameters["sign"] = $this -> getSign($this->parameters); // 签名
        
        $xml=$this->arrayToXml($this->parameters);

        
        $response = $this->postXmlCurl($xml, $url, 30);
        //var_dump($response);exit;
        //var_dump($response);exit;
        $result =$this->xmlToArray($response);
        $prepayId = $result["prepay_id"];
        
        return $prepayId;
    }

    /**
     * 设置jsapi的参数
     * @param unknown $prepayId
     * @return string
     */
    public function getParameters($prepayId)
    {
        $jsApiObj["appId"] = $this -> paymentConfig['appid'];
        $timeStamp = time();
        $jsApiObj["timeStamp"] = "$timeStamp";
        $jsApiObj["nonceStr"] = $this->createNoncestr();
        $jsApiObj["package"] = "prepay_id=$prepayId";
        $jsApiObj["signType"] = "MD5";
        $jsApiObj["paySign"] = $this->getSign($jsApiObj);
        $this->parameters = json_encode($jsApiObj);

        return $this->parameters;
    }

    /**
     * 
     * @param unknown $url
     * @param array $header
     * @param number $timeout
     * @return mixed
     */
    private function curl_https($url, $header=array(), $timeout=30){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);  // 从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        $response = curl_exec($ch);
        if(!$response){
            $error=curl_error($ch);
            $this->logResult("error::curl_https::error_code".$error);
        }
        curl_close($ch);

        return $response;

    }
    /***
     * 获取open id
     * @return number|boolean
     */
    public function getOpenId(){
         
    }
}
