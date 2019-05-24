<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/UserBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';

require_once ROOT_PATH . 'Link/GetRoleBaseInfo.php';
require_once ROOT_PATH . 'Link/QuerySuperUserList.php';
class GamePayMentAction extends PageBase {
    public function __construct() {
        $this->arrConfig = unserialize(SYS_CONFIG);
    }

    public function updateOrderStatus() {
        $orderId = $_GET["orderId"];
        $amount = $_GET["amount"];
        $status = $_GET["status"]; //SUCCESS/FAILD
        file_put_contents("./log/order_".Date('YmdHis').'.txt',$orderId.'||'.$status."||".$amount);

        if($status=='SUCCESS'){
            $ret = $status=='SUCCESS'?1:0;
            if(!empty($orderId) && !empty($status)){

                $orderId = str_replace("GZ","",$orderId);
                $objMasterBLL = new MasterBLL();
                $arrServerList = $objMasterBLL->updateThirdOrder($orderId,$status);
            }
        }
        echo 1;
    }
}
?>