<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserDataBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/OperationLogsBLL.class.php';
require ROOT_PATH . 'Class/BLL/DataChangeLogsBLL.class.php';
require ROOT_PATH . 'Link/DCQueryAllOnlinePlayer.php';
class SpreadOnlineAction extends PageBase {
    public function __construct() {
        $this->arrConfig = unserialize(SYS_CONFIG);
    }


    public function getOnlineUser(){

        $curPage = Utility::isNumeric('curPage',$_POST);
        $curPage = $curPage<=0 ? 1 : $curPage;
        $pagesize =20;
        $arrResult = null;
        $arrResult = DCQueryAllOnlinePlayer($curPage,$pagesize);
        $arrOnlineList = $arrResult["onlinelist"];

        $unique_arr = array_unique($arrOnlineList);

        if($arrOnlineList)
        {
            $iCount = 0;
            //$arrOnlineList =array_flip($arrOnlineList);
            foreach ($arrOnlineList as $val) {

                $loginset = '';
                $LoginId = $val['iUserId'];
                if ($arrOnlineList[$iCount]['nClientType'] == 0) {
                    $loginset = '电脑';
                } else if ($arrOnlineList[$iCount]['nClientType'] == 1) {
                    $loginset = '安卓';
                } else if ($arrOnlineList[$iCount]['nClientType'] == 2) {
                    $loginset = 'IOS';
                }
                $arrOnlineList[$iCount]['device'] = $loginset;
                $arrOnlineList[$iCount]['iUserID'] = $val['iUserId'];
                $arrOnlineList[$iCount]['szUsername'] = Utility::gb2312ToUtf8($val['szAccount']);
                $arrOnlineList[$iCount]['kindname'] = Utility::gb2312ToUtf8($val['szRoomName']);
                $arrOnlineList[$iCount]['gamemoney'] = Utility::FormatMoney($val['iGameMoney']);
                $arrOnlineList[$iCount]['bankmoney'] = Utility::FormatMoney($val['iBankMoney']);
                $iCount++;
            }

        }

        echo  json_encode($arrOnlineList);

    }




}