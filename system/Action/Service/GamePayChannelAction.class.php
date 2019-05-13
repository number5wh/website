<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/3
 * Time: 14:19
 */
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';

class GamePayChannelAction extends PageBase
{
    public function __construct() {
        $this->arrConfig = unserialize(SYS_CONFIG);
        Utility::chkUserLogin($this->arrConfig);
    }

    public function index()
    {
        $arrResult = $this->getListData();
        $arrTags = array('skin' => $this->arrConfig['skin'], 'Page' => $arrResult['Page'], 'ChannelList' => $arrResult['ChannelList']);
        Utility::assign($this->smarty, $arrTags);

        $this->smarty->display($this->arrConfig['skin'] . '/Service/GamePayChannelList.html');
    }

    public function getListData()
    {
        $arrUserList = null;
        $curPage = Utility::isNumeric('curPage', $_POST);
        $curPage = $curPage <= 0 ? 1 : $curPage;

        $arrParam['fields'] = 'ChannelId,Status,ChannelName,MchId,AppId,AppKey,NoticeUrl,Config,Descript';
        $arrParam['tableName'] = 'T_GamePayChannel';
        $arrParam['where'] = '';
        $arrParam['order']='ChannelId';
        $arrParam['pagesize'] = 20;

        $objCommonBLL = new CommonBLL(0);
        $iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
        $Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
        $arrResult = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);
//        var_dump($arrResult);
//        die;
        foreach ($arrResult as  &$v) {
            $v['ChannelName'] = Utility::gb2312ToUtf8($v['ChannelName']);
            $v['Config'] = Utility::gb2312ToUtf8($v['Config']);
            $v['Descript'] = Utility::gb2312ToUtf8($v['Descript']);
            $v['statusinf'] = ($v['Status'] == 0 ? '关闭' : '开通');
        }
        unset($v);
        return array('ChannelList' => $arrResult, 'Page' => $Page);
    }

    public function getList()
    {
        $arrResult = $this->getListData();
        $arrTags = array('skin' => $this->arrConfig['skin'], 'Page' => $arrResult['Page'], 'ChannelList' => $arrResult['ChannelList']);
        Utility::assign($this->smarty,$arrTags);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/GamePayChannelList.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }
    
    //编辑
    public function showEdit()
    {
        $channelid = Utility::isNumeric('channelid', $_POST);
        $descript = Utility::isNullOrEmpty('descript', $_POST);
        $appid = Utility::isNumeric('appid', $_POST);
        $appkey = Utility::isNullOrEmpty('appkey', $_POST);
        $mchid = Utility::isNumeric('mchid', $_POST);
        $noticeurl = Utility::isNullOrEmpty('noticeurl', $_POST);
        $config = Utility::isNullOrEmpty('config', $_POST);
        $channelname = Utility::isNullOrEmpty('channelname', $_POST);
        $status = Utility::isNumeric('status', $_POST);


        $arrTags = array('channelid' => $channelid, 'descript' => $descript,
                         'appid' => $appid, 'appkey' => $appkey, 'mchid' => $mchid,
                         'noticeurl' => $noticeurl, 'config' => $config, 'channelname' => $channelname, 'status' => $status
        );
        $send = array('Info'=>$arrTags);
        Utility::assign($this->smarty, $send);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/GamePayChannelEdit.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }

    //新增
    public function showAdd()
    {
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/GamePayChannelAdd.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }

    //编辑或新增
    public function doHandle()
    {
        $channelid = Utility::isNumeric('channelid', $_POST);
        $descript = Utility::isNullOrEmpty('descript', $_POST) ? Utility::isNullOrEmpty('descript', $_POST) : '';
        $appid = Utility::isNumeric('appid', $_POST);
        $appkey = Utility::isNullOrEmpty('appkey', $_POST);
        $mchid = Utility::isNumeric('mchid', $_POST);
        $noticeurl = Utility::isNullOrEmpty('noticeurl', $_POST) ?Utility::isNullOrEmpty('noticeurl', $_POST) : '';
        $config = Utility::isNullOrEmpty('config', $_POST) ? Utility::isNullOrEmpty('config', $_POST) : '';
        $channelname = Utility::isNullOrEmpty('channelname', $_POST);
        $status = Utility::isNumeric('status', $_POST);
        if (!$appkey || !$appid || !$mchid ||!$channelname) {
            echo 1;
        } else {
            if ($channelid) {
                $this->doEdit($channelid, $mchid, $appid, $appkey, $noticeurl, $config, $channelname, $descript, $status);
            } else {
                $this->doAdd($mchid, $appid, $appkey, $noticeurl, $config, $channelname, $descript);
            }
        }

    }

    public function doEdit($channelid, $mchid, $appid, $appkey, $noticeurl, $config, $channelname, $descript, $status)
    {
        $objDataChangeBLL = new MasterBLL();
        $ret = $objDataChangeBLL->editPaychannel($channelid,$channelname,$mchid, $appid, $appkey, $config, $noticeurl, $descript, $status);
        echo $ret[0]['IRESULT'];
    }

    public function doAdd($mchid, $appid, $appkey, $noticeurl, $config, $channelname, $descript)
    {

        $status = 0;
        $objDataChangeBLL = new MasterBLL();
        $ret = $objDataChangeBLL->addPaychannel($channelname,$mchid, $appid, $appkey, $config, $noticeurl, $descript, $status);
        echo $ret[0]['IRESULT'];
    }

    //删除
    public function delete()
    {
        $channelid = Utility::isNumeric('channelid', $_POST);
        if (!$channelid) {
            echo 1;
        } else {
            $objDataChangeBLL = new MasterBLL();
            $ret = $objDataChangeBLL->deletePaychannel($channelid);
            echo $ret[0]['IRESULT'];
        }
    }

    //修改状态
    public function change()
    {
        $channelid = Utility::isNumeric('channelid', $_POST);
        $status = intval($_POST['status']);
        if (!$channelid || !in_array($status, array(0,1))) {
            echo 1;
        } else {
            $objDataChangeBLL = new MasterBLL();
            $ret = $objDataChangeBLL->changePaychannel($channelid, $status);
            echo $ret[0]['IRESULT'];
        }
    }
}
