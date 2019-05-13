<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';

class GameHallVersionAction extends PageBase
{	
	private $objMasterBLL = null;
	private $iVerType = 0;
	private $iKindID = 0;
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);
		$this->objMasterBLL = new MasterBLL();
		$this->iVerType = 2; //版本类型(1:游戏种类版本,2:大厅版本,3:道具版本)
		$this->iKindID = 0;
	}
	/*public function index1()
	{		
		//读取正常状态的下载服务器列表
		$arrServer = $this->objMasterBLL->getServerList(3,0);
		//读取版本
		$arrVersion = $this->objMasterBLL->getGameVersionList($this->iVerType,$this->iKindID);
	
		$arrTags = array('ServerList'=>$arrServer,'KindID'=>$this->iKindID,'GameVersion'=>$arrVersion,'skin'=>$this->arrConfig['skin']);
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/GameHallVerstion.html');
	}	*/
	public function index()
	{		
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/GameHallList.html');
	}	
	/**
	 * 显示添加版本页面
	 */
	public function showAddGameHallVersioniHtml()
	{		
		$VerID = Utility::isNumeric('VerID',$_POST);
		//读取正常状态的下载服务器列表
		$arrServer = $this->objMasterBLL->getServerList(3,0);
		//读取版本
		if($VerID)
			$arrVersion = $this->objMasterBLL->getGameVersion($VerID);
		else
			$arrVersion = array('FileName'=>'','FileURL'=>'','Version'=>'','ServerID'=>'','FileCategory'=>1,'VerID'=>-1);

		$arrTags = array('ServerList'=>$arrServer,'KindID'=>$this->iKindID,'GameVersion'=>$arrVersion,'skin'=>$this->arrConfig['skin']);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/GameHallVerstion.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}	
	/**
	 * 分页
	 */
	public function getPagerGameHall()
	{
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage<=0 ? 1 : $curPage;
		$arrParam['fields']='VerID,FileName,FileURL,FileCategory,Version,LastUpdateTime,ServerIP';
		$arrParam['tableName']='T_GameVersion AS V LEFT JOIN T_GameServerInfo AS S ON V.ServerID=S.ServerID';
		$arrParam['where']=' WHERE VerType=2';
		$arrParam['order']='Version DESC,VerID';
		$arrParam['pagesize']=20;

		$objCommonBLL = new CommonBLL(0);
		$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);		
		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
		$arrVersionList = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);
		if(is_array($arrVersionList) && count($arrVersionList)>0)
		{		
			$iCount = 1;
			foreach($arrVersionList as $key => $val)
			{
				$arrVersionList[$key]['iCount'] = $iCount++;
				$arrVersionList[$key]['FileURL']=str_replace("\\","\\\\",$val['FileURL']);
				$arrVersionList[$key]['FileCategory']= $val['FileCategory'] ? $this->arrConfig['FileCategory'][$val['FileCategory']] : '';
				$arrVersionList[$key]['Version']=Utility::getVersion($val['Version']);
				$arrVersionList[$key]['ServerIP']=str_replace(',','<br>',$val['ServerIP']);
			}
		}
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'VersionList'=>$arrVersionList);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/GameHallListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	/**
	 * 添加大厅版本
	 */
	public function addGameVersion()
	{
		$VerID = Utility::isNumeric('VerID',$_POST);
		$FileName = Utility::isNullOrEmpty('FileName',$_POST);
		$FileURL = Utility::isNullOrEmpty('FileURL',$_POST);
		$FileCategory = Utility::isNullOrEmpty('FileCategory',$_POST);
		$ServerID = Utility::isNullOrEmpty('ServerID',$_POST);
		$Version = Utility::isNullOrEmpty('Version',$_POST);
		$LocalPath = $_POST['LocalPath'];

		if($FileName && $FileURL && $FileCategory && $ServerID && $Version)
		{
			$arrFileName = explode(',', $FileName);
			$arrFileURL = explode(',', $FileURL);
			$arrFileCategory = explode(',', $FileCategory);
			$arrServerID = explode(',', $ServerID);
			$arrVersion = explode(',', $Version);
			$arrLocalPath = explode(',', $LocalPath);
			if(is_array($arrFileName) && is_array($arrFileURL) && is_array($arrFileCategory) && is_array($arrServerID) && is_array($arrVersion) && is_array($arrLocalPath))
			{
				for ($i=0;$i<count($arrFileName);$i++)
				{
					//版本号之间的点号去掉,是否为数字
					$isNumeric = false;
					if(is_numeric(str_replace('.', '', $arrVersion[$i])))
						$isNumeric = true;
					if(!empty($arrFileName[$i]) && $isNumeric)
					{
						$arrVer = explode('.', $arrVersion[$i]);
						$iVersion = 0;
						//计算版本号
						if(is_array($arrVer) && count($arrVer)>0)
						{							
							for ($k=0;$k<count($arrVer);$k++)
								$iVersion += $arrVer[$k]*pow(256,3-$k);
						}
						//$iResult 大于0:成功,0:失败,-2:数据库异常
						$iResult = $this->objMasterBLL->addGameVersion($VerID,$this->iVerType,$this->iKindID,$arrFileName[$i],
																	   $arrFileURL[$i],$arrFileCategory[$i],$arrServerID[$i],
																	   $iVersion,$arrLocalPath[$i]);
					}
				}
			}			
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
	 * 删除大厅版本
	 * 
	 * **/
	public function delGameHallVersion(){
	    $VerID = Utility::isNumeric('VerID',$_POST);
	    if($VerID && $VerID >0){
		  	$iResult = $this->objMasterBLL->delGameVersion($VerID);		
    	    if($iResult==0)
    	        $msg=Utility::echoResultHtml($this->smarty,'确 认','main.CloseMsgBox(true,\'GameHallVersion\')','游戏房间删除成功','true','GameHallVersion',$this->arrConfig);
    	    else
    	       $msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'GameHallVersion\')','游戏房间删除失败','false','GameHallVersion',$this->arrConfig);
	    }else 
		 	$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'GameHallVersion\')','对不起,您提交的数据异常,请重试','false','GameHallVersion',$this->arrConfig);
	 	echo $msg;
	}
}
?>