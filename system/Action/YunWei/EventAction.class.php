<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/StagePropertyBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';

class EventAction extends PageBase
{	
	private $objStagePropertyBLL = null;
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);
		$this->objStagePropertyBLL = new StagePropertyBLL();
	}
	public function index()
	{		
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/EventList.html');
	}	 
	
	/**
	 * 分页
	 */
	public function getPagerEvent()
	{
		$http = '';
		$strWhere = '';

		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage==0 ? 1 : $curPage;
		$arrParam['fields']='E.EvtID,EvtTitle,E.Locked,CateName,C.ClassID,SubClassID';
		$arrParam['tableName']='T_Event E LEFT JOIN T_Class C ON E.BigClassID=C.ClassID';
		$arrParam['where']=$strWhere;
		$arrParam['order']='C.ClassID,SubClassID,E.EvtID';
		$arrParam['pagesize']=15;
		
		$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['StageProperty']);
		$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
		$arrResult = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);	
		
		if(is_array($arrResult) && count($arrResult)>0)
		{	
			$iCount = 0;
			//读取类别
			$arrClassList = $this->objStagePropertyBLL->getSpClass($this->arrConfig['SpBigClass'][3]['TypeID'],1,0);
			foreach ($arrResult as $val)
			{
				if(is_array($arrClassList) && count($arrClassList)>0)
				{
					foreach ($arrClassList as $v)
					{
						if($v['ClassID']==$val['SubClassID'])
						{
							$arrResult[$iCount]['SubCateName'] = $v['CateName'];
							break;
						}
					}
				}
				else 
					$arrResult[$iCount]['SubCateName'] = '';
				$arrResult[$iCount]['EvtTitle'] = Utility::gb2312ToUtf8($val['EvtTitle']);
				$arrResult[$iCount]['CateName'] = Utility::gb2312ToUtf8($val['CateName']);
				$arrResult[$iCount]['iCount'] = $iCount+1;
				$iCount++;
			}
		}
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'EventList'=>$arrResult); 
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/EventListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	/**
	 * 设置事件禁用/启用
	 * $iResult=0:失败,-2:数据库异常,大于0:成功
	 */
	public function setEventLocked()
	{
		$iResult = -9999;
		$iLocked = -9999;
		$EvtID = Utility::isNumeric('EvtID',$_POST);
		if($EvtID && $EvtID>0)
		{			
			$arrResult = $this->objStagePropertyBLL->setEventLocked($EvtID);
			if(is_array($arrResult) && count($arrResult)>0)
			{
				$iResult = $arrResult['iResult'];
				$iLocked = $arrResult['Locked'];
			}
		}
		if($iResult==0)
			$msg='';
	 	elseif($iResult==-1)
	 		$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'Event\')','事件状态设置失败','false','Event',$this->arrConfig);	
		else 	
		 	$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'Event\')','您提交的参数异常,请重试','false','Event',$this->arrConfig);
	 	
		echo json_encode(array('iResult'=>$iResult,'EvtID'=>$EvtID,'Msg'=>$msg,'iLocked'=>$iLocked));
	}
	/**
	 * 删除事件
	 * $iResult=-1:失败;0:成功,请重试;-9999:您提交的数据异常,请重试
	 */
	public function delEvent()
	{
		$iResult = -9999;
		$EvtID = Utility::isNumeric('EvtID',$_POST);	
		if($EvtID && $EvtID>0)
			$iResult = $this->objStagePropertyBLL->delEvent($EvtID);	

		if($iResult==0)
	 		$msg=$iResult;	 
	 	elseif($iResult==-1)	
	 		$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'Event\')','事件删除失败','false','Event',$this->arrConfig);	
	 	else	
		 	$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'Event\')','对不起,您提交的数据异常,请重试','false','Event',$this->arrConfig);
	 	echo json_encode(array('iResult'=>$iResult,'Msg'=>$msg));
	}
	/**
	 * 显示编辑事件信息页面
	 */
	public function showAddEventHtml()
	{
		$arrSubClassList = null;
		$EvtID = Utility::isNumeric('EvtID',$_POST);	
		if($EvtID && $EvtID>0)
		{
			//读取事件详情
			$arrEvtList = $this->objStagePropertyBLL->getEventList($EvtID,2);
			if(is_array($arrEvtList) && count($arrEvtList)>0)
			{
				$arrEvtInfo['EvtID']=$arrEvtList[0]['EvtID'];
				$arrEvtInfo['BigClassID']=$arrEvtList[0]['BigClassID'];		
				$arrEvtInfo['SubClassID']=$arrEvtList[0]['SubClassID'];		
				$arrEvtInfo['EvtTitle']=Utility::gb2312ToUtf8($arrEvtList[0]['EvtTitle']);
				$arrEvtInfo['EvtRule']=Utility::gb2312ToUtf8($arrEvtList[0]['EvtRule']);						
			}
		}		
		if(empty($arrEvtInfo))
			$arrEvtInfo=array('EvtID'=>0,'EvtTitle'=>'','EvtRule'=>'','BigClassID'=>0,'SubClassID'=>0); 
		//读取事件大类
		$arrClassList = $this->objStagePropertyBLL->getSpClass($this->arrConfig['SpBigClass'][3]['TypeID'],4,0);
		//读取事件子类
		if(is_array($arrClassList) && count($arrClassList)>0)
			$arrSubClassList = $this->objStagePropertyBLL->getSpClass($arrClassList[0]['ClassID'],3,0);
		$arrTags=array('Evt'=>$arrEvtInfo,'ClassList'=>$arrClassList,'SubClassList'=>$arrSubClassList); 
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/EventEdit.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	/**
	 * 显示编辑事件属性页面
	 */
	public function showAddEventDetailHtml()
	{
		$EvtID = Utility::isNumeric('EvtID',$_POST);	
		if($EvtID && $EvtID>0)
			$arrEvtDetailList = $this->objStagePropertyBLL->getEventDetailList($EvtID);
		if(is_array($arrEvtDetailList) && count($arrEvtDetailList)>0)
		{
			$iCount = 0;
			foreach($arrEvtDetailList as $val)
			{
				if($val['ClassID']>0)
				{
					$arrClassList = $this->objStagePropertyBLL->getSpClass($val['ClassID'],2,0);
					if(is_array($arrClassList) && count($arrClassList)>0)
						$arrEvtDetailList[$iCount]['CateName'] = $arrClassList[0]['CateName'];
				}
				if(!isset($arrEvtDetailList[$iCount]['CateName'])) $arrEvtDetailList[$iCount]['CateName']='';
				$iCount++;
			} 
		}
		$arrClassList = $this->getClassList();
		$arrTags=array('EventDetailList'=>$arrEvtDetailList,'ClassList'=>$arrClassList,'EvtID'=>$EvtID); 
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/EventDetailEdit.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	/**
	 * 添加事件属性
	 */
	public function addEventDetail()
	{
		$iResult = -99;
		$iID = 0;
		$arrParams['EvtID'] = Utility::isNumeric('EvtID',$_POST);	
		$arrParams['ClassID'] = Utility::isNumeric('ClassID',$_POST);	
		$arrParams['ObjID'] = Utility::isNumeric('ObjID',$_POST);	
		$arrParams['iNumber'] = Utility::isNumeric('iNumber',$_POST);	
		$arrParams['Probability'] = is_numeric($_POST['Probability']) || is_float($_POST['Probability']) ? $_POST['Probability'] : 0;
		if($arrParams['EvtID'] && $arrParams['EvtID']>0 && $arrParams['ClassID'] && $arrParams['ClassID']>0)
		{
			$arrReturns = $this->objStagePropertyBLL->addEventDetail($arrParams);
			if(is_array($arrReturns) && count($arrReturns)>0)
			{
				$iResult = $arrReturns['iResult'];
				$iID = $arrReturns['ID'];
			}
		}
		
		if($iResult==0)
			$msg='事件属性发布成功';
		elseif($iResult==-1)
			$msg='事件属性发布失败';		
		else
		{
			if(!$arrParams['EvtID'])
				$msg='*事件编号异常';	
			elseif(!$arrParams['ClassID'])
				$msg='*请选择正确的分类';
		}
		echo json_encode(array('iResult'=>$iResult,'Msg'=>$msg,'EvtID'=>$arrParams['EvtID'],'iID'=>$iID));
	}
	/**
	 * 删除事件属性
	 * $iResult= 0:失败,-2:数据库异常,大于0:成功,-99:接收的参数异常
	 */
	public function delEventDetail()
	{
		$iResult = -9999;
		$ID = Utility::isNumeric('ID',$_POST);
		if($ID && $ID>0)
			$iResult = $this->objStagePropertyBLL->delEventDetail($ID);
		echo json_encode(array('iResult'=>$iResult,'ID'=>$ID));
	}
	/**
	 * 获取分类
	 */
	public function getClassList()
	{		
		$TypeID = $this->arrConfig['SpBigClass'][3]['TypeID'];//事件类型
		$Locked = 0;
		$ClassID = -1;
		$arrClassList = null;
		//查找顶级父类
		$arrClass = $this->objStagePropertyBLL->getSpClass($TypeID,1,$Locked);
		if(is_array($arrClass) && count($arrClass)>0)
		{			
			foreach ($arrClass as $val)
			{					
				if($this->arrConfig['SpClassKeyID']['EvtKeyID_01']==$val['KeyID'])
				{
					$ClassID = $val['ClassID'];
					break;
				}
			}				
		}
		//查找子类
		if($ClassID>0)
			$arrClassList = $this->objStagePropertyBLL->getSpClass($ClassID,3,$Locked);
					
		return $arrClassList;
	}	
	/**
	 * 添加事件
	 */
	public function addEvent()
	{
		$iResult = -9999;
		$arrParams['ClassID'] = Utility::isNumeric('ClassID',$_POST);
		$arrParams['SubClassID'] = Utility::isNumeric('SubClassID',$_POST);
		$arrParams['EvtID'] = Utility::isNumeric('EvtID',$_POST);
		$arrParams['EvtTitle'] = Utility::isNullOrEmpty('EvtTitle',$_POST);		
		$arrParams['EvtRule'] = Utility::isNullOrEmpty('EvtRule',$_POST);
		if($arrParams['ClassID'] && $arrParams['EvtTitle'])
			$iResult = $this->objStagePropertyBLL->addEvent($arrParams);

		if($iResult==0)
			$msg='事件信息发布成功';
		elseif($iResult==-1)
			$msg='事件信息发布失败';		
		else
		{
			if(!$arrParams['ClassID'])
				$msg='*请选择正确的分类';	
			elseif(!$arrParams['EvtTitle'])
				$msg='*请输入事件名称';		
		}
		echo json_encode(array('iResult'=>$iResult,'Msg'=>$msg));
	}
	/**
	 * 读取目标对象
	 */
	public function getObjList()
	{
		$strOption = '';
		$strSubOption = '';
		$strBigOption = '';
		$KeyID = Utility::isNumeric('KeyID',$_POST);
		if($KeyID)
		{
			//运势事件
			if($KeyID==$this->arrConfig['SpClassKeyID']['EvtKeyID_06'])
			{
				$objMasterBLL = new MasterBLL();
				$arrLuckyList = $objMasterBLL->getLuckyAll();
				if(is_array($arrLuckyList) && count($arrLuckyList)>0)
				{
					foreach ($arrLuckyList as $val)
					{
						$strOption .= '<option value="'.$val['LuckyID'].'">'.$val['LuckyName'].'</option>';
					}
				}
			}
			//道具事件
			elseif($KeyID==$this->arrConfig['SpClassKeyID']['EvtKeyID_07'])
			{
				//大类
				foreach ($this->arrConfig['SpClass'] as $val)
					$strBigOption .= '<option value="'.$val['TypeID'].'">'.$val['TypeName'].'</option>';
				$strBigOption = '<select id="BigClassID" onchange="ed.GetBigClass()">'.$strBigOption.'</select>';
				//子类
				$arrSubCalss = $this->getClass($this->arrConfig['SpClass'][0]['TypeID'],1);
				$strSubOption = '<select id="SubClassID" onchange="ed.GetSpList()">'.$arrSubCalss['SubOption'].'</select>';
				//道具列表
				if($arrSubCalss['SubClassID']>0)
					$strOption = $this->getSpList($arrSubCalss['SubClassID']);

			}
		}
		if(empty($strOption)) $strOption = '<option value="0">暂无数据</option>';
		echo json_encode(array('BigClass'=>$strBigOption,'SubClass'=>$strSubOption,'ObjList'=>$strOption));
	}
	/**
	 * 读取大类
	 */
	public function getBigClass()
	{
		$BigClassID = Utility::isNumeric('BigClassID',$_POST);
		$arrSubCalss = $this->getClass($BigClassID,1);
		//道具列表
		if($arrSubCalss['SubClassID']>0)
			$strOption = $this->getSpList($arrSubCalss['SubClassID']);
		echo json_encode(array('SubClass'=>$arrSubCalss['SubOption'],'ObjList'=>$strOption));
	}
	/**
	 * 读取子类
	 */
	public function getSubClass()
	{
		$BigClassID = Utility::isNumeric('BigClassID',$_POST);
		$arrSubCalss = $this->getClass($BigClassID,3);		
		echo json_encode($arrSubCalss);
	}
	/**
	 * 读取分类(公共方法)
	 * @param $KeyID 字段TypeID的值
	 * @param $TypeID 1:按TypeID搜索
	 */
	private function getClass($KeyID,$ServerTypeID)
	{
		$strSubOption = '';
		$SubClassID = 0;
		$arrSubCalss = $this->objStagePropertyBLL->getSpClass($KeyID,$ServerTypeID,0);
		if(is_array($arrSubCalss) && count($arrSubCalss)>0)
		{
			$SubClassID = $arrSubCalss[0]['ClassID'];
			foreach ($arrSubCalss as $val)
				$strSubOption .= '<option value="'.$val['ClassID'].'">'.$val['CateName'].'</option>';
		}
		if(empty($strSubOption)) $strSubOption = '<option value="0">暂无分类</option>';
		return array('SubClassID'=>$SubClassID,'SubOption'=>$strSubOption);
	}
	/**
	 * 根据子类读取道具
	 */
	public function getStagePropertyList()
	{
		$SubClassID = Utility::isNumeric('SubClassID',$_POST);
		$strOption = $this->getSpList($SubClassID);
		echo $strOption;
	}
	/**
	 * 按类别读取道具列表
	 */
	private function getSpList($ClassID)
	{
		$strOption = '';
		$arrSpList = $this->objStagePropertyBLL->getSpPublicList($ClassID,1);
		if(is_array($arrSpList) && count($arrSpList))
		{
			foreach ($arrSpList as $val)
				$strOption .= '<option value="'.$val['SpID'].'">'.$val['GoodsName'].'</option>';
		}
		if(empty($strOption)) $strOption = '<option value="0">暂无道具</option>';
		return $strOption;
	}
	
}
?>