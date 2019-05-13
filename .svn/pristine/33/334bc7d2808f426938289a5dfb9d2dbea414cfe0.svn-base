<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/StagePropertyBLL.class.php';

class GameRoomAction extends PageBase
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
		//$this->objStagePropertyBLL = new StagePropertyBLL();
		$this->objMasterBLL = new MasterBLL();
	}
	public function index()
	{
		//游戏种类列表
		$arrKindList = $this->objMasterBLL->getGameKindList(-1,-1);
		//服务器列表
		$arrServerList = $this->objMasterBLL->getServerList(1,-1);
		$arrServerIP = array();
		if (is_array($arrServerList)){
    		foreach($arrServerList as $v){
    			$a = array();
    			$b = array();
    			if(preg_match("/[\,]/", $v['ServerIP'])){
    				$arrServer = explode(",", $v['ServerIP']); 
    				$length = count($arrServer);				
    				for($j=0; $j<$length; $j++){
    					$a = explode(":", $arrServer[$j]);
    					$b[$j] = $a[0]; 
    				}
    			}else{
    				$a = explode(":", $v['ServerIP']);
    				$b[0] = $a[0]; 
    			}
    			//合并数组
    			$arrServerIP = array_merge($arrServerIP,$b);			
    		}
		}
		//去除重复IP
		$arrServerIP = array_unique($arrServerIP);
		$arrTags=array('KindList'=>$arrKindList,'ServerList'=>$arrServerIP);
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/GameRoomList.html');
	}	 
	
	/**
	 * 分页
	 */
	public function getPagerRoom()
	{
		$strWhere = ' WHERE 1=1';
		$KindID = Utility::isNumeric('KindID',$_POST);
		$ServerIP = Utility::isNullOrEmpty('ServerIP',$_POST);
		$RoomName = Utility::isNullOrEmpty('RoomName',$_POST);
		if($KindID) $strWhere .= ' AND R.KindID='.$KindID;
		if($ServerIP) $strWhere .= ' AND S.ServerIP like \'%'.$ServerIP.'%\'';
		if($RoomName) $strWhere .= ' AND RoomName like \'%'.Utility::utf8ToGb2312($RoomName).'%\'';
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage==0 ? 1 : $curPage;
		$arrParam['fields']='RoomID,KindID,RoomType,RoomName,MaxTableCount,MaxPlayerCount,ServerIP,TableSchemeId';
		$arrParam['tableName']='T_GameRoomInfo R LEFT JOIN T_GameServerInfo S ON R.ServerID=S.ServerID';
		$arrParam['where']=$strWhere;
		$arrParam['order']='KindID';
		$arrParam['pagesize']=22;
		
		$objCommonBLL = new CommonBLL(0);
		$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);		
		
		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
		$arrResult = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);
		
		//获取游戏名称数组
		$arrGameKindName = $this->getGameKindName();
		if(is_array($arrResult) && count($arrResult)>0)
		{
			$iCount = 0;
			foreach ($arrResult as $val)
			{
				$arrResult[$iCount]['GameKindIDName'] = $arrGameKindName[$val['KindID']]."(".$val['KindID'].")";
				$arrResult[$iCount]['RoomName']=Utility::gb2312ToUtf8($val['RoomName']);
				$arrResult[$iCount]['ServerIP']=str_replace(',','<br />',$val['ServerIP']);
				
				if(($val['RoomType']&1) == 1)
					$arrResult[$iCount]['RoomTypeName'] = "积分房间";
				elseif(($val['RoomType']&2) == 2)
					$arrResult[$iCount]['RoomTypeName'] = "金币房间";				
				elseif(($val['RoomType']&4) == 4)
					$arrResult[$iCount]['RoomTypeName'] = "比赛房间";		
				elseif(($val['RoomType']&8) == 8)
					$arrResult[$iCount]['RoomTypeName'] = "[体 验]";
				if(($val['RoomType']&32) == 32)
					$arrResult[$iCount]['RoomTypeName'] = "道具房间";
				$arrGameTableScheme = $this->objMasterBLL->getGameTableSchemeList($val['TableSchemeId']); 
				$arrResult[$iCount]['TableSchemeName'] = $arrGameTableScheme[0]['SchemeName'];
				$iCount++;
			}
		}
		
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'GameRoomList'=>$arrResult); 
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/GameRoomPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	
	/**
	 * 将游戏种类数组整理成 KindID=>KIndName形式的新数组
	 */
	public function getGameKindName()
	{
		//游戏种类列表
		$arrKindList = $this->objMasterBLL->getGameKindList(-1,-1); 
		if(is_array($arrKindList) && count($arrKindList)>0)
		{
			$arrNewKindList = array();
			foreach($arrKindList as $v){
				$arrNewKindList[$v['KindID']] = $v['KindName'];
			}
			return $arrNewKindList;
		}
		return '';
	}
	/**
	 * 显示复制游戏房间弹出层
	 */
	public function showCopyGameRoomHtml()
	{
		$arrSpInfo = null;
		$arrMatchSetInfo = null;
		$RoomID = Utility::isNumeric('RoomID',$_POST);
		if($RoomID && $RoomID>0)
		{
			$arrGameRoomInfo=$this->objMasterBLL->getGameRoomInfo($RoomID);
			if(is_array($arrGameRoomInfo) && count($arrGameRoomInfo)>0 && $arrGameRoomInfo['ClassID']>0)
			{
				$iClassID = $arrGameRoomInfo['ClassID'];
				/* if(($arrGameRoomInfo['RoomType'] & $this->arrConfig['RoomType'][2]['TypeID'])>0)//打宝房间
				 $arrSpInfo = $this->objStagePropertyBLL->getPresentRoomSp($RoomID);
				 else */if(($arrGameRoomInfo['RoomType'] & $this->arrConfig['RoomType'][2]['TypeID'])>0)//比赛房间
					$arrMatchSetInfo = $this->objMasterBLL->getGameRoomInfoMatchSetting($RoomID);
			}
			else
				$iClassID = $this->arrConfig['GameKindClass'][0]['ClassID'];
		
		}
		$Locked = 0;
		//读取游戏种类
		$arrKindList = $this->objMasterBLL->getGameKindList($iClassID,$Locked);
		
		//读取游戏服务器列表
		$arrServerList = $this->objMasterBLL->getServerList(1,$Locked);
		//读取桌子规格
		$arrTableList = $this->objMasterBLL->getGameTableSchemeList(0);
		
		$arrTags=array('GameKindClassList'=>$this->arrConfig['GameKindClass'],
				'GameKindList'=>$arrKindList,
				//'RoomTypeList'=>$this->arrConfig['RoomType'],
				'SpTypeList'=>$this->arrConfig['SpClass'],
				'ServerList'=>$arrServerList,
				'TableList'=>$arrTableList,
				'DropSpList'=>$arrSpInfo,
				'GameRoomMatch'=>$arrMatchSetInfo,
				'GameRoom'=>$arrGameRoomInfo,
				'GetPrizeTypeList'=>$this->arrConfig['GetPrizeTypeList'],
				'GetStatusList'=>$this->arrConfig['GetStatusList'],
				'MatchTypeList'=>$this->objMasterBLL->getGameMatchAll()//所有赛事,
		);
		//	$html=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'GameRoom\')','对不起,您提交的数据异常,请重试','false','GameRoom',$this->arrConfig);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/GameRoomCopy.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	/**
	 * 显示添加游戏房间弹出层
	 */
	public function showAddGameRoomHtml()
	{		
		$arrSpInfo = null;
		$arrMatchSetInfo = null;
		$RoomID = Utility::isNumeric('RoomID',$_POST);
		if($RoomID && $RoomID>0)
		{
			$arrGameRoomInfo=$this->objMasterBLL->getGameRoomInfo($RoomID);
			if(is_array($arrGameRoomInfo) && count($arrGameRoomInfo)>0 && $arrGameRoomInfo['ClassID']>0)
			{
				$iClassID = $arrGameRoomInfo['ClassID'];
				/* if(($arrGameRoomInfo['RoomType'] & $this->arrConfig['RoomType'][2]['TypeID'])>0)//打宝房间
					$arrSpInfo = $this->objStagePropertyBLL->getPresentRoomSp($RoomID);
				else */if(($arrGameRoomInfo['RoomType'] & $this->arrConfig['RoomType'][2]['TypeID'])>0)//比赛房间
					$arrMatchSetInfo = $this->objMasterBLL->getGameRoomInfoMatchSetting($RoomID);
			}
			else
				$iClassID = $this->arrConfig['GameKindClass'][0]['ClassID'];
				
				
		}
		else 
		{
			$iClassID = $this->arrConfig['GameKindClass'][0]['ClassID'];
		    $arrGameRoomInfo=array('RoomID' => 0,'KindID' => 0,'RoomType' => 0,'ServerID' => 0,'MaxPlayerCount' => 0,
								   'MaxTableCount' => 0,'TableSchemeId' => 0,'SortID' => 0,'RoomName' => '','EnterPrompt' => '',
								   'RulePrompt' => '','StatusChangeTime' => '','CanJoinWhenPlaying' => 0,'AllowLook' => 0,
								   'RoomWealthMin' => 0,'TableWealthMin' => 0,'RoomScoreMax' => 0,'TableScoreMax' => 0,
								   'MoneyMaxInGame' => 0,'AutoRun' => 0,'StartMode' => 0,'StartForMinUser' => 0,
								   'MaxLookUser' => 0,'CellScoreType' => 0,'CellScore' => 0,'MaxSitTime' => 0,
								   'MaxStartTime' => 0,'MaxFreeTime' => 0,'AllowChatOption' => 0,
								   'EJectMoney' => 0,'CustomField' => '','ClassID' => 0,'PlayCountMax' => 0,
								   'FleeCountMax' => 0,'RoleLevelMin' => 0,'RoomNumMax1' => 0,'RoomNumMax2' => 0);
		}
		$Locked = 0;
		//读取游戏种类
		$arrKindList = $this->objMasterBLL->getGameKindList($iClassID,$Locked);
		
		//读取游戏服务器列表
		$arrServerList = $this->objMasterBLL->getServerList(1,$Locked);		
		//读取桌子规格
		$arrTableList = $this->objMasterBLL->getGameTableSchemeList(0);
		
		$arrTags=array('GameKindClassList'=>$this->arrConfig['GameKindClass'],
					   'GameKindList'=>$arrKindList,
					   //'RoomTypeList'=>$this->arrConfig['RoomType'],
					   'SpTypeList'=>$this->arrConfig['SpClass'],
					   'ServerList'=>$arrServerList,
					   'TableList'=>$arrTableList,
					   'DropSpList'=>$arrSpInfo,
					   'GameRoomMatch'=>$arrMatchSetInfo,
					   'GameRoom'=>$arrGameRoomInfo,
					   'GetPrizeTypeList'=>$this->arrConfig['GetPrizeTypeList'],
					   'GetStatusList'=>$this->arrConfig['GetStatusList'],
					   'MatchTypeList'=>$this->objMasterBLL->getGameMatchAll()//所有赛事
					   );
		//	$html=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'GameRoom\')','对不起,您提交的数据异常,请重试','false','GameRoom',$this->arrConfig);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/GameRoomEdit.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}	
	/**
	 * 获取道具分类
	 */
	public function getStagePropertyClass()
	{		
		$TypeID = Utility::isNumeric('TypeID',$_POST);	
		if($TypeID && $TypeID>0)
		{		
			$arrClass = $this->objStagePropertyBLL->getSpClass($TypeID,$this->SearchType,$this->SpClassLocked);
			if(is_array($arrClass) && count($arrClass)>0)
			{
				$strOption = '';				
				foreach ($arrClass as $val)
				{		
					$strOption .= '<option value="'.$val['ClassID'].'">'.$val['CateName'].'</option>';
				}				
			}
		}	
		if(empty($strOption)) $strOption='<option value="0">请添加子类</option>';
		echo $strOption;
	}
	/**
	 * 读取指定类别下的道具
	 */
	public function getSpPublicList()	
	{
		$strSpList = '';
		$ClassID = Utility::isNumeric('ClassID',$_POST);	
		if($ClassID && $ClassID>0)
		{
			$arrSpList = $this->objStagePropertyBLL->getSpPublicList($ClassID,1);
			if(is_array($arrSpList) && count($arrSpList)>0)
			{
				foreach ($arrSpList as $val)
				{
					if($val['Locked']) continue;
					$strSpList .= '<div class="left" id="'. $val['SpID'] .'" TypeID="" title="点击选择">' . $val['GoodsName'] . '</div>';
				}
			}
		}
		echo $strSpList;
	}
	/**
	 * 读取游戏种类列表
	 */
	public function getGameKind()
	{
		$Locked = 0;
		$strRes = '';
		$iClassID = Utility::isNumeric('classid',$_POST);
		$arrKindList = $this->objMasterBLL->getGameKindList($iClassID,$Locked);		
		if(is_array($arrKindList) && count($arrKindList)>0)
		{
			foreach ($arrKindList as $val)
			{
				$strRes .= '<option value="'.$val['KindID'].'">'.$val['KindName'].'</option>';
			}
		}
		else 
			$strRes = '<option value="0">请选择游戏</option>';
		echo $strRes;
	}
	/**
	 * 读取游戏种类信息
	 */
	public function getGameKindInfo()
	{
		//$strCustomField = '';
		$arrKindInfo = null;
		$strOption = '';
		$iKindID = Utility::isNumeric('KindID',$_POST);
		$RoomType = Utility::isNumeric('RoomType',$_POST);
		if($iKindID && $iKindID>0)
		{
			$arrKindInfo = $this->objMasterBLL->getGameKindInfo($iKindID);	
			if(is_array($arrKindInfo) && count($arrKindInfo)>0)
			{
				//$strCustomField = $arrKindInfo['CustomField'];
				for($i=0;$i<count($this->arrConfig['RoomType']);$i++)
				{
					$selected='';
					$TypeID = $this->arrConfig['RoomType'][$i]['TypeID'];
					$TypeName = $this->arrConfig['RoomType'][$i]['TypeName'];
					if($RoomType & $TypeID) $selected='selected';
					if(($TypeID & $arrKindInfo['PayTypeID']) > 0)
					{
						$strOption .= '<option value="'.$TypeID.'" '.$selected.'>'.$TypeName.'</option>';
					}
				}				
			}
		}
		if(empty($strOption)) $strOption = '<option value="0">请选择</option>';
		$arrKind = array('CustomField'=>$arrKindInfo['CustomField'],'Option'=>$strOption);
		echo json_encode($arrKind);
	}
	/**
	 * 配置房间信息
	 */
	public function addGameRoom()
	{
		$iResult = -9999;
		$iRoomID = 0;
		$Status = 4;//状态,4:获胜赠送
		$arrResult = null;
		$arrParams['RoomID']=Utility::isNumeric('RoomID',$_POST);
		$arrParams['KindID']=Utility::isNumeric('KindID',$_POST);

		$arrParams['RoomType']=Utility::isNumeric('RoomType',$_POST);
        $arrParams['LuckyEggTaxRate'] = Utility::isNumeric('LuckyEggTaxRate', $_POST);
		$arrParams['MatchTypeID']=Utility::isNumeric('MatchType',$_POST);
		$arrParams['ExpMoney']=Utility::isNumeric('ExpMoney',$_POST);
		$arrParams['ServerID']=Utility::isNumeric('ServerID',$_POST);
		$arrParams['RoomName']=Utility::isNullOrEmpty('RoomName',$_POST);
		$arrParams['MaxTableCount']=Utility::isNumeric('MaxTableCount',$_POST);
		$arrParams['MaxPlayerCount']=Utility::isNumeric('MaxPlayerCount',$_POST);
		$arrParams['EnterPrompt']=$_POST['EnterPrompt'];
		$arrParams['RulePrompt']=str_replace("\n","\\n",$_POST['RulePrompt']);
		$arrParams['AllowLook']=Utility::isNumeric('AllowLook',$_POST);
		$arrParams['StartMode']=Utility::isNumeric('StartMode',$_POST);
		$arrParams['StartForMinUser']=Utility::isNumeric('StartForMinUser',$_POST);
		$arrParams['CanJoinWhenPlaying']=Utility::isNumeric('CanJoinWhenPlaying',$_POST);
		$arrParams['MaxLookUser']=Utility::isNumeric('MaxLookUser',$_POST);
		$arrParams['AutoRun']=Utility::isNumeric('AutoRun',$_POST);
		$arrParams['MaxSitTime']=Utility::isNumeric('MaxSitTime',$_POST);
		$arrParams['MaxStartTime']=Utility::isNumeric('MaxStartTime',$_POST);
		$arrParams['MaxFreeTime']=Utility::isNumeric('MaxFreeTime',$_POST);
		
		$arrParams['CustomField']=$_POST['CustomField'];		
		$arrParams['AllowChatOption']=Utility::isNumeric('AllowChatOption',$_POST);		
		
		$arrParams['RoomWealthMin']=Utility::isNumeric('RoomWealthMin',$_POST);
		$arrParams['RoomNumMax1']=Utility::isNumeric('RoomNumMax1',$_POST);
		$arrParams['TableWealthMin']=Utility::isNumeric('TableWealthMin',$_POST);
		$arrParams['RoomNumMax2']=Utility::isNumeric('RoomNumMax2',$_POST);
		$arrParams['PlayCountMax']=Utility::isNumeric('PlayCountMax',$_POST);
		$arrParams['FleeCountMax']=Utility::isNumeric('FleeCountMax',$_POST);
		$arrParams['RoleLevelMin']=Utility::isNumeric('RoleLevelMin',$_POST);
		$arrParams['CellScoreType']=Utility::isNumeric('CellScoreType',$_POST);
		$arrParams['CellScore']=Utility::isNumeric('CellScore',$_POST);
		$arrParams['TableSchemeId']=Utility::isNumeric('TableSchemeId',$_POST);
		$arrParams['SpIDList']=Utility::isNullOrEmpty('SpIDList',$_POST);
		$arrParams['SpProb']=Utility::isNullOrEmpty('SpProb',$_POST);
		
		$arrParams['RobotJoinWhenPlaying'] = Utility::isNumeric('RobotJoinWhenPlaying', $_POST);
		$arrParams['SetFlag']=Utility::isNumeric('SetFlag',$_POST);
		$arrParams['MaxMatchs']=Utility::isNumeric('MaxMatchs',$_POST);
		$arrParams['MaxMatchNumber']=Utility::isNumeric('MaxMatchNumber',$_POST);
		$arrParams['MatchStartDate']=Utility::isNullOrEmpty('MatchStartDate',$_POST) ? $_POST['MatchStartDate'] : '1970-01-01';
		$arrParams['MatchStartTime']=Utility::isNullOrEmpty('MatchStartTime',$_POST) ? $_POST['MatchStartTime'] : '00:00:00';
		$arrParams['MatchEndDate']=Utility::isNullOrEmpty('MatchEndDate',$_POST) ? $_POST['MatchEndDate'] : '1970-01-01';
		$arrParams['MatchEndTime']=Utility::isNullOrEmpty('MatchEndTime',$_POST) ? $_POST['MatchEndTime'] : '00:00:00';
		$arrParams['MatchTimeStatus']=Utility::isNumeric('MatchTimeStatus',$_POST);
		$arrParams['GetPrizeType']=Utility::isNumeric('GetPrizeType',$_POST);
		$arrParams['GetStatus']=Utility::isNumeric('GetStatus',$_POST);
		$arrParams['MatchModel']=Utility::isNumeric('MatchModel',$_POST);
		if($arrParams['KindID'] && $arrParams['RoomType'] && $arrParams['ServerID'])
			$arrResult = $this->objMasterBLL->addGameRoom($arrParams);
		if(is_array($arrResult) && count($arrResult)>0)
		{
			$iResult = $arrResult['iResult'];	
			$iRoomID = $arrResult['RoomID'];	
		}
		if($iResult==0)
		{
			if(($arrParams['RoomType'] & 32) > 0 && is_numeric(str_replace(',', '', $arrParams['SpIDList'])))
			{
				$arrSpID = explode(',', $arrParams['SpIDList']);
				$arrSpProb = explode(',', $arrParams['SpProb']);
				if(is_array($arrSpID) && is_array($arrSpProb) && count($arrSpID)==count($arrSpProb))
				{
					for($i=0;$i<count($arrSpID);$i++)
					{
						if(!empty($arrSpID[$i]) && !empty($arrSpProb[$i]) && is_numeric($arrSpID[$i]) && (is_numeric($arrSpProb[$i]) || is_float($arrSpProb[$i])))
							$this->objStagePropertyBLL->setPresentRoomSp($iRoomID,$arrSpID[$i],$Status,$arrSpProb[$i]);
					}
				}
			}
			$msg='游戏房间发布成功';
		}
		elseif($iResult==-1)
			$msg='游戏房间发布失败';
		else 
		{
			if(!$arrParams['KindID'])
				$msg='请选择正确的游戏类型';
			elseif(!$arrParams['RoomType'])
				$msg='请选择正确的房间类型';
			elseif(!$arrParams['ServerID'])
				$msg='请选择正确的服务器地址';
		}
		echo json_encode($msg);		
	}
	/**
	 * 删除游戏房间
	 * $iResult=0:成功,-1:失败;-9999:您提交的数据异常,请重试;
	 */
	public function delGameRoomInfo()
	{
		$iResult = -9999;
		$RoomID = Utility::isNumeric('RoomID',$_POST);
		$RoomType = Utility::isNumeric('RoomType',$_POST);	
		if($RoomID && $RoomID>0)		
			$iResult = $this->objMasterBLL->delGameRoomInfo($RoomID);			

		if($iResult==0)
		{
			//如果是道具房间,删除房间的同时,删除房间道具
			if(($RoomType & 32)>0)
				$this->objStagePropertyBLL->delPresentRoomSp($RoomID,2);	
	 		$msg=Utility::echoResultHtml($this->smarty,'确 认','main.CloseMsgBox(true,\'GameRoom\')','游戏房间删除成功','true','GameRoom',$this->arrConfig);
		}
		elseif($iResult==-1)	
	 		$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'GameRoom\')','游戏房间删除失败','false','GameRoom',$this->arrConfig);	
	 	else	 	
		 	$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'GameRoom\')','对不起,您提交的数据异常,请重试','false','GameRoom',$this->arrConfig);
	 	echo $msg;
	}
	/**
	 * 删除房间掉落道具
	 * @return 0:成功,-1:失败
	 */
	public function delPresentRoomSp()
	{
		$ID = Utility::isNumeric('ID',$_POST);	
		$SpID = Utility::isNumeric('SpID',$_POST);	
		if($ID && $ID>0)		
			$iResult = $this->objStagePropertyBLL->delPresentRoomSp($ID,1);			
		else 
			$iResult = -9999;
		echo json_encode(array('iResult'=>$iResult,'SpID'=>$SpID));
	}
}
?>