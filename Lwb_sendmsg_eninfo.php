<?php
/**
 * eninfo发送短信类库（类库）
 * ============================================================================
 * 版权所有 2017 文搏，并保留所有权利。
 * 网站地址: http://www.widerwill.com；
 * ----------------------------------------------------------------------------
 * ============================================================================
 * $Author: liuwenbohhh $
 * $Id: Lwb_sendmsg_eninfo.php 17155 2017-02-06 06:29:05Z $
 例子：
                $message=new \Lwb_sendmsg_eninfo($target,$phone,1);
                $result=$message->sendmsg();
 */
class Lwb_sendmsg_eninfo
{
    private $SIGN="[英联帮]";
    private $sn="SDK-BBX-010-21582";
    private $pwd="6-^f^d-4";
    private $phone;
    private $target;
    private $type;
    private $content="";
    private $rrid="071817154181972351";
    private $msgfmt="";
    private $ext="";
    private $stime="";
    private $params="";
    private $length=0;
    /**
     * @param $mobile 发送号码（群发最多支持50个号码，号码之间用英文的逗号分隔“,”）
     * @param $target 验证码或企业邀请字符
     * @param string $type 类别 1：注册 2：找回密码 3：企业邀请（邀请字符写在target）
     */
    function __construct($target,$mobile,$type){
        $this->phone=$mobile;
        $this->target=$target;
        $this->type=$type;
        if($type=="1"){
            $str='尊敬的用户您好，您的注册验证码为：'.$target.'，验证码有效期为10分钟。'.$this->SIGN;
        }else if($type=="2"){
            $str='找回密码的验证码为：'.$target.'，验证码有效期为10分钟，请尽快验证！'.$this->SIGN;
        }else if($type=="3"){
            $str=$target.$this->SIGN;
        }else{
            $str="";
        }
        $this->content=$str;
        $this->pwd=strtoupper(md5($this->sn.$this->pwd));
        $param="sn=".urlencode($this->sn)."&pwd=".urlencode($this->pwd)."&mobile=".urlencode($this->phone)."&content=".urlencode($this->content).
                "&ext=".urlencode($this->ext)."&stime=".urlencode($this->stime)."&msgfmt=".urlencode($this->msgfmt)."&rrid=".urlencode($this->rrid);
        $this->params=$param;
        $this->length=strlen($this->params);
    }
    function getParam(){
        return $this->params;
    }
    /**
     * @return $recode 短信验证码发送返回状态,(utf-8编码)
     *  int 0---成功
     */
    function sendmsg(){
    //global $ezhand;
    $res=array(
        'code'=>0,
        'msg'=>''
    );
    if(""==$this->content || ''==$this->target|| ''==$this->phone|| ''==$this->type){
        $res['code']=1;
        $res['msg']='参数不完整';
        return $res;
    }    
    $fp = fsockopen("sdk2.entinfo.cn",8061,$errno,$errstr,10); 
    if(!$fp){
        $res['code']=1;
        $res['msg']='连接服务器错误';
        return $res;
    }
    $header = "POST /webservice.asmx/mdsmssend HTTP/1.1\r\n"; 
	$header .= "Host:sdk.entinfo.cn\r\n"; 
	$header .= "Content-Type: application/x-www-form-urlencoded\r\n"; 
	$header .= "Content-Length: ".$this->length."\r\n"; 
	$header .= "Connection: Close\r\n\r\n";
	$header .= $this->params."\r\n"; 	
	fputs($fp,$header); 
    $inheader = 1; 
	while (!feof($fp)) { 
		$line = fgets($fp,1024); //去除请求包的头只显示页面的返回数据 
		if ($inheader && ($line == "\n" || $line == "\r\n")) { 
			$inheader = 0; 
		} 
		if ($inheader == 0) { 
			// echo $line; 
		} 
	} 
    $line=str_replace("<string xmlns=\"http://tempuri.org/\">","",$line);
	$line=str_replace("</string>","",$line);
	$result=explode("-",$line);	
	if(count($result)>1){
        $res['code']=$result;
        $res['msg']='下发短信通道出错';
		return $res;
    }else{
        $res['msg']='发送成功 返回值为:'.$line."sp=".$fp."content=".$this->content.$this->params;
    }
    return $res;
    }
}