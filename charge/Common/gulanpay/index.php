<?php
class gulan {
    private $merid = '10100';
    private $url = 'https://888.dadernv.com/Pay_Index.html';
    private $key = 'q2ctw2naqemdfvmzgloz054xerk0o2wo';


    public function index($orderId, $amount, $notify, $method)
    {
        $native = array(
            "pay_memberid" => $this->merid,
            "pay_orderid" => $orderId,
            "pay_amount" => $amount,
            "pay_applydate" => date("Y-m-d H:i:s"),
            "pay_bankcode" => $method,
            "pay_notifyurl" => $notify,
            "pay_callbackurl" => $notify,
        );

        ksort($native);
        $md5str = "";
        foreach ($native as $key => $val) {
            $md5str = $md5str . $key . "=" . $val . "&";
        }
//echo($md5str . "key=" . $Md5key);
        $sign = strtoupper(md5($md5str . "key=" . $this->key));
        $native["pay_md5sign"] = $sign;
        $native['pay_productname'] ='gulanpay';


        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->url);

        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
        curl_setopt($ch, CURLOPT_HEADER,false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
// post数据
        curl_setopt($ch, CURLOPT_POST,1);
// post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($native));
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}
