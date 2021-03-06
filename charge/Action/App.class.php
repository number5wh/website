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
//        var_dump($LoginID, $ret);
//        die;
		if ($ret['szLoginName'] !== '') {
			return false;
		} else {
			return true;
		}

	}


	/*
    * 多得宝支付接口
    */
    public function payment(){
        $params = Utility::request(['LoginCode', 'payType', 'cardType', 'total_fee', 'username']);
//        var_dump($params);
//        die;
//        if ($this->check_logincode($params['LoginCode'])) {
//            Utility::response(-1, '账号不存在');
//            return;
//        }

        $cardType = $params['cardType']; //类别 classID
        $data['cardType'] = $cardType;
        $data['LoginCode'] = $params['LoginCode'];
        $data['order_amount'] = $params['total_fee'];
        $data['payType'] = $params['payType'];  //通道 channelID
        $username = $params['username'];
        $username = $username ? Utility::utf8ToGb2312($username) : '';


        $result = OSGetPayOrderID('');
        if ($result['iResult'] != 0) {
            Utility::response(-2, '生成支付订单失败');
        } else {

            $data['order_no'] = $result['szOrderNo'];
            $objDataCenter = new DataCenterBLL();
//            $CardID = $objDataCenter->getCardID($cardType);
//            if ($CardID == -1) {
//                Utility::response(-3, '充值方式错误');
//                return;
//            }
            $ret = OSCreatePayOrder($cardType, $data['payType'], $username, $data['order_no'], $data['order_amount']*100, $data['LoginCode']);
            if ($ret['iResult'] == 0) {
                if ($cardType == 1500 || $cardType == 1600) {
                    Utility::response(0, '提交成功');
                } else {
                    if ($data['payType'] == 120) {
                        $returnArr = $this->charge_wtpay($data);
                    } elseif ($data['payType'] == 130) {
                        $returnArr = $this->charge_yspay($data);
                    } elseif ($data['payType'] == 140) {
                        $returnArr = $this->charge_wkpay($data);
                    } elseif ($data['payType'] == 150) {
                        $returnArr = $this->charge_hftpay($data);
                    }elseif ($data['payType'] == 160) {
                        $returnArr = $this->charge_huyapay($data);
                    }elseif ($data['payType'] == 170) {
                        $returnArr = $this->charge_jinkapay($data);
                    }elseif ($data['payType'] == 180) {
                        $returnArr = $this->charge_weilianpay($data);
                    }elseif ($data['payType'] == 190) {
                        $returnArr = $this->charge_gulanpay($data);
                    }elseif ($data['payType'] == 200) {
                        $returnArr = $this->charge_jiufupay1($data);
                    }elseif ($data['payType'] == 210) {
                        $returnArr = $this->charge_jiufupay2($data);
                    }elseif ($data['payType'] == 220) {
                        $returnArr = $this->charge_yunchenpay($data);
                    }elseif ($data['payType'] == 230) {
                        $returnArr = $this->charge_yilianbaopay($data);
                    }elseif ($data['payType'] == 240) {
                        $returnArr = $this->charge_98pay($data);
                    }elseif ($data['payType'] == 250) {
                        $returnArr = $this->charge_longfapay($data);
                    }elseif ($data['payType'] == 260) {
                        $returnArr = $this->charge_xinfapay($data);
                    }
                }

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
        if ($param['cardType'] == 1200) {
            $params["paymethod"] = "zfb";//支付宝
            $params["tradetype"] = "MWEB";
        } elseif ($param['cardType'] == 1300) {
            $params["paymethod"] = "wx";//微信
        } elseif ($param['cardType'] == 1400) {
            $params["paymethod"] = "unionpay";//银联
            $params["tradetype"] = "MWEB";
        }

        $params["subject"] = "万通支付";//
        $params["totalfee"] = $param["order_amount"]; //金额元
        $kumo = new wt();
        $resultData = $kumo->send_kumo($params,$payConf['key'],$payConf['orderurl']);
        $result = xmlToArray($resultData);
        if ($result['flag'] == '00') {
            return $result['mweburl'];
        } else {
            return '';
        }
    }

    /**
     * 永顺支付
     */
    public function charge_yspay($param)
    {
        require_once ROOT_PATH.'Common/yspay/erweima.php';
        $goodInfo = '永顺支付';
        $orderId = $param["order_no"];//订单号,自行生成
        list($s1, $s2)	=	explode(' ', microtime());
        list($ling, $haomiao)=	explode('.', $s1);
        $haomiao    =	substr($haomiao,0,3);
        $requestId = date('YmdHis').$haomiao;
        $amount = $param["order_amount"]*100;
        if ($param['cardType'] == 1200) {
            $method = "0201";//支付宝
        } elseif ($param['cardType'] == 1300) {
            $method = "0101";//微信
        }
        $ysPay = new TradeClient();
        $res = $ysPay->invoke($requestId, $orderId, $goodInfo, $amount, $method);
        $res = json_decode($res, true);
        if ($res['key'] == '00' || $res['key'] == '05') {
            $result = json_decode($res['result'], true);
            return $result['url'];
        } else {
            return '';
        }
    }

    /**
     * 万咖支付
     */
    public function charge_wkpay($param)
    {
        require_once ROOT_PATH.'Common/wkpay/index.php';
        $payApi = 'icse4ij4m32rzyotbk1w65bs4qxwdrr3';
        $mchid = '513015545';
        $paramArr = array();
        $paramArr['order_no'] = $param['order_no'];
        $paramArr['amount'] = $param["order_amount"];
        $paramArr['subject'] = '万咖支付';
        $paramArr['paytype'] = 1;
        $paramArr['return_url'] = $this->CFG['URL']['Order']."/wk_notify.php";
        $paramArr['order_time'] = date('YmdHis');
        $paramArr['mch_id'] = $mchid;
        $paramArr['sign'] = md5($param['order_no'].$param['order_amount'].$payApi.$mchid);

        $wkPay = new Wkpay();
        $res = $wkPay->pay($paramArr);
        if ($res['err_code'] == 0) {
            return $res['data']['qrcode'];
        } else {
            return '';
        }
    }

    /**
     * 恒丰泰
     */
    public function charge_hftpay($param)
    {
        require_once ROOT_PATH.'Common/hftpay/tiandihui.php';
        $hftPay = new Hftpay();
        $res = $hftPay->index($param["order_amount"], $param["order_no"], $notifyUrl = $this->CFG['URL']['Order']."/hft_notify.php");
        $res = json_decode($res, true);
        if ($res['code'] == 0) {
            return $res['data']['qr_code_url'];
        } else {
            return '';
        }
    }

    /**
     * 虎牙支付
     */
    public function charge_huyapay($param)
    {
        require_once ROOT_PATH.'Common/huyapay/yidao.php';
        $ydpay = new yidao();
        $method = '';
        $notifyUrl = '';
        if ($param['cardType'] == 1200) {
            $method = "ZFBH5";//支付宝
            $notifyUrl = $this->CFG['URL']['Order']."/huya_notify_zfb.php";
        } elseif ($param['cardType'] == 1300) {
            $method = "WXH5";//微信
            $notifyUrl = $this->CFG['URL']['Order']."/huya_notify_wx.php";
        }

        $data = $ydpay->pay($param["order_no"],"虎牙支付",$param["order_amount"], $method, $notifyUrl);
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://pay.huyazf.com/externalSendPay/rechargepay.do");

        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
        curl_setopt($ch, CURLOPT_HEADER,false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
// post数据
        curl_setopt($ch, CURLOPT_POST,1);
// post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($output,true);
//var_dump($response);die;
        $txnTime = $response['responseObj']['txnTime'];//存储交易时间，查询订单接口必要参数。
        $orgOrderNo = $response['responseObj']['orgOrderNo'];//订单号，查询订单接口必要参数。
        $qrCode =$response['responseObj']['qrCode'];
        if (!$response['responseObj']['qrCode']){
            return "";
        } else {
            return $qrCode;
        }
    }

    //金咖
    public function charge_jinkapay($param)
    {
        require_once ROOT_PATH.'Common/jinkapay/index.php';
        $jkpay = new JinkaPay();
        $method = $notifyUrl = '';
        if ($param['cardType'] == 1200) {
            $method = "903";//支付宝
            $notifyUrl = $this->CFG['URL']['Order']."/jinka_notify_zfb.php";
        }
        $res = $jkpay->index($param["order_amount"], $param["order_no"], $notifyUrl , $method);

        $Key ="fdafas23r42493243l2432rfes";
        $timestamp =time();
        $newsign  =   $Key.$timestamp.$param["order_no"];


        $writedata = array(
            "sign" =>$newsign,
            "timestamp"=>$timestamp,
            "data"=>$res,
            "orderid"=>$param["order_no"]
        );

        $ch1 = curl_init();
        curl_setopt($ch1, CURLOPT_URL, "http://pay.fz1696.com/interface.php");
        curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch1, CURLOPT_POST, true);
        curl_setopt($ch1, CURLOPT_HEADER, false);
        curl_setopt($ch1, CURLOPT_POSTFIELDS, http_build_query($writedata));
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch1);
        curl_close($ch1);
        return "http://pay.fz1696.com/html/".$param["order_no"].'.html';
    }


    //微联
    public function charge_weilianpay($param)
    {
        require_once ROOT_PATH.'Common/weilianpay/pay.php';
        $method = $notifyUrl = '';
        if ($param['cardType'] == 1200) {
            $method = "992";//支付宝
            $notifyUrl = $this->CFG['URL']['Order']."/weilian_notify_zfb.php";
        }elseif ($param['cardType'] == 1300) {
            $method = "1006";//微信
            $notifyUrl = $this->CFG['URL']['Order']."/weilian_notify_wx.php";
        }
        $wlpay = new weilian($method, $param['order_amount'],$param["order_no"], $notifyUrl);
        $res = $wlpay->send();
        $preg='/<a .*?href="(.*?)".*?>/is';
        preg_match_all($preg, $res, $matches);

        return $matches[1];
//        $dom = new DOMDocument;
//        $dom->loadHTML($res);
//        $data = '';
//        foreach ($dom->getElementsByTagName('a') as $node) {
//            $data = $node->getAttribute( 'href' );
//        }
//        return $data;
    }

    //谷岚
    public function charge_gulanpay($param)
    {
        require_once ROOT_PATH.'Common/gulanpay/index.php';
        $gulan = new gulan();
        $method = $notifyUrl = '';
        if ($param['cardType'] == 1300) {
            $method = "901";//微信
            $notifyUrl = $this->CFG['URL']['Order']."/gulan_notify_wx.php";
        } else {
            return '';
        }
        $res = $gulan->index($param["order_no"], $param['order_amount'], $notifyUrl, $method);
        $pre = "<!doctype html><html><head><meta charset=\"utf8\"><title>正在跳转</title></head><body><script type=\"text/javascript\">location.href=\"";
        $end = "\"</script></body></html>";
        $res = str_replace($pre, '', $res);
        $res = str_replace($end, '', $res);
       if ($res) {
           return $res;
       } else {
           return '';
       }
    }


    //玖富H5
    public function charge_jiufupay1($param)
    {

        require_once ROOT_PATH.'Common/jiufupay/pay.php';
        $jiufu = new jiufu();
        $method = $notifyUrl = $isApp = $defaultbank = '';
        if ($param['cardType'] == 1300) {
            $notifyUrl = $this->CFG['URL']['Order']."/jiufu_notify_wx.php";
            $method = 'directPay';
            $isApp = 'h5';
            $defaultbank = 'WXPAY';
        } elseif ($param['cardType'] == 1200) {
            $notifyUrl = $this->CFG['URL']['Order']."/jiufu_notify_zfb.php";
            $method = 'directPay';
            $isApp = 'h5';
            $defaultbank = 'ALIPAY';
        } elseif ($param['cardType'] == 1400) {
            $notifyUrl = $this->CFG['URL']['Order']."/jiufu_notify_yl.php";
            $method = 'directPay';
            $isApp = 'web';
            $defaultbank = 'UNIONQRPAY';
        } else {
            return '';
        }

//        var_dump($param['cardType'], $defaultbank);
//        die;
        $res = $jiufu->index($param["order_no"], $param['order_amount'], $notifyUrl, $method, $defaultbank, $isApp);

        $Key ="fdafas23r42493243l2432rfes";
        $timestamp =time();
        $newsign  =   $Key.$timestamp.$param["order_no"];


        $writedata = array(
            "sign" =>$newsign,
            "timestamp"=>$timestamp,
            "data"=>$res,
            "orderid"=>$param["order_no"]
        );

        $ch1 = curl_init();
        curl_setopt($ch1, CURLOPT_URL, "http://pay.fz1696.com/interface.php");
        curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch1, CURLOPT_POST, true);
        curl_setopt($ch1, CURLOPT_HEADER, false);
        curl_setopt($ch1, CURLOPT_POSTFIELDS, http_build_query($writedata));
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch1);
        curl_close($ch1);
