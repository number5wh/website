<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/12/22
 * Time: 11:35
 */

require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/BankBLL.class.php';


/**
 * 捕鱼账户信息
 * Class FishingAction
 */
class FishingAction extends PageBase{
    public function __construct(){
        $this->arrConfig=unserialize(SYS_CONFIG);
        Utility::chkUserLogin($this->arrConfig);
        $this->fishing_accType = 22;
        $this->dntg_accType = 28;
    }


    public function index(){
        //$accTypeList = $this->arrConfig['BankAccType'];

        $objBankBLL = new BankBLL();
        $info1 = $objBankBLL->getSysBank($this->fishing_accType);
        $info2 = $objBankBLL->getSysBank($this->dntg_accType);

        $tags = array("FishingBankAccount"=>$info1,"DntgBankAccount"=>$info2);
        Utility::assign($this->smarty,$tags);
        $this->smarty->display($this->arrConfig['skin'].'/YunWei/FishingSysBankEdit.html');
    }
}