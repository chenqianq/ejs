<?php

class HelperFactory {
	
	//缓存对象仓库
	private static $object_repos = array();
	
	/**
	 * 获取Service类对象
	 * @param  $module
	 * @param  $service_class
	 * @throws Exception
	 */
	private static function innerGetHelper($class_name, $singleton, $param) {
		$php_path = Zc::C('helpers.dir.fs') . '' . "class." . $class_name . "Helper.php";
       
		//如果不存在到类目录名下面找
		if(!file_exists($php_path)){
			$php_path =Zc::C('helpers.dir.fs') . '' . strtolower($class_name) ."/class." . $class_name . "Helper.php";
		}
		
		//定义单例的key
		$php_path_key = md5($php_path);
		
		//如果是单例的，并且已经缓存该对象了，直接返回
		if($singleton && isset(self::$object_repos[$php_path_key])) {
			return self::$object_repos[$php_path_key];
		} 

		if (require_once ($php_path)) {
            $obj =  new $class_name($param);
            
            //如果是单例的，需要缓存对象
            if ($singleton) {
            	self::$object_repos[$php_path_key] = $obj;
            }
            return $obj;
        } else {
            throw new Exception ($class_name . ' not found');
        }
	}
	
	/**
	 * 获取ProductService对象
	 */
	public static function getHelperClass($class_name = NULL, $singleton = true, $param ) {
		if(!isset($class_name)){
			return false;
		}
		
		return self::innerGetHelper($class_name, $singleton, $param);
	}
	
	
	/**
	 * DB相关的扩展方法, 等等
	 * @param array $params
	 * @return DbExtend
	 */
	public static function getDbExtendHelper( $params=NULL) {
		return self::innerGetHelper('DbExtend', true, $params);
	}
	
	
	/**
	 * 获得Debug调试类
	 * @return Debug
	 * */
	public static function getDebugHelper(){
		return self::getHelperClass('Debug');
	}
	
	/**
	 * 获取Ip类
	 * @return Ip
	 */
	public static function getIpHelper() {
		return self::getHelperClass('Ip');
	}
	
	/**
	 * 获取Safe 安全类
	 * @return Safe
	 */
	public static function getSafeHelper() {
		return self::getHelperClass('Safe');
	}
	
	
	/**
	 * 获取Cookie类
	 * @return Cookie
	 * */
	public static function getCookieHelper() {
		return self::getHelperClass('Cookie');

	}
	
	/**
	 * WechatHelper
	 * @return WechatHelper
	 */
	public static function getWechatHelper() {
		return self::getHelperClass('Wechat');

	}
	
	/**
	 * search helper 分页类
	 * @return PageSplit
	 */
	public static function getPageSplitHelper($params) {
		return self::getHelperClass('PageSplit', false, $params);

	}
	
	/**
	 * 文件编码 修正/转换 类
	 * @return CodeSwitch
	 */
	public static function getCodeSwitchHelper() {
		return self::getHelperClass('CodeSwitch', false);
	
	}

	/**
	 * post过来的字段验证
	 * @param array $params
	 * @return FieldsValid
	 */
	public static function getFieldsValidHelper($params) {
		return self::getHelperClass('FieldsValid', false, $params);
	}

	/**
	 * Url 相关方法
	 * @param array $params
	 * @return Url
	 */
	public static function getUrlHelper() {
		return self::getHelperClass('Url', false);
	}
	
	/**
	 * @return Curl
	 */
	public static function getCurlHelper($params) {
		return self::getHelperClass('Curl', false, $params);
	}
	/**
	 * AbTest 相关方法
	 * @param array $params
	 * @return AbTest
	 */
	public static function getAbTestHelper() {
		return self::getHelperClass('AbTest', false);
	}
	/**
	 * CSV 解析的相关方法
	 * @param array $params
	 * @return CSVParse
	 */
	public static function getCSVParseHelper() {
		return self::getHelperClass('CSVParse', false);
	}
	
	/**
	 * 获取XmlService对象
	 */
	public static function getXmlHelper($params) {
		return self::getHelperClass('Xml', false, $params);
	}
	
	/**
	 * 联邦快递
	 * @return FedEx
	 */
	public static function getFedExHelper() {
		return self::getHelperClass('FedEx', false);
	}
	
	/**
	 * FPDF
	 * @return FPDF
	 */
	public static function getFPDFHelper() {
		return self::getHelperClass('FPDF', false);
	}
	
