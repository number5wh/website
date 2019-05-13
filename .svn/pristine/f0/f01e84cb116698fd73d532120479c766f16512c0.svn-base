<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';

require_once ROOT_PATH . 'Link/GetRoleBaseInfo.php';
class UserBankReturnAction extends PageBase
{	
	private $objMatchBLL = null;	
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);
	}
	public function index()
	{
		$arrResult = null;
		$arrResult = $this->getPagerUserBankRate(20);
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'UserBankRateList'=>$arrResult['arrUserBankRateList'],'EndTime'=>date('Y-m-d'),'StartTime'=>date('Y-m-d'));
		Utility::assign($this->smarty,$arrTags);
		
		$this->smarty->display($this->arrConfig['skin'].'/YunYing/UserBankReturnList.html');
	}	 

	/**
	 * 分页
	 */
    public function getPagerUserBankRate($pagesize){
        $arrUserBankRateList = null;

        $curPage = Utility::isNumeric('curPage',$_POST)?$_POST['curPage']:1;
        $StartTime = Utility::isNullOrEmpty('StartTime',$_POST)?$_POST['StartTime'] : date('Y-m-d');
        $EndTime = Utility::isNullOrEmpty('EndTime',$_POST) ? $_POST['EndTime'] : date('Y-m-d');
        $LoginID = Utility::isNumeric('LoginID',$_POST);
        $RoleID = $LoginID;//当前 LoginID == RoleID

        $orderField ="DealMonery desc";//Utility::isNullOrEmpty('OrderField',$_POST)?$_POST['OrderField'] : " totalMoney desc";        
      
        
        $strWhere=Utility::utf8ToGb2312(" where  a.Information like '%系统返回%' ");
        if(!empty($RoleID)){
          $strWhere.=" and a.RoleID = {$RoleID}";
        }    
        
        
        $StartTime = $StartTime.' 00:00:00';
        $EndTime  = $EndTime.' 23:59:59';
        
        
        if(!empty($StartTime) && !empty($EndTime) ){
            $strWhere.=" and a.dealtime between  '".$StartTime."' and '".$EndTime."'";
        }            
        
        $strTable ='(SELECT  a.RoleID,b.LoginName,SUM(BankMonery) as BankMonery ,SUM(DealMonery) as DealMonery,SUM(c.Money) as gameMoney FROM 
            [CD_UserDB].[dbo].[T_UserBankDealLog] a left join 
            [CD_UserDB].[dbo].[T_Role] b on a.RoleID=b.RoleID  left join 
            CD_UserDB.dbo.T_UserGameWealth c on a.RoleID=c.RoleID'.$strWhere.'  group by a.RoleID,b.LoginName) T ';      

        $arrParam['fields'] = 'RoleID,LoginName,BankMonery,DealMonery,gameMoney';
        $arrParam['Page'] = $curPage;
        $arrParam['pagesize'] = 20;
        $arrParam['where'] = " where 1=1 ";
        $arrParam['tableName'] = $strTable;
        $arrParam['order'] =$orderField;       
        
        $objCommonBLL = new CommonBLL($this->arrConfig['MapType']['DataChangeLogs']);
        $iRecordsCount = $objCommonBLL->getRecordsCountSelect($arrParam);     
        $Page = Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
        
       // print($iRecordsCount);exit();
        
        if($iRecordsCount > 0){
            $arrUserBankRateList = $objCommonBLL->getPageListSelectCache($arrParam,$arrParam['Page']);
          
        }        
        if($arrUserBankRateList)
        {
            $iCount = 0;
            foreach ($arrUserBankRateList as $val)
            {

                $arrUserBankRateList[$iCount]['DealMonery'] = Utility::FormatMoney($val['DealMonery']);
                $arrUserBankRateList[$iCount]['BankMonery'] = Utility::FormatMoney($val['BankMonery']);
                $arrUserBankRateList[$iCount]['gameMoney'] = Utility::FormatMoney($val['gameMoney']);
                $arrUserBankRateList[$iCount]['LoginName'] = Utility::gb2312ToUtf8($val['LoginName']);
                $arrUserBankRateList[$iCount]['LoginID'] = $val['RoleID'];
                if(!$LoginID) $iUserLoginName=null;
                $iCount++;
            }
        }
        return array('arrUserBankRateList'=>$arrUserBankRateList,'Page'=>$Page);
    }
	
	/**
	 * 分页读取
	 */
	public function getPagerUserBankReturnList()
	{
		$arrResult = $this->getPagerUserBankRate(20);
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'UserBankRateList'=>$arrResult['arrUserBankRateList']);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunYing/UserBankReturnListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	
}
?>