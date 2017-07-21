<?php
/**
 * 队列处理类库（类库）
 * ============================================================================
 * 版权所有 2017 文搏，并保留所有权利。
 * 网站地址: http://www.widerwill.com；
 * ----------------------------------------------------------------------------
 * ============================================================================
 * $Author: liuwenbohhh $
 * $Id: Lwb_list.php 17155 2017-02-06 06:29:05Z $
 */
class Lwb_list
{
	static $object;
	function __construct($var)
	{
		if (empty(self::$object)) {
			require_once(dirname(__FILE__)."/list/".$var."list.php");
			$ss=$var."list";
		    self::$object=new $ss();
		}
		
		// }
	}
	function reset($var){
		require_once("list/".$var."list.php");
		self::$object=new arraylist();
	}
	function push($item){
		return self::$object->push($item);
	}
	function pop(){
		return self::$object->pop();
	}
	function len(){
		return self::$object->len();
	}

}
