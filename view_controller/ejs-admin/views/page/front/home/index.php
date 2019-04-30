<!-- 右侧内容 -->
<div class="layui-body layui-form">
    <div class="layui-tab marg0" lay-filter="bodyTab" lay-allowclose="true">
        <!-- tab 标签 -->
        <ul class="layui-tab-title top_tab">
            <li class="layui-this" lay-id="dashboard-dashboard-welcome">
                <i class="iconfont icon-computer"></i>
                <cite>欢迎页面</cite>
            </li>
        </ul>
        <!-- iframe -->
        <div class="layui-tab-content clildFrame">
            <div class="layui-tab-item layui-show">
                <iframe src="<?php echo Zc::url(YfjRouteConst::dashboardWelcome); ?>"></iframe>
            </div>
        </div>
    </div>
</div>