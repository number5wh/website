<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/9/19
 * Time: 12:52
 */
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/SystemBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserDataBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/BankBLL.class.php';
require ROOT_PATH . 'Class/BLL/StagePropertyBLL.class.php';
require ROOT_PATH . 'Class/BLL/OperationLogsBLL.class.php';


require_once ROOT_PATH . 'Link/SetSuperPlayer.php';
require_once ROOT_PATH . 'Link/GetRoleBaseInfo.php';
class SuperUserAction extends PageBase{

    public function __construct(){
        $this->arrConfig = unserialize(SYS_CONFIG);
        $this->strLoginedUser = Utility::chkUserLogin($this->arrConfig);
    }
    public function index(){
        $this->smarty->display($this->arrConfig['skin'].'/YunWei/SuperUser.html');
    }

    /**
     * 获取添加超级页面
     */
    public function getAddSuperUserPage(){
        $this->smarty->display($this->arrConfig['skin'].'/YunWei/SuperUserPage.html');
    }
    public function getEditSuperUserPage(){
        $iRoleId = Utility::isNumeric('roleID',$_POST)?$_POST['roleID']:0;
        $arrTags = array('RoleID'=>$iRoleId);
        Utility::assign($this->smarty,$arrTags);
        $this->smarty->display($this->arrConfig['skin'].'/YunWei/SuperUserEditPage.html');
    }
    /**
     * 添加超级用户
     */
    public function addSuperUser(){
        $iRoleId = Utility::isNumeric('roleId',$_POST)?$_POST['roleId']:0;
        $iSuperLevel = Utility::isNumeric('number',$_POST)?$_POST['number']:0;
        if($iRoleId <= 0 || $iSuperLevel < 0){
            echo -1;
            exit();
        }
        $objMasterBLL = new MasterBLL();
        $iResult = -1;
        $strMsg = "操作失败";
        $arrResult = DCSetSuperPlayer($iRoleId,$iSuperLevel);
        //var_dump($arrResult);
        if(is_array($arrResult) && isset($arrResult['iResult'])){
            if($arrResult['iResult']===0){
                $iResult = 0;
                $strMsg = "操作成功";
            }
            $ret = getUserBaseInfo($iRoleId);
            $objMasterBLL->addSuperUser($iRoleId,$iSuperLevel,Utility::utf8ToGb2312($ret['RealName']));

            $msg = "商人等级修改成：{$iSuperLevel},玩家ID：{$iRoleId},玩家昵称：".($ret['RealName']);
            //做日志操作
            $objSessioinLogin = new Session($this->arrConfig['Session']['SessionLoginName']);
            $SysUserName = $objSessioinLogin->get($this->arrConfig['SessionInfo']['UserName']);
            $objOperationLogsBLL = new OperationLogsBLL(0);

            $objOperationLogsBLL->addCaseOperationLogs($iRoleId, 0, 31, $msg, 0, Utility::getIP(), 0, 2, $SysUserName, '');
        }
        $arrTags = array('iResult'=>$iResult,'resultMsg'=>$strMsg);
        Utility::assign($this->smarty,$arrTags);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/msgbox.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
        //echo json_encode(array('iResult'=>$iResult,'msg'=>$strMsg));
    }
    /**
     *
     */
    public function getPagerSuperUser(){

        $RoleID = Utility::isNumeric('RoleID',$_POST);
        $strWhere = " WHERE 1=1 ";
        if($RoleID){
            $strWhere .= "AND RoleID = {$RoleID}";
        }
        $arrParam['fields']='RoleID,RealName,SuperLevel,CONVERT(VARCHAR(100),UpdateTime,120) AS AddTime';
        $arrParam['tableName']='T_SuperUser';
        $arrParam['where']=$strWhere;//" WHERE MasterRight=$this->MasterRight ".$strWhere;
        $arrParam['order']='RoleID DESC';
        $arrParam['pagesize']=10;
        $objCommonBLL = new CommonBLL(0);
        $curPage = Utility::isNumeric('curPage',$_POST)?$_POST['curPage']:0;
        $iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
        $Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
        $arrResult = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);

        foreach($arrResult as &$val){
            $val['RealName'] = Utility::gb2312ToUtf8($val['RealName']);
        }
        unset($val);

        $arrTags = array('List'=>$arrResult,'Page'=>$Page,'skin'=>$this->arrConfig['skin']);
        Utility::assign($this->smarty,$arrTags);

        $html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/SuperUserListPage.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }
    public function updateSuperUser(){
        $iRoleId = Utility::isNumeric('roleId',$_POST)?$_POST['roleId']:0;
        $iSuperLevel = Utility::isNumeric('number',$_POST)?$_POST['number']:0;
        if($iRoleId <= 0 || $iSuperLevel < 0){
            echo -1;
            exit();
        }
        $objMasterBLL = new MasterBLL();
        $iResult = -1;
        $strMsg = "操作失败";
        $arrResult = DCSetSuperPlayer($iRoleId,$iSuperLevel);
        if(is_array($arrResult) && isset($arrResult['iResult'])){
            if($arrResult['iResult']===0){
                $iResult = 0;
                $strMsg = "操作成功";
                $superUser = $objMasterBLL->getSuperUser($iRoleId);
                $msg = "商人等级修改成：{$iSuperLevel},玩家ID：{$iRoleId},玩家昵称：{$superUser['RealName']}";
                //做日志操作
                $objSessioinLogin = new Session($this->arrConfig['Session']['SessionLoginName']);
                $SysUserName = $objSessioinLogin->get($this->arrConfig['SessionInfo']['UserName']);
                $objOperationLogsBLL = new OperationLogsBLL(0);

                $objOperationLogsBLL->addCaseOperationLogs(0, 0, 31, $msg, 0, Utility::getIP(), 0, 2, $SysUserName, '');

                $objMasterBLL->updateSuperUser($iRoleId,$iSuperLevel);
            }

        }
        $arrTags = array('iResult'=>$iResult,'resultMsg'=>$strMsg);


        Utility::assign($this->smarty,$arrTags);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/msgbox.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }

    /**删除超级用户
     *
     */
    public function deleteSuperUser(){
        $iRoleID = $iRoleId = Utility::isNumeric('roleId',$_POST)?$_POST['roleId']:0;

        if($iRoleID <= 0 ){
            exit();
        }
        $objMasterBLL = new MasterBLL();
        $iResult = -1;
        $strMsg = "操作失败";
        $arrResult = DCSetSuperPlayer($iRoleId,0);
        if(is_array($arrResult) && isset($arrResult['iResult'])){
            if($arrResult['iResult']===0){
                $iResult = 0;
                $strMsg = "操作成功";
                $superUser = $objMasterBLL->getSuperUser($iRoleID);
                $msg = "商人被删除,玩家ID：{$iRoleId},玩家名字：{$superUser['RealName']}";
                //做日志操作
                $objSessioinLogin = new Session($this->arrConfig['Session']['SessionLoginName']);
                $SysUserName = $objSessioinLogin->get($this->arrConfig['SessionInfo']['UserName']);
                $objOperationLogsBLL = new OperationLogsBLL(0);
                $objOperationLogsBLL->addCaseOperationLogs(0, 0, 31, $msg, 0, Utility::getIP(), 0, 2, $SysUserName, '');
            }
            //做日志操作
            $objMasterBLL->deleteSuperUser($iRoleId);
        }
        $arrTags = array('iResult'=>$iResult,'resultMsg'=>$strMsg);
        Utility::assign($this->smarty,$arrTags);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/msgbox.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }
}