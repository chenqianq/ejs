<?php
class YfjUrlHandler extends ZcDefaultUrlHandler {
	
	/**
	 * build一个url
	 *
	 * @param
	 *        	$route
	 */
	public function buildUrl($route, $params = '', $ssl = false, $host = false) {
	    /* if( empty($route) ){
	        return $this -> ncUrl($params, $ssl, $host);
	    } */

        $url = $this->defalutUrl($route, $params, $ssl, $host);

		return $url;
	}
	
	private function brandUrl($route, $params, $scheme){
	    $domian = $this->getAppServer($scheme);
	    $paramArray = $this -> parseParams($params);
	    $brandId = $paramArray['brand_id'];
	    unset($paramArray['brand_id']);
	    $param = $this->buildUri($paramArray);
	     
	     
	     
	    $uri = 'brand/'.$brandId.'.html';
	     
	    $url = $domian .  $uri;
	    if($param) {
	        $url .= (strpos($url, '?') !== false) ? '&' : '?';
	    }
	     
	    return $url . $param;
	}
	
	private function categoriesUrl($route, $params, $scheme){
	    $domian = $this->getAppServer($scheme);
	    $paramArray = $this -> parseParams($params);
	    $categoriesId = $paramArray['categories_id'];
	    unset($paramArray['categories_id']);
	    $param = $this->buildUri($paramArray);
	    
	    
	    
	    $uri = 'classify/'.$categoriesId.'.html';
	    
	    $url = $domian .  $uri;
	    if($param) {
	        $url .= (strpos($url, '?') !== false) ? '&' : '?';
	    }
	    
	    return $url . $param;
	}
	
	private function articleUrl($route, $params, $scheme){
	    $domian = $this->getAppServer($scheme);
	    $paramArray = $this -> parseParams($params);
	    $articleId = $paramArray['article_id'];
	    unset($paramArray['article_id']);
	    $param = $this->buildUri($paramArray);
	     
	     
	    $uri = 'footer/'.$articleId.'.html';
	     
	    $url = $domian .  $uri;
	    if($param) {
	        $url .= (strpos($url, '?') !== false) ? '&' : '?';
	    }
	     
	    return $url . $param;
	}
	
	/**
	 * 购物车url
	 * @param unknown $route
	 * @param unknown $params
	 * @param unknown $scheme
	 */
	private function searchUrl($route, $params, $scheme){
	    $domian = $this->getAppServer($scheme);
	    $paramArray = $this -> parseParams($params);
	    $goodsId = $paramArray['goods_id'];
	    unset($paramArray['goods_id']);
	    $param = $this->buildUri($paramArray);
	
	
	    $uri = 'search.html';
	
	    $url = $domian .  $uri;
	    if($param) {
	        $url .= (strpos($url, '?') !== false) ? '&' : '?';
	    }
	
	    return $url . $param;
	}
	
	/**
	 * 购物车url
	 * @param unknown $route
	 * @param unknown $params
	 * @param unknown $scheme
	 */
	private function cartUrl($route, $params, $scheme){
	    $domian = $this->getAppServer($scheme);
	    $paramArray = $this -> parseParams($params);
	    $goodsId = $paramArray['goods_id'];
	    unset($paramArray['goods_id']);
	    $param = $this->buildUri($paramArray);
	     
	     
	    $uri = 'cart.html';
	     
	    $url = $domian .  $uri;
	    if($param) {
	        $url .= (strpos($url, '?') !== false) ? '&' : '?';
	    }
	
	    return $url . $param;
	}
	
	/**
	 * z支付流程url
	 * @param unknown_type $scheme
	 * @param unknown_type $params
	 * @return string
	 */
	private function goodsDetailUrl($route, $params, $scheme){
	    $domian = $this->getAppServer($scheme);
	    $paramArray = $this -> parseParams($params);
	    $goodsId = $paramArray['goods_id'];
	    unset($paramArray['goods_id']);
	    $param = $this->buildUri($paramArray);
	    
	    
	    $uri = 'product/'.$goodsId.'.html';
	
	    $url = $domian .  $uri;
	    if($param) {
	        $url .= (strpos($url, '?') !== false) ? '&' : '?';
	    }
	    return $url . $param;
	}
	
	/**
	 * 解析参数成数组
	 * @param unknown $params
	 */
	private function parseParams($params){
	    if( !$params ){
	        return [];
	    }
	    
	    if( is_array($params) ){
	        return $params;
	    }
	    
	    parse_str($params,$paramsArray);
	      
	    return $paramsArray;
	}
	 
	
	private function ncUrl($params, $ssl, $host){
	    $scheme = $this->getAppServer($ssl);
	    $port = '';
	    $baseRout = Zc::C('app.base.route');
	    $baseRout = empty($baseRout) ? 'index.php' : $baseRout;
	    $url = $scheme . ($port == 80 ? '' : '') . (Zc::C('app.enable.seo') == true? '/' : (empty($route)? 'shop/'.$baseRout :  'business/' )) . $route ;
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
		$url = $scheme . ($port == 80 ? '' : '') . (Zc::C('app.enable.seo') == true? '/' : (empty($route)? '' :  'business/' )) . $route.($route ? '.html' :'') ;
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