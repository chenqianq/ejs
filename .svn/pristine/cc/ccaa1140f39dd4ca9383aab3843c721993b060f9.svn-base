<?php
class HtmlTool {
	
	/**
	 * 获取静态资源文件
	 *
	 * @param unknown_type $filename        	
	 */
	public static function getStaticFile($filename) {
		$files = array ();
		$static = array ();
		$staticDir = Zc::C ( ZcConfigConst::DirWsViewsStatic );
		global $request_type;
		
		if (is_array ( $filename )) {
			$files = $filename;
		} elseif (is_string ( $filename )) {
			$files [] = $filename;
		}
		if (! empty ( $files )) {
			foreach ( $files as $file ) {
				$timemap = '';
				if (strpos ($file, '?') !== false) {
					list ( $file, $timemap ) = explode ( '?', $file );
					$timemap = empty($timemap)? '' : '?' . $timemap;
				}
				$ext = strrchr ( $file, '.' );
				$base = substr ( $file, 0, strlen ( $ext ) );
				$static [$ext] [] = $file . $timemap;
			}
		}
		
		//静态支援url
		$staticUrl = Zc::C('app.https.img_cdn1'); ///($request_type == 'SSL') ? HTTPS_SERVER : ( G_IS_CN_IP ? G_HTTP_HOST_TMART : G_STATIC_IMAGE_TMART_COM);
		if (! empty ( $static )) {
			$output = '';
			foreach ( $static as $ext => $files ) {
				switch (strtolower ( $ext )) {
					case '.css' :
						foreach ( $files as $f ) {
							$output .= '<link rel="stylesheet" href="' . $staticUrl . '/' . $staticDir . 'css/' . $f . '" />' . "\n";
						}
						break;
					case '.js' :
						foreach ( $files as $f ) {
							$output .= ' <script src="' . $staticUrl . '/' . $staticDir . 'jscript/' . $f . '"></script>' . "\n";
						}
						break;
					case '.jpg' :
					case '.gif' :
					case '.png' :
						foreach ( $files as $f ) {
							$output = $staticUrl . '/' . $staticDir . 'images/' . $f;
						}
						break;
				}
			}
		}
		return $output;
	}
	
	/**
	 * 获取Tmart那边的JS文件
	 *
	 * @param unknown_type $filename
	 */
	public static function js($filename, $timestamp='') {
		return zen_js($filename, $timestamp);
	}
	
	/**
	 * build一个商品图片
	 *
	 * @param unknown_type $productsImgSrc        	
	 * @param unknown_type $sizeType        	
	 * @return string
	 */
	public static function buildProductImgSrc($productsImgSrc = '', $sizeType = '150', $ssl = FALSE) {
		$imgLinks = '';
		if (empty ( $productsImgSrc ) || strpos ( $productsImgSrc, '.' ) === false) {
			return $imgLinks;
		}
		$sizeArray = array (
				'60' => '60x60',
				'150' => '150x150',
				'320' => '320x320',
				'600' => '600x600' 
		);
		$sizeType = (empty ( $sizeType ) || array_key_exists ( $sizeType, $sizeArray ) == false) ? '150' : $sizeType;
		$timemap = '';
		list ( $baseImgsrc, $timemap ) = explode ( '?', $productsImgSrc );
		list ( $base, $ext ) = explode ( '.', $baseImgsrc );
		$cndUrl = self::getCdnRandDomain ( $base, $ssl );
		$imgLinks = $cndUrl . $base . '_' . $sizeArray [$sizeType] . '.' . $ext . ((! empty ( $timemap )) ? '?' . $timemap : '');
		return $imgLinks;
	}
	
