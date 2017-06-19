<?php
/**
 * 日志处理类库（类库）
 * ============================================================================
 * 版权所有 2017 文搏，并保留所有权利。
 * 网站地址: http://www.widerwill.com；
 * ----------------------------------------------------------------------------
 * ============================================================================
 * $Author: liuwenbohhh $
 * $Id: Lwb_log.php 17155 2017-02-06 06:29:05Z $
 例子：$log = new Lwb_log();
		$log->insert("sss");
 */
class Lwb_log
{
	var $folder;
	public function __construct()
	{
		$this->folder=str_replace("\\", '/', dirname(__FILE__) ).'/../log/';
		if(!is_dir($this->folder)){
            mkdir($this->folder);
        }  
        $this->folder=$this->folder.date("Ymd")."/";
        if (!is_dir($this->folder)) {
     		mkdir($this->folder);
     	} 	
	}
	public function insert($content)
	{
		if (is_array($content)) {
			file_put_contents($this->folder.date("Ymd").".log", date("Y-m-d H:i:s")."  " .serialize($content).PHP_EOL, FILE_APPEND);
		}else if(is_string($content)){
			file_put_contents($this->folder.date("Ymd").".log", date("Y-m-d H:i:s")."  " .$content.PHP_EOL, FILE_APPEND);
		}
	}
}