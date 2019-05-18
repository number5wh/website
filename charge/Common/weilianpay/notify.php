<?php
/*
===============================================================================
接收银行支付的下行数据

orderid	
上行过程中传入的orderid

opstate	
操作结果状态:
	0 在线支付成功
	-1 请求参数无效
	-2 签名错误

ovalue	
实际支付的金额，单位元

sysorderid
	支付平台系统的订单Id。

systime
	支付平台系统的订单结束时间。格式为年/月/日 时：分：秒，如2010/04/05 21:50:58。
================================================================================
*/
$key = "34be409f05e14c17a19055292dce6311";
$orderid = @$_GET["orderid"];
$opstate = @$_GET["opstate"];
$ovalue = @$_GET["ovalue"];
$sign = @$_GET["sign"];
$sysorderid = @$_GET["sysorderid"];
$systime = @$_GET["systime"];
$attach = @$_GET["attach"];
$msg = @$_GET["msg"];
$signCheck = strtolower(md5("orderid=".$orderid."&opstate=".$opstate."&ovalue=".$ovalue.$key));
if($sign == $signCheck){
    if($opstate == "0"){
        //支付成功,这里执行支付成功的业务逻辑

    }else if($opstate == "-1"){

    }else if($opstate == "-2"){

    }
    echo("opstate=0");
}else{
    echo("opstate=-2");
}
?>