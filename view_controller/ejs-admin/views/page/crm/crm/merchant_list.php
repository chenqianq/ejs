
<div id="common" class="common">
	<form class="layui-form search_form" method="get" action="" >
		<blockquote class="layui-elem-quote">
			
			<div class="layui-inline W160H26">
				<label class="layui-form-label">商户ID:</label>
				<div class="layui-input-inline">
					<input type="text" value="<?php echo $_GET['merchant_code']?>" name="merchant_code"  placeholder="" class="layui-input search_input">
				</div>
			</div>

            <div class="layui-inline W160H26">
                <label class="layui-form-label">商户名称:</label>
                <div class="layui-input-inline">
                    <input type="text" value="<?php echo $_GET['merchant_name']?>" name="merchant_name"  placeholder="" class="layui-input search_input">
                </div>
            </div>


            <div class="layui-inline W160H26">
				<label class="layui-form-label">手机号:</label>
				<div class="layui-input-inline">
					<input type="text" value="<?php echo $_GET['merchant_contact_phone']?>" name="merchant_contact_phone"  placeholder="" class="layui-input search_input">
				</div>
			</div>
   
			<div class="layui-inline W160H26">
				<label class="layui-form-label">商户状态:</label>
				<div class="layui-input-inline">
					<select name="status" class="search_input">
						<option value="">全部</option>
						<?php foreach ($status as $code => $name){?>
							<option value="<?php echo $code?>" <?php if($_GET['status'] == $code){
								echo 'selected';
							}?>><?php echo $name?></option>
						<?php } ?>
					
					</select>
				</div>
			</div>
			
			<div class="layui-inline W200H30" >
				<button class="layui-btn">查询</button>
                <button class="layui-btn" type="button" onclick="$(this).parents('form').find('input').val('');$(this).parents('form').find('select').val('')">重置</button>
			</div>
		</blockquote>
	
	</form>
	<div class="new-insert">
		<button type="button" class="layui-btn add_new_client" onclick="window.location.href='<?php echo Zc::url(YfjRouteConst::addtMerchant);?>'">新增商户</button>
	</div>
 
	<table class="layui-table">
		<colgroup>
			<!--<col width="8%">
			<col width="8%">
			<col width="8%">
			<col width="8%">
			<col width="7%">
			<col width="7%">
			<col width="7%">
			<col width="9%">
			<col width="9%">
			<col width="20%">-->
			
		</colgroup>
		<thead>
		<tr>
			<th>商户名称/ID</th>
		
			<th>仓库名称</th>
			<th>联系人/手机号</th>
			<th>保税申报</th>
			<th>直邮申报</th>
			<th>小程序商城</th>
			<th>绑定商务</th>
            <th>小程序账号</th>
            <th>微信支付</th>
			<th>商户状态</th>
			<th>操作</th>
		</tr>
		</thead>
		<tbody>
		
		<?php if($list){
			foreach ($list as $info){
				?>
				<tr>
                    <input type="hidden" name="merchant_id" class="merchant_id" value="<?php echo $info['merchant_id'];?>">
                    <input type="hidden" name="sd_status" class="sd_status" value="<?php echo $info['status'];?>">
					<td><a href="<?php echo Zc::url(YfjRouteConst::merchantDetail,['merchant_id' => $info['merchant_id']])?>"><?php echo  $info['merchant_name'].'<br/>'.$info['merchant_code'];?></a></td>
					
                    <td><?php echo $warehouseList[$info['warehouse_id']];?></td>
					<td><?php echo $info['merchant_contact_name']."<br/>".$info['merchant_contact_phone'];?></td>
					<td class="allow-bonded-btn" attr-status="<?php echo $info['allow_bonded'];?>">
                        <?php echo $info['allow_bonded']>0?"<span style='color: #1E9FFF'>启用</span>":"禁用";?>
                    </td>
					<td class="allow-direct-btn" attr-status="<?php echo $info['allow_direct'];?>">
                        <?php echo $info['allow_direct']>0?"<span style='color: #1E9FFF'>启用</span>":'禁用'; ?>
                    </td>
                    <td class="allow-xcx-btn" attr-status="<?php echo $info['allow_xcx'];?>">
						<?php echo $info['allow_xcx']>0?"<span style='color: #1E9FFF'>启用</span>":'禁用'; ?>
                    </td>
                    <td class="bm-name save-bm-btn"><?php echo $bmList[$info['business_manager_id']];?></td>

                    <td><a href="<?php echo Zc::url(YfjRouteConst::merchantXcxDetail,['merchant_id' => $info['merchant_id']])?>"><?php echo $info['is_registered']>0?'已绑定':'未绑定';?></a></td>
                    <td><?php echo $info['is_wechat']>0?'已授权':'未授权';?></td>

                    <td>
                        <form class="layui-form">
                            <input type="checkbox" class="status-btn" name="stauts" lay-skin="switch" <?php echo $info['merchant_status']==1?'checked':'';?> lay-filter="filter" lay-text="正常|冻结" >
                        </form>
                    </td>
					<td>
						<div class="layui-inline">
							<button type="button" class="layui-btn layui-btn-sm edit-btn-css edit-btn" onclick="window.location.href='<?php echo Zc::url(YfjRouteConst::editMerchant,['merchant_id' => $info['merchant_id']]);?>'">编辑</button>
                            <button type="button" class="layui-btn layui-btn-sm edit-btn-css xcx-edit-btn" onclick="window.location.href='<?php echo Zc::url(YfjRouteConst::editMerchantXcx,['merchant_id' => $info['merchant_id']]);?>'">小程序</button>
							<button type="button" class="layui-btn layui-btn-sm delete-btn-css layui-btn-danger delete-btn" merchant-id= '<?php echo $info['merchant_id'];?>'>删除</button>
						</div>
					
					</td>
				</tr>
			
			<?php  }
		} else { ?>
			<tr>
				<td colspan="10" style="text-align: center"> 没有任何数据哦</td>
			</tr>
		<?php }?>
		</tbody>
	
	</table>
	<div id="page"><?php echo $pageHtml;?></div>
    
    <div style="display: none" id="bm-html">
     
            <div class="layui-input-inline">
                <select name="business_manager_id" id="bm-id" >
                <?php foreach ($bmList as $code => $name){?>
                    <option value="<?php echo $code?>"><?php echo $name?></option>
                <?php } ?>
                </select>
            </div>
      
        
    </div>
