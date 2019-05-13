<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/BankBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/DataChangeLogsBLL.class.php';

class SysBankFwAction extends PageBase
{	
	private $objBankBLL = null;	
	private $iWealthType = 1;
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);
		$this->objBankBLL = new BankBLL();
	}
	public function index()
	{
		//取系统配置信息
		$objMasterBLL = new MasterBLL();
		$arrSysConfig = $objMasterBLL->getSysConfigInfo();	
		//取银行信息
		$arrSysBank = $this->objBankBLL->getSysBankInfo();
		
		$arrTags=array('BankList'=>$arrSysBank,'WealthType'=>$this->iWealthType,'ClassName'=>'SysBankFw','Sys'=>$arrSysConfig,'AccTypeList'=>$this->arrConfig['BankAccType']);
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/SysBankList.html');
	}	 
	/**
	 * 显示添加银行账户页面
	 */
	public function showAddBankAccHtml()
	{		
		$arrBank['iAccType'] = Utility::isNumeric('AccType',$_POST);
		$arrSysBank = $this->objBankBLL->getSysBankInfo();
		if(is_array($arrSysBank) && count($arrSysBank)>0)
		{
			foreach ($arrSysBank as $val)
			{
				if($arrBank['iAccType']==$val['AccType'])
				{
					$arrBank['iAccNo']=$val['AccNo'];
					break;
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
		if($iFromAccType && $iWealthType && $iMoney && $iToAccType)
		{
			//转出账户跟转入账户不能一致
			if($iFromAccType==$iToAccType)
				$iResult = -8888;
			else
				$iResult = $this->objBankBLL->transSysBankMoney($iFromAccType,$iWealthType,$iMoney,$iToAccType);
			if($iResult==0)
				$iResult=Utility::echoResultHtml($this->smarty,'确 定','main.CloseMsgBox(true,\'SysBankFw\')','银行账户转账成功','true','SysBankFw',$this->arrConfig);
		}
		echo $iResult;
	}
	public function showAddBankCapacityHtml()
	{
		$objMasterBLL = new MasterBLL();
		$arrSysConfig = $objMasterBLL->getSysConfigInfo();		
		$arrSysConfig['ClassName']='SysBankFw';
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
		if($WealthType && $Capacity && $WealthType>0 && $Capacity>0)
		{
			$objMasterBLL = new MasterBLL();
			$arrReturns = $objMasterBLL->setSysConfig($Capacity,$WealthType);
			if(isset($arrReturns) && is_array($arrReturns) && count($arrReturns)>0)
			{
				$iResult=$arrReturns['iResult'];
				//如果金币或龙币扩容量大于0,同时更新系统银行
				if($iResult==0 && $arrReturns['Balance']>0)
				{
					//系统银行扩容
					$Result = 0;
					$Balance = 0;
					$PreBalance = 0;
					$objBankBLL = new BankBLL();
					$arrResult = $objBankBLL->setSysBankMoney(1,$arrReturns['Balance'],$WealthType);
					if(is_array($arrResult) && count($arrResult)>0)
					{
						$Result = $arrResult['iResult'];
						$Balance = $arrResult['FwMoney'];
						$PreBalance = $arrResult['FwLastBalance'];
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
						$arrParams['Amount'] = $arrReturns['Balance'];
						$arrParams['PreBalance'] = $PreBalance;
						$arrParams['Balance'] = $Balance;
						$objDataChangeLogsBLL = new DataChangeLogsBLL();
						$objDataChangeLogsBLL->addSysBankTransLogs($arrParams);
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
}
?>