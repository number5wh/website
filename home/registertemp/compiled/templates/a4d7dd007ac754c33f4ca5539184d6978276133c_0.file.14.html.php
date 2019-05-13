<?php
/* Smarty version 3.1.31, created on 2018-07-10 16:32:06
  from "C:\website\home\Templates\default\news\14.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5b446f06d69d44_84843215',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a4d7dd007ac754c33f4ca5539184d6978276133c' => 
    array (
      0 => 'C:\\website\\home\\Templates\\default\\news\\14.html',
      1 => 1530522309,
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
function content_5b446f06d69d44_84843215 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>

<html lang="en">

<head>

	<meta charset="UTF-8">

	<meta name="renderer" content="webkit|ie-comp|ie-stand">

	<title>帮助中心</title>

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

							<div align="center" style="color:white; font-size:30px;"><b>520活动结束公告</b></div><br>

							  <div>

					                <p align="left"><span style="font-size:18px;">亲爱的游戏玩家： </span></p>

									<!--StartFragment -->

									<div>

										<span style="font-size:18px;"> 

											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
											在广大朋友的热情参与下，本次活动的所有福利卡已经领取完毕。领取的福利卡只限当天使用，超时未使用将会失效。感谢大家的支持与参与。
											<br><br>
											</span>
										<span style="font-size:18px;"> 
											
											<br><br>
											</span>
									</div>

									<div align="right"><strong> <p align="right">779游戏运营团队</p>

										<p align="right">2017-05-20</p>

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
