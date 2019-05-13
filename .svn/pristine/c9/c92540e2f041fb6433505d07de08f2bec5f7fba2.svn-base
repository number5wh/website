<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOMToAS.php';
include_once ROOT_PATH.'Common/SePtlASToOM.php';
include_once ROOT_PATH.'Common/SePtlDCToOM.php';
include_once ROOT_PATH.'Common/SePtlOMToDC.php';

/*echo "<br />DSBuyRoleVip:<br />";
$socket = ConnectServer(DC_SERVER_IP, DC_SERVERPORT);
SendMDBuyRoleVip($socket, 60006, 3);
$out_data = ReadData($socket);
$out_array = ProcessDMRoleOperateAckRes($out_data);

echo "<br />���������ķ��أ�<br />";
print_r($out_array);*/

/**
 * 补发蓝钻
 * @return array
 */
function DSBuyRoleVip($roleID,$iDays){
    $socket = getSocketInstance();
    SendMDBuyRoleVip($socket, $roleID,$iDays );
    $out_data = $socket->response();
    $out_array = ProcessDMRoleOperateAckRes($out_data);
    return $out_array;
}