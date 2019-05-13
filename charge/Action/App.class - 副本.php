<?php
require_once ROOT_PATH . 'Common/PageBase.class.php';
require_once ROOT_PATH . 'Link/CreatePayOrder.php';
require_once ROOT_PATH . 'Link/GetPayOrderID.php';
require_once ROOT_PATH . 'Link/GetAccountInfoByID.php';
require_once ROOT_PATH . 'Class/BLL/DataCenterBLL.class.php';
require_once ROOT_PATH . 'Common/HttpApi.class.php';

/**
 * 首页
 * @author xuluojiong
 */
class AppAction extends PageBase {
	private $CFG = null;

	public function __construct() {
		$this->CFG = unserialize(SYS_CONFIG);
	}

	/**
	 *获取支付宝支付参数
	 *@param LoginCode  int 账号ID
	 * @param  payType int 充值类型   1欢乐豆   2会员
	 * @param cardType int 支付类型 1手机支付宝   2手机微信
	 * @param total_fee  金额 （元）
	 *
	 * */
	public function get_alipay_params() {
		$params = Utility::request(['LoginCode', 'payType', 'cardType', 'total_fee']);
		if ($this->check_logincode($params['LoginCode'])) {
			Utility::response(-1, '账号不存在');
			return;
		}
		$data['LoginCode'] = $params['LoginCode'];
		$data['total_fee'] = $params['total_fee'];
		$data['payType'] = $params['payType'];
		$result = OSGetPayOrderID('');
		if ($result['iResult'] != 0) {
			Utility::response(-2, '生成支付订单失败');
		} else {
			$data['out_trade_no'] = $result['szOrderNo'];
			$objDataCenter = new DataCenterBLL();
			$CardID = $objDataCenter->getCardID($params['cardType']);
			if ($CardID == -1) {
				Utility::response(-3, '充值方式错误');
				return;
			}
			$ret = OSCreatePayOrder(1, $data['payType'], '', $data['out_trade_no'], $data['total_fee'] * 100, $data['LoginCode']);
			if ($ret['iResult'] == 0) {
				Utility::response(0, '生成支付订单成功', $data);
			} else {
				Utility::response(-2, '生成支付订单失败');
			}

		}

	}
	/**
	 *获取新支付宝支付参数
	 *@param LoginCode  int 账号ID
	 * @param  payType int 充值类型   1欢乐豆   2会员
	 * @param cardType int 支付类型  34手机支付宝   35手机微信
	 * @param total_fee  金额 （元）
	 *
	 * */
	public function get_new_alipay_params() {
		$params = Utility::request(['LoginCode', 'payType', 'cardType', 'total_fee']);
		if ($this->check_logincode($params['LoginCode'])) {
			Utility::response(-1, '账号不存在');
			return;
		}
		$data['LoginCode'] = $params['LoginCode'];
		$data['total_fee'] = $params['total_fee'];
		$data['payType'] = $params['payType'];
		$result = OSGetPayOrderID('');
		if ($result['iResult'] != 0) {
			Utility::response(-2, '生成支付订单失败');
		} else {
			$data['out_trade_no'] = $result['szOrderNo'];
			$objDataCenter = new DataCenterBLL();
			$CardID = $objDataCenter->getCardID($params['cardType']);
			if ($CardID == -1) {
				Utility::response(-3, '充值方式错误');
				return;
			}
			$ret = OSCreatePayOrder(34, $data['payType'], '', $data['out_trade_no'], $data['total_fee'] * 100, $data['LoginCode']);
			if ($ret['iResult'] == 0) {
				Utility::response(0, '生成支付订单成功', $data);
			} else {
				Utility::response(-2, '生成支付订单失败');
			}

		}

	}



	/**
	 * 检验LoginID是否正确
	 *
	 * **/
	public function check_logincode($LoginID) {
		if ($LoginID < 60000) {
			return true;
		}

		$ret = ASGetAccountInfoByID($LoginID);
		if ($ret['szLoginName'] !== '') {
			return false;
		} else {
			return true;
		}

	}

