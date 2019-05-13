<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserDataBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/OperationLogsBLL.class.php';
require ROOT_PATH . 'Class/BLL/DataChangeLogsBLL.class.php';

require_once ROOT_PATH . 'Link/GetRoleBaseInfo.php';
class GameSortAction extends PageBase
{	
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);
	}
	public function index()
	{	
		$arrResult = null;//$this->getPagerGameSortSort();
        $objMasterBLL= new MasterBLL();
        $GameList = $objMasterBLL->getGameKindList(-1,0);
        //var_dump($GameList);
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'UserList'=>$arrResult['UserList'],'GameList'=>$GameList);
		Utility::assign($this->smarty,$arrTags);	
		
		$this->smarty->display($this->arrConfig['skin'].'/YunYing/GameSortList.html');
	}	 

	/**
	 * 分页
	 */
	public function getPagerGameSortSort()
	{
		$strWhere = '';
		$arrUserList = null;
		$KindID = Utility::isNumeric('KindID',$_POST);
		$KindName = Utility::isNullOrEmpty('KindName',$_POST);
		$OrderBy = Utility::isNullOrEmpty('OrderBy',$_POST) ? $_POST['OrderBy'] : 'DESC';
        $OrderField = Utility::isNullOrEmpty('OrderField',$_POST) ? $_POST['OrderField']:'SummaryMoney';
		$curPage = Utility::isNumeric('curPage',$_POST);		
		$curPage = $curPage<=0 ? 1 : $curPage;
		if($KindID) $strWhere = ' Where KindID='.$KindID.' And TotalMoney<>0';
		$arrParam['fields']='RoleID,SummaryMoney,TotalMoney,KindID,RoomType,WinCount,LostCount,FleeCount,CONVERT(VARCHAR(20),AddTime,120) AS AddTime,RoleName'
                            .',CAST(WinRate*100 AS INT) AS WinRate';
		$arrParam['tableName']='(SELECT TOP 5000 RoleID,RoleName,TotalMoney,(TotalMoney - ClearCount) as SummaryMoney,KindID,RoomType,WinCount,LostCount,FleeCount,'
                                .'(CASE WHEN WinCount+LostCount+FleeCount > 0 THEN (CAST(WinCount AS DECIMAL) /(WinCount+LostCount+FleeCount)) ELSE 0 END) AS WinRate ,'
		                        .'AddTime FROM T_UserGameRank WITH(NOLOCK) '.$strWhere.' ORDER BY '." {$OrderField} " .$OrderBy.') AS T';
		$arrParam['where']=$strWhere;
		$arrParam['order']=" {$OrderField} ".$OrderBy.',RoleID ';
		$arrParam['pagesize']=50;
		$arrParam['function']='';

		$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['DataChangeLogs']);
		$iRecordsCount = $objCommonBLL->getRecordsCountSelect($arrParam);
		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
		if($iRecordsCount>0)
			$arrUserList = $objCommonBLL->getPageListSelect($arrParam,$curPage);
		//var_dump( $arrUserList);
		if($arrUserList)
		{
			$iCount = 0;
			//$objUserBLL = new UserBLL(0);

			foreach ($arrUserList as $val)
			{
					$arrUserList[$iCount]['SummaryMoney'] = sprintf('%.2f',$arrUserList[$iCount]['SummaryMoney']/1000);
                    $arrUserList[$iCount]['RoleName'] = Utility::gb2312ToUtf8($arrUserList[$iCount]['RoleName']);
				$iCount++;
			}
		}
		
		return array('UserList'=>$arrUserList,'Page'=>$Page);
	}
	/**
	 * 分页读取
	 */
	public function getPagerGameSortList()
	{
		$arrResult = $this->getPagerGameSortSort();
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'UserList'=>$arrResult['UserList']);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunYing/GameSortListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	/**
	 * 清零
	 * $iResult: 0:成功,-1:失败
	 */
	public function setUserGameDataTotalMoney()
	{
		$iResult = -1;
		$RoleID = Utility::isNumeric('RoleID',$_POST);
		$KindID = Utility::isNumeric('KindID',$_POST);
		$RoomType = Utility::isNumeric('RoomType',$_POST);
		$TotalMoney = Utility::isNumeric('TotalMoney',$_POST);
		$KindName = Utility::isNullOrEmpty('KindName',$_POST);
        if(empty($KindName)){
            $objMasterDB = new MasterBLL(0);
            $KindInfo = $objMasterDB->getGameKindInfo($KindID);
            $KindName = $KindInfo['KindName'];
        }
		if($RoleID && $KindID  && $RoomType)
		{
			//$objUserDataBLL = new UserDataBLL($RoleID);
			//$iResult = $objUserDataBLL->setUserGameDataTotalMoney($RoleID,$KindID,$RoomType);
			$objDataChangeLogsBLL = new DataChangeLogsBLL();
            $iResult = $objDataChangeLogsBLL->setUserGameDataTotalMoney($RoleID,$KindID,$RoomType);
            if($iResult==0)
			{
                $arrResult = $objDataChangeLogsBLL->getUserGameRankInfo($RoleID,$KindID);
                $RoleName = isset($arrResult[0]['RoleName'])? $arrResult[0]['RoleName']:'（RoleName查询失败）';
				$objSessioinLogin = new Session($this->arrConfig['Session']['SessionLoginName']);
				$SysUserName = $objSessioinLogin->get($this->arrConfig['SessionInfo']['UserName']);	
				$objOperationLogsBLL = new OperationLogsBLL($RoleID);
				$objOperationLogsBLL->addCaseOperationLogs($RoleID, '', 33, "玩家游戏金币输赢清零,清零前金币值:$TotalMoney,游戏类型:$KindName,游戏玩家：$RoleName", $iResult, Utility::getIP(), 0, 2, $SysUserName, '');
			}
		}
		echo json_encode(array('iResult'=>$iResult,'RoleID'=>$RoleID,'KindID'=>$KindID,'RoomType'=>$RoomType));
	}
	/**
	 * 清零
	 * $iResult: 0:成功,-1:失败
	 */
	public function setUserGameDataTotalMoneyAll()
	{
		$iResult = -1;
		$KindID = Utility::isNumeric('KindID',$_POST);	
		$KindName = Utility::isNullOrEmpty('KindName',$_POST);
		if($KindID)		
		{
			//$objUserDataBLL = new UserDataBLL(0);
			//$iResult = $objUserDataBLL->setUserGameDataTotalMoney(0,$KindID,0);
            $objDataChangeLogsBLL = new DataChangeLogsBLL();
            $iResult = $objDataChangeLogsBLL->setUserGameDataTotalMoney(0,$KindID,0);
			if($iResult==0)
			{		
				$objSessioinLogin = new Session($this->arrConfig['Session']['SessionLoginName']);
				$SysUserName = $objSessioinLogin->get($this->arrConfig['SessionInfo']['UserName']);	
				$objOperationLogsBLL = new OperationLogsBLL(0);
				$objOperationLogsBLL->addCaseOperationLogs(0, '', 33, "所有玩家游戏金币输赢清零,游戏类型:$KindName", $iResult, Utility::getIP(), 0, 2, $SysUserName, '');
			}
		}
		echo json_encode(array('iResult'=>$iResult,'KindID'=>$KindID));
	}
	
}
?>