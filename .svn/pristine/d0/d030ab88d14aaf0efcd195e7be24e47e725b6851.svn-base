<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOMToAS.php';
include_once ROOT_PATH.'Common/SePtlASToOM.php';
include_once ROOT_PATH.'Common/SePtlDCToOM.php';
include_once ROOT_PATH.'Common/SePtlOMToDC.php';



/*echo "<br />DCReloadGameData:<br />";
$socket = ConnectServer(DC_SERVER_IP, DC_SERVERPORT);
SendMDReloadGameData($socket, 0);
$out_data = ReadData($socket);
$out_array = ProcessDMRoleOperateAckRes($out_data);

echo "<br />���������ķ��أ�<br />";
print_r($out_array);*/

function DCReloadGameData($iLoadType){
    $socket = getSocketInstance('DC');
    SendMDReloadGameData($socket,$iLoadType);
    $out_data = $socket->response();
    $out_array = ProcessDMRoleOperateAckRes($out_data);
    return $out_array;
}