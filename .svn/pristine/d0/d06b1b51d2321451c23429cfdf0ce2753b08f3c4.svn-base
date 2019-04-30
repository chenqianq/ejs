<style>
	.layui-table th{
		font-weight: 600;
	}
</style>
<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
	<legend>商户信息</legend>
</fieldset>

	<table class="layui-table" style="width: 70%;margin-left: 30px">
		<thead>
		<tr>
			<th colspan="2">账户信息<?php echo ' ('.str_replace([EjsConst::merchantStatusOfNomal,EjsConst::merchantStatusOfFreeze],['正常','冻结'],$merchantInfo['merchant_status']).")";?></th>
		
		</tr>
		</thead>
		<tbody>
		<tr>
			<td>商户名称：<?php echo $merchantInfo['merchant_name'];?></td>
			<td>商户编号：<?php echo $merchantInfo['merchant_code'];?></td>
		</tr>
		<tr>
			<td colspan="2">企业名称：<?php echo $merchantInfo['company_name'];?></td>
			
		</tr>
		<tr>
			<td>企业联系人：<?php echo $merchantInfo['merchant_contact_name'];?></td>
			<td>对接商务：<?php echo $bmInfo['bm_name']."-". $bmInfo['bm_contact_phone'];?></td>
			
		</tr>
		<tr>
			<td>登录名：<?php echo $merchantInfo['login_name'];?>
			</td>
			<td>绑定手机号：<?php echo $merchantInfo['admin_mobile'];?></td>
		</tr>
		</tbody>
	</table>
	

	
	<table class="layui-table" style="width: 70%;margin-left: 30px">
		<thead>
		<tr>
			<th colspan="2">业务范围</th>
		
		</tr>
		</thead>
		<tbody>
		<tr>
			<td>直邮仓：<?php echo $merchantInfo['allow_direct']>0?'启用':'禁用';?></td>
			<td>保税仓：<?php echo $merchantInfo['allow_bonded']>0?'启用':'禁用';?></td>
		</tr>
        <tr>
            <td colspan="2">小程序商城：<?php echo $merchantInfo['allow_xcx']>0?'启用':'禁用';?></td>
           
        </tr>
		</tbody>
	</table>
    <button type="button" class="layui-btn layui-btn-primary" onclick="history.go(-1)">返回</button>

<script>


</script>