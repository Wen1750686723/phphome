<?php
/**
 * server处理类库（类库）
 * ============================================================================
 * 版权所有 2017 文搏，并保留所有权利。
 * 网站地址: http://www.widerwill.com；
 * ----------------------------------------------------------------------------
 * ============================================================================
 * $Author: liuwenbohhh $
 * $Id: Lwb_server.php 17155 2017-02-06 06:29:05Z $
 */
class Lwb_server
{
	/**
	 * 判断是否为搜索引擎蜘蛛
	 *
	 * @access  public
	 * @return  string
	 */
	public function is_spider($record = true)
	{
	    static $spider = NULL;

	    if ($spider !== NULL)
	    {
	        return $spider;
	    }

	    if (empty($_SERVER['HTTP_USER_AGENT']))
	    {
	        $spider = '';

	        return '';
	    }

	    $searchengine_bot = array(
	        'googlebot',
	        'mediapartners-google',
	        'baiduspider+',
	        'msnbot',
	        'yodaobot',
	        'yahoo! slurp;',
	        'yahoo! slurp china;',
	        'iaskspider',
	        'sogou web spider',
	        'sogou push spider'
	    );

	    $searchengine_name = array(
	        'GOOGLE',
	        'GOOGLE ADSENSE',
	        'BAIDU',
	        'MSN',
	        'YODAO',
	        'YAHOO',
	        'Yahoo China',
	        'IASK',
	        'SOGOU',
	        'SOGOU'
	    );

	    $spider = strtolower($_SERVER['HTTP_USER_AGENT']);

	    foreach ($searchengine_bot AS $key => $value)
	    {
	        if (strpos($spider, $value) !== false)
	        {
	            $spider = $searchengine_name[$key];

	            if ($record === true)
	            {
	                $GLOBALS['db']->autoReplace($GLOBALS['ecs']->table('searchengine'), array('date' => local_date('Y-m-d'), 'searchengine' => $spider, 'count' => 1), array('count' => 1));
	            }

	            return $spider;
	        }
	    }

	    $spider = '';

	    return '';
	}

	/**
	 * 获得客户端的操作系统
	 *
	 * @access  private
	 * @return  void
	 */
	public function get_os()
	{
	    if (empty($_SERVER['HTTP_USER_AGENT']))
	    {
	        return 'Unknown';
	    }

	    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
	    $os    = '';

	    if (strpos($agent, 'win') !== false)
	    {
	        if (strpos($agent, 'nt 5.1') !== false)
	        {
	            $os = 'Windows XP';
	        }
	        elseif (strpos($agent, 'nt 5.2') !== false)
	        {
	            $os = 'Windows 2003';
	        }
	        elseif (strpos($agent, 'nt 5.0') !== false)
	        {
	            $os = 'Windows 2000';
	        }
	        elseif (strpos($agent, 'nt 6.0') !== false)
	        {
	            $os = 'Windows Vista';
	        }
	        elseif (strpos($agent, 'nt') !== false)
	        {
	            $os = 'Windows NT';
	        }
	        elseif (strpos($agent, 'win 9x') !== false && strpos($agent, '4.90') !== false)
	        {
	            $os = 'Windows ME';
	        }
	        elseif (strpos($agent, '98') !== false)
	        {
	            $os = 'Windows 98';
	        }
	        elseif (strpos($agent, '95') !== false)
	        {
	            $os = 'Windows 95';
	        }
	        elseif (strpos($agent, '32') !== false)
	        {
	            $os = 'Windows 32';
	        }
	        elseif (strpos($agent, 'ce') !== false)
	        {
	            $os = 'Windows CE';
	        }
	    }
	    elseif (strpos($agent, 'linux') !== false)
	    {
	        $os = 'Linux';
	    }
	    elseif (strpos($agent, 'unix') !== false)
	    {
	        $os = 'Unix';
	    }
	    elseif (strpos($agent, 'sun') !== false && strpos($agent, 'os') !== false)
	    {
	        $os = 'SunOS';
	    }
	    elseif (strpos($agent, 'ibm') !== false && strpos($agent, 'os') !== false)
	    {
	        $os = 'IBM OS/2';
	    }
	    elseif (strpos($agent, 'mac') !== false && strpos($agent, 'pc') !== false)
	    {
	        $os = 'Macintosh';
	    }
	    elseif (strpos($agent, 'powerpc') !== false)
	    {
	        $os = 'PowerPC';
	    }
	    elseif (strpos($agent, 'aix') !== false)
	    {
	        $os = 'AIX';
	    }
	    elseif (strpos($agent, 'hpux') !== false)
	    {
	        $os = 'HPUX';
	    }
	    elseif (strpos($agent, 'netbsd') !== false)
	    {
	        $os = 'NetBSD';
	    }
	    elseif (strpos($agent, 'bsd') !== false)
	    {
	        $os = 'BSD';
	    }
	    elseif (strpos($agent, 'osf1') !== false)
	    {
	        $os = 'OSF1';
	    }
	    elseif (strpos($agent, 'irix') !== false)
	    {
	        $os = 'IRIX';
	    }
	    elseif (strpos($agent, 'freebsd') !== false)
	    {
	        $os = 'FreeBSD';
	    }
	    elseif (strpos($agent, 'teleport') !== false)
	    {
	        $os = 'teleport';
	    }
	    elseif (strpos($agent, 'flashget') !== false)
	    {
	        $os = 'flashget';
	    }
	    elseif (strpos($agent, 'webzip') !== false)
	    {
	        $os = 'webzip';
	    }
	    elseif (strpos($agent, 'offline') !== false)
	    {
	        $os = 'offline';
	    }
	    else
	    {
	        $os = 'Unknown';
	    }

	    return $os;
	}

	/**
	 * 获得客户端的真实ip
	 *
	 * @access  private
	 * @return  void
	 */
	public function get_ip(){
	    //判断服务器是否允许$_SERVER
	    if(isset($_SERVER)){    
	        if(isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
	            $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	        }elseif(isset($_SERVER["HTTP_CLIENT_IP"])) {
	            $realip = $_SERVER["HTTP_CLIENT_IP"];
	        }else{
	            $realip = $_SERVER["REMOTE_ADDR"];
	        }
	    }else{
	        //不允许就使用getenv获取  
	        if(getenv("HTTP_X_FORWARDED_FOR")){
	              $realip = getenv( "HTTP_X_FORWARDED_FOR");
	        }elseif(getenv("HTTP_CLIENT_IP")) {
	              $realip = getenv("HTTP_CLIENT_IP");
	        }else{
	              $realip = getenv("REMOTE_ADDR");
	        }
	    }

	    return $realip;
	}
}