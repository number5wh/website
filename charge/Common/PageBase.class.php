<?php
class PageBase
{
	//页面访问相关参数
	protected $strClassName = 'Recommend';
	protected $strActionName = 'Recommend';
	
	/*protected $p = '';
	public function __construct()
    {
   		$iRoleID = Utility::isNumeric('RoleID',$_GET);
		$auth = Utility::isNullOrEmpty('auth',$_GET);
		$strMachineSerial=Utility::isNullOrEmpty('ms',$_GET);		
		$loginFlag = false;
		//验证登陆 
		if($iRoleID && $auth && $strMachineSerial)
		{
			$loginBLL = new LoginBLL($iRoleID);
			$loginFlag = $loginBLL->chkGameUserLogin($iRoleID,$auth,$strMachineSerial);
		}
		if(!$loginFlag || !$iRoleID || !$auth || !$strMachineSerial)
		{
			Utility::output('身份验证失败,请重新登录.');
		}		
		$this->p = 'RoleID='.$iRoleID.'&auth='.$auth.'&ms='.$strMachineSerial;
    }*/
	/**
	 * 设置类名
	 * @param string $strClassName 类名
	 */
	public function setClassName($strClassName)
	{
		$this->strClassName = $strClassName;
	}
	/**
	 * 设置Action名
	 * @param string $strActionName Action名
	 */
	public function setActionName($strActionName)
	{
		$this->strActionName = $strActionName;
	}
	/**
	 * Action初始化
	 */
	final function init()
	{
		global $smarty;
		$smarty->register_function('au', 'getSmartyActionUrl');
	}
}
/**
 * 注册smarty方法
 * @param unknown_type $params
 */
function getSmartyActionUrl($params) {
	$strUrl = '/?';
	//页面名字
	if (isset($params['n']) && !empty($params['n'])) {
		$strUrl .= "n={$params['n']}&";
	}	
	if (isset($params['a']) && !empty($params['a']))
	{
		$strUrl .= "a={$params['a']}";
	}	
	if (!empty($params['p']))
	{
		$strUrl .='&' . $params['p'];
	}
	
	$CFG = unserialize(SYS_CONFIG);	
	$objSessioin = new Session($CFG['Session']['SessionLoginName']);
	$RoleID = $objSessioin->get($CFG['SessionInfo']['RoleID']);
	$Auth = $objSessioin->get($CFG['SessionInfo']['Auth']);
	$Ms = $objSessioin->get($CFG['SessionInfo']['MachineSerial']);
	
	/*$RoleID=$_SESSION['RoleID'];
	$Auth=$_SESSION['Auth'];
	$Ms=$_SESSION['MachineSerial'];*/
	$strUrl .= '&RoleID='.$RoleID.'&auth='.$Auth.'&ms='.$Ms;
	return $strUrl;
}
?>