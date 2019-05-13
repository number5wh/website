<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/DataChangeLogsBLL.class.php';

require_once ROOT_PATH . 'Link/GetRoleBaseInfo.php';
class UserBankChangeAction extends PageBase
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
		$arrResult = $this->getPagerUserBankRate(20);
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'UserBankRateList'=>$arrResult['arrUserBankRateList'],"RoleID"=>$arrResult["RoleID"]);
		Utility::assign($this->smarty,$arrTags);		
		$this->smarty->display($this->arrConfig['skin'].'/YunYing/UserBankChangeList.html');
	}	 

	/**
	 * 分页
	 */
    public function getPagerUserBankRate($pagesize){       
        $arrUserBankRateList = null;
        $curPage = Utility::isNumeric('curPage',$_POST)?$_POST['curPage']:1;
        $StartTime =   date("Y-m-d",strtotime('-9 day'));
        $EndTime = date('Y-m-d');
        $LoginID = Utility::isNumeric('RoleID',$_GET);
        $queryDay = Utility::isNumeric('days',$_GET);     
        $RoleID = $LoginID;//当前 LoginID == RoleID
        
        $SuperUser = Utility::isNumeric('SuperUser',$_GET)? $_GET['SuperUser'] : 0;

        $orderField = Utility::isNullOrEmpty('OrderField',$_POST)?$_POST['OrderField'] : " addtime desc";//

        
        if(!empty($queryDay))
        {
            $StartTime = date("Y-m-d",strtotime("-3 day"));
        }
        
        $STime = strtotime($StartTime);
        $ETime = strtotime($EndTime);

        $strOrder = "";
        $rateType =1;// Utility::isNumeric('rateType',$_POST) ? $_POST['rateType']:1;//默认搜索游戏类型为2

        $MasterDBConfig = $this->arrConfig['MasterDBCONFIG'];
        $MasterDBName = $MasterDBConfig['DBNAME'];
        $subQuery = '(';
        for($t = $STime; $t <= $ETime;$t = strtotime('+1 day',$t)) {

            $subTable = 'T_BankWealthChangeLogs_' . date('Ymd', $t);
            $objDataChangeBLL = new DataChangeLogsBLL();
            $isExits = $objDataChangeBLL->exitsTable($subTable);
            if ($isExits['ret'] === 1) {
                //var_dump($subTable);
                if ($rateType == 1) {
                    $strWhere = ' WHERE ChangeType = 2';//游戏转账类型 赠送
                } else {
                    // $strWhere = ' WHERE ChangeType = 5';//             搜索
                }
                if ($RoleID) {
                    $strWhere .= ' AND RoleID =' . $RoleID;
                }
                if ($orderField) {
                    $strOrder = ' ORDER BY ' . $orderField;
                }
                $subQuery .= '( SELECT * FROM ' . $subTable . ' ' . $strWhere . " " . " ) UNION ";
            }
        }
        $subQuery = substr($subQuery,0,-6);
        $subQuery .= ') TT'; //多表 Union 结果。

        //echo($subQuery);

        $arrParam['fields'] = '*';
        $arrParam['Page'] = $curPage;
        $arrParam['pagesize'] = 20;
        $arrParam['where'] = ' WHERE 1=1';
        $arrParam['tableName'] = ' (SELECT TOP 2000 *  FROM '.$subQuery."  ".$strOrder.") T";
        $arrParam['order'] = 'addtime desc';
        $objCommonBLL = new CommonBLL($this->arrConfig['MapType']['DataChangeLogs']);
        $iRecordsCount = $objCommonBLL->getRecordsCountSelect($arrParam);

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
                $arrUserBankRateList[$iCount]['ChangeMoney'] = Utility::FormatMoney($val['ChangeMoney']);
                $arrUserBankRateList[$iCount]['Balance'] = Utility::FormatMoney($val['Balance']);
                $arrUserBankRateList[$iCount]['RoleName'] = Utility::gb2312ToUtf8($val['RoleName']);
                $arrUserBankRateList[$iCount]['TargetName'] = Utility::gb2312ToUtf8($val['TargetName']);
                $arrUserBankRateList[$iCount]['Description'] = Utility::gb2312ToUtf8($val['Description']);
                $arrUserBankRateList[$iCount]['PayName'] = Utility::gb2312ToUtf8($val['PayName']);
                if(!$LoginID) $iUserLoginName=null;
                $iCount++;
            }
        }
        return array('arrUserBankRateList'=>$arrUserBankRateList,'Page'=>$Page,"RoleID"=>$RoleID);
    }
    
    
	
	/**
	 * 分页读取
	 */
	public function getBankChangepage()
	{
		$arrResult = $this->getPagerUserBankRate(20);
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'UserBankRateList'=>$arrResult['arrUserBankRateList'],"RoleID"=>$arrResult['RoleID']);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunYing/UserBankChangePage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	
}
?>