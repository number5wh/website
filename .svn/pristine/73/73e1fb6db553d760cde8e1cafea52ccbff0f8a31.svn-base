<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';

class MapDBAction extends PageBase
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
	 	//MapType列表	 
		$arrMapType = $this->objMasterBLL->getMapTypeALL();	
	 	//MapList列表	 
		$arrMapList = $this->objMasterBLL->getMapListALL();		
		if(is_array($arrMapType) && count($arrMapType)>0)
		{
			$iCount = 0;
			foreach ($arrMapType as $val)
			{
				if(is_array($arrMapList) && count($arrMapList)>0)
				{
					$TypeName = '';
					$ServerName = '';
					foreach ($arrMapList as $v)
					{
						if($val['MapID']==$v['MapID'])
						{
							$TypeName .= $v['TypeName'] . '<br />';
							$ServerName .= $v['ServerName'] . '<br />';
						}
					}					
				}
				$arrMapType[$iCount]['TypeName'] = $TypeName;
				$arrMapType[$iCount]['ServerName'] = $ServerName;
				$iCount++;
			}
		}

		/*$Locked = -1;	//锁定状态,-1:返回所有数据库服务器列表
		$arrRes = $this->objMasterBLL->getServerList($this->strServerType,$Locked);	
		if(is_array($arrRes) && count($arrRes)>0)
		{
			$iCount = 1;
			foreach($arrRes as $key => $val)
			{
				$arrRes[$key]['iCount'] = $iCount++;
				$arrRes[$key]['ServerName']=Utility::gb2312ToUtf8($val['ServerName']);
				$arrRes[$key]['ServerIP']=str_replace(',','<br />',$val['ServerIP']);
				$arrRes[$key]['Intro']=Utility::gb2312ToUtf8($val['Intro']);
				$arrRes[$key]['AppName']=Utility::gb2312ToUtf8($val['AppName']);
			}
		}*/
		$arrTags=array('MapList'=>$arrMapType);
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/MapList.html');
	}	
	/**
	 * 显示设置MAP弹出层
	 */
	public function showAddMapHtml()
	{
		$ID = Utility::isNumeric('ID',$_POST);
		if($ID && $ID>0)
			$arrRes=$this->objMasterBLL->getMapInfo($ID);
		else 
			$arrRes=array(array('Name'=>'','Hashlimit'=>1,'MapID'=>1,'AppName'=>'','ServerName'=>'','LANServerIP'=>'','ServerPort'=>'','Login'=>'','Pass'=>''));

		$arrServer = $this->objMasterBLL->getServerList(0,0);

		$arrTags=array('Map'=>$arrRes,'ServerList'=>$arrServer,'MapList'=>$this->arrConfig['MapList']);

		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/MapEdit.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}		
	/**
	 * 读取服务器列表
	public function getServerList()
	{
		$strOption = '<option value="0">请选择服务器</option>';
		$Locked = 0;
		$ServerTypeID = Utility::isNumeric('ServerTypeID',$_POST);
		$RowID = Utility::isNullOrEmpty('RowID',$_POST);
		$CurServerID = Utility::isNumeric('CurServerID',$_POST);
		$arrRes = $this->objMasterBLL->getServerList($ServerTypeID,$Locked);	
		if(is_array($arrRes) && count($arrRes)>0)
		{
			$strOption = '';
			foreach($arrRes as $key => $val)
			{
				$selected = '';
				if($CurServerID == $val['ServerID']) $selected='selected';
				$strOption .= '<option value="'.$val['ServerID'].'" '.$selected.'>'.Utility::gb2312ToUtf8($val['ServerName']).'</option>';
			}
		}		
		echo json_encode(array('Option'=>$strOption,'RowID'=>$RowID));
	}*/

	/**
	 * 设置MAP
	 */
	public function addMap()
	{
		$iResult = -9999;
		$arrParams['Name'] = Utility::isNullOrEmpty('Name',$_POST);
		$arrParams['Hashlimit'] = Utility::isNumeric('Hashlimit',$_POST);
		$arrParams['MapID'] = Utility::isNumeric('MapID',$_POST);//MapType表里的ID
		$arrParams['TypeName'] = Utility::isNullOrEmpty('TypeName',$_POST);
		$arrParams['ServerID'] = Utility::isNullOrEmpty('ServerID',$_POST);

		/*$Name = Utility::isNullOrEmpty('Name',$_POST);
		$Hashlimit = Utility::isNumeric('Hashlimit',$_POST);
		$TypeName = Utility::isNullOrEmpty('TypeName',$_POST);
		$ServerID = Utility::isNullOrEmpty('ServerID',$_POST);
		$ID = Utility::isNumeric('ID',$_POST);//MapType表里的ID
		if($Name && $Hashlimit && $TypeName && $ServerID)
		{
			$arrTypeName = explode(',', $TypeName);
			$arrServerID = explode(',', $ServerID);
			if(count($arrTypeName)!=count($arrServerID)*/
			
		if($arrParams['Name'] && $arrParams['Hashlimit'] && $arrParams['TypeName'] && $arrParams['MapID'] && $arrParams['ServerID'])
		{
			$arrTypeName = explode(',', $arrParams['TypeName']);
			$arrServerID = explode(',', $arrParams['ServerID']);		
			
			if(count($arrTypeName)!=$arrParams['Hashlimit'] || count($arrServerID)!=$arrParams['Hashlimit'])
				$msg='对不起,您提交的数据异常,请重试';
			else 
			{
				//$iResult=0:失败,-2:数据库异常,大于0:成功
				$iResult = $this->objMasterBLL->addMap($arrParams);
				if($iResult==0)
					$msg='MAP表设置成功';
				else
					$msg='MAP表设置失败';
			}	
		}	
		else 
			$msg='对不起,您提交的数据异常,请重试';
			
		echo json_encode(array('iResult'=>$iResult,'msg'=>$msg));
	}
	
}
?>