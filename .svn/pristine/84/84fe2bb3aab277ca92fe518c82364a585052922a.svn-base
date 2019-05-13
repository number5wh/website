<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOMToAS.php';
include_once ROOT_PATH.'Common/SePtlASToOM.php';
include_once ROOT_PATH.'Common/SePtlDCToOM.php';
include_once ROOT_PATH.'Common/SePtlOMToDC.php';



function DSQuerySuperRoleList($roleArr)
{
	 Utility::Log("system_error", "DSQuerySuperRoleList", json_encode($roleArr));
	//echo "<br />DSQueryRoleList:<br />";
	//$socket = ConnectServer(DC_SERVER_IP, DC_SERVERPORT);
	//Utility::Log("system_error", "query role list", json_encode($roleArr));
	$socket = getSocketInstance('DC');
    //$roleCount= count($roleArr);
	//SendMDQueryRoleList($socket, $roleCount,$roleArr);
	$condition = ' RoleID IN (' . implode(',', $roleArr) . ')';
	SendMDQureySuperUserList($socket, $condition);
	$out_data = $socket->response();
	$out_array = ProcessDMQuerySuperUserListRes($out_data);
	//echo "<br />输出数据中心返回：<br />";
	//print_r($out_array);

	if($out_array){
	    Utility::Log("system_error", "DSQuerySuperRoleList result", json_encode($out_array));
	}
	
    return $out_array;
}

