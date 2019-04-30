<?php
/**
 * 获取公私钥
 */
if (!defined('PKEY_FILE')) {
    /**
     * @ignore
     */
    define('PKEY_FILE', DIR_FS_CLASSES . 'helpers/pkey/');
    require(PKEY_FILE . 'pkey.inc');
}

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/7
 * Time: 15:44
 */
class RsaSafe
{
    /**
     * 生成公钥和密钥
     */
    public function createPkey()
    {
        $configargs = [
            'digest_alg' => 'sha256',       //要使用的哈希算法名称，例如："md5"，"sha256"，"haval160,4" 等。 如何获取受支持的算法清单，请参见 hash_algos()。
            'private_key_bits' => 512,      //私钥位
            'private_key_type' => OPENSSL_KEYTYPE_RSA,   //私钥类型
            'encrypt' => false                  //是否加密处理
        ];
        $res = openssl_pkey_new($configargs);

        //提取私钥
        openssl_pkey_export($res, $private_key);

        //生成公钥
        $public_key = openssl_pkey_get_details($res);
        $public_key = $public_key["key"];
        $TxtFileName = PKEY_FILE . 'pkey.inc';
        //以读写方式打写指定文件，如果文件不存则创建
        if (($TxtRes = fopen($TxtFileName, "w")) === FALSE) {
            echo("创建可写文件：" . $TxtFileName . "失败");
            exit();
        }
        $StrConents = "<?php define('rse_public_key','" . $public_key . "');define('rse_private_key','" . $private_key . "'); ?>";//要 写进文件的内容
        if (!fwrite($TxtRes, $StrConents)) { //将信息写入文件
            echo("尝试向文件" . $TxtFileName . "写入" . $StrConents . "失败！");
            fclose($TxtRes);
            exit();
        }
        echo("尝试向文件" . $TxtFileName . "写入" . $StrConents . "成功！");
        fclose($TxtRes); //关闭指针
    }

    /**
     * 加密
     * @param $data
     * @param $rsaPrivateKey
     * @return string
     */
    public function encrypt($data, $rsaPrivateKey=rse_private_key) {

        /* 对数据进行签名 */
        //openssl_sign($data, $sign, $res);
        openssl_private_encrypt($data, $sign, $rsaPrivateKey);

        /* 释放资源 */

        /* 对签名进行Base64编码，变为可读的字符串 */
        $sign = base64_encode($sign);

        return $sign;
    }

    /**
     * 解密
     * @param $data
     * @param $rsaPublicKey
     * @return mixed
     */
    function decrypt($encrypted, $rsaPublicKey=rse_public_key) {
        /* 对数据进行解密 */
        openssl_public_decrypt(base64_decode($encrypted), $decrypted, $rsaPublicKey);


        return $decrypted;
    }
}