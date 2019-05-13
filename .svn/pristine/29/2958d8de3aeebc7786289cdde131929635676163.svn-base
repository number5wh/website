<?php
/* Smarty version 3.1.31, created on 2018-07-17 16:11:50
  from "C:\website\home\Templates\default\games.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5b4da4c641a9b6_75051748',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'da584da6597c7ddb0042bb688b96a1c52f1de249' => 
    array (
      0 => 'C:\\website\\home\\Templates\\default\\games.html',
      1 => 1531815107,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:public/header.tpl' => 1,
    'file:public/banner.tpl' => 1,
    'file:public/footer.tpl' => 1,
  ),
),false)) {
function content_5b4da4c641a9b6_75051748 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<!-- <meta name="renderer" content="webkit|ie-comp|ie-stand"> -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>游戏介绍</title>
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/public.css">
	<link rel="stylesheet" href="css/introduce.css">
	<?php echo '<script'; ?>
 src="js/jquery.min.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 src="js/jquery.SuperSlide.2.1.1.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 src="js/bootstrap.min.js"><?php echo '</script'; ?>
>
</head>
<body>
	<?php $_smarty_tpl->_subTemplateRender("file:public/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
	
	<div class="clear"></div>
	<!-- 轮播 -->
	<div id="slideBox" class="slideBox">
		<?php $_smarty_tpl->_subTemplateRender("file:public/banner.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

	</div>
	<div class="clear"></div>
	<!-- 主体部分 -->
	<style>
	
</style>
	<main>
		<div class="container">
			<div class="game clearfix">
				<div class="game-title"><span><img src="images/icon/quality-icon.png" alt="">&nbsp;</span>精品游戏</div>
				<div class="game-picScroll">
					<div class="bd">
						<ul class="gameList">
						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['GameKind']->value, 'kind');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['kind']->value) {
?>
							<li>
								<a href="/?n=games&a=client_game_intro&id=<?php echo $_smarty_tpl->tpl_vars['kind']->value['KindID'];?>
">
									<div class="pic"><img src="images/games/<?php echo $_smarty_tpl->tpl_vars['kind']->value['KindID'];?>
.png" /></div>
									<div class="title"><?php echo $_smarty_tpl->tpl_vars['kind']->value['KindName'];?>
</div>
								</a>
							</li>
						 <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>
							
						</ul>
					</div>
				</div>
			</div>
			<div class="game-introduction clearfix">
				<h3>游戏介绍</h3>
				<p>779游戏是一款以休闲娱乐为主的棋牌游戏，其中包含广受玩家欢迎的斗地主、四国军棋、五子棋、象棋等游戏。</p>
			</div>
		</div>
	</main>
    <?php $_smarty_tpl->_subTemplateRender("file:public/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

</body>
<?php echo '<script'; ?>
>
		// banner	
 
jQuery(".slideBox").slide({mainCell:".bd ul",effect:"fold",autoPlay:true,delayTime:1000});	

<?php echo '</script'; ?>
>
</html>
<?php }
}
