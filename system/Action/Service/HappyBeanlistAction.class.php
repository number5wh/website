<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/2/26
 * Time: 10:02
 */
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/UserBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';

require ROOT_PATH . 'Class/BLL/DataChangeLogsBLL.class.php';
class HappyBeanlistAction extends PageBase
{
    public function __construct() {
        $this->arrConfig = unserialize(SYS_CONFIG);
        Utility::chkUserLogin($this->arrConfig);
    }
    public function index() {
        $arrResult = $this->getPagerHappyBeanSort();
        $arrTags = array('skin' => $this->arrConfig['skin'], 'Page' => $arrResult['Page'], 'UserList' => $arrResult['UserList']);
        Utility::assign($this->smarty, $arrTags);

        $this->smarty->display($this->arrConfig['skin'] . '/Service/HappyBeanList.html');
    }

    public function getPagerHappyBeanSort() {
        $strWhere = ' ';
        $arrUserList = null;
        $curPage = Utility::isNumeric('curPage', $_POST);
        $curPage = $curPage <= 0 ? 1 : $curPage;

        $arrParam['fields'] = 'RoleID,RoleName,TotalMoney,ClassId';
        $arrParam['tableName'] = 'T_BankMoneyTop';
        $arrParam['where'] = $strWhere;
        $arrParam['order'] = ' cast(TotalMoney AS bigint) desc';
        $arrParam['pagesize'] = 20;
        //$arrParam['function'] = 'HappyBeanSort';
        $objCommonBLL = new CommonBLL($this->arrConfig['MapType']['DataChangeLogs']);

        $iRecordsCount = $objCommonBLL->getRecordsCountSelect($arrParam);

        $Page = Utility::setPages($curPage, $iRecordsCount, $arrParam['pagesize']);
        if ($iRecordsCount > 0) {
            $arrUserList = $objCommonBLL->getPageListSelect($arrParam, $curPage, false);
        }
        //var_dump($arrUserList);
        if ($arrUserList) {
            $iCount = 0;
            //$objUserBLL = new UserBLL(0);
            $objDataChangeBLL = new DataChangeLogsBLL();
            foreach ($arrUserList as $val) {
//                $status  = $objDataChangeBLL->getRankStatus($val['RoleID']);
//                if($status["iResult"]==1){
//                    $arrUserList[$iCount]['statustxt'] = "<a href='javascript:;' onclick='setstatus({$val['RoleID']},0);' data_id='{$val['RoleID']}'>显示</a>";
//                }
//                else
//                {
//                    $arrUserList[$iCount]['statustxt'] = "<a href='javascript:;' onclick='setstatus({$val['RoleID']},1);' data_id='{$val['RoleID']}'>隐藏</a>";
//                }
//                $arrUserList[$iCount]['BankMoney']  = Utility::FormatMoney($val['BankMoney']);
//                $arrUserList[$iCount]['GameMoney']  = Utility::FormatMoney($val['GameMoney']);
                $arrUserList[$iCount]['TotalMoney']  = Utility::FormatMoney($val['TotalMoney']);
                /*$arrUserInfo = getUserBaseInfo($val['RoleID']);//$objUserBLL->getRoleInfo($arrUserList[$iCount]['RoleID']);

                    if(is_array($arrUserInfo) && count($arrUserInfo)>0)
                    {
                        $arrUserList[$iCount]['LoginID'] = $arrUserInfo['LoginID'];
                        $arrUserList[$iCount]['LoginName'] = $arrUserInfo['LoginName'];
                        $arrUserList[$iCount]['RegIP'] = $arrUserInfo['RegIP'];
                        $arrUserList[$iCount]['LastLoginIP'] = $arrUserInfo['LastLoginIP'];
                    }
                    else
                */
//                $arrUserList[$iCount]['LoginID'] = $val['RoleID'];
                $arrUserList[$iCount]['RoleName'] = Utility::gb2312ToUtf8($val['RoleName']);

                $iCount++;
            }
        }
        return array('UserList' => $arrUserList, 'Page' => $Page);
    }
    /**
     * 分页读取
     */
    public function getPagerHappyBeanList() {
        $arrResult = $this->getPagerHappyBeanSort();
        $arrTags = array('skin' => $this->arrConfig['skin'], 'Page' => $arrResult['Page'], 'UserList' => $arrResult['UserList']);
        Utility::assign($this->smarty, $arrTags);
        $html = $this->smarty->fetch($this->arrConfig['skin'] . '/Service/HappyBeanListPage.html');
        $html = str_replace("</script>", "<\/script>", str_replace("\r\n", '', $html));
        echo $html;
    }

//
//    public function setStatus(){
//        $roleid = Utility::isNullOrEmpty('roleid', $_POST);
//        $state = Utility::isNullOrEmpty('state', $_POST);
//        $html ="";
//        if(!empty($roleid)){
//            $objDataChangeBLL = new DataChangeLogsBLL();
//            $ret = $objDataChangeBLL->setRankStatus($roleid,$state);
//            if($ret==1){
//
//            }
//        }
//        if($state==1){
//            $html ="<a href='javascript:;' onclick='setstatus($roleid,0);' data_id='{$roleid}'>显示</a>";
//        }
//        else
//        {
//            $html ="<a href='javascript:;' onclick='setstatus($roleid,1);' data_id='{$roleid}'>隐藏</a>";
//        }
//        echo $html;
//    }

