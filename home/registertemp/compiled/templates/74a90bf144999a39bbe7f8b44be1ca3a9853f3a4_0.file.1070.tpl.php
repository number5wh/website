<?php
/* Smarty version 3.1.31, created on 2018-07-10 17:21:19
  from "C:\website\home\Templates\default\Games\1070.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5b447a8f9e5eb9_76236825',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '74a90bf144999a39bbe7f8b44be1ca3a9853f3a4' => 
    array (
      0 => 'C:\\website\\home\\Templates\\default\\Games\\1070.tpl',
      1 => 1530522304,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5b447a8f9e5eb9_76236825 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <title>游戏介绍</title>
    <?php echo '<script'; ?>
 type="text/javascript" src="js/jquery-1.11.2.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 type="text/javascript" src="js/fun.js"><?php echo '</script'; ?>
>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <?php echo '<script'; ?>
 type="text/javascript">
        
        window.console = window.console || (function(){ 
            var c = {};  
                c.log = c.warn = c.debug = c.info = c.error = c.time = c.dir = c.profile = c.clear = c.exception = c.trace = c.assert = function(){}; 
            return c; 
        })();
        function changeTab(id)
        {
            for (i=1;i<=3;i++)
            {
                if (id==i)
                {
                    $("#lab_"+i).attr("src","/images/"+i+"_2.gif");
                    $("#con_"+i).show();
                }
                else
                {
                    $("#lab_"+i).attr("src","/images/"+i+"_1.gif");
                    $("#con_"+i).hide();
                }
            }
        }
        
    <?php echo '</script'; ?>
>
</head>
<body>

            <div class="detailsRight">
                <div class="commonProblem new">
                    <div class="main">
                        <div class="mainframe">

                            <div class="cBody">
                                <img src="images/1070/title.jpg" />


                                <div class="cLabel">
                                    <img src="/images/1_2.gif" id="lab_1" onclick="changeTab(1)"  />
                                    <img src="/images/2_1.gif" id="lab_2" onclick="changeTab(2)" />
                                    <img src="/images/3_1.gif" id="lab_3" onclick="changeTab(3)" />

                                </div>

                                <div class="cContent" id="con_1">
                                    <!--游戏简介-->
                                    <div class="cCbody"> "欢乐斗地主"是一种扑克游戏。游戏最少由3个玩家进行，用一副54张牌（连鬼牌），其中一方为地主，其余两家为另一方，双方对战，先出完牌的一方获胜。该扑克游戏最初流行于中国湖北武汉市汉阳区，现已逐渐在各地流行。</div>
                                    <img src="images/1070/screen.jpg" alt="斗地主" />
                                </div>
                                <div class="cContent" id="con_2" style="display:none;">
                                    <!--游戏规则-->
                                    <div class="cCtitle">游戏规则</div>
                                    <div class="cCbody">

                                        <li>一副牌 54 张，一人 17 张，留 3 张做底牌，在确定地主之前玩家不能看底牌。 </li>

                                        <li>叫牌按出牌的顺序轮流进行，叫牌时可以选择 “叫地主 ” 、“ 不叫 ” 。如果有玩家选择 “叫地主 ” 则立即结束叫牌，该玩家为地主；如果都“不叫”，则重新发牌，重新叫牌，直到有人“叫地主”为止 。 </li>

                                        <li>第一轮叫牌的玩家由系统随机选定。 </li>

                                        <li>当某位玩家叫完地主后，按照次序每位玩家均有且只有一次“抢地主”的机会。玩家选择“抢地主”后，如果没有其他玩家继续“抢地主”则地主权利属于该名“抢地主”的玩家。 </li>

                                        <li>如果没有任何玩家选择“抢地主”，则地主权利属于“叫地主”的玩家。 </li>

                                        <li>每“抢地主”一次，游戏倍数 *2 。 </li>

                                        <li>凡是有过“不叫地主”操作的玩家无法进行“抢地主”的操作。 </li>


                                        <li>将三张底牌交给地主，并亮出底牌让所有人都能看到。地主首先出牌，然后按逆时针顺序依次出牌，轮到用户跟牌时，用户可以选择 “ 不出 ” 或出比上一个玩家大的牌。某一玩家出完牌时结束本局。 </li>



                                    </div>
                                    <div class="cCtitle">牌型</div>
                                    <div class="cCbody">
                                        <li>火箭：即双王（大王和小王），最大的牌。 </li>
                                        <li>炸弹：四张同数值牌（如四个 7 ）。 </li>
                                        <li>单牌：单个牌（如红桃 5 ）。 </li>
                                        <li>对牌：数值相同的两张牌（如梅花 4+ 方块 4 ）。 </li>
                                        <li>三张牌：数值相同的三张牌（如三个 J ）。 </li>
                                        <li>三带一：数值相同的三张牌 + 一张单牌或一对牌。例如： 333+6 或 444+99 </li>
                                        <li>单顺：五张或更多的连续单牌（如： 45678 或 78910JQK ）。不包括 2 点和双王。 </li>
                                        <li>双顺：三对或更多的连续对牌（如： 334455 、 7788991010JJ ）。不包括 2 点和双王。 </li>
                                        <li>三顺：二个或更多的连续三张牌（如： 333444 、 555666777888 ）。不包括 2 点和双王。 </li>
                                        <li>飞机带翅膀：三顺＋同数量的单牌（或同数量的对牌）。 </li>
                                        <li>如： 444555+79 或 333444555+7799JJ </li>
                                        <li>四带二：四张牌＋两手牌。（注意：四带二不是炸弹）。 </li>
                                        <li>如： 5555 ＋ 3 ＋ 8 或 4444 ＋ 55 ＋ 77 。 </li>
                                    </div>

                                </div>
                                <div class="cContent" id="con_3" style="display:none;">
                                    <!--游戏计分-->
                                    <div class="cCtitle">游戏计分</div>
                                    <div class="cCbody">
                                        任意一家出完牌后结束游戏，若是地主先出完牌则地主胜，否则另外两家胜。
                                    </div>
                                    <div class="cCPading">
                                        <li>底分为1分</li>
                                        <li>叫分为积分倍数，最大为3</li>
                                        <li>每抓到一个炸弹积分翻倍</li>
                                        <li>总分=叫分 * 1 * 炸弹个数</li>
                                        <li>地主只出一手牌或者闲家没有出牌再翻1倍</li>
                                    </div>
                                    <br />
                                    <div class="cCtitle">逃跑扣分</div>
                                    <div class="">1、系统发牌前逃跑 </div>
                                    <div class="cCPading">农民：农民逃跑扣3分</div>
                                    <div class="cCPading">地主：地主逃跑扣6分</div>
                                    <div class="">2、系统发牌后逃跑 </div>
                                    <div class="cCPading">地主：地主逃跑扣6分。</div>
                                    <div class="cCPading">农民：农民逃跑扣3分。</div>
                                    <br />
                                    <div class="cCbody">地主与农民之间的记分方式是固定。地主赢的数量与农民输的数量是一样的。</div>

                                </div>
                                <div class="cContent" id="con_4" style="display: none;">
                                    <!--游戏限额-->
                                    <div class="cCtitle">游戏限额</div>
                                    <div class="cCPading">
                                        <table width="100%" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <th width="30%" style="border:1px solid; border-bottom:none;" bgcolor="#CCCCCC">房间类型</th>
                                                <th width="30%" style="border-top:1px solid; border-right:1px solid;" bgcolor="#CCCCCC">每局限额</th>
                                                <th width="40%" style="border-top:1px solid; border-right:1px solid;" bgcolor="#CCCCCC">每日限额</th>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><table width="100%" cellspacing="0" cellpadding="0" style="border:1px solid;">
                                                        <tr>
                                                            <td align="center" width="30%" style="border-right:1px solid;">初级房间</td>
                                                            <td align="center" width="30%">5W欢乐豆</td>
                                                        </tr>
                                                        <tr>
                                                            <td align="center" style="border-top:1px solid; border-right:1px solid;" width="30%">中级房间</td>
                                                            <td align="center" style="border-top:1px solid;" width="30%">20W欢乐豆</td>
                                                        </tr>
                                                        <tr>
                                                            <td align="center" style="border-top:1px solid; border-right:1px solid;" width="30%">高级房间</td>
                                                            <td align="center" style="border-top:1px solid;" width="30%">100W欢乐豆</td>
                                                        </tr>
                                                    </table></td>
                                                <td align="center" width="40%" style="border:1px solid; border-left:none;">500万欢乐豆</td>
                                            </tr>
                                        </table>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
            </div>
        </div>

</body>
</html><?php }
}
