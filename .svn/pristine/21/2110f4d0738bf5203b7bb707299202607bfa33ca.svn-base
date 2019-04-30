//引入上传的js和css
<?php
$timeSlice = '?2017120602';
echo HtmlTool::getStaticFile(array(
	'imgup.css'.$timeSlice,
));
?>

<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
	<legend>
		小程序信息
	</legend>
</fieldset>

<div class="layui-form-item">
	<label class="layui-form-label w280 left">商户名称：<?php echo $merchantXcxInfo['merchant_name'];?></label>
	<label class="layui-form-label w280 left">商户编号：<?php echo $merchantXcxInfo['merchant_code'];?></label>
</div>
<div class="layui-elem-quote">
	<p>
		店铺展示信息
		<button type="button" class="layui-btn layui-btn-xs edit-btn" id="save">编辑</button>
		<button type="button" class="layui-btn layui-btn-xs save-btn" style="display: none">保存</button>
		<button type="button" class="layui-btn layui-btn-xs cancel-btn" style="display: none">取消</button>
	</p>
</div>
<div class="layui-form-item">
	<label class="layui-form-label  left">店铺名称：	</label>
		<div class="layui-input-inline w280">
			<input type="text" class="layui-input" value="<?php echo $merchantXcxInfo['xcx_store_name'];?>" placeholder="请输入商品名称">
		</div>
		

</div>
<div class="layui-form-item">
	<label class="layui-form-label w200 left">店铺营业执照（至多5张）</label>
	<div class="layui-upload">
		<button type="button" class="layui-btn layui-btn-xs add-imag-btn" id="test2">添加图片+</button>
		<blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 10px;">
			预览图：
			<div class="layui-upload-list" id="demo2"></div>
		</blockquote>
	</div>
</div>
<div class="layui-elem-quote">
	<p>小程序信息</p>
</div>
<div class="layui-form-item">
	<label class="layui-form-label w280 left">小程序名称：<?php echo $merchantXcxInfo['xcx_name'];?></label>
	<label class="layui-form-label w280 left">授权状态：</label>
</div>
<div class="layui-form-item">
	<label class="layui-form-label w280 left">服务类目：</label>
	<label class="layui-form-label w280 left">绑定资料上传：<?php if( $merchantXcxInfo['is_registered']>0){ ?>
			已上传
			<a style="color: #1E9FFF" href="<?php echo Zc::url(YfjRouteConst::editMerchantXcx,['merchant_id' => $merchantXcxInfo['merchant_id']])?>">查看</a>
		<?php  }else{?>
			未上传
			<a style="color: #1E9FFF" href="<?php echo Zc::url(YfjRouteConst::editMerchantXcx,['merchant_id' => $merchantXcxInfo['merchant_id']])?>">编辑</a>
		<?php }?>
	
	</label>
</div>
<div class="layui-form-item">
	<label class="layui-form-label w280 left">代码上传状态：</label>
	<label class="layui-form-label w280 left">线上版本：</label>
</div>
<div class="layui-form-item">
	<label class="layui-form-label w280 left">微信支付授权：<?php echo $merchantXcxInfo['is_wechat']>0?'已授权':'未授权';?></label>
</div>
<div class="layui-form-item">
	<label class="layui-form-label w280 left">小程序商户logo：</label>
	<div class="layui-input-inline">
	
	</div>
</div>
<div class="layui-form-item">
	<label class="layui-form-label w280 left">扫码预览：</label>
	<div class="layui-input-inline w280">
	
	</div>
</div>
<div class="layui-upload">
	<button type="button" class="layui-btn" id="test3">多图片上传</button>
	<blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 10px;">
		预览图：
		<div class="layui-upload-list" id="demo3"></div>
	</blockquote>
</div>

<!--图片选择对话框-->
<div id="div_imgfile">选择图片</div>

<!--图片预览容器-->
<div id="div_imglook">
	<div style="clear: both;"></div>
</div>

<!--确定上传按钮-->
<input type="button" value="确定上传" id="btn_ImgUpStart" />

<button type="button" class="layui-btn layui-btn-primary" onclick="history.go(-1)">返回</button>
<script>
    layui.use('upload', function(){
        var $ = layui.jquery
            ,upload = layui.upload;
        //多图片上传
        upload.render({
            elem: '#test3'
            ,url: '/ejs-admin/crm/crm/save_merchant_xcx_info'
            ,multiple: true
            ,accept:'images'
	        ,acceptMime:'image/*'
            , before: function(obj){
            //预读本地文件示例，不支持ie8
            obj.preview(function(index, file, result){
                console.log(111);
                $('#demo3').append('<a href="'+result+'" target="_blank"><img width="200"  src="'+ result +'" alt="'+ file.name +'" class="layui-upload-img">')
            });
        }
	        ,auto:false
	        
            ,bindAction:'#save'
	        ,number:'5'
          
            ,done: function(res){
                cob
                //上传完毕
            }
        });
    
    
    })
	
	
	
	
</script>
<?php
$timeSlice = '?2017120602';
echo HtmlTool::getStaticFile(array(
	'imgup.js'.$timeSlice,
));
?>




