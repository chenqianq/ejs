<?php
class ChkHeaderWidget extends ZcWidget {
    
    private $userService;
	
    public function __construct(){
        $this -> userService = ServiceFactory::getUserService();
    }
    
	public function render($data = '') {
	    $data['userBaseMessageArray'] = $this -> userService -> getUserBaseMessageByUid($_SESSION['member_id']);
		return $this->renderFile('common/chk_header', $data);
	}
}