<?php
/**
 * 时间处理类库（类库）
 * ============================================================================
 * 版权所有 2017 文搏，并保留所有权利。
 * 网站地址: http://www.widerwill.com；
 * ----------------------------------------------------------------------------
 * ============================================================================
 * $Author: liuwenbohhh $
 * $Id: Lwb_time.php 17155 2017-02-06 06:29:05Z $
 */
class Lwb_time
{
	/**
	 * 获得毫秒时间戳
	 * @param string $time  时间字符串
	 * @return int          获得的毫秒时间戳
	 */
	public function getMillisecond($time='') {
	    if (empty($time)) {
	        list($t1, $t2) = explode(' ', microtime());
	        $t1=$t1-date("Z");
	        return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
	    }else{
	        return intval(strtotime($time))*1000;
	    }
	    
	}
	/**
	 * 从毫秒获取格式化当前的时间
	 *
	 * @access   public
	 * @param    varchar      $format      时间格式
	 * @param    varchar      $time        时间戳
	 * @param    int      $timezone     可以有可以无，有参数的时候代表传入时区，然后根据时区进行时间戳格式化，没有的时候代表从用户表里获取时区。
	 *
	 * @return   varchar   返回格式化的时间
	 */
	function getdatefrommilli($format="Y-m-d H:i:s",$time="",$timezone="",$houtai="stu"){
	    if ($timezone==="") {
	        // $user=model("userlogin")->user_timezone();
	        $time=substr($time, 0,10) + get_sessions("timezone")*60*60;
	        //file_put_contents(LOG_PATH."curl".date("Ymd").".log", date("Y-m-d H:i:s")."  ".$houtai.PHP_EOL, FILE_APPEND);
	        if ((get_sessions("type")==1 || get_sessions("type")==3) ) {
	            return date($format,$time);
	            // return date($format,$time)."(".get_sessions('timezonename_zhcn').")";
	        }elseif (get_sessions("type")==2 ) {
	            //file_put_contents(LOG_PATH."curl".date("Ymd").".log", date("Y-m-d H:i:s")."  ".date($format,$time)."(".get_sessions('timezonename_enus').")".PHP_EOL, FILE_APPEND);
	            // return date($format,$time)."(".get_sessions('timezonename_enus').")";
	            return date($format,$time);
	        }
	        
	    }else{
	        $timezones=model("timezone")->where("timezoneid=".$timezone)->find();
	      
	        // $userdetail=model("userdetail")->user_info();
	        $time=substr($time, 0,10)+$timezones["timezone"]*60*60;
	        if (((get_sessions("type")==1 || get_sessions("type")==3) && $houtai == 'stu') || $houtai == 'stu') {
	            return date($format,$time)."(".$timezones["timezonename_zhcn"].")";
	        }elseif (get_sessions("type")==2 || $houtai == 'tutor') {
	            return date($format,$time)."(".$timezones["timezonename_enus"].")";
	        }
	    }
	    
	}
	/**
	*计算时间差
	*@param $start Unix时间戳 开始时间
	*@param $end Unix时间戳 结束时间
	*@return String
	*/
	public function count_time($end,$start=""){
	    if($start == ""){
	        $start=time();
	    }
	    $difference = $end - $start;
	    $days = intval($difference /(24*60*60));
	    $difference = $difference % (24*60*60);
	    $hours = intval($difference / 3600);
	    $difference = $difference % 3600;
	    $minutes = intval($difference /60);
	    $seconds = $difference % 60;

	    return $days."天".$hours."小时".$minutes."分".$seconds."秒";

	}
	/**
	 * 获得当前格林威治时间的时间戳
	 *
	 * @return  integer
	 */
	function gmtime()
	{
	    return (time() - date('Z'));
	}

	/**
	 * 获得服务器的时区
	 *
	 * @return  integer
	 */
	function server_timezone()
	{
	    if (function_exists('date_default_timezone_get'))
	    {
	        return date_default_timezone_get();
	    }
	    else
	    {
	        return date('Z') / 3600;
	    }
	}


