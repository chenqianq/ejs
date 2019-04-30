<?php
if( !defined('IS_ACCESS') ){
    die('Illegal Access');
    exit;
}

/**
 * 用户常量
 * 注意 凡是有涉及到魔术的方法  都必须写到constants  里面定义
 * 另外 这个是模块来划分定义 文件的
 * @author kinsly 2016-10-19
 */
class YfjConst
{
    /**
     * 展示
     * @var unknown
     */
    const articleShow = 1;


    /**
     * 不展示
     * @var unknown
     */
    const articleUnshow = 0;

    /**
     * 网站简介
     * @var unknown
     */
    const articleIntroduction = 51;
    /**
     * 联系我们
     * @var unknown
     */
    const articleContact = 53;
    /**
     * 常见问题
     * @var unknown
     */
    const ploblems = 64;
    const taxInstruction = 44;
    const distribution = 70;
    const durability = 72;
    const returnPolicy = 66;
    const returnFlow = 69;
    const refoundInstruction = 67;
    const payMethod = 43;
    const preferentialUse = 63;
    const authenticationRealName = 65;
    /**
     * 常见问题
     * @var unknown
     */
    const feedbackWap = 1;
    const feedbackPc = 2;
    /**
     * 优惠劵
     * @var unknown
     */
    const voucherType = 2;

    /**
     * 红包
     * @var unknown
     */
    const  RedEnvelopes = 1;

    /**
     * 未使用优惠劵状态
     * @var unknown
     */
    const voucherNormalStatus = 1;


    /**
     * 优惠劵过期
     * @var unknown
     */
    const voucherExpired = 1;

    /**
     * 保税仓 发货
     * @var unknown
     */
    const shippingBondedWarehouse = 0;

    //代金券状态(1-未用,2-已用,3-过期,4-收回)
    /**
     * 未用
     * @var unknown
     */
    const voucherCodeNotUsed = 1;
    /**
     * 已用
     * @var unknown
     */
    const voucherCodeUsed = 2;
    /**
     * 过期
     * @var unknown
     */
    const voucherCodeExpired = 3;
    /**
     * 订单状态交易关闭
     * @var unknown
     */
    const orderClose = 0;

    /**
     * 订单被用户删除
     * @var unknown
     */
    const orderUserDelete = 1;
    
    /**
     * 定单超时常量
     * @var unknown
     */
    const passThreeDay = 259200;
    /**
     * 定单状态待支付
     * @var unknown
     */
    const orderWaitPay = 10;

    /**
     * 定单状态待发货
     * @var unknown
     */
    const orderWaitSend = 20;

    /**
     * 定单状态待收货
     * @var unknown
     */
    const orderWaitRecieve = 30;

    /**
     * 定单状态待评价
     * @var unknown
     */
    const orderEvaluate = 40;
    const orderUnEvaluate = 1;
    const orderTwiceEvaluate = 2;
    const notEvaluate = 0;
    const orderCountBegin = 0;

    /**
     * 定单状态已清关
     * @var unknown
     */
    const orderClear = 80;
    /**
     * 定单售后状态
     * @var unknown
     */
    const orderChecking = 1;
    const orderCheckingPass = 2;

    const orderCheckinUnPass = 3;

    /**
     * 退款延迟天数
     * @var unknown
     */
    const orderRefundDays = 7;
    /**
     * 退款默认原因
     * @var unknown
     */
    const refundReason = 101;

    /**
     * 退款拍错
     * @var unknown
     */
    const refundWrong = 99;

    /**
     * 退货退款类型
     * @var unknown
     */
    const refundReturnType = 2;
    /**
     * 退款类型
     * @var unknown
     */
    const refundType = 1;

    /**
     * 认证状态
     * @var unknown
     */
    const authenticationPass = 1;
    const authenticationUnPass = 0;

    /**
     * 签到
     * @var unknown
     */
    const signAddPoint = 1;
    const addPointOption = 1;

    const signRemindOn = 1;
    const signRemindOff = 0;
    /**
     * 确认订单获得积分类型
     * @var unknown
     */

    const confirmOrderPoints = 2;
    /**
     *消费
     * @var unknown
     */
    const reducePoints = -1;
    const addPoints = 1;

    /**
     * 身份证验证错误级别
     * @var unknown
     */
    const cardIdWrong = '206500';
    const notExistCardId = '206501';
    const maintenance = '206502';
    const net_error = '10024';


    /**
     * 小时常量
     * @var unknown
     */
    const oneHour = '3600';
    const twoHour = '7200';
    const halfHour = '1800';

