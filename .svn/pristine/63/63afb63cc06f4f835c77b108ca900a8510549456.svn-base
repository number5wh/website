<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOMToAS.php';
include_once ROOT_PATH.'Common/SePtlASToOM.php';
include_once ROOT_PATH.'Common/SePtlDCToOM.php';
include_once ROOT_PATH.'Common/SePtlOMToDC.php';

function DSResetRoleBankPwd($RoleID,$szNewPwd){
    $socket = getSocketInstance('DC');
    SendMDResetRoleBankPwd($socket,$RoleID,$szNewPwd);
    $out_data = $socket->response();
    $out_array = ProcessDMRoleOperateAckRes($out_data);
    return $out_array;
}

/*echo "<br />DSResetRoleBankPwd:<br />";
$socket = ConnectServer(DC_SERVER_IP, DC_SERVERPORT);
SendMDResetRoleBankPwd($socket, 60002, "c33367701511b4f6020ec61ded352059");
$out_data = ReadData($socket);
$out_array = ProcessDMRoleOperateAckRes($out_data);

echo "<br />输出数据中心返回：<br />";
print_r($out_array);*/


