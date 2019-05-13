<?php
require_once ROOT_PATH . 'Common/PageBase.class.php';
/**
 * 首页
 * @author xuluojiong
 */
class GamesAction extends PageBase
{
	private $objMasterBLL;
	private $objPassAccountBLL;	
	private $skin=null;
	private $template_dir=null;
	private $smarty;	
	private $CFG=null;
	private $objSession=null;

	
	//当前分页
	private $pageids;
	private $param = array();
	public function __construct()
	{		
		global $smarty;
		$this->CFG=unserialize(SYS_CONFIG);
		$this->skin=$this->CFG['skin'];		
		$this->template_dir=$this->CFG['template_dir'];
		$this->smarty = $smarty;
	    Utility::assign( array('url'=>$this->CFG['URL']));

	}
	/**
	 * 首页
	 */
	public function index()
	{	   
	    $arrTags = array('GameKind'=>$this->CFG['GameKind']);
	    Utility::assign($arrTags);
		$this->smarty->display($this->skin.'/games.html');
	}
	public function register()
	{
		$iResult = -1;
		$LoginCode=Utility::isNullOrEmpty('LoginCode',$_POST);
		$UserPass=Utility::isNullOrEmpty('UserPass',$_POST);
		$Confirm=Utility::isNullOrEmpty('Confirm',$_POST);
		
		$phone=Utility::isNullOrEmpty('phone',$_POST);
		$realname=Utility::isNullOrEmpty('realname',$_POST);
		$cardno=Utility::isNullOrEmpty('cardno',$_POST);

		$Email=Utility::isNullOrEmpty('Email',$_POST);
		$CheckCode=Utility::isNullOrEmpty('CheckCode',$_POST);
		$ChkCode = $this->objSession->get($this->CFG['SessionInfo']['ChkCode']);
		if($CheckCode !== $ChkCode){
			echo -9;
			exit;
		}
		
		if($LoginCode && $UserPass && $Confirm && $phone && $realname && $cardno && $Email)
		{
			if($UserPass!=$UserPass)
				$iResult=-10;//两次密码不一致
			else
			{
				//$iResult = $this->objPassAccountBLL->register($LoginCode,$UserPass,$realname,$cardno,$phone,$Email);
				$SecGrade = Utility::utf8ToGb2312(Utility::getPwdStrong($UserPass));
				$iResult = dc_register_passport($LoginCode,md5($UserPass),Utility::utf8ToGb2312($realname),$cardno,$phone,Utility::getIP(),$SecGrade,$Email);
			}
		}
		else
		{
			if(empty($LoginCode))
				$iResult=-11;
			elseif(empty($UserPass))
				$iResult=-12;
			elseif(empty($Confirm))
				$iResult=-13;
			elseif(empty($phone))
				$iResult=-14;
			elseif(empty($realname))
				$iResult=-15;
			elseif(empty($cardno))
				$iResult=-16;
			elseif(empty($Email))
				$iResult=-17;
			elseif(empty($CheckCode))
				$iResult=-18;
		}
		echo json_encode(array('iResult'=>$iResult,'LoginCode'=>$LoginCode));
	}
	
	public function success()
	{
		$LoginCode=Utility::isNullOrEmpty('LoginCode',$_GET);
		if($LoginCode)
		{
			//$arrResult = $this->objPassAccountBLL->getPassportInfo($LoginCode);
			$arrResult = dc_get_passport_info($LoginCode);
			if(is_array($arrResult))
			{
				$arrTags = array('PassInfo'=>$arrResult);
				//文本日志
				$fp = fopen('Logs/'.date('Y-m-d').".txt", "a+");
				if($fp)
					fwrite($fp,date('Y-m-d H:i:s').',账号:'.$arrResult['LoginCode'].',创建时间:'.$arrResult['CreateTime'].',手机:'.$arrResult['Mobile'].',邮箱:'.$arrResult['Email']."\r\n"); 
				fclose($fp);
			}
			else
			{
				$arrTags = array('PassInfo'=>null);
				//文本日志
				$fp = fopen('Logs/'.date('Y-m-d').".txt", "a+");
				if($fp)
					fwrite($fp,date('Y-m-d H:i:s').$arrResult."\r\n"); 
				fclose($fp);
			}
		}
		else
			$arrTags = array('PassInfo'=>null);
		Utility::assign($arrTags);
		$this->smarty->display($this->skin.'/success.html');
	}
	
	
	public function editPassword()
	{		
		$this->smarty->display($this->skin.'/editPassword.html');
	}
	
	public function chkPassport()
	{
		$LoginCode=Utility::isNullOrEmpty('LoginCode',$_POST);
		$arrResult = array();
		if($LoginCode){
			//$arrResult = $this->objPassAccountBLL->chkPassport($LoginCode);		
			$iResult = dc_check_passport($LoginCode);
			if($iResult>0)
				$arrResult['Passport']=$iResult;
			else 
				$arrResult = null;
		}
		
		echo json_encode($arrResult);		
	}
	
