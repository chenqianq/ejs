<?php
/**
 * 提供搜索引擎的接口
 * 
 *
 */
class SearchUtil {
	private $solr;
	private $searchHelper;
	private $logHelper;
	private $goodsPriceStorageService;

	public function __construct() {
		require_once(DIR_FS_DOCUMENT_ROOT. 'classes/extends/Solr/Service.php');
		require_once(DIR_FS_DOCUMENT_ROOT.'/class_ignore/services/class.GoodsPriceStorageService.php');

		$this->solr = new Apache_Solr_Service(SOLR_HOST, SOLR_PORT, SOLR_PATH);//
		//搜索相关的helper
		$this->searchHelper = HelperFactory::getSearchHelper();
		//日志相关的helper
		$this->logHelper = LogFactory::getBizLog('solr/search.log');
		$this -> goodsPriceStorageService = new GoodsPriceStorageService();
	}
	/**
	 * 
	 */
	
	public function testPing() {
		for ($i = 0; $i < 3; $i++) {
			if ($this->solr->ping()) {
				return true;
			}
		}
		return false;
	}
	
	private function getMicroTime() {
		$start_time = explode ( ' ', microtime () );
		return $start_time;
	}
	
	private function getUsedTime($start_time = array()) {
		$time_end = $this->getMicroTime ();
		$use_time = $time_end [1] + $time_end [0] - $start_time [1] - $start_time [0];
		return $use_time;
	}
	
	public function search( $keywords, $page = 1, $pageSize = 36, $filterQuery = array(), $facet = array(), $sort = '', $refind = false, $refindMixResult = 4) {
	    
		if (! $this->testPing ()) {
			return false;
		}
		
		$params = $this->buildSearchParams ($filterQuery, $facet, $sort,  !$refind);
		
		try {
			
			$start_time = $this->getMicroTime ();
			
			// 搜索开始
			$seachResult = $this->solr->search ( urldecode ( $keywords ), ($page - 1) * $pageSize, $pageSize, $params );
			
			$responseJSON = $seachResult->getRawResponse ();
			
			$responseObj = json_decode ( $responseJSON );
			
			$totalNum = $responseObj->response->numFound;
            
			// 时间信息
			$used_time = $this->getUsedTime ( $start_time );
			$logSort = (! empty ( $sort ) ? ' / ' . $sort : '');
			$logfilterQuery = (! empty ( $filterQuery ) ? ' / ' . implode ( ' - ', $filterQuery ) : '');
			$logData = urldecode ( $keywords ) . ' / (' . $totalNum . ') / ' . $used_time . $logfilterQuery . $logSort;
			$this->logHelper->info ( $logData, 'success' );
		} catch ( Exception $ex ) {
		    
			$this->logHelper->error ( $ex, 'fail' );
		}
		
		$ret = array();
		$ret['totalNum']       = $this->processTotalNum($responseObj);          //处理numFound;
		Zc::G('beging util search'.microtime(true));
		$ret['docs']           = $this->processDocs($responseObj);//处理docs;
		Zc::G('end util search'.microtime(true));
		//$ret['facetCat']       = $this->processFacetCat($responseObj);          //处理facet:Categories;
		//$ret['facetAttrM']     = $this->processFacetAttrM($responseObj);        //处理facet:多重属性;
		//$ret['facetWarehouse'] = $this->processFacetWarehouse($responseObj);    //处理facet:仓库
		
		//$ret['facetPrice']     = $this->processFacetPrice($responseObj );       //处理价格:价格
		return $ret;
	}
	 
	
	
