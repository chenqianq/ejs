<?php
class AheadPageSplit {
	public $total = 0;
	public $page = 1;
	public $limit = 20;
	public $num_links = 10;
	public $url = '';
	public $text = 'Showing {start} to {end} of {total} ({pages} Pages)';
	public $text_first = '首页';
	public $text_last = '末页';
	public $outer_element='<ul>{0}</ul>';
	//替换的是页面数字
	public $no_active_page='<li><span class="currentpage">{0}</span></li>';
	//替换的是a链接
	public $active_page='<li >{0}</li>';
	public $no_first_test='<li><span>{0}</span></li><li><span>{1}</span></li>';
	public $text_next = '下一页';
	public $text_prev = '上一页';

	public $more_page=' ... ';
	public $no_last_test='<li><span >{0}</span></li><li><span>{1}</span></li>';
	public $style_results = 'results';
	public $active_class='demo';

	public $no_page_text='<li><span>首页</span></li><li><span>上一页</span></li><li><span class="currentpage">1</span></li><li><span>下一页</span></li><li><span>末页</span></li>';
	private $page_reg='/(?<=current=)[\d]+/i';
	private $pageHelper;
	private $commonHelper;

	//
	public $page_html;
	public $page_text;
	
	public function __construct(){
		   $this->commonHelper=HelperFactory::getCommonHelper();
		   $this->pageHelper=HelperFactory::getPageHelper();
           $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		   $this->page=preg_match($this->page_reg,$url);
		  if($this->page){
			  $this->url= preg_replace($this->page_reg,'{page}',$url);
		  }else{
			  $this->page=YfjConst::beginPage;
			  if(strpos($url,'?')>-1){
				  $this->url=$url.'&current={page}';
			  }else{
				  $this->url=$url.'?current={page}';
			  }

		  }
		   $this->limit=YfjConst::productCategroiesPage;
		   $this->pageHelper->init();
		   $this->page=$this->pageHelper->getPage();

	}

	public function render() {
		$total = $this->total;
		$page = $this->page > 0 ? $this->page : 1;
		if (! $this->limit) {
			$limit = 10;
		} else {
			$limit = $this->limit;
		}
		
		$num_links = $this->num_links;
		$num_pages = ceil ( $total / $limit );
		
		if($page >= $num_pages && $num_links > 0){
			$page = $num_pages;
		}else if($page <= 1){
			$page = 1;
		}
		
		$output = '';
		
		if ($page > 1) {
			$output .= $this->commonHelper->format($this->active_page,' <a class="'.$this->active_class.'" href="' . str_replace ( '{page}', 1, $this->url ) . '">' . $this->text_first . '</a> <a class="'.$this->active_class.'" href="' . str_replace ( '{page}', $page - 1, $this->url ) . '">' . $this->text_prev . '</a> ');
		}else{
			$output.=$this->commonHelper->format($this->no_first_test,$this->text_first,$this->text_prev);
		}
		
		if ($num_pages > 1) {
			if ($num_pages <= $num_links) {
				$start = 1;
				$end = $num_pages;
			} else {
				$start = $page - floor ( $num_links / 2 );
				$end = $page + floor ( $num_links / 2 );
				
				if ($start < 1) {
					$end += abs ( $start ) + 1;
					$start = 1;
				}
				
				if ($end > $num_pages) {
					$start -= ($end - $num_pages);
					$end = $num_pages;
				}
			}
			
			if ($start > 1) {
				$output .= $this->commonHelper->format($this->no_active_page,$this->more_page);
			}
			
			for($i = $start; $i <= $end; $i ++) {
				if ($page == $i) {
					$output .= $this->commonHelper->format($this->no_active_page,$i);
				} else {
					$output .=$this->commonHelper->format($this->active_page,' <a class="'.$this->active_class.'" href="' . str_replace ( '{page}', $i, $this->url ) . '"><span>' . $i . '</span></a> ') ;
				}
			}
			
			if ($end < $num_pages) {

				$output .= $this->commonHelper->format($this->no_active_page,$this->more_page);
			}
		}
		
		if ($page < $num_pages) {
			$output .= $this->commonHelper->format($this->active_page,' <a class="'.$this->active_class.'" href="' . str_replace ( '{page}', $page + 1, $this->url ) . '">' . $this->text_next . '</a> <a class="'.$this->active_class.'" href="' . str_replace ( '{page}', $num_pages, $this->url ) . '">' . $this->text_last . '</a> ');
		}else{
			$output.=$this->commonHelper->format($this->no_last_test,$this->text_next,$this->text_last);
		}
		
		$find = array (
				'{start}',
				'{end}',
				'{total}',
				'{pages}' 
		);
		$replace = array (
				($total) ? (($page - 1) * $limit) + 1 : 0,
				((($page - 1) * $limit) > ($total - $limit)) ? $total : ((($page - 1) * $limit) + $limit),
				$total,
				$num_pages 
		);
        $this->page_html=preg_match('/[\d]+/',$output) ? $this->commonHelper->format($this->outer_element,$output):$this->commonHelper->format($this->outer_element,$this->no_page_text);
		$this->page_text='<div class="' . $this->style_results . '">' . str_replace ( $find, $replace, $this->text ) . '</div>';

	}
}
?>