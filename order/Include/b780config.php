<?php

//此文件为北网支付配置文件


class b780config
{
	//以下每一项都必须要配置，并准确
	var $public_spid 				="1690037477";												//卖家帐号
	var $public_sp_key				="game5939GAME99391212";									//密钥
	var $private_spid 				="1690037498";												//卖家帐号
	var $private_sp_key				="game5939GAME99391818";                                   //密钥
	var $spid               ="";
	var $sp_key             ="";									
	var $domain				="http://bynotify.game593.com:6012"	;								//商户网站域名
	var $tenpay_dir			="";											//北网支付安装目录
	var $site_name			="北网支付";												//商户网站名称
	var $attach				="";														//支付附加数据，非中文标准字符
	var $imgtitle			="北网支付"; 												//图片说明
	var $imgsrc				="/images/qbpay.jpg";									//图片地址
	var $pay_url			="http://www.b780.com/pay_gate"; 					//北网支付支付网关地址
	var $query_url			="http://www.b780.com/Queryorder"; 					//北网支付查询网关地址
	var $pay_returnurl		="/receive.php"	;											//商户接收支付返回地址	
	var $query_returnurl	="/receive.php"	;											//商户接收查询返回地址
	function b780config()
	{
		$this->imgsrc = $this->tenpay_dir.$this->imgsrc;
		$this->pay_returnurl = $this->domain . $this->tenpay_dir . $this->pay_returnurl;
		$this->query_returnurl = $this->domain . $this->tenpay_dir . $this->query_returnurl;

	}
	function getTransactionId()
	{
		return $this->spid.date("YmdHis").$this->getMicroSecond().rand(0,9).rand(0,9).rand(0,9); 
	}
	//Author     jinyuan   
	//取得毫秒（3位）   
	function getMicroSecond(){   
	   $m="0";   
	   $t=microtime(true);   
	   if(strpos($t,".")!=false)   
		   list($s,$m) = explode(".",$t);   
	   $m.="000";   
		  
	   return substr($m,0,3);   
	}  


}


?>
