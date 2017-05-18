<?php    
/**
 * 分页处理类库（类库）
 * ============================================================================
 * 版权所有 2017 文搏，并保留所有权利。
 * 网站地址: http://www.widerwill.com；
 * ----------------------------------------------------------------------------
 * ============================================================================
 * $Author: liuwenbohhh $ 
 * Example:   
       $myPage=new Pager(1300,intval($CurrentPage));   
       $pageStr= $myPage->GetPagerContent();   
       echo $pageStr;  
       body,html{ padding:0px; margin:0px; color:#333333; font-family:"宋体",Arial,Lucida,Verdana,Helvetica,sans-serif; font-size:12px; line-height:150%;}    

h1,h2,h3,h4,h5,h6,ul,li,dl,dt,dd,form,img,p,label{margin:0; padding:0; border:none; list-style-type:none;}    

/**前台分页样式**/   

// .Pagination {margin:10px 0 0;padding:5px 0; height:20px; line-height:20px; font-family:Arial, Helvetica, sans-serif,"宋体";float: right;}    

// .Pagination a {margin-left:2px;padding:2px 7px 2px;display: block;float: left;line-height: 18px;height: 18px;}    

// .Pagination .dot{ border:medium none; padding:4px 8px;float: left;}    

// .Pagination a:link, .Pagination a:visited {border:1px solid #dedede;color:#696969;text-decoration:none;}    

// .Pagination a:hover, .Pagination a:active, .Pagination a.current:link, .Pagination a.current:visited {border:1px solid #dedede;color:#fff; background-color:#ff6600; background-image:none; border:#ff6600 solid 1px;}    

// .Pagination .selectBar{ border:#dedede solid 1px; font-size:12px; width:95px; height:21px; line-height:21px; margin-left:10px; display:inline}    

// .Pagination a.tips{_padding:4px 7px 1px;}  
// .tipsone{
//   color: #000;
//   background-color: #fff;
//   float: left;
//   background-image: none;
//   border: #ff6600 solid 1px;
//   line-height: 22px;
//   padding-right: 5px;
//   padding-left: 5px;
//   font-family: Arial, Helvetica, sans-serif,"宋体";
//   margin-left: 2px;
// } 
class lwb_page {    
    private $pageSize = 10;    
    private $pageIndex;    
    private $totalNum;    
    private $totalPagesCount;    
    private $pageUrl;    
    private static $_instance;    
    public function __construct($p_totalNum, $p_pageIndex, $p_pageSize = 10,$p_initNum=3,$p_initMaxNum=5) {    
        if (! isset ( $p_totalNum ) || !isset($p_pageIndex)) {    
            die ( "pager initial error" );    
        }    
        $this->totalNum = $p_totalNum;    
        $this->pageIndex = $p_pageIndex;    
        $this->pageSize = $p_pageSize;    
        $this->initNum=$p_initNum;    
        $this->initMaxNum=$p_initMaxNum;    
        $this->totalPagesCount= ceil($p_totalNum / $p_pageSize);    
        $this->pageUrl=$this->_getPageUrl();    
         $this->_initPagerLegal();    
    }    
        
