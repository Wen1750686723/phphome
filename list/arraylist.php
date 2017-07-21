<?php
/**
 * 数组链表处理类库（类库）
 * ============================================================================
 * 版权所有 2017 文搏，并保留所有权利。
 * 网站地址: http://www.widerwill.com；
 * ----------------------------------------------------------------------------
 * ============================================================================
 * $Author: liuwenbohhh $
 * $Id: arraylist.php 17155 2017-02-06 06:29:05Z $
 */
class arraylist{
	public $array=array();
	public function __construct() {

	}
	public function push($k){
		return array_push($this->array, $k);
        
	}
	public function pop(){
		return array_pop($this->array);
	}
	public function len(){
		return count($this->array);
	}
}