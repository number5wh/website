<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/DataChangeLogsBLL.class.php';

require_once ROOT_PATH . 'Link/GetRoleBaseInfo.php';
class GameRateAction extends PageBase
{	
	private $objMatchBLL = null;	
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);
	}
	public function index()
	{
		$arrResult = null;
		$objMasterBLL = new MasterBLL();
		$arrGameKindList = $objMasterBLL->getGameKindList(-1,0);
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'GameRateList'=>$arrResult['arrGameRateList'],'EndTime'=>date('Y-m-d'),'GameKindList'=>$arrGameKindList);
		Utility::assign($this->smarty,$arrTags);
		
		$this->smarty->display($this->arrConfig['skin'].'/YunYing/GameRateList.html');
	}	 

	/**
	 * 分页
	 */
    public function getPagerGameRate($pagesize){
        $arrGameRateList = null;
        $strWhere = ' WHERE 1=1';
        $curPage = Utility::isNumeric('curPage',$_POST);
        $EndTime = Utility::isNullOrEmpty('EndTime',$_POST) ? $_POST['EndTime'] : date('Y-m-d');
        $RoleID = $LoginID = Utility::isNumeric('LoginID',$_POST);
        $KindID = Utility::isNumeric('KindID',$_POST);
        $OrderField = 'RoleID';//Utility::isNullOrEmpty('OrderField',$_POST) ? $_POST['OrderField'] : 'LogsID';
        $curPage = $curPage<=0 ? 1 : $curPage;
        if($KindID) $strWhere .= " AND KindID={$KindID}";
        if($RoleID){
            $strWhere .= " AND RoleID = $RoleID";
        }

        $arrParam['fields']='* ';
        $arrParam['tableName']='(SELECT RoleID,KindID,SUM(Money) as Money,SUM(CASE WHEN ChangeType=0 THEN 1 ELSE 0 END) as WinCount,SUM(CASE WHEN ChangeType=1 THEN 1 ELSE 0 END) as LostCount,
                            SUM(CASE WHEN ChangeType=2 THEN 1 ELSE 0 END) as DrawCount,SUM(CASE WHEN ChangeType=3 THEN 1 ELSE 0 END) as FleeCount,CONVERT(VARCHAR(100),MAX(AddTime),120) as UpdateTime'
                                .' FROM T_UserGameChangeLogs_'.date('Ymd',strtotime($EndTime))
                                .' WHERE KindID = '.$KindID
                                .' GROUP BY RoleID ,KindID) T';
        $arrParam['where']=$strWhere;
        $arrParam['order']="{$OrderField} DESC";
        $arrParam['pagesize']= $pagesize;

        //var_dump($arrParam);//
        $objCommonBLL = new CommonBLL($this->arrConfig['MapType']['DataChangeLogs']);
        $iRecordsCount = $objCommonBLL->getRecordsCountSelect($arrParam);
        //var_dump($iRecordsCount);
        $Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
        if($iRecordsCount>0)
            $arrGameRateList = $objCommonBLL->getPageListSelect($arrParam,$Page['CurPage']);
        if($arrGameRateList)
        {
            $iCount = 0;
            foreach ($arrGameRateList as $val)
            {

                if(($val['WinCount']+$val['LostCount']+$val['DrawCount']+$val['FleeCount']) != 0)$arrGameRateList[$iCount]['WinRate'] = round($val['WinCount']/($val['WinCount']+$val['LostCount']+$val['DrawCount']+$val['FleeCount'])*100,2);
                else $arrGameRateList[$iCount]['WinRate'] = 0.0;
                //$arrGameRateList[$iCount]['KindID'] = $KindID;
                $arrGameRateList[$iCount]['Date'] = $EndTime;
                if(empty($arrUserInfo))
                {
                    //$objUserBLL = new UserBLL($val['RoleID']);
                    //$arrUserInfo = $objUserBLL->getRoleInfo($val['RoleID']);
                    $iLoginName = getUserLoginName($val['RoleID']);
                }
                if(isset($iLoginName))
                {
                    $arrGameRateList[$iCount]['LoginName'] = $iLoginName;
                    $arrGameRateList[$iCount]['LoginID'] = $val['RoleID'];//$arrUserInfo['LoginID'];
                }
                else
                {
                    $arrGameRateList[$iCount]['LoginName'] = '';
                    $arrGameRateList[$iCount]['LoginID'] = '';
                }
                $arrGameRateList[$iCount]['Money'] =Utility::FormatMoney($val['RoleID']);
                $arrGameRateList[$iCount]['TotalHappyBean'] =Utility::FormatMoney($val['RoleID']);

                if(!$LoginID) $iLoginName=null;
                $iCount++;
            }
        }
        return array('arrGameRateList'=>$arrGameRateList,'Page'=>$Page);
    }
	/*public function getPagerGameRate($pagesize)
	{
		$arrGameRateList = null;
		$strWhere = ' WHERE 1=1';
		$curPage = Utility::isNumeric('curPage',$_POST);
		$EndTime = Utility::isNullOrEmpty('EndTime',$_POST) ? $_POST['EndTime'] : date('Y-m-d');
		$LoginID = Utility::isNumeric('LoginID',$_POST);
		$KindID = Utility::isNumeric('KindID',$_POST);
		$OrderField = Utility::isNullOrEmpty('OrderField',$_POST) ? $_POST['OrderField'] : 'LogsID';
		$curPage = $curPage<=0 ? 1 : $curPage;
		if($KindID) $strWhere .= " AND KindID=$KindID";
		if($LoginID)
		{
			$objUserBLL = new UserBLL(0);
			$arrUserInfo = $objUserBLL->getRole(1,$LoginID);
			if(!empty($arrUserInfo))
				$strWhere .= " AND RoleID=".$arrUserInfo['RoleID'];
			else 
				return array('arrGameRateList'=>null,'Page'=>null);
		}	
		
		$arrParam['fields']='RoleID,WinCount,LostCount,DrawCount,FleeCount,WinRate,TotalMoney,TotalHappyBean,CONVERT(VARCHAR(20),UpdateTime,120) AS UpdateTime';
		$arrParam['tableName']='(SELECT TOP 1000 * FROM T_UserGameDataLogs_'.str_replace('-','',$EndTime)." $strWhere ORDER BY $OrderField DESC) AS T";
		$arrParam['where']=$strWhere;
		$arrParam['order']="$OrderField DESC";
		$arrParam['pagesize']=$pagesize;

		$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['DataChangeLogs']);
		$iRecordsCount = $objCommonBLL->getRecordsCountSelect($arrParam);
	
		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
		if($iRecordsCount>0)
			$arrGameRateList = $objCommonBLL->getPageListSelect($arrParam,$Page['CurPage']);
		if($arrGameRateList)
		{
			$iCount = 0;
			if(empty($arrUserInfo))
				$objUserBLL = new UserBLL(0);
			foreach ($arrGameRateList as $val)
			{				
				$arrGameRateList[$iCount]['WinRate'] = round($val['WinRate']*100,2);
				$arrGameRateList[$iCount]['KindID'] = $KindID;
				$arrGameRateList[$iCount]['Date'] = $EndTime;
				if(empty($arrUserInfo))
				{
					//$objUserBLL = new UserBLL($val['RoleID']);
					$arrUserInfo = $objUserBLL->getRoleInfo($val['RoleID']);
				}
				if(is_array($arrUserInfo) && count($arrUserInfo)>0)
				{
					$arrGameRateList[$iCount]['LoginName'] = $arrUserInfo['LoginName'];
					$arrGameRateList[$iCount]['LoginID'] = $arrUserInfo['LoginID'];
				}
				else 
				{
					$arrGameRateList[$iCount]['LoginName'] = '';
					$arrGameRateList[$iCount]['LoginID'] = '';
				}
				if(!$LoginID) $arrUserInfo=null;
				$iCount++;
			}
		}
		return array('arrGameRateList'=>$arrGameRateList,'Page'=>$Page);
	}*/
	/**
	 * 分页读取
	 */
	public function getPagerGameRateList()
	{
		$arrResult = $this->getPagerGameRate(20);
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'GameRateList'=>$arrResult['arrGameRateList']);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunYing/GameRateListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	/**
	 * 折线图
	 */
	public function showLineCharts()
	{
		$KindName = '';
		$arrResult = null;
		$Date = '';
		$RoleID = Utility::isNumeric('RoleID',$_GET);
		$KindID = Utility::isNumeric('KindID',$_GET);
		$LoginID = Utility::isNumeric('LoginID',$_GET);
		$Date = Utility::isNullOrEmpty('Date', $_GET);
		if($RoleID && $KindID)
		{
			$objMasterBLL = new MasterBLL();
			$arrKind = $objMasterBLL->getGameKindInfo($KindID);
			if(!empty($arrKind)) $KindName = $arrKind['KindName'];//游戏种类名称
			
			$objDataChangeLogsBLL = new DataChangeLogsBLL($RoleID);
			$arrResult = $objDataChangeLogsBLL->getUserGameChangeLogs($RoleID,$KindID,$Date);
			//var_dump($arrResult);
			if(!empty($arrResult))
			{
				$iCount = 0;
				foreach($arrResult as $val)
				{
					$arrResult[$iCount]['Day'] = date('m-d',strtotime($val['Date']));
					$Date = date('Y/m',strtotime($val['Date']));
					$iCount++;
				}
			}
		
		}
		$arrTags=array('skin'=>$this->arrConfig['skin'],'GameData'=>$arrResult,'Date'=>$Date,'LoginID'=>$LoginID,'KindName'=>$KindName);
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunYing/LineCharts.html');
	}
}
?>