	public function updatePassword()
	{
		$Passport=Utility::isNullOrEmpty('Passport',$_POST);
		$OldPwd=Utility::isNullOrEmpty('UserPass',$_POST);
		$NewPwd=Utility::isNullOrEmpty('EditPass',$_POST);
		$Confirm=Utility::isNullOrEmpty('Confirm',$_POST);
		
		$arrResult = array();
		if($Passport && $NewPwd && $OldPwd && $Confirm){
			if($NewPwd!=$Confirm)
				$iResult=-10;//两次密码不一致
			else{
				$SecGrade = Utility::utf8ToGb2312(Utility::getPwdStrong($NewPwd));
				//$iResult = $this->objPassAccountBLL->updatePassportPwd($Passport, $OldPwd, $NewPwd, $SecGrade);	
				$iResult = dc_change_passpwd($Passport,md5($OldPwd),md5($NewPwd),$SecGrade);		
			}
		}else{
			if(empty($Passport))
				$iResult=-11;
			elseif(empty($OldPwd))
				$iResult=-12;
			elseif(empty($NewPwd))
				$iResult=-13;
			elseif(empty($Confirm))
				$iResult=-14;
		}
		
		echo $iResult;
	}
	
	public function findPassword()
	{
		$this->smarty->display($this->skin.'/findPassword.html');
	}
	
	public function resetPassPwd()
	{
		$Seconds = 0;//
		$LoginCode=Utility::isNullOrEmpty('LoginCode',$_POST);
		$Passport=Utility::isNullOrEmpty('Passport',$_POST);
		$CheckCode=Utility::isNullOrEmpty('CheckCode',$_POST);
		$ChkCode = $this->objSession->get($this->CFG['SessionInfo']['ChkCode']);
		if($ChkCode !== $CheckCode){
			echo json_encode(array('iResult'=>-10,'Seconds'=>$Seconds));
			exit;
		}
		$this->objSession->set($this->CFG['SessionInfo']['ChkCode'],'');
		
		if($LoginCode && $Passport){
			//$arrResult = $this->objPassAccountBLL->getPassportInfo($LoginCode);
			$arrResult = dc_get_passport_info($LoginCode);
			if(is_array($arrResult) && isset($arrResult['Mobile'])){
				$NewPwd = rand(100000,999999);
				$SecGrade = Utility::utf8ToGb2312(Utility::getPwdStrong($NewPwd));
				$iResult=dc_reset_passpwd($Passport,$NewPwd,$SecGrade);
				/*
				//if(!$iResult && isset($result2) && !$result2['iResult']){
					$objMsgBLL = new MsgBLL();
					$arrRes = $objMsgBLL->insertShortMessage(2,$Passport,$arrResult['MobilePhone'],$NewPwd,26);
					
					if(!empty($arrRes))
					{
						$iResult = $arrRes['iResult'];
						$Seconds = $arrRes['Seconds'];//
					}
					if($iResult==-3) $iResult = -4;
					/*$arrTags = array('loginCode'=>$LoginCode, 'pwd'=>$NewPwd, 'bankPwd'=>'123456');
					Utility::assign($arrTags);
					$body = $this->smarty->fetch($this->skin.'/email.html');
					$this->sendMail('597980472@qq.com', $arrResult['Email'], '密码重置--'.date('Y-m-d H:i:s', time()), $body);
					echo json_encode(array('iResult'=>$iResult, 'email'=>$arrResult['Email']));
					exit;* /
				//}else{
				//	$iResult=-1;
				//}
				if($iResult>0)
				{
					//修改登陆密码
					$iResult = $this->objPassAccountBLL->ResetPassportPwd($Passport, $NewPwd, $SecGrade); 
					//修改银行密码
					$result = $this->objMasterBLL->getRoleIDByKeyID($LoginCode, 1);
					if($result){
						$this->objUserDataBLL = new UserDataBLL($result ['RoleID']);		
						$result2 = $this->objUserDataBLL->updateBankPwd($NewPwd);
					}
				}*/
			}else{
				$iResult=-1;
			}
		}else{
			$iResult = -11;
		}
		echo json_encode(array('iResult'=>$iResult,'Seconds'=>$Seconds));
	}
	

    function introduce(){
        $id = $_GET['id'];


        //$this->smarty->assign("content",$this->skin."/Games/1010/index.htm");


        //$foot = $this->smarty->fecth($this->skin.'/Games/footer.tpl');
        $arrTag = array("skin"=>$this->skin,"KindID"=>$id,"Host"=>$_SERVER['HTTP_HOST']);
        Utility::assign($arrTag);
        $this->smarty->display($this->skin.'/Games/intro.tpl');
    }

    /**
     *
     */
    function client_game_intro(){
        $id = $_GET['id'];
        if(empty($id)||!is_numeric($id)){
            //
            exit;
        }
        $this->smarty->display($this->skin.'/Games/'.$id.'.tpl');
    }
}
?>