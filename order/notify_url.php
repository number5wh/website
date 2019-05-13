<?php
/*
 * 功能：支付宝服务器异步通知页面
 * 版本：3.3
 * 日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
 *
 *
 * ************************页面功能说明*************************
 * 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
 * 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
 * 该页面调试工具请使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyNotify
 * 如果没有收到该页面返回的 success 信息，支付宝会在24小时内按一定的时间策略重发通知
 */
require 'Include/Init.inc.php';
require_once ("Include/alipay.config.php");
require_once ("Common/Alipay/lib/alipay_notify.class.php");
require_once ROOT_PATH . 'Link/BuyHappyBean.php';
require_once ROOT_PATH . 'Link/BuyVIP.php';
require_once ROOT_PATH . 'Link/GetPayOrder.php';
require_once ROOT_PATH . 'Link/FindPayOrder.php';
require_once ROOT_PATH . 'Link/AddPayLogs.php';
require_once ROOT_PATH . 'Link/SetPayOrderStatus.php';
require_once ROOT_PATH . 'Link/SetPayOrderTransactionID.php';
require_once ROOT_PATH . 'Link/LockOrder.php';
require_once ROOT_PATH . 'Link/UnLockOrder.php';
// 计算得出通知验证结果

// $_POST = json_decode('{"discount":"0.00","extra_common_param":"831547_1_1","payment_type":"1","subject":"\u9ea6\u5e01","trade_no":"2015110921001004910055440017","buyer_email":"15892115342","gmt_create":"2015-11-09 22:32:34","notify_type":"trade_status_sync","quantity":"1","out_trade_no":"201511092231306037","seller_id":"2088021641653604","notify_time":"2015-11-09 22:32:40","trade_status":"TRADE_SUCCESS","is_total_fee_adjust":"N","total_fee":"50.00","gmt_payment":"2015-11-09 22:32:40","seller_email":"2914776789@qq.com","price":"50.00","buyer_id":"2088302453945911","notify_id":"41162e442b8ddba3912f1f61d375d6dn0s","use_coupon":"N","sign_type":"MD5","sign":"788c74fe1207bd440e67e734cbc14acc"}');
/*
 * $_POST = json_decode('{"buyer_email":"634771197@qq.com","buyer_id":"2088802252422560","exterface":"create_direct_pay_by_user","extra_common_param":"60009_1_1","is_success":"T","notify_id":"RqPnCoPT3K9%2Fvwbh3InVZ3nuYmZ1E1zIWIC3Tw0mmAy3Djw0d6Qyv7QcKfaKWOy7A4zz","notify_time":"2015-12-03 16:18:57","notify_type":"trade_status_sync","out_trade_no":"201512031608117643","payment_type":"1","seller_email":"2914776789@qq.com","seller_id":"2088021641653604","subject":"\u9ea6\u5e01","total_fee":"0.01","trade_no":"2015120321001004560278305561","trade_status":"TRADE_SUCCESS","sign":"024c29e817a9d4e5adc21eea76f3fa3c","sign_type":"MD5"}');
 * $_POST = (array)$_POST;
 */

$arrConfig = unserialize(SYS_CONFIG);

$alipayNotify = new AlipayNotify ( $alipay_config );
$verify_result = $alipayNotify->verifyNotify ();

/*  打印日志         */
$file_name =basename($_SERVER['PHP_SELF'],'.php');
Utility::Log($file_name, 'receive_info', json_encode($_REQUEST));

if ($verify_result) { // 验证成功
                     // ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                     // 请在这里加上商户的业务逻辑程序代
                     
	// ——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
                     
	// 获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
                     
	// 商户订单号
	
	$out_trade_no = $_POST ['out_trade_no'];
	
	// 支付宝交易号
	
	$trade_no = $_POST ['trade_no'];
	
	// 交易状态
	$trade_status = $_POST ['trade_status'];
	
	$total_fee = $_POST ['total_fee'];
	
	$result_code = $_POST ['trade_status'] == 'TRADE_SUCCESS' ? 0 : 1;
	
	$seller_email = $_POST ['seller_email'];
	$CardType = 1; // 支付宝支付
	$order = OSGetPayOrder ( $out_trade_no, $CardType );
	OSAddPayLogs ( $result_code, '', date ( 'Ymd', time () ), $seller_email, $trade_no, $out_trade_no, ( float ) $total_fee, '', $CardType, $order ['iLoginID'] );
	if ($_POST ['trade_status'] == 'TRADE_FINISHED') {
		
		// 判断该笔订单是否在商户网站中已经做过处理
		// 如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
		// 如果有做过处理，不执行商户的业务程序
		
		// 注意：
		// 退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
		
		// 调试用，写文本函数记录程序运行情况是否正常
		// logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
	} else if ($_POST ['trade_status'] == 'TRADE_SUCCESS') {
		// 判断该笔订单是否在商户网站中已经做过处理
		// 如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
		// 如果有做过处理，不执行商户的业务程序
		if (OSLockOrder ( $CardType, $out_trade_no )['iResult'] == 0) {
			if (OSFindPayOrder ( $CardType, '', $out_trade_no, 0 )['iResult'] != 0) {
				// 这笔订单已经处理过了
			} else {
				
				OSSetPayOrderStatus ( $CardType, '', $out_trade_no, 1 );
				if ($order ['iPayType'] == 1) { // 通易币
					$result = DCBuyHappyBean ( $order ['iLoginID'], floor ( $total_fee * 100 ), 1 );
				} else { // 黄钻会员
					$result = DCBuyVIP ( $order ['iLoginID'], floor ( $total_fee /$arrConfig['VipPrice'])*$arrConfig['VipDays'], 1 );
				}
				if ($result ['iResult'] == 0) {
					OSSetPayOrderStatus ( $CardType, '', $out_trade_no, 3 );
				} else {
				    Utility::Log($file_name, 'error_info', json_encode($result));
					OSSetPayOrderStatus ( $CardType, '', $out_trade_no, 4 );
				}
				OSSetPayOrderTransactionID ( $CardType, $trade_no, $out_trade_no );
			}
			OSUnLockOrder ( $out_trade_no );
		}
		// 注意：
		// 付款完成后，支付宝系统发送该交易状态通知
		
		// 调试用，写文本函数记录程序运行情况是否正常
		// logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
	} else if ($_POST ['trade_status'] == 'TRADE_CLOSED') {
		OSSetPayOrderStatus ( $CardType, '', $out_trade_no, 2 );
	}
	
	// ——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
	
	ob_clean ();
	echo "success"; // 请不要修改或删除
		                
	// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
} else {
	// 验证失败
	
	ob_clean ();
	echo "fail";
	
	// 调试用，写文本函数记录程序运行情况是否正常
	// logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
}
?>