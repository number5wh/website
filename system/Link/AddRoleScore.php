<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOMToAS.php';
include_once ROOT_PATH.'Common/SePtlASToOM.php';
include_once ROOT_PATH.'Common/SePtlDCToOM.php';
include_once ROOT_PATH.'Common/SePtlOMToDC.php';

/*echo "<br />DSAddRoleScore:<br />";
$socket = ConnectServer(DC_SERVER_IP, DC_SERVERPORT);
//SendMDAddRoleScore($socket, 60003, 1020, 1, 100000);
SendMDAddRoleScore($socket, 60003, 1020, 0, 10000);
$out_data = ReadData($socket);
$out_array = ProcessDMRoleOperateAckRes($out_data);

echo "<br />���������ķ��أ�<br />";
print_r($out_array);*/

function DSAddRoleScore($RoleID,$KindID,$Status,$Score){
    $socket = getSocketInstance();
    SendMDAddRoleScore($socket,$RoleID,$KindID,$Status,$Score);
    $out_data = ReadData($socket);
    $out_array = ProcessDMRoleOperateAckRes($out_data);
    return $out_array;
}


