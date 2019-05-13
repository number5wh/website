<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/MsgBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserDataBLL.class.php';
require ROOT_PATH . 'Class/BLL/DataChangeLogsBLL.class.php';
require ROOT_PATH . 'Class/BLL/OperationLogsBLL.class.php';
require ROOT_PATH . 'Class/BLL/SetOperationLogsBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/StagePropertyBLL.class.php';
require ROOT_PATH . 'Class/BLL/PassAccountBLL.class.php';
require ROOT_PATH . 'Class/BLL/PassSecurityBLL.class.php';
require ROOT_PATH . 'Class/BLL/PayLogsBLL.class.php';
require ROOT_PATH . 'Class/BLL/CDAccountBLL.class.php';

require_once ROOT_PATH . 'Link/GetRoleBaseInfo.php';
class GameRecordAction extends PageBase
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

        $RoomType1 = $this->arrConfig['RoomType'][0]['TypeID'];//积分房间
        $RoomType2 = $this->arrConfig['RoomType'][1]['TypeID'];//金币房间
        $strWhere = " AND ((RoomType & $RoomType1)>0 OR (RoomType & $RoomType2)>0)";
        $arrParams['RoomType'] = Utility::isNumeric('RoomType', $_REQUEST);//房间类型
        $arrParams['RoleID'] = Utility::isNumeric('RoleID', $_REQUEST);
        $arrParams['KindID'] = Utility::isNumeric('KindID', $_REQUEST);
        $arrParams['StartTime'] = date('Y-m-d', strtotime('-90 day'));
        $arrParams['EndTime'] = date('Y-m-d');
        setcookie($this->arrConfig['Cookies']['iRecordsCount'].$arrParams['RoleID'],'');//页面载入时重置Cookies(getPagerUserGameDataDetail设置的总记录数)


        $roomType = $this->arrConfig['RoomType'];
        $arrKindList = $this->objMasterBLL->getGameKindList(-1,-1);
        $arrTags=array('skin'=>$this->arrConfig['skin'],'p'=>$arrParams,'KindList'=>$arrKindList,'RoomType'=>$roomType);
        Utility::assign($this->smarty,$arrTags);
        $this->smarty->display($this->arrConfig['skin'].'/YunYing/GameRecordList.html');
    }

    /**
     * 普通房间游戏记录明细分页
     */
    public function getPagerUserGameDetail()
    {
        $strWhere = '';
        $Page = null;
        $iRoleID = Utility::isNumeric('RoleID', $_POST);
        $iKindID = Utility::isNumeric('KindID', $_POST);
        $strKindName = Utility::isNullOrEmpty('KindName', $_POST);
        $RoomType = Utility::isNumeric('RoomType', $_POST);//房间类型
        $arrParam['EndDate'] = Utility::isNullOrEmpty('EndTime', $_POST);
        $arrParam['StartDate'] = Utility::isNullOrEmpty('StartTime', $_POST);
        $PlayResult = Utility::isNumeric('PlayResult', $_POST);
        $isFlag = Utility::isNumeric('sFlag', $_POST);

        $Hour = Utility::isNumeric('Hour', $_POST);
        $Minute = Utility::isNumeric('Minute', $_POST);
        //RoleID='.$iRoleID.' AND
        //$strWhere = '';
        if ($iRoleID) {
            $strWhere .= ' AND RoleID='.$iRoleID;
        }
        if ($iKindID) {
            $strWhere .= ' AND KindID='.$iKindID;
        }
        //筛选条件:全部,赢,输,和,逃
        if($PlayResult!=-1) $strWhere .= ' AND ChangeType='.$PlayResult;

        if($Minute && $Minute>0)
        {
            $date = $arrParam['StartDate']." $Hour:$Minute:00";
            $strWhere .= " AND DATEDIFF(mi,'$date',AddTime)=0";
        }
        elseif($Hour && $Hour>0)
        {
            $date = $arrParam['StartDate']." $Hour:00:00";
            $strWhere .= " AND DATEDIFF(hh,'$date',AddTime)=0";
        }
        else
        {
            if($arrParam['StartDate']) $strWhere .= " AND DATEDIFF(d,AddTime,'".$arrParam['StartDate']."')<=0";
            if($arrParam['EndDate']) $strWhere .= " AND DATEDIFF(d,AddTime,'".$arrParam['EndDate']."')>=0";
        }

        //echo $strWhere;exit;
        //当前页
        $iCurPage = Utility::isNumeric('curPage',$_POST);
        $arrParam['Page'] = $iCurPage > 0 ? $iCurPage : 1;
        $strCookRecordsCount = $this->arrConfig['Cookies']['iRecordsCount'].$iRoleID;
//		$strCookPrevParams1 = $this->arrConfig['Cookies']['PrevParams1'].$iRoleID;
//		$strCookPrevParams2 = $this->arrConfig['Cookies']['PrevParams2'].$iRoleID;
        //从第几条开始读取
        //$arrParam['RowIndex'] = Utility::isNumeric('LogsID',$_POST) ? $_POST['LogsID'] : 0;
        $arrParam['tableName'] = 'T_UserGameChangeLogs_';
        //查询字段
        $arrParam['fields'] = "  ServerID,RoleID,RoleName,LogType,SerialNumber,KindID,RoomType,TableID,ChangeType,Money,LastMoney,Score,LastScore,CONVERT(VARCHAR(100),AddTime,120) as AddTime  ";
        //查询条件
        $arrParam['where'] = ' WHERE  (RoomType & '.$RoomType.')>0'.$strWhere;
        //查询排序
        $arrParam['order'] = " AddTime DESC";
        //每页显示数量
        $arrParam['PageSize'] = 10;
        $objDataChangeLogsBLL = new DataChangeLogsBLL($iRoleID);
        //总记录数
        if(!isset($_COOKIE[$strCookRecordsCount]))
        {
            $iRecordCount = $objDataChangeLogsBLL->getRecordsCount($arrParam);
            setcookie($strCookRecordsCount,$iRecordCount);
        }
        else
            $iRecordCount = $_COOKIE[$strCookRecordsCount];
        if($iRecordCount>0)
        {
            //总分页数
            $iPageAll = $iRecordCount==0 ? 1 : Utility::getPageNum($iRecordCount,$arrParam['PageSize']);
            $arrParam['memName'] = 'PagerUserGameDetail';
            //单击搜索清除缓存
            if($isFlag){
                $objCommonBLL = new CommonBLL($this->arrConfig['MapType']['DataChangeLogs'],$iRoleID);
                $objCommonBLL->delSimplePageMemcache($arrParam['memName'], $iPageAll);
            }

            //分页读取记录
            $arrRes = $objDataChangeLogsBLL->getPageList($arrParam,0);
            $objMasterBLL = new MasterBLL ();
            $GameList = $objMasterBLL->getGameKindList ( -1, -1 );
            if(is_array($arrRes) && count($arrRes)>0)
            {
                $iNum = count($arrRes);
                $NextStartDate=$arrParam["EndDate"];
                $Page=Utility::setSimplePages($iPageAll,$arrParam['Page'],0,$NextStartDate);
                $ChangeType = array(0=>"胜",1=>"负",2=>"和",3 =>"跑");
                $_RoomType = Utility::array_column($this->arrConfig['RoomType'],'TypeName','TypeID');
                foreach($arrRes as $key => $val){
                    $arrRes[$key]['ChangeTypeTip'] = $ChangeType[$val['ChangeType']];
                    $arrRes[$key]['RoomTypeTip'] = $_RoomType[$val['RoomType']];
                    $arrRes[$key]['RoleName'] = Utility::gb2312ToUtf8($val['RoleName']);
                    $arrRes[$key]['Money'] =Utility::FormatMoney($val['Money']);
                    $arrRes[$key]['LastMoney'] = Utility::FormatMoney($val['LastMoney']);

                    foreach ($GameList as $g) {
                        if ($val['KindID'] == $g['KindID']) {
                            $arrRes[$key]['KindName'] = $g['KindName'];
                            break;
                        }
                    }
                }
            }

            $arrTags=array('skin'=>$this->arrConfig['skin'],'KindName'=>$strKindName,'Page'=>$Page,'GameDetailList'=>$arrRes, 'RoleID'=>$iRoleID,"TableNum"=>date('Ymd',strtotime($arrParam['StartDate'])));
            Utility::assign($this->smarty,$arrTags);
            $html = $this->smarty->fetch($this->arrConfig['skin'].'/YunYing/GameRecordListPage.html');
            $html=str_replace("\r\n",'',$html);
            echo $html;
        }else{
            echo '<div style="margin-top:10px; text-align:center;">很抱歉，没有您要查询的信息~</div>';
        }
    }
}




