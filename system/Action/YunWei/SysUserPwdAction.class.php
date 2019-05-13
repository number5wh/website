<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/OperationLogsBLL.class.php';

class SysUserPwdAction extends PageBase
{	
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);		
	}
	public function index()
	{		
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/SysUserPwdEdit.html');
	}	 
	/**
	 * 修改密码
	 * 0:成功,-1:失败,-2:用户不存在,-3:原密码错误,-4:新密码和确认密码不一致
	 */
	public function updateSysUserPwd()
	{
		$iResult = -1;
		$OldPwd = Utility::isNullOrEmpty('OldPwd',$_POST);
		$NewPwd = Utility::isNullOrEmpty('NewPwd',$_POST);
		$ConfirmPwd = Utility::isNullOrEmpty('ConfirmPwd',$_POST);
		if($OldPwd && $NewPwd  && $ConfirmPwd)
		{
			if($NewPwd!=$ConfirmPwd)
				$iResult = -4;
			else 
			{
				$objMasterBLL = new MasterBLL();
				$iResult = $objMasterBLL->updateSysUserPwd($OldPwd,$NewPwd);
				//添加管理员操作日志
				$objSessioin = new Session($this->arrConfig['Session']['SessionLoginName']);
				$iAdminID = $objSessioin->get($this->arrConfig['SessionInfo']['AdminID']);
				$SysUserName = $objSessioin->get($this->arrConfig['SessionInfo']['UserName']);
				$objOperationLogsBLL = new OperationLogsBLL(0);
				$objOperationLogsBLL->addCaseOperationLogs($iAdminID, 0, 31, '权限设置,修改密码', $iResult, Utility::getIP(), 0, 2, $SysUserName, '');
				
			}
		}
		echo $iResult;
	}
	
}
?>