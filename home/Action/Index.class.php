<?php
require_once ROOT_PATH . 'Common/PageBase.class.php';
require_once ROOT_PATH . 'Class/BLL/SafetyServiceBLL.class.php';
require_once ROOT_PATH . 'Class/BLL/IndexServiceBLL.class.php';
/**
 * 首页
 * @author xuluojiong
 */
class IndexAction extends PageBase {
	private $skin = null;
	private $template_dir = null;
	private $smarty;
	private $CFG = null;
	private $objSession = null;
	private $objSafetyService = null;
	private $objIndexService = null;

	//当前分页
	private $pageids;
	private $param = array();
	public function __construct() {
		global $smarty;
		$this->CFG = unserialize(SYS_CONFIG);
		$this->skin = $this->CFG['skin'];
		$this->template_dir = $this->CFG['template_dir'];
		$this->smarty = $smarty;
		Utility::assign(array('url' => $this->CFG['URL']));

	}
	/**
	 * 首页
	 */
	public function index() {		
		$filename = 'Logs/msg.txt';
		$fp = fopen($filename, "r");
		$data = array();
		$k = 0;
		if ($fp) {
			while (!feof($fp)) {
				$buffer = fgets($fp, 4096);
				$buffer = json_decode($buffer);
				$buffer = (array) $buffer;
				if ($buffer) {
					$data[$k] = $buffer;
				}
				$k = ($k + 1) % 20;
			}
		}
		fclose($fp);	
		$this->objSafetyService = new SafetyServiceBLL();		
		$prize = "";
		$res = $this->objSafetyService->GetHomeCaijinMsg();		
		$message = array();
		$t = time();
		$date = date("Y-m-d", $t);
		for ($i = 0; $i < count($res['CaijinMsg']); $i++) {
			$message[$i] = $res['CaijinMsg'][$i]['szMsg'];
			$prize .= $res['CaijinMsg'][$i]['szMsg'];
		}
		$b = preg_match_all('/\d+/', $prize, $number);
		//根据数值长度添加日期，若活动信息为空则不添加
		for ($i = 0; $i < min(20, sizeof($number[0])); $i++) {
			$message[$i] = $message[$i] . '  ' . $date;
		}		
		$arrTags = array('GameKind' => $this->CFG['GameKind'], 'msg' => $data, 'prize' => $prize, 'num' => $number, 'mes' => $message, 'date' => $date);		
		Utility::assign($arrTags);		
		$this->smarty->display($this->skin . '/index.html');
	}

	/**
	 * 防盗号秘籍
	 */
	public function noBurglar() {
		$this->smarty->display($this->skin . '/noBurglar.html');
	}

	/**
	 * 防沉迷系统
	 */
	public function antiAddiction() {
		$this->smarty->display($this->skin . '/antiAddiction.html');
	}

	/**
	 * 家长监护工程
	 */
	public function guardian() {
		$this->smarty->display($this->skin . '/guardian.html');
	}

	/**
	 * 游戏大厅下载
	 */
	public function load() {
		$this->smarty->display($this->skin . '/load.html');
	}

	/**
	 * 游戏手机端下载
	 */
	public function app_load() {
		$download_android = file_get_contents(dirname($_SERVER["DOCUMENT_ROOT"]) . "/logs/download_android.txt");
		$download_ios = file_get_contents(dirname($_SERVER["DOCUMENT_ROOT"]) . "/logs/download_ios.txt");
		$arrTags = array('download_android' => $download_android, 'download_ios' => $download_ios);
		Utility::assign($arrTags);
		$this->smarty->display($this->skin . '/app_load.html');
	}
	/**
	 * 游戏手机端下载
	 */

