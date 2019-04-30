<?php

class Image
{

    private $errorMessage;

    public function __construct()
    {

    }


    /**
     * 获取用户头像(旧接口)
     */
    public function avatarThumb($image_name = '')
    {
        $empty_name = 'default_user_portrait.gif';
        if (empty($image_name)) {
            return Zc::C('app.https.img_cdn1') . 'view_controller/images/' . $empty_name;
        }
        $basePath = Zc::C('upload.dir.fs') . 'shop/avatar/';
        $baseUrl = Zc::C('app.https.img_cdn1') . 'data/upload/shop/avatar/';
        if (!file_exists($basePath . $image_name)) {

            return Zc::C('app.https.img_cdn1') . 'view_controller/images/' . $empty_name;
        }
        return $baseUrl . $image_name;

    }


    /**
     * 取得买家缩略图的完整URL路径
     *
     * @param string $imgurl 商品名称
     * @param string $type 缩略图类型  值为240,1024
     * @return string
     */
    function snsThumb($image_name = '', $type = '')
    {
        if (!in_array($type, array('240', '1024'))) $type = '240';
        if (empty($image_name)) {
            return Zc::C('app.https.img_cdn1') . str_replace(DIR_FS_DOCUMENT_ROOT, '', $this->defaultGoodsImage('240'));
        }

        list($member_id) = explode('_', $image_name);
        $file_path = 'shop/member/' . $member_id . '/' . str_ireplace('.', '_' . $type . '.', $image_name);
        $basePath = Zc::C('upload.dir.fs') . '/';
        $baseUrl = Zc::C('app.https.img_cdn1') . 'data/upload/';
        if (!file_exists($basePath . $file_path)) {
            return Zc::C('app.https.img_cdn1') . str_replace(DIR_FS_DOCUMENT_ROOT, '', $this->defaultGoodsImage('240'));
        }
        return $baseUrl . $file_path;
    }

		/* 获取商品图
     * @param  string $file
     * @param  null|int $type
     * @return string
     */
    public function cthumb($file, $type = '', $store_id = 1)
    {
        if (empty($file)) {
	
	        return Zc::C('app.http.domain') .'view_controller/yifanjie-business/views/static/images/bpcd_noimg.png';
        }
       
        $type_array = explode(',_', ltrim(Zc::C('goods.images.ext'), '_'));
        if ($type && !in_array($type, $type_array)) {
            $type = '1280';
        }
	    if(!$type && $store_id==2){
		    $fname = $file;
	    }else{
		    $search_array = explode(',', Zc::C('goods.images.ext'));
		    $file = str_ireplace($search_array, '', $file);
		
		    $file = str_ireplace('.', '_' . $type . '.', $file);
		
		    $imgRelativePath = 'data/upload/shop/store/goods/' . $store_id . '/';
		    $fname = str_ireplace($imgRelativePath,'',$file);//basename($file);
	    }
      

        if ($store_id === null || !is_numeric($store_id)) {
            $store_id = substr($file, 0, strpos($file, '_'));
        };

        $basePath = Zc::C('upload.common.goods.dir.fs') . $store_id . '/';
	    if($store_id == 2){
		    $baseUrl = Zc::C('app.https.img_cdn4') . $imgRelativePath;
	    }else{
		    $baseUrl = Zc::C('app.https.img_cdn1') . $imgRelativePath;
	    }
	    
//        $baseUrl = 'http://www.yifanjie.com/'. $imgRelativePath;

        if (!file_exists($basePath . $fname) && 'G_WX_XINRITAO_COM_DOMAIN' != G_CURRENT_DOAMIN_CONST_NAME ) {
            return 'http://www.yifanjie.com/'. $imgRelativePath . $fname;
            ///return Zc::C('app.https.img_cdn1') . str_replace(DIR_FS_DOCUMENT_ROOT, '', $this->defaultGoodsImage('240'));
        }
	   
        return $baseUrl . $fname;

    }


    /**
     * 获取商品图(新的)
     * @param  string $file
     * @param  null|int $type
     * @return string
     */
    public function getcThumbPic($file, $type = '', $store_id = 1)
    {
        $imgRelativePath = 'data/upload/shop/store/goods/' . $store_id . '/';
        $baseUrl = DIR_FS_DOCUMENT_ROOT. $imgRelativePath;

        $fileArray = explode('/',$file);
       //拼接图片后缀 1_05775489231837098_360.jpg
        $type_array = explode(',_', ltrim(Zc::C('goods.images.ext'), '_'));
        if (!in_array($type, $type_array)) {
            $type = '1280';
        }

        $baseName = str_replace('.', '_' . $type . '.', $fileArray[count($fileArray)-1]);

        $fileBaseName = $fileArray[count($fileArray)-2].'/'.$baseName;
        $fileName =  $baseUrl.$fileBaseName;

//        $baseUrl = 'http://www.yifanjie.com/'. $imgRelativePath;

        if (!file_exists($fileName)) {

            return Zc::C('app.https.img_cdn1') . str_replace(DIR_FS_DOCUMENT_ROOT, '', $this->defaultGoodsImage('240'));
        }
        
        
        $fileName = Zc::C('app.https.img_cdn1').str_replace(DIR_FS_DOCUMENT_ROOT, '',$fileName);

        return $fileName;

    }

    /**
     * 获取积分商城商品图
     * @param  string $file
     * @param  null|int $type
     * @return string
     */
    public function cIntegralThumb($file)  { 

        if (empty($file)) {
            return Zc::C('app.https.img_cdn1') . str_replace(DIR_FS_DOCUMENT_ROOT, '', $this->defaultGoodsImage('240'));
        }

        $basePath = DIR_FS_DOCUMENT_ROOT;
        $baseUrl = Zc::C('app.https.img_cdn1') . $file;
        
        if (!file_exists($basePath . $file)) {
            return Zc::C('app.https.img_cdn1') . str_replace(DIR_FS_DOCUMENT_ROOT, '', $this->defaultGoodsImage('240'));
        }

        return $baseUrl ;
    }

    /**
     * 获取商品服务图片
     */
    public function getGoodsServiceImage($file) {
        
        $basePath = DIR_FS_DOCUMENT_ROOT;
        if (!file_exists($basePath . $file)) {
            return Zc::C('app.https.img_cdn1') . str_replace(DIR_FS_DOCUMENT_ROOT, '', $this->defaultGoodsImage('240'));
        }
        $baseUrl = Zc::C('app.https.img_cdn1') . $file;
        return $baseUrl;

    }

