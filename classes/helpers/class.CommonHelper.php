<?php

class Common
{

    public $chinese_preg = '/^[\x{4e00}-\x{9fa5}]{1,4}/u';
    public $telphone_preg = '/^1[34578]\d{9}$/';
    public $email_preg = '/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i';
    public $error_message = '';

    public function __construct()
    {

    }

    //字符串格式化
    public function format()
    {


        $args = func_get_args();

        if (count($args) == 0) {
            return;
        }

        if (count($args) == 1) {
            return $args[0];
        }

        $str = array_shift($args);

        $str = preg_replace_callback('/\\{(0|[1-9]\\d*)\\}/', create_function('$match', '$args = ' . var_export($args, true) . '; return isset($args[$match[1]]) ? $args[$match[1]] : $match[0];'), $str);

        return $str;
    }

    private function getCardErrorMessage($error_code)
    {
        switch ($error_code) {
            case YfjConst::cardIdWrong:
                $this->error_message = '身份证号码错误';
                return true;
            case YfjConst::notExistCardId:
                $this->error_message = '库中无此身份证记录';
                return true;
            case YfjConst::maintenance:
                $this->error_message = '身份证验证中心维护中';
                return true;
            case YfjConst::net_error:
                $this->error_message = '网络错误';
                return true;
            default:
                return false;
        }
    }

//身份认证
    public function idCardVerifyByInterface2($realname, $idcard)
    {

        $checkStatus = false;
        //for ($i = 0; $i < 3; $i++) {
            $url = 'http://apis.haoservice.com/idcard/VerifyIdcard?';
            $appKey = '5f7d7e467b5f41daa53dc518c28f7a3b';
            $param = http_build_query(['cardNo' => $idcard, 'realName' => $realname, 'key' => $appKey]);
            $url .= $param;
            try {
                $origi_result = @file_get_contents($url);
                $result = json_decode($origi_result);
                $error_code = intval($result->error_code);
                //$checkStatus = -1;
                if ($error_code === 0) {
                    if ($result->result->isok) {
                        $checkStatus = true;
                    } else {
                        $checkStatus = false;
                        $this->error_message = '身份证号码与名字不匹配';
                    }
                   // break;
                } else {
                    $isBreak = $this->getCardErrorMessage($error_code);
                    if ($isBreak !== false) {
                       // break;
                    }
                }
            } catch (Exception $e) {
                $this->error_message = $e->getMessage();
            }
        //}
        return $checkStatus;
    }

    public function isEmpty($check_field)
    {

        $check_field = $check_field ? true : false;
        return $check_field;
    }

    //邮箱验证
    public function isEmail($email)
    {
        return (preg_match($this->email_preg, $email)) ? true : false;
    }

    public function isMatch($check_field, $preg)
    {
        return (preg_match($preg, $check_field)) ? true : false;
    }

    //生成日历
    public function getDays($day_start_str, $day_end_str, $param_begin_week = 0)
    {
        $timestamp_start = strtotime($day_start_str);
        $timestamp_end = strtotime($day_end_str);
        $year_start = date("Y", $timestamp_start);
        $month_start = date("n", $timestamp_start);
        $year_end = date("Y", $timestamp_end);
        $dates = [];

        for ($year = $year_start; $year <= $year_end; ++$year) {
            if ($year < $year_end) {
                $month_end = 12;
            } else {
                $month_end = date("n", $timestamp_end);
            }
            for ($month = $month_start; $month <= $month_end; ++$month) {
                $day_end = date("t", mktime(0, 0, 0, $month, 1, $year));
                if ($month < 10) {
                    $tmp_j = '0' . $month;
                } else {
                    $tmp_j = $month;
                }

                //1号之前位置补空
                $key = $year . '-' . $tmp_j;
                $week_of_first = date("w", mktime(0, 0, 0, $month, 1, $year));
                $add_end = $week_of_first == 0 ? 7 : $week_of_first;
                if ($add_end - $param_begin_week <= 7) {
                    for ($s = 0; $s < $add_end - $param_begin_week - 1; $s++) {
                        $dates[$key][] = '';
                    }
                }
                //当月日期
                for ($day = 1; $day <= $day_end; ++$day) {
                    if ($day < 10) {
                        $tmp_k = '0' . $day;
                    } else {
                        $tmp_k = $day;
                    }
                    $dates[$key][] = $year . '-' . $tmp_j . '-' . $tmp_k;
                }
                //当月最后一天之后位置补空
                $week_of_last = date("w", mktime(0, 0, 0, $month, $day_end, $year));
                $add_num = $week_of_last == 0 ? 7 : $week_of_last;
                for ($s = 0; $s < 7 - $add_num + $param_begin_week; $s++) {
                    $dates[$key][] = '';
                }
            }
            $month_start = 1;
        }
        return $dates;
    }

