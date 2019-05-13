
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
/* $str = '';
foreach ($ret as $key=>$val){
    $str = $str.'&'.$key.'='.$val;
}
var_dump($str);
 */
require 'Include/Init.inc.php';
require_once ROOT_PATH.'Link/BuyHappyBean.php';
require_once ROOT_PATH.'Link/BuyVIP.php';
require_once ROOT_PATH.'Link/AddPayLogs.php';
require_once ROOT_PATH.'Link/FindPayOrder.php';
require_once ROOT_PATH.'Link/GetPayOrder.php';
require_once ROOT_PATH.'Link/SetPayOrderStatus.php';
require_once ROOT_PATH.'Link/UpdatePayConfig.php';
require_once ROOT_PATH.'Link/LockOrder.php';
require_once ROOT_PATH.'Link/UnLockOrder.php';

$arrConfig = unserialize(SYS_CONFIG);

/* 
回调测试 */

/* $_GET = '{"version":"2.0","cmdno":"1","pay_result":"0","pay_info":"","date":"20151029","bargainorId":"1690035325","transactionId":"169003532520151029174029573060","sp_orderno":"201510291740292382","totalFee":"50.0","burden":"","paytype":"3001","cardType":"3001","remark":"804183^1^19","sign":"FE27EB0B13A243CFEB7D76ED8ACB704C","autosend":"1"}';
$_GET = json_decode($_GET);
$_GET = (array)$_GET;   */
	/*取返回参数*/
        $cmd_no         = trim($_GET['cmdno']);
        $pay_result     = trim($_GET['pay_result']);
        $pay_info       = trim($_GET['pay_info']);
        $bill_date      = trim($_GET['date']);
        $bargainor_id   = trim($_GET['bargainorId']);
        $transaction_id = trim($_GET['transactionId']);
        $sp_billno      = trim($_GET['sp_orderno']);
        $total_fee      = trim($_GET['totalFee']);
        $fee_type       = trim($_GET['cardType']);
        $burden         = trim($_GET['burden']);
		$attach         = trim($_GET['remark']);
        $sign           = trim($_GET['sign']);

        /*  打印日志         */
        $file_name =basename($_SERVER['PHP_SELF'],'.php');
        Utility::Log($file_name, 'receive_info', json_encode($_REQUEST));
        
        $ret = explode('^',$attach);
        OSAddPayLogs($pay_result,$pay_info,$bill_date,$bargainor_id,$transaction_id,$sp_billno,$total_fee,$burden,$fee_type,$ret[0]);
		include_once("Include/b780config.php");
		$tenpay_conf = new b780config();
		if($bargainor_id == $tenpay_conf->private_spid)
		  $key = $tenpay_conf->private_sp_key;		
		else if($bargainor_id == $tenpay_conf->public_spid){
		  $key = $tenpay_conf->public_sp_key;		
		}else{
		    $key = $tenpay_conf->sp_key;
		}

        /* 检查数字签名是否正确 */
        $sign_text  = "cmdno=" . $cmd_no . "&pay_result=" . $pay_result . "&date=" . $bill_date . 
					  "&transactionId=" . $transaction_id . "&sp_orderno=" . $sp_billno . "&totalFee=" . $total_fee .
                      "&burden=" . $burden."&cardType=" . $fee_type . "&remark=" . $attach . "&key=" . $key;
        $sign_md5 = strtoupper(md5($sign_text));
        if ($sign_md5 !== $sign)
        {
			echo "  <DIV class=success>支付失败！</DIV> <DIV class=regMsg>--签名错误</DIV>";
        }
        else
        {   
           if(OSLockOrder($ret[2], $sp_billno)['iResult']==0){           
               if(OSFindPayOrder($ret[2],$transaction_id,$sp_billno,0)['iResult'] != 0){   //这笔订单已经处理过了
                   $order = OSGetPayOrder($sp_billno, $ret[2]);
                   if($order['iStatus']==3){
                       echo "<DIV class=success>充值已到账，登陆游戏查询余额 </DIV><br/>";
                   }else if($order['iStatus']==1){
                       echo "<DIV class=success>服务器处理失败，请联系客服  </DIV><br/>";
                   }if($order['iStatus']==2){
                       echo "<DIV class=success>支付失败 </DIV><br/>";
                   }if($order['iStatus']==4){
                       echo "<DIV class=success>服务器处理失败，请联系客服 </DIV><br/>";
                   }
                       echo " 
                        <DIV class=regMsg>
                                                                                      交易金额：￥".$total_fee."<br/>
                                                                                      支付订单编号：".$transaction_id."<br/>
                                                                                      商户订单编号：".$sp_billno."<br/>
                                                                                      成交时间：".$bill_date."
                        </DIV>";
    			/* 如果pay_result大于0则表示支付失败 */
                }else{
        			if ($pay_result == 0)
        			{
        			    echo " 
                      <DIV class=success>支付成功 </DIV><br/>
                      <DIV class=regMsg>
                                                                              交易金额：￥".$total_fee."<br/>
                                                                              支付订单编号：".$transaction_id."<br/>
                                                                              商户订单编号：".$sp_billno."<br/>
                                                                              成交时间：".$bill_date."
                      </DIV>";
    				    if(OSFindPayOrder($ret[2],$transaction_id,$sp_billno,1)['iResult'] == 0){
    				        //这笔订单已经支付成功过了
    				    }else{
    				        if($bargainor_id == $tenpay_conf->private_spid){
    				            $result = OSUpdatePayConfig('PrivateAmount',$total_fee*100); //充值对私的账户
    				        }else {
    				            $result = OSUpdatePayConfig('PublicAmount',$total_fee*100);  //充值对公的账户
    				        }
    				        OSSetPayOrderStatus($ret[2],$transaction_id,$sp_billno,1);
    				        if($result['iResult'] == 0){                   //修改充值账户的金额成功
    				            
    				        }else {                                 //修改充值账户的金额成功
    				            Utility::Log($file_name, 'updatePayConfig error', json_encode($result));
    				        }
    					    if($ret[1] == 1){ //欢乐豆
    					        $result = DCBuyHappyBean($ret[0], floor($total_fee*100),$ret[2]);   //充值欢乐豆
    					    }
    					    else{//黄钻会员
    					        $result =  DCBuyVIP($ret[0], floor($total_fee/$arrConfig['VipPrice'])*$arrConfig['VipDays'],$ret[2]);      //充值黄钻会员   
    						}
    					    if($result['iResult']==0){
    					        OSSetPayOrderStatus($ret[2],$transaction_id,$sp_billno,3);   //修改订单处理状态为处理成功
    					    }else{
				                Utility::Log($file_name, 'error_info', json_encode($result));
    					        OSSetPayOrderStatus($ret[2],$transaction_id,$sp_billno,4);    //修改订单处理状态为处理失败
    					    }
    				    }
        				/*此处加入商户系统的逻辑处理（例如判断金额，判断支付状态，更新订单状态等等）......*/
        			}else{
        			    OSSetPayOrderStatus($ret[2],$transaction_id,$sp_billno,2);      //修改订单状态为支付失败
                        echo "  <DIV class=success>支付失败！</DIV> <DIV class=regMsg>--修改订单状态失败</DIV>";
        			}
               }
		       OSUnLockOrder($sp_billno);
			}else{
			    echo "  <DIV class=success>操作失败！</DIV> <DIV class=regMsg>--订单处理中,请稍后再试！</DIV>";
			}
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