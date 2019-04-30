<div class="layui-form news_list">
    <form method="post" class="layui-form" >
        <input type="hidden" name="form_submit" value="ok" />
        <div class="layui-form-item">
            <label class="layui-form-label">用户组名称:</label>
            <div class="layui-input-inline">
                <input type="text" name="gname" lay-verify="title" placeholder="请输入用户组名称" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-tab">
            <ul class="layui-tab-title">
                <?php $i = 1;
                foreach ($limitArray as $site => $siteGroup) { ?>
                    <li class="<?php echo $i == 1 ? 'layui-this':''; ?>"><?php echo $site; ?></li>
                <?php $i ++;
                } ?>
            </ul>
            <div class="layui-tab-content">
            <?php $i = 1;
            foreach ($limitArray as $site => $siteGroup) { ?>
                <div class="layui-tab-item <?php echo  $i == 1 ? 'layui-show':''; ?>">
                <?php   $i++;
                foreach ($siteGroup as $leftMenu){ ?>
                <table class="layui-table">
                    <colgroup>
                        <col>
                    </colgroup>
                    <thead>
                    <tr>
                        <th><input title="<?php echo $leftMenu["topName"]; ?>" type="checkbox" lay-skin="primary" lay-filter="allChoose" class="allChoose"></th>
                    </tr>
                    </thead>
                    <tbody class="news_content">
                    <?php
                        foreach($leftMenu["child"] as $leftMenuName => $childRouteArray) {
                            ?>
                            <tr class="leftMenu">
                                <th>&nbsp;&nbsp;&nbsp;<strong><input title="<?php echo $leftMenuName;?>" lay-skin="primary" lay-filter="leftMenuChoose" type="checkbox"/></strong></th>
                            </tr>
                            <tr>
                                <td style="padding-left: 50px;">
                                    <?php
                                    foreach ($childRouteArray as $childRoute){
                                        echo "<input title=\"".$childRoute["desc"]."\"  name=\"permission_id[]\" value=\"".$childRoute["id"]."\" lay-skin=\"primary\" lay-filter=\"choose\" type=\"checkbox\"/>";
                                    } ?>
                               </td>
                             </tr>
                     <?php   }
                     ?>
                    </tbody>
                </table>
                <?php } ?>
                </div>
            <?php } ?>
            </div>
        </div>
        <br/>
        <div class="layui-form-item">
                <button class="layui-btn">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                <a class="layui-btn layui-btn-normal" onclick="history.go(-1)">返回</a>
        </div>
    </form>

</div>
<script>
    layui.use(['form', 'element'], function(){
        var form = layui.form;
        var element = layui.element;
        form.render();
        //自定义验证规则
        form.verify({
            title:function(value){
                if(value.length < 1){
                    return '请输入用户组名称!';
                }
            }
        });
    });
</script>