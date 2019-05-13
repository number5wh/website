<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserDataBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/OperationLogsBLL.class.php';
require ROOT_PATH . 'Class/BLL/DataChangeLogsBLL.class.php';

class OnlineUserAction extends PageBase {
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
		Utility::assign( $this->smarty, $arrTags );

		$this->smarty->display ( $this->arrConfig ['skin'] . '/YunYing/OnlineUserList.html' );
	}

	public function getPagerOnlineUser() {
	    $StartTime = Utility::isNullOrEmpty ( 'StartTime', $_POST ) ? $_POST ['StartTime'] : date ( 'Y-m-d' );
	    $EndTime = Utility::isNullOrEmpty('EndTime', $_POST) ? $_POST['EndTime'] :date('Y-m-d');
	    $OperationLogsBLL = new OperationLogsBLL(0);
	    $arrHallOnlineUser = $OperationLogsBLL->getHallOnlineUser($StartTime,$EndTime);
	    $arrRoomOnlineUser = $OperationLogsBLL->getRoomOnlineUser($StartTime,$EndTime);
	    $TotalSize = count($arrHallOnlineUser);
	    for($i=0 ;$i<count($arrHallOnlineUser); $i++)
	    {
	        if( $i%($TotalSize/10) == 0)
	        {$arrHallOnlineUser[$i]['show']='1';
	         $arrHallOnlineUser[$i]['AddTime']=substr($arrRoomOnlineUser[$i]['AddTime'],0,13).'时';
	        }
	    }
		$arrTags = array (
				'skin' => $this->arrConfig ['skin'],
		        'StartTime'=>$StartTime,
		        'EndTime'=>$EndTime,
		        'arrHallOnlineUser'=>$arrHallOnlineUser,
		        'arrRoomOnlineUser'=>$arrRoomOnlineUser,
		        'TotalSize'=>$TotalSize
		);
		Utility::assign ( $this->smarty, $arrTags );
		$html = $this->smarty->fetch ( $this->arrConfig ['skin'] . '/YunYing/OnlineUserListPage.html' );
		$html = str_replace ( "</script>", "<\/script>", str_replace ( "\r\n", '', $html ) );
		echo $html;
	}
}
?>
