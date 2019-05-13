<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/9/19
 * Time: 12:52
 */

require_once ROOT_PATH . 'Link/QueryRoleList.php';
require_once ROOT_PATH . 'Link/QueryRoleGameInfo.php';
require_once ROOT_PATH . 'Link/QueryRoleBankInfo.php';
require_once ROOT_PATH . 'Link/AddRoleMonery.php';


require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/SystemBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserDataBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/BankBLL.class.php';

class ServiceExchangeAction extends PageBase
{
        private $strLoginedUser = '';
        public function __construct(){

            $this->arrConfig = unserialize(SYS_CONFIG);
            $this->strLoginedUser = Utility::chkUserLogin($this->arrConfig);

        }


        function index(){
            $arrExchangeType = $this->arrConfig['ExchangeType'];
            $arrTags=array('strStatrTime'=>date('Y-m-d',strtotime("-7 day")),
                'strEndTime'=>date('Y-m-d',strtotime("+1 day")),
                'OperateVerifyType'=>$arrExchangeType
               );

            Utility::assign($this->smarty,$arrTags);
            $this->smarty->display($this->arrConfig['skin'].'/Service/ServiceExchangeIndex.html');
        }


    function ExchangeList()
    {
        $flag = false;
        //获取Post参数
        $startTime = isset($_POST['startTime'])?$_POST['startTime']:'';
        $endTime = isset($_POST['endTime'])?$_POST['endTime']:'';
        $iStatus = isset($_POST['status'])?$_POST['status']:'';
        $arrType = isset($_POST['arrType'])?$_POST['arrType']:'';
        $LoginID = isset($_POST['loginId'])?$_POST['loginId']:'';
        $LoginName = isset($_POST['loginName'])?$_POST['loginName']:'';
        $pattern = '/[\d]{4}-[\d]{1,2}-[\d]{1,2}/';

        //组装where条件
        if(($LoginID != '' && !is_numeric($LoginID)) || ($startTime != '' && !preg_match($pattern, $startTime)) ||
            ($endTime != '' && !preg_match($pattern, $endTime)) || ($startTime > $endTime))
        {
            $strWhere = ' WHERE 2=1';
            $flag = true;
        }else{
            $strWhere = ' WHERE 1=1';
            $arrType = $arrType != '' ? explode(',',$arrType) : null;
            if(is_array($arrType) && count($arrType)>0){
                $arrType = implode(",",$arrType);
                $strWhere .= " AND PayWay in ($arrType)";
                $flag = true;
            }
            if($iStatus != '' && ($iStatus == 0 || $iStatus == 1 || $iStatus == 2)){
                $strWhere .= " AND Status=$iStatus ";
                $flag = true;
            }
            if($startTime != ''){
                $strWhere .= " AND AddTime>='$startTime' ";
                $flag = true;
            }
            if($endTime != ''){
                $endTime = date('Y-m-d',strtotime($endTime)+86400);
                $strWhere .= " AND AddTime<'$endTime' ";
                $flag = true;
            }
            if($LoginID != ''){
                $strWhere .= " AND AccountID=$LoginID ";
                $flag = true;
            }elseif ($LoginName != ''){
                $LoginName = Utility::utf8ToGb2312($LoginName);
                $strWhere .= " AND RealName='$LoginName' ";
                $flag = true;
            }
        }

        if($flag){//是否是重新组合查询条件，如果是分页读取session中的where条件
            $_SESSION['getPageOperation_Where'] = $strWhere;
        }

        //组装分页查询条件
        $curPage = Utility::isNumeric('curPage',$_POST);
        $curPage = $curPage <= 0 ? 1 : $curPage;
        $arrParam['fields'] = 'OrderNo, AccountID, iMoney,tax, AddTime,checkuser,checktime,descript,PayWay, RealName, CardNo, IsDrawback, BankName,status';
        $arrParam['tableName'] = 'UserDrawBack';
        $arrParam['where'] = isset($_SESSION['getPageOperation_Where']) && !empty($_SESSION['getPageOperation_Where']) ? $_SESSION['getPageOperation_Where'] : $strWhere;
        $arrParam['order'] = 'AddTime desc';
        $arrParam['page'] = $curPage;
        $arrParam['pagesize'] = 15;

        $objCommonBLL = new CommonBLL($this->arrConfig['MapType']['Bank']);
        $iRecordsCount = $objCommonBLL->getRecordsCountSelect($arrParam);
        $Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
        $arrResult = null;
        if($iRecordsCount > 0)
        {
            $arrResult = $objCommonBLL->getPageListSelect($arrParam,$curPage);
            if(is_array($arrResult) && count($arrResult)>0){
                $i=0;
                foreach ($arrResult as $v){

                    $iRoleIDs = array();
                    $iRoleIDs[] = $v['AccountID'];
                    $arrRole = DSQueryRoleList($iRoleIDs);
                    $arrRole = $arrRole['RoleInfoList'][0];


                    $arrResult[$i]['LoginID'] = $v['AccountID'];
                    $arrResult[$i]['OrderNo'] = $v['OrderNo'];
                    $arrResult[$i]['LoginName'] =$arrRole["szLoginName"];
                    $arrResult[$i]['RealName'] = Utility::gb2312ToUtf8($v['RealName']);
                    $arrResult[$i]['BankName'] = Utility::gb2312ToUtf8($v['BankName']);
                    $arrResult[$i]['descript'] = Utility::gb2312ToUtf8($v['descript']);
                    $arrResult[$i]['payway'] = $this->arrConfig['ExchangeType'][$v['PayWay']];
                    $arrResult[$i]['CheckUser'] = Utility::gb2312ToUtf8($v['CheckUser']);
                    $arrResult[$i]['descript'] = Utility::gb2312ToUtf8($v['descript']);
                    $arrResult[$i]['iMoney'] = sprintf("%.2f",$v['iMoney']/1000);
                    $arrResult[$i]['tax'] = sprintf("%.2f",$v['tax']/1000);
                    $arrResult[$i]['checkamount'] = $arrResult[$i]['iMoney']-$arrResult[$i]['tax'];
                    $arrResult[$i]['amount'] = sprintf("%.2f",$arrRole["iHappyBeanMoney"]/1000);
                    $arrResult[$i]['AddTime'] = empty($v['AddTime'])?'':date('Y-m-d H:i:s',strtotime($v['AddTime']));
                    $arrResult[$i]['CheckTime'] = empty($v['CheckTime'])?'':date('Y-m-d H:i:s',strtotime($v['CheckTime']));
                    $i++;
                }
            }
        }

        $arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'ExchangeList'=>$arrResult);
        Utility::assign($this->smarty,$arrTags);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/ServiceExchangePage.html');
        $html = str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }



    function getOperateVerifyPage()
    {
        $iPId = Utility::isNumeric('id', $_POST) ? $_POST['id'] : 0;
        $iRoleId = Utility::isNumeric('roleId', $_POST) ? $_POST['roleId'] : 0;

        if($iPId <= 0|| $iRoleId<=0){
            echo -1;
            exit();
        }
        $arrTags = array('skin'=>$this->arrConfig['skin'],
            'id'=>$iPId,
            'roleId'=>$iRoleId,
            'ClassName'=>'ServiceAuth');
        Utility::assign($this->smarty,$arrTags);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/ExchangeOperateDetail.html');
        echo $html;
    }


    //审核通过

    function doOperateVerify()
    {
        $iPId = Utility::isNumeric('id', $_POST) ? $_POST['id'] : 0;
        $iStatus = Utility::isNumeric('status', $_POST) ? $_POST['status'] : 0;//1同意 2拒绝
        $iRoleId = Utility::isNumeric('roleId', $_POST) ? $_POST['roleId'] : 0;
        $strRemark = isset($_POST['content']) ? $_POST['content'] : '';
        if($strRemark =='' || mb_strlen($strRemark,'gb2312') > 200)
        {
            echo -1;
            exit();
        }
        $objbank =new BankBLL();
        $iReturn = 0;
        $arrOrder = $objbank->getDrawBack($iPId);
        if(empty($arrOrder)){
            echo -1;
            exit();
        }

        if($iPId > 0 && $iStatus>0 && $iRoleId>0 && $arrOrder["status"]==0)
        {

            $strRemark = Utility::utf8ToGb2312($strRemark);
            $ret = $objbank->updateDrawBack($iPId,$iRoleId,$strRemark,$this->strLoginedUser,$iStatus);
            if($iStatus==3)
            {
                $arrDraw = $objbank->getDrawBack($iPId);
                $arrResult = DSAddRoleMonery($iRoleId,$arrDraw['iMoney']);
            }
            echo 1;
        }
        else
        {
            echo 1001;
        }
    }
























}