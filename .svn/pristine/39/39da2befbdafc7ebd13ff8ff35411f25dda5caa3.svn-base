<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/GameFiveBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';

class NewsCategoryAction extends PageBase
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
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/NewsCategoryList.html');
	}	 
	
	/**
	 * 分页
	 */
	public function getPagerNewsCategory()
	{
		$http = '';
		$strWhere = '';
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage==0 ? 1 : $curPage;
		$arrParam['fields']='CateID,CateName,CONVERT(VARCHAR(20),AddTime,120) AS AddTime';
		$arrParam['tableName']='T_Category';
		$arrParam['where']=$strWhere;
		$arrParam['order']='CateID';
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
				$arrResult[$iCount]['CateName']=Utility::gb2312ToUtf8($val['CateName']);
				$iCount++;
			}
		}
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'NewsCateList'=>$arrResult); 
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/NewsCategoryListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	
	/**
	 * 显示编辑新闻类别信息页面
	 */
	public function showNewsCategoryHtml()
	{
		$CateID = Utility::isNumeric('CateID',$_POST);	
		if($CateID && $CateID>0){
			$arrNewsCateInfo = $this->objGameFiveBLL->getNewsCategory($CateID);
			$arrNewsCateInfo['CateID'] = $CateID;
		}else
			$arrNewsCateInfo = null;
		$arrTags=array('result'=>$arrNewsCateInfo);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/NewsCategoryEdit.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	/**
	 * 添加广告位
	 * $iResult: 0:成功,-1:失败,-9999:参数异常
	 */
	public function addNewsCategory()
	{
		$iResult = -9999;
		$CateID = Utility::isNumeric('CateID',$_POST)?$_POST['CateID']:0;
		$CateName = Utility::isNullOrEmpty('CateName',$_POST);
		
		if($CateName)
			$iResult = $this->objGameFiveBLL->addNewsCategory($CateID, $CateName);
		echo $iResult;
	}
	/**
	 * 删除广告位
	 * @return 0:成功,-1:失败,-2:该广告位含有广告
	 */
	public function delNewsCategory()
	{
		$iResult = -9999;
		$msg = '';
		$CateID = Utility::isNumeric('CateID',$_POST);	
		if($CateID && $CateID>0)		
			$iResult = $this->objGameFiveBLL->delNewsCategory($CateID);
		
		if($iResult==-2){
			$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'NewsCategory\')','该新闻类别下含有发布的新闻,请先删除新闻信息','false','NewsCategory',$this->arrConfig);			
		}elseif($iResult==-1){	
	 		$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'NewsCategory\')','新闻类别删除失败','false','NewsCategory',$this->arrConfig);	
		}elseif($iResult==-9999){ 	
		 	$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'NewsCategory\')','对不起,您提交的数据异常,请重试','false','NewsCategory',$this->arrConfig);
		}
		echo json_encode(array('Msg'=>$msg,'iResult'=>$iResult));		
	}
}
?>