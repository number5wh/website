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

$returnArray = array( // 返回字段
                      "memberid"       => $_REQUEST["memberid"], // 商户ID
                      "orderid"        => $_REQUEST["orderid"], // 订单号
                      "amount"         => $_REQUEST["amount"], // 交易金额
                      "datetime"       => $_REQUEST["datetime"], // 交易时间
                      "transaction_id" => $_REQUEST["transaction_id"], // 支付流水号
                      "returncode"     => $_REQUEST["returncode"],
);
$md5key      = "q2ctw2naqemdfvmzgloz054xerk0o2wo";
ksort($returnArray);
reset($returnArray);
$md5str = "";
foreach ($returnArray as $key => $val) {
    if ($val != '') {
        $md5str .= $key . "=" . $val . "&";
    }
}
$checksign = strtoupper(md5($md5str . "key=" . $md5key));


$merId = $returnArray["memberid"];
$order_no = $returnArray["orderid"];
$outTradeNo = $returnArray['transaction_id'];
$amount = $returnArray["amount"];
$tradeState = $returnArray["returncode"];
$tradetime = $returnArray["datetime"];
$sign = $_REQUEST["sign"];


$cardType = 1300;
$result_code = $tradeState == '00' ? 0 : 1;

$order = OSGetPayOrder ($order_no, $cardType);
Utility::Log($file_name, '订单信息：', json_encode($order));
//file_put_contents("./param".date('H:i:s').rand(0,100).".txt",$order);
OSAddPayLogs ($result_code, '', date ('Ymd', time ()), $merId, $outTradeNo, $order_no, (float) ($amount*100), '', $cardType, $order ['iLoginID'] );

if ($checksign !== $sign) {
    echo "Signature error";
    Utility::Log($file_name, '签名失败：', 'checksign:'.$checkSign.' sign:'.$sign);
} else {
    //处理业务逻辑
    if(OSLockOrder($cardType, $order_no)['iResult']==0){
        if(OSFindPayOrder($cardType,'',$order_no,0)['iResult'] != 0){   //这笔订单已经处理过了
            $order = OSGetPayOrder($order_no, $cardType);
            /* 如果pay_result大于0则表示支付失败 */
        }else{
            if ($result_code == 0)
            {
                if(OSFindPayOrder($cardType,'',$order_no,1)['iResult'] == 0){
                    //这笔订单已经支付成功过了
                }else{
                    OSSetPayOrderStatus($cardType,'',$order_no,1);
//                    if($order['iPayType']  == 1){ //欢乐豆
//                        $result = DCBuyHappyBean( $order ['iLoginID'], floor($amount*100),$cardType);   //充值欢乐豆
//                        Utility::Log($file_name, '购买欢乐豆：', json_encode($result));
//                    }
//                    else{//黄钻会员
//                        $result =  DCBuyVIP( $order ['iLoginID'], floor($amount/$arrConfig['VipPrice'])*$arrConfig['VipDays'],$cardType);
//                        Utility::Log($file_name, '购买黄专：', json_encode($result));
//                    }
                    $result = DCBuyHappyBean( $order ['iLoginID'], floor($amount*100),$cardType);   //充值欢乐豆
                    Utility::Log($file_name, '购买欢乐豆：', json_encode($result));
                    if($result['iResult']==0){
                        OSSetPayOrderStatus($cardType,'',$order_no,3);   //修改订单处理状态为处理成功
                    }else{
                        Utility::Log($file_name, 'error_info', json_encode($result));
                        OSSetPayOrderStatus($cardType,'',$order_no,4);    //修改订单处理状态为处理失败
                    }
                }
                /*此处加入商户系统的逻辑处理（例如判断金额，判断支付状态，更新订单状态等等）......*/
            }else{
                OSSetPayOrderStatus($cardType,'',$order_no,2);      //修改订单状态为支付失败
            }
            OSSetPayOrderTransactionID ( $cardType, $outTradeNo, $order_no );
        }
        OSUnLockOrder($order_no);
    }
    exit("OK");
}


