
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<meta name="B780_ONELINE_PAYMENT" content="China B780">
<HTML xmlns="http://www.w3.org/1999/xhtml">
<HEAD 
id=Head1>
<TITLE>779游戏-支付成功</TITLE>
<META content="text/html; charset=utf-8" http-equiv=Content-Type>
<META name=Keywords 
content=993,779游戏,779游戏官网,休闲游戏,棋牌游戏,双扣,牛牛,麻将,斗地主,Game918>
<META name=description 
content=779游戏平台是中国最专业的网络棋牌游戏中心之一，拥有各类棋牌游戏累计达数十种，包括人们熟知的牛牛、双扣、斗地主、麻将、四国军棋、连连看等>
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

require_once("Include/alipay.config.php");
require_once("Common/Alipay/lib/alipay_notify.class.php");
?>
    <?php
//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyReturn();
if($verify_result) {//验证成功
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//请在这里加上商户的业务逻辑程序代码
	
	//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
    //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

	//商户订单号

	$out_trade_no = $_GET['out_trade_no'];

	//支付宝交易号

	$trade_no = $_GET['trade_no'];

	//交易状态
	$trade_status = $_GET['trade_status'];

	//交易金额
	$total_fee = $_GET['total_fee'];

	//通知时间
	$notify_time = $_GET['notify_time'];



    if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
    	    	    echo " 
              <DIV class=success>支付成功 </DIV><br/>
              <DIV class=regMsg>
              交易金额：￥".$total_fee."<br/>
              支付订单编号：".$trade_no."<br/>
              商户订单编号：".$out_trade_no."<br/>
              成交时间：".$notify_time."
              </DIV>";
		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//如果有做过处理，不执行商户的业务程序
    }
    else {

			echo "  <DIV class=success>".$_GET['trade_status']."</DIV> <DIV class=regMsg></DIV>";
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
  <DIV class=login1><A href="http://game8893.com">返回首页</A> </DIV>
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
