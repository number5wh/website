<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOWToOS.php';
include_once ROOT_PATH.'Common/SePtlOSToOW.php';



/* echo "<br />OSGetPayOrder:<br />";
$socket = ConnectServer(OS_SERVER_IP, OS_SERVERPORT);
SendWOGetPayOrder($socket, "20151127201245448031", 1);
$out_data = ReadData($socket);
$out_array = ProcessOWGetPayOrderRet($out_data);

echo "<br />����������������أ�<br />";
print_r($out_array); */

/**
 * 获取订单信息
 * 
 * @param $OrderNo string 商户订单号
 * @param $CardType int 充值类型
 * 
 * @return iLoginID int 
 * @return iPayType int 支付类型
 * @return iCardType int 充值类型
 * @return iStatus int 订单状态
 * @return szTransactionID string 交易订单号
 * @return szOrderNo  string 商户订单号
 * @return iTotalFee int 支付金额单位分
 * @return iUpdateTime int 最后更新时间
 * */

function OSGetPayOrder($OrderNo, $CardType){
    $socket = getSocketInstance('OS');
    SendWOGetPayOrder($socket, $OrderNo, $CardType);
    $out_data = $socket->response();
    $out_array = ProcessOWGetPayOrderRet($out_data);
    if(!isset($out_array['iResult']))
        $out_array['iResult'] = -1;
    return $out_array;
}