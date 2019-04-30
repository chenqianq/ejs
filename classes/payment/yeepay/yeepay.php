<?php
if (file_exists (DIR_FS_CLASSES . 'payment/yeepay/lib/YopClient.php') && !class_exists('YopClient')) {
    include_once(DIR_FS_CLASSES . 'payment/yeepay/lib/YopClient.php');
}
if (file_exists (DIR_FS_CLASSES . 'payment/yeepay/lib/YopClient3.php') && !class_exists('YopClient3')) {
    include_once(DIR_FS_CLASSES . 'payment/yeepay/lib/YopClient3.php');
}
if (file_exists (DIR_FS_CLASSES . 'payment/yeepay/lib/Util/YopSignUtils.php')) {
    include_once(DIR_FS_CLASSES . 'payment/yeepay/lib/Util/YopSignUtils.php');
}

class yeepay 
{
    private $yopClient;

    private $yopClient3;

    private $config;

    private $private_key;

    private $yop_public_key;

    private $request;
    /**
     * 
     * @var OrderService
     */
    private $orderService;
    
    /**
     * 支付单号
     * @var unknown
     */
    private $requestId;
    
    private $notifyUrl;
    private $callbackUrl;
    private $productDetail;
    private $payer;
    private $subOrders;
    private $mergePayMsg;

    private $yeepayPrepareUrl;
    private $yeepayPrepareJsonParam;
    private $yeepayAppKey;

    // private $orderType;

    /**
     * yeepay constructor.
     * @param $copCode 传输企业代码
     */
    public function __construct($paySn = '')
    {
        $this->yopClient    = new YopClient();
        $this->yopClient3   = new YopClient3();

        $configArray = include('config.php');
        $this->config = $configArray['35012619HL'];
        $this->yeepayAppKey = $this->config['app_key'];

        $this->private_key = $this->config['private_key'];
        $this->yop_public_key = $this->config['yop_public_key'];
         
        $this->request = new YopRequest($this->config["app_key"], $this->private_key, $this->config["yop_center"], $this->yop_public_key);
        
        if( $paySn ){
            $this -> orderService = ServiceFactory::getOrderService();
            $this -> getOrderMsgByPaysn($paySn);
            $this -> requestId = $paySn;
            $this -> notifyUrl = Zc::url(YfjRouteConst::unionYeepayNotify);
            $this -> callbackUrl = Zc::url(YfjRouteConst::unionYeepayCallback);
        }
        
    }
    
    private function getOrderMsgByPaysn($paySn){
        if( !$paySn ){
            return false;
        }
        
        $mergePaySnArray = $this -> orderService -> getMergePayment($paySn);
        
        if( !$mergePaySnArray ){
            return false;
        }
        
        $this -> mergePayMsg = $mergePaySnArray;
        $orderInfoArray = json_decode($mergePaySnArray['order_info'],true);  
        $mainOrderArray = $this -> orderService -> getMainOrdersByPaySnArray($orderInfoArray['pay_sn']);
        if( !$mainOrderArray ){
            return false;
        }
        
        $mainOrdersIdArray = [];
        foreach ( $mainOrderArray as $mainOrder ){
            $mainOrdersIdArray[$mainOrder['main_order_id']] = $mainOrder['main_order_id'];
        }
        
        $subOrdersMsgArray = $this -> orderService -> getBSubOrderInfoByMainOrderIdArray($mainOrdersIdArray);
        if( !$subOrdersMsgArray ){
            return false;
        }

        $subOrderIdArray = array_column($subOrdersMsgArray, 'order_id');
        
        $paysnToOrderArray = [];
        foreach ( $subOrdersMsgArray as $subOrdersMsg ){
            //$tmp = [];
            //$tmp['orderAmount'] = $subOrdersMsg['order_amount'] * 100;
            //$tmp['requestId'] = $subOrdersMsg['order_sn'];
            //$this -> subOrders[] = $tmp;
            $this -> payer['name'] = $subOrdersMsg['true_name'];
            $this -> payer['idType'] = 'IDCARD';
            $this -> payer['idNum'] = $subOrdersMsg['card_id']; 
            $paysnToOrderArray[$subOrdersMsg['order_id']] = $subOrdersMsg['order_sn'];
        }
        
        $orderGoodsArray = $this -> orderService -> getBusinessOrderCommonGoodsByOrderIdArray($subOrderIdArray);
         
        if( !$orderGoodsArray ){
            return false;
        }

        foreach ( $orderGoodsArray as $orderId => $orderGoods ){
            // $subPaysn = $paysnToOrderArray[$orderId];
            foreach ( $orderGoods as $orderGoodsId => $goods ){
                $tmp = [];
                //$tmp['requestId'] = $subPaysn;
                $tmp['name'] = preg_replace('/\s/u', ' ', $goods['goods_name']);
                $tmp['quantity'] = $goods['goods_num'];
                $tmp['amount'] = $goods['final_price'] * $goods['goods_num'] * 100;
                $this -> productDetail[] = $tmp;
            }
        }
        
    }