    /**
     * 获取品牌图
     * @param  string $file
     * @return string
     */
    public function cthumbBrand($file)
    {
        if (empty($file)) {
            return Zc::C('app.https.img_cdn1') . str_replace(DIR_FS_DOCUMENT_ROOT, '', $this->defaultGoodsImage('240'));
        }

        $basePath = Zc::C('upload.dir.fs') . 'shop/brand/';
        $baseUrl = Zc::C('app.https.img_cdn1') . 'data/upload/shop/brand/';
        if (!file_exists($basePath . $file)) {
            return Zc::C('app.https.img_cdn1') . str_replace(DIR_FS_DOCUMENT_ROOT, '', $this->defaultGoodsImage('240'));
        }

        return $baseUrl . $file;
    }

    /**
     * 获取品牌BANNER
     * @param  string $file
     * @return string
     */
    public function cthumbBrandBanner($file)
    {
        if (empty($file)) {
            return Zc::C('app.https.img_cdn1') . str_replace(DIR_FS_DOCUMENT_ROOT, '', $this->defaultBrandBanner());
        }

        $basePath = Zc::C('upload.dir.fs') . 'shop/brand/';
        $baseUrl = Zc::C('app.https.img_cdn1') . 'data/upload/shop/brand/';
        if (!file_exists($basePath . $file)) {
            return Zc::C('app.https.img_cdn1') . str_replace(DIR_FS_DOCUMENT_ROOT, '', $this->defaultBrandBanner());
        }

        return $baseUrl . $file;
    }

    /**
     * 获取商品APP半长图
     * @param  string $file
     * @param  null|int $type
     * @return string
     */
    public function cthumbHalf($file, $store_id = 1)
    {
        if (empty($file)) {
            return Zc::C('app.https.img_cdn1') . str_replace(DIR_FS_DOCUMENT_ROOT, '', $this->defaultGoodsImage('240'));
        }

        if ($store_id === null || !is_numeric($store_id)) {
            $store_id = substr($file, 0, strpos($file, '_'));
        }

        $basePath = Zc::C('upload.common.goods.dir.fs') . $store_id . '/';
        $baseUrl = Zc::C('app.https.img_cdn1') . 'data/upload/shop/store/goods/' . $store_id . '/';

        if (!file_exists($basePath . $file)) {
            return Zc::C('app.https.img_cdn1') . str_replace(DIR_FS_DOCUMENT_ROOT, '', $this->defaultGoodsImage('240'));
        }

        return $baseUrl . $file;
    }

    /**
     * 取得商品默认大小图片
     *
     * @param string $key 图片大小 small tiny
     * @return string
     */
    private function defaultGoodsImage($key)
    {
        $file = str_ireplace('.', '_' . $key . '.', 'default_goods_image.gif');
        return Zc::C('upload.common.dir.fs') . $file;
    }
	

	
    /**
     * 取得品牌默认图片
     *
     * @param string $key 图片大小 sm
     * @return string
     */
    private function defaultBrandBanner()
    {
        $file = 'default_brand_banner.jpg';
        return Zc::C('upload.common.dir.fs') . $file;
    }    

    /**
     * 获取分类的图片
     * @param unknown $file
     * @return boolean|string
     */
    public function getCategoriesImageUrl($file)
    {
        $basePath = DIR_FS_DOCUMENT_ROOT;
        if (!file_exists($basePath . $file)) {
            return Zc::C('app.https.img_cdn1') . str_replace(DIR_FS_DOCUMENT_ROOT, '', $this->defaultGoodsImage('240'));
        }
        $baseUrl = Zc::C('app.https.img_cdn1') . $file;
        return $baseUrl;
    }

    public function thumb($goods = array(), $type = '')
    {

        $type_array = explode(',_', ltrim(Zc::C('goods.images.ext'), '_'));
        if (!in_array($type, $type_array)) {
            $type = '240';
        }

        if (empty($goods)) {
            return Zc::C('app.https.img_cdn1') . str_replace(DIR_FS_DOCUMENT_ROOT, '', $this->defaultGoodsImage($type));
        }

        if (array_key_exists('apic_cover', $goods)) {
            $goods['goods_image'] = $goods['apic_cover'];
        }

        if (empty($goods['goods_image'])) {
            return Zc::C('app.https.img_cdn1') . str_replace(DIR_FS_DOCUMENT_ROOT, '', $this->defaultGoodsImage($type));
        }

        $search_array = explode(',', Zc::C('goods.images.ext'));
        $file = str_ireplace($search_array, '', $goods['goods_image']);
        $fname = basename($file);

        //取店铺ID
        if (preg_match('/^(\d+_)/', $fname)) {
            $store_id = substr($fname, 0, strpos($fname, '_'));
        } else {
            $store_id = $goods['store_id'];
        }
        $file = $type == '' ? $file : str_ireplace('.', '_' . $type . '.', $file);
        $basePath = Zc::C('upload.common.goods.dir.fs') . $store_id . '/';
        $baseUrl = Zc::C('app.https.img_cdn1') . 'data/upload/shop/store/goods/' . $store_id . '/';
        // $baseUrl = 'http://www.yifanjie.com/data/upload/shop/store/goods/' . $store_id . '/';
        if (!file_exists($basePath . $file)) {
            return 'http://m.yifanjie.com/'. 'data/upload/shop/store/goods/' . $store_id . '/' . $file;
            // return Zc::C('app.https.img_cdn1') . str_replace(DIR_FS_DOCUMENT_ROOT, '', $this->defaultGoodsImage($type));
        }

        return $baseUrl . $file;

    }

    //
    /**
     * 获得头像
     * @param  string $file
     * @return string
     */
    public function getAvatar($file = '', $image_size = false)
    {
        if (empty($file)) {
            return Zc::C('app.https.img_cdn1') . str_replace(DIR_FS_DOCUMENT_ROOT, '', $this->defaultGoodsImage('240'));
        }
        $basePath = Zc::C('upload.authinfo.dir.fs');
        $baseUrl = Zc::C('app.https.img_cdn1') . 'data/upload/shop/authinfo/';

        if (!file_exists($basePath . $file)) {
            return Zc::C('app.https.img_cdn1') . str_replace(DIR_FS_DOCUMENT_ROOT, '', $this->defaultGoodsImage('240'));
        }
        return $baseUrl . $file;
    }


