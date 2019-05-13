<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlDCToOM.php';
include_once ROOT_PATH.'Common/SePtlOMToDC.php';

/**
 * 设置银行转账微信绑定
 * 
 * */
function DCSetBankWeChatCheck($LoginID,$value){
    $socket = getSocketInstance('DC');
    SendMDSetBankWeChatCheck($socket,$LoginID,$value);
    $out_data = $socket->response();
    $out_array =ProcessDMRoleOperateAckRes($out_data);

    return $out_array;
}