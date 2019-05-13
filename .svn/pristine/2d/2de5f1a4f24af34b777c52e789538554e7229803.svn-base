<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/GameFiveBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';

class AdAction extends PageBase
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
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/AdList.html');
	}	 
	
	/**
	 * 分页
	 */
	public function getPagerAd()
	{
		$http = '';
		$strWhere = '';
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage==0 ? 1 : $curPage;
		$arrParam['fields']='AdID,AdTitle,PositionName,Status,CONVERT(VARCHAR(24),StartTime,120) AS StartTime,CONVERT(VARCHAR(24),EndTime,120) AS EndTime,FileURL';
		$arrParam['tableName']='T_SysAdList AS Ad INNER JOIN T_SysAdPosition AS P ON Ad.PositionID=P.PositionID ';
		$arrParam['where']=$strWhere;
		$arrParam['order']='EndTime DESC,AdID';
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
				$arrResult[$iCount]['AdTitle'] = Utility::gb2312ToUtf8($val['AdTitle']);
				$arrResult[$iCount]['PositionName'] = Utility::gb2312ToUtf8($val['PositionName']);
				$arrResult[$iCount]['Expire'] = time()-strtotime($val['EndTime'])>=0 ? 1 : 0;//是否已过期
				$iCount++;
			}
		}

		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'AdList'=>$arrResult); 
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/AdListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	
	/**
	 * 显示编辑广告信息页面
	 */
	public function showAddAdHtml()
	{
		$ServerIP = '';
		$PositionTypeID = 1;
		$arrWebAdPosList = null;
		$AdID = Utility::isNumeric('AdID',$_POST);	
		$objMasterBLL = new MasterBLL();
		$arrServerList = $objMasterBLL->getServerList(42,0);//42:图片资源服务器，0:未锁定
		if($AdID && $AdID>0)
			$arrAdInfo = $this->objGameFiveBLL->getAdInfo($AdID);
		else
			$arrAdInfo = array('AdID'=>0,'AdName'=>'','PositionID'=>0,'FileURL'=>'','LinkURL'=>'http://','PositionType'=>1,
							   'StartTime'=>'','EndTime'=>'','SortID'=>0,'Intro'=>'','ServerID'=>0);
		if(is_array($arrAdInfo) && count($arrAdInfo)>0)
		{
			if($arrAdInfo['ServerID']>0)
			{
				foreach ($arrServerList as $val)
				{
					if($val['ServerID']==$arrAdInfo['ServerID'] && !empty($val['ServerIP']))
					{
						$arrServerIP = explode(',', $val['ServerIP']);
						$ServerIP = $arrServerIP[0];
						break;
					}
				}
			}
			else 
			{
				$iRnd = rand(0,count($arrServerList)-1);
				if(!empty($arrServerList[$iRnd]['ServerIP']))
				{
					$arrServerIP = explode(',',$arrServerList[$iRnd]['ServerIP']);
					$ServerIP = $arrServerIP[0];
					$arrAdInfo['ServerID'] = $arrServerList[$iRnd]['ServerID'];
				}	
			}
			$PositionTypeID = $arrAdInfo['PositionType'];
		}
			
		$arrAdInfo['ServerIP'] = $ServerIP;	
		
		$arrAdInfo['FileURL'] = !empty($arrAdInfo['FileURL']) ? substr($arrAdInfo['FileURL'],strpos($arrAdInfo['FileURL'],'/',10),strlen($arrAdInfo['FileURL'])) : '';//str_ireplace('http://'.$ServerIP,'',$arrAdInfo['FileURL']);
		//读取广告位
		$arrAdPosList = $this->getAdPositionList($PositionTypeID,'array');
		$arrTags=array('ad'=>$arrAdInfo,'PositionTypeList'=>$this->arrConfig['PositionType'],'PositionList'=>$arrAdPosList);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/AdEdit.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
		
	}
	/**
	 * 取广告位
	 */
	public function getAdPosition()
	{
		$PositionTypeID = Utility::isNumeric('PositionTypeID',$_POST);
		$arrAdPosList = $this->getAdPositionList($PositionTypeID,'string');
		echo $arrAdPosList;
	}
	/**
	 * 取广告位列表
	 * $return=array:返回数组，string:返回字符串
	 */
	public function getAdPositionList($PositionTypeID,$return)
	{
		$AdPosList = '';
		$arrAdPosList = $this->objGameFiveBLL->getAdPosList();
		if(is_array($arrAdPosList) && count($arrAdPosList)>0)
		{
			$iCount = 0;
			foreach ($arrAdPosList as $val)
			{
				if($val['PositionType']==$PositionTypeID)
				{
					if($return=='array')
					{
						$AdPosList[$iCount]['PositionID']=$val['PositionID'];
						$AdPosList[$iCount]['PositionName']=$val['PositionName'];
					}
					else 
					{
						$AdPosList .= '<option value="'.$val['PositionID'].'">'.$val['PositionName'].'</option>';
					}
					$iCount++;
				}
			}
		}
		if(empty($AdPosList))
		{
			if($return=='array')
			{
				$AdPosList[0]['PositionID']=0;
				$AdPosList[0]['PositionName']='请添加广告位';
			}
			else
				$AdPosList = '<option value="0">请添加广告位</option>';
		}
		return $AdPosList;
	}
	/**
	 * 添加广告
	 * $iResult: 0:成功,-1:失败,-9999:接收的参数异常
	 */
	public function addAd()
	{
		$iResult = -9999;
		$arrParams['AdID'] = Utility::isNumeric('AdID',$_POST);
		$arrParams['PositionID'] = Utility::isNumeric('PositionID',$_POST);
		$arrParams['AdName'] = Utility::isNullOrEmpty('AdName',$_POST);
		$arrParams['FileURL'] = Utility::isNullOrEmpty('FileURL',$_POST);
		$arrParams['LinkURL'] = Utility::isNullOrEmpty('LinkURL',$_POST);
		$arrParams['StartTime'] = Utility::isNullOrEmpty('StartTime',$_POST) ? $_POST['StartTime'] : date('Y-m-d H:i:s');
		$arrParams['EndTime'] = Utility::isNullOrEmpty('EndTime',$_POST) ? $_POST['EndTime'] : date('Y-m-d H:i:s',strtotime('+1 day'));
		$arrParams['SortID'] = Utility::isNumeric('SortID',$_POST);
		$arrParams['Intro'] = Utility::isNullOrEmpty('Intro',$_POST);
		$arrParams['ServerID'] = Utility::isNullOrEmpty('ServerID',$_POST);
		$arrParams['ServerIP'] = Utility::isNullOrEmpty('ServerIP',$_POST);
			
		/*图片以二进制形式读取
		$filename = $_FILES['AdFiles']['tmp_name'];
		$handle = fopen($filename, "r");
		$files = fread($handle, filesize ($filename));
		fclose($handle);*/
		if($arrParams['PositionID'] && $arrParams['FileURL'] && $arrParams['SortID'] && $arrParams['ServerID'] && $arrParams['ServerIP'])
		{
			$arrParams['FileURL'] = 'http://'.$arrParams['ServerIP'].$arrParams['FileURL'];
			$tmpLinkUrl = str_ireplace('http://', '', $arrParams['LinkURL']);
			if(empty($tmpLinkUrl)) $arrParams['LinkURL'] = '#';
			$iResult = $this->objGameFiveBLL->addAd($arrParams);
		}
		echo $iResult;
	}
	/**
	 * 设置广告禁用/启用状态
	 * $iResult=0:成功,-1:失败
	 */
	public function setAdLocked()
	{
		$iResult = -9999;
		$msg = '';
		$AdID = Utility::isNumeric('AdID',$_POST);
		if($AdID && $AdID>0)
			$iResult = $this->objGameFiveBLL->setAdLocked($AdID);
		
		if($iResult==-9999)	 	
		 	$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'Ad\')','对不起,您提交的数据异常,请重试','false','Ad',$this->arrConfig);
	 	elseif($iResult==-1)	
	 		$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'Ad\')','广告状态设置失败','false','Ad',$this->arrConfig);	
		echo json_encode(array('Msg'=>$msg,'iResult'=>$iResult));	
	}
	/**
	 * 删除广告
	 * $iResult=0:成功,-1:失败
	 */
	public function delAd()
	{
		$iResult = -9999;
		$msg = '';
		$AdID = Utility::isNumeric('AdID',$_POST);
		if($AdID && $AdID>0)
			$iResult = $this->objGameFiveBLL->delAd($AdID);
		
		if($iResult==-1)	
	 		$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'Ad\')','广告删除失败','false','Ad',$this->arrConfig);	
		elseif($iResult==-9999)	 	
		 	$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'Ad\')','对不起,您提交的数据异常,请重试','false','Ad',$this->arrConfig);
	 	echo json_encode(array('Msg'=>$msg,'iResult'=>$iResult));	
	}
}
?>