    /**
     * 易宝报关
     * @param  array $orderInfo  报关订单信息
     * @param  array $orderGoodsInfo 报关商品信息
     * @param  array $customsInfo 报关信息
     * @return [type]
     */
    public function yeepayDeclare($ordersArray, $orderProductArray, $copCode, $copName)
    {
        if (!$ordersArray || !is_array($ordersArray) || !$orderProductArray || !is_array($orderProductArray) || !$copCode || !$copName) {
            return false;
        }

        // 获取yeepay需要的商品信息
        $productDetial = $this->getYeepayProductDetail($orderProductArray, $copName);
        // 获取yeepay需要的支付人信息
        $payer = $this->getYeepayPayer($ordersArray);
        // 获取yeepay需要的报关信息
        $customsInfos = $this->getYeepayCustomsInfo($copCode, $copName);

        $yeepayDeclarationData = $this->getYeepayDeclarationData($ordersArray, $productDetial, $payer, $customsInfos);

        $jsonRequest = $this->encode_json($yeepayDeclarationData);

        // var_dump($jsonRequest);
        // exit;

        // 传入组织好的json数据
        $this->request->setJsonParam($jsonRequest);

        //提交Post请求
        $response = YopClient3::post("/rest/v1.0/kj/hg/order", $this->request);

        // if($response->validSign==1){
        //     // print_r ('返回结果签名验证成功!');
        // }

        return $response;

    }

    /**
     * 易宝查询申报
     * @param $requestId
     * @return bool|YopResponse
     */
    public function yeepayQuery($requestId)
    {
        if (!$requestId) {
            return false;
        }
        $data = [];
        $data['requestId'] = $requestId;

        $jsonRequest = $this->encode_json($data);

        $this->request->setJsonParam($jsonRequest);

        $response = YopClient3::post("/rest/v1.0/kj/hg/query", $this->request);

        return $response;
    }

    /**
     * 获取yeepay需要的商品信息
     * @param $orderProductArray
     * @return array
     */
    private function getYeepayProductDetail($orderProductArray, $copName)
    {
        $productDetial = [];
        $i = 0;
        foreach($orderProductArray as $orderProduct) {
            $productDetial[$i]['name'] = $orderProduct['item_name'];
            $productDetial[$i]['quantity'] = $orderProduct['qty'];
            $productDetial[$i]['amount'] = round($orderProduct['total_price'] * 100);  //单位:分，1 元=100 分
            $productDetial[$i]['receiver'] = $copName;
            $productDetial[$i]['description'] = $orderProduct['item_record_no'];
            $i++;
        }
        return $productDetial;
    }

    /**
     * 获取支付人信息
     * @param $ordersArray
     * @return array
     */
    private function getYeepayPayer($ordersArray)
    {
        $payer = [];
        $payer['name'] = $ordersArray['buyer_name'];
        $payer['idType'] = "IDCARD";
        $payer['idNum'] = $ordersArray['cert_id'];
        $payer['customerId'] = $ordersArray['member_id'];
        return $payer;
    }

