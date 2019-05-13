<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/BankBLL.class.php';
require ROOT_PATH . 'Link/SetLuckyEggTax.php';
class RoomLuckyEggMoneyAction extends PageBase
{	
	private $objBankBLL = null;	
	private $objMasterBLL = null;
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);
		$this->objBankBLL = new BankBLL();
		$this->objMasterBLL = new MasterBLL();
	}
	public function index()
	{
		$arrList = $this->objBankBLL->getRoomLuckyEggMoneyList();
		$roomInfo = $this->objMasterBLL->getGameRoomInfoList();
		foreach ($arrList as $key => $val){
		    foreach ($roomInfo as $v){
		        if($val['RoomID'] == $v['RoomID']){
		            $arrList[$key]['RoomName'] = $v['RoomName'];
		        }
		    }
		}
		$arrTags=array('List'=>$arrList);
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/RoomLuckyEggMoneyList.html');
	}	 
	
	/**
	 * 显示设置奖金池弹出层
	 */
	public function showEditRoomLuckyEggMoneyHtml()
	{
	    $ID = Utility::isNumeric('ID',$_POST);
	    $arrRes = $this->objBankBLL->getRoomLuckyEggMoney($ID);
	    Utility::assign($this->smarty,['arrRes'=>$arrRes]);
	    $html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/RoomLuckyEggMoneyEdit.html');
	    $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
	    echo $html;
	}
	
	

	/**
	 * 设置彩蛋抽税率
	 */
	public function editRoomLuckyEggMoney()
	{
	    $iResult = -9999;
	    $RoomID = Utility::isNumeric('RoomID', $_POST);
	    $LuckyEggTax = Utility::isNumeric('LuckyEggTax',$_POST);
	    $iResult = DCSetLuckyEggTax($RoomID, $LuckyEggTax);
	    if($iResult['iResult']==0){
	        $msg = '设置彩蛋税率成功';
	    }else {
	        $msg = '设置失败，请稍后再试';
	    }
	    echo json_encode(array('iResult'=>$iResult,'msg'=>$msg));
	}
}
?>