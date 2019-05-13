<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOWToAS.php';
include_once ROOT_PATH.'Common/SePtlASToOW.php';


/* 
echo "<br />ASGetAccountInfo:<br />";
$socket = ConnectServer(AS_SERVER_IP, AS_SERVERPORT);
SendWAGetAccountInfoByID($socket, 6000000);
$out_data = ReadData($socket);
$out_array = ProcessAWGetAccountInfoRes($out_data);

echo "<br />����ʺŷ��������أ�<br />";
print_r($out_array); */
function ASGetAccountInfoByID($LoginID){
    $socket = getSocketInstance('AS');
    SendWAGetAccountInfoByID($socket,$LoginID);
    $out_data = $socket->response();
    $out_array = ProcessAWGetAccountInfoRes($out_data);
    return $out_array;
}