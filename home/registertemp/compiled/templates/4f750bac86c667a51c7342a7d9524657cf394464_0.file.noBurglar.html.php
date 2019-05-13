<?php
/* Smarty version 3.1.31, created on 2018-06-16 17:58:37
  from "C:\website\home\Templates\default\noBurglar.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5b24df4da24c64_25214838',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4f750bac86c667a51c7342a7d9524657cf394464' => 
    array (
      0 => 'C:\\website\\home\\Templates\\default\\noBurglar.html',
      1 => 1528706909,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:public/header.tpl' => 1,
    'file:public/footer.tpl' => 1,
  ),
),false)) {
function content_5b24df4da24c64_25214838 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>779游戏大厅-盗号措施</title>
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/public.css">
	<link rel="stylesheet" href="css/prevent.css">

	<?php echo '<script'; ?>
 src="js/jquery.min.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 src="js/jquery.SuperSlide.2.1.1.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 src="js/bootstrap.min.js"><?php echo '</script'; ?>
>
	<style>
	
		.prevent{height:477px;}
	
	</style>
</head>
<body>
	<?php $_smarty_tpl->_subTemplateRender("file:public/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

	<div class="clear"></div>
	<!-- 主体部分 -->
	<main>
		<div class="container">
			<div class="banner-prevent">
				<img src="images/liar_banner.png" alt="">
			</div>
			<div class="prevent">
				<h3>玩家防盗号措施</h3>
				<p>下载游戏请到官网www.game779.com下载游戏。账号绑定微信登录和绑定主机,定期的查杀电脑的木马病毒.建议用户不要使用公共场所特别是酒店(宾馆)的电脑，这些场所的电脑很有可能有木马病毒.</p>
				<h4>玩家被盗号之后用户的处理措施</h4>
				<p>发现账号被盗之后，第一时间来联系客服，客服会马上为您查询和做相关的处理。然后您需要马上更改您的账号密码.</p>
			</div>
		</div>
	</main>
	<?php $_smarty_tpl->_subTemplateRender("file:public/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

</body>
</html><?php }
}
