<meta charset="UTF-8" />
<title>一番街</title>
<meta charset="utf-8">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta http-equiv="Access-Control-Allow-Origin" content="*">
<meta name="format-detection" content="telephone=no">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="<?php echo HtmlTool::getStaticFile("yfj_logo.png"); ?>">
<?php
$timeSlice = '?2017120602';
echo HtmlTool::getStaticFile(array(
    'layui/css/layui.css'.$timeSlice,
    'common/common.css'.$timeSlice,
    'font_eolqem241z66flxr.css'.$timeSlice,
    'list.css'.$timeSlice,
    'common/jquery.js'.$timeSlice,
    'common/configure.js'.$timeSlice,
    'common/common.js'.$timeSlice,
    'layui/layui.js'.$timeSlice,
    'list.js'.$timeSlice,
    'ajaxfileupload/ajaxfileupload.js'.$timeSlice,
    'common/jquery.validation.min.js'.$timeSlice,
    'clipboard.min.js'.$timeSlice,
));
?>