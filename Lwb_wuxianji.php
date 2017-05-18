<?php
/**
 * 无限极分类处理类库（类库）
 * ============================================================================
 * 版权所有 2017 文搏，并保留所有权利。
 * 网站地址: http://www.widerwill.com；
 * ----------------------------------------------------------------------------
 * ============================================================================
 * $Author: liuwenbohhh $
 * $Id: Lwb_wuxianji.php 17155 2017-02-06 06:29:05Z $
 $array_object=new Lwb_wuxianji();
foreach ($category as $key => $value) {
	$category_array[$key]["cid"]=$value["cid"];
	$category_array[$key]["fid"]=$value["fid"];
	$category_array[$key]["name"]=$value["name"];
	// $category[$key]=(array)$value;
	
}
//var_dump($category_array);
//var_dump($category);
var_dump($array_object->tree($category_array));
 */
class Lwb_wuxianji{
	public static $list = array();
	/**
     * 无限极分类格式化数据
     *
     * @access      public
     * @param       array      $arr       需要格式化的数据
     * @param       string     $pid       父级的pid
     * @return      array      格式好的数据
     */
	public function tree($arr,$pid=0){
	    
	    foreach($arr as $v){
	        //如果是顶级分类，则将其存储到 $list 中
	        //并以此节点作为根节点，遍历找其子节点
	        if($v['fid'] == $pid){
	            self::$list[$v['fid']][] = $v;
	            $this->tree($arr,$v['cid']);
	        }
	    }
	    $list1 = self::$list;
	    $list2['qian'] = $list1[0];
	    unset($list1[0]);
	    $list2['hou'] = $list1;
	    return $list2;
	}
}