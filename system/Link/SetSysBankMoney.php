<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlDCToOM.php';
include_once ROOT_PATH.'Common/SePtlOMToDC.php';


function DCSetSysBankMoney($BankAccID,$BankMoney){
    $socket = getSocketInstance('DC');
    SendMDAddSysBankMoney($socket, $BankAccID, $BankMoney);
    $out_data = $socket->response();
    $out_array =  ProcessDMSysBankOperateAckRes($out_data);

    return $out_array;
}
