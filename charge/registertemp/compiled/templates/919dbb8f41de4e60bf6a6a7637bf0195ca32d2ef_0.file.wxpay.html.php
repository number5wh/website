<?php
/* Smarty version 3.1.31, created on 2018-06-14 09:34:58
  from "C:\website\charge\Templates\default\wxpay.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5b21c642d498b9_01971649',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '919dbb8f41de4e60bf6a6a7637bf0195ca32d2ef' => 
    array (
      0 => 'C:\\website\\charge\\Templates\\default\\wxpay.html',
      1 => 1528940096,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:public/header.tpl' => 1,
    'file:public/footer.tpl' => 1,
  ),
),false)) {
function content_5b21c642d498b9_01971649 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>779游戏大厅-会员注册</title>
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/public.css">
	<link rel="stylesheet" href="css/wxpay.css">

	<?php echo '<script'; ?>
 src="js/jquery.min.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 src="js/jquery.SuperSlide.2.1.1.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 src="js/bootstrap.min.js"><?php echo '</script'; ?>
>
	<!--[if lt IE 9]>
	    <?php echo '<script'; ?>
 src="http://cdn.static.runoob.com/libs/html5shiv/3.7/html5shiv.min.js"><?php echo '</script'; ?>
>
	<![endif]-->
</head>
<body>
	<?php $_smarty_tpl->_subTemplateRender("file:public/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

	<!-- 主体部分 -->
	<main>
		<div class="container">
			<div class="details">
				<!-- <div class="register">
					<table>
						<tr><td><h1>订单详情</h1></td></tr>
						<tr><td>商品名称</td><td>欢乐豆</td></tr>
						<tr><td>总金额</td><td>1.00</td></tr>
						<tr><td>微信扫描下面二维码支付</td></tr>
						<tr><td><img src="http:\/\/paysdk.weixin.qq.com\/example\/qrcode.php?data=weixin%3A%2F%2Fwxpay%2Fbizpayurl%3Fpr%3DJ9Ojf3F"/></td></tr>
					</table>
				</div> -->
				<div class="payInfor">
		            <h2 class="payInforH2">
		              	<span>订单提交成功，请您尽快完成支付</span>
		              	<span class="payInforH2-sp">(<?php echo $_smarty_tpl->tpl_vars['params']->value['goodsname'];?>
)</span>
		            </h2>
		            <span class="payInforMon">
		                <span style="color:#3d3d3d">应付金额：</span>
		                <span class="payInforMon-num">
		                <span>¥</span> <span><?php echo $_smarty_tpl->tpl_vars['params']->value['amount'];?>
</span>
		                </span>
		            </span>
		            <div class="clear"></div>
		        </div>
				<div class="payGoWeixin clearfix">
		            <div class="payGoWeixinLeft">
		                <img class="code J_wechatCode" width="230" height="230" src="<?php echo $_smarty_tpl->tpl_vars['params']->value['url'];?>
" alt="">
		            </div>
		            <div class="payGoWeixinRight">
		                <h5>请使用微信扫描二维码以完成支付</h5>
		                <p style="text-align: center">
		                   	<a href="#">详细使用帮助</a>
		                </p>
		                <p style="color: #333;text-align: center;">（如果支付遇到问题，可<a href="#">联系客服</a>）</p>
		            </div>
		        </div>
			</div>
		</div>
	</main>
	<?php $_smarty_tpl->_subTemplateRender("file:public/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

</body>
</html><?php }
}