    /**
     * 获取用户头像 保存到新地址: /upload/avatar/'.date('Y-m') . '/'
     * @param string $file 相对路径
     * @param int    $type 需要的缩略图尺寸
     * @return string
     */
    public function getNewAvatar($file,$type) {
        $typeArray = explode(',', Zc::C('avatar.images.width'));
        $old_file = $file;
        if (!$type) {
            $type = 100;
        }

        if ($type) {
            if (in_array($type,$typeArray)) {
                $file = str_ireplace('.' , '_' . $type . '.' , $file);
            }
        }

        $empty_name = 'default_user_portrait.gif';
                
        if (empty($file)) {
            return Zc::C('app.https.img_cdn1') . 'view_controller/images/' . $empty_name;
        }

        $basePath = DIR_FS_DOCUMENT_ROOT . $file;
        $imageUrl = Zc::C('app.https.img_cdn1') . $file;

        if (!file_exists($basePath)) {
            return $this -> avatarThumb($old_file); // 新地址找不到头像,从旧地址查找头像
        }
        return  $imageUrl;


    }


    /**
     * 压缩头像
     * @param string $file     图片的绝对路径
     * @param bool   $ifremove 是否删除原图,默认删除
     */
    public function newAvatar($file,$ifremove= true) {
        $file = strtolower($file);
        $image_info = @getimagesize($file);

        $ifresize = false; // 是否生成缩略图
        $thumb_width = Zc::C('avatar.images.width');
        $thumb_height = Zc::C('avatar.images.height');
        $thumb_ext = Zc::C('avatar.images.ext');

        if ($thumb_width && $thumb_height && $thumb_ext) {
            $thumb_width = explode(',', $thumb_width);
            $thumb_height = explode(',' , $thumb_height);
            $thumb_ext = explode(',', $thumb_ext);
            // print_r($thumb_width);
            // print_r($thumb_height);
            // print_r($thumb_ext);

            if (count($thumb_width) == count($thumb_height) && count($thumb_height) == count($thumb_ext)) {
                $ifresize = true;
            }
        }
        
        // 计算缩略图的尺寸
        if ($ifresize) {
            for ($i = 0; $i < count($thumb_width); $i++) {
                $imgscaleto = ($thumb_width[$i] == $thumb_height[$i]);

                if ($image_info[0] < $thumb_width[$i]) {
                    $thumb_width[$i] = $image_info[0];
                }

                if ($image_info[1] < $thumb_height[$i]) {
                    $thumb_height[$i] = $image_info[1];
                }

                $thumb_wh = $thumb_width[$i] / $thumb_height[$i];
                $src_wh = $image_info[0] / $image_info[1];

                if ($thumb_wh <= $src_wh) {
                    $thumb_height[$i] = $thumb_width[$i] * ($image_info[1] / $image_info[0]);
                } else {
                    $thumb_width[$i] = $thumb_height[$i] * ($image_info[0] / $image_info[1]);
                }


                if ($imgscaleto) {
                    $scale[$i] = $src_wh > 1 ? $thumb_width[$i] : $thumb_height[$i];
                } else {
                    $scale[$i] = 0;
                }

            }
        }


        // 产生缩略图
        if ($ifresize) {
            $resizeImage = HelperFactory::getResizeImageHelper();
            $save_path = rtrim(UPLOAD_AVATAR_DIR,'/');


            for ($i = 0; $i < count($thumb_width); $i++) {

                $arr = $resizeImage -> newImg($file,$thumb_width[$i],$thumb_height[$i],$scale[$i],$thumb_ext[$i] . '.',$save_path,true);
            }

        }

        //删除原图
        if ($ifremove && is_file($file)) {
            @unlink($file);
        }

    }

    //获得指定文件夹下面的图片链接
    /**
     * 获取品牌图
     * @param  string $file
     * @return string
     */


    public function getGroupImage($file,$timeStamp = '2016-12-15')
    {
        if (empty($file)) {
            return Zc::C('app.https.img_cdn1') . str_replace(DIR_FS_DOCUMENT_ROOT, '', $this->defaultGoodsImage('240'));
        }

        $basePath = Zc::C('upload.dir.fs') . 'shop/voucher/1/';
        $baseUrl = Zc::C('app.https.img_cdn1') . 'data/upload/shop/voucher/1/' . $file . '?'.$timeStamp;

        if (!file_exists($basePath . $file)) {
            return Zc::C('app.https.img_cdn1') . str_replace(DIR_FS_DOCUMENT_ROOT, '', $this->defaultGoodsImage('240'));
        }

        return $baseUrl;
    }

