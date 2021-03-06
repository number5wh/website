<?php
//这个文件定义了消息包头，基本不用动

//服务器编号
define('REQ_HOTAL', 1); //大厅
define('REQ_ROOM', 2); //房间
define('REQ_DC', 3); //数据中心
define('REQ_OW', 4); //官网
define('REQ_OM', 5); //运维平台
define('REQ_AI', 6); //通行证认证
define('REQ_WEB', 7); //Web认证
define('REQ_LOGON', 8); //登录服务器
define('REQ_BANK', 9); //银行服务器
define('REQ_LOG', 10); //日志服务器
define('REQ_ORDER', 11); //订单服务器

define('COMM_HEAD_LEN', 12); //消息头长度

function MakeSendHead($type, $len, $ret = 0, $src = REQ_OM, $dest = REQ_DC) {
	$symbol = 0x55; // 0x55为标志 十进制8'5
	//$src = REQ_OM;		// 从哪里来
	//$dest = REQ_DC;		// 到哪里去
	//$ret = 0;		// 返回标志、路径等
	//$type = CMD_MD_TEST_REQ;		// 请求类型
	//$len = 0;		// 数据长度
	$total = 0; // 总报文数
	$number = 0; // 当前编号

	return pack('C4S4', $symbol, $src, $dest, $ret, $type, $len, $total, $number);
}

//帐号服务器地址
define('AS_SERVER_IP', '172.18.190.173');
define('AS_SERVERPORT', 18900);

//数据中心地址
define('DC_SERVER_IP', '172.18.190.172');
define('DC_SERVERPORT', 18600);

//订单服务器地址
define('OS_SERVER_IP', '172.18.190.172');
define('OS_SERVERPORT', 18800);

function getSocketInstance($str = 'DC') {
//获取两个MySocket对象
	require_once 'SvrPtl/MySocket.class.php';
	if ($str == 'DC') {
		// if(!($_SESSION['dcSocket'] instanceof MySocket )){
		$_SESSION['dcSocket'] = new MySocket(DC_SERVER_IP, DC_SERVERPORT);
		// }
		return $_SESSION['dcSocket'];
	} else if ($str == 'AS') {
		//if(!($_SESSION['asSocket'] instanceof MySocket )){
		$_SESSION['asSocket'] = new MySocket(AS_SERVER_IP, AS_SERVERPORT);
		//}
		return $_SESSION['asSocket'];
	} else {
		$_SESSION['osSocket'] = new MySocket(OS_SERVER_IP, OS_SERVERPORT);
		//}
		return $_SESSION['osSocket'];
	}
}
function MakeINT64Value($ValueH32, $ValueL32) {
	$iTempL32 = (float) sprintf('%u', ($ValueL32 & 0xFFFFFFFF));
	$iTempI64 = ($ValueH32 * pow(2, 32)) + $iTempL32;

	return $iTempI64;
}
function fitStr(&$str) {
	//$str = substr($str,0,strpos($str,0));//取到0结束
	$str = trim($str);
	$str = iconv('GBK', 'UTF-8', $str);
}

//xml转数组s
function xmlToArray($xml)
{
    libxml_disable_entity_loader(true);
    //将XML转为array
    $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    return $array_data;
}


