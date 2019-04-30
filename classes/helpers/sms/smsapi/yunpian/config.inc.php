<?php

/*
 * 配置文件 一番街
 */
$options = array();
$options['apikey'] = Zc::C('mobile_key'); //apikey
$options['signature'] =  Zc::C('mobile_signature'); //签名
return $options;
?>