	/**
	 * 获取搜索结果中的商品ID
	 * @param object $responseObj
	 * @return array
	 */
	private function processFacetProductId($responseObj) {
		$output = array();
		foreach ($responseObj->highlighting as $k => $v) {
			$output[] = (int)$k;
		}
		return $output;
	}
	
	
	/**
	 * 取得所有的记录数
	 * @param obj $responseObj
	 * @return int 总记录数
	 */
	private function processTotalNum($responseObj) {
		return $responseObj->response->numFound;
	}
	
	
	/**
	 * 处理商品的详细JSON数据
	 * @param string $countryCode
	 * @param obj $responseObj
	 */
	private function processDocs($responseObj) {
		$search_respons = array();
		
		$goodsIdArray = [];
		foreach ($responseObj->response->docs as $resp) {
			$goodsId = (int)$resp->goods_id;
			
			$goodsName = '';
			$products_description = $resp->products_description;
			if (empty($responseObj->highlighting->$goodsId->goods_name[0])) {
			    $goodsName = $resp->goods_name;
			
			}
			else {
			    $goodsName = $responseObj->highlighting->$goodsId->goods_name[0];
			}
			  
			$search_respons[$goodsId] = array(
					'goods_id'           => $goodsId,
					'goods_name'         => $resp->entrepot_type == YfjConst::directMail  ? YfjConst::directMailText . $goodsName :$goodsName, 
					'goods_jingle'        => $resp->goods_jingle,
					'goods_commonid'        => $resp->goods_commonid,
					'gc_id'=> $resp->gc_id,
					'brand_id'   => $resp->brand_id,
					'brand_name'  => $resp->brand_name,
					'goods_serial'   => $resp->goods_serial,
					// 'goods_storage_alarm'          => $resp->goods_storage_alarm,
					'goods_salenum'          => $resp->goods_salenum,
					'goods_collect'          => $resp->goods_collect,
					'goods_storage'     => $resp->goods_storage,
					'goods_state'            => $resp->goods_state,
			    'goods_verify'            => $resp->goods_verify,
			    // 'goods_spec_value_id'            => $resp->goods_spec_value_id,
			    'goods_shape_code'            => $resp->goods_shape_code,
			    'goods_link_name'            => $resp->goods_link_name,
			    'goods_spec_name'            => $resp->goods_spec_name,
			    'gc_id_top'            => $resp->gc_id_top,
			    // 'delivery_warehouse'            => $resp->delivery_warehouse,
			    'gmt_addtime'            => $resp->gmt_addtime,
			    'gmt_edittime'            => $resp->gmt_edittime,
			    'goods_price_sort'            => $resp->goods_price_sort,
			    'final_price_sort'            => $resp->final_price_sort,
			    // 'goods_common_name'            => $resp->goods_common_name,
			    // 'goods_common_state'            => $resp->goods_common_state,
			    // 'goods_common_verify'            => $resp->goods_common_verify,
			    // 'goods_common_gmt_addtime'            => $resp->goods_common_gmt_addtime,
			    // 'goods_common_gmt_selltime'            => $resp->goods_common_gmt_selltime,
			    // 'goods_common_gmt_edittime'            => $resp->goods_common_gmt_edittime,
			    // 'goods_common_main_image_url'            => $resp->goods_common_main_image_url,
			    'goods_image_url'            => $resp->goods_image_url,
			    // 'goods_common_name'            => $resp->goods_common_name,
			    // 'goods_common_name'            => $resp->goods_common_name,
			    // 'goods_common_name'            => $resp->goods_common_name,
					'products_attr'         => $resp->attr, 
					'goods_attr'      => $resp->attr,
					
					'entrepot_type'      => $resp->entrepot_type,
					'link_number'      => $resp->link_number,
					'goods_format_tax'      => $resp->goods_format_tax,
					'goods_seo_keywords'      => $resp->goods_seo_keywords,
					'goods_seo_description'      => $resp->goods_seo_description,
			);
			$goodsIdArray[$goodsId] = $goodsId;
		}
		
		$goodsList =  DaoFactory::getProductDao() -> getProductsBaseMessageByIds($goodsIdArray);
		$prices = $this -> goodsPriceStorageService -> getPrice($goodsList);

		// $prices = ServiceFactory::getProductService ()->getPriceByProudctsIds($goodsList);
		foreach ( $search_respons as $goodsId => $product ) {
 		    if(!$prices[$goodsId]){
                 continue;
            }
			$search_respons[$goodsId]['market_price']            = $prices[$goodsId]['list_price'];
			$search_respons[$goodsId]['list_price']                = $prices[$goodsId]['list_price'];
			$search_respons[$goodsId]['final_price']                = $prices[$goodsId]['final_price'];
			$search_respons[$goodsId]['sale_num']                 = $prices[$goodsId]['sale_num'];
			$search_respons[$goodsId]['storage_alarm']         = $prices[$goodsId]['storage_alarm']?:0;
			$search_respons[$goodsId]['storage']                = $prices[$goodsId]['storage'];
			$search_respons[$goodsId]['tax']                = $prices[$goodsId]['tax'];
			$search_respons[$goodsId]['format_tax']                = $prices[$goodsId]['format_tax'];
			$search_respons[$goodsId]['goods_state']              = $prices[$goodsId]['goods_state'];
			$search_respons[$goodsId]['format_promotion_type'] = $prices[$goodsId]['format_promotion_type'];
			$search_respons[$goodsId]['format_market_price']    = $prices[$goodsId]['format_list_price'];
			$search_respons[$goodsId]['format_list_price']    = $prices[$goodsId]['format_list_price'];
			$search_respons[$goodsId]['format_final_price']    = $prices[$goodsId]['format_final_price'];
			$search_respons[$goodsId]['label_array']    = $prices[$goodsId]['label_array'];
			$search_respons[$goodsId]['labelArray']    = $prices[$goodsId]['labelArray'];
			$search_respons[$goodsId]['higher_limit']    = $prices[$goodsId]['higher_limit'];
			$search_respons[$goodsId]['lower_limit']    = $prices[$goodsId]['lower_limit'];
			$search_respons[$goodsId]['label_type']    = $prices[$goodsId]['label_type'];
			$search_respons[$goodsId]['couponLabel']    = $prices[$goodsId]['couponLabel'];
			$search_respons[$goodsId]['snap_up_label']    = $prices[$goodsId]['snap_up_label'];
			$search_respons[$goodsId]['pre_direct_mail_label']    = $prices[$goodsId]['pre_direct_mail_label'];
			
			$search_respons[$goodsId]['storage_label']    = $prices[$goodsId]['storage_label'];
			$search_respons[$goodsId]['is_free']    = $prices[$goodsId]['is_free'];
			$search_respons[$goodsId]['tax_price']    = $prices[$goodsId]['tax_price'];
			$search_respons[$goodsId]['format_tax_price']    = $prices[$goodsId]['format_tax_price'];
			$search_respons[$goodsId]['single_tax_price']    = $prices[$goodsId]['single_tax_price'];
			$search_respons[$goodsId]['promotion_type']    = $prices[$goodsId]['promotion_type'];
			//$search_respons[$goodsId]['end_time']    = '';
			if( $prices[$goodsId]['group_message_array']['end_time'] ){
			    $search_respons[$goodsId]['end_time']    = date('Y-m-d H:i:s' , $prices[$goodsId]['group_message_array']['end_time']);
			}
			
			unset($goodsIdArray[$goodsId]);
		}
		 
		if( $goodsIdArray ){
		    foreach ( $goodsIdArray as $goodsId ){
		        unset($search_respons[$goodsId]);
		    }
		}
		
		return $search_respons;
	}
	 
	
	 
	
	/**
	 * 从搜索页面获取参数
	 * @param array $pageParams
	 * @param string $shippingCountryCode
	 */
	public function getFilterQueryFromSearchPage($pageParams, $shippingCountryCode, $includePrice = true) {
		global $currencies;
		
		$filterQuery = array();
		
		//分类
		if (!empty($pageParams['typeid'])) {
			list($top_categories_id, $master_categories_id) = explode(':', $pageParams['typeid']);
			if($top_categories_id > 0) {
				$filterQuery[] = 'top_categories_id:' . (int)$top_categories_id;
			}
			if($master_categories_id > 0 ) {
				$filterQuery[] = 'master_categories_id:' . (int)$master_categories_id;
			}
                        
		}
		
		//属性
		foreach ($pageParams as $k => $v) {
			if (0 === strpos($k, 'attr_m_')) {//收集属性参数
				$filterQuery[] = $k . '_' . $master_categories_id . ':(' . str_replace('-', ' ', $v) . ')';
			}
		}
		
		//仓库筛选
		if (!empty($pageParams['warehouse'])) {
			switch (strtolower($pageParams['warehouse'])) {
				case 'us': $filterQuery[] = 'us_warehouse:1'; break;
				case 'uk': $filterQuery[] = 'uk_warehouse:1'; break;
			}
		}
		
		if($includePrice){
    		//价格条件筛选
    		if (!empty($pageParams['min_price']) || !empty($pageParams['max_price'])) {
    			$orderField = ServiceFactory::getCountryService()->getProductsOrderPriceFieldByCountryCode($shippingCountryCode);
    			
    			$minprice = (float)str_replace(',', '', $pageParams['min_price']);
    			$maxprice = (float)str_replace(',', '', $pageParams['max_price']);
                        
                        
    			
    			$minusd_price = preg_replace('/[^0-9\.]/', '', ($minprice > 0 ? $currencies->value($minprice, true, 'USD') : 0));
    			$maxusd_price = preg_replace('/[^0-9\.]/', '', ($maxprice > 0 ? $currencies->value($maxprice, true, 'USD') : 1000));
    			

    			$filterQuery[] = $orderField . ':[' . $minusd_price . ' TO ' . $maxusd_price . ']';
    		}
		}
		return $filterQuery;
	}
	
