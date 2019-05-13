<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';

class ExtraProgramAction extends PageBase
{	
	private $objMasterBLL = null;
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);
		$this->objMasterBLL = new MasterBLL();
	}
	public function index()
	{		
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/ExtraProgramList.html');
	}	
	/**
	 * 显示添加版本页面
	 */
	public function showAddExtraProgramHtml()
	{		
		$ExtraProgramKey = Utility::isNullOrEmpty('ExtraProgramKey',$_POST);
		//读取敏感词
		if($ExtraProgramKey)
			$arrExtraProgramInfo = $this->objMasterBLL->getExtraProgram($ExtraProgramKey);
		else
			$arrExtraProgramInfo = array('ExtraProgramKey'=>'','LoginName'=>'');

		$arrTags = array('ExtraProgram'=>$arrExtraProgramInfo);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/ExtraProgramEdit.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}	
	/**
	 * 分页
	 */
	public function getPagerExtraProgram()
	{
		$curPage = Utility::isNullOrEmpty('curPage',$_POST);
		$curPage = $curPage<=0 ? 1 : $curPage;
		$arrParam['fields']=' ExtraProgramKey,ExtraProgramName ';
		$arrParam['tableName']='T_ExtraProgramList';
		$arrParam['where']='';
		$arrParam['order']='ExtraProgramKey';
		$arrParam['pagesize']=20;

		$objCommonBLL = new CommonBLL(0);
		$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);		
		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
		$arrExtraProgramList = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);
		if(is_array($arrExtraProgramList)){
		    foreach ($arrExtraProgramList as $key=>$val){
		        $arrExtraProgramList[$key]['ExtraProgramKey'] = Utility::gb2312ToUtf8($val['ExtraProgramKey']);
		        $arrExtraProgramList[$key]['ExtraProgramName'] = Utility::gb2312ToUtf8($val['ExtraProgramName']);
		    }
		}
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'ExtraProgramList'=>$arrExtraProgramList);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/ExtraProgramListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	/**
	 * 添加敏感词
	 */
	public function addExtraProgram()
	{
		$iResult = -9999;
		$iCount = 0;
		$iCountFail = 0;
		$OldExtraProgramKey = Utility::isNullOrEmpty('OldExtraProgramKey',$_POST);
		$ExtraProgramKey = Utility::isNullOrEmpty('ExtraProgramKey',$_POST);
		$ExtraProgramName = Utility::isNullOrEmpty('ExtraProgramName',$_POST);
		$iResult = $this->objMasterBLL->addExtraProgram($OldExtraProgramKey,$ExtraProgramKey,$ExtraProgramName);
		if($iResult == 0)	{
			$msg='外挂设置成功';
		}else if($iResult == -2){
			$msg = '关键词已存在';
		}else{
			$msg='对不起,您提交的数据异常,请重试';
		
		}
		echo $msg;
	}
	/**
	 * 删除敏感词
	 * @return 大于0:成功,0:失败,-2:数据库异常
	 */
	public function delExtraProgram()
	{
		$iResult = -9999;
		$ExtraProgramKey = Utility::isNullOrEmpty('ExtraProgramKey',$_POST);
		if($ExtraProgramKey)
			$iResult = $this->objMasterBLL->delExtraProgram($ExtraProgramKey);
		if($iResult==0)
			$msg='外挂删除成功';
		elseif($iResult==-1)
			$msg='外挂删除失败';
		else
			$msg='对不起,您提交的数据异常,请重试';
		
		echo json_encode(array('iResult'=>$iResult,'Msg'=>$msg));
	}
}
?>