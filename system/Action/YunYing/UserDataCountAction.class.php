<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/StagePropertyBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/BankBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserBLL.class.php';
require ROOT_PATH . 'Class/BLL/MatchBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserDataBLL.class.php';
require ROOT_PATH . 'Class/BLL/DataChangeLogsBLL.class.php';
require ROOT_PATH . 'Class/BLL/OperationLogsBLL.class.php';
require ROOT_PATH . 'Class/BLL/PayLogsBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';

require_once ROOT_PATH . 'Link/GetRoleBaseInfo.php';
require_once ROOT_PATH . 'Link/AddRoleMonery.php';
require_once ROOT_PATH . 'Link/BuyRoleVip.php';

class UserDataCountAction extends PageBase
{
    private $objMatchBLL = null;
    public function __construct()
    {
        $this->arrConfig=unserialize(SYS_CONFIG);
        Utility::chkUserLogin($this->arrConfig);
        //$this->objMatchBLL = new MatchLL();
    }
    public function index()
    {

        $arrResult =$this->getPagerUserDataCount();
        $arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'UserDataCountList'=>$arrResult['arrUserDataCountList'],
            'StartTime'=>date('Y-m-d',strtotime("-30 day")),
            'EndTime'=>date('Y-m-d'));
        Utility::assign($this->smarty,$arrTags);
        $this->smarty->display($this->arrConfig['skin'].'/YunYing/UserDataCountList.html');
    }

    /**
     * 分页
     */
    public function getPagerUserDataCount()
    {
        $strWhere = ' WHERE 1=1';
        $StartTime = Utility::isNullOrEmpty('StartTime',$_POST) ? $_POST['StartTime'] : '';
        $EndTime = Utility::isNullOrEmpty('EndTime',$_POST) ? $_POST['EndTime'] : date('Y-m-d');
        $LoginID = Utility::isNullOrEmpty('LoginID',$_POST) ? str_replace(" ","",$_POST['LoginID']) : '';



        $curPage = Utility::isNumeric('curPage',$_POST);
        $curPage = $curPage==0 ? 1 : $curPage;

        if($LoginID)
        {
            //$objUserBLL = new UserBLL(0);
            //$arrUserInfo = $objUserBLL->getRole(1,$LoginID);
            //if(!empty($arrUserInfo))
            $strWhere .= " AND roleid=".$LoginID;
            //else
            //	return array('arrRechargeList'=>null,'Page'=>null);
        }
        if($StartTime ){
            if($EndTime){
                $strWhere .= " AND adddate>='".$StartTime." 00:00' and adddate<='".$EndTime." 23:59'";
            }
            else
            {
                $strWhere .= " AND adddate>='".$StartTime." 00:00'";
            }
        }
        //if($StartTime) $strWhere .= " AND DATEDIFF(d,adddate,'$StartTime')<=0 ";
        //if($EndTime) $strWhere .= " AND DATEDIFF(d,adddate,'$EndTime')>=0 ";

        $arrParam['fields']='*';
        $arrParam['tableName']='T_GameUserCount';
        $arrParam['where']=$strWhere;
        $arrParam['order']='adddate DESC';
        $arrParam['pagesize']=20;

        $objCommonBLL = new CommonBLL(0);
        $iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
        $Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
        $arrResult = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);
        if(is_array($arrResult) && count($arrResult)>0)
        {
            $iCount = 0;
            foreach ($arrResult as $val)
            {
                $arrResult[$iCount]['agentcharge'] = Utility::FormatMoney($val['agentcharge']);
                $arrResult[$iCount]['appPay'] =sprintf("%.2f",$val['appPay']/10);
                $arrResult[$iCount]['cashout'] =Utility::FormatMoney($val['cashout']);
                $iCount++;
            }
        }

        return array('arrUserDataCountList'=>$arrResult,'Page'=>$Page);
    }
    /**
     * 分页读取
     */
    public function getPagerUserDataCountList()
    {
        $arrResult = $this->getPagerUserDataCount(15);
        $arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'UserDataCountList'=>$arrResult['arrUserDataCountList']);
        Utility::assign($this->smarty,$arrTags);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/YunYing/UserDataCountListPage.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }


}
?>