    //获得指定文件夹下面的图片链接
    /**
     * 获取品牌图
     * @param  string $file
     * @return string
     */
    public function getImageByDir($file = '', $image_size = false)
    {
        if (empty($file)) {
            return Zc::C('app.https.img_cdn1') . str_replace(DIR_FS_DOCUMENT_ROOT, '', $this->defaultGoodsImage('240'));
        }
        $basePath = Zc::C('upload.authinfo.dir.fs');
        $baseUrl = Zc::C('app.https.img_cdn1') . 'data/upload/shop/authinfo/';
        if (!file_exists($basePath . $file)) {
            return '';
        }
        return $baseUrl . $file;
    }
    public function uploadAvatar($member_id, $file_key)
    {

        try { 
            $upload = HelperFactory::getUploadFileHelper();

            // $dir = Zc::C('default.dir') . 'avatar/';
            $dir = UPLOAD_AVATAR_DIR;
            // $upload->set('default_dir', $dir);
            $upload->set('allow_type', array('jpg', 'jpeg', 'gif', 'png'));
            // $upload->set('thumb_width', Zc::C('avatar.images.width'));
            // $upload->set('thumb_height', Zc::C('avatar.images.height'));
            // $upload->set('thumb_ext', Zc::C('avatar.images.ext'));
            // $upload->set('fprefix', 1);
            // $file_key = key($_FILES);

            // $ext = strtolower(pathinfo($_FILES[$file_key]['name'], 4));
            // $upload->set('file_name', 'avatar' . '_' . $member_id . '.' . $ext);

            $upload->set('ifremove', true);
            if (!empty($_FILES[$file_key]['name'])) {

                $result = $upload->upfile($file_key,$dir);
                if (!$result) {
                    $this->errorMessage = $upload->error;
                    return false;
                }
            } else {

                $this->errorMessage = '文件为空';
                return false;
            }

        } catch (Exception  $e) {
            $this->errorMessage = $e->getMessage();
            return false;
        }
        $this -> newAvatar( $dir . $upload->get('file_name') ); // 传入图片绝对路径. 在此生成缩略图,避免对其他图片上传造成影响
        return RELATIVE_UPLOAD_AVATAR_DIR . $upload->get('file_name'); // 返回图片的相对路径
    }
    //获得指定文件夹下面的图片链接
    /**
     * 获取品牌图
     * @param  string $file
     * @return string
     */
    public function uploadAuthInfo($member_id, $file_key)
    {
        try {
            $upload = HelperFactory::getUploadFileHelper();
            $baseUrl = Zc::C('app.https.img_cdn1') . 'data/upload/shop/avatar/';
            $dir = Zc::C('default.dir') . 'authinfo/';
            $upload->set('default_dir', $dir);
            $upload->set('allow_type', array('jpg', 'jpeg', 'gif', 'png'));
            $upload->set('thumb_width', 300);
            $upload->set('thumb_height', 299);
            // $file_key = key($_FILES);

            $ext = strtolower(pathinfo($_FILES[$file_key]['name'], 4));
            $upload->set('file_name', $file_key . '_' . $member_id . '.' . $ext);
            $upload->set('thumb_ext', '_new');
            $upload->set('ifremove', true);
            if (!empty($_FILES[$file_key]['name'])) {
                $result = $upload->upfile($file_key);
                if (!$result) {
                    $this->errorMessage = $upload->error;
                    return false;
                }
            } else {
                $this->errorMessage = '文件为空';
                return false;
            }
        } catch (Exception  $e) {
            $this->errorMessage = $e->getMessage();
            return false;
        }

        return $upload->get('thumb_image');
    }
    //上传评价图片
    public function uploadEvaluateImage($member_id, $file_key)
    {
        try {
            $member_id = $_SESSION['member_id'];
            // usleep(5000);
            $baseUrl = Zc::C('app.https.img_cdn1') . 'data/upload/shop/evaluate/';
            $dir = Zc::C('default.dir') . 'evaluate/';
            $upload = HelperFactory::getUploadFileHelper();
            $upload->set('default_dir', $dir);
            $upload->set('allow_type', array('jpg', 'jpeg', 'gif', 'png'));

            // $ext = strtolower(pathinfo($_FILES[$file_key]['name'], PATHINFO_EXTENSION));
            //$upload->set('file_name', 'eval'.date('ymdHi').mt_rand(100,999).'.'.$ext);
            //$upload->set('ifremove', true);
            if (!empty($_FILES[$file_key]['name'])) {
                $result = $upload->upfile($file_key);
                if (!$result) {
                    $this->errorMessage = $upload->error;
                }
            } else {
                $this->errorMessage = '上传图片为空';
            }
            $imageName = $upload->get('file_name');
            $image_path = $upload->getSysSetPath() . $imageName;
            $imagePath = Zc::C('app.https.img_cdn1') . '/data/upload/' . $dir . $image_path;
        } catch (Exception $e) {
            $this->errorMessage = $upload->error;
        }

        return array('imagename' => $imageName, 'imagepath' => $imagePath);

    }
    public function getEvaluateImage($file){
        if (empty($file)) {
            return Zc::C('app.https.img_cdn1') . str_replace(DIR_FS_DOCUMENT_ROOT, '', $this->defaultGoodsImage('240'));
        }

        $basePath = Zc::C('upload.dir.fs') . 'shop/evaluate/';
        $baseUrl = Zc::C('app.https.img_cdn1') . 'data/upload/shop/evaluate/';
        if (!file_exists($basePath . $file)) {
            return Zc::C('app.https.img_cdn1') . str_replace(DIR_FS_DOCUMENT_ROOT, '', $this->defaultGoodsImage('240'));
        }

        return $baseUrl . $file;

    }

    //上传退款图片
    public function uploadRefoundImage($member_id, $file_key)
    {
        try {
            $member_id = $_SESSION['member_id'];
            // usleep(5000);
            $baseUrl = Zc::C('app.https.img_cdn1') . 'data/upload/shop/refound/';
            $dir = Zc::C('default.dir') . 'refound/';
            $upload = HelperFactory::getUploadFileHelper();
            $upload->set('default_dir', $dir);
            $upload->set('allow_type', array('jpg', 'jpeg', 'gif', 'png'));

            // $ext = strtolower(pathinfo($_FILES[$file_key]['name'], PATHINFO_EXTENSION));
            //$upload->set('file_name', 'eval'.date('ymdHi').mt_rand(100,999).'.'.$ext);
            //$upload->set('ifremove', true);
            if (!empty($_FILES[$file_key]['name'])) {
                $result = $upload->upfile($file_key);
                if (!$result) {
                    $this->errorMessage = $upload->error;

                }
            } else {
                $this->errorMessage = '上传图片为空';
            }
            $imageName = $upload->get('file_name');
            $image_path = $upload->getSysSetPath() . $imageName;
            $imagePath = Zc::C('app.https.img_cdn1') . '/data/upload/' . $dir . $image_path;
        } catch (Exception $e) {
            $this->errorMessage = $upload->error;
        }

        return array('imagename' => $imageName, 'imagepath' => $imagePath);

    }

    /**
     * 上传商品服务图片
     */
    public function uploadGoodsServiceImage($file_key) {
        try {

            $dir = UPLOAD_GOODSSERVICE_DIR;
            
            $upload = HelperFactory::getUploadFileHelper();

            $upload->set('fprefix', 1);
            // $upload->set('default_dir', $dir);

            if (!empty($_FILES[$file_key]['name'])) {
                $result = $upload->upfile($file_key,$dir);
                if (!$result) {
                    $this->errorMessage = $upload->error;

                }
            } else {
                $this->errorMessage = '上传图片为空';

            }

            $imageName = $upload->get('file_name');
            $imagePath = Zc::C('app.https.img_cdn1') . RELATIVE_UPLOAD_GOODSSERVICE_DIR  . $imageName;

        } catch (Exception $e) {
            $this->errorMessage = $upload->error;
        }

        return array('imagename' => $imageName, 'imagepath' => $imagePath);
    }

   
  
