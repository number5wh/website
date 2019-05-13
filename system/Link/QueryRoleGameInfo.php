<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOMToAS.php';
include_once ROOT_PATH.'Common/SePtlASToOM.php';
include_once ROOT_PATH.'Common/SePtlDCToOM.php';
include_once ROOT_PATH.'Common/SePtlOMToDC.php';


function DSQueryRoleGameInfo($RoleID,$page=1,$num=10){
    $socket = getSocketInstance('DC');
    SendMDQueryRoleGameInfo($socket,$RoleID,$page,$num);
    $out_data = $socket->response();
    $out_array = ProcessDMQueryRoleGameInfoRes($out_data);
    return $out_array;
}
/*echo "<br />DSQueryRoleGameInfo:<br />";
$socket = ConnectServer(DC_SERVER_IP, DC_SERVERPORT);
SendMDQueryRoleGameInfo($socket, 60000, 1, 10);
$out_data = ReadData($socket);
$out_array = ProcessDMQueryRoleGameInfoRes($out_data);

echo "<br />输出数据中心返回：<br />";
print_r($out_array);*/


/**
 * @param $type 1:积分游戏 2:欢乐豆游戏
 */
function getRoleGameInfoByType($RoleID,$type){
    $totalGameNum = 18;//全部游戏最大数量
    $ret = DSQueryRoleGameInfo($RoleID,1,$totalGameNum);
    $room = $ret['RoomInfoList'];
    $i = 0;
    $arrResult = array();
    foreach($room as $key => $vo){
        if($vo['iRoomType'] === $type)$arrResult[$i++] = $vo;
    }
    return $arrResult;
}
function getRoleGameInfo($RoleID){
    $keyMap = array("iKindID"=>"KindID","iRoleID"=>"RoleID","iRoomType"=>"RoomType","iWinCount"=>"WinCount","iLostCount"=>"LostCount",
        "iDrawCount"=>"DrawCount","iFleeCount"=>"FleeCount","iTotalMoney"=>"TotalMoney","iTotalScore"=>"TotalScore",
        "iScore"=>"Score","iMoney"=>"Money","iWin"=>"Win","iFlee"=>"Flee","iTotalPage"=>"TotalPage","iCurPage"=>"CurPage","iGameCount"=>"GameCount",
    );
    $ret = DSQueryRoleGameInfo($RoleID,1,16);
    Utility::arrListReplaceKey($ret['RoomInfoList'],$keyMap);
    return $ret['RoomInfoList'];
}