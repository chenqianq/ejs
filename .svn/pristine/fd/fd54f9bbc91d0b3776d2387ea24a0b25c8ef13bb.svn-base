<?php
if (file_exists (DIR_FS_CLASSES .'payment/baoFoo/bfRsa.php') && !class_exists('bfRsa')) {
    include_once(DIR_FS_CLASSES . 'payment/baoFoo/bfRsa.php');
}
if (!defined('BF_KEY_ROOT')) {
    define('BF_KEY_ROOT', DIR_FS_CLASSES ."payment/baoFoo/key/");
}
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/12
 * Time: 9:41
 */
class bfpay
{
    /**
     * @var bfRsa
     */
    private $bfRsa;

    private $bfPayUrl = 'https://public.baofoo.com/platform/gateway/back';

    private $bfCodePayUrl = "https://public.baofoo.com/platform/gateway/front";

    private $bfRefundUrl = "https://public.baofoo.com/cutpayment/api/backTransRequest";

    private $bfDeclareUrl = "https://cb.baofoo.com/declare";

    private $bfProxySubmitUrl = "https://cb.baofoo.com/cbpay/order/proxy/submit";

    private $notifyUrl;

    private $pageNotifyUrl;       //宝付支付页面通知地址    注：扫码返回专有地址

    private $bfRefundNotify;      //宝付退款通知界面

    private $privateKeyPwd = "jychw9w89eu1338yui3rhiuewfhewoyr";//"baofoo";"yfj_3579.";

    private $private_key_path;

    private $public_key_path;

    private $wxAppId;                       //微信appid

    private $terminalId;                    //终端号     由宝付分配

    private $memberId;                       //商户号

    private $orderType;

    function __construct($baofooConfig,$paySn = '', $orderType = 'co')
    {
        $this->notifyUrl = Zc::url(YfjRouteConst::bfPayNotify);
        $this->pageNotifyUrl = Zc::url(YfjRouteConst::success);
        $this->bfRefundNotify = Zc::url(YfjRouteConst::bfRefundNotify);
         
        if ($baofooConfig) {
            $this->paymentConfig = $baofooConfig;
            $this->wxAppId = $baofooConfig['wx_app_id'];
            $this->terminalId = $baofooConfig['terminal_id'];
            $this->memberId = $baofooConfig['member_id'];
            if( $baofooConfig['privateKeyPwd'] ){
                $this -> privateKeyPwd = $baofooConfig['privateKeyPwd'];
            }
        }
        if ($baofooConfig && !empty($paySn)) {
            if ($orderType == 'ds') {
                $this->order = ServiceFactory::getOrderService()->getMergePayment($paySn); // b端合并支付信息
            } else {
                $this->order = ServiceFactory::getOrderService()->getMainOrdersByPaySn($paySn);
                if ( !$this->order ) {
                    $this->order = ServiceFactory::getOrderService() -> getOrdersByPaySn($paySn);
                }
            }
            $this->orderType = $orderType;
        }
        
        if( file_exists(BF_KEY_ROOT . $baofooConfig['private_key']) ){
            $this->private_key_path = BF_KEY_ROOT . $baofooConfig['private_key'];         // "1169801@@34736.pfx";
        }
        else{
            $this->private_key_path = BF_KEY_ROOT . "yfj_pri.pfx";         // "1169801@@34736.pfx";
        }
        
        if( file_exists(BF_KEY_ROOT . $baofooConfig['public_key']) ){
            $this->public_key_path = BF_KEY_ROOT . $baofooConfig['public_key'];
        }
        else{
            $this->public_key_path = BF_KEY_ROOT . "baofu.cer";
        }
        
        $this->bfRsa = new bfRsa($this->private_key_path, $this->public_key_path, $this->privateKeyPwd);
    }

