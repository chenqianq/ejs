/*
* @Author: Marte
* @Date:   2018-12-17 09:23:43
* @Last Modified by:   Marte
* @Last Modified time: 2018-12-18 09:19:42
*/

$(function(){

    var len = $(".searchInput input").val().length;
    if( len > 0 ){
        $(".clearInput").css("display","block");
    } else {
        $(".clearInput").css("display","");
    }

    var width = (window.innerWidth > 0)? window.innerWidth : screen.width;
    var font = width/375 * 50;
    document.documentElement.style.fontSize = font + "px";


    // 点击备注弹出弹窗
    $(".btnGroup .mark").live("click",function(event) {
        var orgReamrk = $(this).parents('.lastLine').siblings('.remarks').find('.content').html();
        var length = orgReamrk.length;
        var orderId = $(this).siblings('.order_id').val();
        if(parseInt(length)<=0){
            length =0;
        }
        layer.open({
            content: '<div class="markWrap"><textarea class="inputMark" maxlength="300">'+orgReamrk+'</textarea><div class="fontLen"><span>'+length+'</span>/300个字</div></div><div class="markBtn"><input type="button" name="" value="取消" class="cancelMark"><input type="button" name="" attr-order-id="'+orderId+'" value="提交" class="submitMark"></div>'
          ,style: 'width: 5.5rem; background-color:#ffffff; border-radius: .08rem; padding: .34rem .2rem .2rem;' //自定风格
          ,shade: 1
        })
    });

    // 提交备注
    $(".markBtn .submitMark").live("click",function(){
        var text = $('.inputMark').val();
        var order_id = $(this).attr('attr-order-id');

        $.ajax({
            url:'add_remark',
            data:{'text':text,'orderId':order_id},
            dataType:"json",
            type:'post',
            success:function (res) {
                if(res.status == 'success'){
                    var laymsg = res.msg;
                    layer.open({
                        content: laymsg
                        ,skin: 'msg'
                        ,time: 1 //2秒后自动关闭
                        ,success:function () {
                            window.location.href='';
                        }
                    });
                }else{
                    //提示
                    var laymsg = res.msg;
                    layer.open({
                        content: laymsg
                        ,skin: 'msg'
                        ,time: 1 //2秒后自动关闭
                    });
                }
            }
        });


    });

    // 监听输入内容的长度
    $(".inputMark").live("input",function(){
        var len = this.value.length;
        $(".fontLen span").html( len );
    })


    // 关闭当前备注弹窗
    $(".markBtn .cancelMark").live("click",function(){
        layer.closeAll();
    });


    // 点击收货地址弹出弹窗
    $(".selectAddr").click(function(){
        var content = $('#address-data-list').html();
        layer.open({
            content: content
          ,style: 'width: 5rem; height: 6.4rem; background-color: #ffffff; border-radius: .08rem; padding: .6rem .2rem;' //自定风格
          ,shade: 1
        })
    })


    // 点击地址时关闭弹窗
    $(".addrList .item").live("click",function(){
        layer.closeAll();
        var value = $(this).attr('data-value');
        $(".activeAddr").html( $(this).text() );
        $(".activeAddr").attr('data-value',value);
    });


    //清除输入内容
    $(".searchInput input").live("input",function(){
        var len = this.value.length;
        if( len > 0 ){
            $(".clearInput").css("display","block");
        } else {
            $(".clearInput").css("display","");
        }
    })


    // 清除搜索输入框内容
    $(".clearInput").click(function(){
        $(".searchInput input").val("");
        $(".clearInput").css("display","");
    })


    //点击订单状态筛选
    $(".statusSelect input").click(function(){
        var newstatus = $(this).attr('org-value');
        var oldUrl = window.location.href;
        var search = window.location.search;

        var newUrl = changeURLArg(oldUrl,'status',newstatus);

        window.location.href = newUrl;

    });

    //url：地址；arg:要变更的参数名；arg_val:要变更的参数值
    function changeURLArg(url,arg,arg_val){
        var pattern=arg+'=([^&]*)';
        var replaceText=arg+'='+arg_val;
        if(url.match(pattern)){
            var tmp='/('+ arg+'=)([^&]*)/gi';
            tmp=url.replace(eval(tmp),replaceText);
            return tmp;
        }else{
            if(url.match('[\?]')){
                return url+'&'+replaceText;
            }else{
                return url+'?'+replaceText;
            }
        }
        return url+'\n'+arg+'\n'+arg_val;
    }



    // 加载更多
    var onOff = true;
    var page = 1;
    $(window).scroll(function(event){
        var bodyH = $("body").outerHeight() * 0.8;

        if( onOff && $(window).scrollTop() > bodyH ){
            page++;

            var oldUrl = window.location.href;

            $.ajax({
                type:'post',
                url: oldUrl,
                dateType:'json',
                cache:false,
                data:{'page':page},
                success:function(result){
                    //console.log('请求数据啦');
                    if( result.status == 'success' ) {

                        html = result.html;
                        if ( html != '' ) {
                            onOff = true;
                            $('.orderList').append(html);
                        }
                    }
                }

            });
            onOff = false;
        }
    });


    // 点击搜索按钮
    $(".searchIcon").live('click',function () {

        var addressValue =$.trim($('.activeAddr').attr('data-value'));
        var addressName =  $.trim($('.activeAddr').html());
        var searchType = $.trim($('.selectVis').attr('data-value'));
        var searchTypeName =  $.trim($('.selectVis').html());
        var searchValue = $.trim($('input[name=search-value]').val());
        var status = $.trim($('.statusSelect').find('.active').attr('org-value'));

        window.location.href = "mobile_order_list?addressId="+addressValue+"&searchType="+searchType+"&searchValue="+searchValue+"&addressName="+addressName+"&searchTypeName="+searchTypeName+"&status="+status;
    });

    //点击搜索类型的js
    $('.get-search-type').live('change',function(data){
        var type_value = $(this).val();
        var type_name = $(this).find('option:selected').text();
        $('.selectVis').html(type_name);
        $('.selectVis').attr('data-value',type_value);
        $('.selectVis').addClass("selectClick");
    });
    //成功送达
    $('#delivery-success').live('click',function () {
        var orderId = $(this).siblings('.order_id').val();
        //return false;
        //询问框
        layer.open({
            content: '<div style="padding: 50px 30px;">确定已经成功送达了吗？</div>'
            ,btn: ['确定', '取消']
            ,yes: function(index){
                layer.close(index);
                $.ajax({
                    url:'success_delivery',
                    data:{'order_id':orderId},
                    dataType:'json',
                    type:'get',
                    success:function (res) {
                        if(res.status == 'success'){
                            var laymsg = res.msg;
                            layer.open({
                                content: laymsg
                                ,skin: 'msg'
                                ,time: 1 //2秒后自动关闭
                                ,success:function () {
                                    window.location.href='';
                                }
                            });
                        }else{
                            layer.open({
                                content: laymsg
                                ,skin: 'msg'
                                ,time: 1 //2秒后自动关闭
                            });
                        }
                    }
                })


            }
        });
    });
    //取消订单
    $('.cancel').live('click',function () {
        var orderSn = $(this).parents('.orderItem').find('.orderNumber').html();
        /*console.log(orderSn);
        return false;*/
        //询问框
        layer.open({
            content: '<div style="padding: 50px 30px;">确定取消该笔订单吗？</div>'
            ,btn: ['确定', '取消']
            ,yes: function(index){
                layer.close(index);

                $.ajax({
                    url:'cancel_order_by_admin',
                    data:{'order_sn':orderSn},
                    dataType:'json',
                    type:'get',
                    success:function (res) {
                        if(res.status == 'success'){
                            var laymsg = res.msg;
                            layer.open({
                                content: laymsg
                                ,skin: 'msg'
                                ,time: 1 //2秒后自动关闭
                                ,success:function () {
                                    window.location.href='';
                                }
                            });
                        }else{
                            layer.open({
                                content: laymsg
                                ,skin: 'msg'
                                ,time: 1 //2秒后自动关闭
                            });
                        }
                    }
                })


            }
        });
    })




})