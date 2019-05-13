<?php

require 'Include/Init.inc.php';
require_once ROOT_PATH . 'Link/CreatePayOrder.php';
require_once ROOT_PATH . 'Link/GetPayOrderID.php';
require_once ROOT_PATH . 'Link/CheckAccount.php';
require_once ROOT_PATH . 'Link/GetAccountInfoByID.php';
require_once ROOT_PATH . 'Common/Session.class.php';
require_once ROOT_PATH . 'Include/b780config.php';

class encrypt {
	private $arrConfig;

	public function __construct() {
		$this->arrConfig = unserialize(SYS_CONFIG);
	}
	/***
		     *
		     * 参数检验
		     *
		     * @return number
		     *
	*/
	private function chkParam() {
		$objSession = new Session($this->arrConfig['Session']['SessionLoginName']);
		$CheckCode = Utility::isNullOrEmpty('CheckCode', $_REQUEST);
		$cardType = Utility::isNullOrEmpty('cardType', $_REQUEST);
		$ChkCode = $objSession->get($this->arrConfig['SessionInfo']['ChkCode']);
		$Amount = Utility::isNullOrEmpty('Amount', $_REQUEST);
		if ($ChkCode !== $CheckCode) {
			$return['iResult'] = -1; //验证码不正确
			return $return;
		} else if ($Amount < 0.01 || $Amount > 2000) {
			$return['iResult'] = -5; //金额不正确
			return $return;
		} else {
			$LoginType = Utility::isNullOrEmpty('LoginType', $_REQUEST);
			$LoginID = Utility::isNullOrEmpty('LoginID', $_REQUEST);
			$LoginCode = Utility::isNullOrEmpty('LoginCode', $_REQUEST);
			if ($LoginType == 1) {
				// 账号登陆
				// if(preg_match('/^[a-zA-Z]{1}([a-zA-Z0-9_]){3,15}$/',$LoginCode)==false&&preg_match('/^(13|14|15|17|18)\d{9}$/',$LoginCode)==false){
				//     $return['iResult'] = -1001;   //账号格式错误
				//     return $return;
				// }
				$ret = ASCheckAccount($LoginCode);
				if ($ret['iResult'] == 0) {
					$return['iResult'] = -2; //账号不存在
					return $return;
				}
				$ret['szLoginName'] = $LoginCode;
			} else {
				//用户编号登陆
				if ($LoginID < 60000 || $LoginID >= 100000000) {
					$return['iResult'] = -1002; //用户编号不正确
					return $return;
				}
				$ret = ASGetAccountInfoByID($LoginID);
				if ($ret['szLoginName'] == '') {
					$return['iResult'] = -1002; //用户编号不存在
					return $return;
				}
				$ret['iResult'] = $LoginID;
			}
			$Rnd = OSGetPayOrderID('');
			if ($Rnd['iResult'] != 0) {
				$return['iResult'] = -3; //生成订单号失败
				return $return;

			} else {
				$objDataCenter = new DataCenterBLL();
				$CardID = $objDataCenter->getCardID($cardType);
				if ($CardID == -1) {
					$return['iResult'] = -4; //充值方式错误
					return $return;
				}
				$return['iLoginID'] = $ret['iResult'];
				$return['szLoginName'] = $ret['szLoginName'];
				$return['Rnd'] = $Rnd['szOrderNo'];
				$return['CardID'] = $CardID;
				$return['iResult'] = 0;
				return $return;
			}
		}
	}

	//此为H5 和PC 版本调收银台时需要的参数组装函数   特别提醒的是  下面的函数中有  $h5url 和$pcurl 两个url地址。 只需要更换这两个地址就可以 调出 H5 收银台和PC版本收银台。
	function H5orPCpay() {
		global $h5url, $pcurl, $appkey, $platpkey, $transid; //得到transid 再次组装数据并签名。
		echo "开始组装号调用支付接口的参数";
		//下单接口
		$orderReq['transid'] = "$transid";
		$orderReq['redirecturl'] = 'http://test/index.php';
		$orderReq['cpurl'] = 'aaa';
		//组装请求报文   对数据签名
		$reqData = composeReq($orderReq, $appkey);

		echo "参数组装完成：请用浏览器访问该链接:$h5url$reqData\n"; //这里组装的最终数据 就可以用浏览器访问调出收银台。
		echo "<script language=\"javascript\">";
		echo "location.href=\"$h5url$reqData\""; //我们的常连接版本 有PC 版本 和移动版本。 根据使用的环境不同请更换相应的URL:$h5url,$pcurl.
		echo "</script>";
	}

