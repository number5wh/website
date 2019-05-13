<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/PassAccountBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserDataBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserBLL.class.php';
require ROOT_PATH . 'Class/BLL/OperationLogsBLL.class.php';
require ROOT_PATH . 'Link/GetRoleBaseInfo.php';
require ROOT_PATH . 'Link/QueryRoleRight.php';
require ROOT_PATH . 'Link/SetRoleRight.php';

class PlayerAAction extends PageBase
{	
	private $MasterRight = 30464;
	private $objMasterBLL = null;
	public function __construct()
	{

	    $this->objMasterBLL = new MasterBLL();
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);		
	}

	
	public function index()
	{
		$arrResult = $this->getPagerPlayerA(1,'');
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'PlayerAList'=>$arrResult['List']); 
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/PlayerAList.html');
	}
	/**
	 * 分页
	 */
	private function getPagerPlayerA($curPage,$strWhere)
	{
		$arrParam['fields']='RID,RoleID,SystemRight,MasterRight,RoleName';
		$arrParam['tableName']='T_UserPrivilege';
		$arrParam['where']='';//" WHERE MasterRight=$this->MasterRight ".$strWhere;
		$arrParam['order']='MasterRight,RID DESC';
		$arrParam['pagesize']=20;
		$objCommonBLL = new CommonBLL(0);
		$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
		$arrResult = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);
		foreach ($arrResult as $key=>$val){
		    $arrResult[$key]['RoleName'] = Utility::gb2312ToUtf8($val['RoleName']);
		} 
		//print_r($arrResult);exit;
		/* if($arrResult)
		{
		
			$iCount = 0;
			foreach ($arrResult as $val)
			{
				$objUserBLL = new UserBLL($val['RoleID']);
				$arrUserInfo = $objUserBLL->getRole(0,$val['RoleID']);				
				if($arrUserInfo)
				{
					$arrResult[$iCount]['LoginName'] = $arrUserInfo['LoginName'];	
					$arrResult[$iCount]['LoginID'] = $arrUserInfo['LoginID'];
					$arrResult[$iCount]['Passport'] = $arrUserInfo['Passport'];
				}
				else
				{
					$arrResult[$iCount]['LoginName'] = '';
					$arrResult[$iCount]['LoginID'] = 0;
					$arrResult[$iCount]['Passport'] = 0;
				}
				$iCount++;
			}
		} */
		return array('List'=>$arrResult,'Page'=>$Page);
	}
	/**
	 * 分页
	 */
	public function getPagerPlayerAList()
	{
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage==0 ? 1 : $curPage;
		$arrResult = $this->getPagerPlayerA($curPage,'');
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'PlayerAList'=>$arrResult['List']); 
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/PlayerAListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}

	/**
	 * 批量添加玩家
	 */
	public function SetPlayerA()
	{
		$iResult = -1;
		$arrParams['LoginCode'] = Utility::isNullOrEmpty('LoginCode',$_POST);
		$arrParams['MasterRight'] = $this->MasterRight;		
		$iResult = $this->insertPlayerA($arrParams);

		echo json_encode(array('iResult'=>$iResult,'LoginCode'=>$arrParams['LoginCode']));
	}
	private function insertPlayerA($arrParams)
	{
		$iResult = -1;
		//读取通行证ID号
		//$objPassAccountBLL = new PassAccountBLL();
		$arrAccount = getUserBaseInfo($arrParams['LoginCode']);
		if(isset($arrAccount['RealName'])&&$arrAccount['RealName']!='')
		{
            $RoleID = $arrParams['LoginCode'];//不知道为什么穿的是Logincode
			//$objUserBLL = new UserBLL(0);
			//$arrResult = $objUserBLL->addPlayerA($arrParams);//添加玩家
			//if(is_array($arrResult) && count($arrResult)>0)
			//{
			//查询玩家权限
			$arrParams = DSQueryRoleRight($arrAccount['RoleID']);
			$arrParams['RoleName'] = $arrAccount['RealName'];
			//添加玩家权限
			//$iResult = DSSetRoleRight($arrAccount['RoleID'],$arrParams['iUserRight'],$arrParams['iMasterRight']);
			$iResult = $this->objMasterBLL->addUserPrivilege($arrParams);	
			//添加管理员操作日志
			$objSessioin = new Session($this->arrConfig['Session']['SessionLoginName']);
			$iAdminID = $objSessioin->get($this->arrConfig['SessionInfo']['AdminID']);
			$SysUserName = $objSessioin->get($this->arrConfig['SessionInfo']['UserName']);

            $userPrivilege = $this->objMasterBLL->getUserPrivilege($RoleID);

			$objOperationLogsBLL = new OperationLogsBLL(0);
			$objOperationLogsBLL->addCaseOperationLogs($iAdminID, 0, 31, '权限设置,添加系统玩家,玩家账号:'.$arrParams['iRoleID'].",玩家昵称:".$userPrivilege['RoleName'], $iResult, Utility::getIP(), 0, 2, $SysUserName, '');
			//}				
		}else {
		    $iResult = -3;
		}
		return $iResult;
	}

	
	/**
	 * 删除权限
	 */
	public function deletePlayerA()
	{
		$iResult = -1;
		$ID = Utility::isNumeric('ID',$_POST);	
		$RoleID = Utility::isNumeric('RoleID',$_POST);	
		$RoleName = Utility::isNullOrEmpty('RoleName', $_POST);
		if($RoleID)
		{
			//$objUserBLL = new UserBLL(0);
			//删除玩家权限
			$iResult = DSSetRoleRight($RoleID, 0, 0,0);
			$iResult = $iResult['iResult'];
			if($iResult == 0){
			    $iResult = $this->objMasterBLL->delUserPrivilege($RoleID);
			    //$iResult = $objUserBLL->delUserPrivilege($RoleID);
			    //添加管理员操作日志
			    $objSessioin = new Session($this->arrConfig['Session']['SessionLoginName']);
			    $iAdminID = $objSessioin->get($this->arrConfig['SessionInfo']['AdminID']);
			    $SysUserName = $objSessioin->get($this->arrConfig['SessionInfo']['UserName']);
			    $objOperationLogsBLL = new OperationLogsBLL(0);
                $userPrivilege = $this->objMasterBLL->getUserPrivilege($RoleID);

			    $objOperationLogsBLL->addCaseOperationLogs($iAdminID, 0, 31, "权限设置,删除管理员玩家,玩家id:{$RoleID},玩家昵称:".$userPrivilege['RoleName'], $iResult, Utility::getIP(), 0, 2, $SysUserName, '');
			     
			}
				
		}
		echo json_encode(array('iResult'=>$iResult,'ID'=>$ID));
	}
	
	/**
	 * 显示设置管理员权限html
	 */
	public function showSetRoleRightHtml()
	{
	    $RoleID = Utility::isNumeric('RoleID',$_POST);
	    $arrRoleRightInfo = $this->objMasterBLL->getUserPrivilege($RoleID);
		if(!$arrRoleRightInfo) $arrRoleRightInfo['RoleID'] = $RoleID;
		$arrTags=array('Role'=>$arrRoleRightInfo,'MasterRight'=>$this->arrConfig['MasterRight'],'UserRight'=>$this->arrConfig['UserRight'],'SystemRight'=>$this->arrConfig['SystemRight']); 
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/PlayerAEdit.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		
		echo $html;
	}
	
	/**
	 * 设置管理员权限
	 * 
	 * */
	public function setRoleRight(){
	    $RoleID = Utility::isNumeric('RoleID',$_POST);
	    $MasterRight = Utility::isNumeric('MasterRight', $_POST);
	    $UserRight = Utility::isNumeric('UserRight', $_POST);
	    $SystemRight = Utility::isNumeric('SystemRight', $_POST);
	    $arrResult = DSSetRoleRight($RoleID, $UserRight, $MasterRight,$SystemRight);
	    $iResult = $arrResult['iResult'];
	    if($iResult == 0){
	        $iResult = $this->objMasterBLL->setUserPrivilege($arrResult['iRoleID'],$arrResult['iUserRight'],$arrResult['iMasterRight'],$arrResult['iSystemRight']);
            //添加管理员操作日志
            $objSessioin = new Session($this->arrConfig['Session']['SessionLoginName']);
            $SysUserName = $objSessioin->get($this->arrConfig['SessionInfo']['UserName']);
            $objOperationLogsBLL = new OperationLogsBLL(0);
            $str = "";
            $userPrivilege = $this->objMasterBLL->getUserPrivilege($RoleID);

            $list =$this->getPrivateRight($MasterRight,1);
            if(count($list)) $str .= " 管理权限：".implode(',',Utility::array_column($list,'RightName'));
            $list = $this->getPrivateRight($UserRight,2);
            if(count($list)) $str .= " 用户权限：".implode(',',Utility::array_column($list,'RightName'));
            $list = $this->getPrivateRight($SystemRight,3);
            if(count($list)) $str .= " 系统权限：".implode(',',Utility::array_column($list,'RightName'));
            $objOperationLogsBLL->addCaseOperationLogs($RoleID, 0, 31, "管理员玩家权限设置,玩家id:{$RoleID},玩家昵称:".$userPrivilege['RoleName']."。权限设置 ".$str, $iResult, Utility::getIP(), 0, 2, $SysUserName, '');
	    }
	    echo $iResult;
	    
	}

    /**
     * @param $right
     * @param $type
     */
    private function getPrivateRight($right,$type = 1){
        $rightList = array();
        switch($type){
            case 1:
                $rightList = $this->arrConfig['MasterRight'];
                break;
            case 2:
                $rightList = $this->arrConfig['UserRight'];
                break;
            case 3:
                $rightList = $this->arrConfig['SystemRight'];
                break;
        }
        $ret = array();
        foreach($rightList as $key =>$val){
            if($val['RightID'] & $right){
                $ret[] = $val;
            }
        }
        return $ret;
    }
}
?>