<?php
/**
 * 常用方法
 */
class CommonFunction {

    private $systemService;

    private $urlHelper;

    /**
     * 构造方法
     */
    public function __construct() {
        if ( class_exists('systemService') ) {
            $this -> systemService = ServiceFactory::getSystemService();
        }
        $this-> urlHelper = HelperFactory::getUrlHelper();
    }

    /**
     * app 版本号是否更新
     */
    public function versionCheck() {
        
        $requestTime = time();
        $version = $this -> urlHelper -> getValue('version');
        $source = strtolower($this -> urlHelper -> getValue('source'));

        $version = $version ?: $_SERVER['HTTP_APPVERSION'];
        $source = $source ?: strtolower($_SERVER['HTTP_CLIENTOS']);

        $versions = $this -> systemService -> getSettingByKey($source.'_version');
        $minimumAvailableVersion = $this -> systemService -> getSettingByKey($source.'_minimum_available_version');
        $openUpdateTips = $this -> systemService -> getSettingByKey('open_'.$source.'_update_tips');
        $updateTips = $this -> systemService -> getSettingByKey($source.'_update_tips');

        $newCurrentVersion = $currestVersion = $versions;

        $flag = 0;
        
        $versionNum = preg_replace('/[^0-9]/', '', $version);
        $currestVersionNum = preg_replace('/[^0-9]/', '', $currestVersion);
        $minimumAvailableVersion = preg_replace('/[^0-9]/', '', $minimumAvailableVersion);
        
        if($versionNum < $currestVersionNum) {
            
            //如果是版本优化那边提示用户可选，如果是版本升级那么强制升级
            //$flag = substr($currestVersionNum, 0, 1) > substr($versionNum, 0, 1) ? 2 : 1; 
            if ($versionNum < $minimumAvailableVersion) {
                $flag = 2;
            }
            else{
                $flag = 1;
            }
            
        }

        $info = array();

        if( $source == 'ios' ){
            $info = array (
                "appname" => "yifanjie_ios_app_v".$newCurrentVersion,
                "apkurl" => "itms-apps://itunes.apple.com/cn/app/jie-zou-da-shi/id1217219002?mt",
            );
        }
        else{
            $info = array (
                "appname" => "yifanjie_andriod_app_v".$newCurrentVersion,
                "apkurl" => "" . Zc::C ('app.http.domain') . "/app/app-release-$newCurrentVersion.apk",
            );
        }

        // app版本号显示用
        $info['verName'] = $newCurrentVersion;
        $info['open_update_tips'] = $openUpdateTips?1:0;
        $info['update_tips'] = $openUpdateTips?$updateTips:[];

        // 标记1用户可选，2强制升级  0 表示无更新
        $info['flag'] = "$flag";
        
        return $info;
    }
}