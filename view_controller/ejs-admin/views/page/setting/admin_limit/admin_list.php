<a class="layui-btn search_btn" href="<?php echo Zc::url(YfjRouteConst::adminLimitAdminAdd); ?>">添加管理员</a>
<div class="layui-form news_list">
    <form method="post" id='form_admin'>
        <input type="hidden" name="form_submit" value="ok" />
        <table class="layui-table">
            <colgroup>
                <col width="50">
                <col width="15%">
                <col>
                <col width="15%">
                <col width="15%">
                <col width="15%">
            </colgroup>
            <thead>
            <tr>
                <th><input title="" type="checkbox" lay-skin="primary" lay-filter="allChoose" class="allChoose"></th>
                <th>登录名</th>
                <th>上次登录</th>
                <th>登录次数</th>
                <th>权限组</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody class="news_content">
            <?php
            if(!empty($adminList) && is_array($adminList)) {
                foreach ($adminList as $k => $v) {
                    ?>
                    <tr>
                        <td>
                            <input title=""  name="del_id[]" value="<?php echo $v['admin_id']; ?>" lay-skin="primary" lay-filter="choose" type="checkbox"/>
                        </td>
                        <td><?php echo $v['admin_name'];?></td>
                        <td><?php echo $v['admin_login_time'] ? date('Y-m-d H:i:s',$v['admin_login_time']) : '未登录'; ?></td>
                        <td><?php echo $v['admin_login_num']; ?></td>
                        <td><?php echo $adminGroupArray[$v['admin_group_permission_id']]?$adminGroupArray[$v['admin_group_permission_id']]['admin_group_name']:""; ?></td>
                        <td>
                            <a class="layui-btn layui-btn-sm" href="<?php echo Zc::url(YfjRouteConst::adminLimitAdminEdit,['admin_id'=>$v['admin_id']]) ?>">编辑</a> <a class="layui-btn layui-btn-danger layui-btn-sm" onclick="adminSingleDelete('<?php echo Zc::url(YfjRouteConst::adminLimitAdminDelete,['admin_id'=>$v['admin_id']]) ?>')">删除</a>
                        </td>
                    </tr>
                    <?php
                }
            }
            else{ ?>
                <tr><td colspan="6" style="text-align: center">暂无数据</td></tr>
            <?php } ?>
            </tbody>
            <tfoot>
               <tr>
                   <td><input title="" type="checkbox" lay-skin="primary" lay-filter="allChoose" class="allChoose"></td>
                   <td colspan="5">
                       <div class="layui-btn-group">
                           <a class="layui-btn layui-btn-danger" data-type="deleteSelectedData" onclick="adminDelete()">删除</a>
                       </div>
                   </td>
               </tr>
            </tfoot>
        </table>
    </form>
</div>
<div id="page"><?php echo $pageHtml; ?></div>
<script>
    function adminDelete() {
        var checkbox = $('.news_list tbody input[type="checkbox"][name="del_id[]"]');
        if(checkbox.is(":checked")){
            layer.confirm('确定删除选中的管理员？',{icon:3, title:'提示信息'},function(index){
                $('#form_admin').submit();
            })
        }else{
            layer.msg("请选择需要删除的管理员");
        }
    }
    /**
     * 管理员单个删除
     */
    function adminSingleDelete(url) {
        layer.confirm('确定删除该管理员？', {icon: 3, title: '提示信息'}, function (index) {
            location.href = url;
        });
    }
</script>