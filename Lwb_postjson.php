<?php
/**
 * 发送json处理类库（类库）
 * ============================================================================
 * 版权所有 2017 文搏，并保留所有权利。
 * 网站地址: http://www.widerwill.com；
 * ----------------------------------------------------------------------------
 * ============================================================================
 * $Author: liuwenbohhh $
 * $Id: Lwb_postjson.php 17155 2017-02-06 06:29:05Z $
 
 */
class Lwb_postjson
{
	public static $result=null;
	public static $url="";
	public static $db;
	public static $data_array=array();
	public static $des_key="member16";
	public static $header=array ('code: mec8041a444bc7','secret: 86fe1adc1f76481fbb5107ee151cddad');
	public static function setUrl($url,$db=null,$header=array(),$des_key=''){
        self::$url=$url;
        self::$db=$db;
        self::$header=$header;
        self::$des_key=$des_key;
	}
	public static function postjson($url,$data_string)
	{
		// $crypt = new Lwb_des(self::$des_key);
		// $data_string = $crypt->encrypt($data_string);
		$time_start=microtime(true);
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_POST, 1);  
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT,5); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); 
        $array= array_merge(array( 
            'Content-Type: application/text;charset=utf-8','Content-Length: ' . strlen($data_string)
            ),self::$header);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $array
        );  
        $return_content=curl_exec($ch);
        $return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);  
        $time_end=microtime(true);
        $time_cost=$time_end-$time_start;
        // file_put_contents(ROOT_PATH."plugins/curl".date("Ymd").".log", date("Y-m-d H:i:s")."  ".$return_code."   ".$time_cost."  " .serialize($return_content).PHP_EOL, FILE_APPEND);
        if (!$return_content) {
        	echo "无法连接到主服务器！";exit;
        }
        curl_close($ch);
        return $return_content; 
	}

}
?>