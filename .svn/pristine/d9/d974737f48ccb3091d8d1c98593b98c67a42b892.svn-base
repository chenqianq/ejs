<!DOCTYPE html>
<html dir="ltr" lang="en">
    <head>
        <meta charset="UTF-8" />
		<title>二加三后台管理中心</title>
        <meta charset="utf-8">
        <meta name="renderer" content="webkit">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta http-equiv="Access-Control-Allow-Origin" content="*">
        <meta name="format-detection" content="telephone=no">
        <link rel="icon" href="<?php echo HtmlTool::getStaticFile("yfj_logo.png"); ?>">
        <?php
        $timeSlice = '?2017120601';
        echo HtmlTool::getStaticFile(array(
                'layui/css/layui.css'.$timeSlice,
                'main.css'.$timeSlice,
                'font_eolqem241z66flxr.css'.$timeSlice,
                'layui/layui.js'.$timeSlice,
                'index.js'.$timeSlice,
                'init.js'.$timeSlice,
               ));

        if(HelperFactory::getIpHelper() -> isMobile()){
	        echo "<style>
.layui-tab-item{-webkit-overflow-scrolling: touch; overflow-y: scroll;}
</style>";
        }
        ?>
        
        
    </head>
    <body class="main_body">
        <div class="layui-layout layui-layout-admin">
            <?php Zc::W('init/header');
                   echo $_content_;
                   Zc::W('init/footer');?>
        </div>
    </body>
</html>
