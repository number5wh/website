<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOMToAS.php';
include_once ROOT_PATH.'Common/SePtlASToOM.php';
include_once ROOT_PATH.'Common/SePtlDCToOM.php';
include_once ROOT_PATH.'Common/SePtlOMToDC.php';


/*echo "<br />DCQueryOnlinePlayer:<br />";
$socket = ConnectServer(DC_SERVER_IP, DC_SERVERPORT);
SendMDQueryOnlinePlayer($socket);
$out_data = ReadData($socket);
$out_array = ProcessDMQueryOnlinePlayerRes($out_data);

echo "<br />���������ķ��أ�<br />";
print_r($out_array);*/

function DCQueryOnlinePlayer(){
    $socket = getSocketInstance('DC');
    SendMDQueryOnlinePlayer($socket);
    $out_data = $socket->response();

    $out_array = ProcessDMQueryOnlinePlayerRes($out_data);
    return $out_array;
}