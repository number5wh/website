<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/SystemBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/OperationLogsBLL.class.php';
require ROOT_PATH . 'Class/BLL/DataChangeLogsBLL.class.php';

class ServiceManagerAction extends PageBase
{
	private $strLoginedUser = '';
	
	public function __construct()
	{
		$this->arrConfig = unserialize(SYS_CONFIG);
		$this->strLoginedUser = Utility::chkUserLogin($this->arrConfig);
	}
	
	public function index()
	{
		//组装分页查询条件
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage <= 0 ? 1 : $curPage;
		$arrParam['fields'] = 'ID,EvtName,EvtContent,EvtSortID';
		$arrParam['tableName'] = 'T_ServiceEvent';
		$arrParam['where'] = '';
		$arrParam['order'] = 'EvtSortID asc';
		$arrParam['pagesize'] = 15;

		$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['System']);
		$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
		$arrResult = null;
		if($iRecordsCount > 0)
		{
			$arrResult = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);
				
			if(is_array($arrResult) && count($arrResult)>0){
				$i=0;
				foreach ($arrResult as $v){
					$arrResult[$i]['EvtName'] = Utility::gb2312ToUtf8($v['EvtName']);
					$arrResult[$i]['EvtContent'] = Utility::gb2312ToUtf8($v['EvtContent']);
					$i++;
				}
			}
		}
		
		$arrTags=array('skin'=>$this->arrConfig['skin'],
					   'ServerEventList'=>$arrResult,
					   'Page'=>$Page);
		
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/Service/ServerEventIndex.html');
	}
	
	public function getServerEventEditPage()
	{
		$id = Utility::isNumeric('id',$_POST);
		$arrResult = null;
		if($id>0)
		{
			$systemBLL = new SystemBLL();
			$arrResult = $systemBLL->getServerEventByID($id);
		}
		
		$arrTags=array('skin'=>$this->arrConfig['skin'],
					   'ServerEventInfo'=>$arrResult);
		
		Utility::assign($this->smarty,$arrTags);
		
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/ServerEventEditPage.html');
		echo $html;
	}
	
	public function addServerEvent()
	{
		$errorMsg = '';//错误信息
		$evtId = Utility::isNumeric('evtId', $_POST) ? $_POST['evtId'] : 0;
		$evtName = isset($_POST['evtName']) ? $_POST['evtName'] : '';
		$evtDescription = isset($_POST['evtDesc']) ? $_POST['evtDesc'] : '';
		$evtSort = Utility::isNumeric('evtSort', $_POST) ? $_POST['evtSort'] : 0;

		if(empty($evtName))
		{
			$errorMsg .= '请填写事件名称<br/>';
		}
		if(empty($evtDescription))
		{
			$errorMsg .= '请填写事件描述<br/>';
		}
		if($evtSort < 0)
		{
			$errorMsg .= '请填写正确的排序，必须大于零<br/>';
		}
		
		if(!empty($errorMsg))
		{
			echo '{"iResult":-1,"msg":"' . $errorMsg . '"}';
		}else
		{
			$systemBLL = new SystemBLL();
			$arrResult = $systemBLL->addServerEvent($evtId,$evtName,$evtDescription,$evtSort);
			if(is_array($arrResult) && count($arrResult)>0 && $arrResult['iResult'] == 0)
			{
				echo '{"iResult":1,"msg":"服务事件保存成功"}';
			}else{
				echo '{"iResult":-1,"msg":"服务事件保存失败"}';
			}
		}
	}
}
?>