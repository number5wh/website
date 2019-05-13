<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/MsgBLL.class.php';

class NoticeAction extends PageBase
{	
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);		
	}
	public function index()
	{
		$arrResult = $this->getPagerNotice(1,'');
		$arrTags=array( 'skin'=>$this->arrConfig['skin'],
						'Page'=>$arrResult['Page'],
						'NoticeList'=>$arrResult['arrNoticeList'],
						'NoticeTypeList'=>$this->arrConfig['NoticeType']						
						); 
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunYing/NoticeList.html');
	}	

	/**
	 * 分页读取单元赛记录
	 */
	public function getPagerNoticeList()
	{
		$strWhere = ' WHERE 1=1';
		$Title=Utility::isNullOrEmpty('Title',$_POST);
		$NoticeType=Utility::isNumeric('NoticeType',$_POST);		
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage==0 ? 1 : $curPage;
		
		if($Title) $strWhere .= " AND Title like '%".Utility::utf8ToGb2312($Title)."%'";
		if($NoticeType) $strWhere .= " AND TypeID = $NoticeType";

		$arrResult = $this->getPagerNotice($curPage,$strWhere);
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'NoticeList'=>$arrResult['arrNoticeList']); 
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunYing/NoticeListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}	
	/**
	 * 分页
	 */
	private function getPagerNotice($curPage,$strWhere)
	{
		//$http = '';
		//$curPage = Utility::isNumeric('curPage',$_POST);
		//$curPage = $curPage==0 ? 1 : $curPage;
		$arrParam['fields']='ID,Title,TypeID,CONVERT(VARCHAR(20),AddTime,120) AS AddTime';
		$arrParam['tableName']='T_Notice';
		$arrParam['where']=$strWhere;
		$arrParam['order']='AddTime DESC';
		$arrParam['pagesize']=20;

		$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['Msg']);
		$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
		$arrNoticeList = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);	
		if($arrNoticeList)
		{
			$iCount = 0;			
			foreach ($arrNoticeList as $val)
			{
				$arrNoticeList[$iCount]['TypeName'] = '';
				foreach ($this->arrConfig['NoticeType'] as $val2)
				{
					if($val['TypeID']==$val2['TypeID'])
					{
						$arrNoticeList[$iCount]['TypeName'] = $val2['TypeName'];
						break;
					}
				}		
				$arrNoticeList[$iCount]['Title'] = Utility::gb2312ToUtf8($val['Title']);
				$iCount++;
			}
		}
		return array('arrNoticeList'=>$arrNoticeList,'Page'=>$Page);
	}
	
	/**
	 * 显示发布公告页面 
	 * @author xlj
	 */
	public function showNoticeEditHtml()
	{		
		$ID = Utility::isNumeric('ID', $_GET);		
		if($ID && $ID>0)
		{
			$objMsgBLL = new MsgBLL();
			$arrNoticeInfo = $objMsgBLL->getNoticeInfo($ID);
		}			
		else 
			$arrNoticeInfo = array('ID'=>0,'Title'=>'','NoticeType'=>0);
		
		$arrTags=array('skin'=>$this->arrConfig['skin'],'NoticeTypeList'=>$this->arrConfig['NoticeType'],'Notice'=>$arrNoticeInfo); 
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunYing/NoticeEdit.html');
	}
	/**
	 * 发布公告
	 * @author xlj
	 */
	public function addNotice()
	{
		$iResult = -1;
		$ID = Utility::isNumeric('ID', $_POST);		
		$NoticeType = Utility::isNumeric('NoticeType', $_POST);		
		$Title = Utility::isNullOrEmpty('Title', $_POST);	
		if($NoticeType && $Title)
		{
			$objMsgBLL = new MsgBLL();
			$iResult = $objMsgBLL->addNotice($ID,$Title,'',$NoticeType);
		}
		echo $iResult;
	}
	
	
	
	
	
}
?>