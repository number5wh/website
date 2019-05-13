<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Common/Zip.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';

class RobotUserAction extends PageBase
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
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/RobotUserList.html');
	}
	
	/**
	 * 机器人配置分页
	 */
	public function getPagerRobotUser()
	{
	    $UserID = Utility::isNumeric('UserID', $_POST);
	    if($UserID)
	    {
	        $arrRobotList = $this->objMasterBLL->getRobotUser($UserID);
	        $iRecordCount=(count($arrRobotList)==10?1:0);
	        $Page=Utility::setPages(1,$iRecordCount,20);
	        $RoomInfo = $this->objMasterBLL->getGameRoomInfoList();
	        foreach ($RoomInfo as $k => $v){
	            if($arrRobotList['RoomID'] == $v['RoomID']){
	                $arrRobotList['RoomName'] = $v['RoomName'];
	                break;
	            }
	        }
	        $RobotList=array();
	        if($iRecordCount)
	           $RobotList[0]=$arrRobotList;
	        $arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'RobotList'=>$RobotList);
	        Utility::assign($this->smarty,$arrTags);
	        $html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/RobotUserListPage.html');
	        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
	        echo $html;
	    }
	    else
	    {
		  $curPage = Utility::isNumeric('curPage',$_POST);
		  $curPage = $curPage<=0 ? 1 : $curPage;
		  $arrParam['fields']=' UserID,RoomID,ServiceTime,MinTakeScore,MaxTakeScore,MinPlayDraw,MaxPlayDraw,MinReposeTime,MaxReposeTime,ServiceGender ';
		  $arrParam['tableName']=' T_RobotUser';
		  $arrParam['where']='';
		  $arrParam['order']=' UserID ';
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
		  $html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/RobotUserListPage.html');
		  $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		  echo $html;
	    }
	}
	
	/**
	 * 显示设置机器人配置弹出层(添加)
	 */
	public function showAddRobotUserHtml()
	{
		$UserID = Utility::isNumeric('UserID',$_POST);
		if($UserID && $UserID>0){
		    $arrRes=$this->objMasterBLL->getRobotUser($UserID);
		}else {
		    $arrRes=array('UserID'=>'');
		}
		$RoomInfo = $this->objMasterBLL->getGameRoomInfoList();
		$arrTags=array('robot'=>$arrRes,'RoomInfo'=>$RoomInfo);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/RobotUserEdit.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	
	/**
	 * 显示设置机器人配置弹出层(添加)
	 */
	public function showdelRobotUserHtml()
	{

	    $RoomInfo = $this->objMasterBLL->getGameRoomInfoList();
	    $arrTags=array('RoomInfo'=>$RoomInfo);
	    Utility::assign($this->smarty,$arrTags);
	    $html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/RobotUserDel.html');
	    $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
	    echo $html;
	}
	/**
	 * 显示批量修改房间机器人配置弹出层(添加)
	 */
	public function showEditRobotUserHtml()
	{
	    
	    $RoomInfo = $this->objMasterBLL->getGameRoomInfoList();
	    $arrTags=array('RoomInfo'=>$RoomInfo);
	    Utility::assign($this->smarty,$arrTags);
	    $html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/RobotUserEditAll.html');
	    $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
	    echo $html;
	}
	/**
	 * 设置机器人信息
	 */
	public function addRobotUser()
	{
		$arrParams['UserID'] = Utility::isNumeric('UserID',$_POST);
		$arrParams['RoomID'] = Utility::isNumeric('RoomID',$_POST);
		$arrParams['ServiceTime'] = Utility::isNumeric('ServiceTime',$_POST);
		$arrParams['MinTakeScore'] = Utility::isNumeric('MinTakeScore',$_POST);
		$arrParams['MaxTakeScore'] = Utility::isNumeric('MaxTakeScore',$_POST);
		$arrParams['MinPlayDraw'] = Utility::isNumeric('MinPlayDraw',$_POST);
		$arrParams['MaxPlayDraw'] = Utility::isNumeric('MaxPlayDraw',$_POST);
		$arrParams['MinReposeTime'] = Utility::isNumeric('MinReposeTime',$_POST);
		$arrParams['MaxReposeTime'] = Utility::isNumeric('MaxReposeTime',$_POST);
		$arrParams['ServiceGender']	 = Utility::isNumeric('ServiceGender', $_POST);
		//$iResult=0:失败,-2:数据库异常,大于0:成功
		$iResult = $this->objMasterBLL->addRobotUser($arrParams);
		if($iResult == 0)
			$msg='机器人配置信息发布成功';
		else
			$msg='机器人配置信息发布失败';		
		echo $msg;
	}
	/**
	 * 设置机器人信息
	 */
	public function addAllRobotUser()
	{
	    $arrParams['RoomID'] = Utility::isNumeric('RoomID',$_POST);
	    $arrParams['ServiceTime'] = Utility::isNumeric('ServiceTime',$_POST);
	    $arrParams['MinTakeScore'] = Utility::isNumeric('MinTakeScore',$_POST);
	    $arrParams['MaxTakeScore'] = Utility::isNumeric('MaxTakeScore',$_POST);
	    $arrParams['MinPlayDraw'] = Utility::isNumeric('MinPlayDraw',$_POST);
	    $arrParams['MaxPlayDraw'] = Utility::isNumeric('MaxPlayDraw',$_POST);
	    $arrParams['MinReposeTime'] = Utility::isNumeric('MinReposeTime',$_POST);
	    $arrParams['MaxReposeTime'] = Utility::isNumeric('MaxReposeTime',$_POST);
	    $arrParams['ServiceGender']	 = Utility::isNumeric('ServiceGender', $_POST);
	    //$iResult=0:失败,-2:数据库异常,大于0:成功
	    $iResult = $this->objMasterBLL->addAllRobotUser($arrParams);
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
	public function delRobotUser()
	{
	    $iResult = -9999;
	    $UserID = Utility::isNumeric('UserID',$_POST);
	    if($UserID && $UserID>0)
	    {
	        $iResult = $this->objMasterBLL->delRobotUser($UserID);
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
	 * 删除机器人信息
	 * TypeID 1 按数量  2按页码  3按房间号
	 * $iResult: 大于0:成功,0:失败,-2:数据库异常
	 */
	public function delAllRobotUser()
	{
	    $iResult = -9999;
	    $TypeID = Utility::isNumeric('TypeID',$_POST);
	    $Value = Utility::isNumeric('Value',$_POST);
	    if($TypeID && $Value)
	    {
	        $iResult = $this->objMasterBLL->delAllRobotUser($TypeID,$Value);
	        if($iResult==0)
	            $msg='删除成功';
	        else
	            $msg='删除失败,请重试';
	    }
	    else
	        $msg='对不起,您提交的数据异常,请重试';
	    echo $msg;
	}
}
?>