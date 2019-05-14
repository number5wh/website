<?php
class Wkpay {
    public function __construct()
    {
        $this->_payurl = "http://wx.wangyua.cn/payapi/apiPayUrl.do";
        $this->_mchid = '513015545';

    }
    public function pay($params) {
        $res = $this->request($params);
        $res = json_decode($res,true);
        return $res;
    }

    //发送请求操作仅供参考,不为最佳实践
    public function request($params)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_payurl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//如果不加验证,就设false,商户自行处理
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $output = curl_exec($ch);
        curl_close($ch);
        return  $output;
    }
}