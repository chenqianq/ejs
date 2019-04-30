<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
    <legend>更新权限名称</legend>
</fieldset>

<form class="layui-form" action="" method="post" >
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="permissionId" value="<?php echo $permissionInfo['id']; ?>">
    <input type="hidden" name="permissionName" value="<?php echo $permissionInfo['route']; ?>">
    <div class="layui-form-item">
        <label class="layui-form-label">权限route：</label>

        <div class="layui-form-mid layui-word-aux"><?php echo $permissionInfo['route']; ?></div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">权限名：</label>
        <div class="layui-input-inline">
            <input type="text"  lay-verify="title"  name="permissionDescription" value="<?php echo $permissionInfo['desc']; ?>" autocomplete="off" placeholder="请输入权限名" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            <a class="layui-btn layui-btn-normal" onclick="history.go(-1)">返回</a>
        </div>
    </div>
</form>
<script>
    layui.use(['form', 'layedit', 'laydate'], function(){
        var form = layui.form;
        form.render();
        //自定义验证规则
        form.verify({
            title: function(value){
                if(value.length < 1){
                    return '权限名至少输入一位!';
                }
            }
        });
    });
</script>