    public function showEdit()
    {
        $roleid = Utility::isNullOrEmpty('roleid', $_POST);
        $nickname = Utility::isNullOrEmpty('nickname', $_POST);
        $totalmoney = Utility::isNullOrEmpty('totalmoney', $_POST);

        $arrTags = array('roleid' => $roleid, 'nickname' => $nickname, 'totalmoney'=>$totalmoney);
        $send = array('Info'=>$arrTags);
        Utility::assign($this->smarty, $send);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/HappyBeanEdit.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }

    public function doEdit()
    {
        $roleid = Utility::isNullOrEmpty('roleid', $_POST);
        $nickname = Utility::isNullOrEmpty('nickname', $_POST);
        $totalmoney = intval(Utility::isNullOrEmpty('totalmoney', $_POST));
        if ($totalmoney>1000000000) {
            echo 0;
        } else {

            $headurl = intval(rand(1,12));
            if (!empty($roleid) && !empty($nickname)&& !empty($totalmoney)) {
                $objDataChangeBLL = new DataChangeLogsBLL();
                $ret = $objDataChangeBLL->editBankMoneyTop($roleid,Utility::utf8ToGb2312($nickname), $totalmoney,$headurl);
                echo $ret['iResult'];
            } else {
                echo 0;
            }
        }

    }

    public function showAdd()
    {
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/HappyBeanAdd.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }

    public function doAdd()
    {
        $roleid = intval(Utility::isNullOrEmpty('roleid', $_POST));
        $nickname = Utility::isNullOrEmpty('nickname', $_POST);
        $totalmoney = intval(Utility::isNullOrEmpty('totalmoney', $_POST));
        $headurl = intval(rand(1,12));
        if (!$roleid || !$nickname || !$totalmoney || !is_numeric($totalmoney) || $totalmoney>1000000000) {
            echo 0;
        } else {
            $objDataChangeBLL = new DataChangeLogsBLL();
            $ret = $objDataChangeBLL->addBankMoneyTop($roleid,Utility::utf8ToGb2312($nickname), $totalmoney,$headurl);
            echo $ret['iResult'];
        }
    }

    public function deleteInfo()
    {
        $roleid = intval(Utility::isNullOrEmpty('roleid', $_POST));
        if (!$roleid) {
            echo 0;
        } else {
            $objDataChangeBLL = new DataChangeLogsBLL();
            $ret = $objDataChangeBLL->deleteBankMoneyTop($roleid);
            echo $ret['iResult'];
        }
    }

}