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
                            <div class="cBody">
                                 <img src="images/3160/title.jpg" />
                                <div class="cLabel"> 
									<img src="/images/1_2.gif" id="lab_1" onclick="changeTab(1)" />
									<img src="/images/2_1.gif" id="lab_2" onclick="changeTab(2)" />
									<img src="/images/3_1.gif" id="lab_3" onclick="changeTab(3)" />
									<img src="/images/4_1.gif" id="lab_4" onclick="changeTab(4)" />
								</div>
                               <div class="cContent" id="con_1">
                    <!--游戏简介-->
                    <div class="cCbody">“幸运骰子”游戏最初起源于酒吧的吹牛游戏，后流行于浙南一带，后来由网民改编成骰子牛牛、骰子港五等多种在线游戏形式。</div>
                    <img src="images/3160/screen.jpg" />
                </div>
                <div class="cContent" id="con_2" style="display:none;">
                    <!--游戏规则-->
                    
                    <div class="cCtitle">游戏玩法</div>
                    <div class="cCbody">
                        <li>游戏至少需要一名庄家与一名玩家才能继续。</li>
                        <li>进入游戏画面后，在玩家上庄后，其它玩家可在游戏下注区域进行压注。 </li>
                        <li>玩家下注最大额会根据每个房间的配置不同而不同。 </li>
                        
                        <li>游戏使用5副骰子，每副骰子包含5个骰子，首先由闲家（东南西北四方）掷骰子，最后由庄家掷骰子。</li>
                        <li>骰子点数大小比较规则：散点 < 一对 < 两对 < 三条 < 顺子 < 葫芦 < 炸弹 < 五相 </li>
                        <li>庄家和闲家的所有点数均一致时，庄家胜</li>

                        
                        <li style="color:Red;">骰子规则：2-6点表示各自的点数。1可以代替任何数字,1在顺子12345中不变</li>
                        <li style="color:Red;">同样牌型、同样大小的情况下(1个1代替和几个1代替是一样大),用1数字代替的牌型小于没有1代替的牌型</li>
                        <li style="color:Red;">同样的百变牌型里,有1代替的除去1之后再按骰子点数大小依次比大小</li>
                        
                        <li style="color:Blue;" >上庄条件：各闲家有申请上庄的机会，如果所持欢乐豆符合做庄条件则立即申请成功，等待坐庄的闲家信息将出现在“等待上庄”列表中。多名闲家申请，先申请者先成功。 </li>
                        <li style="color:Blue;">下庄条件：如果有玩家等待上庄，庄家基本庄为15庄，当庄家欢乐豆大于上庄列表中所有玩家，则最大庄为30庄。 </li>
                        <li style="color:Blue;">下庄条件：庄家欢乐豆不足上庄欢乐豆则强制下庄。 </li>
                    </div>

                    <div class="cCtitle">游戏赔率</div>
                    <div class="cCbody">
                        <li>散点 * 1</li>
                        <li>一对 * 2</li>
                        <li>两对 * 3</li>
                        <li>三条 * 4</li>
                        <li>顺子 * 5</li>
                        <li>葫芦 * 6</li>
                        <li>炸弹 * 7</li>
                        <li>五相 * 8</li>
                    </div>
                </div>
                <div class="cContent" id="con_3" style="display:none;">
                    <!--游戏计分-->

                    <div class="cCtitle">游戏计分</div>
                    <div class="cCbody">
                        玩家下注所获欢乐豆以所押区域赔率进行结算。
                    </div>
                    <div class="cCtitle">断线计分</div>
                    <div class="cCbody">
                        若有玩家或庄家断线，则游戏继续按照正常游戏行，也按照正常结束进行结算。
                    </div>
                    <div class="cCtitle">逃跑计分</div>
                    <div class="cCbody">
                        <li>玩家：逃跑后所押欢乐豆直接输给庄家。</li>
                        <li>庄家：按比例对所有押注玩家进行分配。</li>
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