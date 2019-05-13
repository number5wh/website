<?php
/* Smarty version 3.1.31, created on 2018-07-12 12:01:45
  from "C:\website\home\Templates\default\Games\1011.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5b46d2a98a6583_20903866',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'bf9f2a269faa3793d74359ea557b38220fe41cc7' => 
    array (
      0 => 'C:\\website\\home\\Templates\\default\\Games\\1011.tpl',
      1 => 1531368101,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:public/header.tpl' => 1,
    'file:public/footer.tpl' => 1,
  ),
),false)) {
function content_5b46d2a98a6583_20903866 (Smarty_Internal_Template $_smarty_tpl) {
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
                    <img src="images/games/bpic_4.png" alt="">
                </div>
                <div class="fr">
                    <h3>瑞安牛牛</h3>
                    <p>“瑞安牛牛”主要流行于我国浙江温州瑞安，玩法非常简单。游戏由2到4人打一副牌共54张。游戏中每人分得5张牌，并以3张一排2张一排形式进行编排，如果3张牌部分和2张牌部分的点数相加都刚好是10的整数倍，这种牌型称为“牛牛”，是游戏中最大的牌型（金牛、银牛、牛牛）。</p>
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
                            <img src="images/1011/screen.jpg" alt="">
                        </li>
                    </ul>
                </div>
            </div>
            <h4>游戏说明</h4>
            <div class="explain">
            <h5>【游戏规则】</h5>
            <p>第一局游戏，系统随机确定一名玩家开始叫庄，以后按照逆时针顺序轮换优先叫庄权。如所有玩家都放弃叫庄，则强制第一位叫庄玩家坐庄。除庄家外的其他玩家都为闲家。叫庄结束后闲家开始下注，所有闲家都下注后系统开始发牌，每个玩家分得五张牌，玩家编排牌型后亮牌比较大小，庄家需和每一位闲家比较牌型的大小计算得分，闲家之间不进行比较。每位玩家将牌型编排好之后出牌。</p>
            <h5>【牌型介绍】</h5>
            <p><b>牌型大小：</b></p>
            <p>牛牛>牛九>牛八>牛七>牛六>牛五>牛四>牛三>牛二>牛一>无牛</p>
            <p>大王、小王、K、Q、J、10、9、8、7、6、5、4、3、2 、A</p>
            <p>特殊牌型：大王、小王、J、Q、K都是当10点，然后A是当1点，其他的牌型当自身的点数。</p>
            <p>牌型组合：牌局开始每个人手中都有五张牌，然后玩家需要将手中任意三张牌凑成10点或20点或30点都可以，这样是被称之为牛。接下来在将其余的两张的点数相加得出几点。去掉十位数，只留个位数来进行比较，如果接下来两张的相加点数也正好是整数的话，那就是最大的牌型：“牛牛”。</p>
            <p>当庄家与闲家同时出现相同点数时，系统自动将两家手中牌的最大那一张进行比较，谁大就由谁获得胜利。如果出现牌也相同大的话，就按花色来进行比较，花色大小依次为黑桃、红桃、梅花、方块。</p>
            <p>获胜条件</p>
            <p>游戏为四个人玩，一方为庄家，另三方为闲家，开始后由闲家向桌面掷欢乐豆，掷完欢乐豆后系统开始发牌，最后比较大小，闲家的牌点数大于庄家则算胜利。先决条件是：牛牛大于任何牌点数，牛9（前三张的组合成整数称之为“牛”）大于牛8。有牛的牌大于无牛的牌，玩家手中任意的三张牌组合不成一个整数，则被称之为无牛牌，无牛牌的比较就按牌中最大的那张进行比较。</p>
            <h5>【游戏计分】</h5>
            <p>牛牛——5倍与压注欢乐豆</p>
            <p>牛9——4倍与压注欢乐豆</p>
            <p>牛8——3倍与压注欢乐豆</p>
            <p>牛7——2倍与压注欢乐豆</p>
            <p>牛7以下牌型只翻1倍</p>
            <p>根据压注欢乐豆 </p>
            <h5>逃跑扣分</h5>
            <p><b>1、系统发牌前逃跑</b></p>
            <p>闲家：未押注逃跑不计分，押注后逃跑扣除所押欢乐豆给庄家</p>
            <p>庄家：逃跑扣除所有已押注闲家所押欢乐豆（1倍），已押注闲家获得自身先前所押欢乐豆（1倍），未下注者不得欢乐豆</p>
            <p><b>2、系统发牌后逃跑</b></p>
            <p>闲家：闲家逃跑将他所有押的欢乐豆*5进行扣除，庄家得到他所押欢乐豆*5。</p>
            <p>庄家：逃跑需要扣除他所赔最大限额的欢乐豆，需计算三位闲家所押，就是三家压的欢乐豆的五倍进行扣除。</p><br>
            <p>庄家与闲家之间的记分方式是固定。庄家赢的数量与闲家输的数量是成正比，比如：闲家押欢乐豆为1000两，如果此时庄家摸到牛牛此种大牌，闲家刚才所压的1000两需要翻五倍给庄家。所以两者之间存在的输赢欢乐豆数量是一个固定量。</p>
            <h5>【游戏限额】</h5>
            <table width="100%" border="1">
                <tr>
                    <th>房间类型</th>
                    <th>每局限额</th>
                    <th>每日限额</th>
                </tr>
                <tr>
                    <td>初级房间</td>
                    <td>50W欢乐豆</td>
                    <td>5000W欢乐豆</td>
                </tr>
                <tr>
                    <td>中级房间</td>
                    <td>200W欢乐豆</td>
                    <td>5000W欢乐豆</td>
                </tr>
                <tr>
                    <td>高级房间</td>
                    <td>1000W欢乐豆</td>
                    <td>5000W欢乐豆</td>
                </tr>
            </table>
        </div>
    </main>
    <?php $_smarty_tpl->_subTemplateRender("file:public/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

    <?php echo '<script'; ?>
>
        // 游戏滑动
        
            jQuery(".game-scroll").slide({titCell：".hd ul",mainCell：".bd ul",autoPage：true,effect："left",autoPlay：true,vis：2,trigger："click"});
        
    <?php echo '</script'; ?>
>
</body>
</html><?php }
}
