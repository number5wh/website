<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';

class LoginAction extends PageBase
{	
	private $objMasterBLL = null;
	
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
	}
	
	public function index()
	{		
		$this->smarty->display($this->arrConfig['skin'].'/login.html');
	}
	/**
	 * 验证登陆 
	 */
	public function Login()
	{	   	    
	    
		$arrMsg = '';
		$strUserName = Utility::isNullOrEmpty('UserName',$_POST);
		$strUserPwd = Utility::isNullOrEmpty('UserPwd',$_POST);
		$bindaccout = Utility::isNullOrEmpty('bindaccount',$_POST);
		$strChkCode = Utility::isNullOrEmpty('ChkCode',$_POST);
		
		if(!$strUserName)
			$arrMsg = array('iStatus'=>-1,'strMsg'=>'请输入用户登陆名');
		if(!$strUserPwd)
			$arrMsg = array('iStatus'=>-2,'strMsg'=>'请输入用户登陆密码');
		if(!$strChkCode)
			$arrMsg = array('iStatus'=>-3,'strMsg'=>'请输入验证码');
		if(!empty($arrMsg))
		{
			echo json_encode($arrMsg);
			return false;
		}

		//检查验证码,通过验证后重置
		$objSessioin = new Session($this->arrConfig['Session']['SessionData']);
		$strSessionCode = $objSessioin->get($this->arrConfig['SessionInfo']['ChkCode']);
		if($strSessionCode!=$strChkCode)
		{
			$arrMsg = array('iStatus'=>-4,'strMsg'=>'请输入验证码错误');
			echo json_encode($arrMsg);
			return false;
		}		
		$objSessioin->set($this->arrConfig['SessionInfo']['ChkCode'],'');		
		if($strUserName && $strUserPwd && $strChkCode && $bindaccout)
		{		
		   
			//验证登陆名和登陆密码
			$this->objMasterBLL = new MasterBLL();
			$arrResult = $this->objMasterBLL->chkAdminLogin($strUserName,md5($strUserPwd),$bindaccout);

			if(is_array($arrResult) && count($arrResult)>0)
			{
				//登陆成功
				if($arrResult['iResult']==1)
				{
					$objSessioinLogin = new Session($this->arrConfig['Session']['SessionLoginName']);
					$objSessioinLogin->set($this->arrConfig['SessionInfo']['AdminID'],$arrResult['AdminID']);
					$objSessioinLogin->set($this->arrConfig['SessionInfo']['UserName'],$strUserName);
                    $objSessioinLogin->set($this->arrConfig['SessionInfo']['DeptID'],$arrResult['DeptID']);
                    $objSessioinLogin->set($this->arrConfig['SessionInfo']['AdminRole'],$arrResult['AdminRole']);
                    $this->objMasterBLL->setRoleSession($arrResult['AdminID'],$objSessioinLogin->getSessionID());
					$arrMsg = array('iStatus'=>0,'strMsg'=>'');
				}
				elseif($arrResult['iResult']==0)
					$arrMsg = array('iStatus'=>-5,'strMsg'=>'请输入的用户名不存在');
				else{

				    if($this->arrConfig['CheckIP']){
				        $iResult = $this->objMasterBLL->CheckLoginIp(Utility::getIP(),$strUserName,1,$this->arrConfig['LimitSeconds'],$this->arrConfig['VisitCount']);
				        if($iResult==-1)
				        {
				            $this->objMasterBLL->setSysUserStatus($arrResult['AdminID'],1);
				        }
				    }
					$arrMsg = array('iStatus'=>-6,'strMsg'=>'请输入的密码错误或绑定的手机号或邮箱不正确');
				}
			}
			else 
				$arrMsg = array('iStatus'=>-7,'strMsg'=>'请输入的用户名不存在');
			echo json_encode($arrMsg);
		}
		
	}
	/**
	 * 退出
	 */
	public function Logout()
	{
		$objSessioinLogin = new Session($this->arrConfig['Session']['SessionLoginName']);
		$objSessioinLogin->set($this->arrConfig['SessionInfo']['AdminID'],'');
		$objSessioinLogin->set($this->arrConfig['SessionInfo']['UserName'],'');
		$objSessioinLogin->set($this->arrConfig['SessionInfo']['DeptID'],'');
		echo 1;
	}
	/**
	 * 检查上次操作时间
	 * 
	 * */
	public function checkLastAction(){

        $objMaster = $this->objMasterBLL = new MasterBLL();
        $objSessioinLogin = new Session($this->arrConfig['Session']['SessionLoginName']);
        $adminId = $objSessioinLogin->get($this->arrConfig['SessionInfo']['AdminID']);
        $sessionId = $objSessioinLogin->getSessionID();
	    $arrSession = $objMaster->getRoleSession($adminId);
	    if($sessionId!=$arrSession['SessionId']){
            $objSessioinLogin->set($this->arrConfig['SessionInfo']['AdminID'],'');
            $objSessioinLogin->set($this->arrConfig['SessionInfo']['UserName'],'');
            $objSessioinLogin->set($this->arrConfig['SessionInfo']['DeptID'],'');
            echo 1;
        }
        else {
            $objSessioinLogin = new Session($this->arrConfig['Session']['SessionLoginName']);
            $LastOperateTime = $objSessioinLogin->get($this->arrConfig['SessionInfo']['LastOperateTime']);
            if (time() - $LastOperateTime > $this->arrConfig['AutoLogoutTime'])
                echo 1;
            else
                echo 0;
        }
	}
}
?>