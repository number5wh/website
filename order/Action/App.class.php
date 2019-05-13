<?php
require_once ROOT_PATH . 'Common/PageBase.class.php';
require_once ROOT_PATH.'Link/InsertReceiptData.php';
require_once ROOT_PATH.'Link/FindPayOrder.php';
require_once ROOT_PATH.'Link/BuyHappyBean.php';
require_once ROOT_PATH.'Link/BuyVIP.php';
require_once ROOT_PATH.'Link/CreatePayOrder.php';
require_once ROOT_PATH.'Link/SetPayOrderStatus.php';
/**
 * 首页
 * @author xuluojiong
 */
class AppAction extends PageBase
{

    private $CFG;
    public function __construct()
    {
    
        global $smarty;
        $this->CFG=unserialize(SYS_CONFIG);
    }
	/**
	 * 验证ios支付结果
	 * 
	 * **/
	public function verify_iospay(){
	    $params = Utility::request(['receipt-data','LoginID','PayType']);
	    //$params['receipt-data'] = "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";
        $url = 'https://buy.itunes.apple.com/verifyReceipt';
       
	    $ret = Utility::http_post_data($url,json_encode($params));
	    $ret = json_decode($ret,true);

	    $file_name =basename($_SERVER['PHP_SELF'],'.php');
	    Utility::Log($file_name, 'buy', json_encode($ret));

	    if($ret['status'] != 0){
	        $url = 'https://sandbox.itunes.apple.com/verifyReceipt';
	        $ret = Utility::http_post_data($url,json_encode($params));
    	    $ret = json_decode($ret,true);
    	    Utility::Log($file_name, 'sandbox', json_encode($ret));
	    }
	    if(!isset($ret['status'])){
	        OSInsertReceiptData($params['LoginID'], $params['PayType'], $params['receipt-data']);
	    }
	    if(isset($ret['status'])&&$ret['status'] == 0){
	        if($ret['receipt']['bundle_id']=='com-game593-mobile'
	         || $ret['receipt']['bundle_id']=='com.Game593.mobile'
	         || $ret['receipt']['bundle_id']=='com.593game.mobile'
	         || $ret['receipt']['bundle_id']=='com.ddgame.dphone'
	         || $ret['receipt']['bundle_id']=='com.gamedd.dphone'
	       	){
	            $in_app = $ret['receipt']['in_app'][0];
	            $date = str_replace('-','',explode(' ',$in_app['purchase_date'])[0]);
	            $out_trade_no =  $date.$in_app['transaction_id'];
	            $product_id = $in_app['product_id'];
	            $total_fee = $this->CFG['IosGoodsPrice'][$product_id];
	            if(OSFindPayOrder(33,'',$out_trade_no,3)['iResult']==0){
	                //叮当已完成
	            }else{
	                //$objPayLogsBLL->setPayOrderStatus(1,'',$out_trade_no,1);
	                if($params['PayType'] == 1){ //通易币
	                    $result = DCBuyHappyBean($params['LoginID'], floor($total_fee*100),33);
	                }
	                else{//黄钻会员
	                    $result =  DCBuyVIP($params['LoginID'], floor($total_fee/$this->CFG['VipPrice'])*$this->CFG['VipDays'],33);
	                }
	                if($result['iResult']==0){
	                    OSCreatePayOrder(33, $params['PayType'], '', $out_trade_no, $total_fee*100, $params['LoginID']);
	                    OSSetPayOrderStatus(33, '', $out_trade_no, 3);
	                }
	            }
	            Utility::response(0, '验证成功');
	        }else{
	            Utility::response(-1, '验证失败');
	        }
	    } else {
	        Utility::response(-1,'验证失败');
	    }
	}
}
?>