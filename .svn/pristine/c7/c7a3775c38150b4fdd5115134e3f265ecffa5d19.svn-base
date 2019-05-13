<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/CDAccountBLL.class.php';
class DownUserPhoneAction extends PageBase {
	private $objCDAccountBLL = NULL;
	public function __construct() {
		$this->arrConfig = unserialize ( SYS_CONFIG );
		Utility::chkUserLogin ( $this->arrConfig );
	}
	public function index() {
		$arrTags = array (
				'skin' => $this->arrConfig ['skin'] 
		);
		Utility::assign ( $this->smarty, $arrTags );
		
		$this->smarty->display ( $this->arrConfig ['skin'] . '/YunWei/DownUserPhoneList.html' );
	}
	public function getPagerUserPhone() {
		$objCDAccountBLL = new CDAccountBLL ();
		$MobileList = $objCDAccountBLL->getUserPhone ();
		$RMobileList = array ();
		$arrTags = array (
				"status" => 0,
				"url" => '' 
		);
		$dir = 'mobile/';
		if (! file_exists ( $dir ))
			mkdir ( $dir );
		$FileName = date ( 'Y-m-d' ) . '.txt';
		$FilePath = 'mobile/' . $FileName;
		if (! file_exists ( $FilePath )) {
			$fp = fopen ( ROOT_PATH . $FilePath, 'w' );
			for($i = 0; $i < count ( $MobileList ); $i ++) {
				$RMobileList [$i] = $MobileList [$i] ['Mobile'];
			}
			$RMobileList = array_unique ( $RMobileList );
			for($i = 0; $i < count ( $RMobileList ); $i ++) {
				if (isset ( $RMobileList [$i] ) && preg_match ( '/^(13|14|15|17|18)\d{9}$/', $RMobileList [$i] ))
					fwrite ( $fp, $RMobileList [$i] . "\r\n" );
			}
			fclose ( $fp );
		}
		$url = '/' . $FilePath;
		if ($url) {
			$arrTags ['status'] = 1;
			$arrTags ['url'] = $url;
		}
		echo json_encode ( $arrTags );
	}
}
?>