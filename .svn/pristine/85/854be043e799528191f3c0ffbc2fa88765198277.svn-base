<?php
/**
 *首页
 *
 *一个public 就是一个action 
 *
 *helper 是工具类 这个可以全局去使用，采用的都是工厂单体模式。
 */
class HomeController extends ZcController{
/**
 * 
 * @var unknown $categorieService
 */
    private $categorieService;
    /**
     * 
     * @var bannerService
     */
    private $bannerService;
    /**
     * 
     * @var unknown
     */
    private $productService;
    /**
     * 
     * @var XrtGoodsService
     */
    private $xrtGoodsService;
    /**
     * 
     * @var unknown
     */
    private $urlHelper;
    private $codeMessageHelper;
    private $requestTime;

	public function __construct($route) {

		parent::__construct ( $route );

		//对业务层的service 进行初始化 ， 初始化为工厂单体模式。
		$this -> categorieService = ServiceFactory::getCategorieService();
		$this -> bannerService = ServiceFactory::getBannerService();
		$this -> productService = ServiceFactory::getProductService();
        $this -> xrtGoodsService = ServiceFactory::getXrtGoodsService();

        $this->codeMessageHelper = HelperFactory::getCodeMessageHelper();
		$this->urlHelper = HelperFactory::getUrlHelper();

		$this->requestTime = time();

	}

    /**
     * 小程序首页
     */
	public function index () {
        /** ------------------banner图start------------------ */
        $bannerArray = $this->bannerService->getHomePageWxAdvList();
        $bannerArray = $this->bannerArrayFilter($bannerArray);
        // var_dump($bannerArray);exit;
        /** ------------------banner图end------------------ */

        /** ------------------限时特价start------------------ */

        $activityArray = $this->xrtGoodsService->getValidActivityInfoWithGoodsInfo();
        $activityArray = $this->activityArrayFilter($activityArray);
        // var_dump($activityArray);

        /** ------------------限时特价end------------------ */

        $returnData = [];
        $returnData['banner_box_info'] = $bannerArray;
        $returnData['activity_box_info'] = $activityArray;
		
        $output = $this -> codeMessageHelper -> getCodeMessageByCode(AppConst::requestSuccess, $this->requestTime, $returnData);
		
        $this->renderJSONAPI($output);

	}

	private function activityArrayFilter($activityArray)
    {
        $activityArrayFilter = [];
        foreach ($activityArray as $activityInfo) {
            $tmp = [];
            $tmp['activity_id'] = $activityInfo['activity_id'];
            $tmp['activity_image'] = $activityInfo['activity_image_url'];
            $tmp['remaining_time'] = $activityInfo['remaining_time'];
            foreach ($activityInfo['activity_goods_array'] as $goodsInfo) {
                $tmpGoods = [];
                $tmpGoods['goods_id'] = $goodsInfo['goods_id'];
	            if($goodsInfo['type'] == XianshiConst::marketBoost){
		            $formatPrice = $goodsInfo['target_price'];
	            }else{
		            $formatPrice = $goodsInfo['activity_price'];
	            }
                $tmpGoods['activity_price'] = $formatPrice;
                $tmpGoods['goods_image'] = $goodsInfo['goods_image_url'];
                $tmp['product_info_list'][] = $tmpGoods;
            }
            $activityArrayFilter[] = $tmp;
        }
        return $activityArrayFilter;
    }

    private function bannerArrayFilter($bannerArray)
    {
        $bannerArrayFilter = [];
        foreach ($bannerArray as $bannerInfo) {
            $tmp = [];
            $tmp['adv_url_type'] = $bannerInfo['adv_url_type'];
            $tmp['adv_url_id'] = $bannerInfo['adv_url_id'];
            $tmp['adv_image'] = $bannerInfo['adv_image_url'];
            $bannerArrayFilter[] = $tmp;
        }

        return $bannerArrayFilter;
    }
	
	
	/**扫码的接口
	 *
	 */
    public function changeGoodsShapeCodeToGoodsId(){
	    //$data = json_encode(['goods_shape_code'=> '4901070125258']);
    	$data = $this-> urlHelper -> getValue('data');
    	$data =json_decode($data,true);

	    $goodsShapeCode = $data['goods_shape_code'];
	    //$goodsShapeCode = '6901236341865';
		if(!$goodsShapeCode){
			$output = $this -> codeMessageHelper -> getCodeMessageByCode(ErrorCodeConst::customizeMsg,$this->requestTime,'参数错误');
			$this -> renderJSONAPI($output);
		}
	 
		//接着根据条形码查询商品的id
	    $goodsId = $this -> productService -> GoodsShapeCodeToGoodsId($goodsShapeCode);
	    $goodsInfo ='';
		if($goodsId){
			//在小程序可以搜到
			$condition['goodsId'] = $goodsId;
			
			$goodsInfo = $this -> productService -> getWxappGoodsInfoByCondition($condition, $orderBy='', $page=1, $pageSize=30);
		}
	 
		$return = [];
		if(!$goodsInfo){
			$return['status'] = 2;//未找到结果
		}else{
			$return['status'] = 1;//找到结果
			$return['goods_id'] = $goodsId;
		}
	    $output = $this -> codeMessageHelper -> getCodeMessageByCode(AppConst::requestSuccess, $this->requestTime, $return);
	
	    $this->renderJSONAPI($output);
		
    }








}

