<!DOCTYPE html>
<html dir="ltr" lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
		<title>您需要登录后才可以使用本功能</title>
		<base href="<?php echo HTTP_SERVER . DIR_WS_CATALOG;?>" />
		<?php 
			$timeSlice = '?20150302';
		?>
		
		<?php   echo HtmlTool::getStaticFile(array(
				'common/jquery.js'.$timeSlice,
				'common/common.js'.$timeSlice,
				'common/jquery.tscookie.js'.$timeSlice,
				'common/jquery.validation.min.js'.$timeSlice,
				'font/css/font-awesome.min.css'.$timeSlice,
				'login.css'.$timeSlice,
				'jquery.supersized.min.js'.$timeSlice,
				'jquery.progressBar.js'.$timeSlice
				));  ?>

				
    </head>
    <body >
    	<div id="wrapper">
	    	<?php Zc::W('login/header');?>
	    	<div id="content">
	    		<?php echo $_content_;?>
	    	</div>
	    	<?php Zc::W('login/footer');?>
    	</div>
    </body>
</html>
