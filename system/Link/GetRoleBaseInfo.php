<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOMToAS.php';
include_once ROOT_PATH.'Common/SePtlASToOM.php';
include_once ROOT_PATH.'Common/SePtlDCToOM.php';
include_once ROOT_PATH.'Common/SePtlOMToDC.php';

function ASGetRoleBaseInfo($RoleID)
{
    Utility::Log("system_error", "ASGetRoleBaseInfo", "RoleID:".$RoleID);

	//echo "<br />ASGetRoleBaseInfo:<br />";
	//$socket = ConnectServer(AS_SERVER_IP, AS_SERVERPORT);
    $socket = getSocketInstance('AS');
	SendMAGetRoleBaseInfo($socket, $RoleID);
	$out_data = $socket->response();
	$out_array = ProcessAMGetRoleBaseInfoRes($out_data);

    Utility::Log("system_error", "ASGetRoleBaseInfo Ret", json_encode($out_array));
	//echo "<br />输出帐号服务器返回：<br />";
	//print_r($out_array);
    return $out_array;
}

function DSGetRoleBaseInfo($RoleID)
{
    Utility::Log("system_error", "DSGetRoleBaseInfo", "RoleID:".$RoleID);

	//echo "<br />DSGetRoleBaseInfo:<br />";
	//$socket = ConnectServer(DC_SERVER_IP, DC_SERVERPORT);
    $socket = getSocketInstance('DC');
	SendMDGetRoleBaseInfo($socket, $RoleID);
	$out_data = $socket->response();
	$out_array = ProcessDMGetRoleBaseInfoRes($out_data);

    Utility::Log("system_error", "DSGetRoleBaseInfo Ret", json_encode($out_array));

	//echo "<br />输出数据中心返回：<br />";
	//print_r($out_array);
    return $out_array;
}


function getUserLockStatus($RoleID){
    $asRoleBaseInfo = ASGetRoleBaseInfo($RoleID);
    $keyMap = array(
        "iLocked"=>"Locked","iLoginID"=>"LoginID",
    );
    $asRoleBaseInfo = Utility::arrReplaceKey($asRoleBaseInfo,$keyMap);
    if(isset($asRoleBaseInfo["Locked"])){
        return ["Locked"=>$asRoleBaseInfo['Locked']];
    }else{
        return null;
    }
}
function getUserMobilePhone($RoleID){
    $asRoleBaseInfo = ASGetRoleBaseInfo($RoleID);
    $keyMap = array(
        "szMobilePhone"=>"MobilePhone",'iLoginID'=>"LoginID",
    );
    $asRoleBaseInfo = Utility::arrReplaceKey($asRoleBaseInfo,$keyMap);
    if(isset($asRoleBaseInfo["MobilePhone"])){
        return ["MobilePhone"=>$asRoleBaseInfo['MobilePhone'],"LoginID"=>$asRoleBaseInfo['LoginID']];
    }else{
        return null;
    }
}
function getUserLoginName($RoleID){
    $asRoleBaseInfo = ASGetRoleBaseInfo($RoleID);
    //合并两个数组转化格式
    $keyMap = array("szLoginName"=>"LoginName"
    );
    $asRoleBaseInfo = Utility::arrReplaceKey($asRoleBaseInfo,$keyMap);
    if(isset($asRoleBaseInfo['LoginName']))
        return $asRoleBaseInfo['LoginName'];
    return null;
}
function getUserRealName($RoleID){
    $dsRoleBaseInfo = DSGetRoleBaseInfo($RoleID);
    //合并两个数组转化格式
    $keyMap = array("szRealName"=>"RealName"
    );
    $dsRoleBaseInfo = Utility::arrReplaceKey($dsRoleBaseInfo,$keyMap);
    if(isset($dsRoleBaseInfo['RealName']))
        return $dsRoleBaseInfo['RealName'];
    return null;
}
function getUserBaseInfo($RoleID){
    $asRoleBaseInfo = ASGetRoleBaseInfo($RoleID);
    $dsRoleBaseInfo = DSGetRoleBaseInfo($RoleID);
    //合并两个数组转化格式
    $keyMap = array("szLoginName"=>"LoginName","iLoginID"=>"LoginID","szMobilePhone"=>"MobilePhone","szQQ"=>"QQ",
        "iMoorMachine"=>"MoorMachine","szMachineSerial"=>"MachineSerial","iLockStartTime"=>"LockStartTime","iTitleID"=>"TitleID","iLockEndTime"=>"LockEndTime",
        "iLocked"=>"Locked","iLoginCount"=>"LoginCount","szLastLoginIP"=>"LastLoginIP","iLastLoginTime"=>"LastLoginTime",
        "szRegIP"=>"RegIP","iAddTime"=>"AddTime","iBlocked"=>"Blocked","iBlockStartTime"=>"BlockStartTime","iBlockEndTime"=>"BlockEndTime","szWeChat"=>"WeChat","szPlayerName"=>"PlayerName",
    );
    $asRoleBaseInfo = Utility::arrReplaceKey($asRoleBaseInfo,$keyMap);
    $keyMap = array("iRoleID"=>"RoleID","szRealName"=>"RealName","iGender"=>"Gender","iVipID"=>"VipID","szSignature"=>"Signature",
        "iVipExpireTime"=>"VipExpireTime","iVipOpeningTime"=>"VipOpeningTime","iRoomID"=>"RoomID","iGameLock"=>'GameLock',"iClientType"=>'ClientType'
    );
    $client_type = array('电脑','安卓','苹果','WindowPhone');

    $dsRoleBaseInfo = Utility::arrReplaceKey($dsRoleBaseInfo,$keyMap);
    $dsRoleBaseInfo['ClientTypeTips'] =$client_type[$dsRoleBaseInfo['ClientType']];
    $result = array_merge($asRoleBaseInfo,$dsRoleBaseInfo);
    return $result;
}


