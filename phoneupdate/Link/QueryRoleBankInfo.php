<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOWToDC.php';
include_once ROOT_PATH.'Common/SePtlDCToOW.php';


function DSQueryRoleBankInfo($RoleID){
    $socket = getSocketInstance('DC');
    SendMDQueryRoleBankInfo($socket,$RoleID);
    $out_data = $socket->response();
    $out_array = ProcessDMQueryRoleBankInfoRes($out_data);

    return $out_array;
}

