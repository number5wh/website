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

$payOrderId = $_POST["payOrderId"];
$amount = $_POST["amount"];
$mchId = $_POST["mchId"];
$productId = $_POST["productId"];
$mchOrderNo = $_POST["mchOrderNo"];
$paySuccTime = $_POST["paySuccTime"];
$sign = $_POST["sign"];
$channelOrderNo = $_POST["channelOrderNo"];
$backType = $_POST["backType"];
$param1 = $_POST["param1"];
$param2 = $_POST["param2"];
$appId = $_POST["appId"];
$channelAttach = $_POST["channelAttach"];
$status = $_POST["status"];

if (!empty($payOrderId)
    && !empty($amount)
    && !empty($mchId)
    && !empty($mchOrderNo)
    && !empty($sign)
    && !empty($appId)
)
{

    if ($appId == "4f992ae2e2054eee8702b3ae1407dc28"){

        $signdata = array(
            "payOrderId" => $payOrderId,
            "amount" => $amount,
            "mchId" => $mchId,
            "appId" => $appId,
            "productId" => $productId,
            "mchOrderNo" => $mchOrderNo,
            "paySuccTime" => $paySuccTime,
            "backType" => $backType,
            "status" => $status
        );

        if (!empty($param1)) {
            $signdata["param1"] = $param1;
        }

        if (!empty($param2)) {
            $signdata["param2"] = $param2;
        }

        if (!empty($channelAttach)) {
            $signdata["channelAttach"] = $channelAttach;
        }

        if (!empty($channelOrderNo)) {
            $signdata["channelOrderNo"] = $channelOrderNo;
        }

        $key = "2R7BIIWIJMGQY51W8XBX4AVMUROFUTYOTBSNPY2AGKBI1KD2RV7VZUEIPNT7N5VCMU89G0JUUCRZUFQZHDTH31DUJ7KNP1ACJF7VXMEL5OSK8NS9YTNM6FC5COWXHDQA";
        $newsign =Utility::MakeSign($signdata,$key);

        $order_no =$mchOrderNo;
        $trade_no = $payOrderId;
        $order_amount = $amount/100;
        $cardType = 1200;

        $result_code = $status == '2' ? 0 : 1;

        $order = OSGetPayOrder ($order_no, $cardType);
        Utility::Log($file_name, '订单信息：', json_encode($order));
//file_put_contents("./param".date('H:i:s').rand(0,100).".txt",$order);
        OSAddPayLogs ($result_code, '', date ('Ymd', time ()), $mchId, $trade_no, $mchOrderNo, (float) $order_amount, '', $CardType, $order ['iLoginID'] );

        if ($sign == $newsign && $status == "2")
        {

            //业务处理
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

                            $result = DCBuyHappyBean( $order ['iLoginID'], floor($order_amount*100),$cardType);   //充值欢乐豆
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
                    OSSetPayOrderTransactionID ( $cardType, $trade_no, $order_no );
                }
                OSUnLockOrder($order_no);
            }
            //处理业务逻辑
            echo("success");
        }
        else
        {
            echo("faild");
        }

    }
    else{

        echo  "appid error";
    }

}
else
{
    echo "参数错误";

}