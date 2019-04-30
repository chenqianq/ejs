<!-- 顶部 -->
<div class="layui-header header">
    <div class="layui-main">
        <a href="<?php echo Zc::url(YfjRouteConst::index); ?>" class="logo">商城管理中心</a>
        <!-- 搜索 -->
        <div class="component my-tab">
            <?php
                echo $top_nav;
            ?>
        </div>
        <!-- 顶部右侧菜单 -->
        <ul class="layui-nav top_menu">
            <li class="layui-nav-item my-right" pc>
                <a href="<?php echo Zc::url(YfjRouteConst::loginOut); ?>">
                    <cite>退出</cite>
                </a>
            </li>
        </ul>
    </div>
</div>

<!-- 左侧二级导航 -->
<div class="layui-side layui-bg-black second-part">
    <div class="user-photo">
        <div style="text-align: center">
            <img src="<?php echo HtmlTool::getStaticFile("yfj_logo.png"); ?>">
        </div>
        <p><span class="userName"><?php echo $_SESSION[SessionConst::adminName]; ?></span></p>
    </div>
    <div class="navBar layui-side-scroll">
	    
            <?php echo $left_nav; ?>
    </div>
</div>
