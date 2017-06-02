<?php
/**
 * 字符串类库（类库）
 * ============================================================================
 * 版权所有 2017 文搏，并保留所有权利。
 * 网站地址: http://www.widerwill.com；
 * ----------------------------------------------------------------------------
 * ============================================================================
 * $Author: liuwenbohhh $
 * $Id: Lwb_string.php 17155 2017-02-06 06:29:05Z $
 例子：Lwb_string::substr_text("刘文搏你是hfgoajgoad",0,5)；
 */
class Lwb_string{
	
	public static function substr_text($str, $start=0, $length, $charset="utf-8", $suffix="")
	{
		if(function_exists("mb_substr")){
			return mb_substr($str, $start, $length, $charset).$suffix;
		}
		elseif(function_exists('iconv_substr')){
			return iconv_substr($str,$start,$length,$charset).$suffix;
		}
		$re['utf-8']  = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
		$re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
		$re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
		preg_match_all($re[$charset], $str, $match);
		$slice = join("",array_slice($match[0], $start, $length));
		return $slice.$suffix;
	}
	/**
	 * json加密函数
	 */ 
	function app_json_encode($data){
	    return app_encode(json_encode($data));
	}
	/**
	 * json解密函数
	 */
	function app_json_decode($data){
	    return json_decode(htmlspecialchars_decode(app_decode($data)),true);
	}
	/** 
	 * cookie加密
	 */ 
	function app_cookie($name,$value='pQpAxg3AMXL7EmXOSfRtK2j8222CKbX1'){
	    if($value == null || $value==''){
	        return cookie(C($name),null);
	    }if($value=='pQpAxg3AMXL7EmXOSfRtK2j8222CKbX1'){
	        return app_decode(cookie(C($name)));
	    }else{
	        return cookie(C($name),app_encode($value));
	    }
	}
	/**
	 * 字符串加密函数
	 * @param string $data
	 * @return string
	 */ 
	function app_encode($data){
	    $value='';
	    for($i=0;$i<strlen($data);$i++){
	        $tem=ord($data[$i]) ^ C('APP_ENCODE_KEY');
	        $value .=chr($tem);
	    }    
	    return base64_encode(C('APP_KEY').$value);
	}
	/**
	 * 字符串解密函数
	 * @param string $data
	 * @return string
	 */
	function app_decode($data){    
	    $data=base64_decode($data);    
	    preg_match("/(?:".C('APP_KEY').")([\s|\S]+)/",$data,$param);
	    $data=$param[1];
	    $value='';
	    for($i=0;$i<strlen($data);$i++){
	        $tem=ord($data[$i]) ^ C('APP_ENCODE_KEY');
	        $value .=chr($tem);
	    }
	    return $value;
	}
	/**
	 *  手机号码验证
	 */ 
	function check_mobile($moblie){
	    return  preg_match("/^0?1((3|8)[0-9]|5[0-35-9]|4[57])\d{8}$/", $moblie);
	}
	/**
	 * 生成随机数字，字母，数字和字母，字符
	 * 0-9 48-57    ASSIC
	 * A-Z 65-90    ASSIC
	 * a-z 97-122   ASSIC
	 * @param int $len 长度
	 * @param int $type=0数字,1字符,2数字字符,3数字字符特殊字符
	 */ 
	function str_rand($len = 10,$type=2){  
	    $randpwd = "";
	    if(0 == $type){
	        $min= 48;
	        $max= 57;
	    }elseif(1 == $type){
	        $min= 65;
	        $max= 122;  
	    }elseif(2==$type){
	        $min= 48;
	        $max= 122;  
	    }else{
	        $min= 33;
	        $max= 126;
	    }
	    for ($i = 0;$i < $len;$i++){        
	        $r=mt_rand($min, $max);
	        if(1==$type){
	            if($r>90 && $r<97){
	                $i--;continue;
	            }
	        }elseif(2 == $type){
	            if(($r>57 && $r<65) || ( $r>90 && $r<97)){
	                $i--;continue;
	            }
	        }
	        $randpwd .= chr($r);
	    }  
	    return $randpwd;; 
	}
	/**
	 * 手机号码格式化隐藏处理
	 */ 
	function getHideMobile($mobile){
	    if(empty($mobile)){
	        return "";
	    }
	    if(check_mobile($mobile)){
	            preg_match("/(\d{3})(\d{4})(\d{4})/",$mobile,$param);
	            $mobile=preg_replace("/".$param[2]."/","****",$mobile);
	    }
	    return $mobile;
	}
	/**
	 * 邮箱地址格式化隐藏处理
	 */ 
	function getHideEmail($str){
	    if (strpos($str, '@')) {
	        $email_array = explode("@", $str);
	        if(strlen($email_array[0]) < 4){
	            return $str;
	        }
	        $prevfix =substr($str, 0, 3); //邮箱前缀
	        $count = 0;
	        $str = preg_replace('/([\d\w+_-]{0,100})@/', '***@', $str, -1, $count);
	        $rs = $prevfix . $str;
	        return $rs;
	    }
	}
	/**
	 * 四舍五入保留几位小数
	 */
	function price_format($prices,$point=2){
	    return sprintf("￥%01.".$point."f", $prices);
	}
	/**
	 * 四舍五入保留整数
	 */
	function int_format($int){
	    return sprintf("%01.0f", $int);
	}
	/**
	 * 生成商户号
	 */
	function get_trade_no(){
	    return date('YmdHis',time()).str_rand(10,0);
	}
	function get_refund_no($trade_no){
	    return $trade_no.str_rand(4,0);
	}
	/**
	 * 通过本地商户号获取订单号
	 * @param string $out_trade_no本地商户号
	 */ 
	function get_order_sn($out_trade_no){
	    return substr($out_trade_no,0,20);
	}
	/** 给JS，CSS文件添加版本号
	 * @param string $name
	 */ 
	function addVer($name){
	    $version='1005';//date('YmdHis',time());//rand(0,999999);
	    if(strpos($name,',')){
	        $name=explode($name,',');
	        foreach($name as $key=>$row){
	            $name[$key]=$row.'?'.$version;
	        }
	        $data=implode(',',$name);
	    }else{
	        $data=$name.'?'.$version;
	    }
	    return $data;
	}
	/**
	 * 电子邮箱格式判断
	 * @param  string $email 字符串
	 * @return bool
	 */
	function is_email($email) {
	     if (!empty($email)) {
	          return preg_match('/^[a-z0-9]+([\+_\-\.]?[a-z0-9]+)*@([a-z0-9]+[\-]?[a-z0-9]+\.)+[a-z]{2,6}$/i', $email);
	     }
	     return FALSE;
	}
	/**
	 * 电话号码隐藏处理
	 * @param  string $mobile 字符串
	 */
	function mobile_format($mobile){
	    $IsWhat = preg_match('/(0[0-9]{2,3}[-]?[2-9][0-9]{6,7}[-]?[0-9]?)/i',$mobile); //固定电话
	    if($IsWhat == 1){
	        return preg_replace('/(0[0-9]{2,3}[-]?[2-9])[0-9]{3,4}([0-9]{3}[-]?[0-9]?)/i','$1****$2',$mobile);
	    }else{
	        return  preg_replace('/(1[358]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2',$mobile);
	    }
	}
	//获取文件后缀
	function get_extension($file){
	    return pathinfo($file, PATHINFO_EXTENSION);
	}
	/**
	 * 字符串截取 + 文件后缀名
	 * @param  string $str 字符串
	 * @param  int $count  长度
	 */
	function strcut($str,$count){
	    if(mb_strlen($str,'utf-8')>$count){
	        $str=mb_substr($str,0,$count,'utf-8').'...' . get_extension($str);
	    }
	    return $str;
	}
	/**
	 * 字符串截取
	 * @param  string $str 字符串
	 * @param  int $count  长度
	 */
	function strcut1($str,$count){
	    if(mb_strlen($str,'utf-8')>$count){
	        $str=mb_substr($str,0,$count,'utf-8');
	    }
	    return $str;
	}
	/**
	 * 英文为 null 转换为 空
	 * @param  string $enus 英文
	 */
	function enusisnull($enus){
	    return $enus == null ? '' : $enus;
	}
	/**
	 * 获取uuid
	 * @return      string.      获取的uuid     
	 */
	function guid(){
	    if (function_exists('com_create_guid')){
	        return com_create_guid();
	    }else{
	        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
	        $charid = strtoupper(md5(uniqid(rand(), true)));
	        $hyphen = chr(45);// "-"
	        $uuid = chr(123)// "{"
	                .substr($charid, 0, 8).$hyphen
	                .substr($charid, 8, 4).$hyphen
	                .substr($charid,12, 4).$hyphen
	                .substr($charid,16, 4).$hyphen
	                .substr($charid,20,12)
	                .chr(125);// "}"
	        return $uuid;
	    }
	}
}