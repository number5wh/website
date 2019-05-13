<?php
require_once ROOT_PATH . 'Common/PageBase.class.php';
require_once ROOT_PATH . 'Link/CreatePayOrder.php';
require_once ROOT_PATH . 'Link/GetPayOrderID.php';
require_once ROOT_PATH . 'Link/GetAccountInfoByID.php';
require_once ROOT_PATH . 'Class/BLL/DataCenterBLL.class.php';
require_once ROOT_PATH . 'Common/HttpApi.class.php';

/**
 * 首页
 * @author 
 */
class AppAction extends PageBase {
	private $CFG = null;

	public function __construct() {
		$this->CFG = unserialize(SYS_CONFIG);
	}



	/**
	 * 检验LoginID是否正确
	 *
	 * **/
	public function check_logincode($LoginID) {
//		if ($LoginID < 10000000) {
//			return false;
//		}
		$ret = ASGetAccountInfoByID($LoginID);
		if ($ret['szLoginName'] !== '') {
			return false;
		} else {
			return true;
		}

	}


	/*
    * 多得宝支付接口
    */
    public function get_wft_pay(){
        $params = Utility::request(['LoginCode', 'payType', 'cardType', 'total_fee']);
        if ($this->check_logincode($params['LoginCode'])) {
            Utility::response(-1, '账号不存在');
            return;
        }

        $cardType = $params['cardType'];
        $data['LoginCode'] = $params['LoginCode'];
        $data['order_amount'] = $params['total_fee'];
        $data['payType'] = $params['payType'];
        $result = OSGetPayOrderID('');
        if ($result['iResult'] != 0) {
            Utility::response(-2, '生成支付订单失败');
        } else {
            $data['order_no'] = $result['szOrderNo'];
            $objDataCenter = new DataCenterBLL();
            $CardID = $objDataCenter->getCardID($cardType);
            if ($CardID == -1) {
                Utility::response(-3, '充值方式错误');
                return;
            }
            $ret = OSCreatePayOrder($cardType, $data['payType'], '', $data['order_no'], $data['order_amount']*100, $data['LoginCode']);
            if ($ret['iResult'] == 0) {
                //$returnArr = $this->charge_wftpay($data);
                $returnArr = $this->charge_yunchenpay($data);
				//$ob =$returnArr->response;
				//$retdata =array("payurl"=>$ob->payURL);
                $reponsedata["url"] = $returnArr;
                $reponsedata["orderId"] = $data['order_no'];
               // file_put_contents("./resp.txt",json_encode($reponsedata));
                Utility::response(0, '生成支付订单成功', $reponsedata);
            } else {
                Utility::response(-2, '生成支付订单失败');
            }
        }
    }
	

	

	private function charge_wftpay($param){			
	
        require_once ROOT_PATH . 'Include/dbpayconfig.php';
		//require_once ROOT_PATH . 'Include/wftpayconfig.php';
        $merchant_code ="200011002004";// $param["merchant_code"];//商户号，388003002444是测试商户号，调试时要更换商家自己的商户号
		//$merchant_code ="100002006001";// $param["merchant_code"];//商户号，388003002444是测试商户号，调试时要更换商家自己的商户号
        $service_type = "alipay_transfer_h5";//$param["service_type"];//支付宝：alipay_scan
        $notify_url = $this->CFG['URL']['Order'] . "/wft_notify.php";
        $interface_version = "V3.1";//$param["interface_version"];		

        $return_url ="";
        $show_url = "";
        $redo_flag = "";


		$user_ip = "";
		if (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$user_ip = $_SERVER['HTTP_CLIENT_IP'];
		} else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$user_ip = $_SERVER['REMOTE_ADDR'];
		}
        $client_ip = $user_ip;
        $input_charset = "UTF-8";
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
        $extend_param =$product_name;//$param["extend_param"];

        $pay_type = "";
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
        $signStr = $signStr."input_charset=".$input_charset."&";
        $signStr = $signStr . "interface_version=" . $interface_version . "&";
        $signStr = $signStr . "merchant_code=" . $merchant_code . "&";
        $signStr = $signStr . "notify_url=" . $notify_url . "&";
        $signStr = $signStr . "order_amount=" . $order_amount . "&";
        $signStr = $signStr . "order_no=" . $order_no . "&";
        $signStr = $signStr . "order_time=" . $order_time . "&";

        if($pay_type != ""){
            $signStr = $signStr."pay_type=".$pay_type."&";
        }

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


        if($redo_flag != ""){
            $signStr = $signStr."redo_flag=".$redo_flag."&";
        }
        if($return_url != ""){
            $signStr = $signStr."return_url=".$return_url."&";
        }

        if($show_url != ""){

            $signStr = $signStr."&show_url=".$show_url;
        }


/////////////////////////////   RSA-S签名  /////////////////////////////////	

/////////////////////////////////初始化商户私钥//////////////////////////////////////

        $merchant_private_key = openssl_get_privatekey($merchant_private_key);
        openssl_sign($signStr, $sign_info, $merchant_private_key, OPENSSL_ALGO_MD5);
        $sign = base64_encode($sign_info);
/////////////////////////  提交参数到扫码支付网关  ////////////////////////

