<?php
/**
 * curl处理类库（类库）
 * ============================================================================
 * 版权所有 2017 文搏，并保留所有权利。
 * 网站地址: http://www.widerwill.com；
 * ----------------------------------------------------------------------------
 * ============================================================================
 * $Author: liuwenbohhh $
 * $Id: Lwb_curl.php 17155 2017-02-06 06:29:05Z $
 例子：$array=array();
	  array["key"]="key";
	  var_dump(lwb_curl::get($url,$array));
	  var_dump(lwb_curl::post($url,$array));
 */
class Lwb_curl
{
	
    /**
     * get请求处理函数
     *
     * @access      public
     * @param       string      $url       请求的url
     * @param       array       $data      发送的数据
     * @param       array       $header    发送的header
     * @return      string      成功后获取的内容
     */
	public static function get($url,$data=array(),$header=array())
	{
		if ( !empty($data) )
		{
			$data=http_build_query($data);
			$url = $url.'?'.$data;	
		}
		$ch = curl_init();
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT,5); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 当设置为0时$ch没有返回值，直接输出请求的内容
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$output = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		return  $output;
	}
	/**
     * post请求处理函数
     *
     * @access      public
     * @param       string      $url       请求的url
     * @param       array       $data      发送的数据
     * @return      string      成功后获取的内容
     */
	public static function post($url,$data=array()) {
		$curl = curl_init();
	    //设置抓取的url
	    curl_setopt($curl, CURLOPT_URL, $url);
	    //设置头文件的信息作为数据流输出
	    curl_setopt($curl, CURLOPT_HEADER, 0);
	    //设置获取的信息以文件流的形式返回，而不是直接输出。
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	    //设置post方式提交
	    curl_setopt($curl, CURLOPT_POST, 1);
	    //设置post数据
	    $post_data = $data;
	    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
	    //执行命令
	    $data = curl_exec($curl);
	    //关闭URL请求
	    curl_close($curl);
	    return $data;	
	}
}