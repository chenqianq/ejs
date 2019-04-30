

  <form method="post" id="form_login" >
    <?php /* Security::getToken(); */?>
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="SiteUrl" id="SiteUrl" value="<?php echo Zc::C('web.site.url');?>" />
    <div class="lock-holder">
      <div class="form-group pull-left input-username">
        
          <label>账号</label>
          <input name="user_name" id="user_name" autocomplete="off" type="text" class="input-text" value="" required>
          
      </div>
      <i class="fa fa-ellipsis-h dot-left"></i> <i class="fa fa-ellipsis-h dot-right"></i>
      <div class="form-group pull-right input-password-box">
          <label>密码</label>
          <input name="password" id="password" class="input-text" autocomplete="off" type="password" required pattern="[\S]{6}[\S]*" title="密码不少于6个字符">
      </div>
    </div>
	 <div class="avatar"><img src="<?php echo HtmlTool::getStaticFile(array('login/admin.png'.$timeSlice));?>" alt=""></div> 
	  <div class="submit"> <span>
      <div class="code">
        <div class="arrow"></div>
      <div class="code-img"><img src="<?php echo Zc::url(YfjRouteConst::makeCode,['admin'=>1]);?>" name="codeimage" id="codeimage" border="0"/></div>
        <a href="JavaScript:void(0);" id="hide" class="close" title="关闭"><i></i></a><a href="JavaScript:void(0);" onclick="javascript:document.getElementById('codeimage').src='<?php echo Zc::url(YfjRouteConst::makeCode,['admin'=>1]);?>&t=' + Math.random();" class="change" title="看不清,点击更换验证码"><i></i></a> </div>
      <input name="captcha" type="text" required class="input-code" id="captcha" placeholder="输入验证" pattern="[A-z0-9]{4}" title="验证码为4个字符" autocomplete="off" value="" >
      </span> <span>
      <!--<input name="nchash" type="hidden" value="1111" /> -->
      <input name="" class="input-button btn-submit" type="button" value="登录">
      </span> </div> 
      <div class="submit2"></div>  
 
  </form>
 
