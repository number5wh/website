<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/DataChangeLogsBLL.class.php';
require ROOT_PATH . 'Class/BLL/StagePropertyBLL.class.php';

class MatchAction extends PageBase
{	
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);		
	}
	public function index()
	{
		$MatchTypeID = 0;
		$objMasterBLL = new MasterBLL();
		$arrMatchList = $objMasterBLL->getGameMatchByID($this->arrConfig['MatchMode'][0]['TypeID'],2);//取比赛列表
		if($arrMatchList)
		{
			$MatchTypeID = $arrMatchList[0]['MatchTypeID'];
			$arrGameRoomList = $objMasterBLL->getGameRoomList($MatchTypeID,4);//取66人赛模式的比赛房间
		}
		else
			$arrGameRoomList = null;	
		//$arrGameRoomList = $objMasterBLL->getGameRoomList($this->arrConfig['MatchMode'][0]['TypeID'],3);//取66人赛模式的比赛房间
		//if($arrGameRoomList)
		//	$arrResult = $this->getPagerGameMatch(1,'');
		//else
		$arrResult = null;
		$arrTags=array( 'skin'=>$this->arrConfig['skin'],
						'Page'=>$arrResult['Page'],
						'MatchList'=>$arrResult['arrMatchList'],
						'GameMatchList'=>$arrMatchList,
						'GameRoomList'=>$arrGameRoomList,
						'MatchTypeID'=>$MatchTypeID
						); 
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunYing/GameMatchList.html');
	}	
	/**
	 * 读取相应比赛房间列表
	 */
	public function getMatchRoomList()
	{
		$MatchTypeID = Utility::isNumeric('MatchTypeID',$_POST);
		if($MatchTypeID && $MatchTypeID>0)
		{
			$objMasterBLL = new MasterBLL();
			$arrGameRoomList = $objMasterBLL->getGameRoomList($MatchTypeID,4);//取66人赛模式的比赛房间
			if($arrGameRoomList)
			{
				$strInput = '';
				foreach($arrGameRoomList as $val)
				{
					$strInput .= '<input type="checkbox" class="Room" value="'.$val['RoomID'].'" /> '.$val['RoomName'];
				}
				echo $strInput;
			}
		}
	} 
	/**
	 * 分页读取单元赛记录
	 */
	public function getPagerGameMatchList()
	{
		$StartTime = Utility::isNullOrEmpty('StartTime', $_POST);
		$EndTime = Utility::isNullOrEmpty('EndTime', $_POST);
		$MatchTypeID = Utility::isNullOrEmpty('MatchTypeID', $_POST);
		$RoomID = Utility::isNullOrEmpty('RoomID', $_POST);
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage==0 ? 1 : $curPage;
		$strWhere = ' WHERE 1=1';		
		if($MatchTypeID) $strWhere .= " AND MatchTypeID IN ($MatchTypeID)";
		if($RoomID) $strWhere .= " AND RoomID IN ($RoomID)";
		if($StartTime) $strWhere .= " AND DATEDIFF(d,AddTime,'$StartTime')<=0";
		if($EndTime) $strWhere .= " AND DATEDIFF(d,AddTime,'$EndTime')>=0";
		if($RoomID)
			$arrResult = $this->getPagerGameMatch($curPage,$strWhere);
		else 
			$arrResult = null;
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'MatchList'=>$arrResult['arrMatchList']); 
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunYing/GameMatchListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}	
	/**
	 * 分页
	 */
	private function getPagerGameMatch($curPage,$strWhere)
	{
		//$http = '';
		//$curPage = Utility::isNumeric('curPage',$_POST);
		//$curPage = $curPage==0 ? 1 : $curPage;
		$arrParam['fields']='MatchUnitID,MatchTypeID,RoomID,CONVERT(VARCHAR(8),MatchStartTime,108) AS MatchStartTime,CONVERT(VARCHAR(8),MatchEndTime,108) AS MatchEndTime,iTimes,GiveUpPeople,WinPeople,NewPlayer,CONVERT(VARCHAR(10),MatchStartTime,120) AS PlayDate';
		$arrParam['tableName']='T_GameMatchUnit';
		$arrParam['where']=$strWhere;
		$arrParam['order']='AddTime DESC';
		$arrParam['pagesize']=15;
		
		$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['Match']);
		$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
		$arrMatchList = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);	
		if($arrMatchList)
		{
			$iCount = 0;
			$objMasterBLL = new MasterBLL();
			foreach ($arrMatchList as $val)
			{
				//读取比赛名称
				$arrMatchInfo = $objMasterBLL->getGameMatchByID($val['MatchTypeID'],1);
				if($arrMatchInfo)
					$arrMatchList[$iCount]['MatchName'] = Utility::gb2312ToUtf8($arrMatchInfo['MatchName']);
				else 
					$arrMatchList[$iCount]['MatchName'] = '';
				//读取比赛房间
				$arrRoomInfo = $objMasterBLL->getGameRoomList($val['RoomID'],2);
				if($arrRoomInfo)
					$arrMatchList[$iCount]['RoomName'] = $arrRoomInfo[0]['RoomName'];
				else 
					$arrMatchList[$iCount]['RoomName'] = '';
				//$arrMatchList[$iCount]['MatchName'] = Utility::gb2312ToUtf8($val['MatchName']);
				$iCount++;
			}
		}
		return array('arrMatchList'=>$arrMatchList,'Page'=>$Page);
		/*$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'SpList'=>$arrResult); 
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunYing/GameMatchListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;*/
	}
	/**
	 * 分页读取单元赛排名
	 */
	public function getPagerGameMatchRankList()
	{		
		$iMatchUnitID = Utility::isNumeric('MatchUnitID',$_GET);
		$MatchTypeID = Utility::isNumeric('MatchTypeID',$_GET);
		$PlayDate = Utility::isNullOrEmpty('PlayDate', $_GET);
		$arrResult = $this->getPagerGameMatchRank($iMatchUnitID);
		if($arrResult)
		{			
			$objMasterBLL = new MasterBLL();
			$arrRankArea = $objMasterBLL->getGameMatchRankArea($MatchTypeID);
			$arrTags=array( 'skin'=>$this->arrConfig['skin'],
							'Page'=>$arrResult['Page'],
							'MatchRankList'=>$arrResult['arrMatchRankList'],
							'MatchUnitID'=>$iMatchUnitID,
							'PlayDate'=>$PlayDate,
							'RankArea'=>$arrRankArea		
							); 
			Utility::assign($this->smarty,$arrTags);
			$this->smarty->display($this->arrConfig['skin'].'/YunYing/GameMatchRankList.html');
		}		
	}
	private function getPagerGameMatchRank($iMatchUnitID)
	{		
		if($iMatchUnitID && $iMatchUnitID>0)
		{
			$LoginID = Utility::isNumeric('LoginID', $_POST);
			$Rank = Utility::isNullOrEmpty('Rank', $_POST);
		
			$strWhere = '';		
			if($LoginID) $strWhere .= " AND LoginID=$LoginID";
			if($Rank) $strWhere .= " AND Rank IN ($Rank)";		
		
			$curPage = Utility::isNumeric('curPage',$_POST);
			$curPage = $curPage==0 ? 1 : $curPage;
			$arrParam['fields']='Rank,RoleID,CONVERT(VARCHAR(8),AddTime,108) AS LeaveTime,LoginCode,LoginName,LoginID,Prize,SendStatus,Remarks';
			$arrParam['tableName']='T_GameMatchRank';
			$arrParam['where']=' WHERE MatchUnitID='.$iMatchUnitID.$strWhere;
			$arrParam['order']='Rank ASC';
			$arrParam['pagesize']=18;
			
			$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['Match']);
			$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
			$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
			$arrMatchRankList = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);	
			if($arrMatchRankList)
			{
				$iCount = 0;
				foreach ($arrMatchRankList as $val)
				{
					$arrMatchRankList[$iCount]['LoginName'] = Utility::gb2312ToUtf8($val['LoginName']);					
					$arrMatchRankList[$iCount]['Remarks'] = Utility::gb2312ToUtf8($val['Remarks']);
					$iCount++;
				}
			}
			return array('arrMatchRankList'=>$arrMatchRankList,'Page'=>$Page);
		}
		return null;
	}
	/**
	 * 分页读取单元赛排名
	 */
	public function getPagerGameMatchRankList1()
	{
		$iMatchUnitID = Utility::isNumeric('MatchUnitID',$_POST);
		$arrResult = $this->getPagerGameMatchRank($iMatchUnitID);
		if($arrResult)
		{			
			$arrTags=array( 'skin'=>$this->arrConfig['skin'],
							'Page'=>$arrResult['Page'],
							'MatchRankList'=>$arrResult['arrMatchRankList']							
							); 
			Utility::assign($this->smarty,$arrTags);
			$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunYing/GameMatchRankListPage.html');
			$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
			echo $html;
		}		 
	}	
	/**
	 * 读取单元赛每局记录
	 */
	public function getGameMatchInningsList()
	{
		$iMatchUnitID = Utility::isNumeric('MatchUnitID',$_GET);
		$RoleID = Utility::isNumeric('RoleID',$_GET);
		$PlayDate = Utility::isNullOrEmpty('PlayDate', $_GET);
		if($iMatchUnitID && $iMatchUnitID>0 && $RoleID && $RoleID>0 && $PlayDate)
		{
			$objDataChangeLogsBLL = new DataChangeLogsBLL($RoleID);
			$arrResult = $objDataChangeLogsBLL->getGameMatchLogsList($PlayDate,$iMatchUnitID,$RoleID);			
			
			if($arrResult)
			{
				$iCount = 0;
				foreach ($arrResult as $val)
				{
					$arrResult[$iCount]['MatchStatus']=$this->arrConfig['MatchRound'][$val['MatchStatus']];
					$arrResult[$iCount]['PlayStatus']=$this->arrConfig['PlayStatus'][$val['PlayStatus']];
					$Score = $val['CurScore']-$val['LastScore'];
					$arrResult[$iCount]['Score']=$Score>0 ? '+'.$Score:'<font class="red">'.$Score.'</font>';
					$iCount++;
				}
			}
			$arrTags=array( 'skin'=>$this->arrConfig['skin'],
							'MatchInningsList'=>$arrResult,
							'MatchUnitID'=>$iMatchUnitID,
							'RoleID'=>$RoleID,
							'PlayDate'=>$PlayDate					
							); 
			Utility::assign($this->smarty,$arrTags);
			$this->smarty->display($this->arrConfig['skin'].'/YunYing/GameMatchInningsList.html');
		}			
	}
	/**
	 * 读取单元赛同桌玩家记录
	 */
	private $arrTmpRoleID = array();//已经读取到记录的玩家RoleID
	private $arrDeskPlayer = null;
	public function getGameMatchDeskPlayerList()
	{		
		$arrAllRoleID = null;
		$RoleID = Utility::isNumeric('RoleID',$_GET);
		$PlayDate = Utility::isNullOrEmpty('PlayDate', $_GET);
		$SerialNumber = Utility::isNumeric('SerialNumber', $_GET);
		if($SerialNumber && $SerialNumber>0 && $RoleID && $RoleID>0 && $PlayDate)
		{
			//读取同桌玩家信息
			$this->getGameMatchDeskPlayer($RoleID,$PlayDate,$SerialNumber);
			if($this->arrDeskPlayer)
			{
				$iCount = 0;
				foreach ($this->arrDeskPlayer as $val)
				{
					$this->arrDeskPlayer[$iCount]['LoginName'] = Utility::gb2312ToUtf8($val['LoginName']);
					$this->arrDeskPlayer[$iCount]['PlayStatus'] = $this->arrConfig['PlayStatus'][$val['PlayStatus']];
					$Score = $val['CurScore']-$val['LastScore'];
					$this->arrDeskPlayer[$iCount]['Score'] = $Score>0 ? '+'.$Score:'<font class="red">'.$Score.'</font>';
					$iCount++;
				}
			}
			$arrTags=array( 'skin'=>$this->arrConfig['skin'],
							'MatchDeskPlayerList'=>$this->arrDeskPlayer				
							); 
			Utility::assign($this->smarty,$arrTags);
			$this->smarty->display($this->arrConfig['skin'].'/YunYing/GameMatchDeskPlayerList.html');
		}
	}
	/**
	 * 递归读取同桌玩家
	 * @param unknown_type $RoleID
	 * @param unknown_type $PlayDate
	 * @param unknown_type $SerialNumber 每局游戏流水号
	 */
	private function getGameMatchDeskPlayer($RoleID,$PlayDate,$SerialNumber)
	{
		$objDataChangeLogsBLL = new DataChangeLogsBLL($RoleID);
		$arrResult = $objDataChangeLogsBLL->getGameMatchDeskPlayerList($PlayDate,$SerialNumber);
		if($arrResult)
		{
			$DeskPlayer = $arrResult[0]['RoleID'].','.$arrResult[0]['DeskPlayer'];//同桌所有玩家RoleID(包括自己)
			$arrAllRoleID = explode(',', $DeskPlayer);
			foreach ($arrResult as $val)
				array_push($this->arrTmpRoleID, $val['RoleID']);//把已经读取到记录的玩家RoleID存入到数组
			$arrNewRoleID = array_diff($arrAllRoleID, $this->arrTmpRoleID);//剩余还未读取记录的RoleID
			if(empty($this->arrDeskPlayer))
				$this->arrDeskPlayer = $arrResult;
			else
				$this->arrDeskPlayer = array_merge($this->arrDeskPlayer,$arrResult);//合并之前读取到的记录和当前读取到的记录
			if(is_array($arrNewRoleID) && count($arrNewRoleID)>0)
			{
				$arrKeys = array_keys($arrNewRoleID);//取数组下标	
				if(!empty($arrNewRoleID[$arrKeys[0]]))			
					$this->getGameMatchDeskPlayer($arrNewRoleID[$arrKeys[0]],$PlayDate,$SerialNumber);//递归调用
			}			
		}
		return;
	}	
}
?>