	/**
	 * 随机取得图片域名规则用于cdn
	 * 随机取得图片域名规则如下：
	 * 1.md5图片的url
	 * 2.转换成ASCII码然后相加
	 * 3.相加的结果对3取模+1 得掉的数即是对于的url
	 * 比如结果是1对应的是www1, 2对应的是www2, ...... 以此类推
	 */
	protected static function getCdnRandDomain($src, $ssl = false) {
		$md5scr = md5 ( $src );
		$total = 0;
		for($i = 0; $i < 3; $i ++) {
			$total += ord ( substr ( $md5scr, $i, 1 ) );
		}
		$numUrl = ($total % 3) + 1;
		switch ($numUrl) {
			case 1 :
				$cdnUrl = ($ssl == true) ? Zc::C ( 'app.https.img_cdn1' ) : Zc::C ( 'app.http.img_cdn1' );
				break;
			case 2 :
				$cdnUrl = ($ssl == true) ? Zc::C ( 'app.https.img_cdn2' ) : Zc::C ( 'app.http.img_cdn2' );
				break;
			case 3 :
				$cdnUrl = ($ssl == true) ? Zc::C ( 'app.https.img_cdn3' ) : Zc::C ( 'app.http.img_cdn3' );
				break;
			default :
				$cdnUrl = ($ssl == true) ? Zc::C ( 'app.https.img_cdn' ) : Zc::C ( 'app.http.img_cdn' );
				break;
		}
		return $cdnUrl;
	}
	
	/**
	 * 价格格式化
	 *
	 * @param unknown_type $price        	
	 * @param unknown_type $currency        	
	 * @return Ambigous <string, number>
	 */
	public static function priceFormat($price, $calculate_currency_value = true, $currency = 'USD', $currency_value = '') {
		global $currencies;
		$currencies = empty($currencies)? new currencies() : $currencies;
		$currency = empty ( $currency ) ? ZC::C('app.default.currency.code') : $currency;
		return $currencies->format ($price, $calculate_currency_value, $currency, $currency_value);
	}
	
	
	/**
	 * 获取价格数据
	 *
	 * @return Ambigous <string, number>
	 */
	public static function getCurrencies() {
		global $currencies;
		return $currencies->currencies;
	}
	
	/**
	 * 根据给定的价格，得到计算后所对应的货币价格
	 */
	public static function getCurrenciesValue($number, $calculate_currency_value = true, $currency_type = '', $currency_value = '') {
		global $currencies;
		return $currencies->value($number, $calculate_currency_value = true, $currency_type, $currency_value);
	}
	
	/**
	 * 生产一个slelect的下拉框
	 *
	 * @param unknown_type $name        	
	 * @param unknown_type $values        	
	 * @param unknown_type $default        	
	 * @param unknown_type $parameters        	
	 * @return string
	 */
	public static function drawSelectFiled($name, $values, $default, $parameters = '') {
		$field = '<select name="' . self::outputString( $name ) . '"';
		if (! empty ( $parameters )) {
			$field .= ' ' . $parameters;
		}
		$field .= '>' . "\n";
		for($i = 0, $n = sizeof ( $values ); $i < $n; $i ++) {
			$field .= '  <option value="' . self::outputString ( $values [$i] ['id'] ) . '"';
			if ($default == $values [$i] ['id']) {
				$field .= ' selected="selected"';
			}
			$field .= '>' . self::outputString ( $values [$i] ['text'], array (
					'"' => '&quot;',
					'&' => '&',
					'\'' => '&#039;',
					'<' => '&lt;',
					'>' => '&gt;' 
			) ) . '</option>' . "\n";
		}
		$field .= '</select>' . "\n";
		return $field;
	}
	
	/**
	 * 安全输出html
	 *
	 * @param string $string        	
	 * @param 是否转义 $translate        	
	 * @param 是否转义html实体 $protected        	
	 * @return string
	 */
	public static function outputString($string, $translate = false, $protected = false) {
		if ($protected == true) {
			return htmlspecialchars ( $string );
		} else {
			if ($translate == false) {
				return strtr ( trim ( $string ), array (
						'"' => '&quot;' 
				) );
			} else {
				return strtr ( trim ( $string ), $translate );
			}
		}
	}
	
