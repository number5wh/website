<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Common/Zip.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/DataChangelogsBLL.class.php';
class RoomRobotAction extends PageBase
{	
	private $objMasterBLL = null;
	private $objDataChangelogsDB = null;
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);
		$this->objMasterBLL = new MasterBLL();
		$this->objDataChangelogsDB = new DataChangeLogsBLL();
		$this->strServerType = 1;//服务器类型,0:数据库服务器,1、游戏服务器,2、登录服务器,3、下载服务器,4、版本服务器,5、银行服务器,6、中心服务器, 7.大厅服务器
	}
	public function index()
	{
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/RoomRobotList.html');
		
	}
	
	/**
	 * 机器人配置分页
	 */
	public function getPagerRoomRobot()
	{
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage<=0 ? 1 : $curPage;
		$arrParam['fields']='RoomID,MaxCount,RobotWinWeighted,RobotWinMoney,ServiceTables ';
		$arrParam['tableName']=' T_RoomRobot';
		$arrParam['where']='';
		$arrParam['order']=' RoomID ';
		$arrParam['pagesize']=20;

		$objCommonBLL = new CommonBLL(0);
		$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);	
		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
		$arrRobotList = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);
		$RoomInfo = $this->objMasterBLL->getGameRoomInfoList();
		foreach ($arrRobotList as $key =>$val){
		    foreach ($RoomInfo as $k => $v){
		        if($val['RoomID'] == $v['RoomID']){
		            $arrRobotList[$key]['RoomName'] = $v['RoomName'];
		            break;
		        }
		    }
		}
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'RobotList'=>$arrRobotList);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/RoomRobotListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	
	/**
	 * 显示设置机器人配置弹出层(添加)
	 */
	public function showAddRoomRobotHtml()
	{
		$RoomID = Utility::isNumeric('RoomID',$_POST);
		if($RoomID && $RoomID>0){
		    $arrRes=$this->objMasterBLL->getRoomRobot($RoomID);
		}else {
		    $arrRes=array('RoomID'=>'');
		}
		$RoomInfo = $this->objMasterBLL->getGameRoomInfoList();
		$arrTags=array('robot'=>$arrRes,'RoomInfo'=>$RoomInfo);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/RoomRobotEdit.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	/**
	 * 设置机器人信息
	 */
	public function addRoomRobot()
	{
		$arrParams['RoomID'] = Utility::isNumeric('RoomID',$_POST);
		$arrParams['MaxCount'] = Utility::isNumeric('MaxCount',$_POST);
		$arrParams['RobotWinWeighted'] = Utility::isNumeric('RobotWinWeighted',$_POST);
		$arrParams['RobotWinMoney'] = Utility::isNumeric('RobotWinMoney',$_POST);
		$arrParams['ServiceTables'] = Utility::isNumeric('ServiceTables', $_POST);
		$arrParams['AddWinPre'] = Utility::isNumeric('AddWinPre', $_POST);
		$arrParams['MinTakeScore'] = Utility::isNumeric('MinTakeScore', $_POST);
		$arrParams['MaxTakeScore'] = Utility::isNumeric('MaxTakeScore', $_POST);
		$arrParams['MinPlayDraw'] = Utility::isNumeric('MinPlayDraw', $_POST);
		$arrParams['MaxPlayDraw'] = Utility::isNumeric('MaxPlayDraw', $_POST);
		$arrParams['MinReposeTime'] = Utility::isNumeric('MinReposeTime', $_POST);
		$arrParams['MaxReposeTime'] = Utility::isNumeric('MaxReposeTime', $_POST);
		$arrParams['MinLeavePre'] = Utility::isNumeric('MinLeavePre', $_POST);
		$arrParams['MaxLeavePre'] = Utility::isNumeric('MaxLeavePre', $_POST);
		//$iResult=0:失败,-2:数据库异常,大于0:成功
		$iResult = $this->objMasterBLL->addRoomRobot($arrParams);
		if($iResult == 1){
		    $RoomID = $arrParams['RoomID'];
		    $RoomInfo = $this->objMasterBLL->getGameRoomInfo($RoomID);
		    $KindID = $RoomInfo['KindID'];
		    $RoomType = $RoomInfo['RoomType'];
		    $this->objDataChangelogsDB->addRobotGameMoney($RoomID,$KindID,$RoomType);
		    
		}
		if($iResult >= 0)
			$msg='机器人配置信息发布成功';
		else
			$msg='机器人配置信息发布失败';		
		echo $msg;
	}

	/**
	 * 删除机器人信息
	 * $iResult: 大于0:成功,0:失败,-2:数据库异常
	 */
	public function delRoomRobot()
	{
	    $iResult = -9999;
	    $RoomID = Utility::isNumeric('RoomID',$_POST);
	    if($RoomID && $RoomID>0)
	    {
	        $iResult = $this->objMasterBLL->delRoomRobot($RoomID);
	        if($iResult==0)
	            $html=$iResult;
	        else
	            $html=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'SysVipLevel\')','删除失败,请重试','false','SysVipLevel',$this->arrConfig);
	    }
	    else
	        $html=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'SysVipLevel\')','对不起,您提交的数据异常,请重试','false','SysVipLevel',$this->arrConfig);
	    echo $html;
	}
	
}
?>