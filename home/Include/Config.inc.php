<?php
return array('template_dir' => 'Templates', //模板文件所在目录
	'skin' => 'default', //当前模板文件
	'CheckIP' => 1, //1:开启同一IP频繁访问检测,0:关闭
	'LimitSeconds' => 30, //间隔时间:秒
	'VisitCount' => 1, //间隔时间内最多访问次数
	'MEMCACHED' => array(array('127.0.0.1' => '11211')),
	'CacheTime' => 300, //缓存时间5分钟
	'MapType' => array('Pass' => 1, //通行证
		'User' => 2, //角色
		'UserData' => 3, //角色仓库
		'Bank' => 4, //银行
		'OperationLogs' => 5, //操作日志
		'DataChangeLogs' => 6, //数据变化日志
		'Msg' => 7, //消息库
		'StageProperty' => 8,
		'Master' => 9,
		'PayLogs' => 15, //支付
	), //道具资源库
	'ImageDefault' => array('Img1' => '/images/locked_bag.png', //背包可使用的格子
		'Img2' => '/images/b12.gif'), //背包不可使用的格子
	'CssClass' => array('ClassName1' => 'bag_number', //背包格子有道具图片时引用的样式
		'ClassName2' => 'bag_blank'), //背包格子没道具图片时引用的样式
	'Http' => array('Wardrobe' => '?event=OpenBlock&site=wardrobe', //客户端根据此URL执行相应的操作
		'Mall' => '?event=OpenBlock&site=Mall',
		'MyKnapsack' => '/?event=Update&Para=Package',
	),
	'Cookies' => array('CookiesName' => 'GameFiveLogin', //cookies名字(登陆)
		'CookiesNameKnapsack' => 'GameFiveKnapsackPwd', //cookies名字(背包密码20分免输入)
		'PrevParams1' => 'PrevStartDate', //分页(上一页参数,上一页开始查询的起始日期)
		'PrevParams2' => 'PrevLogsID', //分页(上一页参数,上一页开始查询的起始LogsID)
		'CookiesDomain' => '.gamefive.com'), //cookies有效范围
	'Session' => array('SessionLoginName' => 'GameFiveLoginS', 'SessionData' => 'GameFiveData'), //Session名字
	'SessionInfo' => array('RoleID' => 'RoleID', //角色ID
		'Auth' => 'Auth', //随机数
		'MachineSerial' => 'MachineSerial', //机器码
		'Counter' => 'Counter', //计数器
		'ChkCode' => 'GameFiveChkCode', //验证码
		'RememberPwd' => 'GameFiveKnapsackPwd'), //记住密码
	'MsgType' => array('getLoginPwd' => 1, //短信类型,1:找回登录密码
		'getTranspwd' => 2, //2:找回交易密码
		'HostBind' => 3, //3:主机解绑
		'MobileAuth' => 5, //5:手机认证
		'EmailAuth' => 6, //6:邮箱认证
		'OpenAuth' => 7, //7:开通安全验证
		'CloseAuth' => 8, //8:关闭安全验证
		'ModiMobileAuth' => 9, //9:修改安全手机
		'getKnapsackPwd' => 10), //10:取回背包密码
	'Knapsack' => array('ExecStatus' => 1, //背包丢弃道具时输入密码操作锁定状态
		'ModiPwdStatus' => 2), //背包修改密码操作锁定状态
	'ExpireType' => array('Time' => 1, //我的道具过期类型,1:按时间过期
		'UseTimes' => 2), //2:按使用次数过期
	'LockedTime' => 30, //锁定时间30分钟
	'SpType' => array('Clothing' => 1, 'StageProperty' => 2, 'Gift' => 3), //道具分类
	'SpClassKeyID' => array( //'1001'=>1001,	//(需要清缓存的道具资源)服装卡
		//'2001'=>2001,	//(需要清缓存的道具资源)欢乐豆卡
		'2011' => 2011, //(需要清缓存的道具资源)加油卡
		'2006' => 2006), //(需要清缓存的道具资源)黄钻卡
	'SpClassAllKeyID' => array('2001' => 2001, //欢乐豆卡
		'2002' => 2002, //稳赢不输卡
		'2003' => 2003, //双倍积分卡
		'2004' => 2004, //积分保险卡
		'2005' => 2005, //漂白卡(只针对打宝房间有效)
		'2006' => 2006, //黄钻卡
		'2007' => 2007, //游戏积分卡
		'2008' => 2008, //负分清零卡
		'2009' => 2009, //运势卡
		'2010' => 2010, //体力补充卡
		'2011' => 2011), //打折加油卡
	'SpUseInfo' => array('2001' => '<p>欢乐豆存入背包成功，您背包内目前欢乐豆数量为{$0}</p>', //欢乐豆道具使用后的提示信息
		'2007' => '<p>游戏积分卡使用成功，{$1}游戏目前积分为{$0}。</p>',
		'2008' => '<p>负分清零成功，{$1}游戏目前积分为0。</p>',
		'2009' => '<p>运势随转，马上游戏！</p>',
		'2005' => '<p>漂白卡使用成功，{$1}游戏目前逃跑率为0。</p>',
		'2006' => '<p>黄钻贵族服务已开通，将于 {$0} 到期</p>',
		'2002' => '<p>稳赢不输卡使用成功，{$0}分钟内，您玩所有积分游戏均可享受 赢了双倍积分，输了不扣分的特权。</p>',
		'2003' => '<p>双倍积分卡使用成功，{$0}分钟内，您玩所有积分游戏均可享受赢了双倍积分，输了正常扣分的特权。</p>',
		'2004' => '<p>积分保险卡使用成功，{$0}分钟内，您玩所有积分游戏均可享受赢了正常得分，输了不扣分的特权。</p>',
		'2010' => '<p>体力补充成功，去打宝房间游戏。</p>',
		'2011' => '<p>加油卡使用成功，您现在拥有的等级打折卡可使用次数为{$0}</p>'),
	//'TablePrefix'=>array('LoginMallLogs'=>'T_LoginMallLogs_'),//临时表前缀
	'message' => array('您使用了道具<font class="pagesize">{$0}</font>',
		'您丢弃了道具<font class="pagesize">{$0}</font>(丢弃数量:{$1})',
		'您使用道具<font class="pagesize">{$0}</font>后,增加<font class="pagesize">{$1}</font>欢乐豆',
		'您解开了礼包<font class="pagesize">{$0}</font>后,增加道具<font class="pagesize">{$1}</font>',
	),
	'DcConfig' => array(array('HOST' => '127.0.0.1', 'PORT' => '8600')), //DC访问地址
	'EncryptKey' => 'hrtJ8l7Hlp', //加密解密的KEY值
	'MasterDBCONFIG' => array('DBHOST' => '127.0.0.1',
		'DBPORT' => '1433',
		'DBUSER' => 'sa',
		'DBPWD' => 'c2E=',//SJS0MXEydzNlNHI1dA==',
		'DBNAME' => 'MasterDB',
		'DBCHARSET' => 'utf8'),

	'Discount' => array(5001 => 0.9, 5004 => 0.9, 5002 => 0.85, 5003 => 0.85, 1001 => 0.75, 2001 => 0.82, 2003 => 0.78, 2002 => 0.8, 2004 => 0.75,
		2006 => 0.75, 2007 => 0.75, 2008 => 0.75, 2009 => 0.75, 9999 => 1), //打折，其中9999是网银折扣
	'VipDays' => 31, //黄钻天数
	'Rebate' => array('b780' => 0.6), //充值折扣
	'GameKind' => array(
		array('KindName' => '大闹天宫', 'KindID' => '2018'),
		array('KindName' => '中国象棋', 'KindID' => '4060'),
		array('KindName' => '火拼双扣', 'KindID' => '1120'),
		array('KindName' => '飞禽走兽', 'KindID' => '1109'),
		array('KindName' => '李逵劈鱼', 'KindID' => '2223'),
	),
	'URL' => array(
		"Home" => "https://www.game779.com",
		"RealCard" => "https://RealCard.game779.com",
		"Safety" => "https://Safety.game779.com",
		"WeChat" => "http://wechat.game779.com",
		"Sign" => "https://sign.game779.com",
		"QRCode" => "http://qrcode.game779.com",
		"Charge" => "https://charge.game779.com",
		"Order" => "https://order.game779.com",
		"CardCharge" => "https://CardCharge.game779.com",
		"PassPort" => "https://passport.game779.com",
	),

);
?>