	/**
	 *获取微信支付参数
	 *@param LoginCode  int 账号ID
	 * @param  payType int 充值类型   1欢乐豆   2会员
	 * @param cardType int 支付类型  34手机支付宝   35手机微信
	 * @param total_fee  金额 （元）
	 *
	 * */
	public function get_wxpay_params() {
		$params = Utility::request();
		if ($this->check_logincode($params['LoginCode'])) {
			Utility::response(-2, '账号不存在');
			return;
		}
		/*微信支付*/
		ini_set('date.timezone', 'Asia/Shanghai');
		//error_reporting(E_ERROR);

		require_once ROOT_PATH . "Common/app_wxpay/lib/WxPay.Api.php";
		require_once ROOT_PATH . "Common/app_wxpay/lib/WxPay.NativePay.php";
		// require_once ROOT_PATH .'log.php';
		$notify = new NativePay();
		$goodsname = $params['payType'] == 1 ? "通易币" : '黄钻会员';
		$amount = $params['payType'] ? $params['Amount'] : 100; /* 总金额 */
		$result = OSGetPayOrderID(WxPayConfig::MCHID);
		if ($result['iResult'] != 0) {
			Utility::response(-1, '生成支付订单失败');
		} else {
			$out_trade_no = $result['szOrderNo'];
			$input = new WxPayUnifiedOrder();
			$input->SetBody($goodsname);
			$input->SetAttach($params['LoginCode'] . '^' . $params['payType'] . '^' . $params['CardID']);
			$input->SetOut_trade_no($out_trade_no);
			$input->SetTotal_fee($amount * 100);
			$input->SetTime_start(date("YmdHis"));
			$input->SetTime_expire(date("YmdHis", time() + 6000));
			$input->SetNotify_url($this->CFG['URL']['Order'] . "/app_notify.php");
			$input->SetTrade_type("APP");
			$input->SetProduct_id("123456789");
			$result = $notify->GetPayUrl($input);
			$ret = OSCreatePayOrder(35, $params['payType'], '', $out_trade_no, $amount * 100, $params['LoginCode']);
			if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS' && $ret['iResult'] == 0) {
				$prepay_id = $result["prepay_id"];
				Utility::response(0, '生成订单成功', $prepay_id);
			} else {
				Utility::response(-1, '生成订单失败');
			}
		}
	}

	/**
    *获取微信支付参数
    *@param LoginCode  int 账号ID
    * @param  payType int 充值类型   1欢乐豆   2会员
    * @param cardType int 支付类型  34手机支付宝   35手机微信
    * @param total_fee  金额 （元）
    *
    * */
    public function get_wxpay593_params() {
    	$params = Utility::request();
    	if ($this->check_logincode($params['LoginCode'])) {
    		Utility::response(-2, '账号不存在');
    		return;
    	}
    	/*微信支付*/
    	ini_set('date.timezone', 'Asia/Shanghai');
    	//error_reporting(E_ERROR);

        require_once ROOT_PATH . "Common/app_wxpay593/lib/WxPay.Api.php";
    	require_once ROOT_PATH . "Common/app_wxpay593/lib/WxPay.NativePay.php";
    	// require_once ROOT_PATH .'log.php';
    	$notify = new NativePay();
    	$goodsname = $params['payType'] == 1 ? "通易币" : '黄钻会员';
    	$amount = $params['payType'] ? $params['Amount'] : 100; /* 总金额 */
    	$result = OSGetPayOrderID(WxPayConfig::MCHID);
    	if ($result['iResult'] != 0) {
    		Utility::response(-1, '生成支付订单失败');
    	} else {
    		$out_trade_no = $result['szOrderNo'];
    		$input = new WxPayUnifiedOrder();
    		$input->SetBody($goodsname);
    		$input->SetAttach($params['LoginCode'] . '^' . $params['payType'] . '^' . $params['CardID']);
    		$input->SetOut_trade_no($out_trade_no);
    		$input->SetTotal_fee($amount * 100);
    		$input->SetTime_start(date("YmdHis"));
    		$input->SetTime_expire(date("YmdHis", time() + 6000));
    		$input->SetNotify_url($this->CFG['URL']['Order'] . "/app_notify593.php");
    		$input->SetTrade_type("APP");
    		$input->SetProduct_id("123456789");
    		$result = $notify->GetPayUrl($input);
    		$ret = OSCreatePayOrder(35, $params['payType'], '', $out_trade_no, $amount * 100, $params['LoginCode']);
    		if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS' && $ret['iResult'] == 0) {
    			$prepay_id = $result["prepay_id"];
    			Utility::response(0, '生成订单成功', $prepay_id);
    		} else {
    			Utility::response(-1, '生成订单失败');
    		}
    	}
    }

	/**
	 *获取iOS支付参数
	 *
	 * */
	public function get_iospay_params() {
		$params = Utility::request(['LoginCode', 'payType', 'cardType', 'total_fee']);
		if ($this->check_logincode($params['LoginCode'])) {
			Utility::response(-1, '账号不存在');
			return;
		}
		$data['LoginCode'] = $params['LoginCode'];
		$data['total_fee'] = $params['total_fee'];
		$data['payType'] = $params['payType'];
		$result = OSGetPayOrderID('');
		if ($result['iResult'] != 0) {
			Utility::response(-2, '生成支付订单失败');
		} else {
			$data['out_trade_no'] = $result['szOrderNo'];
			$objDataCenter = new DataCenterBLL();
			$CardID = $objDataCenter->getCardID($params['cardType']);
			if ($CardID == -1) {
				Utility::response(-3, '充值方式错误');
				return;
			}
			$ret = OSCreatePayOrder(33, $data['payType'], '', $data['out_trade_no'], $data['total_fee'] * 100, $data['LoginCode']);
			if ($ret['iResult'] == 0) {
				Utility::response(0, '生成支付订单成功', $data);
			} else {
				Utility::response(-2, '生成支付订单失败');
			}

		}
	}
	/***
		 *获取欢乐豆兑换比例
		 *
		 *
	*/
	public function get_happybean_rate() {
		$params = Utility::request(['cardType']);
		$objDataCenter = new DataCenterBLL();
		$GameConfig = $objDataCenter->getGameConfig();
		$RMB = $GameConfig[8]; //充值比例人民币，单位分
		$HPB = $GameConfig[9]; //充值比例欢乐豆
		$MB = $GameConfig[10]; //充值比例通易币
		$discount = $GameConfig[13];
		$payConfig['discount'] = intval($discount);
		$payConfig['RMB2MB'] = $MB * 100 / $RMB;
		$payConfig['MB2HPB'] = $HPB / $MB;
		$ChargeRate = $objDataCenter->getCardChargeRateByType($params['cardType']);
		if ($ChargeRate != -1) {
			$payConfig['ChargeRate'] = $ChargeRate;
			Utility::response(0, '获取支付比例成功', $payConfig);
		} else {
			Utility::response(-1, '获取支付比例失败');
		}
	}

	/**
	 *支付下单请求
	 *@param LoginCode  int 账号ID
	 * @param  payType int 充值类型   1欢乐豆   2会员
	 * @param cardType int 支付类型  38 爱贝
	 * @param total_fee  金额 （元）
	 *
	 * */
	public function get_heepay_params() {
		$params = Utility::request(['LoginCode', 'payType', 'cardType', 'total_fee']);
		if ($this->check_logincode($params['LoginCode'])) {
			Utility::response(-1, '账号不存在');
			return;
		}
		$data['LoginCode'] = $params['LoginCode'];
		$data['total_fee'] = $params['total_fee'];
		$data['payType'] = $params['payType'];
		$result = OSGetPayOrderID('');
		if ($result['iResult'] != 0) {
			Utility::response(-2, '生成支付订单失败');
		} else {
			$data['out_trade_no'] = $result['szOrderNo'];
			$objDataCenter = new DataCenterBLL();
			$data['CardID'] = $objDataCenter->getCardID($params['cardType']);
			if ($data['CardID'] == -1) {
				Utility::response(-3, '充值方式错误');
				return;
			}
			$ret = OSCreatePayOrder($data['CardID'], $data['payType'], '', $data['out_trade_no'], $data['total_fee'] * 100, $data['LoginCode']);
			if ($ret['iResult'] == 0) {
				$returnArr = $this->charge_jtpay($data);
				//$returnArr = $this->charge_heepay($data);
				if ($returnArr) {
					Utility::response(0, '生成支付订单成功', $returnArr);
				}
			} else {
				Utility::response(-2, '生成支付订单失败');
			}

		}
	}


    /*
 * 威富通支付接口
 */
    public function get_wft_pay(){
        $params = Utility::request(['LoginCode', 'payType', 'cardType', 'total_fee']);
        if ($this->check_logincode($params['LoginCode'])) {
            Utility::response(-1, '账号不存在');
            return;
        }
        $data['LoginCode'] = $params['LoginCode'];
        $data['order_amount'] = $params['total_fee'];
        $data['payType'] = $params['payType'];
        $result = OSGetPayOrderID('');		
        if ($result['iResult'] != 0) {
            Utility::response(-2, '生成支付订单失败');
        } else {
            $data['order_no'] = $result['szOrderNo'];
            $objDataCenter = new DataCenterBLL();
            $CardID = $objDataCenter->getCardID($params['cardType']);
            if ($CardID == -1) {
                Utility::response(-3, '充值方式错误');
                return;
            }
            $ret = OSCreatePayOrder(40, $data['payType'], '', $data['order_no'], $data['order_amount']*100, $data['LoginCode']);
            if ($ret['iResult'] == 0) {
                $returnArr = $this->charge_wftpay($data);			
				$ob =$returnArr->response;
				$retdata =array("payurl"=>$ob->payURL);
                Utility::response(0, '生成支付订单成功',$retdata);
            } else {
                Utility::response(-2, '生成支付订单失败');
            }
        }
    }

	public function getWXPayType() {
		Utility::response(0, '', [34,35]); //官方微信
		//竣付通微信41 官方微信35
        //竣付通支付宝40 支付宝官方34
		//Utility::response(0, '', [41,34,41,34]); //俊付通微信
	}

	private function charge_jtpay($data) {
		require_once ROOT_PATH . 'Include/jtpayconfig.php';

		$p1_usercode = AGENT_ID;
		$p2_order = $data['out_trade_no'];
		$p3_money = $data['total_fee'];
		$p4_returnurl = $this->CFG['URL']['Order'] . "/app_jtpay_return.php";
		$p5_notifyurl = $this->CFG['URL']['Order'] . "/app_jtpay_notify.php";

		// $p4_returnurl = 'http://114.215.255.12:80/Order/app_jtpay_notify.php';
		// $p5_notifyurl = 'http://114.215.255.12:80/Order/app_jtpay_notify.php';
		//$p6_ordertime = date('yyyymmddhhmmss', time());
		$p6_ordertime = date('YmdHis', time());
		$rawString = $p1_usercode . "&" . $p2_order . "&" . $p3_money . "&" . $p4_returnurl . "&" . $p5_notifyurl . "&" . $p6_ordertime . SIGN_KEY;
		$p7_sign = strtoupper(md5($rawString));
		$p9_paymethod = 'SDK';
		$p24_remark = $data['LoginCode'] . '^' . $data['payType'] . '^' . $data['CardID'];

		$p25_terminal = '5';

		$parameter = array(
			"p1_usercode" => $p1_usercode,
			"p2_order" => $p2_order,
			"p3_money" => $p3_money,
			"p4_returnurl" => $p4_returnurl,
			"p5_notifyurl" => $p5_notifyurl,
			"p6_ordertime" => $p6_ordertime,
			"p7_sign" => $p7_sign,
			"p9_paymethod" => $p9_paymethod,
			"p24_remark" => $p24_remark,
			"p25_terminal" => $p25_terminal,
		);
		//Utility::Log("test", 'error_info', json_encode($parameter));
		$pay_url = "http://api.jtpay.com/jft/sdk/token";
		$respData = json_decode(HttpApi::curl_post($pay_url, $parameter), TRUE);

		$ret = array(
			"agent_id" => AGENT_ID,
			"app_id" => APP_ID,
			"app_key" => APP_KEY,
			"app_iv" => APP_IV,
			//"respData" => $respData,
			"p2_order" => $p2_order,
			"token" => $respData['token'],
		);

		return $ret;

	}

	private function charge_iapppay($data) {
		require_once ROOT_PATH . 'Include/iapppayconfig.php';
		require_once ROOT_PATH . 'Common/iapppay/base.php';

		//global $orderUrl, $appkey, $platpkey , $transid,$appid;
		//下单接口
		$orderReq['appid'] = "$appid";
		$orderReq['waresid'] = 1;
		$orderReq['cporderid'] = $data['out_trade_no']; //确保该参数每次 都不一样。否则下单会出问题。
		//echo "microtime()";
		$orderReq['price'] = $data['total_fee']; //单位：元
		$orderReq['currency'] = 'RMB';
		$orderReq['appuserid'] = $data['LoginCode'];
		$orderReq['cpprivateinfo'] = $data['LoginCode'] . '^' . $data['payType'] . '^' . $data['CardID'];
		//$orderReq['notifyurl'] = 'http://58.250.160.241:8888/IapppayCpSyncForPHPDemo/TradingResultsNotice.php';
		//$orderReq['notifyurl'] = $this->CFG['URL']['Order']."/app_iapppay_notify.php";
		$orderReq['notifyurl'] = 'http://114.215.255.12:80/Order/app_iapppay_notify.php';
		//组装请求报文  对数据签名
		$reqData = composeReq($orderReq, $appkey);
		echo "$reqData";
		//发送到爱贝服务后台请求下单
		$respData = request_by_curl($orderUrl, $reqData, 'order test');
		echo "respData:$respData\n";

		//验签数据并且解析返回报文
		if (!parseResp($respData, $platpkey, $respJson)) {
			// echo "failed";
		} else {
			// echo "success";
			// echo "服务端下单完成，trasnid:<br/>";
			// print_r($respJson);
			//     下单成功之后获取 transid
			$transid = $respJson->transid;
			return $transid;
		}

	}

	private function charge_heepay($data) {
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
		$agent_id = AGENT_ID_APP; //	商户编号
		$agent_bill_id = $data['out_trade_no']; //	商户系统内部的定单号（要保证唯一）。长度最长50字符
		$agent_bill_time = date('YmdHis', time());
		$pay_type = $data['CardID'] == 37 ? 30 : 22; //微信支付代码,int型
		$pay_amt = sprintf("%.2f", $data['total_fee']); /* 总金额 */
		if ($this->CFG['TestMode']) //测试
		{
			$pay_amt = 0.5;
		}

		$notify_url = $this->CFG['URL']['Order'] . "/app_heepay_notify.php";
		$return_url = $this->CFG['URL']['Order'] . "/app_heepay_return.php"; //微信支付不涉及同步返回，此处可填写任意URL，没有实际使用
		$goods_name = $data['payType'] == 1 ? "通易币" : '黄钻会员';
		$goods_name = urlencode($goods_name);
		$goods_num = 1;
		$goods_note = ''; //支付说明
		$remark = $data['LoginCode'] . '^' . $data['payType'] . '^' . $data['CardID']; /* 商家数据包 */
		$wxpay_type = 0; // 0 扫码支付
		$sign_key = SIGN_KEY_APP; //签名密钥，需要商户使用为自己的真实KEY
		/*************创建签名***************/
		$sign_str = '';
		$sign_str = $sign_str . 'version=' . $version;
		$sign_str = $sign_str . '&agent_id=' . $agent_id;
		$sign_str = $sign_str . '&agent_bill_id=' . $agent_bill_id;
		$sign_str = $sign_str . '&agent_bill_time=' . $agent_bill_time;
		$sign_str = $sign_str . '&pay_type=' . $pay_type;
		$sign_str = $sign_str . '&pay_amt=' . $pay_amt;
		$sign_str = $sign_str . '&notify_url=' . $notify_url;
		$sign_str = $sign_str . '&user_ip=' . $user_ip;
		$sign_str = $sign_str . '&key=' . $sign_key;

		$sign = md5($sign_str); //签名值
		//构造要请求的参数数组，无需改动
		$parameter = array(
			"version" => $version,
			"agent_id" => $agent_id,
			"agent_bill_id" => $agent_bill_id,
			"agent_bill_time" => $agent_bill_time,
			"pay_type" => $pay_type,
			"pay_amt" => $pay_amt,
			"notify_url" => $notify_url,
			"return_url" => $return_url,
			"user_ip" => $user_ip,
			"goods_name" => $goods_name,
			"goods_num" => $goods_num,
			"goods_note" => $goods_note,
			"remark" => $remark,
			"sign" => $sign,
		);
		Utility::Log("test", 'error_info', json_encode($parameter));
		$pay_url = "https://pay.heepay.com/Phone/SDK/PayInit.aspx";
		$ret = HttpApi::curl_post($pay_url, $parameter);
		if ($ret) {
			$ret = simplexml_load_string($ret);
			$ret = json_decode(json_encode($ret), TRUE);
			$ret['token_id'] = $ret[0];
			unset($ret[0]);
			$ret['agent_bill_id'] = $parameter['agent_bill_id'];
			$ret['agent_id'] = $parameter['agent_id'];
		}
		return $ret;

	}

	private function charge_wftpay($param){			
	
        require_once ROOT_PATH . 'Include/dbpayconfig.php';
        $merchant_code ="200011002004";// $param["merchant_code"];//商户号，388003002444是测试商户号，调试时要更换商家自己的商户号
        $service_type = "alipay_h5api";//$param["service_type"];//支付宝：alipay_scan
        $notify_url = $this->CFG['URL']['Order'] . "/wft_notify.php";;
        $interface_version = "V3.1";//$param["interface_version"];		
		
		$user_ip = "";
		if (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$user_ip = $_SERVER['HTTP_CLIENT_IP'];
		} else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$user_ip = $_SERVER['REMOTE_ADDR'];
		}
        $client_ip = $user_ip;

        $sign_type = "RSA-S";
        $order_no = $param["order_no"];
        $order_time = date("Y-m-d H:i:s");//$param["order_time"];
        $order_amount = $param["order_amount"];
        $product_name = $param['payType'] == 1 ? "金币" : '黄钻会员';
		$product_name = $product_name;
        $product_code =$param['LoginCode'] .'888'.$param["order_amount"]; //$param["product_code"];
        $product_num = 1;//$param["product_num"];
        $product_desc = $param['LoginCode'] . '|' . $param['payType'] . '|' . $param['CardID'];//$param["product_desc"];
        $extra_return_param ="";// $param["extra_return_param"];
        $extend_param = "";//$param["extend_param"];
