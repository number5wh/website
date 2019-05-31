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

$file_name = basename($_SERVER['PHP_SELF'], '.php');



//验证sign

require_once ROOT_PATH.'Common/longfapay/util.php';
$Rsa = new Rsa();

$check = [
    'data' => 'EymcjR0Ey8tghVIqOSSDaJ%2FuWQ084k%2BV0QpNkJT5%2F4BWFa7Yi2e4wik%2F5xIpi5WkjuftvQxltke675SnA%2FeYosKIz4VR3cdoyuG8SSd4WgSmVYX%2BwZqOmWHvuSwo7zbzVReoM4R6ctqSTSyFjmvAThT3wSQclBrd%2BOTdF4Vw3k8jme00I2v3CpUuuFrYv3i32tXeMYK09rVkmM0J2XBGkGXgsgfSNad%2F4DcgOAc%2BJl6B2K4qvn5C4QgByGLCsMfjc%2FYfv1cGr9AQejS%2FDLLaxmY2FrF6O3YGvdX7FhCmdThWQbo0ITUEds5kmMQ%2BEpfOlrIXJl%2FWpnxUhCRkhaA1aA%3D%3D',
    'orderNo' => '20190528170850205624',
    'merchNo' => 'LFP201905251124'
];
$data = $check['data'];

$data = urldecode($data);
$data = $Rsa->decode($data);

$rows = callback_to_array($data,'9FD2F618F9CAC2A8A3023F18170C6C3B');
$order_no   = $rows["orderNo"];
$outTradeNo = 'FZ'.$order_no;

$amount     = $rows["amount"];
$sign       = $rows["sign"];
$merNo       = $rows["merchNo"];
$status = $rows['payStateCode'];

$cardType    = 1200;
$result_code = $status == '00' ? 0 : 1;
$order = OSGetPayOrder($order_no, $cardType);
//var_dump($order);
//die;

Utility::Log($file_name, '订单信息：', json_encode($order));
//file_put_contents("./param".date('H:i:s').rand(0,100).".txt",$order);
OSAddPayLogs($result_code, '', date('Ymd', time()), $merNo, $outTradeNo, $order_no, (float)($amount), '', $cardType, $order ['iLoginID']);
//echo $checkSign;
if ($rows['payStateCode'] == '99') {
    echo 'wrong sign'; //签名不对
    Utility::Log($file_name, '签名失败：', 'checksign:'.$checkSign.' sign:'.$sign);
} else {
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
					 $result = DCBuyHappyBean($order ['iLoginID'], floor($amount), $cardType);   //充值欢乐豆 单位分
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
    echo 'SUCCESS';
}


