<?php 

/**
 * 输出包邮包税标签类
 */

class Label {

    public function __construct()
    {

    }

	/**
	 * 输出包邮包税的样式 hyq
	 * @param array $labelArray 标签数组
	 * @param string $route     路由
	 * @return string 
	 */
	public function getLabel($labelArray,$route = '') {
		if (empty($labelArray) || !is_array($labelArray)) {
			return '';
		}

		
		$return = '';

		foreach ($labelArray as $label) {
			$return .= $this -> getLabelString($label,$route);
		}


		return $return;

	}

	/**
	 * 根据路由返回不同的标签样式
	 * @param string $labelName 标签名称
	 * @param string $route     路由
	 * @return string
	 */
	private function getLabelString($labelName,$route = '') {

		if (empty($labelName)) {
			return '';
		}

		$return = '';

		switch ($route) {

			case YfjRouteConst::index:
				$return = '<span class="tag-style">' . $labelName . '</span>';
				break;
			case YfjRouteConst::cart:
			    if(G_CURRENT_DOAMIN_CONST_NAME== 'G_M_YIFANJIE_COM_DOMAIN'){
                    $return = '<span class="act">' . $labelName . '</span>';
                }else {
                    $return = '<div class="tag-style">' . $labelName . '</div>';
                }
				break;

			case YfjRouteConst::productInfo:
			default:
				$return = '<span class="f3">' . $labelName . '</span>';
				break;
		}

		return $return;

	}

    /**
     * 根据路由返回不同的满减标签
     * @param $currentFullReduce 当前满减
     * @param $nextFullReduce    下一级满减
     * @param $totalPrice        总价
     * @param $groupId           活动id
     * @param string $route      路由
     */
	public function getFullReduceLabel($currentFullReduce,$nextFullReduce,$totalPrice,$groupId,$route = G_CURRENT_DOAMIN_CONST_NAME)
    {
        $return = '';
        //$currentUrl = HelperFactory::getUrlHelper() -> getValue('route');
        switch ($route) {
            case  'G_WWW_YIFANJIE_COM_DOMAIN':
                if ($nextFullReduce) {
                    $agio = $nextFullReduce['full_price'] - $totalPrice;

                    $return .= "<span class=\"COR9 FS14\">&nbsp;再购 " . $agio . "元享【满" . (int)$nextFullReduce['full_price'] . "减" . (int)$nextFullReduce['reduce_price'] . "】</span>";
                    $return .= "<a href=" . Zc::url(YfjRouteConst::group, ['group_id' => $groupId]) . " class=\"FS12 COR1\">去看看&gt;</a>&nbsp;&nbsp;&nbsp;&nbsp;";
                }
                if ($currentFullReduce) {

                    $return .= "<span class=\"COR9 FS14\">&nbsp;已满足【满" . (int)$currentFullReduce['full_price'] . "减" . (int)$currentFullReduce['reduce_price'] . "】</span>";
                };
                break;
            case  'G_M_YIFANJIE_COM_DOMAIN':
                if ($nextFullReduce) {
                    $agio = $nextFullReduce['full_price'] - $totalPrice;

                    $return .= "<a href=" . Zc::url(YfjRouteConst::group, ['group_id' => $groupId]) . " class=\"act-bar\">";
                    $return .= "<span class=\"box1\">活动</span>";
                    $return .= "还差<span>" . $agio . "</span>元享【满" . (int)$nextFullReduce['full_price'] . "减" . (int)$nextFullReduce['reduce_price'] . "】";
                    $return .= "<span class=\"FR\">去凑单<i class=\"icon icon-xiangyoujiantou\"></i></span>";
                    $return .= "</a>";
                }
                if ($currentFullReduce && !$nextFullReduce) {

                    $return .= " <a href=" . Zc::url(YfjRouteConst::group, ['group_id' => $groupId]) . " class=\"act-bar\">";
                    $return .= "<span class=\"box1\">满减专场</span>";
                    $return .= "<span>已满足【满" . (int)$currentFullReduce['full_price'] . "元减" . (int)$currentFullReduce['reduce_price'] . "元】</span></a>";
                };
                break;
            default :    //app返回购物车活动标签
                if ($nextFullReduce) {
                    $agio = $nextFullReduce['full_price'] - $totalPrice;

                    $return .= "再购" . $agio . "元享【满" . (int)$nextFullReduce['full_price'] . "减" . (int)$nextFullReduce['reduce_price'] . "】";
                }
                if ($currentFullReduce && !$nextFullReduce) {

                    $return .= "已满足【满" . (int)$currentFullReduce['full_price'] . "元减" . (int)$currentFullReduce['reduce_price'] . "元】";
                };
                break;
        }
        return $return;
    }

