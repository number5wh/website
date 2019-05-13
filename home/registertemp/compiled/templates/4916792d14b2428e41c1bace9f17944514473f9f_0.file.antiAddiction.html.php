<?php
/* Smarty version 3.1.31, created on 2018-06-18 22:46:24
  from "C:\website\home\Templates\default\antiAddiction.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5b27c5c08e1e37_24645508',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4916792d14b2428e41c1bace9f17944514473f9f' => 
    array (
      0 => 'C:\\website\\home\\Templates\\default\\antiAddiction.html',
      1 => 1528251000,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:public/header.tpl' => 1,
    'file:public/footer.tpl' => 1,
  ),
),false)) {
function content_5b27c5c08e1e37_24645508 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>779游戏大厅-防沉迷</title>
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
		<div class="container">
			<div class="banner-prevent">
				<img src="images/silent_banner.png" alt="">
			</div>
			<div class="prevent">
				<h3>防沉迷系统</h3>
				<p>一、为贯彻落实《中共中央国务院关于进一步加强和改进未成年人思想道德建设的若干意见》，净化网络环境，推广文明上网，保护未成年人身心健康，有效解决未成年 人沉迷网络游戏的社会问题，推出"南丰世纪网络游戏防沉迷系统"。 注册时所填写的身份证确认未满十八周岁，或者填写的身份证号与对应的真实姓名不一致的，都将被纳入网络游戏防沉迷系统。 因此，我们建议您尽快进行身份信息确认，如果您已不记得当初注册时所填写的身份证信息，或所填写的身份证信息与真实姓名不一致，请尽快进行防沉迷信息重 填.关于玩家实名认证运营商定期将经识别分类后初步判定为成年人的实名身份信息提交公安部门进行验证，由公安部门判定该信息是否真实，验证未通过的用户纳入网 络游戏防沉迷系统。</p>
				<h3>身份证</h3>
				<p>一、身份信息：是指用户在进行注册时向运营商提供的本人真实姓名、身份证号码（或户口薄）、联系方式等运营商要求的真实有效的身份信息。身份信息不规范</p>
				<p>二、身份信息不规范：是指用户在进行注册时所提供的本人真实姓名、身份证号码有误，如身份证号码不完整，排列不符合规则等。如何判定为防沉迷限制所有已注册的用户均需要填写：真实身份证号和相应的真实姓名。填写的信息不符合条件或者不填写该信息的账号均会被纳入防沉迷系统，判断条件如下：</p>
				<p>（一）您的账号没有填写相应的防沉迷系统登记信息</p>
				<p>（二）您填写的为虚假身份证号或虚假姓名</p>
				<p>（三）您填写的身份证号未年满18岁只有您填写真实的身份证号和对应真实姓名，并且该身份证对应的公民年满18岁才不受防沉迷系统的限制判断身份证号显示的</p><br>
				<p>年满18岁的成年人——例如18位身份证：510106198501011830 可以判断该身份证持有人为85年出生的。 未年满18岁的用户——例如18位身份证：510106199902025420可以判断该身份证持有人为99年出生，是未成年人,将会被纳入防沉迷系统关于防沉迷系统为保障未成年人适度使用网络游戏并有足够的时间休息学习，对未成年人使用网络游戏的间隔时间、收益进行限制和引导。不同累计在线时间的游戏收益处理如下：累计在线时间 游戏收益 0－3小时内 正常 3小时－5小时 降为正常值的50％ 5小时以上 降为0 未成年人的累计下线时间已满5小时，则累计在线时间清零，如再上线则重新累计在线时间。</p>
			</div>
		</div>
	</main>
	 <?php $_smarty_tpl->_subTemplateRender("file:public/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

</body>
</html><?php }
}
