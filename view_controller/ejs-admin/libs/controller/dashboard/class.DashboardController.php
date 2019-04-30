<?php

/**
*控制台
*/

class DashboardController extends ZcController{


    public function __construct($route)
    {
        parent::__construct($route);
    }

    public function welcome() {
         
        $data =[];
        $this -> render($data,null,false);
    }
	
	
	
	
	
	
	
}
