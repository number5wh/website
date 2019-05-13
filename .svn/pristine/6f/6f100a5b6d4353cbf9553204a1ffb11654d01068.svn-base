<? header("content-Type: text/html; charset=UTF-8");?>
<?php
/* *
 *功能：扫码支付异步通知接口
 *版本：3.0
 *日期：2017-06-30
 *说明：
 *以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,
 *并非一定要使用该代码。该代码仅供学习和研究接口使用，仅为提供一个参考。
 **/

//////////////////////////	接收返回通知数据  /////////////////////////////////
/**
获取订单支付成功之后，通知服务器以post方式返回来的订单通知数据，参数详情请看接口文档,
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

$merchant_code	= $_POST["merchant_code"];
$notify_type = $_POST["notify_type"];
$notify_id = $_POST["notify_id"];
$interface_version = $_POST["interface_version"];
$sign_type = $_POST["sign_type"];
$dinpaySign = base64_decode($_POST["sign"]);
$order_no = $_POST["order_no"];
$order_time = $_POST["order_time"];
$order_amount = $_POST["order_amount"];
$extra_return_param = $_POST["extra_return_param"];
$trade_no = $_POST["trade_no"];
$trade_time = $_POST["trade_time"];
$trade_status = $_POST["trade_status"];
$bank_seq_no = $_POST["bank_seq_no"];



if($merchant_code=='' || $order_no=='' || $trade_no==''){
    echo "Parameter Error";
    exit();
}

/////////////////////////////   参数组装  /////////////////////////////////
/**
除了sign_type dinpaySign参数，其他非空参数都要参与组装，组装顺序是按照a~z的顺序，下划线"_"优先于字母
 */
$signStr = "";

if($bank_seq_no != ""){
    $signStr = $signStr."bank_seq_no=".$bank_seq_no."&";
}

if($extra_return_param != ""){
    $signStr = $signStr."extra_return_param=".$extra_return_param."&";
}
$signStr = $signStr."interface_version=".$interface_version."&";
$signStr = $signStr."merchant_code=".$merchant_code."&";
$signStr = $signStr."notify_id=".$notify_id."&";
$signStr = $signStr."notify_type=".$notify_type."&";
$signStr = $signStr."order_amount=".$order_amount."&";
$signStr = $signStr."order_no=".$order_no."&";
$signStr = $signStr."order_time=".$order_time."&";
$signStr = $signStr."trade_no=".$trade_no."&";
$signStr = $signStr."trade_status=".$trade_status."&";
$signStr = $signStr."trade_time=".$trade_time;

/////////////////////////////   RSA-S验签  /////////////////////////////////
$cardType = 40;

$result_code = $trade_status == 'SUCCESS' ? 0 : 1;

$order = OSGetPayOrder ($order_no, $cardType);
Utility::Log($file_name, '订单信息：', json_encode($order));
//file_put_contents("./param".date('H:i:s').rand(0,100).".txt",$order);
OSAddPayLogs ($result_code, '', date ('Ymd', time () ), $merchant_code, $trade_no, $order_no, (float) $order_amount, '', $CardType, $order ['iLoginID'] );


$ddbill_public_key = openssl_get_publickey($ddbill_public_key);
$flag = openssl_verify($signStr,$dinpaySign,$ddbill_public_key,OPENSSL_ALGO_MD5);
//file_put_contents("./param".date('H:i:s').rand(0,100).".txt",$flag);
//////////////////////   异步通知必须响应“SUCCESS” /////////////////////////
/**
如果验签返回ture就响应SUCCESS,并处理业务逻辑，如果返回false，则终止业务逻辑。
 */


if($flag){
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
                    if($order['iPayType']  == 1){ //欢乐豆
                        $result = DCBuyHappyBean( $order ['iLoginID'], floor($order_amount*100),$cardType);   //充值欢乐豆
                        Utility::Log($file_name, '购买欢乐豆：', json_encode($result));
                    }
                    else{//黄钻会员
                        $result =  DCBuyVIP( $order ['iLoginID'], floor($order_amount/$arrConfig['VipPrice'])*$arrConfig['VipDays'],$cardType);
                        Utility::Log($file_name, '购买黄专：', json_encode($result));
                    }

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
    echo "SUCCESS";

}else{
    echo "Signature error";
}
?>