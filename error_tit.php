<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>一番街首页</title>
	<style>
	.error_tit .hy-error-tit-cont {
	width: 680px;
	height: 650px;
	background: url(./error404-logo.png) no-repeat center 0;
	margin: 80px auto;
	position: relative;
	
}
.error_tit .hy-error-tit-cont div{
	position: absolute;
	top: 525px;
	left: 50%;
	margin-left: -160px;
}
.error_tit .hy-error-tit-cont span{
	color: #010101;
	font-size: 24px;
	font-family: "微软雅黑" ;	
	line-height: 32px;
}
.error_tit .hy-error-tit-cont p{
	font-size: 18px;
	color: #231815;
}
.error_tit .hy-error-tit-cont a{
	background: #ff7198;
    color: #fff;
    line-height: 18px;
    display: inline-block;
    font-size: 18px;
    font-family: "微软雅黑";
    padding: 8px 50px;
    border: transparent solid 1px;
    border-radius: 10px;
    margin:15px 0 0 62px;
    text-decoration: none;
}
.error_tit .hy-error-tit-cont a:hover{
	background: #ff90a6;
}
	</style>
 <script type="text/javascript" src="http://http://localhost/yifanjie/data/resource/js/new/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="http://http://localhost/yifanjie/data/resource/js/new/JS/jquery-ui.min.js"></script>
<script type="text/javascript" src="http://http://localhost/yifanjie/data/resource/js/new/JS/jquery-SuperSlide.2.1.1.js"></script>
 
</head>
<body class="error_tit">
	<div class="hy-error-tit-cont">
		<div id="">
			<span>对不起,您访问的页面不存在……</span>
			<p>页面将于 <span id="hyErrorTitS" style="color:#ff7198;">99</span> 秒后自动返回首页</p>
			<a href="http://www.yifanjie.com">返回上一页</a>
		</div>
	</div>
</body>
<script type="text/javascript">

/**
 * 倒计时跳转
 * @param {Number} ti时间
 */
function Countdown(ti){
	if(ti == 0){
		//跳转地址
		window.location.href="http://www.yifanjie.com";
		return;
	}else{
		ti--;
		$('#hyErrorTitS').text(ti);
	}
	setTimeout(function(){
		Countdown(ti);
	},1000);	
}
Countdown(10);
</script>
</html>