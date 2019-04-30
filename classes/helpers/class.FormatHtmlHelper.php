<?php
/**
 * 返回模块的html
 */

class FormatHtml {
    
    private $labelHelper;
    private $bindModularIdArray;

    function __construct() {
        $this -> labelHelper = HelperFactory::getLabelHelper();
    }

    /**
     * 格式化banner的html
     */
    public function getBannerHtml($bannerArray) {


        if ( !$bannerArray || !is_array($bannerArray) ) {
            return '';
        }

        $html = 
        '<div id="slideBox" class="banner">
            <div class="bd">
                <div class="Wrap">
                    <ul class="slide">';
                    
                    $i = 1;
                    $tmpArray = [];
                    foreach ( $bannerArray as $bannerId => $banner ){
                        $banner['adv_click_url'] = $banner['adv_click_url']?:'javascript:void(0);';
                        $html .= 
                        '<li>
                            <a href="'. $banner['adv_click_url'] .'">
                                <img src="'. $banner['adv_pic_url'] .'" />
                            </a>
                        </li>';
                    }
                    $html .=
                    '</ul>
                </div>
            </div>

            <div class="hd">
                <ul class="indicator hd">';
                
                $i = 1;
                foreach ( $bannerArray as $bannerId => $banner ){
                    $on = $i == 1 ? 'on' : '';
                    $html .=
                    '<li class="'. $on .'"></li>';
                    $i++;
                }
                $html .=
                '</ul>
            </div>
        </div>';

        return $html;
    }

