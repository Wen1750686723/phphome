<?php
/**
 * des加密处理类库（类库）
 * ============================================================================
 * 版权所有 2017 文搏，并保留所有权利。
 * 网站地址: http://www.widerwill.com；
 * ----------------------------------------------------------------------------
 * ============================================================================
 * $Author: liuwenbohhh $
 * $Id: Lwb_curl.php 17155 2017-02-06 06:29:05Z $
 例子：// header("Content-type: text/html; charset=utf-8");
    // $str = '{"uid":"1669"}';
    // $key= 'member16';
    // $crypt = new lwb_des($key);
    // $mstr = $crypt->encrypt($str);
    // $str = $crypt->decrypt($mstr);
 */
class Lwb_des
{
    
      
    // echo $str.' <=> '.$mstr;
    var $key;
    var $iv; //偏移量
  
    function Lwb_des($key, $iv=0)
    {
        $this->key = $key;
        if($iv == 0)
        {
            $this->iv = $key;
        }
        else
        {
            $this->iv = $iv;
        }
    }
  
    /**
     * 加密处理函数
     *
     * @access      public
     * @param       string      $str       加密的字符串
     * @return      string      加密后的字符串
     */
    function encrypt($str)
    {       
        $size = mcrypt_get_block_size ( MCRYPT_DES, MCRYPT_MODE_CBC );
        $str = $this->pkcs5Pad ( $str, $size );
        $data=mcrypt_cbc(MCRYPT_DES, $this->key, $str, MCRYPT_ENCRYPT, $this->iv);
        //$data=strtoupper(bin2hex($data)); //返回大写十六进制字符串
        return base64_encode($data);
    }
  
    /**
     * 解密处理函数
     *
     * @access      public
     * @param       string      $str       解密的字符串
     * @return      string      解密后的字符串
     */
    function decrypt($str)
    {
        $str = base64_decode ($str);
        //$strBin = $this->hex2bin( strtolower($str));
        $str = mcrypt_cbc(MCRYPT_DES, $this->key, $str, MCRYPT_DECRYPT, $this->iv );
        $str = $this->pkcs5Unpad( $str );
        return $str;
    }
  
    function hex2bin($hexData)
    {
        $binData = "";
        for($i = 0; $i < strlen ( $hexData ); $i += 2)
        {
            $binData .= chr(hexdec(substr($hexData, $i, 2)));
        }
        return $binData;
    }
  
    function pkcs5Pad($text, $blocksize)
    {
        $pad = $blocksize - (strlen ( $text ) % $blocksize);
        return $text . str_repeat ( chr ( $pad ), $pad );
    }
  
    function pkcs5Unpad($text)
    {
        $pad = ord ( $text {strlen ( $text ) - 1} );
        if ($pad > strlen ( $text ))
            return false;
        if (strspn ( $text, chr ( $pad ), strlen ( $text ) - $pad ) != $pad)
            return false;
        return substr ( $text, 0, - 1 * $pad );
    }
}

  
?>