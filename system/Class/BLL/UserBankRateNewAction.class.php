<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/DataChangeLogsBLL.class.php';

require_once ROOT_PATH . 'Link/GetRoleBaseInfo.php';
class UserBankRateNewAction extends PageBase
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
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'UserBankRateList'=>$arrResult['arrUserBankRateList'],'EndTime'=>date('Y-m-d'),'StartTime'=>date('Y-m-d'));
		Utility::assign($this->smarty,$arrTags);
		
		$this->smarty->display($this->arrConfig['skin'].'/YunYing/UserBankRateNewList.html');
	}	 

	/**
	 * 分页
	 */
    public function getPagerUserBankRate($pagesize){
        $arrUserBankRateList = null;

        $curPage = Utility::isNumeric('curPage',$_POST)?$_POST['curPage']:1;
        $StartTime =date('Y-m-'.'01');// Utility::isNullOrEmpty('StartTime',$_POST)?$_POST['StartTime'] : date('Y-m-d');       
        $EndTime = date('Y-m-d');//Utility::isNullOrEmpty('EndTime',$_POST) ? $_POST['EndTime'] : date('Y-m-d');
        $LoginID = Utility::isNumeric('LoginID',$_POST);
        $RoleID = $LoginID;//当前 LoginID == RoleID

        $SuperUser = 0;//Utility::isNumeric('SuperUser',$_POST)? $_POST['SuperUser'] : 0;  0普通玩家   1 超级玩家

        $orderField = "totalMoney desc";//Utility::isNullOrEmpty('OrderField',$_POST)?$_POST['OrderField'] : " totalMoney desc";

        $STime = strtotime($StartTime);
        $ETime = strtotime($EndTime);

        $strOrder = "";

        $rateType =1;// Utility::isNumeric('rateType',$_POST) ? $_POST['rateType']:1;//默认搜索游戏类型为2

        $MasterDBConfig = $this->arrConfig['MasterDBCONFIG'];
        $MasterDBName = $MasterDBConfig['DBNAME'];
        
        //$totalzz= "(select SUM(ChangeMoney) as totalzz from CD_DataChangelogsDB.dbo.T_BankWealthChangeLogs_".date('Ymd',$t)." where RoleID=T.RoleID and ChangeType=2)";
        
        $subQuery = '(';
        //$tmp = 'SELECT TOP 1000 SUM(ChangeMoney) totalMoney,MAX(RoleName) szRoleName,RoleID FROM T_BankWealthChangeLogs_'.date('Ymd',strtotime($EndTime))." ".$strWhere." GROUP BY RoleID ".$strOrder;
        //var_dump($STime);var_dump($ETime);
        for($t = $STime; $t <= $ETime;$t = strtotime('+1 day',$t)) {

            $subTable = 'T_BankWealthChangeLogs_' . date('Ymd', $t);
            $objDataChangeBLL = new DataChangeLogsBLL();
            $isExits = $objDataChangeBLL->exitsTable($subTable);
            if ($isExits['ret'] === 1) {
                //var_dump($subTable);
                if ($rateType == 1) {
                    $strWhere = ' WHERE ChangeType = 2';//游戏转账类型 赠送
                } else {
                    $strWhere = ' WHERE ChangeType = 5';//             收款
                }
                if ($RoleID) {
                    $strWhere .= ' AND RoleID =' . $RoleID;
                }
                if ($orderField) {
                    $strOrder = ' ORDER BY ' . $orderField;
                }

                if ($rateType == 1) {
                    $strWhere .= " AND (" .// NOT EXISTS(SELECT * FROM ".$MasterDBName.".dbo.T_SuperUser WHERE T_SuperUser.RoleID = " . $subTable . ".RoleID AND T_SuperUser.SuperLevel != 0 ) OR " .
                        ($SuperUser ? ' ' : 'NOT ') . " EXISTS(SELECT * FROM " . $MasterDBName . ".dbo.T_SuperUser WHERE T_SuperUser.RoleID = " . $subTable . ".TargetID AND T_SuperUser.SuperLevel != 0 ) )";
                } else {
                    $strWhere .= " AND (" . //NOT EXISTS(SELECT * FROM ".$MasterDBName.".dbo.T_SuperUser WHERE T_SuperUser.RoleID = " . $subTable . ".PayID AND T_SuperUser.SuperLevel != 0 ) OR " .
                        ($SuperUser ? ' ' : 'NOT ') . " EXISTS(SELECT * FROM " . $MasterDBName . ".dbo.T_SuperUser WHERE T_SuperUser.RoleID = " . $subTable . ".RoleID AND T_SuperUser.SuperLevel != 0 ) )";
                }

                $subQuery .= '( SELECT * FROM ' . $subTable . ' ' . $strWhere . " " . " ) UNION ";
            }
        }
        $subQuery = substr($subQuery,0,-6);
        $subQuery .= ') TT'; //多表 Union 结果。

        echo($subQuery);
       // ,(select SUM(ChangeMoney)  totalzz from T_BankWealthChangeLogs_".date('Ymd',$t)." where RoleID=T.RoleID and ChangeType=2)
        $arrParam['fields'] = "totalMoney,szRoleName,RoleID,totalzz";
        $arrParam['Page'] = $curPage;
        $arrParam['pagesize'] = 10;
        $arrParam['where'] = ' WHERE 1=1';
        $arrParam['tableName'] = ' (SELECT TOP 1000 SUM(ChangeMoney) totalMoney,MAX(RoleName) szRoleName,RoleID,(select SUM(ChangeMoney)    
from T_BankWealthChangeLogs_'.date('Ymd'). '  where  RoleID=TT.RoleID and  ChangeType=2) totalzz FROM '.$subQuery." GROUP BY RoleID ".$strOrder.") T";
        $arrParam['order'] = 'totalMoney desc';
        //echo($arrParam['tableName']);
        $objCommonBLL = new CommonBLL($this->arrConfig['MapType']['DataChangeLogs']);
        $iRecordsCount = $objCommonBLL->getRecordsCountSelect($arrParam);
        //echo($arrParam['tableName']);
        $Page = Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
        if($iRecordsCount > 0){
            $arrUserBankRateList = $objCommonBLL->getPageListSelectCache($arrParam,$arrParam['Page']);
            //var_dump($arrUserBankRateList);
        }
        if($arrUserBankRateList)
        {
            $iCount = 0;
            foreach ($arrUserBankRateList as $val)
            {
//                if(empty($arrUserInfo))
//                {
//                   /* $objUserBLL = new UserBLL($val['RoleID']);
//                    $arrUserInfo = $objUserBLL->getRoleInfo($val['RoleID']);*/
//                    $iUserLoginName = getUserLoginName($val['RoleID']);
//                }
//                if(!empty($iUserLoginName))
//                {
//                    $arrUserBankRateList[$iCount]['LoginName'] = $iUserLoginName;//$arrUserInfo['LoginName'];
//                    //$arrUserBankRateList[$iCount]['LoginID'] = $arrUserInfo['LoginID'];
//                }
//                else
//                {
//                    $arrUserBankRateList[$iCount]['LoginName'] = '';
//                    //$arrUserBankRateList[$iCount]['LoginID'] = '';
//                }
                $arrUserBankRateList[$iCount]['RoleName'] = Utility::gb2312ToUtf8($val['szRoleName']);
                $arrUserBankRateList[$iCount]['LoginID'] = $val['RoleID'];
                if(!$LoginID) $iUserLoginName=null;
                $iCount++;
            }
        }
        return array('arrUserBankRateList'=>$arrUserBankRateList,'Page'=>$Page);
    }
	/*public function getPagerUserBankRate($pagesize)
	{
		$arrUserBankRateList = null;
		$strWhere = ' WHERE 1=1';
		$curPage = Utility::isNumeric('curPage',$_POST);
		$EndTime = Utility::isNullOrEmpty('EndTime',$_POST) ? $_POST['EndTime'] : date('Y-m-d');
		$LoginID = Utility::isNumeric('LoginID',$_POST);

		$OrderField = Utility::isNullOrEmpty('OrderField',$_POST) ? $_POST['OrderField'] : 'LogsID';
		$curPage = $curPage<=0 ? 1 : $curPage;

		if($LoginID)
		{
			$objUserBLL = new UserBLL(0);
			$arrUserInfo = $objUserBLL->getRole(1,$LoginID);	
			if(!empty($arrUserInfo))
				$strWhere .= " AND RoleID=".$arrUserInfo['RoleID'];
			else 
				return array('arrUserBankRateList'=>null,'Page'=>null);
		}
		
		$arrParam['fields']='RoleID,TransAmount,CONVERT(VARCHAR(20),UpdateTime,120) AS UpdateTime';
		$arrParam['tableName']='(SELECT TOP 1000 * FROM T_BankTransDataLogs_'.str_replace('-','',$EndTime)." $strWhere ORDER BY $OrderField DESC) AS T";
		$arrParam['where']=$strWhere;
		$arrParam['order']="$OrderField DESC";
		$arrParam['pagesize']=$pagesize;

		$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['DataChangeLogs']);
		$iRecordsCount = $objCommonBLL->getRecordsCountSelect($arrParam);

		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
		if($iRecordsCount>0)
			$arrUserBankRateList = $objCommonBLL->getPageListSelect($arrParam,$Page['CurPage']);
		if($arrUserBankRateList)
		{
			$iCount = 0;
			foreach ($arrUserBankRateList as $val)
			{				
				if(empty($arrUserInfo))
				{
					$objUserBLL = new UserBLL($val['RoleID']);
					$arrUserInfo = $objUserBLL->getRoleInfo($val['RoleID']);
				}
				if(is_array($arrUserInfo) && count($arrUserInfo)>0)
				{
					$arrUserBankRateList[$iCount]['LoginName'] = $arrUserInfo['LoginName'];
					$arrUserBankRateList[$iCount]['LoginID'] = $arrUserInfo['LoginID'];
				}
				else 
				{
					$arrUserBankRateList[$iCount]['LoginName'] = '';
					$arrUserBankRateList[$iCount]['LoginID'] = '';
				}
				if(!$LoginID) $arrUserInfo=null;
				$iCount++;
			}
		}
		return array('arrUserBankRateList'=>$arrUserBankRateList,'Page'=>$Page);
	}*/
	/**
	 * 分页读取
	 */
	public function getPagerUserBankRateList()
	{
		$arrResult = $this->getPagerUserBankRate(20);
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'UserBankRateList'=>$arrResult['arrUserBankRateList']);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunYing/UserBankRateNewListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	
}
?>