    /**
     * 返回活动信息(写入订单)
     * @param $currentFullReduce 当前满减
     * @param $nextFullReduce    下一级满减
     * @return string
     */
    public function getPromotionInfo($currentFullReduce,$nextFullReduce) {
        
        $return = '';

        if ($currentFullReduce) {
            $return = "满" . (int)$currentFullReduce['full_price'] . "元减" . (int)$currentFullReduce['reduce_price'] . "元";
        }

        return $return;
    }

    /**
     * 获取商品详情页库存标签
     * @param $storage            商品库存
     * @param int $leftLimit      参与活动的商品限制数量
     * @param int $havingBuy      参与活动的商品已经销售出的数量
     * @param int $highLimit      参与活动的商品限制购买最高件数
     * @param mixed|string $route 路由
     */
    public function getProductsDetailStorageLabel($storage,$leftLimit =null,$havingBuy=0,$highLimit=0,$route = G_CURRENT_DOAMIN_CONST_NAME)
    {
        if ($storage != (int)$storage) {
            return '';
        }

        if ($storage > 0) {


            $label = '库存充足';
            if ($storage <= 10) {

                $label = '仅剩' . $storage . '件';
            }

            $surplusNum = -1;
            $isHighLimit = (int)$highLimit;
            //判断是否有设置参与活动的商品数量
            if ($leftLimit) {

                $label = '限量' . $leftLimit . '件';

                //判断是否有设置活动放出的商品数量，以及限购数量未设置时输出
                $surplusNum = ((int)$leftLimit - (int)$havingBuy);

            } else {
                if ($isHighLimit) {
                    $label = '限购' . $highLimit . '件';
                    $isHighLimit = 0;
                    $surplusNum = $storage;
                }
            }

            //判断活动放出的商品数量是否大于单次限制购买的最多数量
            if ($isHighLimit) {

                if ($route == 'G_WWW_YIFANJIE_COM_DOMAIN') {
                    $label .= ',限购' . $highLimit . '件';
                }
            }

            if ($surplusNum > 0 && $storage <= 10 ) {
                $label .= ',仅剩' . $storage . '件';
            }
            if($leftLimit && $storage >10){
                if($highLimit){
                    $label .= '限购' . $highLimit . '件';
                }
            }
            //判断是否有设置活动放出的商品数量，以及放出的商品数量是否已经售光
//            if ($highLimit == 0 && $surplusNum == 0) {
//
//                if ($route == 'G_WWW_YIFANJIE_COM_DOMAIN') {
//                    $label .= ',已售光';
//                }
//            }

        } else {

            $label = '库存不足';
            //如果设置了参与活动的商品限制数量，则提示为已售光
            if ($leftLimit) {
//                $label = '限量' . (int)$leftLimit . "件" . ",已售光";
            }

        }

        return $label;
    }

    /**
     * 获取app,m,pc(c端,b端)的限购弹窗提示
     * @param  int    $highLimit 商品限制购买件数
     * @param  bool   $promotion 是否活动限购
     * @return string 
     */
    public function getLimitMessage($highLimit=0, $promotion=false) {
        
        if ( !$highLimit ) {
            return '';
        }

        switch ( G_CURRENT_DOAMIN_CONST_NAME ) {
            case 'G_IOS_YIFANJIE_COM_DOMAIN':
            case 'G_ANDROID_YIFANJIE_COM_DOMAIN':
                if ( $promotion ) {
                    return '单次限购' . $highLimit . '件';
                } else {
                    return '单笔交易相同商品不得超过'.$highLimit.'件';
                }
                break;
                
            case 'G_WWW_YIFANJIE_COM_DOMAIN':

                if ( $promotion ) {
                   return "<p class='COR1'>限购<em class=\"FFSHUZI\">" . $highLimit . "</em>件</p>";
                } else {
                    return '单笔交易相同商品不得超过'.$highLimit.'件';
                }

                break;

            case 'G_M_YIFANJIE_COM_DOMAIN':

                if ( $promotion ) {
                    return "<span class=\"limit\">限购".$highLimit."件</span>";
                } else {
                    return '单笔交易相同商品不得超过'.$highLimit.'件';
                }

                break;
        }
    }