    /**
     * 宝付支付
     * @internal  $txnType                    交易类型       10199 -扫码界面    20199-api
     * @param $txnSubType                 交易子类       具体参见宝付附录2：交易子类
     * @return bool|string
     */
    public function baofuPay($txnType,$txnSubType)
    {
        if (empty($this->order['pay_sn']) || empty($txnType) || empty($txnSubType)) {        //如果传递过来的参数为空，则返回false
            
            return false;
        }
        if($this->orderType =="co") {
            $this->order['pay_sn'] = $this->order['parent_pay_sn'] ?: $this->order['pay_sn'];
        }
        $baofooObj = array();
        $baofooObj['version'] = '4.0.0.2';                                      //版本号
        $baofooObj['terminal_id'] = $this->terminalId;  //"34736";//'36735';    //终端号 由宝付分配
        $baofooObj['txn_type'] = $txnType;              //"10199";              //交易类型
        $baofooObj['txn_sub_type'] = $txnSubType;       //"06";                 //交易子类 具体参见附录2：交易子类
        $baofooObj['member_id'] = $this->memberId;      //"1169801";//'1192898';//商户号
        $baofooObj['data_type'] = 'json';                                       //加密数据类型
        $contentArray = array();
        if ($baofooObj['txn_sub_type'] == "04") {
            $contentArray["appid"] = $this->wxAppId;                                        // 公众账号ID,微信app支付需要
        }
        $contentArray["txn_sub_type"] = $txnSubType;//'06';                                 //交易子类
        $contentArray["terminal_id"] = $this->terminalId;//'34736';                         //终端号
        $contentArray["member_id"] = $this->memberId; //'1192898';                          //$this -> paymentConfig['mchid'];  // 商户号
        $contentArray["trans_id"] = $this->order['pay_sn'];                                 //商户订单号
        $contentArray["trans_serial_no"] = $this->order['pay_sn'] . time();                 //商户流水号
        $contentArray["txn_amt"] = $this->order['order_amount'] * 100;                      // 交易金额     单位：分
        $contentArray["trade_date"] = date('YmdHis', time());                               // 订单日期
        $contentArray["commodity_name"] = '一番街订单：' . $this->order['pay_sn'];          // 商品名称
        if($txnSubType == YfjConst::bfWxCodeApiPay){
            $contentArray["open_id"] = $_SESSION['openId'];
        }
        if ($baofooObj['txn_type'] == YfjConst::bfCodePay) {                                //注：只有扫码界面有下面两个属性
            $contentArray["notice_type"] = '1';                                             // 通知类型 1-服务器和页面通知,0-仅服务器通知,3-不通知
            $contentArray['page_url'] = $this->pageNotifyUrl . "?pay_sn=" . $this->order['pay_sn'];//"http://27.156.95.117:3005/zc/checkout/confirmation/baofoo_pay_notify_page.html";//                      // 页面通知地址
        }//http://27.156.95.71:3005/zc/checkout/confirmation/pay_success.html?order_sn=" . $this->order['order_sn'];//
        $contentArray["return_url"] = $this->notifyUrl;                                  // 服务器通知地址
        //"http://27.156.94.168:3005/zc/checkout/confirmation/baofoo_pay_notify.html";//
//        $contentArray["commodity_amount"] = '';                           // 商品数量  选填
//        $contentArray["user_id"] = '';                                     //用户 id          选填
//        $contentArray["user_name"] = '';                                   //用户名         选填
//        $contentArray["share_info"] = '';                                  // 分账信息 单位(分) 格式 商户 1,金额 1;商户 2,金额 2... 例如 100000363,10;100000364,90; 选填
//        $contentArray["notify_url"] = '';                                 //通知地址         选填
        $appVersion = "0.0.0";
        if($_SERVER['HTTP_APPVERSION']) {
            $appVersion = $_SERVER['HTTP_APPVERSION'];
        }
        $orderClient = G_CURRENT_DOAMIN_CONST_NAME;
        $contentArray["additional_info"] = json_encode(['order_type'=>$this->orderType,"app_version"=>$appVersion,"order_client"=>$orderClient]);                           //附加字段   选填
//        $contentArray["req_reserved"] = '';                               //请求方保留域   选填
        $xml = json_encode($contentArray); // $this->arrayToXml($contentArray);
        $dataContent = $this->bfRsa->encryptedByPrivateKey(base64_encode($xml));        //对传送过去的内容进行rsa加密
        $baofooObj['data_content'] = $dataContent;                                     //加密数据类型
        if ($baofooObj['txn_type'] == YfjConst::bfPay) {
            $response = $this->postXmlCurl($this->convertParam($baofooObj), $this->bfPayUrl, 30);
            return $this->decryptReturnContent($response);
        } elseif ($baofooObj['txn_type'] == YfjConst::bfCodePay) {     //跳转到宝付的扫码页面，如果用curl获取到结果，二维码由于路径问题无法显示
            $response = $this->baofooCodePage($baofooObj, $this->bfCodePayUrl);
            return $response;
        }
        exit();
    }

