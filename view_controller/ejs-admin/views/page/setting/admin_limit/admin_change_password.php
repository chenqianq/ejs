<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
    <legend>修改密码</legend>
</fieldset>

<form class="layui-form" lay-filter="admin_change_password_form" method="post" >
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="admin_id" value="<?php echo $adminInfo['id'];?>" />

    <div class="layui-form-item">
        <label class="layui-form-label">旧密码:</label>
        <div class="layui-input-inline">
            <input type="password" name="old_pw" id="old_pw" lay-verify="required" placeholder="请输入旧密码" autocomplete="off" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">请输入旧密码</div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">密码:</label>
        <div class="layui-input-inline">
            <input type="password" name="new_pw" id="new_pw" lay-verify="pass" placeholder="请输入新密码" autocomplete="off" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">请输数字和字母组合的6~12位长度密码</div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">确认密码:</label>
        <div class="layui-input-inline">
            <input type="password" name="new_pw2" lay-verify="confirmPass" placeholder="请重复新密码" autocomplete="off" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">请输数字和字母组合的6~12位长度密码</div>
    </div>

    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-filter="submit_form" lay-submit="">确定修改</button>
            <!--            <button type="reset" class="layui-btn layui-btn-primary">重置</button>-->
        </div>
    </div>
</form>
<script>
    layui.use(['form', 'layedit', 'laydate'], function(){
        var form = layui.form;
        form.render();
        //自定义验证规则
        form.verify({
            pass: [/^(?!\d+$)(?![a-zA-Z]+$)[\da-zA-Z]{6,12}$/, '请输数字和字母组合的6~12位长度密码']
            ,confirmPass:function(value){
                var newPwd = $("#new_pw").val();
                if(value !==newPwd) {
                    return '两次输入的密码不一致!';
                }
            }
        });

        form.on('submit(submit_form)', function(data) {
            $.ajax({
                url:'admin_change_password',
                data:data.form.serialize(),
                dataType:'json',
                type:'post',
                success:function(res) {
                    if (res.status == 'failed') {
                        layer.msg(res.msg);
                        return false;
                    }
                    layer.msg(res.msg, 1000, function() {
                        location.href = location.href();
                    });
                    return false;
                }
            })
        });
    });
</script>