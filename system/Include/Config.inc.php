<?php
return array('template_dir' => 'Templates', //模板文件所在目录
	'skin' => 'blue', //当前模板文件
	'CheckIP' => 1, //1:开启同一IP频繁访问检测,0:关闭
	'LimitSeconds' => 60, //间隔时间:秒
	'VisitCount' => 3, //间隔时间内最多访问次数
	'MEMCACHED' => array(array('127.0.0.1' => '11211')),
	'Directory' => 'Files', //生成配置文件目录
	'AutoLogoutTime' => 10800, //自动登出时间(s)
	'AutoLogoutCheckTime' => 60, //自动登出检测时间(s)
	'MapType' => array('Pass' => 1, //通行证账号
		'User' => 2, //角色
		'UserData' => 3, //角色仓库
		'Bank' => 4, //银行
		'OperationLogs' => 5, //操作日志
		'DataChangeLogs' => 6, //数据变化日志
		'Msg' => 7, //消息库
		'StageProperty' => 8, //道具资源库
		'GameFive' => 9, //官网数据库
		'Match' => 10, //比赛数据库
		'System' => 13, //后台管理数据库
		'PassSecurity' => 14, //通行证安全(临时用)
		'PayLogs' => 15,
		'BankChangeLogs' => 16,
		'SetOperationLogs' => 17,
		'CDAccount' => 18,
	), //支付
	'DB_NAME_PREFIX' => 'NNT_',
	'Session' => array('SessionLoginName' => 'GameFiveAdminLogin', 'SessionData' => 'GameFiveSessionData'), //Session名字
	'SessionInfo' => array('AdminID' => 'AdminID', //管理员ID
		'UserName' => 'UserName', //用户名
		'LastLoginTime' => 'LastLoginTime', //上次登陆时间
		'LastLoginIP' => 'LastLoginIP', //上次登陆ＩＰ
		'ChkCode' => 'GameFiveChkCode', //验证码
		'DeptID' => 'DeptID', //部门ID
        'AdminRole' => 'AdminRole',
		'PHPExcel' => 'PHPExcel', //phpExecl类对象
		'RecordsCount' => 'RecordsCount',
		'BankPHPExcel' => 'BankPHPExcel',
		'LastOperateTime' => 'LastOperateTime',
	), //银行资料phpExecl类对象
	'Cookies' => array('iRecordsCount' => 'iRecordsCount', //总记录数
		'PrevParams1' => 'PrevStartDate', //分页(上一页参数,上一页开始查询的起始日期)
		'PrevParams2' => 'PrevLogsID', //分页(上一页参数,上一页开始查询的起始LogsID)
		'PrevParams3' => 'PrevKindID', //分页(上一页参数,上一页开始查询的起始KindID)
		'NextParams1' => 'NextStartDate', //分页(下一页参数,下一页开始查询的起始日期)
		'NextParams3' => 'NextKindID'), //分页(下一页参数,下一页开始查询的起始KindID)
	'CacheTime' => 300, //缓存时间5分钟
	'GameKindClass' => array(array('ClassID' => 1, 'ClassName' => '牌类游戏'), //游戏种类所属分类
		array('ClassID' => 2, 'ClassName' => '骨牌游戏'),
		array('ClassID' => 3, 'ClassName' => '棋类游戏'),
		array('ClassID' => 4, 'ClassName' => '休闲游戏'),
	),
	'RoomType' => array(//array('TypeID' => 1, 'TypeName' => '积分房间'), //房间类型
		array('TypeID' => 2, 'TypeName' => '金币房间'),
		//array('TypeID' => 4, 'TypeName' => '比赛房间'),
		//  array('TypeID'=>32,'TypeName'=>'道具房间'),
	),
	'PayType' => array(array('TypeID' => 1, 'TypeName' => '积分'), //结算类型
		array('TypeID' => 2, 'TypeName' => '金币'),
		array('TypeID' => 32, 'TypeName' => '道具'),
		array('TypeID' => 4, 'TypeName' => '比赛'),
	),
	'NodeType' => array(array('TypeID' => 1, 'TypeName' => '普通节点'),
		array('TypeID' => 2, 'TypeName' => '链接节点'),
		array('TypeID' => 3, 'TypeName' => '游戏节点'),
		//array('TypeID'=>4,'TypeName'=>'桌子类型'),
		array('TypeID' => 5, 'TypeName' => '房间节点'),
	),
	'ServerType' => array(array('TypeID' => 0, 'TypeName' => '数据库服务器'),
		array('TypeID' => 1, 'TypeName' => '游戏服务器'),
		array('TypeID' => 2, 'TypeName' => '登录服务器'),
		array('TypeID' => 3, 'TypeName' => '下载服务器'),
		array('TypeID' => 4, 'TypeName' => '版本服务器'),
		array('TypeID' => 5, 'TypeName' => '银行服务器'),
		array('TypeID' => 6, 'TypeName' => '中心服务器'),
		array('TypeID' => 7, 'TypeName' => '大厅服务器'),
	),
	'ServerTypeWeb' => array(array('TypeID' => 32, 'TypeName' => '官网'),
		array('TypeID' => 33, 'TypeName' => '商城'),
		array('TypeID' => 34, 'TypeName' => '解绑'),
		array('TypeID' => 35, 'TypeName' => '衣柜'),
		array('TypeID' => 36, 'TypeName' => '银行'),
		array('TypeID' => 37, 'TypeName' => '个人中心'),
		array('TypeID' => 38, 'TypeName' => '角色信息'),
		array('TypeID' => 40, 'TypeName' => '充值'),
		array('TypeID' => 42, 'TypeName' => '图片资源'),
		array('TypeID' => 43, 'TypeName' => '安全中心(通行证)'),
		array('TypeID' => 44, 'TypeName' => '新闻公告'),
		array('TypeID' => 45, 'TypeName' => '广告'),
		array('TypeID' => 48, 'TypeName' => '黄钻'),
		array('TypeID' => 41, 'TypeName' => '比赛专题'),
		array('TypeID' => 50, 'TypeName' => '官网注册'),
		array('TypeID' => 39, 'TypeName' => '游戏下载'),
	),
	'ServerTypeData' => array(
		array('TypeID' => 60, 'TypeName' => '日志'),
		array('TypeID' => 61, 'TypeName' => '账号'),
		array('TypeID' => 62, 'TypeName' => '数据中心'),
		array('TypeID' => 63, 'TypeName' => '充值'),
		array('TypeID' => 64, 'TypeName' => '安全'),
	),
	'RecList' => array(array('TypeID' => 0, 'TypeName' => '不推荐'),
		array('TypeID' => 1, 'TypeName' => '推荐'),
	),
	'SpClass' => array(array('TypeID' => 1, 'TypeName' => '服装'), //道具大类
		array('TypeID' => 2, 'TypeName' => '道具'),
	),
	'Category' => array(array('TypeID' => 1, 'TypeName' => '服装'), //道具大类
		array('TypeID' => 2, 'TypeName' => '道具'),
		array('TypeID' => 9, 'TypeName' => '事件'),
	),
	'SpBigClass' => array(array('TypeID' => 1, 'TypeName' => '服装'), //道具大类,添加道具分类的时候调用
		array('TypeID' => 2, 'TypeName' => '道具'),
		array('TypeID' => 3, 'TypeName' => '礼包'),
		array('TypeID' => 9, 'TypeName' => '事件'),
	),
	'SpClassKeyID' => array('ClothKeyID' => 1001, //服装卡
		'SpKeyID' => 2001, //金币卡
		'GiftKeyID_01' => 3001, //黄钻礼包,道具分类表相应的KeyID值
		'GiftKeyID_02' => 3002, //摇摇星礼包
		'EvtKeyID_01' => 9001, //摇摇星事件
		'EvtKeyID_02' => 9101, //体力事件(属于摇摇星事件)
		'EvtKeyID_03' => 9102, //体力事件(属于摇摇星事件)
		'EvtKeyID_04' => 9103, //掉宝率事件(属于摇摇星事件)
		'EvtKeyID_05' => 9104, //掉宝率事件(属于摇摇星事件)
		'EvtKeyID_06' => 9105, //运势事件(属于摇摇星事件)
		'EvtKeyID_07' => 9106, //道具事件(属于摇摇星事件)
	),
	'Place' => array(array('TypeID' => 1, 'TypeName' => '背包'), //道具使用场景
		array('TypeID' => 2, 'TypeName' => '房间'),
		array('TypeID' => 4, 'TypeName' => '游戏'),
	),
	'MapList' => array(array('MapID' => 1, 'Name' => '通行证账号数据库'), //1:通行证账号
		array('MapID' => 2, 'Name' => '角色数据库'), //2:角色
		array('MapID' => 3, 'Name' => '角色仓库数据库'), //3:角色仓库
		array('MapID' => 4, 'Name' => '银行数据库'), //4:银行
		array('MapID' => 5, 'Name' => '操作日志数据库'), //5:操作日志
		array('MapID' => 6, 'Name' => '数据变化日志数据库'), //6:数据变化日志
		array('MapID' => 7, 'Name' => '消息数据库'), //7:消息库
		array('MapID' => 8, 'Name' => '道具资源数据库'), //8:道具资源库
		array('MapID' => 9, 'Name' => '官网数据库'),
		array('MapID' => 10, 'Name' => '比赛数据库'),
		array('MapID' => 12, 'Name' => '好友数据库'),
		array('MapID' => 13, 'Name' => '后台管理库'),
		//array('MapID'=>9,'Name'=>'登录服务器'),//9:登录服务器
		array('MapID' => 14, 'Name' => '通行证安全数据库'), //10:通行证安全(临时用)
		//array('MapID'=>11,'Name'=>'通行证信息数据库')//11:通行证信息
	),
	'BankAccType' => array('1' => '基本户',
		'2' => '税收户',
		'3' => '充值户',
		'4' => '推广户',
		'5' => '消费户',
		'6' => '冻结户',
		'7' => '诈金花专用户',
		'8' => '百家乐专用户',
		'9' => '同比牛牛专用户',
		'10' => '牛牛专用户',
		'11' => '港五专用户',
		'12' => '彩金专用户',
		'13' => '比赛专用户',
		'14' => '骰子专用户',
		'15' => '会员工资',
		'16' => '转账返还',
		'17' => '微信绑定',
		'18' => '每日任务',
		'19' => '每日签到',
		'20' => '捕鱼专用户',
		'21' => '游戏税收户',
		'22' => '捕鱼结算',
		'23' => '实卡充值户',
		'24' => '至尊专用户',
		'25' => '港五彩金',
		'26' => '飞禽走兽结算户',
		'27' => '飞禽走兽专用户',
		'28' => '大闹天宫结算户',
		'29' => '水果乐园结算户',
		'30' => '水果乐园专用户',
		'31' => '微信分享',
		'32' => '奔驰宝马专用户',
	),
	'BankChangeType' => array(
		array('value' => '1', 'name' => '取款', 'type' => 0), //取款-	1
		array('value' => '2', 'name' => '赠送', 'type' => 0),
		array('value' => '3', 'name' => '冻结', 'type' => 0),
		array('value' => '4', 'name' => '存款', 'type' => 1),
		array('value' => '5', 'name' => '收款', 'type' => 1),
		array('value' => '6', 'name' => '解锁', 'type' => 1),
		array('value' => '7', 'name' => '充值', 'type' => 1),
		array('value' => '8', 'name' => '工资', 'type' => 1),
		array('value' => '9', 'name' => '转账返还', 'type' => 1),
		array('value' => '10', 'name' => '系统存款', 'type' => 1),
		array('value' => '11', 'name' => '系统赠送', 'type' => 1),
		array('value' => '12', 'name' => '转账扣税', 'type' => 0),
		array('value' => '13', 'name' => '任务奖励', 'type' => 1),
		array('value' => '14', 'name' => '绑定微信', 'type' => 1),
		array('value' => '15', 'name' => '每日签到', 'type' => 1),
		array('value' => '16', 'name' => '房间彩蛋', 'type' => 1),
		array('value' => '17', 'name' => '实物卡充值', 'type' => 1),
	),
	'PositionType' => array('1' => 'WEB广告', '2' => '客户端广告'),
	'TransType' => array('TransType1' => 1, //系统银行日志表交易类型,内转
		'TransType2' => 2, //扩容
		'TransType3' => 3, //充值
		'TransType4' => 4, //赠送
		'TransType5' => 5, //税收
		'TransType6' => 6, //道具兑换
		'TransType7' => 7, //角色冻结
		'TransType8' => 8, //商城消费
		'TransType9' => 9, //系统赠送/补偿
		'TransType10' => 10, //案件返还
		'TransType11' => 11, //充值卡返还
		'TransType12' => 12), //领取日工资
	'LogType' => array(
		array("name" => '通过帐号验证', "value" => 1),
		array("name" => '登陆大厅', "value" => 2),
		array("name" => '登陆房间', "value" => 3),
		array("name" => '登陆银行', "value" => 4),
		array("name" => '注销', "value" => 5),
		array("name" => '退出房间', "value" => 6),
		array("name" => '退出银行', "value" => 7),
		array("name" => '银行财富变化', "value" => 8),
		array("name" => '游戏财富数据变化', "value" => 9),
		array("name" => '银行财富', "value" => 10),
		array("name" => '游戏财富数据', "value" => 11),
		array("name" => '角色创建', "value" => 12),
		array("name" => '机器人游戏存取款', "value" => 13),
		array("name" => '玩家购买VIP日志', "value" => 14),
	),
	'BankTransType' => array(0, 1, 2, 3, 4, 5, 6, 7), //用户银行交易类型：0银行(游戏)存取 ,1用户转帐,2税收, 3背包存取,4:充值,5:系统返还,6:系统补偿,7:领取日工资
	'DCFlag' => array('DCFlag1' => 1, 'DCFlag2' => 2), //1'=>'借入','2'=>'贷出'
	'FileCategory' => array('1' => '解压', '2' => '覆盖', '3' => '运行'), //升级包安装类型
	'EncryptKey' => 'hrtJ8l7Hlp', //加密解密的KEY值
	'CaseStatus' => array( //案件状态
		1 => '待处理',
		2 => '处理中',
		3 => '待复核',
		4 => '复核中',
		5 => '待移交',
		6 => '待执行',
		7 => '执行中',
		8 => '执行完毕',
		9 => '案件完结',
		10 => '案件撤销'),
	'CaseLogNote' => array( //案件操作日志内容
		1 => '录入',
		2 => '修改状态&nbsp;处理中',
		3 => '修改状态&nbsp;待复核',
		4 => '修改状态&nbsp;复核中',
		5 => '修改状态&nbsp;待移交',
		6 => '修改状态&nbsp;待执行',
		7 => '修改状态&nbsp;执行中',
		8 => '修改状态&nbsp;执行完毕',
		9 => '处罚&nbsp;已完结',
		10 => '修改状态&nbsp;案件撤销',
		98 => '填写案件进展',
		99 => '上传文件',
		100 => '更新备注',
		101 => '更新追回金额',
		102 => '删除文件',
		103 => '添加涉案人',
		104 => '删除涉案人',
		105 => '修改案件描述'),
	'OperateVerifyType' => array( //操作审核类型
		1 => '重置银行密码',
		2 => '重置背包密码',
		3 => '主机解绑',
		4 => '解除锁定',
		5 => '解除处罚',
		23 => '微信解绑'),
    'ExchangeType' => array( //操作审核类型
        1 => '支付宝',
        2 => '银行'),
	'AuthVerifyType' => array( //授权审核类型
		6 => '返还金币',
		7 => '补发金币',
		8 => '补发龙币',
		9 => '补发积分',
		10 => '补发道具',
		11 => '补发黄钻',
		15 => '回收黄钻',
	),
	'BuyVipKeyID' => 2006, //补发黄钻服务用，黄钻卡为2006
	'TreasureCompensateType' => array( //财富补偿类型
		7 => '金币',
		8 => '龙币',
		9 => '积分',
		10 => '道具',
		11 => '黄钻服务',
		15 => '黄钻回收',
	),
	'TreasureCompensateReason' => array( //财富补偿原因
		1 => '技术故障',
		2 => '游戏BUG',
		3 => '官方赠送'),
    'TreasureBackReason' => array(
        1 => '充值错误',
        2 => '违规账号',
        3 => '其他原因'),
	'RechargeType' => array(1 => '银行卡', //充值方式
		2 => '移动充值卡',
		3 => '联通充值卡',
		4 => '电信充值卡',
		5 => '骏网一卡通',
		6 => '盛大一卡通',
		7 => '征途游戏卡',
		8 => '网易一卡通',
		9 => '搜狐一卡通',
		10 => '完美一卡通',
		11 => '久游一卡通',
		12 => '纵游一卡通',
		13 => '天下一卡通',
		14 => '天宏一卡通',
		15 => '翼支付',
		16 => 'Q币卡',
		17 => '易宝一卡通'),
	'OpPlace' => array(0 => '后台', //操作来源(角色日志用到)
		1 => '银行',
		2 => '商城',
		3 => '背包',
		4 => '衣柜',
		5 => '黄钻',
		6 => '用户中心',
		7 => '角色信息',
		8 => '官网',
		9 => '客户端',
		10 => '银行'),
	'OpType' => array(0 => '锁号', //操作类型(角色日志用到)
		1 => '解锁',
		2 => '冻结金币',
		3 => '处理财富',
		4 => '补发黄钻服务期',
		5 => '主机绑定解绑',
		6 => '返还金币',
		7 => '补发金币',
		8 => '补发龙币',
		9 => '补发积分',
		10 => '补发道具',
		11 => '绑定主机',
		12 => '背包密码重置',
		13 => '背包密码修改',
		14 => '背包功能冻结',
		15 => '解除背包冻结',
		16 => '银行密码重置',
		17 => '银行密码修改',
		18 => '银行功能冻结',
		19 => '解除银行冻结',
		20 => '封号',
		21 => '解封',
		22 => '购买金币',
		23 => '购买道具',
		24 => '丢弃服装',
		32 => '修改注册手机',
	),
	'SpFrom' => array(0 => '商城购买', //道具来源(道具资料用到)
		1 => '打宝房间',
		2 => '黄钻礼包',
		3 => '任务获取',
		4 => '活动获取',
		5 => '财富补偿'),
	'PrizeType' => array(0 => '单元赛奖品', //道具来源(道具资料用到)
		1 => '日奖品',
		2 => '周奖品',
		3 => '月奖品',
		4 => '季度奖品',
		5 => '年度奖品'),
	'LoginIdRule' => array(array('Pattern' => 'AAAAAAAA', 'Regx' => '([\d])\1{7}'), //玩家编号规则,优先级A
		array('Pattern' => '8ABCDEFG', 'Regx' => '8(?:0(?=1)|1(?=2)|2(?=3)|3(?=4)|4(?=5)|5(?=6)|6(?=7)|7(?=8)|8(?=9)){6}\d'),
		array('Pattern' => '8GFEDCBA', 'Regx' => '8(?:9(?=8)|8(?=7)|7(?=6)|6(?=5)|5(?=4)|4(?=3)|3(?=2)|2(?=1)|1(?=0)){6}\d'),

		array('Pattern' => '8AAAAAAA', 'Regx' => '8([\d])\1{6}'), //优先级B
		array('Pattern' => '8*AAAABB', 'Regx' => '8\d{1}([\d])\1{3}([\d])\2{1}'),
		array('Pattern' => '8*ABCABC', 'Regx' => '8\d{1}(([\d]){1}([\d]){1}([\d]){1})\1{1}'),
		array('Pattern' => '8**AAAAA', 'Regx' => '8\d{2}([\d])\1{4}'),
		array('Pattern' => '8*AAABBB', 'Regx' => '8\d{1}([\d])\1{2}([\d])\2{2}'),
		array('Pattern' => '8AA*BBBB', 'Regx' => '8([\d])\1{1}\d{1}([\d])\2{3}'),
		array('Pattern' => '8AAAA*BB', 'Regx' => '8([\d])\1{3}\d{1}([\d])\2{1}'),
		array('Pattern' => '8*ABCDEF', 'Regx' => '8\d{1}(?:0(?=1)|1(?=2)|2(?=3)|3(?=4)|4(?=5)|5(?=6)|6(?=7)|7(?=8)|8(?=9)){5}\d'),
		array('Pattern' => '8ABCDEF*', 'Regx' => '8(?:0(?=1)|1(?=2)|2(?=3)|3(?=4)|4(?=5)|5(?=6)|6(?=7)|7(?=8)|8(?=9)){5}\d{2}'),
		array('Pattern' => '8*FEDCBA', 'Regx' => '8\d{1}(?:9(?=8)|8(?=7)|7(?=6)|6(?=5)|5(?=4)|4(?=3)|3(?=2)|2(?=1)|1(?=0)){5}\d'),
		array('Pattern' => '8FEDCBA*', 'Regx' => '8(?:9(?=8)|8(?=7)|7(?=6)|6(?=5)|5(?=4)|4(?=3)|3(?=2)|2(?=1)|1(?=0)){5}\d{2}'),
		array('Pattern' => '8AAAAAA*', 'Regx' => '8(([\d]){1})\1{5}\d{1}'),

		array('Pattern' => '8*ABABAB', 'Regx' => '8\d{1}(([\d]){1}([\d]){1})\1{2}'), //优先级C
		array('Pattern' => '8*AAAAA*', 'Regx' => '8\d{1}(([\d]){1})\1{4}\d{1}'),
		array('Pattern' => '8AAAAA**', 'Regx' => '8(([\d]){1})\1{4}\d{2}'),
		array('Pattern' => '8*AABBBB', 'Regx' => '8\d{1}([\d])\1{1}([\d])\2{3}'),
		array('Pattern' => '8AAA*BBB', 'Regx' => '8([\d])\1{2}\d{1}([\d])\2{2}'),
		array('Pattern' => '8AAABBB*', 'Regx' => '8([\d])\1{2}([\d])\2{2}\d{1}'),
		array('Pattern' => '8AABBBB*', 'Regx' => '8([\d])\1{1}([\d])\2{3}\d{1}'),
		array('Pattern' => '8ABABAB*', 'Regx' => '8(([\d]){1}([\d]){1})\1{2}\d{1}'),
		array('Pattern' => '8ABCABC*', 'Regx' => '8(([\d]){1}([\d]){1}([\d]){1})\1{1}\d'),
		array('Pattern' => '8AAAABB*', 'Regx' => '8([\d])\1{3}([\d])\2{1}\d{1}'),

		array('Pattern' => '8**AAAA*', 'Regx' => '8\d{2}(([\d]){1})\1{3}\d{1}'), //优先级D
		array('Pattern' => '8AAAA***', 'Regx' => '8(([\d]){1})\1{3}\d{3}'),
		array('Pattern' => '8*AAAA**', 'Regx' => '8\d{1}([\d])\1{3}\d{2}'),
		array('Pattern' => '8*AABBCC', 'Regx' => '8\d{1}([\d])\1{1}([\d])\2{1}([\d])\3{1}'),
		array('Pattern' => '8AABBCC*', 'Regx' => '8([\d])\1{1}([\d])\2{1}([\d])\3{1}\d{1}'),
		array('Pattern' => '8***AAAA', 'Regx' => '8\d{3}([\d])\1{3}'),
	),
	'GetPrizeTypeList' => array(array('TypeID' => 1, 'TypeName' => '按名次')), //比赛获奖条件
	'GetStatusList' => array(array('TypeID' => 0, 'TypeName' => '全取'), //比赛获奖子条件
		array('TypeID' => 1, 'TypeName' => '并列取输赢'),
		array('TypeID' => 2, 'TypeName' => '并列取报名先后'),
		array('TypeID' => 3, 'TypeName' => '并列随机取其一'),
	),
	'MasterRight' => array(
		array('RightID' => 0x1, 'RightName' => '允许禁止游戏'),
		array('RightID' => 0x2, 'RightName' => '允许禁止旁观'),
		array('RightID' => 0x4, 'RightName' => '允许禁止私聊 '),
		array('RightID' => 0x8, 'RightName' => '允许房间禁止聊天'),
		array('RightID' => 0x10, 'RightName' => '允许游戏禁止聊天'),
		array('RightID' => 0x20, 'RightName' => '允许踢出用户'),
		array('RightID' => 0x40, 'RightName' => '允许封锁帐号'),
		array('RightID' => 0x80, 'RightName' => '允许禁止地址'),
		array('RightID' => 0x100, 'RightName' => '允许查看地址'),
		array('RightID' => 0x200, 'RightName' => '允许发送警告'),
		array('RightID' => 0x400, 'RightName' => '允许发布消息'),
		array('RightID' => 0x800, 'RightName' => '允许游戏绑定'),
		array('RightID' => 0x1000, 'RightName' => '允许游戏绑定'),
		array('RightID' => 0x2000, 'RightName' => '允许区分机器'),
		array('RightID' => 0x4000, 'RightName' => '充许查看客户端信息'),
	),
	'UserRight' => array(
		array('RightID' => 0x1, 'RightName' => '不能进行游戏'),
		array('RightID' => 0x2, 'RightName' => '不能旁观游戏'),
		array('RightID' => 0x4, 'RightName' => '不能发送私聊'),
		array('RightID' => 0x8, 'RightName' => '不能大厅聊天'),
		array('RightID' => 0x10, 'RightName' => '不能游戏聊天'),
        array('RightID' => 0x20, 'RightName' => '允许作弊'),
	),
	'SystemRight' => array(
		array('RightID' => 0x20, 'RightName' => '系统玩家'),
		array('RightID' => 0x40, 'RightName' => '捕鱼管理'),
	),
	'RealCardStatus' => array(
		array("value" => 0, "name" => "未取用"),
		array("value" => 1, "name" => "发布"),
		array("value" => 2, "name" => "已充值"),
		array("value" => 3, "name" => "销毁"),

	),
	'SetSevice' => array(
		'1' => '大厅',
		'2' => '银行',
		'3' => '登录',
		'4' => '官网',
		'5' => '后台',
	),
	'OperateType' => array(
		'1' => '锁定',
		'2' => '解锁',
		'3' => '绑定',
		'4' => '解绑',
		'5' => '封号',
		'6' => '解封',
		'7' => '设置',
		'8' => '解除',
		'9' => '重置',
		'10' => '修改',
	),
	'DateType' => array(
		'1' => '帐号（锁定）',
		'2' => '帐号（封号）',
		'3' => '主机',
		'4' => '微信',
		'5' => '登录密码',
		'6' => '银行密码',
		'7' => '当前房间',
		'8' => '手机号',
		'9' => 'IP异常锁',
		'10' => '登录验证',
		'11' => '个性签名',
		'12' => '银行注销时间',
		'13' => '银行关闭询问',
		'14' => '商人',
		'15' => '系统玩家',
		'16' => '管理员',
		'17' => '角色权限',
		'18' => '银行微信验证',
		'19' => '银行',
		'20' => '账号信息',
	),
    'GroupName' => array(
        'YunWei' => '运维平台',
        'Service' => '客户管理',
        'YunYing' => '产品运营'
    ),
	'ClassType' => array (
	    '1200' => '支付宝充值',
        '1300' => '微信支付',
        '1400' => '银联支付',
        '1500' => '银行卡转账',
        '1600' => '支付宝转账'
    ),
	'ChannelType' => array(
	    '120' => '万通支付',
        '130' => '永顺支付',
        '140' => '万咖支付',
        '150' => '恒丰泰支付',
        '160' => '虎牙支付',
        '170' => '金咖支付',
        '180' => '微联支付',
        '190' => '谷岚支付',
        '200' => '玖富支付H5',
        '210' => '玖富支付扫码',
        '220' => '云成支付宝H5',
        '230' => '易联保支付宝H5',
        '240' => '98支付H5',
    ),
	'PlayStatus' => array(0 => '赢', 1 => '输', 2 => '和', 3 => '逃'), //游戏结果
	'MatchMode' => array(array('TypeID' => 1, 'MatchModeName' => '斗地主（打立出局+定局积分）')), //比赛模式
	'MatchRound' => array(1 => '初赛', 2 => '复赛'), //
	'DcConfig' => array(array('HOST' => '127.0.0.1', 'PORT' => '18600')), //DC访问地址
	'NoticeType' => array(array('TypeID' => 1, 'TypeName' => '比赛公告')), //公告类型
	'PlayerType' => array(1 => '钻石玩家', 33 => '管理员', 4 => '系统玩家'), //玩家性质
	//'RechargeType'=>array(0=>'不限',1=>'快钱充值',2=>'聚宝充值',3=>'北网充值'),//充值方式
	'RechargeType' => array(0 => '不限', 1 => '支付宝', 2 => '微信', 3 => '北网', 33 => 'ios'), //充值方式
	'RechargeStatusType' => array(0 => '待付款', 1 => '付款成功', 2 => '付款失败', 3 => '充值成功', 4 => '充值失败'),
	'HttpRequest' => array(array('Url' => 'http://115.238.184.66:8001/matchCode.aspx', 'Key' => '5Sd92304sdkdsds02'), //金币兑换接口
		array('Url' => 'http://www.chongzhi8.com/agentint', 'Param' => 'Version=1.0&Action=OrderQueryReq&AgentID=100071', 'Key' => '')), //手机充值接口
	'HappyBean' => '21200000000', //积分检查增加的金币数
	'MasterDBCONFIG' => array('DBHOST' => '172.23.3.11',
		'DBPORT' => '1433',
		'DBUSER' => 'sa',
		'DBPWD' => 'OXk1d0Q0QFlhcXBBbHhPTw==',
		'DBNAME' => 'OM_MasterDB',
		'DBCHARSET' => 'utf8'),

);
?>