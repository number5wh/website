<?php
require 'Include/Init.inc.php';
require 'Class/BLL/DataCenterBLL.class.php';
require_once ROOT_PATH . 'Link/CheckRoleSession.php';
require_once ROOT_PATH . 'Link/GetRoleBaseInfo.php';
require_once ROOT_PATH . 'Link/QueryRoleBankInfo.php';
class App {
	function __construct() {
		$method = Utility::isNullOrEmpty('f', $_GET) ? $_GET['f'] : 'index';
		switch ($method) {
		case 'getAndroidVersionDiff':
			$this->getAndroidVersionDiff();
			break;
		case 'getServerIP':
			$this->getServerIP();
			break;
		case 'mIndex':
			$this->mIndex();
			break;
		case 'getServerTime':
			$this->getServerTime();
			break;
		case 'getLoginIntro':
			file_put_contents("./log.txt","fdsfds");
			$this->getLoginIntro();
			break;
		case 'getYunCeng':
			$this->getYunCeng();
			break;
		}
	}

	/**
	 *  获取安卓版本信息
	 */
	private function getAndroidVersionDiff() {
		$VerType = 4;
		$params = Utility::request(['CurVer']);
		$Version = $params['CurVer'];
		$ServerType = 2; //服务器类型 ，下载站
		$Locked = 0;

		$DataCenterBLL = new DataCenterBLL();
		$arrFiles = $DataCenterBLL->Download($VerType, $Version, 0); //获取更新版本文件

		if (!is_array($arrFiles)) {
			Utility::response(-1, '没有更新');
		}
		$new = $arrFiles[0]['Version'];
		$ret = $DataCenterBLL->getAndroidVersionDiff($Version, $new);
		if ($ret) {
			Utility::response(0, '差量更新数组', $ret);
		} else {
			Utility::response(-2, '没有找到适应的更新路径');
		}
		return $ret;
	}

	/**
	 * 获取登陆服务器地址
	 *
	 * */
	public function getServerIP() {
		$ServerType = 2;
		$Locked = 0;
		$DataCenterBLL = new DataCenterBLL();
		$arrServerList = $DataCenterBLL->getGameServerInfoList($ServerType, $Locked); //获取登陆服务器地址
		$ret = array();
		if ($arrServerList) {
			foreach ($arrServerList as $val) {
				$ret[] = $val['ServerIP'];
			}
		}
		$res = $DataCenterBLL->GetYouXiDunInfo();
		$youxidun = array('UseYunCeng' => $res['iAppSwitch']);
		Utility::response(0, $ret, $youxidun);
	}

	public function mIndex() {
		$VerType = 4;
		$params = Utility::request(['CurVer']);
		$Version = $params['CurVer'];
		$ServerType = 2; //服务器类型 ，下载站
		$Locked = 0;
		if (!$Version) {
			Utility::response(-9999, '对不起,文件更新失败!');
		} else {
			$DataCenterBLL = new DataCenterBLL();
			$arrFiles = $DataCenterBLL->Download($VerType, $Version, 0); //获取更新版本文件
			$arrServerList = $DataCenterBLL->getGameServerInfoList($ServerType, $Locked); //获取登陆服务器地址
			$iCount = is_array($arrFiles) ? count($arrFiles) : 0;
			$iCountSvr = is_array($arrServerList) ? count($arrServerList) : 0;
			if ($iCount <= 0) {
				Utility::response(-1, '没有更新', []);
			} else {
				Utility::response(0, "", $arrFiles);
			}
		}

	}

	public function getServerTime() {
		Utility::response(0, null, time());
	}

