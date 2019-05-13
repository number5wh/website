<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlDCToOM.php';
include_once ROOT_PATH.'Common/SePtlOMToDC.php';


//清理玩家数据
//UINT32		iRoleID;				//角色ID
function DCClearRoleData($iRoleID){
    $socket = getSocketInstance('DC');
    SendMDClearRoleData($socket,$iRoleID);
    $out_data = $socket->response();
    $out_array = ProcessDMRoleOperateAckRes($out_data);
    return $out_array;
}
/* 
echo "<br />DSClearRoleData:<br />";
$socket = ConnectServer(DC_SERVER_IP, DC_SERVERPORT);
SendMDClearRoleData($socket, 60000);
$out_data = ReadData($socket);
$out_array = ProcessDMRoleOperateAckRes($out_data);

echo "<br />���������ķ��أ�<br />";
print_r($out_array);
 */

