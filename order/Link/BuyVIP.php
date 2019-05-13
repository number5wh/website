<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOWToAS.php';
include_once ROOT_PATH.'Common/SePtlASToOW.php';
include_once ROOT_PATH.'Common/SePtlOWToDC.php';
include_once ROOT_PATH.'Common/SePtlDCToOW.php';


/*echo "<br />DCBuyVIP:<br />";
$socket = ConnectServer(DC_SERVER_IP, DC_SERVERPORT);
SendWDBuyVIP($socket, 60001, 31);
$out_data = ReadData($socket);
$out_array = ProcessDWOperateAckRes($out_data);

echo "<br />���������ķ��أ�<br />";
print_r($out_array);*/
/**
 * 购买vip服务
 * @param $loginID
 * @param $days
 * @return array
 */
function DCBuyVIP($loginID,$days){
    $socket = getSocketInstance('DC');
    SendWDBuyVIP($socket,$loginID,$days);
    $out_data = $socket->response();
    $out_array = ProcessDWOperateAckRes($out_data);
    if(!isset($out_array['iResult']))
        $out_array['iResult'] = -1;
    return $out_array;
}