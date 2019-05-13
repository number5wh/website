<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOMToAS.php';
include_once ROOT_PATH.'Common/SePtlASToOM.php';
include_once ROOT_PATH.'Common/SePtlDCToOM.php';
include_once ROOT_PATH.'Common/SePtlOMToDC.php';

function ASQueryRoleList($param,$code,$page=1,$pageSize=20)
{
	//echo "<br />ASQueryRoleList:<br />";
	//$socket = ConnectServer(AS_SERVER_IP, AS_SERVERPORT);
	Utility::Log("system_error", "query role list", json_encode(array("param"=>$param,"code"=>$code)));
    $socket = getSocketInstance('AS');
    switch($code) {
        case SRLVT_ROLEID:SendMAQueryRoleList($socket, $page, $pageSize, SRLVT_ROLEID, $param);break;
        case SRLVT_PLAYER_NAME:SendMAQueryRoleList($socket, $page, $pageSize, SRLVT_PLAYER_NAME, $param);break;
        case SRLVT_IDCARD:SendMAQueryRoleList($socket, $page, $pageSize, SRLVT_IDCARD, $param);break;
        case SRLVT_ACCOUNT:SendMAQueryRoleList($socket, $page, $pageSize, SRLVT_ACCOUNT, $param);break;
        case SRLVT_LAST_LOGINIP:SendMAQueryRoleList($socket, $page, $pageSize, SRLVT_LAST_LOGINIP, $param);break;
        case SRLVT_QQ:SendMAQueryRoleList($socket, $page, $pageSize, SRLVT_QQ, $param);break;
        case SRLVT_PHONE:SendMAQueryRoleList($socket, $page, $pageSize,SRLVT_PHONE,$param);break;
        case SRLVT_MACHINE:SendMAQueryRoleList($socket, $page, $pageSize,SRLVT_MACHINE,$param);break;
        case SRLVT_WECHAT:SendMAQueryRoleList($socket,$page,$pageSize,SRLVT_WECHAT,$param);
    }

	$out_data = $socket->response();
	$out_array = ProcessAMQueryRoleListRes($out_data);
	if($out_array){
	   Utility::Log("system_error", "query role list result", json_encode($out_array));
	}
	

	//echo "<br />输出帐号服务器返回：<br />";
	//print_r($out_array);
    return $out_array;
}

function DSQueryRoleList($roleArr)
{
	//echo "<br />DSQueryRoleList:<br />";
	//$socket = ConnectServer(DC_SERVER_IP, DC_SERVERPORT);
	Utility::Log("system_error", "query role list", json_encode($roleArr));
	$socket = getSocketInstance('DC');
    $roleCount= count($roleArr);
	SendMDQueryRoleList($socket, $roleCount,$roleArr);
	$out_data = $socket->response();
	$out_array = ProcessDMQueryRoleListRes($out_data);
	//echo "<br />输出数据中心返回：<br />";
	//print_r($out_array);

	if($out_array){
	    Utility::Log("system_error", "query role list result", json_encode($out_array));
	}
	
    return $out_array;
}