	/**
	 * 从类目商品页面获取参数
	 * @param array $pageParams
	 * @param string $shippingCountryCode
	 */
	public function getFilterQueryFromCategoryProductPage($pageParams, $shippingCountryCode) {
		global $currencies;
		
		$filterQuery = array();

		//属性筛选
		$attrs = array();
		if (!empty($pageParams['type_spec'])) {
			foreach (explode('-', $pageParams['type_spec']) as $v) {
				list($v_spec_type_id, $v_spec_id) = explode('_', $v);
				$attrs[$v_spec_type_id][] = $v_spec_id;
			}
			
			if (isset($pageParams['cPath'])) {
				$categories_id = end(explode('_', $pageParams['cPath']));
			}
			foreach ($attrs as $k => $v) {
				$filterQuery[] = 'attr_m_' . $k . '_' . $categories_id . ':(' . implode(' ', $v) . ')';
			}
		}
		
		
		//仓库筛选
		if (!empty($pageParams['warehouse'])) {
			switch (strtolower($pageParams['warehouse'])) {
				case 'us': $filterQuery[] = 'us_warehouse:1'; break;
				case 'uk': $filterQuery[] = 'uk_warehouse:1'; break;
			}
		}
		
		//价格条件筛选
		if (!empty($pageParams['min_price']) || !empty($pageParams['max_price'])) {
			$orderField = ServiceFactory::getCountryService()->getProductsOrderPriceFieldByCountryCode($shippingCountryCode);
			
			$minprice = (float)str_replace(',', '', $pageParams['min_price']);
			$maxprice = (float)str_replace(',', '', $pageParams['max_price']);
			
			$minusd_price = preg_replace('/[^0-9\.]/', '', ($minprice > 0 ? $currencies->value($minprice, true, 'USD') : 0));
			$maxusd_price = preg_replace('/[^0-9\.]/', '', ($maxprice > 0 ? $currencies->value($maxprice, true, 'USD') : 1000));
			
			$filterQuery[] = $orderField . ':[' . $minusd_price . ' TO ' . $maxusd_price . ']';
		}
		
		return $filterQuery;
	}
	 
	 
	
