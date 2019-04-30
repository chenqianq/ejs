<?php

class ReturnMessage{
		/**
	 * 输出信息
	 *
	 * @param string $msg 输出信息
	 * @param string/array $url 跳转地址 当$url为数组时，结构为 array('msg'=>'跳转连接文字','url'=>'跳转连接');
	 * @param string $show_type 输出格式 默认为html
	 * @param string $msg_type 信息类型 succ 为成功，error为失败/错误
	 * @param string $is_show  是否显示跳转链接，默认是为1，显示
	 * @param int $time 跳转时间，默认为1秒
	 * @return string 字符串类型的返回结果
	 */
	public function showMessage($msg,$url='',$show_type='html',$msg_type='succ',$is_show=1,$time=1000){
		/**
	 * 如果默认为空，则跳转至上一步链接
	 */
		$url = ($url!='' ? $url : (empty($_SERVER['HTTP_REFERER'])?'':$_SERVER['HTTP_REFERER']));

		$msg_type = in_array($msg_type,array('succ','error')) ? $msg_type : 'error';

		switch($show_type){

			default:
			$html = '';
			$html .=  '<!DOCTYPE HTML><html><head><meta charset="utf-8"><title>一番街</title>'
						. HtmlTool::getStaticFile(array(
						'skin_0.css'
						)).
						'</head><body><div class="page"><div class="fixed-bar"><div class="item-title"><h3>系统消息</h3>'.
						'</div></div><div class="fixed-empty"></div><table class="table tb-type2 msg"> <tbody class="noborder"><tr>'.
						'<td rowspan="5" class="msgbg"></td><td class="tip">' . $msg . '</td></tr><tr><td class="tip2">若不选择将自动跳转</td></tr>'.
						'<tr><td><a href="' . $url . '" class="btns"><span>返回上一页</span></a>' .
						'<script type="text/javascript"> function toUrl(){ location.href="' . $url . '";}' .
						'window.setTimeout("toUrl();",' . $time . '); </script>' .
						'</td></tr></tbody></table></div></body></html>';
			echo  $html;
		}
		exit;
	}



    /**
     * 输出信息
     * @param string $msg 输出信息
     * @param string/array $url 跳转地址 当$url为数组时，结构为 array('msg'=>'跳转连接文字','url'=>'跳转连接');
     * @param string $show_type 输出格式 默认为html
     * @param int $time 跳转时间，默认为1秒
     * @return string 字符串类型的返回结果
     */
	public function incomingMessage($msg,$url='',$show_type='html',$time=5000)
    {
        $url = ($url != '' ? $url : (empty($_SERVER['HTTP_REFERER']) ? '' : $_SERVER['HTTP_REFERER']));

        switch ($show_type) {
            default:
                $html = '';
                $html .= '<!DOCTYPE HTML><html><head><meta charset="utf-8"><title>一番街</title>'
                    . HtmlTool::getStaticFile(array(
                        'layui/css/layui.css'
                    )) .
                    '</head><body>';
                $html .= '<fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
                       <legend>系统消息</legend>
                        </fieldset>
                        <blockquote class="layui-elem-quote layui-quote-nm" style="font-size: 18px;">' . $msg . '！<br/><br/>若不选择页面将在'.($time/1000).'秒后自动跳转 </blockquote>
                         &nbsp;&nbsp;&nbsp;&nbsp;<a class="layui-btn" href="' . $url . '">返回上一页</a>' .
                    '<script type="text/javascript"> function toUrl(){ location.href="' . $url . '";}' .
                    'window.setTimeout("toUrl();",' . $time . '); </script></body></html>';
                echo $html;
        }
        exit;
    }

}






?>