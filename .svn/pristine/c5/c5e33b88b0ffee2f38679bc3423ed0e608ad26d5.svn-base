<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/SystemBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserDataBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/BankBLL.class.php';
require ROOT_PATH . 'Class/BLL/StagePropertyBLL.class.php';
require ROOT_PATH . 'Class/BLL/OperationLogsBLL.class.php';
require ROOT_PATH . 'Class/BLL/DataChangeLogsBLL.class.php';


require_once ROOT_PATH . 'Link/UnLockRole.php';
require_once ROOT_PATH . 'Link/BuyRoleVip.php';
require_once ROOT_PATH . 'Link/AddRoleMonery.php';
require_once ROOT_PATH . 'Link/UnBlockRole.php';
require_once ROOT_PATH . 'Link/UnBindRoleMachne.php';
require_once ROOT_PATH . 'Link/OMUnBindWeChat.php';
require_once ROOT_PATH . 'Link/GetRoleBaseInfo.php';
require_once ROOT_PATH . 'Link/LockRoleVip.php';

class ServiceAuthAction extends PageBase
{
	private $strLoginedUser = '';
	private $AccType4 = 0;
	private $AccType6 = 0;
	public function __construct()
	{
		$this->arrConfig = unserialize(SYS_CONFIG);
		$this->strLoginedUser = Utility::chkUserLogin($this->arrConfig);
		$this->AccType4 = 4;//系统银行推广户
		$this->AccType6 = 6;//系统银行冻结户
	}
	
	/**
	 * 角色审核授权首页
	 */
	function index()
	{
		$arrOperateVerifyType = $this->arrConfig['OperateVerifyType'];
		$arrAuthVerifyType = $this->arrConfig['AuthVerifyType'];
		$arrTags=array('strStatrTime'=>date('Y-m-d',strtotime("-7 day")),
					   'strEndTime'=>date('Y-m-d'),
					   'OperateVerifyType'=>$arrOperateVerifyType,
					   'AuthVerifyType'=>$arrAuthVerifyType);
		Utility::assign($this->smarty,$arrTags);		
		$this->smarty->display($this->arrConfig['skin'].'/Service/AuthIndex.html');
	}
	
	/**
	 * 操作审核列表
	 */
	function operateVerifyList()
	{
		$flag = false;
		//获取Post参数
		$startTime = isset($_POST['startTime'])?$_POST['startTime']:'';
		$endTime = isset($_POST['endTime'])?$_POST['endTime']:'';
		$iStatus = isset($_POST['status'])?$_POST['status']:'';
		$arrType = isset($_POST['arrType'])?$_POST['arrType']:'';
		$LoginID = isset($_POST['loginId'])?$_POST['loginId']:'';
		$LoginName = isset($_POST['loginName'])?$_POST['loginName']:'';
		$pattern = '/[\d]{4}-[\d]{1,2}-[\d]{1,2}/';
				
		//组装where条件
		if(($LoginID != '' && !is_numeric($LoginID)) || ($startTime != '' && !preg_match($pattern, $startTime)) ||
		   ($endTime != '' && !preg_match($pattern, $endTime)) || ($startTime > $endTime))
		{
			$strWhere = ' WHERE 2=1';
			$flag = true;
		}else{
			$strWhere = ' WHERE ShowType=1';
			$arrType = $arrType != '' ? explode(',',$arrType) : null;
			if(is_array($arrType) && count($arrType)>0){
				$arrType = implode(",",$arrType);
				$strWhere .= " AND OperationType in ($arrType)";				
				$flag = true;
			}
			if($iStatus != '' && ($iStatus == 0 || $iStatus == 1 || $iStatus == 2)){
				$strWhere .= " AND Status=$iStatus ";
				$flag = true;
			}
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
			}elseif ($LoginName != ''){
				$LoginName = Utility::utf8ToGb2312($LoginName);
				$strWhere .= " AND LoginName='$LoginName' ";
				$flag = true;
			}
		}
		
		if($flag){//是否是重新组合查询条件，如果是分页读取session中的where条件
			$_SESSION['getPageOperation_Where'] = $strWhere;
		}
		
