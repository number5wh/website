<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/DataChangeLogsBLL.class.php';


require_once ROOT_PATH . 'Link/GetRoleBaseInfo.php';
class DayUserChangeAction extends PageBase
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
        $arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'UserBankRateList'=>$arrResult['arrUserBankRateList'],
            "RoleID"=>$arrResult['RoleID'],"searchtype"=>$arrResult['changetype']);
        Utility::assign($this->smarty,$arrTags);
        $this->smarty->display($this->arrConfig['skin'].'/YunYing/DayUserChange.html');
    }

    /**
     * 分页
     */
    public function getPagerUserBankRate($pagesize){
        $arrUserBankRateList = null;
        $curPage = Utility::isNumeric('curPage',$_POST)?$_POST['curPage']:1;
        $LoginID = Utility::isNumeric('RoleID',$_POST);
        $changetype = Utility::isNumeric('searchtype',$_POST);
        //$queryDay = Utility::isNumeric('days',$_GET);
        $RoleID = $LoginID;//当前 LoginID == RoleID

        $SuperUser = Utility::isNumeric('SuperUser',$_GET)? $_GET['SuperUser'] : 0;

        $orderField = Utility::isNullOrEmpty('OrderField',$_POST)?$_POST['OrderField'] : " addtime desc";

        $subTable = 'T_BankWealthChangeLogs_'.date('Ymd');

        $strOrder = "";
        $where =" WHERE 1=1 ";
        if($changetype!=''){
            $where.=" and ChangeType=".$changetype;
        }
        else{
            $where.=" and ChangeType in(2,5)";
        }

        if(!empty($RoleID)){
            $where .=' and RoleId='.$RoleID;
        }

        $MasterDBConfig = $this->arrConfig['MasterDBCONFIG'];
        $MasterDBName = $MasterDBConfig['DBNAME'];
        $arrParam['fields'] = '*';
        $arrParam['Page'] = $curPage;
        $arrParam['pagesize'] = 15;
        $arrParam['where'] = $where;
        $arrParam['tableName'] = $subTable;
        $arrParam['order'] = ' addtime desc';
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
                $arrUserBankRateList[$iCount]['Balance'] = Utility::FormatMoney($val['Balance']);
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
       // print_r($arrUserBankRateList);
        return array('arrUserBankRateList'=>$arrUserBankRateList,'Page'=>$Page,'RoleID'=>$RoleID,"searchtype"=>$changetype);
    }



    /**
     * 分页读取
     */
    public function getBankChangepage()
    {
        //echo(Utility::isNumeric('RoleID',$_POST));
        $arrResult = $this->getPagerUserBankRate(15);
        $arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'UserBankRateList'=>$arrResult['arrUserBankRateList'],
            "RoleID"=>$arrResult['RoleID'],"searchtype"=>$arrResult['changetype']);
        Utility::assign($this->smarty,$arrTags);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/YunYing/DayUserChangePage.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }

}
?>