<?php
 
require_once(dirname(__FILE__) .  '/lib/HttpClient.class.php');
require_once(dirname(__FILE__) .  '/config.inc.php');

//$GLOBALS['sms_options'] = $options;

/**
* 模板接口发短信
* apikey 为云片分配的apikey
* tpl_id 为模板id
* tpl_value 为模板值
* mobile 为接收短信的手机号
*/
function tpl_send_sms($tpl_id, $tpl_value, $mobile){
	$path = "/v1/sms/tpl_send.json";
	return Send::sendSms($path, Zc::C('mobile_key'), $mobile, $tpl_id, $tpl_value);
}

/**
* 普通接口发短信
* apikey 为云片分配的apikey
* text 为短信内容
* mobile 为接收短信的手机号
*/
/*function send_sms($content, $mobile){
	$path = "/v1/sms/send.json";
	return Send::sendSms($path, $GLOBALS['sms_options']['apikey'], str_replace(C('site_name'), $GLOBALS['sms_options']['signature'], $content), $mobile);
}*/

class Send {

	const HOST = 'yunpian.com';

	final private static function __replyResult($jsonStr) {
		//header("Content-type: text/html; charset=utf-8");
		$result = json_decode($jsonStr);
		if ($result->code == 0) {
			$data['state'] = 'true';
			return true;
		} else {
			$data['state'] = 'false';
			$data['msg'] = $result->msg;
			return false;
		}
	}

	final public static function sendSms($path, $apikey, $encoded_text, $mobile, $tpl_id = '', $encoded_tpl_value = '') {
		$client = new HttpClient(self::HOST);
		$client->setDebug(false);
		if (!$client->post($path, array (
				'apikey' 		=> $apikey,
				'text' 			=> $encoded_text,
				'mobile' 		=> $mobile,
				'tpl_id' 		=> $tpl_id,
				'tpl_value' 	=> $encoded_tpl_value
		))) {
			return '-10000';
		} else {
			return self::__replyResult($client->getContent());
		}
	}
}

function send_sms($text, $mobile)
{
	$ch = curl_init();
	/* 设置验证方式 */

	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept:text/plain;charset=utf-8', 'Content-Type:application/x-www-form-urlencoded','charset=utf-8'));

	/* 设置返回结果为流 */
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	/* 设置超时时间*/
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);

	/* 设置通信方式 */
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	/*preg_match('/(.*?)(\d+)/', $text, $match);
	$data=array('tpl_id'=>'1575750','tpl_value'=>('#code#').'='.urlencode($match[2]).'&'.urlencode('#content#').'='.urlencode($match[1]),'apikey'=>$GLOBALS['sms_options']['apikey'],'mobile'=>$mobile);
	curl_setopt ($ch, CURLOPT_URL, 'https://sms.yunpian.com/v2/sms/tpl_single_send.json');*/

	$data = array('text' => $text, 'apikey' => Zc::C('mobile_key'), 'mobile' => $mobile);
	curl_setopt($ch, CURLOPT_URL, 'https://sms.yunpian.com/v2/sms/single_send.json');
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

	$json_data= curl_exec($ch);
	
	$array = json_decode($json_data,true);
	
	if ($array && $array['code'] === 0) {
		return true;
	} else {
		//Log::record($json_data.$text);
		return false;
	}
}

?>