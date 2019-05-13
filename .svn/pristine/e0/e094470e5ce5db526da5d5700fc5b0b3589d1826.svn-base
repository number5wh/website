<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';

class ServerDataAction extends PageBase
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
		/*$arrRes = $this->getServerList();
		$arrTags=array('ServerList'=>$arrRes,'ClassName'=>'ServerHall','ServerTypeName'=>'大厅应用');*/
		$arrTags=array('TabTagID'=>'ServerData','IP'=>'','Port'=>0,'ServID'=>0,'ClassName'=>'ServerData');
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/ServerList.html');
	}
	/**
	 * 分页
	 * 
	 */
	public function getPagerServer()
	{
		$TypeID = '';
		foreach ($this->arrConfig['ServerTypeData'] as $val)
			$TypeID .= $val['TypeID'] . ',';
		if(!empty($TypeID)) $TypeID = substr($TypeID, 0,strlen($TypeID)-1);
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage<=0 ? 1 : $curPage;
		$arrParam['fields']='ServerID,ServerName,ServerIP,LANServerIP,Intro,Locked,AppName,ServerType';
		$arrParam['tableName']='T_GameServerInfo';
		$arrParam['where']=' WHERE ServerType IN ('.$TypeID.')';
		$arrParam['order']='ServerID';
		$arrParam['pagesize']=20;

		$objCommonBLL = new CommonBLL(0);
		$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);		
		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
		$arrServerList = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);
		
		if(is_array($arrServerList) && count($arrServerList)>0)
		{		
			$iCount = 1;
			foreach($arrServerList as $key => $val)
			{
				$arrServerList[$key]['RoomCount'] = $this->objMasterBLL->getGameRoomCount($val['ServerID']);
				$arrServerList[$key]['iCount'] = $iCount++;
				$arrServerList[$key]['ServerName']=Utility::gb2312ToUtf8($val['ServerName']);
				$arrServerList[$key]['ServerIP']=str_replace(',','<br />',$val['ServerIP']);
				$arrServerList[$key]['Intro']=Utility::gb2312ToUtf8($val['Intro']);
				$arrServerList[$key]['AppName']=Utility::gb2312ToUtf8($val['AppName']);
				$arrServerList[$key]['ServerTypeID']=$val['ServerType'];
				foreach ($this->arrConfig['ServerTypeWeb'] as $v)
				{
					if($v['TypeID']==$val['ServerType'])
					{
						$arrServerList[$key]['ServerType']=$v['TypeName'];
						break;
					}
				}
			}
		}		
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'ServerList'=>$arrServerList,'ServerTypeName'=>'');
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/ServerWebListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	/**
	 * 显示设置大厅服务器弹出层(添加)
	 */
	public function showAddServerHtml()
	{
		$ServerID = Utility::isNumeric('ServerID',$_POST);
		if($ServerID && $ServerID>0)
			$arrRes=$this->objMasterBLL->getServerInfo($ServerID,$this->strServerType);
		else 
			$arrRes=array('ServerID'=>0,'ServerName'=>'','ServerIP'=>'','LANServerIP'=>'','ServerPort'=>'','Intro'=>'','Login'=>'','Pass'=>'','AppName'=>'','ServPort'=>0,'ServerType'=>100);

		$arrTags=array('server'=>$arrRes,'WebList'=>$this->arrConfig['ServerTypeData']);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/ServerDataEdit.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));

		echo $html;
	}	
	/**
	 * 设置数据服务器
	 */
	public function addServer()
	{
		$iResult = -9999;
		$arrParams['ServerType']='';
		$arrParams['ServerID'] = Utility::isNumeric('ServerID',$_POST);
		$arrParams['ServerName'] = Utility::isNullOrEmpty('ServerName',$_POST);
		$arrParams['ServerIP'] = Utility::isNullOrEmpty('ServerIP',$_POST);		
		$arrParams['Intro'] = Utility::isNullOrEmpty('Intro',$_POST) ? $_POST['Intro'] : '';
		$ServerType = Utility::isNumeric('ServerType',$_POST);
		$arrParams['ServerTypeID']=$ServerType;
		foreach ($this->arrConfig['ServerTypeData'] as $v)
		{
			if($v['TypeID']==$ServerType)
			{
				$arrParams['ServerType']=$v['TypeName'];
				break;
			}
		}				
		$arrParams['LANServerIP'] = '';
		$arrParams['ServerPort'] = 0;
		$arrParams['LoginName'] = '';
		$arrParams['LoginPwd'] = '';
		$arrParams['AppName'] = '';
		$arrParams['ServID'] = 0;
		//ServerIP转二进制格式保存
		$arrServerIP = explode(',',$arrParams['ServerIP']);
		if(is_array($arrServerIP) && count($arrServerIP)>0)
		{
			$strIP = null;			
			for($i=0;$i<count($arrServerIP);$i++)
			{
				$arrServIP = explode(':',$arrServerIP[$i]);
				if(is_array($arrServIP) && count($arrServIP)==2)
				{
					$IP = Utility::ip2int($arrServIP[0]);
					$strIP .= pack('NS',$IP,$arrServIP[1]);
				}
			}
			if(!empty($strIP))
				$arrParams['IP'] = $strIP;
			else 
				$arrParams['IP'] = null;			
		}
		//$iResult=0:成功,-1:失败
		$arrReturns = $this->objMasterBLL->AddServer($arrParams,$ServerType);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		if($iResult==0)
			$msg='网站服务器配置信息发布成功';
		else
			$msg='网站服务器配置信息发布失败';
		$arrParams['IP'] = '';
		echo json_encode(array('Msg'=>$msg,'iResult'=>$iResult,'ServerInfo'=>$arrParams));
	}
	
	/**
	 * 删除服务器配置信息
	 * $iResult: 大于0:成功,0:失败,-2:数据库异常
	 */
	public function delServer()
	{
		$iResult = -9999;
		$ServerID = Utility::isNumeric('ServerID',$_POST);	
		$ServerType = Utility::isNumeric('ServerType',$_POST);
		if($ServerID && $ServerID>0 && $ServerType)		
		{
			$iResult = $this->objMasterBLL->delServer($ServerID,$ServerType);
			if($iResult>0)
				$html='';
			else
				$html=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'ServerHall\')','删除失败,请重试','false','ServerHall',$this->arrConfig);
		}
		else 
			$html=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'ServerHall\')','对不起,您提交的数据异常,请重试','false','ServerHall',$this->arrConfig);
		echo json_encode(array('Msg'=>$html,'iResult'=>$iResult));
	}
}
?>