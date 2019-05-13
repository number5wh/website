<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/GameFiveBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
class SystemTurnAction extends PageBase{
    private $objMasterBLL = null;	
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);
		$this->objMasterBLL = new MasterBLL();
	}
	public function index()
	{
		$arrGameConfigList = $this->objMasterBLL->getSystemTurnList();
		foreach ($arrGameConfigList as $key=>$val){
		    $arrGameConfigList[$key]['Description']=Utility::gb2312ToUtf8($val['Description']);
		}
		$arrTags=array('GameConfigList'=>$arrGameConfigList);
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/SystemTurnList.html');
	}	 
	/**
	 * 显示添加黄钻等级表单
	 */
	public function showAddSystemTurnHtml()
	{
	    $TypeID = Utility::isNumeric('TypeID',$_POST);
	    if($TypeID){
	        $arrGameConfigList = $this->objMasterBLL->getGameConfig($TypeID);
	        if(is_array($arrGameConfigList) && count($arrGameConfigList)>0)
	        {
	            $arrConfigInfo['CfgType']=$arrGameConfigList[0]['CfgType'];
	            $arrConfigInfo['CfgValue']=$arrGameConfigList[0]['CfgValue'];
	            $arrConfigInfo['Description']=Utility::gb2312ToUtf8($arrGameConfigList[0]['Description']);
	        }
	    }else{
	        $arrConfigInfo=array('CfgType'=>0);
	    }
	    $arrTags = array('ConfigInfo'=>$arrConfigInfo);
	    Utility::assign($this->smarty,$arrTags);
	    $html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/SystemTurnEdit.html');
	    $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
	
	    echo $html;
	}
	/**
	 * 添加黄钻等级
	 */
	public function addSystemTurn()
	{
	    $iResult = -9999;
	    $arrParams['TypeID'] = Utility::isNumeric('TypeID',$_POST);
	    $arrParams['CfgValue'] = Utility::isNumeric('CfgValue',$_POST);
	    $arrParams['Description'] = Utility::isNullOrEmpty('Description',$_POST);
	    if($arrParams['TypeID']!=null)
	        $iResult = $this->objMasterBLL->addGameConfig($arrParams);
	    if($iResult==0)
	        $msg='游戏配置修改成功';
	    elseif($iResult==-1)
	    $msg='游戏配置修改失败';
	    else
	        $ms='参数错误';
	    echo $msg;
	}
	/**
	 * 删除黄钻等级
	 * $iResult: 大于0:成功,0:失败,-2:数据库异常
	 */
	public function delSystemTurn()
	{
	    $iResult = -9999;
	    $TypeID = Utility::isNumeric('TypeID',$_POST);
	    if($TypeID && $TypeID>0)
	    {
	        $iResult = $this->objMasterBLL->delGameConfig($TypeID);
	        if($iResult==0)
	            $html=$iResult;
	        else
	            $html=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'SysGameConfig\')','删除失败,请重试','false','SysGameConfig',$this->arrConfig);
	    }
	    else
	        $html=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'SysGameConfig\')','对不起,您提交的数据异常,请重试','false','SysGameConfig',$this->arrConfig);
	    echo $html;
	}
}
?>