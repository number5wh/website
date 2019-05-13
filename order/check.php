<?php

require 'Include/Init.inc.php';
require_once ROOT_PATH . 'Common/PageBase.class.php';
require_once ROOT_PATH.'Link/CheckAccount.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require_once ROOT_PATH.'Link/CreatePayOrder.php';
require_once ROOT_PATH.'Link/GetPayOrder.php';
require_once ROOT_PATH.'Link/AddPayLogs.php';
require_once ROOT_PATH.'Link/SetPayOrderStatus.php';
require_once ROOT_PATH.'Link/GetPayOrder.php';
require_once ROOT_PATH.'Link/FindPayOrder.php';
require_once ROOT_PATH.'Link/BuyHappyBean.php';
require_once ROOT_PATH.'Link/BuyVIP.php';
require_once ROOT_PATH.'Link/GetPayOrderID.php';
require_once ROOT_PATH.'Link/SetPayOrderTransactionID.php';
require_once ROOT_PATH.'Link/DeleteReceiptData.php';
require_once ROOT_PATH.'Link/GetReceiptData.php';
require_once ROOT_PATH.'Link/UpdateReceiptData.php';

/**
 * 验证ios支付结果
 * 
 * **/
function verify_iospay($LoginID,$PayType,$ReceiptData,$RID){
    $url = 'https://buy.itunes.apple.com/verifyReceipt';
    $params['receipt-data'] = $ReceiptData;
    $ret = http_post_data($url,json_encode($params));
    $ret = json_decode($ret,true);
    if($ret['status'] != 0){
        $url = 'https://sandbox.itunes.apple.com/verifyReceipt';
        $ret = http_post_data($url,json_encode($params));
	    $ret = json_decode($ret,true);
    }
    if(isset($ret['status'])){
        OSDeleteReceiptData($LoginID, $RID);
    }else{
        OSUpdateReceiptData($LoginID, $PayType, $RID);
    }
    if(isset($ret['status'])&&$ret['status'] == 0){
        if($ret['receipt']['bundle_id']=='com.Game593.mobile'){
            $in_app = $ret['receipt']['in_app'][0];
            $date = str_replace('-','',explode(' ',$in_app['purchase_date'])[0]);
            $out_trade_no =  $date.$in_app['transaction_id'];
            $product_id = $in_app['product_id'];
            $CFG=unserialize(SYS_CONFIG);
            $total_fee = $CFG['IosGoodsPrice'][$product_id];
            if(OSFindPayOrder(33,'',$out_trade_no,3)['iResult']==0){
                //叮当已完成
            }else{
                //$objPayLogsBLL->setPayOrderStatus(1,'',$out_trade_no,1);
                if($PayType == 1){ //通易币
                    $result = DCBuyHappyBean($LoginID, floor($total_fee*100),33);
                }
                else{//黄钻会员
                    $result =  DCBuyVIP($LoginID, floor($total_fee/100)*31,33);
                }
                if($result['iResult']==0){
                    OSCreatePayOrder(33, $PayType, '', $out_trade_no, $total_fee*100, $LoginID);
                    OSSetPayOrderStatus(33, '', $out_trade_no, 3);
                }
            }
        }
    }
}
/**
 * post数据
 * 
 * */
function http_post_data($url, $data_string) {  
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_POST, 1); // post传输数据
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);// post传输数据
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
      curl_setopt($ch,CURLOPT_TIMEOUT,30);
      curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'Content-Type: application/json; charset=utf-8',
          'Content-Length: ' . strlen($data_string)
        )
      );
      $response = curl_exec($ch);
      return $response;
}  


$data = OSGetReceiptData(); 
if($data['iReceiptCount']!=0){
    foreach ($data['ReceiptDataList'] as $key => $val){
        verify_iospay($val['iLoginID'],$val['iPayType'],$val['szReceiptData'],$val['iRID']);
    }
}

?>