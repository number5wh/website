<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/DataChangeLogsBLL.class.php';
require_once ROOT_PATH . 'Link/GetRoleBaseInfo.php';
class  DateUserChangeAction extends PageBase
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
        $arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],
            'UserBankRateList'=>$arrResult['arrUserBankRateList'],
            'EndTime'=>date('Y-m-d'),'StartTime'=>date('Y-m-d'),"searchtype"=>$arrResult['changetype']);
        Utility::assign($this->smarty,$arrTags);

        $this->smarty->display($this->arrConfig['skin'].'/YunYing/DateUserChangeList.html');
    }

    /**
     * 分页
     */
    public function getPagerUserBankRate($pagesize){
        $arrUserBankRateList = null;
        $curPage = Utility::isNumeric('curPage',$_POST)?$_POST['curPage']:1;
        $StartTime = Utility::isNullOrEmpty('StartTime',$_POST)?$_POST['StartTime'] : date('Y-m-d');
        $EndTime = Utility::isNullOrEmpty('EndTime',$_POST) ? $_POST['EndTime'] : date('Y-m-d');
        $LoginID = Utility::isNumeric('LoginID',$_POST);
        $RoleID = $LoginID;//当前 LoginID == RoleID
        $changetype = Utility::isNumeric('searchtype',$_POST);
        $SuperUser = Utility::isNumeric('SuperUser',$_POST)? $_POST['SuperUser'] : 0;

        $orderField = Utility::isNullOrEmpty('OrderField',$_POST)?$_POST['OrderField'] : " addtime desc";

        $STime = strtotime($StartTime);
        $ETime = strtotime($EndTime);

        $strOrder = "";

        $rateType =$changetype;// Utility::isNumeric('rateType',$_POST) ? $_POST['rateType']:1;//默认搜索游戏类型为2

        $MasterDBConfig = $this->arrConfig['MasterDBCONFIG'];
        $MasterDBName = $MasterDBConfig['DBNAME'];

        $subQuery = '(';
        for($t = $STime; $t <= $ETime;$t = strtotime('+1 day',$t)){

            $subTable = 'T_BankWealthChangeLogs_'.date('Ymd',$t);
            $objDataChangeBLL = new DataChangeLogsBLL();
            $isExits  = $objDataChangeBLL->exitsTable($subTable);
            if($isExits['ret']===1) {
                //var_dump($subTable);
                if ($rateType == '') {
                    $strWhere = ' WHERE ChangeType in(2,5)';//游戏转账类型 赠送
                } else {
                    $strWhere = ' WHERE ChangeType ='.$rateType;//             搜索
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
        $arrParam['tableName'] = ' (SELECT TOP 5000 *  FROM '.$subQuery."  ".$strOrder.") T";
        $arrParam['order'] = 'addtime desc';
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
                $arrUserBankRateList[$iCount]['RoleName'] = Utility::gb2312ToUtf8($val['RoleName']);
                $arrUserBankRateList[$iCount]['TargetName'] = Utility::gb2312ToUtf8($val['TargetName']);
                $arrUserBankRateList[$iCount]['Description'] = Utility::gb2312ToUtf8($val['Description']);
                $arrUserBankRateList[$iCount]['PayName'] = Utility::gb2312ToUtf8($val['PayName']);
                if($val['ChangeType']==5) {
                    $arrUserBankRateList[$iCount]['ChangeType'] = "收入";
                }
                else if($val['ChangeType']==2) {
                    $arrUserBankRateList[$iCount]['ChangeType'] = "转出";
                }
                $iCount++;
            }
        }
        return array('arrUserBankRateList'=>$arrUserBankRateList,'EndTime'=>$EndTime,'StartTime'=>$StartTime,'Page'=>$Page,"searchtype"=>$changetype);
    }

    /**
     * 分页读取
     */
    public function getPagerUserBankReturnList()
    {
        $arrResult = $this->getPagerUserBankRate(20);
        $arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'UserBankRateList'=>$arrResult['arrUserBankRateList'],
            "searchtype"=>$arrResult['changetype'],'EndTime'=>$arrResult['EndTime'],'StartTime'=>$arrResult['StartTime']);
        Utility::assign($this->smarty,$arrTags);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/YunYing/DateUserChangePage.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }

}
?>