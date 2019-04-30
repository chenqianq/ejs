
<div class="site-content">
    <div class="site-title">
        <fieldset><legend>客户信息</legend></fieldset>
    </div>
	<div class="site-text site-block">
		<form class="layui-form">
			<div class="layui-form-item">
				<label class="layui-form-label">*客户名称：</label>
				<div class="layui-input-inline">
					<input type="text" name="client_name" required="" lay-verify="required" value="<?php echo $clientInfo['client_name'];?>" placeholder="" autocomplete="off" class="layui-input">
				</div>
				<label class="layui-form-label w160">客户Id：<?php echo $clientInfo['client_id']?substr(strval($clientInfo['client_id']+10000000000),1,11):'创建时自动生成';?></label>
			</div>
			<div class="layui-form-item">
				<label class="layui-form-label">*客户状态：</label>
				<div class="layui-input-inline">
					<select name="status" lay-verify="required">
						<?php foreach ($status as $code => $name){?>
                            <option value="<?php echo $code?>" <?php if($clientInfo['status'] == $code){
								echo 'selected';
							}?>><?php echo $name?></option>
						<?php } ?>
					</select>
				</div>
				<label class="layui-form-label">归属商务：</label>
                <?php if($clientInfo){?>
                    <label class="layui-form-label"><?php echo $bmList[$clientInfo['business_manager_id']];?></label>
                <?php } else {?>
                    <div class="layui-input-inline">

                        <select name="business_manager_id" >
                            <option value="0">无</option>
	                        <?php foreach ($bmList as $code => $name){?>
                                <option value="<?php echo $code?>" <?php if($bmId == $code){
			                        echo 'selected';
		                        }?>><?php echo $name?></option>
	                        <?php } ?>
                        </select>
                    </div>
                <?php } ?>
                
			
			</div>
			<div class="layui-form-item">
			
			</div>
			<div class="layui-form-item">
				<label class="layui-form-label ">关联商户：</label>
				<label class="layui-form-label " style="text-align: left;width: 200px"><?php echo $merchantNameStr;?></label>
			</div>
			
			<div class="layui-form-item">
				<label class="layui-form-label">*联系人：</label>
				<div class="layui-input-inline">
					<input type="text" name="contact_name" required="" value="<?php echo $clientInfo['contact_name'];?>" lay-verify="required" placeholder="" autocomplete="off" class="layui-input">
				</div>
				
				<label class="layui-form-label">*手机号1：</label>
				<div class="layui-input-inline">
					<input type="text" name="contact_phone1" required="" lay-verify="required|number" value="<?php echo $clientInfo['contact_phone_a'];?>" maxlength="11" placeholder="" autocomplete="off" class="layui-input">
				</div>
			</div>
			
			<div class="layui-form-item">
				<label class="layui-form-label">手机号2：</label>
				<div class="layui-input-inline">
					<input type="text" name="contact_phone2"  maxlength="11" placeholder="" value="<?php echo $clientInfo['contact_phone_b'];?>" autocomplete="off" class="layui-input">
				</div>
				<label class="layui-form-label">手机号3：</label>
				<div class="layui-input-inline">
					<input type="text" name="contact_phone3" maxlength="11" placeholder="" value="<?php echo $clientInfo['contact_phone_c'];?>" autocomplete="off" class="layui-input">
				</div>
			</div>
			<div class="layui-form-item">
			
			</div>
			
			
			<div class="layui-form-item layui-form-text">
				<label class="layui-form-label ">备注：</label>
				<div class="layui-input-block w500">
					<textarea name="remark" placeholder="请输入内容"  class="layui-textarea"><?php echo $clientInfo['remark'];?></textarea>
				</div>
			</div>
			<div class="layui-form-item">
				<div class="layui-input-block">
					<input type="hidden" value="<?php echo $clientInfo['client_id'];?>" name="client_id" id="client_id">
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
               var client_id = $('#client_id').val();
	           if(!client_id){
	               var url = 'save_add_client';
	           }else{
	               var url = 'save_edit_client?client_id'+client_id;
	           }
               layer.closeAll();
               $.ajax({
	               url:url,
                   data:data.field,
                   type:'post',
	               dataType:'json',
	               success:function (res) {
		               layer.msg(res.msg);
		               if(res.status == 'success'){
		                   window.location.href='client_list';
                       }
                   }
	               
               })
           })
        });
    });
</script>