	/**
	 * 组合查询参数
	 * 
	 * @param string $currentLanguageCode
	 * @param array $filterQuery
	 * @param array $facet
	 * @param string $sort
	 * @return array
	 * 
	 * @example
	 * $fqParams[] = 'top_categories_id:3148';
	 * $fqParams[] = 'master_categories_id:3239';
	 * $fqParams[] = 'products_price:[47 TO 50]';
	 * $fqParams[] = 'warehouse:1';
	 * $fqParams[] = 'attr_m_1034:(6038 6039)';
	 */
	private function buildSearchParams( $filterQuery, $facet, $sort, $includeDescription = true) {
		$params = array (
				'defType'       => 'dismax',
				'hl'            => 'true',
				'facet'         => 'true',
				'facet.limit'   => '1000',
				'hl.simple.pre' => '<b style="color:#FF7F05;">',
				'hl.simple.post'=> '</b>',
				'cache'         => 'false',
				'mm'            => '80%',
				'q.alt'         => '*:*',
		);
		
		$params['hl.fl'] = 'goods_name';
		
		$params['qf'] = $this->getSearchFields( $includeDescription);
		
		$params['facet.field'][] = 'gc_id_top';
		$params['facet.field'][] = 'gc_id';
		$params['f.gc_id_top.facet.mincount'] = '1';
		$params['f.gc_id.facet.mincount'] = '1';
		
		// 分析参数
		$category_id = 0;
		if (is_array($filterQuery)) {
			foreach ($filterQuery as $v) {
				if (0 === strpos($v, 'attr_')) {
				    //{goods_commonid}_{attr_id}_{attr_value_id}
					preg_match('/attr_(\d+)_(\d+)_(\d+)/', $v, $out);
					$goodsCommonId = $out[1];
					$v = '{!tag=' . substr($v, 0, strpos($v, ':')) . '}' . $v;
				}
				/* elseif (0 === strpos($v, 'gc_id')) {
					list(, $category_id) = explode(':', $v);
				} */
				$params['fq'][] = $v;
			}
		}
		
		// 分层统计
		if ($goodsCommonId) {
			$rows = $this -> getAttrByCommonId($goodsCommonId);
			foreach ($rows as $v) {
				$params['facet.field'][] = "{!ex=attr_{$v['goods_commonid']}_{$v['attr_id']}_{$v['attr_value_id']}}attr_{$v['goods_commonid']}_{$v['attr_id']}_{$v['attr_value_id']}";
				$params["f.attr_{$v['goods_commonid']}_{$v['attr_id']}_{$v['attr_value_id']}.facet.mincount"] = '1';
			}
		}
		//$params["f.attr_0_0_0.facet.mincount"] = '1';
		// 根据国家获取sold和价格分层的信息
		$geoFields = $this->getGeoParams();
		$params['facet.range'] = $geoFields['priceSorter'];
		$params['f.' . $geoFields['priceSorter'] . '.facet.range.start'][] = '0';
		$params['f.' . $geoFields['priceSorter'] . '.facet.range.end'][] = '1000';
		$params['f.' . $geoFields['priceSorter'] . '.facet.range.gap'][] = '1';
		$params['f.' . $geoFields['priceSorter'] . '.facet.mincount'][] = '1';
		
		// 排序
		$params['sort'] =   (empty($sort) ? 'goods_state desc , score desc, final_price_sort desc, goods_id desc' : $sort);
		return $params;
	}
	