    /**
     * 支付校检
     * @param int $txnSubType         交易子类
     * @param string $date               日期
     * @param bool $rsIsReturn    是否返回数据
     * @param string $paySn       商户订单号
     * @return bool|mixed
     */
    public function return_verify($txnSubType,$date,$rsIsReturn=false,$paySn="",$orderAmount=0)
    {
        if($paySn) {
            $this->order['pay_sn'] = $paySn;
        }
        $baofooObj = array();
        $baofooObj['version'] = '4.0.0.2';                                  //版本号
        $baofooObj['terminal_id'] = $this->terminalId;//"34736";//'36735';                                      //终端号     由宝付分配
        $baofooObj['txn_type'] = "20199";                                  //交易类型      扫码和api查询都是 20199
        $baofooObj['txn_sub_type'] = $txnSubType;//$txnSubType;//"06";                                  //交易子类      具体参见附录2：交易子类
        $baofooObj['member_id'] = $this->memberId;//"1169801";//'1192898';                                     //商户号
        $baofooObj['data_type'] = 'json';                                  //加密数据类型
        $contentArray = array();
        $contentArray["txn_sub_type"] = $txnSubType;//$txnSubType;//'06';                                   //交易子类
        $contentArray["terminal_id"] = $this->terminalId;//'34736';                          //终端号
        $contentArray["member_id"] = $this->memberId; //'1192898';                           //$this -> paymentConfig['mchid'];  // 商户号
        $contentArray["trans_serial_no"] = $this->order["pay_sn"] . time().rand(1,99);                          //商户流水号
        $contentArray["orig_trans_id"] = $this->order["pay_sn"];                            //原始商户订单号
        $contentArray["trade_date"] = $date;//date('YmdHis', time());                         // 订单日期
        $xml = json_encode($contentArray); // $this->arrayToXml($contentArray);
        $dataContent = $this->bfRsa->encryptedByPrivateKey(base64_encode($xml));        //对传送过去的内容进行rsa加密
        $baofooObj['data_content'] = $dataContent;                                     //加密数据类型
        $response = $this->postXmlCurl($this->convertParam($baofooObj), $this->bfPayUrl, 30);
        $returnJson = $this->decryptReturnContent($response);
        $rs = json_decode($returnJson, true);
        if ($rsIsReturn) {
            return $rs;
        }
        if($orderAmount) {
            $this->order['order_amount'] = $orderAmount;
        }
        $amount = $this->order['order_amount'] * 100;
        if (($rs["resp_code"] != YfjConst::bfRespSuccessCode) || bccomp($rs['succ_amt'], $amount, 0) != 0) {
            return false;
        }
        return true;
    }

    /**
     * 宝付退款
     */
    public function baofooRefund($refundType,$refundReaon)
    {
        if (empty($this->order['pay_sn']) || empty($refundType) || empty($refundReaon)) {        //如果传递过来的参数为空，则返回false
            return false;
        }
        $baofooObj = array();
        $baofooObj['Version'] = '4.0.0.0';                                  //版本号
        $baofooObj['terminal_id'] = $this->terminalId;//"34736";//'36735';                                      //终端号     由宝付分配
        $baofooObj['txn_type'] = "331";                                         //交易类型 官方 331
        $baofooObj['txn_sub_type'] = "09";                                      //交易子类       官方 09
        $baofooObj['member_id'] = $this->memberId;//"1169801";//'1192898';                                     //商户号
        $baofooObj['data_type'] = 'json';                                  //加密数据类型
        $contentArray = array();
        $contentArray["txn_sub_type"] = "09";                                //交易子类
        $contentArray["terminal_id"] = $this->terminalId;//'34736';                                 //终端号
        $contentArray["member_id"] = $this->memberId; //'1192898';                                    //$this -> paymentConfig['mchid'];  // 商户号
        $contentArray["refund_type"] = $refundType; //'                                  //退款类型 1:宝付收银台  2:认证支付、代扣、快捷支付 3:微信支付 5:支付宝支付
        $contentArray["trans_id"] = $this->order['pay_sn'];                                  //商户订单号
        $contentArray["refund_order_no"] = "refund" . $this->order['pay_sn'];                                  //退款商户订单号
        $contentArray["trans_serial_no"] = "refund" . $this->order['pay_sn'] . time();                          //退款商户流水号
        $contentArray["refund_reason"] = $refundReaon;                          //商户流水号
        $contentArray["txn_amt"] = $this->order['order_amount'] * 100;                                       // 交易金额     单位：分
        $contentArray["refund_time"] = date('YmdHis', time());                                        // 退款发起时间
        $contentArray["notice_url"] = $this -> bfRefundNotify;
        //"http://27.156.94.168:3005/zc/checkout/confirmation/baofoo_refund_notify.html";//$                                  // 服务器通知地址
        $contentArray["additional_info"] = $this->orderType;                           //附加字段   选填
//        $contentArray["req_reserved"] = '';                                               //请求方保留域   选填
//        $contentArray["version"] = '';                                                  //版本号   选填
//        var_dump($baofooObj);exit;
        $xml = json_encode($contentArray); // $this->arrayToXml($contentArray);
        $dataContent = $this->bfRsa->encryptedByPrivateKey(base64_encode($xml));        //对传送过去的内容进行rsa加密
        $baofooObj['data_content'] = $dataContent;                                     //加密数据类型
        $response = $this->postXmlCurl($this->convertParam($baofooObj), $this->bfRefundUrl, 30);
        return $this->decryptReturnContent($response);
    }


