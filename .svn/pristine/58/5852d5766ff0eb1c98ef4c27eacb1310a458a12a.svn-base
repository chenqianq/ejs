<?php

/**
 * url 操作的相关方法 
 *
 */
class Url
{
    /**
     * bulid一个url，如果参数存在，老的参数会覆盖就的参数
     *
     * @param string $url
     *            源地址
     * @param array $param
     *            添加的参数
     * @param array $delParam
     *            删除的参数键值
     * @return string urldecod 之后的url
     */
    public function bulidUrlWithQuery($url, $param = array(), $delParam = array())
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }
        $url = urldecode($url);

        $urlArray = parse_url($url);
        
        $scheme = $urlArray ['scheme'] . '://';
        $host = $urlArray ['host'];
        $port = $urlArray ['port'];
        $path = $urlArray ['path'];
        $query = $urlArray ['query'];
        $isDefaultPort = ($port == null || $port==80 || $port == 443);
        $baseUrl = $scheme . $host. ($isDefaultPort ? '' : ':' .$port) . $path;
        if (!empty ($query)) {
            $query = str_ireplace('&amp;', '&', $query);
            $querysArray = explode('&', $query);
            $queryArray = array();
            foreach ($querysArray as $q) {
                list ($key, $v) = explode('=', $q);
                $queryArray [$key] = $v;
            }
        }

        //更新参数
        if (!empty ($param)) {
            foreach ($param as $key => $v) {
                if (is_numeric($v) || !empty($v)) {    //如果参数值不为空值，则替换掉参数值
                    $queryArray [$key] = $v;
                }
            }
        }

        //删除参数
        if (!empty ($delParam)) {
            foreach ($delParam as $v) {
                unset($queryArray [$v]);
            }
        }

        $baseUrl .= !empty ($queryArray) ? '?' . http_build_query($queryArray) : '';

        return $baseUrl;
    }

    /**
     * 取得POST 或者 GET的value值，如果没有定义放回默认值
     *
     * @param string $key
     *            Value key
     * @param mixed $defaultValue
     *            (optional)
     * @return mixed Value
     */
    public function getValue($key, $defaultValue = false, $merged = true)
    {
        if (!isset ($key) or empty ($key) or !is_string($key)) {
            return false;
        }
        $post = $get = array();
        $key = strtolower($key);
        $ret = $defaultValue;
        if (!empty($_POST)) {
            foreach ($_POST as $postKey => $postValue) {
                $postKey = strtolower($postKey);
                $post[$postKey] = ($postValue);
            }
        }
        
        if (!empty($_GET) && $merged) {
            foreach ($_GET as $getKey => $getValue) {
                $getKey = strtolower($getKey);
                $get[$getKey] = ($getValue);
            }
        }

        $ret = (isset ($post [$key]) ? $post [$key] : (isset ($get [$key]) ? $get [$key] : $defaultValue));
        
        if (is_string($ret) === true && !is_object($ret)) {
            //$ret = addslashes($ret);//function_exists('mysql_escape_string') ? mysql_escape_string($ret) : addslashes($ret);
        }
        
        return $ret;
    }

    /**
     * 检查表的是否提价了
     *
     * @param string $submit
     *            submit name
     */
    public function isSubmit($submit)
    {
        return (isset ($_POST [$submit]) or isset ($_POST [$submit . '_x']) or isset ($_POST [$submit . '_y']) or isset ($_GET [$submit]) or isset ($_GET [$submit . '_x']) or isset ($_GET [$submit . '_y']));
    }

    /**
     * 获取用户输入的参数值
     * eg:user=32&actionuser=asaf;
     */
    public function getCurrentUrlQueryString($url = null)
    {
        if (strpos($url, '?') > 0) {
            $urlArr = explode('?', $url);
            return $urlArr['1'];
        }
    }

    /**
     * 获取url的相关信息
     * @return multitype:string unknown
     */
    public function getCurrentUrlInfo()
    {
        $protocol = 'http';
        if ($_SERVER ['SERVER_PORT'] == 443 || (!empty ($_SERVER ['HTTPS']) && $_SERVER ['HTTPS'] == 'on')) {
            $protocol .= 's';
            $protocol_port = $_SERVER ['SERVER_PORT'];
        } else {
            $protocol_port = 80;
        }

        $host = G_CURRENT_REFERER_DOAMIN;
        $port = $_SERVER ['SERVER_PORT'];
        $request = $_SERVER ['PHP_SELF'];
        $query = isset ($_SERVER ['argv']) ? substr($_SERVER ['argv'] [0], strpos($_SERVER ['argv'] [0], ';') + 1) : '';
        $uri = $_SERVER['REQUEST_URI'];
        $toret = $protocol . '://' . $host . ($port == $protocol_port ? '' : ':' . $port) . $uri;
        $refer = $_SERVER['HTTP_REFERER'];
        $queryString = empty($uri) ? null : substr($uri, strpos($uri, '?') + 1);
        $return = array(
            'url' => $toret,
            'refer' => $refer,
            'host' => $host,
            'protocol' => $protocol,
            'query' => $query,
            'query_string' => $queryString,
            'uri' => $uri
        );

        return $return;
    }

     
    /**
     * 替换域名
     */
    public function replaceDomain($url, $domin)
    {
        $url = str_ireplace(array('https://', 'http://'), '', $url);
        $url = 'http://' . $url;
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return '';
        }
        $parseUrl = parse_url($url);
        if (!$parseUrl['scheme']) {
            $parseUrl['scheme'] = 'http';
        }
        $parseUrl['path'] = preg_replace('/(\/)+/', '/', $parseUrl['path']);
        $url = $parseUrl['scheme'] . '://' . $domin . $parseUrl['path'];
        if ($parseUrl['query']) {
            $url .= '?' . $parseUrl['query'];
        }
