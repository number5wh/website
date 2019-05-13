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


print_r($out_array);*/

function DCQueryAllOnlinePlayer($currpage,$pagesize){
    $socket = getSocketInstance('DC');
    //SendMDQueryOnlinePlayers($socket,$currpage,$pagesize);
    SendMDQueryOnlinePlayers($socket);
    $out_data = $socket->response();
    //print_r($out_data);
    $out_array = ProcessDMQueryAllOnlinePlayerRes($out_data);
    return $out_array;
}