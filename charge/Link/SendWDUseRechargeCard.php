<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOWToDC.php';
include_once ROOT_PATH.'Common/SePtlDCToOW.php';


function DCSendWDUseRechargeCardCardState($CardNo, $CardPass, $RoleID){
    $socket = getSocketInstance('DC');
    SendWDUseRechargeCard($socket, $CardNo, $CardPass, $RoleID); 
    $out_data = $socket->response();
    $out_array =  ProcessDWOperateAckRes($out_data);
    return $out_array;
}