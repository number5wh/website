<?php

require_once ROOT_PATH . 'Common/PageBase.class.php';
require_once ROOT_PATH . 'Common/Session.class.php';
require_once ROOT_PATH.'Link/CheckAccount.php';
require_once ROOT_PATH.'Link/GetPayOrder.php';
require_once ROOT_PATH.'Link/GetPayOrderID.php';
require_once ROOT_PATH.'Link/QueryRechargeCardState.php';
require_once ROOT_PATH.'Link/SendWDUseRechargeCard.php';
require_once ROOT_PATH.'Link/GetAccountInfoByID.php';
require_once ROOT_PATH.'Class/BLL/DataCenterBLL.class.php';



class IndexAction extends PageBase
{	
    private $smarty;
	private $objSession=null;
	public function __construct()
	{		    

	    global $smarty;
		$this->arrConfig=unserialize(SYS_CONFIG);
		$this->objSession = new Session($this->arrConfig['Session']['SessionLoginName']);	
		$this->smarty = $smarty;
	    Utility::assign( array('url'=>$this->arrConfig['URL']));
	}
	
	public function index()
	{		

	    $objDataCenter = new DataCenterBLL();
	    $GameConfig = $objDataCenter->getGameConfig();
	    $RMB =$GameConfig[8];  //充值比例人民币，单位分
	    $HPB = $GameConfig[9];  //充值比例欢乐豆
	    $MB = $GameConfig[10];  //充值比例通易币
	    $discount  = $GameConfig[13];
	    $LoginCode = Utility::isNullOrEmpty('account', $_REQUEST);
	    $payConfig['discount'] = $discount;
	    $payConfig['RMB2MB'] = $MB*100/$RMB;
	    $payConfig['MB2HPB'] = $HPB/$MB;
		$arrTags=array('LoginCode'=>$LoginCode,'b780'=>$this->arrConfig['b780'],'payConfig'=>$payConfig); 				
		Utility::assign($arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/Recharge.html');

	}	
	/***
	 * 获取欢乐豆充值比例
	 * 
	 * **/
	public function getHappyBeanRate(){

	    $objDataCenter = new DataCenterBLL();
	    $CardType=Utility::isNullOrEmpty('CardType',$_REQUEST);
	    $ChargeRate = $objDataCenter->getCardChargeRateByType($CardType);
	    echo $ChargeRate;
	}
	
	/**
	 * 
	 * 检验支付参数是否正确
	 * 
	 * **/
	public function chkParam()
	{
	    $CheckCode=Utility::isNullOrEmpty('CheckCode',$_REQUEST);
	    $cardType = Utility::isNullOrEmpty('cardType', $_REQUEST);
	    $ChkCode = $this->objSession->get($this->arrConfig['SessionInfo']['ChkCode']);
	    if($ChkCode !== $CheckCode){
	        echo json_encode(['iResult'=>-1]);   //验证码错误
	    }else{
	        $LoginType = Utility::isNullOrEmpty('LoginType',$_REQUEST);
	        $LoginID = Utility::isNullOrEmpty('LoginID',$_REQUEST);
	        $LoginCode = Utility::isNullOrEmpty('LoginCode',$_REQUEST);
	        if($LoginType == 1){
	            // if(preg_match('/^[a-zA-Z]{1}([a-zA-Z0-9_]){3,15}$/',$LoginCode)==false&&preg_match('/^(13|14|15|17|18)\d{9}$/',$LoginCode)==false){
	            //     echo json_encode(['iResult'=> -1001]);  //账号格式错误
	            //     return ;
	            // }
	            $ret = ASCheckAccount($LoginCode);
	            if($ret['iResult']==0){
	                echo json_encode(['iResult'=>-2]);   //账号不存在
	                return ;
	            }
	        }else{
	            if( $LoginID < 60000 || $LoginID >= 100000000){
	                echo json_encode(['iResult'=> -1002]);  //用户编号不存在
	                return ;
	            }
	            $ret = ASGetAccountInfoByID($LoginID);   
	            if($ret['szLoginName']==''){
	                echo json_encode(['iResult'=> -1002]); //用户编号不存在
	                return ;
	            }
	            $ret['iResult'] = $LoginID;
	        }
            $Rnd = OSGetPayOrderID('');
	        if($Rnd['iResult'] != 0){
	            echo json_encode(['iResult'=> -3]);   //生成订单失败
	            
	        }else{
	            $objDataCenter = new DataCenterBLL();
	            $CardID = $objDataCenter->getCardID($cardType);
	            if($CardID == -1)
	                echo json_encode(['iResult' => -4]); // 充值方式错误
	            else
                    echo json_encode(['iResult'=>0,'iLoginCode'=>$ret['iResult'],'Rnd'=>$Rnd['szOrderNo'],'CardID'=>$CardID]);
           }
        }
	}
	/**
	 * 微信扫码支付生成二维码页面
	 * 
	 * **/
	public function wxpay(){
	    $params['url'] = $_REQUEST['url'];
	    $params['amount'] = $_REQUEST['amount'];
	    $params['goodsname'] = $_REQUEST['goodsname'];
	    $this->smarty->assign('params',$params);
	    $this->smarty->display($this->arrConfig['skin'].'/wxpay.html');
	    
	}
	/**
	 * 实卡支付显示页面
	 * 
	 * */
	public function cardRecharge(){
	    $this->smarty->display($this->arrConfig['skin'].'/cardRecharge.html');
	     
	}
}
?>