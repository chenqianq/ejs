<?php
/**
 * 
 * 封装对于Cache Key的生成规则。所有缓存的Key，都必须通过CacheKeyBuilder提供的方法类构造
 * 生成的Key前两位必须是字母或数字。便于生成缓存的分级目录。
 * 
 * @author kinsly
 *
 */
if( !defined('IS_ACCESS') ){
    die('Illegal Access');
    exit;
}

class CacheKeyBuilder {
	
	/**
	 * 智能发货系统的仓库
	 * @return string
	 */
	public static function buildSdWarehouseKey(){
		return 'sd-warehouse-array-sd7';
	}
	
	
	public static function buildTplSearchHeadKey( $site,  $timestamp='20121211' ) {
		return "TplSearchHeadKey_{$site}_{$timestamp}";
	}
	
	public static function bulidHVcoucherCountKey($memberId,$timestamp = '20161102'){
	    return 'vcoucher-count-member-id-' . $memberId . '-'.$timestamp;
	}
	 
	public static function bulidFVoucherCountFiled($timestamp = '20161102'){
	    return 'm-voucher-' . $timestamp;
	}
	
	public static function bulidCategoriesTree($categoriesId , $timestamp = '20161102'){
	    return 'categories-tree-' . $categoriesId .'-' . $timestamp;
	}

	public static function bulidAllCategories($timestamp = '20161102'){
		return 'categories-all-' . $timestamp;
	}
	
    public static function bulidArticle($categoriesId , $timestamp = '20161102'){
	    return 'categories-tree-' . $categoriesId .'-' . $timestamp;
	}
	
    public static function bulidCategories( $timestamp = '20161102'){
	    return 'categories-tree-' . $timestamp;
	}

	public static function bulidGoodsEvaluate($goodsCommonId, $timestamp = '20161102'){
		return 'goods-evaluate-goods-common-id-'. $goodsCommonId. '-' . $timestamp;
	}
  //物流key
	public static function bulidLogisticsEvaluate($shipping_code, $timestamp = '20161102'){
		return 'order_-hipping-code-'. $shipping_code. '-' . $timestamp;
	}
	
	public static function bulidBrandTree($brandId , $timestamp = '20161102'){
	    return 'brand-tree-' . $brandId .'-' . $timestamp;
	}
	
	public static function bulidBrand( $timestamp = '20161102'){
	    return 'brand-tree-' . $timestamp;
	}

	//管理员key
	public static function buildAdminSessionId($adminId,$timestamp = '20161102'){
		return 'brand-admin-session-id-' . $adminId . '-' . $timestamp;
	}

    //敏感词
    public static function buildBadWord($timestamp = '20170616'){
        return 'review-bad-word-' . $timestamp;
    }

    public static function bulidArea($timestamp = '20170704'){
        return 'area-' .  $timestamp;
    }

    /**
     * b端用户属性
     * @param string $timestamp
     * @return string
     */
    public static function bulidBUserAttribute($timestamp = '20170704'){
        return 'b-user-attribute-' .  $timestamp;
    }

    /**
     * 兑换码
     * @param $code
     * @param string $timestamp
     * @return string
     */
    public static function bulidVoucherCode($code,$timestamp = '20170928'){
        return 'voucher-code-'.$code ."-".$timestamp;
    }

    /**
     * @param $categoriesId
     * @param string $timestamp
     * @return string
     */
    public static function bulidChildCategoriesTree($categoriesId , $timestamp = '20170921')
    {
        return 'categories-child-tree-' . $categoriesId . '-' . $timestamp;
    }

    /**
     * 构建c端订单支付状态          "processing","success","failed"
     * @param $paySn             //支付单号 传给宝付的单号
     * @param string $timestamp
     * @return string
     */
    public static function buildOrderPayStatus($paySn,$timestamp = '20170928')
    {
        return 'order-pay-status-' . $paySn . "-" . $timestamp;
    }

    /**
     * 构建b端订单支付状态           "processing","success","failed"
     * @param $paySn             //支付单号 传给宝付的单号
     * @param string $timestamp
     * @return string
     */
    public static function buildBusinessOrderPayStatus($paySn,$timestamp = '20170928')
    {
        return 'order-pay-status-' . $paySn . "-" . $timestamp;
    }


    /**
     * 构建c端订单支付状态          "processing","success","failed"
     * @param $mainOrderId
     * @param $payment
     * @param string $timestamp
     * @return string
     */
    public static function buildMainOrderPayment($mainOrderId,$payment,$timestamp = '20170928')
    {
        return 'order-pay-status-' . $mainOrderId . "-".$payment . $timestamp;
    }


    /**
     *  获取抢购最近日期
     * @param string $type    recently_date 最近开始的抢购日期 recently_end_time 最近开始抢购日期的结束时间
     * @param string $timestamp
     * @return string
     */
    public static function buildSeckillRecentlyTime($type="recently_date",$timestamp = '20180117')
    {
        return 'seckill-recently-time-' . $type . $timestamp;
    }

    /**
     * 获取抢购列表
     * @param $beginTime
     * @param $endTime
     * @param string $timestamp
     * @return string
     */
    public static function buildSeckillListInfo($beginTime,$endTime,$currentSite,$timestamp = '20180117')
    {
        return 'seckill-info-' . $beginTime . "-" . $endTime ."-".$currentSite. "-" . $timestamp;
    }

    /**
     * 获取抢购商品信息
     * @param $groupId
     * @param string $timestamp
     * @return string
     */
    public static function buildGroupProductListInfo($groupId,$timestamp = '20180117')
    {
        return 'seckill-info-' . $groupId ."-" . $timestamp;
    }