    /**
     * 格式化BannerFrame用于html输出
     * @param array $bannerFrameArray
     * @return string
     */
    public function  formatBannerFrame($bannerFrameArray) {
        if ( !$bannerFrameArray || !is_array($bannerFrameArray) ) {
            return '';
        }

        $return = '';

        foreach ( $bannerFrameArray as $bannerFrame ) {

            $typeClass = $bannerFrame['type_class'];

            $detailArray = $bannerFrame['detail'];
            switch ( $typeClass ) {
                case '0': // 高3均分
                case '3': // 低3均分
                    $return .= '<div class="activity1 clearfix" style="display:-none;" >';
                    $detail = array_shift($detailArray);
                    $return .= '<a href="' . $detail['pic_click_url'] . '"><img src="' . $detail['pic_name'] . '" /></a>';
                    $detail = array_shift($detailArray);
                    $return .= '<a href="' . $detail['pic_click_url'] . '"><img src="' . $detail['pic_name'] . '" /></a>';
                    $detail = array_shift($detailArray);
                    $return .= '<a class="last" href="' . $detail['pic_click_url'] . '"><img src="' . $detail['pic_name'] . '" /></a>';
                    $return .= '</div>';
                    break;
                case '1': // 低2均分
                case '11': // 260低2均分
                    $return .= '<div class="activity2 clearfix" style="display:-none;" >';
                    $detail = array_shift($detailArray);
                    $return .= '<a href="' . $detail['pic_click_url'] . '"><img src="' . $detail['pic_name'] . '" /></a>';
                    $detail = array_shift($detailArray);
                    $return .= '<a class="last" href="' . $detail['pic_click_url'] . '"><img src="' . $detail['pic_name'] . '" /></a>';
                    $return .= '</div>';
                    break;
                case '2': // 低单banner
                case '8': // 160低单banner
                case '9': // 260低单banner
                case '10': // 380低单banner
                    $return .= '<div class="activity3 clearfix" style="display:-none;" >';
                    $detail = array_shift($detailArray);
                    $return .= '<a href="' . $detail['pic_click_url'] . '"><img src="' . $detail['pic_name'] . '" /></a>';
                    $return .= '</div>';
                    break;
                case '4': // 低12
                    $return .= '<div class="activity5 clearfix" style="display:-none;" >';
                    $detail = array_shift($detailArray);
                    $return .= '<a href="' . $detail['pic_click_url'] . '" class="child1"><img src="' . $detail['pic_name'] . '" /></a>';
                    $detail = array_shift($detailArray);
                    $return .= '<a href="' . $detail['pic_click_url'] . '" class="child2"><img src="' . $detail['pic_name'] . '" /></a>';
                    $detail = array_shift($detailArray);
                    $return .= '<a href="' . $detail['pic_click_url'] . '" class="child2 last"><img src="' . $detail['pic_name'] . '" /></a>';
                    $return .= '</div>';
                    break;
                case '5': // 横1竖2
                    $return .= '<div class="activity6 clearfix">';
                    $detail = array_shift($detailArray);
                    $return .= '<a href="' . $detail['pic_click_url'] . '" class="child1"><img src="' . $detail['pic_name'] . '" /></a>';
                    $detail = array_shift($detailArray);
                    $return .= '<a href="' . $detail['pic_click_url'] . '" class="child2"><img src="' . $detail['pic_name'] . '" /></a>';
                    $detail = array_shift($detailArray);
                    $return .= '<a href="' . $detail['pic_click_url'] . '" class="child2"><img src="' . $detail['pic_name'] . '" /></a>';
                    $return .= '</div>';
                    break;
                case '6': // 横1竖4
                    $return .= '<div class="activity7 clearfix" style="display:-none;" >';
                    $detail = array_shift($detailArray);
                    $return .= '<a href="' . $detail['pic_click_url'] . '" class="child1"><img src="' . $detail['pic_name'] . '" /></a>';
                    $detail = array_shift($detailArray);
                    $return .= '<a href="' . $detail['pic_click_url'] . '" class="child2"><img src="' . $detail['pic_name'] . '" /></a>';
                    $detail = array_shift($detailArray);
                    $return .= '<a href="' . $detail['pic_click_url'] . '" class="child2"><img src="' . $detail['pic_name'] . '" /></a>';
                    $detail = array_shift($detailArray);
                    $return .= '<a href="' . $detail['pic_click_url'] . '" class="child3"><img src="' . $detail['pic_name'] . '" /></a>';
                    $detail = array_shift($detailArray);
                    $return .= '<a href="' . $detail['pic_click_url'] . '" class="child3"><img src="' . $detail['pic_name'] . '" /></a>';
                    $return .= '</div>';
                    break;
                case '7': // 低4均分
                    $return .= '<div class="activity8 clearfix" style="display:-none;" >';
                    $detail = array_shift($detailArray);
                    $return .= '<a href="' . $detail['pic_click_url'] . '"><img src="' . $detail['pic_name'] . '" /></a>';
                    $detail = array_shift($detailArray);
                    $return .= '<a href="' . $detail['pic_click_url'] . '"><img src="' . $detail['pic_name'] . '" /></a>';
                    $detail = array_shift($detailArray);
                    $return .= '<a href="' . $detail['pic_click_url'] . '"><img src="' . $detail['pic_name'] . '" /></a>';
                    $detail = array_shift($detailArray);
                    $return .= '<a href="' . $detail['pic_click_url'] . '" class="last"><img src="' . $detail['pic_name'] . '" /></a>';
                    $return .= '</div>';
                    break;

            }
            
        }

        return $return;
    } 

    /**
     * 返回精品专题html
     * @param array $activityModular 精品专题模块
     * @return string
     */
    public function getTopSpecialSubjectListHtml($activityModular) {
        // print_r($activityModular);exit;
        if ( !$activityModular ) {
            return '';
        }

        $topSpecialSubjectList = $activityModular['top_special_subject_list'];
        
        $title = $activityModular['title'];
        $subTitle = $activityModular['sub_title'];

        $html = 
        '<div class="sup-topic">
            <a class="topic-tit" href="'. Zc::url(YfjRouteConst::specialSubjectIndex) .'">
                '. $title .'
                <span>'. $subTitle .'</span>
                <i class="FR icon icon-jiantou"></i>
            </a>
            <div class="wrap">
                <div class="list clearfix">';

                foreach ($topSpecialSubjectList as $topSpecialSubject) {
                    $html .=
                    '<a class="item FL" href="'. Zc::url(YfjRouteConst::specialSubjectDetail,'special_subject_id='.$topSpecialSubject['special_subject_id']) .'">
                        <img src="'. $topSpecialSubject['image_url'] .'">
                        <p class="tit one-line">'. $topSpecialSubject['title'] .'</p>
                        <p class="des one-line">'. $topSpecialSubject['sub_title'] .'</p>
                    </a>';
                }
                    $html .=
                    '<a class="more FL" href="'. Zc::url(YfjRouteConst::specialSubjectIndex) .'">
                        <p>更多专题</p>
                        <p>もっと見る</p>
                    </a>
                </div>
            </div>
        </div>';

        return $html;
    }

