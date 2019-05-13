<?php /* Smarty version 2.6.26, created on 2019-04-18 11:20:02
         compiled from blue/index.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'au', 'blue/index.html', 29, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>运维后台</title>
<link type="text/css" rel="stylesheet" href="/css/common.css" />
<link type="text/css" rel="stylesheet" href="/css/blue.css" />
<link type="text/css" rel="stylesheet" href="/css/jquery.treeview.css" />
<link type="text/css" rel="stylesheet" href="/css/wbox.css" />
<link type="text/css" rel="stylesheet" href="/js/My97DatePicker/skin/WdatePicker.css" />
<script type="text/javascript" language="javascript" src="/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" language="javascript" src="/js/jquery.cookie.js"></script>
<script type="text/javascript" language="javascript" src="/js/jquery.treeview.js"></script>
<script type="text/javascript" language="javascript" src="/js/main.js"></script>
<script type="text/javascript" language="javascript" src="/js/common.js"></script>
<script type="text/javascript" language="javascript" src="/js/extend.js"></script>
<script type="text/javascript" language="javascript" src="/js/wbox.js"></script>
<script type="text/javascript" language="javascript" src="/js/event.js"></script>
<script type="text/javascript" language="javascript" src="/js/Calendar.js"></script>
<script type="text/javascript" language="javascript" src="/js/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript" language="javascript" src="/js/jquery.contextmenu.r2.js"></script>
<script type="text/javascript" src="/js/xheditor/xheditor.min.js"></script>

<script type="text/javascript" language="javascript">
<?php echo '
$(function(){
	          $(\'#logout\').click(function(){
    '; ?>

            setting.Url='<?php echo getSmartyActionUrl(array('d' => 'login','c' => 'login','a' => 'logout'), $this);?>
';
    <?php echo '
              ajax.Request(setting.Url,\'\');
          }); 
    '; ?>

    var  AutoLogoutCheckTime = "<?php echo $this->_tpl_vars['AutoLogoutCheckTime']; ?>
"*1000;
    <?php echo '
	 setInterval("checkLastAction()",AutoLogoutCheckTime);//1000为1秒钟
        setInterval("getReviwId()",300*1000);//1000为1秒钟
});
function checkLastAction()
{
    '; ?>

	setting.Url='<?php echo getSmartyActionUrl(array('d' => 'Login','c' => 'login','a' => 'checkLastAction'), $this);?>
';
	var Params = '';
	ajax.RequestCallBack(setting.Url,Params,login.checkLastAction);
    <?php echo '
}
var login = {
	checkLastAction:function (data){
		if(data!=0){
			'; ?>

		          setting.Url='<?php echo getSmartyActionUrl(array('d' => 'login','c' => 'login','a' => 'logout'), $this);?>
';
		     <?php echo '
		          ajax.RequestCallBack(setting.Url,\'\',function(data){
		          		location.reload() ;
		          });
		}
	}
}

function getReviwId()
    {
        '; ?>

            setting.Url='<?php echo getSmartyActionUrl(array('d' => 'Main','c' => 'Index','a' => 'getReview'), $this);?>
';
            <?php echo '
            var Params = \'\';
            ajax.RequestCallBack(setting.Url,Params,function(data){ $("#reviewid").html("("+data+")")});
        }


'; ?>

</script>
</head>

<body class="gPage f1">
<!--顶部-->
<div class="gTop f2" id="gTop">
	<div style="max-width: 1440px">
	<div class="gLogo left">
		<div  class="title"  style="margin: 10px 20px">
		</div>
	</div>
	<span class="right" style="font-size: 14px">欢迎您: <strong class="orange"><?php echo $this->_tpl_vars['UserName']; ?>
</strong> <a href="" id="logout">注销</a> </span>
</div>
	<div class="gTopRight left">
		<div class="gTopInfo"></div>
		<div class="gTab clear">
			<ul>
				<li id="Tab_desktop" class="curTab" onclick="main.Show('desktop')"><span>桌面</span></li>
			</ul>
		</div>
	</div>
</div>


<div class="gMain">
	<!--左边-->
	<div class="gFpage left">
		
		<!--运维平台-->

		<div class="gfTit f2">
			<a class="clsFd f1"></a>
			<a class="gfName" href="">运维平台</a>
		</div>
		
		<ul id="YunWei" class="filetree">
            <?php if ($this->_tpl_vars['DeptID'] != 2 && $this->_tpl_vars['DeptID'] != 1 && $this->_tpl_vars['DeptID'] != 5): ?>
			<li id="1" class="closed"><span class="folder">运营配置</span>
				<ul>
					<li><span class="file"><a href="javascript:void(0)" name="ServerGame">游戏服务器</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="ServerRoom">房间服务器</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="ServerLogin">登录服务器</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="ServerDownload">下载服务器</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="ServerHall">大厅服务器</a></span></li><!-- 
                    <li><span class="file"><a href="javascript:void(0)" name="ServerProxy">大厅代理服务器</a></span></li> -->
                    <li><span class="file"><a href="javascript:void(0)" name="ServerDc">中心服务器</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="ServerWeb">WEB站点服务器</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="ServerData">数据站点服务器</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="ServerBank">银行站点服务器</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="GameDun">游戏盾信息设置</a></span></li>
				</ul>
			</li>
            <?php endif; ?>
			<?php if ($this->_tpl_vars['DeptID'] == 3): ?>
			<!--<li class="closed" id="2"><span class="folder">MAP配置</span>-->
				<!--<ul>-->
					<!--<li><span class="file"><a href="javascript:void(0)" name="MapDB">分库配置</a></span></li>-->
				<!--</ul>-->
			<!--</li>-->
			<?php endif; ?>
            <?php if ($this->_tpl_vars['DeptID'] != 2 && $this->_tpl_vars['DeptID'] != 1 && $this->_tpl_vars['DeptID'] != 5): ?>
			<li class="closed"><span class="folder">游戏配置</span>
				<ul>					<!--
					<li><span class="file"><a href="javascript:void(0)" name="GameMatch">赛事设置</a></span></li>			 -->
					<li><span class="file"><a href="javascript:void(0)" name="GameKind">游戏种类设置</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="GameTable">桌子布局设置</a></span></li>
                    <li><span class="file"><a href="javascript:void(0)" name="GameRoom">游戏房间设置</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="GameNode">游戏节点设置</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="GameTask">游戏任务设置</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="GameSign">游戏签到设置</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="GameHallVersion">大厅版本管理</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="AndroidHallVersion">安卓版本管理</a></span></li>
                    <li><span class="file"><a href="javascript:void(0)" name="AndroidVersion">客户端更新包管理</a></span></li>
					<!--
                    <li><span class="file"><a href="javascript:void(0)" name="GameMobileVersion">手机端版本管理</a></span></li> -->
				</ul>
			</li>
            <?php endif; ?>
			<!-- <?php if ($this->_tpl_vars['DeptID'] == 2 || $this->_tpl_vars['DeptID'] == 3): ?>
            <li class="closed"><span class="folder">道具事件配置</span>
				<ul>
					<li><span class="file"><a href="javascript:void(0)" name="SpClass">分类设置</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="Sp">游戏道具设置</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="Event">事件设置</a></span></li>
				</ul>
			</li>
			<?php endif; ?>-->
			<?php if ($this->_tpl_vars['DeptID'] == 2 || $this->_tpl_vars['DeptID'] == 3): ?>
			<li class="closed"><span class="folder">系统配置</span>
				<ul>
                    <?php if ($this->_tpl_vars['DeptID'] != 2): ?>
					<li><span class="file"><a href="javascript:void(0)" name="SysGameConfig">游戏配置</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="CardChargeRate">充值比例配置</a></span></li>
                    <?php endif; ?>
						 <!--
					<li><span class="file"><a href="javascript:void(0)" name="SysVipLevel">黄钻等级配置</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="SysRoleLevel">角色等级配置</a></span></li>	 -->
					<!-- <li><span class="file"><a href="javascript:void(0)" name="SysLucky">运势配置</a></span></li>	 -->
					<li><span class="file"><a href="javascript:void(0)" name="SysConfine">注册敏感词过滤</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="SysBlack">黑名单设置</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="SysWarn">报警设置</a></span></li>
					<!-- <li><span class="file"><a href="javascript:void(0)" name="ExtraProgram">外挂设置</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="SysLoginID">玩家编号配置</a></span></li>
					<li class="closed"><span class="folder">IP/机器码限制</span>
						<ul>
							<li><span class="file"><a href="javascript:void(0)" name="SysIp">IP限制</a></span></li>
                            <li><span class="file"><a href="javascript:void(0)" name="SysIntervalIp">IP段限制</a></span></li>
							<li><span class="file"><a href="javascript:void(0)" name="SysMs">机器码限制</a></span></li>
						</ul>
					</li>	 -->

				</ul>
			</li>
            <?php endif; ?>
            <?php if ($this->_tpl_vars['DeptID'] != 1 && $this->_tpl_vars['DeptID'] != 4 && $this->_tpl_vars['DeptID'] != 5): ?>
            <li class="closed"><span class="folder">系统银行配置</span>
				<ul>
                    <?php if ($this->_tpl_vars['DeptID'] != 2): ?>
					<li><span class="file"><a href="javascript:void(0)" name="SysBank">金币配置</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="RoomLuckyEggMoney">奖金池记录查询</a></span></li>
                    <li><span class="file"><a href="javascript:void(0)" name="SysBankLogs">系统银行转账日志</a> </span> </li>
                    <li><span class="file"><a href="javascript:void(0)" name="SysBankChangeLogs">系统银行变化统计</a> </span> </li>
                    <?php endif; ?>
                    <!--<li><span class="file"><a href="javascript:void(0)" name="Fishing">捕鱼银行账号</a> </span> </li>-->
					<!-- <li><span class="file"><a href="javascript:void(0)" name="SysBankFw">龙币配置</a></span></li>	 -->
				</ul>
			</li>
            <?php endif; ?>
            <?php if ($this->_tpl_vars['DeptID'] != 1 && $this->_tpl_vars['DeptID'] != 2 && $this->_tpl_vars['DeptID'] != 3 && $this->_tpl_vars['DeptID'] == 4 && $this->_tpl_vars['DeptID'] != 5): ?>
            <li class="closed" style="display:none"><span class="folder">实卡充值</span>
                <ul>
                    <li><span class="file"><a href="javascript:void(0)" name="RealCard">实卡创建</a></span> </li>
                    <li><span class="file"><a href="javascript:void(0)" name="RealCardForm">实卡统计</a></span> </li>
                    <li><span class="file"><a href="javascript:void(0)" name="RealCardGetForm">实卡领取统计</a></span> </li>
                </ul>
            </li>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['DeptID'] == 2 || $this->_tpl_vars['DeptID'] == 3): ?>
			<!--<li class="closed"><span class="folder">广告管理</span>-->
				<!--<ul>-->
					<!--<li><span class="file"><a href="javascript:void(0)" name="AdPos">广告位管理</a></span></li>-->
					<!--<li><span class="file"><a href="javascript:void(0)" name="Ad">广告管理</a></span></li>-->
				<!--</ul>-->
			<!--</li>-->
			<?php endif; ?>
			<!--<li class="closed"><span class="folder">新闻管理</span>-->
				<!--<ul>-->
					<!--<li><span class="file"><a href="javascript:void(0)" name="NewsCategory">新闻类别管理</a></span></li>-->
					<!--<li><span class="file"><a href="javascript:void(0)" name="News">新闻管理</a></span></li>-->
				<!--</ul>-->
			<!--</li>-->
            <li class="closed"><span class="folder">运营账号管理</span>
                <ul>

	                    <?php if ($this->_tpl_vars['DeptID'] == 3 || $this->_tpl_vars['DeptID'] == 4 || $this->_tpl_vars['DeptID'] == 2): ?>
	                    <li><span class="file"><a href="javascript:void(0)" name="SysUser">添加/删除账号</a></span></li>	<!--
	                    <li><span class="file"><a href="javascript:void(0)" name="PlayerS">钻石玩家管理</a></span></li>	 -->
	                    <!--<li><span class="file"><a href="javascript:void(0)" name="DownUserPhone">下载注册手机号</a></span></li>-->
	                    <?php endif; ?>
	                    <?php if ($this->_tpl_vars['DeptID'] != 1 && $this->_tpl_vars['DeptID'] != 5): ?>
	                    <li><span class="file"><a href="javascript:void(0)" name="PlayerA">控制号管理</a></span></li>
	                    <?php endif; ?>
	                    <?php if ($this->_tpl_vars['DeptID'] != 1 && $this->_tpl_vars['DeptID'] != 4 && $this->_tpl_vars['DeptID'] != 5): ?>
	                    <li><span class="file"><a href="javascript:void(0)" name="PlayerLogs">控制号日志查询</a></span></li>
	                    <?php endif; ?>
	                    <?php if ($this->_tpl_vars['DeptID'] == 3 || $this->_tpl_vars['DeptID'] == 4 || $this->_tpl_vars['DeptID'] == 2): ?>
	                    <li><span class="file"><a href="javascript:void(0)" name="SuperUser">商人玩家</a> </span></li>
	       		     	<?php endif; ?>

	                    <li><span class="file"><a href="javascript:void(0)" name="SysUserPwd">修改登录密码</a></span></li>
                </ul>
            </li>
            <?php if ($this->_tpl_vars['DeptID'] != 2 && $this->_tpl_vars['DeptID'] != 1 && $this->_tpl_vars['DeptID'] != 4 && $this->_tpl_vars['DeptID'] != 5): ?>
            <li class="closed"><span class="folder">机器人管理</span>
                <ul>
					<li><span class="file"><a href="javascript:void(0)" name="RobotCreator">创建机器人</a></span></li>
                    <li><span class="file"><a href="javascript:void(0)" name="RobotNamePool">机器人信息配置</a></span></li>
                    <li><span class="file"><a href="javascript:void(0)" name="RobotUser">机器人账号管理</a></span></li>
                    <li><span class="file"><a href="javascript:void(0)" name="RoomRobot">房间机器人管理</a></span></li>
					<?php if ($this->_tpl_vars['DeptID'] != 4 && $this->_tpl_vars['DeptID'] != 2): ?>
					<li><span class="file"><a href="javascript:void(0)" name="RoomRobotBehavior">激活房间机器人</a> </span></li>
					<?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>
            <?php if ($this->_tpl_vars['DeptID'] != 1 && $this->_tpl_vars['DeptID'] != 5): ?>
            <li class="closed"><span class="folder">游戏数据状态</span>
                <ul>
                    <li><span class="file"><a href="javascript:void(0)" name="SynchroData">同步配置数据</a> </span></li>

                </ul>
            </li>

			<!--<li class="closed"><span class="folder">支付管理</span>-->
				<!--<ul>-->
					<!--<li><span class="file"><a href="javascript:void(0)" name="PayMentType">支付通道</a> </span></li>-->
				<!--</ul>-->
			<!--</li>-->
            <?php endif; ?>

			<!--<li><span class="file">File 4</span></li>-->
		</ul>
		<?php if ($this->_tpl_vars['DeptID'] != 4): ?>
		<!--客户管理-->
		<div class="gfTit f2">
			<a class="clsFd f1"></a>
			<a class="gfName" href="">客户管理</a>
		</div>
		<ul id="Service" class="filetree">
			<!--<li><span class="folder">账务管理</span>-->
				<!--<ul>-->
					<!--<li><span class="file"><a href="javascript:void(0)" name="BankAccount">系统账户</a></span></li>-->
					<!--<li><span class="file"><a href="javascript:void(0)" name="BankTrans">转账交易</a></span></li>-->
					<!--<li><span class="file"><a href="javascript:void(0)" name="BankTransDetail">交易明细查询</a></span></li>-->
				<!--</ul>-->
			<!--</li>-->
			<!--<li class="closed"><span class="folder">系统管理</span>
				<ul>
					<li><span class="file"><a href="javascript:void(0)" name="Kind">游戏种类设置</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="Node">游戏节点设置</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="Version">大厅版本管理</a></span></li>
				</ul>
			</li>
			<li class="closed"><span class="folder">日常业务</span>
				<ul>
					<li><span class="file"><a href="javascript:void(0)" name="Kind">游戏种类设置</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="Node">游戏节点设置</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="Version">大厅版本管理</a></span></li>
				</ul>
			</li>	 -->
			<?php if ($this->_tpl_vars['DeptID'] != 5): ?>
			<li class="closed"><span class="folder">游戏管理</span>
				<ul>
					<li><span class="file"><a href="javascript:void(0)" name="ServiceRole">角色信息管理</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="WechatUser">微信客服管理</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="Msg">跑马灯管理</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="HappyBeanlist">排行榜管理</a></span></li>

					<li><span class="file"><a href="javascript:void(0)" name="ServiceCheckOther">客服财富审核</a></span></li>
                    <?php if ($this->_tpl_vars['DeptID'] != 1): ?>
					<li><span class="file"><a href="javascript:void(0)" name="ServiceCase">案件中心</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="ServiceWealth">财富冻结处理</a></span></li>
                    <?php endif; ?>
					<li><span class="file"><a href="javascript:void(0)" name="ServiceAuth">操作审核</a></span></li>
                    <?php if ($this->_tpl_vars['DeptID'] != 1): ?>
					<li><span class="file"><a href="javascript:void(0)" name="ServiceCheck">财富授权审核</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="ServiceWeathBack">玩家分数追回</a></span></li>
					<!--<li><span class="file"><a href="javascript:void(0)" name="ServiceManager">服务事件管理</a></span></li>
                    <li><span class="file"><a href="javascript:void(0)" name="ServiceRealCard">实卡查询</a></span></li>
                    <li><span class="file"><a href="javascript:void(0)" name="ServiceRealCardGet">实卡领取查询</a></span></li>-->

					<li><span class="file"><a href="javascript:void(0)" name="ControlleUser">房间控制</a></span></li>
					<?php endif; ?>
				</ul>
			</li>
			 <li class="closed"><span class="folder">玩家转出管理<b style="color:red" id="reviewid">(<?php echo $this->_tpl_vars['Review']; ?>
)</b></span>
				<ul>
					<li><span class="file"><a href="javascript:void(0)" name="ServiceExchange">转出申请审核</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="ServicePayMent">财务审核</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="CashOut">转出记录</a></span></li>
				</ul>
			</li>
			<?php elseif ($this->_tpl_vars['DeptID'] == 5): ?>
				<li class="closed"><span class="folder">玩家转出管理<b style="color:red" id="reviewid">(<?php echo $this->_tpl_vars['Review']; ?>
)</b></span>
					<ul>
						<li><span class="file"><a href="javascript:void(0)" name="ServicePayMent">财务审核</a></span></li>

					</ul>
				</li>
			<?php endif; ?>
			<!--<li class="closed"><span class="folder">数据查询</span>
				<ul>
					<li><span class="file"><a href="javascript:void(0)" name="Kind">游戏种类设置</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="Node">游戏节点设置</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="Version">大厅版本管理</a></span></li>
				</ul>
			</li>
			<li class="closed"><span class="folder">统计报表</span>
				<ul>
					<li><span class="file"><a href="javascript:void(0)" name="Kind">游戏种类设置</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="Node">游戏节点设置</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="Version">大厅版本管理</a></span></li>
				</ul>
			</li>
			<li class="closed"><span class="folder">其他功能</span>
				<ul>
					<li><span class="file"><a href="javascript:void(0)" name="Kind">游戏种类设置</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="Node">游戏节点设置</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="Version">大厅版本管理</a></span></li>
				</ul>
			</li>	 -->
		</ul>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['DeptID'] == 3 || $this->_tpl_vars['DeptID'] == 2 || $this->_tpl_vars['DeptID'] == 1): ?>
		<!--产品运营-->
		<div class="gfTit f2">
			<a class="clsFd f1"></a>
			<a class="gfName" href="">产品运营</a>
		</div>
		<ul id="YunYing" class="filetree">
        	<?php if ($this->_tpl_vars['DeptID'] == 3): ?>
			<!--<li class="closed"><span class="folder">赛事管理</span>
				<ul>
					<li class="closed"><span class="folder">66人赛</span>
						<ul>
							<li><span class="file"><a href="javascript:void(0)" name="Match">赛事列表</a></span></li>
							<li><span class="file"><a href="javascript:void(0)" name="MatchSearch">赛事查询</a></span></li>

						</ul>
					</li>
				</ul>
			</li>
            <li class="closed"><span class="folder">手机充值管理</span>
				<ul>
					<li><span class="file"><a href="javascript:void(0)" name="MatchRecharge">充值号码</a></span></li>
				</ul>
			</li>
			<li class="closed"><span class="folder">道具礼包配置</span>
				<ul>
					<li><span class="file"><a href="javascript:void(0)" name="SpRelease">发布道具</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="GiftPackage">设置礼包</a></span></li>
				</ul>
			</li>	-->
            <!--<li class="closed"><span class="folder">公告管理</span>-->
				<!--<ul>-->
					<!--<li><span class="file"><a href="javascript:void(0)" name="Notice">公告管理</a></span></li>-->
				<!--</ul>-->
			<!--</li>-->
            <?php endif; ?>
			<li class="closed"><span class="folder">数据统计</span>
				<ul>
					<li><span class="file"><a href="javascript:void(0)" name="InComeList">台账</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="GoldCoin">数据报表</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="User">用户统计</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="Online">在线用户列表</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="dayreg">当日注册用户</a></span></li>

					<li><span class="file"><a href="javascript:void(0)" name="RoomOnline">游戏房间状态</a></span></li>
				    <!--<li><span class="file"><a href="javascript:void(0)" name="OnlineUser">在线人数</a></span></li>-->
					<!--<li><span class="file"><a href="javascript:void(0)" name="ActiveUser">活跃用户</a></span></li>-->

					<li><span class="file"><a href="javascript:void(0)" name="Recharge">充值明细</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="UserDataCount">充值汇总</a></span></li>
					<?php if ($this->_tpl_vars['DeptID'] == 3 || $this->_tpl_vars['DeptID'] == 2): ?>
					<!--<li><span class="file"><a href="javascript:void(0)" name="RechargeForm">充值报表</a></span></li>-->
					<!--<li><span class="file"><a href="javascript:void(0)" name="Cashout">提现管理</a></span></li>-->
					<?php endif; ?>
                    <li><span class="file"><a href="javascript:void(0)" name="Logs">清零日志查询</a></span></li>
                    <li><span class="file"><a href="javascript:void(0)" name="ColorEgg">彩蛋查询</a></span></li>
				<li><span class="file"><a href="javascript:void(0)" name="LoginWarnLogs">登陆报警日志</a></span></li>
				</ul>
			</li>
			<li class="closed"><span class="folder">商人转账记录</span>
				<ul>
					<li><span class="file"><a href="javascript:void(0)" name="UserBankRateNew">普通玩家转出</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="UserBankRateSuper">商人转出</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="UserBankReturn">系统返还查询</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="UserBankReturnDetail">系统返还明细</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="DayUserChange">当日转账明细</a></span></li>

				</ul>
			</li>

			<!--li class="closed"><span class="folder">游戏排行</span>
				<ul>
					<li><span class="file"><a href="javascript:void(0)" name="HappyBean">金币排行</a></span></li>
					<?php if ($this->_tpl_vars['DeptID'] == 3 || $this->_tpl_vars['DeptID'] == 2 || $this->_tpl_vars['DeptID'] == 1): ?>
					<li><span class="file"><a href="javascript:void(0)" name="GameRate">游戏结算排行查询</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="UserBankRate">玩家转账排行查询</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="GameSort">输赢排行</a></span></li>
					<li><span class="file"><a href="javascript:void(0)" name="DayGameSort">每日输赢排行</a></span></li>
					<?php endif; ?>
				</ul>
			</li -->
		</ul>
		<?php endif; ?>		
	</div>
	<!--主区域-->
	<div class="main left">
		<iframe id="desktop" scrolling="auto" frameborder="0" src="/?d=main&c=Index&a=main"></iframe>
	</div>
		<!--右键菜单的源-->
	<div class="contextMenu" id="myMenuSubFolder">
	    <ul>
	        <li id="close">关闭</li>
	        <li id="closeAll">关闭全部</li>
	        <li id="closeOthers">关闭其它</li>
	    </ul>
	</div>
</div>
<br class="clear" />

</body>
</html>