<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOMToAS.php';
include_once ROOT_PATH.'Common/SePtlASToOM.php';
include_once ROOT_PATH.'Common/SePtlDCToOM.php';
include_once ROOT_PATH.'Common/SePtlOMToDC.php';


/*echo "<br />OMASRegisterAccount:<br />";
$socket = ConnectServer(AS_SERVER_IP, AS_SERVERPORT);
SendMARegisterAccount($socket, "robot3", "e10adc3949ba59abbe56e057f20f883e", "192.168.1.150", "����ǿ",
    "330481198909203217", "15067460796", "358600404");
$out_data = ReadData($socket);
$out_array = ProcessAMRoleOperateAckRes($out_data);

echo "<br />����ʺŷ��������أ�<br />";
print_r($out_array);*/

function OMASRegisterAccount($LoginCode,$Pwd,$IP,$RealName,$ID_CARD_NUMBER,$Mobile,$QQ){
    $socket = getSocketInstance('AS');
    SendMARegisterAccount($socket,$LoginCode,$Pwd,$IP,$RealName,$ID_CARD_NUMBER,$Mobile,$QQ);
    $out_data = $socket->response();
    $out_array = ProcessAMRoleOperateAckRes($out_data);
    return $out_array;
}