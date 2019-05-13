<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlDCToOM.php';
include_once ROOT_PATH.'Common/SePtlOMToDC.php';



function DCQueryRoleTotalMoney(){
    $socket = getSocketInstance('DC');
    //$socket = ConnectServer(DC_SERVER_IP, DC_SERVERPORT);
    SendMDQueryRoleTotalMoney($socket, 0);
    $out_data = $socket->response();
    $out_array = ProcessDMQueryRoleTotalMoneyRes($out_data);
    return $out_array;
}/*
echo "<br />DCQueryRoleTotalMoney:<br />";
$socket = ConnectServer(DC_SERVER_IP, DC_SERVERPORT);
SendMDQueryRoleTotalMoney($socket, 0);
$out_data = ReadData($socket);
$out_array = ProcessDMQueryRoleTotalMoneyRes($out_data);

echo "<br />输出数据中心返回：<br />";
print_r($out_array);*/

function getRoleTotalMoney(){
    $TotalMoneyInfo = DCQueryRoleTotalMoney();
    //var_dump($userBankInfo);
    $keyMap = array("iRoleBankTotalMoney"=>"TotalBankMoney","iRoleGameTotalMoney"=>"TotalGameMoney");
    $TotalMoneyInfo = Utility::arrReplaceKey($TotalMoneyInfo,$keyMap);
    return $TotalMoneyInfo;
}