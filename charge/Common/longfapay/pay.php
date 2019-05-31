<?php 
include('util.php');
class pay{
	private $merchNo;
	private $key;
	private $reqUrl;
	public function __construct(){
		$this->merchNo = 'LFP201905251124';
		$this->key = '9FD2F618F9CAC2A8A3023F18170C6C3B';
		$this->reqUrl = 'http://pay.longfapay.com:88/api/pay';// 测试环境
		$this->query_reqUrl = 'http://47.94.6.240:9003/api/queryPayResult';
		$this->version = 'V3.6.0.0';
	}


	public function pay($orderNo, $payType, $notifyUrl, $amount){
		$Rsa = new Rsa();
		$data['orderNo'] = (string)$orderNo;
		$data['randomNo'] = (string) rand(1000,9999);
		$data['merchNo'] = $this->merchNo;
		$data['netwayType'] = $payType;   	//WX:微信支付,ZFB:支付宝支付
		$data['amount'] = (string)$amount;	// 单位:分
		$data['goodsName'] = 'longfapay';
		$data['notifyUrl'] = $notifyUrl;
		$data['notifyViewUrl'] = 'http://www.fz1696.com';
		$data['sign'] = create_sign($data,$this->key);

		$json = json_encode_ex($data);
		$dataStr = $Rsa->encode_pay($json);

		$param = 'data=' . urlencode($dataStr) . '&merchNo=' . $this->merchNo . '&version=' . $this->version;

//        var_dump($param);
//        die;
        //$param = "data=tKO%2BJ4UEu78QgsB1UXrFl6nkAoOEgzlkZurRDMNxqTPQwkilTCTtKd1zCGLJ8K1TD00mFuQfZZ0W9LmB99DJw1WZzXGxkCSf3%2FpHBjscpHWODPciRvAIow1EO2SbDbNUioMjCnP33oL0RgXbTz6%2B7qdbIuXSVSjFoLNaOPTFEzyJyMrJxWVT27pn1sv5Tv4vmzI849nqAda9fpoXORCd5AhtN%2FSXIk%2B6jYHXBTdV0ueAemzzrh3qC9hLh5Pc0S1DOMG8ljOgP3ZWI7tM%2By0smW4BH1KSQ1feuygCisArZ3m8%2BBFsjvoU80SmkcHnBIDHz3ZFBvbi4NpPiSbxGTIkMpDDlE7HG9%2BKQ4OALS5nnNhIqhXtv7eaPdhd0D%2FqjRQgQfQYBu7iiMT2CUwdv%2B3H1iV91ncsPKaitqOz1aIUJwLLCO6h2f5fz9UiehnzHiJjjW%2Fe9CughrFu4WFhh7oMOrsIqApRN5B0jyfyo7aMYEFE%2Byt%2FVy6Embo5S6eQBbJz&merchNo=LFP201808250000&version=V3.6.0.0";

 		$result = wx_post($this->reqUrl,$param);
 		$rows = json_to_array($result,$this->key);
        return $rows;
	}

	public function query(){
		$Rsa = new Rsa();
		$data['merchNo'] = $this->merchNo;
		$data['netwayType'] = 'ZFB';// WX:微信支付,ZFB:支付宝支付
		$data['orderNo'] = '20180826092216382zV8ujN';
		$data['amount'] = '10000';
		$data['goodsName'] = '家具配套';
		$data['payDate'] = '2018-08-26';
		$data['sign'] = create_sign($data,$this->key);

		$json = json_encode_ex($data);
		$dataStr = $Rsa->encode_pay($json);
 		$param = 'data=' . urlencode($dataStr) . '&merchNo=' . $this->merchNo . '&version=' . $this->version;

 		$result = wx_post($this->query_reqUrl,$param);
 		$rows = json_to_array($result,$this->key);
 		if ($rows['stateCode'] == '00'){
 			echo "订单查询成功,以下是订单数据</br>";
 			P($rows); 	#支付状态 00:支付成功 01:支付失败 03:签名错误 04:其他错误 05:未知06:初始 50:网络异常 99:未支付

 		}else{
 			echo "错误代码：" . $rows['stateCode'] . ' 错误描述:' . $rows['msg'];

 		}
	}

	public function callback(){
		$Rsa = new Rsa();
		$data = isset($_POST['data']) ? $_POST['data'] : '';
		if (!empty($data)){
			$data = urldecode($data);
			$data = $Rsa->decode($data);
			$rows = callback_to_array($data,$this->key);
	 		log_write("收到支付回调通知");
	 		log_write(PS($rows));
	 		
		}
		echo "SUCCESS";
	}
}

header("Content-type: text/html; charset=utf-8");
$rec = isset($_GET['rec']) ? $_GET['rec'] : '';
if ($rec == 'pay'){
	$pay = new pay();
	$pay->pay();
}

if ($rec == 'query'){
	$pay = new pay();
	$pay->query();
}

if ($rec == 'callback'){
	$pay = new pay();
	$pay->callback();
}


 
?>