/////////////////////////////   参数组装  /////////////////////////////////
        /**
         * 除了sign_type dinpaySign参数，其他非空参数都要参与组装，组装顺序是按照a~z的顺序，下划线"_"优先于字母
         */
        $signStr = "";
        $signStr = $signStr . "client_ip=" . $client_ip . "&";
		
        if ($extend_param != "") {
            $signStr = $signStr . "extend_param=" . $extend_param . "&";
        }

        if ($extra_return_param != "") {
            $signStr = $signStr . "extra_return_param=" . $extra_return_param . "&";
        }

        $signStr = $signStr . "interface_version=" . $interface_version . "&";
        $signStr = $signStr . "merchant_code=" . $merchant_code . "&";
        $signStr = $signStr . "notify_url=" . $notify_url . "&";
        $signStr = $signStr . "order_amount=" . $order_amount . "&";
        $signStr = $signStr . "order_no=" . $order_no . "&";
        $signStr = $signStr . "order_time=" . $order_time . "&";

        if ($product_code != "") {
            $signStr = $signStr . "product_code=" . $product_code . "&";
        }
        if ($product_desc != "") {
            $signStr = $signStr . "product_desc=" . $product_desc . "&";
        }
        $signStr = $signStr . "product_name=" . $product_name . "&";
        if ($product_num != "") {
            $signStr = $signStr . "product_num=" . $product_num . "&";
        }
        $signStr = $signStr . "service_type=" . $service_type;		

