<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';

class ServerProcessAction extends PageBase
{	
	private $objMasterBLL = null;
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);
		$this->objMasterBLL = new MasterBLL();
		$this->strServerType = 2;//服务器类型,0:数据库服务器,1、游戏服务器,2、登录服务器,3、下载服务器,4、版本服务器,5、银行服务器,6、中心服务器, 7.大厅服务器
		
	}
	public function index()
	{
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/ServerProcessEdit.html');
	}
	
	/**
	 * 设置登陆服务器
	 */
	public function updateServer()
	{
		$iResult = -1;
		$arrParams['OldServerIP'] = Utility::isNullOrEmpty('OldServerIP',$_POST);
		$arrParams['NewServerIP'] = Utility::isNullOrEmpty('ServerIP',$_POST);
		/*if($arrParams['Port'] && $arrParams['ServerIP'])
		{
			$arrParams['OldServerIP'] = str_replace(',', ':'.$arrParams['Port'].',', $arrParams['OldServerIP']).':'.$arrParams['Port'];
			$arrParams['ServerIP'] = str_replace(',', ':'.$arrParams['Port'].',', $arrParams['ServerIP']).':'.$arrParams['Port'];
		}*/
		$arrServerList = $this->objMasterBLL->getServerIP($arrParams['OldServerIP']);
		if(!empty($arrServerList) && count($arrServerList)>0)
		{
			foreach ($arrServerList as $val)
			{
				$arrParams['ServerID'] = $val['ServerID'];
				$arrServerInfo = explode(':',$val['ServerIP']);
				
				if(is_array($arrServerInfo) && count($arrServerInfo)>=2)
				{					
					$Port = strpos($arrServerInfo[1],',')>0 ? substr($arrServerInfo[1],0,strpos($arrServerInfo[1],',')) : $arrServerInfo[1];					
					$arrParams['ServerIP'] = !empty($Port) ? str_replace(',', ':'.$Port.',', $arrParams['NewServerIP']).':'.$Port : $arrParams['NewServerIP'];
				}
				else 
					$arrParams['ServerIP'] = $arrParams['NewServerIP'];
			
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
				$iResult = $this->objMasterBLL->updateServerIP($arrParams);
			}
		}
		

		if($iResult==0)
			$msg='服务器IP批量处理成功';
		else
			$msg='服务器IP批量处理失败';	
		echo json_encode(array('Msg'=>$msg,'iResult'=>$iResult));
	}
	
}
?>