	/**
	 * 爱贝
	 *
	 * */
	private function charge_iapppay($sp_billno, $LoginID, $CardID) {

		require_once ROOT_PATH . 'Include/iapppayconfig.php';
		require_once ROOT_PATH . 'Common/iapppay/base.php';
		global $h5url, $pcurl, $appkey, $platpkey, $transid; //得到transid 再次组装数据并签名。
		echo "开始组装号调用支付接口的参数";
		//下单接口
		$orderReq['transid'] = "$transid";
		$orderReq['redirecturl'] = 'http://test/index.php';
		$orderReq['cpurl'] = 'aaa';
		//组装请求报文   对数据签名
		$reqData = composeReq($orderReq, $appkey);

		echo "参数组装完成：请用浏览器访问该链接:$h5url$reqData\n"; //这里组装的最终数据 就可以用浏览器访问调出收银台。
		echo "<script language=\"javascript\">";
		echo "location.href=\"$h5url$reqData\""; //我们的常连接版本 有PC 版本 和移动版本。 根据使用的环境不同请更换相应的URL:$h5url,$pcurl.
		echo "</script>";
	}

	/**
	 * 俊付通
	 *
	 * */
	private function charge_jtpay($sp_billno, $LoginID, $CardID, $LoginName) {

		require_once ROOT_PATH . 'Include/jtpayconfig.php';

		//获取用户IP
		$user_ip = "";
		if (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$user_ip = $_SERVER['HTTP_CLIENT_IP'];
		} else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$user_ip = $_SERVER['REMOTE_ADDR'];
		}

		$user_ip = str_replace(".", "_", $user_ip);

		$total_fee = trim($_REQUEST['payType'] == 1) ? trim($_REQUEST['Amount']) : $this->arrConfig['VipPrice']; /* 总金额 */
		if ($this->arrConfig['TestMode']) //测试
		{
			$total_fee = 0.01;
		}

		$p1_usercode = AGENT_ID;
		$p2_order = $sp_billno;
		$p3_money = $total_fee;
		$p4_returnurl = $this->arrConfig['URL']['Order'] . "/jtpay_return.php";
		$p5_notifyurl = $this->arrConfig['URL']['Order'] . "/jtpay_notify.php";
		$p6_ordertime = date('YmdHis', time());
		$compkey = SIGN_KEY;

		$p7_sign = strtoupper(md5($p1_usercode . "&" . $p2_order . "&" . $p3_money . "&" . $p4_returnurl . "&" . $p5_notifyurl . "&" . $p6_ordertime . $compkey));

		$p24_remark = $LoginID . '^' . trim($_REQUEST['payType']) . '^' . $CardID; /* 商家数据包 */

		$parameter = array(
			"iResult" => 0,
			"pay_url" => "http://pay.jtpay.com/form/pay",
			"p1_usercode" => $p1_usercode,
			"p2_order" => $p2_order,
			"p3_money" => $p3_money,
			"p4_returnurl" => $p4_returnurl,
			"p5_notifyurl" => $p5_notifyurl,
			"p6_ordertime" => $p6_ordertime,
			"p7_sign" => $p7_sign,
			"p9_paymethod" => "3",
			"p14_customname" => $LoginName,
			"p17_customip" => $user_ip,
			"p24_remark" => $p24_remark,
			"p25_terminal" => 1,
			"p26_iswappay" => 1,

		);

