<?php
/**
 * 分页类，使用范例：<br />
 * <pre>
 * $page = new ZcPagination(300, 10, $_GET['page']);
 * //修改配置，可选的操作
 * $page->config(array('themeCssClass' => 'zc-pagination-compact-theme', 'edgeLen' => 1));
 * $page->renderLinks();
 * </pre>
 *
 * @author kinsly
 *
 */
class ZcPagination {
	private $totalNum;
	private $currPageNo = 1;
	private $totalPageNum;
	private $pageSize = 20;

	//构造分页的URL
	private $route;
	private $params = '';
	private $defautUseGetParams = true;
	private $scheme = false;
	private $pageParamName = 'page';

	//构造分页的文案
	private $prevATitle = 'Previous Page';
	private $prevText = '&lt;&lt;';

	private $nextATitle = 'Next Page';
	private $nextText = '&gt;&gt;';

	private $pageATitle = 'Page %u';

	//分页样式
	private $themeCssClass = 'zc-pagination-light-theme';
	
	private $ulCssClass = '';
	
	private $pageContent = '';

	//构造分页的布局
	private $edgeLen = 2;
	private $minSideLen = 9;
	private $maxSideLen = 12;
	private $midLen = 9;
	private $remainLen = 4;
	
	private $click = '';
	
	private $zcCurrent = 'zc-current';

	private $layout = array();

	public function __construct($totalNum, $pageSize, $currPageNo, $otherConfig = array()) {
		if ($totalNum) {
			$otherConfig['totalNum'] = (int)$totalNum;
		}
		if ($pageSize) {
			$otherConfig['pageSize'] = (int)$pageSize;
		}
		if ($currPageNo) {
			$otherConfig['currPageNo'] = (int)$currPageNo;
		}
		 
		//conf.php的配置，会成为整个应用级别的覆盖
		$config = array_merge(Zc::C(ZcConfigConst::Pagination), $otherConfig);

		$this->setDefaultValue();
		$this->config($config);
	}

	private function setDefaultValue() {
		$this->currPageNo = isset($_GET[$this->pageParamName]) ? (int)$_GET[$this->pageParamName] : 1;
		
		if ($this->defautUseGetParams) {
			$this->route = $_GET['route'];
			$this->params = $_GET;
			unset($this->params['route'], $this->params['_route_']);
		}
	}

	/**
	 * 覆盖与重新计算分页类的各种参数
	 *
	 * @param array $config
	 */
	public function config($config) {
		if (count($config) == 0) {
			return;
		}
		foreach($config as $key => $value) {
			$this->$key = $value;
		}

		return $this->calcPageLayout();
	}

	/**
	 * 计算分页布局
	 *
	 * @return boolean
	 */
	private function calcPageLayout() {
		if ($this->pageSize <= 0) {
			return false;
		}
		$this->totalPageNum = (int)ceil($this->totalNum / $this->pageSize);

		if ($this->currPageNo > $this->totalPageNum) {
			$this->currPageNo = $this->totalPageNum;
		}

		if ($this->totalPageNum <= $this->maxSideLen) {
			$this->layout = range(1, $this->totalPageNum);
		} else {
			//totalPageNum > maxSideLen的情况，根据currPageNo的位置不同而不同
			if (($this->currPageNo + $this->remainLen) <= $this->minSideLen) {

				$this->layout = $this->calcLeftFirst($this->minSideLen);

			} else if (($this->currPageNo + $this->remainLen) <= $this->maxSideLen) {

				$this->layout = $this->calcLeftFirst(($this->currPageNo + $this->remainLen));

			} else if (($this->currPageNo - $this->remainLen) > ($this->totalPageNum - $this->minSideLen)) {

				$this->layout = $this->calcRightFirst($this->totalPageNum - $this->minSideLen + 1);

			} else if (($this->currPageNo - $this->remainLen) > ($this->totalPageNum - $this->maxSideLen)) {

				$this->layout = $this->calcRightFirst($this->currPageNo - $this->remainLen);

			} else {
				$this->layout = $this->calcMid();
			}
		}
	}

	private function calcLeftFirst($realRightEnd) {
		$startLeft = 1;
		$startRight = $realRightEnd;

		$endLeft = $this->totalPageNum - $this->edgeLen + 1;
		$endRight = $this->totalPageNum;

		if (($startRight + 2) < $endLeft) {
			$mid = array('…');
		} else {
			$mid = array($startRight + 1);
		}

		return array_merge(range($startLeft, $startRight), $mid, range($endLeft, $endRight));
	}

	private function calcRightFirst($realLeftStart) {
		$endLeft = $realLeftStart;
		$endRight = $this->totalPageNum;

		$startLeft = 1;
		$startRight = $this->edgeLen;

		if (($startRight + 2) < $endLeft) {
			$mid = array('…');
		} else {
			$mid = array($startRight + 1);
		}

		return array_merge(range($startLeft, $startRight), $mid, range($endLeft, $endRight));
	}

