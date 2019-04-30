<?php

class ZcController
{
    private $route;
    private $layout;
    public $return;

    public function __construct($route)
    {
        $this->route = $route;
    }

    /**
     * 将HTTP请求转发给另一个控制器方法执行
     *
     * 如果是在Controller内部跳转，只要用方法名就可以了
     *
     * @param string $route
     * @param array $args
     * @return ZcAction
     */
    protected function forward($route, $args = array())
    {
        if (strpos($route, '/') === false) {
            //如果没有/，说明是在Controller内部跳转
            $route = substr($this->route, 0, strrpos($this->route, '/')) . '/' . $route;
        }
        return new ZcAction($route, $args);
    }


    protected function redirect($url, $status = 302)
    {

        if (!$status) {
            header('Location: ' . str_replace(array('&amp;', "\n", "\r"), array('&', '', ''), $url));
        } else {
            header('Location: ' . str_replace(array('&amp;', "\n", "\r"), array('&', '', ''), $url), true, $status);
        }

        exit();
    }

    /**
     * 如果以后要采用Theme，那么就可以考虑重写本方法
     */
    private function getViewFile($view)
    {
        //TODO 采用新的配置系统
        if (empty($view)) {
            $view = $this->route;
        }
        return Zc::C(ZcConfigConst::DirFsViewsPage) . $view . '.php';
    }

    /**
     * 寻找Layout的方法
     */
    private function getLayoutFiles($view)
    {
        if (empty($view)) {
            $view = $this->route;
        }
        $dirViewsLayout = Zc::C(ZcConfigConst::DirFsViewsLayout);

        $layoutFiles = array();
        $layoutFiles[] = $dirViewsLayout . 'default.php';

        $path = '';
        $parts = explode('/', $view);
        array_pop($parts);
        foreach ($parts as $part) {
            $path .= $part . '/';
            $layoutFiles[] = $dirViewsLayout . $path . 'default.php';
        }

        $layoutFiles[] = $dirViewsLayout . $view . '.php';

        $layoutFiles = array_reverse($layoutFiles);

        //想知道是按照什么顺序来寻找Layout的，把下面这行代码注释掉就行了
        //dump($layoutFiles);exit;
        return $layoutFiles;
    }

    protected function renderFile($file, $renderData, $return = false)
    {
        if (file_exists($file)) {
            extract($renderData, EXTR_OVERWRITE);

            ob_start();
            ob_implicit_flush(0);
            require($file);
            $content = ob_get_clean();

            if ($return) {
                return $content;
            } else {
                echo $content;
            }

        } else {
            throw new Exception("Can not found $file in controller $this->route");
        }
    }

    /**
     * 不渲染layout的render方法
     *
     * @param array $renderData
     * @param string $view
     * @param boolean $return
     * @return string
     */
    protected function renderWithoutLayout($renderData = array(), $view = null, $return = false)
    {
        return $this->render($renderData, $view, $return, false);
    }

    /**
     * 渲染视图的方法
     *
     * @param  array $renderData 用于渲染的视图层的数据
     * @param  string $view 视图层的模板，如果没有，就根据控制器当前的route来寻找
     * @param  boolean $return 是否返回内容，而不是直接输出，如果需要再控制内部再做一些处理，比如静态化等，需要这个
     * @param  boolean|string $layout 选择模板用什么layout文件，false表示不需要layout；null表示用当前的route；也可以指定一个layout
     * @return string
     */
    protected function render($renderData = array(), $view = null, $return = false, $layout = null)
    {
        $viewFile = $this->getViewFile($view);
        $output = $this->renderFile($viewFile, $renderData, true);
        //设置自定义layout
        if ($layout !== false) {
            $layout = is_null($layout) ? $view : $layout;
            $layoutFiles = $this->getLayoutFiles($layout);
            foreach ($layoutFiles as $layoutFile) {
                if (file_exists($layoutFile)) {
                    $output = $this->renderFile($layoutFile, array('_content_' => $output), true);
                    break;
                }
            }
        }
        if ($return) {
            return $output;
        } else {
            echo $output;
        }
    }

    /**
     * 渲染输出json格式的内容
     * @param array $data
     */
    protected function renderJSON($data)
    {
        header('Content-type: application/json');
        if($this ->isEncipherJson()) {
            echo HelperFactory::getAseSafeHelper()->aes128CbcEncrypt(json_encode($data));
        }else{
            echo json_encode($data);
        }
        exit;
    }

