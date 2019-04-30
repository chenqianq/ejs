<style>
	.edit-data{
		display: none;
	}
</style>

<div id="common" class="common">
	<form class="layui-form search_form" method="get" action="" >
		<blockquote class="layui-elem-quote">
			
			<div class="layui-inline W160H26">
				<label class="layui-form-label">客户名称:</label>
				<div class="layui-input-inline">
					<input type="text" value="<?php echo $_GET['client_name']?>" name="client_name"  placeholder="" class="layui-input search_input">
				</div>
			</div>
			
			<div class="layui-inline W160H26">
				<label class="layui-form-label">手机号:</label>
				<div class="layui-input-inline">
					<input type="text" value="<?php echo $_GET['contact_phone']?>" name="contact_phone"  placeholder="" class="layui-input search_input">
				</div>
			</div>

            <div class="layui-inline W160H26">
                <label class="layui-form-label">客户ID:</label>
                <div class="layui-input-inline">
                    <input type="text" value="<?php echo $_GET['client_id']?>" name="client_id"  placeholder="" class="layui-input search_input">
                </div>
            </div>
            <div class="layui-inline W160H26">
                <label class="layui-form-label">客户状态:</label>
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
		<button type="button" class="layui-btn add_new_client" onclick="window.location.href='<?php echo Zc::url(YfjRouteConst::addClient);?>'">新增客户</button>
	</div>
	
	<table class="layui-table">
		<colgroup>
			<col width="10%">
			<col width="8%">
			<col width="8%">
			<col width="8%">
			<col width="10%">
			<col width="10%">
            <col >
			<col width="18%">
		</colgroup>
		<thead>
		<tr>
			<th>客户ID</th>
			<th>客户名称</th>
			<th>客户状态</th>
			<th>联系人</th>
			<th>手机号</th>
            <th>绑定商务</th>
            <th>备注</th>
            <th>操作</th>
		</tr>
		</thead>
		<tbody>
		
		<?php if($list){
			foreach ($list as $info){
				?>
				
				<tr>
					<td><?php echo substr(strval($info['client_id']+10000000000),1,11);?></td>
					<td><?php echo $info['client_name'];?></td>
					<td><?php echo $status[$info['status']];?></td>
					<td><?php echo $info['contact_name'];?></td>
					<td><?php echo $info['contact_phone_a']?$info['contact_phone_a']:'';
						echo $info['contact_phone_b']?'<br/>'.$info['contact_phone_b']:'';
						echo $info['contact_phone_c']?'<br/>'.$info['contact_phone_c']:'';
					    ?>
                    </td>
                    <td class="bm-name"><?php echo $bmList[$info['business_manager_id']];?></td>
                    <td><?php echo $info['remark'];?></td>
                   
					<td>
						<div class="layui-inline">
							<button type="button" class="layui-btn layui-btn-sm edit-btn-css edit-btn" onclick="window.location.href='<?php echo Zc::url(YfjRouteConst::editClient,['client_id' => $info['client_id']]);?>'">编辑</button>
						
                            <button type="button" class="layui-btn layui-btn-sm edit-btn-css save-bm-btn" client-id="<?php echo $info['client_id'];?>">绑定商务</button>
                            <button type="button" class="layui-btn layui-btn-sm delete-btn-css layui-btn-danger delete-btn" client-id = '<?php echo $info['client_id'];?>'>删除</button>
						</div>
					
					</td>
				</tr>
			
			<?php  }
		} else { ?>
			<tr>
				<td colspan="8" style="text-align: center"> 没有任何数据哦</td>
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
        $('.delete-btn').click(function () {
            var client_id = $(this).attr('client-id');
            layer.confirm('确认删除这条信息吗？',{btn:['确定','取消']},function(){
              
                layer.closeAll();
                $.ajax({
                    url:'delete_client?client_id='+client_id,
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
        //绑定商务
        $('.save-bm-btn').click(function () {
            var clientId  = $(this).attr('client-id');
            var bm_name = $(this).parents('tr').find('.bm-name').text();
            if(!clientId){
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
                        url:'update_client_bm',
                        type:'post',
                        data:{'client_id':clientId,'business_manager_id':bmId},
                        dataType:'json',
                        success:function (res) {
                            layer.msg(res.msg);
                            window.location.href='client_list';
                        }

                    });
                }



            });
        })
    })



</script>