    //获得第一天和最后一天
    public function getTheMonth($date)
    {
        $firstday = date('Y-m-01', strtotime($date));
        $lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day"));
        return array($firstday, $lastday);
    }

    //获得日历
    public function getCalendar()
    {
        $res = $this->getTheMonth(date('Y-m-d H:i:s', time()));
        $days = $this->getDays($res[0], $res[1]);
        return $days;
    }

    private function getChars($charType, $length = 4)
    {
        $chars = '';
        for ($i = 0; $i < $length; $i++) {
            $chars .= $charType;
        }
        return $chars;
    }

    //手机号码处理
    public function getShowMobile($tel, $replaceLength = 4)
    {

        $chars = $this->getChars('*', $replaceLength);
        return substr_replace($tel, $chars, 3, $replaceLength);
        
    }


    /**
     * 身份证号码处理
     * @param string $cartId        身份证号码
     * @param int    $replaceLength 隐藏的位数
     */
    public function getShowCartId($cartId, $replaceLength = 8) {
        $chars = $this -> getChars('*', $replaceLength);
        return substr_replace($cartId, $chars, 5,$replaceLength);
    }

    //名字处理
    public function getShowName($name)
    {

        $replace = mb_substr($name, 0, 2);
        return str_replace($replace, '****', $name);
    }

//字符串处理
    public function getName($name, $begin, $replace = '...')
    {
        $rep = mb_substr($name, $begin, mb_strlen($name), 'utf-8');
        return str_replace($rep, $replace, $name);
    }

    public function getAddressInfo($address)
    {
        $address['name'] = $this->getShowName($address['true_name']);
        $address['phone'] = $this->getShowMobile($address['mob_phone']);
        return $address;
    }

    public function _get_express($e_code, $shipping_code)
    {

        $url = 'http://www.kuaidi100.com/query?type=' . $e_code . '&postid=' . $shipping_code . '&id=1&valicode=&temp=' . random(4) . '&sessionid=&tmp=' . random(4);
        $content = dfsockopen($url);
        $content = json_decode($content, true);

        if ($content['status'] != 200) return array();
        $content['data'] = array_reverse($content['data']);
        $output = array();
        if (is_array($content['data'])) {
            foreach ($content['data'] as $k => $v) {
                if ($v['time'] == '') continue;
                $output[] = $v['time'] . '&nbsp;&nbsp;' . $v['context'];
            }
        }
        if (empty($output)) return array();
        if (strtoupper(CHARSET) == 'GBK') {
            $output = Language::getUTF8($output);//网站GBK使用编码时,转换为UTF-8,防止json输出汉字问题
        }

        return $output;
    }


    /**
     * 从第三方取快递信息
     *
     */
    private function random($length, $numeric = 0)
    {
        $seed = base_convert(md5(microtime() . $_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
        $seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));
        $hash = '';
        $max = strlen($seed) - 1;
        for ($i = 0; $i < $length; $i++) {
            $hash .= $seed{mt_rand(0, $max)};
        }
        return $hash;
    }