	private function getAttrByCommonId($commonId){
	    $sql = "select * from " . TableConst::TABLE_GOODS_ATTR . " WHERE goods_commonid = " . (int)$commonId;
	    $attrArray = $this -> db -> getRows($sql,3600);
	    if( !$attrArray ){
	        return false;
	    }
	
	    $return = [];
	    foreach ( $attrArray as $attr ){
	        $sql = "select attr_value_name from " . TableConst::TABLE_ATTRIBUTE_VALUE . " WHERE  `attr_id` = ".$attr['attr_id']." AND `attr_value_id` = " . $attr['attr_value_id'];
	
	        $attrValueName = $this -> db -> getRow($sql,1800);
	        $tmp = [];
	        
	        $tmp['attr_value_name'] = $attrValueName['attr_value_name'];
	        $tmp['attr_id'] = $attr['attr_id'];
	        $tmp['attr_value_id'] = $attr['attr_value_id'];
	        $tmp['goods_commonid'] = $attr['goods_commonid'];
	        $return[$attr['goods_commonid']][] = $tmp; 
	    }
	
	    return $return;
	}
	
	/**
	 * 获取断货的地区
	 * @param string $countryCode
	 */
	public function getSoldOutZone($countryCode) {
		$geo_zone = $this->getGeoParamsByCountryCode($countryCode);
		return $geo_zone['soldOut'];
	}

	private function getGeoParams() { 
		$geoPrice = 'final_price_sort';
		return $resFields = array ( 
				'priceSorter' => $geoPrice 
		);
	}
	
	/**
	 * 根据当前语言选择要查询的字段
	 * @param string $currentLanguageCode
	 * @return string
	 */
	public function getSearchFields($includeDescription = true) {
	    return 'goods_name^34 goods_seo_keywords^34 parent_brand_name^8.5 brand_name^8.5 categories_name^8.5 label_*^3.5 goods_spec_name^2.4 goods_link_name^0.4 effect_features_*^0.2';
		
	}
	 
