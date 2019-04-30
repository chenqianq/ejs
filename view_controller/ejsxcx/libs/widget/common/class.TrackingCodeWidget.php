<?php
class trackingCodeWidget extends ZcWidget {
	
	/**
	 * @var ProductService
	 */
	private $productService;
	
	
	public function __construct() {
		 
	}
	
	/* (non-PHPdoc)
	 * @see ZcWidget::render()
	 */
	public function render($renderData= '') {
		 
		/* 
		$renderData['repeatPage'] = array(RouteConst::category);
		$renderData['type_name'] = $_GET['type_name'];
		$renderData['layout'] = $_GET['layout'];
		$renderData['page'] = $_GET['page'];
		$renderData['new_add_order'] = $_GET['new_add_order']; */
		return $this->renderFile('common/tracking_code', $renderData);
	}
	
	 
}