    /**
     * 未付款订单关闭时间
     * @var unknown
     */

    const  orderEnd = '7200';

    /**
     * 分页条数
     * @var unknown
     */
    const productCategroiesPage = 10;

    /**
     * 分页起始页
     * @var unknown
     */
    const beginPage = 1;


    /**
     * 商品展示规格
     * @var unknown
     */
    const promotion_zero = 0;
    /**
     *商品展示规格
     * @var unknown
     */
    const promotion_one = 1;
    /**
     *物流状态信息
     * @var unknown
     */
    //在途中
    const intransit = 0;
    //揽件
    const embrace = 1;
    //疑难
    const difficult = 2;
    //签收
    const recieveSign = 3;
    //退签
    const returnSign = 4;
    //派件
    const sendPieces = 5;
    //退回
    const returnBack = 6;


    /**
     * 针对注册用户获取优惠码
     * @var unknown
     */
    const voucherProvideScopeRegUser = 1;

    /**
     * 所有人员获取优惠码
     * @var unknown
     */
    const voucherProvideScopeAllUser = 2;

    /**
     * 优惠劵使用范围
     */
    const voucherUseScopeClassify = 1;    //分类专属优惠劵

    const voucherUseScopeAllCourt = 2;   //全场通用优惠劵

    const voucherUseScopeSingle = 3;     //单品专属优惠劵

    const voucherClassifyExcludeBrand = 1; //有不可使用分类专属优惠券的品牌

    const voucherClassifyExcludeGoods = 1; //有不可使用分类专属优惠券的单品

    const voucherClassifyNoExcludeBrand = 0; //有不可使用分类专属优惠券的品牌

    const voucherClassifyNoExcludeGoods = 0; //有不可使用分类专属优惠券的单品	


    /**
     * 未填写
     * @var integer
     */
    const sexNo = 1;
    /**
     * 男
     * @var unknown
     */
    const sexMale = 2;
    /**
     * 女
     * @var unknown
     */
    const sexFemale = 3;

    /**
     * 绑定手机
     * @var unknown
     */
    const bindMobilePhone = 1;

    /**
     * 待审核
     * @var integer
     */
    const waittingAduit = 0;

    /**
     * 审核通过
     * @var integer
     */
    const aduitPass = 1;

    /**
     * 审核不通过
     * @var integer
     */
    const aduitNoPass = 2;
	
	/**
	 * 确认收货
	 *
	 */
	const confirmReceipt = 3;
	/**
	 * 其它
	 */
	const aduitOther = 4;
	
    /**
     * 删除
     * @var integer
     */
    const delete = 3;

    /**
     * baner在对应的网站显示
     * @var unknown
     */
    const bannerSiteShow = 1;

    /**
     * banner 在对应的网站不显示
     * @var unknown
     */
    const bannerSiteNoShow = 0;

    /**
     * 普通点击链接
     * @var unknown
     */
    const bannerTypeNormal = 0;

    /**
     * banner 限时活动
     * @var unknown
     */
    const bannerTypeXS = 1;

    /**
     * 推荐商品显示
     * @var unknown
     */
    const recommendItemsShow = 1;

    /**
     * 推荐商品不显示
     * @var unknown
     */
    const recommendItemsNoShow = 0;

    const recommendGoodsId = 'goods_id';
    const recommendGoodsCommonId = 'goods_common_id';

    /**
     * 点赞
     * @var integer
     */
    const recommendDig = 1;
    /**
     * 点赞取消
     * @var unknown
     */
    const recommendDigCancel = 0;

    /**
     * 草稿箱推荐商品
     * @var integer
     */
    const recommendDraft = -1;

    /**
     * 发布退回
     * @var integer
     */
    const publishBack = 4;

    /**
     * 垃圾箱
     * @var integer
     */
    const rubbish = 5;

    /**
     * 不分配组
     * @var integer
     */
    const zeroGroup = 0;

    /**
     * baner框架在对应的网站显示
     * @var integer
     */
    const frameSiteShow = 1;

    /**
     * baner框架在对应的网站不显示
     * @var integer
     */
    const frameSiteNoShow = 0;


    /**
     * banner框架显示
     * @var integer
     */
    const frameShow = 1;


    /**
     * banner框架不显示
     * @var integer
     */
    const frameNoShow = 0;


    /**
     * pc,wap,ios,android首页banner框架
     * @var unknown
     */
    const homeBannerFrame = 1;

