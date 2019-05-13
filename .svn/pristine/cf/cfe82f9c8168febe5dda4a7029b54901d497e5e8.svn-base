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

                            <div class="cBody">
                                <img src="images/4030/title.jpg" />


                                <div class="cLabel">
                                    <img src="/images/1_2.gif" id="lab_1" onclick="changeTab(1)"  />
                                    <img src="/images/2_1.gif" id="lab_2" onclick="changeTab(2)" />
                                    <img src="/images/3_1.gif" id="lab_3" onclick="changeTab(3)" />

                                </div>

                                <div class="cContent" id="con_1">
                                    <!--游戏简介-->
                                    <div class="cCbody">
                                        “象棋翻翻棋”游戏规则类似于军棋的明棋，以半个象棋棋盘为战场，双方各执一色，谁能把对方棋子吃光或者对方投降则赢得胜利。
                                    </div>
                                    <img src="images/4030/screen.jpg" />
                                </div>
                                <div class="cContent" id="con_2" style="display:none;">
                                    <!--游戏规则-->

                                    <div class="cCtitle">棋子大小</div>
                                    <div class="cCbody">
                                        <li>将>士>象>车>马>炮>兵； </li>
                                        <li>兵可以吃将，将不能吃兵</li>
                                    </div>
                                    <div class="cCtitle">大小比较</div>
                                    <div class="cCbody">
                                        <li>点击盖着的棋子可以翻开该棋；</li>
                                        <li>除炮外其余棋子只能走与之相邻的棋格；</li>
                                        <li>棋子之间按大小可以吃棋，吃棋双方棋子大小一样时不能吃掉，只能对掉；</li>
                                        <li>炮是一个特殊棋子，可以吃掉任意一个与其自身隔着一个棋子的棋；但不能吃相邻的棋子； </li>
                                        <li>同一个棋子不得追逐对方棋子十步以上，否则无法落子； </li>
                                    </div>
                                    <div class="cCtitle">胜负说明</div>
                                    <div class="cCbody">
                                        <li>一方棋子被吃光，则对方赢得胜利； </li>
                                        <li>超时超过三次，则对方赢得胜利；</li>
                                        <li>一方提出投降，则对方赢得胜利；</li>
                                        <li>双方剩余棋子都为零时，则本局和棋；</li>
                                        <li>一方提出和棋，对方同意，则本局和棋；</li>
                                        <li>对局双方下子步数超过100步，系统判为和棋；</li>
                                        <li>双方剩余棋子少于两个，并且下子步数超过40步，系统判为和棋。</li>
                                    </div>
                                    <div class="cCtitle">特殊情况</div>
                                    <div class="cCbody">
                                        如果有人超，读秒完成时系统把做动作的权利交给另一方。
                                    </div>
                                    <div class="cCtitle">棋子颜色分配</div>
                                    <div class="cCbody">
                                        每局双方轮流获得优先翻棋权，第一个翻开的棋子的颜色即为己方颜色，对家执另一色（第一局游戏随机分配优先翻牌权）

                                    </div>
                                </div>

                                <div class="cContent" id="con_3" style="display:none;">
                                    <!--游戏计分-->
                                    <div class="cCtitle">游戏计分</div>
                                    <div class="cCbody">
                                        <li>1、赢棋一方将获得与自己剩下棋子个数一样多的分数，输方扣掉相应分数（超时获胜分数另计）。如胜利方剩下3个棋子，则胜方赢3分，输方扣3分；</li>
                                        <li>2、和局，则双方各获1分；</li>
                                        <li>3、附加分：第一步如果吃掉的是对方的将或者帅，并且取得本局胜利可以另外获得5分的附加分。</li>


                                    </div>
                                    <div class="cCtitle">逃跑扣分</div>
                                    <div class="cCbody">
                                        逃跑者扣除桌面上已押的全部欢乐豆给其它玩家。
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

</body>
</html>