<?php 

/**
 * 获取站点
 */

class Site {

    public function __construct()
    {

    }

    /**
     * 通过站点获取站点名称
     * @param $site
     */
    public function getSiteNameBySite($site)
    {
        return str_replace(
            ["G_WWW_YIFANJIE_COM_DOMAIN", "G_M_YIFANJIE_COM_DOMAIN", "G_IOS_YIFANJIE_COM_DOMAIN", "G_ANDROID_YIFANJIE_COM_DOMAIN", "G_WEIXIN_YIFANJIE_COM_DOMAIN", "G_B_YIFANJIE_COM_DOMAIN","WWW_YFJ_COM",'35019649F0'],
            ["pc端", "wap端", "ios端", "android端", "微信端", "B端",'',"保莱塔"], $site);
    }


    /**
     * 返回站点数组
     * @return array 一维数组
     */
    public function getSiteArray() {
        return array(
            'G_M_YIFANJIE_COM_DOMAIN'       =>'wap',
            'G_ANDROID_YIFANJIE_COM_DOMAIN' =>'Android',
            'G_IOS_YIFANJIE_COM_DOMAIN'     =>'iOS',
            'G_WEIXIN_YIFANJIE_COM_DOMAIN'  =>'服务号',
            // 'G_WWW_YIFANJIE_COM_DOMAIN'     =>'PC端',
        );
    }

    /**
     * 根据站点常量名称返回站点标识
     * @param string $site 
     * @return string
     */
    public function getSourceBySite($site) {

        $siteArray = $this -> getSiteArray();

        return $siteArray[$site]?:'';

    }

}