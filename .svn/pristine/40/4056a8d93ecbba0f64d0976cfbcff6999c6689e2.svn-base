<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/12/31
 * Time: 14:26
 */

require ROOT_PATH . 'Class/BLL/PayLogsBLL.class.php';

require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
/**
 * Class RealCardFormAction 实卡数据统计
 */
class RealCardGetFormAction extends PageBase{
    public function __construct(){
        $this->arrConfig = unserialize(SYS_CONFIG);
        Utility::chkUserLogin($this->arrConfig);
    }


    public function index(){

        $list = $this->tongji();
        $arrTags = array("UntokenCard"=>$list['UntokenCard'],"TokenCard"=>$list['TokenCard']);
        Utility::assign($this->smarty,$arrTags);
        $this->smarty->display($this->arrConfig['skin'].'/YunWei/RealCardGetForm.html');
    }

    private function tongji(){
        $objPayLogsBLL = new PayLogsBLL(0);
        $list = $objPayLogsBLL->summaryRechargeCard();
        //var_dump($list);
        return $list;
    }
    
    public function destroy(){
        $state = Utility::isNumeric('state', $_REQUEST);
        $objPayLogsBLL = new PayLogsBLL(0);
        $ret = $objPayLogsBLL->delTestCard($state);
        echo $ret['iResult'];
    }
}