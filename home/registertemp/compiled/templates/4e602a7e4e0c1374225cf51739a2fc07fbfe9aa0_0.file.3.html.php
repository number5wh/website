<?php
/* Smarty version 3.1.31, created on 2018-07-10 16:31:09
  from "C:\website\home\Templates\default\news\3.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5b446ecd1427b8_45341019',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4e602a7e4e0c1374225cf51739a2fc07fbfe9aa0' => 
    array (
      0 => 'C:\\website\\home\\Templates\\default\\news\\3.html',
      1 => 1530522311,
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
function content_5b446ecd1427b8_45341019 (Smarty_Internal_Template $_smarty_tpl) {
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
							<div align="center" style="color:white; font-size:30px;"><b>关于假冒官方客服的告示</b></div><br>
							  <div>
					                <p align="left"><span style="font-size:18px;">亲爱的游戏玩家： </span></p>
									<!--StartFragment -->
									<div>
										<span style="font-size:20px;text-indent:2em;display: block">近期发现很多假冒客服出现，请玩家朋友认准官方的客服，<font color="#ff0000">我们QQ是营销QQ，不是私人QQ。</font>假冒官方的QQ昵称改成***，但是QQ号不是这个.我们的营销QQ号是<font color="#ff0000">***</font>，QQ号是唯一的。官方没有私人QQ，也没有私人微信，我们只有企业QQ和微信公众号，我们不会主动问玩家要账号密码，也不会引导玩家去充值转账，我们充值方式只有官网一种。<Br>
										唯一官网：www.game779.com<br>
										唯一企业QQ：***<br>
										唯一公众号：779游戏中心
                                        
										</span>
									</div>
									<div align="right"><strong> <p align="right">779游戏运营团队</p>
										<p align="right">2017-06-12</p>
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
