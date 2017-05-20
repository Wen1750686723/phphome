<?php
/**
 * 文件处理类库（类库）
 * ============================================================================
 * 版权所有 2017 文搏，并保留所有权利。
 * 网站地址: http://www.widerwill.com；
 * ----------------------------------------------------------------------------
 * ============================================================================
 * $Author: liuwenbohhh $
 * $Id: Lwb_file.php 17155 2017-02-06 06:29:05Z $
 例子：  $img = $_POST['img'];
        $file=Lwb_file::putcanvasimg($img,$UPLOAD_DIR)
 */

class Lwb_file{
        /**
        * 二进制文件处理函数
        *
        * @access      public
        * @param       string      $img            base64格式的文件字符串
        * @param       string      $UPLOAD_DIR     保存文件夹
        * @return      string      保存的文件名称或者false
        */
        public static function putcanvasimg($img,$UPLOAD_DIR){
        	$img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        $file =  uniqid() . '.png';
        $success = file_put_contents($UPLOAD_DIR.$file, $data);
        if ($success) {
        	return $file;
        }else{
        	return false;
        }

        }
}