<?php
/* Smarty version 3.1.31, created on 2018-07-10 18:07:22
  from "C:\website\home\Templates\default\Games\2018.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5b44855a65a6d3_66588498',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '88a96a60f55dcf64b298d181bbe0d6d4fdcf9b4c' => 
    array (
      0 => 'C:\\website\\home\\Templates\\default\\Games\\2018.tpl',
      1 => 1531217240,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:public/header.tpl' => 1,
    'file:public/footer.tpl' => 1,
  ),
),false)) {
function content_5b44855a65a6d3_66588498 (Smarty_Internal_Template $_smarty_tpl) {
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
                    <img src="images/games/2018.png" alt="">
                </div>
                <div class="fr">
                    <h3>大闹天宫</h3>
                    <p>大闹天宫街机捕鱼游戏是一款大受欢迎的休闲街机游戏，大闹天宫捕鱼拥有玉皇大帝、孙悟空、神仙船、美人鱼等怪物角色，使得捕鱼的可玩性和趣味性大大的提升，给人眼前一亮的感觉。</p>
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
                            <img src="images/2018/screen.jpg" alt="">
                        </li>
                    </ul>
                </div>
            </div>
            <h4>游戏说明</h4>
            <div class="explain">
            <h5>【游戏规则】</h5>
                <p>Z键可以加速射击</p>

                <p>S锁定大鱼 </p>

                <p>Q取消锁定 </p>

                <p>当发炮累计到一定值后可以获得一个激光炮，激光炮有非常非常大的概率打死碰到的鱼！！ </p>

            <h5>【计分规则】</h5>
                <img src="/images/2018/score.jpg" alt="">
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
