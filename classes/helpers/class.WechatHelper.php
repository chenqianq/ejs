<?php
 
/**
 * 微信授权相关接口
 * 
 */
 
class Wechat {
    /**
     * AppID(应用ID)   wxe4dbb90c13ff16c1
秘钥 97f147fc928950034c41472ec4820c16
     */
    //高级功能-》开发者模式-》获取
    private $app_id = 'wxc18156e7f883ca9b';
    private $app_secret = '4f974e5e7cfe76580978a701ce147d2a';//'8dchw9w89eu1338yui3rhiuewfhewoyr';//
 
 
    public function setAppIdAndKey($appId,$appSecret){
        if( !$appId || !$appSecret ){
            return false;
        }
        
        $this -> app_id = $appId;
        $this -> app_secret = $appSecret;
    }
    /**
     * 获取微信授权链接
     * 
     * @param string $redirect_uri 跳转地址
     * @param mixed $state 参数
     */
    public function get_authorize_url($redirect_uri = '', $state = '1#wechat_redirect'){
        $redirect_uri = urlencode($redirect_uri);
        return "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->app_id}&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_userinfo&state={$state}";
    }
    
    /**
     * 静默授权
     * @param string $redirectUri
     * @param string $state
     */
    public function getOpenId($redirectUri = '' , $state = '1#wechat_redirect'){
        $redirectUri = urlencode($redirectUri);
        //https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri=http%3a%2f%2fwww.aaa.com%2fuc%2ffn_callback.php&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->app_id.'&redirect_uri='.$redirectUri.'&response_type=code&scope=snsapi_base&state='.$state;
        //echo $url;exit;
        return $url;
    }
    /**
     * 获取授权token
     * 
     * @param string $code 通过get_authorize_url获取到的code
     */
    public function get_access_token( $code = ''){
        $token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->app_id}&secret={$this->app_secret}&code={$code}&grant_type=authorization_code";
          
        $token_data = $this->http($token_url,'GET');
        
        
        if( $token_data[0] != 200 ){
            return false;
        } 
         
        $tokenDataJson = json_decode($token_data[1], true);
        
        if( $tokenDataJson['access_token'] ){
            return $tokenDataJson;
        }
        
        
        return false;
    }
    
    /**
     * 获取授权后的微信用户信息
     * 
     * @param string $access_token
     * @param string $open_id
     */
    public function get_user_info($access_token = '', $open_id = ''){
        if( empty($access_token) || empty($open_id) ){
            return false;
        }
        
         
        $info_url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$open_id}&lang=zh_CN";
        //echo '<a href="'.$info_url.'">'.$info_url.'</a>';
        $info_data = $this->http($info_url,'GET');
        
        if($info_data[0] == 200){
            $infoDataJson = json_decode($info_data[1], true);
            if( $infoDataJson['openid'] ){
                return $infoDataJson;
            } 
        }
         
        
        Zc::getLog('wxUserInfo'.date('Y-m-d')) -> log($info_data[1]);
        return false;
    }
    
    /**
     * 公众号的全局唯一票据
     * @return mixed|boolean
     */
    public function getClientCredential(){
        //https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=APPID&secret=APPSECRET
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this -> app_id.'&secret='.$this -> app_secret;
        $response = $this -> http($url, 'GET');
        //{"access_token":"ACCESS_TOKEN","expires_in":7200}
        //{"errcode":40013,"errmsg":"invalid appid"}
        if($response[0] == 200){
            $clientCredential = json_decode($response[1], true);
            if( $clientCredential['access_token'] ){
                return $clientCredential;
            }
        }
        
        $this -> errorLog($response);
        return false;
    }
    
    /**
     * 获得jsapi_ticket
     * {
"errcode":0,
"errmsg":"ok",
"ticket":"bxLdikRXVbTPdHSM05e5u5sUoXNKd8-41ZO3MhKoyN5OfkWITDGgnr2fwJ0m9E8NYzWKVZvdVtaUgWvsdshFKA",
"expires_in":7200
}
     * @param unknown $accessToken
     * @return boolean|mixed
     */
    public function getTicketsByAccessToken($accessToken){
        if( !$accessToken ){
            return false;
        }
       // https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=ACCESS_TOKEN&type=jsapi
        $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$accessToken.'&type=jsapi';
        
        $response = $this -> http($url, 'GET');
         
        if($response[0] == 200){
            $ticketToken = json_decode($response[1], true);
            if( $ticketToken['ticket'] ){
                return $ticketToken;
            }
        }
        
        $this -> errorLog($response);
        return false;
    }
    
    private function errorLog(){
        
    }
    
    
    
    /**
     * 
     * @param unknown $url
     * @param unknown $method
     * @param unknown $postfields
     * @param array $headers
     * @param string $debug
     * @return mixed[]|unknown[]
     */
    public function http($url, $method, $postfields = null, $headers = array(), $debug = false){
        
        //$response = file_get_contents($url);
        $curlHelper = HelperFactory::getCurlHelper(array('ssl'=>'true'));
        $response = $curlHelper -> get($url);
         
        $responseJson = json_decode($response,true);
        $logObj = LogFactory::getBizLog('wxs');
        $logObj -> log(print_r($responseJson,true));
        if ( $responseJson['access_token'] || $responseJson['openid'] || $responseJson['ticket'] ){
            return array(200, $response);
        }
       
        return false;
        
        
        $curlHelper = HelperFactory::getCurlHelper();
        if( $method == 'GET' ){
            $response = $curlHelper -> get($url);
        }
        else{
            $response = $curlHelper -> post($url,$postfields);
        }
        
        
        
        $ci = curl_init();
        /* Curl settings */
        curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ci, CURLOPT_TIMEOUT, 30);
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
 
        switch ($method) {
            case 'POST':
                curl_setopt($ci, CURLOPT_POST, true);
                if (!empty($postfields)) {
                    curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
                    $this->postdata = $postfields;
                }
                break;
        }
        curl_setopt($ci, CURLOPT_URL, $url);
        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ci, CURLINFO_HEADER_OUT, true);
 
        $response = curl_exec($ci);
        $http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
        var_dump($response,$http_code);exit;
        if ($debug) {
            echo "=====post data======\r\n";
            var_dump($postfields);
 
            echo '=====info=====' . "\r\n";
            print_r(curl_getinfo($ci));
 
            echo '=====$response=====' . "\r\n";
            print_r($response);
        }
        curl_close($ci);
        return array($http_code, $response);
    }
	
	 /**
     * OAuth2.0授权认证
     */
    public function get_url_contents($url){
        if (ini_get("allow_url_fopen") == "1") {
            return file_get_contents($url);
        } else {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_URL, $url);
            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
        }
    }
	
}