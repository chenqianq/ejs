<?php

/**
 * 文件上传类
 *
 *
 *
 * @package    library* www.yifanjie.com 专业团队 提供售后服务
 */
class UploadFile
{
   private $imgDirType=1;
    private $ds = '/';
    /**
     * 文件存储路径
     */
    private $save_path;
    /**
     * 允许上传的文件类型
     */
    private $allow_type = array('gif', 'jpg', 'jpeg', 'bmp', 'png');
    /**
     * 允许的最大文件大小，单位为KB
     */
    private $max_size = '10240';
    /**
     * 改变后的图片宽度
     */
    private $thumb_width = 0;
    /**
     * 改变后的图片高度
     */
    private $thumb_height = 0;
    /**
     * 生成扩缩略图后缀
     */
    private $thumb_ext = false;
    /**
     * 生成裁剪图
     */
    private $tailor = false;
    /**
     * 裁剪图宽度
     */
    private $tailor_widht = array(750, 768);
    /**
     * 裁剪图高度
     */
    private $tailor_height = 350;
    /**
     * 允许的图片最大高度，单位为像素
     */
    private $upload_file;
    /**
     * 是否删除原图
     */
    private $ifremove = true;
    public $old_file_name;
    /**
     * 上传文件名
     */
    public $file_name;
    /**
     * 上传文件后缀名
     */
    private $ext;
    /**
     * 上传文件新后缀名
     */
    private $new_ext;
    /**
     * 默认文件存放文件夹
     */
    private $default_dir;


    /**
     * 错误信息
     */
    public $error = '';
    /**
     * 生成的缩略图，返回缩略图时用到
     */
    public $thumb_image;
    /**
     * 是否立即弹出错误提示
     */
    private $if_show_error = false;
    /**
     * 是否只显示最后一条错误
     */
    private $if_show_error_one = false;
    /**
     * 文件名前缀
     *
     * @var string
     */
    private $fprefix;

    /**
     * 是否允许填充空白，默认允许
     *
     * @var unknown_type
     */
    private $filling = true;


    private $config;

    /**
     * 初始化
     *
     *    $upload = new UploadFile();
     *    $upload->set('default_dir','upload');
     *    $upload->set('max_size',1024);
     *    //生成4张缩略图，宽高依次如下
     *    $thumb_width    = '300,600,800,100';
     *    $thumb_height    = '300,600,800,100';
     *    $upload->set('thumb_width',    $thumb_width);
     *    $upload->set('thumb_height',$thumb_height);
     *    //4张缩略图名称扩展依次如下
     *    $upload->set('thumb_ext',    '_small,_mid,_max,_tiny');
     *    //生成新图的扩展名为.jpg
     *    $upload->set('new_ext','jpg');
     *    //开始上传
     *    $result = $upload->upfile('file');
     *    if (!$result){
     *        echo '上传成功';
     *    }
     *
     */
    function __construct()
    {
        $this->default_dir =  Zc::C('default.dir');
        $this->config['thumb_type'] = zc::C('thumb.cut_type');
        //加载语言包
//		Language::read('core_lang_index');
//		echo 2;exit;
    }

    /**
     * 设置
     *
     * @param mixed $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        $this->$key = $value;
    }

    /**
     * 读取
     */
    public function get($key)
    {
        return $this->$key;
    }

