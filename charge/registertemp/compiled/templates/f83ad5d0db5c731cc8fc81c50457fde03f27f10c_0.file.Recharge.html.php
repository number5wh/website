<?php
/* Smarty version 3.1.31, created on 2018-07-13 10:50:14
  from "C:\website\charge\Templates\default\Recharge.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5b48136673af69_03442672',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f83ad5d0db5c731cc8fc81c50457fde03f27f10c' => 
    array (
      0 => 'C:\\website\\charge\\Templates\\default\\Recharge.html',
      1 => 1531450197,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:public/header.tpl' => 1,
    'file:public/footer.tpl' => 1,
  ),
),false)) {
function content_5b48136673af69_03442672 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>779游戏大厅-账号充值</title>
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/public.css">
	<link rel="stylesheet" href="css/register.css">
	<?php echo '<script'; ?>
 src="js/jquery.min.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 src="js/jquery.SuperSlide.2.1.1.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 src="js/bootstrap.min.js"><?php echo '</script'; ?>
>
   <?php echo '<script'; ?>
 type="text/javascript" src="/js/fun.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 language="javascript" type="text/javascript" src="/js/common.js"><?php echo '</script'; ?>
>

	<style>
		.form1{width:700px;height:613px;}
		.group-btn .list-btn{padding-left:50px;text-align:left;}
		.clearfix .a-num{margin-bottom:10px;}
	</style>

</head>
<body>
	<?php $_smarty_tpl->_subTemplateRender("file:public/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

	<div class="clear"></div>
	<!-- 主体部分 -->
	<main>
		<div class="container">
		<span style="LINE-HEIGHT: 25px; COLOR: #ff0000;margin-left:200px" id="Msg"></span>
			<div class="fl group-btn">
				
				<div class="group-btn-title">
					选择充值方式
				</div>
				<!-- <div class="list-btn active" onclick="setpay('4');">
					<img src="images/icon/recharge-icon01.png" alt="">
					网银充值
				</div> -->
				<div class="list-btn active" onclick="setpay('1');">
					<img src="images/icon/recharge-icon02.png" alt="">
					支付宝充值
				</div>
				<!-- <div class="list-btn" onclick="setpay('1');">
					<img src="images/icon/cft.png" alt="">
					财付通充值
				</div>-->
				<div class="list-btn"  onclick="setpay('2');">
					<img src="images/icon/recharge-icon03.png" alt="">
					微信扫码付
				</div>
			</div>
			 <form action="" method="post" class="form1">
			 <input type="hidden"  name="rtype" id="rtype"  value="1"  />
				<label>
					<span>账户类型：</span>
					 <input type="hidden"  name="LoginType" id="LoginType" value="1"  />
					<span class="a-num a-num1  active">登录账号<img class="select-img" src="images/icon/select-icon.png" alt="" ></span>
					<span class="a-num a-num2">用户编号<img class="select-img" src="images/icon/select-icon.png" alt="" ></span>
				</label>
				<label class="zh">
					<span>充值账号：</span>
					<input  maxlength="16" id="LoginCode"  type="text" value="<?php echo $_smarty_tpl->tpl_vars['LoginCode']->value;?>
" name="LoginCode"  />
				</label>
				<label class="zh">
					<span>确认账号：</span>
					<input maxlength="16" id="ConfirmCode"  type="text" value="<?php echo $_smarty_tpl->tpl_vars['LoginCode']->value;?>
" name="ConfirmCode">
				</label>
				<label class="bh"  style="display:none;">
					<span>用户编号：</span>
					<input maxlength="8" id="LoginID"  type="text" value="<?php echo $_smarty_tpl->tpl_vars['LoginID']->value;?>
" name="LoginCode"  />
				</label>
				<label class="bh" style="display:none;">
					<span>确认编号：</span>
					<input maxlength="8" id="ConfirmID"  type="text" value="<?php echo $_smarty_tpl->tpl_vars['LoginID']->value;?>
" name="ConfirmCode" />
				</label>
				<label class="type">
					<span>充值类型：</span>
					<input type="hidden" value="1"  name="payType" id="payType" />
					<span class="a-num active a-num-type1">联通币<img class="select-img" src="images/icon/select-icon.png" alt=""></span>
					<span class="a-num a-num-type2" style="width:200px;"><img width="18px" src="images/icon/黄钻.png" alt="">&nbsp;黄钻会员 <i>(有限期一个月)</i><img class="select-img" src="images/icon/select-icon.png" alt=""></span>
				</label>
				<label class="type1" style="height:120px;">
					<span>充值金额：</span>
					<div class="clearfix" style="width:500px;float:right;margin-right:56px;">
					<input type="hidden"  id="Price"   value="10" />
						<span class="a-num active" RMB="10">10RMB<img class="select-img" src="images/icon/select-icon.png" alt=""></span>
						<span class="a-num" RMB="20">20RMB<img class="select-img" src="images/icon/select-icon.png" alt=""></span>
						<span class="a-num" RMB="30">30RMB<img class="select-img" src="images/icon/select-icon.png" alt=""></span>
						<span class="a-num" RMB="50">50RMB<img class="select-img" src="images/icon/select-icon.png" alt=""></span>
						<span class="a-num" RMB="100">100RMB<img class="select-img" src="images/icon/select-icon.png" alt=""></span>
						<span class="a-num" RMB="200">200RMB<img class="select-img" src="images/icon/select-icon.png" alt=""></span>
						<span class="a-num" RMB="300">300RMB<img class="select-img" src="images/icon/select-icon.png" alt=""></span>
						<span class="a-num" RMB="500">500RMB<img class="select-img" src="images/icon/select-icon.png" alt=""></span>
						<span class="a-num" RMB="1000">1000RMB<img class="select-img" src="images/icon/select-icon.png" alt=""></span>
						<i id="PayMoney">注：10RMB=990通联币=142241欢乐豆</i>
					</div>
				</label>
				<label class="type2" style="display:none;">
                    <input type="hidden"  id="Price2"   value="30" />
					<span>充值金额：</span>
					<span class="a-num active" RMB="30">30RMB<img class="select-img" src="images/icon/select-icon.png" alt=""></span>
				</label>
				<label>
					<span>验证码：</span>
					   <input maxlength="4" type="text" name="Code" id="Code" />
					<u><img src="/Common/ChkCode.class.php" width="60" height="35" id="vCode" style="cursor:pointer;" /></u>
					<a href="javascript:;" id="ResetCode" >换一张</a>
				</label>
				<label style="margin-left:140px;">
					<input  type="checkbox" name="ragree" id="ragree" checked="checked" value="agree" style="width:12px;height:12px;">
					
					已阅读并同意
					<a href="javascript:;">《关于充值的特别提示和约定》</a>
				</label>
				<label style="margin-left:140px;">										
					 <input type="submit" name="submit" id="submit" class="btns" value="下一步" onClick="R.Recharge()">
				</label>
			</form>
		</div>
	</main>
	<?php $_smarty_tpl->_subTemplateRender("file:public/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<div id="Alipay"></div>
    <form name="frmWxPay" method="post" action="?n=Index&a=wxpay">
        <input type="hidden" name="url" id="url" value="">
        <input type="hidden" name="amount" id="amount" value="">
        <input type="hidden" name="goodsname" id="goodsname" value="">
    </form>
    <form name="frmB780" method="post" action="<?php echo $_smarty_tpl->tpl_vars['b780']->value['PostUrl'];?>
">
        <input type="hidden" name="cmdno" id="cmdno" value="1" />
        <input type="hidden" name="date" id="date" value="" />
        <input type="hidden" name="desc" id="desc" value="" />
        <input type="hidden" name="purchaserId" id="purchaserId" value="" />
        <input type="hidden" name="bargainorId" id="bargainorId" value="" />
        <input type="hidden" name="transactionId" id="transactionId" value="" />
        <input type="hidden" name="cardType" id="cardType" value="" />
        <input type="hidden" name="sp_orderno" id="sp_orderno" value="" />
        <input type="hidden" name="totalFee" id="totalFee" value="" />
        <input type="hidden" name="burden" id="burden" value="" />
        <input type="hidden" name="warenum" id="warenum" value="" />
        <input type="hidden" name="returnUrl" id="returnUrl" value="" />
        <input type="hidden" name="remark" id="remark" value="" />
        <!-- 
    <input type="hidden" name="payerName" id="payerName" value="" /> -->
        <input type="hidden" name="sign" id="sign" value="" />
    </form>
    <form id='frmSubmit' method='post' name='frmSubmit' action=''>
        <input type='hidden' name='version' id='hee_version' value='' />
        <input type='hidden' name='agent_id' id='hee_agent_id' value='' />
        <input type='hidden' name='agent_bill_id' id='hee_agent_bill_id' value='' />
        <input type='hidden' name='agent_bill_time' id='hee_agent_bill_time' value='' />
        <input type='hidden' name='pay_type' id='hee_pay_type' value='' />
        <input type='hidden' name='pay_code' id='hee_pay_code' value='' />
        <input type='hidden' name='pay_amt' id='hee_pay_amt' value='' />
        <input type='hidden' name='notify_url' id='hee_notify_url' value='' />
        <input type='hidden' name='return_url' id='hee_return_url' value='' />
        <input type='hidden' name='user_ip' id='hee_user_ip' value='' />
        <input type='hidden' name='goods_name' id='hee_goods_name' value='' />
        <input type='hidden' name='goods_num' id='hee_goods_num' value='' />
        <input type='hidden' name='goods_note' id='hee_goods_note' value='' />
        <input type='hidden' name='remark' id='hee_remark' value='' />
        <input type='hidden' name='sign' id='hee_sign' value='' />
    </form>
    <form id='jtpaySubmit' name='jtpaySubmit' action='' method='post'>
        <input type='hidden' name='p1_usercode'  id='p1_usercode'             value=''>
        <input type='hidden' name='p2_order' id='p2_order'                value=''>
        <input type='hidden' name='p3_money' id='p3_money'                value=''>
        <input type='hidden' name='p4_returnurl'  id='p4_returnurl'            value='>'>
        <input type='hidden' name='p5_notifyurl' id='p5_notifyurl'            value=''>
        <input type='hidden' name='p6_ordertime'  id='p6_ordertime'            value=''>
        <input type='hidden' name='p7_sign'  id='p7_sign'                 value=''>
        <input type='hidden' name='p9_paymethod' id='p9_paymethod'            value=''>
        <input type='hidden' name='p14_customname' id='p14_customname'          value=''>
        <input type='hidden' name='p17_customip' id='p17_customip'            value=''>
        <input type='hidden' name='p24_remark' id='p24_remark'            value=''>
        <input type='hidden' name='p25_terminal'  id='p25_terminal'            value=''>
        <input type='hidden' name='p26_iswappay' id='p26_iswappay'            value=''>
    </form>
</body>

<?php echo '<script'; ?>
 src="js/net-recharge.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript">
    var PriceX
    $(function(){
        // 页面加载完执行一次
        if($(".a-num-type2").is('.active')){
            PriceX = $('#Price2').val();
        }else{
            // 黄钻30
            PriceX = $('#Price').val();
        }
        // console.log(PriceX);
        // 点击后执行
        $(".a-num").click(function(){
            if($(".a-num-type2").is('.active')){
                // 黄钻30
                PriceX = $('#Price2').val();
            }else{
                PriceX = $('#Price').val();
            }
            // console.log(PriceX);
        })
    })

function setpay(pid)
{    	$("#rtype").val(pid); 	
		R.Counter()
}

$(function() {
    $('#ResetCode').click(function() {
        $('#vCode').attr('src', "/Common/ChkCode.class.php?" + Math.random());
    });
    R.Counter();
});

var R = {
        Recharge: function() {
            //$('#Msg').html('暂无法提供此项服务');
            //return false;   
            	/*	if (codePat.test($('#LoginCode').val()) == false) {
                       $('#Msg').html('登录名必须为4-16位！');
            			$('#LoginCode').focus();
                        return false;
                    }        */
            var LoginType = $('#LoginType').val();
            if (LoginType == 1) {
                if ($.trim($('#LoginCode').val()) == '') {
                    $('#Msg').html('请输入充值账号');
                    $('#LoginCode').focus();
                    return false
                }
                if ($.trim($('#ConfirmCode').val()) == '') {
                    $('#Msg').html('请输入确认帐号');
                    $('#ConfirmCode').focus();
                    return false
                }
                if ($.trim($('#LoginCode').val()) != $('#ConfirmCode').val()) {
                    $('#Msg').html('两次输入的账号不致');
                    $('#ConfirmCode').focus();
                    return false
                }
            } else {
                if ($.trim($('#LoginID').val()) == '') {
                    $('#Msg').html('请输入用户编号');
                    $('#LoginID').focus();
                    return false
                }
                if ($.trim($('#ConfirmID').val()) == '') {
                    $('#Msg').html('请输入确认编号');
                    $('#ConfirmID').focus();
                    return false
                }
                if ($.trim($('#LoginID').val()) != $('#ConfirmID').val()) {
                    $('#Msg').html('两次输入的账号不致');
                    $('#ConfirmID').focus();
                    return false
                }
            }

            if ($.trim($('#Code').val()) == '') {
                $('#Msg').html('请输入验证码');
                $('#Code').focus();
                return false
            }
            if (!$('#ragree').attr("checked")) {
                alert('请仔细阅读条款后勾选同意选框！');

                return false;
            }
            //after
            this.encrypt();
            return false;
            //before
            var Url = '?n=Index&a=chkParam';
            var cardType = $("#rtype").val();
            var Params = "CheckCode=" + $.trim($('#Code').val()) + "&LoginType=" + $('#LoginType').val() + "&LoginCode=" + $('#LoginCode').val() + "&LoginID=" + $('#LoginID').val() + '&cardType=' + cardType;
            var that = this;
            ajax.RequestJsonCallBack(Url, Params, function(data) {
                if (data.iResult == -1001) {
                    alert('账号格式错误');
                    $('#LoginCode').focus();
                } else if (data.iResult == -1002) {
                    alert('用户编号不存在');
                    $('#LoginID').focus();
                } else if (data.iResult == -1) {
                    alert('验证码错误');
                } else if (data.iResult == -5) {
                    alert('支付金额错误');
                } else if (data.iResult == -2) {
                    alert('账号不存在');
                } else if (data.iResult == -3) {
                    alert('生成订单失败');
                } else {
                    if (cardType == 1) {
                        that.RechargeToAlipay(data.iLoginCode,data.Rnd,data.CardID);
                       // that.RechargeToIapppay(data.iLoginCode, data.Rnd, data.CardID);
                    } else if (cardType == 2) {
                        that.RechargeToWx(data.iLoginCode, data.Rnd, data.CardID);
                    } else if (cardType == 36) {
                        that.RechargeToHeepay(data.iLoginCode, data.Rnd, data.CardID);
                    } else {
                        that.RechargeToB780(data.iLoginCode, data.Rnd, data.CardID);
                    }
                }
            });
        },
        encrypt: function() {
            var Price = PriceX;
            // var Price = $('#Price').val();
            var cardType = $("#rtype").val();
            var payType = $("#payType").val();
            if (cardType == 'undefined') {
                alert('请选择充值方式');
                return false;
            }
            $('#payerName').val($('#LoginCode').val());
            var Url = 'encrypt.php';
            var Params = "CheckCode=" + $.trim($('#Code').val()) + "&LoginType=" + $('#LoginType').val() + "&LoginCode=" + $('#LoginCode').val() + "&LoginID=" + $('#LoginID').val() +
                "&Amount=" + Price + '&payerName=' + encodeURIComponent($('#LoginCode').val()) + '&cardType=' + cardType + '&Code=' + $('#Code').val() + '&cmdno=' + $('#cmdno').val() + '&payType=' + payType;
            if (cardType == 1) {
                Url = Url + '?Type=alipay';
                ajax.RequestJsonCallBack(Url, Params, R.SubmitToAlipay);
            } else if (cardType == 2) {
                Url = Url + '?Type=wx';
                ajax.RequestJsonCallBack(Url, Params, R.SubmitToWx);
            } else if (cardType == 36) {
                Url = Url + '?Type=heepay';
                ajax.RequestJsonCallBack(Url, Params, R.SubmitToheepay);
            } else if (cardType == 43) {
                Url = Url + '?Type=jtpay';
                ajax.RequestJsonCallBack(Url, Params, R.SubmitToJtPay);
            } else {
                Url = Url + '?Type=b780';
                ajax.RequestJsonCallBack(Url, Params, R.SubmitToB780);
            }
        },
        OutputError: function(iResult) {
            if (iResult == -1001) {
                alert('账号格式错误');
                $('#LoginCode').focus();
            } else if (iResult == -1002) {
                alert('用户编号不存在');
                $('#LoginID').focus();
            } else if (iResult == -1) {
                alert('验证码错误');
            } else if (iResult == -5) {
                alert('支付金额错误');
            } else if (iResult == -2) {
                alert('账号不存在');
            } else if (iResult == -3) {
                alert('生成订单失败');
            } else if (iResult == -4) {
                alert('充值方式错误');
            } else {
                alert('充值失败，请稍后再试');
            }
        },
        RechargeToIapppay: function(iLoginCode, Rnd, CardID) {
            var Price = PriceX;
            // var Price = $('#Price').val();
            var cardType = $('#rtype').val();
            var payType = $("#payType").val();
            $('#sp_orderno').val(Rnd);
            $('#payerName').val($('#LoginCode').val());
            $('#cardType').val(cardType);
            //$('#productNum').val($('#Num').val()*$('#Price').val());
            // var Url = 'encrypt.php?Type=iapppay';
            // var Params = "Amount="+Price+'&iLoginCode='+iLoginCode+'&payerName='+encodeURIComponent($('#LoginCode').val())+'&cardType='+cardType+'&Code='+$('#Code').val()+'&cmdno='+$('#cmdno').val()+'&sp_orderno='+Rnd+'&payType='+payType+'&CardID='+CardID;
            // ajax.RequestJsonCallBack(Url,Params,R.SubmitToB780);


        },
        RechargeToJtPay: function(iLoginCode, Rnd, CardID) {
            var Price = PriceX;
            // var Price = $('#Price').val();
            var cardType = $('#rtype').val();
            var payType = $("#payType").val();
            $('#sp_orderno').val(Rnd);
            $('#payerName').val($('#LoginCode').val());
            $('#cardType').val(cardType);
            //$('#productNum').val($('#Num').val()*$('#Price').val());
            var Url = 'encrypt.php?Type=b780';
            var Params = "Amount=" + Price + '&iLoginCode=' + iLoginCode + '&payerName=' + encodeURIComponent($('#LoginCode').val()) + '&cardType=' + cardType + '&Code=' + $('#Code').val() + '&cmdno=' + $('#cmdno').val() + '&sp_orderno=' + Rnd + '&payType=' + payType + '&CardID=' + CardID;
            ajax.RequestJsonCallBack(Url, Params, R.SubmitToJtPay);
        },
        RechargeToB780: function(iLoginCode, Rnd, CardID) {
            var Price = PriceX;
            // var Price = $('#Price').val();
            var cardType = $('#rtype').val();
            var payType = $("#payType").val();
            $('#sp_orderno').val(Rnd);
            $('#payerName').val($('#LoginCode').val());
            $('#cardType').val(cardType);
            //$('#productNum').val($('#Num').val()*$('#Price').val());
            var Url = 'encrypt.php?Type=b780';
            var Params = "Amount=" + Price + '&iLoginCode=' + iLoginCode + '&payerName=' + encodeURIComponent($('#LoginCode').val()) + '&cardType=' + cardType + '&Code=' + $('#Code').val() + '&cmdno=' + $('#cmdno').val() + '&sp_orderno=' + Rnd + '&payType=' + payType + '&CardID=' + CardID;
            ajax.RequestJsonCallBack(Url, Params, R.SubmitToB780);
        },
        RechargeToHeepay: function(iLoginCode, Rnd, CardID) {
            var Price = PriceX;
            // var Price = $('#Price').val();
            var cardType = $('#rtype').val();
            var payType = $("#payType").val();
            $('#sp_orderno').val(Rnd);
            $('#payerName').val($('#LoginCode').val());
            $('#cardType').val(cardType);
            //$('#productNum').val($('#Num').val()*$('#Price').val());
            var Url = 'encrypt.php?Type=heepay';
            var Params = "Amount=" + Price + '&iLoginCode=' + iLoginCode + '&payerName=' + encodeURIComponent($('#LoginCode').val()) + '&cardType=' + cardType + '&Code=' + $('#Code').val() + '&cmdno=' + $('#cmdno').val() + '&sp_orderno=' + Rnd + '&payType=' + payType + '&CardID=' + CardID;
            ajax.RequestJsonCallBack(Url, Params, R.SubmitToB780);
        },
        RechargeToWx: function(iLoginCode, Rnd, CardID) {
            var Price = PriceX;
            // var Price = $('#Price').val();
            var cardType = $('#rtype').val();
            var payType = $("i#payType").val();
            $('#sp_orderno').val(Rnd);
            $('#payerName').val($('#LoginCode').val());
            $('#cardType').val(cardType);
            //$('#productNum').val($('#Num').val()*$('#Price').val());
            var Url = 'encrypt.php?Type=wx';
            var Params = "Amount=" + Price + '&iLoginCode=' + iLoginCode + '&payerName=' + encodeURIComponent($('#LoginCode').val()) + '&cardType=' + cardType + '&Code=' + $('#Code').val() + '&cmdno=' + $('#cmdno').val() + '&sp_orderno=' + Rnd + '&payType=' + payType + '&CardID=' + CardID;
            ajax.RequestJsonCallBack(Url, Params, R.SubmitToWx);
        },
        RechargeToAlipay: function(iLoginCode, Rnd, CardID) {
            var Price = PriceX;
            // var Price = $('#Price').val();
            var cardType = $('#rtype').val();
            var payType = $("#payType").val();
            $('#sp_orderno').val(Rnd);
            $('#payerName').val($('#LoginCode').val());
            $('#cardType').val(cardType);
            //$('#productNum').val($('#Num').val()*$('#Price').val());
            var Url = 'encrypt.php?Type=alipay';
            var Params = "Amount=" + Price + '&iLoginCode=' + iLoginCode + '&payerName=' + encodeURIComponent($('#LoginCode').val()) + '&cardType=' + cardType + '&Code=' + $('#Code').val() + '&cmdno=' + $('#cmdno').val() + '&sp_orderno=' + Rnd + '&payType=' + payType + '&CardID=' + CardID;
            ajax.RequestJsonCallBack(Url, Params, R.SubmitToAlipay);
        },

        SubmitToAlipay: function(data) {
            if (data.iResult != 0) {
                R.OutputError(data.iResult);
                return false;
            }
            $('body').html(data.html);
        },
        SubmitToWx: function(data) {
            if (data.iResult != 0) {
                R.OutputError(data.iResult);
                return false;
            }
            $('#url').val(data.url);
            $('#amount').val(data.amount);
            $('#goodsname').val(data.goodsname);
            document.frmWxPay.submit();
        },

        SubmitToIapppay: function(data) {
            if (data.iResult == 0) {
                //$('#purchaserId').val(data.PurchaserId);
                $('#bargainorId').val(data.bargainorId);
                $('#purchaserId').val(data.purchaserId);
                $('#transactionId').val(data.transactionId);
                $('#sign').val(data.sign);
                $('#warenum').val(data.warenum);
                $('#date').val(data.date);
                $('#remark').val(data.remark);
                $('#totalFee').val(data.totalFee);
                $('#burden').val(data.burden);
                $('#cardType').val(data.cardType);
                $('#sp_orderno').val(data.sp_orderno);
                $('#returnUrl').val(data.return_url);
                $('#desc').val(data.desc);
                //	console.log(data);
                //console.log(document.forms['frmB780']);
                // document.frmB780.submit();
                $('body').html(data.html);
            } else {
                R.OutputError(data.iResult);
                return;
            }
        },

        SubmitToJtPay: function(data) {
            if (data.iResult == 0) {
                $('#jtpaySubmit').attr('action', data.pay_url);
                $('#p1_usercode').val(data.p1_usercode);
                $('#p2_order').val(data.p2_order);
                $('#p3_money').val(data.p3_money);
                $('#p4_returnurl').val(data.p4_returnurl);
                $('#p5_notifyurl').val(data.p5_notifyurl);
                $('#p6_ordertime').val(data.p6_ordertime);
                $('#p7_sign').val(data.p7_sign);
                $('#p9_paymethod').val(data.p9_paymethod);
                $('#p14_customname').val(data.p14_customname);
                $('#p17_customip').val(data.p17_customip);
                $('#p24_remark').val(data.p24_remark);
                $('#p25_terminal').val(data.p25_terminal);
                $('#p26_iswappay').val(data.p26_iswappay);
       
                console.log(document.forms['jtpaySubmit']);
                document.jtpaySubmit.submit();
            } else {
                R.OutputError(data.iResult);
                return;
            }
        },


        SubmitToB780: function(data) {
            if (data.iResult == 0) {
                //$('#purchaserId').val(data.PurchaserId);
                $('#bargainorId').val(data.bargainorId);
                $('#purchaserId').val(data.purchaserId);
                $('#transactionId').val(data.transactionId);
                $('#sign').val(data.sign);
                $('#warenum').val(data.warenum);
                $('#date').val(data.date);
                $('#remark').val(data.remark);
                $('#totalFee').val(data.totalFee);
                $('#burden').val(data.burden);
                $('#cardType').val(data.cardType);
                $('#sp_orderno').val(data.sp_orderno);
                $('#returnUrl').val(data.return_url);
                $('#desc').val(data.desc);
                //	console.log(data);
                console.log(document.forms['frmB780']);
                document.frmB780.submit();
            } else {
                R.OutputError(data.iResult);
                return;
            }
            /*		else if(data.iResult==-4){
            			$('#Msg').html('请输入正确的验证码');
            		}
            		else
            			$('#Msg').html('下单失败');*/
        },
        SubmitToheepay: function(data) {
            if (data.iResult == 0) {
                //$('#purchaserId').val(data.PurchaserId);
                $('#frmSubmit').attr('action', data.pay_url);
                $('#hee_version').val(data.version);
                $('#hee_agent_id').val(data.agent_id);
                $('#hee_agent_bill_id').val(data.agent_bill_id);
                $('#hee_agent_bill_time').val(data.agent_bill_time);
                $('#hee_pay_type').val(data.pay_type);
                $('#hee_pay_code').val(data.pay_code);
                $('#hee_pay_amt').val(data.pay_amt);
                $('#hee_notify_url').val(data.notify_url);
                $('#hee_return_url').val(data.return_url);
                $('#hee_user_ip').val(data.user_ip);
                $('#hee_goods_name').val(data.goods_name);
                $('#hee_goods_num').val(data.goods_num);
                $('#hee_goods_note').val(data.goods_note);
                $('#hee_remark').val(data.remark);
                $('#hee_sign').val(data.sign);
                //	console.log(data);
                //console.log(document.forms['frmB780']);
                document.frmSubmit.submit();
            } else {
                R.OutputError(data.iResult);
                return;
            }
            /*		else if(data.iResult==-4){
            			$('#Msg').html('请输入正确的验证码');
            		}
            		else
            			$('#Msg').html('下单失败');*/
        },
        Counter: function() {
            var money = PriceX;
            // var money = $('#Price').val();
            var rType = $('#rtype').val();
            ajax.RequestJsonCallBack('?n=Index&a=getHappyBeanRate', 'CardType=' + rType, function(HappyBeanRate) {
                    if (HappyBeanRate == -1) {
                        alert('系统错误，请稍后再试！');
                        $('.specific').hide();
                        return false;
                    } 
                        var YfMoney = parseInt(money * <?php echo $_smarty_tpl->tpl_vars['payConfig']->value['RMB2MB'];?>
 * HappyBeanRate / 10000);
                        var HappyBean = parseInt(money * <?php echo $_smarty_tpl->tpl_vars['payConfig']->value['RMB2MB'];?>
 * <?php echo $_smarty_tpl->tpl_vars['payConfig']->value['MB2HPB'];?>
 * HappyBeanRate / <?php echo $_smarty_tpl->tpl_vars['payConfig']->value['discount'];?>
); 
                        
                        $('#PayMoney').html('(' + money + 'RMB=' + YfMoney + '通易币=' + HappyBean + '欢乐豆)');
                    });

            }
        };

        function ChangeLoginType(data) {
            var LoginType = $('#LoginType').val();
            if (LoginType == 1) {
                $('.LoginID').hide();
                $('.LoginCode').show();
            } else {
                $('.LoginID').show();
                $('.LoginCode').hide();
            }
        }
<?php echo '</script'; ?>
>
    
</html>
<?php }
}