	public function getLoginIntro() {
// 		$params = Utility::request(['time', 'key']);
// 		$time = $params['time'];
// 		$key = $params['key'];
// 		$secret = md5($time . "3b5af0f0fe7c68ea06d4876d746e219e");

// 		//$ret = "time:$time key:$key secret:$secret now:" . time();
// 		if ($secret != $key) {
// 			//Utility::response(0, null, $ret);
// 			return;
// 		}

// 		if (abs(time() - $time) > 10) {
// 			//Utility::response(0, null, $ret . "xxx");
// 			return;
// 		}
	 
		$ServerType = 2;
		$Locked = 0;
		$DataCenterBLL = new DataCenterBLL();
		$arrServerList = $DataCenterBLL->getGameServerInfoList($ServerType, $Locked); //获取登陆服务器地址			
		$ret = "";
		foreach ($arrServerList as $val) {
			if ($ret) {
				$ret .= ";" . $val['Intro'];
			} else {
				$ret = $val['Intro'];
			}
		}
		foreach ($arrServerList as $val) {
		    $str = explode('|', $val['Intro']);
			$ret .= ";" . sprintf("%d|%d|%s|%s|%s", 0, 0, "Server", $val['ServerIP'],$str[4]);
		}
		$res = $DataCenterBLL->GetYouXiDunInfo();
		$youxidun = array('arrServerList' => $ret, 'UseYunCeng' => $res['iAppSwitch']);
		//$youxidun = array('arrServerList' => $ret, 'UseYunCeng' => 0);
		Utility::response(0, null, $youxidun);
	}
	public function getYunCeng() {

		$RoleID = Utility::isNumeric('RoleID', $_GET);
		$SessionID = Utility::isNullOrEmpty('SessionID', $_GET) ? $_GET['SessionID'] : 'NULL';

		$RoleID = $RoleID + 0;
		$SessionID = $SessionID . "";

		$ret = "-1|-1|127.0.0.1";

		$result = DCCheckRoleSession($RoleID, $SessionID);
		if ($result['iResult'] != 0) {
			echo $ret;
			return;
		}

		$ASRoleBaseInfo = ASGetRoleBaseInfo($RoleID);

		//登录次数小于等于1（新玩家） reg09 reg09l2 180.97.162.14
		if ($ASRoleBaseInfo['iLoginCount'] <= 1) {
			$ret = "101|102|180.97.162.14";
			echo $ret;
			return;
		}

		$DSRoleBaseInfo = DSGetRoleBaseInfo($RoleID);
		$RoleBankInfo = DSQueryRoleBankInfo($RoleID);

		//超级会员 super default 116.211.167.14
		if ($RoleBankInfo['iSuperPlayerLevel'] > 0) {
			$ret = "111|114|116.211.167.14";
			echo $ret;
			return;
		}
		//黄钻会员 blue default 116.211.167.14
		else if ($DSRoleBaseInfo['iVipID'] > 0) {
			$ret = "112|114|116.211.167.14";
			echo $ret;
			return;
		}
		//充值会员2000W豆 vip2 default 116.211.167.14
		else if ($RoleBankInfo['iTotalChargeMoney'] > 20000000) {
			$ret = "110|114|116.211.167.14";
			echo $ret;
			return;
		}
		//充值会员200W豆 vip1 default 116.211.167.14
		else if ($RoleBankInfo['iTotalChargeMoney'] > 2000000) {
			$ret = "109|114|116.211.167.14";
			echo $ret;
			return;
		}
		//9天内注册用户 reg09 reg09l2 180.97.162.14
		else if (time() - $ASRoleBaseInfo['iAddTime'] <= 9 * 24 * 3600) {
			$ret = "101|102|180.97.162.14";
			echo $ret;
			return;
		}
		//30天内注册用户 reg30 reg30l2 180.97.162.79
		else if (time() - $ASRoleBaseInfo['iAddTime'] <= 30 * 24 * 3600) {
			$ret = "103|104|180.97.162.79";
			echo $ret;
			return;
		}
		//PC端老玩家 level1 level2 116.211.167.137
		//手机端老玩家 m1 m2 116.211.167.137
		else {
			//$ret = "105|106|116.211.167.137";
			$ret = "107|108|116.211.167.137";
		}
		echo $ret;
		return;

	}

}
$theApp = new App();
?>