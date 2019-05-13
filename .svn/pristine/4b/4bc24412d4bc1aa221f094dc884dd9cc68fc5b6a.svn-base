<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/12/24
 * Time: 17:50
 */
require ROOT_PATH . 'Common/PHPExecl/PHPExcel.php';
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Common/Zip.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/PayLogsBLL.class.php';

class RealCardAction extends PageBase {
	public function __construct() {
		$this->arrConfig = unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);
	}

	public function index() {

		$arrTags = array("RealCardStatus" => $this->arrConfig['RealCardStatus']);
		Utility::assign($this->smarty, $arrTags);
		$this->smarty->display($this->arrConfig['skin'] . '/YunWei/RealCardEdit.html');
	}

	/**
	 * @param int $pagesize
	 */
	private function getRealCardList($curPage, $state = null, $pagesize = 10) {

		$arrParams['fields'] = ' CardNo,CardPass,Money,State,RoleID,CreateTime,RechargeTime,UpdateTime ';
		$arrParams['tableName'] = ' T_RechargeCard';
		$arrParams['where'] = ' Where 1 = 1 ';
		$arrParams['order'] = ' CreateTime desc';
		$arrParams['pagesize'] = $pagesize;
		$objCommonBLL = new CommonBLL(0);

		$arrStatus = $this->arrConfig['RealCardStatus'];

		if ($state !== null && is_numeric($state)) {
			$arrParams['where'] .= " AND state = $state ";
		}
		//var_dump($arrParams);
		$arrStatusMap = Utility::array_column($arrStatus, 'name', 'value');
		$list = array();
		$iRecordsCount = $objCommonBLL->getRecordsCount($arrParams);
		if ($iRecordsCount > 0) {
			$list = $objCommonBLL->getPageList($arrParams, $curPage);

			foreach ($list as &$val) {
				if (array_key_exists($val['State'], $arrStatusMap)) {
					$val['StateTips'] = $arrStatusMap[$val['State']];
				} else {
					$val['StateTips'] = '';
				}
			}
			unset($val);
		}
		$Page = Utility::setPages($curPage, $iRecordsCount, $pagesize);

		//$list = array(array("CardID"=>1));
		return array("arrRealCardList" => $list, "page" => $Page);
	}
	/**
	 *
	 */
	public function getRealCardListPager() {
		//var_dump($_POST);
		$curPage = Utility::isNullOrEmpty('curPage', $_REQUEST) ? $_REQUEST['curPage'] : 1;
		$state = is_numeric($_REQUEST['state']) ? $_REQUEST['state'] : null;
		//var_dump($_REQUEST);
		//var_dump($state);
		$pagesize = 20;
		$ret = $this->getRealCardList($curPage, $state, $pagesize);

		$arrTag = array("RealCardList" => $ret['arrRealCardList'], "Page" => $ret['page'], "skin" => $this->arrConfig['skin']);
		Utility::assign($this->smarty, $arrTag);
		$html = $this->smarty->fetch($this->arrConfig['skin'] . '/YunWei/RealCardList.html');
		$html = str_replace("</script>", "<\/script>", str_replace("\r\n", '', $html));
		echo $html;
	}

	/**批量销毁 --只能销毁已发布的
		     *
	*/
	public function destroy() {
		$cardNo = $_POST['selected'];

		$state = 3; //销毁状态
		$objMasterBLL = new MasterBLL();

		if (!empty($cardNo) && is_array($cardNo)) {
			$where = ' where CardNo in (' . $this->strImplode(',', $cardNo) . ')';
			$ret = $objMasterBLL->updateRechargeCardState($state, $where, 1);
		}

		$arrTags = array("status" => 0);

		if (isset($ret['iResult'])) {
			if ($ret['iResult'] == 0) {
				$arrTags['status'] = 1;
			}
		}

		echo json_encode($arrTags);
	}

	public function destroyRealCard() {
		$cardNo = Utility::isNullOrEmpty('CardNo', $_POST);
		//var_dump($cardNo);
		$ret = $this->destroyArr(array($cardNo));

		if (isset($ret['iResult'])) {
			if ($ret['iResult'] == 0) {
				$arrTags['status'] = 1;
			}
		}

		echo json_encode($arrTags);
	}
	private function destroyArr($cardNo) {
		$state = 3; //销毁状态
		$objMasterBLL = new MasterBLL();
		$ret = null;
		if (!empty($cardNo) && is_array($cardNo)) {
			$where = ' where CardNo in (' . $this->strImplode(',', $cardNo) . ')';
			var_dump($where);
			$ret = $objMasterBLL->updateRechargeCardState($state, $where, 1);
		}
		return $ret;
	}
	/**删除
		     *
	*/
	public function delete() {
		$cardNo = $_POST['selected'];

		$objMasterBLL = new MasterBLL();

		if (!empty($cardNo) && is_array($cardNo)) {
			$where = ' where CardNo in (' . $this->strImplode(',', $cardNo) . ')';
			$ret = $objMasterBLL->deleteRechargeCard($where, 1);
		}

		$arrTags = array("status" => 0);

		if (isset($ret['iResult'])) {
			if ($ret['iResult'] == 0) {
				$arrTags['status'] = 1;
			}
		}
		echo json_encode($arrTags);

	}
	public function deleteRealCard() {
		$cardNo = Utility::isNullOrEmpty('CardNo', $_POST);
		//var_dump($cardNo);
		$ret = $this->deleteArr(array($cardNo));

		$arrTags = array("status" => 0);
		if (isset($ret['iResult'])) {
			if ($ret['iResult'] == 0) {
				$arrTags['status'] = 1;
			}
		}

		echo json_encode($arrTags);
	}
	private function deleteArr($cardNo) {
		$objMasterBLL = new MasterBLL();

		$ret = null;
		if (!empty($cardNo) && is_array($cardNo)) {
			$where = ' where CardNo in (' . $this->strImplode(',', $cardNo) . ')';
			$ret = $objMasterBLL->deleteRechargeCard($where, 1);
		}
		return $ret;
	}

	private function strImplode($splice, $arr) {
		$str = '';
		foreach ($arr as $val) {
			if (is_string($val)) {
				$str .= "'" . $val . "'" . $splice;
			}
		}
		if (strlen($str)) {
			$str = substr($str, 0, strlen($str) - strlen($splice));
		}
		return $str;
	}
	/**
	 *
	 */
	public function addRealCard() {
		$money = Utility::isNumeric('money', $_REQUEST);
		$num = Utility::isNumeric('num', $_REQUEST);

		$objMasterBLL = new MasterBLL();

		$ret = $objMasterBLL->createRechargeCard($money, $num);
		$arrTags = array("status" => 0);
		if (isset($ret['iResult'])) {
			if ($ret['iResult'] == 0) {
				$arrTags['status'] = 1;
			}
		}
		echo json_encode($arrTags);
	}

	public function download() {

		$curPage = 1;
		$arr = array();
		$objMasterBLL = new MasterBLL();
		$arrTags = array("status" => 0, "url" => '');
			$result = $this->getRealCardList($curPage, 1, 50000);
			if ($result['arrRealCardList']) {
				//$page = $result['page'];
				//$totalPage = $page['TotalPage'];
				//$arr = array_merge($arr, $result['arrRealCardList']);
				$arr = $result['arrRealCardList'];
				$url=$this->createTxt($arr);
				//修改状态。
				$cardNo = Utility::array_column($result['arrRealCardList'], 'CardNo');
				//$where = ' WHERE CardNo IN (' . $this->strImplode(',', $cardNo) . ') and State=1';
				//var_dump($where);

                //$ret = $objMasterBLL->updateRechargeCardState(2, $where, 1);

				// if ($ret['iResult'] != 0) {
				// 	break;
				// }
				//var_dump($arr);
				// if ($totalPage <= $curPage && $curPage <= 30) {
				// 	break;
				// }
				//$curPage++;
			}
		//}
		// print_r($arr);
		// exit();
		//$url = $this->createTxt($arr);

		if ($url) {
			$arrTags['status'] = 1;
			//$arrTags['url'] = $url;
			$arrTags['url'] = "complete";
		}
		echo json_encode($arrTags);
	}
	public function create() {
		$arr = array(array());
		$ret = $this->createExcelFile($arr);
	}

	/**
	 * @param $arr
	 */
	public function createTxt(&$arr) {
		$FileName = date('YmdHis') . rand(100, 999) . '.txt';
		$FilePath = 'Files/RealCard/' . $FileName;
		$fp = fopen(ROOT_PATH . $FilePath, 'w');
		fwrite($fp, "卡号\t密码\t金额\r\n");
		foreach ($arr as $key => $val) {
			fwrite($fp, $val['CardNo'] . ' ' . $val['CardPass'] . ' ' . $val['Money'] . "\r\n");
		}

		fclose($fp);

		return '/' . $FilePath;
	}
	/**
	 *
	 */
	public function createExcelFile(&$arr) {

		$objPHPXls = new PHPExcel();

		$objPHPExcel = $objPHPXls;

		//创建新的sheet

		//设置文档属性
		$objPHPExcel->getProperties()->setCreator("xlj") //作者
			->setLastModifiedBy("xlj") //最后一次修改人
			->setTitle("实卡记录") //标题
			->setSubject("实卡记录") //主题
			->setDescription("实卡记录") //备注信息
			->setKeywords("实卡记录") //标记
			->setCategory("实卡记录"); //类

		$sheet = 0;
		//写标题
		$objPHPExcel->setActiveSheetIndex($sheet)
			->setCellValue('A1', '卡号')
			->setCellValue('B1', '密码')
			->setCellValue('C1', '金额');
		$iCount = 2;
		//从第二行开始循环写记录
		foreach ($arr as $val) {
			$objPHPExcel->setActiveSheetIndex($sheet)
				->setCellValue('A' . $iCount, $val['CardNo'])
				->setCellValue('B' . $iCount, $val['CardPass'])
				->setCellValue('C' . $iCount, $val['Money']);
			$iCount++;
		}
		$FileName = date('YmdHis') . rand(100, 999) . '.xls';
		$FilePath = 'Files/RealCard/' . $FileName;

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save(ROOT_PATH . $FilePath);
		$Http = '/' . $FilePath;

		return $Http;
	}
	/**
	 * 发布卡
	 * */
	public function publish() {

		$curPage = 1;
		$arr = array();
		$objMasterBLL = new MasterBLL();
		$arrTags = array("status" => 0, "url" => '');

		while (1) {

			$result = $this->getRealCardList($curPage, 0, 50);
			if (!$result['arrRealCardList']) {
				break;
			}
			$page = $result['page'];
			$totalPage = $page['TotalPage'];
			$arr = array_merge($arr, $result['arrRealCardList']);

			//修改状态。
			$cardNo = Utility::array_column($result['arrRealCardList'], 'CardNo');
			$where = ' WHERE CardNo IN (' . $this->strImplode(',', $cardNo) . ')';
			//var_dump($where);
			$ret = $objMasterBLL->updateRechargeCardState(1, $where, 1);

			if ($ret['iResult'] != 0) {
				break;
			}
			//var_dump($arr);
			if ($totalPage <= $curPage && $curPage <= 10) {
				break;
			}
			//$curPage++;
		}
		$arrTags['status'] = 1;
		$objPayBLL = new PayLogsBLL(0);
		foreach ($arr as $key => $val) {
			$ret = $objPayBLL->addTestCard($val['CardNo'], $val['CardPass']);
			/* if($ret['iResult']!=0)
                $arrTags['status'] = 0; */
		}
		echo json_encode($arrTags);
	}
}