// 		dump($parseUrl);die($url);
        return $url;
    }

    /**
     * 根据Site 和  ShortLink创建一个新的URL
     * 例如：http://www.daobin1900.com/sl/sl52818ca0477bd
     * @param    $site
     * @param    $shortLink
     * @param    $ssl
     * @return    $url
     */
    public function buildUrlBySiteAndShortLink($site = '', $shortLink = '')
    {
        $defaultSite = HTTP_SERVER;
        $scheme = 'http://';
        $site = str_ireplace(array('https://', 'http://'), '', $site);
        $site = $site ? $site : $defaultSite;
        $site = $scheme . str_ireplace(array('https://', 'http://'), '', $site);
        $url = $site . '/sl/' . $shortLink;
        return $url;
    }

    /**
     * 创建一个新的ZC URL，根据Site、Route和其他参数组成的数组
     * @param    $site
     * @param    $route
     * @param    $urlParams
     * @param    $ssl
     * @return    $url
     */
    public function buildZCUrlBySiteAndRoute($route, $site = '', $urlParams = array(), $ssl = false)
    {
        $url = '';
        if (!$route) {
            return $url;
        }
        $defaultSite = HTTP_SERVER;
        $site = str_ireplace(array('https://', 'http://'), '', $site);
        $site = $site ? $site : $defaultSite;
        $site = 'http://' . str_ireplace(array('https://', 'http://'), '', $site);
        $route = trim($route, '/');
        $route = trim($route, '?');
        $urlParamsStr = '';
        if ($urlParams && is_array($urlParams)) {
            foreach ($urlParams as $key => $val) {
                if (!$key) {
                    continue;
                }
                $urlParamsStr .= "&{$key}={$val}";
            }
        }
        $url = $site . '/zc/' . $route . $urlParamsStr;
        return $url;
    }

    /**
     * 获取当前encode后的url
     *
     * @param except 待排除的的参数
     */
    public function getCurrentEncodeUrl($except = array(), $host = NULL)
    {
        $except = empty($except) ? array() : $except;
        $protocol = 'http';
        if ($_SERVER ['SERVER_PORT'] == 443 || (!empty ($_SERVER ['HTTPS']) && $_SERVER ['HTTPS'] == 'on')) {
            $protocol .= 's';
            //$protocol_port = $_SERVER ['SERVER_PORT'];
        } else {
            //$protocol_port = 80;
        }
        $protocol_port = $_SERVER ['SERVER_PORT'];
        //$param = $this->getAllGetParams ( $except, false );
        $uri = $_SERVER ['REQUEST_URI'];
        if (strpos($uri, '?') !== false) {
            $query = substr($uri, strpos($uri, '?') + 1);
            $gets = explode('&', $query);
            $newQuery = '';
            foreach ($gets as $get) {
                list($key, $value) = explode('=', $get);
                if (!empty($value) && (!in_array($key, $except))) {
                    $newQuery .= '&' . $key . '=' . trim($value);
                }
            }
            $newQuery = substr($newQuery, 1);
            $uri = $_SERVER ['REDIRECT_URL'] . (!empty($newQuery) ? ('?' . $newQuery) : '');
        }
        $host = empty($host) ? G_CURRENT_REFERER_DOAMIN : $host;
        $port = $_SERVER ['SERVER_PORT'];
        
        if(!strstr($host, ":")){
             $host = $host . ($port == 80 ? '' : ':' . $port);
        }
       
        $currentUrl = $protocol . '://' . $host. $uri;
        
        if (filter_var($currentUrl, FILTER_VALIDATE_URL) == false) {
            return false;
        }
        return urlencode($currentUrl);
    }


    /**
     * 获取get数组
     * @param unknown_type $exclude_array
     * @param unknown_type $return
     * @return Ambigous <string, multitype:string >
     */
    public function getAllGetParams($exclude_array = '', $return = 'array')
    {
        global $_GET;
        if ($exclude_array == '') {
            $exclude_array = array();
        }

        $get_url = '';
        reset($_GET);
        $get = array();
        while (list($key, $value) = each($_GET)) {
            $value = trim($value);
            if ((!in_array($key, $exclude_array)) && !empty($value)) {
                $get[$key] = $value;
                $get_url .= $key . '=' . trim($value) . '&';
            }
            $get_url = substr($get_url, -1);
        }

        return $return == 'array' ? $get : $get_url;
    }

    /**
     * 判断是否是一个url
     * @param string url 待验证的url
     * @return boolean
     */
    public function isUrl($url = null)
    {
        if (empty($url)) {
            return false;
        }
        $url = urldecode($url);
        if (filter_var($url, FILTER_VALIDATE_URL) !== false) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * 取得顶级域名
     * @param string $url
     * @return boolean|string
     */
    public function getTopLevelDomain($url)
    {
        if (strpos($url, '://')) {
            $url = parse_url($url);
            $url = $url ['host'];
        }
        // echo $url;

        $domain_array = explode('.', $url);
        $domain_size = sizeof($domain_array);
        if ($domain_size > 1) {
            if (is_numeric($domain_array [$domain_size - 2]) && is_numeric($domain_array [$domain_size - 1])) {
                return false;
            } else {
                if ($domain_size > 3) {
                    return $domain_array [$domain_size - 3] . '.' . $domain_array [$domain_size - 2] . '.' . $domain_array [$domain_size - 1];
                } else {
                    return $domain_array [$domain_size - 2] . '.' . $domain_array [$domain_size - 1];
                }
            }
        } else {
            return false;
        }
    }

    public function getUrlParamValue($query, $str = null)
    {
        if (empty($query)) {
            return false;
        }
        $param = urldecode($query);
        if (filter_var($param, FILTER_VALIDATE_URL) !== FALSE) {
            $param = parse_url($param);
            $param = $param['query'];
        }
        if (!empty($param)) {
            parse_str($param, $gets);
            if (!empty($gets[$str])) {
                return $gets[$str];
            }
        }
        return false;
    }

    /**
     * 删除url的某个key
     * @param unknown_type $url
     * @param unknown_type $unsetArray
     * @param unknown_type $retrunArray
     * @return Ambigous <unknown, string, mixed>
     */
    public function unsetUrlParam($url, $unsetArray = array(), $retrunArray = FALSE)
    {
        $isUrl = FALSE;
        if (filter_var(urldecode($url), FILTER_VALIDATE_URL) !== FALSE) {
            $parse = parse_url(urldecode($url));
            $scheme = $parse['scheme'];
            $host = $parse['host'];
            $path = $parse['path'];
            $query = $parse['query'];
            $fragment = $parse['fragment'];
            $isUrl = true;
        } else {
            $query = urldecode($url);
        }
        parse_str($query, $param);
        foreach ($param as $key => $v) {
            if (in_array($key, $unsetArray)) {
                unset($param[$key]);
            }
        }
        $url = urldecode(http_build_query($param));
        if ($isUrl == true) {
            $url = $scheme . '://' . $host . $path . '?' . urlencode(http_build_query($param)) . (!empty($fragment) ? '#' . $fragment : '');
        }

        return $retrunArray == FALSE ? $url : parse_url($url);
    }

    /**
     * 判断是否是认证页面
     * @param unknown_type $pag
     */
    public function isSecurePage($page = null)
    {
        $securePages = array(
            
        );
        return in_array($page, $securePages) ? TRUE : FALSE;
    }

    /**
     * 是否是白名单域名
     * @param unknown_type $url
     * @return boolean
     */
    public function isWhitelistUrl($url)
    {
        global $g_sites;

        if (!$this->isUrl(urldecode($url))) {
            return false;
        }
        $param = parse_url(urldecode($url));
        $host = $param['host'];
        return array_search($host, $g_sites) !== FALSE ? TRUE : FALSE;
    }

    public function isZcPageFilter($mianPage = null, $get = null)
    {
        $zcRoute = array(
            FILENAME_DEFAULT => array(
                '/[^category\/home\/*]/i',
            ),
        );
        if (empty($zcRoute[$mianPage])) {
            return false;
        }

        foreach ($zcRoute[$mianPage] as $key => $pattern) {
            if (preg_match($pattern, $get['route'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * 过滤特殊字符转换成url可用字符
     * @param string $string
     */
    public function urlStripSpecialCharacter($string)
    {
        $anchor = preg_replace('/[^a-zA-Z0-9\_\-\.]/', ' ', $string);
        $anchor = preg_replace('/([[:space:]]|[[:blank:]])+/', '-', trim($anchor));
        $anchor = preg_replace('/[\-]+/', '-', trim($anchor));

        return $anchor;
    }
    
    /**
     * 手机号码验证
     * @param unknown $phone
     * @return boolean
     */
    public function checkMobilePhone($phone){
        if( empty($phone) ){
            return false;
        }
        
        if( !preg_match('/^1[3456789]\d{9}$/', $phone) ){
            return false;
        }
        
        return true;
    }
    
    
}