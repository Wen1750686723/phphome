<?php
/**
 * 网址处理类库（类库）
 * ============================================================================
 * 版权所有 2017 文搏，并保留所有权利。
 * 网站地址: http://www.widerwill.com；
 * ----------------------------------------------------------------------------
 * ============================================================================
 * $Author: liuwenbohhh $
 * $Id: Lwb_url.php 17155 2017-02-06 06:29:05Z $
 */
class Lwb_url
{
	/**
	 * 获取domain
	 * @param  string $str 字符串
	 * @param  int $count  长度
	 */
	public static function getdomain($str){
	    $tempu=parse_url($str);  
	    return $tempu["scheme"]."://".$tempu["host"];
	}
	public static function gethttptype(){
		return ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
	}
	public static function getextension($url){
		// $url = "http://www.sina.com.cn/abc/de/fg.php?id=1";
		$arr=parse_url($url);
		$pathArr=pathinfo($arr['path']);
		return $pathArr['extension'];
		// print_r($pathArr['extension']);
	}
}