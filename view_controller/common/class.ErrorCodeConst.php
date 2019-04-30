<?php
/**
 * 定义App 的 error code  
 * @author kinsly
 */
class ErrorCodeConst
{
    //commo const
    const ack = 'status';
    const longMessage = 'longMessage';
    const shortMessage = 'shortMessage';
    const resultCode = 'resultCode';
    const errorCode = 'error_code';
    const message = 'message';
    const expirssTime = 'expires_time';
    const appToken = 'app_token';
    const deviciesToken = 'devicies_token';

    const jsonTypeObj = 'obj';
    const jsonTypeArray = 'array';
    const jsonType = 'jsonType';
    const mcryptKey = 'mcrypt_key';
    const mcryptIV = 'mcrypt_iv';

    const customerId = 'customer_id';
    //////////////////////////
    const aesEncode = 1;//1:AES

    const md5Encode = 2;//2.MD5

    const plainEncode = 3;//3.明文


    const success = 'success';
    const failed = 'failed';

    const requestSuccess = 100000;

    /**
     * 操作失败
     * @var unknown
     */
    const actionError = 100003;

    const System = 100001;

    /**
     * 手机验证码发送失败
     * @var unknown
     */
    const SystemPhoneSendFailed = 100002;

    ////
    const LoginNameEmpty = 300001;
    const PasswordEmpty = 300002;
    const Login = 300003;
    /**
     * 电话号码不存在
     * @var unknown
     */
    const phoneNoExists = 300004;
    ////注册
    const MobilePhoneEmpty = 300011;
    const MobilePhoneFormat = 300012;
    const MobilePhoneShield = 300022;

    const MobilePasswordEmpty = 300013;
    const MobilePasswordLength = 300014;
    const MobilePasswordNotMatch = 300015;

    const registerFailed = 300019;
    /**
     * 已经注册过
     * @var unknown
     */
    const MobileExists = 300016;
    const PhoneCaptcha = 300017;
    const InviterCode = 300018;

    const SendMsgType = 300030;
    const SendRepeat = 300031;
    const MobileAlreadyBind = 300033;

    /**
     * 忘记密码,登陆， 手机未注册，请核对手机是否有误
     * @var unknown
     */
    const SendPhoneNotMatch = 300032;

    /**
     * 不存在该优惠码
     * @var unknown
     */
    const voucherCodeNotExists = 600001;

    /**
     * 优惠券已失效
     * @var unknown
     */
    const voucherCodeFailed = 600002;

    /**
     * 不存在该红包
     * @var unknown
     */
    const redVoucherCodeNotExists = 600003;

    /**
     * 该红包已用,3-过期,4-收回
     * @var unknown
     */
    const redVoucherCodeUsedOrOther = 600004;


    /**
     * 过期
     * @var unknown
     */
    const voucherExpired = 600005;

    /**
     * 订单商品限额
     * @var unknown
     */
    const voucherLimit = 600006;

    /**
     * 使用红包后导致原先使用的优惠劵不能使用
     * @var unknown
     */
    const redVoucherTovoucherLimit = 600007;

    /**
     * 代金券不能与其他优惠一起使用
     * @var unknown
     */
    const voucherNoJoinOtherActive = 600008;

    /**
     * 红包不能与其他活动使用
     * @var unknown
     */
    const redVoucherNoJoinOtherActive = 600009;

    /**
     * 使用红包后导致原先使用的优惠劵不能使用
     * @var unknown
     */
    const voucherNumberRecipientsPerPerson = 6000081;
    const voucherItemsNoUse = 6000082;
    const voucherCategoriesNoUse = 6000083;


    /**
     * 身份验证未通过
     * @var unknown
     */
    const userAuthenticationNoPassed = 7000001;

    /**
     * 身份验证未进行验证
     * @var unknown
     */
    const userAuthenticationWaiting = 7000002;

    /**
     * 输入有效的姓名
     * @var unknown
     */
    const userAuthenticationNoRealName = 7000003;

