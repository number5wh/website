<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserDataBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/OperationLogsBLL.class.php';
require ROOT_PATH . 'Class/BLL/DataChangeLogsBLL.class.php';

require_once ROOT_PATH . 'Link/GetRoleBaseInfo.php';
class ActiveUserAction extends PageBase {
	public function __construct() {
		$this->arrConfig = unserialize ( SYS_CONFIG );
		Utility::chkUserLogin ( $this->arrConfig );
	}
	public function index() {
		$arrResult = null; 
		$objMasterBLL = new MasterBLL ();
		$arrTags = array (
				'skin' => $this->arrConfig ['skin'],
				'Page' => $arrResult ['Page'],
				'EndTime'=>date('Y-m-d')
		);
		Utility::assign ( $this->smarty, $arrTags );
		
		$this->smarty->display ( $this->arrConfig ['skin'] . '/YunYing/ActiveUserList.html' );
	}

	public function getPagerActiveUserList() {
	    $AddTime = Utility::isNullOrEmpty ( 'AddTime', $_POST ) ? $_POST ['AddTime'] : date ( 'Y-m-d' );
	    $DataChangeLogsBLL = new DataChangeLogsBLL();
	    $arrResult = $DataChangeLogsBLL->getActiveUserNumber($AddTime);
		$arrTags = array (
				'skin' => $this->arrConfig ['skin'],
		        'ActiveUserNumber' => $arrResult[0]['ActiveUserNumber']
		);
		Utility::assign ( $this->smarty, $arrTags );
		$html = $this->smarty->fetch ( $this->arrConfig ['skin'] . '/YunYing/ActiveUserListPage.html' );
		$html = str_replace ( "</script>", "<\/script>", str_replace ( "\r\n", '', $html ) );
		echo $html;
	}
}
?>
