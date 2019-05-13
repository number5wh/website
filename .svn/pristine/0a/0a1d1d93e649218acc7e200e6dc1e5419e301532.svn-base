
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<meta name="B780_ONELINE_PAYMENT" content="China B780">
<HTML xmlns="http://www.w3.org/1999/xhtml">
<HEAD 
id=Head1>
<TITLE>593游戏-支付成功</TITLE>
<META content="text/html; charset=utf-8" http-equiv=Content-Type>
<META name=Keywords 
content=993,593游戏,593游戏官网,休闲游戏,棋牌游戏,双扣,牛牛,麻将,斗地主,Game918>
<META name=description 
content=593游戏平台是中国最专业的网络棋牌游戏中心之一，拥有各类棋牌游戏累计达数十种，包括人们熟知的牛牛、双扣、斗地主、麻将、四国军棋、连连看等>
<LINK 
rel=stylesheet type=text/css href="/css/reg.css">
<LINK rel=stylesheet 
type=text/css href="/css/base.css">
<link rel="stylesheet" type="text/css" href="css/style.css">
<LINK rel=icon type=image/x-icon 
href="/favicon.ico">
<LINK rel="shortcut icon" type=image/x-icon 
href="/favicon.ico">
<META name=GENERATOR content="MSHTML 8.00.7601.17514">
</HEAD>
<BODY class=back>
<DIV class=title>
  <DIV class="title_1 left"></DIV>
  <DIV class="title_2 left"><IMG hspace=10 alt="" src="/images/pen.gif">
    <DIV></DIV>
  </DIV>
  <DIV class="title_3 left"></DIV>
  <BR class=clear>
</DIV>
<DIV class=regInfo>

<?php
/* * 
 * 功能：支付宝页面跳转同步通知页面
 * 版本：3.3
 * 日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。

 *************************页面功能说明*************************
 * 该页面可在本机电脑测试
 * 可放入HTML等美化页面的代码、商户业务逻辑程序代码
 * 该页面可以使用PHP开发工具调试，也可以使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyReturn
 */

include 'Include\heepayconfig.php';
require_once("Common/Alipay/lib/alipay_notify.class.php");
?>
    <?php
//计算得出通知验证结果

    $result = $_GET['result'];
    $pay_message = $_GET['pay_message'];
    $agent_id = $_GET['agent_id'];
    $jnet_bill_no = $_GET['jnet_bill_no'];
    $agent_bill_id = $_GET['agent_bill_id'];
    $pay_type = $_GET['pay_type'];
    $pay_amt = $_GET['pay_amt'];
    $remark = $_GET['remark'];
    $return_sign=$_GET['sign'];
    //$cardType = 36;// 汇付宝微信支付
    
    $remark = iconv("GB2312","UTF-8//IGNORE",urldecode($remark));//签名验证中的中文采用UTF-8编码;
    
    $ret = explode('^',$remark);
    $signStr='';
    $signStr  = $signStr . 'result=' . $result;
    $signStr  = $signStr . '&agent_id=' . $agent_id;
    $signStr  = $signStr . '&jnet_bill_no=' . $jnet_bill_no;
    $signStr  = $signStr . '&agent_bill_id=' . $agent_bill_id;
    $signStr  = $signStr . '&pay_type=' . $pay_type;
    
    $signStr  = $signStr . '&pay_amt=' . $pay_amt;
    $signStr  = $signStr .  '&remark=' . $remark;
    
    $signStr = $signStr . '&key=' . ALI_SIGN_KEY; //商户签名密钥
    
    $sign='';
    $sign=md5($signStr);    
    
    
if($sign==$return_sign) {//验证成功
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	


    if($result == 1) {
    	    	    echo " 
              <DIV class=success>支付成功 </DIV><br/>
              <DIV class=regMsg>
              交易金额：￥".$pay_amt."<br/>
              支付订单编号：".$jnet_bill_no."<br/>
              商户订单编号：".$agent_bill_id."<br/>
              </DIV>";
		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//如果有做过处理，不执行商户的业务程序
    }
    else {

			echo "  <DIV class=success>支付失败</DIV> <DIV class=regMsg></DIV>";
    }

		
	//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
else {
    //验证失败
    //如要调试，请看alipay_notify.php页面的verifyReturn函数
			echo "  <DIV class=success>验证失败</DIV> <DIV class=regMsg></DIV>";
}
?>

  <DIV class=line></DIV>
  <DIV class=login1><A href="https://game593.com">返回首页</A> </DIV>
  <DIV class=regInfo_bg></DIV>
</DIV>
<DIV class=regInfo_bottom></DIV>
<!--底部-->
<DIV class=bottom>
  <DIV class=bg>
    <DIV style="MARGIN: 30px auto 0px; WIDTH: 832px">
      <DIV class="k1 left"></DIV>
      <DIV class="k2 left">抵制不良游戏，拒绝盗版游戏，注意自我保护，谨防受骗上当，适度游戏益脑，沉迷游戏伤身，合理安排时间，健康享受生活-<A 
href="#">《健康游戏忠告》</A></DIV>
      <DIV class="k3 left"></DIV>
      <BR class=clear>
      <DIV class=copyright style="margin-top:10px">
        
        浙江通易网络科技有限公司  
        版权所有 </DIV>

    </DIV>
  </DIV>
</DIV>
</BODY>
</HTML>
