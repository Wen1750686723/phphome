<?php
/**
 * xml处理类库（类库）
 * ============================================================================
 * 版权所有 2017 文搏，并保留所有权利。
 * 网站地址: http://www.widerwill.com；
 * ----------------------------------------------------------------------------
 * ============================================================================
 * $Author: liuwenbohhh $
 * $Id: Lwb_xml.php 17155 2017-02-06 06:29:05Z $
 */
class Lwb_xml
{
    public $prepath=TMPL_PATH."statics/evaluate/xml/1/";
    /**
    * 解析xml
    *
    * @access      public
    * @param       string      $path        xml所在地址    
    */
    public function __construct($path="") {
        if ($path=="") {
            # code...
        }else{
            $this->prepath=$path;
        }
    }
    public function parsexml($xmlpath){
        $xmlpath=$this->prepath.$xmlpath;
        $xml_parse=simplexml_load_file($xmlpath,'SimpleXMLElement', LIBXML_NOCDATA);
        return (array)$xml_parse;
    }
    /**
    * 获取answer
    *
    * @access      public
    * @return      array.      解析好的array      
    */
    public function getanswer(){
        $mainxml=$this->parsexml("Answer.xml");
        $mainarray=array();
        foreach ($mainxml["Answer"] as $key => $value) {
            $value=(array)$value;
            $mainarray[$value["@attributes"]["id"]]=$value;
            
        }
        return $mainarray;
    }
    /**
    * 获取interst描述
    *
    * @access      public
    * @return      array.      解析好的array      
    */
    public function getinterst(){
        $mainxml=$this->parsexml("IntrestDescription.xml");
        $mainarray=array();
        foreach ($mainxml["IntrestDescription"] as $key => $value) {
            $value=(array)$value;
            $mainarray[$value["@attributes"]["id"]]=$value;
            
        }
        return $mainarray;
    }
    /**
    * 获取major描述
    *
    * @access      public
    * @return      array.      解析好的array      
    */
    public function getmajor(){
        $mainxml=$this->parsexml("MajorDescription.xml");
        $mainarray=array();
        foreach ($mainxml["Subject"] as $key => $value) {
            $value=(array)$value;
            $mainarray[$value["@attributes"]["id"]]=$value;
            
        }
        return $mainarray;
    }
    /**
    * 获取extendspecial描述
    *
    * @access      public
    * @return      array.      解析好的array      
    */
    public function getextendspecial(){
        $mainxml=$this->parsexml("ExtendSpecial.xml");
        $mainarray=array();
        foreach ($mainxml["Special"] as $key => $value) {
            $value=(array)$value;
            $mainarray[$value["@attributes"]["Id"]]=$value;
            
        }
        return $mainarray;
    }
    /**
    * 获取overseasability描述
    *
    * @access      public
    * @return      array.      解析好的array      
    */
    public function getoverseasability(){
        $mainxml=$this->parsexml("OverseasaAdaptability.xml");
        $mainarray=array();
        foreach ($mainxml["Oversea"] as $key => $value) {
            $value=(array)$value;
            $mainarray[$value["@attributes"]["id"]]=$value;
            
        }
        return $mainarray;
    }
}