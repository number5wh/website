<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/GameFiveBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';

class MsgAction extends PageBase
{
    private $objGameFiveBLL = null;
    public function __construct()
    {
        $this->arrConfig=unserialize(SYS_CONFIG);
        Utility::chkUserLogin($this->arrConfig);

    }
    public function index()
    {
        $this->smarty->display($this->arrConfig['skin'].'/Service/MsgList.html');
    }

    /**
     * 分页
     */
    public function getPagerMsg()
    {

        $strWhere = ' WHERE ClassId=1 ';
        $curPage = Utility::isNumeric('curPage',$_POST);
        $curPage = $curPage==0 ? 1 : $curPage;
        $arrParam['fields']='MsgID,MsgTitle,MsgContent,Status,StartTime,EndTime,AddTime,Sortid';
        $arrParam['tableName']='T_SysMsgList';
        $arrParam['where']=$strWhere;

        $arrParam['order']='EndTime DESC';
        $arrParam['pagesize']=15;

        $objCommonBLL = new CommonBLL(0);
        $iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
        $Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
        $arrResult = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);

        if(is_array($arrResult) && count($arrResult)>0)
        {
            $iCount = 0;
            foreach ($arrResult as $val)
            {
                $arrResult[$iCount]['MsgTitle'] = Utility::gb2312ToUtf8($val['MsgTitle']);
                $arrResult[$iCount]['MsgContent'] = Utility::gb2312ToUtf8($val['MsgContent']);
                $arrResult[$iCount]['Expire'] = time()-strtotime($val['EndTime'])>=0 ? 1 : 0;//是否已过期
                $iCount++;
            }
        }
        $arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'MsgList'=>$arrResult);
        Utility::assign($this->smarty,$arrTags);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/MsgListPage.html');
        //echo $html;
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }

    /**
     * 显示编辑广告信息页面
     */
    public function ShowAddMsgHtml()
    {
        $arrWebAdPosList = null;
        $MsgID = Utility::isNumeric('msgID',$_POST);
        $objMasterBLL = new MasterBLL();
        $arrServerList = $objMasterBLL->getMsgInfo($MsgID, 1);

        if(is_array($arrServerList) && count($arrServerList)>0){
            $arrMsgInfo = $arrServerList[0];
        }
        else
        {
            $arrMsgInfo = array('MsgID'=>0,'MsgTitle'=>'','StartTime'=>'','EndTime'=>'','SortID'=>0,'MsgContent'=>'');
        }
        $arrTags=array('msg'=>$arrMsgInfo);
        Utility::assign($this->smarty,$arrTags);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/MsgEdit.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;

    }


    /**
     * 添加广告
     * $iResult: 0:成功,-1:失败,-9999:接收的参数异常
     */
    public function addMsg()
    {
        $iResult = -9999;
        $arrParams['MsgID'] = Utility::isNumeric('MsgID',$_POST);
        $arrParams['MsgTitle'] = Utility::isNullOrEmpty('MsgTitle',$_POST);
        $arrParams['StartTime'] = Utility::isNullOrEmpty('StartTime',$_POST) ? $_POST['StartTime'] : date('Y-m-d H:i:s');
        $arrParams['EndTime'] = Utility::isNullOrEmpty('EndTime',$_POST) ? $_POST['EndTime'] : date('Y-m-d H:i:s',strtotime('+1 day'));
        $arrParams['SortID'] = Utility::isNumeric('SortID',$_POST);
        $arrParams['MsgContent'] = Utility::isNullOrEmpty('MsgContent',$_POST);
        $arrParams['ClassId'] = 1;

        if($arrParams['MsgContent'] &&  $arrParams['SortID'] && $arrParams['EndTime'] && $arrParams['StartTime'])
        {
            $objMasterBLL = new MasterBLL();
            $iResult = $objMasterBLL->addMsgInfo($arrParams);
        }
        echo $iResult;
    }
    /**
     * 设置广告禁用/启用状态
     * $iResult=0:成功,-1:失败
     */
    public function setMsgLocked()
    {
        $iResult = -9999;
        $msg = '';
        $MsgID = Utility::isNumeric('msgID',$_POST);
        if($MsgID && $MsgID>0)
            $objMasterBLL = new MasterBLL();
            $iResult = $objMasterBLL->setMsgLocked($MsgID);

        if($iResult==-9999)
            $msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'Msg\')','对不起,您提交的数据异常,请重试','false','Msg',$this->arrConfig);
        elseif($iResult==-1)
            $msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'Msg\')','广告状态设置失败','false','Msg',$this->arrConfig);
        echo json_encode(array('Msg'=>$msg,'iResult'=>$iResult));
    }
    /**
     * 删除广告
     * $iResult=0:成功,-1:失败
     */
    public function delMsg()
    {
        $iResult = -9999;
        $msg = '删除成功';
        $MsgID = Utility::isNumeric('msgID',$_POST);
        if($MsgID && $MsgID>0) {
            $objMasterBLL = new MasterBLL();
            $iResult = $objMasterBLL->delMsgInfo($MsgID);
        }
        if($iResult==-1)
            $msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'Msg\')','跑马灯删除失败','false','Msg',$this->arrConfig);
        elseif($iResult==-9999)
            $msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'Msg\')','对不起,您提交的数据异常,请重试','false','Msg',$this->arrConfig);
        echo json_encode(array('Msg'=>$msg,'iResult'=>$iResult));
    }
}
?>