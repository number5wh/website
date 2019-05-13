<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';

class SysConfineAction extends PageBase
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
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/SysConfineNameList.html');
	}	
	/**
	 * 显示添加版本页面
	 */
	public function showAddSysConfineNameHtml()
	{		
		$ID = Utility::isNumeric('ID',$_POST);
		//读取敏感词
		if($ID)
			$arrSysConfineInfo = $this->objMasterBLL->getSysConfineNameInfo($ID);
		else
			$arrSysConfineInfo = array('ID'=>0,'LoginName'=>'');

		$arrTags = array('confine'=>$arrSysConfineInfo);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/SysConfineNameEdit.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}	
	/**
	 * 分页
	 */
	public function getPagerSysConfine()
	{
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage<=0 ? 1 : $curPage;
		$arrParam['fields']='ID,LoginName,CONVERT(VARCHAR(20),CreatedDate,120) AS CreatedDate';
		$arrParam['tableName']='T_SysConfineLoginName';
		$arrParam['where']='';
		$arrParam['order']='CreatedDate DESC,ID';
		$arrParam['pagesize']=20;

		$objCommonBLL = new CommonBLL(0);
		$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);		
		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
		$arrSysConfineList = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);
		if(is_array($arrSysConfineList) && count($arrSysConfineList)>0)
		{		
			$iCount = 1;
			foreach($arrSysConfineList as $key => $val)
			{
				$arrSysConfineList[$key]['iCount'] = $iCount++;
				$arrSysConfineList[$key]['LoginName']=Utility::gb2312ToUtf8($val['LoginName']);
			}
		}
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'SysConfineList'=>$arrSysConfineList);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/SysConfineNameListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	/**
	 * 添加敏感词
	 */
	public function addSysConfineName()
	{
		$iResult = -9999;
		$iCount = 0;
		$iCountFail = 0;
		$ID = Utility::isNumeric('ConfineID',$_POST);
		$LoginName = Utility::isNullOrEmpty('LoginName',$_POST);	

		if($LoginName)
		{
			$arrLoginName = explode(',', $LoginName);
			
			if(is_array($arrLoginName) && count($arrLoginName)>0)
			{
				for ($i=0;$i<count($arrLoginName);$i++)
				{					
					//$iResult 0:成功,-1:失败
					if(!empty($arrLoginName[$i]))
					{
						$iResult = $this->objMasterBLL->addSysConfineName($ID,$arrLoginName[$i]);
						if($iResult==0)
							$iCount++;
						else 
							$iCountFail++;
					}
				}
			}			
			$msg='敏感词设置完成,成功:'.$iCount.'个,失败:'.$iCountFail.'个';
		}
		else
			$msg='对不起,您提交的数据异常,请重试';
		echo $msg;
	}
	/**
	 * 删除敏感词
	 * @return 大于0:成功,0:失败,-2:数据库异常
	 */
	public function delSysConfineName()
	{
		$iResult = -9999;
		$ID = Utility::isNumeric('ID',$_POST);
		if($ID && $ID>0)
			$iResult = $this->objMasterBLL->delSysConfineName($ID);
		if($iResult==0)
			$msg='敏感词删除成功';
		elseif($iResult==-1)
			$msg='敏感词删除失败';
		else
			$msg='对不起,您提交的数据异常,请重试';
		
		echo json_encode(array('iResult'=>$iResult,'Msg'=>$msg));
	}
}
?>