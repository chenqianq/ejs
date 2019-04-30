/*
* @Author: Marte
* @Date:   2018-12-04 13:17:09
* @Last Modified by:   Marte
* @Last Modified time: 2018-12-04 16:58:59
*/
$(function(){

    var width = (window.innerWidth > 0)? window.innerWidth : screen.width;
    var font = width/375 * 50;
    document.documentElement.style.fontSize = font + "px";

    /** --------------------滚动中奖名单部分-------------------- */

    var outerW = $(".nameList .content").height();
    var listW = $(".nameList .content .list").height() - 20;
    var itemW = $(".nameList .content .list .item").outerHeight(true);

    if(  listW  > outerW ) {

        function scrollT() {
            $(".nameList .content .list").animate(
                {"margin-top": -itemW},1000, function () {
                    $(".nameList .content .list  .item:eq(0)").appendTo($(".nameList .content .list"))
                    $(".nameList .content .list").css({"margin-top": 0})
                }
            )
        }

        setInterval(scrollT, 2525)
    }

    /** --------------------抽奖动画部分-------------------- */

    // jQuery.easing.jswing = jQuery.easing.swing;

    jQuery.extend(jQuery.easing, {

        // def: "easeOutQuad",
        // swing: function(e, f, a, h, g) {
        //     return jQuery.easing[jQuery.easing.def](e, f, a, h, g)
        // },

        // 使用的贝塞尔
        easeInOutCirc: function(e, f, a, h, g) {
            if ((f /= g / 2) < 1) {
                return - h / 2 * (Math.sqrt(1 - f * f) - 1) + a
            }
            return h / 2 * (Math.sqrt(1 - (f -= 2) * f) + 1) + a
        }


    });

    var isBegin = false;

    $.fn.extend({
        slots: function(b, a, d) {

            // b为外层遮罩盒子的高度
            // a为结果
            // d为回调

            if ( isBegin ) {
                return false
            }
            isBegin = true;

            // c为传入的结果a的数组
            var c = (a + "").split("");

            // this为调用slots的元素
            $(this).css("backgroundPositionY", 0);

            // 遍历传入元素
            $(this).each(function(e) {

                // 为每个元素创建定时器
                var f = $(this);

                // c[e]等于数组的每一位
                setTimeout(function() {
                    // console.log( e );

                    f.animate({
                        backgroundPositionY: ((b * 60) - (b * c[e])) / 30 + "rem"
                    },

                    {
                        duration: 6000 + e * 3000,
                        easing: "easeInOutCirc",
                        complete: function() {
                            if (e == 2) {
                                isBegin = false;
                                d()
                            }
                        }
                    })

                },e * 300)

            })
        }
    });


    /** --------------------页面逻辑js-------------------- */

    function normalTip(msg,time) {
        time = time ? time :1000;
        msg = msg ? msg : '网络错误,请稍后再试';
        $('.popup').show(); // 遮罩
        $('.msgPop1').html(msg).show();  // 弹窗
        setTimeout(function() {
            $('.popup').hide(); // 二层遮罩显示
            $('.msgPop1').hide();  // 弹窗显示
        }, time);
        lock = false;
    }

    function normalTip2(msg,time) {
        time = time ? time :1000;
        msg = msg ? msg : '网络错误,请稍后再试';
        $('.popup').show(); // 遮罩
        $('.msgPop2').html(msg).show();  // 弹窗
        setTimeout(function() {
            $('.popup').hide(); // 二层遮罩显示
            $('.msgPop2').hide();  // 弹窗显示
        }, time);
        lock = false;
    }

    var lock = false;

    /**
     * 执行抽奖动作
     */
    $('.start').click(function () {
        if (lock == true) {
            return false;
        }
        lock = true;
        var openid = $('#openid').val();
        if (!openid) {
            normalTip();
            return false;
        }
        $.ajax({
            url:'get_winning_game_result.html',
            type:'post',
            dataType:'json',
            data:{openid:openid},
            success:function(res) {
                if (res.status == 'failed') {
                    normalTip2(res.longMessage);
                    lock = false;
                    return false;
                } else {
                    getRollAnimation(res.winning_number, res); // 执行动画
                    $('#winning_receive_info_form').find('.order_id').val(res.order_id);
                    $('#winning_receive_info_form').find('.winning_name').val(res.winning_info.winning_name);
                }
            },
            error:function(err) {
                // 由于系统原因,抽奖失败啦,不会扣除您的抽奖权益
                normalTip();
            }
        });
        return false;

    });

    /**
     * 点击执行动画
     * @param random
     * @param result
     * @returns {boolean}
     */
    function getRollAnimation(random, result) {
        if (!random) {
            return false;
        }
        $('.catClaw').addClass('run');
        $('.start').addClass('run');

        var u = 45.9; //盒子的高度

        // 摇摇乐动画结束调用
        $(".mian .picList li").slots(u,random,function () {
            // 修改猫爪git显示
            $('.catClaw').removeClass('run');
            $('.start').removeClass('run');

            var status = result.status;
            var winningInfo = result.winning_info;
            var winningRank = winningInfo.rank;
            $('.popup').show(); // 遮罩显示
            if (status == 'needReceiveInfo') {
                $('.popup').find('.popup_hide_prevent').val(1);
                $('.addAddressPop').find('.winning_rank').html(winningInfo.name);
                $('.addAddressPop').find('.winning_name').html(winningInfo.winning_name);
                $('.addAddressPop').show();  // 弹窗显示
            } else if (status == 'success') {
                if (winningRank == 'other') {
                    $('.msgPop2').html(winningInfo.rule_tip).show();  // 弹窗显示

                } else {
                    $('.winningPop').find('.winning_rank').html(winningInfo.name);
                    $('.winningPop').find('.winning_name').html(winningInfo.winning_name);
                    $('.winningPop').show();  // 弹窗显示
                }
            } else {
                $('.msgPop1').html(result.longMessage).show();  // 弹窗显示
            }

            lock = false;

        });
    }

    /**
     * 对于第一层弹窗点击
     */
    $('.popup,.close').click(function() {
        // 如果存在阻止
        if ($('.popup').find('.popup_hide_prevent').val() == 1) {
            // console.log('阻止关闭');
            return false;
        }
        $('.popup').hide(); // 遮罩
        $('.popupCom').hide();  // 弹窗

        var timeObject = new Date;
        var time = timeObject.getTime();

        location.replace(location.href + '&time=' + time);
        // location.href = location.href + '&time=' + time;
    });

    /**
     * 对于第二层弹窗点击
     */
    $('.popupBGTop').click(function() {
        $('.popupBGTop').hide(); // 遮罩
        $('.msgPop1').hide();  // 弹窗
        return false;
    });

    /**
     * 领奖地址提交
     */
    $('#winning_receive_info_form_submit').click(function() {
        var openid = $('#openid').val();
        var winning_name = $('#winning_receive_info_form').find('.winning_name').val();
        var data = $('#winning_receive_info_form').serialize() + '&openid=' + openid;
        $.ajax({
            url:'update_user_winning_receive_info.html',
            type:'post',
            dataType:'json',
            data:data,
            success:function(res) {
                if(res.status == 'success') {
                    $('.popupCom').hide();
                    $('.popup').find('.popup_hide_prevent').val(0);
                    $('.addressConfirmPop').find('.winning_name').html(winning_name);
                    $('.addressConfirmPop').show();  // 弹窗显示
                } else {
                    $('.popupBGTop').show(); // 二层遮罩显示
                    $('.msgPop1').html(res.longMessage).show();  // 弹窗显示
                    setTimeout(function() {
                        $('.popupBGTop').hide(); // 二层遮罩显示
                        $('.msgPop1').hide();  // 弹窗显示
                    }, 1000);
                }

            },
            error:function(err) {
                $('.popupBGTop').show(); // 二层遮罩显示
                $('.msgPop1').html('网络错误,请稍后重试').show();  // 弹窗显示
            }
        });
        return false;
    });

    $('.user_winning_list').click(function() {
        if (lock == true) {
            return false;
        }
        var openid = $('#openid').val();
        location.href = 'user_winning_list.html?openid=' + openid;
        return false;
    })

    /**
     * 阻止内部点击冒泡
     */
    $('.popupCom').click(function() {
        console.log(1);
        return false;
    });


});
