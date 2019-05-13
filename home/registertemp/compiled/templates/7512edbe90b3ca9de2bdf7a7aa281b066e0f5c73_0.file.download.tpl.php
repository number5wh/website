<?php
/* Smarty version 3.1.31, created on 2018-07-12 10:33:40
  from "C:\website\home\Templates\public\download.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5b46be042f2237_05624732',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '7512edbe90b3ca9de2bdf7a7aa281b066e0f5c73' => 
    array (
      0 => 'C:\\website\\home\\Templates\\public\\download.tpl',
      1 => 1531362814,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5b46be042f2237_05624732 (Smarty_Internal_Template $_smarty_tpl) {
?>
	<div class="download">
			<h3>779游戏大厅</h3>
			<hr>
			<h4>多种游戏，等你来战</h4>
			<div class="download-icon">
				<div class="fl icon">
					<a href="#"> <!--<?php echo $_smarty_tpl->tpl_vars['url']->value['Home'];?>
?n=index&a=app_load -->
						<img src="images/icon/android-icon.png" alt="">
						<p>安卓</p>
					</a>
				</div>
				<div class="fl icon">
					<a href="#"><!--<?php echo $_smarty_tpl->tpl_vars['url']->value['Home'];?>
?n=index&a=app_load -->
						<img src="images/icon/ios-icon.png" alt="">
						<p>IOS</p>
					</a>
				</div>
				<div class="fl icon code-dow">
					<img src="images/icon/qr-code.png" alt="" style="margin-top:14px;">
					<div class="qr-code">
						<img src="images/icon/code-dow.png" style="width:110px;" alt="">
						<p>扫一扫，下载</p>
					</div>
				</div>
				<div class="clear"></div>
				<div><a href="<?php echo $_smarty_tpl->tpl_vars['url']->value['Home'];?>
?n=index&a=load" class="btn-dow"><img src="images/icon/icon-03.png" alt="">&nbsp;下载电脑版</a></div>
				
			</div>
		</div><?php }
}
