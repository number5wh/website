<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/9/19
 * Time: 14:17
 */
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/SystemBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserDataBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/BankBLL.class.php';
require ROOT_PATH . 'Class/BLL/StagePropertyBLL.class.php';
require ROOT_PATH . 'Class/BLL/OperationLogsBLL.class.php';

require_once ROOT_PATH . 'Link/ReloadGameData.php';
class SynchroDataAction extends PageBase{
    public function __construct(){
        $this->arrConfig = unserialize(SYS_CONFIG);
        $this->strLoginedUser = Utility::chkUserLogin($this->arrConfig);
    }
    public function index(){
        $this->smarty->display($this->arrConfig['skin'].'/YunWei/SynchroData.html');
    }

    /**
     * 同步数据
     */
    public function synchroData(){

        $iResult = -1;
        $strMsg = "数据同步失败";
        $iLoadType = 0;
        $arrResult = DCReloadGameData($iLoadType);
        if(is_array($arrResult) && isset($arrResult['iResult'])){
            if($arrResult['iResult'] === 0){
                $iResult = 0;
                $strMsg = "数据同步成功";
            }
        }
        echo json_encode(array("iResult"=>$iResult,"msg"=>$strMsg));
    }
}