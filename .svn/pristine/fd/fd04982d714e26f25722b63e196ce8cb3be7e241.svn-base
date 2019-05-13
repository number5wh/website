<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOMToAS.php';
include_once ROOT_PATH.'Common/SePtlASToOM.php';
include_once ROOT_PATH.'Common/SePtlDCToOM.php';
include_once ROOT_PATH.'Common/SePtlOMToDC.php';



/*echo "<br />DCQueryRoomRobotInfo:<br />";
$socket = ConnectServer(DC_SERVER_IP, DC_SERVERPORT);
SendMDQueryRoomRobotInfo($socket, 1, 3);
$out_data = ReadData($socket);
$out_array = ProcessDMQueryRoomRobotInfoRes($out_data);

echo "<br />���������ķ��أ�<br />";
print_r($out_array);*/

function DCQueryRoomOnlinePlayersRes($iRoomID,$curPage,$pageSize){
    $socket = getSocketInstance('DC');

    SendMDQueryRoomOnlinePlayers($socket,$iRoomID,$curPage,$pageSize);
    $out_data = $socket->response();
    $out_array = ProcessDMQueryRoomOnlinePlayersRes($out_data);
    return $out_array;
}

function getRoomOnlinePlayers($iRoomID,$curPage,$pageSize){
    $list = DCQueryRoomOnlinePlayersRes($iRoomID,$curPage,$pageSize);

    $keyMap = array("iUserID"=>"RoleID","szUsername"=>"LoginName","szIP"=>"IP","iCurGold"=>"CurGold"
    ,"iTotalGold"=>"TotalGold","iCurScore"=>"CurScore","iTotalScore"=>"TotalScore","szMachineSerial"
    =>"MachineSerial","iBankGold"=>"BankGold","szRegIP"=>"szRegIP","szMobile"=>"szMobile","szIdCard"=>"szIdCard");
    Utility::arrListReplaceKey($list['RoleInfoList'],$keyMap);
    //var_dump($list);
    return $list;
}