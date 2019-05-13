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
                                <img src="images/3120/title.jpg" />


                                <div class="cLabel">
                                    <img src="/images/1_2.gif" id="lab_1" onclick="changeTab(1)"  />
                                    <img src="/images/2_1.gif" id="lab_2" onclick="changeTab(2)" />
                                    <img src="/images/3_1.gif" id="lab_3" onclick="changeTab(3)" />

                                </div>

                                <div class="cContent" id="con_1">
                                    <!--游戏简介-->
                                    <div class="cCbody">“打地鼠”是一个趣味性的休闲游戏，由2-6个玩家合作使用技能消灭地洞里的地鼠，玩家杀死相应颜色的地鼠，最后结算得到的总分。</div>
                                    <img src="images/3120/screen.jpg" />
                                </div>
                                <div class="cContent" id="con_2" style="display:none;">
                                    <!--游戏规则-->

                                    <div class="cCtitle">游戏玩法</div>
                                    <div class="cCbody">
                                        <li>进入游戏画面后，按“start”开始游戏。 </li>
                                        <li>用鼠标控制木槌，点击鼠标，木槌则下落砸地鼠。 </li>
                                        <li>用小键盘１～９个数字键控制木槌，对应地图上９个洞，按相应按键木槌则下落砸地鼠。 </li>
                                        <li>游戏分为３个关卡，难度递增。</li>
                                        <li>游戏结束后按照游戏分数高低计算积分。</li>
                                    </div>
                                </div>
                                <div class="cContent" id="con_3" style="display:none;">
                                    <!--游戏计分-->
                                    <div class="cCtitle">游戏计分</div>
                                    <div class="cCbody">
                                        <li>比赛开始，2个以上玩家同时打地鼠，根据玩家过关不同，获取不同分数。第一关得2分，第二关得6分，第三关得20分。打地鼠击中5次可过第一关；击中20次可过第二关；击中50次可过第三关。</li>
                                    </div>
                                    <div class="cCtitle">逃跑计分</div>
                                    <div class="cCbody">
                                        <li>有玩家逃跑，则扣除15积分。</li>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

</body>
</html>