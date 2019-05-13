<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOMToAS.php';
include_once ROOT_PATH.'Common/SePtlASToOM.php';
include_once ROOT_PATH.'Common/SePtlDCToOM.php';
include_once ROOT_PATH.'Common/SePtlOMToDC.php';



/*echo "<br />DCSetSuperPlayer:<br />";
$socket = ConnectServer(DC_SERVER_IP, DC_SERVERPORT);
SendMDSetSuperPlayer($socket, 60001, 150);
$out_data = ReadData($socket);
$out_array = ProcessDMRoleOperateAckRes($out_data);

echo "<br />���������ķ��أ�<br />";
print_r($out_array);*/


function DCSetSuperPlayer($iRoleID,$iSuperLevel){
    $socket = getSocketInstance('DC');
    SendMDSetSuperPlayer($socket,$iRoleID,$iSuperLevel);
    $out_data = $socket->response();
    $out_array = ProcessDMRoleOperateAckRes($out_data);
    return $out_array;
}