    /**
     * 快递100接口
     */
    public function GetExpress($e_code, $shipping_code)
    {
        /*
        // $myApi='http://api.kuaidi100.com/api?id=fd059d27c0ed6377&com=emsguoji&nu=9790343876700&show=0&muti=1&order=desc';
        $url = 'http://www.kuaidi100.com/query?type=' . $e_code . '&postid=' . $shipping_code . '&id=1&valicode=&temp=' . $this->random(4) . '&prder=desc&sessionid=&tmp=' . $this->random(4);
        echo $url;
        $content = file_get_contents($url);
        $content = json_decode($content, true);
        if ($content['status'] != 200) return array();

        return $content;
        */
        $url = 'http://poll.kuaidi100.com/poll/query.do'; // 快递100收费接口
        $customer = 'C08F55DD4F00165563EDD1EEF653D8FC';   // 公司编号
        $key = 'VIbXwMxI9748';               
        $param = '{"com":"' . $e_code . '","num":"'. $shipping_code . '","resultv2":"1"}';

        $sign = strtoupper(md5($param . $key . $customer));

        $post = array(
            'customer' => $customer,
            'sign' => $sign,
            'param' => $param,
        );

        $content = $this -> curl_request($url, $post);

        $content = json_decode($content, true);
        
        return $content;

    }

