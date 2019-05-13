<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOMToAS.php';
include_once ROOT_PATH.'Common/SePtlASToOM.php';
include_once ROOT_PATH.'Common/SePtlDCToOM.php';
include_once ROOT_PATH.'Common/SePtlOMToDC.php';



/*echo "<br />ASUpdateUserAccountInfo:<br />";
$socket = ConnectServer(AS_SERVER_IP, AS_SERVERPORT);
//SendMAUpdateUserAccountInfo($socket, 60022, SURAT_PLAYER_NAME, '¬��Ԩ');
//SendMAUpdateUserAccountInfo($socket, 60021, SURAT_PLAYER_ID_CARD, '330481198909203217');
SendMAUpdateUserAccountInfo($socket, 60000, SURAT_PLAYER_PHONE, '15067460796');
$out_data = ReadData($socket);
$out_array = ProcessAMRoleOperateAckRes($out_data);

echo "<br />����ʺŷ��������أ�<br />";
print_r($out_array);*/

function ASUpdateUserAccountInfo($RoleID,$code,$param){
    $socket = getSocketInstance('AS');
    SendMAUpdateUserAccountInfo($socket,$RoleID,$code,$param);
    $out_data = $socket->response();
    $out_array = ProcessAMRoleOperateAckRes($out_data);
    return $out_array;
}