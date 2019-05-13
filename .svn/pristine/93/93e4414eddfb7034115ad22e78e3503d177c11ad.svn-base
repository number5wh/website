<?php /* Smarty version 2.6.26, created on 2019-04-18 11:20:02
         compiled from blue/main.html */ ?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>运维后台</title>
<!--<link type="text/css" rel="stylesheet" href="/css/common.css" />-->
<!--<link type="text/css" rel="stylesheet" href="/css/blue.css" />-->
    <link rel="stylesheet" href="/layui/layuiadmin/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="/layui/layuiadmin/style/admin.css" media="all">
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
</head>

<body>
<div class="layui-fluid">
    <div class="layui-row layui-col-space30">
        <div class="layui-col-md4 layui-col-lg4 layui-col-xs12 layui-col-sm6 ">
            <div class="layui-card">
                <div class="layui-card-header">
                    总用户
                    <span class="layui-badge layui-bg-blue layuiadmin-badge">全部</span>
                </div>
                <div class="layui-card-body layuiadmin-card-list">
                    <p class="layuiadmin-big-font"><?php echo $this->_tpl_vars['data']['TotalUser']; ?>
</p>
                </div>
            </div>
        </div>
        <div class="layui-col-md4 layui-col-lg4 layui-col-xs12 layui-col-sm6 ">
            <div class="layui-card">
                <div class="layui-card-header">
                    今日注册
                    <span class="layui-badge layui-bg-cyan layuiadmin-badge">今日</span>
                </div>
                <div class="layui-card-body layuiadmin-card-list">
                    <p class="layuiadmin-big-font"><?php echo $this->_tpl_vars['data']['TodayReg']; ?>
</p>
                </div>
            </div>
        </div>

        <div class="layui-col-md4 layui-col-lg4 layui-col-xs12 layui-col-sm6 ">
            <div class="layui-card">
                <div class="layui-card-header">
                    总订单
                    <span class="layui-badge layui-bg-blue layuiadmin-badge">全部</span>
                </div>
                <div class="layui-card-body layuiadmin-card-list">
                    <p class="layuiadmin-big-font"><?php echo $this->_tpl_vars['data']['TotalOrder']; ?>
</p>
                </div>
            </div>
        </div>

        <div class="layui-col-md4 layui-col-lg4 layui-col-xs12 layui-col-sm6 ">
            <div class="layui-card">
                <div class="layui-card-header">
                    今日订单
                    <span class="layui-badge layui-bg-cyan layuiadmin-badge">今日</span>
                </div>
                <div class="layui-card-body layuiadmin-card-list">
                    <p class="layuiadmin-big-font"><?php echo $this->_tpl_vars['data']['TodayOrder']; ?>
</p>
                </div>
            </div>
        </div>


        <div class="layui-col-md4 layui-col-lg4 layui-col-xs12 layui-col-sm6 ">
            <div class="layui-card">
                <div class="layui-card-header">
                    商人转出
                    <span class="layui-badge layui-bg-red layuiadmin-badge">商人</span>
                </div>
                <div class="layui-card-body layuiadmin-card-list">
                    <p class="layuiadmin-big-font"><?php echo $this->_tpl_vars['data']['SuperUserOut']; ?>
</p>
                </div>
            </div>
        </div>

        <div class="layui-col-md4 layui-col-lg4 layui-col-xs12 layui-col-sm6 ">
            <div class="layui-card">
                <div class="layui-card-header">
                    玩家账号余额
                    <span class="layui-badge layui-bg-green layuiadmin-badge">玩家</span>
                </div>
                <div class="layui-card-body layuiadmin-card-list">
                    <p class="layuiadmin-big-font"><?php echo $this->_tpl_vars['data']['PlayerAccount']; ?>
</p>
                </div>
            </div>
        </div>

        <div class="layui-col-md12 layui-col-lg12 layui-col-xs12 layui-col-sm12">
            <div class="layui-card">
                <div class="layui-card-header layuiadmin-card-header-auto" style="text-align: left">数据概览</div>
                <div class="layui-card-body">
                    <div class="layui-carousel layadmin-carousel layadmin-dataview" id="test1" lay-arrow="always" data-anim="fade" lay-filter="LAY-index-dataview">
                        <div carousel-item id="LAY-index-dataview">
                            <div><i class="layui-icon layui-icon-loading1 layadmin-loading"></i></div>
                            <div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

</script>
<script type="text/javascript" src="/layui/layuiadmin/layui/layui.js"></script>
<script type="text/javascript">
    <?php echo '
    layui.config({
        base: \'/layui/layuiadmin/\' //静态资源所在路径
    }).extend({
        index: \'lib/index\' //主入口模块
        ,home: \'../mymod/home\'
    }).use([\'index\', \'home\', \'carousel\'], function() {
        var carousel = layui.carousel;
        //建造实例
        carousel.render({
            elem: \'#test1\'
            ,width: \'100%\' //设置容器宽度
            ,arrow: \'always\' //始终显示箭头
            ,autoplay:false
            //,anim: \'updown\' //切换动画方式
        });
    });
    '; ?>

</script>

</body>
</html>