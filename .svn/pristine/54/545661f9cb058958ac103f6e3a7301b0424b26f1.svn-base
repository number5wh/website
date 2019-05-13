<?php
require ROOT_PATH.'Common/PageBase.class.php';
require ROOT_PATH.'Common/Session.class.php';
require ROOT_PATH.'Class/BLL/MasterBLL.class.php';
require ROOT_PATH.'Class/BLL/CommonBLL.class.php';
class GameDunAction extends PageBase{
  private $objMasterBLL = null;
  public function __construct(){
        $this->objMasterBLL = new MasterBLL();
        $this->arrConfig = unserialize(SYS_CONFIG);
        Utility::chkUserLogin($this->arrConfig);
  }  
  public function index(){
        $this->smarty->display($this->arrConfig['skin'].'/YunWei/GameDunList.html');
  }
  /**
   * 游戏盾分页
   */
  public function getPagerGameDun(){
        $curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage<=0 ? 1 : $curPage;
		$arrParam['fields']='ID,GroupName,GroupURL,Type,ParameterType,NUMBER';
		$arrParam['tableName']='T_GameDunInfo';
		$arrParam['where']=' WHERE 1=1 ';
		$arrParam['order']='NUMBER';
		$arrParam['pagesize']=20;

		$objCommonBLL = new CommonBLL(0);
		$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);		
		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
		$arrGameDunList = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);
        if(count($arrGameDunList)>0){
            foreach($arrGameDunList as $key=>$val){
                $arrGameDunList[$key]['GroupName'] = Utility::gb2312ToUtf8($val['GroupName']);
            }
        }
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'arrGameDunList'=>$arrGameDunList);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/GameDunListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
  }
  /**
   * 显示设置游戏盾信息弹出层
   */
  public function showAddGameDunHtml()
  {
		$ID = Utility::isNumeric('ID',$_POST);
		if($ID && $ID>0){
		    $arrRes=$this->objMasterBLL->getGameDunInfo($ID);
		    $arrRes['GroupName'] = Utility::gb2312ToUtf8($arrRes['GroupName']);
		}else 
			$arrRes=array('ID'=>'','GroupName'=>'','Type'=>'','ParameterType'=>'');

		$arrTags=array('GameDun'=>$arrRes);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/GameDunEdit.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));

		echo $html;
  }
  /**
   * 添加游戏盾
   */
  public function addGameDun()
  {
       $OldID = Utility::isNullOrEmpty('OldID', $_POST);
       $ID = Utility::isNumeric('ID', $_POST);
       $GroupName = Utility::isNullOrEmpty('GroupName', $_POST);
       $GroupURL = Utility::isNullOrEmpty('GroupURL', $_POST);
       $Type = Utility::isNumeric('Type', $_POST);
       $ParameterType = Utility::isNullOrEmpty('ParameterType', $_POST);
       $iResult=-1;
       if(!$ID)
            $msg='分组ID格式错误';
       else 
           if(!$Type)
             $msg='类型格式错误';  
       else 
       {
            if(!$OldID)
                $OldID='';
            if($GroupName)
                $GroupName = Utility::utf8ToGb2312($GroupName);
            $arrReturns = $this->objMasterBLL->addGameDun($OldID,$ID,$GroupName,$GroupURL,$Type,$ParameterType);
            $iResult=$arrReturns[0]['iResult'];
            if($iResult==0)
                   $msg='更新成功';
            else
                   $msg='更新失败';
       }
       echo json_encode(array('Msg'=>$msg,'iResult'=>$iResult));
  }
  /**
   * 删除游戏盾
   */
  public function delGameDun(){
        $iResult = -9999;
        $ID = Utility::isNumeric('ID', $_POST);
        if( $ID && $ID>0){
            $arr = $this->objMasterBLL->delGameDun($ID);
            $iResult = $arr['iResult'];
            if($iResult==0)
                $html='';     
            else
                $html = Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'GameDun\')','删除失败,请重试','false','GameDun',$this->arrConfig);
        }
        else
                $html=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'GameDun\')','对不起,您提交的数据异常,请重试','false','GameDun',$this->arrConfig);
        echo json_encode(array('Msg'=>$html,'iResult'=>$iResult));
  }
}
?>