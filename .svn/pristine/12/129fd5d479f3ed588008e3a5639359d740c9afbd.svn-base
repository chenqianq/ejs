<a class="layui-btn search_btn" href="<?php echo Zc::url(YfjRouteConst::updateExistsPermission); ?>">更新权限</a>
<div class="layui-form news_list">
    <table class="layui-table">
        <colgroup>
            <col width="15%">
            <col>
            <col width="15%">
            <col width="15%">
            <col width="15%">
        </colgroup>
        <thead>
        <tr>
            <th style="text-align:left;">权限名</th>
            <th>权限route</th>
            <th>权限所属顶部导航</th>
            <th>权限所属侧栏名称</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody class="news_content">
        <?php
        if($permissionList){
        foreach($permissionList as $v) {
        foreach ($v['child'] as $value) {
        ?>
        <tr>
            <td><?php echo $value["desc"]; ?></td>
            <td><?php echo $value["route"]; ?></td>
            <td><?php echo $v["topName"]; ?></td>
            <td><?php echo $value["class_desc"]; ?></td>
            <td><a class="layui-btn layui-btn-sm" href="<?php echo Zc::url(YfjRouteConst::permissionEdit,['permissionId'=>$value['id']]); ?>">编辑</a></td>
        </tr>
        <?php }}}else{ ?>
        <tr><td colspan="5">暂无数据</td></tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<div id="page" data-num="80"><?php echo $pageHtml; ?></div>