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
    /**
    * 解析xml
    *
    * @access      public
    * @param       string      $xmlpath        xml所在地址
    * @return      array.      解析好的xml      
    */
	public function parsexml($xmlpath){
		$xml_parse=simplexml_load_file($xmlpath,'SimpleXMLElement', LIBXML_NOCDATA);
		return (array)$xml_parse;
	}
    /**
    * 解析xml
    *
    * @access      public
    * @param       string      $xmlpath        xml所在地址
    * @return      array.      解析好的array      
    */
    public function xml_to_array($xmlpath){
        $mainxml=$this->parsexml($xmlpath);
        $mainarray=array();
        foreach ($mainxml["Answer"] as $key => $value) {
            $value=(array)$value;
            $mainarray[$value["@attributes"]["id"]]["type"]=$value["Type"];
            $mainarray[$value["@attributes"]["id"]]["Color"]=$value["Color"];
            $mainarray[$value["@attributes"]["id"]]["TypeDescribe"]=(array)$value["TypeDescribe"];
            $mainarray[$value["@attributes"]["id"]]["DescribeAll"]=$value["DescribeAll"];
        }
        var_dump($mainarray);
    }
}