    /**
     * 宝付退款查询
     */
    public function baofooRefundQuery($txnSubType=10,$rsIsReturn=false)
    {
        $baofooObj = array();
        $baofooObj['version'] = '4.0.0.0';                                  //版本号
        $baofooObj['terminal_id'] = $this->terminalId;//"34736";//'36735';                                      //终端号     由宝付分配
        $baofooObj['txn_type'] = 331;                                  //交易类型      扫码和api查询都是 20199
        $baofooObj['txn_sub_type'] = $txnSubType;//$txnSubType;//"06";                                  //交易子类      具体参见附录2：交易子类
        $baofooObj['member_id'] = $this->memberId;//"1169801";//'1192898';                                     //商户号
        $baofooObj['data_type'] = 'json';                                  //加密数据类型
        $contentArray = array();
        $contentArray["txn_sub_type"] = $txnSubType;//$txnSubType;//'06';                                   //交易子类
        $contentArray["terminal_id"] = $this->terminalId;//'34736';                          //终端号
        $contentArray["member_id"] = $this->memberId; //'1192898';                           //$this -> paymentConfig['mchid'];  // 商户号
        $contentArray["refund_order_no"] = $this->order["pay_sn"];                          //商户流水号
        $contentArray["trans_serial_no"] = $this->order["pay_sn"]. time();                            //原始商户订单号
        $contentArray["additional_info"] = "退款";                         // 订单日期
//       var_dump($contentArray);exit;
        $xml = json_encode($contentArray); // $this->arrayToXml($contentArray);
        $dataContent = $this->bfRsa->encryptedByPrivateKey(base64_encode($xml));        //对传送过去的内容进行rsa加密
        $baofooObj['data_content'] = $dataContent;                                     //加密数据类型
        $response = $this->postXmlCurl($this->convertParam($baofooObj), $this-> bfRefundUrl, 30);
        $returnJson = $this->decryptReturnContent($response);
        $rs = json_decode($returnJson, true);
//        var_dump($rs);exit;
        if ($rsIsReturn) {
            return $rs;
        }
        $amount = $this->order['order_amount'] * 100;
        if (($rs["resp_code"] != YfjConst::bfRespSuccessCode) || bccomp($rs['succ_amt'], $amount, 0)) {
            return false;
        }
        return true;
    }

