<?php

class Lefu
{
    /**
     * cid
     */
    private $cid = 10445;

    /*
    * 玩家ID
    */
    private $userId;

    /*
    * 支付金额，单位分
    */
    private $value;

    /*
    * 请求发起方自己的订单号，该订单号将作为支付平台的返回数据
    */
    private $orderid;

    /*
    * 在下行过程中返回结果的地址，需要以http://开头。
    */
    private $callbackurl = "";

    /*
    * 支付完成之后支付平台会自动跳转回到的页面
    */
    private $hrefbackurl = "http://www.fz1696.com";
    /*
* 商户密钥
*/
    private $key = '2FBDAC6C4F4828D8480D2E265A86BFFD';

    /**
     * 支付类型 1001微信H5 1004支付宝H5
     */
    private $type = '1001';

    public function __construct($userId, $value, $orderid, $backurl, $type, $cid, $key)
    {
        $this->userId        = $userId;
        $this->value       = $value;
        $this->orderid     = $orderid;
        $this->callbackurl = $backurl;
        $this->type = $type;
        $this->cid = $cid;
        $this->key = $key;
    }


    public function send()
    {
        $payerIp = ($_SERVER["HTTP_VIA"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER["REMOTE_ADDR"];
        $gateWay = "http://369now.net/pay/topup";
        $info  = "98pay";

        $timestamp = time();
        $sign    = md5($timestamp . $this->cid . $this->type . $this->value . $this->key);

        $param = [
            'amount'      => $this->value,
            'returnUrl'   => $this->hrefbackurl,
            'appUserId'   => $this->userId,
            'info'        => $info,
            'extra'       => $this->orderid,
            'notifyUrl'   => $this->callbackurl,
            'type'        => $this->type,
            'cid'        => $this->cid,
            'ip'     => $payerIp,
            'time'  => $timestamp,
            'sign'  => $sign
        ];
        $str   = '';
        foreach ($param as $k => $v) {
            $str .= $k . '=' .$v. '&';
        }
        $str = rtrim($str, '&');
        //$str = urlencode($str);
        $gateWay .= '?' . $str;
//        var_dump($gateWay);
//        die;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $gateWay);
        // 执行后不直接打印出来
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        // 跳过证书检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // 不从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        //执行并获取HTML文档内容
        $output = curl_exec($ch);
        //释放curl句柄
        curl_close($ch);
        return $output;
    }
}

?>