    /**
     * 上传商品分类图片
     */
    public function uploadGoodsClassImage($file_key) {
        try {

            $dir = UPLOAD_GOODS_CLASS_DIR;
            
            $upload = HelperFactory::getUploadFileHelper();

            $upload->set('fprefix', 1);
            // $upload->set('default_dir', $dir);

            if (!empty($_FILES[$file_key]['name'])) {
                $result = $upload->upfile($file_key,$dir);
                if (!$result) {
                    $this->errorMessage = $upload->error;

                }
            } else {
                $this->errorMessage = '上传图片为空';

            }

            $imageName = $upload->get('file_name');
            $imagePath = Zc::C('app.https.img_cdn1') . RELATIVE_UPLOAD_GOODS_CLASS_DIR  . $imageName;

        } catch (Exception $e) {
            $this->errorMessage = $upload->error;
        }

        return array('imagename' => $imageName, 'imagepath' => $imagePath);
    }

    /**
     * 上传积分商城商品图片 
     */
    public function uploadIntegralGoodsImage($file_key) {
        try {

            $dir = UPLOAD_INTEGRAL_DIR;
            // var_dump($dir);exit;
            $upload = HelperFactory::getUploadFileHelper();

            $upload->set('thumb_ext', Zc::C('goods.images.ext'));
            $upload->set('fprefix', 1);
            // $upload->set('default_dir', $dir);

            if (!empty($_FILES[$file_key]['name'])) {
                $result = $upload->upfile($file_key,$dir);
                if (!$result) {
                    $this->errorMessage = $upload->error;

                }
            } else {
                $this->errorMessage = '上传图片为空';

            }

            $imageName = $upload->get('file_name');
            $imagePath = Zc::C('app.https.img_cdn1') . RELATIVE_UPLOAD_INTEGRAL_DIR  . $imageName;

        } catch (Exception $e) {
            $this->errorMessage = $upload->error;
        }

        return array('imagename' => $imageName, 'imagepath' => $imagePath);
    }



    //上传商品图片
    public function uploadGoodsImage($file_key,$goodsShapeCode='')
    {
        try {

            // usleep(5000);
//            $baseUrl = Zc::C('app.https.img_cdn1') . 'data/upload/shop/store/1/';
            $dir = Zc::C('goods.dir');
            if($goodsShapeCode){
                $dir = Zc::C('goods.dir').$goodsShapeCode."/";
                if(!is_dir($dir.$goodsShapeCode)){
                    @mkdir($dir, 0777, true);
                }
            }

            $upload = HelperFactory::getUploadFileHelper();

            $upload->set('thumb_width', Zc::C('goods.images.width'));
            $upload->set('thumb_height', Zc::C('goods.images.height'));
            $upload->set('thumb_ext', Zc::C('goods.images.ext'));
            $upload->set('fprefix', 1);
            $upload->set('default_dir', $dir);
//            $upload->set('allow_type', array('jpg', 'jpeg', 'gif', 'png'));


            if (!empty($_FILES[$file_key]['name'])) {
                $result = $upload->upfile($file_key);
                if (!$result) {
                    $this->errorMessage = $upload->error;

                }
            } else {
                $this->errorMessage = '上传图片为空';

            }
            $imageName = $upload->get('file_name');
            if($goodsShapeCode) {
                $imageName = $goodsShapeCode . "/" . $imageName;
            }
//            $image_path = $upload->getSysSetPath() . $imageName;
            $imagePath = Zc::C('app.https.img_cdn1') . '/data/upload/' . $dir  . $imageName;
        } catch (Exception $e) {
            $this->errorMessage = $upload->error;


        }

        return array('imagename' => $imageName, 'imagepath' => $imagePath);

    }


       //上传相册图片 hyq
    public function uploadImage($file_key,$imageName = '')
    {
        try {

            $dir = Zc::C('goods.dir');
            $upload = HelperFactory::getUploadFileHelper();

            $upload->set('thumb_width', Zc::C('goods.images.width'));
            $upload->set('thumb_height', Zc::C('goods.images.height'));
            $upload->set('thumb_ext', Zc::C('goods.images.ext'));
            $upload->set('fprefix', 1);
            $upload->set('default_dir', $dir);
            if ($imageName) {
                $upload->set('file_name', $imageName);
            }

            if (!empty($_FILES[$file_key]['name'])) {
                $result = $upload->upfile($file_key);
                if (!$result) {
                    $this->errorMessage = $upload->error;

                }
            } else {
                $this->errorMessage = '上传图片为空';

            }
            $imageName = $upload->get('file_name');

            $imagePath = Zc::C('app.https.img_cdn1') . '/data/upload/' . $dir  . $imageName;
        } catch (Exception $e) {
            $this->errorMessage = $upload->error;


        }

        return array('imagename' => $imageName, 'imagepath' => $imagePath);

    }



    public function getGoodsImagePath($name)
    {
        $dir = Zc::C('goods.dir');
        $imagePath = Zc::C('app.https.img_cdn1') . '/data/upload/' . $dir . $name;
        return $imagePath;
    }


    /**
     * 获取相册图片的绝对路径 hyq
     * @param string $name 图片名称
     * @return string
     */
    public function getImagePath($name) {
        $dir = Zc::C('goods.dir');
        $imagePath = Zc::C('upload.dir.fs') .  $dir . $name;
        return $imagePath;
    }




    public function getRefundImages($file){
        if (empty($file)) {
            return Zc::C('app.https.img_cdn1') . str_replace(DIR_FS_DOCUMENT_ROOT, '', $this->defaultGoodsImage('240'));
        }

        $basePath = Zc::C('upload.dir.fs') . '';
        $baseUrl = Zc::C('app.https.img_cdn1') . '';
        if( $_GET['pre_sale_id'] ){
           // var_dump($basePath . $file,$file);exit;
        }
        if (!file_exists($basePath . $file)) {
            //return Zc::C('app.https.img_cdn1') . str_replace(DIR_FS_DOCUMENT_ROOT, '', $this->defaultGoodsImage('240'));
        }

        return $baseUrl . $file;

    }
    
    
    public function getPreSaleImage($file){
          
        return Zc::C('app.https.img_cdn1') . $file;
    
    }


    /**
     * 获取优惠券图片
     * @param  string $file
     * @return string
     */
    public function getVorcherImages($file){
        if (empty($file)) {
            return Zc::C('app.https.img_cdn1') . str_replace(DIR_FS_DOCUMENT_ROOT, '', $this->defaultGoodsImage('240'));
        }

        $basePath = Zc::C('upload.dir.fs') . 'shop/voucher/1/';
        $baseUrl = Zc::C('app.https.img_cdn1') . 'data/upload/shop/voucher/1/';
        if (!file_exists($basePath . $file)) {
            return Zc::C('app.https.img_cdn1') . str_replace(DIR_FS_DOCUMENT_ROOT, '', $this->defaultGoodsImage('240'));
        }

        return $baseUrl . $file;
    }