    /**
     * 宝付报关
     */
    public function baofooDeclare($copCode,$orderInfo)
    {
        //pay_sn  order_amount
        if (empty($orderInfo) || !is_array($orderInfo) || empty($copCode)) {        //如果传递过来的参数为空，则返回false
            return false;
        }
        $parentPaySn = $paySn = $orderInfo["pay_sn"];

        $outRequestNo = $paySn . time();
        $baofooObj = array();
        $baofooObj['version'] = '1.0.0';                                  //版本号
        $baofooObj['terminalId'] = $this->terminalId;//"34736";//'36735';                                      //终端号     由宝付分配
        $baofooObj['memberId'] = $this->memberId;//"1169801";//'1192898';                                     //商户号
        $baofooObj['dataType'] = 'JSON';                                  //加密数据类型
        $contentArray = array();
        $contentArray["version"] = "1.0.0";                                     //默认1.0.0
        $contentArray["memberApplyNo"] = $outRequestNo;                                     //商户发起的备案唯一编号
        $contentArray["memberId"] = $this->memberId; //'1192898';                                    //$this -> paymentConfig['mchid'];  // 商户号
        $contentArray["terminalId"] = $this->terminalId;//'34736';                                 //终端号
        $contentArray["memberTransId"] = $parentPaySn;                                  //商户在宝付支付时的订单号
        $contentArray["memberTransDate"] = date('YmdHis', strtotime($orderInfo['gmt_create']));                                 //商户订单日期
        $contentArray["functionId"] = "300117";                                                              //海关关区代码
        $contentArray["companyOrderNo"] = $paySn;                                        //电商订单编号
        $contentArray["payTotalAmount"] = $orderInfo['order_amount'];                                 //支付总金额
        $contentArray["payGoodsAmount"] = $orderInfo['order_amount'];                                 //支付货款
        $contentArray["payTaxAmount"] = 0;                                   //支付税款
        $contentArray["payFeeAmount"] = 0;                                  //支付运费
        $contentArray["payPreAmount"] = 0;                                  //支付保费
        $contentArray["ccy"] = "CNY";                                        //币种
        $contentArray["notifyUrl"] = Zc::url(YfjRouteConst::baofooDeclareNotify);
//            YfjRouteConst::baofooDeclareNotify;//                       //结果通知地址
        $contentArray["companyCode"] = $copCode;                        //商户备案号
        $contentArray["remarks"] = $this->orderType;                        //备注
        $xml = json_encode($contentArray); // $this->arrayToXml($contentArray);
        $dataContent = $this->bfRsa->encryptedByPrivateKey($xml);        //对传送过去的内容进行rsa加密
        $baofooObj['dataContent'] = $dataContent;                                     //加密数据类型
        $response = $this->postXmlCurl($this->convertParam($baofooObj), $this->bfDeclareUrl, 30);
//        var_dump($response);exit;
        return json_decode($response,true);
    }

    /**
     * 宝付跨境结算订单上传
     */
    public function baofooProxySubmit($copCode,$orderInfo,$productArrayInfo)
    {
        if (empty($orderInfo) || !is_array($orderInfo) || empty($copCode) || empty($productArrayInfo) || !is_array($productArrayInfo)) {        //如果传递过来的参数为空，则返回false
            return false;
        }
        $paySn = $orderInfo["pay_sn"];

        $baofooObj = array();
        $baofooObj['version'] = '1.0.0';                                  //版本号
        $baofooObj['terminalId'] = $this->terminalId;//"34736";//'36735';                                      //终端号     由宝付分配
        $baofooObj['memberId'] = $this->memberId;//"1169801";//'1192898';                                     //商户号
        $baofooObj['dataType'] = 'JSON';                                  //加密数据类型
        $contentArray = array();
        $contentArray["memberId"] = $this->memberId; //'1192898';                                    //$this -> paymentConfig['mchid'];  // 商户号
        $contentArray["terminalId"] = $this->terminalId;//'34736';                                 //终端号
        $contentArray["memberTransId"] = $paySn;                                           //商户在宝付支付时的订单号
        $contentArray["orderAmt"] = $orderInfo['order_amount'];                                  //订单金额
        $contentArray["orderCcy"] = "CNY";                                                 //币种
        $contentArray["transAmt"] = $orderInfo['order_amount'];                           //交易金额 需要跨境汇款金额
        $contentArray["transCcy"] = "CNY";                                                 //交易币种
        $contentArray["idCardNo"] = $orderInfo['cert_id'];                                 //身份证号码
        $contentArray["idName"] = $orderInfo['buyer_name'];                               //姓名
        $contentArray["bankCardNo"] = $orderInfo["order_id"];                                                   //银行卡号
        $productInfoArray = [];
        //goodsName  goodsNum goodsPrice
        foreach ($productArrayInfo as $product) {
            $tmp = [];
            $tmp["goodsName"] = $product["gname"];
            $tmp["goodsNum"] = $product["qty"];
            $tmp["goodsPrice"] = sprintf("%.2f", $product["price"]);
            $productInfoArray[] = $tmp;
        }
       
        $contentArray["goodsInfo"] = json_encode($productInfoArray);                                                   //商品信息
        //pay_sn
        $xml = json_encode($contentArray); // $this->arrayToXml($contentArray);
        //var_dump($xml);exit;
        $dataContent = $this->bfRsa->encryptedByPrivateKey($xml);        //对传送过去的内容进行rsa加密
        // echo $dataContent;exit;
        $baofooObj['dataContent'] = $dataContent;                                     //加密数据类型
        $response = $this->postXmlCurl($this->convertParam($baofooObj), $this->bfProxySubmitUrl, 30);
//        var_dump($response);exit;
        return json_decode($response,true);
    }

