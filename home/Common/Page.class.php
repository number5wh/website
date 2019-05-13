<?php
/**
 * 分页类函数
 * @author qxk
 */
class Page{
     public $totalPage;   //总页数
	 public $curPage;     //当前页
	 public $showNum;     //中间显示页码数量不包括第一页和最后一页
     //public $url;    //当前页面url
	 public $str;
     public $preText;      //前一页按钮文字
	 public $nextText;     //后一页按钮文字
     public $prePage;      //前一页
	 public $nextPage;     //后一页
     public $startPage;    //开始页大于等于第二页
     public $endPage; //结束页小于等于倒数第二页
     private $_pageFun="Page.goToPage";
     private $_pageParam="";
     private $PrevStartDate = '';
     private $PrevLogsID = 0;
     private $NextStartDate = '';
     private $NextLogsID = 0;

     //设置显示分页条调用的方法的名称
     public function setPageFunction($funName)
     {
     	$this->_pageFun=$funName;
     }
     //设置显示分页条调用的方法的参数，如果不为空必须以","结尾，因为后面还有一个页码的参数
     public function setPageParam($param)
     {
     	$this->_pageParam=$param;
     }
     
	 function __construct($m,$curPage,$pageParam,$PrevStartDate,$PrevLogsID,$NextStartDate,$NextLogsID,$prev="上一页",$next="下一页"){	 	
            $this->totalPage=$m;
            $this->curPage=$curPage;

			$this->_pageParam = $pageParam;
			
			$this->preText=$prev;
			$this->nextText=$next;
			$this->prePage = ($this->curPage>1) ? $this->curPage-1:1;  
			$this->nextPage =($this->curPage<$this->totalPage)?($this->curPage+1):$this->curPage; //后一页
			
			$this->PrevStartDate=$PrevStartDate;
			$this->PrevLogsID=$PrevLogsID;
			
			$this->NextStartDate=$NextStartDate;
			$this->NextLogsID=$NextLogsID;
			
			
	 }
     
	 public function showPage()
	 {	 	
	 		if($this->totalPage==1)
			{
				return "";
			}
	 	
	 		if($this->curPage==1)
	 		{
	 			$this->str.="<span class='page_n'><a>".htmlspecialchars($this->preText)."</a></span>\t";
	 		}
	 		else
	 		{
	 			$this->str.="<span class='page_n'><a onclick=javascript:$this->_pageFun('".$this->_pageParam."',".$this->prePage.",'".$this->PrevStartDate."',".$this->PrevLogsID.");>$this->preText</a></span>\t";
	 		}
			$this->str.='第<span class="pagesize">'.$this->curPage.'</span>页/共<span class="pagesize">'.$this->totalPage.'</span>页';
	 		if($this->curPage==$this->totalPage)
	 		{
	 			$this->str.="\t<span class='page_n'>\t<a>$this->nextText<a></span>";
	 		}
	 		else
	 		{
	 			$this->str.="\t<a onclick=javascript:$this->_pageFun('".$this->_pageParam."',".$this->nextPage.",'".$this->NextStartDate."',".$this->NextLogsID.");>\t$this->nextText</a>";
	 		}	 		
			return $this->str;
	 }
	 /**
	  * 仿制一个模板预览模式下的分页
	  */
	 public function templateShowPage(){
	 	$str = "<a class='disabled'> " . htmlspecialchars($this->preText). "</a>";
		for ($cc = 1; $cc <= $this->totalPage; $cc++)
		{
			if ($cc == 1)
			{
				$str .= "<a class='current'>$cc</a>";
			}
			else
			{
				$str .= "<a href='javascript:void(0);' onclick='javascript:alert(\"模板预览模式不能进行此操作！\");'>".$cc."</a>";
			}
		}
		//如果页面不是大于1，下一页的链接使其不可点击
		if ($this->totalPage > 1){
			$str .= "<a href='javascript:void(0);' onclick='javascript:alert(\"模板预览模式不能进行此操作！\");'>" . htmlspecialchars($this->nextText). "</a>";
		}else {
			$str .= "<a class='disabled'> " . htmlspecialchars($this->nextText). "</a>";
		}
		
		return $str;
	 }
}



?>