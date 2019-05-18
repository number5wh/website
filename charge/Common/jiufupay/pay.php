<?php
class jiufu {
    private $merId = '108000000002949';
    private $apikey = 'fkr562gi7u0t9zbeph4ubxb15ipo4s22j5dhcvwb2aa4pqyt2cr4ma2z72xn2b9r';

    public function index($orderNo, $amount, $notifyUrl, $method, $defaultbank, $isApp)
    {
        require_once 'utils.php';
        $date = date("Ymd");
        $params['body'] = "jiufupay";                           //商品的具体描述，选填
        $params['charset'] = "utf-8";                           //参数编码字符集，必填
        $params['orderNo'] = $orderNo;                          //商户订单号，务必确保在系统中唯一，必填
        $params['totalFee'] = $amount;                          //订单金额，单位为RMB元，必填
//        $params['defaultbank'] = 'ALIPAY';                           //网银代码，当支付方式为bankPay时，该值为空；支付方式为directPay时该值必传
        $params['defaultbank'] = $defaultbank;                           //网银代码，当支付方式为bankPay时，该值为空；支付方式为directPay时该值必传
        $params['title'] = 'jiufupay';                              //商品的名称，请勿包含字符，选填
//        $params['paymethod'] = 'directPay';                       //支付方式，directPay：直连模式；bankPay：收银台模式，必填
        $params['paymethod'] = $method;                       //支付方式，directPay：直连模式；bankPay：收银台模式，必填
        $params['service'] = "online_pay";                      //固定值online_pay，表示网上支付，必填
        $params['paymentType'] = "1";                           //支付类型，固定值为1，必填
        $params['merchantId'] = $this->merId;                    //支付平台分配的商户ID，必填
        $params['returnUrl'] = 'http://www.fz1696.com';         //支付成功跳转URL，仅适用于支付成功后立即返回商户界面，必填
        $params['notifyUrl'] = $notifyUrl;    //商户支付成功后，该地址将收到支付成功的异步通知信息，该地址收到的异步通知作为发货依据，必填

//        $params['isApp'] = "H5";
        $params['isApp'] = $isApp;


        $baseUri = 'https://ebank.9due.com'.'/payment/v1/order/'.$this->merId.'-'.$orderNo;
        $params['sign'] = utils::Sign($params,$this->apikey);
        $params['signType'] = "SHA";//signType不参与加密，所以要放在最后
 //       $HtmlStr = utils::curl_post($params, $baseUri);
        $HtmlStr = utils::postHtml($baseUri,$params);
        return $HtmlStr;
    }
}
