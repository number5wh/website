<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/GameFiveBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';

class AdPosAction extends PageBase
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
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/AdPositionList.html');
	}	 
	
	/**
	 * 分页
	 */
	public function getPagerAdPos()
	{
		$http = '';
		$strWhere = '';
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage==0 ? 1 : $curPage;
		$arrParam['fields']='PositionType,PositionID,PositionName,PositionWidth,PositionHeight';
		$arrParam['tableName']='T_SysAdPosition';
		$arrParam['where']=$strWhere;
		$arrParam['order']='PositionType ASC,PositionID';
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
				$arrResult[$iCount]['PositionName']=Utility::gb2312ToUtf8($val['PositionName']);
				$arrResult[$iCount]['PositionTypeName']=$this->arrConfig['PositionType'][$val['PositionType']];
				$iCount++;
			}
		}
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'AdPosList'=>$arrResult); 
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/AdPositionListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	
	/**
	 * 显示编辑广告位信息页面
	 */
	public function showAddAdPosHtml()
	{
		$PositionID = Utility::isNumeric('PositionID',$_POST);	
		if($PositionID && $PositionID>0)
			$arrAdPosInfo = $this->objGameFiveBLL->getAdPosInfo($PositionID);
		else
			$arrAdPosInfo = array('PositionType'=>1,'PositionID'=>0,'PositionName'=>'',
								  'PositionWidth'=>0,'PositionHeight'=>0,'Intro'=>'','Disabled'=>'');
		$arrTags=array('adp'=>$arrAdPosInfo,'PositionTypeList'=>$this->arrConfig['PositionType']);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/AdPositionEdit.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	/**
	 * 添加广告位
	 * $iResult: 0:成功,-1:失败,-9999:参数异常
	 */
	public function addAdPos()
	{
		$iResult = -9999;
		$arrParams['PositionTypeID'] = Utility::isNumeric('PositionTypeID',$_POST);
		$arrParams['PositionName'] = Utility::isNullOrEmpty('PositionName',$_POST);
		$arrParams['PositionID'] = Utility::isNumeric('PositionID',$_POST);
		$arrParams['PositionWidth'] = Utility::isNumeric('PositionWidth',$_POST);
		$arrParams['PositionHeight'] = Utility::isNumeric('PositionHeight',$_POST);
		$arrParams['Intro'] = Utility::isNullOrEmpty('Intro',$_POST);
		if($arrParams['PositionTypeID'] && $arrParams['PositionID'])
			$iResult = $this->objGameFiveBLL->addAdPos($arrParams);
		echo $iResult;
	}
	/**
	 * 删除广告位
	 * @return 0:成功,-1:失败,-3:该广告位含有广告
	 */
	public function delAdPos()
	{
		$iResult = -9999;
		$msg = '';
		$PositionID = Utility::isNumeric('PositionID',$_POST);	
		if($PositionID && $PositionID>0)		
			$iResult = $this->objGameFiveBLL->delAdPos($PositionID);
			
		if($iResult==-3)
			$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'AdPos\')','该广告位下含有发布的广告,请先删除广告信息','false','AdPos',$this->arrConfig);
		elseif($iResult==-1)	
	 		$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'AdPos\')','广告位删除失败','false','AdPos',$this->arrConfig);	
		elseif($iResult==-9999)	 	
		 	$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'AdPos\')','对不起,您提交的数据异常,请重试','false','AdPos',$this->arrConfig);
	 	echo json_encode(array('Msg'=>$msg,'iResult'=>$iResult));		
	}
}
?>