    /**
     * 获取的报关信息
     * @param $ordersArray
     * @param $copCode
     * @param $copName
     * @return array
     */
    private function getYeepayCustomsInfo($copCode, $copName)
    {
        $customsInfo = [];
        $customsInfo['customsChannel'] = "OFFICAL";
        $customsInfo['commerceCode'] = $copCode;
        $customsInfo['commerceName'] = $copName;
        $customsInfo['customsCode'] = '3520';
        $customsInfo['dxpid'] = 'DXPENT_FUZHOU';
        return [$customsInfo];
    }

    /**
     * 获取整合的易宝公式
     * @param $orderArray
     * @param $productDetial
     * @param $payer
     * @param $customsInfos
     * @return array
     */
    private function getYeepayDeclarationData($orderArray, $productDetial, $payer, $customsInfos)
    {
        $baseData = [];
        $baseData['requestId'] = $orderArray['pay_sn'];
        $baseData['orderAmount'] = round($orderArray['order_amount'] * 100);
        $baseData['orderCurrency'] = 'CNY';
        $baseData['notifyUrl'] = Zc::url(YfjRouteConst::yeepayDeclareNotify);
        $baseData['remark'] = '福州进出口加工区';
        $baseData['productDetails'] = $productDetial;
        $baseData['payer'] = $payer;
        $baseData['customsInfos'] = $customsInfos;
        return $baseData;
    }


    /**
    * 输出json数据，不解析中文
    * @param string/array $str 需要进行json编码的数据
    * @return string  输出json数据
    */
    private function encode_json($str) 
    {  
        $result = urldecode(json_encode($this->url_encode($str)));  
        return $result; 
    }

    /** 
     *  使用url_encode()对字符串进行编码
     *  @param string/array $str 需要编码的数据
     *  @return string/array $str 返回编码后的字符串
     */  
    private function url_encode($str) {  
        if(is_array($str)) {  
            foreach($str as $key=>$value) {  
                $str[urlencode($key)] = $this->url_encode($value);  
            }  
        } else {  
            $str = urlencode($str);  
        }  

        return $str;  
    }  
    
    public function processScanPay(){
        $productsDetailStr = '';
        
        $data['orderAmount'] = $this -> mergePayMsg['order_amount'] * 100;
        $data['orderCurrency'] = 'CNY';
        $data['requestId'] = $this -> mergePayMsg['pay_sn'];
        $data['notifyUrl'] = $this -> notifyUrl;
        $data['callbackUrl'] = $this -> callbackUrl;
        //$data['remark'] = $this -> orderInfo[''];
        //$data['paymentModeCode'] = 'SCANCODE-WEIXIN_PAY';
        $data['productDetails'] =  ($this -> productDetail);
        $data['payer'] =  ($this -> payer);
        //$data['bankCard'] = '';
        $data['cashierVersion'] = 'CUSTOMS';
        $data['forUse'] = 'GOODSTRADE';
        //$data['merchantUserId'] = $this -> orderInfo[''];
        //$data['bindCardId'] = $this -> orderInfo[''];
        //$data['clientIp'] = $this -> orderInfo[''];
        $data['timeout'] = 30;//订单超时时间
        //$data['subOrders'] = ($this -> subOrders);
        //$data['authCode'] = '';//支付宝支付授权码
        //$data['openId'] = '';//微信公众号openId
        //$data['receiver'] = '';//收货人信息
        
        $jsonRequest = $this->encode_json($data);
        
        $this->request->setJsonParam($jsonRequest);

        $this->yeepayPrepareJsonParam = $this->request->getJsonParam();
         
        $response = YopClient3::post("/rest/v1.0/kj/onlinepay/order", $this->request);

        $this->yeepayPrepareUrl = YopClient3::$prepareServerUrl;

        return $response;
    }


    /**
     * @return mixed
     */
    public function getYeepayPrepareUrl()
    {
        return $this->yeepayPrepareUrl;
    }

    /**
     * @return mixed
     */
    public function getYeepayPrepareJsonParam()
    {
        return $this->yeepayPrepareJsonParam;
    }

    /**
     * @return mixed
     */
    public function getYeepayAppKey()
    {
        return $this->yeepayAppKey;
    }


}