    public static function buildXianshiListByType($types,$displayNum,$timestamp = '20180119')
    {
        return 'Xianshi-list-type-' . $types . "*" . $displayNum."-" . $timestamp;
    }

    /**
     * 活动显示站点信息(活动：专题等)
     * @param string $activityType  活动类型
     * @param int    $activityId    活动id
     * @return string
     */
    public static function buildActivityDisplaySiteKey($activityType, $activityId) {
        return 'activity-display-site-' . $activityType . '-' . $activityId;
    }
    
    /*
     * 活动模块列表
     * @param string $activityType  活动类型
     * @param int    $activityId    活动id
     * @return string
     */
    public static function buildActivityModularListKey($activityType, $specialSubjectId) {
        return 'activity-modular-list-' . $activityType . '-' . $activityId;
    }

    /*
     * 图片模块key
     * @param int    $activityId  活动id
     * @return string
     */
    public static function buildModularImageListKey($modularId) {
        return 'activity-modular-image-list-' . $modularId;
    } 

    /*
     * 多行文本模块key
     * @param int    $activityId  活动id
     * @return string
     */
    public static function buildModularTextKey($modularId) {
        return 'activity-modular-text-' . $modularId;
    } 

    /*
     * 已经绑定的锚点列表key
     * @param int    $activityId  活动id
     * @return string
     */
    public static function buildBindAnchorArrayKey($modularId) {
        return 'activity-bind-anchor-array-' . $modularId;
    }

    /*
     * 已经绑定的锚点列表key
     * @param int    $activityId  活动id
     * @return string
     */
    public static function buildModularListKey($modularId) {
        return 'activity-modular_list-' . $modularId;
    }

    /*
     * 列表模块详情key
     * @param int   $listId 列表id
     * @return string
     */
    public static function buildModularListDetailKey($listId) {
        return 'activity-modular-list-detail-' . $listId;
    }

    /**
     * 老用户不可用优惠券模版id缓存key
     * @return string
     */
    public static function buildOldUserLimitVoucherTemplateIdArrayKey() {
        return 'old_user_limit_voucher_template_id_array';
    }

    /**
     * 日淘礼品兑换码key
     * @param $code
     * @param string $timestamp
     * @return string
     */
    public static function bulidGiftVoucherCodeKey($code,$timestamp = '20170928'){
        return 'gift-voucher-code-'.$code ."-".$timestamp;
    }    

    /*
     * 活动模块列表
     * @param string $activityType  活动类型
     * @param int    $activityId    活动id
     * @return string
     */
    public static function buildBeautifulWomanListKey($page, $pageSize) {
        return 'beautiful-woman-list' . $page . '-' . $pageSize;
    }

    /*
     * 活动模块列表
     * @param string $activityType  活动类型
     * @param int    $activityId    活动id
     * @return string
     */
    public static function buildMyPrizeKey($userId) {
        return 'my-prize-' . $userId;
    }

    /*
     * 已发布的专题信息
     * @param string $specialSubjectId  专题id
     * @return string
     */
    public static function buildPublishSpecialSubjectKey($specialSubjectId) {
        return 'publish-special-subject' . $specialSubjectId;
    }

    /*
     * 优惠券模版信息
     * @param string $voucherTemplateId 
     * @return string
     */
    public static function buildVoucherTemplateInfoKey($voucherTemplateId) {
        return 'voucher-template-info-' . $voucherTemplateId;
    }

    /*
     * 领券信息缓存key
     * @param string $voucherReceiveId 
     * @return string
     */
    public static function buildVoucherReceiveInfoKey($randId,$inviterCode) {
        return 'voucher-receiveinfo-' . $randId.'-'.$inviterCode;
    }

    /*
     * 获取优惠券当天领取的数量缓存key
     * @param string $voucherTemplateId  
     * @return string
     */
    public static function buildDayReceiveQtyKey($voucherTemplateId) {
        return 'day-receive-qty-'. date('Y-m-d') . '-' . $voucherTemplateId;
    }

    /*
     * 优惠券通过领券地址已领取的数量缓存key
     * @param string $voucherTemplateId  
     * @return string
     */
    public static function buildVoucherReceiveQtyKey($voucherReceiveId) {
        return 'voucher-receive-qty-' . $voucherReceiveId;
    }

    /*
     * 用户通过领券地址已领取的数量缓存key
     * @param string $voucherTemplateId  
     * @return string
     */
    public static function buildUserReceiveQtyKey($voucherTemplateId, $memberId) {
        return 'user-receive-qty-' . $voucherTemplateId . '-' . $memberId;
    }

    /*
     * 用户通过领券地址当天已领取的数量缓存key
     * @param string $voucherTemplateId  
     * @return string
     */
    public static function buildUserDayReceiveQtyKey($voucherTemplateId, $memberId) {
        return 'user-day-receive-qty-' . date('Y-m-d') . '-' . $voucherTemplateId . '-' . $memberId;
    }

    /*
     * c端订单推送金额脚本上次执行的时间key
     * @param string $cOrderPushAmountLastExecTime
     * @return string
     */
    public static function buildOrderPushAmountLastExecTimeKey() {
        return 'order-push-amount-last-exec-time';
    }

    /*
     * 售后原因列表缓存key
     * @return string
     */
    public static function buildAfterSaleReasonKey() {
        return 'after-sale-reason';
    }
    /*
     * b端商品分类的缓存key
     * @param string $cOrderPushAmountLastExecTime
     * @return string
     */
    public static function buildDrpProductClassTreeKey() {
        return 'drp-porduct-class-tree';
    }

    /**
     *
     * @param $openid
     * @return string
     */
    public static function buildOpenidKey($openid){
        return 'openid-last-get-winning-result-time-' . $openid;
    }
	
	
}