    /**
     * 商品goods common 下主图的商品
     * @param unknown $image
     * @param number $timeStamp
     */
    public function getGoodsMainImage($image,$timeStamp = 20170311){
        /////E:\yifanjie\Apache24\htdocs\yifanjie_new\data\upload\shop\store\goods\1\1_05239782809753827.jpg
        $mainImageDir = Zc::C('upload.common.goods.dir.fs');
        $imageUrl = str_replace(DIR_FS_DOCUMENT_ROOT,Zc::C('app.https.img_cdn1'),$mainImageDir.'1/'.$image . "?" . $timeStamp);
         $imageUrl = str_replace(DIR_FS_DOCUMENT_ROOT,"http://www.yifanjie.com/",$mainImageDir.'1/'.$image . "?" . $timeStamp);

        return $imageUrl;
    }

    /**
     * 获取限时活动图片
     * @param $file
     * @param string $type
     * @param int $store_id
     * @return string
     */
    public function getXianshiImage($file)
    {
        if (empty($file)) {
           // return Zc::C('app.https.img_cdn1') . str_replace(DIR_FS_DOCUMENT_ROOT, '', $this->defaultGoodsImage('240'));
        }

        //$basePath = Zc::C('upload.dir.fs') . 'shop/voucher/1/';
        $baseUrl = Zc::C('app.https.img_cdn1') . 'data/upload/shop/voucher/1/';
        //$baseUrl = 'http://www.yifanjie.com/data/upload/shop/voucher/1/';

        return $baseUrl . $file;

    }

    //上传限时活动图片
    public function uploadXianshiImage($file_key)
    {
        $this ->errorMessage ='success';
        try {
            $dir = Zc::C('default.dir') . 'shop/voucher/1/';
            $upload = HelperFactory::getUploadFileHelper();
            $upload->set('default_dir', $dir);
            $upload->set('allow_type', array('jpg', 'jpeg', 'gif', 'png'));

            if (!empty($_FILES[$file_key]['name'])) {
                $result = $upload->upfile($file_key,null,true);
                if (!$result) {
                    $this->errorMessage = $upload->error;

                }
            } else {
                $this->errorMessage = '上传图片为空';

            }
            $imageName = $upload->get('file_name');
            $imagePath = Zc::C('app.https.img_cdn1') . '/data/upload/' . $dir  . $imageName;
        } catch (Exception $e) {
            $this->errorMessage = $upload->error;
        }
        return array(
            'imagename' => $imageName,
            'imagepath' => $imagePath,
            'state' => $this -> errorMessage
        );

    }

    /**
     * 上传品牌图片
     * @param $file_key
     */
    public function uploadBrandImage($file_key)
    {
        $statue = 0;
        try {
            $dir = Zc::C('default.dir') . 'shop/brand/';
            $upload = HelperFactory::getUploadFileHelper();
            $upload->set('default_dir', $dir);
            $upload->set('allow_type', array('jpg', 'jpeg', 'gif', 'png'));
            if (!empty($_FILES[$file_key]['tmp_name'])) {
                $result = $upload -> upfile($file_key);
                if (!$result) {
                    $this->errorMessage = $upload->error;
                }else{
                    $statue = 1;
                }
            } else {
                $this->errorMessage = '上传图片为空';
            }
            $imageName = $upload -> get('file_name');
            $imagePath = Zc::C('app.https.img_cdn1') . '/data/upload/' . $dir . $imageName;
        } catch (Exception $e) {
            $this->errorMessage = $upload->error;
        }
        return array(
            'status' => $statue,
            'url' => $imagePath,
            'name' => $imageName,
            'msg' => $this->errorMessage
        );
    }

    /**
     * 获取商品图地址
     * @param string $file 图片相对路径
     * @param int    $type 图片大小
     * @return string
     */
    public function getProductImagePath($file, $type='') {
        
        if (empty($file)) {
            return Zc::C('app.https.img_cdn1') . str_replace(DIR_FS_DOCUMENT_ROOT, '', $this->defaultGoodsImage('240'));
        }

        $typeArray = explode(',_', ltrim(Zc::C('goods.images.ext'),'_'));

        if ( !in_array($type, $typeArray) ) {
            $type = '1280';
        }

        $file = str_ireplace('.', '_'.$type.'.', $file);

        $imageUrl = Zc::C('app.https.img_cdn1') . $file;

        return $imageUrl;
    }  
      
    /**
     * 获取商品图
     * @param  string $file
     * @param  null|int $type
     * @return string
     */
    public function getRelativeGoodsImage($file, $type = '', $store_id = 1)
    {
        
        if (empty($file)) {
            return Zc::C('app.https.img_cdn1') . str_replace(DIR_FS_DOCUMENT_ROOT, '', $this->defaultGoodsImage('240'));
        }
        
        $type_array = explode(',_', ltrim(Zc::C('goods.images.ext'), '_'));
        if (!in_array($type, $type_array)) {
            $type = '1280';
        }
    
        $search_array = explode(',', Zc::C('goods.images.ext'));
        //$file = str_ireplace($search_array, '', $file);
    
        //$file = str_ireplace('.', '_' . $type . '.', $file);
    
        //$fname = basename($file);
        
        
        $imageUrl = Zc::C('app.https.img_cdn1') . $file;
         
    
        return $imageUrl;
    
    }
    
    /**
     * file 为相对路劲
     * @param unknown $file
     */
    public function getRecommendImageUrl($file)
    {
        if (!$file) {
            return false;
        }
        //$fanmiImageDir = Zc::C('upload.common.fanmi.dir.fs');
        //$imageUrl = Zc::C('app.https.img_cdn1')  . $file;
        $imageUrl = 'http://www.yifanjie.com/'  . $file;
        return $imageUrl;
    }


