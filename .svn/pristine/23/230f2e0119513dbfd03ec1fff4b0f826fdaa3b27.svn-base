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
                                <img src="images/4020/title.jpg" />


                                <div class="cLabel">
                                    <img src="/images/1_2.gif" id="lab_1" onclick="changeTab(1)"  />
                                    <img src="/images/2_1.gif" id="lab_2" onclick="changeTab(2)" />
                                    <img src="/images/3_1.gif" id="lab_3" onclick="changeTab(3)" />
                                    <img src="/images/4_1.gif" id="lab_4" onclick="changeTab(4)" />
                                </div>

                                <div class="cContent" id="con_1">
                                    <!--游戏简介-->
                                    <div class="cCbody">“军棋翻翻棋”是我国深受欢迎的棋类游戏之一；</div>
                                    <img src="images/4020/screen.jpg" />
                                </div>
                                <div class="cContent" id="con_2" style="display:none;">
                                    <!--游戏规则-->
                                    <div class="cCtitle">
                                        棋盘和布局规则：

                                    </div>
                                    <div class="cCbody">
                                        <li>双方的棋子以背面朝上，随机摆放在棋盘上； </li>
                                        <li>由两个玩家轮流选棋（第一局有系统选择一位玩家首先开始选棋，第二局换由另一家首先开始选，之后反复轮流。），当一个玩家选到两个同种颜色且大小相同的棋子，则确定该玩家持此种颜色的棋子，游戏正式开始；
                                        </li>
                                        <li>轮到走时，玩家可以选择走一步已经翻开的棋或翻任意一枚还未翻开的棋子； </li>
                                        <li>军旗和地雷无法移动； </li>
                                        <li>行走路线包括公路线和铁路线，显示较细的是公路线，任何棋子在公路线上只能走一步，显示为粗黑的为铁路线，铁路上没有障碍时，工兵和其它棋子在铁路线上一样，只能直走或经过弧形线，不能转直角弯；
                                        </li>
                                        <li>棋子落点包括结点、行营、两个司令部，行营是个安全岛，进入以后，敌方棋子不能吃行营中的棋子； </li>
                                        <li>每步棋的限时：轮到某玩家走棋时，如果在此限时之内不走棋，则判为贻误战机，取消本轮，行棋资格，自动轮到下一家走。默认限时为30秒，连续超时3次都会被判输。 </li>
                                    </div>
                                    <div class="cCtitle">
                                        吃子规则：
                                    </div>
                                    <div class="cCbody">
                                        <li>地雷小于工兵，大于所有其他棋子； </li>
                                        <li>司令 &gt; 军长 &gt; 师长 &gt; 旅长 &gt; 团长 &gt; 营长 &gt; 连长 &gt; 排长 &gt; 工兵； </li>
                                        <li>炸弹与任何棋子相遇时同归于尽，双方都消失； </li>
                                        <li>相同大小的棋子相遇，则同归于尽；</li>
                                    </div>
                                    <div class="cCtitle">游戏规则</div>
                                    <div>

                                        <li>游戏开始第一局随机开棋； </li>

                                        <li>工兵不能飞；</li>

                                        <li>总步数达到30步后才可以认输、求和； </li>

                                        <li>如果总步数达到400步，自动算做和棋；</li>

                                        <li>如果双方连续走了100步没有发生相互碰棋子，算和棋；</li>

                                        <li>同一个棋子不能追吃对方的某一个棋子超过3步。</li>

                                    </div>
                                </div>
                                <div class="cContent" id="con_3" style="display:none;">
                                    <!--游戏计分-->
                                    <div class="cCbody">
                                        <li>断线逃跑者扣 基础分*4</li>
                                        <li>如果逃跑方已经被吃的棋子数大于或者等于20个，那么对方得 基础分*2； </li>
                                        <li>如果逃跑方已经被吃的棋子数大于或者等于15个同时小于20个，那么对方得基础分*1；</li>
                                        <li>如果逃跑方已经被吃的棋子数小于15个，那么对方不得分；</li>
                                        <li>如果军棋被扛（棋子未全部吃光）、无棋可走、都会被判负，那么对方得基础分*1； </li>
                                        <li>如果军棋被扛（棋子全部吃光）的或超时3次，那么对方得基础分*2； </li>
                                        <li>如果对方提出和棋，在对方同意之后，则本局为和棋；如果超过了三次，那么就没有和棋的权力了，必须下完全局；</li>
                                        <li>如果对方提出认输，那么在认输一盘后，则赢基础分*1；认输两盘，则赢基础分*2；如果超过三次，就没有认输的权力，必须下完全局。</li>
                                    </div>
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