    /**
     * 获取购物车库存标签
     * @param $storage            商品库存
     * @param int $leftLimit      参与活动的商品限制数量
     * @param int $havingBuy      参与活动的商品已经销售出的数量
     * @param int $highLimit      参与活动的商品限制购买最高件数
     * @param mixed|string $route 路由
     */
    public function getShopCartStorageLabel($storage,$leftLimit =null,$havingBuy=0,$highLimit=0,$route = G_CURRENT_DOAMIN_CONST_NAME)
    {
        if ($storage != (int)$storage) {
            return '';
        }

        if ($storage > 0) {

            $label = '库存充足';

            if ($storage > 0 && $storage <= 99) {

                $label = '库存' . $storage . '件';
            }

            $surplusNum = 0;
            $isHighLimit = (int)$highLimit;
            //判断是否有设置参与活动的商品数量
            if ($leftLimit) {

                $label = '限量' . $leftLimit . '件';

                //判断是否有设置活动放出的商品数量，以及限购数量未设置时输出
                $surplusNum = ((int)$leftLimit - (int)$havingBuy);

                //设置是否限购
                $isHighLimit = (int)$leftLimit - (int)$highLimit;
            }

            //判断活动放出的商品数量是否大于单次限制购买的最多数量
            if ($isHighLimit) {

                if ($route == 'G_WWW_YIFANJIE_COM_DOMAIN') {
                    $label .= ',单次限购' . (int)$highLimit . '件';
                }
            }

            if ($highLimit == 0 && $surplusNum > 0) {

                if ($route == 'G_WWW_YIFANJIE_COM_DOMAIN') {
                    $label .= ',剩余' . $surplusNum . '件';
                }
            }
            //判断是否有设置活动放出的商品数量，以及放出的商品数量是否已经售光
            if ($surplusNum == 0 || ($isHighLimit && ($surplusNum < $highLimit))) {

                if ($route == 'G_WWW_YIFANJIE_COM_DOMAIN') {
                    $label .= ',已售光';
                }
            }

        } else {

            $label = '库存不足';
            //如果设置了参与活动的商品限制数量，则提示为已售光
            if ($leftLimit) {
                $label = '限量' . (int)$leftLimit . "件" . ",已售光";
            }

        }

        return $label;
    }


    /**
     * 获取商品活动标签
     * @param $label                商品活动标签
     * @param mixed|string $route   路由
     * @return string
     */
    public function getProductsActivityLabel($label,$route = G_CURRENT_DOAMIN_CONST_NAME)
    {

        $return = '';

        if (empty($label)) {
            return '';
        }

        switch ($route) {
            case  'G_M_YIFANJIE_COM_DOMAIN':
                $return = "<span style=\"color: #ff7198; \">" . $label . "/</span>";
                break;
            case  'G_WWW_YIFANJIE_COM_DOMAIN':
                $return = "<span class=\"COR1\">" . $label . "/</span>";
                break;
            default :
                $return = $label;;
                break;
        }

        return $return;
    }


    /**
     * 获取活动规则类型和标签
     * @param $currentType
     * @param $currentLabel
     * @param $groupId
     * @param mixed|string $route
     */
    public function getGroupRuleTypeAndLabel($currentType,$currentLabel,$groupId,$route = G_CURRENT_DOAMIN_CONST_NAME)
    {

        $return = '';

        switch ($route) {
            case  'G_WWW_YIFANJIE_COM_DOMAIN':
                $return = $currentLabel;
                break;
            case  'G_M_YIFANJIE_COM_DOMAIN':
                $return .= "<a href=" . Zc::url(YfjRouteConst::group, ['group_id' => $groupId]) . " class=\"act-bar\">";
                $return .= "<span class=\"box1\">".$currentType."</span>";
                $return .= "<span>".$currentLabel."</span></a>";

                // 新样式
                // '<a href="'. Zc::url(YfjRouteConst::group, ['group_id' => $groupId]) .'" class="act-bar">
                //     <span class="box1">'.$currentType.'</span>
                //     还差<span>24.00</span>元享[满200减30元]
                //     <span class="FR">去凑单<i class="icon icon-xiangyoujiantou"></i></span>
                // </a> '


                break;
            default :    //app返回购物车活动标签
                $return = $currentLabel;
                break;
        }
        return '';
        return $return.'ddddd';
    }

