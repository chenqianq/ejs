
var $, index=0;
layui.config({
	base : "js/"
}).use(['form','element','jquery'],function(){
	var element = layui.element;
		$ = layui.jquery;
    element.on('tabDelete(bodyTab)', function(data){
        var menu = JSON.parse(window.sessionStorage.getItem("menu"));
        menu.splice(data.index-1, 1);
        window.sessionStorage.setItem("menu",JSON.stringify(menu));
    });

    element.on('tab(bodyTab)', function(data){
        var layId = $(".layui-tab").find("li").eq(data.index).attr("lay-id");
        window.sessionStorage.setItem("curmenu",layId);
    });

	// 添加新窗口
	$(".layui-side .layui-nav-child a").on("click",function(){

        var title = $(this).text();
		var url = $(this).data("url");
		//console.log(url);
		//console.log(window.location.host);
		if(url == 'http://'+window.location.host+"/new-yfj-admin-center/marketing/order/mobile_order_list"){
		    window.location.href ='http://'+window.location.host+'/new-yfj-admin-center/marketing/order/mobile_order_list';
            return false;
		}

		var layId = $(this).data("param");
		var itemNum = $("li[lay-id="+layId+"]").length;
        $(".second-part dd").removeClass("layui-this");
        $(this).parents("dd").addClass("layui-this");
		if(itemNum >0) {
            element.tabChange('bodyTab', layId);
            $("."+layId).attr('src',$("."+layId).attr('src'));
        }else {
            element.tabAdd('bodyTab', {
                title: title //用于演示
                , content: '<iframe class="'+layId+'" src="' + url + '"></iframe>'
                , id: layId //实际使用一般是规定好的id，这里以时间戳模拟下
            });
            element.tabChange('bodyTab', layId);
            var menu = [];
            if(window.sessionStorage.getItem("menu")){
                menu = JSON.parse(window.sessionStorage.getItem("menu"));
            }

            //当前窗口内容
            var curmenu = {
                "title" :title,
                "href" : url,
                "layId" : layId
            };
            menu.push(curmenu);
            window.sessionStorage.setItem("menu",JSON.stringify(menu)); //打开的窗口
        }
        window.sessionStorage.setItem("curmenu",layId);  //当前的窗口
        return false;
	});

	// 一级菜单class切换
    $('.my-tab .tab-item').on('click', function(){
        $(this).addClass('active').siblings().removeClass('active');
    });

    //刷新后还原打开的窗口
    if(window.sessionStorage.getItem("menu") !== null){
        var menu = JSON.parse(window.sessionStorage.getItem("menu"));
        var curmenu = window.sessionStorage.getItem("curmenu");

        for(var i=0;i<menu.length;i++){
            element.tabAdd('bodyTab', {
                title: menu[i].title //用于演示
                , content: '<iframe class="'+menu[i].layId+'" src="' + menu[i].href + '"></iframe>'
                , id: menu[i].layId //实际使用一般是规定好的id，这里以时间戳模拟下
            });
            //定位到刷新前的窗口
            if(curmenu !== "undefined"){
                if(curmenu === '' || curmenu === "null"){  //定位到后台首页
                    element.tabChange("bodyTab",'');
                }else if(curmenu === menu[i].layId){  //定位到刷新前的页面
                    element.tabChange("bodyTab",menu[i].layId);
                }
            }else{
                element.tabChange("bodyTab",menu[menu.length-1].layId);
            }
        }
    }


    // 侧边收缩展开
    $('.SorH').on('click', function(){
        $('.layui-side').toggleClass('hide-side');
        $('.layui-body').toggleClass('hide-side');
        $('.SorH').toggleClass('hide-side');
    })


});