
<div class="site-content">
	<div class="site-title">
		<fieldset><legend>小程序绑定</legend></fieldset>
	</div>
	<div class="site-text site-block">
		<form class="layui-form">
			<div class="layui-form-item">
				<label class="layui-form-label w240">*AppID（小程序ID）：</label>
				<div class="layui-input-inline  w280">
					<input type="text" name="xcx_appid" required="" lay-verify="required" value="<?php echo $info['xcx_appid'];?>" placeholder="" autocomplete="off" class="layui-input">
				</div>
			</div>
			<div class="layui-form-item">
				<label class="layui-form-label w240">*App Secret（小程序密匙）：</label>
				<div class="layui-input-inline  w280">
					<input type="text" name="xcx_app_secret" required="" lay-verify="required" value="<?php echo $info['xcx_app_secret'];?>" placeholder="" autocomplete="off" class="layui-input">
				</div>
			</div>
			<div class="layui-form-item">
				<label class="layui-form-label w240">*商户号：</label>
				<div class="layui-input-inline  w280">
					<input type="text" name="xcx_merc_id" required="" lay-verify="required" value="<?php echo $info['xcx_merc_id'];?>" placeholder="" autocomplete="off" class="layui-input">
				</div>
			</div>
			<div class="layui-form-item">
				<label class="layui-form-label w240">*小程序名称：</label>
				<div class="layui-input-inline  w280">
					<input type="text" name="xcx_name" required="" lay-verify="required" value="<?php echo $info['xcx_name'];?>" placeholder="" autocomplete="off" class="layui-input">
				</div>
			</div>
			<div class="layui-form-item">
				<label class="layui-form-label w240">*行业标签：</label>
				<div class="layui-input-inline w280">
					<input type="text" name="xcx_industry_label" required="" lay-verify="required" value="<?php echo $info['xcx_industry_label'];?>" placeholder="" autocomplete="off" class="layui-input">
				    <span >最多10个标签，用英文逗号隔开</span>
                </div>
                
			</div>
			<div class="layui-form-item">
				<label class="layui-form-label w240">支付证书：</label>
				<div class="layui-input-inline  w280">
					<input type="file" name="apiclient_cert" id="apiclient_cert_file"  accept=".pem" value="" placeholder="" autocomplete="off" >
                </div>
                <div class="layui-form-mid layui-word-aux"><?php echo $info['is_wechat']>0?'apiclient_cert.pem已上传,继续上传将覆盖原文件':'apiclient_cert.pem';?></div>
				
			</div>
            <div class="layui-form-item">
                <label class="layui-form-label w240"></label>
                <div class="layui-input-inline  w280">
                    <input type="file" name="apiclient_key" id="apiclient_key_file"  value="" placeholder="" autocomplete="off" accept=".pem" >
                </div>
                <div class="layui-form-mid layui-word-aux"><?php echo $info['is_wechat']>0?'apiclient_key.pem已上传,继续上传将覆盖原文件':'apiclient_key.pem';?></div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label w240"></label>
                <div class="layui-input-inline  w500">
                    说明：请到【微信支付平台 → 账号中心 → API中心 → API证书】，下载证书至本地，解压后将文件“apiclient_cert.pem”与文件“apiclient_key.pem”上传至对应栏位。<span style="color: red">证书下载位置>><span>
                            <img src="" width="100px">
                </div>
            </div>

           
			<div class="layui-form-item">
				<label class="layui-form-label w240"></label>
				<div class="layui-input-block ">
					<input type="hidden" value="<?php echo $info['merchant_id'];?>" name="merchant_id" id="merchant_id">
					<button class="layui-btn" type="button" onclick="history.go(-1)">返回</button>
					<button class="layui-btn" type="button" lay-submit lay-filter="formDemo">立即提交</button>
				</div>
			</div>
		</form>
	</div>


</div>

<script>
    layui.use('form', function(){
        var form = layui.form;

        //监听提交
        form.on('submit(formDemo)', function(data){
            layer.confirm('确认保存当前内容',{btn:['确定','取消']},function(){
                var merchant_id = $('#merchant_id').val();
	            var url = 'save_edit_merchant_xcx';
                
                layer.closeAll();
                $.ajaxFileUpload({
                    fileElementId: ['apiclient_cert_file','apiclient_key_file'],
                    url:url,
                    data:data.field,
                    type:'post',
                    dateType:'json',
                    success:function (res) {
                      
                        layer.msg(res.msg);
                        if(res.status == 'success'){
                            window.location.href='';
                        }
                    }

                })
            })
        });
    });
</script>
