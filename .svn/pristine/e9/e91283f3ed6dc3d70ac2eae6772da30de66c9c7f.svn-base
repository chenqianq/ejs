<?php
class MsgStack {
	private 
	/**
	 * 自定义类型
	 */
	$type, 

	/**
	 * 消息数组
	 */
	$msg;
	
	/**
	 * 构造函数
	 */
	public function __construct() {
		$this->type = array (
				'W',
				'E',
				'S' 
		);
		$this->msg = array ();
		if (! empty ( $_SESSION [SessionConst::mMsgDisplay] )) {
			$this->msg = $_SESSION [SessionConst::mMsgDisplay];
		}
		unset ( $_SESSION [SessionConst::mMsgDisplay] );
	}
	
	/**
	 * 添加错误信息
	 * 
	 * @param string $class
	 *        	自定义错误key
	 * @param string $msg
	 *        	错误消息
	 * @param string $type
	 *        	错误类型
	 */
	public function addMsg($class = '', $msg = '', $type = 'E') {
		$type = strtoupper ( $type );
		if (! in_array ( $type, $this->type )) {
			$type = 'E';
		}
		// 数据不为空
		if (strlen ( $msg ) > 0 && ! empty ( $msg )) {
			$this->msg [$type] [$class] [] = $msg;
		}
	}
	
	/**
	 * 添加SESSION错误信息
	 * 
	 * @param string $class
	 *        	自定义错误key
	 * @param string $msg
	 *        	错误消息
	 * @param string $type
	 *        	错误类型
	 */
	public function addMsgSession($class = '', $msg = '', $type = 'E') {
		$_SESSION [SessionConst::mMsgDisplay] [$type] [$class] [] = $msg;
		$this->addMsg ( $class, $msg, $type );
	}
	
	/**
	 * 信息输出
	 * 
	 * @param string 自定义错误key $class        	
	 * @param string 错误类型 $type    
	 * @return string 带有html的错误内容    	
	 */
	public function display($class, $type) {
		$display = $this->msg [$type] [$class];
		$output = '';
		if (! empty ( $display )) {
			switch (strtoupper ( $type )) {
				case 'W' :
					$style = 'warning';
					break;
				case 'E' :
					$style = 'warning';
					break;
				case 'S' :
					$style = 'success';
					break;
			}
			$id = 'msgbox-' . strtolower($type);
			$output = '<div id="' . $id . '" class="' . $style . ' closebox">';
			foreach ( $display as $msg ) {
				$output .= '<div style="padding:3px 0;">' .  $msg  . '</div>';
			}
			$output .= '</div>';
		}
		return $output;
	}
	
	/**
	 * 验证消息是否为空
	 * @param string $class 自定义key $class        	
	 * @param string $type消息类型
	 * @return boolean
	 */
	public function issetMsg() {
		return empty($this->msg)? false : true;
	}
}