    /**
     * 转换传递过去的参数    宝付接收的是这个application/x-www-form-urlencoded 编码传递过去，直接传数组，宝付那边接收不到参数
     * @param $obj
     * @return bool|string
     */
    private function convertParam($obj)
    {
        $returnStr = "";
        foreach ($obj as $k => $v) {
            $returnStr .= "$k=" . urlencode($v) . "&";
        }
        $returnStr = substr($returnStr, 0, -1);
        return $returnStr;
    }

    /**
     * 获取宝付返回的tokenId
     * @param $paySn
     * @param $txnSubType           //交易子类  04-微信APP支付交易 08-支付宝APP支付交易
     * @return bool
     */
    public function getTokenIdByTxnSubType($txnSubType)
    {
        if (!in_array($txnSubType, [YfjConst::bfWxAppPay, YfjConst::bfAliAppPay])) {        //如果不是app支付，则无法获取token_id
            return false;
        }
        $returnJson = $this->baofuPay(YfjConst::bfPay, $txnSubType);
        $rs = json_decode($returnJson, true);
        $respCode = $rs['resp_code'];
        $tokenId = $rs['token_id'];
        if ($respCode == YfjConst::bfRespSuccessCode && $tokenId) {
            return $this->deleteEmpty($tokenId);
        }
        return false;
    }

    /**
     * 去除空格
     * @param $str
     * @return string
     */
    function deleteEmpty($str)
    {
        $str = trim($str); //清除字符串两边的空格
        $str = preg_replace("/\t/", "", $str);    //使用正则表达式替换内容，如：空格，换行，并将替换为空。
        $str = preg_replace("/\r\n/", "", $str);
        $str = preg_replace("/\r/", "", $str);
        $str = preg_replace("/\n/", "", $str);
        $str = preg_replace("/ /", "", $str);
        $str = preg_replace("/  /", "", $str);  //匹配html中的空格
        return trim($str); //返回字符串
    }

