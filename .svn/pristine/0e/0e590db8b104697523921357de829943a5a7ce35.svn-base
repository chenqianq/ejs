<?php

/**
 * 错误监控
 * @author Administrator
 *
 */
class YfjMonitorHandler extends ZcMonitorHandler {
	
	public function monitor($errorStr, $errorType = 1, $needNotify = false) {
		if ($errorType == 40007) {
			return $this->paymentWarnning($errorStr, true);
		}
		
		parent::monitor($errorStr, $errorType, $needNotify);		
	}
	
	protected function getDbLink() {
		if ($this->dbLink) {
			return $this->dbLink;
		}
		
		$this->dbLink = new ZcDbSimpleMysql(DB_SERVER_LOG, DB_SERVER_USERNAME_LOG, DB_SERVER_PASSWORD_LOG, DB_DATABASE_LOG);
		return $this->dbLink;
	}
	
	protected  function getErrorContents($errorStr) {
		if (!is_string($errorStr)) {
			$errorStr = "\r\n" . print_r($errorStr, true);
		}
	
		$errorContents = $errorStr;
		$errorContents .= "\r\n <b>hostname:</b> " . php_uname ( 'n' ) . "\r\n";
		$errorContents .= '&split&';
		if (isset($_SESSION)) {
			$errorContents .= "\r\n + - - - - - - - - - - - - - - - - SESSION INFOMATION - - - - - - - - - - - - - - - + \r\n";
			$errorContents .= "\r\n" . print_r($_SESSION, true) . "\r\n";
		}
		$errorContents .= "\r\n + - - - - - - - - - - - - - - - - SERVER INFOMATION - - - - - - - - - - - - - - - + \r\n";
		$errorContents .= "\r\n" . print_r($_SERVER, true) . "\r\n";
		$errorContents .= "\r\n + - - - - - - - - - - - - - - - - DEBUG BACKTRACE - - - - - - - - - - - - - - - - + \r\n";
		$stackInfo = $this->getStackInfo();
		$errorContents .= "\r\n". $stackInfo . "\r\n";
	
		return $errorContents;
	}
	
	/**
	 * 获取debug backtrace 的文件名，line，function ，class
	 * @return boolean|Ambigous <multitype:, unknown>
	 */
	private function getStackInfo() {
		$backTraceArray = debug_backtrace();
		if( empty($backTraceArray) ){
			return false;
		}
		
		$returnTraceArray = array();
		$returnTraceString = '';
		foreach ( $backTraceArray as $key => $backTrace ){
			$returnTraceArray[$key]['file'] = $backTrace['file'];
			$returnTraceArray[$key]['line'] = $backTrace['line'];
			$returnTraceArray[$key]['function'] = $backTrace['function'];
			$returnTraceArray[$key]['class'] = $backTrace['class'];
			
		}
		return print_r($returnTraceArray,true);
	}
	
	/**
	 * 添加警告信息到paayment 错误表中
	 *
	 * @param unknown_type $error_str        	
	 * @param unknown_type $error_type        	
	 */
	private function paymentWarnning($error_str = null, $db_debug_mode = true) {
		if ($error_str != null) {
			if ($db_debug_mode) {
				$db_link = $this->getDbLink ();
				if ($db_link) {
					$error_contents = "\r\n <b>hostname:</b> " . php_uname ( 'n' ) . "\r\n";
					$error_contents .= '&split&';
					$error_contents .= "\r\n + - - - - - - - - - - - - - - - - SESSION INFOMATION - - - - - - - - - - - - - - - + \r\n";
					$error_contents .= "\r\n" . print_r ( $_SESSION, true ) . "\r\n";
					$error_contents .= "\r\n + - - - - - - - - - - - - - - - - SERVER INFOMATION - - - - - - - - - - - - - - - + \r\n";
					$error_contents .= "\r\n" . print_r ( $_SERVER, true ) . "\r\n";
					$error_contents .= "\r\n + - - - - - - - - - - - - - - - - DEBUG BACKTRACE - - - - - - - - - - - - - - - - + \r\n";
					$error_contents .= "\r\n" . print_r ( debug_backtrace (), true ) . "\r\n";
					
					$email = $error_str ['customer_email'];
					$customer_info = $error_str ['customer_info'];
					$order_type = $error_str ['order_type'];
					$error_code = $error_str ['error_code'];
					$error_txt = $error_str ['error_txt'];
					$site = $error_str ['site'];
					$country_code = $error_str ['country_code'];
					$payment_method = $error_str ['payment_method'];
					$creaeted = date ( 'Y-m-d H:i:s', time () );
					$insert = array (
							'customer_email' => $email,
							'customer_info' => $customer_info . $error_contents,
							'order_type' => $order_type,
							'error_code' => $error_code,
							'error_txt' => $error_txt,
							'payment_method' => $payment_method,
							'site' => $site,
							'country_code' => $country_code,
							'gmt_created' => $creaeted 
					);
					//$db_link->insert ( 'payment_error_log', $insert, 'insert' );
				}
			} else {
				/*  error_log("{$client_ip} {$now} {$levelTag}: {$message}\r\n",
				 3, $this->logPath); */
			}
			return true;
		}
	}
}

Zc::startMonitor ( new YfjMonitorHandler () );