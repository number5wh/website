<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';

class GameSignAction extends PageBase
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
		$arrList = $this->objMasterBLL->getGameSignList('','');
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
		$arrTags=array('GameSignList'=>$arrList);
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/GameSignList.html');
	}
	
	/**
	 * 显示添加桌子类型表单
	 */
	public function showAddGameSignHtml()
	{
		$KindID = Utility::isNumeric('KindID',$_POST);
	    $RoomType = Utility::isNumeric('RoomType', $_POST);
		$arrList = array();
		if($KindID && $RoomType){
			//显示选定桌子的详细信息
			$arrResult = $this->objMasterBLL->getGameSignList($KindID,$RoomType);
			$arrList = $arrResult[0];
		}			
		$roomKind = $this->objMasterBLL->getGameKindList(1,0);
		$roomType = $this->arrConfig['RoomType'];
		$arrTags = array('GameSign'=>$arrList, 'KindID'=>$KindID,'RoomKind'=>$roomKind,'RoomType'=>$roomType);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/AddGameSign.html');
		$html = str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));

		echo $html;
	}
	
	/**
	 * 增加或更新签到信息
	 */
	function updateGameSignInfo()
	{
		$KindID = Utility::isNumeric('KindID',$_POST);
		$RoomType = Utility::isNumeric('RoomType',$_POST);
		$SignType = Utility::isNumeric('SignType',$_POST);
		$SignValue = Utility::isNumeric('SignValue',$_POST);
		$SignAward = Utility::isNullOrEmpty('SignAward', $_POST);
		$PhoneExtra = Utility::isNullOrEmpty('PhoneExtra', $_POST);
		$iResult = $this->objMasterBLL->addGameSign($KindID,$RoomType,$SignType,$SignValue,$SignAward,$PhoneExtra);
		$iResult = $iResult['iResult'];
		if($iResult==0)
			$msg='游戏签到设置成功';
		else
			$msg='游戏签到设置失败';
		echo json_encode(array('msg'=>$msg,'result'=>$iResult));
	}
	/**
	 * 删除游戏签到信息
	 */
	function delGameSign()
	{
		$KindID = Utility::isNumeric('KindID',$_POST);
		$RoomType = Utility::isNumeric('RoomType',$_POST);
	    $iResult = 0;
	    if($KindID && $KindID>0&&$RoomType && $RoomType>0)
	        $iResult = $this->objMasterBLL->delGameSign($KindID,$RoomType);
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