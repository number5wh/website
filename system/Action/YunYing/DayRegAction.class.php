<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserDataBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/OperationLogsBLL.class.php';
require ROOT_PATH . 'Class/BLL/DataChangeLogsBLL.class.php';

class DayRegAction extends PageBase {
	public function __construct() {
		$this->arrConfig = unserialize ( SYS_CONFIG );
		Utility::chkUserLogin ( $this->arrConfig );
	}
public function index()
	{
		$arrResult = $this->getPagerRegUser(20);
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'arrRegUserList'=>$arrResult['arrRegUserList'],);
		Utility::assign($this->smarty,$arrTags);		
		
		$this->smarty->display($this->arrConfig['skin'].'/YunYing/DayRegList.html');
	}	 

	/**
	 * 分页
	 */
	public function getPagerRegUser($pagesize)
	{
		$arrRegUserList = null;
		$strWhere = ' ';
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage<=0 ? 1 : $curPage;		
		
		$arrParam['fields']='RoleID,AccountID,AccountName,RegIP,LastLoginIP,LoginName,MoorMachine,VipExpireTime,Locked,LockEndTime,Money,Status,CONVERT(VARCHAR(20),RegisterTime,120) AS RegisterTime';//LoginCode,Phone,Realname,CONVERT(VARCHAR(20),AddTime,120) AS AddTime'..'
		$arrParam['tableName']='CD_Account.dbo.vw_dayreguser';
		$arrParam['where']=$strWhere;
		$arrParam['order']='RegisterTime DESC';
		$arrParam['pagesize']=$pagesize;

		$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['DataChangeLogs']);
		$iRecordsCount =$objCommonBLL->getRecordsCountSelect($arrParam);	
		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
		if($iRecordsCount>0)
			$arrRegUserList = $objCommonBLL->getPageListSelect($arrParam,$Page['CurPage']);
		if($arrRegUserList)
		{
			$iCount = 0;
			foreach ($arrRegUserList as $val)
			{
                $arrRegUserList[$iCount]['Money'] = Utility::FormatMoney($val['Money']);
				$arrRegUserList[$iCount]['LoginName'] = Utility::gb2312ToUtf8($val['LoginName']);
                $arrRegUserList[$iCount]['Money'] =Utility::FormatMoney($val['Money']);
				$iCount++;
			}
		}		
		return array('arrRegUserList'=>$arrRegUserList,'Page'=>$Page);
	}
	/**
	 * 分页读取
	 */
	public function getPagerUserList()
	{	   
		$arrResult = $this->getPagerRegUser(20);	
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'arrRegUserList'=>$arrResult['arrRegUserList']);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunYing/DayRegUserListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
}
?>
