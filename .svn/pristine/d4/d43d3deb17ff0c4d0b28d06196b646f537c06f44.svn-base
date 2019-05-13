<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/BankBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserDataBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/DataChangeLogsBLL.class.php';
require ROOT_PATH . 'Class/BLL/OperationLogsBLL.class.php';
require ROOT_PATH . 'Link/QueryRoleTotalMoney.php';
require ROOT_PATH . 'Link/SetSysBankMoney.php';
require ROOT_PATH . 'Link/SysBankDeal.php';
require ROOT_PATH . 'Link/QuerySystemBankData.php';

class SysBankAction extends PageBase
{	
	private $objBankBLL = null;	
	private $iWealthType = 2;
	public function __construct()
	{
        $this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);
		$this->objBankBLL = new BankBLL();
	}
	public function index()
	{		
		//统计玩家玩乐豆数量
		//$objUserDataBLL = new UserDataBLL(0);
		//var_dump($arrUserWealth);
        $arrUserWealth = getRoleTotalMoney();    //接口获取
      //  var_dump($arrUserWealth);
		//取系统配置信息
		$objMasterBLL = new MasterBLL();
		$arrSysConfig = $objMasterBLL->getSysConfigInfo();
		/***************特殊账户金币数量 开始****************/
		$FinanceMoney = 0;
		$objBankBLL = new BankBLL();
		$arrBankInfo = $objBankBLL->getFinanceInfo();
		if(!empty($arrBankInfo)) $FinanceMoney = $arrBankInfo['Money'];
		/***************特殊账户金币数量 结束****************/
		
        /******************获取房间彩蛋金额开始**************/
		$arrList = $this->objBankBLL->getRoomLuckyEggMoneyList();
		$roomInfo = $objMasterBLL->getGameRoomInfoList();
		$RoomMoney = array();
		$total = 0;
		foreach ($arrList as $k => $v){
		    if(!isset($RoomMoney[$v['RoomID']]))
		        $RoomMoney[$v['RoomID']]['money'] = 0;
		    $RoomMoney[$v['RoomID']]['money'] = $RoomMoney[$v['RoomID']]['money'] + $v['LuckyEggMoney'];
		    $total = $total + $v['LuckyEggMoney'];
		}
		foreach ($RoomMoney as $k => $v){
		    foreach ($roomInfo as $key => $val){
		        if($k == $val['RoomID'])
		            $RoomMoney[$k]['RoomName'] = $val['RoomName'];
		    }
		}
		/******************获取房间彩蛋金结束始**************/
		//取银行信息
		$arrSysBank = $this->getSysBankInfo();
		//$arrSysBank = DCQuerySystemBankData();

		//获取当前金币
		foreach ($arrSysBank as $key => $val){
		    if($val['AccNo'] =='')
		        $CurrentHB = $val['Balance'];
		}
		$CurrentHB = $CurrentHB + $total + $arrUserWealth['TotalBankMoney'] - $FinanceMoney + $arrUserWealth['TotalGameMoney'];
		$arrTags=array('RoomMoney'=>$RoomMoney,'CurrentHB'=>$CurrentHB,'total'=>$total,'BankList'=>$arrSysBank,'WealthType'=>$this->iWealthType,'ClassName'=>'SysBank','Sys'=>$arrSysConfig,'AccTypeList'=>$this->arrConfig['BankAccType'],'UserWealth'=>$arrUserWealth,'FinanceMoney'=>$FinanceMoney);
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/SysBankList.html');
	}	 
	/**
	 * 显示添加银行账户页面
	 */
	public function showAddBankAccHtml()
	{		
		$arrBank['iAccType'] = Utility::isNumeric('AccType',$_POST);
		$arrSysBank = $this->getSysBankInfo();
		if(is_array($arrSysBank) && count($arrSysBank)>0)
		{
			foreach ($arrSysBank as $val)
			{
				if(isset($val['AccType']))
				{
					if($arrBank['iAccType']==$val['AccType'])
					{
						$arrBank['iAccNo']=$val['AccNo'];
						break;
					}
				}
			}
		}
		$arrTags=array('AccTypeList'=>$this->arrConfig['BankAccType'],'BankInfo'=>$arrBank);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/SysBankEdit.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}	
	/**
	 * 添加银行账户
	 */
	public function addSysBankAccNo()
	{
		$iResult = -9999;
		$iAccNo = Utility::isNumeric('AccNo',$_POST);	
		$iAccType = Utility::isNumeric('AccType',$_POST);
		if($iAccNo && $iAccType)
		$iResult = $this->objBankBLL->addSysBankAccNo($iAccNo,$iAccType);
		if($iResult==0)
		{
			$objSessioin = new Session($this->arrConfig['Session']['SessionLoginName']);
			$iAdminID = $objSessioin->get($this->arrConfig['SessionInfo']['AdminID']);
			$SysUserName = $objSessioin->get($this->arrConfig['SessionInfo']['UserName']);
			$objOperationLogsBLL = new OperationLogsBLL(0);
			$objOperationLogsBLL->addCaseOperationLogs($iAdminID, 0, 25, '添加银行账户', $iResult, Utility::getIP(), 0, 2, $SysUserName, '');
		}
		echo $iResult;
	}
	/**
	 * 转账
	 * $iResult=-4:金额必须大于0,-3:余额不足，大于0:成功,0:失败,-9999:参数异常,-8888:转出账户和转入账户不能一致
	 */
	public function transSysBankMoney()
	{
		$iResult = -9999;
		$msg = '';
		$iFromAccType = Utility::isNumeric('FromAccType',$_POST);	
		$iWealthType = Utility::isNumeric('WealthType',$_POST);
		$iMoney = Utility::isNumeric('Money',$_POST);
		$iToAccType = Utility::isNumeric('ToAccType',$_POST);
        $iMoney =$iMoney*1000;
		if($iFromAccType && $iWealthType && $iMoney && $iToAccType)
		{
			//转出账户跟转入账户不能一致
			if($iFromAccType==$iToAccType)
				$iResult = -8888;
			else
			{
				//$arrResult = $this->objBankBLL->transSysBankMoney($iFromAccType,$iWealthType,$iMoney,$iToAccType);
				$arrResult = DCSysBankDeal($iFromAccType, $iToAccType, $iMoney);
				if(!isset($arrResult['iResult']))
				    $iResult = 1;
				else 
				     $iResult = $arrResult['iResult'];
				$PreBalance = $arrResult['iLastBalance'];
				$Balance = $arrResult['iBalance'];
				 $ToPreBalance = $arrResult['iToLastBalance'];
				$ToBalance = $arrResult['iToBalance'];
				if($iResult==0)
				{
					$iResult=Utility::echoResultHtml($this->smarty,'确 定','main.CloseMsgBox(true,\'SysBank\')','银行账户转账成功,转入户余额：'.($ToBalance/1000).',转出户余额：'.($Balance/1000),'true','SysBank',$this->arrConfig);
					/* $objDataChangeLogsBLL = new DataChangeLogsBLL(0);
					$objDataChangeLogsBLL->insertSysBankTransLogs($iFromAccType, $iToAccType, 0, 1, 2, $iMoney, $PreBalance, $Balance, '');
					$objDataChangeLogsBLL->insertSysBankTransLogs($iToAccType, $iFromAccType, 0, 1, 1, $iMoney, $ToPreBalance, $ToBalance, ''); */

                    $objBankBLL = new BankBLL();
                    $fromSysBankInfo =  $objBankBLL->getSysBank($iFromAccType);
                    $toSysBankInfo = $objBankBLL->getSysBank($iToAccType);
					$objSessioin = new Session($this->arrConfig['Session']['SessionLoginName']);
					$iAdminID = $objSessioin->get($this->arrConfig['SessionInfo']['AdminID']);
					$SysUserName = $objSessioin->get($this->arrConfig['SessionInfo']['UserName']);
					$objOperationLogsBLL = new OperationLogsBLL(0);
					$objOperationLogsBLL->addCaseOperationLogs($iAdminID, 0, 26, '银行账户转账:'."从{$fromSysBankInfo['AccTypeName']}转 {$iMoney} 到{$toSysBankInfo['AccTypeName']}", 0, Utility::getIP(), 0, 2, $SysUserName, '');
				}  
			}
		}
		echo $iResult;
	}
	public function showAddBankCapacityHtml()
	{
		$objMasterBLL = new MasterBLL();
		$arrSysConfig = $objMasterBLL->getSysConfigInfo();	
		$arrSysConfig['ClassName']='SysBank';
		$arrSysConfig['WealthType']=$this->iWealthType;
		$arrTags=array('Sys'=>$arrSysConfig);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/SysBankCapacityEdit.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;		
	}
	/**
	 * 系统扩容
	 */
	public function addBankCapacity()
	{
		$iResult = -9999;
		$WealthType = Utility::isNumeric('WealthType',$_POST);	
		$Capacity = Utility::isNumeric('Capacity',$_POST);
        $Capacity = $Capacity*1000;
		if($WealthType && $Capacity && $WealthType>0 && $Capacity>0)
		{
			$objMasterBLL = new MasterBLL();
			$arrReturns = $objMasterBLL->setSysConfig($Capacity,$WealthType);
			if(isset($arrReturns) && is_array($arrReturns) && count($arrReturns)>0)
			{
				$iResult=$arrReturns['iResult'];
				//如果金币或龙币扩容量大于0,同时更新系统银行
				if($iResult==0 && $Capacity>0)
				{
					//系统银行扩容
					$Result = 0;
					$Balance = 0;
					$PreBalance = 0;
					$objBankBLL = new BankBLL();
					$arrResult= DCSetSysBankMoney(1, $Capacity);
					//$arrResult = $objBankBLL->setSysBankMoney(1,$arrReturns['Balance'],$WealthType);
					if(is_array($arrResult) && count($arrResult)>0)
					{
						$Result = $arrResult['iResult'];
						$Balance = $arrResult['iBalance'];
						$PreBalance = $arrResult['iLastBalance'];
					} 
					//如果系统银行扩容失败,系统配置表回滚,否则记录日志
					if($Result<0)
					{
						$Result = $objMasterBLL->setSysConfigCallbank($Capacity,$WealthType);//备注：如果回滚失败，此处应该增加日志以便日后查询
						$iResult = -1;
					}
					else 
					{						
						$arrKeys = array_keys($this->arrConfig['BankAccType']);//返回包含数组中所有键名的一个新数组,下标数组
						$arrParams['TransNo'] = date('YmdHis').rand(1000, 9999);
						$arrParams['AccType'] = $arrKeys[0];//取数组下标
						$arrParams['TargetID'] = 0;
						$arrParams['KindID'] = 0;
						$arrParams['TransType'] = $this->arrConfig['TransType']['TransType2'];
						$arrParams['DCFlag'] = $this->arrConfig['DCFlag']['DCFlag1'];
						$arrParams['Amount'] = $Capacity;
						$arrParams['PreBalance'] = $PreBalance;
						$arrParams['Balance'] = $Balance;
					/* 	$objDataChangeLogsBLL = new DataChangeLogsBLL();
						$objDataChangeLogsBLL->insertSysBankTransLogs($arrParams['AccType'],$arrParams['TargetID'],$arrParams['KindID'],$arrParams['TransType'],
																   $arrParams['DCFlag'],$arrParams['Amount'],$arrParams['PreBalance'],$arrParams['Balance'],'');
					 */											   
						$objSessioin = new Session($this->arrConfig['Session']['SessionLoginName']);
						$iAdminID = $objSessioin->get($this->arrConfig['SessionInfo']['AdminID']);
						$SysUserName = $objSessioin->get($this->arrConfig['SessionInfo']['UserName']);
						$objOperationLogsBLL = new OperationLogsBLL(0);
						$objOperationLogsBLL->addCaseOperationLogs($iAdminID, 0, 27, '系统银行扩容', 0, Utility::getIP(), 0, 2, $SysUserName, '');
																	   
					}
				}
			}
		}
		if($iResult==0)
			$msg='系统银行扩容成功';
		elseif($iResult==-1)
			$msg='系统银行扩容失败';
		elseif($iResult==-2)
			$msg='扩容数量必须大于之前容量';
		elseif($iResult==-3)
			$msg='回滚数量必须小于现有容量';		
		else
			$msg='您提交的参数异常';		

		echo $msg;
	}
	public function getSysBankInfo(){
	   $arrBankInfo = $this->objBankBLL->getSysBankInfo();
	   $iBankInfo = DCQuerySystemBankData();
	   $TotalBalance = 0;
	   if(is_array($arrBankInfo) && count($arrBankInfo)>0)
	   {
	       $iCount = 0;
	       foreach ($arrBankInfo as $key => $bank)
	       {
	           foreach ($iBankInfo['SystemBankInfoList'] as $k => $v){
	               if($bank['AccNo']!=''&&$bank['AccType'] == $v['iBankID']){
	                   $arrBankInfo[$key]['Balance'] = $v['iBalance'];
	                   $arrBankInfo[$key]['LastBalance'] = $v['iLastBalance'];
	               }
	           }
	           $arrBankInfo[$iCount]['AccTypeName']=$this->arrConfig['BankAccType'][$bank['AccType']];
	           $TotalBalance += $arrBankInfo[$iCount]['Balance'];
	           $iCount++;
	       }
	       $arrBankInfo[$iCount]['Balance']=$TotalBalance;
	       $arrBankInfo[$iCount]['AccTypeName']='总计';
	       $arrBankInfo[$iCount]['AccNo']='';
	       	
	   }
	   else
	       $arrBankInfo=null;
	   return $arrBankInfo;
	}
}
?>