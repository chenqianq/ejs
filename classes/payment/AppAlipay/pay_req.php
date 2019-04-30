<?php

/*
 * 支付宝支付请求
 * Date: 2015-7-10
 * Author: hzl
 */


/*
 * 支付宝插件测试
 * @param array $order 订单
 */

function aliPayReq($order) {
    require_once(VENDOR_PATH . "AliPay/alipay.config.php");
    require_once(VENDOR_PATH . "AliPay/lib/alipay_core.function.php");
    require_once(VENDOR_PATH . "AliPay/lib/alipay_rsa.function.php");
    $param['service'] = 'mobile.securitypay.pay';
    $param['partner'] = $alipay_config['partner'];
    $param['_input_charset'] = $alipay_config['input_charset'];
    $param['sign_type'] = $alipay_config['sign_type'];
    $param['sign'] = '';
    $param['notify_url'] = $order['notify_url'];
//            $param['app_id'] = '';
//            $param['appenv'] = '';
    $param['out_trade_no'] = $order['out_trade_no'];
    $param['subject'] = $order['subject'];
    $param['payment_type'] = 1;
    $param['seller_id'] = $alipay_config['partner'];
    $param['total_fee'] = $order['total_fee'];
    $param['body'] = isset($order['body']) ? $order['body'] : $order['subject'];
//            $param['it_b_pay'] = '';
//            $param['extern_token'] = '';
    $str = createLinkstrings(paraFilter($param)); //待签名字符串   
    $param['sign'] = urlencode(rsaSign($str, $alipay_config['private_key_path']));
    $str = createLinkstrings($param);
    return $str;
}
