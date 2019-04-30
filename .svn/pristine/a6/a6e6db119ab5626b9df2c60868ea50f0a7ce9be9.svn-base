<?php

if( class_exists('AseSafe') ){
    return true;
}
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/7
 * Time: 14:21
 */
class AseSafe
{

    const HASHMETHOD = "sha256";      //要使用的哈希算法名称，例如："md5"，"sha256"，"haval160,4" 等。 如何获取受支持的算法清单，请参见 hash_algos()。

    const EncryptionMethod = "AES-128-CBC";       //ase加密方式
    //使用下列方法获取有效加密方式列表      //例如[0] => AES-128-CBC [1] => AES-128-CFB [2] => AES-128-CFB1 [3] => AES-128-CFB8 [4] => AES-128-ECB [5] => AES-128-OFB [6] => AES-192-CBC等等
    // $ciphers = openssl_get_cipher_methods();
    // print_r($ciphers);

    const KEY = "s,546?asf&kd)ssj";      //ase加密key

     const IV = "val>sld^%$64{dsf";

    /**
     * 加密
     * @param $value  要加密的值
     * @param string $key 加密key
     * @return bool|string
     */
    public function encrypt($value, $key = self::KEY)
    {
        $length = mb_strlen($key, '8bit');

        if (!$length === 16) {
            return false;
        }

        $seeds = '0123456789abcdefghijklmnopqrstuvwxyz';
        $length = 16;

        $iv = substr(str_shuffle(str_repeat($seeds, $length)), 0, $length);

        $value = openssl_encrypt(serialize($value), self::EncryptionMethod, $key, 0, $iv);

        if ($value === false) {
            return false;
        }

        $iv = base64_encode($iv);

        $mac = hash_hmac(self::HASHMETHOD, $iv . $value, $key);

        $json = json_encode(compact('iv', 'value', 'mac'));

        if (!is_string($json)) {
            return false;
        }

        return base64_encode($json);
    }

    /**
     * 解密
     * @param $payload     要解密的值
     * @param string $key 解密key
     * @return bool|mixed
     */
    public function decrypt($payload, $key = self::KEY)
    {
        $length = mb_strlen($key, '8bit');

        if (!$length === 16) {
            return false;
        }

        $payload = json_decode(base64_decode($payload), true);

        if (!$payload || !is_array($payload) || !isset($payload['iv']) || !isset($payload['value']) || !isset($payload['mac'])) {
            return false;
        }

        $seeds = '0123456789abcdefghijklmnopqrstuvwxyz';
        $length = 16;

        $bytes = substr(str_shuffle(str_repeat($seeds, $length)), 0, $length);
        $hash = hash_hmac(self::HASHMETHOD, $payload['iv'] . $payload['value'], $key);
        $calcMac = hash_hmac(self::HASHMETHOD, $hash, $bytes, true);

        if (!hash_equals(hash_hmac(self::HASHMETHOD, $payload['mac'], $bytes, true), $calcMac)) {
            return false;
        }

        $iv = base64_decode($payload['iv']);

        $decrypted = openssl_decrypt($payload['value'], self::EncryptionMethod, $key, 0, $iv);

        if ($decrypted === false) {
            return false;
        }

        return unserialize($decrypted);
    }

    function aes128CbcEncrypt($str, $iv=self::IV, $key=self::KEY ) {    // $this->addPkcs7Padding($str,16)
        $base = (mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key,$this->addPkcs7Padding($str,16) , MCRYPT_MODE_CBC, $iv));
        return base64_encode($base);
    }

    private function addPkcs7Padding($string, $blocksize = 32) {
        $len = strlen($string); //取得字符串长度
        $pad = $blocksize - ($len % $blocksize); //取得补码的长度
        $string .= str_repeat(chr($pad), $pad); //用ASCII码为补码长度的字符， 补足最后一段
        return $string;
    }

    function aes128cbcDecrypt($str, $iv=self::IV, $key=self::KEY ) {    // $this->addPkcs7Padding($str,16)
        $str = base64_decode($str);
        $base = (mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key,$str , MCRYPT_MODE_CBC, $iv));
        return $this->stripPkcs7Padding($base);
    }

    /**
     * 除去pkcs7 padding
     * @param String 解密后的结果
     * @return String
     */
    private function stripPkcs7Padding($string){
        $slast = ord(substr($string, -1));
        $slastc = chr($slast);
        $pcheck = substr($string, -$slast);

        if(preg_match("/$slastc{".$slast."}/", $string)){
            $string = substr($string, 0, strlen($string)-$slast);
            return $string;
        } else {
            return false;
        }
    }
}