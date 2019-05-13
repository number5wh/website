<?php
/* *
 *功能：智付支付宝支付接口
 *版本：3.0
 *日期：2016-07-10
 *说明：
 *以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,
 *并非一定要使用该代码。该代码仅供学习和研究智付接口使用，仅为提供一个参考。
 **/
	

///////////////////////////  初始化接口参数  //////////////////////////////
/**
接口参数请参考智付微信支付文档，除了sign参数，其他参数都要在这里初始化
*/
	
	include ('phpqrcode.php');
	
	include_once("./merchant.php");
	
	$merchant_code = "1111110166";//商户号，1111110166是测试商户号，调试时要更换商家自己的商户号

	$service_type = "alipay_scan";

	$notify_url = "http://www.dinpay.com/ali/offline.php";	

	$interface_version ="V3.1";
	
	$client_ip = "120.237.123.242";
	
	$sign_type = "RSA-S";

	$order_no = date( 'YmdHis' );

	$order_time = date( 'Y-m-d H:i:s' );

	$order_amount ="0.1";

	$product_name = "test";

	$product_code ="";

	$product_num = "";
	
	$product_desc = "";

	$extra_return_param = "";
	
	$extend_param ="";
	
/////////////////////////////   参数组装  /////////////////////////////////
/**
除了sign_type dinpaySign参数，其他非空参数都要参与组装，组装顺序是按照a~z的顺序，下划线"_"优先于字母
*/

	$signStr = "";
	
	$signStr = $signStr."client_ip=".$client_ip."&";
	
	if($extend_param != ""){
		$signStr = $signStr."extend_param=".$extend_param."&";
	}
	
	if($extra_return_param != ""){
		$signStr = $signStr."extra_return_param=".$extra_return_param."&";
	}
	
	$signStr = $signStr."interface_version=".$interface_version."&";	
	
	$signStr = $signStr."merchant_code=".$merchant_code."&";	
	
	$signStr = $signStr."notify_url=".$notify_url."&";		
	
	$signStr = $signStr."order_amount=".$order_amount."&";		
	
	$signStr = $signStr."order_no=".$order_no."&";		
	
	$signStr = $signStr."order_time=".$order_time."&";	

	if($product_code != ""){
		$signStr = $signStr."product_code=".$product_code."&";
	}	
	
	if($product_desc != ""){
		$signStr = $signStr."product_desc=".$product_desc."&";
	}
	
	$signStr = $signStr."product_name=".$product_name."&";

	if($product_num != ""){
		$signStr = $signStr."product_num=".$product_num."&";
	}	
	
	$signStr = $signStr."service_type=".$service_type;
	
/////////////////////////////   RSA-S签名  /////////////////////////////////



/////////////////////////////////初始化商户私钥//////////////////////////////////////

			
	$merchant_private_key= openssl_get_privatekey($merchant_private_key);
		
	openssl_sign($signStr,$sign_info,$merchant_private_key,OPENSSL_ALGO_MD5);
	
	$sign = base64_encode($sign_info);
	
/////////////////////////  提交参数到智付支付宝网关  ////////////////////////
/**
curl方法提交支付参数到智付支付宝网关https://api.dinpay.com/gateway/api/scanpay，并且获取返回值
*/

	
	$postdata=array('extend_param'=>$extend_param,
	'extra_return_param'=>$extra_return_param,
	'product_code'=>$product_code,
	'product_desc'=>$product_desc,
	'product_num'=>$product_num,
	'merchant_code'=>$merchant_code,
	'service_type'=>$service_type,
	'notify_url'=>$notify_url,
	'client_ip'=>$client_ip,
	'interface_version'=>$interface_version,
	'sign_type'=>$sign_type,
	'order_no'=>$order_no,
	'sign'=>$sign,
	'order_time'=>$order_time,
	'order_amount'=>$order_amount,
	'product_name'=>$product_name);
		
	$ch = curl_init();	
	curl_setopt($ch,CURLOPT_URL,"https://api.dinpay.com/gateway/api/scanpay");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata));  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response=curl_exec($ch);
	$res=simplexml_load_string($response);
	
	var_dump($res);
	curl_close($ch);
	/////////////////////////////   获取qrcode，并生成二维码  /////////////////////
	/**
	解析智付返回参数，获取qrcode的值，并且根据这个值生成二维码
	*/
	$resp_code=$res->response->resp_code;
	
	
					
					if($resp_code=="SUCCESS"){
					
					$result_code=$res->response->result_code;
					
					if($result_code =="0")
					{
						
					$qrcode=$res->response->qrcode;
					
					if(file_exists('qrcode.png')){
													
										unlink('qrcode.png');
										}
					$pic="qrcode.png";
					
					$errorCorrectionLevel = 'L';
    
					$matrixPointSize = 10;
						
					QRcode::png ( $qrcode, $pic, $errorCorrectionLevel, $matrixPointSize, 2 );
					
					echo "使用支付宝扫描此二维进行支付："."<br>"."<img src=$pic>";
					
					}else{
						
						echo "通讯成功，获取二维失败，请重新发起支付请求";
					}
					}else{
						
						echo "通讯失败，参数异常或者验签失败,错误描述如下："."<br>";
						
						echo $res->response->resp_desc;
						
					}
			
?>