    /**
     * 上传操作
     *
     * @param string $field 上传表单名
     * @return bool
     */
    public function upfile($field,$dir = NULL,$ifresize=false,$ifremove= false)
    {
        
        //上传文件
        $this->upload_file = $_FILES[$field];
        if ($this->upload_file['tmp_name'] == "") {
            $this->setError("'找不到临时文件，请确认临时文件夹是否存在可写'");
            return false;
        }

        //对上传文件错误码进行验证
        $error = $this->fileInputError();
        if (!$error) {
            return false;
        }
        //验证是否是合法的上传文件
        if (!is_uploaded_file($this->upload_file['tmp_name'])) {
            $this->setError("'非法上传文件'");
            return false;
        }

        //验证文件大小
        if ($this->upload_file['size'] == 0) {
            $error = "禁止上传空文件";
            $this->setError($error);
            return false;
        }
        if ($this->upload_file['size'] > $this->max_size * 1024) {
            $error = "'上传文件大小不能超过'" . $this->max_size . 'KB';
            $this->setError($error);
            return false;
        }

        //文件后缀名
        $tmp_ext = explode(".", $this->upload_file['name']);
        $tmp_name = $tmp_ext[count($tmp_ext) - 2];
        $tmp_ext = $tmp_ext[count($tmp_ext) - 1];
        $this->ext = strtolower($tmp_ext);

        //验证文件格式是否为系统允许
        if (!in_array($this->ext, $this->allow_type)) {
            $error = " 该类型文件不允许上传，允许的文件类型为: " . implode(',', $this->allow_type);
            $this->setError($error);
            return false;
        }

        //检查是否为有效图片
        if (!$image_info = @getimagesize($this->upload_file['tmp_name'])) {
            $error = "'非法图像文件'";
            $this->setError($error);
            return false;
        }

        //设置图片路径
        if ($dir) {
            $this->save_path = $this->createPath($dir);
            
        } else {
            $this->save_path = $this->setPath();
        }
        // echo $this->file_name;exit;
        //设置文件名称
	    $fileName = $this->file_name;
        if (empty($this->file_name)) {
           $this->setFileName();
        }

        if ($dir) {
            $this->file_name = md5(date('Y-m').$tmp_name).".".$this->ext;
        }
       
       if($fileName){
        	//有设置名称，则用设置的指定名称
	       $this->file_name = $fileName;
       }
       
       // var_dump($this->file_name);exit;
        //是否需要生成缩略图
//        $ifresize = true;
        if ($this->thumb_width && $this->thumb_height && $this->thumb_ext) {
            $thumb_width = explode(',', $this->thumb_width);
            $thumb_height = explode(',', $this->thumb_height);
            $thumb_ext = explode(',', $this->thumb_ext);
            if (count($thumb_width) == count($thumb_height) && count($thumb_height) == count($thumb_ext)) $ifresize = true;
        }

        //计算缩略图的尺寸
        if ($ifresize) {
            for ($i = 0; $i < count($thumb_width); $i++) {
                $imgscaleto = ($thumb_width[$i] == $thumb_height[$i]);
                if ($image_info[0] < $thumb_width[$i]) $thumb_width[$i] = $image_info[0];
                if ($image_info[1] < $thumb_height[$i]) $thumb_height[$i] = $image_info[1];
                $thumb_wh = $thumb_width[$i] / $thumb_height[$i];
                $src_wh = $image_info[0] / $image_info[1];
                if ($thumb_wh <= $src_wh) {
                    $thumb_height[$i] = $thumb_width[$i] * ($image_info[1] / $image_info[0]);
                } else {
                    $thumb_width[$i] = $thumb_height[$i] * ($image_info[0] / $image_info[1]);
                }
                if ($imgscaleto) {
                    $scale[$i] = $src_wh > 1 ? $thumb_width[$i] : $thumb_height[$i];
                    if ($this->config['thumb_type'] == 'gd') {
                        $scale[$i] = $src_wh > 1 ? $thumb_width[$i] : $thumb_height[$i];
                    } else {
                        $scale[$i] = $src_wh > 1 ? $thumb_width[$i] : $thumb_height[$i];
                    }
                } else {
                    $scale[$i] = 0;
                }
                if ($thumb_width[$i] == $thumb_height[$i]) {
                    $scale[$i] = $thumb_width[$i];
                } else {
                    $scale[$i] = 0;
                }
            }
        }


        //是否立即弹出错误
        if ($this->if_show_error) {
            echo "<script type='text/javascript'>alert('" . ($this->if_show_error_one ? $error : $this->error) . "');history.back();</script>";
            die();
        }


        if ($this->error != '') return false;

        if(empty($dir)){
            $dir = zc::C('upload.dir.fs') . $this->ds . $this->save_path . $this->ds ;
        }

        if (@move_uploaded_file($this->upload_file['tmp_name'],$dir . $this->file_name)) {

            //产生缩略图
            if ($ifresize) {
                //error_reporting(E_ALL);

                $resizeImage = HelperFactory::getResizeImageHelper();

                $resizeImage -> compressionImg($dir . $this->file_name,0.7,"",$this->upload_file['size']);

                $save_path = rtrim(zc::C('upload.dir.fs') . $this->ds . $this->save_path, '/');


                $resizeImage->newImg($save_path . $this->ds . $this->file_name);

                //var_dump($thumb_ext);var_dump($thumb_ext);var_dump($save_path);
                for ($i = 0; $i < count($thumb_width); $i++) {
                    $resizeImage->newImg($save_path . $this->ds . $this->file_name, $thumb_width[$i], $thumb_height[$i], $scale[$i], $thumb_ext[$i] . '.', $save_path, $this->filling);
                    if ($i == 0) {
                        $resize_image = explode('/', $resizeImage->relative_dstimg);
                        $this->thumb_image = $resize_image[count($resize_image) - 1];
                    }

                }
                if ($this->tailor) {
                    foreach ($this->tailor_widht as $value) {
                        $resizeImage->newImg($save_path . $this->ds . $this->file_name, $value, $this->tailor_height, 1, '_' . $value . '.', $save_path, $this->filling, true);
                    }
                }

            }
            //删除原图

            if ($this->ifremove && is_file($dir . $this->ds . $this->save_path . $this->ds . $this->old_file_name)) {
                @unlink($dir. $this->ds . $this->save_path . $this->ds . $this->old_file_name);
            }
            return true;
        } else {
            $this->setError('文件上传失败:不具有copy操作权限');
            return false;
        }

        return $this->error;


    }


