<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Common/PHPExecl/PHPExcel.php';
require ROOT_PATH . 'Class/BLL/BankBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/MsgBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserDataBLL.class.php';
require ROOT_PATH . 'Class/BLL/DataChangeLogsBLL.class.php';
require ROOT_PATH . 'Class/BLL/OperationLogsBLL.class.php';
require ROOT_PATH . 'Class/BLL/SetOperationLogsBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/SystemBLL.class.php';
require ROOT_PATH . 'Class/BLL/StagePropertyBLL.class.php';
require ROOT_PATH . 'Class/BLL/PassAccountBLL.class.php';
require ROOT_PATH . 'Class/BLL/PassSecurityBLL.class.php';
require ROOT_PATH . 'Class/BLL/PayLogsBLL.class.php';


require_once ROOT_PATH . 'Link/GetRoleBaseInfo.php';
require_once ROOT_PATH . 'Link/QueryRoleList.php';
require_once ROOT_PATH . 'Link/QueryRoleGameInfo.php';
require_once ROOT_PATH . 'Link/QueryRoleBankInfo.php';
require_once ROOT_PATH . 'Link/LockRole.php';
require_once ROOT_PATH . 'Link/ResetRoleBankPwd.php';
require_once ROOT_PATH . 'Link/LockRoleMonery.php';
require_once ROOT_PATH . 'Link/BlockRole.php';
require_once ROOT_PATH . 'Link/UpdateUserAccountInfo.php';
require_once ROOT_PATH . 'Link/SetRoleIPLock.php';
require_once ROOT_PATH . 'Link/ClearCurRoomInfo.php';
require_once ROOT_PATH . 'Link/ResetLoginPwd.php';
require_once ROOT_PATH . 'Link/QueryRoleID.php';
require_once ROOT_PATH . 'Link/SavingRoleMonery.php';
require_once ROOT_PATH . 'Link/SetBankWeChatCheck.php';
require_once ROOT_PATH . 'Link/UnLockUserBank.php';
require_once ROOT_PATH . 'Link/SetPassType.php';
require_once ROOT_PATH . 'Link/ClearRoleData.php';
class ServiceTransferAction extends PageBase
{

    private $objBankBLL = null;
    private $objMasterBLL = null;
    private $objSystemBLL = null;
    private $objStagePropertyBLL = null;
    private $iVerType = 0;
    private $iTmpRoleID = array();
    private $arrIntroRoleID = array();
    private $iFlag = 0;
    public function __construct()
    {
        $this->arrConfig=unserialize(SYS_CONFIG);
        Utility::chkUserLogin($this->arrConfig);
        $this->objBankBLL = new BankBLL();
        $this->objMasterBLL = new MasterBLL();
        $this->objSystemBLL = new SystemBLL();

        //$this->objStagePropertyBLL = new StagePropertyBLL();
    }


    function getBankInfoDetail()
    {
        $iRoleID = Utility::isNumeric('RoleID', $_GET);

        $arrTags=array('ChangeType'=>$this->arrConfig['BankChangeType'],'nowTime'=>date("Y-m-d H:i:s",time()),'FromDate'=>date("Y-m-d",strtotime('-7 days')),'ToDate'=>date("Y-m-d",time()),'RoleID'=>$iRoleID);
        Utility::assign($this->smarty,$arrTags);
        $this->smarty->display($this->arrConfig['skin'].'/Service/TransferBankInfo.html');
        //$html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/TransferBankInfo.html');
        //$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        //echo $html;
    }



    /**
     * 银行资料——转账查询记录
     */
    function getPageUserTransferRecords()
    {
        $iRoleID = Utility::isNumeric('RoleID', $_GET);
        $arrRes = $this->getUserTransferRecords(10);

        $arrTags=array('skin'=>$this->arrConfig['skin'],'roleID'=>$iRoleID,'Page'=>$arrRes['Page'],'BankDetailList'=>$arrRes['Result'],"tt"=>$arrRes['tt']);
        Utility::assign($this->smarty,$arrTags);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/RoleTransferRecordsPage.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }

    /**
     * 银行资料——转账记录分页
     * @param $iRoleID
     * @param $pagesize
     * @param $delFlag 下载删缓存标识
     */
    function getUserTransferRecords($pagesize)
    {
        $arrRes = null;
        $Page = 1;
        $strWhere = '';
        $totalchange =0;
        $curPage = Utility::isNumeric('curPage',$_POST)?$_POST['curPage']:1;

        $iRoleID = Utility::isNumeric('RoleID', $_POST);
        $iDCFlag = Utility::isNumeric('DCFlag', $_POST);//-1全部 1 存入 2 取出

        $iTransType = Utility::isNumeric('TransType', $_POST);//类型
        $arrParam['StartDate'] = Utility::isNullOrEmpty('Stime', $_POST);
        $arrParam['EndDate'] = Utility::isNullOrEmpty('Etime', $_POST);



        $startTime =Utility::isNullOrEmpty('Stime', $_POST);
        $endtime = Utility::isNullOrEmpty('Etime', $_POST);
        $STime = strtotime($startTime);
        $ETime = strtotime($endtime);
        //var_dump($_POST);

        //当前分页
        $iCurPage = Utility::isNumeric('curPage',$_POST);
        $arrParam['Page'] = $iCurPage > 0 ? $iCurPage : 1;
        $arrParam['pagesize'] = $pagesize;

       // $arrParam['tableName'] = "T_BankWealthChangeLogs_";
        $ChangeType = $this->arrConfig['BankChangeType'];

        $subQuery = '(';
        for($t = $STime; $t <= $ETime;$t = strtotime('+1 day',$t)){
            $subTable = 'T_BankWealthChangeLogs_'.date('Ymd',$t);

            $objDataChangeBLL = new DataChangeLogsBLL();
            $isExits  = $objDataChangeBLL->exitsTable($subTable);
            if($isExits['ret']===1) {
                //var_dump($subTable);
                $strWhere = " where RoleID = {$iRoleID}";
                if($iDCFlag != -1){
                    $strWhere = $strWhere. " AND ". "( ";//构造条件
                    if($iDCFlag == 2){
                        foreach($ChangeType as $val) if($val['type'] == 0){//0代表扣除
                            $strWhere .= " ChangeType = {$val['value']} OR";
                        }

                    }else{
                        //只查看增加的
                        foreach($ChangeType as $val) if($val['type'] == 1){//0代表增加
                            $strWhere .= " ChangeType = {$val['value']} OR";
                        }
                    }
                    $strWhere = substr($strWhere,0, -2);$strWhere .= ')';
                }
                if($iTransType != -1){
                    $strWhere = $strWhere ." AND ChangeType = {$iTransType}"; //具体某种交易类型
                }

                $subQuery .= '( SELECT * FROM ' . $subTable . ' ' . $strWhere . " " . " ) UNION ";
            }
        }
        $subQuery = substr($subQuery,0,-6);
        $subQuery .= ') TT'; //多表 Union 结果。

       // echo $subQuery;

        $arrParam['where'] = $strWhere;
        $arrParam['Page'] = $curPage;
        $arrParam['fields'] ="*";// " ServerID,RoleID,RoleName,LogType,ChangeMoney,Balance,ChangeType,ClientIP,MachineSerial,PayID,PayName,TargetID,TargetName,AddTime,Description";
        $arrParam['tableName'] = ' (SELECT TOP 2000 *  FROM '.$subQuery."  order by  AddTime desc) T";
        $arrParam['order'] = "AddTime DESC";
        $objCommonBLL = new CommonBLL($this->arrConfig['MapType']['DataChangeLogs']);
        $iRecordsCount = $objCommonBLL->getRecordsCountSelect($arrParam);
        $Page = Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
        if($iRecordsCount > 0){
            $arrRes = $objCommonBLL->getPageListSelectCache($arrParam,$arrParam['Page']);
        }
        $_ChangeType = Utility::array_column($ChangeType,'name','value');
        if(!empty($arrRes)) {
            foreach ($arrRes as $key => $val) {
                $arrRes[$key]['ChangeMoney'] = Utility::FormatMoney($val['ChangeMoney']);
                $arrRes[$key]['Balance'] = Utility::FormatMoney($val['Balance']);
                $arrRes[$key]['DcFlag'] = ($val['ChangeType'] <= 3 || $val['ChangeType'] == 12)? 0 : 1;
                $arrRes[$key]['DcFlagTips'] = $arrRes[$key]['DcFlag']? '收入':'支出';

                $arrRes[$key]['RoleName'] = Utility::gb2312ToUtf8($val['RoleName']);
                $arrRes[$key]['RoleName'] = Utility::gb2312ToUtf8($val['RoleName']);

                //转码
                $arrRes[$key]['RoleName'] = Utility::gb2312ToUtf8($val['RoleName']);
                $arrRes[$key]['TargetName'] = Utility::gb2312ToUtf8($val['TargetName']);
                $arrRes[$key]['PayName'] = Utility::gb2312ToUtf8($val['PayName']);
                $arrRes[$key]['Description'] = Utility::gb2312ToUtf8($val['Description']);
                if($arrRes[$key]['DcFlag']==1){
                    $totalchange = $totalchange+ $val['ChangeMoney'];
                }
                else
                {
                    $totalchange = $totalchange- $val['ChangeMoney'];
                }

            }
        }
        //$Page = Utility::setSimplePages(Utility::getPageNum($iRecordsCount,$pagesize) ,$arrParam['Page'],2,$arrParam['StartDate'],$arrParam['EndDate']);
        $totalchange = Utility::FormatMoney($totalchange);
        return array('Result'=>$arrRes,'Page'=>$Page,"tt"=>$totalchange);
    }


}