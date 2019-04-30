<?php

/**
 * 宝付Rsa加密
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/12
 * Time: 9:32
 */
if (!function_exists( 'hex2bin')) {
    function hex2bin( $str ) {
        $sbin = "";
        $len = strlen( $str );
        for ( $i = 0; $i < $len; $i += 2 ) {
            $sbin .= pack( "H*", substr( $str, $i, 2 ) );
        }
        return $sbin;
    }
}

class bfRsa
{
    private $private_key;
    private $public_key;
    /**
     * @Param  $private_key_path 商户证书路径（pfx）
     * @Param  $public_key_path 宝付公钥证书路径（cer）
     * @Param  $private_key_password 证书密码
     */
    function __construct($private_key_path,$public_key_path,$private_key_password){
        // 初始化商户私钥
        $pkcs12 = file_get_contents($private_key_path);
        $private_key = array();
        openssl_pkcs12_read($pkcs12, $private_key, $private_key_password);
        //echo "私钥是否可用:", empty($private_key) == true ? '不可用':'可用', "\n";
        //LOG::LogWirte(empty($private_key) == true ? "读取私钥是否可用:不可用":"读取私钥是否可用:可用");
        $this -> private_key = $private_key["pkey"];
        
        //宝付公钥

        $keyFile = file_get_contents($public_key_path);
        $this->public_key = openssl_get_publickey($keyFile);
       // echo "宝付公钥是否可用:", empty($this -> public_key) == true ? '不可用':'可用', "\n";
        //LOG::LogWirte(empty($this -> public_key) == true ? "读取宝付公钥是否可用:不可用":"读取宝付公钥是否可用:可用");
    }

    // 私钥加密
    function encryptedByPrivateKey($data_content){
        $encrypted = "";
        $totalLen = strlen($data_content);
        $encryptPos = 0;
        while ($encryptPos < $totalLen){
            openssl_private_encrypt(substr($data_content, $encryptPos, 117), $encryptData, $this -> private_key);
            $encrypted .= bin2hex($encryptData);
            $encryptPos += 117;
        }
        return $encrypted;
    }

    // 公钥解密
    public function decryptByPublicKey($encrypted)
    {
        $decrypt = "";
        $totalLen = strlen($encrypted);
        $decryptPos = 0;
        
        while ($decryptPos < $totalLen) {
            openssl_public_decrypt(hex2bin(substr($encrypted, $decryptPos, 256)), $decryptData, $this->public_key);
            $decrypt .= $decryptData;
            $decryptPos += 256;
        }
        if ($this->checkStringIsBase64($decrypt)) {
            return base64_decode($decrypt);
        }
        return $decrypt;
    }

    /**
     * 判断是否是base64格式
     * @param $str
     * @return bool
     */
    function checkStringIsBase64($str)
    {
        $str = $this->deleteEmpty($str);
        return trim($str) == base64_encode(base64_decode($str)) ? true : false;
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
}