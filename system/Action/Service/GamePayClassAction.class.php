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
class GamePayClassAction extends PageBase
{
    public function __construct() {
        $this->arrConfig = unserialize(SYS_CONFIG);
        Utility::chkUserLogin($this->arrConfig);
    }

    public function index()
    {
        $arrResult = $this->getPagerClass();
        $arrTags = array('skin' => $this->arrConfig['skin'], 'Page' => $arrResult['Page'], 'ClassList' => $arrResult['ClassList']);
        Utility::assign($this->smarty, $arrTags);

        $this->smarty->display($this->arrConfig['skin'] . '/Service/GamePayClassList.html');
    }

    public function getPagerClass()
    {
        $arrUserList = null;
        $curPage = Utility::isNumeric('curPage', $_POST);
        $curPage = $curPage <= 0 ? 1 : $curPage;

        $arrParam['fields'] = 'ClassId,ClassName,Bank,CardNo,CardName,Descript';
        $arrParam['tableName'] = 'T_GamePayClass';
        $arrParam['where'] = '';
        $arrParam['order']='ClassId';
        $arrParam['pagesize'] = 20;

        $objCommonBLL = new CommonBLL(0);
        $iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
        $Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
        $arrResult = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);
        foreach ($arrResult as &$v) {
            $v['ClassName'] = Utility::gb2312ToUtf8($v['ClassName']);
            $v['Bank'] = Utility::gb2312ToUtf8($v['Bank']);
            $v['CardNo'] = Utility::gb2312ToUtf8($v['CardNo']);
            $v['CardName'] = Utility::gb2312ToUtf8($v['CardName']);
            $v['Descript'] = Utility::gb2312ToUtf8($v['Descript']);
        }
        unset($v);
        return array('ClassList' => $arrResult, 'Page' => $Page);

    }

    public function getPagerClassList()
    {
        $arrResult = $this->getPagerClass();
        $arrTags = array('skin' => $this->arrConfig['skin'], 'Page' => $arrResult['Page'], 'ClassList' => $arrResult['ClassList']);
        Utility::assign($this->smarty,$arrTags);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/GamePayClassList.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }
    
    //编辑
    public function showEdit()
    {
        $classid = Utility::isNumeric('classid', $_POST);
        $classname = Utility::isNullOrEmpty('classname', $_POST);
        $bank = Utility::isNullOrEmpty('bank', $_POST);
        $descript = Utility::isNullOrEmpty('descript', $_POST);
        $cardno = Utility::isNumeric('cardno', $_POST);
        $cardname = Utility::isNullOrEmpty('cardname', $_POST);

        $arrTags = array('classid' => $classid, 'classname' => $classname, 'bank' => $bank, 'descript' => $descript, 'cardno' => $cardno, 'cardname' => $cardname);
        $send = array('Info'=>$arrTags);
        Utility::assign($this->smarty, $send);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/GamePayClassEdit.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }

    public function doEdit()
    {
        $classid = Utility::isNullOrEmpty('classid', $_POST);
        $classname = Utility::isNullOrEmpty('classname', $_POST);
        $bank = Utility::isNullOrEmpty('bank', $_POST);
        $descript = Utility::isNullOrEmpty('descript', $_POST);
        $cardno = Utility::isNumeric('cardno', $_POST);
        $cardname = Utility::isNullOrEmpty('cardname', $_POST);
        if (!empty($classid) && !empty($classname) &&!empty($bank) &&!empty($cardno) &&!empty($cardname)) {
            $objDataChangeBLL = new MasterBLL();
            $ret = $objDataChangeBLL->setPayclass($classid, $classname, $bank, $cardno, $cardname, $descript);
            echo 0;
        } else {
            echo 1;
        }
    }
}
