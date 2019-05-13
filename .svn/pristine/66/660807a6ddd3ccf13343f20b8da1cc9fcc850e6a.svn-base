<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/11/30
 * Time: 13:33
 */
require 'Include/Init.inc.php';
require_once ROOT_PATH . 'Include/dbpayconfig.php';
require_once ROOT_PATH . 'Link/BuyHappyBean.php';
require_once ROOT_PATH . 'Link/BuyVIP.php';
require_once ROOT_PATH . 'Link/GetPayOrder.php';
require_once ROOT_PATH . 'Link/FindPayOrder.php';
require_once ROOT_PATH . 'Link/AddPayLogs.php';
require_once ROOT_PATH . 'Link/SetPayOrderStatus.php';
require_once ROOT_PATH . 'Link/SetPayOrderTransactionID.php';
require_once ROOT_PATH . 'Link/LockOrder.php';
require_once ROOT_PATH . 'Link/UnLockOrder.php';

$file_name =basename($_SERVER['PHP_SELF'],'.php');
Utility::Log($file_name, 'receive_info', json_encode($_REQUEST));

$payOrderId = $_POST["orderid"];
$outTradeNo = $_POST['outtradeno'];
$amount = $_POST["totalfee"];
$tradeType = $_POST["tradetype"];
$subject = $_POST["subject"];
$paymethod = $_POST["paymethod"];
$merid = $_POST["merid"];
$body = $_POST["body"];
$tradeState = $_POST['tradestate'];
$sign = $_POST["sign"];

//验证sign
$str = '';
foreach($_POST as $key=>$value)
{
    if($key != "sign"){
        $str.=$key.'='.$value.'&';
    }
}
$str .= 'key='.'5be782a110a62f6fc3abc242b76033e9';
$checksign = strtoupper(md5($str));
if ($checksign !== $sign) {
    return "签名不对";
}


$cardType = 40;
$result_code=0;

////处理业务逻辑
//if(OSLockOrder($cardType, $order_no)['iResult']==0){
//    if(OSFindPayOrder($cardType,'',$order_no,0)['iResult'] != 0){   //这笔订单已经处理过了
//        $order = OSGetPayOrder($order_no, $cardType);
//        /* 如果pay_result大于0则表示支付失败 */
//    }else{
//        if ($result_code == 0)
//        {
//            if(OSFindPayOrder($cardType,'',$order_no,1)['iResult'] == 0){
//                //这笔订单已经支付成功过了
//            }else{
//                OSSetPayOrderStatus($cardType,'',$order_no,1);
//                if($order['iPayType']  == 1){ //欢乐豆
//                    $result = DCBuyHappyBean( $order ['iLoginID'], floor($amount),$cardType);   //充值欢乐豆
//                    Utility::Log($file_name, '购买欢乐豆：', json_encode($result));
//                }
//                else{//黄钻会员
//                    $result =  DCBuyVIP( $order ['iLoginID'], floor($amount/$arrConfig['VipPrice'])*$arrConfig['VipDays'],$cardType);
//                    Utility::Log($file_name, '购买黄专：', json_encode($result));
//                }
//
//                if($result['iResult']==0){
//                    OSSetPayOrderStatus($cardType,'',$order_no,3);   //修改订单处理状态为处理成功
//                }else{
//                    Utility::Log($file_name, 'error_info', json_encode($result));
//                    OSSetPayOrderStatus($cardType,'',$order_no,4);    //修改订单处理状态为处理失败
//                }
//            }
//            /*此处加入商户系统的逻辑处理（例如判断金额，判断支付状态，更新订单状态等等）......*/
//        }else{
//            OSSetPayOrderStatus($cardType,'',$order_no,2);      //修改订单状态为支付失败
//        }
//        OSSetPayOrderTransactionID ( $cardType, $trade_no, $order_no );
//    }
//    OSUnLockOrder($order_no);
//}
echo "SUCCESS";