    /**
     * 输入有效的身份
     * @var unknown
     */
    const userAuthenticationNoRealCardId = 7000004;


    /**
     * 商品卖完
     * @var unknown
     */
    const productSoldOut = 8000001;

    /**
     * 商品已收藏
     * @var unknown
     */
    const productFavorited = 9000001;

    /**
     * 商品已删除收藏
     * @var unknown
     */
    const productFavoritesDeleted = 9000002;

    /**
     * 库存不足
     * @var unknown
     */
    const cartStorageNoEnough = 4000001;
    /* /**
     * 库存
     * @var unknown
     */
    //const cartStorageNoEnough = 4000002; */

    /**
     * 购物车
     * @var unknown
     */
    const  emptyCart = 10000001;
    const  inventoryShortageProducts = 10000002;
    /**
     * 地址
     * @var unknown
     */
    const  provinceError = 11000001;
    const  cityError = 11000002;
    const  realNameError = 11000003;
    const  mobileError = 11000004;
	
    const detailAdressError = 11000007;
    
    const  areaError = 11000004;
    const  addressError = 11000005;
    const  mobileNumberWrong = 11000006;
    /**
     * 搜索
     * @var unknown
     */
    const  emptyProductName = 12000001;

    /**
     * 没有购物车ID
     * @var unknown
     */
    const  processOrderEmptyCart = 13000001;

    /**
     * 没有购物车ID
     * @var unknown
     */
    const  processOrderIsEmptyCart = 13000002;

    /**
     * 没有购物车ID
     * @var unknown
     */
    const  processOrderSoldoutProducts = 13000003;

    /**
     * 当前购买的商品库存已改变
     * @var unknown
     */
    const  processOrderStorageChange = 13000004;


    /**
     * 活动商品已经购买过
     * @var unknown
     */
    const  promotionIsBuyLimit = 12000002;

    /**
     * 支付宝支付失败,支付解析异常
     * @var unknown
     */
    const  alipayPayParseFailed = 13000001;

    /**
     * 支付宝支付失败
     * @var unknown
     */
    const  alipayPayFailed = 13000002;

    /**
     * 微信支付失败
     * @var unknown
     */
    const  wxPayFailed = 130000011;    

    /**
     * 请输入手机验证码
     * @var unknown
     */
    const emptyCaptCode = 13000013;

    /**
     * 未登录
     * @var unknown
     */
    const noLogin = 130000041;

    /**
     * 绑定的手机号码和原来的相同
     * @var unknown
     */
    const changeMobilEqua = 13000005;

    /**
     * 请设置昵称
     * @var unknown
     */
    const nickNameEmpty = 13000006;

    /**
     * 请设置真实用户名称
     * @var unknown
     */
    const trueNameEmpty = 13000007;

    /**
     * 请设置头像
     * @var unknown
     */
    const avatarEmtpy = 13000008;

    /**
     * 当前订单还不能进行评价
     * @var unknown
     */
    const noReview = 13000009;

    /**
     * 未添加评论不能进行追加评论
     * @var unknown
     */
    const noAddReview = 14000001;

    /**
     * 订单不存在
     * @var unknown
     */
    const orderNoExists = 14000002;

    /**
     * 该售后不存在
     * @var unknown
     */
    const preSaleNoExists = 14000003;

    /**
     * 请输入物流公司
     * @var unknown
     */
    const inputLogisticsCompany = 14000004;

    /**
     * 请输入物流单号
     * @var unknown
     */
    const inputLogisticsNumber = 14000005;

    /**
     * 请选择售后类型
     * @var unknown
     */
    const selectPreSaleReasonType = 14000006;

    /**
     * 请选择申请原因
     * @var unknown
     */
    const selectPreSaleReason = 14000007;

    /**
     * 请输入申请原因
     * @var unknown
     */
    const inputPreSaleDescription = 14000008;

