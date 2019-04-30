<?php
/**
 * 数据表定义
 * @author kinsly
 */
class CodeMessage {
    private $output = array(
        AppConst::ack => AppConst::failed,
        'resultCode' => 100001,
        'longMessage' => '',
        'shortMessage' => '',
        'data' => '',
        'encryptType' => AppConst::md5Encode,
        'requestId' => '',
        'responseTime' => '',
        'requestTime' => '',
        'repsoneContent' => '',
        'url'=>'',
        'msg'=>'',
    );
    
    public function __construct () {
        $this -> dbExtendHelper = HelperFactory::getDbExtendHelper();
        $this -> output['repsoneContent'] = json_encode(array());
    }
    
    private function processData($repsoneContent){
        if( is_array($repsoneContent) ){
            //$this -> processData($repsoneContent)
        }
    }
	
    /**
     * 
     * @param string $code
     * @param string $requestTime
     * @param array $repsoneContent
     */
    public function getCodeMessageByCode($code,$requestTime,$repsoneContent = array())
    {
        $this->output['requestTime'] = $requestTime;
        $clientOs = HelperFactory::getUrlHelper()->getValue('clientOS');
        switch ($code) {
            case AppConst::requestSuccess:
                $this->output [AppConst::ack] = AppConst::success;
                $this->output [AppConst::resultCode] = AppConst::requestSuccess;
                $this->output ['repsoneContent'] = $repsoneContent;
                break;

            case ErrorCodeConst::getGoodsInfoFailed:
                $this->output['resultCode'] = ErrorCodeConst::getGoodsInfoFailed;
                $this->output['longMessage'] = '获取商品信息失败';
                $this->output['shortMessage'] = '获取商品信息失败';
                break;
            case ErrorCodeConst::getUserOpenidFailed:
                $this->output['resultCode'] = ErrorCodeConst::getUserOpenidFailed;
                $this->output['longMessage'] = '获取用户授权信息失败';
                $this->output['shortMessage'] = '获取用户授权信息失败';
                break;
            case ErrorCodeConst::updateUserInfoFailed:
                $this->output['resultCode'] = ErrorCodeConst::updateUserInfoFailed;
                $this->output['longMessage'] = '授权微信信息更新失败';
                $this->output['shortMessage'] = '授权微信信息更新失败';
                break;
            case ErrorCodeConst::getActivityInfoFailed:
                $this->output['resultCode'] = ErrorCodeConst::getActivityInfoFailed;
                $this->output['longMessage'] = '获取限时活动信息失败';
                $this->output['shortMessage'] = '获取限时活动信息失败';
                break;
            case ErrorCodeConst::getSessionOpenidFailed:
                $this->output['resultCode'] = ErrorCodeConst::getSessionOpenidFailed;
                $this->output['longMessage'] = '未读取到用户openid';
                $this->output['shortMessage'] = '未读取到用户openid';
                break;
            case ErrorCodeConst::wrongQtyInput:
                $this->output['resultCode'] = ErrorCodeConst::wrongQtyInput;
                $this->output['longMessage'] = '未正确获取商品购买数量';
                $this->output['shortMessage'] = '未正确获取商品购买数量';
                break;
            case ErrorCodeConst::wrongCartIdInput:
                $this->output['resultCode'] = ErrorCodeConst::wrongCartIdInput;
                $this->output['longMessage'] = '未正确获取购物车id';
                $this->output['shortMessage'] = '未正确获取购物车id';
                break;
            case ErrorCodeConst::updateCartGoodsFailed:
                $this->output['resultCode'] = ErrorCodeConst::updateCartGoodsFailed;
                $this->output['longMessage'] = '更新购物车商品失败';
                $this->output['shortMessage'] = '更新购物车商品失败';
                break;

            case ErrorCodeConst::customizeMsg:
                $this->output['resultCode'] = ErrorCodeConst::MobilePhoneShield;
                $this->output['longMessage'] = $repsoneContent;
                $this->output['shortMessage'] = $repsoneContent;
                break;

            case ErrorCodeConst::bindPhoneUnusable:
                $this->output['resultCode'] = ErrorCodeConst::bindPhoneUnusable;
                $this->output['longMessage'] = '该手机存在绑定微信';
                $this->output['shortMessage'] = '该手机存在绑定微信';
                break;
            case ErrorCodeConst::LoginNameEmpty:
                $this->output[AppConst::resultCode] = ErrorCodeConst::LoginNameEmpty;
                $this->output['longMessage'] = '请填写用户名或是电话号码';
                $this->output['shortMessage'] = '请填写用户名或是电话号码';
                break;
            case ErrorCodeConst::PasswordEmpty:
                $this->output[AppConst::resultCode] = ErrorCodeConst::PasswordEmpty;
                $this->output['longMessage'] = '请填密码';
                $this->output['shortMessage'] = '请填密码';
                break;
            case ErrorCodeConst::Login:
                $this->output[AppConst::resultCode] = ErrorCodeConst::Login;
                $this->output['longMessage'] = '用户名或密码错误';
                $this->output['shortMessage'] = '用户名或密码错误';
                break;
            case ErrorCodeConst::MobilePhoneEmpty:
                $this->output['resultCode'] = ErrorCodeConst::MobilePhoneEmpty;
                $this->output['longMessage'] = '请填写手机号码';
                $this->output['shortMessage'] = '请填写手机号码';;
                break;
            case ErrorCodeConst::MobilePhoneFormat:
                $this->output['resultCode'] = ErrorCodeConst::MobilePhoneFormat;
                $this->output['longMessage'] = '手机号码格式有误';
                $this->output['shortMessage'] = '手机号码格式有误';;
                break;
            case ErrorCodeConst::MobilePasswordEmpty:
                $this->output['resultCode'] = ErrorCodeConst::MobilePasswordEmpty;
                $this->output['longMessage'] = '密码或是确认密码不能为空';
                $this->output['shortMessage'] = '密码或是确认密码不能为空';;
                break;
            case ErrorCodeConst::MobilePasswordLength:
                $this->output['resultCode'] = ErrorCodeConst::MobilePasswordLength;
                $this->output['longMessage'] = '密码或是确认密码最低要为6位';
                $this->output['shortMessage'] = '密码或是确认密码最低要为6位';;
                break;
            case ErrorCodeConst::MobilePasswordNotMatch:
                $this->output['resultCode'] = ErrorCodeConst::MobilePasswordNotMatch;
                $this->output['longMessage'] = '密码与确认密码不一致';
                $this->output['shortMessage'] = '密码与确认密码不一致';;
                break;
            case ErrorCodeConst::MobileExists:
                $this->output['resultCode'] = ErrorCodeConst::MobileExists;
                $this->output['longMessage'] = '该手机号码已经注册';
                $this->output['shortMessage'] = '该手机号码已经注册';;
                break;
            case ErrorCodeConst::MobileAlreadyBind:
                $this->output['resultCode'] = ErrorCodeConst::MobileAlreadyBind;
                $this->output['longMessage'] = '该账号已绑定微信|注册';
                $this->output['shortMessage'] = '该账号已绑定微信|注册';
                break;
            case ErrorCodeConst::PhoneCaptcha:
                $this->output['resultCode'] = ErrorCodeConst::PhoneCaptcha;
                $this->output['longMessage'] = '动态码错误或已过期';
                $this->output['shortMessage'] = '动态码错误或已过期';;
                break;
            case ErrorCodeConst::InviterCode:
                $this->output['resultCode'] = ErrorCodeConst::InviterCode;
                $this->output['longMessage'] = '邀请码无效，是否忽略邀请码?';
                $this->output['shortMessage'] = '邀请码无效，是否忽略邀请码?';
                break;
            case ErrorCodeConst::System:
                $this->output['resultCode'] = ErrorCodeConst::System;
                $this->output['longMessage'] = '系统错误';
                $this->output['shortMessage'] = '系统错误';
                break;
            case ErrorCodeConst::SendMsgType:
                $this->output['resultCode'] = ErrorCodeConst::SendMsgType;
                $this->output['longMessage'] = '错误识别码';
                $this->output['shortMessage'] = '错误识别码';
                break;
            case ErrorCodeConst::SendRepeat:
                $this->output['resultCode'] = ErrorCodeConst::SendRepeat;
                $this->output['longMessage'] = '同一手机号码2分钟内，请勿多次获取动态码！';
                $this->output['shortMessage'] = '同一手机号码2分钟内，请勿多次获取动态码！';
                break;
            case ErrorCodeConst::SendPhoneNotMatch:
                $this->output['resultCode'] = ErrorCodeConst::SendPhoneNotMatch;
                $this->output['longMessage'] = '当前手机号未注册，请检查号码是否正确。';
                $this->output['shortMessage'] = '当前手机号未注册，请检查号码是否正确。';
                break;
            case ErrorCodeConst::SystemPhoneSendFailed:
                $this->output['resultCode'] = ErrorCodeConst::SystemPhoneSendFailed;
                $this->output['longMessage'] = '发送失败';
                $this->output['shortMessage'] = '发送失败';
                break;
            case ErrorCodeConst::registerFailed:
                $this->output['resultCode'] = ErrorCodeConst::registerFailed;
                $this->output['longMessage'] = '注册失败';
                $this->output['shortMessage'] = '注册失败';
                break;
                break;
            case ErrorCodeConst::voucherCodeNotExists:
                $this->output['resultCode'] = ErrorCodeConst::voucherCodeNotExists;
                $this->output['longMessage'] = '不存在该优惠码';
                $this->output['shortMessage'] = '不存在该优惠码';
                break;
            case ErrorCodeConst::voucherCodeFailed:
                $this->output['resultCode'] = ErrorCodeConst::voucherCodeFailed;
                $this->output['longMessage'] = '优惠券已失效';
                $this->output['shortMessage'] = '优惠券已失效';
                break;
                break;
            case ErrorCodeConst::redVoucherCodeNotExists:
                $this->output['resultCode'] = ErrorCodeConst::redVoucherCodeNotExists;
                $this->output['longMessage'] = '不存在该红包';
                $this->output['shortMessage'] = '不存在该红包';
                break;
            case ErrorCodeConst::redVoucherCodeUsedOrOther:
                $this->output['resultCode'] = ErrorCodeConst::redVoucherCodeUsedOrOther;
                $this->output['longMessage'] = '该红包已使用或是过期';
                $this->output['shortMessage'] = '该红包已使用或是过期';
                break;
            case ErrorCodeConst::voucherExpired:
                $this->output['resultCode'] = ErrorCodeConst::voucherExpired;
                $this->output['longMessage'] = '该红包已过期';
                $this->output['shortMessage'] = '该红包已过期';
                break;
            case ErrorCodeConst::voucherLimit:
                $this->output['resultCode'] = ErrorCodeConst::voucherLimit;
                $this->output['longMessage'] = '不满足使用条件';
                $this->output['shortMessage'] = '不满足使用条件';
                break;
            case ErrorCodeConst::redVoucherTovoucherLimit:
                $this->output['resultCode'] = ErrorCodeConst::redVoucherTovoucherLimit;
                $this->output['longMessage'] = '使用红包后，不满足优惠券使用条件';
                $this->output['shortMessage'] = '使用红包后，不满足优惠券使用条件';
                break;
                break;
            case ErrorCodeConst::voucherNoJoinOtherActive:
                $this->output['resultCode'] = ErrorCodeConst::voucherNoJoinOtherActive;
                $this->output['longMessage'] = '优惠券不能与其他活动同享，逛逛其他商品吧！';
                $this->output['shortMessage'] = '优惠券不能与其他活动同享，逛逛其他商品吧！';
                break;
            case ErrorCodeConst::redVoucherNoJoinOtherActive:
                $this->output['resultCode'] = ErrorCodeConst::redVoucherNoJoinOtherActive;
                $this->output['longMessage'] = '红包不能与其他活动同享，逛逛其他商品吧';
                $this->output['shortMessage'] = '红包不能与其他活动同享，逛逛其他商品吧！';
                break;
            case ErrorCodeConst::voucherNumberRecipientsPerPerson:
                $this->output['resultCode'] = ErrorCodeConst::voucherNumberRecipientsPerPerson;
                $this->output['longMessage'] = '超过每人领取次数';
                $this->output['shortMessage'] = '超过每人领取次数';
                break;
            case ErrorCodeConst::phoneNoExists:
                $this->output['resultCode'] = ErrorCodeConst::phoneNoExists;
                $this->output['longMessage'] = '该电话未注册，请注册';
                $this->output['shortMessage'] = '该电话未注册，请注册';
                break;
            case ErrorCodeConst::userAuthenticationWaiting:
                $this->output['resultCode'] = ErrorCodeConst::userAuthenticationWaiting;
                $this->output['longMessage'] = '身份未进行验证';
                $this->output['shortMessage'] = '身份未进行验证';
                break;
            case ErrorCodeConst::userAuthenticationNoRealName:
                $this->output['resultCode'] = ErrorCodeConst::userAuthenticationNoRealName;
                $this->output['longMessage'] = '请输入有效的姓名';
                $this->output['shortMessage'] = '请输入有效的姓名';
                break;
            case ErrorCodeConst::userAuthenticationNoRealCardId:
                $this->output['resultCode'] = ErrorCodeConst::userAuthenticationNoRealCardId;
                $this->output['longMessage'] = '请输入有效的身份证号码';
                $this->output['shortMessage'] = '请输入有效的身份证号码';
                break;
            case ErrorCodeConst::userAuthenticationNoPassed:
                $this->output['resultCode'] = ErrorCodeConst::userAuthenticationNoPassed;
                $this->output['longMessage'] = '认证失败,身份证号码与名字不匹配';
                $this->output['shortMessage'] = '认证失败,身份证号码与名字不匹配';
                break;
            case AppConst::requestError:
                $this->output [AppConst::ack] = AppConst::failed;
                $this->output [AppConst::resultCode] = AppConst::requestError;
                $this->output ['repsoneContent'] = $repsoneContent;
                break;
            case ErrorCodeConst::productSoldOut:
                $this->output['resultCode'] = ErrorCodeConst::productSoldOut;
                $this->output['longMessage'] = '商品已经售完，请购买其他的商品';
                $this->output['shortMessage'] = '商品已经售完，请购买其他的商品';
                break;
            case ErrorCodeConst::productFavorited:
                $this->output['resultCode'] = ErrorCodeConst::productFavorited;
                $this->output['longMessage'] = '商品已收藏,请不要重复点击';
                $this->output['shortMessage'] = '商品已收藏,请不要重复点击';
                break;
            case ErrorCodeConst::productFavoritesDeleted:
                $this->output['resultCode'] = ErrorCodeConst::productFavoritesDeleted;
                $this->output['longMessage'] = '商品已取消收藏,请不要重复点击';
                $this->output['shortMessage'] = '商品已取消收藏,请不要重复点击';
                break;
            case ErrorCodeConst::emptyCart:
                $this->output['resultCode'] = ErrorCodeConst::emptyCart;
                $this->output['longMessage'] = '购物车为空';
                $this->output['shortMessage'] = '购物车为空';
                break;
            case ErrorCodeConst::inventoryShortageProducts:
                $this->output['resultCode'] = ErrorCodeConst::inventoryShortageProducts;
                $this->output['longMessage'] = '商品库存不足';
                $this->output['shortMessage'] = '商品库存不足';
                break;
            case ErrorCodeConst::provinceError:
                $this->output['resultCode'] = ErrorCodeConst::provinceError;
                $this->output['longMessage'] = '请选择省份';
                $this->output['shortMessage'] = '请选择省份';
                break;
            case ErrorCodeConst::cityError:
                $this->output['resultCode'] = ErrorCodeConst::cityError;
                $this->output['longMessage'] = '请选择城市';
                $this->output['shortMessage'] = '请选择城市';
                break;
            case ErrorCodeConst::areaError:
                $this->output['resultCode'] = ErrorCodeConst::areaError;
                $this->output['longMessage'] = '请选择地区';
                $this->output['shortMessage'] = '请选择地区';
                break;
	        case ErrorCodeConst::detailAdressError:
		        $this->output['resultCode'] = ErrorCodeConst::detailAdressError;
		        $this->output['longMessage'] = '请输入详细的地址';
		        $this->output['shortMessage'] = '请输入详细地址';
		        break;
            case ErrorCodeConst::realNameError:
                $this->output['resultCode'] = ErrorCodeConst::realNameError;
                $this->output['longMessage'] = '请输入收货人名字';
                $this->output['shortMessage'] = '请输入收货人名字';
                break;
            case ErrorCodeConst::realNameOutOfLength:
                $this->output['resultCode'] = ErrorCodeConst::realNameOutOfLength;
                $this->output['longMessage'] = '姓名不能超过10个字符';
                $this->output['shortMessage'] = '姓名不能超过10个字符';
                break;
            case ErrorCodeConst::mobileError:
                $this->output['resultCode'] = ErrorCodeConst::mobileError;
                $this->output['longMessage'] = '请输入电话号码';
                $this->output['shortMessage'] = '请输入电话号码';
                break;
            case ErrorCodeConst::addressError:
                $this->output['resultCode'] = ErrorCodeConst::addressError;
                $this->output['longMessage'] = '地址不存在';
                $this->output['shortMessage'] = '地址不存在';
                break;
            case ErrorCodeConst::mobileNumberWrong:
                $this->output['resultCode'] = ErrorCodeConst::mobileNumberWrong;
                $this->output['longMessage'] = '电话号码有误';
                $this->output['shortMessage'] = '电话号码有误';
                break;
            case ErrorCodeConst::emptyProductName:
                $this->output['resultCode'] = ErrorCodeConst::emptyProductName;
                $this->output['longMessage'] = '请输入商品名称';
                $this->output['shortMessage'] = '请输入商品名称';
                break;
            case ErrorCodeConst::processOrderEmptyCart:
                $this->output['resultCode'] = ErrorCodeConst::processOrderEmptyCart;
                $this->output['longMessage'] = '当前订单无法处理，请确认是否从购物车提交您要购买的商品';
                $this->output['shortMessage'] = '当前订单无法处理，请确认是否从购物车提交您要购买的商品';
                break;
            case ErrorCodeConst::processOrderIsEmptyCart:
                $this->output['resultCode'] = ErrorCodeConst::processOrderIsEmptyCart;
                $this->output['longMessage'] = '当前订单无法处理，请确认是否从购物车提交您要购买的商品';
                $this->output['shortMessage'] = '当前订单无法处理，请确认是否从购物车提交您要购买的商品';
                break;
            case ErrorCodeConst::promotionIsBuyLimit:
                $this->output['resultCode'] = ErrorCodeConst::promotionIsBuyLimit;
                $this->output['longMessage'] = '该活动您已经购买过';
                $this->output['shortMessage'] = '该活动您已经购买过';

                break;
            case ErrorCodeConst::processOrderSoldoutProducts:
                $this->output['resultCode'] = ErrorCodeConst::processOrderSoldoutProducts;
                $this->output['longMessage'] = '当前订单无法处理，您当前要购买的产品中有售完的产品';
                $this->output['shortMessage'] = '当前订单无法处理，您当前要购买的产品中有售完的产品';
                break;
            case ErrorCodeConst::processOrderStorageChange:
                $this->output['resultCode'] = ErrorCodeConst::processOrderStorageChange;
                $this->output['longMessage'] = '当前订单无法处理，您当前要购买的产品库存已经变更';
                $this->output['shortMessage'] = '当前订单无法处理，您当前要购买的产品库存已经变更';
                break;

            case ErrorCodeConst::voucherItemsNoUse:
                $this->output['resultCode'] = ErrorCodeConst::voucherItemsNoUse;
                $this->output['longMessage'] = '当前您购买的商品不符合该优惠劵的使用条件';
                $this->output['shortMessage'] = '当前您购买的商品不符合该优惠劵的使用条件';

                break;
            case ErrorCodeConst::voucherCategoriesNoUse:
                $this->output['resultCode'] = ErrorCodeConst::voucherCategoriesNoUse;
                $this->output['longMessage'] = '当前您购买的商品不符合该优惠劵的使用条件';
                $this->output['shortMessage'] = '当前您购买的商品不符合该优惠劵的使用条件';
                break;
            case ErrorCodeConst::alipayPayParseFailed:
                $this->output['resultCode'] = ErrorCodeConst::alipayPayParseFailed;
                $this->output['longMessage'] = '支付宝支付失败，请查看您当前订单。如果已经支付完成，请进入订单列表是否支付成功或是联系客服，谢谢。';
                $this->output['shortMessage'] = '解析异常';
                break;
            case ErrorCodeConst::alipayPayFailed:
                $this->output['resultCode'] = ErrorCodeConst::alipayPayFailed;
                $this->output['longMessage'] = '支付宝支付失败，请查看您当前订单。如果已经支付完成，请进入订单列表是否支付成功或是联系客服，谢谢。';
                $this->output['shortMessage'] = '支付宝支付失败，请查看您当前订单。如果已经支付完成，请进入订单列表是否支付成功或是联系客服，谢谢。';
                break;
            case ErrorCodeConst::wxPayFailed:
                $this->output['resultCode'] = ErrorCodeConst::alipayPayFailed;
                $this->output['longMessage'] = '微信支付失败，请查看您当前订单。如果已经支付完成，请进入订单列表是否支付成功或是联系客服，谢谢。';
                $this->output['shortMessage'] = '微信支付失败，请查看您当前订单。如果已经支付完成，请进入订单列表是否支付成功或是联系客服，谢谢。';
                break;
            case ErrorCodeConst::emptyCaptCode:
                $this->output['resultCode'] = ErrorCodeConst::emptyCaptCode;
                $this->output['longMessage'] = '请输入手机验证码';
                $this->output['shortMessage'] = '请输入手机验证码';
                break;
            case ErrorCodeConst::noLogin:
                $this->output['resultCode'] = ErrorCodeConst::noLogin;
                $this->output['longMessage'] = '请登录';
                $this->output['shortMessage'] = '请登录';
                break;
            case ErrorCodeConst::changeMobilEqua:
                $this->output['resultCode'] = ErrorCodeConst::changeMobilEqua;
                $this->output['longMessage'] = '您当前要更换的手机号码和原先绑定的手机号码一样，请确认';
                $this->output['shortMessage'] = '您当前要更换的手机号码和原先绑定的手机号码一样，请确认';
                break;
                break;
            case ErrorCodeConst::nickNameEmpty:
                $this->output['resultCode'] = ErrorCodeConst::nickNameEmpty;
                $this->output['longMessage'] = '请输入昵称';
                $this->output['shortMessage'] = '请输入昵称';
                break;
            case ErrorCodeConst::trueNameEmpty:
                $this->output['resultCode'] = ErrorCodeConst::trueNameEmpty;
                $this->output['longMessage'] = '请输入您的名字';
                $this->output['shortMessage'] = '请输入您的名字';
                break;
            case ErrorCodeConst::avatarEmtpy:
                $this->output['resultCode'] = ErrorCodeConst::avatarEmtpy;
                $this->output['longMessage'] = '请设置您的头像';
                $this->output['shortMessage'] = '请设置您的头像';
                break;
            case ErrorCodeConst::noReview:
                $this->output['resultCode'] = ErrorCodeConst::noReview;
                $this->output['longMessage'] = '当前订单还不能进行评价';
                $this->output['shortMessage'] = '当前订单还不能进行评价';
                break;
            case ErrorCodeConst::noAddReview:
                $this->output['resultCode'] = ErrorCodeConst::noAddReview;
                $this->output['longMessage'] = '未添加评论不能进行追加评论';
                $this->output['shortMessage'] = '未添加评论不能进行追加评论';
                break;
            case ErrorCodeConst::orderNoExists:
                $this->output['resultCode'] = ErrorCodeConst::orderNoExists;
                $this->output['longMessage'] = '订单不存在';
                $this->output['shortMessage'] = '订单不存在';
                break;
            case ErrorCodeConst::preSaleNoExists:
                $this->output['resultCode'] = ErrorCodeConst::preSaleNoExists;
                $this->output['longMessage'] = '售后不存在';
                $this->output['shortMessage'] = '售后不存在';
                break;
            case ErrorCodeConst::inputLogisticsCompany:
                $this->output['resultCode'] = ErrorCodeConst::inputLogisticsCompany;
                $this->output['longMessage'] = '请输入或是选择物流公司';
                $this->output['shortMessage'] = '请输入或是选择物流公司';
                break;
            case ErrorCodeConst::inputLogisticsNumber:
                $this->output['resultCode'] = ErrorCodeConst::inputLogisticsNumber;
                $this->output['longMessage'] = '请输入物流单号';
                $this->output['shortMessage'] = '请输入物流单号';
                break;
            case ErrorCodeConst::selectPreSaleReasonType:
                $this->output['resultCode'] = ErrorCodeConst::selectPreSaleReasonType;
                $this->output['longMessage'] = '请选择售后类型';
                $this->output['shortMessage'] = '请选择售后类型';
                break;
            case ErrorCodeConst::selectPreSaleReason:
                $this->output['resultCode'] = ErrorCodeConst::selectPreSaleReason;
                $this->output['longMessage'] = '请选择申请原因';
                $this->output['shortMessage'] = '请选择申请原因';
                break;
            case ErrorCodeConst::inputPreSaleDescription:
                $this->output['resultCode'] = ErrorCodeConst::inputPreSaleDescription;
                $this->output['longMessage'] = '请输入申请原因';
                $this->output['shortMessage'] = '请输入申请原因';
                break;
            case ErrorCodeConst::selectPreSaleGoods:
                $this->output['resultCode'] = ErrorCodeConst::selectPreSaleGoods;
                $this->output['longMessage'] = '请选择需要售后的产品';
                $this->output['shortMessage'] = '请选择需要售后的产品';
                break;
            case ErrorCodeConst::memberbirthday:
                $this->output['resultCode'] = ErrorCodeConst::memberbirthday;
                $this->output['longMessage'] = '请设置您的生日';
                $this->output['shortMessage'] = '请设置您的生日';
                break;
            case ErrorCodeConst::membersex:
                $this->output['resultCode'] = ErrorCodeConst::membersex;
                $this->output['longMessage'] = '请设置您的性别';
                $this->output['shortMessage'] = '请设置您的性别';
                break;
            case ErrorCodeConst::emptyInviterCode:
                $this->output['resultCode'] = ErrorCodeConst::emptyInviterCode;
                $this->output['longMessage'] = '请输入您的邀请码';
                $this->output['shortMessage'] = '请输入您的邀请码';
                break;
            case ErrorCodeConst::inviterCodeError:
                $this->output['resultCode'] = ErrorCodeConst::inviterCodeError;
                $this->output['longMessage'] = '邀请码错误';
                $this->output['shortMessage'] = '邀请码错误';
                break;
            case ErrorCodeConst::inviterTime:
                $this->output['resultCode'] = ErrorCodeConst::inviterTime;
                $this->output['longMessage'] = '不能被后注册的人邀请';
                $this->output['shortMessage'] = '不能被后注册的人邀请';
                break;
            case ErrorCodeConst::invitedAgain:
                $this->output['resultCode'] = ErrorCodeConst::invitedAgain;
                $this->output['longMessage'] = '不能再次被邀请';
                $this->output['shortMessage'] = '不能再次被邀请';
                break;
            case ErrorCodeConst::inviteYourself:
                $this->output['resultCode'] = ErrorCodeConst::inviteYourself;
                $this->output['longMessage'] = '自己不能邀请自己';
                $this->output['shortMessage'] = '自己不能邀请自己';
                break;
            case ErrorCodeConst::invitePromoters:
                $this->output['resultCode'] = ErrorCodeConst::invitePromoters;
                $this->output['longMessage'] = '你是推广员不能被邀请';
                $this->output['shortMessage'] = '你是推广员不能被邀请';
                break;
            case ErrorCodeConst::recommendNoExists:
                $this->output['resultCode'] = ErrorCodeConst::recommendNoExists;
                $this->output['longMessage'] = '番觅不存在';
                $this->output['shortMessage'] = '番觅不存在';
                break;
            case ErrorCodeConst::recommendReviewNoInput:
                $this->output['resultCode'] = ErrorCodeConst::recommendReviewNoInput;
                $this->output['longMessage'] = '请输入番觅评论内容';
                $this->output['shortMessage'] = '请输入番觅评论内容';
                break;
            case ErrorCodeConst::subscribersAlreadyExist:
                $this->output['resultCode'] = ErrorCodeConst::subscribersAlreadyExist;
                $this->output['longMessage'] = '订购人已经存在';
                $this->output['shortMessage'] = '订购人已经存在';
                break;
            case ErrorCodeConst::requestSuccess:
                ;
                break;
            case ErrorCodeConst::emptyShapeCode:
                $this->output['resultCode'] = ErrorCodeConst::emptyShapeCode;
                $this->output['longMessage'] = '商品编码为空';
                $this->output['shortMessage'] = '商品编码为空';
                break;
            case ErrorCodeConst::emptyProductNum:
                $this->output['resultCode'] = ErrorCodeConst::emptyProductNum;
                $this->output['longMessage'] = '商品数量为空';
                $this->output['shortMessage'] = '商品数量为空';
                break;
            case ErrorCodeConst::emptyConsignee:
                $this->output['resultCode'] = ErrorCodeConst::emptyConsignee;
                $this->output['longMessage'] = '收货人必须填写';
                $this->output['shortMessage'] = '收货人必须填写';
                break;
            case ErrorCodeConst::emptyProvince:
                $this->output['resultCode'] = ErrorCodeConst::emptyProvince;
                $this->output['longMessage'] = '请填写省份';
                $this->output['shortMessage'] = '请填写省份';
                break;
            case ErrorCodeConst::emptyCity:
                $this->output['resultCode'] = ErrorCodeConst::emptyCity;
                $this->output['longMessage'] = '请填写';
                $this->output['shortMessage'] = '市为空';
                break;
            case ErrorCodeConst::emptyArea:
                $this->output['resultCode'] = ErrorCodeConst::emptyArea;
                $this->output['longMessage'] = '区为空';
                $this->output['shortMessage'] = '区为空';
                break;
            case ErrorCodeConst::emptyDetailAdress:
                $this->output['resultCode'] = ErrorCodeConst::emptyDetailAdress;
                $this->output['longMessage'] = '详细地址为空';
                $this->output['shortMessage'] = '详细地址为空';
                break;
            case ErrorCodeConst::addressOutOfLength:
                $this->output['resultCode'] = ErrorCodeConst::addressOutOfLength;
                $this->output['longMessage'] = '详细地址不能超过75个字';
                $this->output['shortMessage'] = '详细地址不能超过75个字';
                break;
            case ErrorCodeConst::emptySubscriber:
                $this->output['resultCode'] = ErrorCodeConst::emptySubscriber;
                $this->output['longMessage'] = '订购人为空';
                $this->output['shortMessage'] = '订购人为空';
                break;
            case ErrorCodeConst::emptyCartId:
                $this->output['resultCode'] = ErrorCodeConst::emptyCartId;
                $this->output['longMessage'] = '身份证为空';
                $this->output['shortMessage'] = '身份证为空';
                break;
            case ErrorCodeConst::notPositiveInteger:
                $this->output['resultCode'] = ErrorCodeConst::notPositiveInteger;
                $this->output['longMessage'] = '不是正整数';
                $this->output['shortMessage'] = '不是正整数';
                break;
            case ErrorCodeConst::moreTenCharacters:
                $this->output['resultCode'] = ErrorCodeConst::moreTenCharacters;
                $this->output['longMessage'] = '用户名长度大于10个字符';
                $this->output['shortMessage'] = '用户名长度大于10个字符';
                break;
            case ErrorCodeConst::noSubscriber:
                $this->output['resultCode'] = ErrorCodeConst::noSubscriber;
                $this->output['longMessage'] = '订购人实名认证失败';
                $this->output['shortMessage'] = '订购人实名认证失败';
                break;
            case ErrorCodeConst::errorProvince:
                $this->output['resultCode'] = ErrorCodeConst::errorProvince;
                $this->output['longMessage'] = '您输入的省份错误';
                $this->output['shortMessage'] = '您输入的省份错误';
                break;
            case ErrorCodeConst::errorCity:
                $this->output['resultCode'] = ErrorCodeConst::errorCity;
                $this->output['longMessage'] = '您输入的市错误';
                $this->output['shortMessage'] = '您输入的市错误';
                break;
            case ErrorCodeConst::errorArea:
                $this->output['resultCode'] = ErrorCodeConst::errorArea;
                $this->output['longMessage'] = '您输入的区错误';
                $this->output['shortMessage'] = '您输入的区错误';
                break;
            case ErrorCodeConst::noProduct:
                $this->output['resultCode'] = ErrorCodeConst::noProduct;
                $this->output['longMessage'] = '商品已下架';
                $this->output['shortMessage'] = '商品已下架';
                break;
            case ErrorCodeConst::ManagerGroupEmpty:
                $this->output['resultCode'] = ErrorCodeConst::ManagerGroupEmpty;
                $this->output['longMessage'] = '商务经理分组不存在,请先添加商务经理分组';
                $this->output['shortMessage'] = '商务经理分组不存在,请先添加商务经理分组';
                break;
            case ErrorCodeConst::emptyOrderNo:
                $this->output['resultCode'] = ErrorCodeConst::emptyOrderNo;
                $this->output['longMessage'] = '订单号不为空';
                $this->output['shortMessage'] = '订单号不为空';
                break;
            case ErrorCodeConst::differentsubscriber:
                $this->output['resultCode'] = ErrorCodeConst::differentsubscriber;
                $this->output['longMessage'] = '订购人不同';
                $this->output['shortMessage'] = '订购人不同';
                break;
            case ErrorCodeConst::differentCartId:
                $this->output['resultCode'] = ErrorCodeConst::differentCartId;
                $this->output['longMessage'] = '身份证不同';
                $this->output['shortMessage'] = '身份证不同';
                break;
            case ErrorCodeConst::differentConsignee:
                $this->output['resultCode'] = ErrorCodeConst::differentConsignee;
                $this->output['longMessage'] = '收货人不同';
                $this->output['shortMessage'] = '收货人不同';
                break;
            case ErrorCodeConst::differentMobiePhone:
                $this->output['resultCode'] = ErrorCodeConst::differentMobiePhone;
                $this->output['longMessage'] = '收货人手机号码不同';
                $this->output['shortMessage'] = '收货人手机号码不同';
                break;
            case ErrorCodeConst::differentAddress:
                $this->output['resultCode'] = ErrorCodeConst::differentAddress;
                $this->output['longMessage'] = '收货人地址不同';
                $this->output['shortMessage'] = '收货人地址不同';
                break;
            case ErrorCodeConst::wxTokenError:
                $this->output['resultCode'] = ErrorCodeConst::wxTokenError;
                $this->output['longMessage'] = '授权失败，请重新登录';   //wx invalid code
                $this->output['shortMessage'] = '授权失败，请重新登录';
                break;
            case ErrorCodeConst::wxInvalidOpenid:
                $this->output['resultCode'] = ErrorCodeConst::wxInvalidOpenid;
                $this->output['longMessage'] = '授权失败，请重新登录';
                $this->output['shortMessage'] = '授权失败，请重新登录'; //wx invalid openid
                break;
            case ErrorCodeConst::skuLimit:
                $this->output['resultCode'] = ErrorCodeConst::skuLimit;
                $this->output['longMessage'] = '有商品超过了单次购买的件数上限';
                $this->output['shortMessage'] = '有商品超过了单次购买的件数上限';
                break;
            case ErrorCodeConst::MobilePhoneShield:
                $this->output['resultCode'] = ErrorCodeConst::MobilePhoneShield;
                $this->output['longMessage'] = $repsoneContent;
                $this->output['shortMessage'] = $repsoneContent;
                break;
            case ErrorCodeConst::alreadySignedIn:
                $this->output['resultCode'] = ErrorCodeConst::alreadySignedIn;
                $this->output['longMessage'] = '今日已签到明日再来吧';
                $this->output['shortMessage'] = '今日已签到明日再来吧';
                break;
            case ErrorCodeConst::alreadyBind:
                $this->output['resultCode'] = ErrorCodeConst::alreadyBind;
                $this->output['longMessage'] = '该微信已绑定其他账号';
                $this->output['shortMessage'] = '该微信已绑定其他账号';
                break;
            case ErrorCodeConst::unbindMobile:
                $this->output['resultCode'] = ErrorCodeConst::unbindMobile;
                $this->output['longMessage'] = '请先绑定手机号';
                $this->output['shortMessage'] = '请先绑定手机号';
                break;
            case ErrorCodeConst::unbindWxAccount:
                $this->output['resultCode'] = ErrorCodeConst::unbindWxAccount;
                $this->output['longMessage'] = '未绑定微信账号';
                $this->output['shortMessage'] = '未绑定微信账号';
                break;
            case ErrorCodeConst::unbindFail:
                $this->output['resultCode'] = ErrorCodeConst::unbindFail;
                $this->output['longMessage'] = '解除绑定失败';
                $this->output['shortMessage'] = '解除绑定失败';
                break;
            case ErrorCodeConst::bindFail:
                $this->output['resultCode'] = ErrorCodeConst::bindFail;
                $this->output['longMessage'] = '绑定失败';
                $this->output['shortMessage'] = '绑定失败';
                break;
            case ErrorCodeConst::memberSkinType:
                $this->output['resultCode'] = ErrorCodeConst::memberSkinType;
                $this->output['longMessage'] = '请设置肤质';
                $this->output['shortMessage'] = '请设置肤质';
                break;
            case ErrorCodeConst::tooManyOrders:
                $this->output['resultCode'] = ErrorCodeConst::tooManyOrders;
                $this->output['longMessage'] = '为避免刷单，我们限制了购买订单数量，感谢合作。';
                $this->output['shortMessage'] = '为避免刷单，我们限制了购买订单数量，感谢合作。';
                break;

            case ErrorCodeConst::wrongWxInfo:
                $this->output['resultCode'] = ErrorCodeConst::wrongWxInfo;
                $this->output['longMessage'] = '微信信息获取错误';
                $this->output['shortMessage'] = '微信信息获取错误';
                break;

            case ErrorCodeConst::bsOrderAmountLimitError:
                $this->output['resultCode'] = ErrorCodeConst::jpOrderAmountLimitError;
                $this->output['longMessage'] = '保税商品单次购买总额不能超过'.$repsoneContent['errorMsgData'];
                $this->output['shortMessage'] = '保税商品单次购买总额不能超过'.$repsoneContent['errorMsgData'];
                break;
            case ErrorCodeConst::jpOrderAmountLimitError:
                $this->output['resultCode'] = ErrorCodeConst::tooManyOrders;
                $this->output['longMessage'] = '直邮商品单次购买总额不能超过'.$repsoneContent['errorMsgData'];
                $this->output['shortMessage'] = '直邮商品单次购买总额不能超过'.$repsoneContent['errorMsgData'];
                break;
            case ErrorCodeConst::noNewUser:
                $this->output['resultCode'] = ErrorCodeConst::noNewUser;
                $this->output['longMessage'] = '兑换失败，该券仅限新用户兑换';
                $this->output['shortMessage'] = '兑换失败，该券仅限新用户兑换';
                break;

            case ErrorCodeConst::createBWxOrderFailed:
                $this->output['resultCode'] = ErrorCodeConst::createBWxOrderFailed;
                $this->output['longMessage'] = '生成小程序订单失败';
                $this->output['shortMessage'] = '生成小程序订单失败';
                break;
            case ErrorCodeConst::pleaseSetShippingInfo:
                $this->output['resultCode'] = ErrorCodeConst::pleaseSetShippingInfo;
                $this->output['longMessage'] = '请选择收货人';
                $this->output['shortMessage'] = '请选择收货人';
                break;
            case ErrorCodeConst::pleaseFinishAuthentication:
                $this->output['resultCode'] = ErrorCodeConst::pleaseFinishAuthentication;
                $this->output['longMessage'] = '请完善实名认证';
                $this->output['shortMessage'] = '请完善实名认证';
                break;
            case ErrorCodeConst::updateBWxOrderStatusFailed:
                $this->output['resultCode'] = ErrorCodeConst::updateBWxOrderStatusFailed;
                $this->output['longMessage'] = '更新订单状态失败';
                $this->output['shortMessage'] = '更新订单状态失败';
                break;
            case ErrorCodeConst::getBWxOrderSnFailed:
                $this->output['resultCode'] = ErrorCodeConst::getBWxOrderSnFailed;
                $this->output['longMessage'] = '获取订单号失败';
                $this->output['shortMessage'] = '获取订单号失败';
                break;
            case ErrorCodeConst::getBWxOrderGoodsFailed:
                $this->output['resultCode'] = ErrorCodeConst::getBWxOrderGoodsFailed;
                $this->output['longMessage'] = '获取订单商品信息失败';
                $this->output['shortMessage'] = '获取订单商品信息失败';
                break;
            case ErrorCodeConst::needLoginDataCode:
                $this->output['resultCode'] = ErrorCodeConst::needLoginDataCode;
                $this->output['longMessage'] = '请求获取openid';
                $this->output['shortMessage'] = '请求获取openid';
                break;
            case ErrorCodeConst::storageNotEnough:
                $this->output['resultCode'] = ErrorCodeConst::storageNotEnough;
                $this->output['longMessage'] = '商品库存不足';
                $this->output['shortMessage'] = '商品库存不足';
                break;
            case ErrorCodeConst::goodsIsUnshelf:
                $this->output['resultCode'] = ErrorCodeConst::goodsIsUnshelf;
                $this->output['longMessage'] = '商品已经下架';
                $this->output['shortMessage'] = '商品已经下架';
                break;
            case ErrorCodeConst::cartGoodsIsOutData:
                $this->output['resultCode'] = ErrorCodeConst::cartGoodsIsOutData;
                $this->output['longMessage'] = '购物车商品已过期';
                $this->output['shortMessage'] = '购物车商品已过期';
                break;
            case ErrorCodeConst::overGoodsPurchaseLimit:
                $this->output['resultCode'] = ErrorCodeConst::overGoodsPurchaseLimit;
                $this->output['longMessage'] = '购买数量大于商品活动限购量';
                $this->output['shortMessage'] = '购买数量大于商品活动限购量';
                break;
            case ErrorCodeConst::overStorageLimit:
                $this->output['resultCode'] = ErrorCodeConst::overStorageLimit;
                $this->output['longMessage'] = '购买数量大于库存数量';
                $this->output['shortMessage'] = '购买数量大于库存数量';
                break;
	        case ErrorCodeConst::boostGoodsOnlyOne:
		        $this->output['resultCode'] = ErrorCodeConst::boostGoodsOnlyOne;
		        $this->output['longMessage'] = '助力成功的商品只能以目标助力价购买一次';
		        $this->output['shortMessage'] = '助力成功的商品只能以目标助力价购买一次';
		        break;
	        case ErrorCodeConst::emptySubmitTitle:
		        $this->output['resultCode'] =ErrorCodeConst::emptySubmitTitle;
		        $this->output['longMessage'] = '请填入标题';
		        $this->output['shortMessage'] = '请填入标题';
		        break;
	        case ErrorCodeConst::emptyFeedbackDetail:
		        $this->output['resultCode'] =ErrorCodeConst::emptyFeedbackDetail;
		        $this->output['longMessage'] = '请填入问题详情';
		        $this->output['shortMessage'] = '请填入问题详情';
		        break;
            case ErrorCodeConst::overActivityPurchaseLimit:
		        $this->output['resultCode'] =ErrorCodeConst::overActivityPurchaseLimit;
		        $this->output['longMessage'] = '部分商品已触发限购条件';
		        $this->output['shortMessage'] = '购买数量大于限购数量';
		        break;
            case ErrorCodeConst::buyOnceWithAllLimitedGoods:
                $this->output['resultCode'] =ErrorCodeConst::buyOnceWithAllLimitedGoods;
                $this->output['longMessage'] = '全部商品下架或限购未添加到购物车';
                $this->output['shortMessage'] = '全部商品下架或限购未添加到购物车';
                break;
	        case ErrorCodeConst::unvalidEnvelopeCode:
		        $this->output['resultCode'] =ErrorCodeConst::unvalidEnvelopeCode;
		        $this->output['longMessage'] = '该红包已失效';
		        $this->output['shortMessage'] = '该红包已失效';
		        break;
            case ErrorCodeConst::needUserInfoCode:
                $this->output['resultCode'] =ErrorCodeConst::needUserInfoCode;
                $this->output['longMessage'] = '未获取用户微信账号信息';
                $this->output['shortMessage'] = '未获取用户微信账号信息';
                break;
	        case ErrorCodeConst::cartQtyIsExceed:
		        $this->output['resultCode'] =ErrorCodeConst::cartQtyIsExceed;
		        $this->output['longMessage'] = '亲亲~单笔订单合计购买数量不可超出9件哟~';
		        $this->output['shortMessage'] = '单笔订单的合计购买数量超过了';
		        break;
            default:
                $this->output['resultCode'] = ErrorCodeConst::actionError;
                $this->output['longMessage'] = '操作失败';
                $this->output['shortMessage'] = '操作失败';
                break;
        }
        return $this->output;
    }
}