
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
//计算得出通知验证结果
include 'Include\jtpayconfig.php';

// http://order.game593.net/jtpay_return.php?p1_usercode=10210330&p2_order=20170306174710400040&p3_money=0.01&p4_status=1&p5_jtpayorder=20170306174714947418&p6_paymethod=3&p9_signtype=MD5&p7_paychannelnum=&p8_charset=UTF-8&p11_remark=800000%5e1%5e43&p10_sign=24D2C8F1F17CC34C1E81E178C88422BD

$signmsg=SIGN_KEY;
$md5info_paramet = $_REQUEST['p1_usercode']."&".$_REQUEST['p2_order']."&".$_REQUEST['p3_money']."&".$_REQUEST['p4_status']."&".$_REQUEST['p5_jtpayorder']."&".$_REQUEST['p6_paymethod']."&".$_REQUEST['p7_paychannelnum']."&".$_REQUEST['p8_charset']."&".$_REQUEST['p9_signtype']."&".$signmsg;
$sign= strtoupper(md5($md5info_paramet));

$return_sign=$_REQUEST['p10_sign'];

$p2_order = $_REQUEST['p2_order'];
$p3_money = $_REQUEST['p3_money'];
$p5_jtpayorder = $_REQUEST['p5_jtpayorder'];

    

    
if($sign==$return_sign) {//验证成功
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	


    //if($result == 1) {
    	    	    echo " 
              <DIV class=success>支付成功 </DIV><br/>
              <DIV class=regMsg>
              交易金额：￥".$p3_money."<br/>
              支付订单编号：".$p5_jtpayorder."<br/>
              商户订单编号：".$p2_order."<br/>
              </DIV>";
		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//如果有做过处理，不执行商户的业务程序
  // }
  // else {

  // 	echo "  <DIV class=success>支付失败</DIV> <DIV class=regMsg></DIV>";
  // }

		
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
  <DIV class=login1><A href="https://game593.net">返回首页</A> </DIV>
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
      <SCRIPT type=text/javascript>
          var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
          document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3Fa9b4407a24bb47344412f49bc3d63631' type='text/javascript'%3E%3C/script%3E"));
            </SCRIPT>
    </DIV>
  </DIV>
</DIV>
</BODY>
</HTML>
