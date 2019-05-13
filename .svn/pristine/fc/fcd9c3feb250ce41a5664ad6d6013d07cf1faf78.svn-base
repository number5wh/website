<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/UserBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';

require_once ROOT_PATH . 'Link/GetRoleBaseInfo.php';
class HappyBeanAction extends PageBase {
	public function __construct() {
		$this->arrConfig = unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);
	}
	public function index() {
		$arrResult = $this->getPagerHappyBeanSort();
		$arrTags = array('skin' => $this->arrConfig['skin'], 'Page' => $arrResult['Page'], 'UserList' => $arrResult['UserList']);
		Utility::assign($this->smarty, $arrTags);

		$this->smarty->display($this->arrConfig['skin'] . '/YunYing/HappyBeanList.html');
	}

	/**
	 * 分页
	 */
	public function getPagerHappyBeanSort() {
		$strWhere = '';
		$arrUserList = null;
		$curPage = Utility::isNumeric('curPage', $_POST);
		$curPage = $curPage <= 0 ? 1 : $curPage;

		$arrParam['fields'] = 'RoleID,RoleName,[Money],GameMoney,TotalMoney';
		//$arrParam['tableName']='(SELECT TOP 1000 UB.RoleID,ISNULL([Money],0) AS [Money],ISNULL(GameMoney,0) AS GameMoney,ISNULL([Money],0)+ISNULL(GameMoney,0) AS TotalMoney FROM T_UserBank AS UB LEFT JOIN (SELECT RoleID,SUM([Money]) AS GameMoney FROM T_UserGameWealth GROUP BY RoleID) AS T1 ON UB.RoleID=T1.RoleID ORDER BY TotalMoney DESC) AS TT';
		//$arrParam['tableName']='(SELECT TOP 1000 ISNULL(UB.RoleID,T1.RoleID) AS RoleID,ISNULL(UB.RoleName,T1.RoleName) AS RoleName,ISNULL([BankMoney],0) AS [Money],ISNULL(GameMoney,0) AS GameMoney,ISNULL([BankMoney],0)+ISNULL(GameMoney,0) AS TotalMoney FROM T_BankMoneyRank AS UB FULL JOIN (SELECT TOP 1000 RoleID,min([RoleName]) AS RoleName,SUM([TotalMoney]) AS GameMoney FROM T_UserGameRank WITH(NOLOCK) WHERE [TotalMoney]>0 GROUP BY RoleID ORDER BY GameMoney DESC) AS T1 ON UB.RoleID=T1.RoleID ORDER BY TotalMoney DESC) AS TT';
		$arrParam['tableName'] = '(SELECT TOP 1000 RoleID,RoleName,ISNULL(BankMoney,0) as [Money],ISNULL(GameMoney,0) as GameMoney,(ISNULL(GameMoney,0)+ISNULL(BankMoney,0)) as TotalMoney FROM T_BankMoneyRank ORDER BY TotalMoney DESC ) AS TT';
		$arrParam['where'] = $strWhere;
		$arrParam['order'] = 'TotalMoney DESC';
		$arrParam['pagesize'] = 20;
		$arrParam['function'] = 'HappyBeanSort';
		$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['DataChangeLogs']);

		$iRecordsCount = $objCommonBLL->getRecordsCountSelect($arrParam);

		$Page = Utility::setPages($curPage, $iRecordsCount, $arrParam['pagesize']);
		if ($iRecordsCount > 0) {
			$arrUserList = $objCommonBLL->getPageListSelect($arrParam, $curPage);
		}
		//var_dump($arrUserList);
		if ($arrUserList) {
			$iCount = 0;
			//$objUserBLL = new UserBLL(0);
			foreach ($arrUserList as $val) {
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

                $arrUserList[$iCount]['Money'] =sprintf("%.2f", $val['Money']/1000);
                $arrUserList[$iCount]['GameMoney'] = sprintf("%.2f",$val['GameMoney']/1000);
                $arrUserList[$iCount]['TotalMoney'] = sprintf("%.2f",$val['TotalMoney']/1000);

				$arrUserList[$iCount]['LoginID'] = $val['RoleID'];
				$arrUserList[$iCount]['LoginName'] = Utility::gb2312ToUtf8($val['RoleName']);
				$arrUserList[$iCount]['RegIP'] = '';
				$arrUserList[$iCount]['LastLoginIP'] = '';

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
		$html = $this->smarty->fetch($this->arrConfig['skin'] . '/YunYing/HappyBeanListPage.html');
		$html = str_replace("</script>", "<\/script>", str_replace("\r\n", '', $html));
		echo $html;
	}
	/**
	 * 删除卡号
	 * $iResult: 0:成功,-1:失败
	 */
	public function delCard() {
		$iResult = -1;
		$CardID = Utility::isNumeric('CardID', $_POST);
		if ($CardID && $CardID > 0) {
			$arrResult = $this->objStagePropertyBLL->delCard($CardID);
			if ($arrResult && $arrResult['iResult'] == 0) {
				if ($arrResult['IsUsed'] == 0) {
					$objBankBLL = new BankBLL();
					$arrRes = $objBankBLL->setSysBankMoney($arrResult['CardType'], $arrResult['iMoney'], 2);
					$objDataChangeLogsBLL = new DataChangeLogsBLL();
					$objDataChangeLogsBLL->insertSysBankTransLogs($arrResult['CardType'], $arrRes['AccNo'], 0, $this->arrConfig['TransType']['TransType10'], $this->arrConfig['DCFlag']['DCFlag1'], $arrResult['iMoney'], $arrRes['LastBalance'], $arrRes['Balance'], '');
				}
				$html = $arrResult['iResult'];
			} else {
				$html = Utility::echoResultHtml($this->smarty, '取 消', 'main.CloseMsgBox(false,\'SysCard\')', '删除失败,请重试', 'false', 'SysCard', $this->arrConfig);
			}

		} else {
			$html = Utility::echoResultHtml($this->smarty, '取 消', 'main.CloseMsgBox(false,\'SysCard\')', '对不起,您提交的数据异常,请重试', 'false', 'SysCard', $this->arrConfig);
		}

		echo $html;
	}
	/**
	 * 显示添加卡号表单
	 */
	public function showAddCardHtml() {
		$arrCodeList = $this->objStagePropertyBLL->getRegularCodeList(1);
		$arrTags = array('RegularCodeList' => $arrCodeList);
		Utility::assign($this->smarty, $arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'] . '/YunYing/CardEdit.html');
		$html = str_replace("</script>", "<\/script>", str_replace("\r\n", '', $html));

		echo $html;
	}
	/**
	 * 添加卡号
	 */
	public function addCard() {
		$iResult = -1;
		$iResult1 = -1;
		$iSuccess = 0;
		$iFail = 0;
		$iCount = 0;
		$Prefix = Utility::isNullOrEmpty('Prefix', $_POST) ? $_POST['Prefix'] : '';
		$RegularCode = Utility::isNullOrEmpty('RegularCode', $_POST) ? $_POST['RegularCode'] : '';
		$SortNumber = Utility::isNumeric('SortNumber', $_POST);
		$iNumber = Utility::isNumeric('iNumber', $_POST);
		$iMoney = Utility::isNumeric('iMoney', $_POST);
		$CardType = Utility::isNumeric('CardType', $_POST);
		$Flag = Utility::isNumeric('Flag', $_POST);
		if ($iNumber && $iNumber > 0) {
			if ($CardType > 0 && $Flag) {
				$objBankBLL = new BankBLL();
				$iResult1 = $objBankBLL->updateSysBank($CardType, $iMoney * $iNumber);
			}
			if ($CardType > 0 && $iResult1 == 0 || $Flag == 0) {
				$iResult1 = 0;
				$pattern = '1234567890'; //'1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
				for ($i = 0; $i < $iNumber; $i++) {
					$CardNumber = '';
					$CardKey = '';
					$iZero = '';
					//for($k = 0; $k < 12; $k++)
					//	$CardNumber .= mt_rand(0, 9);
					if (strlen($SortNumber) < 6) {
						$iLen = 6 - strlen($SortNumber);
						for ($k = 0; $k < $iLen; $k++) {
							$iZero .= '0';
						}

					}
					$tmpSortNumber = $iZero . $SortNumber;
					$CardNumber = $Prefix . $RegularCode . $tmpSortNumber;
					for ($k = 0; $k < 8; $k++) {
						$CardKey .= mt_rand(0, 9);
					}

					$iResult = $this->objStagePropertyBLL->addCard($CardNumber, $CardKey, $iMoney, $CardType);
					if ($iResult == 0) {
						$iSuccess++;
					} else {
						$iFail++;
					}

					$iCount++;
					$SortNumber++;
					if ($iCount == 20) {
						break;
					}

				}
				if ($iFail > 0) {
					$objBankBLL = new BankBLL();
					$objBankBLL->setSysBankMoney($CardType, $iFail * $iMoney, 2);
				}

			} else {
				$iFail = $iNumber;
			}

		}

		echo json_encode(array('iSuccess' => $iSuccess, 'iFail' => $iFail, 'iCount' => $iCount, 'Prefix' => $Prefix, 'iNumber' => $iNumber - $iCount, 'iMoney' => $iMoney, 'CardType' => $CardType, 'RegularCode' => $RegularCode, 'SortNumber' => $SortNumber, 'iResult1' => $iResult1));
	}
	/**
	 * 锁定卡号
	 * $iResult: 0:成功,-1:失败
	 */
	public function lockCard() {
		$iResult = -1;
		$CardID = Utility::isNumeric('CardID', $_POST);
		if ($CardID && $CardID > 0) {
			$iResult = $this->objStagePropertyBLL->lockCard($CardID);
			if ($iResult == 0) {
				$html = $iResult;
			} else {
				$html = Utility::echoResultHtml($this->smarty, '取 消', 'main.CloseMsgBox(false,\'SysCard\')', '冻结失败,请重试', 'false', 'SysCard', $this->arrConfig);
			}

		} else {
			$html = Utility::echoResultHtml($this->smarty, '取 消', 'main.CloseMsgBox(false,\'SysCard\')', '对不起,您提交的数据异常,请重试', 'false', 'SysCard', $this->arrConfig);
		}

		echo $html;
	}
}
?>