    /**
     * pc,wap,ios,android首页banner框架2(抢购和折扣专区之间)
     * @var unknown
     */
    const homeBannerFrameTwo = 3;
   
   
    /**
     * 积分商城首页Banner框架
     * @var unknown
     */
    const integralMallBannerFrame = 2;


    /**
     * 用户账号类型是否正常
     */
    const isNormal = 1;

    /**
     * 用户账号类型是否冻结
     */
    const isFreeze = 0;

    /**
     * b端用户
     */
    const businessUser = "ds";

    /**
     * c端用户
     */
    const cTerminalUser = "normal";

    /**
     * b端用户
     */
    const businessUserStr = "B端用户";

    /**
     * c端用户
     */
    const cTerminalUserStr = "普通";

    /**
     * b端代发用户
     */
    const daifaUser = 1;

    /**
     * b端代发用户
     */
    const shuadanUser = 2;

    /**
     * b端未付款订单关闭时间
     * @var unknown
     */

    const  businessOrderEnd = '600';

    /**
     * b端佣金未结算
     */
    const commissionUnsettled = 0;

    /**
     * b端佣金已结算
     */
    const commissionAlreadySettled = 1;

    /**
     * b端不计佣金
     */        
    const commissionAbnormal = 3;

    /**
     * 积分商城商品类型
     */
    const coupon = 0; // 优惠券
    const physicalCommodity = 1; // 实物
    const virtualCode = 2; // 虚拟卡

    /**
     * 积分商城商品状态
     */
    const integralGoodsOffShelves = 0; // 积分商品下架

    const integralGoodsShelves = 1; // 积分商品上架
    /**
     * 宝付微信APP支付交易
     */
    const bfWxAppPay = "04";

    /**
     * 宝付支付宝APP支付交易
     */
    const bfAliAppPay = "08";

    /**
     * 支付宝扫码支付
     */
    const bfAliCodePay = "06";

    /**
     * 支付宝扫码API支付
     */
    const bfAliCodeApiPay = "21";

    /**
     * 微信扫码支付
     */
    const bfWxCodePay = "01";

    /**
     * 微信扫码API支付
     */
    const bfWxCodeApiPay = "05";

    /**
     * 宝付扫码支付
     */
    const bfCodePay = "10199";

    /**
     * 宝付支付
     */
    const bfPay = "20199";

    /**
     * 宝付成功回复代码
     */
    const bfRespSuccessCode = "0000";

    /**
     * 登录
     */
    const login = 1;

    /**
     * 注册
     */
    const register = 2;
    /**
     * 绑定手机账号
     */
    const bindPhone = 6;

    const pageSize = 10;

    const postageEnable       =  1;                     //邮费启用

    const postageDisabled       =  0;                     //邮费禁用
    /**
     * 聚易
     */
    const jyBaseTransferId = 1;

    /**
     * 一番街
     */
    const yfjBaseTransferId = 2;

    const jyCopCode = "35012619BA";

    const yfjCopCode = "35012619HL";
    /**
     * 启用
     */
    const signRecommendEnable = 1;

    /**
     * 关闭
     */
    const signRecommendDisabled = 0;

    const bonded  = 1;           //保税

    const directMail  = 2;           //直邮

    const controlPriceEnable = 1;    //控价启用

    const controlPriceDisable = 2;   //控价启用

    const companyEnable = 1;    //公司启用

    const companyDisable = 0;   //公司禁用

    const isBasicsGoodsService = 1; // 是基础的服务配置
    
    const dcLogisticsCode = '350196T002';
    const dcLogisticsName = '福州乔韵达速递有限公司';
    const dcLogisticsId = 4;

    /**
     * 首页模块
     */
    const banner = 1; // 首页banner

    const notify = 2; // 公告

    const panicBuying = 3; // 抢购

    const timeLimitPromotion = 4; // 限时活动|折扣专区

    const specialSubject = 5; // 精选专题

    const goodsRecommend = 6; // 单品推荐

    const newItems = 7; // 新品速递

    const specialSubjectList = 8; // 精选专题列表

    const topBrand = 9; // 品牌专区|热门品牌

    const show = 1; // 显示

    const noShow = 0; // 不显示

    const quickConsignee = 1; // 快捷收货人

    const bUserDelete = 1; // b端用户被删除

    const bUserNoDelete = 0; // b端用户未删除

    const passwordReset = 1;// b端用户密码重置

    const passwordIsNotReset = 0; // 用户密码未重置

    const businessPublicAccount = '13111111111'; // b端商务经理公共账号

	const directMailText = '【直邮】';

    const requestPhoneBind = 1;  //需要绑定手机提示