    /**
     * 渲染输出json格式的内容
     * @param array $data
     */
    protected function renderJSONAPI($data)
    {

        $data['responseTime'] = time();
        $data['requestId'] = $this->createRequsetId();
        $data['encryptType'] = AppConst::md5Encode;
        $data['requestData'] = '======================GET===========================';
        $data['requestData'] .= print_r($_GET, true);
        $data['requestData'] .= '======================POST===========================';
        $data['requestData'] .= print_r($_POST, true);
        $data['dataAll'] = HelperFactory::getUrlHelper()->getValue('data');
        // $data['requestData'] .= '======================_SERVER===========================';
        //$data['requestData'] .= print_r($_SERVER, true);
        //$data['requestData'] .= '======================file_get_contents===========================';
        // $data['requestData'] .= print_r(file_get_contents('php://input'), true);

        $log = LogFactory::getAppLog($this->route . '/' . $data['requestId'].'-' . G_CURRENT_DOAMIN_CONST_NAME . '-' . $_SERVER['HTTP_APPVERSION']);
        $log->log(print_r($data, true));
        if( defined('G_CURRENT_DOAMIN_CONST_NAME') && ('G_IOS_YIFANJIE_COM_DOMAIN' ==  G_CURRENT_DOAMIN_CONST_NAME ||  'G_ANDROID_YIFANJIE_COM_DOMAIN' ==  G_CURRENT_DOAMIN_CONST_NAME) ){
            $hostname = php_uname ( 'n' );
            $db_selects = Zc::getDb() -> getStats();
            $stat_str = 'ExecStats : <i><b>DB</b></i> ' . $db_selects['execStats']['db']['readCount'] . ' (' . $db_selects['execStats']['db']['readTime'] .')';
            $time_start = explode ( ' ', PAGE_PARSE_START_TIME );
            $time_end = explode ( ' ', microtime () );
            $parse_time = number_format ( ($time_end [1] + $time_end [0] - ($time_start [1] + $time_start [0])), 3 );
            $stat_str .= " --- Parse Time: <b style='color:green;'> $parse_time </b> - <b style='color:blue'> $hostname </b>" ;
            if( $_GET['yfj_need_parse_time'] ){
                Zc::dump(Zc::G());
                
                echo '<div align="center"> ' . $stat_str . '</div>' ;
                exit;
            }
        
            /*if( $stat_str && extension_loaded('redis') ){
                $redis = new Redis();
        
                try {
                    $isSupportedRedis = $redis->connect(Zc::C(ZcConfigConst::LogHandlerLogstashredisHost), Zc::C(ZcConfigConst::LogHandlerLogstashredisPort), 1);
        
                    $rs = $redis->rPush('parse-times-app-'.date('Y-m-d'), '[' . $_GET['route'] . '] - version - '.$_SERVER['HTTP_APPVERSION'].' --- ' . $stat_str);
                    if( $_GET['yfj_need_parse_time'] ){
                        //echo '<div align="center"> ' . $stat_str . '</div>' ;
                        //var_dump($rs);exit;
                    }
                } catch (Exception $ex) {
                    $isSupportedRedis = false;
                    //parent::log(print_r($ex, true));
                }
            }*/
        }
        
        unset($data['requestData']);
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Method: *');
        header('Access-Control-Allow-Headers: x-requested-with,content-type');
        header('Content-type: application/json');

        if($this ->isEncipherJson()) {
            echo HelperFactory::getAseSafeHelper()->aes128CbcEncrypt(json_encode($data));
        }else{
            echo json_encode($data);
        }
        exit;
    }

    private function createRequsetId()
    {
        $arr = array('2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'U', 'X', 'Y', 'Z');
        $arrString = Array($arr[rand(0, 27)], $arr[rand(0, 27)], $arr[rand(0, 27)], $arr[rand(0, 27)], $arr[rand(0, 27)], $arr[rand(0, 27)], $arr[rand(0, 27)], $arr[rand(0, 27)], $arr[rand(0, 27)], $arr[rand(0, 27)], $arr[rand(0, 27)], $arr[rand(0, 27)], $arr[rand(0, 27)]);
        $time = date('ymd', time());
        //查看结果
        $requestId = $time . implode('', $arrString);
        return $requestId;
    }


    public function error($msg = '非法参数', $url = '')
    {
        $codeMessageHelper=HelperFactory::getCodeMessageHelper();
        $outPut=  $codeMessageHelper-> getCodeMessageByCode(AppConst::requestError,time());
        $outPut['msg'] = $msg;
        $outPut['url'] = $url ? $url : $outPut['url'];
        $outPut['shortMessage']=$msg;
        $this->renderJSONAPI($outPut);
        exit;
    }

    public function success($msg = '操作成功', $url = '',$data=false,$responeContent=false)
    {
        $codeMessageHelper=HelperFactory::getCodeMessageHelper();
        $outPut=  $codeMessageHelper-> getCodeMessageByCode(AppConst::requestSuccess,time());
        $outPut['msg'] = $msg;
        $outPut['url'] = $url ? $url : $outPut['url'];
        $outPut['data']=$data?$data:'';
        $outPut['repsoneContent']=$responeContent;
        $outPut['shortMessage']=$msg;
        $this->renderJSONAPI($outPut);
        exit;
    }

    /**
     * 返回值是否加密
     */
    private function isEncipherJson()
    {
        if (in_array(G_CURRENT_DOAMIN_CONST_NAME, ["G_IOS_YIFANJIE_COM_DOMAIN", "G_ANDROID_YIFANJIE_COM_DOMAIN"]) && $this -> isAfterThanVersion()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 是否在$version该版本号之后
     * @param string $version
     * @return bool
     */
    private function isAfterThanVersion($version='1.3.1')
    {
        $rs = strnatcasecmp($_SERVER['HTTP_APPVERSION'], $version) >= 0 ? true : false;
        return true;
    }
}