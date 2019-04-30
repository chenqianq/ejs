<?php
/**
 * 定义dat文件路径
 *
 *
 */
if (!defined('PHPEXCEL_ROOT')) {
    /**
     * @ignore
     */
    define('DAT_ROOT', DIR_FS_CLASSES . 'helpers/dat/');
}
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/15
 * Time: 18:24
 */
class Filter
{
    public function __construct()
    {

    }

    /**
     * 是否是敏感词
     * @param string $str
     * @return bool
     */
    public function isBadWord($str = '')
    {
        if (!$str) {
            return false;
        }
        $content_filter = $this->contentFilter($str);
        $rs = false;
        $badWord = $this->createBadWordCache();
        if (!$badWord) {
            $rs = true;
        }
        foreach ($badWord as $word) {
            if (substr_count($content_filter, $word)) {
                $rs = true;
                break;
            }
        }
        return $rs;
    }


    /**
     * 对内容进行过滤
     * @param $str
     */
    private function contentFilter($str)
    {
        $flag_arr = array('？', '！', '￥', '（', '）', '：', '‘', '’', '“', '”', '《', '》', '，', '…', '。', '、', 'nbsp', '】', '【', '～');
        $content_filter = preg_replace('/\s/', '', preg_replace("/[[:punct:]]/", '', strip_tags(html_entity_decode(str_replace($flag_arr, '', $str), ENT_QUOTES, 'UTF-8'))));
        return $content_filter;
    }

    /**
     * 创建敏感词缓存
     * @param $str
     */
    private function createBadWordCache()
    {
        $redisObj = CacheFactory::getRedisCache();
        $categoriesKey = CacheKeyBuilder::buildBadWord();
        $categoriesArray = json_decode($redisObj->get($categoriesKey), true);
        if ($categoriesArray) {
            return $categoriesArray;
        }
        $return = $this->getBadWord();
        $rs = $redisObj->set($categoriesKey, json_encode($return), 3600 * 24 * 1);
        if (!$rs) {
            return false;
        }
        return $return;
    }

    /**
     * 获取敏感词
     * @return array|bool
     */
    private function getBadWord()
    {
        $filePath = DAT_ROOT . 'bad_word.dat';
        if (!is_file($filePath)) {
            return false;
        }
        $handle = @fopen($filePath, "r");
        $file_read = fread($handle, filesize($filePath));
        fclose($handle);
        $badword = explode(',', $file_read);
        return $badword;
    }

    /**
     * 过滤特殊字符
     * @param $str                                //要过滤的字符串
     * @param array $filterCharacterArray         //过滤的字符集合
     * @return mixed
     */
    public function filterSpecialCharacter($str,$filterCharacterArray = [])
    {
        if (!$filterCharacterArray) {
            $filterCharacterArray = ["@", "&", ">", "<", "'", "''"];
        }
        return str_replace($filterCharacterArray, "", @$str);
    }
}