    const notRequestPhoneBind = 0;  //不需求绑定手机提示

    /**
     * C端测试号的验证码
     */
    const  cTestPhoneCaptcha = 623326;
    /**
     * C端测试号
     */
    const  cTestPhone = 18905017267;

    const normalSource = 0; //B端商品购买来源  普通购买

    const lockedSource = 1; //B端商品购买来源  云仓购买

    const minIncentiveGoldCoefficient = 0;

    const maxIncentiveGoldCoefficient = 0.5;

    /**
     * 聚易仓
     */
    const jyEntrepotId = 1;

    /**
     * 一番街仓
     */
    const yfjEntrepotId = 2;

    /**
     * 日本仓
     */
    const directMailEntrepotId = 3;
	
	/**
	 * 现货仓
	 */
	const spotEntrepotId = 4;

    /**
     * c端/b端 订单激励金状态
     */
    const incentiveGoldUnsettled = 0; // 未核销
    const incentiveGoldAlreadySettled = 1; // 已核销
	
	/**
	 * 售后
	 */
	const pendingReview = 1;//待审核
	const confirmReturn = 2;//确认回寄
	
	const preSaleExported = 1;//售后已导出
	


    /**
     * 直邮订单表状态
     */
    const dirCancelOrder = 1;//取消订单
    const dirWaitingForDelivery = 2;//等待物流单
    const dirWaitingForPicking = 3;//等待配货
    const dirWaitingForAccessories =4;//等待揽件
    const dirFinished =5;//揽件完成
    const dirUnPaied = 6;//未付款

    const normalWarnningPrice = 1;//价格预警 正常
    const abnormalWarnningPrice = 2;//价格预警 异常

    /**
     * 新后台权限类型常数
     */
    const newAdminPermissionType = 0;
    /**
     * 旧后台权限类型常数
     */
    const oldAdminPermissionType = 1;
    /**
     * 渠道端权限类型常数
     */
    const drpPermissionType = 2;

    /** -----------------------渠道端用户等级----------------------- */
    /**
     * 渠道端普通用户
     */
    const drpUserNormalRank = 1;
    /**
     * 渠道端高级用户
     */
    const drpUserHigherRank = 2;
    /**
     * 渠道端超级用户
     */
    const drpUserSuperRank = 3;
    
    
    
    /**-------营销后台的上下架-----------**/
    const marketIsShelf = 1;//上架
	const marketNoShelf = 2;//下架

    /**
     * 新日淘图片store_id
     */
    const bWxGoodsStoreId = 2;

    /**
     * 新日淘小程序图片链接图片
    */
    const bWxLinkImage = 1;

    /**
     * 新日淘小程序图片详情图片
     */
    const bWxDetailImage = 2;
	/**
	 * 广告类型
	 */
    const activityAdv = 1;//活动
    const goodsAdv = 2;//商品
	const wxH5PageAdv = 3;//h5页面
	const winningGameAdv = 4;//h5页面

	/**
	 * 是否为快捷联系地址
	 */
	const isCommonAdress = 1;//是
	
	
	/**
	 * 地址的类型
	 */
	const universityType = 1; //高校
	const communityType = 2;//社区
	const mallType = 3;//商场

    /**
     * 小程序用户身份验证通过
     */
     
	const bWxCardVerifyPassed = 1;
	
	/**
	 * 分配
	 * @var unknown
	 */
	const cloudAllot = 1;
	/**
	 * 回收
	 * @var unknown
	 */
	const cloudRecovery = 2;

	/**
     * 渠道端超级用户18850422484
     */
	const drpSuperAccountForWx = '18850422484';

    /**
     * 渠道端超级用户18850422484
     */
    const drpSuperAccountIdForWx = 16488;


    /**
     * 现货入库单类型
     */
    const spotWarehouseEntryType = 2;
    
    //欣日淘数据提醒的上线时间
    const xinRiTaoOnlineTime = '2018-10-31 00:00:00';
    
    /**
     * 小程序的处理进度
     */
    
    const wxAppFeedbackUnProcess = 0;//未处理
    const wxAppFeedbackProcessed = 1;//已处理

    /**
     * 营销后台的活动类型
     */
    const marketSpecialPrice = 1;//特价
    const marketBoost = 2;//助力
    const marketLimit = 3;//限购
    /**
     * 小程序的订单派送方式
     */
    const wxAppGetOrderByXinRitaoId = 98;//欣日淘派送的快递Id
    const wxAppGetOrderBySelf =  99;//自提的Id
    
}
