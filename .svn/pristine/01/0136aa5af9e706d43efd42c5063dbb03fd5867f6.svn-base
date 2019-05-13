

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
                            <div class="cBody"> <!-- <img src="images/1108/title.jpg" /> -->
                                <div class="cLabel"> <img src="/images/1_2.gif" id="lab_1" onclick="changeTab(1)" /> <img src="/images/2_1.gif" id="lab_2" onclick="changeTab(2)" /> <img src="/images/3_1.gif" id="lab_3" onclick="changeTab(3)" /> <img src="/images/4_1.gif" id="lab_4" onclick="changeTab(4)" /> </div>
                                <div class="cContent" id="con_1">
                                    <img src="images/1108/screen.jpg" style="width:545px" />
                                </div>
                                <div class="cContent" id="con_2" style="display: none;">
                                    <!--游戏规则-->
                                     <div class="cCtitle"> 基本规则 </div>
                                    <div class="cCbody"> 玩家人数：2人以上  <br>
                                    组队：一人为庄家，其它为闲家<br>
                                    牌数：32张牌，不包括大、小王，由2张红Q、2张红J、4张10、2张红9、4张8、4张7、4张6、2张红5、4张4、2张2、黑桃A、黑桃3组成
                                    </div>

                                    <div class="cCtitle"> 出牌顺序</div>
                                    <div class="cCbody">
                                    由庄家开牌，每人发两张，每家闲家和庄家比大小
                                    </div>

                                    <div class="cCtitle"> 牌的大小 </div>
                                    <div class="cCbody">
                                    对子&nbsp;>&nbsp;特殊牌型&nbsp;>&nbsp;点数<br>
                                    欢乐至尊的大小
                                        <li>第一&nbsp;&nbsp;至尊宝&nbsp;黑桃A+黑桃3</li>
                                        <li>第二&nbsp;&nbsp;双天&nbsp;由两张天牌组成，红桃Q+方块Q</li>
                                        <li>第三&nbsp;&nbsp;双地&nbsp;由两地牌组成，红桃2+方块2</li>
                                        <li>第四&nbsp;&nbsp;双人&nbsp;由两人牌组成，红桃8+方块8</li>
                                        <li>第五&nbsp;&nbsp;双和&nbsp;由两和牌组成，红桃4+方块4</li>
                                        <li>第六&nbsp;&nbsp;双梅&nbsp;由两梅花牌组成，黑桃10+梅花10 <br>
                                        双幺五&nbsp;&nbsp;由两张幺五牌组成，黑桃6+梅花6 <br>
                                        双板凳&nbsp;&nbsp;由两板凳牌组成，黑桃4+梅花4
                                        </li>
                                        <li>第七&nbsp;双斧头&nbsp;由两斧头牌组成，红桃J+方块J <br>
                                        双红头&nbsp;由两张红头牌组成，红桃10+方块10<br>
                                        双长&nbsp;由两张长牌组成，红桃6+方块6 <br>
                                        双铜锤&nbsp;由两张铜锤牌组成，红桃7+方块7</li>
                                        <li>第八&nbsp;杂九&nbsp;由两杂九牌组成，红桃9+方块9 <br>
                                        杂八&nbsp;由两杂八牌组成，黑桃8+梅花8 <br>
                                        杂七&nbsp;由两杂七牌组成，黑桃7+梅花7 <br>
                                        杂五&nbsp;由两杂五牌组成，红桃5+方块5 <br>
                                        </li>
                                        <li>第九&nbsp;天九王&nbsp;天牌配任何一种9点牌，即天牌配杂九牌。<br> 特点是点数为12+9=21，算个位数是1 任意Q+任意9</li>
                                        <li>第十&nbsp;天&nbsp;天牌配任何一种8点牌，即天牌配人牌或杂八牌。<br>特点是点数为12+8=20，算个位数是0 任意Q+任意8</li>
                                        <li>第十一&nbsp;地&nbsp;地牌配任何一种8点牌，即地牌配人牌或杂八牌。<br>特点是点数为2+8=20，算个位数是0 任意2+任意8</li>
                                    点牌数：任何不属于以上组合牌型的，取2张牌之和的个位数为最后点数，大小依次排列：9点&nbsp;>&nbsp;8点&nbsp;>&nbsp;7点&nbsp;>&nbsp;6点&nbsp;>&nbsp;5点&nbsp;>&nbsp;4点&nbsp;>&nbsp;3点&nbsp;>&nbsp;2点&nbsp;>&nbsp;1点&nbsp;>&nbsp;0点<br><br>
                                    在点数牌中，黑桃A为6点；同样点数，取组合中最大的一张牌比较大小，大小按以上对子出现的顺序，黑桃A和3按最小的计算；<br><br>
                                    
                                    <div class="cCtitle"> 下注规则 </div>
                                    <div class="cCbody"> 普通玩家个人一局最大押注限额为2亿豆。每局仅能接受等同于庄家当前坐庄金额的下注<br><br>
                                    玩家在游戏中可对顺门、对门、倒门、桥以及两个角6个下注选项分别下注，开牌后所押筹码的大于庄家即可赢得押注额相当的奖励。<br><br>
                                    桥：对应顺门、倒门，只有这两门同时赢过庄家，才算押注胜利。<br><br>
                                    角（左）：对应顺门、对门，只有这两门同时赢过庄家，才算押注胜利。<br><br>
                                    角（右）：对应对门、倒门，只有这两门同时赢过庄家，才算押注胜利。<br><br>

                                    </div>


                                    <div class="cCtitle"> 庄家规则 </div>
                                    <div class="cCbody"> 1.上庄 <br>
                                    上庄条件为玩家携带至少2千万豆，方能申请上庄<br><br>
                                    2.下庄<br>
                                    a.庄家在每局结束后可以选择离开当前牌桌，从而造成下庄事实，此时系统在上庄申请列表中按申请时间选择最早申请的玩家做下一轮庄家。<br>
                                    b.庄家在游戏中连庄达到一定局数，自动下庄。<br>
                                    c.庄家在游戏中输光游戏豆，被迫下庄。<br>
                                    </div>
    


                                    </div>  
                                    
                                </div>
                                <div class="cContent" id="con_3" style="display: none;">
                                </div>
                                <div class="cContent" id="con_4" style="display: none;">
                                    <!--游戏限额-->
                                    <div class="cCtitle">游戏限额</div>
                                    <div class="cCPading">
                                    <div class="cCbody">
                                       欢乐至尊规定<br>
                                    房间基数：<br>
                                    1.普通房：10万豆以上入场 <br>
                                    2.100万房：100万豆以上入场  <br>
                                    </div>
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
</html>