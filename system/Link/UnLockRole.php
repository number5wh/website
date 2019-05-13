<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOMToAS.php';
include_once ROOT_PATH.'Common/SePtlASToOM.php';
include_once ROOT_PATH.'Common/SePtlDCToOM.php';
include_once ROOT_PATH.'Common/SePtlOMToDC.php';


/*echo "<br />ASUnLockRole:<br />";
$socket = ConnectServer(AS_SERVER_IP, AS_SERVERPORT);
SendMAUnLockRole($socket, 60025);
$out_data = ReadData($socket);
$out_array = ProcessAMRoleOperateAckRes($out_data);

echo "<br />����ʺŷ��������أ�<br />";
print_r($out_array);*/

function ASUnLockRole($RoleID){
    $socket = getSocketInstance('AS');
    SendMAUnLockRole($socket,$RoleID);
    $out_data = $socket->response();
    $out_array = ProcessAMRoleOperateAckRes($out_data);
    return $out_array;
}