	/**
	 * 表达的原声提交
	 * @param string $string 提交数据
	 * @return string|Ambigous <unknown, string>
	 */
	public static function prepareInput($string) {
		if (is_string ( $string )) {
			$string = preg_replace ( '/ +/', ' ', $string );
			$string = preg_replace ( "/[<>]/", '_', $string );
			return trim ( stripslashes ( $string ) );
		} elseif (is_array ( $string )) {
			reset ( $string );
			while ( list ( $key, $value ) = each ( $string ) ) {
				$string [$key] = self::prepareInput ( $value );
			}
			return $string;
		} else {
			return $string;
		}
	}
	
	/**
	 * 生产一个Form表单
	 * 
	 * @param string $name        	
	 * @param string $action        	
	 * @param string $method        	
	 * @param string $parameters        	
	 * @return string
	 */
	public static function drawForm($name, $action, $method = 'post', $parameters = '') {
		$form = '<form name="' . self::outputString ( $name ) . '" action="' . self::outputString ( $action ) . '" method="' . self::outputString ( $method ) . '"';
		if (! empty ( $parameters )) {
			$form .= ' ' . $parameters;
		}
		$form .= '>';
		return $form;
	}
	
	/**
	 * 输出一个input表单
	 */
	public static function drawInputField($name, $value = '', $parameters = '', $type = 'text') {
		$field = '<input type="' . self::outputString ( $type ) . '" name="' . self::outputString ( $name ) . '"';
		if (! empty ( $value )) {
			$field .= ' value="' . self::outputString ( $value ) . '"';
		}
		if (! empty ( $parameters )) {
			$field .= ' ' . $parameters;
		}
		$field .= ' />';
		return $field;
	}
	
	/**
	 * 输出一个input表单
	 */
	public static function drawSubmitField($value = '', $parameters = '', $type = 'submit') {
		$field = '<input type="' . self::outputString ( $type ) . '"';
		if (! empty ( $value )) {
			$field .= ' value="' . self::outputString ( $value ) . '"';
		}
		if (! empty ( $parameters )) {
			$field .= ' ' . $parameters;
		}
		$field .= ' />';
		return $field;
	}
	
	/**
	 * 输出一个password表单
	 */
	public static function drawPasswordField($name, $value = '', $parameters = 'maxlength="40"') {
		return self::drawInputField ( $name, $value, $parameters, 'password' );
	}
	
	/**
	 * 输出一个selection表单
	 */
	public static function dawSelectionField($name, $type, $value = '', $checked = false, $parameters = '') {
		$selection = '<input type="' . self::outputString ( $type ) . '" name="' . self::outputString ( $name ) . '"';
		if (! empty ( $value )) {
			$selection .= ' value="' . self::outputString ( $value ) . '"';
		}
		if ($checked == true) {
			$selection .= ' checked="checked"';
		}
		if (! empty ( $parameters )) {
			$selection .= ' ' . $parameters;
		}
		$selection .= ' />';
		return $selection;
	}
	
	/**
	 * 输出一个checkbox表单
	 */
	public static function drawCheckboxField($name, $value = '', $checked = false, $parameters = '') {
		return self::dawSelectionField ( $name, 'checkbox', $value, $checked, $parameters );
	}
	
	/**
	 * 输出一个radio表单
	 */
	public static function drawRadioField($name, $value = '', $checked = false, $parameters = '') {
		return self::dawSelectionField ( $name, 'radio', $value, $checked, $parameters );
	}
	
	/**
	 * 输出一个textarea表单
	 */
	public static function drawTextareaField($name, $wrap = 'virtual', $width, $height, $text = '', $parameters = '') {
		$field = '<textarea name="' . self::outputString ( $name ) . '" wrap="' . self::outputString ( $wrap ) . '" cols="' . zen_output_string ( $width ) . '" rows="' . zen_output_string ( $height ) . '"';
		if (! empty ( $parameters )) {
			$field .= ' ' . $parameters;
		}
		$field .= '>';
		if (! empty ( $text )) {
			$field .= $text;
		}
		$field .= '</textarea>';
		return $field;
	}
	
