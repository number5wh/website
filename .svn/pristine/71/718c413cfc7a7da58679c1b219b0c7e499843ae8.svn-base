<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/DataChangeLogsBLL.class.php';
require ROOT_PATH . 'Class/BLL/MatchBLL.class.php';

class MatchSearchAction extends PageBase
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
				
		$arrTags=array('GameMatchList'=>$arrMatchList,'GameRoomList'=>$arrGameRoomList,'MatchTypeID'=>$MatchTypeID); 
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunYing/GameMatchSearchList.html');
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
			var_dump($arrGameRoomList);
            if($arrGameRoomList)
			{
				$strInput = '';
				foreach($arrGameRoomList as $val)
				{
					$strInput .= '<input type="checkbox" class="Room" value="'.$val['RoomID'].'" /> <span id="Room_'.$val['RoomID'].'">'.$val['RoomName'].'</span>';
				}
				echo $strInput;
			}
		}
	}
	/**
	 * 搜索(按单元赛流水号)
	 */	
	public function searchGameMatchUnit()
	{
		$MatchUnitID = Utility::isNumeric('MatchUnitID',$_POST);
		$objMatchBLL = new MatchBLL();		
		$arrMatchUnitInfo = $objMatchBLL->getGameMatchUnitInfo($MatchUnitID);	

		if($arrMatchUnitInfo)
		{
			$arrMatchUnitInfo['MatchUnitID'] = $MatchUnitID;			
			//读取比赛名称
			$objMasterBLL = new MasterBLL();
			$arrMatchInfo = $objMasterBLL->getGameMatchByID($arrMatchUnitInfo['MatchTypeID'],1);
			if($arrMatchInfo)
				$arrMatchUnitInfo['MatchName'] = Utility::gb2312ToUtf8($arrMatchInfo['MatchName']);
			else
				$arrMatchUnitInfo['MatchName'] = '';
			//读取比赛房间
			$arrRoomInfo = $objMasterBLL->getGameRoomList($arrMatchUnitInfo['RoomID'],2);
			if($arrRoomInfo)
				$arrMatchUnitInfo['RoomName'] = $arrRoomInfo[0]['RoomName'];
			else 
				$arrMatchUnitInfo['RoomName'] = '';
		}
		$arrTags=array('skin'=>$this->arrConfig['skin'],
					   'Match'=>$arrMatchUnitInfo
					  ); 
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunYing/GameMatchSearchUnit.html');
		//$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	/**
	 * 搜索(按玩家编号等条件)
	 */	
	public function searchGameMatchRole()
	{
		$arrParams['LoginID'] = Utility::isNumeric('LoginID',$_POST);
		$arrParams['StartTime'] = Utility::isNullOrEmpty('StartTime',$_POST);
		$arrParams['EndTime'] = Utility::isNullOrEmpty('EndTime',$_POST);
		$arrParams['MatchTypeID'] = Utility::isNumeric('MatchTypeID',$_POST);
		$arrParams['RoomID'] = Utility::isNullOrEmpty('RoomID',$_POST);
		$curPage = Utility::isNumeric('curPage',$_POST);
		$arrParams['CurPage'] = $curPage==0 ? 1 : $curPage;
		if($arrParams['RoomID'])
			$arrResult = $this->getPagerGameMatchRank($arrParams);
		else 
			$arrResult = null;
		if($arrResult)
		{			
			$arrTags=array( 'skin'=>$this->arrConfig['skin'],
							'Page'=>$arrResult['Page'],
							'MatchRankList'=>$arrResult['arrMatchRankList']							
							); 
			Utility::assign($this->smarty,$arrTags);
			$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunYing/GameMatchSearchRank.html');
			$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
			echo $html;
		}	
	}
	private function getPagerGameMatchRank($arrParams)
	{
		if($arrParams['MatchTypeID'] && $arrParams['MatchTypeID']>0)
		{		
			$strWhere = '';		
			if($arrParams['LoginID']) $strWhere .= " AND LoginID=".$arrParams['LoginID'];
			if($arrParams['StartTime']) $strWhere .= " AND DATEDIFF(d,SignUpTime,'".$arrParams['StartTime']."')<=0";	
			if($arrParams['EndTime']) $strWhere .= " AND DATEDIFF(d,SignUpTime,'".$arrParams['EndTime']."')>=0";
			if($arrParams['MatchTypeID']) $strWhere .= " AND MatchTypeID=".$arrParams['MatchTypeID'];	
			if($arrParams['RoomID']) $strWhere .= " AND MatchUnitID IN (SELECT MatchUnitID FROM T_GameMatchUnit WHERE RoomID IN (".$arrParams['RoomID']."))";		

			//$curPage = Utility::isNumeric('curPage',$_POST);
			//$curPage = $curPage==0 ? 1 : $curPage;
			$arrParam['fields']='LoginID,Rank,CONVERT(VARCHAR(10),SignUpTime,120) AS PlayDate,CONVERT(VARCHAR(8),SignUpTime,108) AS SignUpTime,Prize,SendStatus,MatchUnitID,RoleID,RID,LoginName,Remarks';
			$arrParam['tableName']='T_GameMatchRank';
			$arrParam['where']=' WHERE 1=1'.$strWhere;
			$arrParam['order']='MatchUnitID DESC,Rank ASC,AddTime DESC';
			$arrParam['pagesize']=18;
			
			$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['Match']);
			$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
			$Page=Utility::setPages($arrParams['CurPage'],$iRecordsCount,$arrParam['pagesize']);
			$arrMatchRankList = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);	
			if($arrMatchRankList)
			{
				$objMatchBLL = new MatchBLL();
				$iCount = 0;
				foreach ($arrMatchRankList as $val)
				{
					//
					$arrMatchUnitInfo = $objMatchBLL->getGameMatchUnitInfo($val['MatchUnitID']);
					if($arrMatchUnitInfo)
					{
						$arrMatchRankList[$iCount]['RoomID'] = $arrMatchUnitInfo['RoomID'];
						$arrMatchRankList[$iCount]['iTimes'] = $arrMatchUnitInfo['iTimes'];						
						$arrMatchRankList[$iCount]['MatchStartTime'] = $arrMatchUnitInfo['MatchStartTime'];
						$arrMatchRankList[$iCount]['MatchEndTime'] = $arrMatchUnitInfo['MatchEndTime'];
					}
					else 
					{
						$arrMatchRankList[$iCount]['RoomID'] = 0;
						$arrMatchRankList[$iCount]['iTimes'] = 0;
						$arrMatchRankList[$iCount]['MatchStartTime'] = '';
						$arrMatchRankList[$iCount]['MatchEndTime'] = '';
					}
					$arrMatchRankList[$iCount]['Remarks'] = Utility::gb2312ToUtf8($val['Remarks']);
					$arrMatchRankList[$iCount]['LoginName'] = Utility::gb2312ToUtf8($val['LoginName']);
					$iCount++;
				}
			}
			return array('arrMatchRankList'=>$arrMatchRankList,'Page'=>$Page);
		}
		return null;
	}
	
	public function OutputToFile()
	{
		$TotalPage = 0;
		$FilePath = '';
		$arrParams['LoginID'] = Utility::isNumeric('LoginID',$_POST);
		$arrParams['StartTime'] = Utility::isNullOrEmpty('StartTime',$_POST);
		$arrParams['EndTime'] = Utility::isNullOrEmpty('EndTime',$_POST);
		$arrParams['MatchTypeID'] = Utility::isNumeric('MatchTypeID',$_POST);
		$arrParams['RoomID'] = Utility::isNullOrEmpty('RoomID',$_POST);
		$arrParams['FileName'] = Utility::isNullOrEmpty('FileName',$_POST);
		$curPage = Utility::isNumeric('curPage',$_POST);
		$arrParams['CurPage'] = $curPage==0 ? 1 : $curPage;
		
		if($arrParams['RoomID'] && $arrParams['FileName'])
		{
			$arrResult = $this->getPagerGameMatchRank($arrParams);
			$arrTitle = array(Utility::utf8ToGb2312('单元赛流水号,角色ID,玩家编号,玩家昵称,比赛排名,比赛日期,报名时间,开赛时间,离赛时间,奖品明细,发放情况,比赛单元(场次),比赛房间'));
			if($arrResult)
			{
				$arrMatchInfo = null;
				$iCount = 0;
				$objMasterBLL = new MasterBLL();
				foreach ($arrResult['arrMatchRankList'] as $val)
				{
					$arrMatchInfo[$iCount]['MatchUnitID'] = $val['MatchUnitID'];
					$arrMatchInfo[$iCount]['RoleID'] = $val['RoleID'];
					$arrMatchInfo[$iCount]['LoginID'] = $val['LoginID'];
					$arrMatchInfo[$iCount]['LoginName'] = empty($val['LoginName']) ? '' : Utility::utf8ToGb2312($val['LoginName']);
					$arrMatchInfo[$iCount]['Rank'] = $val['Rank'];
					$arrMatchInfo[$iCount]['PlayDate'] = $val['PlayDate'];
					$arrMatchInfo[$iCount]['SignUpTime'] = $val['SignUpTime'];
					$arrMatchInfo[$iCount]['MatchStartTime'] = $val['MatchStartTime'];
					$arrMatchInfo[$iCount]['MatchEndTime'] = $val['MatchEndTime'];
					$arrMatchInfo[$iCount]['Remarks'] = empty($val['Remarks']) ? '' : Utility::utf8ToGb2312($val['Remarks']);
					$arrMatchInfo[$iCount]['SendStatus'] = $val['SendStatus']==1 ? Utility::utf8ToGb2312('发放成功') : ($val['SendStatus']==2 ? Utility::utf8ToGb2312('发放失败') : Utility::utf8ToGb2312('弃赛不发放'));
					$arrMatchInfo[$iCount]['iTimes'] = $val['iTimes'];
					$arrRoomInfo = $objMasterBLL->getGameRoomList($val['RoomID'],2);
					if($arrRoomInfo)
						$arrMatchInfo[$iCount]['RoomName'] = empty($arrRoomInfo[0]['RoomName']) ? '' : Utility::utf8ToGb2312($arrRoomInfo[0]['RoomName']);
					else 
						$arrMatchInfo[$iCount]['RoomName'] = $val['RoomID'];					
					$iCount++;
				}
				$FilePath = 'Files/Match/'.$arrParams['FileName'].'.csv';
				$fp = fopen($FilePath, 'a');
				//如果是第一页,添加标题栏
				if($curPage==1)
				{
					foreach ($arrTitle as $line){
					   fputcsv($fp, explode(',', $line));
					}
				}
				//插入数据
				if($arrMatchInfo)
				{
					foreach ($arrMatchInfo as $line){
					   fputcsv($fp, $line);
					}
				}
				fclose($fp);
				$TotalPage = $arrResult['Page']['TotalPage'];
				if($curPage>=$TotalPage)//已经是最后一页
					$curPage = 0;
				else 
					$curPage++;
			}
			else
				$curPage = 0;			
		}
		else 
			$curPage = 0;
		echo json_encode(array('CurPage'=>$curPage,'TotalPage'=>$TotalPage,'FilePath'=>$FilePath));
	}
}
?>