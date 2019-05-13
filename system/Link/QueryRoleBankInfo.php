<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOMToAS.php';
include_once ROOT_PATH.'Common/SePtlASToOM.php';
include_once ROOT_PATH.'Common/SePtlDCToOM.php';
include_once ROOT_PATH.'Common/SePtlOMToDC.php';


function DSQueryRoleBankInfo($RoleID){
    Utility::Log("system_error", "DSQueryRoleBankInfo", "RoleID:".$RoleID);
    $socket = getSocketInstance('DC');
    SendMDQueryRoleBankInfo($socket,$RoleID);
    $out_data = $socket->response();
    $out_array = ProcessDMQueryRoleBankInfoRes($out_data);
    Utility::Log("system_error", "DSQueryRoleBankInfo Ret", json_encode($out_array));
    return $out_array;
}

/*
echo "<br />DSQueryRoleBankInfo:<br />";
$socket = ConnectServer(DC_SERVER_IP, DC_SERVERPORT);
SendMDQueryRoleBankInfo($socket, 60001);
$out_data = ReadData($socket);
$out_array = ProcessDMQueryRoleBankInfoRes($out_data);

echo "<br />输出数据中心返回：<br />";
print_r($out_array);*/


function getBankMoney($RoleID){
    $userBankInfo = DSQueryRoleBankInfo($RoleID);
    //var_dump($userBankInfo);
    $keyMap = array("iGameWealth"=>"GameWealth","iFreeze"=>"Freeze","iMoney"=>"Money","iAddTime"=>"AddTime","iFirstRechargeTime"=>"FirstRechargeTime","iTotalTime"=>"TotalTime");
    $userBankInfo = Utility::arrReplaceKey($userBankInfo,$keyMap);
    return $userBankInfo;
}