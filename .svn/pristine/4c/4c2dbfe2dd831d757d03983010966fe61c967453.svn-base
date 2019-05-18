<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';

require_once ROOT_PATH . 'Link/GetRoleBaseInfo.php';
class BankSelloutAction extends PageBase
{	
	private $objMatchBLL = null;	
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);
	}
	public function index()
	{
        $arrResult = $this->getPagerUserBankRate(20);

        $arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'BankSellout'=>$arrResult['BankSellout'],'EndTime'=>date('Y-m-d'),'StartTime'=>date('Y-m-d'));
		Utility::assign($this->smarty,$arrTags);
		
		$this->smarty->display($this->arrConfig['skin'].'/Service/BankSelloutList.html');
	}	 

	/**
	 * 分页
	 */
    public function getPagerUserBankRate($pagesize){
         $arrUserBankRateList = null;         
        $curPage = Utility::isNumeric('curPage',$_POST)?$_POST['curPage']:1;
        $StartTime = Utility::isNullOrEmpty('StartTime',$_POST)?$_POST['StartTime'] : date('Y-m-d');
        $EndTime = Utility::isNullOrEmpty('EndTime',$_POST) ? $_POST['EndTime'] : date('Y-m-d');
        $TargetID = Utility::isNumeric('TargetID',$_POST); //玩家id


        $STime = strtotime($StartTime);
        $ETime = strtotime($EndTime);

        $strOrder = "";

        $subQuery = '(';
        for($t = $STime; $t <= $ETime;$t = strtotime('+1 day',$t)){

            $subTable = 'T_BankWealthChangeLogs_'.date('Ymd',$t);
            //var_dump($subTable);
            $strWhere = ' WHERE ChangeType=2 and PayID=0';
            if($TargetID){
                $strWhere .= ' AND TargetID ='.$TargetID;
            }


            $subQuery .=  '( SELECT * FROM '.$subTable.' '.$strWhere." " . " ) UNION ";
        }
        $subQuery = substr($subQuery,0,-6);
        $subQuery .= ') TT'; //多表 Union 结果。

        //echo($subQuery);

        $arrParam['fields'] = '*';
        $arrParam['Page'] = $curPage;
        $arrParam['pagesize'] = 20;
        $arrParam['where'] = ' WHERE 1=1';
        $arrParam['tableName'] = ' (SELECT TOP 1000 *  FROM '.$subQuery."  ".$strOrder.") T";
        $arrParam['order'] = 'Addtime desc';
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
                $arrUserBankRateList[$iCount]['ChangeMoney'] = Utility::FormatMoney($val['ChangeMoney']);
//                $arrUserBankRateList[$iCount]['Balance'] = Utility::FormatMoney($val['Balance']);
                $arrUserBankRateList[$iCount]['RoleName'] = Utility::gb2312ToUtf8($val['RoleName']);
//                $arrUserBankRateList[$iCount]['Description'] = Utility::gb2312ToUtf8($val['Description']);
                $arrUserBankRateList[$iCount]['TargetName'] = Utility::gb2312ToUtf8($val['TargetName']);
                $iCount++;
            }
        }
        return array('BankSellout'=>$arrUserBankRateList,'Page'=>$Page);
    }
	
	/**
	 * 分页读取
	 */
	public function getSelloutList()
	{
		$arrResult = $this->getPagerUserBankRate(20);

		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'BankSellout'=>$arrResult['BankSellout']);
//        var_dump($arrResult['BankSellout']);
//        die;
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/BankSelloutListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	
}
?>