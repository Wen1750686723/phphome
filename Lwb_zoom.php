<?php
/**
 * 视频软件zoom处理类库（类库）
 * ============================================================================
 * 版权所有 2017 文搏，并保留所有权利。
 * 网站地址: http://www.widerwill.com；
 * ----------------------------------------------------------------------------
 * ============================================================================
 * $Author: liuwenbohhh $
 * $Id: Lwb_zoom.php 17155 2017-02-06 06:29:05Z $
 例子：//例子 时间：20161014
        // $data["api_key"]=$zoomparam[0]["key"];
        // $data["api_secret"]=$zoomparam[0]["secret"];
        // $data["id"]=$zoomparam[0]["userid"];
        // //$data["host_id"]=$zoomparam[0]["userid"];
        // //$data["id"]=1541799956;
        // // $data["topic"]="文博的测试课堂";
        // // $data["type"]=1;
        // // $data["start_time"]="2016-10-12T09:00:49Z";
        // // $data["duration"]=1;
        // //$result=\zoombasic::create_meeting($data);
        // //$result=\zoombasic::get_meeting($data);
        // //$result=\zoombasic::end_meeting($data);
 */
class Lwb_zoom
{
         
	/**
     * 创建房间
     *
     * @access      public
     * @param       array      $array       参数数组
     * @return      string     创建房间后返回的数组
     */
    public static function create_meeting($array){
        $data=$array;

        $data["data_type"]=empty($array["data_type"])?"json":$array["data_type"];    
        $json=self::post("https://www.zoomus.cn/v1/meeting/create",$data);
        $array=json_decode($json);
        $array=(array)$array;
        return $array;
    }
	/**
     * 获得房间列表
     *
     * @access      public
     * @param       array      $array       参数数组
     * @return      string     房间列表数组
     */
    public static function get_meeting($array){
        $data=$array;
        //$data["data_type"]=empty($array["data_type"])?"json":$array["data_type"];    
        $json=self::post("https://www.zoomus.cn/v1/meeting/get",$data);
        $array=json_decode($json);
        $array=(array)$array;
        return $array;
    }
	//获得房间列表
    public static function list_meeting($array){
        $data=$array;
        //$data["data_type"]=empty($array["data_type"])?"json":$array["data_type"];    
        $json=self::post("https://www.zoomus.cn/v1/meeting/list",$data);
        $array=json_decode($json);
        $array=(array)$array;
        return $array;
    }
    /**
     * 结束房间
     *
     * @access      public
     * @param       array      $array       参数数组
     * @return      string     结束房间后返回的参数数组
     */
    public static function end_meeting($array){
        $data=$array;
        $data["data_type"]=empty($array["data_type"])?"json":$array["data_type"];    
        $json=self::post("https://www.zoomus.cn/v1/meeting/end",$data);
        $array=json_decode($json);
        $array=(array)$array;
        return $array;
    }
    /**
     * 删除房间
     *
     * @access      public
     * @param       array      $array       参数数组
     * @return      string     删除房间后返回的参数数组
     */
    public static function delete_meeting($array){
        $data=$array;
        $data["data_type"]=empty($array["data_type"])?"json":$array["data_type"];    
        $json=self::post("https://www.zoomus.cn/v1/meeting/end",$data);
        $array=json_decode($json);
        $array=(array)$array;
        return $array;
    }
    //结束房间
    public static function get_user($array){
        $data=$array;
        $data["data_type"]=empty($array["data_type"])?"json":$array["data_type"];    
        $json=self::post("https://www.zoomus.cn/v1/user/get",$data);
        $array=json_decode($json);
        $array=(array)$array;
        return $array;
    }
    //通过get方式获得信息
	public static function get($url,$data=array())
	{
		if ( !empty($data) )
		{
			$data=http_build_query($data);
			$url = $url.'?'.$data;	
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT,15); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 当设置为0时$ch没有返回值，直接输出请求的内容
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$output = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		return  $output;
	}
	//通过post方式获得用户信息
	public static function post($url,$data=array())
	{
		if ( !empty($data) )
		{
			$data=http_build_query($data);
		}	
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 当设置为0时$ch没有返回值，直接输出请求的内容
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,15); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
		$output = curl_exec($ch);
		curl_close($ch);
		return  $output;
	}
}