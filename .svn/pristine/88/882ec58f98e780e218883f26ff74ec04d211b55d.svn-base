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
    <div class="myPrize">
        <?php if (!$winningList) { ?>
        <div class="noPrizes">
            抱歉，暂无奖品
        </div>
        <?php } else { ?>
        <div class="withPrizes">

            <div class="userInfo">
                <p class="t1">
                    <span class="uName"><?php echo $userInfo['winning_receive_name']; ?></span>
                    <span class="uPhone"><?php echo $userInfo['winning_receive_mobile']; ?></span>
                </p>
                <p class="addr"><?php echo $userInfo['winning_receive_address']; ?></p>
            </div>

            <div class="itemList">
                <?php foreach ($winningList as $winningInfo) { ?>
                <div class="item">
                    <p class="t2">
                        <span class="time"><?php echo $winningInfo['gmt_get_number']; ?></span>
                        <span class="number"><?php echo $winningInfo['order_sn']; ?></span>
                    </p>
                    <p class="t3">
                        <span class="goodsName"><?php echo $winningInfo['winning_name']; ?></span>
                        <span class="status"><?php echo $winningInfo['winning_receive_state_name']; ?></span>
                    </p>
                </div>
                <?php } ?>
            </div>
        </div>
        <?php } ?>
    </div>

</body>
</html>