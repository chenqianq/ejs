<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
    <legend>修改管理员信息</legend>
</fieldset>

<form class="layui-form" method="post" >
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="admin_id" value="<?php echo $adminInfo['admin_id'];?>" />

    <div class="layui-form-item">
        <label class="layui-form-label">登录名:</label>
        <div class="layui-form-mid layui-word-aux"><?php echo $adminInfo['admin_name']; ?></div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">密码:</label>
        <div class="layui-input-inline">
            <input type="password" name="new_pw" id="new_pw" lay-verify="pass" placeholder="请输入密码" autocomplete="off" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">请填写6到12位密码</div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">确认密码:</label>
        <div class="layui-input-inline">
            <input type="password" name="new_pw2" lay-verify="confirmPass" placeholder="请输入密码" autocomplete="off" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">请填写6到12位密码</div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">权限组:</label>
        <div class="layui-input-inline">
            <select name="gid" title="权限组">
                <?php foreach((array)$gadmin as $v){?>
                    <option <?php if ($v['admin_group_id'] == $adminInfo['admin_group_permission_id']) echo 'selected';?> value="<?php echo $v['admin_group_id'];?>"><?php echo $v['admin_group_name'];?></option>
                <?php }?>
            </select>
        </div>
        <div class="layui-form-mid layui-word-aux">请选择一个权限组，如果还未设置，请马上设置</div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">添加分组:</label>
        <div class="layui-input-inline">
            <input title="" <?php if((int)($adminInfo['is_allow_group'])>0){ echo "checked";} ?> value="addGroup" type="checkbox" name="add_group" lay-skin="switch" lay-filter="switch" lay-text="是|否">
        </div>
        <div class="layui-form-mid layui-word-aux">	请选择是否允许添加分组</div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">超级管理员:</label>
        <div class="layui-input-inline">
            <input title="" type="checkbox" <?php if((int)($adminInfo['admin_is_super'])>0){ echo "checked";} ?> value="1" name="super_admin" lay-skin="switch" lay-text="是|否">
        </div>
        <div class="layui-form-mid layui-word-aux">请选择是否设置为超级管理员</div>
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
            pass: [/^$|^(.+){6,12}$/, '密码必须6到12位']
            ,confirmPass:function(value){
                var newPwd = $("#new_pw").val();
                if(value !==newPwd) {
                    return '两次输入的密码不一致!';
                }
            }
        });
    });
</script>