/////////////////////////////   RSA-S签名  /////////////////////////////////	

/////////////////////////////////初始化商户私钥//////////////////////////////////////

        $merchant_private_key = openssl_get_privatekey($merchant_private_key);
        openssl_sign($signStr, $sign_info, $merchant_private_key, OPENSSL_ALGO_MD5);
        $sign = base64_encode($sign_info);
/////////////////////////  提交参数到扫码支付网关  ////////////////////////

        /**
         * curl方法提交支付参数到扫码网关https://api.efubill.com/gateway/api/h5apipay，并且获取返回值
         */
        $postdata = array('extend_param' => $extend_param,
            'extra_return_param' => $extra_return_param,
            'product_code' => $product_code,
            'product_desc' => $product_desc,
            'product_num' => $product_num,
            'merchant_code' => $merchant_code,
            'service_type' => $service_type,
            'notify_url' => $notify_url,
            'interface_version' => $interface_version,
            'sign_type' => $sign_type,
            'order_no' => $order_no,
            'client_ip' => $client_ip,
            'sign' => $sign,
            'order_time' => $order_time,
            'order_amount' => $order_amount,
            'product_name' => $product_name);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.yuanruic.com/gateway/api/h5apipay");
       // curl_setopt($ch, CURLOPT_URL, "https://api.efubill.com/gateway/api/h5apipay");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);		
		curl_close($ch);
        $res = simplexml_load_string($response);		  
        return $res;
    }




}
?>