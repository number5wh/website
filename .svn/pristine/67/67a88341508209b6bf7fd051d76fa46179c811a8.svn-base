<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOMToAS.php';
include_once ROOT_PATH.'Common/SePtlASToOM.php';
include_once ROOT_PATH.'Common/SePtlDCToOM.php';
include_once ROOT_PATH.'Common/SePtlOMToDC.php';


/*echo "<br />DSSavingRoleMonery:<br />";
$socket = ConnectServer(DC_SERVER_IP, DC_SERVERPORT);
$gameArr = array(array("iKindID"=>"1010","iMonery"=>"20"), array("iKindID"=>"4060","iMonery"=>"300"));
SendMDSavingRoleMonery($socket, 60000, 2, $gameArr);
$out_data = ReadData($socket);
$out_array = ProcessDMRoleOperateAckRes($out_data);

echo "<br />输出数据中心返回：<br />";
print_r($out_array);*/

function DSSavingRoleMonery($RoleID,$gameArr){
    $socket = getSocketInstance();
    SendMDSavingRoleMonery($socket,$RoleID,count($gameArr),$gameArr);
    $out_data = $socket->response();

    $out_array = ProcessDMRoleOperateAckRes($out_data);
    return $out_array;
}

