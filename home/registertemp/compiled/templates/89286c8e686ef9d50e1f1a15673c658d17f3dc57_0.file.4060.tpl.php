<?php
/* Smarty version 3.1.31, created on 2018-07-04 20:20:50
  from "C:\website\home\Templates\default\Games\4060.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5b3cbba2af3006_12702534',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '89286c8e686ef9d50e1f1a15673c658d17f3dc57' => 
    array (
      0 => 'C:\\website\\home\\Templates\\default\\Games\\4060.tpl',
      1 => 1530522308,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:public/header.tpl' => 1,
    'file:public/footer.tpl' => 1,
  ),
),false)) {
function content_5b3cbba2af3006_12702534 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>779游戏大厅-游戏介绍</title>
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/public.css">
	<link rel="stylesheet" href="css/game.css">

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
	<!-- 主体部分 -->
	<main>
		<div class="container">
			<div class="game-title clearfix">
				<div class="game-icon fl">
					<img src="images/games/4060.png" alt="">
				</div>
				<div class="fr">
					<h3>中国象棋</h3>					<p>象棋，亦作“象碁”、中国象棋（英文名Xiangqi）。在中国有着悠久的历史，属于二人对抗性游戏的一种，由于用具简单，趣味性强，成为流行极为广泛的棋艺活动。主要流行于华人及越南人社区，是中国正式开展的78个体育运动项目之一。是首届世界智力运动会的正式比赛项目之一。</p>
				</div>
			</div>
			<h4>游戏浏览</h4>
			<div class="game-scroll">
				<div class="hd">
					<a class="next"><img src="images/icon/zuo-icon.png" alt=""></a>
					<a class="prev"><img src="images/icon/you-icon.png" alt=""></a>
				</div>
				<div class="bd">
					<ul class="gameList">
						<li>
							<img src="images/4060/screen.jpg" alt="">
						</li>
						
					</ul>
				</div>
			</div>
			<h4>游戏说明</h4>
			<div class="explain">
				<h5>【吃子规则】</h5>
									 <p>棋子： 棋子共有三十二个，分为红、黑两组，每组共十六个，各分七种，其名称和数目如下： </p>
                                     <p>红棋子：帅一个，车、马、炮、相、士各两个，兵五个。 </p>
                                     <p>黑棋子：将一个，车、马、炮、象、士各两个，卒五个。 </p>
                                     <p>走法：对局时，由执红棋的一方先走，双方轮流各走一着，直至分出胜、负、和，对局即终了。 </p>
                                     <p>轮到走棋的一方，将某个棋子从一个交叉点走到另一个交叉点，或者吃掉对方的棋子而占领其交叉点，都算走了一着。双方各走一着，称为一个回合。 </p>
                                     <p>帅（将）每一着只许走一步，前进、后退、横走都可以，但不能走出“九宫”。将和帅不准在同一直线上直接对面，如一方已先占据，另一方必须回避。 </p>
                                     <p>士每一着只许沿“九宫”斜线走一步，可进可退。 </p>
                                     <p>相（象）不能越过“河界”，每一着斜走两步，可进可退，即俗称“相（象）走田字”。当田字中心有别的棋子时，俗称“塞（相）象眼”，则不许走过去。 </p>
                                     <p>马每着走一直（或一横）一斜，可进可退，即俗称“马走日字”。如果在要去的方向有别的棋子挡住。俗称“蹩马腿”，则不许走过去。 
                                    车每一着可以直进、直退、横走，不限步数。 </p>
			  <p>走一着棋时，如果己方棋子能够走到的位置有对方棋子存在，就可以把对方棋子吃掉而占领那个位置。  </p>
                                     <p>只有炮吃子时必须隔一个棋子（无论是哪一方的）跳吃，即俗称“炮打隔子”。  </p>
                                     <p>除帅（将）外其他棋子都可以听任对方吃，或主动送吃。吃子的一方，必须立即把被吃掉的棋子从棋盘上拿走。 
 </p>
				<h5>【游戏胜负】</h5>
			 <p>帅（将）被对方“将死”为负。   </p>
                                         <p>走棋后形成帅（将）直接对面为负。   </p>
                                         <p>被“困毙”为负。   </p>
                                         <p>在规定的时限内未走满规定的着数为负。   </p>
                                         <p>双方都未赢为和棋   </p>
				
			<h5>【游戏计分】</h5>
			<p>游戏胜方各获得2个基础分，负方扣2个基础分，和棋双方为0分 </p>
			<h5>【逃跑计分】</h5>
			 <p>有玩家逃跑，则判另一方获胜。  </p>
			</div>
		</div>
	</main>
	<?php $_smarty_tpl->_subTemplateRender("file:public/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

	<?php echo '<script'; ?>
>
		// 游戏滑动
		
			jQuery(".game-scroll").slide({titCell:".hd ul",mainCell:".bd ul",autoPage:true,effect:"left",autoPlay:true,vis:2,trigger:"click"});
		
	<?php echo '</script'; ?>
>
</body>
</html><?php }
}
