<?php
/* Smarty version 3.1.31, created on 2018-07-10 16:31:38
  from "C:\website\home\Templates\default\news\6.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5b446eea010958_46505990',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '03b8e3f4a50c3b1c93c429ee2e32819a058d4446' => 
    array (
      0 => 'C:\\website\\home\\Templates\\default\\news\\6.html',
      1 => 1530522314,
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
function content_5b446eea010958_46505990 (Smarty_Internal_Template $_smarty_tpl) {
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

							<div align="center" style="color:white; font-size:30px;"><b>实体卡领取活动结束公告</b></div><br>

							  <div>

					                <p align="left"><span style="font-size:18px;">亲爱的游戏玩家： </span></p>

									<!--StartFragment -->

									<div>

										<span style="font-size:18px;"> 

											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 在玩家朋友的热情参与下，本次百亿欢乐豆已经领取完毕。感谢新老玩家的支持与厚爱。为了让更多玩家能享受到官方福利，签到活动将继续进行，请各玩家朋友广而告之。
看到消息把公告发出去<br><br>
											</span>
										<span style="font-size:18px;"> 
											实体卡活动圆满结束，敬请期待下次活动。<br><br>
											</span>
									</div>

									<div align="right"><strong> <p align="right">779游戏运营团队</p>

										<p align="right">2017-04-14</p>

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
