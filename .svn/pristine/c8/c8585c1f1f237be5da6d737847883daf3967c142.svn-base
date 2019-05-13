<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <title>游戏介绍</title>
    <script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="js/fun.js"></script>
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
        {/literal}
    </script>
</head>
<body>

            <div class="detailsRight">
                <div class="commonProblem new">
                    <div class="main">
                        <div class="mainframe">

                            <div class="cBody">
                                <img src="images/2010/title.jpg" />


                                <div class="cLabel">
                                    <img src="/images/1_2.gif" id="lab_1" onclick="changeTab(1)"  />
                                    <img src="/images/2_1.gif" id="lab_2" onclick="changeTab(2)" />
                                    <img src="/images/3_1.gif" id="lab_3" onclick="changeTab(3)" />

                                </div>

                                <div class="cContent" id="con_1">
                                    <!--游戏简介-->
                                    <div class="cCbody">“温州麻将”牌型简单，讲究软牌、硬牌、双翻等规则，通俗简单，轻松而又休闲，只保留几种牌型，以快速和牌为其最大特色。游戏开始，庄家分得17张牌，闲家每人16张，共一百三十六张：筒、索、万、东、南、西、北风、中、发、白。</div>
                                    <img src="images/2010/screen.jpg" />
                                </div>
                                <div class="cContent" id="con_2" style="display:none;">
                                    <!--游戏规则-->
                                    <div class="cCtitle">基本说明</div>
                                    <div class="cCbody">

                                        <div>每种牌4张，共136张牌。包括：</div>

                                        <li>条：一条、二条、三条、四条、五条、六条、七条、八条、九条</li>
                                        <li>万：一万、二万、三万、四万、五万、六万、七万、八万、九万</li>
                                        <li>筒：一筒、二筒、三筒、四筒、五筒、六筒、七筒、八筒、九筒</li>
                                        <li>风：东风、南风、西风、北风</li>

                                        <li>字：红中、发财、白板</li>

                                        <div>四人参与游戏，分别为东、南、西、北，首轮东家为庄家，其余均为闲家。游戏开始，庄家分得17张牌，闲家每人16张。</div>

                                    </div>
                                    <div class="cCtitle">
                                        出牌规则</div>
                                    <div class="cCbody">
                                        庄家出一张最无用的牌（必须先出完手中单张的风字牌）。此时，其他三家都有权力要这张牌。庄家的下家，有权力“吃”、“碰”、“杠”或“和”那张牌，其他两家则只可“碰”、“杠”或“和”那张牌，“碰”比“吃”优先。
                                    </div>
                                    <div class="cCbody">
                                        出牌时，必须先出风牌和字牌，假设前面有玩家出了某张风牌或其他字牌，那么后面的玩家如果有此单张的风牌或字牌必须出这张牌；如果后面的玩家没有此单张的风牌或字牌，可出其他风牌或字牌，如没有风牌或字牌则出其他类型牌。</div>
                                    <div class="cCbody">
                                        <li>财神：可用来在和牌时替代任何牌，但是财神不能在游戏过程中出掉或用于吃牌。财神牌型在桌上亮出（翻牌就是财神）。白板代替财神牌。</li>

                                        <li>吃牌：吃牌时分吃头、吃中、吃尾。比如：上家出的牌是四条，你手中有五条和六条那么就是吃头。</li>

                                        <li>碰牌：如玩家A手上有一对“四条”，玩家B打出一张“四条”。此时，玩家A可碰。“碰”比“吃”优先。</li>

                                        <li>流局：结尾剩16张牌为特殊牌或者打到财神，这部分牌只能供“杠”使用，不能作其他用途。当抓完前面所有牌时，系统判定为“流局”。开始新一局游戏。</li>
                                    </div>
                                    <div class="cCtitle">
                                        胡牌规则</div>
                                    <div class="cCbody">
                                        <li>（每把牌一定要有个对子当牛（将），才可以胡牌）<br /></li>
                                        <li>（1）AA（牛），ABC，ABC，ABC，ABC，ABC。</li>

                                        <li>（2）AA（牛），ABC，ABC，ABC，ABC，AAA。</li>

                                        <li>（3）AA（牛），ABC，ABC，ABC，AAA，AAA。</li>

                                        <li>（4）AA（牛），ABC，ABC，AAA，AAA，AAA。</li>

                                        <li>（5）AA（牛），ABC，AAA，AAA，AAA，AAA。</li>

                                        <li>（6）AA（牛），AAA，AAA，AAA，AAA，AAA。</li>

                                    </div>

                                    <div class="cCtitle">
                                        五、特殊牌型</div>

                                    <div class="cCbody">
                                        <li>软牌：和牌中有财神代替其他牌。</li>
                                        <li>硬牌：自摸和牌，和牌没有财神或财神归位（财神牌代表自身）；手中有3张财神牌不须符合和牌牌型就和牌。</li>

                                        <li>双翻</li>

                                        <li>（1）和牌型为：11，11，11，11，11，11，11，11（硬八对，没财神）。</li>

                                        <li>（2）3个财神同时符合其他和牌牌型（三财神）。</li>

                                        <li>（3）天胡（庄家抓过第一张牌就和了）。</li>

                                        <li>（4）地胡（游戏开始的第一轮，上家打出一张牌就和了）。</li>

                                        <li>（5）游戏开始后通过“吃”和“碰”只剩下一张牌就可以和牌的情况。</li>

                                        <li>（6）AA，AAA，AAA，AAA，AAA，AAA。（碰碰和）。</li>

                                    </div>
                                    <div class="cCbody">
                                        在游戏中，最先和牌的为胜利者。一局只能有一位和牌者。如有一人以上同时表示和牌时，按逆时针方向，顺序在前者被定为“和牌者”。如出现财神单钓不胡，则需跑马一圈自摸胡牌。</div>

                                </div>
                                <div class="cContent" id="con_3" style="display:none;">
                                    <!--游戏计分-->
                                    <div class="cCtitle">
                                        计分规则</div>
                                    <div class="cCbody">
                                        采用温州通用进阶分算法：如[10.20.30.40],即第一轮庄闲家基础分为10.，当庄家继续坐庄，第二轮庄闲家基础分为20.以此类推。闲家间基础分为10（所有玩家最低级别的首轮基础分）
                                    </div>
                                    <div class="cCbody">
                                        买顶底为当前的庄闲家基础分，即庄闲家底分。</div>
                                    <div class="cCbody">
                                        当四个玩家都同意买财神钱后，财神钱算法生效，财神钱为当前四玩家中最低级别的第一轮基础分。</div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

</body>
</html>