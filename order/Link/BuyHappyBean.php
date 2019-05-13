<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOWToAS.php';
include_once ROOT_PATH.'Common/SePtlASToOW.php';
include_once ROOT_PATH.'Common/SePtlOWToDC.php';
include_once ROOT_PATH.'Common/SePtlDCToOW.php';



/*echo "<br />DCBuyHappybean:<br />";
$socket = ConnectServer(DC_SERVER_IP, DC_SERVERPORT);
SendWDBuyHappybean($socket, 60000, 10000);
$out_data = ReadData($socket);
$out_array = ProcessDWOperateAckRes($out_data);

echo "<br />���������ķ��أ�<br />";
print_r($out_array);*/

/**
 * 购买欢乐豆
 * @param $loginID
 * @param $RMBMoney
 * @return array
 */
function DCBuyHappyBean($loginID,$RMBMoney,$CardID){
    $socket = getSocketInstance('DC');
    SendWDBuyHappyBean($socket,$loginID,$RMBMoney,$CardID);
    $out_data = $socket->response();
    $out_array = ProcessDWOperateAckRes($out_data);
    if(!isset($out_array['iResult'])){
        $log_path = dirname($_SERVER["DOCUMENT_ROOT"])."/logs/charge_error/";
        if(!file_exists($log_path))
            mkdir($log_path,0777,true);
        $log_name = date('Y-m-d').".txt";
        error_log(date('Y-m-d H:i:s',time()).' error_info : '.json_encode($out_array)."\r\n", 3, $log_path.$log_name);
        $out_array['iResult'] = -1;
    }
    return $out_array;
}