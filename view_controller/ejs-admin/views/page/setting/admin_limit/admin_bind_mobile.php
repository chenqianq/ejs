<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
    <legend>验证手机号</legend>
</fieldset>

<form class="layui-form" method="post" >
    <input type="hidden" name="form_submit" value="no" />
    <input type="hidden" name="send_mobile_submit" value="ok" />
    <input type="hidden" name="admin_id" value="<?php echo $adminInfo['id'];?>" />

    <div class="layui-form-item">
        <label class="layui-form-label">手机号:</label>
        <div class="layui-input-inline">
            <input type="text" name="mobile" id="mobile" placeholder="请输入手机号" class="layui-input" maxlength="11">
        </div>
        <div class="layui-form-mid layui-word-aux">请输入手机号</div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">验证码:</label>
        <div class="layui-input-inline">
            <input type="text" name="log_captcha" id="log_captcha" placeholder="请输入手机验证码" autocomplete="off" class="layui-input" style="display:inline">
        </div>
        <div class="layui-inline">
            <button class="layui-btn layui-btn-sm send_code_btn">发送验证码</button>
            <div class="count_time_btn" style="display: none;">重新发送等待(<span id="count_time"></span>)</div>
        </div>

        <div class="layui-inline layui-word-aux">请输入手机验证码</div>
    </div>

    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-filter="submit_form" lay-submit="">确定</button>
        </div>
    </div>
</form>
<script>
    layui.use(['form', 'layedit', 'laydate'], function(){
        var form = layui.form;
        form.render()

        var setRefreshTime = <?php echo $countTimeRefreshTime; ?>; //60000; // 秒


        // 发送验证码
        $('.send_code_btn').click(function() {
            var mobile = $('input[name="mobile"]').val();
            var send_mobile_submit = $('input[name="send_mobile_submit"]').val();

            if (!mobile) {
                layer.msg('请填写手机号');
                return false;
            }

            $.ajax({
                url:'admin_bind_mobile',
                data:{mobile:mobile, send_mobile_submit: send_mobile_submit},
                dataType:'json',
                type:'post',
                success:function(res) {
                    if (res.status == 'failed') {
                        layer.msg(res.msg);
                        return false;
                    }
                    layer.msg(res.msg);
                    count_time(setRefreshTime);

                    return false;
                }
            });
            return false;
        });

        form.on('submit(submit_form)', function(data) {
            var mobile = $('input[name="mobile"]').val();
            var form_submit = $('input[name="form_submit"]').val();
            var admin_id = $('input[name="admin_id"]').val();
            var log_captcha = $('input[name="log_captcha"]').val();

            if (!mobile) {
                layer.msg('请填写手机号');
                return false;
            }
            if (!log_captcha) {
                layer.msg('请填写验证码');
                return false;
            }

            $.ajax({
                url:'admin_bind_mobile',
                data:{mobile:mobile, form_submit: form_submit, admin_id : admin_id, log_captcha:log_captcha},
                dataType:'json',
                type:'post',
                success:function(res) {
                    if (res.status == 'failed') {
                        layer.msg(res.msg);
                        return false;
                    }
                    layer.msg(res.msg, {time:1000}, function() {
                        console.log(res);
                        location.reload();
                    });
                    return false;
                }
            })
            return false;
        });

        function count_time(setRefreshTime) {
            var time = setRefreshTime;

            $("#count_time").html(time);
            $('.send_code_btn').hide();
            $('.count_time_btn').show();
            $('input[name="form_submit"]').val('ok');

            var clock = setInterval(function() {
                time -= 1;
                $("#count_time").html(time);
                if (parseInt(time) == 0) {
                    $('.send_code_btn').show();
                    $('.count_time_btn').hide();
                    clearInterval(clock);
                }
            }, 1000)
        }

    });
</script>