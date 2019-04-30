
<div class="common-edit" id="common-edit">
	<div class="site-title">
		<fieldset><legend>商户信息</legend></fieldset>
	</div>
	<div class="site-text site-block">
		<form class="layui-form">
			<div class="layui-form-item">
				<label class="layui-form-label">*商户名称：</label>
				<div class="layui-input-inline">
					<input type="text" name="merchant_name" required="" lay-verify="required" value="<?php echo $merchantInfo['merchant_name'];?>" placeholder="" autocomplete="off" class="layui-input">
				</div>
				<label class="layui-form-label w160">客户Id：<?php echo $merchantInfo['merchant_code']?:'创建时自动生成';?></label>
			</div>
			<div class="layui-form-item">
				<label class="layui-form-label">*绑定仓库：</label>
				<div class="layui-input-inline">
					<?php if($merchantInfo){?>
						<label class="layui-form-label"><?php echo $warehouseList[$merchantInfo['warehouse_id']];?></label>
					<?php } else{ ?>
						<select name="warehouse_id" lay-verify="required">
							<?php foreach ($warehouseList as $code => $name){?>
								<option value="<?php echo $code?>" <?php if($merchantInfo['warehouse_id'] == $code){
									echo 'selected';
								}?>><?php echo $name?></option>
							<?php } ?>
						</select>
					<?php }?>
				</div>
				<label class="layui-form-label">公司全称：</label>
				<div class="layui-input-inline">
					<input type="text" name="company_name" value="<?php echo $merchantInfo['company_name'];?>" placeholder="" autocomplete="off" class="layui-input">
				</div>
			</div>
			
			<div class="layui-form-item">
				<label class="layui-form-label">关联客户：</label>
			
				<div class="layui-input-inline">
					<select name="client_id" >
						<option value="0">无</option>
						<?php foreach ($clientList as $code => $name){?>
							<option value="<?php echo $code?>" <?php if($merchantInfo['client_id'] == $code){
								echo 'selected';
							}?>><?php echo $name?></option>
						<?php } ?>
					</select>
				</div>
			
				
				<label class="layui-form-label">归属商务：</label>
				<?php if($merchantInfo){?>
					<label class="layui-form-label"><?php echo $bmList[$merchantInfo['business_manager_id']];?></label>
				<?php } else {?>
					<div class="layui-input-inline">
						
						<select name="business_manager_id" >
							<option value="0">无</option>
							<?php foreach ($bmList as $code => $name){?>
								<option value="<?php echo $code?>" <?php if($merchantInfo['business_manager_id'] == $code){
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
				<label class="layui-form-label">*联系人：</label>
				<div class="layui-input-inline">
					<input type="text" name="merchant_contact_name" required="" value="<?php echo $merchantInfo['merchant_contact_name'];?>" lay-verify="required" placeholder="" autocomplete="off" class="layui-input">
				</div>
				
				<label class="layui-form-label">*手机号：</label>
				<div class="layui-input-inline">
					<input type="text" name="merchant_contact_phone" required="" lay-verify="required|number" value="<?php echo $merchantInfo['merchant_contact_phone'];?>" maxlength="11" placeholder="" autocomplete="off" class="layui-input">
				</div>
			</div>
			<div class="layui-form-item layui-form-text">
				<label class="layui-form-label">备注：</label>
				<div class="layui-input-block w500">
					<textarea name="remark" placeholder="请输入内容"  class="layui-textarea"><?php echo $merchantInfo['remark'];?></textarea>
				</div>
			</div>
			
			<div class="layui-form-item">
				<label class="layui-form-label">*登录名：</label>
				<div class="layui-input-inline">
					<?php if($merchantInfo){?>
						<label class="layui-form-label"><?php echo $merchantInfo['login_name'];?></label>
					<?php } else{ ?>
						<input type="text" name="login_name" required="" lay-verify="required"  placeholder="" value="<?php echo $merchantInfo['login_name'];?>" autocomplete="off" class="layui-input">
					<?php }?>
				</div>
                <label class="layui-form-label w100">*注册手机号：</label>
                <div class="layui-input-inline">
					<?php if($merchantInfo){?>
                        <label class="layui-form-label"><?php echo $merchantInfo['admin_mobile'];?></label>
					<?php } else{ ?>
                        <input type="text" name="admin_mobile" required="" lay-verify="required"  placeholder="" value="<?php echo $merchantInfo['admin_mobile'];?>" autocomplete="off" class="layui-input">
					<?php }?>
                </div>
			</div>
			<div class="layui-form-item">
			
			</div>
			
			<div class="layui-form-item">
				<div class="layui-input-block">
					<input type="hidden" value="<?php echo $merchantInfo['merchant_id'];?>" name="merchant_id" id="merchant_id">
					<button class="layui-btn" type="button" lay-submit submit-num = "0" lay-filter="formDemo">立即提交</button>
                    <button class="layui-btn layui-btn-primary" type="button" onclick="history.go(-1)">返回</button>
                    
				</div>
			</div>
		</form>
	</div>
    <table class="layui-table" style="width: 80%">
        <thead>
        <tr>
            <th>操作时间</th>
            <th>操作人员</th>
            <th>操作内容</th>
        </tr>
        </thead>
        <tbody>
	    <?php if($logList){
		    foreach ($logList as $info){
			    ?>

                <tr>
                    <td><?php echo $info['gmt_create'];?></td>
                    <td><?php echo $info['operator'];?></td>
                    <td><?php echo $info['content'];?></td>
                </tr>
		
		    <?php  }
	    } else { ?>
            <tr>
                <td colspan="3" style="text-align: center"> 没有任何数据哦</td>
            </tr>
	    <?php }?>
        </tbody>
    </table>


</div>

<script>
    layui.use('form', function(){
        var form = layui.form;

        //监听提交
        form.on('submit(formDemo)', function(data){
            var submit_num = $(this).attr('submit-num');
            if(parseInt(submit_num) >0){
                return false;
            }
            
            var that = $(this);
            layer.confirm('确认保存当前内容',{btn:['确定','取消']},function(){

                that.attr('submit-num','1');
                
                var merchant_id = $('#merchant_id').val();
                if(!merchant_id){
                    var url = 'save_add_merchant';
                }else{
                    var url = 'save_edit_merchant?merchant_id'+merchant_id;
                }
                layer.closeAll();
                $.ajax({
                    url:url,
                    data:data.field,
                    type:'post',
                    dataType:'json',
                    success:function (res) {
                    
                        if(res.status == 'success'){
                            layer.alert(res.msg,{yes:function () {
                                window.location.href='merchant_list';
                            }});
                         
                        }else{
                            that.attr('submit-num','0');
                            layer.msg(res.msg);
                        }
                    }

                })
            })
        });
    });
</script>
