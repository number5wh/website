<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlDCToOM.php';
include_once ROOT_PATH.'Common/SePtlOMToDC.php';


function DCSysBankDeal($FromBankAccID,$ToBankAccID,$DealMoney){
    $socket = getSocketInstance('DC');
    SendMDSysBankDeal($socket, $FromBankAccID,$ToBankAccID,$DealMoney);
    $out_data = $socket->response();
    $out_array =ProcessDMSysBankDealAckRes($out_data);

    return $out_array;
}
