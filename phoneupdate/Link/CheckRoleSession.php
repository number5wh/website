<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOWToDC.php';
include_once ROOT_PATH.'Common/SePtlDCToOW.php';

//检验验证码
function DCCheckRoleSession($LoginCode,$Code){
    $socket = getSocketInstance('DC');
    SendWDCheckRoleSession($socket,$LoginCode,$Code);
    $out_data = $socket->response();
    $out_array = ProcessDWOperateAckRes($out_data);
    return $out_array;
}