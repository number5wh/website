<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOMToAS.php';
include_once ROOT_PATH.'Common/SePtlASToOM.php';
include_once ROOT_PATH.'Common/SePtlDCToOM.php';
include_once ROOT_PATH.'Common/SePtlOMToDC.php';



/*echo "<br />ASLockRole:<br />";
$socket = ConnectServer(AS_SERVER_IP, AS_SERVERPORT);
SendMALockRole($socket, 60023, 2);
$out_data = ReadData($socket);
$out_array = ProcessAMRoleOperateAckRes($out_data);

echo "<br />输出帐号服务器返回：<br />";
print_r($out_array);*/

function ASLockRole($RoleID,$Days){
    $socket = getSocketInstance('AS');
    SendMALockRole($socket, $RoleID,$Days);
    $out_data = $socket->response();
    $out_array = ProcessAMRoleOperateAckRes($out_data);
    return $out_array;
}