	private function calcMid() {
		$halfMidlen = intval(($this->midLen - 1) / 2);
		$midStart = (($this->currPageNo - $halfMidlen) >= 1) ? ($this->currPageNo - $halfMidlen) : 1;
		$midEnd =  (($this->currPageNo + $halfMidlen) <= $this->totalPageNum) ? ($this->currPageNo + $halfMidlen) : $this->totalPageNum;

		$startLeft = 1;
		$startRight = $this->edgeLen;

		if (($startRight + 2) < $midStart) {
			$margin1 = array('…');
		} else {
			$margin1 = array($startRight + 1);
		}

		$endLeft = $this->totalPageNum - $this->edgeLen + 1;
		$endRight = $this->totalPageNum;

		if (($midEnd + 2) < $endLeft) {
			$margin2 = array('…');
		} else {
			$margin2 = array($startRight + 1);
		}

		return array_merge(range($startLeft, $startRight), $margin1, range($midStart, $midEnd), $margin2, range($endLeft, $endRight));
	}

	/**
	 * 构造指定页码的URL
	 *
	 * @param int $pageNo
	 */
	private function buildPageUrl($pageNo) {
		
		if ($this->route) {
			$params = $this->params;
			
			if (is_string($this->params)) {
				parse_str($this->params, $params);
			}

			//TODO
			unset($params['main_page']);
			unset($params[$this->pageParamName]);
			if ($pageNo > 1) {
				$params[$this->pageParamName] = $pageNo;
			}
 
			return Zc::url($this->route, $params, $this->scheme);
		} else {
			$requestUri = $_SERVER["REQUEST_URI"];
			
			$urlArray = parse_url($requestUri);
			$path = $urlArray['path'];
			$query = $urlArray['query'];
			parse_str($query, $params);

			unset($params[$this->pageParamName]);
			if ($pageNo > 1) {
				$params[$this->pageParamName] = $pageNo;
			}

			if (count($params) > 0) {
				$requestUri = $path . '?' . http_build_query($params);
			} else {
				$requestUri = $path;
			}

			return $requestUri;
		}
	}

	public function getCurrenPage(){
		return $this -> currPageNo;
	}
	
	public function getPageSize(){
		return $this -> pageSize;
	}
	
	public function getTotalNum(){
		return $this -> totalNum;
	}
	
	/**
	 * 渲染分页链接
	 *
	 * @return string
	 */
	public function renderLinks() {
		if ($this->totalPageNum == 0) {
			return '';
		}
		
		$displayStr = sprintf('<div class="pagination zc-pagination %s"><ul class="%s">', $this->themeCssClass,$this -> ulCssClass);
		
		// 渲染“上一页”按钮
		if ($this->currPageNo == 1) {
			$displayStr .= '<li class="zc-prev"><span class="'.$this -> zcCurrent.'">' . $this->prevText .'</span></li>';
		} else {
			$pageNo = $this->currPageNo - 1;
			if( $this -> click ){
			    $pageUrl = sprintf('javascript:'.$this -> click,$pageNo);
			}
			else{
			    $pageUrl = $this->buildPageUrl($pageNo);
			}
			
			
			$displayStr .= sprintf('<li class="zc-prev"><a href="%s" title="%s" data-page-no="%d" rel="nofollow">%s</a></li>', Zc::shm($pageUrl), $this->prevATitle, $pageNo, $this->prevText);
		}
		
		// 渲染中间的分页按钮
		foreach ($this->layout as $page) {
			if( $this -> pageContent ){
				$pageContent = sprintf($this -> pageContent,$page);
			} 
			else{
				$pageContent = $page;
			}
			
			if (is_int($page)) {
				if ($page == $this->currPageNo) {
					$displayStr .= '<li class="active"><span class="'.$this -> zcCurrent.'">' . $pageContent .'</span></li>';
				} else {
				    if( $this -> click ){
				        $pageUrl = sprintf('javascript:'.$this -> click,$page);
				    }
				    else{
				        $pageUrl = $this->buildPageUrl($page); 
				    }
					
					$displayStr .= sprintf('<li><a href="%s" title="%s" data-page-no="%d" rel="nofollow">%s</a></li>', Zc::shm($pageUrl), Zc::shm(sprintf($this->pageATitle, $page)), $page, $pageContent);
				}
			} else {
				$displayStr .= '<li class="disabled"><span class="zc-ellipse">…</span></li>';
			}
		}

		// 渲染“下一页”按钮
		if ($this->currPageNo == $this->totalPageNum) {
			$displayStr .= '<li class="zc-next"><span class="'.$this -> zcCurrent.'">' . $this->nextText .'</span></li>';
		} else {
			$pageNo = $this->currPageNo + 1;
			if( $this -> click ){
			    $pageUrl = sprintf('javascript:'.$this -> click,$pageNo);
			}
			else{
			    $pageUrl = $this->buildPageUrl($pageNo);
			}
			
			
			$displayStr .= sprintf('<li class="zc-next"><a href="%s" title="%s" data-page-no="%d" rel="nofollow" >%s</a></li>', Zc::shm($pageUrl), $this->nextATitle, $pageNo, $this->nextText);
		}

		$displayStr .= "</ul></div>";
       
		return $displayStr;
	}
	
	/**
	 * 渲染分页显示数量，这个一般在后台用
	 *
	 * @return string
	 */
	public function displayCount($rend = 'reviews') {
		$to1 = ($this->currPageNo - 1) * $this->pageSize + 1;
		$to2 = ($this->currPageNo) * $this->pageSize;
		$to2 = $to2 <= $this->totalNum ? $to2 : $this->totalNum;
		return "Displaying {$to1} to {$to2} (of {$this->totalNum} {$rend})";
	}
}