	/**
	 * 输出一个hidden表单
	 */
	public static function drawHiddenField($name, $value = '', $parameters = '') {
		$field = '<input type="hidden" name="' . self::outputString ( $name ) . '"';
		if (! empty ( $value )) {
			$field .= ' value="' . self::outputString ( $value ) . '"';
		}
		if (! empty ( $parameters )) {
			$field .= ' ' . $parameters;
		}
		$field .= ' />';
		return $field;
	}
	
	/**
	 *  取得模板目录下widget的文件路径
	 * @param unknown_type $tpl
	 */
	public static function getWidgetTpl($tpl = '') {
		$tpl = strpos('.php', $tpl) === true? $tpl : $tpl . '.php';
		$filename = Zc::C(ZcConfigConst::DirFsViewsWidget) . $tpl;
		if(file_exists($filename)) {
			return $filename;
		}
		return false;
	}
	
	/**
	 * 清楚HTML标签
	 * @param unknown_type $tpl
	 */
	public static function cleanHtml($string) {
	
		$string = preg_replace('/\r/', ' ', $string);
		$string = preg_replace('/\t/', ' ', $string);
		$string = preg_replace('/\n/', ' ', $string);
	
		$string= nl2br($string);
	
		while (strstr($string, '<br>')) $string = str_replace('<br>', ' ', $string);
		while (strstr($string, '<br />')) $string = str_replace('<br />', ' ', $string);
		while (strstr($string, '<br/>')) $string = str_replace('<br/>', ' ', $string);
		while (strstr($string, '<p>')) $string = str_replace('<p>', ' ', $string);
		while (strstr($string, '</p>')) $string = str_replace('</p>', ' ', $string);
		while (strstr($string, '  ')) $string = str_replace('  ', ' ', $string);
	
		$string = strip_tags($string);
		
		return $string;
	}
	
	/**
	 * 获取get数组
	 * @param unknown_type $exclude_array
	 * @param unknown_type $return
	 * @return Ambigous <string, multitype:string >
	 */
	public static function getAllGetParams($exclude_array = '', $return = 'array') {
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
     * webView网页调用小程序的图片文件夹图片
     *
     * @param unknown_type $filename
     */
    public static function getWxAppStaticFile($filename) {
        $files = array ();
        $static = array ();
        $staticDir = 'view_controller/yifanjie-wxapp/views/page/';

        if (is_array ( $filename )) {
            $files = $filename;
        } elseif (is_string ( $filename )) {
            $files [] = $filename;
        }
        if (! empty ( $files )) {
            foreach ( $files as $file ) {
                $timemap = '';
                if (strpos ($file, '?') !== false) {
                    list ( $file, $timemap ) = explode ( '?', $file );
                    $timemap = empty($timemap)? '' : '?' . $timemap;
                }
                $ext = strrchr ( $file, '.' );
                $base = substr ( $file, 0, strlen ( $ext ) );
                $static [$ext] [] = $file . $timemap;
            }
        }

        //静态支援url
        $staticUrl = Zc::C('app.https.img_cdn1');
        if (! empty ( $static )) {
            $output = '';
            foreach ( $static as $ext => $files ) {
                switch (strtolower ( $ext )) {
                    case '.css' :
                        foreach ( $files as $f ) {
                            $output .= '<link rel="stylesheet" href="' . $staticUrl . '/' . $staticDir . 'css/' . $f . '" />' . "\n";
                        }
                        break;
                    case '.js' :
                        foreach ( $files as $f ) {
                            $output .= ' <script src="' . $staticUrl . '/' . $staticDir . 'jscript/' . $f . '"></script>' . "\n";
                        }
                        break;
                    case '.jpg' :
                    case '.gif' :
                    case '.png' :
                        foreach ( $files as $f ) {
                            $output = $staticUrl . '/' . $staticDir . 'images/' . $f;
                        }
                        break;
                }
            }
        }
        return $output;
    }
}