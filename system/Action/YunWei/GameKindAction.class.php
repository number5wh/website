<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';

class GameKindAction extends PageBase
{	
	private $objMasterBLL = null;
	private $iVerType = 0;
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);
		$this->objMasterBLL = new MasterBLL();
		$this->iVerType = 1; //版本类型(1:游戏种类版本,2:大厅版本,3:道具版本)
	}
	public function index()
	{
		$Locked = -1;
		$ClassID = -1;//种类标识,0未分类类型,1牌类游戏,2骨牌游戏,3棋牌游戏,4休闲游戏
		$arrList = $this->objMasterBLL->getGameKindList($ClassID,$Locked);
		$arrTags=array('GameKindList'=>$arrList);
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/GameKindList.html');
	}	 
	/**
	 * 显示修改游戏种类弹出层
	 */
	public function showAddGameKindHtml()
	{
		$KindID = Utility::isNumeric('KindID',$_POST);
		if($KindID && $KindID>0)
			$arrRes=$this->objMasterBLL->getGameKindInfo($KindID);
		else 
			$arrRes=array('KindID'=>'','ClassID'=>'','ServerDLL'=>'','KindName'=>'','ProcessName'=>'','CustomField'=>'');
		$arrTags=array('GameKind'=>$arrRes,'GameKindClass'=>$this->arrConfig['GameKindClass'],'PayTypeList'=>$this->arrConfig['PayType'],'BankAccType'=>$this->arrConfig['BankAccType']);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/GameKindEdit.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));

		echo $html;
	}	
	/**
	 * 添加游戏种类
	 */
	public function addGameKind()
	{
		$iResult = -9999;
		$KindName = Utility::isNullOrEmpty('KindName',$_POST);
		$KindID = Utility::isNumeric('KindID',$_POST);	
		$ProcessName = Utility::isNullOrEmpty('ProcessName',$_POST);
		$ServerDLL = Utility::isNullOrEmpty('ServerDLL',$_POST);
		$ClassID = Utility::isNumeric('ClassID',$_POST);	
		$CustomField = Utility::isNullOrEmpty('CustomField',$_POST) ? $_POST['CustomField'] : '';
		$PayTypeID = Utility::isNumeric('PayTypeID',$_POST);		
		$SysBank = Utility::isNumeric('SysBank',$_POST);		
		$RobotBank = Utility::isNumeric('RobotBank',$_POST);	
		if($KindName && $KindID && $KindID>0 && $ProcessName && $ServerDLL && $PayTypeID)
		{
			$iResult = $this->objMasterBLL->addGameKind($KindName,$KindID,$ProcessName,$ServerDLL,$ClassID,$CustomField,$PayTypeID,$SysBank,$RobotBank);
			if($iResult==0)
				$msg='游戏种类设置成功';
			else
				$msg='游戏种类设置失败';
		}
		else
		{
			if(!$KindName)
				$msg='请输入游戏名称';
			elseif(!$KindID || $KindID<=0)
				$msg='请输入游戏标识';
			elseif(!$ProcessName)
				$msg='请输入进程名称';
			elseif(!$ServerDLL)
				$msg='请输入服务端动态库名称';
			elseif(!$PayTypeID)
				$msg='请选择结算类型';
		}
		echo json_encode(array('msg'=>$msg,'result'=>$iResult));
	}
	/**
	 * 删除游戏种类
	 * $iResult=0:删除失败,请重试;-1:您提交的数据异常,请重试;-2:数据库异常,请稍后再试或联系技术人员;大于0:删除成功
	 */
	public function delGameKind()
	{
		$iResult = -9999;		
		$KindID = Utility::isNumeric('KindID',$_POST);
		if($KindID && $KindID>0)		
			$iResult = $this->objMasterBLL->delGameKind($KindID);			
		else 
			$iResult = -1;
		if($iResult==0)
	 		$msg=$iResult;
	 	elseif($iResult==-1)
	 		$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'GameKind\')','游戏种类删除失败','false','GameKind',$this->arrConfig);	
		else	 	
		 	$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'GameKind\')','对不起,您提交的数据异常,请重试','false','GameKind',$this->arrConfig);
	 	echo $msg;
	}
	/**
	 * 设置游戏种类禁用/启用
	 * $iResult=0:失败,-2:数据库异常,大于0:成功
	 */
	public function setGameKindLocked()
	{
		$iResult = -9999;
		$KindID = Utility::isNumeric('KindID',$_POST);
		if($KindID && $KindID>0)
			$iResult = $this->objMasterBLL->setGameKindLocked($KindID);

		if($iResult==0)
	 		$msg=$iResult;
	 	else
	 		$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'GameKind\')','游戏种类状态设置失败','false','GameKind',$this->arrConfig);	
		echo $msg;
	}
	/**
	 * 点击显示设置游戏级别弹出层
	 */
	public function showSetGameKindLevelHtml()
	{
		$arrLevelScore = null;
		$arrLevelHappyBean = null;
		$KindID = Utility::isNumeric('KindID',$_POST);
		if($KindID && $KindID>0)
		{
			$arrLevel = array('ID'=>0);
			$arrGameKind=$this->objMasterBLL->getGameKindInfo($KindID);		
			$arrTags=array('GameKind'=>$arrGameKind,'Level'=>$arrLevel);
			Utility::assign($this->smarty,$arrTags);
			$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/GameKindLevel.html');
			$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));			
		}
		else 
			$html=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'GameKind\')','对不起,参数异常,请重试','false','GameKind',$this->arrConfig);

		echo $html;
	}
	/**
	 * 添加游戏级别
	 */
	public function addGameLevel()
	{
		$ID = Utility::isNumeric('ID',$_POST);	
		$KindID = Utility::isNumeric('KindID',$_POST);	
		$LevelType = Utility::isNumeric('LevelType',$_POST);		
		$LevelID = Utility::isNumeric('LevelID',$_POST);
		$LevelName = Utility::isNullOrEmpty('LevelName',$_POST);
		$LBound = Utility::isNumeric('LBound',$_POST);	
		$CellAmount = Utility::isNumeric('CellAmount',$_POST);
		$ClothesImage = Utility::isNumeric('ClothesImage', $_POST);
		if($LevelName && $KindID>0 && $LevelType>0 && $LevelID>0)
		{
			//0:成功,-1:失败
			$iResult = $this->objMasterBLL->addGameLevel($ID,$KindID,$LevelType,$LevelID,$LevelName,$LBound,$CellAmount,$ClothesImage);
			if($iResult==0)
				$msg='游戏级别设置成功';
			else
				$msg='游戏级别设置失败';
		}
		else
			$msg='参数异常,请重试';
		echo $msg;
	}
	/**
	 * 删除游戏级别
	 * $iResult=0:成功,-1:失败;-9999:您提交的数据异常,请重试;
	 */
	public function delGameLevel()
	{
		$iResult = -9999;
		$ID = Utility::isNumeric('ID',$_POST);	
		$curPage = Utility::isNumeric('curPage',$_POST);	
		$LevelType = Utility::isNumeric('LevelType',$_POST);
		if($ID && $ID>0)		
			$iResult = $this->objMasterBLL->delGameLevel($ID);			

		$arrRes = array('iResult'=>$iResult,'curPage'=>$curPage,'LevelType'=>$LevelType);
		echo json_encode($arrRes);
	}
	/**
	 * 分页
	 */
	public function getPagerLevel()
	{
		$LevelType = Utility::isNumeric('LevelType',$_POST);
		$curPage = Utility::isNumeric('curPage',$_POST);
		$KindID = Utility::isNumeric('KindID',$_POST);
		$curPage = $curPage==0 ? 1 : $curPage;
		$arrParam['fields']='ID,LevelID,LevelName,LBound,CellAmount,ClothesImage';
		$arrParam['tableName']='T_GameLevel';
		$arrParam['where']=' WHERE KindID='.$KindID.' AND LevelType='.$LevelType;
		$arrParam['order']='LevelID';
		$arrParam['pagesize']=8;
		
		$objCommonBLL = new CommonBLL(0);		
		$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);			
		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
		$Page['LevelType'] = $LevelType;
		$arrResult = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);
		if(is_array($arrResult) && count($arrResult)>0)
		{
			$iCount = 0;
			foreach ($arrResult as $val)
			{
				$arrResult[$iCount]['LevelName']=Utility::gb2312ToUtf8($val['LevelName']);
				$iCount++;
			}
			if($LevelType==1)
				$arrTags=array('skin'=>$this->arrConfig['skin'],'GameLevelList'=>$arrResult,'Page'=>$Page);
			else 
				$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'GameLevelList'=>$arrResult);
			Utility::assign($this->smarty,$arrTags);
			$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/GameKindLevelPage.html');
			$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));	
			echo $html;	
		}			
	}
	/**
	 * 显示设置游戏版本界面
	 */
	public function showAddGameVersionHtml()
	{
		$KindID = Utility::isNumeric('KindID',$_POST);
		$KindName = Utility::isNullOrEmpty('KindName',$_POST);
		//读取正常状态的下载服务器列表
		$arrServer = $this->objMasterBLL->getServerList(3,0);
		//读取版本
		$arrVersion = $this->objMasterBLL->getGameVersionList($this->iVerType,$KindID);

		$arrTags = array('ServerList'=>$arrServer,'KindID'=>$KindID,'KindName'=>$KindName,'VersionList'=>$arrVersion);//,'GameVersion'=>$arrVersion
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Common/GameVersion.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));

		echo $html;
	}
	/**
	 * 添加游戏版本
	 */
	public function addGameVersion()
	{
		$KindID = Utility::isNumeric('KindID',$_POST);
		$FileName = Utility::isNullOrEmpty('FileName',$_POST);
		$FileURL = Utility::isNullOrEmpty('FileURL',$_POST);
		$FileCategory = Utility::isNumeric('FileCategory',$_POST);
		$ServerID = Utility::isNumeric('ServerID',$_POST);
		$Version = Utility::isNullOrEmpty('Version',$_POST);
		$LocalPath = '';
		$isNumeric = false;
		//版本号之间的点号去掉,是否为数字
		if($Version && is_numeric(str_replace('.', '', $Version)))
			$isNumeric = true;

		if($KindID && $FileName && $FileURL && $FileCategory && $ServerID && $Version && $isNumeric)
		{
			$arrVer = explode('.', $Version);
			$iVersion = 0;
			//计算版本号
			if(is_array($arrVer) && count($arrVer)>0)
			{
				for ($i=0;$i<count($arrVer);$i++)
					$iVersion += $arrVer[$i]*pow(256,3-$i);
			}
			//$iResult 大于0:成功,0:失败,-2:数据库异常
			$VerID = 0;
			$iResult = $this->objMasterBLL->addGameVersion($VerID,$this->iVerType,$KindID,$FileName,$FileURL,$FileCategory,$ServerID,$iVersion,$LocalPath);
			if($iResult==0)
				$msg='版本发布成功';
			else
				$msg='版本发布失败';
		}
		else
			$msg='对不起,您提交的数据异常,请重试';
		echo $msg;
	}	
	/**
	 * 删除游戏版本
	 */
	public function delGameVersion()
	{
		$iResult = -9999;
		$VerID = Utility::isNumeric('VerID',$_POST);
		if($VerID)
			$iResult = $this->objMasterBLL->delGameVersion($VerID);
		echo json_encode(array('iResult'=>$iResult,'VerID'=>$VerID));
	}
}
?>