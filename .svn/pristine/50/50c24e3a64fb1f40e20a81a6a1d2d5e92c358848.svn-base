<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/StagePropertyBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';

class GiftPackageAction extends PageBase
{	
	private $objStagePropertyBLL = null;
	private $objMasterBLL = null;
	private $iVerType = 0;
	private $SearchType = 1;//按TypeID搜索道具分类
	private $SpClassLocked = 0;//道具分类锁定状态
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);
		$this->objStagePropertyBLL = new StagePropertyBLL();
		$this->objMasterBLL = new MasterBLL();
	}
	public function index()
	{
		$this->smarty->display($this->arrConfig['skin'].'/YunYing/GiftPackageList.html');
	}	 
	
	/**
	 * 分页
	 */
	public function getPagerGiftPackage()
	{
		$http = '';
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage==0 ? 1 : $curPage;
		$arrParam['fields']='SpID,ImgPath,GoodsName,ResourceID,SpNumber,Place,CateName,TypeID,SP.Locked,ServerID,GiftProb,KeyID';
		$arrParam['tableName']='T_StagePropertyPublic SP LEFT JOIN T_Class C ON SP.ClassID=C.ClassID';
		$arrParam['where']=' WHERE SP.Locked=0 AND TypeID='.$this->arrConfig['SpBigClass'][2]['TypeID'];
		$arrParam['order']='SP.ClassID,SpID';
		$arrParam['pagesize']=15;
		
		$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['StageProperty']);
		$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
		$arrResult = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);	
		if(is_array($arrResult) && count($arrResult)>0)
		{	
			$iCount = 0;
			//获取上传站点的IP地址
			$arrServerList = $this->objMasterBLL->getServerList($this->arrConfig['ServerTypeWeb'][8]['TypeID'],0);//42:图片资源服务器,0:正常未锁定的
			foreach ($arrResult as $val)
			{
				if(is_array($arrServerList) && count($arrServerList)>0)
				{
					foreach ($arrServerList as $v)
					{
						if($val['ServerID']==$v['ServerID'] && !empty($v['ServerIP']))
						{
							$arrServer = explode(',',$v['ServerIP']);
							$http = 'http://'.$arrServer[0];
							break;
						}
					}
				}
				$arrResult[$iCount]['ImgPath'] = $http . $val['ImgPath'];
				$arrResult[$iCount]['Place']='';
				$arrResult[$iCount]['iCount']=$iCount+1;
				$arrResult[$iCount]['GoodsName']=Utility::gb2312ToUtf8($val['GoodsName']);
				$arrResult[$iCount]['CateName']=Utility::gb2312ToUtf8($val['CateName']);
				$arrResult[$iCount]['GiftProb']=$val['KeyID']==$this->arrConfig['SpClassKeyID']['GiftKeyID_02'] ? floor($val['GiftProb']*100).'%' : '--';
				foreach($this->arrConfig['Place'] as $v)
				{
					if($val['Place'] & $v['TypeID'])
						$arrResult[$iCount]['Place'] .= $v['TypeName'].',';
				}
				if(!empty($arrResult[$iCount]['Place'])) $arrResult[$iCount]['Place'] = substr($arrResult[$iCount]['Place'], 0,strlen($arrResult[$iCount]['Place'])-1);
				
				/*$arrSpInfo = $this->objStagePropertyBLL->getSpInfo($val['SpID']);
				if(is_array($arrSpInfo) && count($arrSpInfo)>0)
					$arrResult[$iCount]['Locked']=$arrSpInfo['Locked'];
				else
					$arrResult[$iCount]['Locked']=1;*/
				$iCount++;
			}
		}
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'SpList'=>$arrResult); 
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunYing/GiftPackageListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	/**
	 * 读取指定类别下的道具或事件
	 */
	public function getSpPublicList()	
	{
		$strSpList = '';
		$TypeID = Utility::isNumeric('TypeID',$_POST);	
		$ClassID = Utility::isNumeric('ClassID',$_POST);	
		if($ClassID && $ClassID>0)
		{
			//$TypeID==9:读取事件,否则读取道具
			if($TypeID==9)
				$arrSpList = $this->objStagePropertyBLL->getEventList($ClassID,1);
			else
				$arrSpList = $this->objStagePropertyBLL->getSpPublicList($ClassID,1);
				
			if(is_array($arrSpList) && count($arrSpList)>0)
			{
				foreach ($arrSpList as $val)
				{
					if($val['Locked']) continue;
					$strSpList .= '<div class="left" id="'.$val['SpID'].'" TypeID="'.$TypeID.'" title="点击选择">' . $val['GoodsName'] . '</div>';
				}
			}
		}
		echo $strSpList;
	}
	/**
	 * 显示编辑道具信息页面
	 */
	public function showAddGiftPackageHtml()
	{
		$strUploadIP = '';
		$ServerID = 0;
		$GameClassID = 0;
		$arrSpList = null;
		$StartDate = '';
		$SpID = Utility::isNumeric('SpID',$_GET);	
		if($SpID && $SpID>0)
		{
			$arrSpInfo = $this->objStagePropertyBLL->getSpPublicInfo($SpID);
			//如果返回的道具属于礼包(typeid=3),读取礼包里的道具
			if(is_array($arrSpInfo) && count($arrSpInfo)>0 && $arrSpInfo['TypeID']=3)
			{
				$arrSpList = $this->objStagePropertyBLL->getGiftPackage($SpID);
				if(is_array($arrSpList) && count($arrSpList)>0)
					$StartDate = $arrSpList[0]['StartDate'];
			}
		}
		if(empty($arrSpInfo))
			$arrSpInfo=array('SpID'=>0,'GoodsName'=>'','SpNumber'=>'','ResourceID'=>0,'ClassID'=>0,'Sex'=>-1,'Intro'=>'','Place'=>0,'TypeID'=>1,'ImgPath'=>'','ServerID'=>0,'GiftProb'=>0); 
		//随机获取上传站点的IP地址
		$arrServerList = $this->objMasterBLL->getServerList($this->arrConfig['ServerTypeWeb'][8]['TypeID'],0);//42:文件上传站点类型,0:正常未锁定的
		if(is_array($arrServerList) && count($arrServerList)>0)
		{
			if($arrSpInfo['ServerID']>0)
			{
				foreach ($arrServerList as $val)
				{
					if($arrSpInfo['ServerID']==$val['ServerID'] && !empty($val['ServerIP']))
					{
						$arrServer = explode(',',$val['ServerIP']);
						$strUploadIP = $arrServer[0];
						$ServerID = $val['ServerID'];
					}
				}
			}
			else 
			{
				$iRnd = rand(0,count($arrServerList)-1);
				if(!empty($arrServerList[$iRnd]['ServerIP']))
				{
					$arrServer = explode(',',$arrServerList[$iRnd]['ServerIP']);
					$strUploadIP = $arrServer[0];
					$ServerID = $arrServerList[$iRnd]['ServerID'];
				}
			}	
		}
		//礼包生效日期
		$arrSpInfo['StartDate'] = $StartDate;
		$arrSpInfo['ServerID'] = $ServerID;
		//礼包分类
		$arrClass = $this->objStagePropertyBLL->getSpClass(3,$this->SearchType,$this->SpClassLocked);
		
		$arrTags=array( 'RecList'=>$this->arrConfig['RecList'],
						'SpClass'=>$arrClass,
						'SpTypeList'=>$this->arrConfig['Category'],
						'PlaceList'=>$this->arrConfig['Place'],
						'GameKindClassList'=>$this->arrConfig['GameKindClass'],
						'SpPublic'=>$arrSpInfo,
						'GiftSpList'=>$arrSpList,
						'Domain'=>$strUploadIP
						); 
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunYing/GiftPackageEdit.html');
	}	
	/**
	 * 获取道具分类
	 */
	public function getStagePropertyClass()
	{		
		$ClassID = 0;
		$strSubOption = '';
		$TypeID = Utility::isNumeric('TypeID',$_POST);	
		if($TypeID && $TypeID>0)
		{	
			if($TypeID==$this->arrConfig['SpBigClass'][3]['TypeID'])
				$SearchTypeID = 4;
			else
				$SearchTypeID = $this->SearchType;			
			$arrClass = $this->objStagePropertyBLL->getSpClass($TypeID,$SearchTypeID,$this->SpClassLocked);
			if(is_array($arrClass) && count($arrClass)>0)
			{
				$ClassID = $arrClass[0]['ClassID'];
				$strOption = '';				
				foreach ($arrClass as $val)
				{		
					$strOption .= '<option value="'.$val['ClassID'].'">'.$val['CateName'].'</option>';
				}				
			}
		}	
		if(empty($strOption)) $strOption='<option value="0">请添加子类</option>';
		if($ClassID>0) 
		{
			$strSubOption = $this->getSpClass($ClassID,3);
			if(!empty($strSubOption)) $strSubOption = '<select id="ThirdClassID" onchange="gift.GetSpPublicList()">'.$strSubOption.'</select>';
			//$strOption = '<select name="SubClassID" id="SubClassID" onchange="gift.GetSpClass();" style="width:90px">'.$strOption.'</select>';
		}
		//else 
		//	$strOption = '<select name="SubClassID" id="SubClassID" onchange="gift.GetSpPublicList();" style="width:90px">'.$strOption.'</select>';
		echo json_encode(array('Option'=>$strOption,'SubOption'=>$strSubOption));
	}
	/**
	 * 获取子类
	 */
	public function getClassList()
	{		
		$ClassID = Utility::isNumeric('ClassID',$_POST);
		$strOption = $this->getSpClass($ClassID,3);
		echo $strOption;
	}
	/**
	 * 获取子类(公共方法)
	 */
	private function getSpClass($KeyID,$SearchTypeID)
	{		
		$strOption = '';
		$arrClass = $this->objStagePropertyBLL->getSpClass($KeyID,$SearchTypeID,$this->SpClassLocked);
		if(is_array($arrClass) && count($arrClass)>0)
		{							
			foreach ($arrClass as $val)
			{		
				$strOption .= '<option value="'.$val['ClassID'].'">'.$val['CateName'].'</option>';
			}				
		}
		return $strOption;
	}
	/**
	 * 添加礼包
	 */
	public function addGiftPackage()
	{
		$msg = '';
		$OverRanging = 0;
		$iResult = -9999;
		$arrParams['GoodsName'] = Utility::isNullOrEmpty('GoodsName',$_POST);
		$arrParams['SpNumber'] = Utility::isNullOrEmpty('SpNumber',$_POST);
		$arrParams['ResourceID'] = Utility::isNumeric('ResourceID',$_POST);
		$arrParams['ClassID'] = Utility::isNumeric('ClassID',$_POST);
		$arrParams['ImgPath'] = Utility::isNullOrEmpty('ImgPath',$_POST);		
		$arrParams['Sex'] = Utility::isNumeric('Sex',$_POST);
		$arrParams['SpIDList'] = Utility::isNullOrEmpty('SpIDList',$_POST);
		$arrParams['SpProb'] = Utility::isNullOrEmpty('SpProb',$_POST);
		$arrParams['TypeID'] = Utility::isNullOrEmpty('TypeID',$_POST);
		$arrParams['Year'] = Utility::isNumeric('Year',$_POST);		
		$arrParams['Month'] = Utility::isNumeric('Month',$_POST);		
		$arrParams['Intro'] = Utility::isNullOrEmpty('Intro',$_POST);
		$arrParams['SpID'] = Utility::isNumeric('SpID',$_POST);
		$arrParams['Place'] = Utility::isNumeric('Place',$_POST);
		$arrParams['ServerID'] = Utility::isNumeric('ServerID',$_POST);
		$arrParams['GiftProb'] = is_numeric($_POST['GiftProb']) ||  is_float($_POST['GiftProb']) ? $_POST['GiftProb'] : 0;
		//添加到StagePropertyPublic表里用到
		$arrParams['ImgPath1']='';
		$arrParams['ImgPath2']='';
		$arrParams['Level']=0;
		$arrParams['VipID']=0;
		$arrParams['KindID']=0;
		$arrParams['EffectiveType']=2;
		$arrParams['Unit']='次';
		$arrParams['iNumber']=1;
		$arrParams['CustomField']='';
		
		if($arrParams['Year'] && $arrParams['Month'])
			$arrParams['StartDate'] = $arrParams['Year'] . '-' . $arrParams['Month'] . '-01';
		if(!isset($arrParams['StartDate'])) $arrParams['StartDate']=date('Y-m-d');
		
		if($arrParams['GoodsName'] && $arrParams['SpNumber'] && $arrParams['ResourceID'] && $arrParams['ClassID'] && $arrParams['ImgPath'] && $arrParams['SpIDList'] && $arrParams['Place'])
		{
			$arrSpID = explode(',',$arrParams['SpIDList']);//礼包里的道具或事件
			$arrSpProb = explode(',',$arrParams['SpProb']);//道具或事件出现的概率
			$arrTypeID = explode(',',$arrParams['TypeID']);//1:服装,2:道具,9:事件
			if(is_array($arrSpID) && is_array($arrSpProb) && is_array($arrTypeID) && count($arrSpID)==count($arrSpProb) && count($arrSpProb)==count($arrTypeID))
			{
				$arrResult = $this->objStagePropertyBLL->addSpPublic($arrParams);	
						
				if(is_array($arrResult) && count($arrResult)>0 && $arrResult['iResult']==0)
				{
					for($i=0;$i<count($arrSpID);$i++)
					{
						if(!is_numeric($arrSpProb[$i]) && !is_float($arrSpProb[$i]))
							$arrSpProb[$i] = 0;
						
						if(!empty($arrSpID[$i]) && !empty($arrTypeID[$i]) && is_numeric($arrSpID[$i]) && is_numeric($arrTypeID[$i]))
							$iResult = $this->objStagePropertyBLL->addGiftPackage($arrResult['SpID'],$arrSpID[$i],$arrSpProb[$i],$arrParams['StartDate'],$arrTypeID[$i]);
					}
				}
				else 
					$iResult = $arrResult['iResult'];
					if($iResult==-3) $OverRanging = (round($arrResult['TotalGiftProb'],2)-1)*100;//计算超出的百分比
			}
		}

		if($iResult==0)
			$msg='礼包信息发布成功';
		elseif($iResult==-1)
			$msg='礼包信息发布失败';
		elseif($iResult==-3)
			$msg='同类礼包概率总和不能超过百分百,您已超出'.$OverRanging.'%';		
		else
		{
			if(!$arrParams['GoodsName'])
				$msg='请输入礼包名称';
			elseif(!$arrParams['SpNumber'])
				$msg='请输入礼包编号';
			elseif(!$arrParams['ResourceID'])
				$msg='请输入礼包资源编号';
			elseif(!$arrParams['ClassID'])
				$msg='请选择礼包分类';
			elseif(!$arrParams['ImgPath'])
				$msg='请上传礼包缩略图';
			elseif(!$arrParams['SpIDList'])
				$msg='请选择礼包下的道具';
			elseif(!$arrParams['Place'])
				$msg='请选择礼包使用场景';
		}		
		echo json_encode(array('Msg'=>$msg,'iResult'=>$iResult));
	}
	/**
	 * 删除礼包
	 */
	public function delGiftPackage()
	{
		$iResult = -9999;
		$SpID = Utility::isNumeric('SpID',$_POST);	
		if($SpID && $SpID>0)
			$iResult = $this->objStagePropertyBLL->delGiftPackage($SpID,1);//1:删除礼包,2:删除礼包里的道具或事件

		if($iResult==0)
	 		$msg=$iResult;
	 	elseif($iResult==-1)	
	 		$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'GiftPackage\')','礼包删除失败','false','GiftPackage',$this->arrConfig);	
	 	else
		 	$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'GiftPackage\')','对不起,您提交的数据异常,请重试','false','GiftPackage',$this->arrConfig);
	 	echo $msg;
	}
	/**
	 * 删除礼包里的道具或事件
	 * @return -1:失败,0:成功
	 */
	public function delGiftPackageSpEvt()
	{
		$GpID = Utility::isNumeric('GpID',$_POST);	
		$SpID = Utility::isNullOrEmpty('SpID',$_POST);	
		if($GpID && $GpID>0)		
			$iResult = $this->objStagePropertyBLL->delGiftPackage($GpID,2);//1:删除礼包,2:删除礼包里的道具或事件	
		else 
			$iResult = -9999;
		echo json_encode(array('iResult'=>$iResult,'SpID'=>$SpID));
	}
}
?>