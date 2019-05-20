<?php

include_once 'function.php';
  // $test->query();
  // $test->daifu();
  // $test->queryDF();
class Yilianbao {

	/**
	 * 支付
	 * @param request
	 * @param response
	 * @throws IOException
	 */
	public function  pay($orderId, $amount, $notifyUrl, $method, $type) {

		$key = "6bb148080fb34c61ad4601db0d17515c"; //商户的key（签名秘钥）	开户方提供
		$url = 'http://47.244.7.147:8087/v1/pay/order'; //访问地址
		$merchantNo = "88882019051910000087";

		$data = array();
		$data['appid'] = "76ec9ca74fc44e9496c5fe74bd1447ef"; //商户的appid	开户方提供
		$data['merchantNo'] = $merchantNo; //商户号	开户方提供
		$data['merchantOrderNo'] = $orderId;  //生成符合要求的订单号	唯一性
		$data['tradeTime'] = date('Ymdhis'); //下单时间
		$data['price'] = $amount; //金额(分)
		$data['version'] = "1.0"; //固定值1.0
		//$data['returnUrl'] = "http://XXXX"; //同步通知地址
		$data['notifyUrl'] = $notifyUrl; //异步通知地址
		$data['payChannel'] = $method; //01：支付宝 02: 微信	详询开户方
		$data['payType'] = $type; //01： 扫码 02：H5 05：阿里收款	详询开户方
		$data['productName'] = "yilianbaopay"; //商品名称		自定义
		$data['sign'] = getSign($data,$key); //签名
//        var_dump($data);
//        die;
		$ret = https_post($url,$data);
		return $ret;
	}

	/**
	 * 支付结果查询
	 * @param request
	 * @param response
	 * @throws IOException
	 */
	public function  query(){
		
		$key = "de950fdb88194834a264895334878556"; //商户的key（签名秘钥）	开户方提供
		$url = 'http://47.106.237.198:8087/v1/pay/queryOrder'; //访问地址
		$merchantOrderNo = "T0309141900104734667";//传入需要查询的订单号
		$merchantNo = "88882019012210000002";
		$data = array();
		$data['merchantNo'] = $merchantNo; //商户号	开户方提供
		$data['merchantOrderNo'] = $merchantOrderNo;  
		$data['version'] = "1.0"; //固定值1.0
		$data['sign'] = getSign($data,$key); //签名
		$ret = https_post($url,$data);
		return $ret;
	}

	/**
	 * 余额查询
	 * @param request
	 * @param response
	 * @throws IOException
	 */
	public function  queryBalance(){
		$key = "de950fdb88194834a264895334878556"; //商户的key（签名秘钥）	开户方提供
		$url = 'http://47.106.237.198:8087/v1/pay/queryBalance'; //访问地址
		$merchantNo = "88882019012210000002";
		$data = array();
		$data['merchantNo'] = $merchantNo; //商户号	开户方提供
		$data['version'] = "1.0"; //固定值1.0
		$data['sign'] = getSign($data,$key); //签名
		$ret = https_post($url,$data);
		return $ret;
	}

	/**
	 * 代付
	 * @param request
	 * @param response
	 * @throws IOException
	 */
	public function  daifu(){
		
		$key = "de950fdb88194834a264895334878556"; //商户的key（签名秘钥）	开户方提供
		$url = 'http://47.106.237.198:8087/v1/dfpay/apply'; //访问地址
		$merchantNo = "88882019012210000002";
		$data = array();
		$data['merchantNo'] = $merchantNo; //商户号	开户方提供
		$data['merchantOrderNo'] = "T".date('Ymdhi');  //生成符合要求的订单号	唯一性
		$data['applyTime'] = date('Ymdhis'); //下单时间
		$data['price'] = "1000"; //金额
		$data['bankName'] = "兴业银行"; 
		$data['accountName'] = "张三"; 
		$data['accountNo'] = "6500000XXXX"; //收款账号
		$data['accountMobile'] = "183XXXX9834";//收款账户绑定的手机号
		$data['certNo'] = "3502333XXXXXX";//身份证号码
		$data['feeType'] = "02";//收款方付：01   付款方付：02
		$data['version'] = "1.0"; //固定值1.0
		$data['sign'] = getSign($data,$key); //签名
		$ret = https_post($url,$data);
		return $ret;
	}

	/**
	 * 代付结果查询
	 * @param request
	 * @param response
	 * @throws IOException
	 */
	public function  queryDF(){
		$key = "de950fdb88194834a264895334878556"; //商户的key（签名秘钥）	开户方提供
		$url = 'http://47.106.237.198:8087/v1/dfpay/queryOrder'; //访问地址
		$merchantNo = "88882019012210000002";
		$merchantOrderNo = "T0309141900104734667";//传入需要查询的订单号
		$data = array();
		$data['merchantNo'] = $merchantNo; //商户号	开户方提供
		$data['merchantOrderNo'] = $merchantOrderNo; 
		$data['version'] = "1.0"; //固定值1.0
		$data['sign'] = getSign($data,$key); //签名
		$ret = https_post($url,$data);
		return $ret;
	}

}

?>
