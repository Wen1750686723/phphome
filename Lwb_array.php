<?php
/**
 * 数组处理类库（类库）
 * ============================================================================
 * 版权所有 2017 文搏，并保留所有权利。
 * 网站地址: http://www.widerwill.com；
 * ----------------------------------------------------------------------------
 * ============================================================================
 * $Author: liuwenbohhh $
 * $Id: Lwb_array.php 17155 2017-02-06 06:29:05Z $
 */
class Lwb_array{
     /**
     * 递归获取父类型变量，可以匹配数组或者查库
     *
     * @access      public
     * @param       string      $cid            自身字符串
     * @param       string      $pid            截止的pid
     * @return      array      返回的父id数组
     */
	function getfarray($cid,$pid=0){
		// static $array;
		// $array=array();
	 //    $criteria = new CDbCriteria;
		// $criteria->condition = "cid=".$cid;
		// $cgs = Category::model()->find($criteria);
		// if ($cgs->fid==$pid or $cgs->fid==0) {
		// 	$array[]=array("name"=>$cgs->name,"cid"=>$cgs->cid);
		// }
		// else{
		// 	$array[]=array("name"=>$cgs->name,"cid"=>$cgs->cid);
		// 	getfarray($cgs->fid,$pid);
		// }
		// return array_reverse($array);
	}
}