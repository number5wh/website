<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOMToAS.php';
include_once ROOT_PATH.'Common/SePtlASToOM.php';
include_once ROOT_PATH.'Common/SePtlDCToOM.php';
include_once ROOT_PATH.'Common/SePtlOMToDC.php';
include_once ROOT_PATH.'Common/SePtlDCToOM.php';


function DSSetUserRate($AccountId,$Ratio,$ControlTimeLong,$ControlTimeInterval){
    $socket = getSocketInstance('DC');
    SendMDCtrolPerson($socket,$AccountId,$Ratio,$ControlTimeLong,$ControlTimeInterval);
    $out_data = $socket->response();
    $out_array =  ProcessDMSetRoomRate($out_data);

    return $out_array;
}



function DSSetRoomRate($RoomId,$Ratio,$InitStorage,$CurrentStorage){
    $socket = getSocketInstance('DC');
    SendMDCtrolRoom($socket,$RoomId,$Ratio,$InitStorage,$CurrentStorage);
    $out_data = $socket->response();
    $out_array =  ProcessDMSetRoomRate($out_data);
    return $out_array;
}


function DSQueryRoom($RoomId){
    $socket = getSocketInstance('DC');
    SendMDQueryRoom($socket,$RoomId);
    $out_data = $socket->response();
    $out_array =  ProcessDMQueryRoomRate($out_data);
    return $out_array;

}


