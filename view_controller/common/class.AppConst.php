<?php
/**
 * 定义App 的 error code  以及相关的 字符常量
 * @author kinsly
 */
class AppConst {
	//commo const 
	const ack = 'status';
	const longMessage = 'longMessage';
	const shortMessage = 'shortMessage';
	const resultCode = 'resultCode';
	const errorCode = 'error_code';
	const message = 'message';
	const expirssTime = 'expires_time';
	const appToken = 'app_token';
	const deviciesToken = 'devicies_token';
	
	const jsonTypeObj = 'obj';
	const jsonTypeArray = 'array';
	const jsonType = 'jsonType';
	const mcryptKey = 'mcrypt_key';
	const mcryptIV = 'mcrypt_iv';
	
	const customerId = 'customer_id';
	//////////////////////////
	const aesEncode = 1;//1:AES    
	
	const md5Encode = 2;//2.MD5
	
	const plainEncode = 3;//3.明文
	
	
	
	const success = 'success';
	const failed = 'failed';
	
	const requestSuccess = 100000;
	const requestError = 200000;
	
	const errorCodeSystem = 100001;
	
	/**
	 * 手机验证码发送失败
	 * @var unknown
	 */
	const errorCodeSystemPhoneSendFailed = 100002;
	
	////登陆
	const errorCodeLoginNameEmpty = 300001;
	const errorCodePasswordEmpty = 300002;
	const errorCodeLogin = 300003;
	
	////注册
	const errorCodeMobilePhoneEmpty = 300011;
	const errorCodeMobilePhoneFormat = 300012;
	const errorCodeMobilePasswordEmpty = 300013;
	const errorCodeMobilePasswordLength = 300014;
	const errorCodeMobilePasswordNotMatch = 300015;
	/**
	 * 已经注册过
	 * @var unknown
	 */
	const errorCodeMobileExists = 300016;
	const errorCodePhoneCaptcha = 300017;
	const errorCodeInviterCode = 300018;
	
	const errorCodeSendMsgType = 300030;
	const errorCodeSendRepeat= 300031;
	
	/**
	 * 忘记密码,登陆， 手机未注册，请核对手机是否有误
	 * @var unknown
	 */
	const errorCodeSendPhoneNotMatch = 300032;
	
	
	
}