    /**
     * HTTP请求
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

    public function array2object($array)
    {
        if (is_array($array)) {
            $obj = new StdClass();
            foreach ($array as $key => $val) {
                $obj->$key = $val;
            }
        } else {
            $obj = $array;
        }
        return $obj;
    }

    public function object2array($object)
    {
        if (is_object($object)) {
            foreach ($object as $key => $value) {
                $arr[$key] = $value;
            }
        } else {
            $arr = $object;
        }
        return $array;
    }

    public function stdClassToArray($stds)
    {

        if (is_object($stds))
            throw new NotObjectException('params not object');
        $params = get_object_vars($stds);

        return $this->toArray($params);
    }

    public function toArray($params)
    {
        $tmp = array();
        if (is_string($params) && !is_null(json_decode($params)))
        {
             $tmp = $this->jsonToArray($params);
        }
        elseif (is_array($params))
            $tmp = $this->arrayRToArray($params);
        //这里注意一下，假如$params 是一个对象，只有包含的属性是可读取（public或者临时的对象属性）的时候才能实现转换
        elseif (is_object($params))
            $tmp = $this->stdClassToArray($params);
        else
            $tmp = $params;
        return $tmp;
    }

    public function jsonToArray($json)
    {
        if (!is_string($json) || is_null(json_decode($json, true)))
            throw new NotJsonStringException('param is not a json string');
        $deJson = json_decode($json, true);
        return $this->toArray($deJson);
    }

    public function arrayRToArray($params)
    {
        $tmp = array();
        if (!is_array($params))
            throw new NotArrayException('params not array');
        foreach ($params as $k => $v) {
            $tmp[$k] = $this->toArray($v);
        }
        //var_dump($tmp);
        return $tmp;
    }


    /**
     * 字符串切割函数，一个字母算一个位置,一个字算2个位置
     *
     * @param string $string 待切割的字符串
     * @param int $length 切割长度
     * @param string $dot 尾缀
     */
    public function str_cut($string, $length, $dot = '') {
        $string = str_replace(array('&nbsp;', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), array(' ', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), $string);
        $strlen = strlen($string);
        if($strlen <= $length) return $string;
        $maxi = $length - strlen($dot);
        $strcut = '';
        // if(strtolower(CHARSET) == 'utf-8')
        // {
            $n = $tn = $noc = 0;
            while($n < $strlen)
            {
                $t = ord($string[$n]);
                if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                    $tn = 1; $n++; $noc++;
                } elseif(194 <= $t && $t <= 223) {
                    $tn = 2; $n += 2; $noc += 2;
                } elseif(224 <= $t && $t < 239) {
                    $tn = 3; $n += 3; $noc += 2;
                } elseif(240 <= $t && $t <= 247) {
                    $tn = 4; $n += 4; $noc += 2;
                } elseif(248 <= $t && $t <= 251) {
                    $tn = 5; $n += 5; $noc += 2;
                } elseif($t == 252 || $t == 253) {
                    $tn = 6; $n += 6; $noc += 2;
                } else {
                    $n++;
                }
                if($noc >= $maxi) break;
            }
            if($noc > $maxi) $n -= $tn;
            $strcut = substr($string, 0, $n);
        // }
        // else
        // {
        //     $dotlen = strlen($dot);
        //     $maxi = $length - $dotlen;
        //     for($i = 0; $i < $maxi; $i++)
        //     {
        //         $strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
        //     }
        // }
        $strcut = str_replace(array('&', '"', "'", '<', '>'), array('&amp;', '&quot;', '&#039;', '&lt;', '&gt;'), $strcut);
        return $strcut.$dot;
    }


    /**
     * 获取字符串首字母
     * @param $str
     * @return string
     */
    function getFirstCharter($str)
    {
        if (empty($str)) {
            return '';
        }
        $fchar = ord($str{0});
        if ($fchar >= ord('A') && $fchar <= ord('z'))
            return strtoupper($str{0});
        $s1 = iconv('UTF-8', 'gb2312', $str);
        $s2 = iconv('gb2312', 'UTF-8', $s1);
        $s = $s2 == $str ? $s1 : $str;
        $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
        if ($asc >= -20319 && $asc <= -20284)
            return 'A';
        if ($asc >= -20283 && $asc <= -19776)
            return 'B';
        if ($asc >= -19775 && $asc <= -19219)
            return 'C';
        if ($asc >= -19218 && $asc <= -18711)
            return 'D';
        if ($asc >= -18710 && $asc <= -18527)
            return 'E';
        if ($asc >= -18526 && $asc <= -18240)
            return 'F';
        if ($asc >= -18239 && $asc <= -17923)
            return 'G';
        if ($asc >= -17922 && $asc <= -17418)
            return 'H';
        if ($asc >= -17417 && $asc <= -16475)
            return 'J';
        if ($asc >= -16474 && $asc <= -16213)
            return 'K';
        if ($asc >= -16212 && $asc <= -15641)
            return 'L';
        if ($asc >= -15640 && $asc <= -15166)
            return 'M';
        if ($asc >= -15165 && $asc <= -14923)
            return 'N';
        if ($asc >= -14922 && $asc <= -14915)
            return 'O';
        if ($asc >= -14914 && $asc <= -14631)
            return 'P';
        if ($asc >= -14630 && $asc <= -14150)
            return 'Q';
        if ($asc >= -14149 && $asc <= -14091)
            return 'R';
        if ($asc >= -14090 && $asc <= -13319)
            return 'S';
        if ($asc >= -13318 && $asc <= -12839)
            return 'T';
        if ($asc >= -12838 && $asc <= -12557)
            return 'W';
        if ($asc >= -12556 && $asc <= -11848)
            return 'X';
        if ($asc >= -11847 && $asc <= -11056)
            return 'Y';
        if ($asc >= -11055 && $asc <= -10247)
            return 'Z';
        return "";
    }

    /**
     * 生成随机字符串
     * @param int $length 生成字符串的长度
     * @return string
     */
    public function createRandStr($length=6) {
        
        if (intval($length) <= 0) {
            $length = 6;
        }

        $arr = array('0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');

        if ($length > count($arr)) {
            $length = count($arr);
        }

        shuffle($arr);

        $arr = array_slice($arr, 0, $length);

        $arrString = implode('', $arr);

        return $arrString;
    }
	
	
	/**
	 * 生成单个邀请码(6位数字和字母组成)
	 * @return string
	 */
	public function makeSixCode()
	{
		$code = "ABCDEFGHIGKLMNOPQRSTUVWXYZ";
		
		$rand = $code[rand(0, 25)] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
		for (
			$a = md5($rand, true),
			$s = '0123456789ABCDEFGHIJKLMNOPQRSTUV',
			$d = '',
			$f = 0;
			$f < 6;
			$g = ord($a[$f]), // ord（）函数获取首字母的 的 ASCII值
			$d .= $s[($g ^ ord($a[$f + 8])) - $g & 0x1F],//按位亦或，按位与。
			$f++
		) ;
		
		return $d;
	}
 
 
}
