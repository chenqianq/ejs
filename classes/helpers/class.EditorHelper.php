<?php
/**
 * 内容编辑器类
 *
 * 
 */

class Editor{

	
	public function __construct(){
		
	}
	
	/**
 * 编辑器内容
 *
 * @param int $id 编辑器id名称，与name同名
 * @param string $value 编辑器内容
 * @param string $width 宽 带px
 * @param string $height 高 带px
 * @param string $style 样式内容
 * @param string $upload_state 上传状态，默认是开启
 */
public function showEditor($id, $value='', $width='700px', $height='300px', $style='visibility:hidden;',$upload_state="true", $media_open=false, $type='all'){
    $html = '';
	//是否开启多媒体
	$media = '';
	if ($media_open){
		$media = ", 'flash', 'media'";
	}
    switch($type) {
    case 'basic':
        $items = "['source', '|', 'fullscreen', 'undo', 'redo', 'cut', 'copy', 'paste', '|', 'about']";
        break;
    case 'simple':
        $items = "['source', '|', 'fullscreen', 'undo', 'redo', 'cut', 'copy', 'paste', '|',
            'fontname', 'fontsize', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
            'removeformat', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
            'insertunorderedlist', '|', 'emoticons', 'image', 'link', '|', 'about']";
        break;
    default:
        $items = "['source', '|', 'fullscreen', 'undo', 'redo', 'print', 'cut', 'copy', 'paste',
            'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
            'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
            'superscript', '|', 'selectall', 'clearhtml','quickformat','|',
            'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
            'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image'".$media.", 'table', 'hr', 'emoticons', 'link', 'unlink', '|', 'about']";
        break;
    }
	//图片、Flash、视频、文件的本地上传都可开启。默认只有图片，要启用其它的需要修改resource\kindeditor\php下的upload_json.php的相关参数
    $html .=  '<textarea id="'. $id .'" name="'. $id .'" style="width:'. $width .';height:'. $height .';'. $style .'">'.$value.'</textarea>';
    $html .= '
<script src="'. Zc::C('app.https.domain') .'/view_controller/yifanjie-admin/views/static/kindeditor/kindeditor-min.js" charset="utf-8"></script>
<script src="'. Zc::C('app.https.domain') .'/view_controller/yifanjie-admin/views/static/kindeditor/lang/zh_CN.js" charset="utf-8"></script>
<script>
	var KE;
  KindEditor.ready(function(K) {
        KE = K.create("textarea[name=\''.$id.'\']", {
						items : '.$items.',
						cssPath : "' . Zc::C('app.https.domain') . '/view_controller/yifanjie-admin/views/static/kindeditor/themes/default/default.css",
						allowImageUpload : '.$upload_state.',
						allowFlashUpload : false,
						allowMediaUpload : false,
						allowFileManager : false,
						syncType:"form",
						afterCreate : function() {
							var self = this;
							self.sync();
						},
						afterChange : function() {
							var self = this;
							self.sync();
						},
						afterBlur : function() {
							var self = this;
							self.sync();
						}
        });
			KE.appendHtml = function(id,val) {
								//console.log(this);
				//$(".ke-content").html(this.html() + val)
				this.html(this.html() + val);
				if (this.isCreated) {
					var cmd = this.cmd;
					cmd.range.selectNodeContents(cmd.doc.body).collapse(false);
				}
				return this;
			}
	});
</script>
	';
	return $html;
}
	
}
?>