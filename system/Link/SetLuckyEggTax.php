<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOMToAS.php';
include_once ROOT_PATH.'Common/SePtlASToOM.php';
include_once ROOT_PATH.'Common/SePtlDCToOM.php';
include_once ROOT_PATH.'Common/SePtlOMToDC.php';



function DCSetLuckyEggTax($RoomID,$LuckyEggTax){
    $socket = getSocketInstance('DC');

    SendMDSetLuckyEggTax($socket,$RoomID,$LuckyEggTax);
    $out_data = $socket->response();
    $out_array = ProcessDMRoleOperateAckRes($out_data);
    return $out_array;
}
/*echo "<br />DCSetLuckyEggTax:<br />";
$socket = ConnectServer(DC_SERVER_IP, DC_SERVERPORT);
SendMDSetLuckyEggTax($socket, 13, 0);
$out_data = ReadData($socket);
$out_array = ProcessDMRoleOperateAckRes($out_data);

echo "<br />���������ķ��أ�<br />";
print_r($out_array);*/