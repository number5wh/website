<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/SystemBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/OperationLogsBLL.class.php';

require_once ROOT_PATH . 'Link/GetRoleBaseInfo.php';
class ServiceCaseAction extends PageBase
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
					   'strStatrTime'=>date('Y-m-d',strtotime("-6 day")),
					   'strEndTime'=>date('Y-m-d'));
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/Service/CaseIndex.html');
	}

	/**
	 * 分页案件信息列表
	 */
	public function caseList()
	{
		$flag = false;
		//获取Post参数
		$CaseSerial = isset($_POST['caseSerial']) ? $_POST['caseSerial'] : '';
		$LoginID = isset($_POST['loginId']) ? $_POST['loginId'] : '';
		$startTime = isset($_POST['startTime'])?$_POST['startTime']:'';
		$endTime = isset($_POST['endTime'])?$_POST['endTime']:'';
		$CaseStatus = isset($_POST['caseStatus']) ? $_POST['caseStatus'] : '';		
		$pattern = '/[\d]{4}-[\d]{1,2}-[\d]{1,2}/';
		
		//组装where条件
		if(($CaseSerial != '' && !is_numeric($CaseSerial)) || ($LoginID != '' && !is_numeric($LoginID)) ||
		   ($startTime != '' && !preg_match($pattern, $startTime)) || ($endTime != '' && !preg_match($pattern, $endTime)) || ($startTime > $endTime))
		{
			$strWhere = ' WHERE 2=1';
			$flag = true;
		}else{
			$strWhere = ' WHERE 1=1';
			if($CaseSerial != ''){			
				$strWhere .= " AND CaseSerial=$CaseSerial ";
				$flag = true;
			}
			if($LoginID != ''){
				$strWhere .= " AND LoginID=$LoginID ";
				$flag = true;
			}
			if($startTime != ''){
				$strWhere .= " AND DATEDIFF(d,HappenTime,'$startTime')<=0 ";
				$flag = true;
			}
			if($endTime != ''){
				//$endTime = date('Y-m-d',strtotime($endTime)+86400);
				$strWhere .= " AND DATEDIFF(d,HappenTime,'$endTime')>=0 ";
				$flag = true;
			}
			if($CaseStatus != ''){
				if($CaseStatus > 0){
					$strWhere .= " AND Step=$CaseStatus ";
				}
				$flag = true;
			}
		}		

		if($flag){//是否是重新组合查询条件，如果是分页读取session中的where条件
			$_SESSION['getPageCaseInfo_Where'] = $strWhere;
		}
		
		//组装分页查询条件
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage <= 0 ? 1 : $curPage;
		$arrParam['fields'] = 'CaseSerial,CaseType,LoginName,LoginID,RoleID,HappenTime,iNumber,Step,Decision,UpdateTime';
		$arrParam['tableName'] = 'T_CaseInfo';
		$arrParam['where'] = isset($_SESSION['getPageCaseInfo_Where']) && !empty($_SESSION['getPageCaseInfo_Where']) ? $_SESSION['getPageCaseInfo_Where'] : $strWhere;
		$arrParam['order'] = 'UpdateTime desc';
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
					$arrResult[$i]['HappenTime'] = date('Y-m-d H:i:s',strtotime($v['HappenTime']));
					$arrResult[$i]['UpdateTime'] = date('Y-m-d H:i:s',strtotime($v['UpdateTime']));
					$arrResult[$i]['Status'] = $this->arrConfig['CaseStatus'][$v['Step']];
					$arrResult[$i]['Decision'] = empty($v['Decision'])?"无":"有";
                    $arrResult[$i]['iNumber'] = Utility::FormatMoney($v['iNumber']);
					$i++;
				}
			}
		}
		
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'CaseInfoList'=>$arrResult);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/CaseInfoList.html');
		$html = str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}

	/**
	 * 案件录入弹出框
	 */
	public function caseInsertView()
	{
		$arrTags=array('skin'=>$this->arrConfig['skin']);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/CaseInfoAdd.html');		
		echo $html;
	}
	
	/**
	 * 根据游戏编号获取用户昵称
	 */
	public function getLoginName()
	{
		$returnMsg = '{"iResult":-2,"msg":"请输入正确的玩家编号"}';
		$iLoginId = Utility::isNumeric('loginId', $_POST) ? $_POST['loginId'] : 0;

		if($iLoginId > 0)
		{
			/*$masterBLL = new MasterBLL();
			$arrResult = $masterBLL->getRoleIDByKeyID($iLoginId,2);*/
            $iRoleID = $iLoginId;
            $arrReturn = getUserBaseInfo($iRoleID);
			if(is_array($arrReturn) && count($arrReturn)>0)
			{
				/*$userBLL = new UserBLL($arrResult['RoleID']);
				$arrReturn = $userBLL->getRoleInfo();*/

				//if(is_array($arrReturn) && count($arrReturn)>0)
					$returnMsg = '{"iResult":1,"msg":"'.$arrReturn['RealName'].'","roleId":'.$iRoleID.'}';
				//else
				//	$returnMsg = '{"iResult":-1,"msg":"对不起，数据读取失败"}';
			}else{
				$returnMsg = '{"iResult":0,"msg":"对不起，无该玩家编号"}';
			}
		}
		echo $returnMsg;
	}

	/**
	 * 案件录入，插入数据库
	 */
	public function caseInsert()
	{
		$errorMsg = '';//错误信息
		$iCaseType = Utility::isNumeric('caseType', $_POST) ? $_POST['caseType'] : 0;
		$iLoginId = Utility::isNumeric('loginId', $_POST) ? $_POST['loginId'] : 0;
		$strLoginName = Utility::isNullOrEmpty('loginName', $_POST) ? $_POST['loginName'] : '';
		$strRealName = Utility::isNullOrEmpty('realName', $_POST) ? $_POST['realName'] : '';
		$strCardId = Utility::isNullOrEmpty('cardId', $_POST) ? $_POST['cardId'] : '';
		$strMobile = Utility::isNullOrEmpty('mobile', $_POST) ? $_POST['mobile'] : '';
		$strTelephone = Utility::isNullOrEmpty('telephone', $_POST) ? $_POST['telephone'] : '';
		$happenTime = Utility::isNullOrEmpty('happenTime', $_POST) ? $_POST['happenTime'] : '';
		$iAmount = Utility::isNumeric('amount', $_POST) ? $_POST['amount'] : 0;
		$strDescription = Utility::isNullOrEmpty('description', $_POST) ? $_POST['description'] : '';
		$iRoleID = Utility::isNumeric('roleId', $_POST) ? $_POST['roleId'] : 0;

		if($iCaseType != 1 && $iCaseType != 2)
		{
			$errorMsg .= '请选择案件类型<br/>';
		}
		if($iLoginId < 1)
		{
			$errorMsg .= '请填写正确的玩家编号<br/>';
		}
		if(empty($strLoginName) || strlen($strLoginName) < 2)
		{
			$errorMsg .= '请填写正确的玩家昵称<br/>';
		}
		if($iCaseType == 1 && (empty($strRealName) || strlen($strRealName) < 2))
		{
			$errorMsg .= '请填写正确的玩家姓名<br/>';
		}
		if($iCaseType == 1 && (empty($strCardId) || (strlen($strCardId) != 15 && strlen($strCardId) != 18)))
		{
			$errorMsg .= '请填写正确的玩家身份证<br/>';
		}
		if($iCaseType == 1 && (empty($strMobile) || strlen($strMobile) < 8 ))
		{
			$errorMsg .= '请填写正确的玩家身手机<br/>';
		}
		if(strtotime($happenTime) >= time())
		{
			$errorMsg .= '请填写正确的案发时间<br/>';
		}
		if(empty($strDescription) || mb_strlen($strDescription,'gb2312') > 200)
		{
			$errorMsg .= '请填写正确的案件描述不超过200个字<br/>';
		}
		
		if(!empty($errorMsg))
		{
			echo '{"iResult":-1,"msg":"' . $errorMsg . '"}';
		}else
		{
			$systemBLL = new SystemBLL();
			$arrResult = $systemBLL->addCaseInfo($iCaseType,$iLoginId,$iRoleID,$strLoginName,$strRealName,$strCardId,$strMobile,$strTelephone,$happenTime,$iAmount,$strDescription,$this->strLoginedUser);
			if($arrResult['iResult'] == 0)
			{
				echo '{"iResult":1,"msg":"案件录入成功,案件编号为:'.$arrResult['iCaseSerial'].'"}';
				$systemBLL->addCaseOperateLog($arrResult['iCaseSerial'],$this->arrConfig['CaseLogNote'][1],$this->strLoginedUser);
				
				$objSessioin = new Session($this->arrConfig['Session']['SessionLoginName']);
				$iAdminID = $objSessioin->get($this->arrConfig['SessionInfo']['AdminID']);
				$SysUserName = $objSessioin->get($this->arrConfig['SessionInfo']['UserName']);
				$objOperationLogsBLL = new OperationLogsBLL(0);
				$objOperationLogsBLL->addCaseOperationLogs($iAdminID, 0, 30, '[案件中心]案件录入,案件编号为:'.$arrResult['iCaseSerial'], 0, Utility::getIP(), 0, 2, $SysUserName, '');
			}elseif($arrResult['iResult'] == -99){
				echo '{"iResult":-99,"msg":"数据库异常，请重试"}';
			}else{
				echo '{"iResult":-1,"msg":"案件录入失败"}';
			}
		}
	}
	
	/**
	 * 导出案件列表

	public function export2Excel()
	{
		//获取Get参数
		$CaseSerial = Utility::isNullOrEmpty('p1',$_GET);
		$LoginID = Utility::isNumeric('p2',$_GET);
		//$LoginName	= Utility::isNullOrEmpty('p3', $_GET);
		$HappenTime	= Utility::isNullOrEmpty('p4', $_GET);
		$CaseStatus = Utility::isNumeric('p5', $_GET);
		$date = getdate(strtotime($HappenTime));

		//组装where条件
		$strWhere = ' WHERE 1=1';
		if($CaseSerial)	$strWhere .= " AND CaseSerial=$CaseSerial ";
		if($LoginID) $strWhere .= " AND LoginID=$LoginID ";
		//if($LoginName) $strWhere .= " AND LoginName like '%".Utility::utf8ToGb2312($LoginName)."%' ";
		if($HappenTime)	$strWhere .= " AND HappenTime between '$HappenTime' and '".date('Y-m-d H:i:s',mktime(23,59,59,$date['mon'],$date['mday'],$date['year']))."' ";
		if($CaseStatus)	$strWhere .= " AND Step=$CaseStatus ";

		//组装分页查询条件
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage <= 0 ? 1 : $curPage;
		$arrParam['fields'] = 'CaseID,CaseSerial,CaseType,LoginName,HappenTime,iNumber,Step,AddTime';
		$arrParam['tableName'] = 'T_CaseInfo';
		$arrParam['where'] = $strWhere;
		$arrParam['order'] = 'CaseID';
		$arrParam['pagesize'] = 100;

		$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['System']);
		$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
		
		if($iRecordsCount > 0)
		{
			$arrResult = $objCommonBLL->getPageList($arrParam,$curPage);
			$this->getOutputHtml($arrResult);			
		}else{
			$this->operateResult(6);
		}
	}
	*/
	/**
	 * 获取案件列表导出Excel的html
	 * @param $arrList
	 
	public function getOutputHtml($arrList)
	{
		header("Content-Type: application/vnd.ms-execl;charset=utf-8");
		header("Content-Disposition: attachment; filename=CaseInfo_".date('YmdHis').".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		
		$html = '';
		$html .= "<table cellspacing=\"0\" cellpadding=\"5\" rules=\"all\" border=\"1\" bordercolor=\"#974807\" style=\"font-size:10pt;\">";
		//添加表头
		$html .= "<tr style=\"white-space: nowrap;text-align:center;height:35px;\">";
		$html .= "<td>案件编号</td>";
		$html .= "<td>案件类型</td>";
		$html .= "<td>案发时间</td>";
		$html .= "<td>涉案数量</td>";
		$html .= "<td>玩家昵称</td>";
		$html .= "<td>状态</td>";
		$html .= "<td>录入时间</td>";
		$html .= "</tr>";
		foreach ($arrList as $list)
		{
			$html .= "<tr>";
			$html .= "<td>".$list['CaseSerial']."</td>";
			$html .= "<td>".$list['CaseType']."</td>";
			$html .= "<td style=\"mso-number-format:yyyy-m-d h:mm;\">".date('Y-m-d H:i:s',strtotime($list['HappenTime']))."</td>";
			$html .= "<td>".$list['iNumber']."</td>";
			$html .= "<td>".Utility::gb2312ToUtf8($list['LoginName'])."</td>";
			$html .= "<td>".$list['Step']."</td>";
			$html .= "<td style=\"mso-number-format:yyyy-m-d h:mm;\">".date('Y-m-d H:i:s',strtotime($list['AddTime']))."</td>";
			//$html .= "<td>{0}</td>", codS.DZRQ;
			//$html .= "<td style=\"vnd.ms-excel.numberformat:yyyy/mm/dd;\">{0}</td>", codS.ZFJE;
			//$html .= "<td style=\"mso-number-format:0.00;\">{0}</td>", codS.dzje;
			//$html .= "<td style=\"mso-number-format:0.00;\">{0}</td>", codS.wdzje;
			//$html .= "<td>{0}</td>", codS.SFJS == null ? "未结算" : ((bool)codS.SFJS == true ? "已结算" : "已关闭");
			//$html .= "<td>{0}</td>", codS.JSRQ;
			$html .= "</tr>";
		}
		//$html .= "<tr><td colspan=\"13\">" + totalString + "</td></tr>";
		$html .= "</table>";
		echo $html;
	}
	*/
	/**
	 * 获取案件处理页面
	 */
	public function caseHandle()
	{		
		$iOnlyShow = Utility::isNumeric('flag', $_GET) ? $_GET['flag'] : 0;		
		$iCaseSerial = Utility::isNumeric('id', $_GET) ? $_GET['id'] : 0;
		
		if($iCaseSerial>0)
		{
			$systemBLL = new SystemBLL();
			$arrReturn = $systemBLL->getCaseInfoByID($iCaseSerial);
			if(is_array($arrReturn) && count($arrReturn) > 0)
			{
				
				$arrReturn['CaseStatus'] = $this->arrConfig['CaseStatus'][$arrReturn['Step']];
				$arrReturn['CaseSuspect'] = '';
				//获取案件进展信息
				if($arrReturn['Step']>=2){
					$arrReturn['CaseSuspect'] = $systemBLL->getCaseSuspectList($iCaseSerial, $iOnlyShow == 0 && $arrReturn['Step']<8);
				}
				$arrReturn['Progress'] = '';
				//获取案件进展信息
				if($arrReturn['Step']>=2){
					$arrReturn['Progress'] = $systemBLL->getCaseProgress($iCaseSerial);
				}
				$arrReturn['Files'] = '';
				//获取案件附件信息
				if($arrReturn['Step']>=4){
					$arrReturn['Files'] = $systemBLL->getCaseFiles($iCaseSerial,$arrReturn['Step']==4 && $iOnlyShow == 0);
				}
				$arrReturn['LoginName'] = Utility::gb2312ToUtf8($arrReturn['LoginName']);
				$arrReturn['RealName'] = Utility::gb2312ToUtf8($arrReturn['RealName']);
				$arrReturn['CaseIntro'] = Utility::gb2312ToUtf8($arrReturn['CaseIntro']);
				$arrReturn['HappenTime'] = date('Y-m-d H:i:s',strtotime($arrReturn['HappenTime']));
				$arrReturn['Decision'] = Utility::gb2312ToUtf8($arrReturn['Decision']);
				$arrReturn['Remarks'] = Utility::gb2312ToUtf8($arrReturn['Remarks']);
				
				//设置为查看状态
				if($iOnlyShow == 1){
					$arrReturn['Step'] = 0;
				}
				
				//获取案件操作日志
				$arrOperateLog = $systemBLL->getOperateLogs($iCaseSerial);
				
				$arrTags=array('skin'=>$this->arrConfig['skin'],
							   'case'=>$arrReturn,
							   'arrOperateLog'=>$arrOperateLog);
								
				Utility::assign($this->smarty,$arrTags);
				$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/CaseHandle.html');
				echo $html;
			}
		}
	}
	
	/**
	 * 案件操作流程处理
	 */
	public function caseUpdate()
	{
		$iResult = 0;
		$iCaseSerial = Utility::isNumeric('id', $_POST) ? $_POST['id'] : 0;
		$iStep = Utility::isNumeric('step', $_POST) ? $_POST['step'] : 0;
		$iType = Utility::isNumeric('type', $_POST) ? $_POST['type'] : 0;
		$flag = Utility::isNumeric('flag', $_POST) ? $_POST['flag'] : 0;
		if($iCaseSerial > 0 && $iStep > 1)
		{
			if($iStep == 99){
				$this->caseOperate($iCaseSerial, '退回重新复核', 3, $flag);
				exit();
			}elseif($iStep == 9){
				$this->caseOperate($iCaseSerial, '生成处罚决定', 5, $flag);
				exit();
			}elseif($iStep == 100){
				$this->caseOperate($iCaseSerial, '填写备注信息', 6, $flag);
				exit();
			}elseif($iStep == 8 && $iType == 1){
				$this->caseOperate($iCaseSerial, '填写追回金额', 4, $flag);
				exit();
			}elseif($iStep == 10){
				$this->caseOperate($iCaseSerial, '案件撤销', 7, $flag);
				exit();
			}
			//更新案件状态
			$iResult = $this->updateCaseStatus($iCaseSerial, $iStep);		
			
		}
		echo $iResult;
	}
	
	/**
	 * 更新案件处理状态
	 */
	private function updateCaseStatus($iCaseSerial, $iStep, $strContent='')
	{
		//更新案件状态
		$systemBLL = new SystemBLL();
		$iResult = $systemBLL->updateCaseStatus($iCaseSerial,$iStep);
		if($iResult == 0)
		{
			//插入案件操作日志
			$strNote = $this->arrConfig['CaseLogNote'][$iStep];
			if(!empty($strContent)){//补充日志内容
				$strNote .= '<br/><span class="orange">原因：'.$strContent.'</span>';
			}
			
			$systemBLL->addCaseOperateLog($iCaseSerial,$strNote,$this->strLoginedUser);
			
			$objSessioin = new Session($this->arrConfig['Session']['SessionLoginName']);
			$iAdminID = $objSessioin->get($this->arrConfig['SessionInfo']['AdminID']);
			$SysUserName = $objSessioin->get($this->arrConfig['SessionInfo']['UserName']);
			$objOperationLogsBLL = new OperationLogsBLL(0);
			$objOperationLogsBLL->addCaseOperationLogs($iAdminID, 0, 30, '[案件中心]更新案件状态,案件编号:'.$iCaseSerial, $iResult, Utility::getIP(), 0, 2, $SysUserName, '');
		
			return 1;	
		}
	}
	
	/**
	 * 获取添加案件进展页面
	 */
	public function getAddProgressPage()
	{
		$iCaseSerial = Utility::isNumeric('id', $_POST) ? $_POST['id'] : 0;
		if($iCaseSerial <= 0){
			echo -1;
			exit();
		}
		$arrTags=array('skin'=>$this->arrConfig['skin'],
					   'id'=>$iCaseSerial,
					   'title'=>'添加案件进展',
					   'type'=>1);
		
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/CaseOperatePage.html');
		echo $html;
	}
	
	/**
	 * 添加案件进展
	 */
	public function addProgress()
	{
		$iCaseSerial = Utility::isNumeric('id', $_POST) ? $_POST['id'] : 0;
		$strProgress = Utility::isNullOrEmpty('content', $_POST) ? $_POST['content'] : '';
		
		if(empty($strProgress) || mb_strlen($strProgress,'gb2312') > 200)
		{
			echo "请填正确的案件进展,不要超过200个字";
			exit();
		}
		
		if($iCaseSerial > 0)
		{
			//添加案件进展
			$systemBLL = new SystemBLL();
			$iResult = $systemBLL->addCaseProgress($iCaseSerial,$strProgress,$this->strLoginedUser);
			if($iResult == 0)
			{
				//插入案件操作日志
				$strNote = $this->arrConfig['CaseLogNote'][98];
				$systemBLL->addCaseOperateLog($iCaseSerial,$strNote,$this->strLoginedUser);
				
				$objSessioin = new Session($this->arrConfig['Session']['SessionLoginName']);
				$iAdminID = $objSessioin->get($this->arrConfig['SessionInfo']['AdminID']);
				$SysUserName = $objSessioin->get($this->arrConfig['SessionInfo']['UserName']);
				$objOperationLogsBLL = new OperationLogsBLL(0);
				$objOperationLogsBLL->addCaseOperationLogs($iAdminID, 0, 30, '[案件中心]更新案件进展,案件编号:'.$iCaseSerial, $iResult, Utility::getIP(), 0, 2, $SysUserName, '');

				echo 1;
				exit();			
			}
		}
		
		echo "添加案件进展失败";
	}
	
	/**
	 * 统一获取案件详情操作页面
	 * @param $iCaseSerial
	 * @param $title
	 * @param $type
	 */	
	public function caseOperate($iCaseSerial,$title,$type,$param='')
	{
		$arrTags=array('skin'=>$this->arrConfig['skin'],
					   'id'=>$iCaseSerial,
					   'title'=>$title,
					   'type'=>$type,
					   'param'=>$param);
		
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/CaseOperatePage.html');
		echo $html;
	}
	
	/**
	 * 获取上传附件页面
	 */
	public function getUploadFilePage()
	{
		$iCaseSerial = Utility::isNumeric('id', $_POST) ? $_POST['id'] : 0;
		if($iCaseSerial <= 0){
			echo -1;
			exit();
		}
		$serverInfo = $this->getServiceInfo();
		$arrTags=array('skin'=>$this->arrConfig['skin'],
					   'id'=>$iCaseSerial,
					   'title'=>'上传文件',
					   'serverInfo'=>$serverInfo,
					   'type'=>2);
		
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/CaseOperatePage.html');
		echo $html;
	}
	
	/**
	 * 获取图片上传服务器信息
	 */
	private function getServiceInfo()
	{
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
	 * 案件信息添加附件
	 */
	public function addCaseFiles()
	{
		$iResult = 0;
		$iCaseSerial = Utility::isNumeric('id', $_POST) ? $_POST['id'] : 0;
		$strFilePath = Utility::isNullOrEmpty('filePath', $_POST) ? $_POST['filePath'] : '';
		$strFileName = Utility::isNullOrEmpty('fileName', $_POST) ? $_POST['fileName'] : '';
		$strFilePath1 = Utility::isNullOrEmpty('filePath1', $_POST) ? $_POST['filePath1'] : '';
		$strFileName1 = Utility::isNullOrEmpty('fileName1', $_POST) ? $_POST['fileName1'] : '';
		$strFilePath2 = Utility::isNullOrEmpty('filePath2', $_POST) ? $_POST['filePath2'] : '';
		$strFileName2 = Utility::isNullOrEmpty('fileName2', $_POST) ? $_POST['fileName2'] : '';
		$iServerID = Utility::isNumeric('svrId', $_POST) ? $_POST['svrId'] : 0;		
		
		if($iCaseSerial > 0)
		{
			$iCount = 0;
			$iResult = -1;
			//添加案件附件
			$systemBLL = new SystemBLL();
			if(!empty($strFilePath) && !empty($strFileName)){
				$iCount += $systemBLL->addCaseFiles($iCaseSerial,$strFileName,$strFilePath,$iServerID,$this->strLoginedUser);
			}
			if(!empty($strFilePath1) && !empty($strFileName1)){
				$iCount += $systemBLL->addCaseFiles($iCaseSerial,$strFileName1,$strFilePath1,$iServerID,$this->strLoginedUser);
			}
			if(!empty($strFilePath2) && !empty($strFileName2)){
				$iCount += $systemBLL->addCaseFiles($iCaseSerial,$strFileName2,$strFilePath2,$iServerID,$this->strLoginedUser);
			}
			if($iCount > -3)
			{
				//插入案件操作日志
				$strNote = $this->arrConfig['CaseLogNote'][99];
				if(!empty($strFileName)) $strNote .= "&nbsp;$strFileName";
				if(!empty($strFileName1)) $strNote .= "&nbsp;$strFileName1";
				if(!empty($strFileName2)) $strNote .= "&nbsp;$strFileName2";
				$systemBLL->addCaseOperateLog($iCaseSerial,$strNote,$this->strLoginedUser);				
				//添加管理员操作日志
				$objSessioin = new Session($this->arrConfig['Session']['SessionLoginName']);
				$iAdminID = $objSessioin->get($this->arrConfig['SessionInfo']['AdminID']);
				$SysUserName = $objSessioin->get($this->arrConfig['SessionInfo']['UserName']);
				$objOperationLogsBLL = new OperationLogsBLL(0);
				$objOperationLogsBLL->addCaseOperationLogs($iAdminID, 0, 30, '[案件中心]添加案件附件,案件编号:'.$iCaseSerial, 0, Utility::getIP(), 0, 2, $SysUserName, '');
				
				$iResult = 1;
			}
		}
		
		echo $iResult;
	}
	
	/**
	 * 删除案件附件
	 */
	public function deleteFile()
	{
		$iResult = 0;
		$fileId = Utility::isNumeric('fid', $_POST) ? $_POST['fid'] : 0;
		$fileName = Utility::isNullOrEmpty('fname', $_POST) ? $_POST['fname'] : '';
		$iCaseSerial = Utility::isNumeric('id', $_POST) ? $_POST['id'] : 0;
		if($fileId > 0 && !empty($fileName) && $iCaseSerial > 0)
		{
			$systemBLL = new SystemBLL();
			$iCount = $systemBLL->deleteFile($fileId);
			if($iCount == 0)
			{
				//插入案件操作日志
				$strNote = $this->arrConfig['CaseLogNote'][102]."&nbsp;$fileName";
				$systemBLL->addCaseOperateLog($iCaseSerial,$strNote,$this->strLoginedUser);
				
				//添加管理员操作日志
				$objSessioin = new Session($this->arrConfig['Session']['SessionLoginName']);
				$iAdminID = $objSessioin->get($this->arrConfig['SessionInfo']['AdminID']);
				$SysUserName = $objSessioin->get($this->arrConfig['SessionInfo']['UserName']);
				$objOperationLogsBLL = new OperationLogsBLL(0);
				$objOperationLogsBLL->addCaseOperationLogs($iAdminID, 0, 30, '[案件中心]删除案件附件,案件编号:'.$iCaseSerial, 0, Utility::getIP(), 0, 2, $SysUserName, '');
				
				$iResult = 1;
			}
		}
		
		echo $iResult;
	}
	
	/**
	 * 案件退回复核添加原因
	 * @author blj
	 */
	public function addBackReason()
	{
		$iResult = 0;
		$iCaseSerial = Utility::isNumeric('id', $_POST) ? $_POST['id'] : 0;
		$strReason = Utility::isNullOrEmpty('content', $_POST) ? $_POST['content'] : '';
		
		if(empty($strReason) || mb_strlen($strReason,'gb2312') > 200)
		{
			echo -1;
			exit();
		}
		if($iCaseSerial > 0)
		{
			$iResult = $this->updateCaseStatus($iCaseSerial, 4, $strReason);
			//添加管理员操作日志
			$objSessioin = new Session($this->arrConfig['Session']['SessionLoginName']);
			$iAdminID = $objSessioin->get($this->arrConfig['SessionInfo']['AdminID']);
			$SysUserName = $objSessioin->get($this->arrConfig['SessionInfo']['UserName']);
			$objOperationLogsBLL = new OperationLogsBLL(0);
			$objOperationLogsBLL->addCaseOperationLogs($iAdminID, 0, 30, '[案件中心]案件退回复核,案件编号:'.$iCaseSerial, 0, Utility::getIP(), 0, 2, $SysUserName, '');
						
		}
		
		echo $iResult;
	}	
	
	/**
	 * 案件执行完毕，填写追回金额
	 * @author blj
	 */
	public function addReturnAmount()
	{
		$iResult = 0;
		$iCaseSerial = Utility::isNumeric('id', $_POST) ? $_POST['id'] : 0;
		$iAmount = isset($_POST['amount']) ? $_POST['amount'] : -1;
		$iFlag = Utility::isNumeric('flag', $_POST) ? $_POST['flag'] : 0;
		if($iAmount < 0)
		{
			echo -1;
			exit();
		}
		
		if($iCaseSerial > 0)
		{
			$systemBLL = new SystemBLL();
			$iResult = $systemBLL->updateCaseReturnAmount($iCaseSerial,$iAmount);
			if($iResult == 0)
			{
				if($iFlag == 0){
					$iResult = $this->updateCaseStatus($iCaseSerial, 8);
				}else{
					$strNote = $this->arrConfig['CaseLogNote'][101];
					$systemBLL->addCaseOperateLog($iCaseSerial,$strNote,$this->strLoginedUser);
					$iResult = 1;
				}
				//添加管理员操作日志
				$objSessioin = new Session($this->arrConfig['Session']['SessionLoginName']);
				$iAdminID = $objSessioin->get($this->arrConfig['SessionInfo']['AdminID']);
				$SysUserName = $objSessioin->get($this->arrConfig['SessionInfo']['UserName']);
				$objOperationLogsBLL = new OperationLogsBLL(0);
				$objOperationLogsBLL->addCaseOperationLogs($iAdminID, 0, 30, '[案件中心]案件执行完毕,填写追回金额,案件编号:'.$iCaseSerial, 0, Utility::getIP(), 0, 2, $SysUserName, '');
				
			}
		}
		
		echo $iResult;
	}
	
	/**
	 * 案件完结，填写处罚决定
	 * @author blj
	 */
	public function addCaseDecision()
	{
		$iResult = 0;
		$iCaseSerial = Utility::isNumeric('id', $_POST) ? $_POST['id'] : 0;
		$strDecision = Utility::isNullOrEmpty('content', $_POST) ? $_POST['content'] : '';
		
		if(empty($strDecision) || mb_strlen($strDecision,'gb2312') > 200)
		{
			echo "请填正确的处罚内容,不要超过200个字";
			exit();
		}
		if($iCaseSerial > 0)
		{
			$systemBLL = new SystemBLL();
			$iResult = $systemBLL->updateCaseDecision($iCaseSerial,$strDecision);
			if($iResult == 0)
			{
				$iResult = $this->updateCaseStatus($iCaseSerial, 9);
				//添加管理员操作日志
				$objSessioin = new Session($this->arrConfig['Session']['SessionLoginName']);
				$iAdminID = $objSessioin->get($this->arrConfig['SessionInfo']['AdminID']);
				$SysUserName = $objSessioin->get($this->arrConfig['SessionInfo']['UserName']);
				$objOperationLogsBLL = new OperationLogsBLL(0);
				$objOperationLogsBLL->addCaseOperationLogs($iAdminID, 0, 30, '[案件中心]案件完结,填写处罚决定,案件编号:'.$iCaseSerial, $iResult, Utility::getIP(), 0, 2, $SysUserName, '');
				
			}
		}
		
		echo $iResult;
	}
	
	/**
	 * 案件完结后，填写案件备注
	 * @author blj
	 */
	public function addCaseRemark()
	{
		$iResult = 0;
		$iCaseSerial = Utility::isNumeric('id', $_POST) ? $_POST['id'] : 0;
		$strRemark = Utility::isNullOrEmpty('content', $_POST) ? $_POST['content'] : '';
		
		if(empty($strRemark) || mb_strlen($strRemark,'gb2312') > 200)
		{
			echo "请填正确的案件备注,不要超过200个字";
			exit();
		}
		if($iCaseSerial > 0)
		{
			$systemBLL = new SystemBLL();
			$iResult = $systemBLL->updateCaseRemark($iCaseSerial,$strRemark);
			if($iResult == 0)
			{
				//插入案件操作日志
				$strNote = $this->arrConfig['CaseLogNote'][100];
				$systemBLL->addCaseOperateLog($iCaseSerial,$strNote,$this->strLoginedUser);
				$iResult = 1;
			}
		}
		
		echo $iResult;
	}
	
	/**
	 * 案件撤销，填写撤销原因
	 * @author blj
	 */
	public function addCancelReason()
	{
		$iResult = 0;
		$iCaseSerial = Utility::isNumeric('id', $_POST) ? $_POST['id'] : 0;
		$strCancelReason = Utility::isNullOrEmpty('content', $_POST) ? $_POST['content'] : '';
		
		if(empty($strCancelReason) || mb_strlen($strCancelReason,'gb2312') > 200)
		{
			echo -1;
			exit();
		}
		if($iCaseSerial > 0)
		{
			$iResult = $this->updateCaseStatus($iCaseSerial, 10, $strCancelReason);		
			//添加管理员操作日志
			$objSessioin = new Session($this->arrConfig['Session']['SessionLoginName']);
			$iAdminID = $objSessioin->get($this->arrConfig['SessionInfo']['AdminID']);
			$SysUserName = $objSessioin->get($this->arrConfig['SessionInfo']['UserName']);
			$objOperationLogsBLL = new OperationLogsBLL(0);
			$objOperationLogsBLL->addCaseOperationLogs($iAdminID, 0, 30, '[案件中心]案件撤销,案件编号:'.$iCaseSerial, $iResult, Utility::getIP(), 0, 2, $SysUserName, '');
					
		}
		
		echo $iResult;
	}
	
	/**
	 * 获取修改案件描述页面
	 */
	public function getEditCaseIntroPage()
	{
		$iCaseSerial = Utility::isNumeric('id', $_POST) ? $_POST['id'] : 0;
		if($iCaseSerial <= 0){
			echo -1;
			exit();
		}
		
		$arrTags=array('skin'=>$this->arrConfig['skin'],
					   'id'=>$iCaseSerial,
					   'title'=>'修改案件描述',
					   'type'=>9);
		
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/CaseOperatePage.html');
		echo $html;
	}
	
	/**
	 * 修改案件描述
	 */
	public function updateCaseIntro()
	{
		$iResult = 0;
		$iCaseSerial = Utility::isNumeric('id', $_POST) ? $_POST['id'] : 0;
		$strCaseIntro = Utility::isNullOrEmpty('content', $_POST) ? $_POST['content'] : '';
		
		if(empty($strCaseIntro) || mb_strlen($strCaseIntro,'gb2312') > 1000)
		{
			echo "请填正确的案件描述,不要超过1000个字";
			exit();
		}
		if($iCaseSerial > 0)
		{
			$systemBLL = new SystemBLL();
			$iResult = $systemBLL->updateCaseIntro($iCaseSerial,$strCaseIntro);
			if($iResult == 0)
			{
				//插入案件操作日志
				$strNote = $this->arrConfig['CaseLogNote'][105];
				$systemBLL->addCaseOperateLog($iCaseSerial,$strNote,$this->strLoginedUser);
				//添加管理员操作日志
				$objSessioin = new Session($this->arrConfig['Session']['SessionLoginName']);
				$iAdminID = $objSessioin->get($this->arrConfig['SessionInfo']['AdminID']);
				$SysUserName = $objSessioin->get($this->arrConfig['SessionInfo']['UserName']);
				$objOperationLogsBLL = new OperationLogsBLL(0);
				$objOperationLogsBLL->addCaseOperationLogs($iAdminID, 0, 30, '[案件中心]修改案件描述,案件编号:'.$iCaseSerial, $iResult, Utility::getIP(), 0, 2, $SysUserName, '');
			
				$iResult = 1;
			}
		}
		
		echo $iResult;
	}
	
	/**
	 * 获取添加涉案人员页面
	 */
	public function getAddCaseSuspectPage()
	{
		$iCaseSerial = Utility::isNumeric('id', $_POST) ? $_POST['id'] : 0;
		if($iCaseSerial <= 0){
			echo -1;
			exit();
		}		
		$arrTags=array('skin'=>$this->arrConfig['skin'],
					   'id'=>$iCaseSerial,
					   'title'=>'新增涉案玩家',
					   'type'=>8);
		
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/CaseOperatePage.html');
		echo $html;
	}
	
	/**
	 * 添加案件涉案人员
	 */
	public function addCaseSuspect()
	{
		$iResult = 0;
		$iCaseSerial = Utility::isNumeric('id', $_POST) ? $_POST['id'] : 0;
		$iLoginId = Utility::isNumeric('loginId', $_POST) ? $_POST['loginId'] : 0;
		$strLoginName = Utility::isNullOrEmpty('loginName', $_POST) ? $_POST['loginName'] : '';
		$iRoleId = Utility::isNullOrEmpty('roleId', $_POST) ? $_POST['roleId'] : 0;		
		
		if($iCaseSerial>0 && $iLoginId>0 && !empty($strLoginName) && $iRoleId>0)
		{
			$iResult = -1;
			//添加涉案人员
			$systemBLL = new SystemBLL();
			
			$iCount = $systemBLL->addCaseSuspect($iCaseSerial,$iRoleId,$iLoginId,$strLoginName);
			
			if($iCount == 0)
			{
				//插入案件操作日志
				$strNote = $this->arrConfig['CaseLogNote'][103]."&nbsp;$strLoginName($iLoginId)";
				$systemBLL->addCaseOperateLog($iCaseSerial,$strNote,$this->strLoginedUser);
				//添加管理员操作日志
				$objSessioin = new Session($this->arrConfig['Session']['SessionLoginName']);
				$iAdminID = $objSessioin->get($this->arrConfig['SessionInfo']['AdminID']);
				$SysUserName = $objSessioin->get($this->arrConfig['SessionInfo']['UserName']);
				$objOperationLogsBLL = new OperationLogsBLL(0);
				$objOperationLogsBLL->addCaseOperationLogs($iAdminID, 0, 30, '[案件中心]添加案件涉案人员,案件编号:'.$iCaseSerial, 0, Utility::getIP(), 0, 2, $SysUserName, '');
			
				$iResult = 1;
			}
		}
		
		echo $iResult;
	}
	
	/**
	 * 删除案件涉案人员
	 * @author blj
	 */
	public function deleteCaseSuspect()
	{
		$iResult = 0;
		$iRoleId = Utility::isNumeric('roleId', $_POST) ? $_POST['roleId'] : 0;
		$loginName = Utility::isNullOrEmpty('name', $_POST) ? $_POST['name'] : '';
		$iCaseSerial = Utility::isNumeric('id', $_POST) ? $_POST['id'] : 0;
		if($iRoleId > 0 && !empty($loginName) && $iCaseSerial > 0)
		{
			$systemBLL = new SystemBLL();
			$iCount = $systemBLL->deleteCaseSuspect($iCaseSerial, $iRoleId);
			if($iCount == 0)
			{
				//插入案件操作日志
				$strNote = $this->arrConfig['CaseLogNote'][104]."&nbsp;$loginName";
				$systemBLL->addCaseOperateLog($iCaseSerial,$strNote,$this->strLoginedUser);
				//添加管理员操作日志
				$objSessioin = new Session($this->arrConfig['Session']['SessionLoginName']);
				$iAdminID = $objSessioin->get($this->arrConfig['SessionInfo']['AdminID']);
				$SysUserName = $objSessioin->get($this->arrConfig['SessionInfo']['UserName']);
				$objOperationLogsBLL = new OperationLogsBLL(0);
				$objOperationLogsBLL->addCaseOperationLogs($iAdminID, 0, 30, '[案件中心]删除案件涉案人员,案件编号:'.$iCaseSerial, 0, Utility::getIP(), 0, 2, $SysUserName, '');
			
				$iResult = 1;
			}
		}
		
		echo $iResult;
	}
	
	/**
	 *  获取涉案玩家操作日志记录
	 */
	public function getOperateSucpectInfo()
	{
		$iRoleId = Utility::isNumeric('roleId', $_POST) ? $_POST['roleId'] : 0;		
		$iCaseSerial = Utility::isNumeric('id', $_POST) ? $_POST['id'] : 0;
		if($iRoleId > 0 && $iCaseSerial > 0)
		{
			$operationLogsBLL = new OperationLogsBLL($iRoleId);
			$arrResult = $operationLogsBLL->getOperationLogs($iRoleId, $iCaseSerial);
			if(is_array($arrResult) && count($arrResult)>0)
			{
				$i = 0;
				foreach ($arrResult as $v)
				{
					$arrResult[$i]['SysUserName'] = Utility::gb2312ToUtf8($v['SysUserName']);
					$arrResult[$i]['OpContent'] = Utility::gb2312ToUtf8($v['OpContent']);
					$arrResult[$i]['AddTime'] = date('Y-m-d H:i:s',strtotime($v['AddTime']));
					$i++;
				}
			}else{
				$arrResult = null;
			}			
			$arrTags=array('skin'=>$this->arrConfig['skin'],
						   'OperateUserLogList'=>$arrResult);
			
			Utility::assign($this->smarty,$arrTags);
			$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/CaseOperateUserLogList.html');
			echo $html;
			exit();
		}
		echo -1;		
	}

}