    //上传限时活动图片
    public function uploadRecommendImage($file_key)
    {
        $this ->errorMessage ='success';
        try {
            $dir = Zc::C('default.dir') . 'fanmi/';
            $upload = HelperFactory::getUploadFileHelper();
            $upload->set('default_dir', $dir);
            $upload->set('allow_type', array('jpg', 'jpeg', 'gif', 'png'));

            if (!empty($_FILES[$file_key]['name'])) {
                $result = $upload->upfile($file_key);
                if (!$result) {
                    $this->errorMessage = $upload->error;

                }
            } else {
                $this->errorMessage = '上传图片为空';

            }
            $imageName = $upload->get('file_name');
            $imagePath = Zc::C('app.https.img_cdn1') . '/data/upload/' . $dir  . $imageName;
        } catch (Exception $e) {
            $this->errorMessage = $upload->error;
        }
        return array(
            'imagename' => $imageName,
            'imagepath' => $imagePath,
            'state' => $this -> errorMessage
        );

    }
    
    /**
     * file 为相对路劲
     * @param unknown $file
     */
    public function getRecommendContentImageUrl($file)
    {
        if (!$file) {
            return false;
        }
        
        //$fanmiImageDir = Zc::C('upload.common.fanmi.dir.fs');
        
        $imageUrl =  Zc::C('app.https.img_cdn1') . $file ;
        return $imageUrl;
    }

    /**
     * 保存微信头像
     * @param $url
     * @param string $filename
     * @return bool|string
     */
    function saveWxHeadImg($wxHeadImgUrl,$member_id)
    {

        if (empty($wxHeadImgUrl)) {       //如果$wxHeadImgUrl地址为空，直接退出
            return false;
        }
        $wxHeadImgUrl = str_replace("https", "http", $wxHeadImgUrl);
        $ext = "jpg";        //微信返回的图片路径不带图片格式后缀，故默认jpg
        $avatarName = 'avatar_' . $member_id . '.' . $ext;         //新的头像图像名
        $basePath = Zc::C('upload.dir.fs') . 'shop/avatar/';;
        unlink($basePath . $avatarName);
        ob_start();//打开输出
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $wxHeadImgUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $img = curl_exec($ch);
        $httpCode = curl_getinfo($img, CURLINFO_HTTP_CODE);
        curl_close($ch);
        ob_end_clean();//清除输出并关闭
        IF ($httpCode != 200) {
            return false;
        }
        $fp2 = @fopen($basePath . $avatarName, "a");
        fwrite($fp2, $img);//向当前目录写入图片文件，并重新命名
        fclose($fp2);
        return $avatarName;//返回新的文件名
    }

    /**
     * cps推广规则图片上传
     * @param $imageName
     * @return array
     */
    public function saveCpsRulePic($imageName){

        try {

            // $dir = Zc::C('adv.dir'); 可以配置后使用
            $upload = HelperFactory::getUploadFileHelper();
            $dir = UPLOAD_CPS_DIR;
            $upload->set('allow_type', array('jpg','jpeg','gif','png'));

            if (!empty($_FILES[$imageName]['name'])) {
                $result = $upload->upfile($imageName,$dir);

                if (!$result) {
                    $this->errorMessage = $upload->error;

                }
            } else {

                $this->errorMessage = '上传图片为空';

            }
            $imageName = $upload->get('file_name');

            $imagePath = Zc::C('app.https.img_cdn1') . '/upload/cps/'. $imageName;
        } catch (Exception $e) {
            $this->errorMessage = $upload->error;


        }
        return array('imagename' => $imageName, 'imagepath' => $imagePath, 'message'=>$this -> errorMessage);

    }

	 /* *********************************小程序**********/
	
	
	/**欣日淘-小程序的展示图的上传
	 * @param $imageName
	 * @param $linkNum  用来创建文件夹
	 * @param $thumb 是否压缩
	 */
    public function uploadMainImages($imageName,$linkNum,$thumb=true){
        try{
        	if(!$imageName || !$linkNum){
        		return false;
	        }
	        $store_id = 2;
	        $linkNum = strtolower($linkNum);
	        //$dir = 'data/upload/shop/store/goods/' . $store_id . '/'.$linkNum.'/';
	        $dir = Zc::C('default.dir') . 'shop/store/goods/' . $store_id . '/'.$linkNum.'/';
	        if ( !file_exists($dir)) {
		        @mkdir($dir,0777,true);
	        }
	        $upload = HelperFactory::getUploadFileHelper();
	
	        $upload->set('default_dir', $dir);
	        if($thumb){
		        $upload->set('thumb_width', '65,130,240,420');
		        $upload->set('thumb_height', '65,130,240,420');
		        $upload->set('thumb_ext','_65,_130,_240,_420');
	        }
	       
	        $upload->set('allow_type', array('jpg','jpeg','gif','png'));
	        if (!empty($_FILES[$imageName]['name'])) {
		        $result = $upload->upfile($imageName);
		        if (!$result) {
			        $this->errorMessage = $upload->error;
		        }
	        }
	        $imageName = $upload->get('file_name');
	        
	        $imagePath = Zc::C('app.https.img_cdn4') .'data/upload/'. $dir  . $imageName;
	        
        }catch(Exception $e){
            $this -> errorMessage = $upload -> error;
        }
	
	    return array('status'=> 'success','imagename' => $imageName, 'imagepath' => $imagePath, 'msg'=>$this -> errorMessage);
    
    
    
    }
	
	/**获得主图
	 * @param $imageName
	 * @return bool|string
	 */
    public function getMainImage($imageName){
    	if(!$imageName){
    		return false;
	    }
	    
	    $dir = 'shop/store/goods/2/';
	    $imagePath = Zc::C('app.https.img_cdn4') .'data/upload/'. $dir  . $imageName;
	    return $imagePath;
    }
	
