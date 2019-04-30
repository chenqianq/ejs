<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>抽奖啦</title>
    <?php $timeStamp = "?2018120703"; ?>
    <?php echo HtmlTool::getWxAppStaticFile('prizeDraw.css' . $timeStamp); ?>
    <?php echo HtmlTool::getWxAppStaticFile('Y-common.css' . $timeStamp); ?>
    <?php echo HtmlTool::getWxAppStaticFile('jquery.js' . $timeStamp); ?>
    <?php echo HtmlTool::getWxAppStaticFile('prizeDraw.js' . $timeStamp); ?>
</head>
<body>
<div class="mian">
    <div class="prizeDrawBG">
        <ul class="picList clearfix">
            <li></li>
            <li></li>
            <li></li>
        </ul>
        <div class="catClaw start"></div>
        <p class="tips">点击右侧猫爪，也可以抽奖！</p>
        <div class="btnGrop clearfix">
            <input type="button" name="" value="" class="start"><!-- 点击抽奖 -->
            <input type="button" name="" value="" class="myPrize user_winning_list"><!-- 我的奖品 -->
        </div>
        <p class="prizeCount">您一共有<span><?php echo $notUseWinningOrderNum; ?></span>次机会</p>
        <input type="hidden" id="openid" value="<?php echo $openid; ?>">
    </div>

    <div class="prizeName">
        <div class="title">
            <div class="line"></div>
            <span>中奖用户</span>
        </div>
        <div class="nameList">
            <div class="content">
                <div class="list">
                    <?php foreach ($showWinningOrderList as $winningOrderInfo) {?>
                        <div class="item clearfix">
                            <image src="<?php echo $winningOrderInfo['buyer_wx_avatar_url']; ?>">
                            <p class="userName"><?php echo $winningOrderInfo['buyer_wx_nick_name']; ?></p>
                            <p class="prizeInfo">于<?php echo date('Y-m-d', strtotime($winningOrderInfo['gmt_get_number'])); ?> 抽中 <span><?php echo $winningOrderInfo['winning_name']; ?></span></p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="prizeBulletin">
        <div class="title">
            <div class="line"></div>
            <span>奖品告示</span>
        </div>
        <div class="tableList">
            <table>
                <tbody>
                <tr>
                    <th>等级</th>
                    <th>图案</th>
                    <th>奖品</th>
                </tr>
                <?php foreach ($allWinning as $winningInfo) { ?>
                    <tr>
                        <td><?php echo $winningInfo['name']; ?></td>
                        <?php if ($winningInfo['rank'] == 'forth_rank') { ?>
                            <td class="lastEle">
                                2个<image src="<?php echo HtmlTool::getWxAppStaticFile($winningInfo['image']); ?>"><br>
                                    2个<image src="<?php echo HtmlTool::getWxAppStaticFile($winningInfo['image2']); ?>">
                                        <span>或</span>
                            </td>
                        <?php } else if ($winningInfo['rank'] == 'third_rank') { ?>
                            <td>
                                2个<image src="<?php echo HtmlTool::getWxAppStaticFile($winningInfo['image']); ?>">
                            </td>
                        <?php } else { ?>

                            <td>3个<image src="<?php echo HtmlTool::getWxAppStaticFile($winningInfo['image']); ?>"></td>
                        <?php } ?>
                        <td><?php echo $winningInfo['winning_name_with_price']; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>


    <div class="regular">
        <div class="title">
            <div class="line"></div>
            <span>奖品告示</span>
        </div>
        <div class="list">
            <p>1.在二加三小程序下单实付金额满<?php echo $wxAppWinningOrderPriceLimit; ?>元将获得一次抽奖机会（必须是已确认收货的有效订单，每个订单独立计算，若实付金额为300的单笔订单，也只有一次抽奖机会）。</p>
            <p>2. 抽奖次数可以累计。</p>
            <p>3.若发现用户使用非常规手段（包含但不限于作弊，刷单，攻击服务器等）获得的奖品，二加三将不予派发奖品并保留使用法律武器的权利。</p>
            <p>4.中奖者领奖时需要出示我的奖品页面给二加三工作人员。</p>
            <p>5.抽奖权益获取日期截止到12月25日 23点59分59秒。</p>
            <p>6.抽奖页面开放时间截止到12月28日 23点59分59秒。</p>
            <p>7.二加三可能根据实际情况作出奖品的调整。</p>
            <p>8.本活动与Apple Inc无关。</p>
        </div>
    </div>
</div>

<div class="popup">

    <input type="hidden" name="popup_hide_prevent" class="popup_hide_prevent">

    <div class="popupCom msgPop1" >
    </div>

    <div class="popupCom msgPop2">
    </div>

    <div class="popupCom addAddressPop">
        <img src="<?php echo HtmlTool::getWxAppStaticFile('mp_prize_congra@3x.png' . $timeStamp); ?>" alt="" class="banner" />
        <p class="prize">你中了<span class="winning_rank"></span></p>
        <p class="p1">奖品：<span class="winning_name"></span></p>
        <p class="p2">请完善信息以便于领奖确认，</p>
        <p class="p3">否则奖品全部归程序员</p>
        <form action="" method="get" accept-charset="utf-8" id="winning_receive_info_form">
            <div class="name clearfix">
                <div>姓名:</div>
                <input type="text" name="winning_receive_name" placeholder="请输入姓名">
            </div>
            <div class="phone clearfix">
                <div>手机号:</div>
                <input type="text" name="winning_receive_mobile" placeholder="请输入手机号" maxlength="11">
            </div>
            <input type="hidden" name="order_id" class="order_id">
            <input type="hidden" name="winning_name" class="winning_name">
            <div class="addr clearfix">
                <div>领奖点:</div>
                <select name="b_wx_distribution_address_id" id="">
                    <option value="">未选择</option>
                    <?php foreach ($allDistributionAddress as $addressInfo) { ?>
                        <option value="<?php echo $addressInfo['b_wx_distribution_address_id']; ?>"><?php echo $addressInfo['shipping_address']; ?></option>
                    <?php } ?>
                </select>
                <img src="<?php echo HtmlTool::getWxAppStaticFile('mp_chose_class@3x.png' . $timeStamp); ?>" alt="" class="downIcon"/>
            </div>

            <p class="p4">提交成功后将成功界面截图作为兑奖凭证</p>
            <input type="submit" name="" value="提交" id="winning_receive_info_form_submit">
        </form>
    </div>

    <div class="popupCom addressConfirmPop">
        <p class="prize">领奖信息提交成功！</p>
        <p class="p1">奖品：<span class="winning_name"></span></p>

        <p class="step">领奖步骤：</p>
        <p class="step">1.将本弹窗截图保存</p>
        <p class="step last">2.把截图发朋友圈作为兑奖凭证</p>

        <p class="p5"> 关注二加三公众号，</p>
        <p class="p5">回复“双十二”，参与抽奖！</p>

        <img src="<?php echo HtmlTool::getWxAppStaticFile('mp_qrcode@3x.png' . $timeStamp); ?>" alt="">
        <div class="close"></div>
    </div>

    <div class="popupCom winningPop">
        <img src="<?php echo HtmlTool::getWxAppStaticFile('mp_prize_congra@3x.png' . $timeStamp); ?>" alt="" class="banner" />
        <p class="prize">你中了<span class="winning_rank"></span></p>
        <p class="p1">奖品：<span class="winning_name"></span></p>

        <p class="step">领奖步骤：</p>
        <p class="step">1.将本弹窗截图保存</p>
        <p class="step last">2.把截图发朋友圈作为兑奖凭证</p>

        <p class="p5"> 关注二加三公众号，</p>
        <p class="p5">回复“双十二”，参与抽奖！</p>

        <img src="<?php echo HtmlTool::getWxAppStaticFile('mp_qrcode@3x.png' . $timeStamp); ?>" alt="">
        <div class="close"></div>
    </div>

    <div class="popupBG"></div>
    <div class="popupBGTop"></div>
</div>
</body>
</html>