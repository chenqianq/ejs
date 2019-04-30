<?php
class YfjUrlHandler extends ZcDefaultUrlHandler {
	
	/**
	 * build一个url
	 *
	 * @param
	 *        	$route
	 */
	public function buildUrl($route, $params = '', $ssl = false, $host = false) {
	    if( empty($route) ){
	        return $this -> ncUrl($params, $ssl, $host);
	    }
	    
		return $this->defalutUrl($route, $params, $ssl, $host);
	}
	
	private function ncUrl($params, $ssl, $host){
	    $scheme = $this->getAppServer($ssl);
	    $port = '';
	    $baseRout = Zc::C('app.base.route');
	    $baseRout = empty($baseRout) ? 'index.php' : $baseRout;
	    $url = $scheme . ($port == 80 ? '' : '') . (Zc::C('app.enable.seo') == true? '/' : (empty($route)? 'shop/'.$baseRout :  'zc/' )) . $route ;
	    //$url = $domain . (Zc::C('app.enable.seo') == true? '/' : (empty($route)? '' : '/zc/' )) . $route;
	    $uri = $this->buildUri($params);
	    if(!empty($uri)) {
	        $url .= (strpos($url, '?') !== false)? ('&' . $uri) : ('?' . $uri);
	    }
	    return $url;
	}
	
	/**
	 * 默认的链接方法
	 * @param string $route 路由
	 * @param array $params 参数数组
	 * @param bool $ssl 是否是ssl链接
	 * @return string url
	 */
	private function defalutUrl ( $route, $params, $ssl, $host ) {
		$scheme = $this->getAppServer($ssl);
		$port = '';
		$baseRout = Zc::C('app.base.route');
		$baseRout = empty($baseRout) ? 'index.php' : $baseRout;
		$url = $scheme . ($port == 80 ? '' : '') . (Zc::C('app.enable.seo') == true? '/' : (empty($route)? '' :  'ejs-admin/' )) . $route ;
		//$url = $domain . (Zc::C('app.enable.seo') == true? '/' : (empty($route)? '' : '/zc/' )) . $route;
		$uri = $this->buildUri($params);
		if(!empty($uri)) {
			$url .= (strpos($url, '?') !== false)? ('&' . $uri) : ('?' . $uri);
		}
		return $url;
	}
	
	/**
	 * 放回当前网站的域名
	 * @param bool $ssl 是否是ssl链接
	 * @return string url
	 */
	private function getAppServer($ssl = false) {
		$domain = ($ssl == true && Zc::C('app.enable.ssl') == true) ? Zc::C ( 'app.https.domain' ) : Zc::C ( 'app.http.domain' );
		return empty($domain) ? $_SERVER['HTTP_HOST'] : $domain;
	}
	
	/**
	 * 拼接uri参数
	 * @param array $params
	 * @return string
	 */
	private function buildUri($params = array()) {
		if (is_array ( $params )) {
			$tmp = '';
			foreach ( $params as $key => $val ) {
				if(!empty($val) || is_numeric($val)){
					$tmp .= '&' . $key . '=' . $val;
				}
			}
			$params = $tmp;
		}
	
		if ($params) {
			$url .= ltrim ( $params, '&' );
		}
	
		return $url;
	}
}

?>