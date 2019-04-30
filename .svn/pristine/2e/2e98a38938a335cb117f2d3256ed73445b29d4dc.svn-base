 <div class="bottom">
  </div>
</div>
<script>
$(function(){
        $.supersized({

        // 功能
        slide_interval     : 4000,    
        transition         : 1,    
        transition_speed   : 1000,    
        performance        : 1,    

        // 大小和位置
        min_width          : 0,    
        min_height         : 0,    
        vertical_center    : 1,    
        horizontal_center  : 1,    
        fit_always         : 0,    
        fit_portrait       : 1,    
        fit_landscape      : 0,    

        // 组件
        slide_links        : 'blank',    
        slides             : [    
                                 {image : 
								 '<?php    echo HtmlTool::getStaticFile(array('login/1.jpg'.$timeSlice));?>'								 
								 },
								 {image : 
								 '<?php    echo HtmlTool::getStaticFile(array('login/2.jpg'.$timeSlice));?>'								 
								 },
								 {image : 
								 '<?php    echo HtmlTool::getStaticFile(array('login/3.jpg'.$timeSlice));?>'								 
								 },
								 {image : 
								 '<?php    echo HtmlTool::getStaticFile(array('login/4.jpg'.$timeSlice));?>'								 
								 },
								 {image : 
								 '<?php    echo HtmlTool::getStaticFile(array('login/5.jpg'.$timeSlice));?>'								 
								 }
                       ]

    });
	//显示隐藏验证码
    $("#hide").click(function(){
        $(".code").fadeOut("slow");
    });
    $("#captcha").focus(function(){
        $(".code").fadeIn("fast");
    });
    //跳出框架在主窗口登录
   if(top.location!=this.location)	top.location=this.location;
    $('#user_name').focus();
    //if ($.browser.msie && ($.browser.version=="6.0" || $.browser.version=="7.0")){
    //    window.location.href='<?php //echo Zc::C('admin.templates.url');?>///ie6update.html';
    //}
    $("#captcha").nc_placeholder();
	//动画登录
    $('.btn-submit').click(function(e){
            $('.input-username,dot-left').addClass('animated fadeOutRight')
            $('.input-password-box,dot-right').addClass('animated fadeOutLeft')
            $('.btn-submit').addClass('animated fadeOutUp')
            setTimeout(function () {
                      $('.avatar').addClass('avatar-top');
                      $('.submit').hide();
                      $('.submit2').html('<div class="progress"><div class="progress-bar progress-bar-success" aria-valuetransitiongoal="100"></div></div>');
                      $('.progress .progress-bar').progressbar({
                          done : function() {$('#form_login').submit();}
                      }); 
              },
          300);

          });

    // 回车提交表单
    $('#form_login').keydown(function(event){
        if (event.keyCode == 13) {
            $('.btn-submit').click();
        }
    });
});

</script>