    /**
     * 请选择需要售后的产品
     * @var unknown
     */
    const selectPreSaleGoods = 14000009;
    /**
     * 请设置生日
     * @var unknown
     */
    const memberbirthday = 13000010;
    /**
     * 请设置生日
     * @var unknown
     */
    const membersex = 13000011;

    /**
     * 请设置肤质
     * @var unknown
     */
    const memberSkinType = 13000012;

    /**
     * 请输入邀请码
     */
    const emptyInviterCode = 13000110;

    /**
     * 邀请码错误
     */
    const inviterCodeError = 13000111;

    /**
     * 不能被后注册的人邀请,邀请无效
     */
    const inviterTime = 13000112;

    /**
     * 不能再次被邀请
     */
    const invitedAgain = 13000113;

    /**
     * 不能邀请自身
     */
    const inviteYourself = 13000114;

    /**
     * 不能邀请推广员
     */
    const invitePromoters = 13000115;

    /**
     * 番觅文章不存在
     * @var unknown
     */
    const recommendNoExists = 15000001;

    /**
     * 请输入番觅文章评论内容
     * @var unknown
     */
    const recommendReviewNoInput = 15000002;
    /**
     * 敏感番觅文章评论内容
     * @var unknown
     */
    const sensitiveRecommend = 15000003;


    /**
     * 订购人已经存在
     */
    const subscribersAlreadyExist = 16000001;

    /**
     * 商品编码为空
     */
    const emptyShapeCode = 20000001;

    /**
     * 商品编码为空
     */
    const emptyProductNum = 20000002;

    /**
     * 收货人为空
     */
    const emptyConsignee = 20000003;

    /**
     * 省份为空
     */
    const emptyProvince = 20000004;

    /**
     * 市为空
     */
    const emptyCity = 20000005;

    /**
     * 区为空
     */
    const emptyArea = 20000006;

    /**
     * 详细地址为空
     */
    const emptyDetailAdress = 20000007;

    /**
     * 订购人为空
     */
    const emptySubscriber = 20000008;

    /**
     * 身份证为空
     */
    const emptyCartId = 20000009;

    /**
     * 不是正整数
     */
    const notPositiveInteger = 20000010;

    /**
     * 超过十个字符
     */
    const moreTenCharacters = 20000011;

    /**
     * 认购人不存在
     */
    const noSubscriber = 20000012;

    /**
     * 错误省份
     */
    const errorProvince = 20000013;

    /**
     * 错误城市
     */
    const errorCity = 20000014;

    /**
     * 错误地区
     */
    const errorArea = 20000015;

    /**
     * 商品不存在
     */
    const noProduct = 20000016;

    /**
     * 详细地址不能超过75个字
     */
    const addressOutOfLength = 20000017;

    /**
     * 姓名不能超过10个字
     * @var unknown
     */
    const realNameOutOfLength = 20000018;

    /**
     * 商务经理分组不存在,请先添加商务经理分组
     * @var unknown
     */
    const ManagerGroupEmpty = 20000019;

    /**
     * 订单号为空
     * @var unknown
     */
    const emptyOrderNo = 20000020;

    /**
     * 订购人不同
     * @var unknown
     */
    const differentsubscriber = 20000021;
    /**
     * 身份证不同
     * @var unknown
     */
    const differentCartId = 20000022;
    /**
     * 收货人不同
     * @var unknown
     */
    const differentConsignee = 20000023;
    /**
     * 收货人手机号码不同
     * @var unknown
     */
    const differentMobiePhone = 20000024;
    /**
     * 收货人地址不同
     * @var unknown
     */
    const differentAddress = 20000025;
    /**
     * 无效token
     * @var unknown
     */
    const wxTokenError = 30000001;

    /**
     * 无效openid
     * @var unknown
     */
    const wxInvalidOpenid = 30000002;

    /**
     * 有商品被限购
     */
    const skuLimit = 40000001;
    
    /**
     * 保税购买上限值
     */
    const bsOrderAmountLimitError = 40000002;
    
    /**
     * 直邮购买上限值
     */
    const jpOrderAmountLimitError = 40000003;

