<?php
return array(
	'template_dir' => 'Templates', //模板文件所在目录
	'skin' => 'default', //当前模板文件
	'CheckIP' => 1, //1:开启同一IP频繁访问检测,0:关闭
	'LimitSeconds' => 60, //间隔时间:秒
	'VisitCount' => 5, //间隔时间内最多访问次数
	'MEMCACHED' => array(array('127.0.0.1' => '11211')),
	'CacheTime' => 300, //缓存时间5分钟
	'TestMode' => false, //测试
	'MapType' => array(
		'Pass' => 1, //通行证
		'User' => 2, //角色
		'UserData' => 3, //角色仓库
		'Bank' => 4, //银行
		'OperationLogs' => 5, //操作日志
		'DataChangeLogs' => 6, //数据变化日志
		'Msg' => 7, //消息库
		'StageProperty' => 8,
		'Master' => 9,
		'PayLogs' => 15, //支付
	),
	'b780' => array(
		'returnUrl' => 'http://bynotify.game779.com:6012/receive.php"', //支付结果返回地址
		'PostUrl' => 'http://www.b780.com/pay_gate', //提交地址,支付网关
	),
    'debaopay' => array(
        'merchantcode' => '200011002004', //支付结果返回地址
        'PostUrl' => 'https://api.yuanruic.com/gateway/api/h5apipay', //提交地址,支付网关
    ),
    'efpay' => array(
        'merchantcode' => '100002006001', //支付结果返回地址
        'PostUrl' => 'https://api.efubill.com/gateway/api/h5apipay', //提交地址,支付网关
    ),
	'Session' => array('SessionLoginName' => 'GameFiveLoginS', 'SessionData' => 'GameFiveData'), //Session名字
	'SessionInfo' => array('RoleID' => 'RoleID', //角色ID
		'Auth' => 'Auth', //随机数
		'MachineSerial' => 'MachineSerial', //机器码
		'Counter' => 'Counter', //计数器
		'ChkCode' => 'GameFiveChkCode', //验证码
	),
	'Discount' => array(5001 => 0.9, 5004 => 0.9, 5002 => 0.85, 5003 => 0.85, 1001 => 0.75, 2001 => 0.82, 2003 => 0.78, 2002 => 0.8, 2004 => 0.75,
		2006 => 0.75, 2007 => 0.75, 2008 => 0.75, 2009 => 0.75, 9999 => 1), //打折，其中9999是网银折扣
	'VipDays' => 31, //黄钻天数
	'VipPrice' => 30,
	'Rebate' => array('b780' => 0.6), //充值折扣
	'IosGoodsPrice' => array(
		'com.ddgame.gold12' => 12,
		'com.ddgame.gold25' => 25,
		'com.ddgame.gold50' => 50,
		'com.ddgame.gold88' => 88,
		'com.ddgame.gold168' => 168,
		'com.ddgame.gold288' => 288,
		'com.ddgame.gold388' => 388,
		'com.ddgame.gold488' => 488,
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

    //万通支付
    'wtpay' => array(
        'merid' => '999201904170455101',
        'key' => '5be782a110a62f6fc3abc242b76033e9',
        'orderurl' => 'http://wttrade.wt668899.com/online/payEntry.do',
        'queryurl' => 'http://wtquery.wt668899.com/online/doQuery.do'
    )

);
?>