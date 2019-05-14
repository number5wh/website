<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/GameFiveBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';

class GameTaskAction extends PageBase
{
    private $objGameFiveBLL = null;
    private $objMasterBLL = null;

    public function __construct()
    {
        $this->arrConfig=unserialize(SYS_CONFIG);
        //Utility::chkUserLogin($this->arrConfig);
        $this->objMasterBLL = new MasterBLL();

    }
    public function index()
    {
        $this->smarty->display($this->arrConfig['skin'].'/Service/GameTaskList.html');
    }

    /**
     * 分页
     */
    public function getTaskList()
    {

        $strWhere = ' ';
        $curPage = Utility::isNumeric('curPage',$_POST);
        $curPage = $curPage==0 ? 1 : $curPage;
        // [RoomId]   房间id
        //      ,[TaskReqRound]   局数
        //      ,[TaskAward]    奖励
        //      ,[TaskName]   任务名称
        //修改
        $arrParam['fields']='RoomId,TaskReqRound,TaskAward,TaskName,Status';
        $arrParam['tableName']='T_GameActivity';
        $arrParam['where']=$strWhere;

        $arrParam['order']=' ';
        $arrParam['pagesize']=30;



        $objCommonBLL = new CommonBLL(0);
        $iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
        $Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
        $arrResult = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);
        if ($arrResult) {
            $RoomInfo = $this->objMasterBLL->getGameRoomInfoList();
            foreach ($arrResult as &$val) {
                foreach ($RoomInfo as $k => $v){
                    if($val['RoomId'] == $v['RoomID']){
                        $val['RoomName'] = $v['RoomName'];
                        break;
                    }
                }
                $val['TaskName'] = Utility::gb2312ToUtf8($val['TaskName']);
                $val['TaskAward'] = $val['TaskAward'] / 1000;
            }
        }
        unset($val);


        $arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'TaskList'=>$arrResult);
        Utility::assign($this->smarty,$arrTags);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/GameTaskListPage.html');
        //echo $html;

        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }

    //编辑
    public function showEdit()
    {
        $roomid = Utility::isNullOrEmpty('roomid', $_POST);
        $reqround = Utility::isNullOrEmpty('reqround', $_POST);
        $roomname = Utility::isNullOrEmpty('roomname', $_POST);
        $taskname = Utility::isNullOrEmpty('taskname', $_POST);
        $award = Utility::isNullOrEmpty('award', $_POST);


        $arrTags = array('roomid' => $roomid, 'roomname' => $roomname, 'reqround' => $reqround, 'taskname' => $taskname, 'award'=>$award);
        $send = array('Info'=>$arrTags);
        Utility::assign($this->smarty, $send);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/GameTaskEdit.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }

    public function doEdit()
    {
        $roomid = Utility::isNullOrEmpty('roomid', $_POST);
        $reqround = Utility::isNullOrEmpty('reqround', $_POST);
        $taskname = Utility::isNullOrEmpty('taskname', $_POST);
        $award = Utility::isNullOrEmpty('award', $_POST);
        $taskname = Utility::utf8ToGb2312($taskname);

        if (!$roomid || !is_numeric($roomid) || $roomid<0 || !$reqround || !is_numeric($reqround) || $reqround<0 || !$award || !is_numeric($award) || $award<0 || !$taskname) {
            echo -1;
        } else {
            $ret = $this->objMasterBLL->updateGameTask($roomid, $reqround, $award*1000, $taskname);
            echo $ret[0]['IRESULT'];
        }
    }

    //上下架
    public function setStatus()
    {
        $roomid = Utility::isNumeric('roomid', $_POST);
        $status = Utility::isNumeric('status', $_POST);
        if (!$roomid || !is_numeric($roomid) || !is_numeric($status)) {
            echo -1;
        } else {
            $ret = $this->objMasterBLL->setGameTaskStatus($roomid, $status);
            echo $ret[0]['IRESULT'];
        }
    }
}
?>