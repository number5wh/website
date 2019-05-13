<?php
require 'Include/Init.inc.php';
require_once ROOT_PATH . 'Common/wxpay/lib/WxPay.Notify.php';
require_once ROOT_PATH . 'Common/wxpay/lib/WxPay.Api.php';
require_once 'log.php';
require_once ROOT_PATH . 'Link/BuyHappyBean.php';
require_once ROOT_PATH . 'Link/BuyVIP.php';
require_once ROOT_PATH . 'Link/AddPayLogs.php';
require_once ROOT_PATH . 'Link/FindPayOrder.php';
require_once ROOT_PATH . 'Link/SetPayOrderStatus.php';
require_once ROOT_PATH . 'Link/SetPayOrderTransactionID.php';
require_once ROOT_PATH . 'Link/LockOrder.php';
require_once ROOT_PATH . 'Link/UnLockOrder.php';

ini_set('date.timezone', 'Asia/Shanghai');
error_reporting(E_ERROR);

// 初始化日志

$log_path = dirname($_SERVER["DOCUMENT_ROOT"]) . "/logs/notify/";
if (!file_exists($log_path)) {
	mkdir($log_path, 0777, true);
}

$logHandler = new CLogFileHandler($log_path . date('Y-m-d') . ".txt");
$log = Log::Init($logHandler, 15);
class PayNotifyCallBack extends WxPayNotify {
	// 查询订单
	public function Queryorder($transaction_id) {
		Log::DEBUG("Queryorder transaction_id:" . $transaction_id);
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
		Log::DEBUG("query:" . json_encode($result));
		if (array_key_exists("return_code", $result) && array_key_exists("result_code", $result) && $result["return_code"] == "SUCCESS" && $result["result_code"] == "SUCCESS") {
			return true;
		}
		return false;
	}

	// 重写回调处理函数
	public function NotifyProcess($data, &$msg) {
		Log::DEBUG("NotifyProcess");
		$arrConfig = unserialize(SYS_CONFIG);
		Log::DEBUG("call back:" . json_encode($data));
		$notfiyOutput = array();

		if (!array_key_exists("transaction_id", $data)) {
			$msg = "输入参数不正确";
			return false;
		}
		// 查询订单，判断订单真实性
		if (!$this->Queryorder($data["transaction_id"])) {
			$msg = "订单查询失败";
			return false;
		}
		$data = (array) $data;
		$CardType = 2; // 微信支付
		$ret = explode('^', $data['attach']);
		$result_code = $data['result_code'] == 'SUCCESS' ? 0 : 1;
		OSAddPayLogs($result_code, '', date('Ymd', time()), $data['appid'], $data['transaction_id'], $data['out_trade_no'], (float) $data['total_fee'] / 100.0, '', $CardType, $ret[0]);

		if ($result_code == 0) {
			if (OSLockOrder($CardType, $data['out_trade_no'])['iResult'] == 0) {
				if (OSFindPayOrder($CardType, '', $data['out_trade_no'], 0)['iResult'] != 0) {
					// 这笔订单已经处理过了
				} else {
					OSSetPayOrderStatus($CardType, '', $data['out_trade_no'], 1);
					if ($ret[1] == 1) {
						// 通易币
						$result = DCBuyHappyBean($ret[0], floor($data['total_fee']), $ret[2]);
					} else {
						// 黄钻会员
						$result = DCBuyVIP($ret[0], floor($data['total_fee'] / 100 / $arrConfig['VipPrice']) * $arrConfig['VipDays'], $ret[2]);
					}
					if ($result['iResult'] == 0) {
						OSSetPayOrderStatus($CardType, '', $data['out_trade_no'], 3);
					} else {
						Log::DEBUG(date('Y-m-d H:i:s', time()) . ' error_info : ' . json_encode($result) . "\r\n");
						OSSetPayOrderStatus($CardType, '', $data['out_trade_no'], 4);
					}
					OSSetPayOrderTransactionID($CardType, $data['transaction_id'], $data['out_trade_no']);
				}
				OSUnLockOrder($data['out_trade_no']);
			}
		} else {
			OSSetPayOrderStatus($CardType, '', $data['out_trade_no'], 2);
		}
		return true;
	}
}

Log::DEBUG("begin notify");
$notify = new PayNotifyCallBack();
$notify->Handle(false);
