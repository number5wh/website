<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';

class ServerHallAction extends PageBase
{	
	private $objMasterBLL = null;
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);
		$this->objMasterBLL = new MasterBLL();
		$this->strServerType = 7;//服务器类型,0:数据库服务器,1、游戏服务器,2、登录服务器,3、下载服务器,4、版本服务器,5、银行服务器,6、中心服务器, 7.大厅服务器,8.代理服务器
	}
	public function index()
	{
		/*$arrRes = $this->getServerList();
		$arrTags=array('ServerList'=>$arrRes,'ClassName'=>'ServerHall','ServerTypeName'=>'大厅应用');*/
		$arrTags=array('TabTagID'=>'ServerHall','IP'=>'','Port'=>0,'ServID'=>0,'ClassName'=>'ServerHall');
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/ServerList.html');
	}
	/**
	 * 分页
	 */
	public function getPagerServer()
	{
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage<=0 ? 1 : $curPage;
		$arrParam['fields']='ServerID,ServerName,ServerIP,LANServerIP,Intro,Locked,AppName,ServerPort';
		$arrParam['tableName']='T_GameServerInfo';
		$arrParam['where']=' WHERE ServerType='.$this->strServerType;
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
			}
		}
		
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'ServerList'=>$arrServerList,'ServerTypeName'=>'');
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/ServerListPage.html');
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
		{
			$arrRes=$this->objMasterBLL->getServerInfo($ServerID,$this->strServerType);
			/*if(is_array($arrRes) && count($arrRes)>0)
			{
				$LANServerIP='';
				$LANServerPort=0;
				$arrServer = $this->objMasterBLL->getServerList(8,0);//8:大厅服务器,0:锁定状态
				if(is_array($arrServer) && count($arrServer)>0)
				{
					foreach ($arrServer as $val)
					{
						if($arrRes['ServerIP']==$val['ServerIP'] && $arrRes['ServerID']==$val['ServID'])
						{
							$LANServerIP = $val['LANServerIP'];
							$LANServerPort = $val['ServerPort'];
							break;
						}
					}
				}
				$arrRes['ProxyServerIP'] = $LANServerIP;
				$arrRes['ProxyServerPort'] = $LANServerPort;
				$arrRes['LANServerIP'] = $arrRes['LANServerIP'];
				$arrRes['ServerPort'] = $arrRes['ServerPort'];
			}*/
		}
		else 
			$arrRes=array('ServerID'=>0,'ServerName'=>'','ServerIP'=>'','LANServerIP'=>'','ServerPort'=>'','Intro'=>'','Login'=>'','Pass'=>'','AppName'=>'','ServPort'=>0,'ProxyServerIP'=>'','ProxyServerPort'=>0);
		
		$arrTags=array('ServerTypeName1'=>'服务器','ServerTypeName'=>'大厅应用','ClassName'=>'ServerHall','server'=>$arrRes,'MustWriteTags'=>'');
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/ServerEdit.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));

		echo $html;
	}	
	/**
	 * 设置大厅服务器
	 */
	public function addServer()
	{
		$iResult = -9999;
		$iResult1 = -9999;
		$iServerID = 0;
		$arrParams['ServerID'] = Utility::isNumeric('ServerID',$_POST);
		$arrParams['ServerName'] = Utility::isNullOrEmpty('ServerName',$_POST) ? $_POST['ServerName'] : '';
		$arrParams['ServerIP'] = Utility::isNullOrEmpty('ServerIP',$_POST) ? $_POST['ServerIP'] : '';
		$arrParams['LANServerIP'] = Utility::isNullOrEmpty('LANServerIP',$_POST) ? $_POST['LANServerIP'] : '';
		$arrParams['ServerPort'] = Utility::isNullOrEmpty('ServerPort',$_POST) ? $_POST['ServerPort'] : '';
		$arrParams['Intro'] = Utility::isNullOrEmpty('Intro',$_POST) ? $_POST['Intro'] : '';
		$arrParams['LoginName'] = Utility::isNullOrEmpty('LoginName',$_POST) ? $_POST['LoginName'] : '';
		$arrParams['LoginPwd'] = Utility::isNullOrEmpty('LoginPwd',$_POST) ? $_POST['LoginPwd'] : '';
		$arrParams['AppName'] = Utility::isNullOrEmpty('AppName',$_POST) ? $_POST['AppName'] : '';
		$arrParams['ServID'] = Utility::isNumeric('ServID',$_POST);
		//$arrParams['ProxyServerIP'] = Utility::isNullOrEmpty('ProxyServerIP',$_POST) ? $_POST['ProxyServerIP'] : '';
		//$arrParams['ProxyServerPort'] = Utility::isNumeric('ProxyServerPort',$_POST);
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
		//$iResult=0:失败,-2:数据库异常,大于0:成功
		$arrReturns = $this->objMasterBLL->AddServer($arrParams,$this->strServerType);
		if(is_array($arrReturns) && count($arrReturns)>0)
		{
			$iResult = $arrReturns['iResult'];
			$iServerID = $arrReturns['ServerID'];
		}
		/*$LANServerIP = $arrParams['LANServerIP'];
		$ServerPort = $arrParams['ServerPort'];
		if($iResult==0)
		{
			$arrParams['ServID'] = $iServerID;
			$arrParams['LANServerIP'] = $arrParams['ProxyServerIP'];
			$arrParams['ServerPort'] = $arrParams['ProxyServerPort'];
			if($arrParams['ServerID']==0)
			{
				$arrReturns = $this->objMasterBLL->AddServer($arrParams,8);
				if(is_array($arrReturns) && count($arrReturns)>0)
					$iResult1 = $arrReturns['iResult'];
			}
			else			
				$iResult1 = $this->objMasterBLL->UpdateServer($arrParams);
		}
		$arrParams['LANServerIP'] = $LANServerIP;
		$arrParams['ServerPort'] = $ServerPort;
		$arrParams['ServerIP']=str_replace(',','<br />',$arrParams['ServerIP']);
		//返回结果
		if($arrParams['ServerID']==0)
			if($iResult==0 && $iResult1==0)
				$iResult=0;
			else
				$iResult=-1;
		else 
			if($iResult==0 || $iResult1==0) $iResult=0;
		*/		
		if($iResult==0)
			$msg='大厅服务器配置信息发布成功';
		else
			$msg='大厅服务器配置信息发布失败';	
		$arrParams['IP'] = '';	
		echo json_encode(array('Msg'=>$msg,'iResult'=>$iResult,'ServerInfo'=>$arrParams));
	}
	/**
	 * 设置服务器禁用/启用
	 * $iResult=0:失败,-2:数据库异常,大于0:成功
	 */
	public function setServerLocked()
	{
		$iResult = -9999;
		$ServerID = Utility::isNumeric('ServerID',$_POST);
		if($ServerID && $ServerID>0)
		{			
			$iResult = $this->objMasterBLL->setServerLocked($ServerID,$this->strServerType);
		}
		if($iResult==0)
	 		$msg='';
	 	else
	 		$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'ServerHall\')','大厅服务器配置信息发布失败','false','ServerHall',$this->arrConfig);	
		echo json_encode(array('Msg'=>$msg,'iResult'=>$iResult,'ServerID'=>$ServerID));
	}
	/**
	 * 删除服务器配置信息
	 * $iResult: 大于0:成功,0:失败,-2:数据库异常
	 */
	public function delServer()
	{
		$iResult = -9999;
		$ServerID = Utility::isNumeric('ServerID',$_POST);		
		if($ServerID && $ServerID>0)		
		{
			$iResult = $this->objMasterBLL->delServer($ServerID,$this->strServerType);
			if($iResult==0)
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