<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOMToAS.php';
include_once ROOT_PATH.'Common/SePtlASToOM.php';
include_once ROOT_PATH.'Common/SePtlDCToOM.php';
include_once ROOT_PATH.'Common/SePtlOMToDC.php';


function DSQueryRoleRight($RoleID){
    $socket = getSocketInstance('DC');
    SendMDQueryRoleRight($socket,$RoleID);
    $out_data = $socket->response();
    $out_array =  ProcessDMRoleRightAckRes($out_data);

    return $out_array;
}/*
echo "<br />DSQueryRoleBankInfo:<br />";
$socket = ConnectServer(DC_SERVER_IP, DC_SERVERPORT);
SendMDQueryRoleBankInfo($socket, 60001);
$out_data = ReadData($socket);
$out_array = ProcessDMQueryRoleBankInfoRes($out_data);

echo "<br />输出数据中心返回：<br />";
print_r($out_array);*/
