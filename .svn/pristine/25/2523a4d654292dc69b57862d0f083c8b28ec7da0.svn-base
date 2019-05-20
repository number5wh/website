<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOWToDC.php';
include_once ROOT_PATH.'Common/SePtlDCToOW.php';

function ASGetRoleBaseInfo($RoleID)
{
	//echo "<br />ASGetRoleBaseInfo:<br />";
	//$socket = ConnectServer(AS_SERVER_IP, AS_SERVERPORT);
    $socket = getSocketInstance('AS');
	SendMAGetRoleBaseInfo($socket, $RoleID);
	$out_data = $socket->response();
	$out_array = ProcessAMGetRoleBaseInfoRes($out_data);

	//echo "<br />输出帐号服务器返回：<br />";
	//print_r($out_array);
    return $out_array;
}

function DSGetRoleBaseInfo($RoleID)
{
	//echo "<br />DSGetRoleBaseInfo:<br />";
	//$socket = ConnectServer(DC_SERVER_IP, DC_SERVERPORT);
    $socket = getSocketInstance('DC');
	SendMDGetRoleBaseInfo($socket, $RoleID);
	$out_data = $socket->response();
	$out_array = ProcessDMGetRoleBaseInfoRes($out_data);

	//echo "<br />输出数据中心返回：<br />";
	//print_r($out_array);
    return $out_array;
}
