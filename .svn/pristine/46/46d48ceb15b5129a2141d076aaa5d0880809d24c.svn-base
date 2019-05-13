<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlDCToOM.php';
include_once ROOT_PATH.'Common/SePtlOMToDC.php';

//锁定红钻
//UINT32		iRoleID;				//角色ID
//UINT32		iDays;					//会员天数
function DCLockRoleVip( $iRoleID, $iDays){
    $socket = getSocketInstance('DC');
    SendMDLockRoleVip($socket, $iRoleID, $iDays);
    $out_data = $socket->response();
    $out_array = ProcessDMRoleOperateAckRes($out_data);
    return $out_array;
}

/* echo "<br />DSBuyRoleVip:<br />";
$socket = ConnectServer(DC_SERVER_IP, DC_SERVERPORT);
SendMDLockRoleVip($socket, 60000, 3);
$out_data = ReadData($socket);
$out_array = ProcessDMRoleOperateAckRes($out_data);

echo "<br />���������ķ��أ�<br />";
print_r($out_array);
 */

