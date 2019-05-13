<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOWToAS.php';
include_once ROOT_PATH.'Common/SePtlASToOW.php';



function ASCheckAccount($username){
    $socket = getSocketInstance('AS');
    SendWACheckAccount($socket,$username);
    $out_data = $socket->response();
    $out_array = ProcessAWOperateAckRes($out_data);
    return $out_array;
}
/*echo "<br />ASCheckAccount:<br />";
$socket = ConnectServer(AS_SERVER_IP, AS_SERVERPORT);
SendWACheckAccount($socket, "zzqt002");
$out_data = ReadData($socket);
$out_array = ProcessAWOperateAckRes($out_data);

echo "<br />����ʺŷ��������أ�<br />";
print_r($out_array);*/