		if ($CardID == 0) {
			//充值方式传上来传错了！
			$parameter['iResult'] = -4;
		} else if (trim($_REQUEST['payType']) == 2 && $CardID >= 3 && $CardID <= 14) {
			//充值黄钻并且充值方式为游戏点卡或者充值卡  ，不行！！！
			$parameter['iResult'] = -4;
		} else {
			$iResult = OSCreatePayOrder($CardID, trim($_REQUEST['payType']), '', $sp_billno, $total_fee * 100, $LoginID);
			if ($iResult['iResult'] != 0) {
				$parameter['iResult'] = -3;
			} else {
				$parameter['iResult'] = 0;
			}
		}

		echo json_encode($parameter);
	}
	/**
	 * 北网支付
	 *
	 * */
	private function charge_b780($sp_billno, $LoginID, $CardID) {
		$tenpay_conf = new b780config();

		$cmd_no = '1'; /* 业务代码 */
		$desc = 'OnlineRecharge'; /* 商品名 */
		$purchaserId = ''; //trim($_REQUEST['purchaserId']);             /* 卖家用户编号 */
		$datestr = date('Ymd'); /* 交易日期 */
		$remark = $LoginID . '^' . trim($_REQUEST['payType']) . '^' . $CardID; /* 商家数据包 */
		$total_fee = trim($_REQUEST['payType'] == 1) ? trim($_REQUEST['Amount']) : $this->arrConfig['VipPrice']; /* 总金额 */
		if ($this->arrConfig['TestMode']) //测试
		{
			$total_fee = 1;
		}

		$fee_type = trim($_REQUEST['cardType']); /* 支付方式  */
		$burden = ''; //trim($_REQUEST['burden']);                      /*谁负担手续费（默认为空）*/
		$ware_num = 1; //trim($_REQUEST['warenum']);                      /* 商品数量 */
		$store_id = $tenpay_conf->spid; /* 商户号 */
		$transaction_id = $tenpay_conf->getTransactionId(); /* 将商户号+商户订单号 (共30位)*/
		$key = $tenpay_conf->sp_key; /* 商户加密key */
		$return_url = $tenpay_conf->pay_returnurl; /* 返回的路径 */
		$pay_url = $tenpay_conf->pay_url; /* 返回的路径 */
		/* 数字签名 */
		$sign_text = "cmdno=" . $cmd_no . "&date=" . $datestr . "&purchaserId=" . $purchaserId . "&bargainorId=" . $store_id .
			"&transactionId=" . $transaction_id . "&sp_orderno=" . $sp_billno . "&totalFee=" . $total_fee .
			"&burden=" . $burden . "&returnUrl=" . $return_url . "&remark=" . $remark . "&key=" . $key;
		$sign = strtoupper(md5($sign_text));
		/* 交易参数 */
		$parameter = array(
			'return_url' => $tenpay_conf->pay_returnurl, /* 返回的路径 */
			'cmdno' => $cmd_no, // 业务代码, 财付通支付支付接口填  1
			'date' => $datestr, // 商户日期：如20051212
			'desc' => $desc, // 交易的商品名称
			'purchaserId' => $purchaserId, // 用户(买方)的财付通帐户,可以为空
			'bargainorId' => $store_id, // 商家的财付通商户号
			'transactionId' => $transaction_id, // 交易号(订单号)，由商户网站产生(建议顺序累加)
			'cardType' => $fee_type, // 支付卡类型
			'sp_orderno' => $sp_billno, // 商户系统内部的定单号,最多10位
			'totalFee' => $total_fee, // 订单金额
			'burden' => $burden, // 谁负担手续费（默认为空）
			'warenum' => $ware_num, // 商品数量
			'returnUrl' => $return_url, // 接收财付通返回结果的URL
			'remark' => $remark, // 用户自定义签名
			'sign' => $sign, // MD5签名

		);
		if ($CardID == 0) {
			//充值方式传上来传错了！
			$parameter['iResult'] = -4;
		} else if (trim($_REQUEST['payType']) == 2 && $CardID >= 3 && $CardID <= 14) {
			//充值黄钻并且充值方式为游戏点卡或者充值卡  ，不行！！！
			$parameter['iResult'] = -4;
		} else {
			$iResult = OSCreatePayOrder($CardID, trim($_REQUEST['payType']), $transaction_id, $sp_billno, $total_fee * 100, $LoginID);
			if ($iResult['iResult'] != 0) {
				$parameter['iResult'] = -3;
			} else {
				$parameter['iResult'] = 0;
			}
		}
		echo json_encode($parameter);
	}
	/***
		     *
		     * 汇付宝
		     *
		     *
	*/
	private function charge_heepay($sp_billno, $LoginID, $CardID) {
		require_once ROOT_PATH . 'Include/heepayconfig.php';

		//获取用户IP
		$user_ip = "";
		if (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$user_ip = $_SERVER['HTTP_CLIENT_IP'];
		} else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$user_ip = $_SERVER['REMOTE_ADDR'];
		}

		//获取项目URL
		$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$project_url = str_replace('post_action.php', '', $url);

		$version = 1; //当前接口版本号1
		$agent_id = AGENT_ID; //   商户编号
		$agent_bill_id = $sp_billno; //    商户系统内部的定单号（要保证唯一）。长度最长50字符
		$agent_bill_time = date('YmdHis', time());
		$pay_type = $_REQUEST['cardType'] == 36 ? 30 : 22; //微信支付代码30,int型  支付宝  22
		$pay_code = ""; //char型，空字符串
		$pay_amt = trim($_REQUEST['payType'] == 1) ? trim($_REQUEST['Amount']) : $this->arrConfig['VipPrice']; /* 总金额 */
		if ($this->arrConfig['TestMode']) //测试
		{
			$pay_amt = 0.5;
		}

		$notify_url = $this->arrConfig['URL']['Order'] . "/heepay_notify.php";
		$return_url = $this->arrConfig['URL']['Order'] . "/heepay_return.php"; //微信支付不涉及同步返回，此处可填写任意URL，没有实际使用
		$goods_name = $_REQUEST['payType'] == 1 ? "通易币" : '黄钻会员';
		$goods_name = urlencode($goods_name);
		$goods_num = 1;
		$goods_note = ''; //支付说明
		$remark = $LoginID . '^' . trim($_REQUEST['payType']) . '^' . $CardID; /* 商家数据包 */
		$wxpay_type = 0; // 0 扫码支付
		$sign_key = SIGN_KEY; //签名密钥，需要商户使用为自己的真实KEY
		/*************创建签名***************/
		$sign_str = '';
		$sign_str = $sign_str . 'version=' . $version;
		$sign_str = $sign_str . '&agent_id=' . $agent_id;
		$sign_str = $sign_str . '&agent_bill_id=' . $agent_bill_id;
		$sign_str = $sign_str . '&agent_bill_time=' . $agent_bill_time;
		$sign_str = $sign_str . '&pay_type=' . $pay_type;
		$sign_str = $sign_str . '&pay_amt=' . $pay_amt;
		$sign_str = $sign_str . '&notify_url=' . $notify_url;
		$sign_str = $sign_str . '&return_url=' . $return_url;
		$sign_str = $sign_str . '&user_ip=' . $user_ip;
		$sign_str = $sign_str . '&key=' . $sign_key;

		$sign = md5($sign_str); //签名值
		//构造要请求的参数数组，无需改动
		$parameter = array(
			"iResult" => 0,
			"version" => $version,
			"agent_id" => $agent_id,
			"agent_bill_id" => $agent_bill_id,
			"agent_bill_time" => $agent_bill_time,
			"pay_type" => $pay_type,
			"pay_code" => $pay_code,
			"pay_amt" => $pay_amt,
			"notify_url" => $notify_url,
			"return_url" => $return_url,
			"user_ip" => $user_ip,
			"goods_name" => $goods_name,
			"goods_num" => $goods_num,
			"goods_note" => $goods_note,
			"remark" => $remark,
			'pay_url' => PAY_URL,
			"sign" => $sign,
		);
		if ($CardID == 0) {
			//充值方式传上来传错了！
			$parameter['iResult'] = -4;
		} else if (trim($_REQUEST['payType']) == 2 && $CardID >= 3 && $CardID <= 14) {
			//充值黄钻并且充值方式为游戏点卡或者充值卡  ，不行！！！
			$parameter['iResult'] = -4;
		} else {
			$iResult = OSCreatePayOrder($CardID, trim($_REQUEST['payType']), '', $agent_bill_id, $pay_amt * 100, $LoginID);
			if ($iResult['iResult'] != 0) {
				$parameter['iResult'] = -3;
			} else {
				$parameter['iResult'] = 0;
			}
		}

		echo json_encode($parameter);
	}

	/**
	 * 支付宝支付
	 * @param $out_trade_no string 商户订单号
	 * @param $LoginCode 登陆账号
	 * @param $CardID int　 充值方式ID
	 *
	 * **/
	private function charge_alipay($out_trade_no, $LoginID, $CardID) {
		/*支付宝支付*/
		require_once "Include/alipay.config.php";
		require_once "Common/Alipay/lib/alipay_submit.class.php";

		/**************************请求参数**************************/
		//支付类型
		$payment_type = "1";
		//必填，不能修改
		//服务器异步通知页面路径
		$notify_url = $this->arrConfig['URL']['Order'] . "/notify_url.php";
		//需http://格式的完整路径，不能加?id=123这类自定义参数
		//页面跳转同步通知页面路径
		$return_url = $this->arrConfig['URL']['Order'] . "/return_url.php";
		//需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/
		//商户网站订单系统中唯一订单号，必填
		//订单名称
		$subject = $_REQUEST['payType'] == 1 ? '通易币' : '黄钻会员';
		//必填
		//付款金额
		$total_fee = trim($_REQUEST['payType'] == 1) ? trim($_REQUEST['Amount']) : $this->arrConfig['VipPrice']; /* 总金额 */
		if ($this->arrConfig['TestMode']) //测试
		{
			$total_fee = 0.01;
		}

		//必填
		//订单描述
		$body = ''; //$_POST['WIDbody'];
		//商品展示地址
		$show_url = ''; // $_POST['WIDshow_url'];
		//需以http://开头的完整路径，例如：http://www.商户网址.com/myorder.html

		//防钓鱼时间戳
		$anti_phishing_key = "";
		//若要使用请调用类文件submit中的query_timestamp函数
		//客户端的IP地址
		$exter_invoke_ip = "";
		//非局域网的外网IP地址，如：221.0.0.1

		/************************************************************/

		//构造要请求的参数数组，无需改动
		$parameter = array(
			"service" => "create_direct_pay_by_user",
			"partner" => trim($alipay_config['partner']),
			"seller_email" => trim($alipay_config['seller_email']),
			"payment_type" => $payment_type,
			"notify_url" => $notify_url,
			"return_url" => $return_url,
			"out_trade_no" => $out_trade_no,
			"subject" => $subject,
			"total_fee" => $total_fee,
			"body" => $body,
			"show_url" => $show_url,
			"anti_phishing_key" => $anti_phishing_key,
			"exter_invoke_ip" => $exter_invoke_ip,
			"_input_charset" => trim(strtolower($alipay_config['input_charset'])),
			"extra_common_param" => $LoginID . '_' . trim($_REQUEST['payType'] . '_' . $CardID),

		);

		//建立请求
		$alipaySubmit = new AlipaySubmit($alipay_config);

		$iResult = OSCreatePayOrder(1, trim($_REQUEST['payType']), '', $out_trade_no, $total_fee * 100, $LoginID);
		if ($iResult['iResult'] != 0) {
			$iResult = -3;
			$html_text = "";
		} else {
			$iResult = 0;
			$html_text = $alipaySubmit->buildRequestForm($parameter, "get", "确认");
		}
		echo json_encode(['iResult' => $iResult, 'html' => $html_text]);
	}
	/**
	 * 微信支付
	 *
	 * */
	private function charge_wxpay($LoginID, $CardID) {

		ini_set('date.timezone', 'Asia/Shanghai');
		//error_reporting(E_ERROR);

		require_once ROOT_PATH . "Common/wxpay/lib/WxPay.Api.php";
		require_once ROOT_PATH . "Common/wxpay/lib/WxPay.NativePay.php";
		// require_once ROOT_PATH .'log.php';

		//模式一
		/**
		 * 流程：
		 * 1、组装包含支付信息的url，生成二维码
		 * 2、用户扫描二维码，进行支付
		 * 3、确定支付之后，微信服务器会回调预先配置的回调地址，在【微信开放平台-微信支付-支付配置】中进行配置
		 * 4、在接到回调通知之后，用户进行统一下单支付，并返回支付信息以完成支付（见：native_notify.php）
		 * 5、支付完成之后，微信服务器会通知支付成功
		 * 6、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
		 */
		$notify = new NativePay();
		/* $url1 = $notify->GetPrePayUrl("123456789"); */
		//模式二
		/**
		 * 流程：
		 * 1、调用统一下单，取得code_url，生成二维码
		 * 2、用户扫描二维码，进行支付
		 * 3、支付完成之后，微信服务器会通知支付成功
		 * 4、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
		 */
		$goodsname = $_REQUEST['payType'] == 1 ? "通易币" : '黄钻会员';
		$amount = trim($_REQUEST['payType'] == 1) ? trim($_REQUEST['Amount']) : $this->arrConfig['VipPrice']; /* 总金额 */
		if ($this->arrConfig['TestMode']) //测试
		{
			$amount = 0.01;
		}

		$out_trade_no = OSGetPayOrderID(WxPayConfig::MCHID)['szOrderNo'];

		$input = new WxPayUnifiedOrder();
		$input->SetBody($goodsname);
		$input->SetAttach($LoginID . '^' . trim($_REQUEST['payType']) . '^' . $CardID);
		$input->SetOut_trade_no($out_trade_no);
		$input->SetTotal_fee($amount * 100); //
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 6000));
		$input->SetNotify_url($this->arrConfig['URL']['Order'] . "/notify.php");
		$input->SetTrade_type("NATIVE");
		$input->SetProduct_id("123456789");
		$result = $notify->GetPayUrl($input);
		$url = $result["code_url"];
		$parameter['url'] = "http://charge.game779.com/qrcode.php?data=" . urlencode($url);
		$parameter['amount'] = $amount;
		$parameter['goodsname'] = $goodsname;
		$iResult = OSCreatePayOrder(2, trim($_REQUEST['payType']), '', $out_trade_no, $amount * 100, $LoginID);
		if ($iResult['iResult'] != 0) {
			$parameter['iResult'] = -3;
		} else {
			$parameter['iResult'] = 0;
		}
		echo json_encode($parameter);
	}
	/***
		     * 发起支付
		     *
	*/
	public function charge() {
		$ret = $this->chkParam();
		if ($ret['iResult'] != 0) {
			echo json_encode($ret);
			return;
		}
		$type = $_REQUEST['Type'];
		if ($type == "b780") {
			$this->charge_b780($ret['Rnd'], $ret['iLoginID'], $ret['CardID']);
		} else if ($type == "alipay") {
			$this->charge_alipay($ret['Rnd'], $ret['iLoginID'], $ret['CardID']);
		} else if ($type == "wx") {
			$this->charge_wxpay($ret['iLoginID'], $ret['CardID']);
		} else if ($type == "heepay") {
			$this->charge_heepay($ret['Rnd'], $ret['iLoginID'], $ret['CardID']);
		} else if ($type == 'iapppay') {
			$this->charge_iapppay($ret['Rnd'], $ret['iLoginID'], $ret['CardID']);
		} else if ($type == 'jtpay') {
			$this->charge_jtpay($ret['Rnd'], $ret['iLoginID'], $ret['CardID'], $ret['szLoginName']);
		}
	}
}
$encrypt = new encrypt();
$encrypt->charge();
?>