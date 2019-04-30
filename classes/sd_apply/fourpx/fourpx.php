<?php

/**4px的接口方法拼接
 * Class fourpx
 */
class fourpx{
	//测试的
	private $token = '46723841-7599-4C48-9D8C-69D8A5FEB213';
	private $userCode = 'YAZAAHE';
	private $resevationLogUrl = 'http://sandbox.tr.4px.com/TRSAPI/Agent/CreateAgent'; //向转运四方预约物流单号的接口
	
	//正式的
	//private $token = 'D13EEB1C-953E-4149-9272-ED15B954DB52';
	//private $userCode = 'JPTXXD';
	//private $resevationLogUrl = 'http://open.tr.4px.com/TRSAPI/Agent/CreateAgent'; //向转运四方预约物流单号的接口
	
	/**要求 标准请求头 Content-Type: text/json; charset=utf-8
	 * 数据传输以Http Post方式发送，数据字符集一律采用Utf-8，数据传输使用Json格式。
	 * @param $url
	 * @param $postData
	 * @return mixed
	 */
	public function createCurl($url,$postData){
		$curl = HelperFactory::getCurlHelper(['header'=>array('Content-Type: application/json', 'Content-Length:' . strlen($postData))]);
		$output = $curl -> post($url,[$postData],'', '', true);
		
 		/*$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
		curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type: application/json', 'Content-Length:' . strlen($postData)) );
		curl_setopt($ch, CURLOPT_POSTFIELDS , $postData);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);*/
		
		return $output;
	
	
	}
	//预约单号物流的数据拼接
	public function createReservationLogisticsData($order,$cardInfo){
		//进行数据的组装
		$postData = [];
		$tmp = [];
		$tmp['CarrierDeliveryNo'] = $order['out_order_no'];
		$tmp['ShipperOrderNo'] = $order['out_order_no'];
		$tmp['WarehouseCode'] = 'CNHKG';
		$tmp['CarrierCompanyCode'] = '';
		$tmp['WarehouseOperateMode'] = 'NON';
		$tmp['UserCode'] = $this -> userCode;
		$tmp['ServiceTypeCode'] = 'DPS';
		$tmp['OperatingInstruction'] = '';
		$tmp['ItemDeclareCurrency'] = 'CNY';
		//$tmp['HasInsure'] = 'false';
		$tmp['ConsigneeStreetDoorNo'] = $order['shipping_address'];
		$tmp['ConsigneePostCode'] = '000000';
		$tmp['ConsigneeName'] = $cardInfo['true_name'];
		$tmp['ConsigneeMobile'] = $order['shipping_phone'];
		$tmp['ConsigneeIDType'] = '';
		$tmp['ConsigneeIDNumber'] =  $cardInfo['card_id'];
		$tmp['ConsigneeIDBackCopy'] = "http://www.baidu.com/img/bd_logo1.png";
		$tmp['ConsigneeIDFrontCopy'] = "http://www.baidu.com/img/bd_logo1.png";
		$tmp['ConsigneeEMail'] = "";
		$tmp['ConsigneeAreaID'] = "";
		$tmp['AttachParam'] = "";
		$tmp['WarehouseName'] = "";
		$tmp['TaxMode'] = 'DDP';
		$tmp['OrderWeight'] = $order['order_weight']/1000;
		
		$tmp['DestinationCountry'] ='142';
		
		$tmp['Province'] = $order['shipping_province'];
		
		$tmp['City'] = $order['shipping_city'];
		$tmp['District'] = $order['shipping_area'];
		$tmp['DeliveryCompany'] = 'YDGJ';
		
		$tmp['ReceiptCountry'] ='142';
		$tmp['IsReturnDTB'] = '1';
		
		$goodsList = [];
		foreach ($order['goodsArr'] as $goods){
			$arr = [];
			$arr['ItemDeclareType'] = $goods['goods_cate'];
			$arr['ItemNameEnglish'] = '';
			$arr['ProductType'] = null;
			$arr['ItemNameLocalLang'] = $goods['goods_name'];
			$arr['ItemNumber'] = $goods['goods_num'];
			$arr['ItemPictureURL'] = '';
			
			$arr['ItemSKU'] = $goods['goods_shape_code'];
			$arr['ItemTotalAmount'] = $goods['total_goods_price'];
			$arr['ItemUnitPrice'] = $goods['unit_price'];
			
			$arr['PlaceOfOrigin'] = $goods['origin_place']; //原厂国
			$arr['Brand'] = $goods['goods_brand'];
			$arr['SpecValue'] = $goods['xf_unit_value'];
			$arr['SpecUnit'] = $goods['xf_unit'];
			
			$goodsList[] = $arr;
		}
		
		$tmp['ITEMS'] = $goodsList;
		//$tmp['ValueAddedService'] = '';
		$postData['Data'] = $tmp;
		
		$postData['ConsignorCountry'] = '';
		$postData['ConsignorStateOrProvince'] = '';
		$postData['ConsignorCity'] = '';
		$postData['ConsignorDistrict'] = '';
		$postData['ConsignorDetailAddress'] = '';
		$postData['ConsignorPostCode'] = '';
		$postData['ConsignorCompany'] = '';
		$postData['Consignor'] = '';
		$postData['ConsignorPhone'] = '';
		$postData['ConsignorMobile'] = '';
		$postData['ConsignorEmail'] = '';
		$postData['Token'] = $this->token;
		
		return json_encode($postData);
	}
	
	/**获取物流单号的url
	 * @return string
	 */
	public function getResevationLogUrl(){
		return $this -> resevationLogUrl;
	}



}