		//组装分页查询条件
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage <= 0 ? 1 : $curPage;
		$arrParam['fields'] = 'PID,RoleID,LoginID,LoginName,OperationType,Reason,Remarks,Requirement,SysUserName,AddTime,Status,Checker,CheckTime,CheckRemarks';
		$arrParam['tableName'] = 'T_AuthProcess';
		$arrParam['where'] = isset($_SESSION['getPageOperation_Where']) && !empty($_SESSION['getPageOperation_Where']) ? $_SESSION['getPageOperation_Where'] : $strWhere;
		$arrParam['order'] = 'AddTime desc';
        $arrParam['page'] = $curPage;
		$arrParam['pagesize'] = 15;

		$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['System']);
		$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
		$arrResult = null;
		if($iRecordsCount > 0)
		{
			$arrResult = $objCommonBLL->getPageList($arrParam,$curPage);
			
			if(is_array($arrResult) && count($arrResult)>0){
				$i=0;
				foreach ($arrResult as $v){					
					$arrResult[$i]['LoginID'] = $v['LoginID'];
					$arrResult[$i]['LoginName'] = Utility::gb2312ToUtf8($v['LoginName']);					
					$arrResult[$i]['SysUserName'] = Utility::gb2312ToUtf8($v['SysUserName']);
					$arrResult[$i]['Checker'] = Utility::gb2312ToUtf8($v['Checker']);
					$arrResult[$i]['CheckRemarks'] = Utility::gb2312ToUtf8($v['CheckRemarks']);
					$arrResult[$i]['Reason'] = Utility::gb2312ToUtf8($v['Reason']);					
					$arrResult[$i]['Remarks'] = str_replace("\n",'<br>',Utility::gb2312ToUtf8($v['Remarks']));
					$arrResult[$i]['Requirement'] = Utility::gb2312ToUtf8($v['Requirement']);
					$arrResult[$i]['OperationType'] = $v['OperationType'];
					$arrResult[$i]['OperationTypeName'] = $this->arrConfig['OperateVerifyType'][$v['OperationType']];
					$arrResult[$i]['AddTime'] = empty($v['AddTime'])?'':date('Y-m-d H:i:s',strtotime($v['AddTime']));
					$arrResult[$i]['CheckTime'] = empty($v['CheckTime'])?'':date('Y-m-d H:i:s',strtotime($v['CheckTime']));
					$i++;
				}
			}
		}
		
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'OperateVerifyList'=>$arrResult);
		Utility::assign($this->smarty,$arrTags);		
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/AuthOperateVerifyList.html');
		$html = str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	
	/**
	 * 获取操作审核页面
	 */
	function getOperateVerifyPage()
	{
		$iPId = Utility::isNumeric('id', $_POST) ? $_POST['id'] : 0;
		$iRoleId = Utility::isNumeric('roleId', $_POST) ? $_POST['roleId'] : 0;
		$iType = Utility::isNumeric('type', $_POST) ? $_POST['type'] : 0;
		$iExtId = Utility::isNumeric('extId', $_POST) ? $_POST['extId'] : 0;
		$iNumber = Utility::isNumeric('num', $_POST) ? $_POST['num'] : 0;
		$iFId = Utility::isNumeric('fid', $_POST) ? $_POST['fid'] : 0;
		if($iPId <= 0|| $iRoleId<=0||$iType<=0){
			echo -1;
			exit();
		}
		$arrTags = array('skin'=>$this->arrConfig['skin'],
					     'id'=>$iPId,
					     'roleId'=>$iRoleId,
					     'type'=>$iType,
					     'number'=>$iNumber,
					     'extId'=>$iExtId,
					     'fid'=>$iFId,
						 'ClassName'=>'ServiceAuth');
		
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/AuthoperateDetail.html');
		echo $html;
	} 
	
	/**
	 * 操作审核-批准或拒绝
	 */
	function doOperateVerify()
	{
		$iResult = 0;
		$iPId = Utility::isNumeric('id', $_POST) ? $_POST['id'] : 0;
		$iStatus = Utility::isNumeric('status', $_POST) ? $_POST['status'] : 0;//1同意 2拒绝
		$iRoleId = Utility::isNumeric('roleId', $_POST) ? $_POST['roleId'] : 0;
		$iType = Utility::isNumeric('type', $_POST) ? $_POST['type'] : 0;
		$strRemark = isset($_POST['content']) ? $_POST['content'] : '';
		$iNumber = Utility::isNumeric('num', $_POST) ? $_POST['num'] : 0;
		$iExtId = Utility::isNumeric('extId', $_POST) ? $_POST['extId'] : 0;
		$iFId = Utility::isNumeric('fid', $_POST) ? $_POST['fid'] : 0;
		//echo $iType;exit;
		$strLoginName = Utility::isNullOrEmpty('LoginName', $_POST) ? $_POST['LoginName'] : '';
		$LoginID = Utility::isNumeric('fid', $_POST);
		if($strRemark =='' || mb_strlen($strRemark,'gb2312') > 200)
		{
			echo -1;
			exit();
		}
		if($iPId > 0 && $iStatus>0 && $iRoleId>0 && $iType>0)
		{
			$OpResult = -1;
			$Balance = 0;
			$systemBLL = new SystemBLL();
			//$objDataChangeLogsBLL = new DataChangeLogsBLL($iRoleId);
			$iCount = $systemBLL->OperateVerify($iPId, $iStatus, $strRemark, $this->strLoginedUser);
            if($iCount == 0)
			{
				$iReturn = 0;				
				if($iStatus==1)
				{
					//审批同意后执行操作,审批操作执行失败是否要回滚？？？？？
					$Result = $this->doOperate($iRoleId, $iType, $iExtId, $iNumber,$LoginID);
					$arrResult = unserialize($Result);
					if(is_array($arrResult))
					{
						$iReturn = $arrResult['iResult'];
						if($iReturn == 1002){  //银行余额不足
						    $iResult = 1002;
						}
						$Balance = $arrResult['Money'];
					}
					//记录银行交易日志
					/*if($iReturn==0)
					{						
						$iTimes = 0;						
						$iFlag = $this->arrConfig['DCFlag']['DCFlag1'];//借入
						if($iType==6)
						{
							$iTransType = $this->arrConfig['BankTransType'][5];//系统银行返还
							$Note = '从冻结户返还玩家'.$iNumber.'金币';
							$objDataChangeLogsBLL->writeBankTransLog($iRoleId,$strLoginName,$iTransType,$this->AccType6,$this->arrConfig['BankAccType'][$this->AccType6],$iFlag,$iNumber,$Balance,$LoginID,$Note);
							
							//财富冻结日志
							$OpContent = "审批同意返还给给玩家(".$strLoginName.")".$iNumber."金币";
							$objDataChangeLogsBLL->insertLockMoneyLogs($iRoleId, 0, $iNumber, 0, 2, $strLoginName, $this->strLoginedUser, $OpContent);
						}
						elseif($iType==7)
						{
							$iTransType = $this->arrConfig['BankTransType'][6];//财富补偿
							$Note = '从系统推广户补发玩家'.$iNumber.'金币';
							$objDataChangeLogsBLL->writeBankTransLog($iRoleId,$strLoginName,$iTransType,$this->AccType4,$this->arrConfig['BankAccType'][$this->AccType4],$iFlag,$iNumber,$Balance,$LoginID,$Note);
						}
						elseif($iType==8)
						{
							$iTransType = $this->arrConfig['BankTransType'][6];//财富补偿
							$Note = '从系统推广户补发玩家'.$iNumber.'龙币';
							$objSessioin = new Session($this->arrConfig['Session']['SessionLoginName']);
							$SysUserName = $objSessioin->get($this->arrConfig['SessionInfo']['UserName']);
							$objDataChangeLogsBLL->insertBankFwMoneyTransLogs($iRoleId,$iNumber,$Balance,$iFlag,$iTransType,$Note,$SysUserName);
						}
					}*/
				}elseif($iType == 6){//拒绝返回财富，数量重新返回到申请财富返回表中
					$iReturn = $systemBLL->updateCaseOperateUser($iFId, $iNumber, 2);
					//财富冻结日志							
							$OpContent = "审批拒绝返还给玩家(".$strLoginName.")".$iNumber."金币";
							//$objDataChangeLogsBLL->insertLockMoneyLogs($iRoleId, 0, $iNumber, 0, 3, $strLoginName, $this->strLoginedUser, $OpContent);
				}
				if($iReturn==0){
					$iResult = 1;
					$OpResult = 0;					
				}else{
					//回滚审核
					$systemBLL->OperateVerify($iPId, 0, '', '');
					$OpResult = $iReturn;
				}
			}
			
			//记录审核操作日志			
			$operateName = isset($this->arrConfig['OperateVerifyType'][$iType])?$this->arrConfig['OperateVerifyType'][$iType]:$this->arrConfig['AuthVerifyType'][$iType];			
			$lType = $iType;
			if($iType==4){
				$status = 2;
				$lType = 1;
			}elseif($iType==3){
				$lType = 5;
			}elseif($iType==5){
				$status = 5;
				$lType = 21;
			}elseif($iType==11){
				$lType = 4;
			}
			if($iStatus==1){
				$OpContent = $operateName.'审核批准';
			}else{
				$OpContent = $operateName.'审核拒绝';
				$status = 6;
			}
			
			$operationLogsBLL = new OperationLogsBLL($iRoleId);
			$operationLogsBLL->addCaseOperationLogs($iRoleId, 0, $lType, $OpContent, $OpResult, Utility::getIP(), 0, 2, $this->strLoginedUser, '');
			if($iType==4 || $iType==5){
				$operationLogsBLL->addLockUserLogs(0, $strRemark, $status, '', '', $this->strLoginedUser, $OpContent, Utility::getIP());
			}
		}

		echo $iResult;
	}
	
	/**
	 * 审批同意后执行操作
	 * @author blj
	 * @param $iRoleId
	 * @param $iType
     * @return $string  'iResult' 1操作成功 0 操作失败
	 */
	private function doOperate($iRoleID, $iType, $iExtId, $iNumber,$LoginID)
	{
		$Money = 0;
		switch ($iType)
		{
			case 1:	//重置银行密码
				$userDataBLL = new UserDataBLL($iRoleID);
				$strPwd = Utility::getRandomNumeral(6);
				$arrReturn = $userDataBLL->updateBankPwd($strPwd);
				$iResult = isset($arrReturn['iResult']) ? $arrReturn['iResult'] : -1;
				break;
			case 2:	//重置背包密码
				$userDataBLL = new UserDataBLL($iRoleID);
				$strPwd = Utility::getRandomNumeral(6);
				$arrReturn = $userDataBLL->updateMyKnapsackPwd($strPwd);
				$iResult = isset($arrReturn['iResult']) ? $arrReturn['iResult'] : -1;
				break;
				break;
			case 3:		//主机解绑
				$arrReturn = ASUnBindRoleMachne($iRoleID);
				$iResult = isset($arrReturn['iResult']) ? $arrReturn['iResult'] : -1;
				break;
			case 4:		//解除锁定
				/*$userDataBLL = new UserDataBLL($iRoleID);
				$arrReturn = $userDataBLL->editUserDataLocked(0);//用户数据解锁

				if(is_array($arrReturn) && count($arrReturn)>0){
					$userBLL = new UserBLL($iRoleID);
					$arrReturn = $userBLL->LockUserLockedStatus(0,0);//角色解锁
					$iResult = isset($arrReturn['iResult']) ? $arrReturn['iResult'] : -1;
				}else{
					$iResult = -1;
				}*/
                $arrResult = ASUnLockRole($iRoleID);
                $iResult = isset($arrResult['iResult'])?$arrResult['iResult']:-1;
				break;
			case 5:		//解除处罚
				/*$userBLL = new UserBLL($iRoleID);
				$arrReturn = $userBLL->blockUserLockedStatus(0,'');*/
                $arrReturn = ASUnBlockRole($iRoleID);
				$iResult = isset($arrReturn['iResult']) ? $arrReturn['iResult'] : -1;
				break;
			case 6:		//返回金币
				$iResult = -1;
				$Money = 0;
                $arrResult = DSAddRoleMonery($iRoleID,$iNumber);

		        if(is_array($arrResult) ){
                    if($arrResult['iResult'] === 0)
                        $iResult = 0;
                    else if($arrResult['iResult'] == 1002)
                        $iResult = 1002;
                    //记录日志
                }
				/*$bankBLL = new BankBLL();
				$arrReturn = $bankBLL->updateSysBankMoney($this->AccType6, $iNumber, 0, 1);//从冻结户扣金币
				if(is_array($arrReturn) && count($arrReturn)>0 && $arrReturn['iResult']==0)
				{
					$TransType = $this->arrConfig['TransType']['TransType10'];
					$DCFlag = $this->arrConfig['DCFlag']['DCFlag2'];
					//$objDataChangeLogsBLL = new DataChangeLogsBLL($iRoleID);
					//$objDataChangeLogsBLL->insertSysBankTransLogs($this->AccType6, $iRoleID, 0, $TransType, $DCFlag, $iNumber, $arrReturn['LastBalance'],$arrReturn['Balance'], '');

					if($arrReturn['iResult']==0)
					{
						//$userDataBLL = new UserDataBLL($iRoleID);
						//$arrReturn = $userDataBLL->editUserBankMoney($iNumber,0,1);

						$iResult = isset($arrReturn['iResult']) ? $arrReturn['iResult'] : -1;
						//$objDataChangeLogsBLL->writeBankTransLog($iRoleID,'',6,$this->AccType6,'系统银行冻结户',1,$iNumber,$arrReturn['Money'],$LoginID,'返还金币');
					}
				}*/
					
					
				/*$userDataBLL = new UserDataBLL($iRoleID);
				$arrReturn = $userDataBLL->editUserBankMoney($iNumber,0,1);
				if(is_array($arrReturn) && count($arrReturn)>0 && $arrReturn['iResult'] == 0){	
					$Money = $arrReturn['Money'];	
					$bankBLL = new BankBLL();
					$arrReturn = $bankBLL->updateSysBankMoney($this->AccType6, $iNumber, 0, 1);//从冻结户扣金币
					if(is_array($arrReturn) && count($arrReturn)>0)
					{
						$iResult = isset($arrReturn['iResult']) ? $arrReturn['iResult'] : -1;
						if($iResult==0 || $iResult==-1)//成功或失败
						{							
							$TransType = $this->arrConfig['TransType']['TransType10'];
							$DCFlag = $this->arrConfig['DCFlag']['DCFlag2'];
							$objDataChangeLogsBLL = new DataChangeLogsBLL($iRoleID);
							$objDataChangeLogsBLL->insertSysBankTransLogs($this->AccType6, $iRoleID, 0, $TransType, $DCFlag, $iNumber, $arrReturn['LastBalance'],$arrReturn['Balance'], '');
						}
					}
				}*/
				break;
			case 7:		//补发金币
				$iResult = -2;
                $arrResult = DSAddRoleMonery($iRoleID,$iNumber);
                
                if(is_array($arrResult) ){
                    if($arrResult['iResult'] === 0)
                        $iResult = 0;
                    else if($arrResult['iResult'] == 1002)
                        $iResult = 1002;
                    //记录日志
                }
				/*$bankBLL = new BankBLL();
				$arrReturn = $bankBLL->updateSysBankMoney($this->AccType4, $iNumber, 0, 1);//从推广户扣金币				
							
				if(is_array($arrReturn) && count($arrReturn)>0 && $arrReturn['iResult']==0)
				{
					$TransType = $this->arrConfig['TransType']['TransType9'];
					$DCFlag = $this->arrConfig['DCFlag']['DCFlag2'];
					$objDataChangeLogsBLL = new DataChangeLogsBLL($iRoleID);
					$objDataChangeLogsBLL->insertSysBankTransLogs($this->AccType4, $iRoleID, 0, $TransType, $DCFlag, $iNumber, $arrReturn['LastBalance'],$arrReturn['Balance'], '');
				
					if($arrReturn['iResult']==0)
					{
						$userDataBLL = new UserDataBLL($iRoleID);
						$arrReturn = $userDataBLL->editUserBankMoney($iNumber,0,1);	
						$iResult = isset($arrReturn['iResult']) ? $arrReturn['iResult'] : -3;										
						$objDataChangeLogsBLL->writeBankTransLog($iRoleID,'',6,$this->AccType4,'系统银行推广户',1,$iNumber,$arrReturn['Money'],$LoginID,'补发金币');
					}
				}*/
				/*
				
				$userDataBLL = new UserDataBLL($iRoleID);
				$arrReturn = $userDataBLL->editUserBankMoney($iNumber,0,1);				
				if(is_array($arrReturn) && count($arrReturn)>0 && $arrReturn['iResult'] == 0){	
					$Money = $arrReturn['Money'];	
					$bankBLL = new BankBLL();
					$arrReturn = $bankBLL->updateSysBankMoney($this->AccType4, $iNumber, 0, 1);//从推广户扣金币
					if(is_array($arrReturn) && count($arrReturn)>0)
					{
						$iResult = isset($arrReturn['iResult']) ? $arrReturn['iResult'] : -3;
						if($iResult==0 || $iResult==-1)//成功或失败
						{							
							$TransType = $this->arrConfig['TransType']['TransType9'];
							$DCFlag = $this->arrConfig['DCFlag']['DCFlag2'];
							$objDataChangeLogsBLL = new DataChangeLogsBLL($iRoleID);
							$objDataChangeLogsBLL->insertSysBankTransLogs($this->AccType4, $iRoleID, 0, $TransType, $DCFlag, $iNumber, $arrReturn['LastBalance'],$arrReturn['Balance'], '');
						}
					}
				}*/
				break;
			case 8:		//补发龙币
				$iResult = -1;
				$userDataBLL = new UserDataBLL($iRoleID);
				$arrReturn = $userDataBLL->editUserBankMoney(0,$iNumber,1);
				if(is_array($arrReturn) && count($arrReturn)>0 && $arrReturn['iResult'] == 0){	
					$Money = $arrReturn['FwMoney'];	
					$bankBLL = new BankBLL();
					$arrReturn = $bankBLL->updateSysBankMoney($this->AccType4, 0, $iNumber, 1);//从推广户扣龙币
					if(is_array($arrReturn) && count($arrReturn)>0)
					{
						$iResult = isset($arrReturn['iResult']) ? $arrReturn['iResult'] : -1;
						if($iResult==0 || $iResult==-1)//成功或失败
						{							
							$TransType = $this->arrConfig['TransType']['TransType9'];
							$DCFlag = $this->arrConfig['DCFlag']['DCFlag2'];
							$objDataChangeLogsBLL = new DataChangeLogsBLL($iRoleID);
							$objDataChangeLogsBLL->insertSysBankTransLogs($this->AccType4, $iRoleID, 0, $TransType, $DCFlag, $iNumber, $arrReturn['FwLastBalance'],$arrReturn['FwMoney'], '');
						}
					}
				}
				break;
			case 9:		//补发积分
				$userDataBLL = new UserDataBLL($iRoleID);
				$iResult = $userDataBLL->returnBackGameScore($iExtId,$iNumber);
				break;
			case 10:	//补发道具
				$userDataBLL = new UserDataBLL($iRoleID);
				for($i=0;$i<$iNumber;$i++){
					$iResult = $userDataBLL->returnBackStageProperty($iExtId);
				}
				break;
			case 11:	//补发黄钻
				$iResult = -1;
				/*$userBLL = new UserBLL($iRoleID);
				$arrResult = $userBLL->ReturnBackUserVip($iNumber);*/
                $arrResult = DSBuyRoleVip($iRoleID,$iNumber);

				if(is_array($arrResult) && count($arrResult)>0)
				{				
					$iResult = $arrResult['iResult'];
					if($iResult == 0){
					//记录黄钻充值日志
					    /*$objDataChangeLogsBLL = new DataChangeLogsBLL($iRoleID);
					    $objDataChangeLogsBLL->addBuyVipLogs($arrResult['VipOpeningTime'], $arrResult['VipExpireTime'], $iNumber, $arrResult['VipID']);*/
					}
				}				
				break;
			case 12:	//补发月礼包
				$userDataBLL = new UserDataBLL($iRoleID);
				$iResult = $userDataBLL->returnBackStageProperty($iExtId);
				break;
			case 15:	//回收黄钻
			    $iResult = -1;
			    $arrResult = DCLockRoleVip($iRoleID,$iNumber);
			    if(is_array($arrResult) && count($arrResult)>0)
			    {
			        $iResult = $arrResult['iResult'];
			        if($iResult == 0){
			            //记录黄钻充值日志
			            /*$objDataChangeLogsBLL = new DataChangeLogsBLL($iRoleID);
			             $objDataChangeLogsBLL->addBuyVipLogs($arrResult['VipOpeningTime'], $arrResult['VipExpireTime'], $iNumber, $arrResult['VipID']);*/
			        }
			    }
			    break;			
            case 23: //微信解绑
                $userInfo = getUserBaseInfo($iRoleID);

                $arrReturn = OMASUnBindWeChat($userInfo['LoginName']);
                $iResult = isset($arrReturn['iResult']) ? $arrReturn['iResult'] : -1;
                break;

		}
		$arrResult = serialize(array('iResult'=>$iResult,'Money'=>$Money));
		return $arrResult;
	}	

	
}