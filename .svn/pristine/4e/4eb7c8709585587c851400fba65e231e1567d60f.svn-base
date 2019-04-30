<?php


class PostIpController extends ZcController {

    private $ip;
    private $url;
    private $yfjUrl;
    private $result;

    private $curlHelper;

    public function __construct($route) {
        parent::__construct($route);   

        $this -> curlHelper = HelperFactory::getCurlHelper();

        // $this -> url = 'http://1212.ip138.com/ic.asp';  
        $this -> url = 'http://2017.ip138.com/ic.asp';  

        $this -> yfjUrl = 'http://www.yifanjie.com/new-yfj-admin-center/timers/ip/put_ip';
    }



    /**
     * post发送公网ip到一番街
     */
    public function postIpToYfj() {
        
        $this -> getIp();

        // $this -> ip = HelperFactory::getEncryptionHelper()->encrypt('62.155.32.188', MD5_KEY);; // test code

        if ( $this -> ip ) {

            $this -> result = $this -> curl_request($this -> yfjUrl,array('ip_str'=>$this -> ip));

        }
        var_dump($this -> result);
        file_put_contents('d:/post_ip_to_YFJ.log', $this -> getMsg(),  FILE_APPEND);

    }


    /**
     * post日志信息
     */
    private function getMsg() {
        
        $msg = '';
        $msg .= date('Y-m-d H:i:s') . ' - POST 结果: '. $this -> result ;
        $msg .= " POST的IP: " . HelperFactory::getEncryptionHelper()->decrypt($this -> ip, MD5_KEY) . "\r\n";
        return $msg;
    }

    /**
     *  从测试服务器访问,获取公网ip
     */
    private function getIp() { 

        $result = $this -> curlHelper -> get($this -> url);
        // $result = $this -> curl_request($this -> url);
        
        $result = iconv('GB2312', 'UTF-8', $result);
        
        $pp = '/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/';

        preg_match($pp,$result,$matches);

        $ip = $matches[0];

        if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE)) {

            $this -> ip = HelperFactory::getEncryptionHelper()->encrypt($ip, MD5_KEY);

        } else {

            $this -> ip = 0;

            $this -> result = '获取ip失败 ';

        }

    }        


    /**
     * 获取网页数据
     */
    private function curl_request($url,$post='',$cookie='', $returnCookie=0){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_REFERER, "http://XXX");
        if($post) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
        }
        if($cookie) {
            curl_setopt($curl, CURLOPT_COOKIE, $cookie);
        }
        curl_setopt($curl, CURLOPT_HEADER, $returnCookie);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        if (curl_errno($curl)) {
            return curl_error($curl);
        }
        curl_close($curl);
        if($returnCookie){
            list($header, $body) = explode("\r\n\r\n", $data, 2);
            preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
            $info['cookie']  = substr($matches[1][0], 1);
            $info['content'] = $body;
            return $info;
        }else{
            return $data;
        }
    }

}
