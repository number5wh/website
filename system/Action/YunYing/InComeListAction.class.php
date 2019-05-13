<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserDataBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/OperationLogsBLL.class.php';
require ROOT_PATH . 'Class/BLL/DataChangeLogsBLL.class.php';

class InComeListAction extends PageBase {
    public function __construct() {
        $this->arrConfig = unserialize ( SYS_CONFIG );
        Utility::chkUserLogin ( $this->arrConfig );
    }
    public function index()
    {
        $arrResult = $this->getPagerIncome(35);
        $start =date("Y-m-d",strtotime("-7 day",time()));
        $arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'arrInComeList'=>$arrResult['arrInComeList'],'EndTime'=>date('Y-m-d'),'StartTime'=>$start,'arrtotal'=>$arrResult['arrtotal']);
        Utility::assign($this->smarty,$arrTags);

        $this->smarty->display($this->arrConfig['skin'].'/YunYing/InComeList.html');
    }

    /**
     * 分页
     */
    public function getPagerIncome($pagesize)
    {
        $arrRegUserList = null;
        $curPage = Utility::isNumeric('curPage',$_POST);
        $curPage = $curPage<=0 ? 1 : $curPage;
        $start =date("Y-m-d",strtotime("-20 day",time()));
        $StartTime = Utility::isNullOrEmpty('StartTime',$_POST)?$_POST['StartTime'] : $start;
        $EndTime = Utility::isNullOrEmpty('EndTime',$_POST) ? $_POST['EndTime'] : date("Y-m-d",strtotime("+1 day",time()));;

        $strWhere ="  where adddate >= '{$StartTime}' and adddate<'{$EndTime}' ";
        $arrParam['fields']='*';//LoginCode,Phone,Realname,CONVERT(VARCHAR(20),AddTime,120) AS AddTime'..'
        $arrParam['tableName']='OM_MasterDB.dbo.T_GameDayMark';
        $arrParam['where']=$strWhere;
        $arrParam['order']='sdate DESC';
        $arrParam['pagesize']=$pagesize;

        $objCommonBLL = new CommonBLL($this->arrConfig['MapType']['DataChangeLogs']);
        $iRecordsCount =$objCommonBLL->getRecordsCountSelect($arrParam);
        $Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);

        $arrtotal =array();
        if($iRecordsCount>0)
            $arrInComeList = $objCommonBLL->getPageListSelect($arrParam,$Page['CurPage']);
        if($arrInComeList)
        {
            $iCount = 0;
            foreach ($arrInComeList as $val)
            {
                $arrInComeList[$iCount]['totalpay'] =$val["totalpay"];
                $arrInComeList[$iCount]['superchange'] =Utility::FormatMoney($val["superchange"]);
                $arrInComeList[$iCount]['cashout'] = Utility::FormatMoney($val["cashout"]);
                $arrInComeList[$iCount]['playeraccout'] =Utility::FormatMoney($val["playeraccout"]);
                $arrInComeList[$iCount]['tax'] = Utility::FormatMoney($val["tax"]);
                // $arrRegUserList[$iCount]['LoginID'] = $val['RoleID'];

                $arrtotal["totalpay"]+=$arrInComeList[$iCount]['totalpay'];
                $arrtotal["superchange"]+=$arrInComeList[$iCount]['superchange'];
                $arrtotal["cashout"]+=$arrInComeList[$iCount]['cashout'];
                $arrtotal["playeraccout"]+=$arrInComeList[$iCount]['playeraccout'];
                $arrtotal["tax"]+=$arrInComeList[$iCount]['tax'];
                $iCount++;
            }
        }
        //print_r($arrInComeList);
        return array('arrInComeList'=>$arrInComeList,'Page'=>$Page,'EndTime'=>$EndTime,'StartTime'=>$StartTime,'arrtotal'=>$arrtotal);
    }
    /**
     * 分页读取
     */
    public function getPagerUserList()
    {
        $arrResult = $this->getPagerIncome(35);
        $arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'arrInComeList'=>$arrResult['arrInComeList'],'Page'=>$arrResult['Page'],'EndTime'=>$arrResult['EndTime'],'StartTime'=>$arrResult['StartTime'],'arrtotal'=>$arrResult['arrtotal']);
        Utility::assign($this->smarty,$arrTags);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/YunYing/InComeListPage.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }
}
?>
