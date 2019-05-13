<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/9/19
 * Time: 12:52
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
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';

class SuperUserAction extends PageBase{

    public function __construct(){
        $this->arrConfig = unserialize(SYS_CONFIG);
        $this->strLoginedUser = Utility::chkUserLogin($this->arrConfig);
    }

    /**
     *
     */
    public function index(){
        $this->smarty->display($this->arrConfig['skin'].'/Service/SuperUser.html');
    }

    /**
     *
     */
    public function getPagerSuperUser(){

        $objCommonBLL = new CommonBLL(0);//masterDB

        var_dump($_POST);
        //$objCommonBLL->getRecordsCount($arrParams);
    }

    /**
     * 获取添加超级页面
     */
    public function getAddSuperUserPage(){
        $this->smarty->display($this->arrConfig['skin'].'/Service/SuperUserPage.html');
    }

    /**
     * 添加超级用户
     */
    public function addSuperUser(){
        $iRoleId = Utility::isNumeric('roleId',$_POST)?$_POST['roleId']:0;
        $iSuperLevel = Utility::isNumeric('number',$_POST)?$_POST['number']:0;
        if($iRoleId <= 0 || $iSuperLevel < 0){
            echo -1;
            exit();
        }
        $iResult = -1;
        $strMsg = "操作失败";
        $arrResult = DCSetSuperPlayer($iRoleId,$iSuperLevel);
        var_dump($arrResult);
        if(is_array($arrResult) && isset($arrResult['iResult'])){
            if($arrResult['iResult']===0){
                $iResult = 1;
                $strMsg = "操作成功";
            }
            //做日志操作
        }
        echo json_encode(array('iResult'=>$iResult,'msg'=>$strMsg));
    }
}