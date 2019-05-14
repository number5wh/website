<?php

class Hftpay {
    function index($amount, $orderId, $notifyUrl)
    {

        $request = array(
            "version"  => 2,
            "merchant_number" => 1165,//商户号 商户后台获取
            "cash" => $amount, //金额
            "server_url" =>$notifyUrl,// 同步返回地址
            "brower_url" => $notifyUrl, //异步返回地址根据这个地址回调
            "order_id" => $orderId,//订单号
            "order_time" => time(),
            "pay_type" => 1, //支付方式，后台看1.支付宝扫码;2.支付宝H5;3.微信扫码;4.微信H5; 5.银联扫码;6.网关;7.快捷;8.固码
        );

        $key = '59914131-19ee-4fd8-aad2-4fdc4797909c' ; //商户后台获取
//        $url = "http://www.tiandihuipay.com/api/pay/scanpay";//提交地址
//        $url = 'http://www.hftppay.com/api/recharge/index';
        $url = 'http://www.hftppay.com/api/recharge/scanpay';
        $request['sign'] = $this->psign($key, $request);
        $re = $this->send($url, $request);
        return $re;
    }

    //签名
    function psign($key, $param)
    {
        ksort($param);
        return md5(urldecode(http_build_query($param)) .'&key='. $key);
    }

    function send($url, $request)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($request));
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    function notice()
    {
        $request = (array)$this->request->param();
        $returnArray = array( //
                              "merchant_number" => $request["merchant_number"],
                              "order_id" =>  $request["order_id"],
                              "cash" =>  $request["cash"],
                              "order_time" =>  $request["order_time"],
                              "status" =>  $request["status"],
                              "notify_type" => $request["notify_type"]
        );
        //验证签名
//        if () {
//            //你的逻辑
//            exit('success');//给我们返回Success
//        }
    }
}




