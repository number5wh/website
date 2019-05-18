<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/11/30
 * Time: 13:33
 */
header("Content-type: text/html; charset=utf-8");
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

$file_name = basename($_SERVER['PHP_SELF'], '.php');
Utility::Log($file_name, 'receive_info', json_encode($_REQUEST));


require_once ROOT_PATH.'Common/huyapay/yidao.php';
$ydpay = new yidao();
//验证签名
$str = json_decode(stripslashes($_REQUEST['reqJson']));
//验证签名
if ($data = $ydpay->VerifySign($str->transData)){
//    if($data['isClearOrCancel']=="0"){
//        echo order_paid($data["reqMsgId"],2);
//
//        die("SUCCESS");
//    }
    $order_no   = $data['smzfMsgId'];
    $outTradeNo = $data['reqMsgId'];
    $timestamp  = $data['payTime'];
    $amount     = $data['totalAmount'];
    $tradeState = $data['isClearOrCancel'];
    $trateDate  = $data['payTime'];
    $sign       = $data['sign'];
    $respMsg    = $data['respMsg'];
    $merNo      = $data['merchantCode'];

    $cardType    = 1200;//支付宝
    $result_code = $tradeState == '0' ? 0 : 1;

    $order = OSGetPayOrder($order_no, $cardType);
    Utility::Log($file_name, '订单信息：', json_encode($order));
//file_put_contents("./param".date('H:i:s').rand(0,100).".txt",$order);
    OSAddPayLogs($result_code, '', date('Ymd', time()), $merNo, $outTradeNo, $order_no, (float)($amount*100), '', $cardType, $order ['iLoginID']);
    //处理业务逻辑
    if (OSLockOrder($cardType, $order_no)['iResult'] == 0) {
        if (OSFindPayOrder($cardType, '', $order_no, 0)['iResult'] != 0) {   //这笔订单已经处理过了
            $order = OSGetPayOrder($order_no, $cardType);
            /* 如果pay_result大于0则表示支付失败 */
        } else {
            if ($result_code == 0) {
                if (OSFindPayOrder($cardType, '', $order_no, 1)['iResult'] == 0) {
                    //这笔订单已经支付成功过了
                } else {
                    OSSetPayOrderStatus($cardType, '', $order_no, 1);
                    $result = DCBuyHappyBean($order ['iLoginID'], floor($amount*100), $cardType);   //充值欢乐豆 单位分
                    Utility::Log($file_name, '购买欢乐豆：', json_encode($result));
                    if ($result['iResult'] == 0) {
                        OSSetPayOrderStatus($cardType, '', $order_no, 3);   //修改订单处理状态为处理成功
                    } else {
                        Utility::Log($file_name, 'error_info', json_encode($result));
                        OSSetPayOrderStatus($cardType, '', $order_no, 4);    //修改订单处理状态为处理失败
                    }
                }
                /*此处加入商户系统的逻辑处理（例如判断金额，判断支付状态，更新订单状态等等）......*/
            } else {
                OSSetPayOrderStatus($cardType, '', $order_no, 2);      //修改订单状态为支付失败
            }
            OSSetPayOrderTransactionID($cardType, $outTradeNo, $order_no);
        }
        OSUnLockOrder($order_no);
    }
    die("SUCCESS");
}
die("ERROR");




