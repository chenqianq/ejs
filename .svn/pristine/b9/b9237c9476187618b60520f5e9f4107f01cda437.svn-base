<style>
    .show {
        display: inline-block;
    }
    .hide {
        display: none;
    }
    .column-main {
        display: inline-block;
        width:600px;
    }
    .column-main .title {
        font-size: 18px;
        font-weight: bold;
        padding: 9px 15px;
        text-align: right;
    }
</style>
<div style="padding: 20px;">
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>平台参数配置</legend>
    </fieldset>

    <?php foreach ($settingParamArray as $entrepotTypeName => $settingInfoArray) { ?>
    <div class="column-main">
        <div class="layui-field-box">
            <div class="title w160">
                <?php echo $entrepotTypeName; ?>
            </div>
        </div>
        <?php foreach ($settingInfoArray as $settingInfo) {?>
        <div class="layui-field-box">
            <form class="layui-form" action="" id="">
                <div class="layui-form-item">
                    <input type="hidden" name="setting_name" value="<?php echo  $settingInfo['name']; ?>">
                    <input type="hidden" name="setting_title" value="<?php echo  $settingInfo['title']; ?>">
                    <label class="layui-form-label w160"><?php echo  $settingInfo['title']; ?></label>
                    <div class="layui-input-inline">
                        <input type="text" value="<?php echo  $settingInfo['value']; ?>" autocomplete="off" class="layui-input layui-btn-disabled" name="setting_value" disabled origin_value="<?php echo  $settingInfo['value']; ?>">
                    </div>
                    <div class="layui-form-mid layui-word-aux"></div>
                    <a class="layui-btn layui-btn-normal editBtn">编辑</a>
                    <a class="layui-btn submitBtn hide hideGroup">提交</a>
                    <a class="layui-btn layui-btn-primary cancelBtn hide hideGroup" >取消</a>
                </div>
            </form>
        </div>
        <?php } ?>
    </div>
    <?php } ?>

    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>操作历史</legend>
    </fieldset>
    <table class="layui-table">
        <tr>
            <th>操作时间</th>
            <th>操作人</th>
            <th>操作内容</th>
        </tr>
        <?php foreach ($logArray as $log) { ?>
        <tr>
            <td><?php echo $log['gmt_create']; ?></td>
            <td><?php echo $log['operator']; ?></td>
            <td><?php echo $log['content']; ?></td>
        </tr>
        <?php } ?>
    </table>
</div>

<script type="text/javascript">
    var lock = false;

    $('.editBtn').click(function() {
        $(this).parents("form").find('.hideGroup').removeClass('hide');
        $(this).addClass('hide');
        $(this).parents("form").find('input[name="setting_value"]').removeClass('layui-btn-disabled').prop('disabled', false);
    });

    $('.cancelBtn').click(function() {
        $(this).parents("form").find('.hideGroup').addClass('hide');
        $(this).parents("form").find('.editBtn').removeClass('hide');
        var origin_value = $(this).parents("form").find('input[name="setting_value"]').attr('origin_value');
        $(this).parents("form").find('input[name="setting_value"]').addClass('layui-btn-disabled').prop('disabled', true).val(origin_value);
    });

    $('.submitBtn').click(function() {
        if (lock == true) {
            return false;
        }
        lock = true;
        var that = this;
        var setting_value = $(this).parents("form").find('input[name="setting_value"]').val();
        var setting_name = $(this).parents("form").find('input[name="setting_name"]').val();
        var setting_title = $(this).parents("form").find('input[name="setting_title"]').val();
        var origin_value = $(this).parents("form").find('input[name="setting_value"]').attr('origin_value');
        if (isNaN(parseFloat(setting_value))) {
            layer.msg('请设置正确的参数值');
            lock = false;
            return false;
        }
        if (parseFloat(setting_value) == parseFloat(origin_value)) {
            layer.msg('未修改参数', {time:500});
            lock = false;
            return false;
        }

        $.ajax({
            url:'setting_list',
            data:{is_submit:'ok', setting_name:setting_name, setting_value:setting_value, setting_title:setting_title},
            type:'post',
            dataType:'json',
            success:function(res) {
                if (res.status == 'failed') {
                    layer.msg(res.msg);
                    lock = false;
                    return false;
                }
                $(that).parents("form").find('.hideGroup').addClass('hide');
                $(that).parents("form").find('.editBtn').removeClass('hide');
                $(that).parents("form").find('input[name="setting_value"]').addClass('layui-btn-disabled').prop('disabled', true);
                layer.msg(res.msg, {time:1000}, function() {
                    $(that).parents("form").find('input[name="setting_value"]').val(setting_value).attr('origin_value', setting_value);
                });
                lock = false;
            }
        });
        return false;
    });
</script>
