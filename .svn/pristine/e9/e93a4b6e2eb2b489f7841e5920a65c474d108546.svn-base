<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/SystemBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/OperationLogsBLL.class.php';
require ROOT_PATH . 'Class/BLL/DataChangeLogsBLL.class.php';

class ServiceWealthAction extends PageBase
{
	private $strLoginedUser = '';
	
	public function __construct()
	{
		$this->arrConfig = unserialize(SYS_CONFIG);
		$this->strLoginedUser = Utility::chkUserLogin($this->arrConfig);
	}

	public function index()
	{
		$arrCaseStatus = $this->arrConfig['CaseStatus'];
		$arrTags=array('arrCaseStatus'=>$arrCaseStatus,
					   'strStatrTime'=>date('Y-m-d',strtotime("-7 day")),
					   'strEndTime'=>date('Y-m-d'));
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/Service/CaseWealthIndex.html');
	}
	
	
	/**
	 * 财富冻结处理--分页列表
	 */
	public function treasureLockedList()
	{
		$flag = false;
		//获取Post参数
		$startTime = isset($_POST['startTime']) ? $_POST['startTime'] : '';
		$endTime = isset($_POST['endTime']) ? $_POST['endTime'] : '';		
		$LoginID = isset($_POST['loginId']) ? $_POST['loginId'] : '';
		$CaseSerial = isset($_POST['caseSerial']) ? $_POST['caseSerial'] : '';
		$pattern = '/[\d]{4}-[\d]{1,2}-[\d]{1,2}/';
		
		//组装where条件
		if(($CaseSerial != '' && !is_numeric($CaseSerial)) || ($LoginID != '' && !is_numeric($LoginID)) ||
		   ($startTime != '' && !preg_match($pattern, $startTime)) || ($endTime != '' && !preg_match($pattern, $endTime)) ||
		   ($startTime > $endTime))
		{
			$strWhere = ' WHERE 2=1';
			$flag = true;
		}else{
			$strWhere = ' WHERE OperationType=1';
			if($startTime != ''){   		
				$strWhere .= " AND AddTime>='$startTime' ";
				$flag = true;
    		}
			if($endTime != ''){
				$endTime = date('Y-m-d',strtotime($endTime)+86400);
				$strWhere .= " AND AddTime<'$endTime' ";
				$flag = true;
    		}
			if($LoginID != ''){			
				$strWhere .= " AND LoginID=$LoginID ";
				$flag = true;
			}elseif ($CaseSerial != ''){
				$strWhere .= " AND FID IN (SELECT FID FROM T_CaseOperateUserSn WHERE CaseSerial=$CaseSerial) ";
				$flag = true;
			}
		}		

		if($flag){//是否是重新组合查询条件，如果是分页读取session中的where条件
			$_SESSION['getPageWealthLocked_Where'] = $strWhere;
		}
		
		//组装分页查询条件
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage <= 0 ? 1 : $curPage;
		$arrParam['fields'] = 'FID,RoleID,LoginID,LoginName,Reason,SysUserName,AddTime,iNumber,ReturnNumber';
		$arrParam['tableName'] = 'T_CaseOperateUser';
		$arrParam['where'] = isset($_SESSION['getPageWealthLocked_Where']) && !empty($_SESSION['getPageWealthLocked_Where']) ? $_SESSION['getPageWealthLocked_Where'] : $strWhere;
		$arrParam['order'] = 'AddTime desc';
		$arrParam['pagesize'] = 15;

		$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['System']);
		$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
		$arrResult = null;
		if($iRecordsCount > 0)
		{			
			$arrResult = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);
			
