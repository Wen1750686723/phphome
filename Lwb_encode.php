<?php
/**
 * 加密解密类
 * =======================================================================
 * ----------------------------------------------------------------------------
 * ============================================================================
 * $Author: liuwenbo $
 * $Id: Lwb_encode.php 17217 2011-01-19 06:29:08Z liuwenbo $
 */
public class Lwb_encode{
	/**
	 * 加密函数
	 * @param   string  $str    加密前的字符串
	 * @param   string  $key    密钥
	 * @return  string  加密后的字符串
	 */
	public function encrypt($str, $key = "key1")
	{
	    $coded = '';
	    $keylength = strlen($key);

	    for ($i = 0, $count = strlen($str); $i < $count; $i += $keylength)
	    {
	        $coded .= substr($str, $i, $keylength) ^ $key;
	    }

	    return str_replace('=', '', base64_encode($coded));
	}

	/**
	 * 解密函数
	 * @param   string  $str    加密后的字符串
	 * @param   string  $key    密钥
	 * @return  string  加密前的字符串
	 */
	public function decrypt($str, $key = "key1")
	{
	    $coded = '';
	    $keylength = strlen($key);
	    $str = base64_decode($str);

	    for ($i = 0, $count = strlen($str); $i < $count; $i += $keylength)
	    {
	        $coded .= substr($str, $i, $keylength) ^ $key;
	    }

	    return $coded;
	}
}