<?php
class ChkFooterWidget extends ZcWidget {
	/*
	 * (non-PHPdoc) @see ZcWidget::render()
	 */
	public function render($renderData = '') {
	    
		return $this->renderFile('common/chk_footer', $renderData);
	}
}
