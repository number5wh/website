<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserDataBLL.class.php';
require ROOT_PATH . 'Class/BLL/PayLogsBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
class RechargeFormAction extends PageBase
{	
	private $objMatchBLL = null;	
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);
		//$this->objMatchBLL = new MatchLL();
	}
	public function index()
	{
		$DateTime = date('Y-m-d');
		$arrTags=array('skin'=>$this->arrConfig['skin'],'DateTime'=>$DateTime,'FromDate'=>date("Y-m-d",strtotime('-90 day')));
		Utility::assign($this->smarty,$arrTags);	
		
		$this->smarty->display($this->arrConfig['skin'].'/YunYing/RechargeForm.html');
	}	 

	/**
	 * 分页读取
	 */
	public function getRechargeFormList()
	{
		$TotalHappyBean = 0;
		$TotalMoney = 0;
        $TotalPayed = 0;
        $TotalUnpayed=0;
        $TotalPayFail = 0;
        $TotalCharged = 0;
        $TotalChargeFail = 0;
        $TotalRealCharged = 0;
		$StartTime = Utility::isNullOrEmpty('StartTime',$_POST) ? $_POST['StartTime'] : date('Y-m-d');
		$EndTime = Utility::isNullOrEmpty('EndTime',$_POST) ? $_POST['EndTime'] : date('Y-m-d');
		
		$objPayLogsBLL = new PayLogsBLL(0);
		$arrResult = $objPayLogsBLL->getRechargeOrderCount($StartTime,$EndTime);
		if($arrResult)
		{
			$iCount = 0;
            $dataResult =array();
            $beiwang = array('UnpayedMoney'=>0,'PayedMoney'=>0,'PayFailMoney'=>0,'ChargedMoney'=>0
                                    ,'ChargeFailMoney'=>0,'CardTypeTip'=>'北网');
            $objMasterBLL = new MasterBLL();

            $beiwangCardCharge = $objMasterBLL->getBeiwangCardRateList();
            $allCardCharge = $objMasterBLL->getCardChargeRateList();

            
            $beiwangCardName = Utility::array_column($beiwangCardCharge,'CardName','CardID');
            $allCardName = Utility::array_column($allCardCharge,'CardName','CardID');

            $mergeResult = array();

            foreach($arrResult as $val){
                if(empty($mergeResult[$val['CardType']])){
                    $mergeResult[$val['CardType']] = $val;
                }else{
                    $mergeResult[$val['CardType']]['UnpayedMoney'] += $val['UnpayedMoney'];
                    $mergeResult[$val['CardType']]['PayedMoney'] += $val['PayedMoney'];
                    $mergeResult[$val['CardType']]['PayFailMoney'] += $val['PayFailMoney'];
                    $mergeResult[$val['CardType']]['ChargedMoney'] += $val['ChargedMoney'];
                    $mergeResult[$val['CardType']]['ChargeFailMoney'] += $val['ChargeFailMoney'];
                }
            }
            $arrResult = $mergeResult;

			foreach ($arrResult as $val)
			{
                /*//1 支付宝  2 微信    3北网
                switch($val['CardType']){
                    case 1:$arrResult[$iCount]['CardTypeTip'] = '支付宝';break;
                    case 2:$arrResult[$iCount]['CardTypeTip'] = '微信';break;
                    case 3:$arrResult[$iCount]['CardTypeTip'] = '北网';break;
                    default :$arrResult[$iCount]['CardTypeTip'] = '';break;
                }*/

                //var_dump($val['CardType']);
                if($val['CardType'])  $percent = $objMasterBLL->getCardChargeRate($val['CardType']);
                else{
                    $percent = 10000;
                }
                $percent = is_array($percent)?$percent[0]['ChargeRate']:10000;
                if(($percent+0.0)/10000 > 0){
                    $val['RealChargedMoney'] = round($val['ChargedMoney']*($percent+0.0)/10000,2);
                }
                if(!array_key_exists($val['CardType'],$beiwangCardName)){
                    $val['CardTypeTip'] = Utility::gb2312ToUtf8($allCardName[$val['CardType']]);
                    $dataResult[] = $val;
                }else{
                    $beiwang['UnpayedMoney'] += $val['UnpayedMoney'];
                    $beiwang['PayedMoney'] += $val['PayedMoney'];
                    $beiwang['PayFailMoney'] += $val['PayFailMoney'];
                    $beiwang['ChargedMoney'] += $val['ChargedMoney'];
                    $beiwang['ChargeFailMoney'] += $val['ChargeFailMoney'];
                    $beiwang['RealChargedMoney'] += $val['RealChargedMoney'];
                    $showBeiwang = 1;
                }
                /*if($val['CardType'] == 1){
                    $val['CardTypeTip'] = '支付宝';
                    $dataResult[] = $val;
                }else if($val['CardType'] == 2){
                    $val['CardTypeTip'] = '微信';
                    $dataResult[] = $val;
                }else if($val['CardType'] == 33){
                    $val['CardTypeTip'] = 'ios';
                    $dataResult[] = $val;
                }else{
                    $beiwang['UnpayedMoney'] += $val['UnpayedMoney'];
                    $beiwang['PayedMoney'] += $val['PayedMoney'];
                    $beiwang['PayFailMoney'] += $val['PayFailMoney'];
                    $beiwang['ChargedMoney'] += $val['ChargedMoney'];
                    $beiwang['ChargeFailMoney'] += $val['ChargeFailMoney'];
                    $beiwang['RealChargedMoney'] += $val['RealChargedMoney'];
                    $showBeiwang = 1;
                }*/
				//$TotalHappyBean += $val['TotalHappyBean'];
				//$TotalMoney += $val['TotalMoney'];
				//$arrResult[$iCount]['TypeName'] = Utility::gb2312ToUtf8($val['Corp']);//$this->arrConfig['RechargeType'][$val['TypeID']];
                //0待付款  1 付款成功  2付款失败  3充值成功 4充值失败
                $TotalUnpayed +=$val['UnpayedMoney'];
                $TotalPayed += $val['PayedMoney'];
                $TotalPayFail += $val['PayFailMoney'];
                $TotalCharged += $val['ChargedMoney'];
                $TotalChargeFail += $val['ChargeFailMoney'];
                $TotalRealCharged += $val['RealChargedMoney'];
				$iCount++;
			}


            empty($showBeiwang)? : ($dataResult[] = $beiwang);
		}

        $arrResult = $dataResult;
		$arrTotal = array('TotalUnpayed'=>$TotalUnpayed,'TotalPayed'=>$TotalPayed,'TotalPayFail'=>$TotalPayFail
        ,'TotalCharged'=>$TotalCharged,'TotalChargeFail'=>$TotalChargeFail,'TotalRealCharged'=>$TotalRealCharged);
		$arrTags=array('skin'=>$this->arrConfig['skin'],'RechargeFormList'=>$arrResult,'arrTotal'=>$arrTotal);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunYing/RechargeFormList.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	
}
?>