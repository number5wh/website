<?php
/* Smarty version 3.1.31, created on 2018-08-01 00:22:12
  from "E:\GameSite\home\Templates\public\header.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5b608cb487b876_27560223',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8e4ff67ec70547b26aa6ad23e8029708e9060b72' => 
    array (
      0 => 'E:\\GameSite\\home\\Templates\\public\\header.tpl',
      1 => 1530522026,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5b608cb487b876_27560223 (Smarty_Internal_Template $_smarty_tpl) {
?>
<header>
		<div class="container">
			<!-- nav -->
			<ul class="nav navbar-nav navbar-right navbar-header">
				<li class=""><a href="<?php echo $_smarty_tpl->tpl_vars['url']->value['Home'];?>
">首页</a></li>
				<li><a href="<?php echo $_smarty_tpl->tpl_vars['url']->value['Home'];?>
?n=games">游戏介绍</a></li>
				<li><a href="<?php echo $_smarty_tpl->tpl_vars['url']->value['Charge'];?>
">账号充值</a></li>
				<li><a href="<?php echo $_smarty_tpl->tpl_vars['url']->value['PassPort'];?>
">会员注册</a></li>
				<li><a href="<?php echo $_smarty_tpl->tpl_vars['url']->value['Home'];?>
?n=helpCenter">帮助中心</a></li>
				<li><a href="<?php echo $_smarty_tpl->tpl_vars['url']->value['Safety'];?>
?n=index&a=findPassword">找回密码</a></li>
			</ul>
		</div>
	</header><?php }
}
