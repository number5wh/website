<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOWToDC.php';
include_once ROOT_PATH.'Common/SePtlDCToOW.php';


function DCQueryRechargeCardState($CardNo){
    $socket = getSocketInstance('DC');
    SendWDQueryRechargeCardState($socket, $CardNo); 
    $out_data = $socket->response();
    $out_array = ProcessDWQueryRechargeCardRes($out_data);
    return $out_array;
}