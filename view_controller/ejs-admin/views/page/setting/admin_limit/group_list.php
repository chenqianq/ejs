<a class="layui-btn search_btn" href="<?php echo Zc::url(YfjRouteConst::adminLimitGroupAdd); ?>">添加用户组</a>
<div class="layui-form news_list">
    <form method="post" id='form_admin'>
        <input type="hidden" name="form_submit" value="ok" />
        <table class="layui-table" style="text-align: center">
            <colgroup>
                <col>
                <col width="20%">
            </colgroup>
            <thead>
            <tr>
                <th style="text-align: center">权限组</th>
                <th style="text-align: center">操作</th>
            </tr>
            </thead>
            <tbody class="news_content">
            <?php
            if(!empty($list) && is_array($list)) {
                foreach($list as $k => $v) {
                    ?>
                    <tr>
                        <td><?php echo $v['admin_group_name'];?></td>
                        <td>
                            <a class="layui-btn layui-btn-sm" href="<?php echo Zc::url(YfjRouteConst::adminLimitGroupEdit,['gid'=>$v['admin_group_id']]) ?>">编辑</a> <a class="layui-btn layui-btn-danger layui-btn-sm" onclick="groupSingleDelete('<?php echo Zc::url(YfjRouteConst::adminLimitGroupDelete,['gid'=>$v['admin_group_id']]) ?>')">删除</a>
                        </td>
                    </tr>
                    <?php
                }
            }
            else{ ?>
                <tr><td colspan="2" style="text-align: center">暂无数据</td></tr>
            <?php } ?>
            </tbody>
        </table>
    </form>
</div>
<div id="page"><?php echo $pageHtml; ?></div>
<script>
    /**
     * 管理员单个删除
     */
    function groupSingleDelete(url) {
        layer.confirm('确定删除该用户组？', {icon: 3, title: '提示信息'}, function (index) {
            location.href = url;
        });
    }
</script>
