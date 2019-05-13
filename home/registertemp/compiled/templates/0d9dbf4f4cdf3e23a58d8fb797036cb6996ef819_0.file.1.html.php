<?php
/* Smarty version 3.1.31, created on 2018-07-23 06:14:40
  from "C:\website\home\Templates\default\news\1.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5b5501d0cdcff7_72951516',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0d9dbf4f4cdf3e23a58d8fb797036cb6996ef819' => 
    array (
      0 => 'C:\\website\\home\\Templates\\default\\news\\1.html',
      1 => 1531272151,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:public/header.tpl' => 1,
    'file:public/footer.tpl' => 1,
  ),
),false)) {
function content_5b5501d0cdcff7_72951516 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>779游戏大厅-帮助中心</title>
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/public.css">
	<link rel="stylesheet" href="css/help.css">

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

	<div class="clear"></div>
	<!-- 主体部分 -->
	<main>
		<div class="container clearfix">
			<div class="help-lists fl">
			
				<div class="help-list-title"><img src="images/icon/help-icon01.png" alt=""> 最新资讯</div>
				<div class="help-list"><a href="?n=helpCenter&a=problems&id=1">> 如何在线充值购买欢乐豆</a></div>
				<div class="help-list"><a href="?n=helpCenter&a=problems&id=2">> 游戏充值流程说明</a></div>
				<div class="help-list"><a href="?n=helpCenter&a=problems&id=3">> 怎么找回银行密码</a></div>
				<div class="help-list"><a href="?n=helpCenter&a=problems&id=4">> 怎么找回游戏密码</a></div>
				<div class="help-list"><a href="?n=helpCenter&a=problems&id=5">> 怎么修改游戏密码</a></div>
				<div class="help-list"><a href="?n=helpCenter&a=problems&id=6">> 怎么进入游戏</a></div>
				<div class="help-list"><a href="?n=helpCenter&a=problems&id=7">> 怎么注册通行证</a></div>				
				<div class="help-list"><a href="?n=helpCenter&a=problems&id=8">> 游戏账号解绑</a></div>
				<div class="help-list"><a href="?n=helpCenter&a=problems&id=9">> 游戏登录验证安全手机</a></div>
				<div class="help-list"><a href="?n=helpCenter&a=problems&id=1">> 如何在线充值购买欢乐豆</a></div>
				<div class="help-list"><a href="?n=helpCenter&a=problems&id=2">> 游戏充值流程说明</a></div>
				<div class="help-list"><a href="?n=helpCenter&a=problems&id=3">> 怎么找回银行密码</a></div>
			</div>
			<div class="help-box fl">
				<h3>779游戏中心上线公告</h3>
				<hr>
				<p>亲爱的游戏玩家：</p>
				<p>
					779游戏于2017年03月17日正式上线啦。<br>
					新上线活动：<br>
					1、推出微信绑定送19.8万<br>
					2、每日签到领取欢乐豆，签到最高领取60万欢乐豆！<br>
					后续还有更多更好的活动等着您。<br>
					唯一官网www.game779.com,期待您的加入！
				</p>
				<div align="right">
					<strong>
						<p align="right">779游戏运营团队</p>
						<p align="right">2017-03-17</p>
						<br>
					</strong>
				</div>
			</div>
		</div>
	</main>
    <?php $_smarty_tpl->_subTemplateRender("file:public/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

</body>
</html><?php }
}
