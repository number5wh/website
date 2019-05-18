<?php

class JinkaPay {
    private $merno = '10071';
    private $head = 'https://www.cs689.cn';
    private $url = 'https://www.cs689.cn/Pay_Index.html';
    private $key = '9ojt2nk2y03p3xbzl3cr8k1teiftn7mm';

    public function index($amount, $orderId, $notifyUrl, $method)
    {

        $request = array(
            "pay_memberid"  => $this->merno,
            "pay_orderid" => $orderId,
            "pay_applydate" => date('Y-m-d H:i:s'),
            "pay_bankcode" =>$method,
            "pay_notifyurl" => $notifyUrl, //异步返回地址根据这个地址回调
            "pay_callbackurl" => $notifyUrl,//订单号
            "pay_amount" => $amount
        );
        $request['pay_md5sign'] = $this->psign($this->key, $request);

        $request['pay_productname'] = '金咖支付';

        $res = $this->send($this->url, $request);

        $replace = "src=\"".$this->head."/Uploads/codepay";
        $res = str_replace("src=\"/Uploads/codepay",$replace,$res);
        return $res;
    }

    //签名
    public function psign($key, $arr)
    {
        $str = '';
        ksort($arr);
        foreach($arr as $k=>$value)
        {
            $str.=$k.'='.$value.'&';
        }
        $str .= 'key='.$key;
        $checksign = strtoupper(md5($str));
//        var_dump($str, $checksign);
//        die;
        return $checksign;
    }

    public function send($url, $request)
    {
//        var_dump($url, $request);
//        die;
        //http_build_query($request)
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
        curl_setopt($ch, CURLOPT_HEADER,false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
// post数据
        curl_setopt($ch, CURLOPT_POST,1);
// post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($request));
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    public function notice()
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




