<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';

class SysRoleLevelAction extends PageBase
{	
	private $objMasterBLL = null;	
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);
		$this->objMasterBLL = new MasterBLL();
	}
	public function index()
	{
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/SysRoleLevelList.html');
	}	 
	/**
	 * 显示添加黄钻等级表单
	 */
	public function showAddRoleLevelHtml()
	{
		$LvlID = Utility::isNumeric('LvlID',$_POST);
		if($LvlID){
			$arrLevelInfo = $this->objMasterBLL->getRoleLevel($LvlID);
		}
		else 
			$arrLevelInfo=array('LvlID'=>1,'LvlExperience'=>0,'MaxScoreDay'=>0,'LoginScore'=>0,
								'MaxScoreOnline'=>0,'MaxScoreOnlineHour'=>0,'MaxScoreGame'=>0,
								'MaxScoreTask'=>0
								);
		$arrTags = array('Lvl'=>$arrLevelInfo);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/SysRoleLevelEdit.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));

		echo $html;
	}
	/**
	 * 分页
	 */
	public function getPagerLevel()
	{
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage<=0 ? 1 : $curPage;
		$arrParam['fields']='LvlID,LvlExperience,MaxScoreDay';
		$arrParam['tableName']='T_SysRoleLevel';
		$arrParam['where']='';
		$arrParam['order']='LvlID';
		$arrParam['pagesize']=20;

		$objCommonBLL = new CommonBLL(0);
		$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);		
		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
		$arrLvlList = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);
		
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'LevelList'=>$arrLvlList);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/SysRoleLevelListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	/**
	 * 添加角色等级
	 */
	public function addRoleLevel()
	{
		$iResult = -9999;
		$arrParams['LvlID'] = Utility::isNumeric('LvlID',$_POST);
		$arrParams['LvlExperience'] = Utility::isNumeric('LvlExperience',$_POST);
		$arrParams['MaxScoreDay'] = Utility::isNumeric('MaxScoreDay',$_POST);
		$arrParams['LoginScore'] = Utility::isNumeric('LoginScore',$_POST);
		$arrParams['MaxScoreOnline'] = Utility::isNumeric('MaxScoreOnline',$_POST);
		$arrParams['MaxScoreOnlineHour'] = Utility::isNumeric('MaxScoreOnlineHour',$_POST);
		$arrParams['MaxScoreGame'] = Utility::isNumeric('MaxScoreGame',$_POST);
		$arrParams['MaxScoreTask'] = Utility::isNumeric('MaxScoreTask',$_POST);
		if($arrParams['LvlID'])
			$iResult = $this->objMasterBLL->addRoleLevel($arrParams);
		if($iResult==0)
			$msg='角色等级设置成功';
		elseif($iResult==-1)
			$msg='角色等级设置失败';
		else
			$msg='您提交的角色等级数据异常,请重试';		

		echo $msg;
	}	
	/**
	 * 删除角色等级
	 * $iResult: 大于0:成功,0:失败,-2:数据库异常
	 */
	public function delRoleLevel()
	{
		$iResult = -9999;
		$LvlID = Utility::isNumeric('LvlID',$_POST);
		if($LvlID && $LvlID>0)		
		{
			$iResult = $this->objMasterBLL->delRoleLevel($LvlID);
			if($iResult==0)
				$html=$iResult;
			else
				$html=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'SysRoleLevel\')','删除失败,请重试','false','SysRoleLevel',$this->arrConfig);
		}
		else 
			$html=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'SysRoleLevel\')','对不起,您提交的数据异常,请重试','false','SysRoleLevel',$this->arrConfig);
		echo $html;
	}
}
?>