	/**
	 * 获取bannr的路径 hyq 这是旧的
	 */
	public function getBannerPath($file) {
		// $baseUrl = Zc::C('app.httmps.img_cdn1') . Zc::C('adv.dir');
		$baseUrl = Zc::C('app.https.img_cdn4') . 'data/upload/shop/adv/';
		return $baseUrl . $file;
	}
	/**
	 * 上传Banner图片 这是旧的
	 */
	public function uploadBannerImage($file_key,$imageName = '',$thumb = false)
	{
		try {
			
			// $dir = Zc::C('adv.dir'); 可以配置后使用
			$dir = 'shop/adv/';
			$upload = HelperFactory::getUploadFileHelper();
			
			if ($thumb) {
				$upload->set('thumb_width', '320,420,750');
				$upload->set('thumb_height', '320,420,750');
				$upload->set('thumb_ext','_320,_420,_750');
			}
			
			$upload->set('default_dir', $dir);
			$upload->set('allow_type', array('jpg','jpeg','gif','png'));
			if ($imageName) {
				$upload->set('file_name', $imageName);
			}
			
			if (!empty($_FILES[$file_key]['name'])) {
				$result = $upload->upfile($file_key);
				if (!$result) {
					$this->errorMessage = $upload->error;
					
				}
			} else {
				$this->errorMessage = '上传图片为空';
				
			}
			$imageName = $upload->get('file_name');
			
			$imagePath = Zc::C('app.https.img_cdn4') . '/data/upload/' . $dir  . $imageName;
		} catch (Exception $e) {
			$this->errorMessage = $upload->error;
			
			
		}
		
		return array('imagename' => $imageName, 'imagepath' => $imagePath, 'message'=>$this -> errorMessage);
		
	}
	/**
	 * 上传活动(专题等)图片 这是旧的
	 * @param string $file_key
	 * @param string $$activityType 活动类型
	 * @return string|array
	 */
	public function uploadActivityImage($file_key,$activityType) {
		try {
			
			$dir = UPLOAD_ACTIVITY_DIR;
			
			if ( !$activityType ) {
				$activityType = 'special_subject';
			}
			
			switch( $activityType ) {
				case 'special_subject':
					$dir .= $activityType . '/' . date('Y-m') . '/';
					break;
				case 'wx_activity':
					$dir .= $activityType . '/' . date('Y-m') . '/';
					break;
			}
			
			if ( !file_exists($dir) ) {
				@mkdir($dir,0777,true);
			}
			
			
			$upload = HelperFactory::getUploadFileHelper();
			
			$upload->set('fprefix', 1);
			// $upload->set('default_dir', $dir);
			
			if (!empty($_FILES[$file_key]['name'])) {
				$result = $upload->upfile($file_key,$dir);
				if (!$result) {
					$this->errorMessage = $upload->error;
					
				}
			} else {
				$this->errorMessage = '上传图片为空';
				
			}
			
			$imageName = $upload->get('file_name');
			$imageRelativePath = str_replace(DIR_FS_DOCUMENT_ROOT, '', $dir)  . $imageName;
			$imagePath = Zc::C('app.https.img_cdn4') . $imageRelativePath;
			
		} catch (Exception $e) {
			$this->errorMessage = $upload->error;
		}
		
		return array('image_relative_path' => $imageRelativePath, 'imagepath' => $imagePath);
	}
	
	/**
	 * 获取活动图片(专题图片,专题商品图等)
	 * @param string $file 图片相对路径 这是旧的
	 */
	public function getActivityImage($file) {
		
		$basePath = DIR_FS_DOCUMENT_ROOT;
		// var_dump($basePath);
		if (!file_exists($basePath . $file)) {
			//return Zc::C('app.https.img_cdn1') . str_replace(DIR_FS_DOCUMENT_ROOT, '', $this->defaultGoodsImage('240'));
		}
		$baseUrl = Zc::C('app.https.img_cdn4') . $file;
		return $baseUrl;
		
	}
	
	/**获得默认的图片 当前域名下
	 * @return string
	 */
	public function getDefaultDetailImage(){
		
			return Zc::C('app.http.domain') .'view_controller/yifanjie-business/views/static/images/bpcd_nodetail.png';
		
	}
	
	/**获得微信小程序反馈的图片
	 * @param $img
	 */
	public function getWxFeedbackImageSrc($img){
		if(!$img){
			return false;
		}
		$basePath = UPLOAD_WXFEEDBACK_DIR;
		if (!file_exists($basePath . $img)) {
			return false;
		}else {
			$baseUrl = Zc::C('app.https.img_cdn4') . RELATIVE_UPLOAD_WXFEEDBACK_DIR . $img;
		}
		return $baseUrl;
	
	}
	/**将图片上传到B端的服务器
	 * @param $abImagePath
	 */
	public function moveImageToB($abImagePath){
		if(!file_exists($abImagePath)){
			return false;
		}
		$array = ['fileField' => '@'.$abImagePath];
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, 'www.xinritao.com/new-yfj-admin-center/marketing/feedback/get_wx_image_move_to_b');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT,60); //设置超时时间
		curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $array);
		$res = curl_exec($ch);
		curl_close($ch);
		return $res;
		
		
		
	}
	
	/**
	 * 上传微信小程序的反馈图片
	 */
	
	public function uploadFeedbackImage($file_key){
		$dir = UPLOAD_WXFEEDBACK_DIR;
		if ( !file_exists($dir) ) {
			@mkdir($dir,0777,true);
		}
		$upload = HelperFactory::getUploadFileHelper();
		
		$upload->set('fprefix', 1);
		// $upload->set('default_dir', $dir);
		
		if (!empty($_FILES[$file_key]['name'])) {
			$result = $upload->upfile($file_key,$dir);
			if (!$result) {
				$this->errorMessage = $upload->error;
				
			}
		} else {
			$this->errorMessage = '上传图片为空';
			
		}
		$this->errorMessage = '';
		$imageName = $upload->get('file_name');
		//$imageRelativePath = RELATIVE_UPLOAD_WXFEEDBACK_DIR . $imageName;
		$abImagePath = UPLOAD_WXFEEDBACK_DIR.$imageName;
		$res = $this -> moveImageToB($abImagePath);
		return $res;
		
	}
	
	
	/**
	 * 上传微信小程序的反馈图片
	 */
	
	public function uploadBFeedbackImage($file_key){
		$dir = UPLOAD_WXFEEDBACK_DIR;
		if ( !file_exists($dir) ) {
			@mkdir($dir,0777,true);
		}
		$upload = HelperFactory::getUploadFileHelper();
		
		$upload->set('fprefix', 1);
		// $upload->set('default_dir', $dir);
		$upload-> set('file_name',$_FILES[$file_key]['name']);
		if (!empty($_FILES[$file_key]['name'])) {
			$result = $upload->upfile($file_key,$dir);
			if (!$result) {
				$this->errorMessage = $upload->error;
				
			}
		} else {
			$this->errorMessage = '上传图片为空';
			
		}
		$this->errorMessage = '';
		$imageName = $upload->get('file_name');
		$imageRelativePath = RELATIVE_UPLOAD_WXFEEDBACK_DIR . $imageName;
		$imagePath = Zc::C('app.https.img_cdn4') . $imageRelativePath;
		//var_dump(88);
		return array('imageName' => $imageName, 'imagepath' => $imagePath,'msg'=>$this->errorMessage);
		
	}
	
	
}

