<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/GameFiveBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';

class NewsAction extends PageBase
{	
	private $objGameFiveBLL = null;
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);
		$this->objGameFiveBLL = new GameFiveBLL();
	}
	public function index()
	{		
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/NewsList.html');
	}	 
	
	/**
	 * 分页
	 */
	public function getPagerNews()
	{
		$http = '';
		$strWhere = '';
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage==0 ? 1 : $curPage;
		$arrParam['fields']='NewsID,A.CateID,NewsTitle,NewsContent,CONVERT(VARCHAR(24),A.AddTime,120) AS AddTime, P.CateName';
		$arrParam['tableName']='T_News AS A INNER JOIN T_Category AS P ON A.CateID=P.CateID ';
		$arrParam['where']=$strWhere;
		$arrParam['order']='NewsID';
		$arrParam['pagesize']=15;
		
		$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['GameFive']);
		$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
		$arrResult = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);
		if(is_array($arrResult) && count($arrResult)>0)
		{
			$iCount = 0;
			foreach ($arrResult as $val)
			{
				$arrResult[$iCount]['CateName'] = Utility::gb2312ToUtf8($val['CateName']);
				$arrResult[$iCount]['NewsTitle'] = Utility::gb2312ToUtf8($val['NewsTitle']);
				$arrResult[$iCount]['NewsContent'] = mb_substr(strip_tags(Utility::gb2312ToUtf8($val['NewsContent'])), 0, 30, 'utf-8')."...";
				$iCount++;
			}
		}

		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'NewsList'=>$arrResult); 
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/NewsListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	
	/**
	 * 显示编辑广告信息页面
	 */
	public function showAddNewsHtml()
	{
		$NewsID = Utility::isNumeric('NewsID',$_POST);
		$arrResult = null;
		if($NewsID){
			$arrResult = $this->objGameFiveBLL->getNewsDetail($NewsID);
		}
		//读取新闻类别
		$arrNewsCate = $this->objGameFiveBLL->getNewsCategory(0);
		$arrTags=array('result'=>$arrResult,'NewsCate'=>$arrNewsCate);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/NewsEdit.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
		
	}
	
	/**
	 * 添加广告
	 * $iResult: 0:成功,-1:失败,-9999:接收的参数异常
	 */
	public function addNews()
	{
		$iResult = -9999;
		$NewsID = Utility::isNumeric('NewsID',$_POST);
		$CateID = Utility::isNumeric('CateID',$_POST);
		$NewsTitle = Utility::isNullOrEmpty('NewsTitle',$_POST);
		$NewsContent = Utility::isNullOrEmpty('NewsContent',$_POST);
			
		if($CateID && $NewsTitle && $NewsContent)
		{
			$iResult = $this->objGameFiveBLL->addNews($NewsID, $CateID, $NewsTitle, $NewsContent);
		}
		echo $iResult;
	}
	
	/**
	 * 删除广告
	 * $iResult=0:成功,-1:失败
	 */
	public function delNews()
	{
		$iResult = -9999;
		$msg = '';
		$NewsID = Utility::isNumeric('NewsID',$_POST);
		if($NewsID && $NewsID>0)
			$iResult = $this->objGameFiveBLL->delNews($NewsID);
		
		if($iResult==-1)	
	 		$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'News\')','新闻删除失败','false','News',$this->arrConfig);	
		elseif($iResult==-9999)	 	
		 	$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'News\')','对不起,您提交的数据异常,请重试','false','News',$this->arrConfig);
	 	echo json_encode(array('Msg'=>$msg,'iResult'=>$iResult));	
	}
}
?>