        /**
         * curl方法提交支付参数到扫码网关https://api.efubill.com/gateway/api/h5apipay，并且获取返回值
         */
        $postdata = array(
            'sign' => $sign,
            'merchant_code' => $merchant_code,
            'order_no' => $order_no,
            'order_amount' => $order_amount,
            'service_type' => $service_type,
            'input_charset' =>$input_charset,
            'notify_url' => $notify_url,
            'interface_version' => $interface_version,
            'sign_type' => $sign_type,
            'order_time' => $order_time,

            'product_name' => $product_name,
            'client_ip' => $client_ip,
            'extend_param' => $extend_param,
            'extra_return_param' => $extra_return_param,
            'pay_type'=>$pay_type,

            'product_code' => $product_code,
            'product_desc' => $product_desc,
            'product_num' => $product_num,

            'return_url' => $return_url,
            'show_url' => $show_url,
            'redo_flag' => $redo_flag
            );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://pay.hofobao.com/?/pay");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);		
		curl_close($ch);

        $Key ="fdafas23r42493243l2432rfes";
        $timestamp =time();
        $newsign  =   $Key.$response.$timestamp.$order_no;


        $writedata = array(
            "sign" =>$newsign,
            "timestamp"=>$timestamp,
            "data"=>$response,
            "orderid"=>$order_no
        );

        $ch1 = curl_init();
        curl_setopt($ch1, CURLOPT_URL, "http://pay.gamewuzhou.com/interface.php");
        curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch1, CURLOPT_POST, true);
        curl_setopt($ch1, CURLOPT_HEADER, false);
        curl_setopt($ch1, CURLOPT_POSTFIELDS, http_build_query($writedata));
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch1);
        curl_close($ch1);

        return "http://pay.gamewuzhou.com/html/".$order_no.'.html';
    }



    //云成支付
    private function charge_yunchenpay($param){
        $mchId = "20000030";
        $appId = "545ac2b4d0a3464fbd54542d999a93bc";
        $passageId = "";

        $mchOrderNo = $param["order_no"];
        $productId = "8008";
        $currency = "cny";
        $amount = $param["order_amount"]*100;
        $device = Utility::getDevice();
        $notifyUrl = $this->CFG['URL']['Order'] . "/yc_notify.php";
        $product_name = $param['payType'] == 1 ? "金币" : '黄钻会员';
        $subject = $product_name;
        $body = $param['LoginCode'] . '|' . $param['payType'] . '|' . $param['CardID'];
        $param1 = "";
        $param2 = "";
        $extra = $_POST["extra"];


        $clientIp = "";
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $user_ip = $_SERVER['HTTP_CLIENT_IP'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $user_ip = $_SERVER['REMOTE_ADDR'];
        }


        $signdata = array(
            "mchId" => $mchId,
            "appId" => $appId,
            "mchOrderNo" => $mchOrderNo,
            "productId" => $productId,
            "currency" => $currency,
            "amount" => $amount,
            "subject" => $subject,
            "body" => $body,
            "notifyUrl" => $notifyUrl
        );

        $postdata =$signdata;
        if (!empty($passageId)) {
            $signdata["passageId"] = $passageId;
        }

        if (!empty($clientIp)) {
            $signdata["clientIp"] = $clientIp;
        }
        if (!empty($device)) {
            $signdata["device"] = $device;
        }

        if (!empty($param1)) {
            $signdata["param1"] = $param1;
        }

        if (!empty($param2)) {
            $signdata["param2"] = $param2;
        }

        if (!empty($extra)) {
            $signdata["extra"] = $extra;
        }

        $postdata["passageId"] = $passageId;
        $postdata["clientIp"] = $clientIp;
        $postdata["device"] = $device;
        $postdata["param1"] = $param1;
        $postdata["param2"] = $param2;
        $postdata["extra"] = $extra;

//签名
        $key = "6LQ2N5XGHAMR1I2VTUXCVKGVB8ZNVTX9FGYHDRXPN6HMTHZEW2ZCWBXAMFQUYVG5WO6BXIKHDDO5KWQJHQPGDBPJT1GHPPZXULPGMF9EVPM6ONHYUCSNUPO4PFGRCCPR";
        $sign = Utility::MakeSign($signdata,$key);
        $posturl = "http://api.53hjj.com/api/pay/create_order";
        $postdata["sign"] = $sign;
        $postjson = "params=".json_encode($postdata);

        $ret = Utility::postCurl($postjson,$posturl);
        $obj = json_decode($ret,false);
        if($obj->retCode=="SUCCESS"){
            return $obj->payParams->codeUrl;
        }
        else
        {
            //echo $obj->retMsg;
            return "";
        }

    }


    /**
     * 万通支付
     */
    public function charge_wtpay($param)
    {
        require_once ROOT_PATH.'Common/wtpay/wt.php';
        $payConf = $this->CFG['wtpay'];
        $params = array();
        $params["body"] = "万通支付";
        $params["funname"] = "prepay";
        $params["merid"] = $payConf['merid']; //商户id
        $params["notifyurl"] = $this->CFG['URL']['Order']."/wt_notify.php"; //回调地址
        $params["orderid"] = $param["order_no"];//订单号,自行生成
        $params["paymethod"] = "zfb";//支付方式
        $params["subject"] = "万通支付";//
        $params["totalfee"] = $param["order_amount"]; //金额元
        $params["tradetype"] = "MWEB";
        $kumo = new wt();
        $resultData = $kumo->send_kumo($params,$payConf['key'],$payConf['orderurl']);
        $result = xmlToArray($resultData);
        if ($result['flag'] == '00') {
            return $result['mweburl'];
        } else {
            return '';
        }
    }
}
?>