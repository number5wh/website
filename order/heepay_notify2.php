<?php
/*
 * 异步通知处理页面
 */

require 'Include/Init.inc.php';
require_once ROOT_PATH.'Link/BuyHappyBean.php';
require_once ROOT_PATH.'Link/BuyVIP.php';
require_once ROOT_PATH.'Link/AddPayLogs.php';
require_once ROOT_PATH.'Link/FindPayOrder.php';
require_once ROOT_PATH.'Link/GetPayOrder.php';
require_once ROOT_PATH.'Link/SetPayOrderStatus.php';
require_once ROOT_PATH.'Link/UpdatePayConfig.php';
require_once ROOT_PATH.'Link/LockOrder.php';
require_once ROOT_PATH.'Link/UnLockOrder.php';
require_once ROOT_PATH . 'Link/SetPayOrderTransactionID.php';
require 'Include\heepayconfig.php';

$arrConfig = unserialize(SYS_CONFIG);

    /*  打印日志         */
    $file_name =basename($_SERVER['PHP_SELF'],'.php');
    Utility::Log($file_name, 'receive_info', json_encode($_REQUEST));
    
	$result = $_GET['result'];
	$pay_message = $_GET['pay_message'];
	$agent_id = $_GET['agent_id'];
	$jnet_bill_no = $_GET['jnet_bill_no'];
	$agent_bill_id = $_GET['agent_bill_id'];
	$pay_type = $_GET['pay_type'];
	$pay_amt = $_GET['pay_amt'];
	$remark = $_GET['remark'];
	$return_sign=$_GET['sign'];
	//$cardType = $pay_type==30?36:38;// 汇付宝微信支付
	//$cardType = $pay_type==30?51:50;
	$remark = iconv("GB2312","UTF-8//IGNORE",urldecode($remark));//签名验证中的中文采用UTF-8编码;

	$ret = explode('^',$remark);

	$cardType = $ret[2];
	OSAddPayLogs ( $result, '', date ( 'Ymd', time () ), $agent_id, $jnet_bill_no, $agent_bill_id, ( float ) $pay_amt, '', $cardType,$ret[0] );
	$signStr='';
	$signStr  = $signStr . 'result=' . $result;
	$signStr  = $signStr . '&agent_id=' . $agent_id;
	$signStr  = $signStr . '&jnet_bill_no=' . $jnet_bill_no;
	$signStr  = $signStr . '&agent_bill_id=' . $agent_bill_id;
	$signStr  = $signStr . '&pay_type=' . $pay_type;
	
	$signStr  = $signStr . '&pay_amt=' . $pay_amt;
	$signStr  = $signStr .  '&remark=' . $remark;
	
	$signStr = $signStr . '&key=' . ALI_SIGN_KEY; //商户签名密钥
	
	$sign='';
	$sign=md5($signStr);
	if($sign==$return_sign){   //比较签名密钥结果是否一致，一致则保证了数据的一致性
	    if(OSLockOrder($cardType, $agent_bill_id)['iResult']==0){
	        if(OSFindPayOrder($cardType,'',$agent_bill_id,0)['iResult'] != 0){   //这笔订单已经处理过了
	            $order = OSGetPayOrder($agent_bill_id, $cardType);
	            /* 如果pay_result大于0则表示支付失败 */
	        }else{
	            if ($result == 1)
	            {
				    if(OSFindPayOrder($cardType,'',$agent_bill_id,1)['iResult'] == 0){
				        //这笔订单已经支付成功过了
				    }else{
				        OSSetPayOrderStatus($cardType,'',$agent_bill_id,1);
				        if($ret[1] == 1){ //欢乐豆
				            $result = DCBuyHappyBean($ret[0], floor($pay_amt*100),$cardType);   //充值欢乐豆
				        }
				        else{//黄钻会员
				            $result =  DCBuyVIP($ret[0], floor($pay_amt/$arrConfig['VipPrice'])*$arrConfig['VipDays'],$cardType);      //充值黄钻会员
				        }
				        if($result['iResult']==0){
				            OSSetPayOrderStatus($cardType,'',$agent_bill_id,3);   //修改订单处理状态为处理成功
				        }else{
				            Utility::Log($file_name, 'error_info', json_encode($result));
				            OSSetPayOrderStatus($cardType,'',$agent_bill_id,4);    //修改订单处理状态为处理失败
				        }
				    }
	            				/*此处加入商户系统的逻辑处理（例如判断金额，判断支付状态，更新订单状态等等）......*/
	            }else{
	                OSSetPayOrderStatus($cardType,'',$agent_bill_id,2);      //修改订单状态为支付失败
	            }
	            OSSetPayOrderTransactionID ( $cardType, $jnet_bill_no, $agent_bill_id );
	        }
	        OSUnLockOrder($agent_bill_id);
	    }
		echo 'ok';
		//商户自行处理自己的业务逻辑
	}
	else{
		echo 'error';
		//商户自行处理，可通过查询接口更新订单状态，也可以通过商户后台自行补发通知，或者反馈运营人工补发
	}
	
?>