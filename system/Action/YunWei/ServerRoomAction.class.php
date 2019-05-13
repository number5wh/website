<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Common/Zip.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';

class ServerRoomAction extends PageBase
{	
	private $objMasterBLL = null;
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);
		$this->objMasterBLL = new MasterBLL();
		$this->strServerType = 1;//服务器类型,0:数据库服务器,1、游戏服务器,2、登录服务器,3、下载服务器,4、版本服务器,5、银行服务器,6、中心服务器, 7.大厅服务器
	}
	public function index()
	{
		$arrTags=array('ClassName'=>'ServerRoom');
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/ServerList.html');
		/*
		$arrRes = $this->getServerList();
		$arrTags=array('ServerList'=>$arrRes,'ClassName'=>'ServerGame','ServerTypeName'=>'房间数量');
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/ServerList.html');
		*/
	}
	/**
	 * 显示设置游戏服务器弹出层(添加)
	 */
	public function showAddGameServerHtml()
	{
		$ServerID = Utility::isNumeric('ServID',$_POST);
		if($ServerID && $ServerID>0)
			$arrRes=$this->objMasterBLL->getGameServer($ServerID);
		else 
			$arrRes=array('ServID'=>0,'ServerName'=>'','ServerIP'=>'','ServerPort'=>'');
			
		$arrTags=array('server'=>$arrRes);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/ServerEditGame.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));

		echo $html;
	}
	
	public function showServerList()
	{
		$ServID = Utility::isNumeric('ServID',$_GET);
		if($ServID)
		{
			$strServerIP = '';
			$strServerPort = 0;
			$arrLanServer = $this->objMasterBLL->getGameServer($ServID);
			if(is_array($arrLanServer) && count($arrLanServer)>0)
			{
				$strServerIP = $arrLanServer['ServerIP'];
				$strServerPort = $arrLanServer['ServerPort'];
			}	
			$arrTags=array('TabTagID'=>$ServID,'IP'=>$strServerIP,'Port'=>$strServerPort,'ServID'=>$ServID,'ClassName'=>'ServerRoom');
			Utility::assign($this->smarty,$arrTags);
			$this->smarty->display($this->arrConfig['skin'].'/YunWei/ServerList.html');
		}
		//$arrRes = $this->getGameServerList();
		//$arrTags=array('ClassName'=>'ServerGame','ServerTypeName'=>'房间数量');
		//Utility::assign($this->smarty,$arrTags);
		//$this->smarty->display($this->arrConfig['skin'].'/YunWei/ServerListGameRoom.html');
	}
	
	
	/**
	 * 分页
	 */
	public function getPagerServer()
	{
		$ServID = Utility::isNumeric('ServID',$_POST);
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage<=0 ? 1 : $curPage;
		$arrParam['fields']='ServerID,ServerName,ServerIP,LANServerIP,Intro,Locked,AppName,ServID';
		$arrParam['tableName']='T_GameServerInfo';
		$arrParam['where']=' WHERE ServerType=1 ';
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
				if($arrServerList[$key]['ServID']!=0){
				    $Result = $this->objMasterBLL->getGameDunInfo($arrServerList[$key]['ServID']);
				    $arrServerList[$key]['GameDunName']=Utility::gb2312ToUtf8($Result['GroupName']);
				}
				else 
				    $arrServerList[$key]['GameDunName']='默认组';
			}
		}
		
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'ServerList'=>$arrServerList,'ServerTypeName'=>'房间数量','ClassName'=>'ServerRoom');
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/ServerListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	
	
	
	
	
	
	/**
	 * 返回所有游戏服务器列表
	 
	private function getServerList()
	{
		$Locked = -1;	//锁定状态,-1:返回所有游戏服务器列表
		$arrRes = $this->objMasterBLL->getServerList($this->strServerType,$Locked);	
		if(is_array($arrRes) && count($arrRes)>0)
		{
			$iCount = 1;
			foreach($arrRes as $key => $val)
			{
				$arrList = $this->objMasterBLL->getRoomAndKindList($val['ServerID']);
				$arrRes[$key]['RoomCount'] = count($arrList);
				$arrRes[$key]['iCount'] = $iCount++;
				$arrRes[$key]['ServerName']=Utility::gb2312ToUtf8($val['ServerName']);
				$arrRes[$key]['ServerIP']=str_replace(',','<br />',$val['ServerIP']);
				$arrRes[$key]['Intro']=Utility::gb2312ToUtf8($val['Intro']);
				$arrRes[$key]['AppName']=Utility::gb2312ToUtf8($val['AppName']);
			}
		}		
		return $arrRes;
	}*/
	/**
	 * 显示设置游戏服务器弹出层(添加)
	 */
	public function showAddServerHtml()
	{
		$ServerID = Utility::isNumeric('ServerID',$_POST);
		$IP = Utility::isNullOrEmpty('IP',$_POST);
		$Port = Utility::isNumeric('Port',$_POST);
		$ServID = Utility::isNumeric('ServID',$_POST);
		if($ServerID && $ServerID>0)
			$arrRes=$this->objMasterBLL->getServerInfo($ServerID,$this->strServerType);
		else 
			$arrRes=array('ServerID'=>0,'ServerName'=>'','ServerIP'=>'','LANServerIP'=>$IP,'ServerPort'=>$Port,'ServID'=>0,'Intro'=>'','Login'=>'','Pass'=>'','AppName'=>'');
		//取指定内网IP的端口列表
		$arrPortList = null;
		$GameDunList = $this->objMasterBLL->selectGameDunList();
		foreach($GameDunList as $k=>$val)
		      $GameDunList[$k]['GroupName']=Utility::gb2312ToUtf8($val['GroupName']);
		$temp=count($GameDunList);
		$GameDunList[$temp]['GroupName']='默认组';
		$GameDunList[$temp]['ID']=0;//游戏盾列表添加默认组
		if($ServID)
		{
			$arrServer = $this->objMasterBLL->getGameServer($ServID);
			if(is_array($arrServer) && count($arrServer)>0)
			{
				$arrPort = explode(',', $arrServer['ServerPort']);
				if(is_array($arrPort) && count($arrPort)>0)
				{
					for($i=0;$i<count($arrPort);$i++)
						$arrPortList[$i]['Port'] = $arrPort[$i];
				}
			}
		}
		//var_dump($GameDunList);
		$arrTags=array('ServerTypeName1'=>'服务器','ServerTypeName'=>'游戏应用','ClassName'=>'ServerRoom','server'=>$arrRes,'portList'=>$arrPortList,'MustWriteTags'=>'','GameDunList'=>$GameDunList,'ServID'=>$ServID);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/ServerEdit.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));

		echo $html;
	}
	/**
	 * 设置游戏服务器
	 */
	public function addServer()
	{
		$arrParams['ServerID'] = Utility::isNumeric('ServerID',$_POST);
		$arrParams['ServerName'] = Utility::isNullOrEmpty('ServerName',$_POST);
		$arrParams['ServerIP'] = Utility::isNullOrEmpty('ServerIP',$_POST);
		$arrParams['LANServerIP'] = Utility::isNullOrEmpty('LANServerIP',$_POST);
		$arrParams['ServerPort'] = Utility::isNullOrEmpty('ServerPort',$_POST);
		$arrParams['Intro'] = Utility::isNullOrEmpty('Intro',$_POST);
		$arrParams['LoginName'] = Utility::isNullOrEmpty('LoginName',$_POST);
		$arrParams['LoginPwd'] = Utility::isNullOrEmpty('LoginPwd',$_POST);
		$arrParams['AppName'] = Utility::isNullOrEmpty('AppName',$_POST);
		$arrParams['ServPort'] = Utility::isNullOrEmpty('ServPort',$_POST);
		$arrParams['ServID'] = Utility::isNumeric('ServID',$_POST);
		
		if($arrParams['ServPort'] && $arrParams['ServerIP'])
			$arrParams['ServerIP'] = str_replace(',', ':'.$arrParams['ServPort'].',', $arrParams['ServerIP']).':'.$arrParams['ServPort'];
		
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
			$iResult = $arrReturns['iResult'];
		$arrParams['ServerIP'] = str_replace(',', '<br>', $arrParams['ServerIP']);
		if($iResult==0)
			$msg='游戏服务器配置信息发布成功';
		else
			$msg='游戏服务器配置信息发布失败';
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
			$iResult = $this->objMasterBLL->setServerLocked($ServerID,$this->strServerType);
		if($iResult==0)
	 		$msg='';
	 	else
	 		$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'ServerGame\')','游戏服务器配置信息发布失败','false','ServerGame',$this->arrConfig);	
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
				$html=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'ServerGame\')','删除失败,请重试','false','ServerGame',$this->arrConfig);
		}
		else 
			$html=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'ServerGame\')','对不起,您提交的数据异常,请重试','false','ServerGame',$this->arrConfig);
		echo json_encode(array('Msg'=>$html,'iResult'=>$iResult));
	}
	/**
	 * 生成配置文件
	 */
	public function createFiles()
	{
		$ServerID = Utility::isNumeric('ServID',$_POST);		
		if($ServerID && $ServerID>0)		
		{
			
				$arrResult = $this->objMasterBLL->getRoomAndKindList($ServerID);
				if(is_array($arrResult) && count($arrResult)>0)
				{
					$iCount = 0;
					$strXMLHeader = '<?xml version="1.0" encoding="GB2312" ?>'.chr(13).'<root>'.chr(13);
					$strXMLFooter = '</root>';
					$strXML = '';
					foreach ($arrResult as $val)
					{
						$strXML .= '<server kindname="'.$arrResult[$iCount]['KindName'].'"  roomid="'.$arrResult[$iCount]['RoomID'].'" modulename="'.$arrResult[$iCount]['ServerDLL'].'" roomname="'.$arrResult[$iCount]['RoomName'].'" dbfile="GameConfig.ini"></server>'.chr(13);
						$iCount++;
					}
					
					$filename=$this->arrConfig['Directory']."/".$ServerID.'_config.xml';
					$fp=fopen($filename, "w+"); //打开文件指针，创建文件
					if (!is_writable($filename))
						$html=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'ServerGame\')','文件:' .$filename. '创建失败，可能目录权限不够！','false','ServerGame',$this->arrConfig);
					else 
					{
						file_put_contents($filename, $strXMLHeader.$strXML.$strXMLFooter);
						$zipName = str_replace('xml','zip',$filename);
						$zip = new PHPZip();
 						$iResult = $zip->Zip($filename,$zipName);//生成压缩文件
 						if($iResult)
							$html=Utility::echoResultHtml($this->smarty,'点击下载','window.location.href=\''.$zipName.'\'','文件生成完成','false','ServerGame',$this->arrConfig);
					}
					fclose($fp);  //关闭指针	
				}
				else 
					$html=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'ServerGame\')','对不起,该服务器尚未分配房间','false','ServerGame',$this->arrConfig);
		}
		else 
			$html=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'ServerGame\')','对不起,您提交的数据异常,请重试','false','ServerGame',$this->arrConfig);
		echo $html;
	}
	
	/**
	 * 显示设置房间服务器弹出层(添加)
	 */
	public function showEditServerHtml()
	{
        $ServerID = $_POST['selected'];
        $ServerID = implode(',',$ServerID);
    	$arrTags=array("ServerID"=>$ServerID,'ServerTypeName'=>'房间','ClassName'=>'ServerRoom');
    	Utility::assign($this->smarty,$arrTags);
    	$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/ServerEditAll.html');
    	$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));  
    	echo $html;
	}
	public function showEditServerLineHtml()
	{
	    $ServerID = $_POST['selected'];
        $ServerID = implode(',',$ServerID);
	    $arrTags=array("ServerID"=>$ServerID,'ServerTypeName'=>'房间','ClassName'=>'ServerRoom');
	    Utility::assign($this->smarty,$arrTags);
	    $html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/ServerEditAllLine.html');
	    $html = str_replace("</script>","<\/script>",str_replace("\r\n","",$html));
	    echo $html;
	}
	/**
	 * 
	 * 批量修改房间服务器
	 * 
	 * **/
	public function editServer(){
	    $ServerID = Utility::isNullOrEmpty('ServerID', $_POST);
	    $ServerIP = Utility::isNullOrEmpty('ServerIP', $_POST);
	    $PreServerIP = Utility::isNullOrEmpty('PreServerIP', $_POST);
	    if($ServerID){
	        $ServerID = explode(',',$ServerID);
	        foreach ($ServerID as $ID){
	            $arrRes=$this->objMasterBLL->getServerInfo($ID,$this->strServerType);
	            $arrRes['ServerIP'] = str_replace($PreServerIP,$ServerIP,$arrRes['ServerIP']);
	            if($arrRes['ServPort'] && $arrRes['ServerIP'])
	                $arrRes['ServerIP'] = str_replace(',', ':'.$arrRes['ServPort'].',', $arrRes['ServerIP']).':'.$arrRes['ServPort'];
	            
        	    //ServerIP转二进制格式保存
        	    $arrServerIP = explode(',',$arrRes['ServerIP']);
        	    if(is_array($arrServerIP) && count($arrServerIP)>0)
        	    {
        	        $strIP = null;
        	        for($i=0;$i<count($arrServerIP);$i++){
        	            $arrServIP = explode(':',$arrServerIP[$i]);
        	            if(is_array($arrServIP) && count($arrServIP)==2){
        	                $IP = Utility::ip2int($arrServIP[0]);
        	                $strIP .= pack('NS',$IP,$arrServIP[1]);
        	            }
        	        }
        	        if(!empty($strIP))
        	            $arrRes['IP'] = $strIP;
                    else
            			$arrRes['IP'] = null;
            	}
            	$ret = $this->objMasterBLL->addServer($arrRes,$this->strServerType);
	        }
	    }
	    $msg="修改成功";
		echo json_encode(array('Msg'=>'修改成功','iResult'=>0));
	}
	
	/**
	 *
	 * 批量修改房间服务器线路信息
	 *
	 * **/
	public function editServerLine(){
	    $ServerID = Utility::isNullOrEmpty('ServerID', $_POST);
	    $ServerIP = Utility::isNullOrEmpty('ServerIP', $_POST);
	    $PreServerIP = Utility::isNullOrEmpty('PreServerIP', $_POST);
	    if($ServerID){
	        $ServerID = explode(',',$ServerID);
	        foreach ($ServerID as $ID){
	            $arrRes=$this->objMasterBLL->getServerInfo($ID,$this->strServerType);
	            $arrRes['Intro'] = str_replace($PreServerIP,$ServerIP,$arrRes['Intro']);
	            if($arrRes['ServPort'] && $arrRes['ServerIP'])
	                $arrRes['ServerIP'] = str_replace(',', ':'.$arrRes['ServPort'].',', $arrRes['ServerIP']).':'.$arrRes['ServPort'];   
	            $ret = $this->objMasterBLL->addServer($arrRes,$this->strServerType);
	        }
	    }
	    $msg="修改成功";
		echo json_encode(array('Msg'=>'修改成功','iResult'=>0));
	}
	
}
?>