<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/BankChangeLogsBLL.class.php';

class SysBankChangeLogsAction extends PageBase
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
		
		
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/SysBankChangeLogs.html');
	}	 

	/**
	 * 分页读取
	 */
	public function getChangeLogsFormList()
	{
		$StartTime = Utility::isNullOrEmpty('StartTime',$_POST) ? $_POST['StartTime'] : date('Y-m-d');
		$EndTime = Utility::isNullOrEmpty('EndTime',$_POST) ? $_POST['EndTime'] : date('Y-m-d');
		$objBankChangeLogsBLL = new BankChangeLogsBLL(0);
		$arrResult = $objBankChangeLogsBLL->getRechargeOrderCount($StartTime, $EndTime);
        $BankAccType = $this->arrConfig['BankAccType'];
        $Total['DrawMoney'] = 0;
        $Total['DepositMoney'] = 0;
        $Total['TransferInMoney'] = 0;
        $Total['TransferOutMoney'] = 0;
        $Total['ExpansionMoney'] = 0;
        $BankChangeLogsList = array();
        if($arrResult){
            foreach ($arrResult as $key => $val){
                $BankID = $val['BankID'];
                if(!isset($BankChangeLogsList[$BankID])){
                    $BankChangeLogsList[$BankID] = array();
                    $BankChangeLogsList[$BankID]['DrawMoney'] = sprintf("%.2f",$val['DrawMoney']/1000);
                    $BankChangeLogsList[$BankID]['DepositMoney'] = sprintf("%.2f",$val['DepositMoney']/1000);
                    $BankChangeLogsList[$BankID]['TransferInMoney'] = sprintf("%.2f",$val['TransferInMoney']/1000);
                    $BankChangeLogsList[$BankID]['TransferOutMoney'] = sprintf("%.2f",$val['TransferOutMoney']/1000);
                    $BankChangeLogsList[$BankID]['ExpansionMoney'] = sprintf("%.2f",$val['ExpansionMoney']/1000);
                }else{
                    $BankChangeLogsList[$BankID]['DrawMoney'] = sprintf("%.2f",($BankChangeLogsList[$BankID]['DrawMoney'] + $val['DrawMoney'])/1000);
                    $BankChangeLogsList[$BankID]['DepositMoney'] = sprintf("%.2f",($BankChangeLogsList[$BankID]['DepositMoney'] + $val['DepositMoney'])/1000);
                    $BankChangeLogsList[$BankID]['TransferInMoney'] = sprintf("%.2f",($BankChangeLogsList[$BankID]['TransferInMoney'] + $val['TransferInMoney'])/1000);
                    $BankChangeLogsList[$BankID]['TransferOutMoney'] = sprintf("%.2f",($BankChangeLogsList[$BankID]['TransferOutMoney'] + $val['TransferOutMoney'])/1000);
                    $BankChangeLogsList[$BankID]['ExpansionMoney'] = sprintf("%.2f",($BankChangeLogsList[$BankID]['ExpansionMoney'] + $val['ExpansionMoney'])/1000);
                }
                $Total['DrawMoney'] = sprintf("%.2f",($Total['DrawMoney'] + $val['DrawMoney'])/1000);
                $Total['DepositMoney'] = sprintf("%.2f",($Total['DepositMoney'] + $val['DepositMoney'])/1000);
                $Total['TransferInMoney'] = sprintf("%.2f",($Total['TransferInMoney'] + $val['TransferInMoney'])/1000);
                $Total['TransferOutMoney'] = sprintf("%.2f",($Total['TransferOutMoney'] + $val['TransferOutMoney'])/1000);
                $Total['ExpansionMoney'] = sprintf("%.2f",($Total['ExpansionMoney'] + $val['ExpansionMoney'])/1000);
            }
            foreach ($BankChangeLogsList as $key => $val){
                foreach ($BankAccType as $BankID => $BankType){
                    if($key == $BankID){
                        $BankChangeLogsList[$key]['BankType'] = $BankType;
                    }
                }
                $BankChangeLogsList[$key]['SysMoney'] =sprintf("%.2f",($val['DepositMoney'] - $val['DrawMoney'])/1000);
            }
            $Total['SysMoney'] = sprintf("%.2f",($Total['DepositMoney'] - $Total['DrawMoney'])/1000);
        }
		$arrTags=array('skin'=>$this->arrConfig['skin'],'BankChangeLogsList'=>$BankChangeLogsList,'Total'=>$Total);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/SysBankChangeLogsList.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;  
	}
	
}
?>