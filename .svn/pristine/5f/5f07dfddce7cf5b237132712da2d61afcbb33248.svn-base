<?php
class YifanjieDbListener extends ZcDbListener {
	
	private $log;
	
	public function __construct() {
		$this->log = LogFactory::getBizLog('db');;
	}
	
	/**
	 * 处理数据库异常
	 * 
	 * @see ZcDbListener::error()
	 */
	public function error($db, $args) {
		/* @var $ex ZcDbException */
		$ex = $args['exception'];
		
		//记录错误日志
		$errorData = array(
				'access_date' => date ('Y-m-d H:i:s', time()),
				'error_number' => $ex->getCode() . ' ' . $ex->getMessage() ."\t\r\n",
				'sql_query' =>  $ex->getSql() . "\t\r\n",
				'query_string' => $_SERVER['QUERY_STRING'],
				'ip_address' => substr($_SERVER['REMOTE_ADDR'],0,15)
		);
		$this->log->monitor(print_r($errorData, true), 40008);
		
		if (defined('IS_ADMIN_FLAG') && IS_ADMIN_FLAG == true) {
			echo 'If you were entering information, press the BACK button in your browser and re-check the information you had entered to be sure you left no blank fields.<br />';
			$this->log->monitor("If you were entering information, press the BACK button in your browser and re-check the information you had entered to be sure you left no blank fields.<br />", 40008);
		}
		
		// db出问题了，就展示nddbc.html
		if (($ex instanceof ZcDbConnectionException) && ($ex->getDbId() == 'db')) {
			include('nddbc.html');
			die; 
		}
	}
}