			if(is_array($arrResult) && count($arrResult)>0){
				$i=0;				
				foreach ($arrResult as $v){					
					$arrResult[$i]['LoginID'] = $v['LoginID'];
                    $arrResult[$i]['iNumber'] =Utility::FormatMoney($v['iNumber']);
                    $arrResult[$i]['ReturnNumber'] =Utility::FormatMoney($v['ReturnNumber']);
					$arrResult[$i]['LoginName'] = Utility::gb2312ToUtf8($v['LoginName']);
					$arrResult[$i]['CaseSerial'] = $this->getOperateUserCaseSerial($v['FID']);
					$arrResult[$i]['SysUserName'] = Utility::gb2312ToUtf8($v['SysUserName']);					
					$arrResult[$i]['Reason'] = Utility::gb2312ToUtf8($v['Reason']);														
					$arrResult[$i]['AddTime'] = empty($v['AddTime'])?'':date('Y-m-d H:i:s',strtotime($v['AddTime']));
					$i++;
				}
			}
		}		
		
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'TreasureLockedList'=>$arrResult);
		Utility::assign($this->smarty,$arrTags);		
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/CaseTreasureLockedList.html');
		$html = str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}

	/**
	 * 获取申请财富返还页面
	 */
	public function getApplicationPage()
	{
		$iFId = Utility::isNumeric('id', $_POST) ? $_POST['id'] : 0;
		$iNumber = Utility::isNumeric('num', $_POST) ? $_POST['num'] : 0;
		$loginId = Utility::isNumeric('loginId', $_POST) ? $_POST['loginId'] : 0;
		$loginName = Utility::isNullOrEmpty('loginName', $_POST) ? $_POST['loginName'] : '';
		if($iFId <= 0 || $iNumber <= 0 || $loginId <= 0 || empty($loginName)){
			echo -1;
			exit();
		}
        //$iNumber =Utility::FormatMoney($iNumber);
		$arrTags=array('skin'=>$this->arrConfig['skin'],
					   'fid'=>$iFId,
					   'iNumber'=>$iNumber,
					   'loginId'=>$loginId,
					   'loginName'=>$loginName);
		
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/CaseApplicationBackPage.html');
		echo $html;
	}
    /**退还金币页面
     *
     */
    public function getApplicationPageConst(){
        $iFId = Utility::isNumeric('id', $_POST) ? $_POST['id'] : 0;
        $iNumber = Utility::isNumeric('num', $_POST) ? $_POST['num'] : 0;
        $loginId = Utility::isNumeric('loginId', $_POST) ? $_POST['loginId'] : 0;
        $loginName = Utility::isNullOrEmpty('loginName', $_POST) ? $_POST['loginName'] : '';
        if($iFId <= 0 || $iNumber <= 0 || $loginId <= 0 || empty($loginName)){
            echo -1;
            exit();
        }
        //$iNumber =Utility::FormatMoney($iNumber);
        $arrTags=array('skin'=>$this->arrConfig['skin'],
            'fid'=>$iFId,
            'iNumber'=>$iNumber,
            'loginId'=>$loginId,
            'loginName'=>$loginName);

        Utility::assign($this->smarty,$arrTags);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/EditOperateFreezeTreasureBackPage.html');
        echo $html;
    }
	/**
	 * 申请财富返还
	 */
	public function applyWealthBack()
	{
		$iResult = 0;
		$iFId = Utility::isNumeric('fid', $_POST) ? $_POST['fid'] : 0;
		$iRoleID = Utility::isNumeric('roleId', $_POST) ? $_POST['roleId'] : 0;
		$iLoginId = Utility::isNumeric('loginId', $_POST) ? $_POST['loginId'] : 0;
		$strLoginName = Utility::isNullOrEmpty('loginName', $_POST) ? $_POST['loginName'] : '';
		$iNumber = Utility::isNumeric('number', $_POST) ? $_POST['number'] : 0;
		$strPayment = Utility::isNullOrEmpty('payment', $_POST) ? $_POST['payment'] : '';
		$strRemark = Utility::isNullOrEmpty('remark', $_POST) ? $_POST['remark'] : '';
		$CaseSerial = Utility::isNumeric('caseSerial',$_POST) ? $_POST['caseSerial'] : '';
		
		if(!empty($CaseSerial) && !$this->checkCaseSerial($CaseSerial))
		{
			echo -1;
			exit();
		}
		if($iFId > 0 && $iNumber>0 && $iRoleID>0 && $iLoginId>0)
		{
			$OpResult = -1;
            $iNumber = $iNumber*1000;
			$systemBLL = new SystemBLL();
			$arrResult = $systemBLL->addAuthProcess($iRoleID,$iLoginId,$strLoginName,6,0,$iNumber,$strPayment,$CaseSerial,$strRemark,'',2,$this->strLoginedUser,$iFId);
			if($arrResult['iResult'] == 0)
			{
				$iReturn = $systemBLL->updateCaseOperateUser($iFId,$iNumber,1);
				if($iReturn==0){
					$iResult = 1;
					$OpResult = 0;
				}
			}
			
			//记录审核操作日志			
			$OpContent = "玩家编号为".$iLoginId."的用户申请返回".$iNumber."金币";
			$operationLogsBLL = new OperationLogsBLL($iRoleID);
			$operationLogsBLL->addCaseOperationLogs($iRoleID, $CaseSerial, 3, $OpContent, $OpResult, Utility::getIP(), 0, 2, $this->strLoginedUser, '');
			
			//财富冻结日志
			$objDataChangeLogsBLL = new DataChangeLogsBLL($iRoleID);
			$objDataChangeLogsBLL->insertLockMoneyLogs($iRoleID, $CaseSerial, $iNumber, 0, 1, $strLoginName, $this->strLoginedUser, $OpContent);
		}
		
		echo $iResult;
	}
	
	/**
	 * 获取申请财富返还记录
	 */
	public function getApplyWealthBackList()
	{
		$iFId = Utility::isNumeric('fid', $_POST) ? $_POST['fid'] : 0;
		if($iFId <= 0){
			echo -1;
			exit();
		}
		$systemBLL = new SystemBLL();
		$arrResult = $systemBLL->getCaseAuthProcess($iFId, 0, 1);

        if(is_array($arrResult) && count($arrResult)>0){
            $i=0;
            foreach ($arrResult as $v){
                $arrResult[$i]['iNumber'] =Utility::FormatMoney($v['iNumber']);
            }
        }
        $arrTags=array('skin'=>$this->arrConfig['skin'],
					   'ApplyWealthBackList'=>$arrResult);
		
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/ApplyTreasureBackList.html');
		echo $html;
	}
	
	/**
	 * 获取财富冻结记录相关案件编号
	 * @param $iFId
	 */
	public function getOperateUserCaseSerial($iFId)
	{
		$result = '';
		$systemBLL = new SystemBLL();
		$arrReturn = $systemBLL->getCaseOperateUserSn($iFId);
		if(is_array($arrReturn) && count($arrReturn)>0)
		{
			foreach ($arrReturn as $v)
			{
				$CaseSerial = $v['CaseSerial'];
				$result .= "<a class='blue' href='javascript:getCaseDetailPage($CaseSerial)'>$CaseSerial</a>";
				$result .= "&nbsp;&nbsp;";
			}
		}
		
		return $result;
	}
	
	/**
	 * 判断案件编号存不存在
	 * @param $iCaseSerial
	 */
	private function checkCaseSerial($iCaseSerial)
	{
		$systemBLL = new SystemBLL();
		$arrResult = $systemBLL->getCaseInfoByID($iCaseSerial);
		if(is_array($arrResult) && count($arrResult)>0)
		{
			return true;
		}else{
			return false;
		}
	}
}