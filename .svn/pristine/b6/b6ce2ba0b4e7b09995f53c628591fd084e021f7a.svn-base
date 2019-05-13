<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlDCToOM.php';
include_once ROOT_PATH.'Common/SePtlOMToDC.php';


//解锁玩家银行
function DCUnLockUserBank($iRoleID){
    $socket = getSocketInstance('DC');
    SendMDUnLockUserBank($socket,$iRoleID);
    $out_data = $socket->response();
    $out_array = ProcessDMRoleOperateAckRes($out_data);
    return $out_array;
}
/* 
echo "<br />DCMDUnLockUserBank:<br />";
$socket = ConnectServer(DC_SERVER_IP, DC_SERVERPORT);
SendMDUnLockUserBank($socket, 60000);
$out_data = ReadData($socket);
$out_array = ProcessDMRoleOperateAckRes($out_data);

echo "<br />���������ķ��أ�<br />";
print_r($out_array);

 */