	/**
	 * 根据当前语言选择要放回字段排序
	 * @param string $order_type
	 * @return string
	 */
	public function getSearchSort($order_type = 0 , $orderValue) {
	    
	    switch ($order_type) {
	        case 'sales' :
	            if( $orderValue == 'desc' ){
	                $search_sort = 'goods_verify asc,goods_state desc , goods_salenum desc, score desc, gmt_edittime desc, gmt_addtime desc , goods_id desc';
	            }
	            else{
	                $search_sort = 'goods_verify asc,goods_state desc ,goods_salenum asc, score desc, gmt_edittime desc, gmt_addtime desc , goods_id desc';
	            } 
	            break;
	        case 'click' :
	            if( $orderValue == 'desc' ){
	                $search_sort = 'goods_verify asc,goods_state desc ,goods_salenum desc, score desc, gmt_edittime desc, gmt_addtime desc , goods_id desc';
	            }
	            else{
	                $search_sort = 'goods_verify asc,goods_state desc ,goods_salenum asc, score desc, gmt_edittime desc, gmt_addtime desc , goods_id desc';
	            } 
	            break;
	        case 'price' :
	            if( $orderValue == 'desc' ){
	                $search_sort = 'goods_verify asc,goods_state desc ,final_price_sort desc, score desc, gmt_edittime desc, gmt_addtime desc , goods_id desc';
	            }
	            else{
	                $search_sort = 'goods_verify asc,goods_state desc,final_price_sort asc, score desc, gmt_edittime desc, gmt_addtime desc , goods_id desc';
	            } 
	            break;
	         
	        default :
	            $search_sort = 'goods_verify asc,goods_state desc , score desc, goods_salenum desc, gmt_edittime desc, gmt_addtime desc, final_price_sort desc';
	            break;
	    }
	    return $search_sort;
	}
	
	/**
	 * 发送邮件警告
	 */
	public function sendEmailWarn($content) {
		$account = array(
		);
		
		$text = ' ， 在 ' . date('Y-m-d H:i:s') . ' 搜索失败';;
		 
	}
	
	/***
	 * 联想提示
	 * @param unknown $keywords
	 * @return void|boolean
	 */
	public function suggest($keywords){
	    
	    if (! $this->testPing ()) {
	        return false;
	    }
	    
	    $suggestResult = $this->solr->suggest( urldecode ( $keywords ) );
	    $suggestXml = $suggestResult -> getRawResponse ();
	    
	    $suggestData = $this -> xmlToArray($suggestXml);
	    
	    if( !$suggestData || !$suggestData['lst'] || !$suggestData['lst'][1] || !$suggestData['lst'][1]['lst'] || !$suggestData['lst'][1]['lst'][0] || !$suggestData['lst'][1]['lst'][0]['lst'] || !$suggestData['lst'][1]['lst'][0]['lst']['arr']  || !$suggestData['lst'][1]['lst'][0]['lst']['arr']['str'] ){
	        return ;
	    }
	    
	    $suggestDataArray = $suggestData['lst'][1]['lst'][0]['lst']['arr']['str'];
	    $return = [];
	    
	    $i = 0;
	    foreach ( $suggestDataArray as $data ){
	        if( !$data ){
	            continue;
	        }
	        
	        list($suggest1,$suggest2) = explode('|', $data);
	        $preg = "/[\x{4e00}-\x{9fa5}]+/u";
	        preg_match_all($preg, $suggest1, $match);
	       
	        if( !$match || !$match[0] ){
	           /*  preg_match_all($preg, $suggest2, $match);
	            if( !$match || !$match[0] ){
	                continue;
	            } */
	            if( !$suggest2 ){
	                continue;
	            }
	            
	            $zhString = $suggest2;
	        }
	        else {
	            $zhString = $suggest1;
	        }
	         
	        $return[] = $zhString;
	        $i++;
	    }
       
        return $return;
	}
	
	/**
	 * 将xml转为array
	 * @param unknown $xml
	 * @return mixed
	 */
	public function xmlToArray($xml){
	    //将XML转为array
	    $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
	    return $array_data;
	}
}