</div>
<script>
    layui.use(['form','layer'],function() {
        var layer = layui.layer;
        var form = layui.form;
        
        //删除
        $('.delete-btn').click(function () {
            var merchant_id = $(this).attr('merchant-id');
            layer.confirm('确认删除这条信息吗？',{btn:['确定','取消']},function(){

                layer.closeAll();
                $.ajax({
                    url:'delete_merchant?merchant_id='+merchant_id,
                    dataType:'json',
                    success:function (res) {
                        layer.msg(res.msg);
                        if(res.status == 'success'){
                            window.location.href='merchant_list';
                        }
                    }

                })
            })
        });
       
        //保税申报
        $('.allow-bonded-btn').click(function () {
            //获得下状态
            var sdStatus = $(this).parents('tr').find('.sd_status').val();
            if(sdStatus == '<?php echo EjsConst::merchantStatusOfFreeze;?>'){
                return false;
            }
            var merchantName = $(this).parents('tr').find('.merchant-name').text();
            var status = $(this).attr('attr-status');
            var merchantId = $(this).parents('tr').find('.merchant_id').val();
            if(status == 0){
                var statusName = '启用';
                var newStatus = 1;
            }else if(status == 1){
                var statusName = "禁用";
                var newStatus =0;
            }else{
                layer.msg('出错啦');
                return false;
            }
            
            if(!merchantId){
                layer.msg('出错啦');
                return false;
            }
            var msg = '您确定要'+statusName+'商户'+merchantName+"的保税申报吗？"
            
            layer.confirm(msg,{btn:['确定','取消']},function(){

                layer.closeAll();
                $.ajax({
                    url:'update_merchant_allow_bonded',
                    type:'post',
                    data:{'merchant_id':merchantId,'status':newStatus},
                    dataType:'json',
                    success:function (res) {
                        layer.msg(res.msg);
                        if(res.status == 'success'){
                            window.location.href='merchant_list';
                        }
                    }

                })
            })
        });
        
        //直邮申报
        $('.allow-direct-btn').click(function () {
            var sdStatus = $(this).parents('tr').find('.sd_status').val();
            if(sdStatus == '<?php echo EjsConst::merchantStatusOfFreeze;?>'){
                return false;
            }

            var merchantName = $(this).parents('tr').find('.merchant-name').text();
            var status = $(this).attr('attr-status');
            var merchantId = $(this).parents('tr').find('.merchant_id').val();
            if(status == 0){
                var statusName = '启用';
                var newStatus = 1;
            }else if(status == 1){
                var statusName = "禁用";
                var newStatus =0;
            }else{
                layer.msg('出错啦');
                return false;
            }

            if(!merchantId){
                layer.msg('出错啦');
                return false;
            }
            var msg = '您确定要'+statusName+'商户'+merchantName+"的直邮申报吗？";
            
            layer.confirm(msg,{btn:['确定','取消']},function(){

                layer.closeAll();
                $.ajax({
                    url:'update_merchant_allow_direct',
                    type:'post',
                    data:{'merchant_id':merchantId,'status':newStatus},
                    dataType:'json',
                    success:function (res) {
                        layer.msg(res.msg);
                        if(res.status == 'success'){
                            window.location.href='merchant_list';
                        }
                    }

                })
            })
        });



        //小程序js
        $('.allow-xcx-btn').click(function () {
            //获得下状态
            var sdStatus = $(this).parents('tr').find('.sd_status').val();
            if(sdStatus == '<?php echo EjsConst::merchantStatusOfFreeze;?>'){
                return false;
            }
            var merchantName = $(this).parents('tr').find('.merchant-name').text();
            var status = $(this).attr('attr-status');
            var merchantId = $(this).parents('tr').find('.merchant_id').val();
            if(status == 0){
                var statusName = '启用';
                var newStatus = 1;
            }else if(status == 1){
                var statusName = "禁用";
                var newStatus =0;
            }else{
                layer.msg('出错啦');
                return false;
            }

            if(!merchantId){
                layer.msg('出错啦');
                return false;
            }
            var msg = '您确定要'+statusName+'商户'+merchantName+"的保税申报吗？"

            layer.confirm(msg,{btn:['确定','取消']},function(){

                layer.closeAll();
                $.ajax({
                    url:'update_merchant_allow_xcx',
                    type:'post',
                    data:{'merchant_id':merchantId,'status':newStatus},
                    dataType:'json',
                    success:function (res) {
                        layer.msg(res.msg);
                        if(res.status == 'success'){
                            window.location.href='merchant_list';
                        }
                    }

                })
            })
        });
        
        
        
        layui.use('form',function () {
           var form = layui.form;
            //商户状态
            form.on('switch(filter)', function(data){
                var merchantId = $(this).parents('tr').find('.merchant_id').val();
                if(data.elem.checked == false){
                    var msg = '您确定要冻结选中的商户吗？';
                    var newStatus = 2;
                }else{
                    var msg = '您确定要解冻选中的商户吗？';
                    var newStatus = 1;
                }
                if(!merchantId){
                    layer.msg('信息出错了');
                    return false;
                }
                layer.confirm(msg,{btn:['确定','取消']},function(){

                    layer.closeAll();
                    $.ajax({
                        url:'update_merchant_status',
                        type:'post',
                        data:{'merchant_id':merchantId,'status':newStatus},
                        dataType:'json',
                        success:function (res) {
                            layer.msg(res.msg);
                            window.location.href='merchant_list';
                        }

                    })
                },function () {
                    window.location.href='merchant_list';
                });
            });
        });
      
        //绑定商户
        $('.save-bm-btn').click(function () {
            var merchantId = $(this).parents('tr').find('.merchant_id').val();
            var bm_name = $(this).parents('tr').find('.bm-name').text();
            if(!merchantId){
                layer.msg('参数错误');
                return false;
            }
            
            var html=
                '<form class="layui-form search_form"  >\
					 <div class="layui-form-item">\
					<div class="layui-inline">\
						<label class="layui-form-label" style="width:100px">*当前绑定商户:</label>\
						<label class="layui-form-label" style="width:110px">'+bm_name+'</label>\
					</div></div>\
					<div class="layui-form-item">\
					<div class="layui-inline">\
						<label class="layui-form-label">*绑定商务:</label>\
						  <div class="layui-input-inline">\
                <select name="business_manager_id" id="bm-id" >\
            <?php foreach ($bmList as $code => $name){?><option value="<?php echo $code?>"><?php echo $name?></option>\
                <?php } ?></select>\
                </div>\
					</div></div>\
				</form>';
      
            layer.open({
                type: 1,
                area:['350px','300px'],
                content: html, //这里content是一个普通的String
                btn:['确定','取消'],
                success:function() {
                    form.render();
                },
                btn1:function (index,layero) {
                    var bmId = layero.find('#bm-id').val();
                    layer.closeAll();

                    $.ajax({
                        url:'update_merchant_bm',
                        type:'post',
                        data:{'merchant_id':merchantId,'business_manager_id':bmId},
                        dataType:'json',
                        success:function (res) {
                            layer.msg(res.msg);
                            window.location.href='merchant_list';
                        }

                    });
                }
            });
        })
        

    });

</script>