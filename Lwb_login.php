<?php
/**
 * 登录处理类库（类库）
 * ============================================================================
 * 版权所有 2017 文搏，并保留所有权利。
 * 网站地址: http://www.widerwill.com；
 * ----------------------------------------------------------------------------
 * ============================================================================
 * $Author: liuwenbohhh $
 * $Id: Lwb_login.php 17155 2017-02-06 06:29:05Z $
 例子：$array=array();
	  array["key"]="key";
	  var_dump(lwb_curl::get($url,$array));
	  var_dump(lwb_curl::post($url,$array));
 */
class Lwb_login
{
	public $logintype;
	public function __construct($id=1){
        $this->logintype=$id;
	}
	public function md5_two($string){
		return md5(md5($string));
	}
	public function md5_three($string){
		return md5(md5(md5($string)));
	}
	public function passwordcheck($password){
		if ($this->logintype==1) {
			return md5($password);
		}elseif ($this->logintype==2) {
			return $this->md5_two($password);
		}elseif ($this->logintype==3) {
			return $this->md5_three($password);
		}else{
			return md5($password);
		}
	}
}