//        var_dump("http://pay.fz1696.com/html/".$param["order_no"].'.html');
//        die;
        return "http://pay.fz1696.com/html/".$param["order_no"].'.html';
    }

    //玖富扫码
    public function charge_jiufupay2($param)
    {
        require_once ROOT_PATH.'Common/jiufupay/pay.php';
        $jiufu = new jiufu();
        $method = $notifyUrl = $isApp = $defaultbank = '';
        if ($param['cardType'] == 1300) {
            $notifyUrl = $this->CFG['URL']['Order']."/jiufu_notify_wx.php";
            $method = 'directPay';
            $isApp = 'web';
            $defaultbank = 'WXPAY';
        } elseif ($param['cardType'] == 1200) {
            $notifyUrl = $this->CFG['URL']['Order']."/jiufu_notify_zfb.php";
            $method = 'directPay';
            $isApp = 'web';
            $defaultbank = 'ALIPAY';
        } elseif ($param['cardType'] == 1400) {
            $notifyUrl = $this->CFG['URL']['Order']."/jiufu_notify_yl.php";
            $method = 'directPay';
            $isApp = 'web';
            $defaultbank = 'UNIONQRPAY';
        } else {
            return '';
        }

        $res = $jiufu->index($param["order_no"], $param['order_amount'], $notifyUrl, $method, $defaultbank, $isApp);
        $Key ="fdafas23r42493243l2432rfes";
        $timestamp =time();
        $newsign  =   $Key.$timestamp.$param["order_no"];


        $writedata = array(
            "sign" =>$newsign,
            "timestamp"=>$timestamp,
            "data"=>$res,
            "orderid"=>$param["order_no"]
        );

        $ch1 = curl_init();
        curl_setopt($ch1, CURLOPT_URL, "http://pay.fz1696.com/interface.php");
        curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch1, CURLOPT_POST, true);
        curl_setopt($ch1, CURLOPT_HEADER, false);
        curl_setopt($ch1, CURLOPT_POSTFIELDS, http_build_query($writedata));
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch1);
        curl_close($ch1);
        return "http://pay.fz1696.com/html/".$param["order_no"].'.html';
    }

    //云成
    private function charge_yunchenpay($param){
        $mchId = "20000069";
        $appId = "4f992ae2e2054eee8702b3ae1407dc28";
        $passageId = "";

        $mchOrderNo = $param["order_no"];
        $productId = "8015";
        $currency = "cny";
        $amount = $param["order_amount"]*100;
        $device = Utility::getDevice();
        if ($param['cardType'] == 1200) {
            $notifyUrl = $this->CFG['URL']['Order'] . "/yc_notify.php";
        } else {
            return '';
        }

        $product_name = '云成支付';
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
        $key = "2R7BIIWIJMGQY51W8XBX4AVMUROFUTYOTBSNPY2AGKBI1KD2RV7VZUEIPNT7N5VCMU89G0JUUCRZUFQZHDTH31DUJ7KNP1ACJF7VXMEL5OSK8NS9YTNM6FC5COWXHDQA";
        $sign = Utility::MakeSign($signdata,$key);
        $posturl = "http://api.dahm888.com/api/pay/create_order";
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
     * 易联保(金额单位分)
     */
    public function charge_yilianbaopay($param)
    {
        return '';
        require_once ROOT_PATH.'Common/yilianbaopay/test.php';

        $yilianbao = new Yilianbao();
        $method = $notifyUrl = $type =  '';
        if ($param['cardType'] == 1200) {//
            $notifyUrl = $this->CFG['URL']['Order']."/yilianbao_notify_zfb.php";
            $method = '01';//zfb
            $type = '02';//h5
        }  else {
            return '';
        }

        $res = $yilianbao->pay($param['order_no'], $param['order_amount']*100, $notifyUrl, $method, $type);
        $res = json_decode($res, true);
        if ($res['resultCode'] == '000000') {
            return $res['payInfo'];
        } else {
            return '';
        }
    }

    /**
     * 98支付H5 单位分
     */
    public function charge_98pay($param)
    {
        require_once ROOT_PATH.'Common/98pay/pay.php';
        $method = $notifyUrl = $type =  $cid= $key ='';
        if ($param['cardType'] == 1200) {//
            $notifyUrl = $this->CFG['URL']['Order']."/lefu98_notify_zfb.php";
            $type = '1004';//zfb
            $cid =10446;
            $key = 'CB4C23C80471E26E38B4E065EB2B236B';
        }  elseif ($param['cardType'] == 1300) {
            $notifyUrl = $this->CFG['URL']['Order']."/lefu98_notify_wx.php";
            $type = '1001';//wx
            $cid =10445;
            $key = '2FBDAC6C4F4828D8480D2E265A86BFFD';
        } else {
            return '';
        }
        $model = new Lefu($param['LoginCode'],$param['order_amount']*100,$param['order_no'],$notifyUrl, $type, $cid,$key);
        $res = $model->send();
        $res = json_decode($res, true);

        if ($res['code'] == 0) {
            return $res['payUrl'];
        } else {
            return '';
        }
    }

    /**
 * 隆发  单位分
 */
    public function charge_longfapay($param)
    {
        require_once ROOT_PATH.'Common/longfapay/pay.php';
        $method = $notifyUrl = $type =  '';
        if ($param['cardType'] == 1200) {//
            $notifyUrl = $this->CFG['URL']['Order']."/longfa_notify_zfb.php";
            $type = 'ZFB_WAP';//zfb
        }  elseif ($param['cardType'] == 1300) {
            $notifyUrl = $this->CFG['URL']['Order']."/longfa_notify_wx.php";
            $type = 'WX_WAP';//wx
        } else {
            return '';
        }

        $model = new pay();
        $rows = $model->pay($param['order_no'], $type, $notifyUrl, $param['order_amount']*100);
        if ($rows['stateCode'] == '00'){
            return $rows['qrcodeUrl'];
        }else{
            return '';
        }
    }

    /**
     * 鑫发  单位分
     */
    public function charge_xinfapay($param)
    {
        require_once ROOT_PATH.'Common/xinfapay/pay.php';
        $method = $notifyUrl = $type =  '';
        if ($param['cardType'] == 1200) {//
            $notifyUrl = $this->CFG['URL']['Order']."/xinfa_notify_zfb.php";
            $type = 'ZFB_WAP';//zfb
        }  elseif ($param['cardType'] == 1300) {
            $notifyUrl = $this->CFG['URL']['Order']."/xinfa_notify_wx.php";
            $type = 'WX_WAP';//wx
        } else {
            return '';
        }

        $model = new pay();
        $rows = $model->pay($param['order_no'], $type, $notifyUrl, $param['order_amount']*100);
        if ($rows['stateCode'] == '00'){
            return $rows['qrcodeUrl'];
        }else{
            return '';
        }
    }
}
?>