    /**
     * 返回种草指南(专题列表)
     * @param array $activityModular 种草指南专题列表模块
     * @return string
     */
    public function getSpecialSubjectListForYouHtml($activityModular) {
        
        if ( !$activityModular ) {
            return '';
        }

        $specialSubjectList = $activityModular['special_subject_list'];
        
        $title = $activityModular['title'];
        $subTitle = $activityModular['sub_title'];

        $html = 
        '<div class="topic-wrap">
            <a href="'. Zc::url(YfjRouteConst::specialSubjectIndex) .'" class="tit">
                <h6 class="FL">'. $title .'<span>'. $subTitle .'</span></h6>
                <i class="FR icon icon-jiantou"></i>
            </a>';
            foreach ($specialSubjectList as $specialSubject) {
                $html .= 
                '<div class="rec-topic">
                    <a href="'. Zc::url(YfjRouteConst::specialSubjectDetail,'special_subject_id='.$specialSubject['special_subject_id']) .'"><img src="'. $specialSubject['image_url'] .'" /></a>
                    <a class="shadow" href="'. Zc::url(YfjRouteConst::specialSubjectDetail,'special_subject_id='.$specialSubject['special_subject_id']) .'">
                        <p class="title">'. $specialSubject['title'] .'</p>
                        <p class="sub-tit">'. $specialSubject['sub_title'] .'
                            <span class="FR">
                                <img src="'. HtmlTool::getStaticFile('new/news/home_icon_views@3x.png') .'" />'.$specialSubject['page_view'].'
                            </span>
                        </p>
                    </a>
                </div>';
            }
        $html .= '</div>';

        return $html;
    }

    /**
     * 格式化前台活动模块输出html
     */
    public function formatActivityModularHtml($activityModularList) {

        if ( !$activityModularList ) {
            return '';
        }

        $html = '';
        foreach ($activityModularList as $activityModular) {
            
            $modularType = $activityModular['modular_type'];

            $html .= $this -> getIdHtml($activityModular['modular_id']);

            switch ( $modularType ) {
                case 'one-img':
                    $html .= $this -> getOneImgHtml($activityModular);
                    break;
                case 'two-img':
                    $html .= $this -> getTwoImgHtml($activityModular);
                    break;
                case 'add-text':
                    $html .= $this -> getAddTextHtml($activityModular);
                    break;
                case 'anchor': // 锚点TAB
                    $html .= $this -> getAnchorHtml($activityModular);
                    break;
                case 'title':
                    $html .= $this -> getTitleHtml($activityModular);
                    break;
                case 'list1':
                    $html .= $this -> getList1Html($activityModular);
                    break;
                case 'list2':
                    $html .= $this -> getList2Html($activityModular);
                    break;
                case 'list3':
                    $html .= $this -> getList3Html($activityModular);
                    break;
                case 'list4':
                    $html .= $this -> getList4Html($activityModular);
                    break;
                case 'top_special_subject_list':
                    $html .= $this -> getTopSpecialSubjectListHtml($activityModular);
                    break;
            }

        }

        return $html;
    }

    /**
     * 生成锚点id
     */
    public function getIdHtml($modularId) {
        
        if ( !$modularId ) {
            return '';
        }

        $html = '';
        if ( $this -> bindModularIdArray[$modularId] ) {
            
            unset($this -> bindModularIdArray[$modularId]);
            $html = "<a class='anchor' id='modular_id_". $modularId ."' ></a>";

        }

        return $html;
    }

    /**
     * 单图html
     * @param array $activityModular 
     * @param string
     */
    private function getOneImgHtml($activityModular) {
        
        if ( !$activityModular ) {
            return '';
        }

        $detail = array_shift($activityModular['detail']);
        $href = $this -> getUrl($detail['url'], $detail['url_type']);
        $idHtml = 
        $html .= 
        '<div class="one-img">
            <a href="'. $href .'"><img src="'. $detail['image_url'] .'" /></a>
        </div>';

        return $html;
    }

