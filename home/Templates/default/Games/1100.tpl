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
                            <div class="cBody"> <img src="images/1100/title.jpg" />
                                <div class="cLabel"> <img src="/images/1_2.gif" id="lab_1" onclick="changeTab(1)" /> <img src="/images/2_1.gif" id="lab_2" onclick="changeTab(2)" /> <img src="/images/3_1.gif" id="lab_3" onclick="changeTab(3)" /> <img src="/images/4_1.gif" id="lab_4" onclick="changeTab(4)" /> </div>
                                <div class="cContent" id="con_1">
                                    <!--游戏简介-->
                                    <div class="cCbody">“欢乐龙虎”是非常受欢迎的扑克游戏,中世纪它源于意大利,因为人面牌和 10 是许多游戏中的大牌点, 但在30秒中都算作 0 点，故有 " 零 " 的含义。玩家可以下注，猜哪一方（ “ 闲 ( 左方 )”或 “ 庄 ( 右方 )” ）的总点数最接近 9 点。压注中间是“和” 。双方均会收到至少两张牌，但总数不会超过三张。</div>
                                    <img src="images/1100/screen.jpg" /> </div>
                                <div class="cContent" id="con_2" style="display: none;">
                                    <!--游戏规则-->
                                    <div class="cCtitle"> 玩法介绍</div>
                                   <div class="cCbody">
                                        <li>游戏使用6副牌，每副52张牌（不包括大小王） </li>
                                        <li>游戏至少需要一名庄家与一名玩家才能继续。 </li>
                                        <li>
                                        进入游戏画面后，在玩家上庄后，其它玩家可在游戏下注区域进行压注。 </li>
                                        <li>
                                        玩家下注最大额会根据每个房间的配置不同而不同。 </li>
                                        <li>
                                        上庄条件：各闲家有申请上庄的机会，如果所持欢乐豆符合做庄条件则立即申请成功，等待坐庄的闲家信息将出现在“等待上庄”列表中。多名闲家申请，先申请者先成功。 </li>
                                        <li>下庄条件：如果有玩家等待上庄，庄家基本庄为15庄，当庄家欢乐豆大于上庄列表中所有玩家，则最大庄为30庄。 </li>
                                        <li>下庄条件：庄家欢乐豆不足上庄欢乐豆则强制下庄。 </li>
                                        </div>
                                         <div class="cCtitle"> 牌点规则</div>
                                         <div class="cCbody">10、J、Q、K为0点，A为1点，其他牌照牌面点数计算，而牌面9点者为最大点数纸牌 </div>
                                         <div class="cCtitle"> 发牌规则</div>
                                         <div class="cCbody">
                                            游戏使用8副牌，每副52张牌<不含大小王>，每次发放四张牌，其中第1张和第3张发放给闲家，第2张和第4张发放给庄家。所发牌 均摊开显示，每方最多可拿3张牌，第3张牌拿牌规则如下：
                                            <table width="100%" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <th width="30%" style="border:1px solid; border-bottom:none;" bgcolor="#CCCCCC">闲规则：</th>
                                                    <th width="30%" style="border-top:1px solid; border-right:1px solid;" bgcolor="#CCCCCC">闲前两张牌的总牌点</th>
                                                    <th width="40%" style="border-top:1px solid; border-right:1px solid;" bgcolor="#CCCCCC">闲行动</th>
                                                </tr>
                                                <tr>
                                                    <td colspan="3">
                                                        <table width="100%" cellspacing="0" cellpadding="0" style="border:1px solid;">
                                                            <tr>
                                                                <td align="center"  style="border-top:1px solid; border-right:1px solid;" width="30%"></td>
                                                                <td align="center" style="border-top:1px solid; border-right:1px solid;" width="30%">0, 1, 2, 3, 4, 5</td>
                                                                <td align="center"style="border-top:1px solid; border-right:1px solid;"  width="40%">拿牌 </td>
                                                            </tr>
                                                             <tr>
                                                                <td align="center" style="border-top:1px solid; border-right:1px solid;" width="30%"></td>
                                                                <td align="center" style="border-top:1px solid; border-right:1px solid;"width="30%">6, 7</td>
                                                                <td align="center"  style="border-top:1px solid; border-right:1px solid;" width="40%">停牌  </td>
                                                            </tr>
                                                            <tr>
                                                                <td align="center" style="border-top:1px solid; border-right:1px solid;" width="30%"></td>
                                                                <td align="center" style="border-top:1px solid; border-right:1px solid;" width="30%">8, 9</td>
                                                                <td align="center" style="border-top:1px solid; border-right:1px solid;" width="40%">停牌（天王） </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                             </table>
                                             当闲家在6或7点停牌时（不再拿第三张牌），庄家的玩法就很简单了。如庄家的牌点是 0、1、2、3、4或5，则必须拿牌；如牌点 为6或7则停牌。
                                            <table width="100%" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <th width="30%" style="border:1px solid; border-bottom:none;" bgcolor="#CCCCCC">庄规则：</th>
                                                    <th width="30%" style="border-top:1px solid; border-right:1px solid;" bgcolor="#CCCCCC">庄前两张牌的总牌点</th>
                                                    <th width="40%" style="border-top:1px solid; border-right:1px solid;" bgcolor="#CCCCCC">庄行动</th>
                                                </tr>
                                                <tr>
                                                    <td colspan="3">
                                                        <table width="100%" cellspacing="0" cellpadding="0" style="border:1px solid;">
                                                            <tr>
                                                                <td align="center"  style="border-top:1px solid; border-right:1px solid;" width="30%"></td>
                                                                <td align="center" style="border-top:1px solid; border-right:1px solid;" width="30%">0, 1, 2, 3, 4, 5</td>
                                                                <td align="center"style="border-top:1px solid; border-right:1px solid;"  width="40%">拿牌 </td>
                                                            </tr>
                                                             <tr>
                                                                <td align="center" style="border-top:1px solid; border-right:1px solid;" width="30%"></td>
                                                                <td align="center" style="border-top:1px solid; border-right:1px solid;"width="30%">6, 7</td>
                                                                <td align="center"  style="border-top:1px solid; border-right:1px solid;" width="40%">停牌  </td>
                                                            </tr>
                                                            <tr>
                                                                <td align="center" style="border-top:1px solid; border-right:1px solid;" width="30%"></td>
                                                                <td align="center" style="border-top:1px solid; border-right:1px solid;" width="30%">8, 9</td>
                                                                <td align="center" style="border-top:1px solid; border-right:1px solid;" width="40%">停牌（天王） </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                             </table>
                                             如闲家拿了第三张牌，对庄家的游戏规定变化形式则变得较多了。庄家拿到7点停牌，但是在6点或少于6点时拿牌或停牌取决于闲 家的第三张牌面值（而不是闲家手中的牌点）。指导庄家拿牌或停牌的游戏规则如下：
                                             <img src="images/1100/30.gif">
                                          </div>
                                           <div class="cCtitle">胜负判定</div>
                                            <div class="cCbody">
                                                   <p>&nbsp;&nbsp; 庄闲：双方牌相加的点数来比较大小，总点数最接近9点的一方获胜。赔率：赔率为1：1；平的赔率为1：8，庄对，闲对的赔率为1： 11</p>
                                                  <p>&nbsp;&nbsp;龙虎：双方牌相加的点数来比较大小，前两张点数大的一方一方获胜。赔率：赔率为1：1；</p>
                                              </div>
                                            <div class="cCtitle">押注规则</div>
                                            <div class="cCbody">
                                                   <p>&nbsp;&nbsp; 坐庄玩家不参与押注，其他玩家任选押注区域（庄，闲，平或庄对，闲对），玩家下注额不能超过庄家所带银子数，下在和区域 的欢乐豆数按8倍计算，押注在庄对或闲对的欢乐豆数按11倍计算。</p>
                                              </div>


                                </div>
                                <div class="cContent" id="con_3" style="display: none;">
                                    <!--游戏计分-->
                                    <div class="cCtitle">游戏计分</div>
                                    <div class="cCbody">
                                       玩家下注所获欢乐豆以所押区域赔率进行结算。
                                    </div>
                                    <br />
                                    <div class="cCtitle">断线计分</div>
                                    <div class="cCbody">
                                       若有玩家或庄家断线，则游戏继续按照正常游戏行，也按照正常结束进行结算。 
                                    </div>
                                    <br />
                                    <div class="cCtitle">逃跑计分</div>
                                    <div class="cCbody">
                                          <li>  玩家：逃跑后所押欢乐豆直接输给庄家。 </li>
                                           <li>庄家：按比例对所有押注玩家进行分配。 </li>
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
                                                            <td align="center" width="30%">100W欢乐豆</td>
                                                        </tr>
                                                        <tr>
                                                            <td align="center" style="border-top:1px solid; border-right:1px solid;" width="30%">中级房间</td>
                                                            <td align="center" style="border-top:1px solid;" width="30%">200W欢乐豆</td>
                                                        </tr>
                                                        <tr>
                                                            <td align="center" style="border-top:1px solid; border-right:1px solid;" width="30%">高级房间</td>
                                                            <td align="center" style="border-top:1px solid;" width="30%">500W欢乐豆</td>
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