    /**
     * 裁剪指定图片
     *
     * @param string $field 上传表单名
     * @return bool
     */
    public function create_thumb($pic_path)
    {
        if (!file_exists($pic_path)) return;

        //是否需要生成缩略图
        $ifresize = false;
        if ($this->thumb_width && $this->thumb_height && $this->thumb_ext) {
            $thumb_width = explode(',', $this->thumb_width);
            $thumb_height = explode(',', $this->thumb_height);
            $thumb_ext = explode(',', $this->thumb_ext);
            if (count($thumb_width) == count($thumb_height) && count($thumb_height) == count($thumb_ext)) $ifresize = true;
        }
        $image_info = @getimagesize($pic_path);
        //计算缩略图的尺寸
        if ($ifresize) {
            for ($i = 0; $i < count($thumb_width); $i++) {
                $imgscaleto = ($thumb_width[$i] == $thumb_height[$i]);
                if ($image_info[0] < $thumb_width[$i]) $thumb_width[$i] = $image_info[0];
                if ($image_info[1] < $thumb_height[$i]) $thumb_height[$i] = $image_info[1];
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
        //产生缩略图
        if ($ifresize) {
            $resizeImage = new ResizeImage();
            $save_path = rtrim(zc::C('upload.dir.fs') . $this->ds . $this->save_path, '/');
            for ($i = 0; $i < count($thumb_width); $i++) {
//				$resizeImage->newImg($save_path.$this->ds.$this->file_name,$thumb_width[$i],$thumb_height[$i],$scale[$i],$thumb_ext[$i].'.',$save_path,$this->filling);
                $resizeImage->newImg($pic_path, $thumb_width[$i], $thumb_height[$i], $scale[$i], $thumb_ext[$i] . '.', dirname($pic_path), $this->filling);
            }
        }
    }

    /**
     * 获取上传文件的错误信息
     *
     * @param string $field 上传文件数组键值
     * @return string 返回字符串错误信息
     */
    private function fileInputError()
    {
        switch ($this->upload_file['error']) {
            case 0:
                //文件上传成功
                return true;
                break;

            case 1:
                //上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值
                $this->setError(Language::get('upload_file_size_over'));
                return false;
                break;

            case 2:
                //上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值
                $this->setError(Language::get('upload_file_size_over'));
                return false;
                break;

            case 3:
                //文件只有部分被上传
                $this->setError(Language::get('upload_file_is_not_complete'));
                return false;
                break;

            case 4:
                //没有文件被上传
                $this->setError(Language::get('upload_file_is_not_uploaded'));
                return false;
                break;

            case 6:
                //找不到临时文件夹
                $this->setError(Language::get('upload_dir_chmod'));
                return false;
                break;

            case 7:
                //文件写入失败
                $this->setError(Language::get('upload_file_write_fail'));
                return false;
                break;

            default:
                return true;
        }
    }

    /**
     * 创建存储路径
     *
     * @return string 字符串形式的返回结果
     */
    public function createPath($dir = NULL){
        if(empty($dir)){
            $dir = zc::C('upload.dir.fs');
        }
        if(!is_dir($dir)){
            if (!@mkdir($dir,0755, true)) {
                $this->setError('创建目录失败，请检查是否有写入权限');
                return false;
            }
        }
    }
    /**
     * 设置存储路径
     *
     * @return string 字符串形式的返回结果
     */
    public function setPath()
    {
        /**
         * 判断目录是否存在，如果不存在 则生成
         */
        if (!is_dir(zc::C('upload.dir.fs') . $this->ds . $this->default_dir)) {
            $dir = $this->default_dir;
            $dir_array = explode($this->ds, $dir);
            $tmp_base_path = zc::C('upload.dir.fs');
            foreach ($dir_array as $k => $v) {
                $tmp_base_path = $tmp_base_path . $this->ds . $v;
                if (!is_dir($tmp_base_path)) {
                    if (!@mkdir($tmp_base_path, 0755, true)) {
                        $this->setError('创建目录失败，请检查是否有写入权限');
                        return false;
                    }
                }
            }
            unset($dir, $dir_array, $tmp_base_path);
        }

        //设置权限
        @chmod(zc::C('upload.dir.fs') . $this->ds . $this->default_dir, 0755);

        //判断文件夹是否可写
        if (!is_writable(zc::C('upload.dir.fs') . $this->ds . $this->default_dir)) {
            $this->setError(Language::get('upload_file_dir') . $this->default_dir . Language::get('upload_file_dir_cant_touch_file'));
            return false;
        }
        return $this->default_dir;
    }

    /**
     * 设置文件名称 不包括 文件路径
     *
     * 生成(从2000-01-01 00:00:00 到现在的秒数+微秒+四位随机)
     */
    private function setFileName()
    {
        $tmp_name = sprintf('%010d', time() - 946656000)
            . sprintf('%03d', microtime() * 1000)
            . sprintf('%04d', mt_rand(0, 9999));
        $this->file_name = (empty ($this->fprefix) ? '' : $this->fprefix . '_')
            . $tmp_name . '.' . ($this->new_ext == '' ? $this->ext : $this->new_ext);
    }

    /**
     * 设置错误信息
     *
     * @param string $error 错误信息
     * @return bool 布尔类型的返回结果
     */
    private function setError($error)
    {
        $this->error = $error;
    }

    /**
     * 根据系统设置返回商品图片保存路径
     */
    public function getSysSetPath()
    {
        switch ($this->imgDirType) {
            case "1":
                //按文件类型存放,例如/a.jpg
                $subpath = "";
                break;
            case "2":
                //按上传年份存放,例如2011/a.jpg
                $subpath = date("Y", time()) . "/";
                break;
            case "3":
                //按上传年月存放,例如2011/04/a.jpg
                $subpath = date("Y", time()) . "/" . date("m", time()) . "/";
                break;
            case "4":
                //按上传年月日存放,例如2011/04/19/a.jpg
                $subpath = date("Y", time()) . "/" . date("m", time()) . "/" . date("d", time()) . "/";
        }
        return $subpath;
    }

    /**
     * 移动文件夹
     *
     * @param string $oldDir
     * @param string $aimDir
     * @return boolean
     */
    function moveDir($oldDir, $newDir) {
        $newDir = str_replace('', '/', $newDir);
        $newDir = substr($newDir, -1) == '/' ? $newDir : $newDir . '/';
        $oldDir = str_replace('', '/', $oldDir);
        $oldDir = substr($oldDir, -1) == '/' ? $oldDir : $oldDir . '/';
        if (!is_dir($oldDir)) {
            return false;
        }
        if (!file_exists($newDir)) {
            if (!@mkdir($newDir,0755, true)) {
                $this->setError('创建目录失败，请检查是否有写入权限');
                return false;
            }
        }
        @ $dirHandle = opendir($oldDir);
        if (!$dirHandle) {
            return false;
        }
        while (false !== ($file = readdir($dirHandle))) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            if (!is_dir($oldDir . $file)) {
                copy($oldDir . $file, $newDir . $file);
                unlink($oldDir . $file);
            } else {
                continue;
            }
        }
        closedir($dirHandle);
        return rmdir($oldDir);
    }


    /**
     * 上传操作
     *
     * @param string $field 上传表单名
     * @return bool
     */
    public function upOtherfile($field,$dir = NULL,$ifresize=false,$ifremove= false)
    {

        //上传文件
        $this->upload_file = $_FILES[$field];
        if ($this->upload_file['tmp_name'] == "") {
            $this->setError("'找不到临时文件，请确认临时文件夹是否存在可写'");
            return false;
        }

        //对上传文件错误码进行验证
        $error = $this->fileInputError();
        if (!$error) {
            return false;
        }
        //验证是否是合法的上传文件
        if (!is_uploaded_file($this->upload_file['tmp_name'])) {
            $this->setError("'非法上传文件'");
            return false;
        }

        //验证文件大小
        if ($this->upload_file['size'] == 0) {
            $error = "禁止上传空文件";
            $this->setError($error);
            return false;
        }
        if ($this->upload_file['size'] > $this->max_size * 1024) {
            $error = "'上传文件大小不能超过'" . $this->max_size . 'KB';
            $this->setError($error);
            return false;
        }

        //文件后缀名
        $tmp_ext = explode(".", $this->upload_file['name']);
        $tmp_name = $tmp_ext[count($tmp_ext) - 2];
        $tmp_ext = $tmp_ext[count($tmp_ext) - 1];
        $this->ext = strtolower($tmp_ext);

        //验证文件格式是否为系统允许
        if (!in_array($this->ext, $this->allow_type)) {
            $error = " 该类型文件不允许上传，允许的文件类型为: " . implode(',', $this->allow_type);
            $this->setError($error);
            return false;
        }

        //设置路径
        if ($dir) {
            $this->save_path = $this->createPath($dir);

        } else {
            $this->save_path = $this->setPath();
        }
        // echo $this->file_name;exit;
        //设置文件名称
        if (empty($this->file_name)) {
            $this->setFileName();
        }

        if ($dir) {
            $this->file_name = md5(date('Y-m').$tmp_name).".".$this->ext;
        }

        //是否立即弹出错误
        if ($this->if_show_error) {
            echo "<script type='text/javascript'>alert('" . ($this->if_show_error_one ? $error : $this->error) . "');history.back();</script>";
            die();
        }

        if ($this->error != '') return false;

        if(empty($dir)){
            $dir = zc::C('upload.dir.fs') . $this->ds . $this->save_path . $this->ds ;
        }

        if (@move_uploaded_file($this->upload_file['tmp_name'],$dir . $this->file_name)) {
            return true;
        } else {
            $this->setError('文件上传失败:不具有copy操作权限');
            return false;
        }
    }
}
