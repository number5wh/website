<?php
header("Content-type: text/html; charset=utf-8");
/*
 * Created on 2015-9-1
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
// require_once ("base.php");
// require_once ("config.php");


require 'Include/Init.inc.php';
require_once ROOT_PATH . 'Include/jtpayconfig.php';
require_once ROOT_PATH . 'Link/GetPayOrder.php';
require_once ROOT_PATH . 'Link/BuyHappyBean.php';
require_once ROOT_PATH . 'Link/BuyVIP.php';
require_once ROOT_PATH . 'Link/FindPayOrder.php';
require_once ROOT_PATH . 'Link/AddPayLogs.php';
require_once ROOT_PATH . 'Link/SetPayOrderStatus.php';
require_once ROOT_PATH . 'Link/SetPayOrderTransactionID.php';
require_once ROOT_PATH . 'Link/LockOrder.php';
require_once ROOT_PATH . 'Link/UnLockOrder.php';

$file_name =basename($_SERVER['PHP_SELF'],'.php');
Utility::Log($file_name, 'receive_info', json_encode($_REQUEST));


// Utility::Log("app_jtpay_notify", 'receive_info', json_encode($_REQUEST));
// receive_info:{"p1_usercode":"10208627","p2_order":"20161116153224447049","p3_money":"0.01","p4_status":"1","p5_jtpayorder":"20161116153226305634","p6_paymethod":"10","p8_charset":"UTF-8","p9_signtype":"MD5","p11_remark":"800001^1^35","p10_sign":"68A2C6B3AB8A0562784578A806C66240"}

$signmsg=SIGN_KEY;
$md5info_paramet = $_REQUEST['p1_usercode']."&".$_REQUEST['p2_order']."&".$_REQUEST['p3_money']."&".$_REQUEST['p4_status']."&".$_REQUEST['p5_jtpayorder']."&".$_REQUEST['p6_paymethod']."&".$_REQUEST['p7_paychannelnum']."&".$_REQUEST['p8_charset']."&".$_REQUEST['p9_signtype']."&".$signmsg;
$md5info_tem= strtoupper(md5($md5info_paramet));

$requestsign=$_REQUEST['p10_sign'];
$remark=$_REQUEST['p11_remark'];
// echo "sign begin\n";
if ($md5info_tem == $_REQUEST['p10_sign'])
{
	//$remark = iconv("GB2312","UTF-8//IGNORE",urldecode($remark));//签名验证中的中文采用UTF-8编码;
    //改变订单状态，及其他业务修改
   //print "<script language=\"JavaScript\">alert(\"订单充值成功，进行业务操作\");</script>"; 

   //echo "success";
	// echo "sign ok\n";

	$ret = explode('^',$remark);

	$cardType = $ret[2];

	if ($cardType == 42 || $cardType == 43) {
		$appuserid  = $_REQUEST['p1_usercode'];
		$cporderid = $_REQUEST['p2_order'];
		$money = $_REQUEST['p3_money'];
		$transid = $_REQUEST['p5_jtpayorder'];




		$result_code = $_REQUEST ['p4_status'] == '1' ? 0 : 1;
		OSAddPayLogs ( $result_code, '', date ( 'Ymd', time () ), $appuserid, $transid, $cporderid, ( float ) $money, '', $cardType, $ret [0]  );	


		$agent_bill_id = $cporderid;

		if ($result_code == 0) { //success
			if (OSLockOrder ( $cardType, $agent_bill_id )['iResult'] == 0) {
				if (OSFindPayOrder ( $cardType, '', $agent_bill_id, 0 )['iResult'] != 0) {
					Utility::Log($file_name, 'log_info', "这笔订单已经处理过了或者不存在");
					// 这笔订单已经处理过了
				} else {
					OSSetPayOrderStatus ( $cardType, '', $agent_bill_id, 1 );
					if ($ret [1] == 1) { // 通易币
						$result = DCBuyHappyBean ( $ret [0], floor ( $money*100), $cardType );
					} else { // 黄钻会员
						$result = DCBuyVIP ( $ret [0], floor ( $money / $arrConfig['VipPrice'])*$arrConfig['VipDays'], $cardType );
					}
					if ($result ['iResult'] == 0) {
						Utility::Log($file_name, 'error_info', "充值成功");
						OSSetPayOrderStatus ( $cardType, '', $agent_bill_id, 3 );
						OSSetPayOrderTransactionID ( $cardType, $transid, $agent_bill_id );
					} else {
						// Log::DEBUG ( date ( 'Y-m-d H:i:s', time () ) . ' error_info : ' . json_encode ( $result ) . "\r\n" );
						Utility::Log($file_name, 'error_info', "充值失败:".json_encode($result));
						OSSetPayOrderStatus ( $cardType, '', $agent_bill_id, 4 );
						OSSetPayOrderTransactionID ( $cardType, $transid, $agent_bill_id );
					}
				}
				OSUnLockOrder ( $agent_bill_id );
			}
		} else {
			OSSetPayOrderStatus ( $cardType, '', $agent_bill_id, 2 );
		}
		
	    echo 'success'."\n";

	}


 }
  


?>
