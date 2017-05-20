<?php
/**
 * 验证处理类库（类库）
 * ============================================================================
 * 版权所有 2017 文搏，并保留所有权利。
 * 网站地址: http://www.widerwill.com；
 * ----------------------------------------------------------------------------
 * ============================================================================
 * $Author: liuwenbohhh $
 * $Id: Lwb_validate.php 17155 2017-02-06 06:29:05Z $
 */
class Lwb_validate{
	/**
	 * 验证输入的邮件地址是否合法
	 *
	 * @access  public
	 * @param   string      $email      需要验证的邮件地址
	 *
	 * @return bool
	 */
	function is_email($user_email)
	{
	    $chars = "/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}\$/i";
	    if (strpos($user_email, '@') !== false && strpos($user_email, '.') !== false)
	    {
	        if (preg_match($chars, $user_email))
	        {
	            return true;
	        }
	        else
	        {
	            return false;
	        }
	    }
	    else
	    {
	        return false;
	    }
	}
	/**
	 * 检查是否为一个合法的时间格式
	 *
	 * @access  public
	 * @param   string  $time
	 * @return  void
	 */
	function is_time($time)
	{
	    $pattern = '/[\d]{4}-[\d]{1,2}-[\d]{1,2}\s[\d]{1,2}:[\d]{1,2}:[\d]{1,2}/';

	    return preg_match($pattern, $time);
	}
	/**
	 * 获取输入参数 支持过滤和默认值
	 * 使用方法:
	 * <code>
	 * I('id',0); 获取id参数 自动判断get或者post
	 * I('post.name','','htmlspecialchars'); 获取$_POST['name']
	 * I('get.'); 获取$_GET
	 * </code>
	 * @param string $name 变量的名称 支持指定类型
	 * @param mixed $default 不存在的时候默认值
	 * @param mixed $filter 参数过滤方法
	 * @param mixed $datas 要获取的额外数据源
	 * @return mixed
	 */
	function I($name,$default=null,$filter=null,$datas=null) {
	    static $_PUT    =   null;
	    $default_filter='htmlspecialchars';
	    if(strpos($name,'/')){ // 指定修饰符
	        list($name,$type)   =   explode('/',$name,2);
	    }
	    if(strpos($name,'.')) { // 指定参数来源
	        list($method,$name) =   explode('.',$name,2);
	    }else{ // 默认为自动判断
	        $method =   'param';
	    }
	    switch(strtolower($method)) {
	        case 'get'     :   
	            $input =& $_GET;
	            break;
	        case 'post'    :   
	            $input =& $_POST;
	            break;
	        case 'put'     :   
	            if(is_null($_PUT)){
	                parse_str(file_get_contents('php://input'), $_PUT);
	            }
	            $input  =   $_PUT;        
	            break;
	        case 'param'   :
	            switch($_SERVER['REQUEST_METHOD']) {
	                case 'POST':
	                    $input  =  $_POST;
	                    break;
	                case 'PUT':
	                    if(is_null($_PUT)){
	                        parse_str(file_get_contents('php://input'), $_PUT);
	                    }
	                    $input  =   $_PUT;
	                    break;
	                default:
	                    $input  =  $_GET;
	            }
	            break;
	        case 'request' :   
	            $input =& $_REQUEST;   
	            break;
	        case 'session' :   
	            $input =& $_SESSION;   
	            break;
	        case 'cookie'  :   
	            $input =& $_COOKIE;    
	            break;
	        case 'server'  :   
	            $input =& $_SERVER;    
	            break;
	        case 'globals' :   
	            $input =& $GLOBALS;    
	            break;
	        default:
	            return null;
	    }

	    if(''==$name) { // 获取全部变量
	        $data       =   $input;
	        $filters    =   isset($filter)?$filter:$default_filter;
	        if($filters) {
	            if(is_string($filters)){
	                $filters    =   explode(',',$filters);
	            }
	            foreach($filters as $filter){
	                $data   =   array_map_recursive($filter,$data); // 参数过滤
	            }
	        }
	    }elseif(isset($input[$name])) { // 取值操作
	        $data       =   $input[$name];
	        $filters    =   isset($filter)?$filter:$default_filter;

	        if($filters) {
	            if(is_string($filters)){
	                if(0 === strpos($filters,'/')){
	                    if(1 !== preg_match($filters,(string)$data)){
	                        // 支持正则验证
	                        return   isset($default) ? $default : null;
	                    }
	                }else{
	                    $filters    =   explode(',',$filters);                    
	                }
	            }elseif(is_int($filters)){
	                $filters    =   array($filters);
	            }

	            if(is_array($filters)){
	                foreach($filters as $filter){

	            // exit;
	                    if(function_exists($filter)) {
	                    
	                        $data   =   is_array($data) ? array_map_recursive($filter,$data) : $filter($data); // 参数过滤
	                    }else{

	                        $data   =   filter_var($data,is_int($filter) ? $filter : filter_id($filter));
	                        if(false === $data) {
	                            return   isset($default) ? $default : null;
	                        }
	                    }
	                }
	            }
	        }

	        if(!empty($type)){
	            switch(strtolower($type)){
	                case 'a':   // 数组
	                    $data   =   (array)$data;
	                    break;
	                case 'd':   // 数字
	                    $data   =   (int)$data;
	                    break;
	                case 'f':   // 浮点
	                    $data   =   (float)$data;
	                    break;
	                case 'b':   // 布尔
	                    $data   =   (boolean)$data;
	                    break;
	                case 's':   // 字符串
	                default:
	                    $data   =   (string)$data;
	            }
	        }
	    }else{ // 变量默认值
	        $data       =    isset($default)?$default:null;
	    }

	    is_array($data) && array_walk_recursive($data,'think_filter');
	    return $data;
	}
}