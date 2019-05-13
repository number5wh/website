<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/StagePropertyBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';

class SpClassAction extends PageBase
{	
	private $objStagePropertyBLL = null;
	private $SearchType = 0;//返回所有
	private $SpClassLocked = -1;//道具分类锁定状态
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);
		$this->objStagePropertyBLL = new StagePropertyBLL();
	}
	public function index()
	{
		//$arrSpClassList = $this->objStagePropertyBLL->getSpClass(0,$this->SearchType,$this->SpClassLocked);
		//$arrTags=array('SpClassList'=>$arrSpClassList);
		//Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/SpClassList.html');
	}	 
	/**
	 * 分页
	 */
	public function getPagerSpClass()
	{
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage<=0 ? 1 : $curPage;
		$arrParam['fields']='CateName,TypeID,Target,KeyID,ClassID,Locked,AllSubID,ParentID';
		$arrParam['tableName']='T_Class';
		$arrParam['where']=' WHERE ParentID=0';
		$arrParam['order']='TypeID,KeyID,ClassID';
		$arrParam['pagesize']=20;

		$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['StageProperty']);
		$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);		
		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
		$arrSpClassList = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);
		if(is_array($arrSpClassList) && count($arrSpClassList)>0)
		{
			$iCount = 0;
			foreach ($arrSpClassList as $val)
			{
				$arrSpClassList[$iCount]['CateName'] = Utility::gb2312ToUtf8($val['CateName']);
				$iCount++;
			}
		}
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'SpClassList'=>$arrSpClassList);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/SpClassListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	/**
	 * 添加道具分类
	 */
	public function addSpClass()
	{
		$arrParams['CateName'] = Utility::isNullOrEmpty('CateName',$_POST);
		$arrParams['TypeID'] = Utility::isNumeric('TypeID',$_POST);	
		$arrParams['KeyID'] = Utility::isNumeric('KeyID',$_POST);
		$arrParams['Target'] = Utility::isNumeric('Target',$_POST);
		$arrParams['ClassID'] = Utility::isNumeric('ClassID',$_POST);
		$arrParams['ParentClassID'] = Utility::isNumeric('ParentClassID',$_POST);
		$iResult = -1;
		if($arrParams['CateName'])
		{
			$iResult = $this->objStagePropertyBLL->addSpClass($arrParams);
			if($iResult==0)
				$msg='游戏种类设置成功';
			elseif($iResult==-1)
				$msg='游戏种类设置失败';
		}
		else
		{
			if(!$arrParams['CateName'])
				$msg='请输入道具分类名称';
			elseif(!$arrParams['TypeID'] || $arrParams['TypeID']<=0)
				$msg='请选择正确的道具大类';	
		}
		echo json_encode(array('Msg'=>$msg,'iResult'=>$iResult,'ClassInfo'=>$arrParams));
	}
	/**
	 * 删除道具分类
	 * $iResult=0:删除失败,请重试;-99:您提交的数据异常,请重试;-2:数据库异常,请稍后再试或联系技术人员;大于0:删除成功,-1:该类别下有数据,无法删除
	 */
	public function delSpClass()
	{
		$iResult = 0;
		$ClassID = Utility::isNumeric('ClassID',$_POST);	
		$ParentID = Utility::isNumeric('ParentID',$_POST);	
		if($ClassID && $ClassID>0)		
			$iResult = $this->objStagePropertyBLL->delSpClass($ClassID);			
		else 
			$iResult = -9999;
		if($iResult==0)
	 		$msg='';
	 	elseif($iResult==-1)	
	 		$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'SpClass\')','道具类别删除失败','false','SpClass',$this->arrConfig);	
		elseif($iResult==-3)	 	
		 	$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'SpClass\')','该类别下含有子类,请先删除子类后再删除该类别','false','SpClass',$this->arrConfig);
	 	elseif($iResult==-4)	 	
		 	$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'SpClass\')','该类别下含有道具信息,请先删除道具后再删除该类别','false','SpClass',$this->arrConfig);
	 	elseif($iResult==-5)	 	
		 	$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'SpClass\')','该类别下含有事件信息,请先删除事件信息后再删除该类别','false','SpClass',$this->arrConfig);
	 	else 	
		 	$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'SpClass\')','对不起,您提交的数据异常,请重试','false','SpClass',$this->arrConfig);
	 	echo json_encode(array('Msg'=>$msg,'iResult'=>$iResult,'ParentID'=>$ParentID,'ClassID'=>$ClassID));
	}
	/**
	 * 设置道具分类禁用/启用
	 * $iResult=0:失败,-2:数据库异常,大于0:成功
	 */
	public function setSpClassLocked()
	{
		$iResult = 0;
		$ClassID = Utility::isNumeric('ClassID',$_POST);
		if($ClassID && $ClassID>0)
		{			
			$iResult = $this->objStagePropertyBLL->setSpClassLocked($ClassID);
		}
		if($iResult>0)
	 		$msg='';
	 	elseif($iResult==-2)
		 	$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'SpClass\')','数据库异常,请稍后再试或联系技术人员','false','SpClass',$this->arrConfig);
	 	else
	 		$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'SpClass\')','道具类别状态设置失败','false','SpClass',$this->arrConfig);	
		echo json_encode(array('Msg'=>$msg,'ClassID'=>$ClassID,'iResult'=>$iResult));
	}	
	/**
	 * 显示设置道具分类界面
	 */
	public function showAddSpClassHtml()
	{
		$KeyID = $this->arrConfig['SpBigClass'][0]['TypeID'];
		$ClassID = Utility::isNumeric('ClassID',$_POST);
		if($ClassID && $ClassID>0)
		{
			$arrClass = $this->objStagePropertyBLL->getSpClass($ClassID,2,$this->SpClassLocked);
			if(is_array($arrClass) && count($arrClass)>0)
				$KeyID = $arrClass[0]['TypeID'];
		}
		if(!isset($arrClass)) 
			$arrClass=array(array('TypeID'=>0,'ParentID'=>0,'CateName'=>'','KeyID'=>0,'ClassID'=>$ClassID,'Target'=>0));
		//一级分类
		$arrBigClassList = $this->objStagePropertyBLL->getSpClass($KeyID,4,$this->SpClassLocked);
		
		$arrTags = array('SpClass'=>$arrClass,'SpClassList'=>$this->arrConfig['SpBigClass'],'ClassList'=>$arrBigClassList);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/SpClassEdit.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));

		echo $html;
	}
	/**
	 * 读取子类(SpClassEdit.html调用)
	 */
	public function getClassList()
	{
		$strOption = '<option value="0">根目录</option>';
		$KeyID = Utility::isNumeric('KeyID',$_POST);
		$TypeID = Utility::isNumeric('TypeID',$_POST);
		$arrBigClassList = $this->objStagePropertyBLL->getSpClass($KeyID,$TypeID,$this->SpClassLocked);
		if(is_array($arrBigClassList) && count($arrBigClassList)>0)
		{
			foreach ($arrBigClassList as $val)
			{
				$strOption .= '<option value="'.$val['ClassID'].'">'.$val['CateName'].'</option>';
			}
		}
		echo $strOption;
	}
	/**
	 * 读取子类(SpClassListPage.html调用)
	 */
	public function getSubClassList()
	{
		$ClassID = Utility::isNumeric('ClassID',$_POST);
		$arrBigClassList = $this->objStagePropertyBLL->getSpClass($ClassID,3,$this->SpClassLocked);
		$arrTags = array('SpClassList'=>$arrBigClassList);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/SpClassListPage.SubClassList.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo json_encode(array('RowHtml'=>$html,'ClassID'=>$ClassID));
	}
}
?>