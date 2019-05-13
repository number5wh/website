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

require_once ROOT_PATH . 'Link/QueryRoleGameInfo.php';

/**
 * 代码是Service 拷贝的 action 一样 少掉申请的接口
 * Class ServiceCheckAction
 */
class ServiceCheckOtherAction extends PageBase
{
	private $strLoginedUser = '';
	
	public function __construct()
	{
		$this->arrConfig = unserialize(SYS_CONFIG);
		$this->strLoginedUser = Utility::chkUserLogin($this->arrConfig);
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
		$this->smarty->display($this->arrConfig['skin'].'/Service/AuthCheckOtherIndex.html');
	}
	/**
	 * 获取操作审核页面
     * 这部分页面不改 由于 页面中请求的 ServiceAuth
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
						 'ClassName'=>'ServiceCheck');
		
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/AuthoperateDetail.html');
		echo $html;
	} 
	
	/**
	 * 授权审核列表
	 */
	public function authVerifyList()
	{
		$flag = false;
		//获取Post参数
		$startTime = isset($_POST['startTime'])?$_POST['startTime']:'';
		$endTime = isset($_POST['endTime'])?$_POST['endTime']:'';
		$iStatus = isset($_POST['status'])?$_POST['status']:'';
		$arrType = isset($_POST['arrType'])?$_POST['arrType']:'';
		$keyword = isset($_POST['keyword'])?$_POST['keyword']:'';
		$keyvalue = isset($_POST['keyvalue'])?$_POST['keyvalue']:'';		
		$pattern = '/[\d]{4}-[\d]{1,2}-[\d]{1,2}/';		
		
		//组装where条件
		if(($keyword != '' && $keyword !='loginName' && !is_numeric($keyvalue)) || ($startTime != '' && !preg_match($pattern, $startTime)) ||
		   ($endTime != '' && !preg_match($pattern, $endTime)) || ($startTime > $endTime))
		{
			$strWhere = ' WHERE 2=1';
			$flag = true;
		}else{
			$strWhere = ' WHERE ShowType=2';
			$arrType = $arrType!='' ? explode(',',$arrType) : null;
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
			if($keyvalue != '')
			{
				if($keyword=='loginId')
					$strWhere .= " AND $keyword=$keyvalue ";
				else{
					
					$keyvalue = $keyword=='loginName'?Utility::utf8ToGb2312($keyvalue):$keyvalue;
					$strWhere .= " AND $keyword='$keyvalue' ";
				}
				$flag = true;
			}
		}
		
		if($flag){//是否是重新组合查询条件，如果是分页读取session中的where条件
			$_SESSION['getPageAuthVerify_Where'] = $strWhere;
		}
		
		//组装分页查询条件
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage <= 0 ? 1 : $curPage;
		$arrParam['fields'] = 'PID,RoleID,LoginID,LoginName,OperationType,iNumber,SysUserName,AddTime,Remarks,Reason,Payment,ExtendID,Status,Checker,CheckTime,CheckRemarks,FID';
		$arrParam['tableName'] = 'T_AuthProcess';
		$arrParam['where'] = isset($_SESSION['getPageAuthVerify_Where']) && !empty($_SESSION['getPageAuthVerify_Where']) ? $_SESSION['getPageAuthVerify_Where'] : $strWhere;
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
					$arrResult[$i]['LoginName'] = Utility::gb2312ToUtf8($v['LoginName']);					
					$arrResult[$i]['SysUserName'] = Utility::gb2312ToUtf8($v['SysUserName']);					
					$arrResult[$i]['Remarks'] =  str_replace(array("\r\n", "\r", "\n"),' ',Utility::gb2312ToUtf8($v['Remarks']));
					if($v['OperationType']==10){
						$stagePropertyBLL = new StagePropertyBLL();
						$arrReturn = $stagePropertyBLL->getSpPublicInfo($v['ExtendID']);
						$arrResult[$i]['Amount'] = $arrReturn['GoodsName']."*".$v['iNumber'];
					}elseif($v['OperationType']==11){
						$arrResult[$i]['Amount'] = $v['iNumber'].'天黄钻服务';
					}elseif($v['OperationType']==6){
						$arrResult[$i]['Amount'] = $v['iNumber'].$this->arrConfig['TreasureCompensateType'][7];
					}else{
                        $arrResult[$i]['Amount'] =sprintf("%.2f",$v['iNumber']/1000).$this->arrConfig['TreasureCompensateType'][$v['OperationType']];
					}
					$arrResult[$i]['Reason'] = Utility::gb2312ToUtf8($v['Reason']);
					$arrResult[$i]['Checker'] = Utility::gb2312ToUtf8($v['Checker']);
					$arrResult[$i]['Payment'] = empty($v['Payment'])?"系统账户":Utility::gb2312ToUtf8($v['Payment']);
					$arrResult[$i]['CheckRemarks'] = Utility::gb2312ToUtf8($v['CheckRemarks']);					
					$arrResult[$i]['OperationTypeName'] = $this->arrConfig['AuthVerifyType'][$v['OperationType']];
					$arrResult[$i]['AddTime'] = empty($v['AddTime'])?'':date('Y-m-d H:i:s',strtotime($v['AddTime']));
					$arrResult[$i]['CheckTime'] = empty($v['CheckTime'])?'':date('Y-m-d H:i:s',strtotime($v['CheckTime']));
					//读取报案人信息
					$arrResult[$i]['InformantRoleID'] = 0;//报案人角色ID
					$arrResult[$i]['InformantLoginID'] = 0;//报案人编号
					$arrResult[$i]['InformantLoginName'] = '';//报案人昵称
					$objSystemBLL = new SystemBLL();
					if($v['OperationType']==6 && !empty($arrResult[$i]['Reason']))
					{						
						$arrUserInfo = $objSystemBLL->getCaseInfoByID($arrResult[$i]['Reason']);
						if($arrUserInfo)
						{
							$arrResult[$i]['InformantRoleID'] = $arrUserInfo['RoleID'];
							$arrResult[$i]['InformantLoginID'] = $arrUserInfo['LoginID'];
							$arrResult[$i]['InformantLoginName'] = Utility::gb2312ToUtf8($arrUserInfo['LoginName']);
						}
					}
					//读取附件信息,判断是否拥有附件
					$arrReturns = $objSystemBLL->getAuthProcessFile($v['PID']);
					if(is_array($arrReturns) && count($arrReturns)>0)
						$arrResult[$i]['HaveFiles'] = true;
					else 
						$arrResult[$i]['HaveFiles'] = false;
					$i++;
				}
			}
		}
				
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'AuthVerifyList'=>$arrResult);
		Utility::assign($this->smarty,$arrTags);		
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/AuthCenterOtherList.html');
		$html = str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	
	/**
	 * 获取申请财富补偿页面
     * 屏蔽该接口
	 */
	private function getAddApplyTreasurePage()
	{
		$arrCompensateType = $this->arrConfig['TreasureCompensateType'];
		$arrCompensateReason = $this->arrConfig['TreasureCompensateReason'];
		$serverInfo = $this->getServiceInfo();
		
		$arrTags=array('skin'=>$this->arrConfig['skin'],
					   'arrCompensateType'=>$arrCompensateType,
					   'arrCompensateReason'=>$arrCompensateReason,
					   'serverInfo'=>$serverInfo);
		
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/AuthApplyTreasureCompensate.html');
		echo $html;
	}
	
	/**
	 * 获取图片上传服务器信息
	 */
	private function getServiceInfo()
	{
		$strUploadIP = '';
		$ServerID = '';
		//获取上传站点的IP地址
		$masterBLL = new MasterBLL();
		$arrServerList = $masterBLL->getServerList($this->arrConfig['ServerTypeWeb'][8]['TypeID'],0);//42:文件上传站点类型,0:正常未锁定的
		
		if(is_array($arrServerList) && count($arrServerList)>0)
		{
			$arrServer = explode(',',$arrServerList[0]['ServerIP']);
			$strUploadIP = $arrServer[0];
			$ServerID = $arrServerList[0]['ServerID'];				
		}
		
		return array('ServerID'=>$ServerID,'Domain'=>$strUploadIP);
	}
	
	/**
	 * 添加财富补偿申请
     * 屏蔽该接口
	 */
	private function addApplyTreasure()
	{
		$errorMsg = '';//错误信息
		$iLoginId = Utility::isNumeric('loginId', $_POST) ? $_POST['loginId'] : 0;
		$strLoginName = Utility::isNullOrEmpty('loginName', $_POST) ? $_POST['loginName'] : '';
		$iRoleID = Utility::isNumeric('roleId', $_POST) ? $_POST['roleId'] : 0;
		$iOperateType = Utility::isNumeric('typeId', $_POST) ? $_POST['typeId'] : 0;
		$iExtId = Utility::isNumeric('extId', $_POST) ? $_POST['extId'] : 0;
		$iNumber = Utility::isNumeric('number', $_POST) ? $_POST['number'] : 0;
		$strReason = Utility::isNullOrEmpty('reason', $_POST) ? $_POST['reason'] : '';
		$strRemark = isset($_POST['remark']) ? $_POST['remark'] : '';		
		$strFilePath = Utility::isNullOrEmpty('filePath', $_POST) ? $_POST['filePath'] : '';
		$strFilePath1 = Utility::isNullOrEmpty('filePath1', $_POST) ? $_POST['filePath1'] : '';
		$strFilePath2 = Utility::isNullOrEmpty('filePath2', $_POST) ? $_POST['filePath2'] : '';
		$iServerID = Utility::isNumeric('serverId', $_POST) ? $_POST['serverId'] : 0;
		$strKindName = Utility::isNullOrEmpty('kindName', $_POST) ? $_POST['kindName']:'';
		$strGoodsName = Utility::isNullOrEmpty('goodsName', $_POST) ? $_POST['goodsName']:'';
		
		if(empty($strLoginName) || strlen($strLoginName) < 2)
		{
			$errorMsg .= '请填写正确的玩家昵称<br/>';
		}
		if($iNumber < 0 || strlen($iNumber) > 10)
		{
			$errorMsg .= '请填写正确的数量<br/>';
		}
		if($iOperateType == 10 && $iExtId < 0)
		{
			$errorMsg .= '请填写正确的道具名称<br/>';
		}
		if($iOperateType == 9 && $iExtId < 0)
		{
			$errorMsg .= '请选择需要补偿的积分游戏<br/>';
		}
		if($strRemark =='' || mb_strlen($strRemark,'gb2312') > 50)
		{
			$errorMsg .= '请填写正确的备注不超过50个字<br/>';
		}
		
		if(!empty($errorMsg))
		{
			echo '{"iResult":-1,"msg":"' . $errorMsg . '"}';
		}else
		{
			$systemBLL = new SystemBLL();
			$arrResult = $systemBLL->addAuthProcess($iRoleID,$iLoginId,$strLoginName,$iOperateType,$iExtId,$iNumber,'',$strReason,$strRemark,'',2,$this->strLoginedUser,0);
			if($arrResult['iResult'] == 0)
			{
				echo '{"iResult":1,"msg":"财富补偿申请成功"}';
				if(!empty($strFilePath)){
					$systemBLL->addAuthProcessFile($arrResult['PID'],$strFilePath,$iServerID);
				}
				if(!empty($strFilePath1)){
					$systemBLL->addAuthProcessFile($arrResult['PID'],$strFilePath1,$iServerID);
				}
				if(!empty($strFilePath2)){
					$systemBLL->addAuthProcessFile($arrResult['PID'],$strFilePath2,$iServerID);
				}				
			}else{
				echo '{"iResult":-1,"msg":"财富补偿申请失败"}';
			}
			switch ($iOperateType)
			{
				case 7:
					$iType = 7;
					$OpContent = "由于".$strReason."，补偿".$iNumber."金币";
					break;
				case 8:
					$iType = 8;
					$OpContent = "由于".$strReason."，补偿".$iNumber."龙币";
					break;
				case 9:
					$iType = 9;
					$OpContent = "由于".$strReason."，补偿".$strKindName."游戏".$iNumber."积分";
					break;
				case 10:
					$iType = 10;
					$OpContent = "由于".$strReason."，补偿道具(".$strGoodsName."*".$iNumber.")";
					break;
				case 11:
					$iType = 4;
					$OpContent = "由于".$strReason."，补偿".$iNumber."天黄钻服务";
					break;
			}
			$operationLogsBLL = new OperationLogsBLL($iRoleID);
			$operationLogsBLL->addCaseOperationLogs($iRoleID, 0, $iType, $OpContent, $arrResult['iResult'], Utility::getIP(), 0, 2, $this->strLoginedUser, '');
		}
	}
	/**
	 * 读取附件信息 
	 */
	public function getAuthProcessFile()
	{
		$iPId = Utility::isNumeric('pid', $_POST) ? $_POST['pid'] : 0;
		$strFiles = '';
		$systemBLL = new SystemBLL();
		$arrReturns = $systemBLL->getAuthProcessFile($iPId);
		
		if(is_array($arrReturns) && count($arrReturns) > 0)
		{
			$i = 0 ;
			$masterBLL = new MasterBLL();
			$arrServerList = $masterBLL->getServerList($this->arrConfig['ServerTypeWeb'][8]['TypeID'],0);		
			foreach ($arrReturns as $val)
			{
				$strDomain = '';
				if(is_array($arrServerList) && count($arrServerList)>0)
				{
					foreach ($arrServerList as $v)
					{
						if($val['ServerID']==$v['ServerID'] && !empty($v['ServerIP']))
						{
							$arrServer = explode(',',$v['ServerIP']);
							$strDomain = 'http://'.$arrServer[0];
							break;
						}
					}
				}
				$fileUrl = $strDomain.$val['FilePath'];
				$iCount = $i+1;
				$strFiles .= "<a class='blue' href='$fileUrl' target='_blank'>附件$iCount</a>";				
				$strFiles .= "&nbsp;&nbsp;";
				$i++;
			}
		}else{
			$strFiles = "&nbsp;&nbsp;&nbsp;&nbsp;没有附件信息&nbsp;&nbsp;&nbsp;&nbsp;";
		}
		
		$arrTags=array('windowTitle'=>'附件信息',
					   'windowContent'=>$strFiles);
		
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Common/table.html');
		echo $html;
	}
	
	/**
	 * 根据道具名称搜索类似的道具列表
	 */
	public function searchSpListByName()
	{
		$html = '';
		$strGoodsName = Utility::isNullOrEmpty('goodsName', $_POST) ? $_POST['goodsName'] : '';
		$stagePropertyBLL = new StagePropertyBLL();
		$arrResult = $stagePropertyBLL->searchSpPublicInfo($strGoodsName);
		if(is_array($arrResult) && count($arrResult)>0)
		{
			$html .= '<ul>';
			foreach ($arrResult as $v)
			{
				$spId = $v['SpID'];
				$goodsName = Utility::gb2312ToUtf8($v['GoodsName']);
				$html .= "<li onclick='selectSp($spId,\"$goodsName\")'>$goodsName</li>";
			}
			$html .= '</ul>';
		}
		echo $html;
	}
	
	/**
	 * 获取积分游戏列表
	 */
	public function getGameKindList()
	{
		$html = '';
		$RoleID = Utility::isNumeric('RoleID', $_GET);
		if($RoleID && $RoleID>0)
		{
			//取积分游戏列表
			$masterBLL = new MasterBLL();
			$arrResult = $masterBLL->getGameKind();
			if(is_array($arrResult) && count($arrResult)>0)
			{
				//取玩家玩过的游戏
				/*$objUserDataBLL = new UserDataBLL($RoleID);
				$SqlWhere = ' AND (RoomType & 1)>0';
				$arrUserKind = $objUserDataBLL->getUserGameData($SqlWhere);*/
                $arrUserKind = getRoleGameInfoByType($RoleID,1);//积分房间
                //$arrUserKind = DSQueryRoleGameInfo($RoleID,1,10);
				if($arrUserKind)
				{		
					foreach ($arrResult as $v)
					{
						foreach ($arrUserKind as $val)
						{
							//过滤玩家没玩过的游戏
							if($v['KindID']==$val['KindID'])
							{
								$kindId = $v['KindID'];
								$kindName = $v['KindName'];
								$html .= "<option value='$kindId'>$kindName</option>";
								break;
							}
						}
					}
				}
			}
		}
		echo $html;
	}
}