<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <title>游戏介绍</title>
    <script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="js/fun.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <link rel="stylesheet" type="text/css" href="css/style.css">

    <script type="text/javascript">
        {literal}
        window.console = window.console || (function(){ 
            var c = {};  
                c.log = c.warn = c.debug = c.info = c.error = c.time = c.dir = c.profile = c.clear = c.exception = c.trace = c.assert = function(){}; 
            return c; 
        })();
        function changeTab(id)
        {
            console.log(id);
            for (i=1;i<=7;i++)
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
        {/literal}
    </script>
</head>
<body>

            <div class="detailsRight">
                <div class="commonProblem new">
                    <div class="main">
                        <div class="mainframe">
                            <div class="cBody"> <img src="images/1140/title.jpg" />
                                <div class="cLabel"> 
                                    <img src="/images/1_2.gif" id="lab_1" onclick="changeTab(1)" /> 
                                    <img src="/images/2_1.gif" id="lab_2" onclick="changeTab(2)" /> 
                                    <img src="/images/3_1.gif" id="lab_3" onclick="changeTab(3)" /> 
                                    <img src="/images/4_1.gif" id="lab_4" onclick="changeTab(4)" /> 
                                    <img src="/images/7_1.gif" id="lab_7" onclick="changeTab(7)" /> 
                                </div>
                                <div class="cContent" id="con_1">
                                    <!--游戏简介-->
                                    <div class="cCbody">“通比牛牛”是一款在民间广为流传的牌类游戏。简单说来，只需几名朋友聚在一起，每人每局随机发五张牌，一决高下，最终一名玩家最终胜出。游戏规则相当简单，但是游戏过程耐玩刺激，比拼是智慧技巧，更是胆识。</div>
                                    <img src="images/1140/screen.jpg" /> </div>
                                <div class="cContent" id="con_2" style="display: none;">
                                    <!--游戏规则-->
                                    <div class="cCtitle"> 玩法介绍</div>
                                    <div class="cCbody"> 第一局游戏，游戏开始后系统开始发牌，每个玩家分得五张牌，玩家编排牌型后亮牌比较大小，所有玩家比较牌型的大小计算得分。
</div>
                                    <div class="cCtitle"> 牌型介绍</div>
                                    <div class="cCbody">牌型大小</div>
                                    <div class="cCbody">牛牛>牛九>牛八>牛七>牛六>牛五>牛四>牛三>牛二>牛一>无牛</div>
                                    <div class="cCbody">大王、小王、K、Q、J、10、9、8、7、6、5、4、3、2 、A</div>
                                    <div class="cCbody">特殊牌型</div>
                                    <div class="cCbody">大王、小王、J、Q、K都是当10点，然后A是当1点，其他的牌型当自身的点数。</div>
                                    <div class="cCbody">牌型组合</div>
                                    <div class="cCbody">牌局开始每个人手中都有五张牌，然后玩家需要将手中任意三张牌凑成10点或20点或30点都可以，这样是被称之为牛。接下来在将其余的两张的点数相加得出几点。去掉十位数，只留个位数来进行比较，如果接下来两张的相加点数也正好是整数的话，那就是最大的牌型：“牛牛”。</div>
                                    <div class="cCbody">当庄家与闲家同时出现相同点数时，系统自动将两家手中牌的最大那一张进行比较，谁大就由谁获得胜利。如果出现牌也相同大的话，就按花色来进行比较，花色大小依次为黑桃、红桃、梅花、方块。</div>
                                    <div class="cCbody">获胜条件</div>
                                    <div class="cCbody">大小王可以当做任意牌使用，系统自动会把大小王折算成能配成最大牛牛的点数，俗称百变。同点数下，百变牛牛牌型小于未百变的牛牛。</div>
                                </div>
                                <div class="cContent" id="con_3" style="display: none;">
                                    <!--游戏计分-->
                                    <div class="cCtitle">游戏计分</div>
                                    <div class="cCPading">
                                        <li>牛牛——2倍与压注欢乐豆 </li>
                                        <li>牛9以下只翻1倍 </li>
                                    </div>
                                    <br />
                                    <div class="cCtitle">逃跑扣分</div>
                                    <div class="">逃跑扣除所有已押注欢乐豆（2倍），由本局胜利者获得。 </div>
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
                                                            <td align="center" width="30%">20W欢乐豆</td>
                                                        </tr>
                                                        <tr>
                                                            <td align="center" style="border-top:1px solid; border-right:1px solid;" width="30%">中级房间</td>
                                                            <td align="center" style="border-top:1px solid;" width="30%">100W欢乐豆</td>
                                                        </tr>
                                                        <tr>
                                                            <td align="center" style="border-top:1px solid; border-right:1px solid;" width="30%">高级房间</td>
                                                            <td align="center" style="border-top:1px solid;" width="30%">200W欢乐豆</td>
                                                        </tr>
                                                    </table></td>
                                                <td align="center" width="40%" style="border:1px solid; border-left:none;">5000万欢乐豆</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="cContent" id="con_7" style="display:none;">
                                    <!--彩蛋牌型-->
                                    <div class="cCtitle">彩蛋牌型</div>
                                    <div class="">
                                       <img src="/images/game_main.png"  style="width:90%;margin:75px 5%;">
                                    </div>
                                </div>
                    

                            </div>
                        </div>
                    </div>
                </div>
            </div>

</body>
</html>