  /**   
    * 获取去除page部分的当前URL字符串   
    *   
    * @return String URL字符串   
    */   
  private function _getPageUrl() {    
        $CurrentUrl = $_SERVER["REQUEST_URI"];    
        $arrUrl     = parse_url($CurrentUrl);    
        $urlQuery   = null;    
        if(isset($arrUrl["query"])){    
            $urlQuery  = preg_replace("/(^|&)page=" . $this->pageIndex."/", "", $arrUrl["query"]);    
            $CurrentUrl = str_replace($arrUrl["query"], $urlQuery, $CurrentUrl);    
            if($urlQuery){    
                 $CurrentUrl.="&page";    
            }    
            else $CurrentUrl.="page";    
        } else {    
            $CurrentUrl.="?page";    
        }    
    return $CurrentUrl;    
  }    
  /*   
   *设置页面参数合法性   
   *@return void   
  */   
  private function _initPagerLegal()    
  {    
      if((!is_numeric($this->pageIndex)) ||  $this->pageIndex<1)    
      {    
          $this->pageIndex=1;    
      }elseif($this->pageIndex > $this->totalPagesCount)    
      {    
          $this->pageIndex=$this->totalPagesCount;    
      }    
          
  }    
//$this->pageUrl}={$i}    
//{$this->CurrentUrl}={$this->TotalPages}    
    public function GetPagerContent() {    
        $str = "<div class=\"Pagination\">";    
        //首页 上一页    
        if($this->pageIndex==1)    
        {    
            $str .="<div class='tipsone'>首页</div> "."\n";    
            $str .="<div class='tipsone'>上一页</div> "."\n"."\n";    
        }else   
        {    
            $str .="<a href='{$this->pageUrl}=1' class='tips' title='首页'>首页</a> "."\n";    
                    $str .="<a href='{$this->pageUrl}=".($this->pageIndex-1)."' class='tips' title='上一页'>上一页</a> "."\n"."\n";    
        }    
            
        /*   
        除首末后 页面分页逻辑   
        */   
         //10页（含）以下    
         $currnt="";    
         if($this->totalPagesCount<=10)    
         {    
            for($i=1;$i<=$this->totalPagesCount;$i++)    
            {    
                       if($i==$this->pageIndex)    
                       {    $currnt=" class='current'";}    
                       else   
                       {    $currnt="";    }    
                        $str .="<a href='{$this->pageUrl}={$i} ' {$currnt}>$i</a>"."\n" ;    
            }    
         }else                                //10页以上    
         {   if($this->pageIndex<3)  //当前页小于3    
             {    
                     for($i=1;$i<=3;$i++)    
                     {    
                         if($i==$this->pageIndex)    
                           {    $currnt=" class='current'";}    
                         else   
                         {    $currnt="";    }    
                        $str .="<a href='{$this->pageUrl}={$i} ' {$currnt}>$i</a>"."\n" ;    
                     }    
                     $str.="<span class=\"dot\">……</span>"."\n";    
                 for($i=$this->totalPagesCount-3+1;$i<=$this->totalPagesCount;$i++)//功能1    
                 {    
                      $str .="<a href='{$this->pageUrl}={$i}' >$i</a>"."\n" ;    
                 }    
             }elseif($this->pageIndex<=5)   //   5 >= 当前页 >= 3    
             {    
                 for($i=1;$i<=($this->pageIndex+1);$i++)    
                 {    
                      if($i==$this->pageIndex)    
                       {    $currnt=" class='current'";}    
                       else   
                       {    $currnt="";    }    
                        $str .="<a href='{$this->pageUrl}={$i} ' {$currnt}>$i</a>"."\n" ;    
                 }    
                 $str.="<span class=\"dot\">……</span>"."\n";    
                 for($i=$this->totalPagesCount-3+1;$i<=$this->totalPagesCount;$i++)//功能1    
                 {    
                      $str .="<a href='{$this->pageUrl}={$i}' >$i</a>"."\n" ;    
                 }    
             }elseif(5<$this->pageIndex  &&  $this->pageIndex<=$this->totalPagesCount-5 )             //当前页大于5，同时小于总页数-5    
             {    
                 for($i=1;$i<=3;$i++)    
                 {    
                     $str .="<a href='{$this->pageUrl}={$i}' >$i</a>"."\n" ;    
                 }    
                  $str.="<span class=\"dot\">……</span>";                 
                 for($i=$this->pageIndex-1 ;$i<=$this->pageIndex+1 && $i<=$this->totalPagesCount-5+1;$i++)    
                 {    
                       if($i==$this->pageIndex)    
                       {    $currnt=" class='current'";}    
                       else   
                       {    $currnt="";    }    
                        $str .="<a href='{$this->pageUrl}={$i} ' {$currnt}>$i</a>"."\n" ;    
                 }    
                 $str.="<span class=\"dot\">……</span>";    
                 for($i=$this->totalPagesCount-3+1;$i<=$this->totalPagesCount;$i++)    
                 {    
                      $str .="<a href='{$this->pageUrl}={$i}' >$i</a>"."\n" ;    
                 }    
             }else   
             {    
                  for($i=1;$i<=3;$i++)    
                 {    
                     $str .="<a href='{$this->pageUrl}={$i}' >$i</a>"."\n" ;    
                 }    
                  $str.="<span class=\"dot\">……</span>"."\n";    
                  for($i=$this->totalPagesCount-5;$i<=$this->totalPagesCount;$i++)//功能1    
                 {    
                       if($i==$this->pageIndex)    
                       {    $currnt=" class='current'";}    
                       else   
                       {    $currnt="";    }    
                        $str .="<a href='{$this->pageUrl}={$i} ' {$currnt}>$i</a>"."\n" ;    
                 }    
            }           
         }    
             
             
        /*   
        除首末后 页面分页逻辑结束   
        */   
        //下一页 末页    
        if($this->pageIndex==$this->totalPagesCount)    
        {       
            $str .="\n"."<div class='tipsone'>下一页</div>"."\n" ;    
            $str .="<div class='tipsone'>末页</div>"."\n";    
                
        }else   
        {    
            $str .="\n"."<a href='{$this->pageUrl}=".($this->pageIndex+1)."' class='tips' title='下一页'>下一页</a> "."\n";    
            $str .="<a href='{$this->pageUrl}={$this->totalPagesCount}' class='tips' title='末页'>末页</a> "."\n" ;    
        }           
        $str .= "</div>";    
        return $str;    
    }    
   
   
/**   
 * 获得实例   
 * @return     
 */   
//  static public function getInstance() {    
//      if (is_null ( self::$_instance )) {    
//          self::$_instance = new pager ();    
//      }    
//      return self::$_instance;    
//  }    
   
}    
?>  