	/**
	 *  生成一个用户自定义时区日期的GMT时间戳
	 *
	 * @access  public
	 * @param   int     $hour
	 * @param   int     $minute
	 * @param   int     $second
	 * @param   int     $month
	 * @param   int     $day
	 * @param   int     $year
	 *
	 * @return void
	 */
	function local_mktime($hour = NULL , $minute= NULL, $second = NULL,  $month = NULL,  $day = NULL,  $year = NULL)
	{
	    $timezone = isset($_SESSION['timezone']) ? $_SESSION['timezone'] : $GLOBALS['_CFG']['timezone'];

	    /**
	    * $time = mktime($hour, $minute, $second, $month, $day, $year) - date('Z') + (date('Z') - $timezone * 3600)
	    * 先用mktime生成时间戳，再减去date('Z')转换为GMT时间，然后修正为用户自定义时间。以下是化简后结果
	    **/
	    $time = mktime($hour, $minute, $second, $month, $day, $year) - $timezone * 3600;

	    return $time;
	}


	/**
	 * 将GMT时间戳格式化为用户自定义时区日期
	 *
	 * @param  string       $format
	 * @param  integer      $time       该参数必须是一个GMT的时间戳
	 *
	 * @return  string
	 */

	function local_date($format, $time = NULL)
	{
	    $timezone = isset($_SESSION['timezone']) ? $_SESSION['timezone'] : $GLOBALS['_CFG']['timezone'];
	    if ($time === NULL)
	    {
	        $time = gmtime();
	    }
	    elseif ($time <= 0)
	    {
	        return '';
	    }

	    $time += ($timezone * 3600);

	    return date($format, $time);
	}


	/**
	 * 转换字符串形式的时间表达式为GMT时间戳
	 *
	 * @param   string  $str
	 *
	 * @return  integer
	 */
	function gmstr2time($str)
	{
	    $time = strtotime($str);

	    if ($time > 0)
	    {
	        $time -= date('Z');
	    }

	    return $time;
	}

	/**
	 *  将一个用户自定义时区的日期转为GMT时间戳
	 *
	 * @access  public
	 * @param   string      $str
	 *
	 * @return  integer
	 */
	function local_strtotime($str)
	{
	    $timezone = isset($_SESSION['timezone']) ? $_SESSION['timezone'] : $GLOBALS['_CFG']['timezone'];

	    /**
	    * $time = mktime($hour, $minute, $second, $month, $day, $year) - date('Z') + (date('Z') - $timezone * 3600)
	    * 先用mktime生成时间戳，再减去date('Z')转换为GMT时间，然后修正为用户自定义时间。以下是化简后结果
	    **/
	    $time = strtotime($str) - $timezone * 3600;

	    return $time;

	}

	/**
	 * 获得用户所在时区指定的时间戳
	 *
	 * @param   $timestamp  integer     该时间戳必须是一个服务器本地的时间戳
	 *
	 * @return  array
	 */
	function local_gettime($timestamp = NULL)
	{
	    $tmp = local_getdate($timestamp);
	    return $tmp[0];
	}

	/**
	 * 获得用户所在时区指定的日期和时间信息
	 *
	 * @param   $timestamp  integer     该时间戳必须是一个服务器本地的时间戳
	 *
	 * @return  array
	 */
	function local_getdate($timestamp = NULL)
	{
	    $timezone = isset($_SESSION['timezone']) ? $_SESSION['timezone'] : $GLOBALS['_CFG']['timezone'];

	    /* 如果时间戳为空，则获得服务器的当前时间 */
	    if ($timestamp === NULL)
	    {
	        $timestamp = time();
	    }

	    $gmt        = $timestamp - date('Z');       // 得到该时间的格林威治时间
	    $local_time = $gmt + ($timezone * 3600);    // 转换为用户所在时区的时间戳

	    return getdate($local_time);
	}
}
