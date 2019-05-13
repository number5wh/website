<?php
/* Smarty version 3.1.31, created on 2018-07-10 16:37:50
  from "C:\website\home\Templates\default\news\76.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5b44705e2e3b36_16919393',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ca958d5540d262d46930caa409537df0d2d9ca6c' => 
    array (
      0 => 'C:\\website\\home\\Templates\\default\\news\\76.html',
      1 => 1530522316,
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
function content_5b44705e2e3b36_16919393 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="renderer" content="webkit|ie-comp|ie-stand">
	<title>维护公告</title>
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
							<div align="center" style="color:white; font-size:30px;"><b>维护公告</b></div><br>
							  <div>
					                <!-- <p align="left"><span style="font-size:18px;">亲爱的游戏玩家： </span></p> -->
									<!--StartFragment -->
									<div>
										<span style="font-size:18px;text-indent:2em;display: block">
											<p>亲爱的游戏玩家：
										</p>
									</br>
										<p>779游戏平台将于2016年05月17日9:00-10:00进行维护，维护时间为 60分钟左右。如有延时另行通知，请关注我们779游戏官网公告。给您带来不便敬请谅解。</p>
										</span>
									</div>
									</br>
									</br>
									</br>
									</br>
									</br>
									 <div align="right"><strong> <p align="right">779游戏运营团队</p>
										<p align="right">2016-05-16</p>
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
