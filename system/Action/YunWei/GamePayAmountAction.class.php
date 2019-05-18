<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/3
 * Time: 14:19
 */
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';

class GamePayAmountAction extends PageBase
{
    public function __construct() {
        $this->arrConfig = unserialize(SYS_CONFIG);
        Utility::chkUserLogin($this->arrConfig);
    }

    public function index()
    {
        $arrResult = $this->getListData();
        $arrTags = array('skin' => $this->arrConfig['skin'], 'Page' => $arrResult['Page'], 'AmountList' => $arrResult['AmountList']);
        Utility::assign($this->smarty, $arrTags);

        $this->smarty->display($this->arrConfig['skin'] . '/Yunwei/GamePayAmountList.html');
    }

    public function getListData()
    {
        $arrUserList = null;
        $curPage = Utility::isNumeric('curPage', $_POST);
        $curPage = $curPage <= 0 ? 1 : $curPage;

        $arrParam['fields'] = 'Id,AmountId,Amount';
        $arrParam['tableName'] = 'T_GamePayAmount';
        $arrParam['where'] = '';
        $arrParam['order']='Id';
        $arrParam['pagesize'] = 20;

        $objCommonBLL = new CommonBLL(0);
        $iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
        $Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
        $arrResult = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);
//        foreach ($arrResult as &$v) {
//            $v['ChannelName'] = Utility::gb2312ToUtf8($v['ChannelName']);
//            $v['Config'] = Utility::gb2312ToUtf8($v['Config']);
//            $v['Descript'] = Utility::gb2312ToUtf8($v['Descript']);
//        }
//        unset($v);
        return array('AmountList' => $arrResult, 'Page' => $Page);

    }

    public function getList()
    {
        $arrResult = $this->getListData();
        $arrTags = array('skin' => $this->arrConfig['skin'], 'Page' => $arrResult['Page'], 'AmountList' => $arrResult['AmountList']);
        Utility::assign($this->smarty,$arrTags);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Yunwei/GamePayAmountListPage.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }
    
    //编辑
    public function showEdit()
    {
        $id = Utility::isNullOrEmpty('id', $_POST);
        $amount = Utility::isNullOrEmpty('amount', $_POST);
        $amountid = Utility::isNullOrEmpty('amountid', $_POST);


        $arrTags = array('id' => $id, 'amount' => $amount, 'amountid' => $amountid);
        $send = array('Info'=>$arrTags);
        Utility::assign($this->smarty, $send);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Yunwei/GamePayAmountEdit.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }

    public function doEdit()
    {
        $amount = Utility::isNumeric('amount', $_POST);
        $amountid= Utility::isNumeric('amountid', $_POST);
        if (!$amount || $amount<=0 || !$amountid || $amountid<=0) {
            echo 1;
        } else {
            $objDataChangeBLL = new MasterBLL();
            $ret = $objDataChangeBLL->editPayamount($amountid, $amount);
            echo $ret[0]['IRESULT'];
        }
    }

    //新增
    public function showAdd()
    {
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Yunwei/GamePayAmountAdd.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }

    public function doAdd()
    {
        $amount = Utility::isNumeric('amount', $_POST);
        if (!$amount || $amount<=0) {
            echo 1;
        } else {
            $objDataChangeBLL = new MasterBLL();
            $ret = $objDataChangeBLL->addPayamount($amount);
            echo $ret[0]['IRESULT'];
        }
    }

    //删除
    public function delete()
    {
        $amountid= Utility::isNumeric('amountid', $_POST);
        if (!$amountid || $amountid<=0) {
            echo 1;
        } else {
            $objDataChangeBLL = new MasterBLL();
            $ret = $objDataChangeBLL->deletePayamount($amountid);
            echo $ret[0]['IRESULT'];
        }
    }
}