	public function download() {
		$url = 'http://down.game779.com/install/game779.apk';
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
			//微信
			if (strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')) {
				$url = 'https://fir.im/7jt1';
			} elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')) {
				$url = 'https://fir.im/7jt1';
			}
			Utility::assign(array('url' => $url));
			$this->smarty->display($this->skin . '/download.html');
		} else {
			if (strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')) {
				$url = 'https://fir.im/7jt1';
			} elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')) {
				$url = 'https://fir.im/7jt1';
			}
			header("location: " . $url);
		}
	}
	/**
	 * 游戏手机端下载 新
	 */
	public function download_tmp() {
		$url = 'http://down.game779.com/install/game593_30.apk';
		//$url = 'http://116.211.168.169:801/lobby/game593_29.apk';
		// $url = 'https://www.game779.com/download/game593.apk';
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
			//微信
			Utility::assign(array('url' => $url));
			$this->smarty->display($this->skin . '/download.html');
		} else {
			$filename = dirname($_SERVER["DOCUMENT_ROOT"]) . "/logs/download_android.txt";
			$num = file_get_contents($filename);
			$num = intval($num) + 1;
			file_put_contents($filename, $num);
			header("location: " . $url);
		}
	}
	/**
	 * 游戏手机端ios下载
	 */
	public function download_ios() {
		//$url = 'https://itunes.apple.com/us/app/593you-xi-zhong-xin/';
		$url = 'https://fir.im/7jt1/';
		//$url = 'https://itunes.apple.com/app/id1166449653/';
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
			//微信
			Utility::assign(array('url' => $url));
			$this->smarty->display($this->skin . '/download.html');
		} else {
			$filename = dirname($_SERVER["DOCUMENT_ROOT"]) . "/logs/download_ios.txt";
			$num = file_get_contents($filename);
			$num = intval($num) + 1;
			file_put_contents($filename, $num);
			header('location: ' . $url);
		}
	}
	/**
	 * 游戏资讯
	 */
	public function news() {
		$id = $_GET['id'];
		$this->smarty->display($this->skin . '/news/' . $id . '.html');
	}
	/**
	 * 产品基本信息
	 */
	public function goodsinfo() {
		$this->smarty->display($this->skin . '/goodsinfo.html');
	}
	/**
	 * 安全
	 */
	public function anquan() {
		$this->smarty->display($this->skin . '/anquan.html');
	}
	/**
	 * 公司简介
	 */
	public function intro() {
		$this->smarty->display($this->skin . '/intro.html');
	}
	/**
	 * 最新活动
	 */
	public function activity() {
		$id = $_GET['id'];
		$this->smarty->display($this->skin . '/activity/' . $id . '.html');
	}
	public function chkVerify() {
		$CheckCode = Utility::isNullOrEmpty('CheckCode', $_POST);
		$ChkCode = $this->objSession->get($this->CFG['SessionInfo']['ChkCode']);
		if ($ChkCode !== $CheckCode) {
			echo -1;
		} else {
			echo 0;
		}

	}
	public function success() {
		$this->smarty->display($this->skin . '/success.html');
	}

	public function editPassword() {
		$this->smarty->display($this->skin . '/editPassword.html');
	}

	/***
		     * 验证是否是正确的手机号
		     *
	*/
	public function isMobile($mobile) {
		if (preg_match('/^(13|14|15|17|18)\d{9}$/', $mobile)) {
			return true;
		}

		return false;
	}

	public function getPagerHappyBeanSortTop100() {
		$this->objIndexService = new IndexServiceBLL();
		$res = $this->objIndexService->getPagerHappyBeanSortTop100();
		echo $res;
	}

	public function getMsgInfo()
	{
		$this->objIndexService = new IndexServiceBLL();
		$res = $this->objIndexService->getMsgInfo();
		echo $res;
	}

	//获取邮件信息
    public function getEmailInfo()
    {

		$roleid = $_REQUEST['roleid'];
        if (!$roleid) {
        	$roleid=0;
		}
        $this->objIndexService = new IndexServiceBLL();
        $res = $this->objIndexService->getEmailInfo($roleid);
//		$res = ['code' => 0,'message' => '','data' => [["2019-3-5 12:36","1000","2019051545311353"], ["2019-3-5 12:36","1000","2019051545311353"]]];
//        $res = json_encode($res, JSON_UNESCAPED_UNICODE);
		echo $res;
	}

}
?>
