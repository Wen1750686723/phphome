<?php

class Pagination
{
	public $page_id;    //页面的id
	public $page_size;  //每一整页的页面个数
	public $page_all;   //总页面个数
	public $page_params;  //url标识
	public $page_cid;
	
	public $return;
	public $face;

	public $params;   
	
	public $last_page;  //最后分页的排列数
	
	public function __construct($page_id=1, $page_size=10, $page_all=0, $page_params='', $face='default')
	{
		$this->page_id = $page_id;
		$this->page_size = $page_size;
		$this->page_all = $page_all;
		$this->page_params = '?'.$page_params;
		$this->params = array();
		$this->face = $face;
		self::calculate();
	}
	
	public function calculate()  //判断分页有多少个
	{
		if($this->page_all > $this->page_size)
		{
			if($this->page_all% $this->page_size ==0)
			{
				$this->last_page = $this->page_all/ $this->page_size;
			}
			else
			{
				$this->last_page = (int)($this->page_all/ $this->page_size) +1;
			}
		}else{
			$this->last_page = 1;
		}
		$this->return = '';
	}
	
	public function setparams()
	{
		$str = "";
		foreach($this->params as $key=>$params)
		{
			if(!empty($str) ) $str .= '&';
			$str .= $key.'='.$params;
		}
		$this->page_params .= $str;
	}

	/*
	 * return this object
	 */ 
	public function object()
	{
		self::calculate();
		return $this;
	}

	public function output()
	{
		self::calculate();
		self::setparams();
		$func = 'face_'.$this->face;
		if(false == method_exists($this, $func)){
		    die('error: not found '.$this->face.' face.');
		}else{
		    self::$func();
		}
		return $this->return;
	}

	public function output2()
	{

		echo 2;
	}
	
	/*
	 * manager face.
	 * This face for manager, plase don't edit it
	 */ 
	
	public function face_manager()
	{
		// the first page
		if($this->page_id != 1)
		{
			$this->return .= '<a class="page first" href="'.$this->page_params.'&pid='.($this->page_id- 1).'">上一页</a>';
		}
		// the simple pages
		if($this->page_id <=6)
		{
			$start = 1;
			$end = ($this->last_page<= 10)? $this->last_page: 10;
		}
		else
		{
			$lavePages = $this->last_page- $this->page_id;	//计算后边剩余页数
			if($lavePages> 4)
			{
				$start = $this->page_id- 5;
				$end = $this->page_id+ 4;
			}else{
				$start = $this->page_id- (10- $lavePages) +1;
				if($start< 1) $start = 1;
				$end = $this->page_id+ $lavePages;
			}
		}
	
		for($i= $start; $i<= $end; $i++)
		{
			$active = ($i == $this->page_id)? 'page active': 'page simple';
  			$this->return .= '<a class="'.$active.'" href="'.$this->page_params.'&pid='.$i.'">'.$i.'</a>';
		}
 		// the last page
		if($this->page_id != $this->last_page)
		{
			$this->return .= '<a class="page last" href="'.$this->page_params.'&pid='.($this->page_id+ 1).'">下一页</a>';
		}
	}
	
	
	// default face.
	public function face_default()
	{
		if($this->page_id != 1)
		{
			$this->return .= '<a href="'.$this->page_params.'&pid=1"><span>首页</span></a>';
			$this->return .= '<a href="'.$this->page_params.'&pid='.($this->page_id- 1).'"><span>上一页</span></a>';
		}
		if($this->page_id != $this->last_page)
		{
			$this->return .= '<a href="'.$this->page_params.'&pid='.($this->page_id+ 1).'"><span>下一页</span></a>';
			$this->return .= '<a href="'.$this->page_params.'&pid='.$this->last_page.'"><span>尾页</span></a>';
		}
		if($this->last_page==1)
		{
			$this->return .= '暂无分页';
		}
	}
	
	// simple face.
	public function face_simple()
	{
		// the first page
		if($this->page_id != 1)
		{
			$this->return .= '<a class="page first" href="'.routerByCid($this->page_cid, ($this->page_id- 1)).'">上一页</a>';
		}
		// the simple pages
		if($this->page_id <=6)
		{
			$start = 1;
			$end = ($this->last_page<= 10)? $this->last_page: 10;
		}
		else
		{
			$lavePages = $this->last_page- $this->page_id;	//计算后边剩余页数
			if($lavePages> 4)
			{
				$start = $this->page_id- 5;
				$end = $this->page_id+ 4;
			}else{
				$start = $this->page_id- (10- $lavePages) +1;
				if($start< 1) $start = 1;
				$end = $this->page_id+ $lavePages;
			}
		}
	
		for($i= $start; $i<= $end; $i++)
		{
			$active = ($i == $this->page_id)? 'page active': 'page simple';
  			$this->return .= '<a class="'.$active.'" href="'.routerByCid($this->page_cid, $i).'">'.$i.'</a>';
		}
 		// the last page
		if($this->page_id != $this->last_page)
		{
			$this->return .= '<a class="page last" href="'.routerByCid($this->page_cid, ($this->page_id+ 1)).'">下一页</a>';
		}
	}

	// simple face.
	public function face_hy()
	{
		// the first page
		if($this->page_id != 1)
		{
			$this->return .= '<a class="fist" href="'.routerByCid($this->page_cid, (1)).$this->page_params.'"><<</a>';
			$this->return .= '<a class="prev" href="'.routerByCid($this->page_cid, ($this->page_id- 1)).$this->page_params.'"><</a>';
		}
		// the simple pages
		if($this->page_id <=6)
		{
			$start = 1;
			$end = ($this->last_page<= 10)? $this->last_page: 10;
		}
		else
		{
			$lavePages = $this->last_page- $this->page_id;	//计算后边剩余页数
			if($lavePages> 4)
			{
				$start = $this->page_id- 5;
				$end = $this->page_id+ 4;
			}else{
				$start = $this->page_id- (10- $lavePages) +1;
				if($start< 1) $start = 1;
				$end = $this->page_id+ $lavePages;
			}
		}
	
		for($i= $start; $i<= $end; $i++)
		{
			$active = ($i == $this->page_id)? 'on': '';
  			$this->return .= '<a class="'.$active.'" href="'.routerByCid($this->page_cid, $i).$this->page_params.'">'.$i.'</a>';
		}
 		// the last page
		if($this->page_id != $this->last_page)
		{
			$this->return .= '<a class="page last" href="'.routerByCid($this->page_cid, ($this->page_id+ 1)).$this->page_params.'">></a>';
			$this->return .= '<a class="last" href="'.routerByCid($this->page_cid, ($this->last_page)).$this->page_params.'">>></a>';
		}
		if($this->last_page  <= 1)
		{
			$this->return = '';
		}
	}

	// rich face.
	public function face_rich()
	{
		
		$this->return = '共'.$this->last_page.'页  当前第'.$this->page_id.'页  共'.$this->page_all.'条记录 ';
		if($this->page_id != 1)
		{
			$this->return .= '<a href="'.$this->page_params.'&pid='.($this->page_id- 1).'">【上一页】</a>';
		}
		if($this->page_id != $this->last_page)
		{
			$this->return .= '<a href="'.$this->page_params.'&pid='.($this->page_id+ 1).'">【下一页】</a>';
		}
	}
	
}