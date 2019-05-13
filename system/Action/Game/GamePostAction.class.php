<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/UserBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';

require_once ROOT_PATH . 'Link/GetRoleBaseInfo.php';
require_once ROOT_PATH . 'Link/QuerySuperUserList.php';
class GamePostAction extends PageBase {
	public function __construct() {
		$this->arrConfig = unserialize(SYS_CONFIG);
	}

	public function getMsgInfo() {
        $objMasterBLL = new MasterBLL();
        $arrServerList = $objMasterBLL->getMsgInfoFront(2);

        if(is_array($arrServerList) && count($arrServerList)>0){
            $arrMsgInfo = $arrServerList[0];
            $arrMsgInfo = array_values($arrMsgInfo);
        } else {
            $arrMsgInfo = [];
        }

        echo json_encode($arrMsgInfo,JSON_UNESCAPED_UNICODE);
	}
}
?>