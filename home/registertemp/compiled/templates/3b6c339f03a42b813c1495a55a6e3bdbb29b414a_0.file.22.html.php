<?php
/* Smarty version 3.1.31, created on 2018-07-10 16:32:48
  from "C:\website\home\Templates\default\news\22.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5b446f306bbce2_49387735',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3b6c339f03a42b813c1495a55a6e3bdbb29b414a' => 
    array (
      0 => 'C:\\website\\home\\Templates\\default\\news\\22.html',
      1 => 1530522310,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:public/header.tpl' => 1,
    'file:public/leftMenu.tpl' => 1,
    'file:public/footer.tpl' => 1,
  ),
),false)) {
function content_5b446f306bbce2_49387735 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="renderer" content="webkit|ie-comp|ie-stand">
	<title>临时维护公告</title>
	<?php echo '<script'; ?>
 type="text/javascript" src="js/jquery-1.11.2.min.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 type="text/javascript" src="js/fun.js"><?php echo '</script'; ?>
>
	<link rel="stylesheet" type="text/css" href="css/style.css">

</head>
<body>
    <?php $_smarty_tpl->_subTemplateRender("file:public/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

	<div id="main">
		<div class="content">
			<div class="details h">
                <?php $_smarty_tpl->_subTemplateRender("file:public/leftMenu.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

				<div class="detailsRight">
					<div class="commonProblem new-two">
						<div id="main">
							<div align="center" style="color:white; font-size:30px;"><b>779游戏更名公告</b></div><br>
							  <div>
					                <!-- <p align="left"><span style="font-size:18px;">亲爱的游戏玩家： </span></p> -->
									<!--StartFragment -->
									<div>
										<span style="font-size:18px;text-indent:2em;display: block">
											<p>亲爱的游戏玩家：
										</p>
									</br>
										<p>779游戏正式更名为多多游戏，原电脑和手机客户端讲自动升级成最新版的多多游戏，<br>
										升级完成之后原779游戏账号即可在多多游戏登录，原数据保留，欢乐豆不变。<br>
										原779微信登录账号需要在官网生成新的账号密码才可以在多多游戏登录，登录之后和原微信账号的数据不变。<br>
										如您的客户端无法自动更新到最新版本，请到多多官网（www.gameduoduo.com）下载最新客户端即可。<br>
										详询在线客服QQ：800171968</p>
										</span>
									</div>
									</br>
									</br>
									</br>
									</br>
									</br>
									 <div align="right"><strong> <p align="right">779游戏运营团队</p>
										<p align="right">2017-07-26</p>
										<br>
										</strong>
									</div>
					            </div>
						</div>
					</div>
				</div>
				<div style="clear: both"></div>
			</div>
		</div>
	</div>
    <?php $_smarty_tpl->_subTemplateRender("file:public/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

</body>
</html><?php }
}
