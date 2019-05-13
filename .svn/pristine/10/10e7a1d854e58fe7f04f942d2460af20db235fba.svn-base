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

function DCQueryRoomRobotInfo($curPage,$pageSize){
    $socket = getSocketInstance('DC');
    SendMDQueryRoomRobotInfo($socket,$curPage,$pageSize);
    $out_data = $socket->response();
    $out_array = ProcessDMQueryRoomRobotInfoRes($out_data);
    return $out_array;
}

function getRoomRobotInfo($curPage,$pageSize){
    $arrResult = DCQueryRoomRobotInfo($curPage,$pageSize);



    $keyMap = array("iRoomID"=>"RoomID","iOnLineCount"=>"OnLineCount","iRobotCount"=>"RobotCount"
                    ,"iUpdateTime"=>"UpdateTime","iRobotWinMoney"=>"RobotWinMoney");
    if(is_array($arrResult) && $arrResult['iRoomCount'] > 0)
        Utility::arrListReplaceKey($arrResult['RoomRobotInfoList'],$keyMap);
    return $arrResult;
}