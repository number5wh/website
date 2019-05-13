<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/StagePropertyBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserDataBLL.class.php';

class SpAction extends PageBase
{	
	private $objStagePropertyBLL = null;
	private $objMasterBLL = null;
	private $iVerType = 0;
	private $ServerType = 0;
	private $SearchType = 1;//按TypeID搜索道具分类
	private $SpClassLocked=0;//道具分类锁定状态
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);
		$this->objStagePropertyBLL = new StagePropertyBLL();
		$this->objMasterBLL = new MasterBLL();
		$this->ServerType=42;//上传服务器,文件上传站点类型
	}
	public function index()
	{		
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/SpList.html');
	}	 
	
	/**
	 * 分页
	 */
	public function getPagerSp()
	{
		$http = '';
		$strWhere = ' WHERE TypeID<>3';

		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage==0 ? 1 : $curPage;
		$arrParam['fields']='SpID,ImgPath,GoodsName,ResourceID,SpNumber,Place,SP.Locked,CateName,TypeID,ServerID,Sex,VipID';
		$arrParam['tableName']='T_StagePropertyPublic SP LEFT JOIN T_Class C ON SP.ClassID=C.ClassID';
		$arrParam['where']=$strWhere;
		$arrParam['order']='SpID';
		$arrParam['pagesize']=15;
		
		$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['StageProperty']);
		$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
		$arrResult = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);	
		if(is_array($arrResult) && count($arrResult)>0)
		{	
			$iCount = 0;
			//获取文件上传服务器地址
			$arrServerList = $this->objMasterBLL->getServerList($this->ServerType,0);//0:正常未锁定的
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
				//图片路径,上传服务器地址+图片路径
				$arrResult[$iCount]['ImgPath'] = $http . $arrResult[$iCount]['ImgPath'];
				$arrResult[$iCount]['Place']='';
				$arrResult[$iCount]['iCount']=$iCount+1;
				$arrResult[$iCount]['GoodsName']=Utility::gb2312ToUtf8($val['GoodsName']);
				if($arrResult[$iCount]['Sex']==0)
					$arrResult[$iCount]['Sex']='男';
				elseif($arrResult[$iCount]['Sex']==1)
					$arrResult[$iCount]['Sex']='女';
				else 
					$arrResult[$iCount]['Sex']='不限';
				$arrResult[$iCount]['VipID'] = $arrResult[$iCount]['VipID']==1 ? '是' : '否';
				foreach($this->arrConfig['Place'] as $v)
				{
					if($val['Place'] & $v['TypeID'])
						$arrResult[$iCount]['Place'] .= $v['TypeName'].',';
				}
				if(!empty($arrResult[$iCount]['Place'])) $arrResult[$iCount]['Place'] = substr($arrResult[$iCount]['Place'], 0,strlen($arrResult[$iCount]['Place'])-1);
				$iCount++;
			}
		}
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'SpList'=>$arrResult); 
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/SpListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	/**
	 * 设置游戏道具禁用/启用
	 * $iResult=-1:失败,0:成功
	 */
	public function setSpPublicLocked()
	{
		$iResult = -1;
		$SpID = Utility::isNumeric('SpID',$_POST);
		if($SpID && $SpID>0)
		{			
			$iResult = $this->objStagePropertyBLL->setSpPublicLocked($SpID);
		}
		if($iResult == 0)
	 		$msg=$iResult;
	 	else
	 		$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'Sp\')','道具状态设置失败','false','Sp',$this->arrConfig);	
		echo $msg;
	}
	/**
	 * 删除游戏道具
	 * $iResult=0:删除失败,请重试;-1:您提交的数据异常,请重试;-2:数据库异常,请稍后再试或联系技术人员;大于0:删除成功
	 */
	public function delSpPublic()
	{
		$iResult = -9999;
		$SpID = Utility::isNumeric('SpID',$_POST);	
		if($SpID && $SpID>0)
			$iResult = $this->objStagePropertyBLL->delSpPublic($SpID);	

		if($iResult==0)
	 		$msg=$iResult;	 		
	 	elseif($iResult==-1)	
	 		$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'Sp\')','道具删除失败','false','Sp',$this->arrConfig);	
		else	 	
		 	$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'Sp\')','对不起,您提交的数据异常,请重试','false','Sp',$this->arrConfig);
	 	echo $msg;
	}
	/**
	 * 显示编辑道具信息页面
	 */
	public function showAddSpPublicHtml()
	{
		$strUploadIP = '';
		$GameClassID = -1;
		$ServerID = 0;
		$SpID = Utility::isNumeric('SpID',$_GET);	
		if($SpID && $SpID>0)
		{
			$arrSpInfo = $this->objStagePropertyBLL->getSpPublicInfo($SpID);
			if(is_array($arrSpInfo) && count($arrSpInfo)>0)
			{
				//读取游戏种类
				$arrKindInfo = $this->objMasterBLL->getGameKindInfo($arrSpInfo['KindID']);
				if(is_array($arrKindInfo) && count($arrKindInfo)>0) 
					$GameClassID = $arrKindInfo['ClassID'];
				$arrSpInfo['GameClassID']=$GameClassID;
			}
		}		
		if(empty($arrSpInfo))
			$arrSpInfo=array('SpID'=>0,'GoodsName'=>'','SpNumber'=>'','ResourceID'=>0,'ClassID'=>0,'Sex'=>-1,'Level'=>0,'VipID'=>0,
							 'KindID'=>0,'EffectiveType'=>0,'Effective'=>0,'Unit'=>'天','Number'=>0,'Intro'=>'','Place'=>0,'TypeID'=>1,
							 'GameClassID'=>-1,'ServerID'=>0,'CustomField'=>''); 
		//获取上传站点的IP地址
		$arrServerList = $this->objMasterBLL->getServerList($this->ServerType,0);//0:正常未锁定的

		if(is_array($arrServerList) && count($arrServerList)>0)
		{
			//如果记录已经存在对应的服务器ID,则从服务器列表中筛选出对应的IP地址
			if($arrSpInfo['ServerID']>0)
			{
				foreach ($arrServerList as $v)
				{
					if($arrSpInfo['ServerID']==$v['ServerID'] && !empty($v['ServerIP']))
					{
						$arrServer = explode(',',$v['ServerIP']);
						$strUploadIP = $arrServer[0];
						$ServerID = $arrSpInfo['ServerID'];
						break;
					}
				}
			}
			else //随机获取上传站点的IP地址
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
		$arrServerInfo = array('ServerIP'=>$strUploadIP,'ServerID'=>$ServerID);
		$arrTags=array( 'RecList'=>$this->arrConfig['RecList'],
						'SpClass'=>$this->arrConfig['SpClass'],
						'PlaceList'=>$this->arrConfig['Place'],
						'GameKindClassList'=>$this->arrConfig['GameKindClass'],
						'SpPublic'=>$arrSpInfo,
						'Server'=>$arrServerInfo
						); 
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/SpEdit.html');
	}
	/**
	 * 获取道具分类
	 */
	public function getSpClass()
	{		
		$TypeID = Utility::isNumeric('TypeID',$_POST);	
		$ClassID = Utility::isNumeric('ClassID',$_POST);
		if($TypeID && $TypeID>0)
		{		
			$arrClass = $this->objStagePropertyBLL->getSpClass($TypeID,$this->SearchType,$this->SpClassLocked);
			if(is_array($arrClass) && count($arrClass)>0)
			{
				$strOption = '';				
				foreach ($arrClass as $val)
				{					
					$selected = '';
					if($ClassID==$val['ClassID']) $selected = 'selected';						
					$strOption .= '<option value="'.$val['ClassID'].'" '.$selected.'>'.$val['CateName'].'</option>';
				}				
			}
		}	
		if(empty($strOption)) $strOption='<option value="0">请添加子类</option>';
		echo $strOption;
	}
	/**
	 * 显示应用目标游戏选项
	 */
	public function showTargetSelect()
	{
		$display = 'hide';
		$KeyID = 0;
		$iClassID = Utility::isNumeric('ClassID',$_POST);
		$TypeID = Utility::isNumeric('TypeID',$_POST);
		//获取道具分类
		$arrClass = $this->objStagePropertyBLL->getSpClass($TypeID,$this->SearchType,$this->SpClassLocked);		
		if(is_array($arrClass) && count($arrClass)>0)
		{			
			foreach ($arrClass as $val)
			{				
				if($iClassID==$val['ClassID'])
				{
					$KeyID = $val['KeyID'];
					if($val['Target']==1) $display = 'show';
					break;
				}
			}
			if($KeyID==0) $KeyID = $arrClass[0]['KeyID'];
		}
		echo json_encode(array('Display'=>$display,'KeyID'=>$KeyID));
	}
	/**
	 * 读取游戏种类
	 */
	public function getGameKind()
	{
		$Locked = 0;
		$strRes = '';
		$iClassID = Utility::isNumeric('ClassID',$_POST);
		$iCurKindID = Utility::isNumeric('iCurKindID',$_POST);
		$KeyID = Utility::isNumeric('KeyID',$_POST);
		if($iClassID>0)
		{
			//指定类型下的游戏列表
			$arrKindList = $this->objMasterBLL->getGameKindList($iClassID,$Locked);		
			if(is_array($arrKindList) && count($arrKindList)>0)
			{			
				foreach ($arrKindList as $val)
				{
					//漂白卡,运势卡 只显示道具结算方式的游戏
					if(($KeyID==2005 || $KeyID==2009) && ($val['PayTypeID'] & 32)==0)
						continue;
					//游戏积分卡,负分清零卡 只显示积分结算方式的游戏
					elseif(($KeyID==2007 || $KeyID==2008) && ($val['PayTypeID'] & 1)==0)
						continue;
					$selected = '';
					if($iCurKindID==$val['KindID']) $selected = 'selected';
					$strRes .= '<option value="'.$val['KindID'].'" '.$selected.'>'.$val['KindName'].'</option>';
				}
				//if($iCurKindID==0) $iCurKindID=$arrKindList[0]['KindID'];
			}
			else 
				$strRes = '<option value="0">请选择游戏</option>';
		}
		else 
			$strRes = '<option value="-1">所有游戏</option>';
		echo json_encode(array('KindList'=>$strRes,'RoomList'=>''));
	}	
	/**
	 * 添加道具
	 */
	public function addSpPublic()
	{
		$iResult = -99999;
		$arrParams['GoodsName'] = Utility::isNullOrEmpty('GoodsName',$_POST);
		$arrParams['SpNumber'] = Utility::isNullOrEmpty('SpNumber',$_POST);
		$arrParams['ResourceID'] = Utility::isNumeric('ResourceID',$_POST);
		$arrParams['ClassID'] = Utility::isNumeric('ClassID',$_POST);
		$arrParams['ImgPath'] = Utility::isNullOrEmpty('ImgPath',$_POST);
		$arrParams['ImgPath1'] = Utility::isNullOrEmpty('ImgPath1',$_POST);
		$arrParams['ImgPath2'] = Utility::isNullOrEmpty('ImgPath2',$_POST);
		$arrParams['Sex'] = Utility::isNumeric('Sex',$_POST);
		$arrParams['Level'] = Utility::isNumeric('Level',$_POST);
		$arrParams['VipID'] = Utility::isNumeric('VipID',$_POST);		
		$arrParams['KindID'] = Utility::isNumeric('KindID',$_POST);
		$arrParams['EffectiveType'] = Utility::isNumeric('EffectiveType',$_POST);
		$arrParams['Unit'] = Utility::isNullOrEmpty('Unit',$_POST);
		$arrParams['iNumber'] = Utility::isNumeric('iNumber',$_POST);
		$arrParams['Intro'] = Utility::isNullOrEmpty('Intro',$_POST);
		$arrParams['SpID'] = Utility::isNumeric('SpID',$_POST);
		$arrParams['Place'] = Utility::isNumeric('Place',$_POST);
		$arrParams['ServerID'] = Utility::isNumeric('ServerID',$_POST);
		$arrParams['CustomField'] = Utility::isNullOrEmpty('CustomField',$_POST);
		$arrParams['GiftProb'] = 1;//概率,目前只适用于打宝房间配置的礼包概率
		if($arrParams['GoodsName'] && $arrParams['SpNumber'] && $arrParams['ResourceID'] && $arrParams['ClassID'] && $arrParams['Place'] && $arrParams['ImgPath'] && $arrParams['ServerID'])
		{
			$arrResult = $this->objStagePropertyBLL->addSpPublic($arrParams);
			if(is_array($arrResult) && count($arrResult)>0)
				$iResult = $arrResult['iResult'];
		}

		if($iResult==0)
			$msg='道具信息发布成功';
		elseif($iResult==-1)
			$msg='道具信息发布失败';
		else
		{
			if(!$arrParams['GoodsName'])
				$msg='*请输入道具名称';
			elseif(!$arrParams['SpNumber'])
				$msg='*请输入道具编号';
			elseif(!$arrParams['ResourceID'])
				$msg='*请输入道具资源编号';
			elseif(!$arrParams['ClassID'])
				$msg='*请选择正确的道具分类';
			elseif(!$arrParams['ImgPath'])
				$msg='*请上传道具缩略图';
			elseif(!$arrParams['Place'])
				$msg='*请选择使用场景';
			elseif(!$arrParams['ServerID'])
				$msg='*图片上传服务器可能未配置,请先配置WEB站点服务器';
		}
		echo json_encode(array('iResult'=>$iResult,'Msg'=>$msg));
	}
}
?>