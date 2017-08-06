<?php
/**
 * 文件链表处理类库（类库）
 * ============================================================================
 * 版权所有 2017 文搏，并保留所有权利。
 * 网站地址: http://www.widerwill.com；
 * ----------------------------------------------------------------------------
 * ============================================================================
 * $Author: liuwenbohhh $
 * $Id: arraylist.php 17155 2017-02-06 06:29:05Z $
 */
class filelist{
	public $array=array();
	public $path;
	public function __construct() {
		require_once("../Lwb_filelock.php");
		$this->path=dirname(__FILE__).DIRECTORY_SEPARATOR."data.txt";
	}
	public function push($k){
		$file = fopen($this->path,"r+");
  		$data=fread($file,filesize($this->path));
  		$array=(array)json_decode($data,true);	  
	  	// var_dump($data);
	  	// var_dump($array);	
	  	array_push($array, $k);
	  	// var_dump($array);
	    ftruncate($file,0); // 将文件截断到给定的长度

		rewind($file); // 倒回文件指针的位置	

	    $result=fwrite($file,json_encode($array));
	    // release lock
		fclose($file);
		if ($result) {
			return true;
		}
		else{
			return false;
		}
        
	}
	public function pop(){
		$file = fopen($this->path,"r+");

	  	if (filesize($this->path)==0) {
	  		$data="";
	  		$array=array();
	  	}else{
	  		$data=fread($file,filesize($this->path));
	  		$array=(array)json_decode($data);
	  	}
	  	
	  	if (empty($data)) {
	  		$k=false;
	  	}else{
	  		$k=array_pop($array);
		    ftruncate($file,0); // 将文件截断到给定的长度

    		rewind($file); // 倒回文件指针的位置	

		    $result=fwrite($file,json_encode($array,true));
	  	}	  	

		fclose($file);
		return $k;
	}
	public function len(){
		$file = fopen($this->path,"r+");

		  	if (filesize($this->path)==0) {
		  		$data="";
		  		$array=array();
		  	}else{
		  		$data=fread($file,filesize($this->path));
		  		$array=(array)json_decode($data);

		  	}
		fclose($file);
		return count($array);
	}
}