    /**
     * 双图html
     * @param array $activityModular 
     * @param string
     */
    private function getTwoImgHtml($activityModular) {
        
        if ( !$activityModular ) {
            return '';
        }

        $detail = $activityModular['detail'];
        $html .= '<div class="two-img clearfix">'; 
            foreach ($detail as $value) {
                $href = $this -> getUrl($value['url'], $value['url_type']);
                $html .= '<a href="'. $href .'"><img src="'. $value['image_url'] .'" /></a>';
            }
        $html .= '</div>';

        return $html;
    }

    /**
     * 多行文本html
     * @param array $activityModular 
     * @param string
     */
    private function getAddTextHtml($activityModular) {
        
        if ( !$activityModular ) {
            return '';
        }

        $html .= 
        '<div class="intro" style="background:'. $activityModular['background'] .';color:'. $activityModular['color'] .'" >
            <p style="color:'. $activityModular['color'] .'">'. $activityModular['content'] .'</p>
        </div>';

        return $html;
    }

    /**
     * 锚点html
     * @param array $activityModular 
     * @param string
     */
    private function getAnchorHtml($activityModular) {
        
        if ( !$activityModular ) {
            return '';
        }
        $this -> bindModularIdArray = [];
        $detail = $activityModular['detail'];
        $html .= 
        '<div class="nav">
            <div class="wrap clearfix">';
                // '<a class="active" href="#">模块标题</a>
                foreach ($detail as $value) {
                    $html .= '<a name="modular_id_'. $value['bind_modular_id'] .'">'. $value['title'] .'</a>';
                    $this -> bindModularIdArray[$value['bind_modular_id']] = $value['bind_modular_id'];
                }
        $html .=
            '</div>
        </div>';
        return $html;
    }

    /**
     * 标题html
     * @param array $activityModular 
     * @param string
     */
    private function getTitleHtml($activityModular) {
        
        if ( !$activityModular ) {
            return '';
        }

        $html .= 
        '<h4 class="title">'. $activityModular['title'] .'</h4>';

        return $html;
    }

    /**
     * 列表1html
     * @param array $activityModular 
     * @param string
     */
    private function getList1Html($activityModular) {

        if ( !$activityModular ) {
            return '';
        }

        $goodsArray = $activityModular['goods_array'];
        $html .= 
        '<div class="list1">';
            foreach ($goodsArray as $goods) {
                $html .= 
                '<a class="item" href="'. Zc::url(YfjRouteConst::goodsDetail,'goods_id='.$goods['goods_id']) .'">
                    <img src="'. $goods['goods_image_url'] .'" />
                    <div class="info">
                        <h6>'. $goods['goods_sologan'] .'</h6>
                        <p class="tit two-line">
                            <span class="COR5">'. $goods['goods_name_pre'] .'</span>
                            '. $goods['goods_name'] .'
                        </p>
                        <div class="pri">
                            <span class="COR5">¥<span class="price">'. $goods['final_price'] .'</span></span>
                            <span class="DEL"></span>
                            '. $this -> labelHelper -> getTaxAndPostageLabel($goods['label_type']) .'
                        </div>
                        <div class="buy">
                            <img data-goods_id="'. $goods['goods_id'] .'" class="js-addCart" src="'. HtmlTool::getStaticFile("new/news/enter_car_l@3x.png") .'" />
                        </div>
                    </div>
                </a>';
            }

        $html .= 
        '</div>';
        return $html;
    }

    /**
     * 列表2html
     * @param array $activityModular 
     * @param string
     */
    private function getList2Html($activityModular) {

        if ( !$activityModular ) {
            return '';
        }

        $goodsArray = $activityModular['goods_array'];
        $html .= 
        '<div class="list2">
            <div class="wrap clearfix">';
            foreach ($goodsArray as $goods) {
                $html .= 
                '<a class="item" href="'. Zc::url(YfjRouteConst::goodsDetail,'goods_id='.$goods['goods_id']) .'">
                    <img src="'. $goods['goods_image_url'] .'" />
                    <p class="tit two-line">'. $goods['goods_name'] .'</p>
                    <p class="COR5">'. $goods['format_final_price'] .'</p>
                </a>';
            }
        $html .= 
            '</div>
        </div>';
        return $html;
    }

