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
        {/literal}
    </script>
</head>
<body>

            <div class="detailsRight">
                <div class="commonProblem new">
                    <div class="main">
                        <div class="mainframe">
                            <div class="cBody"> <img src="images/1210/title.jpg" />
                                <div class="cLabel"> <img src="/images/1_2.gif" id="lab_1" onclick="changeTab(1)" /> <img src="/images/2_1.gif" id="lab_2" onclick="changeTab(2)" /> <img src="/images/3_1.gif" id="lab_3" onclick="changeTab(3)" /> <img src="/images/4_1.gif" id="lab_4" onclick="changeTab(4)" /> </div>
                                <div class="cContent" id="con_1">
                                    <!--游戏简介-->
                                    <div class="cCbody">“诈金花”又叫三张牌是在全国广泛流传的一种民间多人纸牌游戏，具有独特的比牌规则。玩家以手中的三张牌比输赢，游戏过程中需要考验玩家的胆略和智慧，诈金花是被公认的最受欢迎的纸牌游戏之一。</div>
                                    <img src="images/1210/screen.jpg" /> </div>
                                <div class="cContent" id="con_2" style="display: none;">
                                    <!--游戏规则-->
                                    <div class="cCtitle"> 玩法介绍</div>
                                    <div class="cCbody">游戏使用一副除去大小王的扑克牌，共4个花色52张牌,1、豹子（AAA最大，222最小）. 2、同花顺（AKQ最大，A23最小）. 3、同花（AKJ最大，352最小）. 4、顺子（AKQ最大，A23最小）. . 5、对子（AAK最大，223最小）. 6、单张（AKJ最大，352最小）.玩“诈金花”可能牌小诈走牌大，是实力、勇气和智谋的较量，是冒险家的游戏。</div>
                                    <div class="cCtitle"> 牌型介绍</div>
                                    <div class="cCbody">牌型大小</div>
                                    <div class="cCbody">
                                        <li>豹子>同花顺>金花>顺子>对子>散牌；特殊>豹子。特殊<散牌。</li>
                                        <li>豹子：三张点相同的牌，AAA、222。</li>
                                        <li>同花顺：花色相同的顺子，黑桃456、红桃789。</li>
                                        <li>金花：花色相同，非顺子，黑桃368，方片945。</li>
                                        <li>顺子：花色不同的顺子，黑桃5红桃6方片7。</li>
                                        <li>对子：两张点相同的牌，223，334。</li>
                                        <li>散牌：三张牌不组成任何类型的牌。</li>
                                    </div>
                                    <div class="cCbody">获胜条件</div>
                                    <div class="cCbody">
                                        游戏为五个人玩，一方为庄家，另四方为闲家，游戏开始后按照顺序向桌面掷欢乐豆，每次掷欢乐豆都可以选择比牌或者取消，直到最后一个人获胜。
                                    </div>
                                </div>
                                <div class="cContent" id="con_3" style="display: none;">
                                    <!--游戏计分-->
                                    <div class="cCtitle">游戏计分</div>
                                    <div class="cCbody">
                                        <li>根据压注欢乐豆 </li>
                                    </div>
                                    <br />
                                    <div class="cCtitle">逃跑扣分</div>
                                    <div class="">1、系统发牌前逃跑 </div>
                                    <div class="cCbody">闲家：不记分</div>
                                    <div class="cCbody">庄家：不记分</div>
                                    <div class="">2、系统发牌后逃跑 </div>
                                    <div class="cCbody">庄家：押注后逃跑扣除所押欢乐豆给最后赢家。</div>
                                    <div class="cCbody">闲家：押注后逃跑扣除所押欢乐豆给最后赢家。</div>
                                    <br />
                                    <div class="cCbody">庄家与闲家之间的记分方式是固定。庄家赢的数量与闲家输的数量是成正比。</div>
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
                                                            <td align="center" width="30%">50W欢乐豆</td>
                                                        </tr>
                                                        <tr>
                                                            <td align="center" style="border-top:1px solid; border-right:1px solid;" width="30%">中级房间</td>
                                                            <td align="center" style="border-top:1px solid;" width="30%">200W欢乐豆</td>
                                                        </tr>
                                                        <tr>
                                                            <td align="center" style="border-top:1px solid; border-right:1px solid;" width="30%">高级房间</td>
                                                            <td align="center" style="border-top:1px solid;" width="30%">1000W欢乐豆</td>
                                                        </tr>
                                                    </table></td>
                                                <td align="center" width="40%" style="border:1px solid; border-left:none;">5000万欢乐豆</td>
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
</html>