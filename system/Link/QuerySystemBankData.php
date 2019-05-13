<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOMToAS.php';
include_once ROOT_PATH.'Common/SePtlASToOM.php';
include_once ROOT_PATH.'Common/SePtlDCToOM.php';
include_once ROOT_PATH.'Common/SePtlOMToDC.php';



/* echo "<br />DCQuerySystemBankData:<br />";
$socket = ConnectServer(DC_SERVER_IP, DC_SERVERPORT);
SendMDQuerySystemBankData($socket, 0);
$out_data = ReadData($socket);
$out_array = ProcessDMQuerySystemBankDataRes($out_data);

echo "<br />���������ķ��أ�<br />";
print_r($out_array); */
function DCQuerySystemBankData(){
    $socket = getSocketInstance('DC');
    SendMDQuerySystemBankData($socket, 0);
    $out_data = $socket->response();
    $out_array = ProcessDMQuerySystemBankDataRes($out_data);
    return $out_array;
}
