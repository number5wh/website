<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/9/19
 * Time: 14:38
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

require_once ROOT_PATH . 'Link/QueryRoomRobotInfo.php';
require_once ROOT_PATH . 'Link/ActiveRoomRobot.php';
class RoomRobotBehaviorAction extends PageBase{

    public function __construct(){
        $this->arrConfig = unserialize(SYS_CONFIG);
        $this->strLoginedUser = Utility::chkUserLogin($this->arrConfig);
    }

    public function index(){

        $arrTags = array();
        Utility::assign($this->smarty,$arrTags);
        $this->smarty->display($this->arrConfig['skin'].'/YunWei/RoomRobotBehaviorEdit.html');
    }
    /**
     * 游戏房间 状态修改
     */
    public function getRoomRobotInfoList(){

        $curPage = Utility::isNumeric('curPage',$_POST);
        $curPage = $curPage<=0 ? 1: $curPage;
        $pageSize = 10;

        //读取游戏房间状态
        $arrResult = getRoomRobotInfo($curPage,$pageSize);
        $iRecordsCount = ($arrResult['iTotalPage']-1)*$pageSize + ($arrResult['iCurPage'] == $arrResult['iTotalPage'] ? $arrResult['iRoomCount']:$pageSize);


        $objMasterBll = new MasterBLL();
        $showData = array();
        $i = 0;
        foreach($arrResult['RoomRobotInfoList'] as $key => $val){
            $arrResult['RoomRobotInfoList'][$key]['UpdateTime'] = date("Y-m-d h:i:s",$val['UpdateTime']);
            $roomRobot = $objMasterBll->getRoomRobot($val['RoomID']);
            if($roomRobot){
                $showData[$i] = $arrResult['RoomRobotInfoList'][$key];
                $showData[$i]['MaxCount'] = $roomRobot['MaxCount'];
                $showData[$i]['RobotWinWeighted'] = $roomRobot['RobotWinWeighted'];
                $showData[$i]['RobotNeedWinMoney'] = $roomRobot['RobotWinMoney'];

                $roomInfo = $objMasterBll->getGameRoomInfo($val['RoomID']);
                if($roomInfo != null) $showData[$i]['RoomName'] = $roomInfo['RoomName'];
                $i++;
            }else{
                $showData[$i] = $arrResult['RoomRobotInfoList'][$key];
                $showData[$i]['MaxCount'] = 0;
                $showData[$i]['RobotWinWeighted'] = 0;
                $showData[$i]['RobotNeedWinMoney'] = 0;
                $roomInfo = $objMasterBll->getGameRoomInfo($val['RoomID']);
                if($roomInfo != null ) $showData[$i]['RoomName'] = $roomInfo['RoomName'];
                $i++;
            }


        }
        $arrResult['RoomRobotInfoList'] = $showData;
        $Page = Utility::setPages($curPage,$iRecordsCount,$pageSize);
        $arrTags = array("RoomList"=>$arrResult['RoomRobotInfoList'],'Page'=>$Page,'skin'=>$this->arrConfig['skin']);
        Utility::assign($this->smarty,$arrTags);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/RoomRobotBehavior.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }

    /**
     * 激活房间机器人。
     */
    public function activeRoomRobot(){
        $iRoomID = Utility::isNumeric("RoomID",$_REQUEST);
        $arrResult = DCActiveRoomRobot($iRoomID);
        $iResult = -1;
        //var_dump($arrResult);
        if(isset($arrResult['iResult']) ){
            //房间激活成功， 日志记录
            $iResult = $arrResult['iResult'];
        }
        //echo $iResult;
        $arrTags = array('iResult'=>$iResult);
        Utility::assign($this->smarty,$arrTags);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/msgbox.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }
}