    /**
     * 获取宝付返回的地址
     * @param $txnSubType
     * @return bool
     */
    public function getPayUrlByTxnSubType($txnSubType)
    {
        if (!in_array($txnSubType, [YfjConst::bfAliCodeApiPay, YfjConst::bfWxCodeApiPay])) {        //如果不是app支付，则无法获取token_id
            return false;
        }
        $returnJson = $this->baofuPay(YfjConst::bfPay, $txnSubType);
        $rs = json_decode($returnJson, true,512 , JSON_BIGINT_AS_STRING);
        $respCode = $rs['resp_code'];
        $codeUrl = $rs['code_url'];
        $tokenId = $rs['token_id'];
        if ($respCode == YfjConst::bfRespSuccessCode && $codeUrl) {
            return $codeUrl;
        }
        if($respCode == YfjConst::bfRespSuccessCode && $tokenId){
//            return $tokenId;
            $wxPayInfo = json_decode($rs['pay_info'], true,512 , JSON_BIGINT_AS_STRING);
            $js = '
            <script type="text/javascript">
                function onBridgeReady(){  
                    WeixinJSBridge.invoke(  
                       \'getBrandWCPayRequest\', {  
                           "appId":"'.$wxPayInfo['appId'].'",      
                           "timeStamp":"'.$wxPayInfo['timeStamp'].'",          
                           "nonceStr":"'.$wxPayInfo["nonceStr"].'",  
                           "package" : "'.$wxPayInfo["package"].'",       
                           "signType" : "MD5",         
                           "paySign" : "'.$wxPayInfo["paySign"].'"  
                       },  
                       function(res){       
//                          alert( res.err_msg);
                           if(res.err_msg === "get_brand_wcpay_request:ok" ) {
                               window.location.href="'.Zc::url(YfjRouteConst::success,'order_sn='.$this -> order['order_sn']).'";
                           }
                           else if(res.err_msg === "get_brand_wcpay_request:cancel" ) {
                               window.location.href="'.Zc::url(YfjRouteConst::pay,'pay_sn='.$this -> order['pay_sn']).'";
                           }
                           else{
                               window.location.href="' . Zc::url(YfjRouteConst::payFailed, 'pay_sn=' . $this->order['pay_sn']) . '";
                           }
                       }  
                   );   
                }   
                           
                if (typeof WeixinJSBridge === "undefined"){  
                   if( document.addEventListener ){  
                       document.addEventListener(\'WeixinJSBridgeReady\', onBridgeReady, false);  
                   }else if (document.attachEvent){  
                       document.attachEvent(\'WeixinJSBridgeReady\', onBridgeReady);   
                       document.attachEvent(\'onWeixinJSBridgeReady\', onBridgeReady);  
                   }  
                }else{  
                   onBridgeReady();  
                }  
             </script>';
            echo $js;
            exit;
        }
        return false;
    }

    /**
     * 解密宝付传递过来的参数
     * @param $data
     */
    public function decryptReturnContent($dataContent){
         if(empty($dataContent)){
             return false;
         }
         return $this->bfRsa->decryptByPublicKey($dataContent);
    }

    /**
     * 以post方式提交xml到对应的接口url
     * @param $xml
     * @param string $url
     * @param number $second
     */
    public function postXmlCurl($xml,$url,$second=30)
    {
        //初始化curl
        $ch = curl_init();
        //设置超时
        $header = array();
        $header[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8';
        $header[] = 'Accept-Encoding: gzip, deflate';
        $header[] = 'Accept-Language: zh-CN,zh;q=0.8';
        $header[] = 'Cache-Control: max-age=0';
        $header[] = 'Content-Length: ' . strlen($xml);
        $header[] = 'Content-Type: application/x-www-form-urlencoded';
        $header[] = 'Proxy-Connection: keep-alive';
        $header[] = 'Upgrade-Insecure-Requests: 1';
        $header[] = 'Host:public.baofoo.com';
        $header[] = 'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
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
        if (!$data) {
            $error = curl_errno($ch);
            $this->logResult("error::postXmlCurl::curl出错，错误码:$error,http://curl.haxx.se/libcurl/c/libcurl-errors.html 错误原因查询");

        }
        curl_close($ch);
        return $data;
    }

    /**
     * 宝付二维码扫码界面
     * @param $xml
     * @param $url
     * @return string
     */
    private function baofooCodePage($xml,$url)
    {
        $FormString = "正在处理中，请稍候。。。。。。。。。。。。。。"
            . "<body onload=\"document.pay.submit()\"><form id=\"pay\" name=\"pay\" action=\"" . $url . "\" method=\"post\">"
            . "<input name=\"version\" type=\"hidden\" id=\"version\" value=\"" . $xml["version"] . "\" />"
            . "<input name=\"txn_type\" type=\"hidden\" id=\"txn_type\" value=\"" . $xml["txn_type"] . "\" />"
            . "<input name=\"txn_sub_type\" type=\"hidden\" id=\"txn_sub_type\" value=\"" . $xml["txn_sub_type"] . "\" />"
            . "<input name=\"terminal_id\" type=\"hidden\" id=\"terminal_id\" value=\"" . $xml["terminal_id"] . "\" />"
            . "<input name=\"member_id\" type=\"hidden\" id=\"member_id\" value=\"" . $xml["member_id"] . "\" />"
            . "<input name=\"data_type\" type=\"hidden\" id=\"data_type\" value=\"" . $xml["data_type"] . "\" />"
            . "<textarea name=\"data_content\" style=\"display:none;\" id=\"data_content\">" . $xml['data_content'] . "</textarea>"
            . "</form></body>";
        return $FormString;
    }

    /**
     * 生成日志
     * @param string $word
     * @param array $var
     */
    public function logResult($word = '',$var=array())
    {
        $logObj = LogFactory::getBizLog('wxPayNotify-error');
        $logObj->log('==================请求宝付出错.' . date('Y-m-d H:i:s') . '==================' . "\n");
        $logObj->log(print_r($word, true) . "\n");
    }
}