    /**
     * 返回仓库包邮状态标签
     * @param flaot $productsTotal     商品总价格
     * @param int   $shippingFreePrice 满足免邮的金额
     * @param bool  $isFreeFreight     是否包邮
     */
    public function getShippingStatus($productsTotal, $shippingFreePrice, $isFreeFreight,$entrepotType) {

        if ( intval($shippingFreePrice) <= 0 ) {
            return '';
        }
        
        if ( $entrepotType == YfjConst::directMail ) {
            $entrepotTypeName = '直邮';
        } else {
            $entrepotTypeName = '保税';
        }
        $shippingStatus = '';
        $buyMore = 0;
        $difference = 0;
        $labelStr1 = '<a class="FL" >已满足'.$entrepotTypeName.'仓免邮</a>';

        if ( $productsTotal < $shippingFreePrice ) {
            // $labelStr2 = '还差' . ($shippingFreePrice*100 - $productsTotal * 100) / 100 . '元享' . $shippingFreePrice . '元包邮,去凑单';
            $difference = ($shippingFreePrice*100 - $productsTotal * 100) / 100;
            $labelStr2 = '<a class="FL" href="'.Zc::url(YfjRouteConst::addOn,['entrepot_type'=>$entrepotType]).'">还差<span>' . $difference . '</span>元享'.$entrepotTypeName.'满' . $shippingFreePrice . '元包邮, <span>去凑单></span></a>';

        }

        switch (G_CURRENT_DOAMIN_CONST_NAME) {
            case 'G_M_YIFANJIE_COM_DOMAIN':
                if ( $isFreeFreight ) {
                    $shippingStatus = $labelStr1;
                } else {
                    $shippingStatus = $labelStr2;
                }
                break;
            case 'G_IOS_YIFANJIE_COM_DOMAIN':
            case 'G_ANDROID_YIFANJIE_COM_DOMAIN':
                if ( $isFreeFreight ) {
                    $shippingStatus = '已满足'.$entrepotTypeName.'仓免邮';
                } else {
                    $shippingStatus = '还差' . ($shippingFreePrice*100 - $productsTotal * 100) / 100 . '元享'.$entrepotTypeName.'满' . $shippingFreePrice . '元包邮, 去凑单';
                    $buyMore = 1;
                }                
                break;
            default:
                if ( $isFreeFreight ) {
                    $shippingStatus = $labelStr1;
                } else {
                    $shippingStatus = $labelStr2;
                }
                break;
        }

        $difference = $difference?:-1;

        return [$shippingStatus,$difference,$buyMore];
    }

    /**
     * 返回订单结算页包邮状态标签
     * @param float $cartFreightTotal 订单运费
     * @return string
     */
    public function getConfirmationLabel($cartFreightTotal) {

        switch (G_CURRENT_DOAMIN_CONST_NAME) {
            case 'G_M_YIFANJIE_COM_DOMAIN':

                return $cartFreightTotal?'<span class="s1">未包邮</span>':'<span class="s1 COR5">已包邮</span>';
                break;
            
            default:
                return $cartFreightTotal?'未包邮':'已包邮';
                break;
        }

    }

    /**
     * 返回商品的直邮标签
     * @param int entrepotType 贸易类型,1保税模式,2直邮模式
     */
    public function getEntrepotTypeLabel($entrepotType) {
        
        if ( $entrepotType != YfjConst::directMail ) {
            return '';
        }

        switch (G_CURRENT_DOAMIN_CONST_NAME) {
            case 'G_M_YIFANJIE_COM_DOMAIN':
                return '<span class="COR5">【直邮】</span>';
                break;
            
            default:
                return '【直邮】';
                break;
        }

    }

    /**
     * 返回包邮包税标签
     * @param int labelType 包税类型为1，包邮类型为2，包邮包税类型为3
     * @return string
     */
    public function getTaxAndPostageLabel($labelType) {

        switch ($labelType) {
            case 1:
                return '<span class="box1">包税</span>';
                break;
            case 2:
                return '<span class="box1">包邮</span>';
                break;
            case 3:
                return '<span class="box1">包税</span> <span class="box1">包邮</span>';
                break;
            default:
                return '';
                break;
        }
    }

}