    /**
     * 自定义错误
     */
    const customizeMsg = 50000001;

    /**
     * 已经签到
     */
    const alreadySignedIn = 60000001;


    /**
     * 已绑定
     * @var unknown
     */
    const alreadyBind = 60000002;


    /**
     * 绑定失败
     * @var unknown
     */
    const bindFail = 60000003;

    /**
     * 未绑定手机号
     * @var unknown
     */
    const unbindMobile = 60000004;

    /**
     * 未绑定微信账号
     * @var unknown
     */
    const unbindWxAccount = 60000005;

    /**
     * 解除绑定失败
     * @var unknown
     */
    const unbindFail = 60000006;

    /**
     * 手机存在绑定微信
     * @var unknown
     */
    const bindPhoneUnusable = 60000007;

    /**
     * 微信信息获取错误
     * @var unknown
     */
    const wrongWxInfo = 60000008;

    /**
     * 防止刷单提示
     */
    const tooManyOrders = 70000001;

    /**
     * 兑换失败，您非一番街新用户
     */
    const noNewUser = 80000001;


    /** -------------------小程序接口状态码------------------- */

    /**
     * 获取商品信息失败
     */
    const getGoodsInfoFailed = 110001;

    /**
     * 初始获取用户授权失败
     */
    const getUserOpenidFailed = 110002;

    /**
     * 授权更新用户信息失败
     */
    const updateUserInfoFailed = 110003;

    /**
     * 获取活动信息失败
     */
    const getActivityInfoFailed = 110004;

    /**
     * 未读取到用户openid
     */
    const getSessionOpenidFailed = 110005;

    /**
     * 未正确获取商品购买数量
     */
    const wrongQtyInput = 110006;

    /**
     * 未正确获取购物车id
     */
    const wrongCartIdInput = 110007;

    /**
     * 更新购物车商品失败
     */
    const updateCartGoodsFailed = 110008;

    /**
     * 生成小程序订单失败
     */
    const createBWxOrderFailed = 110009;

    /**
     * 请选择收货人
     */
    const pleaseSetShippingInfo = 110010;

    /**
     * 请选择完善实名认证信息
     */
    const pleaseFinishAuthentication = 110011;

    /**
     * 更新订单状态失败
     */
    const updateBWxOrderStatusFailed = 110012;

    /**
     * 获取订单号失败
     */
    const getBWxOrderSnFailed = 110013;

    /**
     * 获取订单商品信息失败
     */
    const getBWxOrderGoodsFailed = 110014;

    /**
     * 请求客户端获取调用openid的code
     */
    const needLoginDataCode = 110015;

    /**
     * 库存不足
     */
    const storageNotEnough = 110016;


    /**
     * 商品已经下架
     */
    const goodsIsUnshelf = 110017;

    /**
     * 购物车商品已过期
     */
    const cartGoodsIsOutData = 110018;

    /**
     * 购买数量大于商品活动限购量
     */
    const overGoodsPurchaseLimit = 110019;

    /**
     * 购买数量大于库存数量
     */
    const overStorageLimit = 110020;
	/**
	 * 提交标题
	 */
    const emptySubmitTitle = 110021;
	/**
	 * 反馈详情
	 */
    const emptyFeedbackDetail = 110022;

    /**
     * 购买数量大于全场限购活动限购量
     */
    const overActivityPurchaseLimit = 110023;

    /**
     * 再次购买包含限制商品
     */
    const buyOnceWithLimitedGoods = 110024;
    /**
     * 再次购买全部都是限制商品
     */
    const buyOnceWithAllLimitedGoods = 110025;

    /**
     * 请求客户端获取用户的昵称和头像
     */
    const needUserInfoCode = 110026;
	/**
	 *红包已经失效啦
	 */
    const unvalidEnvelopeCode = 110023;
	/**
	 * 助力成功的商品只能以目标助力价购买一次
	 */
    const boostGoodsOnlyOne = 110029;
    
    //超出单笔订单的限购数量
    const cartQtyIsExceed = 110040;
}