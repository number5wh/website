<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOMToAS.php';
include_once ROOT_PATH.'Common/SePtlASToOM.php';
include_once ROOT_PATH.'Common/SePtlDCToOM.php';
include_once ROOT_PATH.'Common/SePtlOMToDC.php';

/*echo "<br />DSAddRoleMonery:<br />";
$socket = ConnectServer(DC_SERVER_IP, DC_SERVERPORT);
SendMDAddRoleMonery($socket, 60003, 100000);
$out_data = ReadData($socket);
$out_array = ProcessDMRoleOperateAckRes($out_data);

echo "<br />���������ķ��أ�<br />";
print_r($out_array);*/

/**
 * 补发欢乐豆
 * @param $RoleID
 * @param $Monery
 * @return array
 */
function DSAddRoleMonery($RoleID,$Monery){
    $socket = getSocketInstance();
    SendMDAddRoleMonery($socket, $RoleID, $Monery);
    $out_data = $socket->response();
    $out_array = ProcessDMRoleOperateAckRes($out_data);
    return $out_array;
}