<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOMToAS.php';
include_once ROOT_PATH.'Common/SePtlASToOM.php';
//设置登录验证方式
//UINT32		iRoleID;				//角色ID
//UINT32		iPassType;				//验证类型

function ASSetPassType($iRoleID,$iPassType){
    $socket = getSocketInstance('AS');
    SendMASetPassType($socket,$iRoleID,$iPassType);
    $out_data = $socket->response();
    $out_array = ProcessAMRoleOperateAckRes($out_data);
    return $out_array;
}
/* echo "<br />ASBlockRole:<br />";
$socket = ConnectServer(AS_SERVER_IP, AS_SERVERPORT);
SendMASetPassType($socket, 60000, 0);
$out_data = ReadData($socket);
$out_array = ProcessAMRoleOperateAckRes($out_data);

echo "<br />����ʺŷ��������أ�<br />";
print_r($out_array); */