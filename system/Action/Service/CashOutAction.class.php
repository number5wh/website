<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserBLL.class.php';
require ROOT_PATH . 'Class/BLL/OperationLogsBLL.class.php';

require_once ROOT_PATH . 'Link/GetRoleBaseInfo.php';
class CashOutAction extends PageBase
{
    private $objMatchBLL = null;
    public function __construct()
    {
        $this->arrConfig=unserialize(SYS_CONFIG);
        Utility::chkUserLogin($this->arrConfig);
    }
    public function index()
    {
        $arrResult = $this->getPagerCashOut(20);
        $arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'CashOutList'=>$arrResult['CashOutList'],'EndTime'=>date('Y-m-d'));
        Utility::assign($this->smarty,$arrTags);

        $this->smarty->display($this->arrConfig['skin'].'/Service/CashOutList.html');
    }

    /**
     * 分页
     */
    public function getPagerCashOut($pagesize)
    {
        $arrCashOutList = null;

        $curPage = Utility::isNumeric('curPage',$_POST);
        $EndTime = Utility::isNullOrEmpty('EndTime',$_POST) ? $_POST['EndTime'] : date('Y-m-d');
        $LoginID = Utility::isNumeric('LoginID',$_POST) ? $_POST['LoginID'] : '';
        $paytype = Utility::isNumeric('payType',$_POST);
        $checktype =  Utility::isNumeric('checktype',$_POST);
        $ispay = Utility::isNumeric('ispay',$_POST);

        $curPage = $curPage<=0 ? 1 : $curPage;
        $strWhere =" where 1=1 ";
        if($LoginID!=''){
            $strWhere.=" and AccountID='{$LoginID}'";
        }

        if($paytype!=''){
            $strWhere.=" and PayWay=".$paytype;
        }

        if($checktype!=''){
            $strWhere.=" and status=".$checktype;
        }

        if($ispay!=''){
            $strWhere.=" and IsDrawback=".$ispay;
        }

        $arrParam['fields']=' OrderNo, AccountID, iMoney,tax, AddTime, PayWay, RealName, CardNo, IsDrawback, BankName,status';
        $arrParam['tableName']='UserDrawBack';
        $arrParam['where']=$strWhere;
        $arrParam['order']='AddTime DESC';
        $arrParam['pagesize']=$pagesize;

        $objCommonBLL = new CommonBLL($this->arrConfig['MapType']['Bank']);
        $iRecordsCount = $objCommonBLL->getRecordsCountSelect($arrParam);
        $Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
        if($iRecordsCount>0)
            $arrCashOutList = $objCommonBLL->getPageListSelect($arrParam,$Page['CurPage']);
        if($arrCashOutList)
        {
            $iCount = 0;
            foreach ($arrCashOutList as $val)
            {
                $arrCashOutList[$iCount]['iMoney'] =Utility::FormatMoney($val['iMoney']);
                $arrCashOutList[$iCount]['tax'] =Utility::FormatMoney($val['tax']);
                $arrCashOutList[$iCount]['sRealName'] = Utility::gb2312ToUtf8($val['RealName']);
                $arrCashOutList[$iCount]['sBankName'] = Utility::gb2312ToUtf8($val['BankName']);
                $iCount++;
            }
        }
        return array('CashOutList'=>$arrCashOutList,'Page'=>$Page);
    }
    /**
     * 分页读取
     */
    public function getPagerCashOutList()
    {
        $arrResult = $this->getPagerCashOut(20);
        $arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'CashOutList'=>$arrResult['CashOutList']);
        Utility::assign($this->smarty,$arrTags);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/CashOutListPage.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }

}
?>