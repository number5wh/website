<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/10/12
 * Time: 19:49
 */
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/OperationLogsBLL.class.php';
require ROOT_PATH . 'Link/QueryOnlinePlayer.php';
require ROOT_PATH . 'Link/QueryRoomOnlinePlayersRes.php';
class RoomOnlineAction extends PageBase{

    public function __construct(){
        $this->arrConfig = unserialize(SYS_CONFIG);
        Utility::chkUserLogin($this->arrConfig);
    }

    public function index(){

        $objMasterBLL = new MasterBLL();

        $arrResult = DCQueryOnlinePlayer();
        $RoomOnlineList = array();
        $UserTotalCount = 0;
        $RobotTotalCount = 0;
        $TotalCount = 0;
        if($arrResult['iRoomCount']>0){
            $RoomOnlineList = $arrResult['RoomOnlineInfoList'];
            foreach($RoomOnlineList as $key => $vo){
                $RoomOnlineList[$key]['UpdateTime'] = date('Y-m-d h:i:s',$vo['iUpdateTime']);
                $RoomOnlineList[$key]['UserCount'] = $vo['iOnLineCount'] - $vo['iRobotCount'];
                $UserTotalCount += $RoomOnlineList[$key]['UserCount'];
                $RobotTotalCount += $vo['iRobotCount'];
                $TotalCount += $vo['iOnLineCount'];
                $RoomInfo = $objMasterBLL->getGameRoomInfo($vo['iRoomID']);
                $RoomOnlineList[$key]['RoomName'] = $RoomInfo['RoomName'];
            }
        }
        $arrTags = array("RoomOnlineList"=>$RoomOnlineList,"UserTotalCount"=>$UserTotalCount,
                            "RobotTotalCount"=>$RobotTotalCount,"TotalCount"=>$TotalCount);

        Utility::assign($this->smarty,$arrTags);
        $this->smarty->display($this->arrConfig['skin'].'/YunYing/RoomOnlineInfo.html');
    }

    /**
     * 查看
     */
    public function getRoomOnlineInfo(){
        $roomID = Utility::isNumeric('roomID',$_REQUEST);

        $arrTags = array("RoomID"=>$roomID);
        Utility::assign($this->smarty,$arrTags);
        $this->smarty->display($this->arrConfig['skin'].'/YunYing/RoomUserOnlineEdit.html');
    }

    /**
     * 查看某个房间内，所有用户的信息列表
     */
    public function getRoomOnlineList(){
        $roomID = Utility::isNumeric('RoomID',$_REQUEST);

        //$roomID = 7;
        $curPage = Utility::isNumeric('curPage',$_REQUEST)?$_REQUEST['curPage']:1;

        $pageSize = 30;

        $list = getRoomOnlinePlayers($roomID,$curPage,$pageSize);

        $iRecordsCount = $pageSize*($list['iTotalPage']-1)+ ($curPage==$list['iTotalPage']
                ?$list['iRoleCount']:$pageSize);
        if(!empty($list['RoleInfoList'])){
            foreach($list['RoleInfoList'] as &$val){
                $val['LoginID'] = $val['RoleID'];
            }
        }

        unset($val);
        $page = Utility::setPages($curPage,$iRecordsCount,$pageSize);

        $arrTags = array("RoleList"=>$list['RoleInfoList'],"Page"=>$page,"skin"=>$this->arrConfig['skin']);
        Utility::assign($this->smarty,$arrTags);

        $html = $this->smarty->fetch($this->arrConfig['skin'].'/YunYing/RoomUserOnlineList.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }
    /**
     *
     */
    public function getRoomOnlineInfo1(){
        //var_dump($_REQUEST);
        $roomID = Utility::isNumeric('roomID',$_REQUEST);

        //$roomID = 7;
        $curPage = Utility::isNumeric('CurPage',$_REQUEST)?$_REQUEST['CurPage']:1;

        $pageSize = 10;

        $list = getRoomOnlinePlayers($roomID,$curPage,$pageSize);

        $iRecordsCount = $pageSize*($list['iTotalPage']-1)+ ($curPage==$list['iTotalPage']
                ?$list['iRoleCount']:$pageSize);
        if(!empty($list['RoleInfoList'])){
            foreach($list['RoleInfoList'] as &$val){
                $val['LoginID'] = $val['RoleID'];
            }
        }

        unset($val);
        $page = Utility::setPages($curPage,$iRecordsCount,$pageSize);

        $arrTags = array("RoleList"=>$list['RoleInfoList'],"Page"=>$page,"skin"=>$this->arrConfig['skin']);
        Utility::assign($this->smarty,$arrTags);
        $this->smarty->display($this->arrConfig['skin'].'/YunYing/RoomUserOnlineList.html');
    }
}