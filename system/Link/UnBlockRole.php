<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOMToAS.php';
include_once ROOT_PATH.'Common/SePtlASToOM.php';
include_once ROOT_PATH.'Common/SePtlDCToOM.php';
include_once ROOT_PATH.'Common/SePtlOMToDC.php';

/*echo "<br />ASUnBlockRole:<br />";
$socket = ConnectServer(AS_SERVER_IP, AS_SERVERPORT);
SendMAUnBlockRole($socket, 60024);
$out_data = ReadData($socket);
$out_array = ProcessAMRoleOperateAckRes($out_data);

echo "<br />����ʺŷ��������أ�<br />";
print_r($out_array);*/

function ASUnBlockRole($RoleID){
    $socket = getSocketInstance('AS');
    SendMAUnBlockRole($socket,$RoleID);
    $out_data = $socket->response();
    $out_array = ProcessAMRoleOperateAckRes($out_data);

    return $out_array;
}