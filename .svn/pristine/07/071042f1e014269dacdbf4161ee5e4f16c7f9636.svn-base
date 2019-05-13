<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';

class GameTaskAction extends PageBase
{	
	private $objMasterBLL = null;
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);
		$this->objMasterBLL = new MasterBLL();
	}
	
	function index()
	{
		$arrList = $this->objMasterBLL->getGameTaskList('');
		$roomKind = $this->objMasterBLL->getGameKindList(1,0);
		$roomType = $this->arrConfig['RoomType'];
		if(is_array($arrList)){
    		foreach($arrList as $k=>$v){
    		    foreach ($roomKind as $val){
    		        if($v['KindID'] == $val['KindID']){ 
    		            $arrList[$k]['KindName'] = $val['KindName'];
    		        }
    		    }
    		    foreach ($roomType as $val){
    		        if($v['RoomType']  == $val['TypeID']){
    		            $arrList[$k]['TypeName']  = $val['TypeName'];
    		        }
    		    }
    		}
		    
		}else {
		    $arrList = array();
		}
		$arrTags=array('GameTaskList'=>$arrList);
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/GameTaskList.html');
	}
	
	/**
	 * 显示添加桌子类型表单
	 */
	public function showAddGameTaskHtml()
	{
		$TaskID = Utility::isNumeric('TaskID',$_POST);
		$arrList = array();
		if($TaskID){
			//显示选定桌子的详细信息
			$arrResult = $this->objMasterBLL->getGameTaskList($TaskID);
			$arrList = $arrResult[0];
		}			
		$roomKind = $this->objMasterBLL->getGameKindList(1,0);
		$roomType = $this->arrConfig['RoomType'];
		$arrTags = array('GameTask'=>$arrList, 'TaskID'=>$TaskID,'RoomKind'=>$roomKind,'RoomType'=>$roomType);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/AddGameTask.html');
		$html = str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));

		echo $html;
	}
	
	/**
	 * 增加或更新桌子类型信息
	 */
	function updateGameTaskInfo()
	{
		$TaskID = Utility::isNumeric('TaskID',$_POST);
		$KindID = Utility::isNumeric('KindID',$_POST);
		$RoomType = Utility::isNumeric('RoomType',$_POST);
		$GameCount = Utility::isNumeric('GameCount',$_POST);
		$AwardMoney = Utility::isNumeric('AwardMoney',$_POST);
		$iResult = 0;
		$iResult = $this->objMasterBLL->addGameTask($TaskID, $KindID,$RoomType,$GameCount,$AwardMoney);
		if($iResult==0)
			$msg='游戏任务设置成功';
		else
			$msg='游戏任务设置失败';
		echo json_encode(array('msg'=>$msg,'result'=>$iResult));
	}
	/**
	 * 删除游戏任务
	 */
	function delGameTask()
	{
	    $TaskID = Utility::isNumeric('TaskID',$_POST);
	    $iResult = 0;
	    if($TaskID && $TaskID>0)
	        $iResult = $this->objMasterBLL->delGameTask($TaskID);
	    else
	        $iResult = -9999;
	    if($iResult==0)
	        $msg=$iResult;
	    elseif($iResult==-1)
	    $msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'GameTable\')','游戏任务删除失败','false','GameTable',$this->arrConfig);
	    else
	        $msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'GameTable\')','对不起,您提交的数据异常,请重试','false','GameTable',$this->arrConfig);
	    echo $msg;
	}
}