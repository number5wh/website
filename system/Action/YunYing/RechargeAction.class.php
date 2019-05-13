<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/StagePropertyBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/BankBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserBLL.class.php';
require ROOT_PATH . 'Class/BLL/MatchBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserDataBLL.class.php';
require ROOT_PATH . 'Class/BLL/DataChangeLogsBLL.class.php';
require ROOT_PATH . 'Class/BLL/OperationLogsBLL.class.php';
require ROOT_PATH . 'Class/BLL/PayLogsBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';

require_once ROOT_PATH . 'Link/GetRoleBaseInfo.php';
require_once ROOT_PATH . 'Link/AddRoleMonery.php';
require_once ROOT_PATH . 'Link/BuyRoleVip.php';

class RechargeAction extends PageBase
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
		$objSessioinLogin = new Session($this->arrConfig['Session']['SessionLoginName']);
		$DeptID = $objSessioinLogin->get($this->arrConfig['SessionInfo']['DeptID']);
		$arrResult =$this->getPagerRecharge(20);

        $arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'RechargeList'=>$arrResult['arrRechargeList'],
                'RechargeType'=>$this->arrConfig['RechargeType'],'DeptID'=>$DeptID,'StartTime'=>date('Y-m-d',strtotime("-15 day")),
            'EndTime'=>date('Y-m-d'),'RechargeStatusType'=>$this->arrConfig['RechargeStatusType'],'PayType'=>[1=>'金币',2=>'黄钻']);
		Utility::assign($this->smarty,$arrTags);	
		
		$this->smarty->display($this->arrConfig['skin'].'/YunYing/RechargeList.html');
	}	 

	/**
	 * 分页
	 */
	public function getPagerRecharge($pagesize)
	{
		$strWhere = ' WHERE 1=1';
		$curPage = Utility::isNumeric('curPage',$_POST);
		//$RechargeType = Utility::isNumeric('RechargeType',$_POST);
		$StartTime = Utility::isNullOrEmpty('StartTime',$_POST) ? $_POST['StartTime'] : '';
		$EndTime = Utility::isNullOrEmpty('EndTime',$_POST) ? $_POST['EndTime'] : date('Y-m-d');
		$LoginID = Utility::isNullOrEmpty('LoginID',$_POST) ? str_replace(" ","",$_POST['LoginID']) : '';
		$OrderID = Utility::isNullOrEmpty('OrderID',$_POST) ? str_replace(" ","",$_POST['OrderID']) : '';
		$Amount = Utility::isNumeric('Amount',$_POST);
		$RechargeStatus = Utility::isNumeric('RechargeStatus',$_POST);
        $PayType = Utility::isNumeric('PayType',$_POST);
        $TransactionID = Utility::isNullOrEmpty('TransactionID',$_POST)? str_replace(" ","",$_POST['TransactionID']):'';
		//$StartSortNumber = $Prefix.$RegularCode.$StartSortNumber;
		//$EndSortNumber = $Prefix.$RegularCode.$EndSortNumber;
				
		$curPage = $curPage<=0 ? 1 : $curPage;
		//if($RechargeType) $strWhere .= " AND TypeID=$RechargeType";
		if($LoginID)
		{
			//$objUserBLL = new UserBLL(0);
			//$arrUserInfo = $objUserBLL->getRole(1,$LoginID);
			//if(!empty($arrUserInfo))
				$strWhere .= " AND LoginID=".$LoginID;
			//else
			//	return array('arrRechargeList'=>null,'Page'=>null);
		}
		if($OrderID) $strWhere .= " AND SpOrderNo='$OrderID' ";
		if($Amount) $strWhere .= " AND TotalFee=$Amount ";
		//if($StartTime) $strWhere .= " AND DATEDIFF(d,AddTime,'$StartTime')<=0 ";
		//if($EndTime) $strWhere .= " AND DATEDIFF(d,AddTime,'$EndTime')>=0 ";
		if(!empty($RechargeStatus) && $RechargeStatus != -1){
            $strWhere .= " AND Status={$RechargeStatus}";
        }
        if(!empty($PayType) && $PayType != -1){
            $strWhere .= " AND PayType={$PayType}";
        }
        if(!empty($TransactionID)){
            $strWhere .= " AND TransactionID='{$TransactionID}' ";
            //var_dump($strWhere);
        }
        //var_dump($strWhere);
		$arrParam['fields']='OrderID, Status, TransactionID, SpOrderNo, TotalFee, LoginID, CONVERT(VARCHAR(100),UpdateTime,120) AS UpdateTime,PayType,CardType';
		$arrParam['tableName']='T_PayOrder_';
		$arrParam['where']=$strWhere;
		$arrParam['order']='OrderID desc';
        $arrParam['Page'] = $curPage;
		$arrParam['PageSize']=$pagesize;
        $arrParam['StartDate'] = $EndTime;
        $arrParam['EndDate'] = $EndTime;
		$objPayLogsBLL = new PayLogsBLL($this->arrConfig['MapType']['PayLogs']);
		$iRecordsCount = $objPayLogsBLL->getRecordsCount($arrParam);
        //var_dump($iRecordsCount);
		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['PageSize']);
		$arrRechargeList = $objPayLogsBLL->getPageList($arrParam,0);

        $objMasterBLL = new MasterBLL();

        $beiwangCardCharge = $objMasterBLL->getBeiwangCardRateList();
        $allCardCharge = $objMasterBLL->getCardChargeRateList();
        $beiwangCardName = Utility::array_column($beiwangCardCharge,'CardName','CardID');
        $allCardName = Utility::array_column($allCardCharge,'CardName','CardID');

		if($arrRechargeList)
		{
			$iCount = 0;
			foreach ($arrRechargeList as $val)
			{
				if(empty($arrUserInfo))
				{
					    $objUserBLL = new UserBLL($val['RoleID']);
					    $arrUserInfo = $objUserBLL->getRoleInfo($val['RoleID']);

                    //$iLoginName = getUserLoginName($val['LoginID']);
                    //$arrUserInfo = getUserBaseInfo($val['LoginID']);
                    $iLoginName = $arrUserInfo['RealName'];
				}
				if(isset($iLoginName))
				{
					$arrRechargeList[$iCount]['LoginName'] = $iLoginName.'('.$val['LoginID'].')';
                    //1 支付宝  2 微信    3北网
                    if(!array_key_exists($val['CardType'],$beiwangCardName)){
                        $arrRechargeList[$iCount]['CardTypeTip'] = Utility::gb2312ToUtf8($allCardName[$val['CardType']]);
                    }else{
                        $arrRechargeList[$iCount]['CardTypeTip'] = '骏付通';
                    }
                    /*switch($val['CardType']){
                        case 1:$arrRechargeList[$iCount]['CardTypeTip'] = '支付宝';break;
                        case 2:$arrRechargeList[$iCount]['CardTypeTip'] = '微信';break;
                        case 33:$arrRechargeList[$iCount]['CardTypeTip'] = 'ios';break;
                        //case 3:$arrRechargeList[$iCount]['CardTypeTip'] = '北网';break;
                        default :$arrRechargeList[$iCount]['CardTypeTip'] = '北网';break;
                    }*/
                    //spOrderNo 内部订单编号

                    //0待付款  1 付款成功  2付款失败  3充值成功 4充值失败
                    switch($val['Status']){
                        case 0:$arrRechargeList[$iCount]['StatusTip'] = '待付款';break;
                        case 1:$arrRechargeList[$iCount]['StatusTip'] = '付款成功';break;
                        case 2:$arrRechargeList[$iCount]['StatusTip'] = '付款失败';break;
                        case 3:$arrRechargeList[$iCount]['StatusTip'] = '充值成功';break;
                        case 4:$arrRechargeList[$iCount]['StatusTip'] = '充值失败';break;
                        default:$arrRechargeList[$iCount]['StatusTip'] = '状态错误';break;
                    }
                    switch($val['PayType']){
                        case 1:$arrRechargeList[$iCount]['PayTypeTip'] = '金币充值';break;
                        case 2:$arrRechargeList[$iCount]['PayTypeTip'] = '黄钻充值';break;
                        default:$arrRechargeList[$iCount]['PayTypeTip'] = '';break;
                    }
					//$arrRechargeList[$iCount]['Corp'] = Utility::gb2312ToUtf8($val['Corp']);//==1 ? '快钱充值' : ($val['TypeID']==2 ? '聚宝充值' : '北网充值');
					$arrRechargeList[$iCount]['StatusName'] = $val['Status']==1 ? '成功' : ($val['Status']==-1 ? '未付款' : '失败');
				}
				else 
				{
					$arrRechargeList[$iCount]['LoginName'] = '';
					$arrRechargeList[$iCount]['Corp'] = '';
					$arrRechargeList[$iCount]['StatusName'] = '';
				}
				if(!$LoginID) $arrUserInfo=null;
				$iCount++;
			}
		}
		return array('arrRechargeList'=>$arrRechargeList,'Page'=>$Page);
	}
	/**
	 * 分页读取
	 */
	public function getPagerRechargeList()
	{
		$arrResult = $this->getPagerRecharge(20);
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'RechargeList'=>$arrResult['arrRechargeList']);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunYing/RechargeListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	/**
	 * 补发
	 */
	public function supplyAgain()
	{
		$iResults = -1;
		$OpType = -1;
		$OpContent = '';
		$RoleID = Utility::isNumeric('RoleID',$_POST);
		$OrderSerial = Utility::isNullOrEmpty('OrderSerial',$_POST);
        $CardType = Utility::isNumeric('CardType',$_POST);
		$RID = Utility::isNumeric('RID',$_POST);
		if($RoleID && $OrderSerial)
		{
			$objPayOrderBLL = new PayLogsBLL($RoleID);
            $objMasterBLL = new MasterBLL($RoleID);
			$arrOrderInfo = $objPayOrderBLL->getPayOrder($OrderSerial,$CardType);
			if(!empty($arrOrderInfo) )
			{

				//充值操作
				//$arrResult = $objUserDataBLL->setUserBankRecharge($OrderSerial,$arrOrderInfo['Money'],'',$arrOrderInfo['Money'],$arrOrderInfo['RechargeFrom']);
				
				if( $arrOrderInfo['Status'] ==0 || $arrOrderInfo['Status'] ==4)//未付款也能补发mlgt
				{					
					if($arrOrderInfo['PayType'] == 1)//充值金币
					{
//					    $GameConfig = $objMasterBLL->getGameConfig(0);
//					    foreach ($GameConfig as $val){
//					        if($val['CfgType'] == 8){               //充值比例人民币，单位分
//					            $RMB = $val['CfgValue'];
//					        }
//					        if($val['CfgType'] == 9){               //充值比例金币
//					            $HPB = $val['CfgValue'];
//					        }
//					        if($val['CfgType'] == 10){               //充值比例593币
//					            $MB = $val['CfgValue'];
//					        }
//					        if($val['CfgType'] == 13){
//					            $discount = $val['CfgValue'];
//					        }
//					    }
                        ///$times = $MB*100/$RMB;
//					    $RMB2MB = $MB*100/$RMB;
//					    $MB2HPB = $HPB/$MB;
//					    $ChargeRate = $objMasterBLL->getCardChargeRate($arrOrderInfo['CardType'])[0]['ChargeRate'];
//					    $times = $RMB2MB*$MB2HPB*$ChargeRate/$discount;
                        /* $CfgType = 9;//充值比例金币
                        $gameConfig = $objMasterBLL->getGameConfig($CfgType);
                        $times = $gameConfig[0]['CfgValue']; */



                        $money = intval (1000 * $arrOrderInfo['TotalFee']);
                        $OpType = 7;
						$OpContent = '后台补发金币充值记录';
						$arrResult = DSAddRoleMonery($RoleID,$money);
                        if(is_array($arrResult)){
                            if($arrResult['iResult'] != 0){
                                $iResults = -2;
                            } else {
                                $iResults = 0;
                            }
                        }
						//$objDataChangeLogsBLL = new DataChangeLogsBLL();
						//$objDataChangeLogsBLL->insertBankTransLog($RoleID,'',4,8003,'充值户',1,$arrResult['Money'],$arrResult['Balance'],0,$OpContent,'',$OrderSerial);
						//$objBankBLL = new BankBLL();
						//$arrResult1 = $objBankBLL->updateSysBank(3, $arrOrderInfo['Count']);//扣除系统银行充值户金币数量
						/*if(!empty($arrResult1))
						{							
							//充值失败,回滚
							if($arrResult1['iResult']!=0)
							{
								$Result2 = $objUserDataBLL->setUserBankRollback($OrderSerial);
								if($Result2==0)
									$Note='后台补发金币充值记录失败,系统回滚成功';
								else 
									$Note='后台补发金币充值记录失败,系统回滚失败';
								$objDataChangeLogsBLL->insertBankTransLog($RoleID,'',4,8003,'充值户',2,$arrResult['Money'],$arrResult['Balance'],0,$Note,'',$OrderSerial);
							}
							else
							{
								$objDataChangeLogsBLL->insertSysBankTransLogs(3, $RoleID, 0, 9, 2, $arrOrderInfo['Count'], $arrResult1['LastBalance'], $arrResult1['Balance'], '');
								$iResults = 0;
							}
						}*/
					}
					else //充值黄钻
					{	
//						$OpType = 4;
//						$OpContent = '后台补发黄钻服务期';
//						//$objUserBLL = new UserBLL($RoleID);
//						//$arrResult1 = $objUserBLL->ReturnBackUserVip($arrOrderInfo['Count']);
//						//充值失败,回滚
//                        $iNumber = 30;
//                       // $arrResult = DSBuyRoleVip($RoleID,$iNumber);
//                        //var_dump($arrResult);
//                        if(is_array($arrResult))
//						{
//							if($arrResult['iResult']!=0){
//
//                            }
//							else
//							{
//								$iResults = 0;
//							}
//						}
					}

                    if($iResults == 0){//更新订单状态
                        $objPayOrderBLL->setPayOrderStatus($arrOrderInfo['CardType'],$arrOrderInfo['TransactionID'],$arrOrderInfo['SpOrderNo'],3);
                    }
				}	
			}			
		}
		
		//操作日志
		$objSessioinLogin = new Session($this->arrConfig['Session']['SessionLoginName']);
		$SysUserName = $objSessioinLogin->get($this->arrConfig['SessionInfo']['UserName']);
		$objOperationLogsBLL = new OperationLogsBLL($RoleID);
		$objOperationLogsBLL->addCaseOperationLogs($RoleID, 0, $OpType, $OpContent, $iResults, Utility::getIP(), 0, 2, $SysUserName, '');
		
		echo json_encode(array('iResult'=>$iResults,'RID'=>$RID));
	}
	/**
	 * 删除卡号
	 * $iResult: 0:成功,-1:失败
	 
	public function delCard()
	{
		$iResult = -1;
		$CardID = Utility::isNumeric('CardID',$_POST);
		if($CardID && $CardID>0)		
		{
			$arrResult = $this->objStagePropertyBLL->delCard($CardID);
			if($arrResult && $arrResult['iResult']==0)
			{			
				if($arrResult['IsUsed']==0)
				{
					$objBankBLL = new BankBLL();				
					$arrRes = $objBankBLL->setSysBankMoney($arrResult['CardType'],$arrResult['iMoney'],2);
					$objDataChangeLogsBLL = new DataChangeLogsBLL();
					$objDataChangeLogsBLL->insertSysBankTransLogs($arrResult['CardType'],$arrRes['AccNo'],0,$this->arrConfig['TransType']['TransType10'],$this->arrConfig['DCFlag']['DCFlag1'],$arrResult['iMoney'],$arrRes['LastBalance'],$arrRes['Balance'],'');
				}
				$html=$arrResult['iResult'];
			}
			else
				$html=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'SysCard\')','删除失败,请重试','false','SysCard',$this->arrConfig);
		}
		else 
			$html=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'SysCard\')','对不起,您提交的数据异常,请重试','false','SysCard',$this->arrConfig);
		echo $html;
	}
	**
	 * 显示添加卡号表单
	 
	public function showAddCardHtml()
	{
		$arrCodeList = $this->objStagePropertyBLL->getRegularCodeList(1);
		$arrTags=array('RegularCodeList'=>$arrCodeList);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunYing/CardEdit.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));

		echo $html;
	}
	**
	 * 添加卡号
	 
	public function addCard()
	{
		$iResult = -1;
        $iResult1 = -1;
		$iSuccess = 0;
		$iFail = 0;
		$iCount = 0;
		$Prefix = Utility::isNullOrEmpty('Prefix',$_POST) ? $_POST['Prefix'] : '';
		$RegularCode = Utility::isNullOrEmpty('RegularCode',$_POST) ? $_POST['RegularCode'] : '';
		$SortNumber = Utility::isNumeric('SortNumber',$_POST);
		$iNumber = Utility::isNumeric('iNumber',$_POST);
		$iMoney = Utility::isNumeric('iMoney',$_POST);
        $CardType = Utility::isNumeric('CardType',$_POST);
        $Flag = Utility::isNumeric('Flag',$_POST);
		if($iNumber && $iNumber>0)
		{
            if($CardType>0 && $Flag)
            {
                $objBankBLL = new BankBLL();
                $iResult1 = $objBankBLL->updateSysBank($CardType, $iMoney*$iNumber);
            }
            if($CardType>0 && $iResult1==0 || $Flag==0)
            {
                $iResult1 = 0;
                $pattern = '1234567890';//'1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
        		for ($i=0;$i<$iNumber;$i++)
        		{
            		$CardNumber = '';
            		$CardKey = '';
            		$iZero = '';
            		//for($k = 0; $k < 12; $k++)			
                	//	$CardNumber .= mt_rand(0, 9);	
                	if(strlen($SortNumber)<6)
                	{
                		$iLen = 6-strlen($SortNumber);
                		for ($k=0;$k<$iLen;$k++)
                			$iZero .= '0';
                	}
                	$tmpSortNumber = $iZero.$SortNumber;
                	$CardNumber = trim($Prefix).$RegularCode.$tmpSortNumber;
                    for($k = 0; $k < 8; $k++)	
                        $CardKey .= mt_rand(0, 9);				
    			
               	    $iResult = $this->objStagePropertyBLL->addCard($CardNumber,$CardKey,$iMoney,$CardType);
               	    if($iResult==0)
                        $iSuccess++;
                    else
                        $iFail++;
                    $iCount++;
                    $SortNumber++;
                    if($iCount==20) break;
                }
                if($iFail>0){
                    $objBankBLL = new BankBLL();
                    $objBankBLL->setSysBankMoney($CardType,$iFail*$iMoney,2);
                }
                    
            }
            else
                $iFail = $iNumber;
        }			
			

		echo json_encode(array('iSuccess'=>$iSuccess,'iFail'=>$iFail,'iCount'=>$iCount,'Prefix'=>$Prefix,'iNumber'=>$iNumber-$iCount,'iMoney'=>$iMoney,'CardType'=>$CardType,'RegularCode'=>$RegularCode,'SortNumber'=>$SortNumber,'iResult1'=>$iResult1));
	}	
	**
	 * 锁定卡号
	 * $iResult: 0:成功,-1:失败
	 
	public function lockCard()
	{
		$iResult = -1;
		$CardID = Utility::isNumeric('CardID',$_POST);
		$iResult = $this->lockCard1($CardID);
		if($iResult==0)
			$html=$iResult;
		else
			$html=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'SysCard\')','冻结失败,请重试','false','SysCard',$this->arrConfig);
		echo $html;
		/*if($CardID && $CardID>0)		
		{
			$iResult = $this->objStagePropertyBLL->lockCard($CardID);
			if($iResult==0)
				$html=$iResult;
			else
				$html=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'SysCard\')','冻结失败,请重试','false','SysCard',$this->arrConfig);
		}
		else 
			$html=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'SysCard\')','对不起,您提交的数据异常,请重试','false','SysCard',$this->arrConfig);
		echo $html;* /
	}
	**
	 * 锁定卡号
	 * $iResult: 0:成功,-1:失败
	 
	public function lockCard1($CardID)
	{
		$iResult = -1;
		if($CardID && $CardID>0)		
			$iResult = $this->objStagePropertyBLL->lockCard($CardID);
		return $iResult;
	}
	/ **
	 * 分页读取
	 
	public function getPagerCardListLocked()
	{
		$arrResult = $this->getPagerCard(1);
		$objSessioin = new Session($this->arrConfig['Session']['SessionLoginName']);
		$TotalRecords = $objSessioin->get($this->arrConfig['SessionInfo']['RecordsCount']);
		if(!$TotalRecords || $arrResult['Page']['CurPage']==1)
		{
			$TotalRecords = $arrResult['Page']['RecordsCount'];
			$objSessioin->set($this->arrConfig['SessionInfo']['RecordsCount'],$TotalRecords);
		}
		elseif($TotalRecords==$arrResult['Page']['CurPage'])
			$objSessioin->set($this->arrConfig['SessionInfo']['RecordsCount'],0);
		//锁定
		$iResult=$this->lockCard1($arrResult['arrCardList'][0]['CardID']);
		//如果已经是最后一条记录，更改当前页码，不再发起请求
		if($arrResult['Page']['CurPage']>=$TotalRecords || $TotalRecords==0)
			$CurPage = 0;
		else 
			$CurPage = $arrResult['Page']['CurPage']+1;


		echo json_encode(array('CardID'=>$arrResult['arrCardList'][0]['CardID'],'TotalRecords'=>$TotalRecords,'CurPage'=>$CurPage));
	
	}*/
}
?>