    /**
     * 列表3html
     * @param array $activityModular 
     * @param string
     */
    private function getList3Html($activityModular) {

        if ( !$activityModular ) {
            return '';
        }

        $goodsArray = $activityModular['goods_array'];
        $html .=
        '<div class="list3 clearfix">';
        foreach ($goodsArray as $goods) {
            $html .=
            '<a class="item" href="'. Zc::url(YfjRouteConst::goodsDetail,'goods_id='.$goods['goods_id']) .'">
                <img src="'. $goods['goods_image_url'] .'" />
                <div class="info">
                    <p class="three-line">
                        <span class="COR5">'. $goods['goods_name_pre'] .'</span>
                        '. $goods['goods_name'] .'
                    </p>
                    <p class="COR5">¥<span class="price">'. $goods['final_price'] .'</span><span class="DEL"></span></p>
                    <div class="buy">
                        <img data-goods_id="'. $goods['goods_id'] .'" class="js-addCart" src="'. HtmlTool::getStaticFile("new/news/enter_car_l@3x.png") .'" />
                    </div>
                </div>
            </a>';
        }
        $html .=
        '</div>';
        return $html;
    }

    /**
     * 列表4html
     * @param array $activityModular 
     * @param string
     */
    private function getList4Html($activityModular) {

        if ( !$activityModular ) {
            return '';
        }

        $goodsArray = $activityModular['goods_array'];
        $html .=
        '<div class="list4">';
        foreach ($goodsArray as $goods) {
            $html .=
            '<a class="item" href="'. Zc::url(YfjRouteConst::goodsDetail,'goods_id='.$goods['goods_id']) .'">
                <img class="FL" src="'. $goods['goods_image_url'] .'" />
                <div class="wrap FR">
                    <div class="info">
                        <p class="tit">'. $goods['goods_sologan'] .'</p>
                        <p class="des two-line">'. $goods['goods_name'] .'</p>
                    </div>
                    <div class="pri">
                        <p>
                            '. $this -> labelHelper -> getTaxAndPostageLabel($goods['label_type']) .'
                        </p>
                        ¥<span class="COR5">'. $goods['final_price'] .'</span>
                        <span class="DEL"></span>
                        <img data-goods_id="'. $goods['goods_id'] .'" class="FR js-addCart" src="'. HtmlTool::getStaticFile("new/news/enter_car_l@3x.png") .'" />
                    </div>
                </div>
            </a>';
        }
        $html .= 
        '</div>';
        return $html;
    }

    /**
     * 返回url
     * @param string $url 
     * @param string $urlType url类型
     * @return string
     */
    private function getUrl($url, $urlType) {
        
        if ( $urlType == 0 ) {
            return 'javascript:void(0)';
        }

        return $url;
    }
	
	/**
	 * 获得回收单的HTML
	 */
    public function getRecoveryGoodsHtml($goodsInfo){
    	$html ='';
    
		foreach ($goodsInfo  as $goods){
			$disable ='';
			if($goods['cloud_storage'] <=0){
				$disable = 'disabled';
			}
			
			$html.="<tr>
		<td  class='add_sku'>". $goods['goods_shape_code']."</td>
		<td class='add_name'>". $goods['goods_name']."</td>
		<td>". $goods['sale_num']."</td>
		<td class='cloud_num'>". $goods['cloud_storage']."</td>
		<td>". $goods['entrepot_type_mame']."</td>
		<td><input class='add_goods_num' type='text' style='width:50px' ".$disable."></td>
		<td><input type='button'  value='添加' ".$disable." class='layui-btn layui-btn-sm add-recovery-goods'></td></tr>";
		}
		return $html;
	 }
	
	/**获得静态的分页
	 * @param $count
	 * @param $size
	 */
	 public function getStasticPagination($count,$size){
         $pageNum = ceil($count/$size);//总页数
		 $html = '';
		 $html = '
             
                <div class="pageList">
                    <span class="tiao prev" filert="prev" page="0">上一页</span>
   					<span class="tiao next" filert="next" page="2">下一页</span>
   					<span>跳转到<input type="text" class="link-page" value="" style="width: 30px">页</span>
   					
                </div><div class="pageListInfo">
                    共<span class="total"> '.$count.'</span>条 |<span class="">'.$pageNum.'</span><span class="PD">页</span>每页<span class="">'.$size.'</span>条';
		 $html.='
              第<span class="current">1</span>页';
		 
		 
	 	return $html;
	 	
	 	
	 }
  
}