	/**
	 * 谷歌自动翻译
	 * @return GoogleTranslate
	 */
    public static function getGoogleTranslateHelper() {
    	return self::getHelperClass('GoogleTranslate', false);
    }
	
	/**
	 * 加密类
	 * @return Encryption
	 */
    public static function getEncryptionHelper() {
    	return self::getHelperClass('Encryption', false);
    }
	
    /**
     * code  的定义消息
     * @return CodeMessage
     */
    public static function getCodeMessageHelper() {
        return self::getHelperClass('CodeMessage', false);
    }
    /**
     * code  的定义消息
     * @return Sms
     */
    public static function getSmsHelper() {
        return self::getHelperClass('Sms', false);
    }
    
    /**
     * Image
     * @return Image
     */
    public static function getImageHelper() {
        return self::getHelperClass('Image', false);
    }
    
    /**
     * Captcha
     * @return Captcha
     */
    public static function getCaptchaHelper($configure) {
        return self::getHelperClass('Captcha', false,$configure);
    }

    /**
     * 推广系统CpaCaptcha
     * @return Captcha
     */
    public static function getCpaCaptchaHelper($param) {
        return self::getHelperClass('CpaCaptcha', false, $param);
    }


      /**
     * ReturnMessage
     * @return ReturnMessage
     */
    public static function getReturnMessageHelper() {
        return self::getHelperClass('ReturnMessage', false);
    }
	/**
	 * 上传文件类
	 * @return Captcha
	 */
	public static function getUploadFileHelper($params) {
		return self::getHelperClass('UploadFile', false,$params);
	}

	/**
	 * 图片处理类
	 * @return Captcha
	 */
	public static function getResizeImageHelper() {
		return self::getHelperClass('ResizeImage', false,'');
	}

	/**
	 * 公共函数类
	 * @return Common
	 */
	public static function getCommonHelper() {
		return self::getHelperClass('Common', false,'');
	}
	/**
	 * 分页帮助类
	 * @return Page
	 */
	public static function getPageHelper() {
		return self::getHelperClass('Page', false,'');
	}
	

	/**
	 * Excel类
	 * @return Excel
	 */
	public static function getExcelHelper() {
		return self::getHelperClass('Excel', false,'');
	}

	/**
	 * post验证类
	 * @return Validate
	 */
	public static function getValidateHelper() {
		return self::getHelperClass('Validate', false,'');
	}

	/**
	 * 内容编辑器类
	 * @return Captcha
	 */
	public static function getEditorHelper() {
		return self::getHelperClass('Editor', false,'');
	}
	
	/**
	 * 订单状态帮助类
	 * @return Order
	 */
	public static function getOrderHelper() {
	    return self::getHelperClass('Order', false,'');
	}

	/**
	 * 标签帮助类
	 * @return Order
	 */
	public static function getLabelHelper() {
	    return self::getHelperClass('Label', false,'');
	}

	/**
	 * 邀请码帮助类
	 */
	public static function getInviterCodeHelper() {
		return self::getHelperClass('InviterCode', false, '');
	}

	public static function getFilterHelper()
    {
        return self::getHelperClass('Filter', false, '');
    }
	/**
	 * Search
	 */
	public static function getSearchHelper() {
		return self::getHelperClass('Search', false, '');
	}

    /**
     * AseSafe
     */
    public static function getAseSafeHelper() {
        return self::getHelperClass('AseSafe', false, '');
    }

    /**
     * RsaSafe
     */
    public static function getRsaSafeHelper() {
        return self::getHelperClass('RsaSafe', false, '');
    }

	/**
	 * SourceCodeEncryption
	 */
	public static function getSourceCodeEncryptionHelper() {
		return self::getHelperClass('SourceCodeEncryption', false, '');
	}


    /**
     * Site
     */
    public static function getSiteHelper()
    {
        return self::getHelperClass('Site', false, '');
    }

    /**
     * ActivityModular
     */
    public static function getActivityModular() {
        return self::getHelperClass('ActivityModular', false, '');
    }

    /**
     * FormatHtml
     */
    public static function getFormatHtmlHelper() {
        return self::getHelperClass('FormatHtml', false, '');
    }

    /**
     * Qinkaint
     * @return Qinkaint
     */
    public static function getQinkaintHelper() {
        return self::getHelperClass('Qinkaint', false, '');
    }
}
