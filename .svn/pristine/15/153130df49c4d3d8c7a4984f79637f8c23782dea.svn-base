<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserBLL.class.php';
require ROOT_PATH . 'Class/BLL/MatchBLL.class.php';
class MatchRechargeAction extends PageBase
{	
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);		
	}
	public function index()
	{		
		$arrResult = $this->getPagerRecharge(1,'');
		$arrTags=array( 'skin'=>$this->arrConfig['skin'],
						'Page'=>$arrResult['Page'],
						'RechargeList'=>$arrResult['arrRechargeList']						
						); 
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunYing/GameMatchRechargeList.html');
	}	

	/**
	 * 分页读取单元赛记录
	 */
	public function getPagerRechargeList()
	{
		$strWhere = ' WHERE 1=1';
		$Mobile=Utility::isNullOrEmpty('Mobile',$_POST);
		$LoginID=Utility::isNumeric('LoginID',$_POST);		
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage==0 ? 1 : $curPage;
		
		if($Mobile) $strWhere .= " AND Mobile='$Mobile'";
		if($LoginID)
		{
			$objMasterBLL = new MasterBLL();
			$arrRoleInfo = $objMasterBLL->getRoleIDByKeyID($LoginID,2);
			if(!empty($arrRoleInfo))
			{
				$RoleID = $arrRoleInfo['RoleID'];
				$strWhere .= " AND RoleID=".$RoleID;
			}
			else 
				$strWhere .= " AND RoleID=-1";
		}
		$arrResult = $this->getPagerRecharge($curPage,$strWhere);
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'RechargeList'=>$arrResult['arrRechargeList']); 
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunYing/GameMatchRechargeListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}	
	/**
	 * 分页
	 */
	private function getPagerRecharge($curPage,$strWhere)
	{
		//$http = '';
		//$curPage = Utility::isNumeric('curPage',$_POST);
		//$curPage = $curPage==0 ? 1 : $curPage;
		$arrParam['fields']='RID,RoleID,Mobile,Amount,OrderID,RetCode,RetMsg,CONVERT(VARCHAR(20),AddTime,120) AS AddTime';
		$arrParam['tableName']='T_UserRecharge';
		$arrParam['where']=$strWhere;
		$arrParam['order']='AddTime DESC';
		$arrParam['pagesize']=20;
		
		$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['Match']);
		$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
		$arrRechargeList = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);	
		if($arrRechargeList)
		{
			$iCount = 0;
			
			foreach ($arrRechargeList as $val)
			{
				$objUserBLL = new UserBLL($val['RoleID']);
				$arrRoleInfo = $objUserBLL->getRole(0);				
				if($arrRoleInfo)
				{
					$arrRechargeList[$iCount]['LoginName'] = $arrRoleInfo['LoginName'];
					$arrRechargeList[$iCount]['LoginID'] = $arrRoleInfo['LoginID'];
				}
				else 
				{
					$arrRechargeList[$iCount]['LoginName'] = '';	
					$arrRechargeList[$iCount]['LoginID'] = '';
				}			
				$arrRechargeList[$iCount]['RetMsg'] = Utility::gb2312ToUtf8($val['RetMsg']);
				$iCount++;
			}
		}
		return array('arrRechargeList'=>$arrRechargeList,'Page'=>$Page);
	}
	
	/**
	 * 设置玩家手机充值的状态
	 */
	public function setRecharge()
	{		
		$iResult = -1;
		$RechargeTime = '';
		$RID = Utility::isNumeric('RID', $_POST);
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage==0 ? 1 : $curPage;
		if($RID && $RID>0)
			$arrResult = $this->getPagerRecharge($curPage," WHERE RID=$RID");
		else
			$arrResult = $this->getPagerRecharge($curPage,' WHERE RetCode<>0');
		if($arrResult)
		{
			$objMatchBLL = new MatchBLL();
			foreach ($arrResult['arrRechargeList'] as $val)
			{
				$Params = "&OrderID=".$val['OrderID'];
				$Sign=MD5($this->arrConfig['HttpRequest'][1]['Param'].$Params.'||'.$this->arrConfig['HttpRequest'][1]['Key']);
				$content = file_get_contents($this->arrConfig['HttpRequest'][1]['Url'].'?'.$this->arrConfig['HttpRequest'][1]['Param'].$Params."&Sign=$Sign");
				preg_match_all("/\<RetCode\>(\d*)\<\/RetCode\><RetMsg>(.*)<\/RetMsg>/i", $content,$arrRet);
				if($arrRet && $arrRet[1][0]!=$val['RetCode'])
				{
					$RetMsg = urldecode($arrRet[2][0]);
					$objMatchBLL->setRecharge($RID,$arrRet[1][0],$RetMsg);
				}
				
			}			
			if($curPage>=$arrResult['Page']['TotalPage'])
				$curPage = 0;
			else 
				$curPage++;			
		}
		else		
			$curPage = 0;
		echo json_encode(array('CurPage'=>$curPage,'TotalPage'=>$arrResult['Page']['TotalPage']));
	}
	
	
	
	
	
	
}
?>