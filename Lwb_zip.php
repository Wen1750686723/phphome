<?php
/**
 * zip处理类库（类库）
 * ============================================================================
 * 版权所有 2017 文搏，并保留所有权利。
 * 网站地址: http://www.widerwill.com；
 * ----------------------------------------------------------------------------
 * ============================================================================
 * $Author: liuwenbohhh $
 * $Id: Lwb_zip.php 17155 2017-02-06 06:29:05Z $
 */
class Lwb_zip
{
	/**
	 * 压缩文件夹
	 * @access  public
	 * @param   string  public string        $path        文件夹路径
	 * @param   string  public ziparchive句柄 $zip         句柄
	 * @return  string
	 *  例子：$zip = new ZipArchive();//使用本类，linux需开启zlib，windows需取消php_zip.dll前的注释   
	    if ($zip->open("test.zip", ZIPARCHIVE::CREATE)!==TRUE) {   
	        exit('无法打开文件，或者文件创建失败');
	        
	    }else{
	    	Lwb_zip::addFileToZip('views/', $zip); //调用方法，对要打包的根目录进行操作，并将ZipArchive的对象传递给方法
	    	$zip->close(); //关闭处理的zip文件
	    }
}
	 */
	public static function addFileToZip($path,$zip){
	    $handler=opendir($path); //打开当前文件夹由$path指定。
	    while(($filename=readdir($handler))!==false){
	        if($filename != "." && $filename != ".."){//文件夹文件名字为'.'和‘..’，不要对他们进行操作
	            if(is_dir($path."/".$filename)){// 如果读取的某个对象是文件夹，则递归
	                self::addFileToZip($path."/".$filename, $zip);
	            }else{ //将文件加入zip对象
	                $zip->addFile($path."/".$filename);
	            }
	        }
	    }
	    @closedir($path);
	}
}