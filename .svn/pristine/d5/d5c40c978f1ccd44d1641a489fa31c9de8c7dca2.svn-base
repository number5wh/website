<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/10/1
 * Time: 17:24
 */
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOMToAS.php';
include_once ROOT_PATH.'Common/SePtlASToOM.php';
include_once ROOT_PATH.'Common/SePtlDCToOM.php';
include_once ROOT_PATH.'Common/SePtlOMToDC.php';


function DCQueryRoleID($LoginName){
    $socket = getSocketInstance('DC');
    SendMDQueryRoleID($socket, $LoginName);
    $out_data = $socket->response();
    $out_array = ProcessDMRoleOperateAckRes($out_data);
    return $out_array;
}
/*echo "<br />DCQueryRoleID:<br />";
$socket = ConnectServer(DC_SERVER_IP, DC_SERVERPORT);
SendMDQueryRoleID($socket, "机器40000");
$out_data = ReadData($socket);
$out_array = ProcessDMRoleOperateAckRes($out_data);

echo "<br />输出数据中心返回：<br />";
print_r($out_array);*/