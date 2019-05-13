<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';

class GameTableAction extends PageBase
{	
	private $objMasterBLL = null;
	private $iVerType = 0;
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);
		$this->objMasterBLL = new MasterBLL();
	}
	
	function index()
	{
		$arrList = $this->objMasterBLL->getGameTableList('');
		$i=0;
		foreach($arrList as $v){
			$arrList[$i]['id'] = $i+1;
			$arrList[$i]['SchemeName'] = Utility::gb2312ToUtf8($v['SchemeName']);
			$i++;
		}
		$arrTags=array('GameTableList'=>$arrList);
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/GameTableList.html');
	}
	
	/**
	 * 显示添加桌子类型表单
	 */
	public function showAddGameTableHtml()
	{
		$TableSchemeID = Utility::isNumeric('TableSchemeID',$_POST);
		$arrList = array();
		if($TableSchemeID){
			//显示选定桌子的详细信息
			$arrResult = $this->objMasterBLL->getGameTableList($TableSchemeID);
			$arrResult[0]['SchemeName'] = Utility::gb2312ToUtf8($arrResult[0]['SchemeName']);
			$arrList = $arrResult[0];
		}			
		
		$arrTags = array('GameTable'=>$arrList, 'TableSchemeID'=>$TableSchemeID);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/AddGameTable.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));

		echo $html;
	}
	
	/**
	 * 增加或更新桌子类型信息
	 */
	function updateGameTableInfo()
	{
		$TableSchemeID = Utility::isNumeric('TableSchemeID',$_POST);
		$SchemeName = Utility::isNullOrEmpty('SchemeName',$_POST);
		$TableID = Utility::isNumeric('TableID',$_POST);
		$LockBkID = Utility::isNumeric('LockBkID',$_POST);
		$GestureID = Utility::isNumeric('GestureID',$_POST);
		$RunButtonID = Utility::isNumeric('RunButtonID',$_POST);
		$TableDataID = Utility::isNumeric('TableDataID',$_POST);
		$ChairID = Utility::isNumeric('ChairID',$_POST);
		$iResult = 0;
		if($SchemeName && $TableID && $LockBkID && $GestureID && $RunButtonID && $TableDataID && $ChairID){
			$iResult = $this->objMasterBLL->addGameTable($TableSchemeID, Utility::utf8ToGb2312($SchemeName), $TableID, $LockBkID, $GestureID, $RunButtonID, $TableDataID, $ChairID);
			if($iResult==0)
				$msg='桌子类型设置成功';
			else
				$msg='桌子类型设置失败';
		}else{
			if(!$SchemeName)
				$msg='请输入桌子名称';
			if(!$TableID)
				$msg='请输入桌子ID';
			if(!$LockBkID)
				$msg='请输入桌子锁图片的ID';
			if(!$GestureID)
				$msg='请输入准备好后玩家手势ID';
			if(!$RunButtonID)
				$msg='请输入启动按纽ID';
			if(!$TableDataID)
				$msg='请输入桌子数据文件ID';
			if(!$ChairID)
				$msg='请输入椅子ID';
		}
		echo json_encode(array('msg'=>$msg,'result'=>$iResult));
	}
	
	/**
	 * 删除桌子类型信息
	 */
	function delGameTableInfo()
	{
		$TableSchemeID = Utility::isNumeric('TableSchemeID',$_POST);
		$iResult = 0;
		if($TableSchemeID && $TableSchemeID>0)		
			$iResult = $this->objMasterBLL->delGameTable($TableSchemeID);			
		else 
			$iResult = -9999;
		if($iResult==0)
	 		$msg=$iResult;
	 	elseif($iResult==-1)
	 		$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'GameTable\')','桌子类型删除失败','false','GameTable',$this->arrConfig);	
		else	 	
		 	$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'GameTable\')','对不起,您提交的数据异常,请重试','false','GameTable',$this->arrConfig);
	 	echo $msg;
	}
}