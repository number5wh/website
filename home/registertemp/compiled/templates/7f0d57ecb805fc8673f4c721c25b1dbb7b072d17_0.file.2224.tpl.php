<?php
/* Smarty version 3.1.31, created on 2018-07-04 20:11:20
  from "C:\website\home\Templates\default\Games\2224.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5b3cb968cac7f6_21938866',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '7f0d57ecb805fc8673f4c721c25b1dbb7b072d17' => 
    array (
      0 => 'C:\\website\\home\\Templates\\default\\Games\\2224.tpl',
      1 => 1530522306,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5b3cb968cac7f6_21938866 (Smarty_Internal_Template $_smarty_tpl) {
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
    <?php echo '<script'; ?>
 type="text/javascript" src="js/common.js"><?php echo '</script'; ?>
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
            console.log(id);
            for (i=1;i<=4;i++)
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
                                <div class="cLabel">
                                 <img src="/images/1_2.gif" id="lab_1" onclick="changeTab(1)" />
                                  <img src="/images/2_1.gif" id="lab_2" onclick="changeTab(2)" /> 
                                  <!-- <img src="/images/3_1.gif" id="lab_3" onclick="changeTab(3)" />
                                   <img src="/images/4_1.gif" id="lab_4" onclick="changeTab(4)" />  -->
                               </div>
                                <div class="cContent" id="con_1">
                                    <!--游戏简介--> 
                                    <div class="cCtitle"> 游戏介绍</div>
                                    <div class="cCbody">&nbsp;&nbsp捕鱼摇钱树是根据街玩捕鱼达人，自主研发的一款休闲娱乐游戏。游戏场景采用3D制作，各种鱼类游动姿态自然，炮弹打击粒子效果华丽，打击感强烈。游戏中丰富了更多游戏场景、游戏火炮多达数十种、更有随机翻倍火炮BUFF使你游玩的更加爽快。里面不仅有大鲸鱼，宝藏海龟，更有屠龙宝刀等你来拿，简直停不下来。 </div>

                                    <img src="images/2224/screen.jpg" /> </div>
                                </div>

                                <div class="cContent" id="con_2" style="display: none;">
                                     <div class="cCtitle"> 规则介绍</div>
                                    <div class="cCbody">
                                        <p>　　 1、多种炮值按键盘“↑↓”键切换：50-9900炮；鱼类的倍率为2-500倍；炮弹永不落空，无限反弹；选择不同的炮值打相同的鱼类得到不同的分值，炮值越大得到的分值越多。<br>　　2、李逵可通过不断劈鱼并吸收玩家炮弹将自己倍率从40增长到300多倍！<br>　　3、创新的局部炸弹、超级炸弹、定屏炸弹，三种炸弹强强联手，具有大规模的杀伤力。<br>　　4、创新的定屏炸弹，打中定屏炸弹得20倍奖励，并定住全屏所有的鱼，让玩家乐不思蜀的捕鱼。<br>　　5、强大的锁定功能，按“S”键可以锁定30倍以上的鱼或同类炸弹及组合的鱼，让大家不放过任何鱼类，按“Q”键可以取消锁定。<br>　　6、能量炮加快捕鱼速度，增强炮力，属于兵家必争之法宝！</span></p>
                                    </div>
                                </div>
                                <div class="cContent" id="con_3" style="display: none;">
                                </div>
                                <div class="cContent" id="con_4" style="display: none;">
                                    <!--游戏限额-->
                                    <div class="cCtitle">游戏限额</div>
                                    <div class="cCPading">
                                        <table width="100%" cellspacing="0" cellpadding="0">
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
