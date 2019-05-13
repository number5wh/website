<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Common/PHPExecl/PHPExcel.php';
require ROOT_PATH . 'Class/BLL/BankBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/MsgBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserDataBLL.class.php';
require ROOT_PATH . 'Class/BLL/DataChangeLogsBLL.class.php';
require ROOT_PATH . 'Class/BLL/OperationLogsBLL.class.php';
require ROOT_PATH . 'Class/BLL/SetOperationLogsBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/SystemBLL.class.php';
require ROOT_PATH . 'Class/BLL/StagePropertyBLL.class.php';
require ROOT_PATH . 'Class/BLL/PassAccountBLL.class.php';
require ROOT_PATH . 'Class/BLL/PassSecurityBLL.class.php';
require ROOT_PATH . 'Class/BLL/PayLogsBLL.class.php';
require ROOT_PATH . 'Class/BLL/CDAccountBLL.class.php';

require_once ROOT_PATH . 'Link/GetRoleBaseInfo.php';
require_once ROOT_PATH . 'Link/QueryRoleList.php';
require_once ROOT_PATH . 'Link/QueryRoleGameInfo.php';
require_once ROOT_PATH . 'Link/QueryRoleBankInfo.php';
require_once ROOT_PATH . 'Link/LockRole.php';
require_once ROOT_PATH . 'Link/ResetRoleBankPwd.php';
require_once ROOT_PATH . 'Link/LockRoleMonery.php';
require_once ROOT_PATH . 'Link/BlockRole.php';
require_once ROOT_PATH . 'Link/UpdateUserAccountInfo.php';
require_once ROOT_PATH . 'Link/SetRoleIPLock.php';
require_once ROOT_PATH . 'Link/ClearCurRoomInfo.php';
require_once ROOT_PATH . 'Link/ResetLoginPwd.php';
require_once ROOT_PATH . 'Link/QueryRoleID.php';
require_once ROOT_PATH . 'Link/SavingRoleMonery.php';
require_once ROOT_PATH . 'Link/SetBankWeChatCheck.php';
require_once ROOT_PATH . 'Link/UnLockUserBank.php';
require_once ROOT_PATH . 'Link/SetPassType.php';
require_once ROOT_PATH . 'Link/ClearRoleData.php';
class ServiceRoleAction extends PageBase
{	
	private $objBankBLL = null;
	private $objMasterBLL = null;
	private $objSystemBLL = null;
	private $objStagePropertyBLL = null;
	private $iVerType = 0;
	private $iTmpRoleID = array();
	private $arrIntroRoleID = array();
	private $iFlag = 0;
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);
		$this->objBankBLL = new BankBLL();
		$this->objMasterBLL = new MasterBLL();		
		$this->objSystemBLL = new SystemBLL();

		//$this->objStagePropertyBLL = new StagePropertyBLL();
	}
	public function index()
	{
		$this->smarty->display($this->arrConfig['skin'].'/Service/RoleList.html');
	}	 
	
	/**
	 * 分页取角色信息
	 */
	public function getPagerRole()
	{
		Utility::setMicroTime();
		$LoginID = trim(Utility::isNumeric('LoginID',$_REQUEST));	//游戏编号
		$SafePhone = trim(Utility::isNumeric('SafePhone', $_REQUEST)); //安全手机
		$LoginCode = trim(Utility::isNullOrEmpty('LoginCode', $_REQUEST));//通行证账号
		$CardNumber = trim(Utility::isNullOrEmpty('CardNumber', $_REQUEST));//身份证号
		$LoginName = trim(Utility::isNullOrEmpty('LoginName', $_REQUEST));//角色昵称
		//var_dump($_REQUEST);
		$LastLoginIP = trim(Utility::isNullOrEmpty('LastLoginIP', $_REQUEST));//登陆IP
		$QQ = trim(Utility::isNullOrEmpty('QQ', $_REQUEST));
		$MachineSerial = trim(Utility::isNullOrEmpty('MachineSerial', $_REQUEST));//机器码
		$WechatSerial = trim(Utility::isNullOrEmpty('WeChatSerial', $_REQUEST));//微信码
	
		$curPage = Utility::isNumeric('curPage',$_REQUEST)?$_REQUEST['curPage']:1;
		$pageSize = 10;
		$Page=NULL;
		$arrResult = null;
		$SearchContent = '';
		$lockStatus['iResult'] = -1;
		$totalNum = 0;
		$SearchParam = array();
		if($LoginID || $SafePhone || $LoginCode || $CardNumber || $LoginName || $LastLoginIP || $QQ || $MachineSerial || $WechatSerial)
		{
			if($LoginID){//玩家编号
				$temp = ASQueryRoleList($LoginID,1,$curPage,$pageSize);
				$last = ASQueryRoleList($LoginID,1,$temp['iTotalPage'],$pageSize);

				if(isset($temp['RoleInfoList']))
					$arrPassSec = $temp['RoleInfoList'];
				$totalNum = ($temp['iTotalPage']-1)*$pageSize+$last['iRoleCount'];
				$SearchContent = "LoginID($LoginID)";
				$SearchParam['LoginID'] = $LoginID;
			}
			elseif($LoginCode){ //通行证账号
				$temp = ASQueryRoleList($LoginCode,4,$curPage,$pageSize);
				$last = ASQueryRoleList($LoginCode,4,$temp['iTotalPage'],$pageSize);
				if(isset($temp['RoleInfoList']))
					$arrPassSec = $temp['RoleInfoList'];
				$totalNum = ($temp['iTotalPage']-1)*$pageSize+$last['iRoleCount'];
				$SearchContent = "通行证账号($LoginCode)";
				$SearchParam['LoginCode'] = $LoginCode;
			}
			elseif ($SafePhone)//安全手机
			{
				$temp = ASQueryRoleList($SafePhone,7,$curPage,$pageSize);
				$last = ASQueryRoleList($SafePhone,7,$temp['iTotalPage'],$pageSize);
				$SearchContent = "安全手机($SafePhone)";
				$SearchParam['SafePhone'] = $SafePhone;
				if(isset($temp['RoleInfoList']))
					$arrPassSec = $temp['RoleInfoList'];
				$totalNum = ($temp['iTotalPage']-1)*$pageSize+$last['iRoleCount'];
			}
			elseif($LoginName){//角色昵称
				$LoginName1 = Utility::utf8ToGb2312($LoginName);
				$temp = DCQueryRoleID($LoginName1);
				if($temp['iResult'] != 0 ){
					$LoginID = $temp['iResult'];
					$temp = ASQueryRoleList($LoginID,1,$curPage,$pageSize);
					$last = ASQueryRoleList($LoginID,1,$temp['iTotalPage'],$pageSize);
					if(isset($temp['RoleInfoList']))
						$arrPassSec = $temp['RoleInfoList'];
				}
				$SearchParam['LoginID'] = $LoginID;
				$totalNum = ($temp['iTotalPage']-1)*$pageSize+$last['iRoleCount'];
			}
			elseif($LastLoginIP){//登陆IP
				$temp = ASQueryRoleList($LastLoginIP,5,$curPage,$pageSize);
				$last = ASQueryRoleList($LastLoginIP,5,$temp['iTotalPage'],$pageSize);
				if(isset($temp['RoleInfoList']))
					$arrPassSec = $temp['RoleInfoList'];
				$totalNum = ($temp['iTotalPage']-1)*$pageSize+$last['iRoleCount'];
				$SearchContent = "登陆IP($LastLoginIP)";
				$SearchParam['LastLoginIP'] = $LastLoginIP;
			}
			elseif($QQ){//QQ
				$temp = ASQueryRoleList($QQ,6,$curPage,$pageSize);
				$last = ASQueryRoleList($QQ,6,$temp['iTotalPage'],$pageSize);
				if(isset($temp['RoleInfoList']))
					$arrPassSec = $temp['RoleInfoList'];
				$totalNum = ($temp['iTotalPage']-1)*$pageSize+$last['iRoleCount'];
				$SearchContent = "QQ($QQ)";
				$SearchParam['QQ'] = $QQ;
			}
			elseif($CardNumber){//身份证号
				$temp = ASQueryRoleList($CardNumber,3,$curPage,$pageSize);
				$last = ASQueryRoleList($CardNumber,3,$temp['iTotalPage'],$pageSize);
				if(isset($temp['RoleInfoList']))
					$arrPassSec = $temp['RoleInfoList'];
				$totalNum = ($temp['iTotalPage']-1)*$pageSize+$last['iRoleCount'];
				$SearchContent = "身份证($CardNumber)";
				$SearchParam['CardNumber'] = $CardNumber;
			}
			elseif($MachineSerial){//机器码
				$temp = ASQueryRoleList($MachineSerial,8,$curPage,$pageSize);
				$last = ASQueryRoleList($MachineSerial,8,$temp['iTotalPage'],$pageSize);
				if(isset($temp['RoleInfoList'])){
					$arrPassSec = $temp['RoleInfoList'];
				}
				$totalNum = ($temp['iTotalPage']-1)*$pageSize+$last['iRoleCount'];
				$SearchContent = "机器码($MachineSerial)";
				$SearchParam['MachineSerial'] = $MachineSerial;
			}
			elseif($WechatSerial){//微信码
				$temp = ASQueryRoleList($WechatSerial,9,$curPage,$pageSize);
				$last = ASQueryRoleList($WechatSerial,9,$temp['iTotalPage'],$pageSize);
				if(isset($temp['RoleInfoList'])){
					$arrPassSec = $temp['RoleInfoList'];
				}
				$totalNum = ($temp['iTotalPage']-1)*$pageSize+$last['iRoleCount'];
				$SearchContent = "微信码($WechatSerial)";
				$SearchParam['WechatSerial'] = $WechatSerial;
			}
			$SearchParam['curPage'] = $curPage;
			//记录查询日志
			$objSessioin = new Session($this->arrConfig['Session']['SessionLoginName']);
			$SysUserName = $objSessioin->get($this->arrConfig['SessionInfo']['UserName']);
			$strMsg = "客服中心查询玩家信息,查询条件:$SearchContent";
			$objOperationLogsBLL = new OperationLogsBLL(0);
			$objOperationLogsBLL->addCaseOperationLogs(0, 0, 34, $strMsg, 0, Utility::getIP(), 0, 2, $SysUserName, '');
			$Page = Utility::setPages($curPage,$totalNum,$pageSize);
			
			if(isset($arrPassSec) && $arrPassSec)
			{
					
				$iRoleIDs = Utility::array_column($arrPassSec,'iLoginID');
				$arrRole = DSQueryRoleList($iRoleIDs);
				$arrRole = $arrRole['RoleInfoList'];
				foreach ($arrPassSec as $key => $val)
				{
					//  在arrRole里面查找对应LoginID的角色信息
					foreach ($arrRole as $k => $v){
						if($v['iRoleID'] == $val['iLoginID']){
							$Role = $v;
							break;
						}
	
					}
					//$objUserBLL = new UserBLL(0);
					//$arrRoleInfo = $objUserBLL->getRole(3,$val['Passport']);
					//$arrRoleInfo['Passport'] = $val['Passport'];
					//$arrRoleInfo['Signature'] = str_replace(array("\r\n", "\r", "\n"),'<br />',Utility::gb2312ToUtf8($arrRoleInfo['Signature']));
					$arrRoleInfo['LockStartTime'] = date('Y-m-d H:i:s',$val['iLockStartTime']);
					$arrRoleInfo['LockEndTime'] = date('Y-m-d H:i:s', $val['iLockEndTime']);
					$arrRoleInfo['LastLoginTime'] = date('Y-m-d H:i:s',$val['iLastLoginTime']);
					$arrRoleInfo['AddTime'] = date('Y-m-d', $val['iAddTime']);
					//从Data中获取
					$arrRoleInfo['VipID'] = $Role['iVipID'];
					$arrRoleInfo['VipExpireTime'] = date('Y-m-d H:i:s', $Role['iVipExpireTime']);
					$arrRoleInfo['VipOpeningTime'] = date('Y-m-d H:i:s', $Role['iVipOpeningTime']);
					$arrRoleInfo['Signature'] = $Role['szSignature'];
					$arrRoleInfo['LoginCode'] = $val['szLoginCode'];
					$arrRoleInfo['LoginName'] = $Role['szLoginName'];
					$arrRoleInfo['LoginID'] = $val['iLoginID'];
					if(isset($val['szLoginCode']))
						$arrRoleInfo['LoginCode'] = $val['szLoginCode'];
					else
					{
	
						$arrRoleInfo['LoginCode'] = '';
						/* $objPassAccountBLL = new PassAccountBLL();
						 $arrPassportInfo = $objPassAccountBLL->getUserAccountInfo($val['Passport'],2);
						 $arrRoleInfo['LoginCode'] = !empty($arrPassportInfo) ? $arrPassportInfo['LoginCode'] : ''; */
					}
					
					//统计转出转出数据

 					$stardate = date("Y-m-d",time());
 					$enddate =  date('Y-m-d',strtotime('-3 day'));
 					$enddate1 =  date('Y-m-d',strtotime('-1 month'));
                    $enddate2 =  date('Y-m-d',strtotime('-3 month'));


 					$objDataChangeBLL = new DataChangeLogsBLL();
 					$threedata = $objDataChangeBLL->getUserDayOut($enddate,$stardate,(string)$val['iLoginID'],'2');


 					if(!empty($threedata[0]['changemoney']))
 					   $arrRoleInfo['threeDayout'] =Utility::FormatMoney($threedata[0]['changemoney']);
 					else
 					   $arrRoleInfo['threeDayout'] ='0';

 					$monthdata = $objDataChangeBLL->getUserDayOut($enddate1,$stardate,(string)$val['iLoginID'],'2');

 					if(!empty($monthdata[0]['changemoney']))
 					    $arrRoleInfo['monthDayout'] = Utility::FormatMoney($monthdata[0]['changemoney']);
 					else
 					    $arrRoleInfo['monthDayout'] =0;


                    //end统计转入数据


                    $objDataChangeBLL = new DataChangeLogsBLL();
                    $threedatain = $objDataChangeBLL->getUserDayOut($enddate,$stardate,(string)$val['iLoginID'],'5');


                    if(!empty($threedatain[0]['changemoney']))
                        $arrRoleInfo['threeDayin'] = Utility::FormatMoney($threedatain[0]['changemoney']);
                    else
                        $arrRoleInfo['threeDayin'] ='0';

                    $monthdatain = $objDataChangeBLL->getUserDayOut($enddate1,$stardate,(string)$val['iLoginID'],'5');

                    if(!empty($monthdatain[0]['changemoney']))
                        $arrRoleInfo['monthDayin'] = Utility::FormatMoney($monthdatain[0]['changemoney']);
                    else
                        $arrRoleInfo['monthDayin'] =0;



                    $alldataout = $objDataChangeBLL->getUserDayOut($enddate2,$stardate,(string)$val['iLoginID'],'2');

                    if(!empty($alldataout[0]['changemoney']))
                        $arrRoleInfo['allout'] = Utility::FormatMoney($alldataout[0]['changemoney']);
                    else
                        $arrRoleInfo['allout'] =0;


                    $alldatain = $objDataChangeBLL->getUserDayOut($enddate2,$stardate,(string)$val['iLoginID'],'5');

                    if(!empty($alldatain[0]['changemoney']))
                        $arrRoleInfo['allin'] = Utility::FormatMoney($alldatain[0]['changemoney']);
                    else
                        $arrRoleInfo['allin'] =0;


					
					$arrRoleInfo['HappyBeanMoney'] =sprintf('%.2f',$Role['iHappyBeanMoney']/1000);
					//用户账号锁定状态 0:有锁,-1:没锁
					$arrRoleInfo['Locked'] = $val['iLocked'];
					$arrRoleInfo['LockStartTime'] = date('Y-m-d H:i:s',$val['iLockStartTime']);
					$arrRoleInfo['LockEndTime'] = date('Y-m-d H:i:s',$val['iLockEndTime']);
	
					$arrResult[]=$arrRoleInfo;
	
				}
			}
		}
		$objSessioinLogin = new Session($this->arrConfig['Session']['SessionLoginName']);
		$DeptID = $objSessioinLogin->get($this->arrConfig['SessionInfo']['DeptID']);
		$arrTags=array('skin'=>$this->arrConfig['skin'],'nowTime'=>date("Y-m-d H:i:s",time()), 'RoleList'=>$arrResult, 'lockStatus'=>$lockStatus['iResult'],'Page'=>$Page,'DeptID'=>$DeptID,'searchParam'=>http_build_query($SearchParam));
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/RolePage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	private function getRoleList($TypeID,$Key)
	{
		$lockStatus = null;
		
		$curPage = Utility::isNumeric('curPage',$_POST);		
		$curPage = $curPage<=0 ? 1 : $curPage;
		if($TypeID==1)
			$strWhere = ' Where LoginID='.$Key;
		elseif($TypeID==2)
			$strWhere = " Where MachineSerial='$Key'";
		elseif($TypeID==4)
			$strWhere = " Where LoginName='$Key'";

		$arrParam['fields']='RoleID,LoginName,VipID,VipExpireTime,LvlID,Passport,LoginID,Gender,MoorMachine,LockStartTime,LockEndTime,Locked,LoginCount,LastLoginIP,LastLoginTime,RegIP,AddTime,[Signature],VipOpeningTime';
		$arrParam['tableName']='T_Role';
		$arrParam['where']=$strWhere;
		$arrParam['order']='RoleID';
		$arrParam['pagesize']=10;
		$arrParam['function']='';		
		$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['User']);
		$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
		if($iRecordsCount>0)
			$arrResult = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);
		
		$objUserBLL = new UserBLL(0);
		//$arrResult = $objUserBLL->getRoleList($TypeID,$Key);
		if($arrResult)
		{
			$iCount = 0;
			$objPassAccountBLL = new PassAccountBLL();
			foreach ($arrResult as $val)
			{
				$arrResult[$iCount]['LoginName'] = str_replace(array("\r\n", "\r", "\n"),'<br />',Utility::gb2312ToUtf8($val['LoginName']));
				$arrResult[$iCount]['Signature'] = str_replace(array("\r\n", "\r", "\n"),'<br />',Utility::gb2312ToUtf8($val['Signature']));
				$arrResult[$iCount]['LockStartTime'] = date('Y-m-d H:i:s', strtotime($val['LockStartTime']));
				$arrResult[$iCount]['LockEndTime'] = date('Y-m-d H:i:s', strtotime($val['LockEndTime']));
				$arrResult[$iCount]['LastLoginTime'] = date('Y-m-d H:i:s', strtotime($val['LastLoginTime']));
				$arrResult[$iCount]['AddTime'] = date('Y-m-d', strtotime($val['AddTime']));
				$arrResult[$iCount]['VipExpireTime'] = date('Y-m-d H:i:s', strtotime($val['VipExpireTime']));
				$arrResult[$iCount]['VipOpeningTime'] = date('Y-m-d H:i:s', strtotime($val['VipOpeningTime']));
					
				$arrInfo = $objPassAccountBLL->getUserAccountInfo($val['Passport'],2);				
				$arrResult[$iCount]['LoginCode']= $arrInfo['LoginCode'];
				
				$objUserDataBLL = new UserDataBLL($val['RoleID']);
				$result = $objUserDataBLL->checkBankMyKnapsackPWD();
				$arrResult[$iCount]['TransPass'] = $result['PackageCount'];
				$arrResult[$iCount]['Pwd'] = $result['UserBankCount'];
				
				//获取用户银行金币
				$userBankMoney = $objUserDataBLL->getUserBankMoney();
				//查询背包中金币
				$myKnapsack = $objUserDataBLL->getMyKnapsackMoney();
				//游戏中的金币
				$gameWealth = $objUserDataBLL->getUserGameWealthAllMoney();
				$arrResult[$iCount]['HappyBeanMoney'] = $userBankMoney['Money']+$myKnapsack['Money']+$gameWealth['TotalMoney'];
				//用户账号锁定状态 0:有锁,-1:没锁
				$lockStatus = $objUserDataBLL->checkUserLockInfo();
				if(!empty($lockStatus))
					$arrResult[$iCount]['LockStatus']=$lockStatus['iResult'];
				else 
					$arrResult[$iCount]['LockStatus']=-1;
				//读取玩家权限
				$arrUserPrivilege = $objUserBLL->getUserPrivilegeInfo();
				if($arrUserPrivilege)
					$arrResult[$iCount]['PlayerType'] = isset($this->arrConfig['PlayerType'][$arrUserPrivilege['MasterRight']]) ? '['.$this->arrConfig['PlayerType'][$arrUserPrivilege['MasterRight']].']' : '';
				else
					$arrResult[$iCount]['PlayerType'] = '';
					
				$iCount++;
			}
		}
		return array('Result'=>$arrResult,'Page'=>$Page,'LockStatus'=>$lockStatus);
		//return array('Result'=>$arrResult,'LockStatus'=>$lockStatus);
	}
	/**
	 * 用户基本信息
	 */
	function getRoleBaseInfo()
	{

		$RoleID = Utility::isNumeric('RoleID',$_REQUEST);

		//获取管理员所在部门
		$objSessioinLogin = new Session($this->arrConfig['Session']['SessionLoginName']);
        $DeptID = $objSessioinLogin->get($this->arrConfig['SessionInfo']['DeptID']);
		
        $result = getUserBaseInfo($RoleID);
        $result['AddTime'] = date("Y-m-d H:i:s",$result['AddTime']);
        $result['LastLoginTime'] = date("Y-m-d H:i:s",$result['LastLoginTime']);

        //游戏锁定， 非零 为 KindID
        $GameLock = $result['GameLock'];
        $result['GameLock'] = '未锁定';
        if($GameLock){
            $GameLockInfo = $this->objMasterBLL->getGameKindInfo($GameLock);
            //var_dump($GameLockInfo);
            $result['GameLock'] = $GameLockInfo['KindName'];
        }

        /*$objUserBLL = new UserBLL($RoleID);
        $userInfo = $objUserBLL->getRoleInfo();

        $objUserDataBLL = new UserDataBLL($RoleID);
        $userPower = $objUserDataBLL->getUserPowerInfo();
        $result = array();
        if(is_array($userInfo) && is_array($userPower)){
            $result = array_merge($userInfo, $userPower);
            $result['GenderName'] = isset($result['Gender']) && $result['Gender']?'女':'男';
        }
        else
        {
            $result = $userInfo;
            $result['GenderName'] = isset($result['Gender']) && $result['Gender']?'女':'男';
        }*/
        $result['GenderName'] = isset($result['Gender']) && $result['Gender']?'女':'男';
		/*//用户账号锁定状态 0:有锁,-1:没锁
		$lockStatus = $objUserDataBLL->checkUserLockInfo();
		//检查用户表是否锁定 0:锁定,-1:未锁定
		$roleLockStatus = $objUserBLL->checkUserLockStatus();  
		if($roleLockStatus['iResult'] && $lockStatus['iResult']){
			$lFlag = 0;	//显示锁定角色
		}else{
			$lFlag = 1;
		}*/
        $lFlag = $result['Locked'];
		//检查解除银行、背包冻结状态
		$BMStatus = DSQueryRoleBankInfo($RoleID);
		/*
		//DC验证并根据Passport查询安全手机
		//$com = new COM("PHPItfc.PHPInterface") or die("无法建立COM组件");
		if(isset($userInfo['Passport'])){			
			$arrPara = array($this->arrConfig['DcConfig'][0]['HOST'],$this->arrConfig['DcConfig'][0]['PORT'],$userInfo['Passport'],'');
			//$res = $com->DCSTGetPhoneByPspt($arrPara);	//			echo 33;exit;
	
			//$json = get_object_vars(json_decode($res));
			//$result['Mobile'] = ($json['Para0'] == 'success'?$json['Para1']:'');	//安全手机
			$objPassAccountBLL = new PassAccountBLL();
			$arrPassInfo = $objPassAccountBLL->getUserAccountInfo($userInfo['Passport'],2);
			if($arrPassInfo)
			{
				$result['RealName'] = str_replace(array("\r\n", "\r", "\n"),'<br />',Utility::gb2312ToUtf8($arrPassInfo['RealName']));
				$result['MobilePhone'] = $arrPassInfo['MobilePhone'];
				$result['IdCard'] = $arrPassInfo['IdCard'];
				$result['QQ'] = $arrPassInfo['QQ'];
			}
		}*/
		//DC获取当前登录状态
        if($result['RoomID']) {
            $RoomInfo = $this->objMasterBLL->getGameRoomInfo($result['RoomID']);
            $result['RoomName'] = $RoomInfo['RoomName'];
        }else{
            $result['RoomName'] = "未登录";
        }

		/*$arrInGame = $objUserDataBLL->getUserGameWealthList($RoleID);
		if(!empty($arrInGame))
		{
			foreach($arrInGame as $val)
			{
				$arrRoomInfo = $this->objMasterBLL->getGameRoomList($val['InGame'],2);
				if(!empty($arrRoomInfo)) $RoomName .= $arrRoomInfo[0]['RoomName'] . '&nbsp;&nbsp;&nbsp;&nbsp;';
			}
		}*/
		/*$arrPara2 = array($this->arrConfig['DcConfig'][0]['HOST'],$this->arrConfig['DcConfig'][0]['PORT'],$RoleID,'');
		$res2 = $com->DCSTGetRoleState($arrPara2);	
		$json2 = get_object_vars(json_decode($res2));
		if($json2['Para0'] == 'success'){
			$GameKind = $this->objMasterBLL->getGameKindInfo($json2['Para3']); 
			$result['KindName'] = $GameKind?$GameKind['KindName']:'';
			$GameRoom = $this->objMasterBLL->getGameRoomList($json2['Para4'],2);
			$result['RoomName'] = $GameRoom?$GameRoom[0]['RoomName']:'';
			$result['TableID'] = $json2['Para5']?$json2['Para5']:'';
		}*/
        /**
         * 'BMStatus'=>$BMStatus,
         */
		/*$result['RoomName'] = $RoomName;*/
        //var_dump($result);
		$arrTags=array('DeptID'=>$DeptID, 'RoleID'=>$RoleID, 'lFlag'=>$lFlag,  'result'=>$result,'nowTime'=>date("Y-m-d H:i:s",time())
        ,'RegIPDistrict'=>$result['RegIP'],'LastLoginIPDistrict'=>$result['LastLoginIP'],'BMStatus'=>$BMStatus);
		//print_r($arrTags);exit;
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/RoleBaseInfo.html');
		$html=str_replace("</script>","<\/script>",str_replace(array("\r\n","\n"),'',$html));//str_replace("\r\n",'',$html);
		echo $html;
	}
	
	/**
	 * 取角色信息
	 */
	public function getRoleInfo()
	{
		$LoginID = Utility::isNumeric('loginID',$_GET);
        $roleID = Utility::isNumeric('roleID',$_GET);
        if(empty($LoginID)){
            $LoginID = $roleID;
        }
		//点击银行密码或背包密码时，设置的银行资料或道具资料参数
        //var_dump($LoginID);
        /*$asRoleBaseInfo = ASGetRoleBaseInfo($LoginID);
        $dsRoleBaseInfo = DSGetRoleBaseInfo($LoginID);
        //合并两个数组转化格式
        $keyMap = array("szLoginName"=>"LoginName","iLoginID"=>"LoginID","szMobilePhone"=>"MobilePhone","szQQ"=>"QQ",
            "iMoorMachine"=>"MoorMachine","iLockStartTime"=>"LockStartTime","iTitleID"=>"TitleID","iLockEndTime"=>"LockEndTime",
            "iLocked"=>"Locked","iLoginCount"=>"LoginCount","szLastLoginIP"=>"LastLoginIP","iLastLoginTime"=>"LastLoginTime",
            "szRegIP"=>"RegIP","iAddTime"=>"AddTime",
        );
        $asRoleBaseInfo = Utility::arrReplaceKey($asRoleBaseInfo,$keyMap);
        $keyMap = array("iRoleID"=>"RoleID","szRealName"=>"RealName","iGender"=>"Gender","iVipID"=>"VipID","szSignature"=>"Signature",
                "iVipExpireTime"=>"VipExpireTime","iVipOpeningTime"=>"VipOpeningTime","iRoomName"=>"RoomName"
            );
        $dsRoleBaseInfo = Utility::arrReplaceKey($dsRoleBaseInfo,$keyMap);

        $result = array_merge($asRoleBaseInfo,$dsRoleBaseInfo);*/
        $result = getUserBaseInfo($LoginID);
        //var_dump($result);

        if($result['VipOpeningTime'] > 0)$result['VipOpeningTime'] = date("Y-m-d H:i:s",$result['VipOpeningTime']);
        if($result['VipExpireTime'] > 0) $result['VipExpireTime'] = date("Y-m-d H:i:s", $result['VipExpireTime']);
		$DivID = isset($_GET['DivID'])?trim($_GET['DivID']):'';
		
		/*$objUserBLL = new UserBLL($LoginID);
		$result = $objUserBLL->getRoleInfo();
		$objUserDataBLL = new UserDataBLL($LoginID);*/
//		$userPower = $objUserDataBLL->getUserPowerInfo();
		//银行资料信息显示
        $userBankInfo = DSQueryRoleBankInfo($LoginID);
        $keyMap =array("iGameWealth"=>"GameWealth","iFreeze"=>"Freeze","iMoney"=>"Money","iAddTime"=>"AddTime","iFirstRechargeTime"=>"FirstRechargeTime","iTotalTime"=>"TotalTime"
        ,"iSuperPlayerLevel"=>"SuperPlayerLevel","iChargeCount"=>"ChargeCount","iTotalChargeMoney"=>"TotalChargeMoney");
        $userBankInfo = Utility::arrReplaceKey($userBankInfo,$keyMap);
        $userBankInfo['FirstRechargeTime'] = date('Y-m-d h:i:s',$userBankInfo['FirstRechargeTime']);
        //var_dump($userBankInfo);
		//查询背包中金币
		/*$myKnapsack = $objUserDataBLL->getMyKnapsackMoney();
		//游戏中的金币
		$gameWealth = $objUserDataBLL->getUserGameWealthAllMoney();
		$arrHappyBeanMoney = array('fwBank'=>$userBankInfo['FwMoney'],'bank'=>$userBankInfo['Money'], 'knapsack'=>$myKnapsack['Money'],'game'=>$gameWealth['TotalMoney']);*/
//		//取得被冻结的财富数量getPagerUserRechargeRecords
//		$lockMoney = $this->objSystemBLL->getCaseOperateUser($RoleID, 1);
//		$beLockedMoney = 0;
//		if($lockMoney && count($lockMoney)>0){
//			$i=0;			
//			foreach ($lockMoney as $v){
//				$beLockedMoney += $v['iNumber']+$v['ReturnNumber'];
//				$i++;
//			}
//		}
		
//		$result = array();
//		if(is_array($userInfo)&& is_array($userPower)){ 
//			$result = array_merge($userInfo, $userPower);
			$result['GenderName'] = isset($result['Gender']) && $result['Gender']?'女':'男';
//		}
		/*//用户账号锁定状态 0:有锁,-1:没锁
		$lockStatus = $objUserDataBLL->checkUserLockInfo();		
		//检查用户表是否锁定 0:锁定,-1:未锁定
		$roleLockStatus = $objUserBLL->checkUserLockStatus();  */
        //锁定未定
		/*if($roleLockStatus['iResult'] && $lockStatus['iResult']){
			$lFlag = 0;	//显示锁定角色
		}else{
			$lFlag = 1;
		}*/
        $lFlag = 1;
		//封号状态
		$pFlag =  isset($result['Locked'])&& $result['Locked']?1:0;
		//检查解除银行、背包冻结状态
		/*$BMStatus = $objUserDataBLL->checkBankMyKnapsackStatus();*/
				
		$result['FileName'] = date('YmdHis').rand(100, 999);//用于充值记录导出(下载)execl的文件名
        /**
         * 暂时去除的变量
         * 'BMStatus'=>$BMStatus，
         * 'M'=>$arrHappyBeanMoney,
         * 'userBankInfo'=>$userBankInfo,
         */
		$SetSevice = $this->arrConfig['SetSevice'];
		$OperateType = $this->arrConfig['OperateType'];
		$DateType = $this->arrConfig['DateType'];
		$arrTags=array('RoleID'=>$LoginID, 'DivID'=>$DivID,'userBankInfo'=>$userBankInfo, 'lFlag'=>$lFlag, 'pFlag'=>$pFlag,  'result'=>$result,'nowTime'=>date("Y-m-d H:i:s",time()),'FromDate1'=>date("Y-m-d",strtotime('-6 day')),'FromDate7'=>date("Y-m-d",strtotime('-6 day')), 'nowDate'=>date("Y-m-d",time()), 'RechargeTypeList'=>$this->arrConfig['RechargeType'], 'MatchMode'=>$this->arrConfig['MatchMode'],'SetSevice'=>$SetSevice,'OperateType'=>$OperateType,'DateType'=>$DateType);
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/Service/RoleEdit.html');
	}
	
	/**
	 * VIP开通明细 
	 */
	public function showVipDetail()
	{
		$iRoleID = Utility::isNumeric('RoleID',$_POST);
		//当前分页
		$iCurPage = Utility::isNumeric('curPage',$_POST);
		$iCurPage = $iCurPage<=0 ? 1 : $iCurPage;		
		$startTime = Utility::isNullOrEmpty('Stime',$_POST)? $_POST['Stime']:date('Y-m-d');
        $endTime = Utility::isNullOrEmpty('Etime',$_POST)? $_POST['Etime']:date('Y-m-d');
		//$arrParam['fields']='LogsID, RoleID, CONVERT(VARCHAR(20),RechargeTime,120) AS RechargeTime, CONVERT(VARCHAR(20),ExpireTime,120) AS ExpireTime,OpenType,TimeLimit,Status,VipID';
		$arrParam['fields']='RoleID, CONVERT(VARCHAR(100),BuyTime,120) AS BuyTime,LogType,BuyDays,BuyType';
		$arrParam['tableName']='T_UserVIPLogs_';
        $arrParam['StartDate'] = $startTime;
        $arrParam['EndDate'] = $endTime;
		$arrParam['where']=" WHERE RoleID=$iRoleID";
		$arrParam['order']=' BuyTime DESC';
        $arrParam['Page'] = $iCurPage;
		$arrParam['PageSize']=10;
		
		$objDataChangeLogsBLL = new DataChangeLogsBLL($iRoleID);
		$iRecordsCount = $objDataChangeLogsBLL->getUserVipDetailCount($arrParam);
		$Page=Utility::setPages($iCurPage,$iRecordsCount,$arrParam['PageSize']);
		$arrResult = $objDataChangeLogsBLL->getUserVipDetailList($arrParam);
		if($arrResult){
			$i=0;
			/*foreach($arrResult as $v){
				$arrResult[$i]['startTime'] = date("Y-m-d", strtotime($v['RechargeTime']));
				$arrResult[$i]['expire'] = date("Y-m-d", strtotime($v['ExpireTime']));
				$i++;
			}*/
		}
		$nowTime = date("Y-m-d", time());
		$arrTags=array('skin'=>$this->arrConfig['skin'], 'RoleID'=>$iRoleID, 'result'=>$arrResult,'Page'=>$Page, 'nowTime'=>$nowTime);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/RoleVipPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	
	/**
	 * 日工资明细
	 */
	public function showSalaryDetail()
	{	
		$iRoleID = Utility::isNumeric('RoleID',$_POST);
		$VipOpenFlag = Utility::isNumeric('VipOpenFlag',$_POST);
		$VipOpenTime=Utility::isNullOrEmpty('VipOpenTime', $_POST) ? $_POST['VipOpenTime'] : '';
		/*if($VipOpenFlag){
			$iStartDate = Utility::isNullOrEmpty('StartTime', $_POST);
			$iEndDate = Utility::isNullOrEmpty('EndTime', $_POST) ? $_POST['EndTime'] : date('Y-m-d');
		}else{			
			$iEndDate = date('Y-m-d');
			//取开始查询日期
			if(empty($VipOpenTime))
				$iStartDate = date('Y-m-d');
			else
			{
				//如果会员开通日期在近90天内开通的,开始日期取会员开通日期,否则取当前日期倒退90天后的日期
				if(strtotime($VipOpenTime)>strtotime("-7 day"))
					$iStartDate = date("Y-m-d",strtotime($VipOpenTime));
				else
					$iStartDate = date("Y-m-d",strtotime("-7 day"));
			}
		}*/
		$iStartDate = date("Y-m-d",strtotime("-14 day"));
		$iEndDate = date('Y-m-d');
		$arrTags=array('strStime'=>$iStartDate, 'strEtime'=>$iEndDate,'RoleID'=>$iRoleID,'VipOpenTime'=>$VipOpenTime); 
		Utility::assign($this->smarty,$arrTags);	
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/RoleSalaryDetailList.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	/**
	 * 日工资明细
	 */
	public function getPagerSalaryDetail()
	{	
		$iRoleID = Utility::isNumeric('RoleID',$_POST);
		$VipOpenTime=Utility::isNullOrEmpty('VipOpenTime', $_POST) ? $_POST['VipOpenTime'] : date("Y-m-d",strtotime("-9 day"));
		$iStartDate = Utility::isNullOrEmpty('StartTime', $_POST) ? $_POST['StartTime'] : date("Y-m-d",strtotime("-9 day"));
		//如果会员开通日期在近90天内开通的,开始日期取会员开通日期,否则取当前日期倒退90天后的日期
		//if(strtotime($VipOpenTime)>strtotime("-7 day"))
		//	$iStartDate = date("Y-m-d",strtotime($VipOpenTime));

		$iEndDate = Utility::isNullOrEmpty('EndTime', $_POST) ? $_POST['EndTime'] : date('Y-m-d');
		//当前分页
		$iCurPage = Utility::isNumeric('curPage',$_POST);
		$iCurPage = $iCurPage<=0 ? 1 : $iCurPage;		
		
		$arrParam['fields']='LogsID, RoleID, DaySalary, CONVERT(VARCHAR(20),ReceiveTime,120) AS ReceiveTime,CONVERT(VARCHAR(10),ReceiveTime,120) AS SendTime';
		$arrParam['tableName']='T_DaySalaryReceiveLogs';
		$arrParam['where']=" WHERE RoleID=$iRoleID AND DATEDIFF(d,'$iStartDate',ReceiveTime)>=0 AND DATEDIFF(d,'$iEndDate',ReceiveTime)<=0";
		$arrParam['order']=' ReceiveTime DESC,LogsID';
		$arrParam['pagesize']=10;

		$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['DataChangeLogs'],$iRoleID);
		$iRecordsCount = $objCommonBLL->getRecordsCountSelect($arrParam);
		$Page=Utility::setPages($iCurPage,$iRecordsCount,$arrParam['pagesize']);
		$arrResult = $objCommonBLL->getPageListSelect($arrParam,$Page['CurPage']);	
		
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'SalaryDetailList'=>$arrResult); 
		Utility::assign($this->smarty,$arrTags);
	
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/RoleSalaryDetailPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}	
	
	/**
	 * 月礼包明细
	 */
	function showMonthGiftPackage()
	{
		$iRoleID = Utility::isNumeric('RoleID',$_POST);
		$VipOpenFlag = Utility::isNumeric('VipOpenFlag',$_POST);
		if($VipOpenFlag){
			$iStartDate = Utility::isNullOrEmpty('StartTime', $_POST);
			$iEndDate = Utility::isNullOrEmpty('EndTime', $_POST) ? $_POST['EndTime'] : date('Y-m-d');
		}else{
			$iEndDate = date('Y-m-d');
			//取开始查询日期
			$iStartDate = date("Y-m-d",strtotime("-30 day"));
		}

		$arrTags=array('strStime'=>$iStartDate, 'strEtime'=>$iEndDate,'RoleID'=>$iRoleID); 
		Utility::assign($this->smarty,$arrTags);	
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/RoleMonthGiftDetailList.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	
	/**
	 * 月礼包分页明细
	 */
	function getPageMonthGiftPackage()
	{
		$iRoleID = Utility::isNumeric('RoleID',$_POST);
		$iStartDate = Utility::isNullOrEmpty('StartTime', $_POST) ? $_POST['StartTime'] : date("Y-m-d",strtotime("-90 day"));
		$iEndDate = Utility::isNullOrEmpty('EndTime', $_POST) ? $_POST['EndTime'] : date('Y-m-d');
		
		//当前分页
		$iCurPage = Utility::isNumeric('curPage',$_POST);
		$iCurPage = $iCurPage<=0 ? 1 : $iCurPage;		
		
		$arrParam['fields']='RoleID, SpID, Receive, CONVERT(VARCHAR(20),ReceiveTime,120) AS ReceiveTime, CONVERT(VARCHAR(10),GiftDate,120) AS GiftDate';
		$arrParam['tableName']=' T_MonthGiftPackage ';
		$arrParam['where']=" WHERE RoleID=$iRoleID AND DATEDIFF(d,'$iStartDate',GiftDate)>=0 AND DATEDIFF(d,'$iEndDate',GiftDate)<=0";
		$arrParam['order']=' GiftDate DESC';
		$arrParam['pagesize']=10;	

		$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['UserData'],$iRoleID);
		$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
		$Page=Utility::setPages($iCurPage,$iRecordsCount,$arrParam['pagesize']);
		$arrResult = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);	
		
		if(is_array($arrResult) && count($arrResult)>0){
			$i=0;
			foreach ($arrResult as $v){
				$arr = $this->objStagePropertyBLL->getGiftPackage($v['SpID']);	//礼包中的道具信息				
				if($arr){		
					$arrResult[$i]['spIDInfo'] = '';			
					foreach($arr as $v1){
						$arrResult[$i]['spIDInfo'] .=$v1['GoodsName'].' '; 
					}
				}
				$i++;
			}
		}
		
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'MonthDetailList'=>$arrResult); 
		Utility::assign($this->smarty,$arrTags);
	
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/RoleMonthGiftDetailPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	
	/**
	 * 用户游戏数据
	 */
	public function getUserGameData()
	{
		$Room = Utility::isNullOrEmpty('Room',$_POST);
		$RoleID = Utility::isNumeric('RoleID', $_POST);
		if($Room && $RoleID)
		{
			if($Room=='generic')//普通房间			
				$html = $this->genericRoomData($RoleID);		
			else				//寻宝乐园
				$html = $this->spRoomData($RoleID);
			echo $html;	
		}
	}
	/**
	 * 普通房间数据
	 */
	private function genericRoomData($RoleID)
	{
		//当前分页
		$iCurPage = Utility::isNumeric('curPage', $_POST) ? intval($_POST['curPage']) : 1;
		$iRoleID = Utility::isNumeric('RoleID', $_POST);
        $keyMap = array("iKindID"=>"KindID","iRoleID"=>"RoleID","iRoomType"=>"RoomType","iWinCount"=>"WinCount","iLostCount"=>"LostCount",
            "iDrawCount"=>"DrawCount","iFleeCount"=>"FleeCount","iTotalMoney"=>"TotalMoney","iTotalScore"=>"TotalScore",
            "iScore"=>"Score","iMoney"=>"Money","iWin"=>"Win","iFlee"=>"Flee","iTotalPage"=>"TotalPage","iCurPage"=>"CurPage","iGameCount"=>"GameCount",
            "iLastSignTime"=>"LastSignTime","iContinuousSign"=>"ContinuousSign","iPlayTimeLastDay"=>"PlayTimeLastDay","iPlayTimeDay"=>"PlayTimeDay",
            "iPlayCountLastDay"=>"PlayCountLastDay","iPlayCountDay"=>"PlayCountDay",
        );

		if($iRoleID && $iRoleID>0)
		{

            $arrParam['pagesize']=10;
            $ret = DSQueryRoleGameInfo($RoleID,$iCurPage,$arrParam['pagesize']);
            $arrResult = array();
            if(isset($ret['iGameCount'])&&$ret['iGameCount']>0)
                $arrResult = $ret['RoomInfoList'];
            Utility::arrListReplaceKey($arrResult,$keyMap);
            //var_dump($arrResult);
            //var_dump($arrResult);
			//查询条件
			/*
			$arrParam['fields']='D.KindID,D.RoleID,RoomType,WinCount,LostCount,DrawCount,FleeCount,TotalMoney,TotalScore,Score,[Money]';
			$arrParam['tableName']='T_UserGameData AS D INNER JOIN T_UserGameWealth AS W ON D.RoleID=W.RoleID AND D.KindID=W.KindID';
			$arrParam['where']=' WHERE D.RoleID='.$iRoleID.' AND (RoomType='.$this->arrConfig['RoomType'][0]['TypeID'].' OR RoomType='.$this->arrConfig['RoomType'][1]['TypeID'].')';	//RoomType:房间类型,1:积分房间,2:金币房间
			$arrParam['order']='D.KindID';*/
			
			//$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['UserData'],$iRoleID);
			//$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
			$iRecordsCount = $arrParam['pagesize']*($ret['iTotalPage']-1)+$ret['iGameCount'];
			$Page=Utility::setPages($iCurPage,$iRecordsCount,$arrParam['pagesize']);
			//$arrResult = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);
			if($arrResult)
			{
				for($i=0;$i<count($arrResult);$i++)
				{
                    //重写
					$arrKindInfo = $this->objMasterBLL->getGameKindInfo($arrResult[$i]['KindID']);
					if(is_array($arrKindInfo) && count($arrKindInfo)>0)
						$arrResult[$i]['KindName'] = $arrKindInfo['KindName'];
					else 
						$arrResult[$i]['KindName'] = '';
					$TotalTimes = $arrResult[$i]['WinCount']+$arrResult[$i]['LostCount']+$arrResult[$i]['DrawCount']+$arrResult[$i]['FleeCount'];
					$arrResult[$i]['Win'] = $TotalTimes?round($arrResult[$i]['WinCount'] / $TotalTimes*100,2):0;
					$arrResult[$i]['Flee'] = $TotalTimes?round($arrResult[$i]['FleeCount'] / $TotalTimes*100,2):0;
					$arrResult[$i]['LastSignTime'] = date('Y-m-d h:i:s',$arrResult[$i]['LastSignTime']);
					$arrResult[$i]['PlayTimeLastDay'] = Utility::dataformat($arrResult[$i]['PlayTimeLastDay']);
					$arrResult[$i]['PlayTimeDay'] = Utility::dataformat($arrResult[$i]['PlayTimeDay']);

                    $arrResult[$i]['Money'] =Utility::FormatMoney($arrResult[$i]['Money']);
                    $arrResult[$i]['TotalMoney'] =Utility::FormatMoney($arrResult[$i]['TotalMoney']);
				}
			}
			//var_dump($arrResult);
			$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'BillDetailList'=>$arrResult,'Date'=>date('Y-m-d')); 
			Utility::assign($this->smarty,$arrTags);
			$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/RoleGameDataPage.html');	
			$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
			return $html;
		}
	}
	/**
	 * 寻宝乐园数据
	 */
	private function spRoomData($RoleID)
	{		
		$arrParams['RoleID'] = $RoleID;
		$arrParams['StartTime'] = date('Y-m-d', strtotime('-1 day'));
		$arrParams['EndTime'] = date('Y-m-d');	
		setcookie($this->arrConfig['Cookies']['iRecordsCount'].$RoleID,'');//页面载入时重置Cookies(getPagerUserGameDataDetail设置的总记录数)
		$RoomType = $this->arrConfig['RoomType'][2]['TypeID'];//道具房间
		$strWhere = " AND (RoomType & $RoomType)>0";		
		//读取玩过的游戏种类
		$objUserDataBLL = new UserDataBLL($RoleID);
		$arrKindList = $objUserDataBLL->getUserGameData($strWhere);
		if(is_array($arrKindList) && count($arrKindList)>0)
		{
			//读取所有的游戏种类
			$arrAllKind = $this->objMasterBLL->getGameKindList(-1,-1);
			$iCount = 0;
			//只匹配玩过的游戏
			foreach ($arrKindList as $val1)
			{
				foreach ($arrAllKind as $val2)
				{
					if($val1['KindID']==$val2['KindID'])
					{
						$arrKindList[$iCount]['KindName'] = $val2['KindName'];						
						break;
					}
				}
				if(!isset($arrKindList[$iCount]['KindName'])) $arrKindList[$iCount]['KindName'] = '';
				$iCount++;
			}
		}
		$arrTags=array('skin'=>$this->arrConfig['skin'],'p'=>$arrParams,'KindList'=>$arrKindList);
		Utility::assign($this->smarty,$arrTags);				
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/RoleGameDataSp.html');			
		$html=str_replace("\r\n",'',$html);
		return $html;
	}
	/**
	 * 寻宝乐园房间成绩明细
	 */
	public function getPagerUserGameDataSp()
	{		
		$RoomType = $this->arrConfig['RoomType'][2]['TypeID'];//道具房间
		$iRoleID = Utility::isNumeric('RoleID', $_POST);
		$iKindID = Utility::isNumeric('KindID', $_POST);	
		$isFlag = Utility::isNumeric('sFlag', $_POST);	
		$arrParam['EndDate'] = Utility::isNullOrEmpty('EndTime', $_POST);
		$arrParam['StartDate'] = Utility::isNullOrEmpty('StartTime', $_POST);	
		$arrParam['RowIndex'] = Utility::isNumeric('LogsID',$_POST) ? $_POST['LogsID'] : 0;
		//当前分页
		$iCurPage = Utility::isNumeric('curPage',$_POST);
		$iCurPage = $iCurPage > 0 ? $iCurPage : 1;
		$arrParam['iCurPage'] = $iCurPage;
		$strCookRecordsCount = $this->arrConfig['Cookies']['iRecordsCount'].$iRoleID;
		//$strCookPrevParams1 = $this->arrConfig['Cookies']['PrevParams1'].$iRoleID;
		//$strCookPrevParams2 = $this->arrConfig['Cookies']['PrevParams2'].$iRoleID;
		
		//查询条件
		$strWhere = '';
		$arrParam['KindID'] = $iKindID;		
		if($arrParam['RowIndex']>0)
			$arrParam['KindID'] = $arrParam['RowIndex'];
		if($iKindID==0)
		{
			$arrParam['IsAll'] = 1;
			$strWhere = ' AND KindID>'.$arrParam['KindID'];
		}
		else 
		{
			$arrParam['IsAll'] = 0;
			$strWhere = ' AND KindID='.$iKindID;
		}
		//查询记录数使用的条件
		$arrParam['where'] = ' WHERE RoleID='.$iRoleID.' AND (TypeID & '.$RoomType.')>0'.$strWhere;
		//分页查询记录使用的条件
		$arrParam['sqlWhere'] = ' WHERE RoleID='.$iRoleID.' AND (TypeID & '.$RoomType.')>0';
		//每页显示数量
		$arrParam['pagesize'] = 10;
		$arrParam['TableName']='T_UserGameSpLogs_';
		$objDataChangeLogsBLL = new DataChangeLogsBLL($iRoleID);
		//总记录数
		if(!isset($_COOKIE[$strCookRecordsCount]))
		{
			$iRecordCount = $objDataChangeLogsBLL->getUserGameLogsRecordsCount($arrParam);
			setcookie($strCookRecordsCount,$iRecordCount);
		}
		else 
			$iRecordCount = $_COOKIE[$strCookRecordsCount];
		if($iRecordCount>0)
		{
			//总分页数
			$iPageAll = $iRecordCount==0 ? 1 : ceil($iRecordCount/$arrParam['pagesize']);
		
			//单击搜索清除缓存
			if($isFlag){
				$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['DataChangeLogs'],$iRoleID);
				$objCommonBLL-> delSimplePageMemcache('UserGameSpLogsPage', $iPageAll);
			}			
			//从cookies取上一页的状态位,上一页开始日期,上一页开始取的ID起始值
			//$PrevStartDate = $arrParam['EndDate'];
			//$PrevKindID=$arrParam['RowIndex'];
			//if(isset($_COOKIE[$strCookPrevParams1]))
			//	$PrevStartDate=$_COOKIE[$strCookPrevParams1];	
			//if(isset($_COOKIE[$strCookPrevParams2]))
			//	$PrevKindID=$_COOKIE[$strCookPrevParams2];		
			//分页读取记录
			$arrRes = $objDataChangeLogsBLL->getUserGameSpLogsPage($arrParam);
			$Page = null;
			if(is_array($arrRes) && count($arrRes)>0)
			{
				$k = 0;
				$arrKindList = $this->objMasterBLL->getGameKindList(-1,-1);
				foreach ($arrRes as $val)
				{
					$arrRes[$k]['Win'] = $val['TotalCount']?round($val['WinCount']/$val['TotalCount']*100,2):0;
					$arrRes[$k]['Flee'] = $val['TotalCount']?round($val['FleeCount']/$val['TotalCount']*100,2):0;
					$arrRes[$k]['Dtime'] = date("Ymd", strtotime($val['PlayTime']));
					$arrRes[$k]['KindName'] = '';
					foreach ($arrKindList as $val2)
					{
						if($val['KindID']==$val2['KindID'])
						{
							$arrRes[$k]['KindName'] = $val2['KindName'];
							break;
						}
					}
					$k++;
				}
				$iNum = count($arrRes);
				//$tmpPrevKindID=0;
				//$tmpPrevStartDate = date('Y-m-d',strtotime($arrRes[0]['PlayTime']));		
				//记录上一页的状态位
				//if($arrParam['RowIndex']>0)
				//	$tmpPrevKindID=$arrRes[0]['KindID']-1;			
				//setcookie($strCookPrevParams1,$tmpPrevStartDate,0,'/');
				//setcookie($strCookPrevParams2,$tmpPrevKindID,0,'/');
				//记录下一页的状态位
				$NextStartDate=$iKindID==0 ? $arrRes[$iNum-1]['PlayTime'] : date('Y-m-d',strtotime($arrRes[$iNum-1]['PlayTime'].' -1 days'));;
				$NextKindID = $arrRes[$iNum-1]['KindID'];
				$Page=Utility::setSimplePages($iPageAll,$iCurPage,$NextKindID,$NextStartDate);
				$arrTags=array('skin'=>$this->arrConfig['skin'], 'RoleID'=>$iRoleID, 'Page'=>$Page,'GameDetailList'=>$arrRes,'KindID'=>$iKindID); 
				Utility::assign($this->smarty,$arrTags);				
				$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/RoleGameDataSpPage.html');			
				$html=str_replace("\r\n",'',$html);
				echo $html;
			}			
		}else{
			echo '<div style="margin-top:10px; text-align:center;">很抱歉，没有您要查询的信息~</div>';
		}	
	}
	
	/**
	 * 寻宝房间查看当天的战斗记录
	 */
	function getUserGameDataSpDetail()
	{
		$iRoleID = Utility::isNumeric('RoleID', $_REQUEST);
		$iKindID = Utility::isNumeric('KindID', $_REQUEST);
		$strKName = Utility::isNullOrEmpty('KindName', $_REQUEST);
		$strDtime = Utility::isNullOrEmpty('dTime', $_REQUEST);		
		
		$arrTags=array('skin'=>$this->arrConfig['skin'], 'RoleID'=>$iRoleID, 'KindName'=>$strKName, 'KindID'=>$iKindID, 'Dtime'=>$strDtime);
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/Service/RoleGameDataSpDetail.html');
	}
	
	/**
	 * 寻宝房间查看当天的战斗记录分页
	 */
	function getUserGameDataSpDetailPage()
	{
		$iRoleID = Utility::isNumeric('RoleID', $_REQUEST);
		$iKindID = Utility::isNumeric('KindID', $_REQUEST);
		$strKName = Utility::isNullOrEmpty('KindName', $_REQUEST);
		$strDtime = Utility::isNullOrEmpty('dTime', $_REQUEST);		
		//当前分页
		$iCurPage = Utility::isNumeric('curPage', $_POST) ? intval($_POST['curPage']) : 1;
		$iCurPage = $iCurPage<=0 ? 1 : $iCurPage;		
		
		$arrParam['fields']='LogsID, SerialNumber, KindID, CONVERT(VARCHAR(20),AddTime,120) AS AddTime, PlayStatus,Intro';
		$arrParam['tableName']="T_UserGameSpLogs_$strDtime";
		$arrParam['where']=" WHERE RoleID=".$iRoleID." AND KindID=".$iKindID;
		$arrParam['order']=' LogsID DESC';
		$arrParam['pagesize']=15;
		
		$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['DataChangeLogs'],$iRoleID);
		$iRecordsCount = $objCommonBLL->getRecordsCountSelect($arrParam);
		$Page=Utility::setPages($iCurPage,$iRecordsCount,$arrParam['pagesize']);
		$arrResult = $objCommonBLL->getPageListSelect($arrParam,$Page['CurPage']);
		
		if($arrResult){
			$i=0;
			$arrPlayTips = array(0=>'赢', 1=>'输', 2=>'和',3 =>'逃');
			foreach($arrResult as $v){
				$arrResult[$i]['Intro'] = Utility::gb2312ToUtf8($v['Intro']);
				$arrResult[$i]['playTips'] = $arrPlayTips[$v['PlayStatus']];
				$arrResult[$i]['Dtime'] = date("Ymd", strtotime($v['AddTime']));
				$i++;
			}
		}		
		
		$arrTags=array('skin'=>$this->arrConfig['skin'], 'RoleID'=>$iRoleID, 'KindName'=>$strKName, 'Page'=>$Page, 'result'=>$arrResult);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/RoleGameDataSpDetailPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	
	/**
	 * 获取寻宝房间同桌玩家游戏信息
	 */
	function getSpUserGameAllPeopleInfo()
	{
		$iRoleID = Utility::isNumeric('RoleID', $_POST);
		$iSerialNum = Utility::isNumeric('serialNum', $_POST);	//游戏流水号
		$iDataNum = Utility::isNumeric('dataNum', $_POST);	//数据库编号
        $iLogTime = Utility::isNullOrEmpty('LogTime',$_POST);//日志时间
		$strKindName = Utility::isNullOrEmpty("KindName", $_POST); //游戏名称
		//返回游戏其他玩家的RoleID
		$objDataChangeLogsBLL = new DataChangeLogsBLL($iRoleID);
		$arrReturn = $objDataChangeLogsBLL->getSelectSpGameResult($iSerialNum, $iDataNum);

		$arrIntroRoleID = explode(',', $arrReturn[0]['Intro']);	
		array_pop($arrIntroRoleID);
		array_push($arrIntroRoleID, $iRoleID);//所有游戏参与者
		
		$this->iTmpRoleID = $iRoleID;	//临时RoleID
		$this->arrIntroRoleID = $arrIntroRoleID;
		
		//返回同桌游戏记录
		$arrRes = array();
		$arrResult = array();
		while(1){
			$arrRes = $this->getSelectGameResult($this->iTmpRoleID, $this->arrIntroRoleID,$iSerialNum, $iDataNum, 1,$iLogTime);
			$arrResult = array_merge($arrResult, $arrRes['result']);
			if($arrRes['newRoleID']){
				$this->iTmpRoleID = $arrRes['newRoleID'][0];
				$this->arrIntroRoleID = $arrRes['newRoleID'];	//同桌其他玩家RoleID
			}else
				break;			
		}
		
		if($arrResult){
			$i=0;
			$arrPlayStatus = array(0=>'赢', 1=>'输', 2=>'和',3 =>'逃');
			foreach($arrResult as $v){			
				$objUserBLL = new UserBLL($v['RoleID']);
				$userInfo = $objUserBLL->getRoleInfo();
				$arrResult[$i]['LoginName'] = $userInfo['LoginName'];	
				$arrResult[$i]['PlayStatus'] = $arrPlayStatus[$v['PlayStatus']];
				$arrResult[$i]['LoginID'] = $userInfo['LoginID'];
				$i++;
			}
		}
					
		$arrTags=array('GameInfoList'=>$arrResult,'KindName'=>$strKindName); 
		Utility::assign($this->smarty,$arrTags);			
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/RoleUserGameAllPeopleInfo.html');			
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	
	/**
	 * 普通房间成绩明细
	 */
	public function getUserGameDataDetail()
	{
		$RoomType1 = $this->arrConfig['RoomType'][0]['TypeID'];//积分房间
		$RoomType2 = $this->arrConfig['RoomType'][1]['TypeID'];//金币房间
		$strWhere = " AND (RoomType = $RoomType1 OR RoomType = $RoomType2)";
		$arrParams['RoomType'] = Utility::isNumeric('RoomType', $_REQUEST);//房间类型
		$arrParams['RoleID'] = Utility::isNumeric('RoleID', $_REQUEST);
		$arrParams['KindID'] = Utility::isNumeric('KindID', $_REQUEST);
		$arrParams['StartTime'] = date('Y-m-d', strtotime('-9 day'));
		$arrParams['EndTime'] = date('Y-m-d');	
		setcookie($this->arrConfig['Cookies']['iRecordsCount'].$arrParams['RoleID'],'');//页面载入时重置Cookies(getPagerUserGameDataDetail设置的总记录数)
			
		//读取玩过的游戏种类
		//$objUserDataBLL = new UserDataBLL($arrParams['RoleID']);
		//$arrKindList = $objUserDataBLL->getUserGameData($strWhere);
      /* 
        $arrKindList = getRoleGameInfo($arrParams['RoleID']);
		if(is_array($arrKindList) && count($arrKindList)>0)
		{
			//读取所有的游戏种类
			$arrAllKind = $this->objMasterBLL->getGameKindList(-1,-1);
			$iCount = 0;
			//只匹配玩过的游戏
			foreach ($arrKindList as $val1)
			{
				foreach ($arrAllKind as $val2)
				{
					if($val1['KindID']==$val2['KindID'])
					{
						$arrKindList[$iCount]['KindName'] = $val2['KindName'];						
						break;
					}
				}
				if(!isset($arrKindList[$iCount]['KindName'])) $arrKindList[$iCount]['KindName'] = '';
				$iCount++;
			}
		}		
		 */

		$roomType = $this->arrConfig['RoomType'];
		$arrKindList = $this->objMasterBLL->getGameKindList(-1,-1);
		$arrTags=array('skin'=>$this->arrConfig['skin'],'p'=>$arrParams,'KindList'=>$arrKindList,'RoomType'=>$roomType);
		Utility::assign($this->smarty,$arrTags);				
		$this->smarty->display($this->arrConfig['skin'].'/Service/RoleGameDataDetail.html');			
	}
	/**
	 * 普通房间成绩明细分页
	 */
	public function getPagerUserGameDataDetail()
	{
		$iRoleID = Utility::isNumeric('RoleID', $_POST);
		$iKindID = Utility::isNumeric('KindID', $_POST);
		$RoomType = Utility::isNumeric('RoomType', $_POST);//房间类型
		$arrParam['EndDate'] = Utility::isNullOrEmpty('EndTime', $_POST);
		$arrParam['StartDate'] = Utility::isNullOrEmpty('StartTime', $_POST);
		$isFlag = Utility::isNumeric('sFlag', $_POST);		
		//当前分页
		$iCurPage = Utility::isNumeric('curPage',$_POST);
		$arrParam['Page'] = $iCurPage > 0 ? $iCurPage : 1;
		$strCookRecordsCount = $this->arrConfig['Cookies']['iRecordsCount'].$iRoleID;
//		$strCookPrevParams1 = $this->arrConfig['Cookies']['PrevParams1'].$iRoleID;
        $arrParam['fields'] = " ServerID,RoleID,RoleName,LogType,KindID,RoomType,Money,Score,WinCount,LostCount,FleeCount,DrawCount,CONVERT(VARCHAR(100),AddTime,120) as AddTime";
		//查询条件
		$arrParam['where'] = ' WHERE RoleID='.$iRoleID.' AND KindID='.$iKindID.' AND RoomType = '.$RoomType;
		//每页显示数量
		$arrParam['PageSize'] = 10;
		$arrParam['tableName']='T_UserGameLogs_';
        $arrParam['order'] = 'AddTime desc';
		$objDataChangeLogsBLL = new DataChangeLogsBLL($iRoleID);
		//总记录数
		if(!isset($_COOKIE[$strCookRecordsCount]))
		{
			$iRecordCount = $objDataChangeLogsBLL->getRecordsCount($arrParam);
			setcookie($strCookRecordsCount,$iRecordCount);
		}
		else 
			$iRecordCount = $_COOKIE[$strCookRecordsCount];

		if($iRecordCount>0)
		{
			//总分页数
			$iPageAll = $iRecordCount==0 ? 1 : ceil($iRecordCount/$arrParam['PageSize']);
			//单击搜索清除缓存
			/*if($isFlag){
				$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['DataChangeLogs'],$iRoleID);
				$objCommonBLL-> delSimplePageMemcache('UserGameLogsPage', $iPageAll);
			}*/
//			//从cookies取上一页的状态位,上一页开始日期,上一页开始取的ID起始值
//			$PrevStartDate = $arrParam['EndDate'];
//			if(isset($_COOKIE[$strCookPrevParams1]))
//				$PrevStartDate=$_COOKIE[$strCookPrevParams1];			
			//分页读取记录
			$arrRes = $objDataChangeLogsBLL->getPageList($arrParam);

			if(is_array($arrRes) && count($arrRes)>0)
			{
				$iNum = count($arrRes);
				//$arrRes[0]['PrevStartDate'] = date('Y-m-d',strtotime($arrRes[0]['PlayTime']));
				//$arrRes[0]['NextStartDate'] = date('Y-m-d',strtotime($arrRes[$iNum-1]['PlayTime'].' -1 days'));
				//记录上一页的状态位
				//setcookie($strCookPrevParams1.$iCurPage,$arrRes[0]['PrevStartDate'],0,'/');
				//记录下一页的状态位
				$NextStartDate=$arrParam["EndDate"];
				$Page=Utility::setSimplePages($iPageAll,$arrParam['Page'],0,$NextStartDate);
                $_RoomType = Utility::array_column($this->arrConfig['RoomType'],'TypeName','TypeID');
                $_ChangeType = array(0=>'赢',1=>'输',2=>'和','3'=>'逃');
                foreach($arrRes as $key => $val){
                    $arrRes[$key]['RoomTypeTip'] = $_RoomType[$val['RoomType']];
                    $arrRes[$key]['Money'] = Utility::FormatMoney($val['Money']);
                    //$arrRes[$key]['ChangeTypeTip'] = $_ChangeType[$val['ChangeType']];
                }
                //var_dump($arrRes);
			}
			$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'GameDetailList'=>$arrRes,'KindID'=>$iKindID); 
			Utility::assign($this->smarty,$arrTags);				
			$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/RoleGameDataDetailPage.html');			
			$html=str_replace("\r\n",'',$html);
			echo $html;
		}else{
			echo '<div style="margin-top:10px; text-align:center;">很抱歉，没有您要查询的信息~</div>';
		}
	}
	/**
	 * 普通房间游戏记录明细
	 */
	public function getUserGameDetail()
	{
		$RoomType1 = $this->arrConfig['RoomType'][0]['TypeID'];//积分房间
		$RoomType2 = $this->arrConfig['RoomType'][1]['TypeID'];//金币房间
		$strWhere = " AND ((RoomType & $RoomType1)>0 OR (RoomType & $RoomType2)>0)";
		$arrParams['RoomType'] = Utility::isNumeric('RoomType', $_REQUEST);//房间类型
		$arrParams['RoleID'] = Utility::isNumeric('RoleID', $_REQUEST);
		$arrParams['KindID'] = Utility::isNumeric('KindID', $_REQUEST);
		$arrParams['StartTime'] = date('Y-m-d', strtotime('-90 day'));
		$arrParams['EndTime'] = date('Y-m-d');
		setcookie($this->arrConfig['Cookies']['iRecordsCount'].$arrParams['RoleID'],'');//页面载入时重置Cookies(getPagerUserGameDataDetail设置的总记录数)
		
		//读取玩过的游戏种类
		//$objUserDataBLL = new UserDataBLL($arrParams['RoleID']);
		//$arrKindList = $objUserDataBLL->getUserGameData($strWhere);
      /*   $arrKindList = getRoleGameInfo($arrParams['RoleID']);
		if(is_array($arrKindList) && count($arrKindList)>0)
		{
			//读取所有的游戏种类
			$arrAllKind = $this->objMasterBLL->getGameKindList(-1,-1);
			$iCount = 0;
			//只匹配玩过的游戏
			foreach ($arrKindList as $val1)
			{
				foreach ($arrAllKind as $val2)
				{
					if($val1['KindID']==$val2['KindID'])
					{
						$arrKindList[$iCount]['KindName'] = $val2['KindName'];						
						break;
					}
				}
				if(!isset($arrKindList[$iCount]['KindName'])) $arrKindList[$iCount]['KindName'] = '';
				$iCount++;
			}
		}		 */

		$roomType = $this->arrConfig['RoomType'];
		$arrKindList = $this->objMasterBLL->getGameKindList(-1,-1);
		$arrTags=array('skin'=>$this->arrConfig['skin'],'p'=>$arrParams,'KindList'=>$arrKindList,'RoomType'=>$roomType);
		Utility::assign($this->smarty,$arrTags);	
		$this->smarty->display($this->arrConfig['skin'].'/Service/RoleGameDetail.html');			
	}
	/**
	 * 普通房间游戏记录明细分页
	 */
	public function getPagerUserGameDetail()
	{
		$strWhere = '';
		$Page = null;
		$iRoleID = Utility::isNumeric('RoleID', $_POST);
		$iKindID = Utility::isNumeric('KindID', $_POST);
		$strKindName = Utility::isNullOrEmpty('KindName', $_POST);
		$RoomType = Utility::isNumeric('RoomType', $_POST);//房间类型
		$arrParam['EndDate'] = Utility::isNullOrEmpty('EndTime', $_POST);
		$arrParam['StartDate'] = Utility::isNullOrEmpty('StartTime', $_POST);
		$PlayResult = Utility::isNumeric('PlayResult', $_POST);
		$isFlag = Utility::isNumeric('sFlag', $_POST);			
		
		$Hour = Utility::isNumeric('Hour', $_POST);	
		$Minute = Utility::isNumeric('Minute', $_POST); 
		//$strWhere = '';	
		//筛选条件:全部,赢,输,和,逃
		if($PlayResult!=-1) $strWhere .= ' AND ChangeType='.$PlayResult;
		
		if($Minute && $Minute>0)
		{
			$date = $arrParam['StartDate']." $Hour:$Minute:00";
			$strWhere .= " AND DATEDIFF(mi,'$date',AddTime)=0";
		}
		elseif($Hour && $Hour>0)
		{
			$date = $arrParam['StartDate']." $Hour:00:00";
			$strWhere .= " AND DATEDIFF(hh,'$date',AddTime)=0";
		}
		else 
		{
			if($arrParam['StartDate']) $strWhere .= " AND DATEDIFF(d,AddTime,'".$arrParam['StartDate']."')<=0";
			if($arrParam['EndDate']) $strWhere .= " AND DATEDIFF(d,AddTime,'".$arrParam['EndDate']."')>=0";
		}
		
		//echo $strWhere;exit;
		//当前页
		$iCurPage = Utility::isNumeric('curPage',$_POST);
		$arrParam['Page'] = $iCurPage > 0 ? $iCurPage : 1;
		$strCookRecordsCount = $this->arrConfig['Cookies']['iRecordsCount'].$iRoleID;
//		$strCookPrevParams1 = $this->arrConfig['Cookies']['PrevParams1'].$iRoleID;
//		$strCookPrevParams2 = $this->arrConfig['Cookies']['PrevParams2'].$iRoleID;
		//从第几条开始读取
		//$arrParam['RowIndex'] = Utility::isNumeric('LogsID',$_POST) ? $_POST['LogsID'] : 0;
        $arrParam['tableName'] = 'T_UserGameChangeLogs_';
		//查询字段
		$arrParam['fields'] = "  ServerID,RoleID,RoleName,LogType,SerialNumber,KindID,RoomType,TableID,ChangeType,Money,LastMoney,Score,LastScore,CONVERT(VARCHAR(100),AddTime,120) as AddTime  ";
		//查询条件
		$arrParam['where'] = ' WHERE RoleID='.$iRoleID.' AND KindID='.$iKindID.' AND (RoomType & '.$RoomType.')>0'.$strWhere;
		//查询排序
		$arrParam['order'] = " AddTime DESC";
		//每页显示数量
		$arrParam['PageSize'] = 10;
		$objDataChangeLogsBLL = new DataChangeLogsBLL($iRoleID);
		//总记录数
		if(!isset($_COOKIE[$strCookRecordsCount]))
		{
			$iRecordCount = $objDataChangeLogsBLL->getRecordsCount($arrParam);
			setcookie($strCookRecordsCount,$iRecordCount);
		}
		else
			$iRecordCount = $_COOKIE[$strCookRecordsCount];
		if($iRecordCount>0)
		{
			//总分页数
			$iPageAll = $iRecordCount==0 ? 1 : Utility::getPageNum($iRecordCount,$arrParam['PageSize']);
			$arrParam['memName'] = 'PagerUserGameDetail';
			//单击搜索清除缓存
			if($isFlag){
				$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['DataChangeLogs'],$iRoleID);
				$objCommonBLL->delSimplePageMemcache($arrParam['memName'], $iPageAll);
			}			
//			//从cookies取上一页的状态位,上一页开始日期,上一页开始取的ID起始值
//			$PrevStartDate = $arrParam['EndDate'];
//			$PrevLogsID=$arrParam['RowIndex'];
//			if(isset($_COOKIE[$strCookPrevParams1]))
//				$PrevStartDate=$_COOKIE[$strCookPrevParams1];
//			if(isset($_COOKIE[$strCookPrevParams2]))
//				$PrevLogsID=$_COOKIE[$strCookPrevParams2];
			//分页读取记录
			$arrRes = $objDataChangeLogsBLL->getPageList($arrParam,0);
            if(is_array($arrRes) && count($arrRes)>0)
            {
                $iNum = count($arrRes);
                //$arrRes[0]['PrevStartDate'] = date('Y-m-d',strtotime($arrRes[0]['PlayTime']));
                //$arrRes[0]['NextStartDate'] = date('Y-m-d',strtotime($arrRes[$iNum-1]['PlayTime'].' -1 days'));
                //记录上一页的状态位
                //setcookie($strCookPrevParams1.$iCurPage,$arrRes[0]['PrevStartDate'],0,'/');
                //记录下一页的状态位
                $NextStartDate=$arrParam["EndDate"];
                $Page=Utility::setSimplePages($iPageAll,$arrParam['Page'],0,$NextStartDate);
                $ChangeType = array(0=>"胜",1=>"负",2=>"和",3 =>"跑");
                $_RoomType = Utility::array_column($this->arrConfig['RoomType'],'TypeName','TypeID');
                foreach($arrRes as $key => $val){
                    $arrRes[$key]['ChangeTypeTip'] = $ChangeType[$val['ChangeType']];
                    $arrRes[$key]['RoomTypeTip'] = $_RoomType[$val['RoomType']];

                    $arrRes[$key]['Money'] =Utility::FormatMoney($val['Money']);
                    $arrRes[$key]['LastMoney'] = Utility::FormatMoney($val['LastMoney']);
                }
            }
			/*if(is_array($arrRes) && count($arrRes)>0)
			{
				$i=0;	
				foreach($arrRes as $val)
				{			
					$arrRes[$i]['HappyBean'] = $val['LastMoney']-$val['Money'];
					$arrRes[$i]['Integral'] = $val['LastScore']-$val['Score'];
					$CurDate = date('Y-m-d',strtotime($val['AddTime']));
					$arrRes[$i]['TableNumber'] = date('Ymd',strtotime($val['AddTime']));
//					//读取上一页的状态位,上一页开始日期和LogsID
//					if(!isset($arrRes[0]['PrevStartDate']) && !isset($arrRes[0]['PrevLogsID']))
//					{
//						$arrRes[0]['PrevStartDate']=$CurDate;
//						if($arrParam['RowIndex']==0)
//							$arrRes[0]['PrevLogsID']=0;
//						else
//							$arrRes[0]['PrevLogsID']=$val['LogsID']+1;
//					}
					//读取下一页的状态位,下一页开始日期和LogsID
					if(!isset($arrRes[0]['NextStartDate']) || (strtotime($arrRes[0]['NextStartDate'])-strtotime($CurDate))>=0)
					{
						$arrRes[0]['NextStartDate']=$CurDate;
						$arrRes[0]['NextLogsID']=$val['LogsID'];
					}
					$i++;	
				}
//				//记录上一页的状态位
//				setcookie($strCookPrevParams1.$iCurPage,$arrRes[0]['PrevStartDate'],0,'/');
//				setcookie($strCookPrevParams2.$iCurPage,$arrRes[0]['PrevLogsID'],0,'/');
	
				$NextStartDate=$arrRes[0]['NextStartDate'];
				$NextLogsID=$arrRes[0]['NextLogsID'];
			
				$Page=Utility::setSimplePages($iPageAll,$arrParam['iCurPage'],$NextLogsID,$NextStartDate);
			}*/
			$arrTags=array('skin'=>$this->arrConfig['skin'],'KindName'=>$strKindName,'Page'=>$Page,'GameDetailList'=>$arrRes, 'RoleID'=>$iRoleID,"TableNum"=>date('Ymd',strtotime($arrParam['StartDate'])));
			Utility::assign($this->smarty,$arrTags);			
			$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/RoleGameDetailPage.html');			
			$html=str_replace("\r\n",'',$html);
			echo $html;
		}else{
			echo '<div style="margin-top:10px; text-align:center;">很抱歉，没有您要查询的信息~</div>';
		}
	}
	
	/**
	 * 获取普通房间当局游戏其他玩家的游戏记录
	 */
	function getUserGameAllPeopleInfo()
	{
		$iRoleID = Utility::isNumeric('RoleID', $_POST);
		$iSerialNum = Utility::isNumeric('serialNum', $_POST);	//游戏流水号
		$iDataNum = Utility::isNumeric('dataNum', $_POST);	//数据库编号
		$iServerID = Utility::isNumeric('ServerID',$_POST);
        $strKindName = Utility::isNullOrEmpty("KindName", $_POST); //游戏名称
        $iLogTime = Utility::isNullOrEmpty('LogTime',$_POST);//日志时间
		$this->iTmpRoleID = $iRoleID;	//临时RoleID
		//返回同桌游戏记录
		$arrRes = array();
		$arrResult = array();
		//while(1){
			$arrRes = $this->getSelectGameResult($this->iTmpRoleID, $this->arrIntroRoleID,$iSerialNum, $iDataNum, $this->iFlag,$iServerID,$iLogTime);
			$arrResult = array_merge($arrResult, $arrRes['result']);
		//	if($arrRes['newRoleID']){
		//		$this->iTmpRoleID = $arrRes['newRoleID'][0];
		//		$this->arrIntroRoleID = $arrRes['newRoleID'];	//同桌其他玩家RoleID
		//		$this->iFlag = 1;			//是否是所有游戏玩家标识 0：是   1：不是
		//	}else
		//		break;			
		//}
		
		if($arrResult){
			$i=0;
			$arrPlayStatus = array(0=>'赢', 1=>'输', 2=>'和',3 =>'逃');
			foreach($arrResult as $v){			
				/*$objUserBLL = new UserBLL($v['RoleID']);
				$userInfo = $objUserBLL->getRoleInfo();*/
                //$userInfo = getUserBaseInfo($v['RoleID']);
				//$arrResult[$i]['LoginName'] = $userInfo['LoginName'];
				$arrResult[$i]['ChangeTypeTip'] = $arrPlayStatus[$v['ChangeType']];
				$arrResult[$i]['LoginID'] = $arrResult[$i]['RoleID'];
                $arrResult[$i]['LastMoney'] = Utility::FormatMoney($arrResult[$i]['LastMoney']);
                $arrResult[$i]['Money'] = Utility::FormatMoney($arrResult[$i]['Money']);
				$arrResult[$i]['RoleName'] = Utility::gb2312ToUtf8($arrResult[$i]['RoleName']);
				$i++;
			}
		}
					
		$arrTags=array('GameInfoList'=>$arrResult,'KindName'=>$strKindName);
        //var_dump($arrResult);
		Utility::assign($this->smarty,$arrTags);			
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/RoleUserGameAllPeopleInfo.html');			
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	
	/**
	 * 返回同桌游戏玩家记录
	 * @param $iRoleID
	 * @param $arrIntroRoleID
	 * @param $SerialNumber	流水号
	 * @param $DateTime	数据库编号
	 */
	function getSelectGameResult($iRoleID, $arrIntroRoleID, $SerialNumber, $DateTime, $flag=0,$iServerID,$LogTime)
	{
		$arrResult = array();
		$arrNewRoleID = array();
		$objDataChangeLogsBLL = new DataChangeLogsBLL($iRoleID);
		$arrResult = $objDataChangeLogsBLL->getSelectGameResult($SerialNumber, $DateTime,$iServerID,$LogTime);
		
		/*if($arrResult){
			$arrRoleIDs = array();
			foreach($arrResult as $v){
				array_push($arrRoleIDs, $v['RoleID']);
			}
			if(!$flag){
				$arrIntroRoleID = explode(',', $arrResult[0]['Intro']);	
				array_pop($arrIntroRoleID);
				array_push($arrIntroRoleID, $arrResult[0]['RoleID']);//所有游戏参与者
			}
			$aRoleID = array_diff($arrIntroRoleID, $arrRoleIDs);
			if($aRoleID){
				$arrNewRoleID = array_merge($aRoleID);				
			}
		}*/
		return array('result'=>$arrResult);//return array('result'=>$arrResult, 'newRoleID'=>array_filter($arrNewRoleID));
	}
	
	/**
	 * 加载银行资料页面
	 */
	function getBankInfoDetail()
	{
		$iRoleID = Utility::isNumeric('RoleID', $_POST);
        $userBankInfo = DSQueryRoleBankInfo($iRoleID);
        //var_dump($userBankInfo);
        $keyMap = array("iGameWealth"=>"GameWealth","iFreeze"=>"Freeze","iMoney"=>"Money","iAddTime"=>"AddTime","iFirstRechargeTime"=>"FirstRechargeTime","iTotalTime"=>"TotalTime"
                        ,"iSuperPlayerLevel"=>"SuperPlayerLevel","iChargeCount"=>"ChargeCount","iTotalChargeMoney"=>"TotalChargeMoney","iTotalLockMoney"=>"TotalLockMoney"
                        ,"iBankDealBackCanGetCount"=>"BankDealBackCanGetCount","iBankDealBackMoney"=>"BankDealBackMoney","iBankTotalGetBackMoney"=>"BankTotalGetBackMoney");
        $userBankInfo = Utility::arrReplaceKey($userBankInfo,$keyMap);
        $userBankInfo['FirstRechargeTime'] = date('Y-m-d h:i:s',$userBankInfo['FirstRechargeTime']);
        $userBankInfo['AddTime'] = date('Y-m-d h:i:s',$userBankInfo['AddTime']);
        //var_dump($userBankInfo);
		//$objUserDataBLL = new UserDataBLL($iRoleID);
		//银行资料信息显示
		//$userBankInfo = $objUserDataBLL->getUserBankMoney();
		//查询背包中金币
		//$myKnapsack = $objUserDataBLL->getMyKnapsackMoney();
		//游戏中的金币
		$arrHappyBeanMoney = array('bank'=>$userBankInfo['Money'],'game'=>$userBankInfo['GameWealth']);
		//取得被冻结的财富数量
		//$lockMoney = $this->objSystemBLL->getCaseOperateUser($iRoleID, 1);
		$lockMoney = [];
		$beLockedMoney = 0;
		if($lockMoney && count($lockMoney)>0){
			$i=0;			
			foreach ($lockMoney as $v){
				$beLockedMoney += $v['iNumber']+$v['ReturnNumber'];
				$i++;
			}
		}
		$arrTags=array('ChangeType'=>$this->arrConfig['BankChangeType'],'M'=>$arrHappyBeanMoney, 'userBankInfo'=>$userBankInfo, 'lockMoney'=>$beLockedMoney,'nowTime'=>date("Y-m-d H:i:s",time()),'FromDate'=>date("Y-m-d",strtotime('-30 days')),'ToDate'=>date("Y-m-d",time()));
		Utility::assign($this->smarty,$arrTags);				
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/RoleBankInfo.html');	
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	
	/**
	 * 银行资料——转账查询记录
	 */
	function getPageUserTransferRecords()
	{
		$iRoleID = Utility::isNumeric('RoleID', $_POST);
		$arrRes = $this->getUserTransferRecords(10,0);
		
		$arrTags=array('skin'=>$this->arrConfig['skin'],'roleID'=>$iRoleID,'Page'=>$arrRes['Page'],'BankDetailList'=>$arrRes['Result']);
		Utility::assign($this->smarty,$arrTags);				
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/RoleTransferRecordsPage.html');	
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	
	/**
	 * 银行资料——转账记录分页
	 * @param $iRoleID
	 * @param $pagesize
	 * @param $delFlag 下载删缓存标识
	 */
	function getUserTransferRecords($pagesize, $delFlag)
	{
		$arrRes = null;
		$Page = null;
		$strWhere = '';
		$iRoleID = Utility::isNumeric('RoleID', $_POST);
		$iDCFlag = Utility::isNumeric('DCFlag', $_POST);//-1全部 1 存入 2 取出

		$iTransType = Utility::isNumeric('TransType', $_POST);//类型
		$arrParam['StartDate'] = Utility::isNullOrEmpty('Etime', $_POST);
		$arrParam['EndDate'] = Utility::isNullOrEmpty('Etime', $_POST);



        $strWhere = $strWhere." where RoleID = {$iRoleID}";

		//var_dump($_POST);

		//当前分页
		$iCurPage = Utility::isNumeric('curPage',$_POST);
		$arrParam['Page'] = $iCurPage > 0 ? $iCurPage : 1;
        $arrParam['PageSize'] = $pagesize;

        $arrParam['tableName'] = "T_BankWealthChangeLogs_";

        $ChangeType = $this->arrConfig['BankChangeType'];

        if($iDCFlag != -1){
            $strWhere = $strWhere. " AND ". "( ";//构造条件
            if($iDCFlag == 2){
                foreach($ChangeType as $val) if($val['type'] == 0){//0代表扣除
                    $strWhere .= " ChangeType = {$val['value']} OR";
                }

            }else{
                //只查看增加的
                foreach($ChangeType as $val) if($val['type'] == 1){//0代表增加
                    $strWhere .= " ChangeType = {$val['value']} OR";
                }
            }
            $strWhere = substr($strWhere,0, -2);$strWhere .= ')';
        }
        if($iTransType != -1){
            $strWhere = $strWhere ." AND ChangeType = {$iTransType}"; //具体某种交易类型
        }

        $arrParam['where'] = $strWhere;
        $arrParam['fields'] = " ServerID,RoleID,RoleName,LogType,ChangeMoney,Balance,ChangeType,ClientIP,MachineSerial,PayID,PayName,TargetID,TargetName,AddTime,Description";
        $arrParam['order'] = "AddTime DESC";
        //var_dump($arrParam);//exit;
        $objDataChangeLogsBLL = new DataChangeLogsBLL($iRoleID);
        $totCount = $objDataChangeLogsBLL->getRecordsCount($arrParam);
        $arrRes = $objDataChangeLogsBLL->getPageList($arrParam,0);
        $_ChangeType = Utility::array_column($ChangeType,'name','value');
        if(!empty($arrRes)) {
            foreach ($arrRes as $key => $val) {
                $arrRes[$key]['ChangeTypeTips'] = $_ChangeType[$val['ChangeType']];
                $arrRes[$key]['DcFlag'] = ($val['ChangeType'] <= 3 || $val['ChangeType'] == 12)? 0 : 1;
                $arrRes[$key]['DcFlagTips'] = $arrRes[$key]['DcFlag']? '收入':'支出';

                $arrRes[$key]['ChangeMoney'] = Utility::FormatMoney($val['ChangeMoney']);
                $arrRes[$key]['Balance'] = Utility::FormatMoney($val['Balance']);
                //转码
                $arrRes[$key]['RoleName'] = Utility::gb2312ToUtf8($val['RoleName']);
                $arrRes[$key]['TargetName'] = Utility::gb2312ToUtf8($val['TargetName']);
                $arrRes[$key]['PayName'] = Utility::gb2312ToUtf8($val['PayName']);
                $arrRes[$key]['Description'] = Utility::gb2312ToUtf8($val['Description']);
            }
        }
        $Page = Utility::setSimplePages(Utility::getPageNum($totCount,$pagesize) ,$arrParam['Page'],2,$arrParam['EndDate']);
		return array('Result'=>$arrRes,'Page'=>$Page);
	}
	
	/**
	 * 银行转账记录分页导出execl
	 */
	public function downloadUserBankRecordsExecl()
	{			
		$CurPage = 0;	
		$TotalPage = 1;	
		$Http = '';
		//读取记录
		$arrResult = $this->getUserTransferRecords(100,1);
		if(is_array($arrResult['Result']) && count($arrResult['Result'])>0)
		{
			$TotalPage = $arrResult['Page']['TotalPage'];
			$CurPage = $arrResult['Page']['CurPage'];			
			$sheet = $CurPage-1;//当前写入的sheet			
			//初始化对象
			$objPHPXls = new PHPExcel();	
			$objSession = new Session($this->arrConfig['Session']['SessionData']);
			$objTmpPHPExecl = $objSession->get($this->arrConfig['SessionInfo']['BankPHPExcel']);
			if($objTmpPHPExecl)
				$objPHPExcel = $objTmpPHPExecl;
			else
			{
				$objPHPExcel=$objPHPXls;
				$objSession->set($this->arrConfig['SessionInfo']['BankPHPExcel'], $objPHPXls);
			}
			//创建新的sheet
			if($CurPage>1)
				$objPHPExcel->createSheet();
			else 
			{
				//设置文档属性
				$objPHPExcel->getProperties()->setCreator("xlj")//作者
											 ->setLastModifiedBy("xlj")//最后一次修改人
											 ->setTitle("银行转账记录")//标题
											 ->setSubject("银行转账记录")//主题
											 ->setDescription("银行转账记录")//备注信息
											 ->setKeywords("银行转账 记录")//标记
											 ->setCategory("银行转账记录");//类别
			}
			//写标题	
			$objPHPExcel->setActiveSheetIndex($sheet)
			            ->setCellValue('A1', '交易时间')
			            ->setCellValue('B1', '变化金额')
			            ->setCellValue('C1', '借贷方式')
			            ->setCellValue('D1', '余额')
			            ->setCellValue('E1', '交易类型')
			            ->setCellValue('F1', '转入账户')
			            ->setCellValue('G1', 'IP')
			            ->setCellValue('H1', 'MAC')
			            ->setCellValue('I1', '备注');	
			$iCount = 2;
			//从第二行开始循环写记录
			foreach ($arrResult['Result'] as $val)
			{
				$objPHPExcel->setActiveSheetIndex($sheet)
				            ->setCellValue('A'.$iCount, $val['TransTime'])
				            ->setCellValue('B'.$iCount, $val['DcFlag'] == 1?$val['Money']:'-'.$val['Money'])
				            ->setCellValue('C'.$iCount, $val['DcFlagTips'])
				            ->setCellValue('D'.$iCount, $val['Balance'])
				            ->setCellValue('E'.$iCount, $val['TransTypeTips'])
				            ->setCellValue('F'.$iCount, $val['TargetAccNo'])
				            ->setCellValue('G'.$iCount, $val['ClientIP'])
				            ->setCellValue('H'.$iCount, $val['MachineSerial'])
				            ->setCellValue('I'.$iCount, $val['Note']);
		        $iCount++;
			}
			
			//如果当前页码已经大于等于总页码,则结束,否则当前页码加1
			if($CurPage>=$TotalPage)
				$CurPage = 0;
			else
				$CurPage++;				
			//重命名sheet标签
			$objPHPExcel->getActiveSheet()->setTitle('第'.($sheet+1).'页');
			$objPHPExcel->setActiveSheetIndex($sheet);		
			//如果已经是最后一页,生成execl文件
			if($CurPage==0)
			{
				//清空session
				$objSession->set($this->arrConfig['SessionInfo']['BankPHPExcel'], null);
				//文件名
				$FileName = date('YmdHis').rand(100, 999).'.xls';
				$FilePath = 'Files/BankTransfer/'.$FileName;
				
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
				$objWriter->save(ROOT_PATH.$FilePath);
				$Http = '/'.$FilePath;
			}
		}		
		echo json_encode(array('CurPage'=>$CurPage,'TotalPage'=>$TotalPage,'Http'=>$Http));
	}
	
	/**
	 * 充值记录分页
	 */
	public function getPagerUserRechargeRecords()
	{		
		Utility::Log("system_error", "getUserRechargeRecords", "xxx");
		$arrRes = $this->getUserRechargeRecords(14);
		Utility::Log("system_error", "getUserRechargeRecords", json_encode($arrRes));
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrRes['Page'],'RechargeList'=>$arrRes['Result']);
		Utility::assign($this->smarty,$arrTags);				
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/RoleRechargeRecordsPage.html');	
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	/**
	 * 分页取充值记录
	 * @param unknown_type $pagesize 每页显示记录数
	 */
    private  function getUserRechargeRecords($pagesize){
        $arrResult = null;
        $Page = null;
        $LoginID = $RoleID = Utility::isNumeric('RoleID', $_POST);
        $StartTime = Utility::isNullOrEmpty('RechargeStartTime', $_POST);
        $EndTime = Utility::isNullOrEmpty('RechargeEndTime', $_POST);
        $RechargeType = Utility::isNumeric('RechargeType', $_POST);
        $RechargeStatus = Utility::isNullOrEmpty('RechargeStatus', $_POST);
        $OrderSerial = $_POST['OrderSerial'];
        $curPage = Utility::isNumeric('curPage',$_POST);
        $curPage = $curPage==0 ? 1 : $curPage;
        $strWhere = ' WHERE PayType = 1';//指定金币充值
        $strWhere .= ' AND ([Status] = 3 OR [Status] = 4)';//指定充值的状态
        if($LoginID)
        {
            //$objUserBLL = new UserBLL(0);
            //$arrUserInfo = $objUserBLL->getRole(1,$LoginID);
            //if(!empty($arrUserInfo))
            $strWhere .= " AND LoginID=".$LoginID;
            //else
            //	return array('arrRechargeList'=>null,'Page'=>null);
        }
        if($RechargeStatus == 'Success')
            $strWhere .= ' AND [Status] = 3';
        elseif($RechargeStatus == 'Fail')
            $strWhere .= ' AND [Status] = 4';
        if($OrderSerial) $strWhere .= " AND SpOrderNo='$OrderSerial' ";
        if($RechargeType) $strWhere .=  " AND CardType = {$RechargeType}";//充值方式
        //if($Amount) $strWhere .= " AND TotalFee=$Amount ";
        //if($StartTime) $strWhere .= " AND DATEDIFF(d,AddTime,'$StartTime')<=0 ";
        //if($EndTime) $strWhere .= " AND DATEDIFF(d,AddTime,'$EndTime')>=0 ";

        $arrParam['fields']='OrderID, Status, TransactionID, SpOrderNo, TotalFee, LoginID, CONVERT(VARCHAR(100),UpdateTime,120) AS UpdateTime,PayType,CardType';
        $arrParam['tableName']='T_PayOrder_';
        $arrParam['where']=$strWhere;
        $arrParam['order']='OrderID desc';
        $arrParam['Page'] = $curPage;
        $arrParam['PageSize']=$pagesize;
        $arrParam['StartDate'] = $StartTime;
        $arrParam['EndDate'] = $EndTime;
        $objPayLogsBLL = new PayLogsBLL($this->arrConfig['MapType']['PayLogs']);
        Utility::Log("system_error", "getPageList", json_encode($objPayLogsBLL));
        $iRecordsCount = $objPayLogsBLL->getRecordsCount($arrParam);
        Utility::Log("system_error", "getRecordsCount", "$iRecordsCount");
        $Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['PageSize']);
        $arrRechargeList = $objPayLogsBLL->getPageList($arrParam,0);
        Utility::Log("system_error", "getPageList", json_encode($arrRechargeList));
        if($arrRechargeList)
        {
            $iCount = 0;
            foreach ($arrRechargeList as $val)
            {
                if(empty($arrUserInfo))
                {
                    //$objUserBLL = new UserBLL($val['RoleID']);
                    //$arrUserInfo = $objUserBLL->getRoleInfo($val['RoleID']);

                    $iLoginName = getUserLoginName($val['LoginID']);
                }
                if(isset($iLoginName))
                {
                    $arrRechargeList[$iCount]['LoginName'] = $iLoginName.'('.$val['LoginID'].')';
                
                    //获取充值方式
                    $arrCardNameInfo = $this->objMasterBLL->getCardNameByCardType($val['CardType']);
                    $arrRechargeList[$iCount]['CardTypeTip'] = $arrCardNameInfo['CardName'];
                   
                    //spOrderNo 内部订单编号

                    //0待付款  1 付款成功  2付款失败  3充值成功 4充值失败
                    switch($val['Status']){
                        case 0:$arrRechargeList[$iCount]['StatusTip'] = '待付款';break;
                        case 1:$arrRechargeList[$iCount]['StatusTip'] = '付款成功';break;
                        case 2:$arrRechargeList[$iCount]['StatusTip'] = '付款失败';break;
                        case 3:$arrRechargeList[$iCount]['StatusTip'] = '充值成功';break;
                        case 4:$arrRechargeList[$iCount]['StatusTip'] = '充值失败';break;
                        default:$arrRechargeList[$iCount]['StatusTip'] = '状态错误';break;
                    }
                    switch($val['PayType']){
                        case 1:$arrRechargeList[$iCount]['PayTypeTip'] = '金币充值';break;
                        case 2:$arrRechargeList[$iCount]['PayTypeTip'] = '黄钻充值';break;
                        default:$arrRechargeList[$iCount]['PayTypeTip'] = '';break;
                    }
                    //$arrRechargeList[$iCount]['Corp'] = Utility::gb2312ToUtf8($val['Corp']);//==1 ? '快钱充值' : ($val['TypeID']==2 ? '聚宝充值' : '北网充值');
                    //$arrRechargeList[$iCount]['StatusName'] = $val['Status']==1 ? '成功' : ($val['Status']==-1 ? '未付款' : '失败');
                }
                else
                {
                    $arrRechargeList[$iCount]['LoginName'] = '';
                    $arrRechargeList[$iCount]['Corp'] = '';
                    $arrRechargeList[$iCount]['StatusName'] = '';
                }
                if(!$LoginID) $arrUserInfo=null;
                $iCount++;
            }
        }
        return array('Result'=>$arrRechargeList,'Page'=>$Page);

    }
	/*private  function getUserRechargeRecords($pagesize)
	{
		$arrResult = null;
		$Page = null;
		$RoleID = Utility::isNumeric('RoleID', $_POST);
		$RechargeStartTime = Utility::isNullOrEmpty('RechargeStartTime', $_POST);
		$RechargeEndTime = Utility::isNullOrEmpty('RechargeEndTime', $_POST);
		$RechargeType = Utility::isNumeric('RechargeType', $_POST);
		$RechargeStatus = Utility::isNullOrEmpty('RechargeStatus', $_POST);
		$OrderSerial = $_POST['OrderSerial'];
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage==0 ? 1 : $curPage;
		if($RoleID && $RechargeStartTime && $RechargeEndTime)
		{		
			$strWhere = '';	
			if($RechargeType!=0)
				$strWhere .= ' AND RechargeFrom='.$RechargeType;
			if($RechargeStatus=='Success')
				$strWhere .= ' AND [Status]=1';
			elseif($RechargeStatus=='Fail')
				$strWhere .= ' AND [Status]=0';
			if(!empty($OrderSerial))
				$strWhere .= ' AND OrderSerial='.$OrderSerial;
			$arrParam['fields']='CONVERT(VARCHAR(20),AddTime,120) AS AddTime,OrderSerial,RechargeFrom,[Count],[Money],[Status],Corp';
			$arrParam['tableName']='T_RechargeOrder';
			$arrParam['where']=' WHERE RoleID='.$RoleID.' AND DATEDIFF(d,\''.$RechargeStartTime.'\',AddTime)>=0 AND DATEDIFF(d,\''.$RechargeEndTime.'\',AddTime)<=0'.$strWhere;
			$arrParam['order']='AddTime DESC,RID';
			$arrParam['pagesize'] = $pagesize;
			
			$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['UserData'], $RoleID);
			$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
			if($iRecordsCount>0)
			{
				$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
				$arrResult = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);
				if(is_array($arrResult) && count($arrResult)>0)
				{
					$iCount = 0;
					foreach ($arrResult as $val)
					{					
						$arrResult[$iCount]['RechargeTypeName'] = $val['RechargeFrom'];//$this->arrConfig['RechargeType'][$val['RechargeFrom']];
						$arrResult[$iCount]['Status'] = $val['Status']==1 ? '成功' : ($val['Status']==0 ? '失败' : '未付款');
						$arrResult[$iCount]['Corp'] = Utility::gb2312ToUtf8($val['Corp']);
						$iCount++;
					}
				}		
			}	
		}
		return array('Result'=>$arrResult,'Page'=>$Page);
	}*/
	
	/**
	 * 充值记录分页导出execl
	 */
	public function downloadUserRechargeRecordsExecl()
	{			
		$CurPage = 0;	
		$TotalPage = 1;	
		$Http = '';
		//读取记录
		$arrResult = $this->getUserRechargeRecords(100);
        //var_dump($arrResult);
		if(is_array($arrResult['Result']) && count($arrResult['Result'])>0)
		{
			$TotalPage = $arrResult['Page']['TotalPage'];
			$CurPage = $arrResult['Page']['CurPage'];			
			$sheet = $CurPage-1;//当前写入的sheet
			//初始化对象
			$objPHPXls = new PHPExcel();	
			$objSession = new Session($this->arrConfig['Session']['SessionData']);
			$objTmpPHPExecl = $objSession->get($this->arrConfig['SessionInfo']['PHPExcel']);
			if($objTmpPHPExecl)
				$objPHPExcel = $objTmpPHPExecl;
			else
			{
				$objPHPExcel=$objPHPXls;
				$objSession->set($this->arrConfig['SessionInfo']['PHPExcel'], $objPHPXls);
			}
			//创建新的sheet
			if($CurPage>1)
				$objPHPExcel->createSheet();
			else 
			{
				//设置文档属性
				$objPHPExcel->getProperties()->setCreator("xlj")//作者
											 ->setLastModifiedBy("xlj")//最后一次修改人
											 ->setTitle("充值记录")//标题
											 ->setSubject("充值记录")//主题
											 ->setDescription("充值记录")//备注信息
											 ->setKeywords("充值记录")//标记
											 ->setCategory("充值记录");//类别
			}
			//写标题	
			$objPHPExcel->setActiveSheetIndex($sheet)
			            ->setCellValue('A1', '充值时间')
			            ->setCellValue('B1', '充值订单号')
			            ->setCellValue('C1', '充值方式')
			            ->setCellValue('D1', '充值金额')
			            ->setCellValue('E1', '状态');
			$iCount = 2;
			//从第二行开始循环写记录
			foreach ($arrResult['Result'] as $val)
			{
				$objPHPExcel->setActiveSheetIndex($sheet)
				            ->setCellValue('A'.$iCount, $val['UpdateTime'])
				            ->setCellValueExplicit('B'.$iCount, $val['SpOrderNo'],PHPExcel_Cell_DataType::TYPE_STRING)
				            ->setCellValue('C'.$iCount, $val['CardTypeTip'])
				            ->setCellValue('D'.$iCount, $val['TotalFee'])
				            ->setCellValue('E'.$iCount, $val['StatusTip']);
		        $iCount++;
			}
			
			//如果当前页码已经大于等于总页码,则结束,否则当前页码加1
			if($CurPage>=$TotalPage)
				$CurPage = 0;
			else
				$CurPage++;				
			//重命名sheet标签
			$objPHPExcel->getActiveSheet()->setTitle('第'.($sheet+1).'页');
			$objPHPExcel->setActiveSheetIndex($sheet);		
			//如果已经是最后一页,生成execl文件
			if($CurPage==0)
			{
				//清空session
				$objSession->set($this->arrConfig['SessionInfo']['PHPExcel'], null);
				//文件名
				$FileName = $_POST['FileName'].'.xls';
				$FilePath = 'Files/RechargeOrder/'.$FileName;
				
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
				$objWriter->save(ROOT_PATH.$FilePath);
				$Http = '/'.$FilePath;
			}
		}
		echo json_encode(array('CurPage'=>$CurPage,'TotalPage'=>$TotalPage,'Http'=>$Http));


		/*$file_type = "vnd.ms-excel";
		$path='/';
		$file_ending = "xls";
		$savename = 123;//date('YmdHis');

		header("Content-Type: application/$file_type;charset=utf8"); 
		header("Content-Disposition: attachment; filename=".$savename.".".$file_ending);
		
		$stu_info ='<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns="[url=http://www.w3.org/TR/REC-html40]http://www.w3.org/TR/REC-html40[/url]">';
		$stu_info .='<head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name></x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head>';
		$stu_info .='<body link=blue vlink=purple leftmargin=0 topmargin=0>';
		$stu_info .='<table width="100%" border="1" cellspacing="0" cellpadding="0">';
		$stu_info .= '<tr>';
		$stu_info .= '<td nowrap><b>11</b></td>';
		$stu_info .= '<td nowrap><b>22</b></td>';
		$stu_info .= '<td nowrap><b>33</b></td>';
		$stu_info .= '</tr>';
		$stu_info .= '<tr>';
		$stu_info .= '<td nowrap>1</td>';
		$stu_info .= '<td nowrap>2</td>';
		$stu_info .= '<td nowrap>3</td>';
		$stu_info .= '</tr>';
		$stu_info .= '</table></body></html>';	

		$out=fopen($savename.'.'.$file_ending,'a+');//打开文件流		
		fwrite($out,$stu_info);//写入数据		
		fclose($out);//关闭文件流*/
		

	}
	
	/**
	 * 道具资料——获取背包信息
	 */
	public function getMyKnapsackInfo()
	{
		return;
		$arrResult = null;
		$Page = null;
		$RoleID = Utility::isNumeric('RoleID', $_POST);			
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage==0 ? 1 : $curPage;
		if($RoleID && $RoleID>0)
		{		
			$arrParam['fields']='CONVERT(VARCHAR(20),AddTime,120) AS AddTime,SpID,Locked,ISNULL(SpFrom1,0) AS SpFrom1,ISNULL(SpFrom2,0) AS SpFrom2,SpFrom3';
			$arrParam['tableName']='T_UserStageProperty';
			$arrParam['where']=' WHERE RoleID='.$RoleID.' AND IsUsed=0';
			$arrParam['order']='AddTime DESC';
			$arrParam['pagesize'] = 15;
			
			$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['UserData'],$RoleID);
			$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
			if($iRecordsCount>0)
			{
				$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
				$arrResult = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);
				if(is_array($arrResult) && count($arrResult)>0)
				{
					$iCount = 0;				
					foreach ($arrResult as $val)
					{					
						$arrResult[$iCount]['Locked'] = $val['Locked']==0 ? '正常' : '锁定';
						//组合道具来源
						$arrResult[$iCount]['SpFrom'] = $this->arrConfig['SpFrom'][$val['SpFrom1']];
						if($val['SpFrom2']>0)
						{
							if($val['SpFrom1']==1)
							{
								//读取游戏名称
								$arrKindInfo = $this->objMasterBLL->getGameKindInfo($val['SpFrom2']);
								if(is_array($arrKindInfo) && count($arrKindInfo)>0)
									$arrResult[$iCount]['SpFrom'] .= '('.$arrKindInfo['KindName'].')';
							}
						}
						if(!empty($val['SpFrom3']))
							$arrResult[$iCount]['SpFrom'] .= '('.Utility::gb2312ToUtf8($val['SpFrom3']).')';
						//读取道具名称
						$arrSpInfo = $this->objStagePropertyBLL->getSpPublicInfo($val['SpID']);
						if(is_array($arrSpInfo) && count($arrSpInfo)>0)
							$arrResult[$iCount]['GoodsName'] = $arrSpInfo['GoodsName'];
						else 
							$arrResult[$iCount]['GoodsName'] = '';
						$iCount++;
					}
				}		
			}
		}		
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'SpList'=>$arrResult);
		Utility::assign($this->smarty,$arrTags);				
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/RoleMyKnapsackInfo.html');	
		$html=str_replace("\r\n",'',$html);
		echo $html;
	}
	
	/**
	 * 道具资料——获取衣柜信息
	 */
	public function getMyWardrobeInfo()
	{
		$arrResult = null;
		$Page = null;
		$RoleID = Utility::isNumeric('RoleID', $_POST);			
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage==0 ? 1 : $curPage;
		if($RoleID && $RoleID>0)
		{		
			$arrParam['fields']='CONVERT(VARCHAR(20),UseTime,120) AS UseTime,CONVERT(VARCHAR(20),ExpireTime,120) AS ExpireTime,SpID,DATEDIFF(s,ExpireTime,GETDATE()) AS Second';
			$arrParam['tableName']='T_UserStageProperty';
			$arrParam['where']=' WHERE RoleID='.$RoleID.' AND IsUsed=1 AND Space=2';
			$arrParam['order']='ExpireTime DESC';
			$arrParam['pagesize'] = 15;
			
			$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['UserData'],$RoleID);
			$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
			if($iRecordsCount>0)
			{
				$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
				$arrResult = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);
				if(is_array($arrResult) && count($arrResult)>0)
				{
					$iCount = 0;					
					foreach ($arrResult as $val)
					{
						$arrResult[$iCount]['Status'] = $val['Second']<0 ? '使用中' : '已过期';						
						//读取道具名称
						$arrSpInfo = $this->objStagePropertyBLL->getSpPublicInfo($val['SpID']);
						if(is_array($arrSpInfo) && count($arrSpInfo)>0)
							$arrResult[$iCount]['GoodsName'] = $arrSpInfo['GoodsName'];
						else 
							$arrResult[$iCount]['GoodsName'] = '';
						$iCount++;
					}
				}		
			}
		}		
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'SpList'=>$arrResult);
		Utility::assign($this->smarty,$arrTags);				
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/RoleMyWardrobeInfo.html');	
		$html=str_replace("\r\n",'',$html);
		echo $html;
	}
	
	/**
	 * 道具资料——使用中的道具
	 */
	public function getMyUsingSpInfo()
	{
		$arrResult = null;
		$Page = null;
		$RoleID = Utility::isNumeric('RoleID', $_POST);			
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage==0 ? 1 : $curPage;
		if($RoleID && $RoleID>0)
		{		
			$arrParam['fields']='CONVERT(VARCHAR(20),UseTime,120) AS UseTime,CONVERT(VARCHAR(20),ExpireTime,120) AS ExpireTime,SpID,UseCount';
			$arrParam['tableName']='T_UserStageProperty';
			$arrParam['where']=' WHERE RoleID='.$RoleID.' AND IsUsed=1 AND (DATEDIFF(s,ExpireTime,GETDATE())<0 OR UseCount>0)';
			$arrParam['order']='ExpireTime DESC,UseCount DESC';
			$arrParam['pagesize'] = 15;
			
			$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['UserData'],$RoleID);
			$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
			if($iRecordsCount>0)
			{
				$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
				$arrResult = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);
				if(is_array($arrResult) && count($arrResult)>0)
				{
					$iCount = 0;					
					foreach ($arrResult as $val)
					{			
						//读取道具名称
						$arrSpInfo = $this->objStagePropertyBLL->getSpPublicInfo($val['SpID']);
						if(is_array($arrSpInfo) && count($arrSpInfo)>0)
							$arrResult[$iCount]['GoodsName'] = $arrSpInfo['GoodsName'];
						else 
							$arrResult[$iCount]['GoodsName'] = '';
						$iCount++;
					}
				}		
			}
		}		
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'SpList'=>$arrResult);
		Utility::assign($this->smarty,$arrTags);				
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/RoleUsingSpInfo.html');	
		$html=str_replace("\r\n",'',$html);
		echo $html;
	}
	/**
	 * 道具资料——背包日志
	 */	
	public function getMyKnapsackLogs()
	{
		$Page = null;
		$arrRes = null;
		$iRoleID = Utility::isNumeric('RoleID', $_POST);
		$arrParam['EndDate'] = Utility::isNullOrEmpty('LogsEndTime', $_POST) ? $_POST['LogsEndTime'] : date('Y-m-d');
		$arrParam['StartDate'] = Utility::isNullOrEmpty('LogsStartTime', $_POST) ? $_POST['LogsStartTime'] : date('Y-m-d',strtotime('-90 days'));
		//从第几条开始读取
		$arrParam['RowIndex'] = Utility::isNumeric('LogsID',$_POST) ? $_POST['LogsID'] : 0;	
		$isFlag = Utility::isNumeric('sFlag', $_POST);	
			
		$strCookRecordCount = $this->arrConfig['Cookies']['iRecordsCount'].$iRoleID.'MyKnapsackLogs';
//		$strCookPrevParams1 = $this->arrConfig['Cookies']['PrevParams1'].$iRoleID.'MyKnapsackLogs';
//		$strCookPrevParams2 = $this->arrConfig['Cookies']['PrevParams2'].$iRoleID.'MyKnapsackLogs';
		//当前分页
		$iCurPage = Utility::isNumeric('curPage',$_POST);
		$arrParam['iCurPage'] = $iCurPage > 0 ? $iCurPage : 1;
		//查询条件
		$arrParam['where'] = ' WHERE RoleID='.$iRoleID;
		$arrParam['fields'] = 'CONVERT(VARCHAR(20),AddTime,120) AS AddTime,LogsID,Intro,ClientIP,MachineSerial,RoleID';
		//每页显示数量
		$arrParam['pagesize'] = 15;
		$arrParam['tableName']='T_KnapsackDataLogs_';
		$arrParam['order'] = 'AddTime DESC';		
			
		
		$objDataChangeLogsBLL = new DataChangeLogsBLL($iRoleID);
		//总记录数
		if(!isset($_COOKIE[$strCookRecordCount]))
		{
			$iRecordCount = $objDataChangeLogsBLL->getRecordsCount($arrParam);
			setcookie($strCookRecordCount,$iRecordCount);
		}
		else 
			$iRecordCount = $_COOKIE[$strCookRecordCount];

		if($iRecordCount>0)
		{
			//总分页数
			$iPageAll = $iRecordCount==0 ? 1 : ceil($iRecordCount/$arrParam['pagesize']);	
			$arrParam['memName'] = 'MyKnapsackLogs';
			//单击搜索清除缓存
			if($isFlag){
				$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['DataChangeLogs'],$iRoleID);
				$objCommonBLL->delSimplePageMemcache($arrParam['memName'], $iPageAll);
			}	
//			//从cookies取上一页的状态位,上一页开始日期,上一页开始取的ID起始值
//			$PrevStartDate = $arrParam['EndDate'];
//			$PrevLogsID=$arrParam['RowIndex'];
//			if(isset($_COOKIE[$strCookPrevParams1]))
//				$PrevStartDate=$_COOKIE[$strCookPrevParams1];
//			if(isset($_COOKIE[$strCookPrevParams2]))
//				$PrevLogsID=$_COOKIE[$strCookPrevParams2];
					
			//分页读取记录
			$arrRes = $objDataChangeLogsBLL->getPageList($arrParam,1);
			if(is_array($arrRes) && count($arrRes)>0)
			{
				$iCount = 0;
				foreach ($arrRes as $val)
				{
					$arrRes[$iCount]['Intro'] = Utility::gb2312ToUtf8($val['Intro']);					
					$iCount++;
				}
				$iNum = count($arrRes);
//				$arrRes[0]['PrevStartDate'] = date('Y-m-d',strtotime($arrRes[0]['AddTime']));
//				$arrRes[0]['PrevLogsID'] = $arrParam['RowIndex']==0 ? 0 : $arrRes[0]['LogsID']+1;						
//				//记录上一页的状态位
//				setcookie($strCookPrevParams1.$iCurPage,$arrRes[0]['PrevStartDate'],0,'/');
//				setcookie($strCookPrevParams2.$iCurPage,$arrRes[0]['PrevLogsID'],0,'/');					
					
				//记录下一页的状态位
				$NextStartDate = date('Y-m-d',strtotime($arrRes[$iNum-1]['AddTime']));	
				$NextLogsID = $arrRes[$iNum-1]['LogsID'];
				//$NextStartDate=$arrRes[0]['NextStartDate'];
			
				$Page=Utility::setSimplePages($iPageAll,$arrParam['iCurPage'],$NextLogsID,$NextStartDate);
			}			
		}
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'LogsList'=>$arrRes); 
		Utility::assign($this->smarty,$arrTags);				
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/RoleMyKnapsackLogs.html');			
		$html=str_replace("\r\n",'',$html);
		echo $html;
	}
	/**
	 * 角色日志——玩家日志详情
	 */
	public function getPlayerLogsDetail()
	{
		$OpType = Utility::isNumeric('OpTypeID', $_POST);//日志类型,1:登陆日志,2:操作日志
		if($OpType==1)
			$this->getPlayerLoginLogsDetail();
		elseif($OpType==2)
			$this->getPlayerOperationLogsDetail();
	}
	/**
	 * 角色日志——设置操作日志
	 */
	public function getPlayerSetLogsDetail()
	{
	    $SetSevice = Utility::isNumeric('SetSevice', $_POST);//日志类型,1:登陆日志,2:操作日志
	    $OperateType = Utility::isNumeric('OperateType', $_POST);//日志类型,1:登陆日志,2:操作日志
	    $DateType = Utility::isNumeric('DateType', $_POST);//日志类型,1:登陆日志,2:操作日志
	    $Page = null;
		$arrRes = null;
		$iRoleID = Utility::isNumeric('RoleID', $_POST);
		$iKindID = Utility::isNumeric('KindID', $_POST);
		//$OpType = Utility::isNumeric('OpTypeID', $_POST);//操作类型
		$arrParam['EndDate'] = Utility::isNullOrEmpty('LogsEndTime', $_POST);
		$arrParam['StartDate'] = Utility::isNullOrEmpty('LogsStartTime', $_POST);	
		$isFlag = Utility::isNumeric('sFlag', $_POST);	//删除缓存标识
		//从第几条开始读取
		$arrParam['RowIndex'] = Utility::isNumeric('LogsID',$_POST) ? $_POST['LogsID'] : 0;	
		$strCookRecordCount = $this->arrConfig['Cookies']['iRecordsCount'].$iRoleID.'Set';
		//当前分页
		$iCurPage = Utility::isNumeric('curPage',$_POST);
		$arrParam['iCurPage'] = $iCurPage > 0 ? $iCurPage : 1;
		//查询条件
		$arrParam['where'] = ' WHERE RoleID='.$iRoleID;
		if($SetSevice)
		    $arrParam['where'] = $arrParam['where'].' AND SetSevice = '.$SetSevice;
		if($OperateType)
		    $arrParam['where'] = $arrParam['where'].' AND OperateType = '.$OperateType;
		if($DateType)
		    $arrParam['where'] = $arrParam['where'].' AND DateType = '.$DateType;
		 
		$arrParam['fields'] = 'CONVERT(VARCHAR(20),SetTime,120) AS SetTime,SetSevice,OperateType,DateType,ClientIP,MachieSerial,LastValue,SetValue,Description';
		//每页显示数量
		$arrParam['pagesize'] = 15;
		$arrParam['tableName']='T_UserSetOperateLogs_';
		$arrParam['order'] = 'SetTime DESC';		
			
		
		$objSetOperationLogsBLL = new SetOperationLogsBLL($iRoleID);
		//总记录数


		$iRecordCount = $objSetOperationLogsBLL->getRecordsCount($arrParam);
/* 		if(!isset($_COOKIE[$strCookRecordCount]))
		{
			$iRecordCount = $objSetOperationLogsBLL->getRecordsCount($arrParam);
			setcookie($strCookRecordCount,$iRecordCount);
		}
		else
			$iRecordCount = $_COOKIE[$strCookRecordCount]; */


		if($iRecordCount>0)
		{
			//总分页数
			$iPageAll = $iRecordCount==0 ? 1 : ceil($iRecordCount/$arrParam['pagesize']);	
			$arrParam['memName'] = 'PlayerOperationLogsDetail';
			//单击搜索清除缓存
			/* if($isFlag){
				$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['OperationLogs'],$iRoleID);
				$objCommonBLL->delSimplePageMemcache($arrParam['memName'], $iPageAll);
			}	 */
			//分页读取记录
			$arrRes = $objSetOperationLogsBLL->getPageList($arrParam,0);
			$SetSevice = $this->arrConfig['SetSevice'];
			$OperateType = $this->arrConfig['OperateType'];
			$DateType = $this->arrConfig['DateType'];
			if(is_array($arrRes) && count($arrRes)>0)
			{
				$iCount = 0;
				foreach ($arrRes as $val)
				{
					$arrRes[$iCount]['Description'] = Utility::gb2312ToUtf8($val['Description']);
					$arrRes[$iCount]['SetValue'] = Utility::gb2312ToUtf8($val['SetValue']);
					$arrRes[$iCount]['LastValue'] = Utility::gb2312ToUtf8($val['LastValue']);
					$arrRes[$iCount]['SetSevice'] = $SetSevice[$arrRes[$iCount]['SetSevice']];
					$arrRes[$iCount]['OperateType'] = $OperateType[$arrRes[$iCount]['OperateType']];
					$arrRes[$iCount]['DateType'] = $DateType[$arrRes[$iCount]['DateType']];
					$iCount++;
				}
				$iNum = count($arrRes);					

				//$Page=Utility::setPages($arrParam['iCurPage'],$iRecordCount,$arrParam['pagesize']);
				//记录下一页的状态位
 			 	$NextStartDate = date('Y-m-d',strtotime($arrRes[$iNum-1]['SetTime']));	
				$NextLogsID = $arrRes[$iNum-1]['RowIndex'];
			
				$Page=Utility::setSimplePages($iPageAll,$arrParam['iCurPage'],$NextLogsID,$NextStartDate);  
			}else {
			    $arrRes = null;
			}
		}
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'OpLogsList'=>$arrRes); 
		Utility::assign($this->smarty,$arrTags);				
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/RolePlayerSetLogsDetail.html');			
		$html=str_replace("\r\n",'',$html);
		echo $html;
	}



    private function getPlayerLoginLogsDetail(){
        $arrRes = null;
        $Page = null;
        $iRoleID = Utility::isNumeric('RoleID',$_POST);
        $arrParam['StartDate'] = Utility::isNullOrEmpty('LogsStartTime',$_POST);
        $arrParam['EndDate'] = Utility::isNullOrEmpty('LogsEndTime',$_POST);
        $iCurPage = Utility::isNumeric('curPage',$_POST)?$_POST['curPage']:1;

        //$objDataChangeLogsBLL = new DataChangeLogsBLL($iRoleID);

        $objCommonBLL = new CommonBLL($this->arrConfig['MapType']['DataChangeLogs']);

        $arrParam['fields'] = 'CONVERT(VARCHAR(100),AddTime,120) AS AddTime,RoleID,RoleName,ClientIP,MachineSerial,ServerID,LogType';
        $arrParam['Page'] = $iCurPage > 0 ? $iCurPage :1;
        $arrParam['pagesize'] = 15;
        $arrParam['where'] = ' where RoleID = '.$iRoleID;
        //$arrParam['tableName'] = 'T_LoginLogs_';
        $time = date('Ymd',strtotime($arrParam['StartDate']));
        $column = ' ServerID,RoleID,RoleName,LogType,AddTime ';
        $arrParam['tableName'] = "((Select {$column},ClientIP,MachineSerial FROM T_LoginLogs_{$time}) Union (Select {$column},'' AS ClientIP,'' AS MachineSerial FROM T_LogoutLogs_{$time})) AS T";
        $arrParam['order'] = 'AddTime DESC';


        //$iRecordCount = $objDataChangeLogsBLL->getRecordsCount($arrParam);
        $iRecordCount = $objCommonBLL->getRecordsCountSelect($arrParam);
        //var_dump($iRecordCount);
        if($iRecordCount > 0){
            //$arrResult = $objDataChangeLogsBLL->getPageList($arrParam,0);
            $arrResult = $objCommonBLL->getPageListSelect($arrParam,$arrParam['Page']);
        }else{
            $arrResult = array();
        }
        $LogType = $this->arrConfig['LogType'];
        $_LogType = Utility::array_column($LogType,'name','value');
        $objMasterBLL = new MasterBLL();
        foreach($arrResult as $key =>$item){
            //
            $remark = "{$_LogType[$item['LogType']]}";
            switch($item['LogType']){
                case 3:
                case 6: $info = $objMasterBLL->getGameRoomInfo($item['ServerID']);
                        $remark .= ":{$info['RoomName']}";
                        break;
            }
            $arrResult[$key]['Remark'] = $remark;
        }
        $Page = Utility::setSimplePages(Utility::getPageNum($iRecordCount,$arrParam['pagesize']),$iCurPage,'',$arrParam['EndDate']);

        $arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'LoginLogsList'=>$arrResult);
        //var_dump($arrResult);
        Utility::assign($this->smarty,$arrTags);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/RolePlayerLoginLogsDetail.html');
        $html=str_replace("\r\n",'',$html);
        echo $html;
        //$Page = Utility::setSimplePages()

    }
	/**
	 * 角色日志——玩家日志详情(登陆日志)
	 */
	private function getPlayerLoginLogsDetail1()
	{
		$arrRes = null;
		$Page = null;
		$iRoleID = Utility::isNumeric('RoleID', $_POST);
		$iKindID = Utility::isNumeric('KindID', $_POST);
		$arrParam['EndDate'] = Utility::isNullOrEmpty('LogsEndTime', $_POST);
		$arrParam['StartDate'] = Utility::isNullOrEmpty('LogsStartTime', $_POST);	
		$isFlag = Utility::isNumeric('sFlag', $_POST);	//删除缓存标识
		//从第几条开始读取
		$arrParam['RowIndex'] = Utility::isNumeric('LogsID',$_POST) ? $_POST['LogsID'] : 0;	
			
		$strCookRecordCount = $this->arrConfig['Cookies']['iRecordsCount'].$iRoleID.'Login';
//		$strCookPrevParams1 = $this->arrConfig['Cookies']['PrevParams1'].$iRoleID;
//		$strCookPrevParams2 = $this->arrConfig['Cookies']['PrevParams2'].$iRoleID;
		//当前分页
		$iCurPage = Utility::isNumeric('curPage',$_POST);
		$arrParam['iCurPage'] = $iCurPage > 0 ? $iCurPage : 1;
		//查询条件
		$arrParam['where'] = ' WHERE RoleID='.$iRoleID;
		$arrParam['fields'] = 'CONVERT(VARCHAR(20),LoginTime,120) AS LoginTime,LogsID,RoleID,ClientIP,MachineSerial,LoginResult,LoginContent,TypeID';
		//每页显示数量
		$arrParam['pagesize'] = 15;
		$arrParam['tableName']='T_LoginLogs_';
		$arrParam['order'] = 'LoginTime DESC';		
		
		$objOperationLogsBLL = new OperationLogsBLL($iRoleID);
		//总记录数
		if(!isset($_COOKIE[$strCookRecordCount]))
		{
			$iRecordCount = $objOperationLogsBLL->getRecordsCount($arrParam);
			setcookie($strCookRecordCount,$iRecordCount);
		}
		else 
			$iRecordCount = $_COOKIE[$strCookRecordCount];

		if($iRecordCount>0)
		{
			//总分页数
			$iPageAll = $iRecordCount==0 ? 1 : ceil($iRecordCount/$arrParam['pagesize']);	
			$arrParam['memName'] = 'PlayerLoginLogsDetail';
			//单击搜索清除缓存
			if($isFlag){
				$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['OperationLogs'],$iRoleID);
				$objCommonBLL->delSimplePageMemcache($arrParam['memName'], $iPageAll);
			}		
//			//从cookies取上一页的状态位,上一页开始日期,上一页开始取的ID起始值
//			$PrevStartDate = $arrParam['EndDate'];
//			$PrevLogsID=$arrParam['RowIndex'];
//			if(isset($_COOKIE[$strCookPrevParams1]))
//				$PrevStartDate=$_COOKIE[$strCookPrevParams1];
//			if(isset($_COOKIE[$strCookPrevParams2]))
//				$PrevLogsID=$_COOKIE[$strCookPrevParams2];					
			
			//分页读取记录
			$arrRes = $objOperationLogsBLL->getPageList($arrParam,1);

			if(is_array($arrRes) && count($arrRes)>0)
			{
				$iCount = 0;
				foreach ($arrRes as $val)
				{
					$arrRes[$iCount]['LoginContent'] = Utility::gb2312ToUtf8($val['LoginContent']);
					$arrRes[$iCount]['OpPlace'] = $this->arrConfig['OpPlace'][$val['TypeID']];
					$arrRes[$iCount]['LoginResult'] = $val['LoginResult']==0 ? '成功' : '失败';
					$arrRes[$iCount]['MachineSerial'] = !empty($val['MachineSerial']) && strlen($val['MachineSerial'])<20 ? '----' : $val['MachineSerial'];
					$iCount++;
				}
				$iNum = count($arrRes);
//				$arrRes[0]['PrevStartDate'] = date('Y-m-d',strtotime($arrRes[0]['LoginTime']));
//				$arrRes[0]['PrevLogsID'] = $arrParam['RowIndex']==0 ? 0 : $arrRes[0]['LogsID']+1;						
//				//记录上一页的状态位
//				setcookie($strCookPrevParams1.$iCurPage,$arrRes[0]['PrevStartDate'],0,'/');
//				setcookie($strCookPrevParams2.$iCurPage,$arrRes[0]['PrevLogsID'],0,'/');					
					
					
				//记录下一页的状态位
				$NextStartDate = date('Y-m-d',strtotime($arrRes[$iNum-1]['LoginTime']));	
				$NextLogsID = $arrRes[$iNum-1]['LogsID'];
				//$NextStartDate=$arrRes[0]['NextStartDate'];
			
				$Page=Utility::setSimplePages($iPageAll,$arrParam['iCurPage'],$NextLogsID,$NextStartDate);
			}			
		}
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'LoginLogsList'=>$arrRes); 
		Utility::assign($this->smarty,$arrTags);				
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/RolePlayerLoginLogsDetail.html');			
		$html=str_replace("\r\n",'',$html);
		echo $html;

	}
	/**
	 * 角色日志——玩家日志详情(操作日志)
	 */
	private function getPlayerOperationLogsDetail()
	{
		$Page = null;
		$arrRes = null;
		$iRoleID = Utility::isNumeric('RoleID', $_POST);
		$iKindID = Utility::isNumeric('KindID', $_POST);
		//$OpType = Utility::isNumeric('OpTypeID', $_POST);//操作类型
		$arrParam['EndDate'] = Utility::isNullOrEmpty('LogsEndTime', $_POST);
		$arrParam['StartDate'] = Utility::isNullOrEmpty('LogsStartTime', $_POST);	
		$isFlag = Utility::isNumeric('sFlag', $_POST);	//删除缓存标识
		//从第几条开始读取
		$arrParam['RowIndex'] = Utility::isNumeric('LogsID',$_POST) ? $_POST['LogsID'] : 0;	
			
		$strCookRecordCount = $this->arrConfig['Cookies']['iRecordsCount'].$iRoleID;
//		$strCookPrevParams1 = $this->arrConfig['Cookies']['PrevParams1'].$iRoleID;
//		$strCookPrevParams2 = $this->arrConfig['Cookies']['PrevParams2'].$iRoleID;
		//当前分页
		$iCurPage = Utility::isNumeric('curPage',$_POST);
		$arrParam['iCurPage'] = $iCurPage > 0 ? $iCurPage : 1;
		//查询条件
		$arrParam['where'] = ' WHERE RoleID='.$iRoleID;
		$arrParam['fields'] = 'CONVERT(VARCHAR(20),AddTime,120) AS AddTime,LogsID,RoleID,OpType,ClientIP,MachineSerial,OpResult,OpContent,SysUserName,OpPlace';
		//每页显示数量
		$arrParam['pagesize'] = 15;
		$arrParam['tableName']='T_OperationLogs_';
		$arrParam['order'] = 'AddTime DESC';		
			
		
		$objOperationLogsBLL = new OperationLogsBLL($iRoleID);
		//总记录数

		if(!isset($_COOKIE[$strCookRecordCount]))
		{
			$iRecordCount = $objOperationLogsBLL->getRecordsCount($arrParam);
			setcookie($strCookRecordCount,$iRecordCount);
		}
		else
			$iRecordCount = $_COOKIE[$strCookRecordCount];

		if($iRecordCount>0)
		{
			//总分页数
			$iPageAll = $iRecordCount==0 ? 1 : ceil($iRecordCount/$arrParam['pagesize']);	
			$arrParam['memName'] = 'PlayerOperationLogsDetail';
			//单击搜索清除缓存
			if($isFlag){
				$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['OperationLogs'],$iRoleID);
				$objCommonBLL->delSimplePageMemcache($arrParam['memName'], $iPageAll);
			}	
//			//从cookies取上一页的状态位,上一页开始日期,上一页开始取的ID起始值
//			$PrevStartDate = $arrParam['EndDate'];
//			$PrevLogsID=$arrParam['RowIndex'];
//			if(isset($_COOKIE[$strCookPrevParams1]))
//				$PrevStartDate=$_COOKIE[$strCookPrevParams1];
//			if(isset($_COOKIE[$strCookPrevParams2]))
//				$PrevLogsID=$_COOKIE[$strCookPrevParams2];
					
			//分页读取记录
			$arrRes = $objOperationLogsBLL->getPageList($arrParam,1);
			if(is_array($arrRes) && count($arrRes)>0)
			{
				$iCount = 0;
				foreach ($arrRes as $val)
				{
					$arrRes[$iCount]['OpContent'] = Utility::gb2312ToUtf8($val['OpContent']);
					$arrRes[$iCount]['OpPlace'] = $this->arrConfig['OpPlace'][$val['OpPlace']];
					$arrRes[$iCount]['OpType'] = $this->arrConfig['OpType'][$val['OpType']];
					$arrRes[$iCount]['OpResult'] = $val['OpResult']==0 ? '成功' : '失败';
					$iCount++;
				}
				$iNum = count($arrRes);
//				$arrRes[0]['PrevStartDate'] = date('Y-m-d',strtotime($arrRes[0]['AddTime']));
//				$arrRes[0]['PrevLogsID'] = $arrParam['RowIndex']==0 ? 0 : $arrRes[0]['LogsID']+1;						
//				//记录上一页的状态位
//				setcookie($strCookPrevParams1.$iCurPage,$arrRes[0]['PrevStartDate'],0,'/');
//				setcookie($strCookPrevParams2.$iCurPage,$arrRes[0]['PrevLogsID'],0,'/');					
					
				//记录下一页的状态位
				$NextStartDate = date('Y-m-d',strtotime($arrRes[$iNum-1]['AddTime']));	
				$NextLogsID = $arrRes[$iNum-1]['LogsID'];
				//$NextStartDate=$arrRes[0]['NextStartDate'];
			
				$Page=Utility::setSimplePages($iPageAll,$arrParam['iCurPage'],$NextLogsID,$NextStartDate);
			}			
		}
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'OpLogsList'=>$arrRes); 
		Utility::assign($this->smarty,$arrTags);				
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/RolePlayerLogsDetail.html');			
		$html=str_replace("\r\n",'',$html);
		echo $html;
	}
	
	/**
	 * 角色日志——锁定/封号日志
	 */
	public function getLockAccountLogs()
	{
		$arrResult = null;
		$Page = null;
		$RoleID = Utility::isNumeric('RoleID', $_POST);	
		$OpResult = $_POST['OpResult'];	
		$CaseSerial	= Utility::isNullOrEmpty('CaseSerial', $_POST);	
		$LogsStartTime = Utility::isNullOrEmpty('LogsStartTime', $_POST);	
		$LogsEndTime = Utility::isNullOrEmpty('LogsEndTime', $_POST);	
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage==0 ? 1 : $curPage;
		if($RoleID && $RoleID>0)
		{		
			$strWhere = '';	
			if($OpResult>=0) $strWhere .= ' AND Result='.$OpResult;
			if($CaseSerial) $strWhere .= ' AND CaseSerial='.$CaseSerial;
			if($LogsStartTime) $strWhere .= ' AND DATEDIFF(d,\''.$LogsStartTime.'\',AddTime)>=0';
			if($LogsEndTime) $strWhere .= ' AND DATEDIFF(d,\''.$LogsEndTime.'\',AddTime)<=0';
			$arrParam['fields']='CONVERT(VARCHAR(20),AddTime,120) AS AddTime,Reason,Result,LockExpireTime,Requirement,CaseSerial,SysUserName,Remarks,ClientIP';
			$arrParam['tableName']='T_LockUserLogs';
			$arrParam['where']=' WHERE RoleID='.$RoleID.$strWhere;
			$arrParam['order']='AddTime DESC';
			$arrParam['pagesize'] = 15;
            //var_dump($arrParam);exit;
			$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['OperationLogs'],$RoleID);
			$iRecordsCount = $objCommonBLL->getRecordsCountSelect($arrParam);
			if($iRecordsCount>0)
			{
				$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
				$arrResult = $objCommonBLL->getPageListSelect($arrParam,$Page['CurPage']);
				if(is_array($arrResult) && count($arrResult)>0)
				{
					$iCount = 0;					
					foreach ($arrResult as $val)
					{					
						$arrResult[$iCount]['Reason'] = str_replace(array("\r\n", "\r", "\n"),'<br />',Utility::gb2312ToUtf8($val['Reason']));
						$arrResult[$iCount]['SysUserName'] = Utility::gb2312ToUtf8($val['SysUserName']);
						$arrResult[$iCount]['Remarks'] = str_replace(array("\r\n", "\r", "\n"),'<br />',Utility::gb2312ToUtf8($val['Remarks']));						
						$arrResult[$iCount]['Result'] = Utility::gb2312ToUtf8($val['Result']);	
						$arrResult[$iCount]['LockExpireTime'] = Utility::gb2312ToUtf8($val['LockExpireTime']);
						$arrResult[$iCount]['Requirement'] = Utility::gb2312ToUtf8($val['Requirement']);
						$iCount++;
					}
				}		
			}
		}
		
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'LockAccountList'=>$arrResult);
		Utility::assign($this->smarty,$arrTags);				
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/RoleLockAccountLogs.html');	
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	
	/**
	 * 角色日志——财富冻结日志
	 */
    public function getTreasureFreezeLogs(){
        $arrResult = null;
        $Page = null;
        $RoleID = Utility::isNumeric('RoleID',$_POST);
        $OpStep = $_POST['OpStep'];
        $CaseSerial	= Utility::isNullOrEmpty('CaseSerial', $_POST);
        $LogsStartTime = Utility::isNullOrEmpty('LogsStartTime', $_POST);
        $LogsEndTime = Utility::isNullOrEmpty('LogsEndTime', $_POST);

        $curPage = Utility::isNumeric('curPage',$_POST);
        $curPage = $curPage==0 ? 1 : $curPage;
        if($RoleID && $RoleID>0)
        {
            $strWhere = '';
            if($OpStep>=0) $strWhere .= ' AND Step='.$OpStep;
            if($CaseSerial) $strWhere .= ' AND CaseSerial='.$CaseSerial;
            if($LogsStartTime) $strWhere .= ' AND DATEDIFF(d,\''.$LogsStartTime.'\',AddTime)>=0';
            if($LogsEndTime) $strWhere .= ' AND DATEDIFF(d,\''.$LogsEndTime.'\',AddTime)<=0';
            $arrParam['fields']='CONVERT(VARCHAR(20),AddTime,120) AS AddTime,iMoney,iFwMoney,CaseSerial,Step,LoginName,SysUserName,Remarks';
            $arrParam['tableName']='T_LockMoneyLogs';
            $arrParam['where']=' WHERE RoleID='.$RoleID.$strWhere;
            $arrParam['order']='AddTime DESC';
            $arrParam['pagesize'] = 15;

            $objCommonBLL = new CommonBLL($this->arrConfig['MapType']['DataChangeLogs'],$RoleID);
            $iRecordsCount = $objCommonBLL->getRecordsCountSelect($arrParam);
            if($iRecordsCount>0)
            {
                $Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
                $arrResult = $objCommonBLL->getPageListSelect($arrParam,$Page['CurPage']);
                if(is_array($arrResult) && count($arrResult)>0)
                {
                    $iCount = 0;
                    $arrStep=array('冻结','申请返还','已返还','拒绝');
                    foreach ($arrResult as $val)
                    {
                        $arrResult[$iCount]['LoginName'] = str_replace(array("\r\n", "\r", "\n"),'<br />',Utility::gb2312ToUtf8($val['LoginName']));
                        $arrResult[$iCount]['SysUserName'] = Utility::gb2312ToUtf8($val['SysUserName']);
                        $arrResult[$iCount]['Remarks'] = str_replace(array("\r\n", "\r", "\n"),'<br />',Utility::gb2312ToUtf8($val['Remarks']));
                        $arrResult[$iCount]['StepName'] = $arrStep[$val['Step']];
                        $arrResult[$iCount]['iMoney'] =sprintf('%.2f',$arrResult[$iCount]['iMoney']/1000);
                        $iCount++;
                    }
                }
            }
        }

        $arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'TreasureList'=>$arrResult);
        Utility::assign($this->smarty,$arrTags);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/RoleTreasureFreezeLogs.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }
	/*public function getTreasureFreezeLogs()
	{
		$arrResult = null;
		$Page = null;
		$RoleID = Utility::isNumeric('RoleID', $_POST);	
		$OpStep = $_POST['OpStep'];	
		$CaseSerial	= Utility::isNullOrEmpty('CaseSerial', $_POST);	
		$LogsStartTime = Utility::isNullOrEmpty('LogsStartTime', $_POST);	
		$LogsEndTime = Utility::isNullOrEmpty('LogsEndTime', $_POST);	
			
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage==0 ? 1 : $curPage;
		if($RoleID && $RoleID>0)
		{		
			$strWhere = '';	
			if($OpStep>=0) $strWhere .= ' AND Step='.$OpStep;
			if($CaseSerial) $strWhere .= ' AND CaseSerial='.$CaseSerial;
			if($LogsStartTime) $strWhere .= ' AND DATEDIFF(d,\''.$LogsStartTime.'\',AddTime)>=0';
			if($LogsEndTime) $strWhere .= ' AND DATEDIFF(d,\''.$LogsEndTime.'\',AddTime)<=0';
			$arrParam['fields']='CONVERT(VARCHAR(20),AddTime,120) AS AddTime,iMoney,iFwMoney,CaseSerial,Step,LoginName,SysUserName,Remarks';
			$arrParam['tableName']='T_LockMoneyLogs';
			$arrParam['where']=' WHERE RoleID='.$RoleID.$strWhere;
			$arrParam['order']='AddTime DESC';
			$arrParam['pagesize'] = 15;
			
			$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['DataChangeLogs'],$RoleID);
			$iRecordsCount = $objCommonBLL->getRecordsCountSelect($arrParam);
			if($iRecordsCount>0)
			{
				$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
				$arrResult = $objCommonBLL->getPageListSelect($arrParam,$Page['CurPage']);
				if(is_array($arrResult) && count($arrResult)>0)
				{
					$iCount = 0;
					$arrStep=array('冻结','申请返还','已返还','拒绝');
					foreach ($arrResult as $val)
					{					
						$arrResult[$iCount]['LoginName'] = str_replace(array("\r\n", "\r", "\n"),'<br />',Utility::gb2312ToUtf8($val['LoginName']));
						$arrResult[$iCount]['SysUserName'] = Utility::gb2312ToUtf8($val['SysUserName']);
						$arrResult[$iCount]['Remarks'] = str_replace(array("\r\n", "\r", "\n"),'<br />',Utility::gb2312ToUtf8($val['Remarks']));						
						$arrResult[$iCount]['StepName'] = $arrStep[$val['Step']];
						$iCount++;
					}
				}		
			}
		}
		
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'TreasureList'=>$arrResult);
		Utility::assign($this->smarty,$arrTags);				
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/RoleTreasureFreezeLogs.html');	
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}*/
	
	/**
	 * 比赛汇总
	 */
	function getGameSummaryDetail()
	{
		//当前分页
		$iCurPage = Utility::isNumeric('curPage', $_POST) ? intval($_POST['curPage']) : 1;
		$iRoleID = Utility::isNumeric('RoleID', $_POST);
		if($iRoleID && $iRoleID>0)
		{	
			//查询条件
			$arrParam['fields']='MatchTypeID,KindID,PlayCount,FleeCount,GoldMedalistCount,BestRank,CONVERT(VARCHAR(20),UpdateTime,120) AS UpdateTime';
			$arrParam['tableName']='T_GameMatchData';
			$arrParam['where']=' WHERE RoleID='.$iRoleID;	
			$arrParam['order']='UpdateTime DESC';
			$arrParam['pagesize']=1;
			
			$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['UserData'],$iRoleID);
			$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam); 
			$Page=Utility::setPages($iCurPage,$iRecordsCount,$arrParam['pagesize']);
			$arrResult = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);			
			if($arrResult)
			{
				for($i=0;$i<count($arrResult);$i++)
				{
					$arrMatch = $this->objMasterBLL->getGameMatchByID($arrResult[$i]['MatchTypeID'],1);
					$arrResult[$i]['MatchName'] = Utility::gb2312ToUtf8($arrMatch['MatchName']);
					$arrKindInfo = $this->objMasterBLL->getGameKindInfo($arrResult[$i]['KindID']);
					if(is_array($arrKindInfo) && count($arrKindInfo)>0)
						$arrResult[$i]['KindName'] = $arrKindInfo['KindName'];
					else 
						$arrResult[$i]['KindName'] = '';
				}
			}
			$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'result'=>$arrResult); 
			Utility::assign($this->smarty,$arrTags);
			$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/RoleGameSummaryDetail.html');	
			$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
			echo $html;	
		}
	} 
	
	/**
	 * 加载比赛模式查询
	 */
	function getGameSearchMode()
	{
		$RoleID = Utility::isNumeric('RoleID',$_REQUEST);
		$iTypeID = Utility::isNumeric('typeID', $_REQUEST);		//比赛模式
		$strMatchModeName = Utility::isNullOrEmpty('MatchModeName', $_REQUEST);

		$arrMatchList = $this->objMasterBLL->getGameMatchByID($iTypeID,2);//取比赛列表
		
		$arrTags=array('skin'=>$this->arrConfig['skin'], 
					   'nowDate'=>date('Y-m-d', time()),
					   'MatchModeName'=>$strMatchModeName, 
					   'GameMatchList'=>$arrMatchList, 
					   'RoleID'=>$RoleID);
		Utility::assign($this->smarty,$arrTags);				
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/RoleGameSearchMode.html');	
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	
	/**
	 * 根据比赛类型获取比赛场地
	 */
	function getGameRoomList()
	{
		$iMatchTypeID = Utility::isNumeric('MatchTypeID', $_REQUEST);	//比赛类型ID
		$arrGameRoomList = $this->objMasterBLL->getGameRoomList($iMatchTypeID,4);//取66人赛模式的比赛房间
		
		$arrTags=array('skin'=>$this->arrConfig['skin'], 'GameRoomList'=>$arrGameRoomList);
		Utility::assign($this->smarty,$arrTags);				
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Common/GameRoomList.html');	
		$html=str_replace("\r\n",'',$html);
		echo $html;
	}
	
	/**
	 * 比赛模式查询结果列表
	 */
	function getGameSearchModeResultList()
	{
		$RoleID = Utility::isNumeric('RoleID',$_POST);
		$StartTime = Utility::isNullOrEmpty('StartTime', $_POST);
		$EndTime = Utility::isNullOrEmpty('EndTime', $_POST);
		$MatchTypeID = Utility::isNullOrEmpty('MatchTypeID', $_POST);
		$RoomID = Utility::isNullOrEmpty('RoomID', $_POST);
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage==0 ? 1 : $curPage;
		$strWhere = ' WHERE B.RoleID='.$RoleID;		
		if($MatchTypeID) $strWhere .= " AND A.MatchTypeID IN ($MatchTypeID)";
		if($RoomID) $strWhere .= " AND A.RoomID IN ($RoomID)";
		if($StartTime) $strWhere .= " AND DATEDIFF(d,A.AddTime,'$StartTime')<=0";
		if($EndTime) $strWhere .= " AND DATEDIFF(d,A.AddTime,'$EndTime')>=0";
		
		$arrResult = $this->getPagerGameMatch($curPage,$strWhere);
		$arrTags=array('skin'=>$this->arrConfig['skin'], 'RoleID'=>$RoleID, 'Page'=>$arrResult['Page'],'MatchList'=>$arrResult['arrMatchList']); 
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/RoleGameMatchListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	
	/**
	 * 分页
	 */
	private function getPagerGameMatch($curPage,$strWhere)
	{
		$arrParam['fields']='A.MatchUnitID,A.MatchTypeID,A.RoomID,CONVERT(VARCHAR(20),A.MatchStartTime,120) AS MatchStartTime,CONVERT(VARCHAR(20),A.MatchEndTime,120) AS MatchEndTime,A.iTimes,CONVERT(VARCHAR(10),A.AddTime,120) AS PlayDate, B.Rank, B.Prize, B.SendStatus, CONVERT(VARCHAR(10),B.SignUpTime,120) AS SignUpTime';
		$arrParam['tableName']='T_GameMatchUnit AS A LEFT JOIN T_GameMatchRank AS B ON A.MatchUnitID=B.MatchUnitID';
		$arrParam['where']=$strWhere;
		$arrParam['order']='A.AddTime DESC';
		$arrParam['pagesize']=15;

		$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['Match']);
		$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
		$arrMatchList = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);	
		if($arrMatchList)
		{
			$iCount = 0;
			$arrSendStatus = array(1=>'发放成功', 2=>'发放失败', 3=>'弃赛不发放');
			foreach ($arrMatchList as $val)
			{
				//读取比赛名称
				$arrMatchInfo = $this->objMasterBLL->getGameMatchByID($val['MatchTypeID'],1);
				if($arrMatchInfo)
					$arrMatchList[$iCount]['MatchName'] = Utility::gb2312ToUtf8($arrMatchInfo['MatchName']);
				else 
					$arrMatchList[$iCount]['MatchName'] = '';
				//读取比赛房间
				$arrRoomInfo = $this->objMasterBLL->getGameRoomList($val['RoomID'],2);
				if($arrRoomInfo)
					$arrMatchList[$iCount]['RoomName'] = $arrRoomInfo[0]['RoomName'];
				else 
					$arrMatchList[$iCount]['RoomName'] = '';
				$arrMatchList[$iCount]['SendStatusTip'] = $arrSendStatus[$val['SendStatus']];
				$iCount++;
			}
		}
		return array('arrMatchList'=>$arrMatchList,'Page'=>$Page);
	}
	
	/**
	 * 分页读取单元赛排名
	 */
	public function getPagerGameMatchRankList()
	{		
		$iRoleID = Utility::isNumeric('RoleID', $_GET);
		$iMatchUnitID = Utility::isNumeric('MatchUnitID',$_GET);
		$MatchTypeID = Utility::isNumeric('MatchTypeID',$_GET);
		$PlayDate = Utility::isNullOrEmpty('PlayDate', $_GET);
		$arrResult = $this->getPagerGameMatchRank($iMatchUnitID);
		if($arrResult)
		{		
			$arrTags=array( 'skin'=>$this->arrConfig['skin'],
							'Page'=>$arrResult['Page'],
							'MatchRankList'=>$arrResult['arrMatchRankList'],
							'RoleID'=>$iRoleID,
							'MatchUnitID'=>$iMatchUnitID,
							'PlayDate'=>$PlayDate	
							); 
			Utility::assign($this->smarty,$arrTags);
			$this->smarty->display($this->arrConfig['skin'].'/Service/RoleGameMatchRankList.html');
		}		
	}
	
	private function getPagerGameMatchRank($iMatchUnitID)
	{		
		if($iMatchUnitID && $iMatchUnitID>0)
		{
			$LoginID = Utility::isNumeric('LoginID', $_POST);
		
			$strWhere = '';		
			if($LoginID) $strWhere .= " AND LoginID=$LoginID";		
		
			$curPage = Utility::isNumeric('curPage',$_POST);
			$curPage = $curPage==0 ? 1 : $curPage;
			$arrParam['fields']='Rank,RoleID,CONVERT(VARCHAR(8),AddTime,108) AS LeaveTime,LoginCode,LoginName,LoginID,Prize,SendStatus,Remarks';
			$arrParam['tableName']='T_GameMatchRank';
			$arrParam['where']=' WHERE MatchUnitID='.$iMatchUnitID.$strWhere;
			$arrParam['order']='Rank ASC';
			$arrParam['pagesize']=18;
			
			$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['Match']);
			$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
			$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
			$arrMatchRankList = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);	
			if($arrMatchRankList)
			{
				$iCount = 0;
				foreach ($arrMatchRankList as $val)
				{
					$arrMatchRankList[$iCount]['LoginName'] = Utility::gb2312ToUtf8($val['LoginName']);
					$arrMatchRankList[$iCount]['Remarks'] = Utility::gb2312ToUtf8($val['Remarks']);
					$iCount++;
				}
			}
			return array('arrMatchRankList'=>$arrMatchRankList,'Page'=>$Page);
		}
		return null;
	}
	
	/**
	 * 分页读取单元赛排名
	 */
	public function getPagerGameMatchRankList1()
	{
		$iMatchUnitID = Utility::isNumeric('MatchUnitID',$_POST);
		$arrResult = $this->getPagerGameMatchRank($iMatchUnitID);
		if($arrResult)
		{			
			$arrTags=array( 'skin'=>$this->arrConfig['skin'],
							'Page'=>$arrResult['Page'],
							'MatchRankList'=>$arrResult['arrMatchRankList']							
							); 
			Utility::assign($this->smarty,$arrTags);
			$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/RoleGameMatchRankListPage.html');
			$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
			echo $html;
		}		 
	}	
	
	/**
	 * 读取单元赛每局记录
	 */
	public function getGameMatchInningsList()
	{
		$iMatchUnitID = Utility::isNumeric('MatchUnitID',$_GET);
		$RoleID = Utility::isNumeric('RoleID',$_GET);
		$PlayDate = Utility::isNullOrEmpty('PlayDate', $_GET);
		if($iMatchUnitID && $iMatchUnitID>0 && $RoleID && $RoleID>0 && $PlayDate)
		{
			$objDataChangeLogsBLL = new DataChangeLogsBLL($RoleID);
			$arrResult = $objDataChangeLogsBLL->getGameMatchLogsList($PlayDate,$iMatchUnitID,$RoleID);
			if($arrResult)
			{
				$iCount = 0;
				foreach ($arrResult as $val)
				{
					$arrResult[$iCount]['MatchStatus']=$this->arrConfig['MatchRound'][$val['MatchStatus']];
					$arrResult[$iCount]['PlayStatus']=$this->arrConfig['PlayStatus'][$val['PlayStatus']];
					$Score = $val['CurScore']-$val['LastScore'];
					$arrResult[$iCount]['Score']=$Score>0 ? '+'.$Score:'<font class="red">'.$Score.'</font>';
					$iCount++;
				}
			}
			$arrTags=array( 'skin'=>$this->arrConfig['skin'],
							'MatchInningsList'=>$arrResult,
							'MatchUnitID'=>$iMatchUnitID,
							'RoleID'=>$RoleID,
							'PlayDate'=>$PlayDate					
							); 
			Utility::assign($this->smarty,$arrTags);
			$this->smarty->display($this->arrConfig['skin'].'/Service/RoleGameMatchInningsList.html');
		}			
	}
	
	/**
	 * 读取单元赛同桌玩家记录
	 */
	private $arrTmpRoleID = array();//已经读取到记录的玩家RoleID
	private $arrDeskPlayer = null;
	public function getGameMatchDeskPlayerList()
	{
		$RoleID = Utility::isNumeric('RoleID',$_GET);
		$PlayDate = Utility::isNullOrEmpty('PlayDate', $_GET);
		$SerialNumber = Utility::isNumeric('SerialNumber', $_GET);
		if($SerialNumber && $SerialNumber>0 && $RoleID && $RoleID>0 && $PlayDate)
		{			
			//读取同桌玩家信息
			$this->getGameMatchDeskPlayer($RoleID,$PlayDate,$SerialNumber);
			if($this->arrDeskPlayer)
			{
				$iCount = 0;
				foreach ($this->arrDeskPlayer as $val)
				{
					$this->arrDeskPlayer[$iCount]['LoginName'] = Utility::gb2312ToUtf8($val['LoginName']);
					$this->arrDeskPlayer[$iCount]['PlayStatus'] = $this->arrConfig['PlayStatus'][$val['PlayStatus']];
					$Score = $val['CurScore']-$val['LastScore'];
					$this->arrDeskPlayer[$iCount]['Score'] = $Score>0 ? '+'.$Score:'<font class="red">'.$Score.'</font>';
					$iCount++;
				}
			}
			$arrTags=array( 'skin'=>$this->arrConfig['skin'],
							'MatchDeskPlayerList'=>$this->arrDeskPlayer				
							); 
			Utility::assign($this->smarty,$arrTags);
			$this->smarty->display($this->arrConfig['skin'].'/Service/RoleGameMatchDeskPlayerList.html');
		}
	}
	/**
	 * 递归读取同桌玩家
	 * @param $RoleID
	 * @param $PlayDate
	 * @param $SerialNumber 每局游戏流水号
	 */
	private function getGameMatchDeskPlayer($RoleID,$PlayDate,$SerialNumber)
	{
		$objDataChangeLogsBLL = new DataChangeLogsBLL($RoleID);
		$arrResult = $objDataChangeLogsBLL->getGameMatchDeskPlayerList($PlayDate,$SerialNumber);
		if($arrResult)
		{
			$DeskPlayer = $arrResult[0]['RoleID'].','.$arrResult[0]['DeskPlayer'];//同桌所有玩家RoleID(包括自己)
			$arrAllRoleID = explode(',', $DeskPlayer);
			foreach ($arrResult as $val)
				array_push($this->arrTmpRoleID, $val['RoleID']);//把已经读取到记录的玩家RoleID存入到数组
			$arrNewRoleID = array_diff($arrAllRoleID, $this->arrTmpRoleID);//剩余还未读取记录的RoleID
			if(empty($this->arrDeskPlayer))
				$this->arrDeskPlayer = $arrResult;
			else
				$this->arrDeskPlayer = array_merge($this->arrDeskPlayer,$arrResult);//合并之前读取到的记录和当前读取到的记录
			if(is_array($arrNewRoleID) && count($arrNewRoleID)>0)
			{
				$arrKeys = array_keys($arrNewRoleID);//取数组下标
				if(!empty($arrNewRoleID[$arrKeys[0]]))					
					$this->getGameMatchDeskPlayer($arrNewRoleID[$arrKeys[0]],$PlayDate,$SerialNumber);//递归调用
			}			
		}
		return;
	}
	
	/**
	 * 加载修改操作
	 */
	function getModifyDetail()
	{
		$RoleID = Utility::isNumeric('RoleID',$_REQUEST);	
		//获取管理员所在部门
		$objSessioinLogin = new Session($this->arrConfig['Session']['SessionLoginName']);
        $DeptID = $objSessioinLogin->get($this->arrConfig['SessionInfo']['DeptID']);
		
		/*$objUserBLL = new UserBLL($RoleID);
		$result = $objUserBLL->getRoleInfo($RoleID);
		$objUserDataBLL = new UserDataBLL($RoleID);
		//用户账号锁定状态 0:有锁,-1:没锁
		$lockStatus = $objUserDataBLL->checkUserLockInfo();		
		//检查用户表是否锁定 0:锁定,-1:未锁定
		$roleLockStatus = $objUserBLL->checkUserLockStatus();*/


       /* $asRoleBaseInfo = ASGetRoleBaseInfo($RoleID);
        $dsRoleBaseInfo = DSGetRoleBaseInfo($RoleID);
        //合并两个数组转化格式
        $keyMap = array("szLoginName"=>"LoginName","iLoginID"=>"LoginID","szMobilePhone"=>"MobilePhone","szQQ"=>"QQ",
            "iMoorMachine"=>"MoorMachine","iLockStartTime"=>"LockStartTime","iTitleID"=>"TitleID","iLockEndTime"=>"LockEndTime",
            "iLocked"=>"Locked","iLoginCount"=>"LoginCount","szLastLoginIP"=>"LastLoginIP","iLastLoginTime"=>"LastLoginTime",
            "szRegIP"=>"RegIP","iAddTime"=>"AddTime",
        );
        $asRoleBaseInfo = Utility::arrReplaceKey($asRoleBaseInfo,$keyMap);
        $keyMap = array("iRoleID"=>"RoleID","szRealName"=>"RealName","iGender"=>"Gender","iVipID"=>"VipID","szSignature"=>"Signature",
            "iVipExpireTime"=>"VipExpireTime","iVipOpeningTime"=>"VipOpeningTime","iRoomName"=>"RoomName"
        );
        $dsRoleBaseInfo = Utility::arrReplaceKey($dsRoleBaseInfo,$keyMap);*/

        $result = getUserBaseInfo($RoleID);//array_merge($asRoleBaseInfo,$dsRoleBaseInfo);
        //var_dump($result);
		if(!$result['Locked']){
			$lFlag = 0;	//显示锁定角色
		}else{
			$lFlag = 1;
		}
		//封号状态
		$pFlag = $result['Blocked']?1:0;

        //玩家是否游戏中
        $InGame = $result['RoomID'] || $result['GameLock'];

        //游戏锁定， 非零 为 KindID
        $GameLock = $result['GameLock'];



		/*//检查解除银行、背包冻结状态
		$BMStatus = $objUserDataBLL->checkBankMyKnapsackStatus();

		//是否绑定安全手机(登陆绑定)
		$objPassSecurityBLL = new PassSecurityBLL();
		$BindMobile = $objPassSecurityBLL->getSecurityInfo($result['Passport'],6);
		//是否显示账户检查和积分检查
		$show = true;
		$time = $this->ReturnTime();
		if(!$time)
			$show = false;
		else 
		{
			$Wealth = $this->ReturnMoney($RoleID);
			if(!$Wealth) $show = false;
		}*/
		/*'BMStatus'=>$BMStatus, 'BindMobile'=>$BindMobile,'Show'=>$show*/
        $date = getdate();
        if($date['hours']=='01' && $date['minutes']<=10){
            $RightTime = 1;
        }else{
            $RightTime = 0;
        } 
		$arrTags=array('RoleID'=>$RoleID, 'lFlag'=>$lFlag, 'pFlag'=>$pFlag, 'result'=>$result,'DeptID'=>$DeptID,'InGame'=>$InGame,'RightTime'=>$RightTime);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/RoleModifyDetail.html');
		$html = str_replace("\r\n",'',$html);
		echo $html;
	}


    function showBakInfo(){
        $RoleId = Utility::isNumeric('roleID',$_REQUEST);

        if($RoleId && $RoleId>0) {
            $objMasterBLL = new  MasterBLL();
            $arrRes = $objMasterBLL->getColorTop($RoleId);
            $arrRes= $arrRes[0];
        }
        else
            $arrRes=array('$RoleId'=>$RoleId,'descript'=>'');
        //print($proxy["ProxyId"]);exit();
        $objSessioinLogin = new Session($this->arrConfig['Session']['SessionLoginName']);
        $DeptID = $objSessioinLogin->get($this->arrConfig['SessionInfo']['DeptID']);

        $arrTags=array('RoleID'=>$RoleId, 'DeptID'=>$DeptID,"des"=>$arrRes);
        Utility::assign($this->smarty,$arrTags);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/SetUserBankInfo.html');
        $html = str_replace("\r\n",'',$html);
        echo json_encode(array("Result"=>$html));
    }



    function updateBakInfo(){
        $RoleId = Utility::isNumeric('roleID',$_REQUEST);
        $descript = Utility::isNullOrEmpty('descript', $_REQUEST); //锁定原因

        $iResult =0;
        $objMasterBLL = new  MasterBLL();
        if($RoleId && $RoleId>0) {
            $arrResult = $objMasterBLL->addOnlineDes($RoleId,$descript);
            $iResult = isset($arrResult['iResult'])?$arrResult['iResult']:0;
        }

        $objSessioin = new Session($this->arrConfig['Session']['SessionLoginName']);
        $SysUserName = $objSessioin->get($this->arrConfig['SessionInfo']['UserName']);
        $objOperationLogsBLL = new OperationLogsBLL(0);
        $objOperationLogsBLL->addCaseOperationLogs($RoleId, 0, 32, '修改玩家备注'.$descript,$iResult , Utility::getIP(), 0, 2, $SysUserName, '');

        echo $iResult;

    }


	function showAgentInfo(){
        $RoleID = Utility::isNumeric('roleID',$_REQUEST);
        $objCDAccountBLL = new CDAccountBLL();
       // print_r();
        //print($RoleID);

        $proxy=$objCDAccountBLL->getProxyId($RoleID);
        //print($proxy["ProxyId"]);exit();
        $objSessioinLogin = new Session($this->arrConfig['Session']['SessionLoginName']);
        $DeptID = $objSessioinLogin->get($this->arrConfig['SessionInfo']['DeptID']);

        $arrTags=array('RoleID'=>$RoleID, 'DeptID'=>$DeptID,"proxy"=>$proxy["ProxyId"]);
        Utility::assign($this->smarty,$arrTags);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/SetAgentInfo.html');
        $html = str_replace("\r\n",'',$html);
        echo json_encode(array("Result"=>$html));
    }

    public function updateProxyId(){
        $iRoleID = Utility::isNumeric('roleID',$_REQUEST);
        $ProxyId = Utility::isNullOrEmpty('ProxyId', $_REQUEST); //锁定原因
        $objCDAccountBLL = new CDAccountBLL();
        $arrResult = $objCDAccountBLL->setProxyId($iRoleID,$ProxyId);
        $iResult = isset($arrResult['iResult'])?$arrResult['iResult']:-1;

        $objSessioin = new Session($this->arrConfig['Session']['SessionLoginName']);
        $SysUserName = $objSessioin->get($this->arrConfig['SessionInfo']['UserName']);
        $objOperationLogsBLL = new OperationLogsBLL(0);
        $objOperationLogsBLL->addCaseOperationLogs($iRoleID, 0, 32, '修改商户代理ID'.$ProxyId,$iResult , Utility::getIP(), 0, 2, $SysUserName, '');

        echo $iResult;

    }



    function showAliPayInfo(){
        $RoleID = Utility::isNumeric('roleID',$_REQUEST);

        $objUserBLL = new UserBLL($RoleID);
        $payinfo = $objUserBLL->getpayWay($RoleID);
        if($payinfo) {
            $payinfo["UserName"] = Utility::gb2312ToUtf8($payinfo["UserName"]);
            $payinfo["BankName"] = Utility::gb2312ToUtf8($payinfo["BankName"]);

            $objSessioinLogin = new Session($this->arrConfig['Session']['SessionLoginName']);
            $DeptID = $objSessioinLogin->get($this->arrConfig['SessionInfo']['DeptID']);
        }

        $arrTags=array('RoleID'=>$RoleID, 'DeptID'=>$DeptID,"payinfo"=>$payinfo);
        Utility::assign($this->smarty,$arrTags);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/SetPayMentInfo.html');
        $html = str_replace("\r\n",'',$html);
        echo json_encode(array("Result"=>$html));
    }


    function updatePayMent(){
        $RoleID = Utility::isNumeric('roleID',$_REQUEST);
        $username = Utility::isNullOrEmpty('username', $_REQUEST);
        $BankName = Utility::isNullOrEmpty('BankName', $_REQUEST);
        $BankCardNo = Utility::isNullOrEmpty('BankCardNo', $_REQUEST);
        $PayWayType = Utility::isNullOrEmpty('PayWayType', $_REQUEST); //锁定原因
        $iResult =-1;
        if($RoleID){
            $objSessioin = new Session($this->arrConfig['Session']['SessionLoginName']);
            $SysUserName = $objSessioin->get($this->arrConfig['SessionInfo']['UserName']);
            if($PayWayType==1 || $PayWayType==2){

                $param =array("RoleId"=>$RoleID,"PayWayType"=>$PayWayType,"username"=>$username,"BankName"=>$BankName,"BankCardNo"=>$BankCardNo);
                $objUserBLL = new UserBLL($RoleID);
                $arrResult  = $objUserBLL->setpayWay($param);
                $objOperationLogsBLL = new OperationLogsBLL(0);
                $objOperationLogsBLL->addCaseOperationLogs($RoleID, 0, 32, '修改玩家支付宝'.$RoleID,$iResult , Utility::getIP(), 0, 2, $SysUserName, '');

                $iResult=0;// isset($arrResult['iResult'])?$arrResult['iResult']:-1;

            }
        }
        echo $iResult;
    }
	
	/**
	 * 修改操作——锁定角色
	 */
	function lockRoleTable()
	{
		$iRoleID = Utility::isNumeric('roleID',$_REQUEST); //角色id
		$iLoginID = Utility::isNumeric('loginID',$_REQUEST); //角色编号
		$iCaseSerial = isset($_REQUEST['caseSerial']) ? $_REQUEST['caseSerial'] : ''; //案件编号
		$iNumber = Utility::isNumeric('number',$_REQUEST); //期限(天数)
		$strRemarks = Utility::isNullOrEmpty('Remarks', $_REQUEST); //锁定原因	
		$iNumber = 300; // add by zhuzhongqiang 201510312046
		/*判断原来的状态
		//用户账号锁定状态 0:有锁,-1:没锁
		$objUserDataBLL = new UserDataBLL($iRoleID);
		$lockStatus = $objUserDataBLL->checkUserLockInfo();		
		//检查用户表是否锁定 0:锁定,-1:未锁定		
		$objUserBLL = new UserBLL($iRoleID);
		$roleLockStatus = $objUserBLL->checkUserLockStatus();  
		if(!$roleLockStatus['iResult'] && !$lockStatus['iResult']){
			echo json_encode(array("Result"=>'该用户已被锁定'));
			exit;
		}*/
		//var_dump($_REQUEST);exit;
        $iLoginName = getUserRealName($iRoleID);//改成RealName
		if($iNumber && $strRemarks){
			$objSessioinLogin = new Session($this->arrConfig['Session']['SessionLoginName']);
			$SysUserName = $objSessioinLogin->get($this->arrConfig['SessionInfo']['UserName']);
			//插入角色锁定记录 
			$result = $this->objSystemBLL->addCaseOperateUser($iCaseSerial, $iRoleID, $iLoginID, $iLoginName, 3, $iNumber, '',$strRemarks, '', $SysUserName);
			/*这部分修改成交互

			 * //锁定角色信息表
			$res = $objUserBLL->LockUserLockedStatus($iNumber, 1);
			//锁定用户银行，背包，衣柜，用户道具库
			$res2 = $objUserDataBLL->editUserDataLocked(1);
			if(!$result['iResult'] && !$res['iResult'] && !$res2['iResult']){
				$strMsg = "角色锁定成功！";				
				$status = 1;	
				$OpResult=0;
			}else{
				$strMsg = "角色锁定失败！";
				$status = 0;
				$OpResult=-1;
			}*/
            $res = ASLockRole($iRoleID,$iNumber);
            if(isset($res['iResult']) && $res['iResult'] == 0){
                $strMsg="角色锁定成功！";
                $status = 1;
                $OpResult = $res['iResult'];
            }else{
                $strMsg = "角色锁定失败";
                $status = 0;
                $OpResult = $res['iResult']?$res['iResult']:-1;
            }

			//插入锁定角色操作日志
			$objOperationLogsBLL = new OperationLogsBLL($iRoleID);
			$res = $objOperationLogsBLL->addCaseOperationLogs($iRoleID, $iCaseSerial, 0, "角色ID为".$iLoginID."用户因".$strRemarks."被锁定", $OpResult, Utility::getIP(), 0, 2, $SysUserName, '');
			$objOperationLogsBLL->addLockUserLogs($iCaseSerial, $strRemarks, 0, $iNumber, '', $SysUserName, "角色ID为".$iLoginID."用户因".$strRemarks."被锁定", Utility::getIP());
			echo json_encode(array("Result"=>$strMsg, "Status"=>$status, "From"=>"lockRole"));
		}else{
			$arrTags=array('skin'=>$this->arrConfig['skin'],
					   	   'roleID'=>$iRoleID,
						   'loginID'=>$iLoginID);
			Utility::assign($this->smarty,$arrTags);
			$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/OperatelockRoleTable.html');
			$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
			echo json_encode(array("Result"=>$html));
		}		
	}
	
	/**
	 * 修改操作——解除锁定
	 */
	function unlockTable()
	{
		$iRoleID = Utility::isNumeric('roleID',$_REQUEST); //角色id
		$iLoginID = Utility::isNumeric('loginID',$_REQUEST); //角色编号
		$strRemarks = Utility::isNullOrEmpty('Remarks', $_REQUEST); //解锁原因
		
		//查询解除锁定申请记录
		$unlockInfo = $this->objSystemBLL->getCaseAuthProcess($iRoleID, 4, 0);
		if($unlockInfo){
			echo json_encode(array("Result"=>'解除锁定申请已提交，正在等待审核...'));
			exit;
		}
		//$objUserBLL = new UserBLL($iRoleID);
		//判断角色是否被锁定 0:锁定,-1:未锁定
		/*$userLockStatus = $objUserBLL->checkUserLockStatus();*/
		$userLockStatus = getUserLockStatus($iRoleID);
		//查询锁定角色信息
		$CaseOperateInfo = $this->objSystemBLL->getCaseOperateUser($iRoleID, 3);
        //var_dump($CaseOperateInfo);
		$caseSerial = $this->objSystemBLL->getCaseOperateUserSn($CaseOperateInfo[0]['FID']);
		$serialNum = !$caseSerial?'':$caseSerial[0]['CaseSerial'];
		if($strRemarks){
			$objSessioinLogin = new Session($this->arrConfig['Session']['SessionLoginName']);
			$SysUserName = $objSessioinLogin->get($this->arrConfig['SessionInfo']['UserName']); 
			
			if($userLockStatus['Locked'] === 0){
				/*$objUserDataBLL = new UserDataBLL($iRoleID);
				$res = $objUserDataBLL->editUserDataLocked(0);
				if(!$res['iResult']){
					$strMsg = "被锁用户解除锁定成功！";
					$status = 1;
				}else{
					$strMsg = "被锁用户解除锁定失败！";
					$status = 0;
				}
				$OpResult = $res['iResult'];
				$OpContent = "RoleID=".$iRoleID."用户解除锁定";*/
                $strMsg = "用户未被锁定";
                $status = 0;
                $OpResult = -1;
                $OpContent="RoleID=".$iRoleID."用户解除锁定";
			}else{
//				if(count($CaseOperateInfo)>0){
//					$iCount = count($CaseOperateInfo)-1;
//				}
                $iLoginName= getUserLoginName($iRoleID);
                $FID = isset($CaseOperateInfo[0]['FID'])?$CaseOperateInfo[0]['FID']:0;
                $result = $this->objSystemBLL->addAuthProcess($iRoleID,$iLoginID, $iLoginName, 4,0, 0,0,'',$strRemarks,'',1,$SysUserName, $FID);
				//$result = $this->objSystemBLL->addAuthProcess($iRoleID,$iLoginID, $iLoginName, 4,0, 0,0,'',$strRemarks,'',1,$SysUserName, $CaseOperateInfo[0]['FID']);
                if(!$result['iResult']){
					$strMsg = "解除锁定申请成功，待审核后方可生效！";
					$status = 1;
				}else{
					$strMsg = "解除锁定申请失败！";
					$status = 0;
				}
				$OpResult = $result['iResult'];
				$OpContent = "角色ID为".$iLoginID."用户提交解除锁定申请";
			}
			//插入解除锁定操作日志
			$objOperationLogsBLL = new OperationLogsBLL($iRoleID);
			$res = $objOperationLogsBLL->addCaseOperationLogs($iRoleID, $serialNum, 1, $OpContent, $OpResult, Utility::getIP(), 0, 2, $SysUserName, '');
			//锁定角色日志
			$objOperationLogsBLL->addLockUserLogs(0, $strRemarks, 1, 0, '', $SysUserName, $OpContent, Utility::getIP());
			echo json_encode(array("Result"=>$strMsg, "Status"=>$status, "From"=>"lockRole"));
		}else{			
			$arrTags=array('skin'=>$this->arrConfig['skin'],
					   	   'roleID'=>$iRoleID,
						   'loginID'=>$iLoginID);
			Utility::assign($this->smarty,$arrTags);
			$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/OperateUnlockTable.html');
			$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
			echo json_encode(array("Result"=>$html));
		}		
	}
	
	/**
	 * 修改操作——主机解绑
	 */
	function hostUnlockTable()
	{
		$iRoleID = Utility::isNumeric('roleID',$_REQUEST);
		$iLoginID = Utility::isNumeric('loginID',$_REQUEST); //角色编号
		$objSessioinLogin = new Session($this->arrConfig['Session']['SessionLoginName']);
		$SysUserName = $objSessioinLogin->get($this->arrConfig['SessionInfo']['UserName']);
		$res = $this->objSystemBLL->getCaseAuthProcess($iRoleID, 3);
		if($res){
			$strMsg = "主机解绑申请已提交，正在等待审核...";
		}else{

			$result = $this->objSystemBLL->addAuthProcess($iRoleID,'', '', 3,0, 0,0,'','主机解绑申请','',1,$SysUserName,'');

			if(!$result['iResult']){
				$strMsg = "解除主机绑定申请成功，待审核后方可生效！";
			}else{
				$strMsg = "解除主机绑定申请失败！";
			}
			$OpResult = $result['iResult'];
			$OpContent = "角色ID为".$iLoginID."用户提交主机解绑申请";
			//插入主机解绑操作日志
			$objOperationLogsBLL = new OperationLogsBLL($iRoleID);
			$res = $objOperationLogsBLL->addCaseOperationLogs($iRoleID, '', 5, $OpContent, $OpResult, Utility::getIP(), 0, 2, $SysUserName, '');
		}		
		echo json_encode(array("Result"=>$strMsg));			
	}

    /**微信解绑
     *
     */
    function wechatUnlockTable(){
        $iRoleID = Utility::isNumeric('roleID',$_REQUEST);
        $iLoginID = Utility::isNumeric('loginID',$_REQUEST); //角色编号
        $objSessioinLogin = new Session($this->arrConfig['Session']['SessionLoginName']);
        $SysUserName = $objSessioinLogin->get($this->arrConfig['SessionInfo']['UserName']);
        $res = $this->objSystemBLL->getCaseAuthProcess($iRoleID, 23);
        if($res){
            $strMsg = "微信解绑申请已提交，正在等待审核...";
        }else{

            $result = $this->objSystemBLL->addAuthProcess($iRoleID,'', '', 23,0, 0,0,'','微信解绑申请','',1,$SysUserName,'');

            if(!$result['iResult']){
                $strMsg = "解除微信绑定申请成功，待审核后方可生效！";
            }else{
                $strMsg = "解除微信绑定申请失败！";
            }
            $OpResult = $result['iResult'];
            $OpContent = "角色ID为".$iLoginID."用户提交微信解绑申请";
            //插入主机解绑操作日志
            $objOperationLogsBLL = new OperationLogsBLL($iRoleID);
            $res = $objOperationLogsBLL->addCaseOperationLogs($iRoleID, '', 23, $OpContent, $OpResult, Utility::getIP(), 0, 2, $SysUserName, '');
        }
        echo json_encode(array("Result"=>$strMsg));
    }
	/**
	 * 修改操作——登陆解绑
	 */
	function MobileUnlockTable()
	{
		$iResult = -1;
		$iRoleID = Utility::isNumeric('roleID',$_REQUEST);
		$passport = Utility::isNumeric('passport',$_REQUEST);	
		
		$objPassSecurityBLL = new PassSecurityBLL();
		$iResult = $objPassSecurityBLL->deleteSecurityInfo($passport,6);

		if($iResult==0){
			$strMsg = "登陆绑定已经解除成功";
			$objSessioin = new Session($this->arrConfig['Session']['SessionLoginName']);
			$iAdminID = $objSessioin->get($this->arrConfig['SessionInfo']['AdminID']);
			$SysUserName = $objSessioin->get($this->arrConfig['SessionInfo']['UserName']);
			//插入绑操作日志
			$objOperationLogsBLL = new OperationLogsBLL(0);
			$objOperationLogsBLL->addCaseOperationLogs($iRoleID, 0, 28, '登陆解绑成功', 0, Utility::getIP(), 0, 2, $SysUserName, '');
		}else{		
			$strMsg = "登陆绑定已经解除失败";			
		}		
		echo json_encode(array("Result"=>$strMsg));			
	}
	
	/**
	 * 修改操作——重置密码
	 */
	function resetPasswordTable()
	{
		$iRoleID = Utility::isNumeric('roleID',$_REQUEST);
		$iLoginID = Utility::isNumeric('loginID',$_REQUEST); //角色编号
		$iStatus = Utility::isNumeric('pswChked', $_REQUEST);	//iStatus 1: 重置交易密码 2: 重置背包密码   3:两者都选了
		$iTransPwd = Utility::isNumeric('TransPwd',$_REQUEST);
		$LoginPwd = Utility::isNullOrEmpty('LoginPwd',$_REQUEST);
		$iMobile = '';
		$OpResult = -1;
		/*$objUserBLL = new UserBLL($iRoleID);
		$userInfo = $objUserBLL->getRoleInfo();
		if($userInfo){
			//DC验证并根据Passport查询安全手机
			$com = new COM("PHPItfc.PHPInterface") or die("无法建立COM组件");
			$arrPara = array($this->arrConfig['DcConfig'][0]['HOST'],$this->arrConfig['DcConfig'][0]['PORT'],$userInfo['Passport'],'');
			$res = $com->DCSTGetPhoneByPspt($arrPara);		
			$json = get_object_vars(json_decode($res));
		}
		$iMobile = (isset($json['Para0']) && $json['Para0'] == 'success'?$json['Para1']:'');	
		if(!$iMobile){
			echo json_encode(array("Result"=>'没有设置安全手机，无法重置密码~'));
			exit;
		}
		*/
        //var_dump($_REQUEST);
		if($iStatus){
			$objSessioinLogin = new Session($this->arrConfig['Session']['SessionLoginName']);
			$SysUserName = $objSessioinLogin->get($this->arrConfig['SessionInfo']['UserName']); 
			$objOperationLogsBLL = new OperationLogsBLL($iRoleID);
			//$objMsgBLL = new MsgBLL();
			//$objUserDataBLL = new UserDataBLL($iRoleID);
			$arrErrorMsg = array('1'=>'密码重置成功', '-1'=>'密码重置失败', '-2'=>'数据库异常', '-3'=>'同一手机同一类型短信在5分钟内不能重复发送');
			//$MsgContent = rand(100000, 999999);
			if($iStatus == 1){
				//$res = $objMsgBLL->insertShortMessage(1, $iRoleID, $iMobile, $MsgContent, 23);
				//if($res['iResult'] > 0){
					/*$result = $objUserDataBLL->updateBankPwd($iTransPwd);//$result = $objUserDataBLL->updateBankPwd($MsgContent);
					$strMsg = !$result['iResult']?"重置银行交易密码成功！":"重置银行交易密码失败！";*/
                    $result = DSResetRoleBankPwd($iRoleID,md5($iTransPwd));//md5后的加密信息发送
                    $strMsg = !$result['iResult']?"重置银行交易密码成功!":"重置银行交易密码失败!";
					$OpResult = $result['iResult'];
				//}else{
				//	$strMsg = $arrErrorMsg[$res['iResult']];
				//	$OpResult = $res['iResult'];
				//}
				//插入重置密码操作日志
				$objOperationLogsBLL->addCaseOperationLogs($iRoleID, '', 16, $strMsg, $OpResult, Utility::getIP(), 0, 2, $SysUserName, '');				
			}elseif($iStatus == 2){//
				//$res = $objMsgBLL->insertShortMessage(1, $iRoleID, $iMobile, $MsgContent, 24);
				//if($res['iResult'] > 0){
					//$objUserBLL = new UserBLL($iRoleID);
					//$arrUserInf = $objUserBLL->getRole(0,$iRoleID);
					//if(is_array($arrUserInf) && count($arrUserInf)>0)
					//{
						//$objPassAccountBLL = new PassAccountBLL();
						//$result=$objPassAccountBLL->resetPassword($arrUserInf['Passport'],$LoginPwd,'弱');
				    	$result = ASResetLoginPwd($iRoleID,md5($LoginPwd));
                        $strMsg = !$result['iResult']?"重置登陆密码成功！":"重置登陆密码失败！";
						$OpResult = $result['iResult'];					
					//}
					//$result = $objUserDataBLL->updateMyKnapsackPwd(123456);//$result = $objUserDataBLL->updateMyKnapsackPwd($MsgContent);
					//$strMsg = !$result['iResult']?"重置背包密码成功！":"重置背包密码失败！";
					//$OpResult = $result['iResult'];
				//}else{
				//	$strMsg = $arrErrorMsg[$res['iResult']];
				//	$OpResult = $res['iResult'];
				//}
				//插入重置密码操作日志
				$objOperationLogsBLL->addCaseOperationLogs($iRoleID, '', 12, $strMsg, $OpResult, Utility::getIP(), 0, 2, $SysUserName, '');	
			}else{
				//$res1 = $objMsgBLL->insertShortMessage(1, $iRoleID, $iMobile, $MsgContent, 23);
				//if($res1['iResult'] > 0){
					$BankResult = DSResetRoleBankPwd($iRoleID,md5($iTransPwd));//md5后的加密信息发送
					$LoginResult = ASResetLoginPwd($iRoleID,md5($LoginPwd));
					$BankstrMsg = !$BankResult['iResult']?"重置银行交易密码成功!":"重置银行交易密码失败!";
					$LoginstrMsg = !$BankResult['iResult']?"重置登陆密码成功！":"重置登陆密码失败！";
					$strMsg = $BankstrMsg.$LoginstrMsg;
					$BankOpResult = $BankResult['iResult'];
					$LoginOpResult = $LoginResult['iResult'];
					$objOperationLogsBLL->addCaseOperationLogs($iRoleID, '', 16, $strMsg, $BankOpResult, Utility::getIP(), 0, 2, $SysUserName, '');
					$objOperationLogsBLL->addCaseOperationLogs($iRoleID, '', 12, $strMsg, $LoginOpResult, Utility::getIP(), 0, 2, $SysUserName, '');
				//}else{
				//	$strMsg1 = $arrErrorMsg[$res1['iResult']];
				//	$OpResult1 = $res1['iResult'];
				//}
				//插入重置密码操作日志
				// $objOperationLogsBLL->addCaseOperationLogs($iRoleID, '', 16, $strMsg1, $OpResult1, Utility::getIP(), 0, 2, $SysUserName, '');
				//$res2 = $objMsgBLL->insertShortMessage(1, $iRoleID, $iMobile, $MsgContent, 24);
				//if($res2['iResult'] > 0){
					// $objUserBLL = new UserBLL($iRoleID);
					// $arrUserInf = $objUserBLL->getRole(0,$iRoleID);
					// if(is_array($arrUserInf) && count($arrUserInf)>0)
					// {
					// 	$objPassAccountBLL = new PassAccountBLL();
					// 	$result=$objPassAccountBLL->resetPassword($arrUserInf['Passport'],$LoginPwd,'弱');
					// 	$strMsg2 = !$result['iResult']?"重置登陆密码成功！":"重置登陆密码失败！";
					// 	$OpResult2 = $result['iResult'];					
					// }
					//$result = $objUserDataBLL->updateMyKnapsackPwd(123456);//$result = $objUserDataBLL->updateMyKnapsackPwd($MsgContent);
					//$strMsg2 = !$result['iResult']?"重置背包密码成功！":"重置背包密码失败！";
					//$OpResult2 = $result['iResult'];
				//}else{
				//	$strMsg2 = $arrErrorMsg[$res2['iResult']];
				//	$OpResult2 = $res2['iResult'];
				//}
				//插入重置密码操作日志
				// $objOperationLogsBLL->addCaseOperationLogs($iRoleID, '', 12, $strMsg2, $OpResult2, Utility::getIP(), 0, 2, $SysUserName, '');
				// $strMsg='重置银行交易密码'.(!$OpResult1?'成功':'失败').',重置登陆密码'	.(!$OpResult2?'成功':'失败');
			}
			
			echo json_encode(array("Result"=>$strMsg));			
		}else{			
			$arrTags=array('skin'=>$this->arrConfig['skin'],
						   'Mobile'=>$iMobile,
						   'roleID'=>$iRoleID,
						   'loginID'=>$iLoginID);
			Utility::assign($this->smarty,$arrTags);
			$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/OperateResetPasswordTable.html');
			$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
			echo json_encode(array("Result"=>$html));
		}
	}
	
	/**
	 * 修改操作——解除冻结
	 */
	function removeFreezeTable()
	{
		$iRoleID = Utility::isNumeric('roleID',$_REQUEST);
		$iLoginID = Utility::isNumeric('loginID',$_REQUEST); //角色编号
		$iStatus = Utility::isNumeric('freezeChked', $_REQUEST);	//iStatus 1: 解除银行冻结 2: 解除背包冻结  3:两者都选了
		
		//检查解除银行、背包冻结状态		
		$objUserDataBLL = new UserDataBLL($iRoleID);
		$BMStatus = $objUserDataBLL->checkBankMyKnapsackStatus();
		if(!$BMStatus['Status'] && !$BMStatus['iBankStatus']){
			echo json_encode(array("Result"=>'银行、背包未冻结！'));
			exit;
		}
		
		if($iStatus){
			$objSessioinLogin = new Session($this->arrConfig['Session']['SessionLoginName']);
			$SysUserName = $objSessioinLogin->get($this->arrConfig['SessionInfo']['UserName']); 
			//插入解除冻结操作日志
			$objOperationLogsBLL = new OperationLogsBLL($iRoleID);
			if($iStatus == 1){
				$strRemark = "解除银行冻结";
				$OpType = 19;	//15:解除背包冻结, 19:解除银行冻结
				$result = $this->objSystemBLL->addCaseOperateUser(0, $iRoleID, '', '', 5, 0, '', $strRemark, '', $SysUserName);
				$OpResult = $result['iResult'];
				if(!$result['iResult']){
					$strMsg = $strRemark."成功！";
					$objUserDataBLL->updateUserBankStatus(0,0);
				}else{
					$strMsg = $strRemark."失败！";
				}
				
				$strMsg = !$result['iResult']?$strRemark."成功！":$strRemark."失败！";
				
				$OpContent = "ID=".$iLoginID."用户".$strMsg;
				$res = $objOperationLogsBLL->addCaseOperationLogs($iRoleID, '', $OpType, $OpContent, $OpResult, Utility::getIP(), 0, 2, $SysUserName, '');
			}else if($iStatus == 2){
				$strRemark = "解除背包冻结";
				$OpType = 15;
				$result = $this->objSystemBLL->addCaseOperateUser(0, $iRoleID, '', '', 6, 0, '', $strRemark, '', $SysUserName);
				$OpResult = $result['iResult'];
				if(!$result['iResult']){
					$strMsg = $strRemark."成功！";
					$objUserDataBLL->updateMyKnapsackStatus(0,0);
				}else{
					$strMsg = $strRemark."失败！";
				}
				$OpContent = "ID=".$iLoginID."用户".$strMsg;
				$res = $objOperationLogsBLL->addCaseOperationLogs($iRoleID, '', $OpType, $OpContent, $OpResult, Utility::getIP(), 0, 2, $SysUserName, '');
			}else{
				$result = $this->objSystemBLL->addCaseOperateUser(0, $iRoleID, '', '', 5, 0, '', "解除银行冻结", '', $SysUserName);
				$strMsg1 = !$result['iResult']?"解除银行冻结成功！":"解除银行冻结失败！";
				$OpContent = "ID=".$iLoginID."用户".$strMsg1;
				$res = $objOperationLogsBLL->addCaseOperationLogs($iRoleID, '', 19, $OpContent, $result['iResult'], Utility::getIP(), 0, 2, $SysUserName, '');
				$result2 = $this->objSystemBLL->addCaseOperateUser(0, $iRoleID, '', '', 6, 0, '', "解除背包冻结", '', $SysUserName);
				$strMsg2 = !$result['iResult']?"解除背包冻结成功！":"解除背包冻结失败！";
				$OpContent2 = "ID=".$iLoginID."用户".$strMsg2;
				$res = $objOperationLogsBLL->addCaseOperationLogs($iRoleID, '', 15, $OpContent2, $result2['iResult'], Utility::getIP(), 0, 2, $SysUserName, '');
				$strMsg = "解除银行、背包冻结".(!$result['iResult']&& !$result2['iResult']?"成功！":"失败！");
				//解除背包、银行冻结
				$objUserDataBLL->updateUserBankStatus(0,0);
				$objUserDataBLL->updateMyKnapsackStatus(0,0);
			}
			echo json_encode(array("Result"=>$strMsg));			
		}else{
			$arrTags=array('skin'=>$this->arrConfig['skin'],
						   'roleID'=>$iRoleID,
						   'bmStatus'=>$BMStatus,
						   'loginID'=>$iLoginID);
			Utility::assign($this->smarty,$arrTags);
			$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/OperateRemoveFreezeTable.html');
			$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
			echo json_encode(array("Result"=>$html, 'ShowType'=>1));
		}
	}
	
	/**
	 * 修改操作——处理财富
	 */
	function handleTreasureTable()
	{
		$iRoleID = Utility::isNumeric('roleID',$_REQUEST);
		$kids = Utility::isNullOrEmpty('kindIDs', $_REQUEST);
		$transMoney = Utility::isNullOrEmpty('money', $_REQUEST);

		/*$userDataBLL = new UserDataBLL($iRoleID);
		$arrResult = null;
		//查询该用户游戏中的金币
		$result = $userDataBLL->getUserGameWealth();*/
        $result = getRoleGameInfo($iRoleID);
        $arrResult = null;
		if(is_array($result)){
			$i=0;				
			foreach($result as $v) if($v['Money']>0 && $v['RoomType'] == 2){
				$result2 = $this->objMasterBLL->getGameKindInfo($v['KindID']);
				$arrResult[$i]['KindID'] = $v['KindID'];
				$arrResult[$i]['Money'] =Utility::FormatMoney($v['Money']);
				$arrResult[$i]['KindName'] = $result2['KindName'];
				$i++;
			}
		}
		//查询背包中金币
		/*$myKnapsack = $userDataBLL->getMyKnapsackMoney();
		if($myKnapsack && $myKnapsack['Money']>0){
			$size = count($arrResult);
			$arrResult[$size]['KindID'] = 0;
			$arrResult[$size]['Money'] = $myKnapsack['Money'];
			$arrResult[$size]['KindName'] = '背包';
		}*/

		if(!$arrResult){
			echo json_encode(array("Result"=>'暂时没有要处理的财富！'));
			exit;
		}
		
		if(isset($kids) && $transMoney){
			$arrKindIDs = explode(',', $kids);
			$arrMoney = explode(',', $transMoney);
			if(is_array($arrKindIDs) && count($arrKindIDs)>0){
				$objSessioinLogin = new Session($this->arrConfig['Session']['SessionLoginName']);
				$SysUserName = $objSessioinLogin->get($this->arrConfig['SessionInfo']['UserName']); 
				//用户相关信息
				//$objUserBLL = new UserBLL($iRoleID);
				//$userInfo = $objUserBLL->getRoleInfo();
                $data = [];
                for($j=0, $len=count($arrKindIDs); $j<$len; $j++){
                    $data[$j]['iKindID'] = $arrKindIDs[$j];
                    $data[$j]['iMonery'] = $arrMoney[$j]*1000;
                }
                //直接一次性处理
                $arrResult = DSSavingRoleMonery($iRoleID,$data);
                //var_dump($arrResult);exit;
				for($j=0, $len=count($arrKindIDs); $j<$len; $j++){
					/*if(!$arrKindIDs[$j]){
						$result3 = $userDataBLL->editUserBankCunKuan($arrKindIDs[$j], $arrMoney[$j], 1);
						$OpContent = "背包中财富处理".$arrMoney[$j]."金币";	
						$KindName = "背包";					
					}else{ */
						//$result3 = $userDataBLL->editUserBankCunKuan($arrKindIDs[$j], $arrMoney[$j], 0);
						/*
						$result4 = $this->objMasterBLL->getGameKindInfo($arrKindIDs[$j]);	
						$KindName = $result4['KindName'];		*/
					//}
					$OpResult = $arrResult['iResult'];
                    $OpContent = "游戏中财富处理".$arrMoney[$j]."金币";
					//用户银行信息
					/*$userBankMoney = $userDataBLL->getUserBankMoney();*/
					//插入处理财富操作日志
					$objOperationLogsBLL = new OperationLogsBLL($iRoleID);
					$objOperationLogsBLL->addCaseOperationLogs($iRoleID, '', 3, $OpContent, $OpResult, Utility::getIP(), 0, 2, $SysUserName, '');
					//插入用户银行交易日志
					//$objDataChangeLogsBLL = new DataChangeLogsBLL($iRoleID);
					//$objDataChangeLogsBLL->writeBankTransLog($iRoleID,$userInfo['LoginName'],0,$arrKindIDs[$j],$KindName,1,$arrMoney[$j],$userBankMoney['Money'],$userInfo['LoginID'],$OpContent);
				}
				$strMsg = !$arrResult['iResult']?'存款成功！':'存款失败！';
				echo json_encode(array("Result"=>$strMsg));
			}		
		}else{			
			$arrTags=array('skin'=>$this->arrConfig['skin'],
						   'roleID'=>$iRoleID,
						   'arrResult'=>$arrResult);
			Utility::assign($this->smarty,$arrTags);
			$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/OperateHandleTreasureTable.html');
			$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
			echo json_encode(array("Result"=>$html));
		}
	}
	
	/**
	 * 修改操作——冻结财富
	 */
	function freezeTreasureTable()
	{
		$iRoleID = Utility::isNumeric('roleID',$_REQUEST); //角色id
		$iLoginID = Utility::isNumeric('loginID',$_REQUEST); //角色编号
		$iCaseSerial = isset($_REQUEST['caseSerial']) ? $_REQUEST['caseSerial'] : '';  //案件编号
		$iNumber = Utility::isNumeric('number',$_REQUEST); //冻结金币数量
		//$iNumber1 = 0;//Utility::isNumeric('number1',$_REQUEST); //冻结龙币数量
		$strRemarks = Utility::isNullOrEmpty('Remarks', $_REQUEST); //冻结原因
		$strMsg = '';
		
//		//查询游戏中金币
		//$userDataBLL = new UserDataBLL($iRoleID);
//		$arrResult = $userDataBLL->getUserGameWealth();
//		if($arrResult){
//			$strMsg = "游戏中还有未存入银行的金币！";
//			echo json_encode(array("Result"=>$strMsg, 'ShowType'=>1));
//			exit;
//		}else{
//			//查询背包中金币
//			$myKnapsack = $userDataBLL->getMyKnapsackMoney();
//			if($myKnapsack['Money']){
//				$strMsg = "背包中还有未存入银行的金币！";
//				echo json_encode(array("Result"=>$strMsg, 'ShowType'=>1));
//				exit;
//			}			
//		}
		
		//用户相关信息
		//$objUserBLL = new UserBLL($iRoleID);
		//$userInfo = $objUserBLL->getRoleInfo();
		//用户银行信息
        $userBankInfo = getBankMoney($iRoleID);

		/*$userBankMoney = $userDataBLL->getUserBankMoney();
		//查询背包中金币
		'fwBank'=>$userBankMoney['FwMoney'],, 'knapsack'=>$myKnapsack['Money']
		$myKnapsack = $userDataBLL->getMyKnapsackMoney();*/
		//游戏中的金币
		//$gameWealth = $userDataBLL->getUserGameWealthAllMoney();
        //var_dump($userBankInfo);
		$arrHappyBeanMoney = array('bank'=>Utility::FormatMoney($userBankInfo['Money']),'game'=>$userBankInfo['GameWealth']);
        $iNumber = $iNumber*1000;
		if($iNumber && $iNumber>0 && $iNumber<=$userBankInfo['Money'] && $strRemarks){
			$objDataChangeLogsBLL = new DataChangeLogsBLL($iRoleID);
			$objSessioinLogin = new Session($this->arrConfig['Session']['SessionLoginName']);
			$SysUserName = $objSessioinLogin->get($this->arrConfig['SessionInfo']['UserName']);
            $iLoginName = getUserRealName($iRoleID);//$iLoginName = getUserLoginName($iRoleID);//LoginName 改成RealName
			$result = $this->objSystemBLL->addCaseOperateUser($iCaseSerial, $iRoleID, $iLoginID, $iLoginName, 1, $iNumber, '', $strRemarks, '', $SysUserName);
			if(!$result['iResult']){
				$strMsg = "财富冻结成功！";
				//扣除用户银行的金币（冻结）
				/*$userBankResult = $userDataBLL->editUserBankMoney($iNumber, $iNumber1,0);*/
                $arrResult = DSLockRoleMonery($iRoleID,$iNumber);
				//银行交易日志				
				/*$objDataChangeLogsBLL->writeBankTransLog($iRoleID,$userInfo['LoginName'],0,0,'',2,$iNumber,$userBankResult['Money'],$userInfo['LoginID'],'冻结该用户银行'.$iNumber.'金币');*/
				//$objDataChangeLogsBLL->writeBankTransLog($iRoleID,$userInfo['LoginName'],0,0,'',2,$iNumber,$arrResult['iResult'],$userInfo['LoginID'],'冻结该用户银行'.$iNumber.'金币');
				if(!$arrResult['iResult']){
					//将扣除的金币，存入系统银行
					//$res = $this->objBankBLL->updateSysBankMoney(6, $iNumber, $iNumber1,0);
					//系统银行日志
					/*$objDataChangeLogsBLL->insertSysBankTransLogs(6, $iRoleID, 0, 7, 1, $iNumber, $res['Balance'], $res['LastBalance'], '');
					if($res['iResult']){
						//存入系统银行不成功，则返还给用户银行
						$userBankResult = $userDataBLL->editUserBankMoney($iNumber, $iNumber1,1);
						//银行交易日志
						$objDataChangeLogsBLL->writeBankTransLog($iRoleID,$userInfo['LoginName'],0,0,'',1,$iNumber,$userBankMoney['Money']+$iNumber,$userInfo['LoginID'],'冻结该用户银行金币失败，返还给用户'.$iNumber.'金币');
					}*/
				}
			}else{
				$strMsg = "财富冻结失败！";					
			}
			$OpResult = $result['iResult'];
			$OpContent = "ID=".$iLoginID."用户被财富冻结，冻结数量为".$iNumber."金币";
			//插入操作日志
			$objOperationLogsBLL = new OperationLogsBLL($iRoleID);
			$res = $objOperationLogsBLL->addCaseOperationLogs($iRoleID, $iCaseSerial, 2, $OpContent, $OpResult, Utility::getIP(), 0, 2, $SysUserName, '');
			//财富冻结日志
			$objDataChangeLogsBLL->insertLockMoneyLogs($iRoleID, $iCaseSerial, $iNumber, /*$iNumber1,*/ 0, '', $SysUserName, $OpContent);
			
			echo json_encode(array("Result"=>$strMsg));			
		}else{			
			$arrTags=array('skin'=>$this->arrConfig['skin'],
					   	   'roleID'=>$iRoleID,
					   	   'M'=>$arrHappyBeanMoney,
						   'loginID'=>$iLoginID);
			Utility::assign($this->smarty,$arrTags);
			$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/OperateFreezeTreasureTable.html');
			$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
			echo json_encode(array("Result"=>$html, 'ShowType'=>1));
		}		
	}
	
	/**
	 * 修改操作——处罚角色
	 */
	function punishRoleTable()
	{
		$iRoleID = Utility::isNumeric('roleID',$_REQUEST); //角色id
		$iLoginID = Utility::isNumeric('loginID',$_REQUEST); //角色编号
		$iCaseSerial = isset($_REQUEST['caseSerial']) ? $_REQUEST['caseSerial'] : '';  //案件编号
		$iReason = Utility::isNumeric('reason',$_REQUEST); //处理原因
		$iNumber = Utility::isNumeric('number',$_REQUEST); //封号期限(天数)
		$iRequirement = Utility::isNumeric('requirement',$_REQUEST); //解封要求
		$strRemarks = Utility::isNullOrEmpty('Remarks', $_REQUEST); //备注
		
		$userInfo = getUserLockStatus($iRoleID);
		if($userInfo['Locked']){
			echo json_encode(array("Result"=>"角色已封号！", 'ShowType'=>1));
			exit;	
		}
		if($iReason && $iNumber){
			$strReason = array(1=>'恶意炒分',
							   2=>'拉变速',
							   3=>'杀猪',
							   4=>'非正常积分',
							   5=>'公开买卖游戏虚拟财富',
							   6=>'作弊软件',
							   7=>'盗号',
							   8=>'盗取虚拟财富',
							   9=>'不文明聊天或刷屏',
							   10=>'违规用户名或昵称',
							   11=>'恶意占桌锁机');
			$strRequirement = array(0=>'无',
								    1=>'财富清零',
								    2=>'积分清零');
			$objSessioinLogin = new Session($this->arrConfig['Session']['SessionLoginName']);
			$SysUserName = $objSessioinLogin->get($this->arrConfig['SessionInfo']['UserName']);

            //var_dump($_REQUEST);exit;
            $iLoginName = getUserRealName($iRoleID);
			$result = $this->objSystemBLL->addCaseOperateUser($iCaseSerial, $iRoleID, '', $iLoginName, 4, $iNumber, $strReason[$iReason], $strRemarks, $strRequirement[$iRequirement], $SysUserName);
			//$objUserBLL = new UserBLL($iRoleID);

			/*$res = $objUserBLL->blockUserLockedStatus(1, $strRemarks);*/
            //处罚
            $res = ASBlockRole($iRoleID,$iNumber);
			if(!$result['iResult'] && !$res['iResult']){
				$strMsg = "处罚角色成功！";
				$status = 1;
				$OpResult = 0;
			}else{
				$strMsg = "处罚角色失败！";
				$status = 0;
				$OpResult = -1;
			}
	
			$OpContent = "ID=".$iLoginID."用户被封号处罚，封号期限为".($iNumber>0?$iNumber."天":"永久");
			//插入处罚角色操作日志
			$objOperationLogsBLL = new OperationLogsBLL($iRoleID);
			$res = $objOperationLogsBLL->addCaseOperationLogs($iRoleID, $iCaseSerial, 20, $OpContent, $OpResult, Utility::getIP(), 0, 2, $SysUserName, '');
			//封号日志
			$objOperationLogsBLL->addLockUserLogs($iCaseSerial, $strReason[$iReason], 3, $iNumber>0?$iNumber:"永久", $strRequirement[$iRequirement], $SysUserName, $strRemarks);

			echo json_encode(array("Result"=>$strMsg, "Status"=>$status, "From"=>"punishRole"));
		}else{
			$arrTags=array('skin'=>$this->arrConfig['skin'],
						   'roleID'=>$iRoleID,
						   'loginID'=>$iLoginID);
			Utility::assign($this->smarty,$arrTags);
			$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/OperatePunishRoleTable.html');
			$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
			echo json_encode(array("Result"=>$html, 'ShowType'=>1));
		}
	}
	
	/**
	 * 修改操作——解除处罚
	 */
	function unpunishRoleTable()
	{
		$iRoleID = Utility::isNumeric('roleID',$_REQUEST); //角色id
		$iLoginID = Utility::isNumeric('loginID',$_REQUEST); //角色编号
		$strRemarks = Utility::isNullOrEmpty('Remarks', $_REQUEST); //解锁处罚
		$arrRequirement = array(0=>'无',1=>'财富清零',2=>'积分清零');//解封要求
		
		//查询是否已提交解除处罚
		$result = $this->objSystemBLL->getCaseAuthProcess($iRoleID, 5, 0);
		if($result){
			echo json_encode(array("Result"=>'解除处罚申请已提交，正在等待审核...'));
			exit;
		}
		
		//查询处罚角色信息
		$CaseOperateInfo = $this->objSystemBLL->getCaseOperateUser($iRoleID, 4);	
		$caseSerial = $this->objSystemBLL->getCaseOperateUserSn($CaseOperateInfo[0]['FID']);	
		$serialNum = !$caseSerial?'':$caseSerial[0]['CaseSerial'];
		if($strRemarks){			
			$objSessioinLogin = new Session($this->arrConfig['Session']['SessionLoginName']);
			$SysUserName = $objSessioinLogin->get($this->arrConfig['SessionInfo']['UserName']); 
			$result = $this->objSystemBLL->addAuthProcess($iRoleID,'','', 5, 0, 0, 0,'',$strRemarks,$CaseOperateInfo[0]['Requirement'],1,$SysUserName,$CaseOperateInfo[0]['FID']);
			if(!$result['iResult']){
				$strMsg = "解除处罚申请成功，待审核后方可生效！";
			}else{
				$strMsg = "角色处罚申请失败！";
			}
			$OpResult = $result['iResult'];
			$OpContent = "ID=".$iLoginID."用户申请解除封号，解封要求：".$CaseOperateInfo[0]['Requirement'];
			//插入解除处罚操作日志
			$objOperationLogsBLL = new OperationLogsBLL($iRoleID);
			$res = $objOperationLogsBLL->addCaseOperationLogs($iRoleID, $serialNum, 21, $OpContent, $OpResult, Utility::getIP(), 0, 2, $SysUserName, ''); 
			$objOperationLogsBLL->addLockUserLogs('', '申请解封', 4, '', $CaseOperateInfo[0]['Requirement'], $SysUserName, $strRemarks);

			echo json_encode(array("Result"=>$strMsg));
		}else{				
			$arrTags=array('skin'=>$this->arrConfig['skin'],
						   'roleID'=>$iRoleID,
						   'CaseOperateInfo'=>$CaseOperateInfo[0],
						   'loginID'=>$iLoginID);
			Utility::assign($this->smarty,$arrTags);
			$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/OperateUnpunishRoleTable.html');
			$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
			echo json_encode(array("Result"=>$html, 'ShowType'=>1));
		}	
	}
	
	/**
	 * 修改操作——黄钻服务
	 */
	function lanZuanServiceTable()
	{
		$iRoleID = Utility::isNumeric('roleID',$_REQUEST); //角色id
		$iNumber = Utility::isNumeric('number',$_REQUEST); //延长天数
		$strRemarks = Utility::isNullOrEmpty('Remarks', $_REQUEST); //修改原因
		//查询已提交的黄钻服务申请
		$result = $this->objSystemBLL->getCaseAuthProcess($iRoleID, 11, 0);
		if($result){
			echo json_encode(array("Result"=>'黄钻服务申请已提交，正在等待授权...', 'ShowType'=>1));
			exit;
		}
		
		if($iNumber && $strRemarks){
			$objSessioinLogin = new Session($this->arrConfig['Session']['SessionLoginName']);
			$SysUserName = $objSessioinLogin->get($this->arrConfig['SessionInfo']['UserName']); 
			$result = $this->objSystemBLL->addAuthProcess($iRoleID,'','',11,0,$iNumber,0,'',$strRemarks,0,2,$SysUserName,0);
			if(!$result['iResult']){
				$strMsg = "修改黄钻服务申请成功，	待授权通过后方可生效！";
			}else{
				$strMsg = "修改黄钻服务申请失败！";
			}
			$OpResult = $result['iResult'];
			$OpContent = "ID=".$iRoleID."用户申请延长黄钻服务，延长天数：".$iNumber."天";
			//插入延长黄钻服务申请操作日志
			$objOperationLogsBLL = new OperationLogsBLL($iRoleID);
			$res = $objOperationLogsBLL->addCaseOperationLogs($iRoleID, '', 7, $OpContent, $OpResult, Utility::getIP(), 0, 2, $SysUserName, '');

			echo json_encode(array("Result"=>$strMsg, 'ShowType'=>1));
		}else{			
			$arrTags=array('skin'=>$this->arrConfig['skin'],
						   'roleID'=>$iRoleID);
			Utility::assign($this->smarty,$arrTags);
			$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/OperateLanZuanServiceTable.html');
			$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
			echo json_encode(array("Result"=>$html, 'ShowType'=>1));
		}
	}
	
	/**
	 * 修改操作——补发月礼包
	 */
	function reissueMonthGiftTable()
	{
		$iRoleID = Utility::isNumeric('roleID',$_REQUEST); //角色id
		$userDataBLL = new UserDataBLL($iRoleID);
		$arrResult = NULL;
		//查询补发黄钻月礼包申请
		$result = $this->objSystemBLL->getCaseAuthProcess($iRoleID, 12, 0);
		if($result){
			echo json_encode(array("Result"=>'补发月礼包申请已提交，正在等待授权...', 'ShowType'=>1));
			exit;
		}
		
		//月礼包信息
		$monthGiftInfo = $userDataBLL->getMonthGiftPackage();
		if($monthGiftInfo){
			$monthGiftInfo['ReceiveTime'] = date("Y-m-d H:i:s", strtotime($monthGiftInfo['ReceiveTime']));
			$monthGiftInfo['GiftDate'] = date("Y-m-d H:i:s", strtotime($monthGiftInfo['GiftDate']));
			$arrResult = $this->objStagePropertyBLL->getGiftPackage($monthGiftInfo['SpID']);	//礼包中的道具信息
		}
		
		$arrTags=array('skin'=>$this->arrConfig['skin'],
					   'roleID'=>$iRoleID,
					   'monthGift'=>$monthGiftInfo,
					   'arrResult'=>$arrResult);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/OperateReissueMonthGiftTable.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo json_encode(array("Result"=>$html, 'ShowType'=>1));	
	}
	
	/**
	 * 修改操作——补发月礼包说明
	 */
	function reissueMonthGiftTipsTable()
	{
		$iRoleID = Utility::isNumeric('roleID',$_REQUEST); //角色id
		$iSpID = Utility::isNumeric("iSpID", $_REQUEST);//礼包道具ID
		$strRemarks = Utility::isNullOrEmpty('Remarks', $_REQUEST); //备注
		$userDataBLL = new UserDataBLL($iRoleID);		
		
		//背包格总数
		$myKnapsack = $userDataBLL->getMyKnapsackMoney();
		$gridCount = $myKnapsack['GridCount'];
		//已使用的背包格
		$usedGridCount = $userDataBLL->getUsedMyKnapsacksByRoleID(1);
		if($gridCount <= $usedGridCount){
			echo json_encode(array("Result"=>'玩家背包已满，补发失败', 'ShowType'=>1));
			exit;
		}
		
		if($strRemarks){
			$objSessioinLogin = new Session($this->arrConfig['Session']['SessionLoginName']);
			$SysUserName = $objSessioinLogin->get($this->arrConfig['SessionInfo']['UserName']); 
			$result = $this->objSystemBLL->addAuthProcess($iRoleID, '', '', 12, $iSpID, 1, 0, '',$strRemarks,0,2,$SysUserName,0);
			if(!$result['iResult']){
				$strMsg = "补发月礼包申请成功，待授权通过后方可生效！";
			}else{
				$strMsg = "补发月礼包申请失败！";
			}
			$OpResult = $result['iResult'];
			$OpContent = "ID=".$iRoleID."用户申请补发黄钻月礼包";
			//插入补发月礼包申请操作日志
			$objOperationLogsBLL = new OperationLogsBLL($iRoleID);
			$res = $objOperationLogsBLL->addCaseOperationLogs($iRoleID, '', 6, $OpContent, $OpResult, Utility::getIP(), 0, 2, $SysUserName, '');

			echo json_encode(array("Result"=>$strMsg, 'ShowType'=>1));
		}else{			
			$arrTags=array('skin'=>$this->arrConfig['skin'],
						   'roleID'=>$iRoleID,
						   'iSpID'=>$iSpID);
			Utility::assign($this->smarty,$arrTags);
			$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/OperatereissueMonthGiftTipsTable.html');
			$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
			echo json_encode(array("Result"=>$html, 'ShowType'=>1));
		}
	}
	
	/**
	 * 显示锁定/解锁信息
	 */
	function showEditLockInfo()
	{
		$iRoleID = Utility::isNumeric('roleID',$_REQUEST); //角色id
		$curPage = Utility::isNumeric('curPage',$_REQUEST);
		$curPage = ($curPage<=0 ? 1 : $curPage);
		$arrParam['fields']='A.FID, A.iNumber, A.Remarks AS ARemarks, A.SysUserName AS ASysUserName, CONVERT(VARCHAR(20),A.AddTime,120) AS AAddTime, B.PID, B.Remarks, B.SysUserName, CONVERT(VARCHAR(20),B.AddTime,120) AS AddTime, B.Status, B.Checker, CONVERT(VARCHAR(20),CheckTime,120) AS CheckTime, B.CheckRemarks';
		$arrParam['tableName']='T_CaseOperateUser AS A LEFT JOIN T_AuthProcess AS B ON A.FID=B.FID';
		$arrParam['where']=" WHERE A.OperationType=3 AND A.RoleID=".$iRoleID;
		$arrParam['order']=' A.AddTime Desc, B.AddTime Desc';
		$arrParam['pagesize']=5;

		//获取角色基本信息
		$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['System']);
		$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
        //var_dump($iRecordsCount);
		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
        //var_dump($Page);
        $arrResult = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);
		if($arrResult){
			$i=0;
			foreach($arrResult as $v){
				$arrResult[$i]['ARemarks'] = str_replace("\n","<br>",Utility::gb2312ToUtf8($v['ARemarks']));
				$arrResult[$i]['Remarks'] = str_replace("\n","<br>",Utility::gb2312ToUtf8($v['Remarks']));
				$arrResult[$i]['CheckRemarks'] = str_replace("\n","<br>",Utility::gb2312ToUtf8($v['CheckRemarks']));
				$arrResult[$i]['lockEndTime'] = date("Y-m-d H:i:s", strtotime($v['AAddTime']."+". $v['iNumber']." days"));
				$arrFids = $this->objSystemBLL->getCaseOperateUserSn($v['FID']);
				$arrResult[$i]['CaseSerial'] = '';
				if(count($arrFids)>1){
					if(!$v['Status']){
						foreach($arrFids as $val){
							$arrResult[$i]['CaseSerial'] .='<div id="caseDiv_'.$v['FID'].'_'.$val['CaseSerial'].'" style="width:110px; float:left;" onmouseover="editOperate.showImg('.$v['FID'].','.$val['CaseSerial'].');" onmouseout="editOperate.hidImg('.$v['FID'].','.$val['CaseSerial'].');"><a class="hand blue" href="javascript:editOperate.getCaseDetailPage('.$val['CaseSerial'].',1);">'.$val['CaseSerial'];
							$arrResult[$i]['CaseSerial'] .='</a><img id="Img_'.$v['FID'].'_'.$val['CaseSerial'].'" src="/images/common/del.gif" class="close_btn" onclick="editOperate.delCaseSerial('.$v['FID'].','.$val['CaseSerial'].')" style="visibility:hidden;" /></div>';
						}
						$arrResult[$i]['CaseSerial'] .='<br/><br class="clearFloat"/>';
					}else{
						foreach($arrFids as $val){
							$arrResult[$i]['CaseSerial'] .= $val['CaseSerial'].'&nbsp;&nbsp;';
						}
					}
				}else{
					if(!$v['Status']){
						if($arrFids[0]['CaseSerial']){
							$arrResult[$i]['CaseSerial'] .='<div id="caseDiv_'.$v['FID'].'_'.$arrFids[0]['CaseSerial'].'" style="width:110px;" onmouseover="editOperate.showImg('.$v['FID'].','.$arrFids[0]['CaseSerial'].');" onmouseout="editOperate.hidImg('.$v['FID'].','.$arrFids[0]['CaseSerial'].');"><a class="hand blue" href="javascript:editOperate.getCaseDetailPage('.$arrFids[0]['CaseSerial'].',1);">'.$arrFids[0]['CaseSerial'];
							$arrResult[$i]['CaseSerial'] .='</a><img id="Img_'.$v['FID'].'_'.$arrFids[0]['CaseSerial'].'" src="/images/common/del.gif" class="close_btn" onclick="editOperate.delCaseSerial('.$v['FID'].','.$arrFids[0]['CaseSerial'].')" style="visibility:hidden;" /></div>';
						}
					}else{
						$arrResult[$i]['CaseSerial'] .= $arrFids[0]['CaseSerial'].'&nbsp;&nbsp;';
					}
				}
				$i++;
			}
		}

		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page, 'InfoList'=>$arrResult, 'RoleID'=>$iRoleID);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/EditOperateLockInfo.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	
	/**
	 * 更新解锁/解封信息的备注
	 */
	function updateAuthProcessRemarks()
	{
		$PID = Utility::isNumeric("pid", $_REQUEST);
		$strRemarks = Utility::isNullOrEmpty('Remarks', $_REQUEST); //备注
		$iRoleID = Utility::isNumeric('roleID',$_REQUEST); //角色id
		$OpType = Utility::isNumeric('opType', $_REQUEST); //操作类型
		
		if($PID && $strRemarks){
			$result = $this->objSystemBLL->updateAuthProcessRemarks($PID, $strRemarks);
			$objSessioinLogin = new Session($this->arrConfig['Session']['SessionLoginName']);
			$SysUserName = $objSessioinLogin->get($this->arrConfig['SessionInfo']['UserName']);
			
			//插入操作日志
			$objOperationLogsBLL = new OperationLogsBLL($iRoleID);
			$objOperationLogsBLL->addCaseOperationLogs($iRoleID, '', $OpType, $this->arrConfig['OpType'][$OpType].'修改备注为“'.$strRemarks.'”', $result['iResult'], Utility::getIP(), 0, 2, $SysUserName, '');
			echo json_encode(array('result'=>$result['iResult'], "remarks"=>$strRemarks, "showId"=>"tdRemarks_".$PID));
		}else{
			echo json_encode(array('result'=>-1));
		}		
	}
	
	/**
	 * 更新锁定角色原因
	 */
	function updateCaseOperateUserRemarks()
	{
		$FID = Utility::isNumeric("fid", $_REQUEST);
		$strRemarks = Utility::isNullOrEmpty('Reason', $_REQUEST); //备注
		$iRoleID = Utility::isNumeric('roleID',$_REQUEST); //角色id
		$OpType = Utility::isNumeric('opType', $_REQUEST); //操作类型
		if($FID && $strRemarks){
			$result = $this->objSystemBLL->updateCaseOperateUserReason($FID, $strRemarks);
			$objSessioinLogin = new Session($this->arrConfig['Session']['SessionLoginName']);
			$SysUserName = $objSessioinLogin->get($this->arrConfig['SessionInfo']['UserName']);
			
			//插入操作日志
			$objOperationLogsBLL = new OperationLogsBLL($iRoleID);
			$objOperationLogsBLL->addCaseOperationLogs($iRoleID, '', $OpType, $this->arrConfig['OpType'][$OpType].'修改备注为“'.$strRemarks.'”', $result['iResult'], Utility::getIP(), 0, 2, $SysUserName, '');
			
			echo json_encode(array('result'=>$result['iResult'], "remarks"=>$strRemarks, "showId"=>"tdReason_".$FID));
		}else{
			echo json_encode(array('result'=>-1));
		}	
	}
	
	/**
	 * 验证案件编号
	 */
	function checkCaseSerial()
	{
		$iCaseSerial = Utility::isNumeric("iCaseSerial", $_REQUEST);
		$arrResult = $this->objSystemBLL->getCaseInfoByID($iCaseSerial);
		if(is_array($arrResult) && count($arrResult)>0)
		{
			echo 1;
		}else{
			echo 0;
		}
	}
	
	/**
	 * 插入案件编号
	 */
	function insertCaseSerial()
	{
		$iFID = Utility::isNumeric("fid", $_REQUEST);
		$iCaseSerial = Utility::isNumeric("iCaseSerial", $_REQUEST);
		$iRoleID = Utility::isNumeric('roleID',$_REQUEST); //角色id
		$OpType = Utility::isNumeric('opType', $_REQUEST); //操作类型	0：锁号， 2：冻结金币,	20：封号
		if($iFID && $iCaseSerial){
			$arrResult = $this->objSystemBLL->insertCaseOperateUserSn($iFID, $iCaseSerial);
			$objSessioinLogin = new Session($this->arrConfig['Session']['SessionLoginName']);
			$SysUserName = $objSessioinLogin->get($this->arrConfig['SessionInfo']['UserName']);
			
			$arrOpTypeName = array(0=>'锁号', 2=>'冻结金币', 20=>'封号');
			
			//插入操作日志
			$objOperationLogsBLL = new OperationLogsBLL($iRoleID);
			$res = $objOperationLogsBLL->addCaseOperationLogs($iRoleID, $iCaseSerial, $OpType, $arrOpTypeName[$OpType].'添加案件编号'.$iCaseSerial, $arrResult['iResult'], Utility::getIP(), 0, 2, $SysUserName, '');
			echo $arrResult['iResult']?0:1;
		}else{
			echo 0;
		}
	}
	
	/**
	 * 删除案件编号
	 */
	function delCaseSerial()
	{
		$iFID = Utility::isNumeric("fid", $_REQUEST);
		$iCaseSerial = Utility::isNumeric("iCaseSerial", $_REQUEST);
		if($iFID && $iCaseSerial){
			$arrResult = $this->objSystemBLL->delCaseOperateUserSn($iFID,$iCaseSerial);
			echo $arrResult['iResult']?0:1;
		}else{
			echo 0;
		}
	}
	
	/**
	 * 封号/解封信息
	 */
	function showEditBlockInfo()
	{
		$iRoleID = Utility::isNumeric('roleID',$_REQUEST); //角色id
		$curPage = Utility::isNumeric('curPage',$_REQUEST);
		$curPage = ($curPage<=0 ? 1 : $curPage);
		$arrParam['fields']='A.FID, A.iNumber, A.Reason, A.Requirement, A.Remarks AS ARemarks, A.SysUserName AS ASysUserName, CONVERT(VARCHAR(20),A.AddTime,120) AS AAddTime, B.PID, B.Remarks, B.SysUserName, CONVERT(VARCHAR(20),B.AddTime,120) AS AddTime, B.Status, B.Checker, CONVERT(VARCHAR(20),CheckTime,120) AS CheckTime, B.CheckRemarks';
		$arrParam['tableName']='T_CaseOperateUser AS A LEFT JOIN T_AuthProcess AS B ON A.FID=B.FID';
		$arrParam['where']=" WHERE A.OperationType=4 AND A.RoleID=".$iRoleID;
		$arrParam['order']=' A.AddTime Desc, B.AddTime Desc';
		$arrParam['pagesize']=5;
		//获取角色基本信息
		$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['System']);
		$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']); 
		$arrResult = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);
		
		$arrRequirement = array(0=>'无',1=>'财富清零',2=>'积分清零');//解封要求
		if($arrResult){
			$i=0;
			foreach($arrResult as $v){
				$arrResult[$i]['Reason'] = str_replace("\n","<br>",Utility::gb2312ToUtf8($v['Reason']));
				$arrResult[$i]['ARemarks'] = str_replace("\n","<br>",Utility::gb2312ToUtf8($v['ARemarks']));
				$arrResult[$i]['Requirement'] = str_replace("\n","<br>",Utility::gb2312ToUtf8($v['Requirement']));
				$arrResult[$i]['Remarks'] = str_replace("\n","<br>",Utility::gb2312ToUtf8($v['Remarks']));
				$arrResult[$i]['CheckRemarks'] = str_replace("\n","<br>",Utility::gb2312ToUtf8($v['CheckRemarks']));
				$arrFids = $this->objSystemBLL->getCaseOperateUserSn($v['FID']);
				$arrResult[$i]['CaseSerial'] = '';
				if(count($arrFids)>1){
					if(!$v['Status']){
						foreach($arrFids as $val){
							$arrResult[$i]['CaseSerial'] .='<div id="caseDiv_'.$v['FID'].'_'.$val['CaseSerial'].'" style="width:110px; float:left;" onmouseover="editOperate.showImg('.$v['FID'].','.$val['CaseSerial'].');" onmouseout="editOperate.hidImg('.$v['FID'].','.$val['CaseSerial'].');"><a class="hand blue" href="javascript:editOperate.getCaseDetailPage('.$val['CaseSerial'].',1);">'.$val['CaseSerial'];
							$arrResult[$i]['CaseSerial'] .='</a><img id="Img_'.$v['FID'].'_'.$val['CaseSerial'].'" src="/images/common/del.gif" class="close_btn" onclick="editOperate.delCaseSerial('.$v['FID'].','.$val['CaseSerial'].')" style="visibility:hidden;" /></div>';
						}
						$arrResult[$i]['CaseSerial'] .='<br/><br class="clearFloat"/>';
					}else{
						foreach($arrFids as $val){
							$arrResult[$i]['CaseSerial'] .= $val['CaseSerial'].'&nbsp;&nbsp;';
						}
					}
				}else{
					if(!$v['Status']){
						if($arrFids[0]['CaseSerial']){
							$arrResult[$i]['CaseSerial'] .='<div id="caseDiv_'.$v['FID'].'_'.$arrFids[0]['CaseSerial'].'" style="width:110px;" onmouseover="editOperate.showImg('.$v['FID'].','.$arrFids[0]['CaseSerial'].');" onmouseout="editOperate.hidImg('.$v['FID'].','.$arrFids[0]['CaseSerial'].');"><a class="hand blue" href="javascript:editOperate.getCaseDetailPage('.$arrFids[0]['CaseSerial'].',1);">'.$arrFids[0]['CaseSerial'];
							$arrResult[$i]['CaseSerial'] .='</a><img id="Img_'.$v['FID'].'_'.$arrFids[0]['CaseSerial'].'" src="/images/common/del.gif" class="close_btn" onclick="editOperate.delCaseSerial('.$v['FID'].','.$arrFids[0]['CaseSerial'].')" style="visibility:hidden;" /></div>';
						}
					}else{
						$arrResult[$i]['CaseSerial'] .= $arrFids[0]['CaseSerial'].'&nbsp;&nbsp;';
					}
				}
				$i++;
			}
		}
		
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page, 'BlockInfoList'=>$arrResult, 'RoleID'=>$iRoleID); 	
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/EditOperateBlockInfo.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	
	/**
	 * 冻结财富信息
	 */
	function FreezeTreasureInfo()
	{
		$iRoleID = Utility::isNumeric('roleID',$_REQUEST); //角色id
		$curPage = Utility::isNumeric('curPage',$_REQUEST);
		$curPage = ($curPage<=0 ? 1 : $curPage);
		$arrParam['fields']='FID, iNumber, ReturnNumber, Remarks, SysUserName,LoginName,LoginID, CONVERT(VARCHAR(20),AddTime,120) AS AddTime';
		$arrParam['tableName']='T_CaseOperateUser';
		$arrParam['where']=" WHERE OperationType=1 AND RoleID=".$iRoleID;
		$arrParam['order']=' AddTime Desc';
		$arrParam['pagesize']=15;
		//获取角色基本信息
		$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['System']);
		$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']); 
		$arrResult = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);

		$objSessioinLogin = new Session($this->arrConfig['Session']['SessionLoginName']);
		$SysUserName = $objSessioinLogin->get($this->arrConfig['SessionInfo']['UserName']); 
		
		if($arrResult){
			$i=0;
			foreach($arrResult as $v){
				$arrResult[$i]['Remarks'] = str_replace(array("\r\n", "\r", "\n"),'<br />',Utility::gb2312ToUtf8($v['Remarks']));
                $arrResult[$i]['iNumber'] = Utility::FormatMoney($v['iNumber']);
                $arrResult[$i]['LoginName'] = Utility::gb2312ToUtf8($v['LoginName']);
				$arrFids = $this->objSystemBLL->getCaseOperateUserSn($v['FID']);
				$arrResult[$i]['CaseSerial'] = '';
				if(count($arrFids)>1){
					if($v['iNumber']){
						foreach($arrFids as $val){
							$arrResult[$i]['CaseSerial'] .='<div id="caseDiv_'.$v['FID'].'_'.$val['CaseSerial'].'" style="width:110px; float:left;" onmouseover="editOperate.showImg('.$v['FID'].','.$val['CaseSerial'].');" onmouseout="editOperate.hidImg('.$v['FID'].','.$val['CaseSerial'].');"><a class="hand blue" href="javascript:editOperate.getCaseDetailPage('.$val['CaseSerial'].',1);">'.$val['CaseSerial'];
							$arrResult[$i]['CaseSerial'] .='</a><img id="Img_'.$v['FID'].'_'.$val['CaseSerial'].'" src="/images/common/del.gif" class="close_btn" onclick="editOperate.delCaseSerial('.$v['FID'].','.$val['CaseSerial'].')" style="visibility:hidden;" /></div>';
						}
						$arrResult[$i]['CaseSerial'] .='<br/><br class="clearFloat"/>';
					}else{
						foreach($arrFids as $val){
							$arrResult[$i]['CaseSerial'] .= $val['CaseSerial'].'&nbsp;&nbsp;';
						}
					}
				}else{
					if($v['iNumber']){
						if($arrFids[0]['CaseSerial']){
							$arrResult[$i]['CaseSerial'] .='<div id="caseDiv_'.$v['FID'].'_'.$arrFids[0]['CaseSerial'].'" style="width:110px;" onmouseover="editOperate.showImg('.$v['FID'].','.$arrFids[0]['CaseSerial'].');" onmouseout="editOperate.hidImg('.$v['FID'].','.$arrFids[0]['CaseSerial'].');"><a class="hand blue" href="javascript:editOperate.getCaseDetailPage('.$arrFids[0]['CaseSerial'].',1);">'.$arrFids[0]['CaseSerial'];
							$arrResult[$i]['CaseSerial'] .='</a><img id="Img_'.$v['FID'].'_'.$arrFids[0]['CaseSerial'].'" src="/images/common/del.gif" class="close_btn" onclick="editOperate.delCaseSerial('.$v['FID'].','.$arrFids[0]['CaseSerial'].')" style="visibility:hidden;" /></div>';
						}
					}else{
						$arrResult[$i]['CaseSerial'] .= $arrFids[0]['CaseSerial'].'&nbsp;&nbsp;';
					}
				}
				$i++;
			}
		}
		
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page, 'FreezeInfoList'=>$arrResult, 'SystemName'=>$SysUserName, 'RoleID'=>$iRoleID); 
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/EditOperateFreezeTreasureDetailInfo.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		$html=str_replace("</span>","<\/span>",$html);
		echo $html;
	}
	
	/**
	 * 获取财富返还记录
	 */
	function getApplyWealthBackList()
	{
		$iFId = Utility::isNumeric('fid', $_REQUEST) ? $_REQUEST['fid'] : 0;
		if($iFId <= 0){
			echo -1;
			exit();
		}
		$arrResult = $this->objSystemBLL->getCaseAuthProcess($iFId, 0, 1);
		
		$arrTags=array('skin'=>$this->arrConfig['skin'],
					   'ApplyWealthBackList'=>$arrResult);
		
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/ApplyTreasureBackList.html');
		echo $html;
	}
	
	/**
	 * 显示银行、背包解冻信息
	 */
	function showBankKnapsackUnFreezeInfo()
	{
		$iRoleID = Utility::isNumeric('roleID',$_REQUEST); //角色id
		$curPage = Utility::isNumeric('curPage',$_REQUEST);
		$curPage = ($curPage<=0 ? 1 : $curPage);
		$arrParam['fields']='FID, Remarks, OperationType, SysUserName, CONVERT(VARCHAR(20),AddTime,120) AS AddTime';
		$arrParam['tableName']='T_CaseOperateUser';
		$arrParam['where']=" WHERE (OperationType=5 OR OperationType=6) AND RoleID=".$iRoleID;
		$arrParam['order']=' AddTime Desc';
		$arrParam['pagesize']=15;
		//获取角色基本信息
		$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['System']);
		$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']); 
		$arrResult = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);

		$objSessioinLogin = new Session($this->arrConfig['Session']['SessionLoginName']);
		$SysUserName = $objSessioinLogin->get($this->arrConfig['SessionInfo']['UserName']); 
		
		if($arrResult){
			$i=0;
			foreach($arrResult as $v){
				$arrResult[$i]['Remarks'] = str_replace(array("\r\n", "\r", "\n"),'<br />',Utility::gb2312ToUtf8($v['Remarks']));
				$i++;
			}
		}
		
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page, 'FreezeInfoList'=>$arrResult, 'SystemName'=>$SysUserName, 'RoleID'=>$iRoleID); 
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/EditOperateUnFreezeDetailInfo.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	
	/**
	 * 显示其他操作记录
	 * 1:重置银行密码,2:重置背包密码,3:主机解绑,6:返回金币,7:补发金币,
	 * 8:补发龙币,9:补发积分,10:补发道具,11:补发黄钻,12:补发月礼包,
	 */
	function showOtherEditOperateInfo()
	{
		$iRoleID = Utility::isNumeric('roleID',$_REQUEST); //角色id
		$curPage = Utility::isNumeric('curPage',$_REQUEST);
		$curPage = ($curPage<=0 ? 1 : $curPage);
		$arrParam['fields']='PID, Remarks, OperationType, SysUserName, CONVERT(VARCHAR(20),AddTime,120) AS AddTime, Status, Checker, CONVERT(VARCHAR(20),CheckTime,120) AS CheckTime, CheckRemarks';
		$arrParam['tableName']='T_AuthProcess';
		$arrParam['where']=" WHERE OperationType IN (1,2,3,6,7,8,9,10,11,12) AND RoleID=".$iRoleID;
		$arrParam['order']=' AddTime Desc';
		$arrParam['pagesize']=15;
		//获取角色基本信息
		$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['System']);
		$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']); 
		$arrResult = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);

		$objSessioinLogin = new Session($this->arrConfig['Session']['SessionLoginName']);
		$SysUserName = $objSessioinLogin->get($this->arrConfig['SessionInfo']['UserName']); 
		$arrApplyType = array('1'=>'重置银行密码','2'=>'重置背包密码','3'=>'主机解绑','6'=>'返还金币','7'=>'补发金币',
							  '8'=>'补发龙币','9'=>'补发积分','10'=>'补发道具','11'=>'补发黄钻服务','12'=>'补发黄钻福利（月礼包）');
		$arrApplyTypeTo = array(1=>16, 2=>12, 3=>11, 6=>6, 7=>7, 8=>8, 9=>9, 10=>10,11=>4, 12=>10);	//ApplyType=>OpType(操作日志类型)
		if($arrResult){
			$i=0;
			foreach($arrResult as $v){
				$arrResult[$i]['ApplyName'] = $arrApplyType[$v['OperationType']];
				$arrResult[$i]['Remarks'] = str_replace(array("\r\n", "\r", "\n"),' ',Utility::gb2312ToUtf8($v['Remarks']));
				$arrResult[$i]['CheckRemarks'] = Utility::gb2312ToUtf8($v['CheckRemarks']);
				$arrResult[$i]['opType'] = $arrApplyTypeTo[$v['OperationType']];
				$i++;
			}
		}
		
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page, 'OtherInfoList'=>$arrResult, 'SystemName'=>$SysUserName, 'RoleID'=>$iRoleID); 
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/EditOperateOthersDetailInfo.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	
	/**
	 * 嵌套Table框架
	 */
	public function getTableFrame($html, $flag=0)
	{
		$arrTags=array('windowContent'=>$html);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Common/table.html');
		if($flag)
			$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	
	/**
	 * 嵌套Table框架
	 */
	/*public function getInGameHtml()
	{
		$iRoleID = Utility::isNumeric('roleID',$_REQUEST); //角色id
		$arrKind = $this->objMasterBLL->getGameKindList(-1,-1);

		$arrTags=array('skin'=>$this->arrConfig['skin'],
					   'roleID'=>$iRoleID,
					   'arrKind'=>$arrKind);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/InGame.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo json_encode(array("Result"=>$html));
	}*/
	/**
	 * 更新玩家在房间状态
	 */
	public function updateInGame()
	{
		$iResult = -1;
		$iRoleID = Utility::isNumeric('roleID',$_REQUEST); //角色id
		if($iRoleID && $iRoleID>0)
		{
			/*$objUserDataBLL = new UserDataBLL($iRoleID);
			$iResult = $objUserDataBLL->updateInGame($iRoleID,$KindID);*/
			$arrResult = DCClearCurRoomInfo($iRoleID);
            $iResult = $arrResult['iResult'];
			$objSessioin = new Session($this->arrConfig['Session']['SessionLoginName']);
			$iAdminID = $objSessioin->get($this->arrConfig['SessionInfo']['AdminID']);
			$SysUserName = $objSessioin->get($this->arrConfig['SessionInfo']['UserName']);
			$objOperationLogsBLL = new OperationLogsBLL(0);
			$objOperationLogsBLL->addCaseOperationLogs($iRoleID, 0, 29, '解除卡房', $iResult, Utility::getIP(), 0, 2, $SysUserName, '');
		}
		echo $iResult;
	}
	/**
	 * 显示修改通行证注册手机页面
	 */
	public function showPassportInfo()
	{
		$Passport = Utility::isNumeric('Passport',$_REQUEST);
        $iRoleID = Utility::isNumeric('roleID',$_REQUEST);
		/*$objPassAccountBLL = new PassAccountBLL();
		$arrPassAccInfo = $objPassAccountBLL->getUserAccountInfo($Passport,2);*/
		$arrResult = getUserMobilePhone($iRoleID);
		$arrTags=array('skin'=>$this->arrConfig['skin'],
					   'PassInfo'=>$arrResult);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/PassAccountInfo.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo json_encode(array("Result"=>$html));
	}
	/**
	 * 修改通行证注册手机
	 */
	public function updateMobilePhone()
	{
		$Passport = Utility::isNumeric('Passport',$_POST);
		$MobilePhone = Utility::isNumeric('MobilePhone',$_POST);
        $iRoleID = Utility::isNumeric('roleID',$_POST);
		/*$objPassAccountBLL = new PassAccountBLL();
		$iResult = $objPassAccountBLL->updateUserAccountInfo($Passport,$MobilePhone,3);*/
        $arrResult = ASUpdateUserAccountInfo($iRoleID,SURAT_PLAYER_PHONE,$MobilePhone);
        $iResult = isset($arrResult['iResult'])?$arrResult['iResult']:-1;

		$objSessioin = new Session($this->arrConfig['Session']['SessionLoginName']);
		$SysUserName = $objSessioin->get($this->arrConfig['SessionInfo']['UserName']);
		$objOperationLogsBLL = new OperationLogsBLL(0);
		$objOperationLogsBLL->addCaseOperationLogs($iRoleID, 0, 32, '修改通行证注册手机'.$MobilePhone,$iResult , Utility::getIP(), 0, 2, $SysUserName, '');
		
		echo $iResult;
	}
	/**
	 * 发送通行证账号和登陆密码
	 */
	public function sendPassportInfo()
	{
		$ResMsg = '通行证账号和密码未发送至玩家注册手机,请重试';
		$OpResult = -1;
		$newPass = rand(100000,999999);
		$iRoleID = Utility::isNumeric('RoleID',$_POST);	
		$Passport = Utility::isNumeric('Passport',$_POST);
        //var_dump($_POST);
		if($iRoleID && $Passport)
		{
			$objPassAccountBLL = new PassAccountBLL();
			$OpResult = $objPassAccountBLL->resetPassword($Passport,$newPass,'弱');
			$strMsg = !$OpResult ? "发送通行证账号和登陆密码成功,RoleID:$iRoleID" : "发送通行证账号和登陆密码失败,RoleID:$iRoleID";
			if($OpResult==0)
			{
				$arrResult = $objPassAccountBLL->getUserAccountInfo($Passport,2);
				if(!empty($arrResult))
				{
					$MobilePhone = $arrResult['MobilePhone'];
					$LoginCode = $arrResult['LoginCode'];
					$MsgContent = "通行证账号:$LoginCode,登陆密码:$newPass";
					$objMsgBLL = new MsgBLL();
					$arrRes = $objMsgBLL->insertShortMessage(1, $iRoleID, $MobilePhone, Utility::utf8ToGb2312($MsgContent), 27);
					if(!empty($arrRes) && $arrRes['iResult']>0)
						$ResMsg = '通行证账号和密码已成功发送至玩家注册手机,5分钟内请匆重复发送.';
				}
				
			}
			$objSessioin = new Session($this->arrConfig['Session']['SessionLoginName']);
			$SysUserName = $objSessioin->get($this->arrConfig['SessionInfo']['UserName']);
			$objOperationLogsBLL = new OperationLogsBLL(0);
			$objOperationLogsBLL->addCaseOperationLogs($iRoleID, 0, 12, $strMsg, $OpResult, Utility::getIP(), 0, 2, $SysUserName, '');	
		}
		echo json_encode(array('iResult'=>$OpResult,'ResMsg'=>$ResMsg));
	}
	/**
	 * 设置/取消IP段锁定控制
	 */
	public function setIpLocked()
	{
		$iRoleID = Utility::isNumeric('roleID',$_POST);	
		$TitleID = Utility::isNumeric('TitleID',$_POST);	
		if($iRoleID && $iRoleID>0)
		{

			/*$objUserBLL = new UserBLL($iRoleID);
			$arrResult = $objUserBLL->setIpLocked($TitleID);*/
            $arrResult = ASSetRoleIPLock($iRoleID,$TitleID);
            var_dump($arrResult);
			if(!empty($arrResult))
			{				
				if($arrResult['iResult']==0)
				{
					$objSessioin = new Session($this->arrConfig['Session']['SessionLoginName']);
					$SysUserName = $objSessioin->get($this->arrConfig['SessionInfo']['UserName']);
					$Note = $TitleID==0 ? '取消IP段锁定控制' : '设置IP段锁定控制';
					$objOperationLogsBLL = new OperationLogsBLL(0);
					$objOperationLogsBLL->addCaseOperationLogs($iRoleID, 0, 35, $Note, $arrResult['iResult'], Utility::getIP(), 0, 2, $SysUserName, '');	
				}
				echo json_encode($arrResult);
			}
		}		
	}
	/**
	 * 删除玩家账号,对应的角色、角色对应的数据
	 */
	public function showDelPlayer()
	{
		$iRoleID = Utility::isNumeric('roleID',$_POST);	
		$Passport = Utility::isNumeric('Passport',$_POST);
		$time = $this->ReturnTime();
		if(!$time)
			echo json_encode(array("Result"=>'请在22:00到22:10分再进行此操作'));
		else 
		{	
			if($iRoleID && $iRoleID>0 && $Passport && $Passport>0)
			{
				$Wealth = $this->ReturnMoney($iRoleID);				
				if(!$Wealth)
					$html = '该玩家金币数不为零,无法进行此操作.';
				else 
				{
					$arrTags=array('skin'=>$this->arrConfig['skin'],
							   	   'RoleID'=>$iRoleID,
								   'Passport'=>$Passport);
					Utility::assign($this->smarty,$arrTags);
					$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/DelPlayerTable.html');
					$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
				}
				echo json_encode(array("Result"=>$html));				
			}
			else 
				echo json_encode(array("Result"=>'参数异常,请重试'));
		}
	}
	/**
	 * 删除玩家账号,对应的角色、角色对应的数据
	 */
	public function DelPlayer()
	{
		$iResult = -1;
		$iRoleID = Utility::isNumeric('RoleID',$_POST);	
		$Passport = Utility::isNumeric('Passport',$_POST);
		$time = $this->ReturnTime();
		if(!$time)
			$iResult = -2;
			//echo json_encode(array("Result"=>'请在22:00到22:10分再进行此操作'));
		else 
		{	
			if($iRoleID && $iRoleID>0 && $Passport && $Passport>0)
			{
				$Wealth = $this->ReturnMoney($iRoleID);				
				if(!$Wealth)
					$iResult = -3;//$html = '该玩家金币数不为零,无法进行此操作.';
				else 
				{
					$objPassAccountBLL = new PassAccountBLL();
					$iResult = $objPassAccountBLL->DelPlayer($Passport);
					if($iResult==0)
					{
						$objPassSecurityBLL = new PassSecurityBLL();
						$objPassSecurityBLL->DelPlayer($Passport);
						
						$objUserBLL = new UserBLL($iRoleID);
						$objUserBLL->deletePlayer();
						
						$objUserDataBLL = new UserDataBLL($iRoleID);
						$objUserDataBLL->DelPlayer($iRoleID);
					
						$objSessioin = new Session($this->arrConfig['Session']['SessionLoginName']);
						$SysUserName = $objSessioin->get($this->arrConfig['SessionInfo']['UserName']);
						$objOperationLogsBLL = new OperationLogsBLL(0);
						$objOperationLogsBLL->addCaseOperationLogs($iRoleID, 0, 36, "账号检查,删除玩家数据,通行证编号:$Passport,角色ID:$iRoleID", 0, Utility::getIP(), 0, 2, $SysUserName, '');	
					}
				}
			}
	
		}
		echo json_encode(array("Result"=>$iResult));
		
	}
	/**
	 * 积分检查只能在22:00到22:10分进行操作
	 */
	private function ReturnTime()
	{
		$time = date('H:i');
		if($time<'22:00' || $time>'22:10')
			return false;
		else
			return true;		
	}
	/**
	 * 积分检查只能对金币总数等于0的玩家进行操作
	 */
	private function ReturnMoney($iRoleID)
	{
		$objUserDataBLL = new UserDataBLL($iRoleID);
		$arrResult = $objUserDataBLL->getUserWealthByRoleID($iRoleID);
		if(!empty($arrResult) && $arrResult['Money']>0)
			return false;
		else
			return true;
	}
	/**
	 * 积分检查
	 */
	public function showAddHappyBean()
	{
		$iRoleID = Utility::isNumeric('roleID',$_POST);
		$time = $this->ReturnTime();
		if(!$time)
			echo json_encode(array("Result"=>'请在22:00到22:10分再进行此操作'));
		else 
		{	
			if($iRoleID && $iRoleID>0)
			{
				$Wealth = $this->ReturnMoney($iRoleID);				
				if(!$Wealth)
					$html = '该玩家金币数不为零,无法进行补偿.';
				else 
				{
					$arrTags=array('skin'=>$this->arrConfig['skin'],
							   	   'RoleID'=>$iRoleID,
								   'HappyBean'=>$this->arrConfig['HappyBean']
								   );
					Utility::assign($this->smarty,$arrTags);
					$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/AddHappyBeanTable.html');
					$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
				}
				echo json_encode(array("Result"=>$html));			
			}
			else 
				echo json_encode(array("Result"=>'参数异常,请重试'));
		}
	}
	/**
	 * 积分检查
	 */
	public function AddHappyBean()
	{
		$iResult = -1;
		$HappyBean = $this->arrConfig['HappyBean'];//21200000000;
		$iRoleID = Utility::isNumeric('RoleID',$_POST);	
		$time = $this->ReturnTime();
		if(!$time)
			$iResult = -2;
		else 
		{	
			if($iRoleID && $iRoleID>0)
			{
				$Wealth = $this->ReturnMoney($iRoleID);				
				if(!$Wealth)
					$iResult = -3;
				else 
				{
					$objBankBLL = new BankBLL();
					$arrResult = $objBankBLL->updateSysBank(1,$HappyBean);
					if(!empty($arrResult) && $arrResult['iResult']==0)
					{				
						$objUserDataBLL = new UserDataBLL($iRoleID);
						$arrRes = $objUserDataBLL->editUserBankMoney($HappyBean, 0, 1);
						if(!empty($arrRes) && $arrRes['iResult']==0)
						{
							$iResult = 0;
							$Note = "积分检查,角色ID为".$iRoleID."的玩家增加".$HappyBean."金币";
							
							//$objDataChangeLogsBLL = new DataChangeLogsBLL($iRoleID);
							//$objDataChangeLogsBLL->writeBankTransLog($iRoleID,'',8,1,'基本户',1,$HappyBean,$arrRes['Money'],0,'积分检查');
							
							//$objDataChangeLogsBLL->insertSysBankTransLogs(1, $iRoleID, 0, 11, 2, $HappyBean, $arrResult['LastBalance'], $arrResult['Balance'], '');
							
							$objSessioin = new Session($this->arrConfig['Session']['SessionLoginName']);
							$SysUserName = $objSessioin->get($this->arrConfig['SessionInfo']['UserName']);
							$objOperationLogsBLL = new OperationLogsBLL(0);
							$objOperationLogsBLL->addCaseOperationLogs($iRoleID, 0, 37, $Note, 0, Utility::getIP(), 0, 2, $SysUserName, '');
						}
						else 
						{
							$arrR = $objBankBLL->setSysBankMoney(1,$HappyBean,2);
							if(!empty($arrR) && $arrR['iResult']==0)
							{}
							else 
							{
								//$objDataChangeLogsBLL = new DataChangeLogsBLL($iRoleID);
								//$objDataChangeLogsBLL->insertSysBankTransLogs(1, $iRoleID, 0, 11, 1, $HappyBean, $arrResult['LastBalance'], $arrResult['Balance'], Utility::utf8ToGb2312('积分检查,银行回滚失败'));
								
								$Note = "积分检查,角色ID为".$iRoleID."的玩家增加".$HappyBean."金币(失败)";
								$objSessioin = new Session($this->arrConfig['Session']['SessionLoginName']);
								$SysUserName = $objSessioin->get($this->arrConfig['SessionInfo']['UserName']);
								$objOperationLogsBLL = new OperationLogsBLL(0);
								$objOperationLogsBLL->addCaseOperationLogs($iRoleID, 0, 37, $Note, -1, Utility::getIP(), 0, 2, $SysUserName, '');
							}
						}	
					}
				}
			}
		}
		echo json_encode(array("Result"=>$iResult));
	}

    /**使用json
     *
     */
    public function taobao_ip_info(){
        $callback = Utility::isNullOrEmpty("callback",$_REQUEST);
        $ip = Utility::isNullOrEmpty("ip",$_REQUEST);

        $arr = Utility::getIPDistrict2($ip);
        $arr = str_replace($ip,'',$arr);
        //$arr = json_encode($arr);
        echo $arr;
    }
    /**
     * 获取手机归属地
     * 
     * */
    public function taobao_tel_segment(){
        $tel = Utility::isNullOrEmpty("tel", $_REQUEST);
        $arr = Utility::getTelSegment($tel);
        $arr = json_encode($arr);
        echo $arr;
    }
    /**
     * 获取手机归属地
     *
     * */
    public function tel_segment(){
        $tel = Utility::isNullOrEmpty("tel", $_REQUEST);
        $ret = $this->objMasterBLL->getTelSegment($tel);
        $ret = json_encode($ret);
        echo $ret;
    }
    /**
     * 获取身份证号归属地
     *
     * */
    public function card_segment(){
        $idcard = Utility::isNullOrEmpty("idcard", $_REQUEST);
        $ret = $this->objMasterBLL->getCardSegment($idcard);
        $ret = json_encode($ret);
        echo $ret;
    }
    /***
     * 设置银行转账微信绑定
     * 
     * */
    public function SetBankWeChatCheck(){

        $LoginID= Utility::isNullOrEmpty("roleID",$_REQUEST);
        $value = Utility::isNullOrEmpty('value', $_REQUEST);
        $ret = DCSetBankWeChatCheck($LoginID, $value);
        echo json_encode(array("Result"=>$ret['iResult']));
    }
    
    /***
     * 解锁玩家银行
     *
     * */
    public function UnLockUserBank(){
    
        $LoginID= Utility::isNullOrEmpty("roleID",$_REQUEST);
        $ret = DCUnLockUserBank($LoginID);
        echo json_encode(array("Result"=>$ret['iResult']));
    }
    /***
     * 设置登陆验证方式
     *
     * */
    public function SetPassType(){
    
        $LoginID= Utility::isNullOrEmpty("roleID",$_REQUEST);
        $ret = ASSetPassType($LoginID,0);
        echo json_encode(array("Result"=>$ret['iResult']));
    }
    /**
     * 清楚角色日志
     * 
     * */
    public function ClearRoleData(){
        $LoginID= Utility::isNullOrEmpty("roleID",$_REQUEST);
        $objCommonBLL = new CommonBLL($this->arrConfig['MapType']['DataChangeLogs'],0);
        $objCommonBLL->delAllLogs('T_UserVIPLogs_', $LoginID);
        $objCommonBLL->delAllLogs('T_LoginLogs_', $LoginID);
        $objCommonBLL->delAllLogs('T_LogoutLogs_', $LoginID);
        $objCommonBLL->delLogs('T_LockMoneyLogs', $LoginID);
        $objCommonBLL->delAllLogs('T_BankWealthChangeLogs_', $LoginID);
        $objCommonBLL = new CommonBLL($this->arrConfig['MapType']['SetOperationLogs'],0);
        $objCommonBLL->delLogs('T_UserSetOperateLogs_', $LoginID);
        $objCommonBLL = new CommonBLL($this->arrConfig['MapType']['OperationLogs'],0);
        $objCommonBLL->delLogs('T_LockUserLogs', $LoginID);
        $objCommonBLL = new CommonBLL($this->arrConfig['MapType']['System'],0);
        $objCommonBLL->delLogs('T_CaseOperateUser', $LoginID);
        $objCommonBLL->delLogs('T_AuthProcess', $LoginID);
        $ret = DCClearRoleData($LoginID);
        echo json_encode(array("Result"=>$ret['iResult']));
        
    }
    //
    public function lockPagerRolePre()
    {
    	$LoginID = Utility::isNumeric('LoginID',$_REQUEST);	//游戏编号
    	$SafePhone = Utility::isNumeric('SafePhone', $_REQUEST); //安全手机
    	$LoginCode = Utility::isNullOrEmpty('LoginCode', $_REQUEST);//通行证账号
    	$CardNumber = Utility::isNullOrEmpty('CardNumber', $_REQUEST);//身份证号
    	$LoginName = Utility::isNullOrEmpty('LoginName', $_REQUEST);//角色昵称
    	$LastLoginIP = Utility::isNullOrEmpty('LastLoginIP', $_REQUEST);//登陆IP
    	$QQ = Utility::isNullOrEmpty('QQ', $_REQUEST);
    	$MachineSerial = Utility::isNullOrEmpty('MachineSerial', $_REQUEST);//机器码
    	$WechatSerial = Utility::isNullOrEmpty('WeChatSerial', $_REQUEST);//微信码
    	$strReason =  Utility::isNullOrEmpty('strReason', $_REQUEST);//原因
    	$curPage = Utility::isNumeric('reqPage',$_REQUEST)?$_REQUEST['reqPage']:0;
    	$totalPage = Utility::isNumeric('totalPage',$_REQUEST)?$_REQUEST['totalPage']:0;
    	if(!$curPage)
    		$curPage = Utility::isNumeric('curPage',$_REQUEST)?$_REQUEST['curPage']:1;
    	if(!$totalPage)
    		$totalPage = $curPage;
    	$arrTags = array(
    		 'skin'=>$this->arrConfig['skin'],
    	     'LoginID' => $LoginID,
    		 'SafePhone' => $SafePhone,
    		 'LoginCode' => $LoginCode,
    		 'CardNumber' => $CardNumber,
    		 'LoginName' => $LoginName,
    		 'LastLoginIP' => $LastLoginIP,
    		 'QQ' => $QQ,
    		 'MachineSerial' => $MachineSerial,
    		 'WechatSerial' => $WechatSerial,
    		 'strReason' => $strReason,
    		 'curPage' =>$curPage,
    		 'totalPage' => $totalPage 
    	);
    	
    	Utility::assign($this->smarty,$arrTags);
    	$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/OperatelockRolePage.html');
    	echo $html;
    }

    /**
     * 批量锁定角色
     */
    public function lockPagerRole()
    {
        Utility::setMicroTime();
        $LoginID = Utility::isNumeric('LoginID',$_REQUEST);	//游戏编号
        $SafePhone = Utility::isNumeric('SafePhone', $_REQUEST); //安全手机
        $LoginCode = Utility::isNullOrEmpty('LoginCode', $_REQUEST);//通行证账号
        $CardNumber = Utility::isNullOrEmpty('CardNumber', $_REQUEST);//身份证号
        $LoginName = Utility::isNullOrEmpty('LoginName', $_REQUEST);//角色昵称
        $LastLoginIP = Utility::isNullOrEmpty('LastLoginIP', $_REQUEST);//登陆IP
        $QQ = Utility::isNullOrEmpty('QQ', $_REQUEST);
        $MachineSerial = Utility::isNullOrEmpty('MachineSerial', $_REQUEST);//机器码
        $WechatSerial = Utility::isNullOrEmpty('WeChatSerial', $_REQUEST);//微信码
        $strReason =  Utility::isNullOrEmpty('strReason', $_REQUEST);//原因
        $curPage = Utility::isNumeric('reqPage',$_REQUEST)?$_REQUEST['reqPage']:0;
        if(!$curPage)
            $curPage = Utility::isNumeric('curPage',$_REQUEST)?$_REQUEST['curPage']:1;
        $pageSize = 10;
        $Page=NULL;
        $arrResult = null;
        $SearchContent = '';
        $lockStatus['iResult'] = -1;
        $totalNum = 0;
        $SearchParam = array();
        if($LoginID || $SafePhone || $LoginCode || $CardNumber || $LoginName || $LastLoginIP || $QQ || $MachineSerial || $WechatSerial)
		{
			if($LoginID){//玩家编号
                $temp = ASQueryRoleList($LoginID,1,$curPage,$pageSize);
                if(isset($temp['RoleInfoList']))
                    $arrPassSec = $temp['RoleInfoList'];
                $totalNum = ($temp['iTotalPage']-1)*$pageSize+$temp['iRoleCount'];
				$SearchContent = "LoginID($LoginID)";	
			}
			elseif($LoginCode){ //通行证账号
                $temp = ASQueryRoleList($LoginCode,4,$curPage,$pageSize);
                if(isset($temp['RoleInfoList']))
                    $arrPassSec = $temp['RoleInfoList'];
                $totalNum = ($temp['iTotalPage']-1)*$pageSize+$temp['iRoleCount'];
				$SearchContent = "通行证账号($LoginCode)";
			}
			elseif ($SafePhone)//安全手机
			{
				$temp = ASQueryRoleList($SafePhone,7,$curPage,$pageSize);
				$SearchContent = "安全手机($SafePhone)";
                if(isset($temp['RoleInfoList']))
                    $arrPassSec = $temp['RoleInfoList'];
                $totalNum = ($temp['iTotalPage']-1)*$pageSize+$temp['iRoleCount'];
			}
			elseif($LoginName){//角色昵称
				$LoginName1 = Utility::utf8ToGb2312($LoginName);
                $temp = DCQueryRoleID($LoginName1);
                if($temp['iResult'] != 0 ){
                    $LoginID = $temp['iResult'];
                    $temp = ASQueryRoleList($LoginID,1,$curPage,$pageSize);
                    if(isset($temp['RoleInfoList']))
                        $arrPassSec = $temp['RoleInfoList'];
                }
                $totalNum = ($temp['iTotalPage']-1)*$pageSize+$temp['iRoleCount'];
			}
			elseif($LastLoginIP){//登陆IP
                $temp = ASQueryRoleList($LastLoginIP,5,$curPage,$pageSize);
                if(isset($temp['RoleInfoList']))
                    $arrPassSec = $temp['RoleInfoList'];
                $totalNum = ($temp['iTotalPage']-1)*$pageSize+$temp['iRoleCount'];
				$SearchContent = "登陆IP($LastLoginIP)";
			}
			elseif($QQ){//QQ
                $temp = ASQueryRoleList($QQ,6,$curPage,$pageSize);
                if(isset($temp['RoleInfoList']))
                    $arrPassSec = $temp['RoleInfoList'];
                $totalNum = ($temp['iTotalPage']-1)*$pageSize+$temp['iRoleCount'];
				$SearchContent = "QQ($QQ)";
			}
			elseif($CardNumber){//身份证号
                $temp = ASQueryRoleList($CardNumber,3,$curPage,$pageSize);
                if(isset($temp['RoleInfoList']))
                    $arrPassSec = $temp['RoleInfoList'];
                $totalNum = ($temp['iTotalPage']-1)*$pageSize+$temp['iRoleCount'];
				$SearchContent = "身份证($CardNumber)";
			}
			elseif($MachineSerial){//机器码
                $temp = ASQueryRoleList($MachineSerial,8,$curPage,$pageSize);
                if(isset($temp['RoleInfoList'])){
                    $arrPassSec = $temp['RoleInfoList'];
                }
                $totalNum = ($temp['iTotalPage']-1)*$pageSize+$temp['iRoleCount'];
				$SearchContent = "机器码($MachineSerial)";
			}
            elseif($WechatSerial){//微信码
                $temp = ASQueryRoleList($WechatSerial,9,$curPage,$pageSize);
                if(isset($temp['RoleInfoList'])){
                    $arrPassSec = $temp['RoleInfoList'];
                }
                $totalNum = ($temp['iTotalPage']-1)*$pageSize+$temp['iRoleCount'];
                $SearchContent = "微信码($WechatSerial)";
            }
        }
        $iNumber = 1095;
        $msg = '操作成功！';
        foreach ($arrPassSec as $key => $val){
            if($val['iLocked'] == 1)
                continue;
            $iRoleID = $val['iLoginID'];
            $iLoginID = $val['iLoginID'];
            $iLoginName = getUserRealName($iRoleID);
            $iCaseSerial = '';
            $strRemarks = "批量封号".date('Y-m-d H:i:s')."搜索条件:".$SearchContent;
            $objSessioinLogin = new Session($this->arrConfig['Session']['SessionLoginName']);
            $SysUserName = $objSessioinLogin->get($this->arrConfig['SessionInfo']['UserName']);
            //插入角色锁定记录
            $result = $this->objSystemBLL->addCaseOperateUser($iCaseSerial, $iRoleID, $iLoginID, $iLoginName, 3, $iNumber, $strReason,$strRemarks, '', $SysUserName);
            $res = ASLockRole($iRoleID,$iNumber);
            if(isset($res['iResult']) && $res['iResult'] == 0){
                $strMsg="角色锁定成功！";
                $status = 1;
                $OpResult = $res['iResult'];
            }else{
                $msg = '操作失败！';
                $strMsg = "角色锁定失败";
                $status = 0;
                $OpResult = $res['iResult']?$res['iResult']:-1;
            }
            
            //插入锁定角色操作日志
            $objOperationLogsBLL = new OperationLogsBLL($iRoleID);
            $res = $objOperationLogsBLL->addCaseOperationLogs($iRoleID, $iCaseSerial, 0, "角色ID为".$iLoginID."用户因".$strRemarks."被锁定", $OpResult, Utility::getIP(), 0, 2, $SysUserName, '');
            $objOperationLogsBLL->addLockUserLogs($iCaseSerial, $strReason.','.$strRemarks, 0, $iNumber, '', $SysUserName, "角色ID为".$iLoginID."用户因".$strRemarks."被锁定", Utility::getIP());
        }
        $msg=Utility::echoResultHtml($this->smarty,'确定','main.CloseMsgBox(false,\'\');',$msg,'false','Ad',$this->arrConfig);
        echo $msg;
    }
}
?>