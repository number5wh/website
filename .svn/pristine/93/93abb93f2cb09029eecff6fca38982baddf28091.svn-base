<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Common/Zip.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';

class RobotNamePoolAction extends PageBase
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
		//$arrRes = $this->objMasterBLL->getGameServerList();
		//$arrTags=array('ServerList'=>$arrRes);
		//Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/RobotNameList.html');
		/*
		$arrRes = $this->getServerList();
		$arrTags=array('ServerList'=>$arrRes,'ClassName'=>'ServerGame','ServerTypeName'=>'房间数量');
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/ServerList.html');
		*/
	}
	
	/**
	 * 机器人配置分页
	 */
	public function getPagerRobotName()
	{
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage<=0 ? 1 : $curPage;
		$arrParam['fields']=' NameID,Name,Signature,Sex,RobotID ';
		$arrParam['tableName']=' T_RobotNamePool ';
		$arrParam['where']='';
		$arrParam['order']=' NameID ';
		$arrParam['pagesize']=20;

		$objCommonBLL = new CommonBLL(0);
		$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);	
		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
		$arrRobotList = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);
		if(is_array($arrRobotList) && count($arrRobotList)>0)
		{
			$iCount = 0;
			foreach ($arrRobotList as $val)
			{
				$arrRobotList[$iCount]['Name']=Utility::gb2312ToUtf8($val['Name']);
				$arrRobotList[$iCount]['Signature']=Utility::gb2312ToUtf8($val['Signature']);
				$arrRobotList[$iCount]['iCount']=$iCount+1;
				$iCount++;
			}
		}		
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'RobotList'=>$arrRobotList);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/RobotNamePoolListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	
	/**
	 * 显示设置机器人配置弹出层(添加)
	 */
	public function showAddRobotNamePoolHtml()
	{
		$NameID = Utility::isNumeric('NameID',$_POST);
		if($NameID && $NameID>0)
			$arrRes=$this->objMasterBLL->getRobotNamePool($NameID);
		else 
			$arrRes=array('NameID'=>0,'Name'=>'','Description'=>'','sex'=>'1');
		$arrTags=array('robot'=>$arrRes);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/RobotNameEdit.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	/**
	 * 显示设置机器人配置弹出层(添加)
	 */
	public function showAddAllRobotNamePoolHtml()
	{
	    $arrRes=array('NameID'=>0,'Name'=>'','Description'=>'','sex'=>'1');
	    $arrTags=array('robot'=>$arrRes);
	    Utility::assign($this->smarty,$arrTags);
	    $html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/RobotNameEditAll.html');
	    $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
	    echo $html;
	}
	
	/**
	 * 设置机器人信息
	 */
	public function addRobotNamePool()
	{
		$arrParams['NameID'] = Utility::isNumeric('NameID',$_POST);
		$arrParams['Name'] = Utility::isNullOrEmpty('Name',$_POST);
		$arrParams['Signature'] = Utility::isNullOrEmpty('Signature',$_POST);
		$arrParams['Sex'] = Utility::isNullOrEmpty('Sex',$_POST);	
		//$iResult=0:失败,-2:数据库异常,大于0:成功
		$iResult = $this->objMasterBLL->addRobotNamePool($arrParams);
		if($iResult == 0)
			$msg='1';
		else
			$msg='0';
		echo $msg;
	}
	/**
	 * 设置机器人信息
	 */
	public function addAllRobotNamePool()
	{
	    $Names = Utility::isNullOrEmpty('Name',$_POST);
	    $Sex = Utility::isNullOrEmpty('Sex',$_POST);	
		$arrParams['Sex'] = $Sex;
	    $Names = explode("\n",$Names);
	    //$iResult=0:失败,-2:数据库异常,大于0:成功
	    $iResult = 0;
	    foreach ($Names as $k =>$v){
	        if($v != ""&& strlen($v)>3&&strlen($v)<16){
    	        $arrParams['Name'] = $v;
        	    $arrParams['NameID'] ='';
        	    $arrParams['Signature'] = '';
    	       $ret = $this->objMasterBLL->addRobotNamePool($arrParams);
    	       if($ret !=0){
    	           $iResult = $ret;
    	       }	
	            
	        } 
	    }
	    if($iResult == 0)
	        $msg='机器人配置信息发布成功';
	    else
	        $msg='机器人配置信息发布失败';
	    echo $msg;
	}
	/**
	 * 删除机器人信息
	 * $iResult: 大于0:成功,0:失败,-2:数据库异常
	 */
	public function delRobotNamePool()
	{
	    $iResult = -9999;
	    $NameID = Utility::isNumeric('NameID',$_POST);
	    if($NameID && $NameID>0)
	    {
	        $iResult = $this->objMasterBLL->delRobotName($NameID);
	        if($iResult==0)
	            $html=$iResult;
	        else
	            $html=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'SysVipLevel\')','删除失败,请重试','false','SysVipLevel',$this->arrConfig);
	    }
	    else
	        $html=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'SysVipLevel\')','对不起,您提交的数据异常,请重试','false','SysVipLevel',$this->arrConfig);
	    echo $html;
	}
	/**
	 * 整理机器人信息
	 * $iResult: 大于0:成功,0:失败,-2:数据库异常
	 */
	public function formatRobotNamePool()
	{
        $iResult = $this->objMasterBLL->formatRobotName();
        if($iResult==0)
            $html=$iResult;
        else
            $html=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'SysVipLevel\')','整理失败,请重试','false','SysVipLevel',$this->arrConfig);
        echo $html;
	}
}
?>