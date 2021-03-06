<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/1/7
 * Time: 12:42
 */

require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/DataChangeLogsBLL.class.php';

require_once ROOT_PATH . 'Link/SetRoomCtrl.php';
class ControlleUserAction extends PageBase
{

    public function __construct()
    {
        $this->arrConfig=unserialize(SYS_CONFIG);
        Utility::chkUserLogin($this->arrConfig);
        $this->objMasterBLL = new MasterBLL();
    }




    public function index(){
        //游戏种类列表
        $arrKindList = $this->objMasterBLL->getGameKindList(-1,-1);

        $arrTags=array('KindList'=>$arrKindList);
        Utility::assign($this->smarty,$arrTags);
        $this->smarty->display($this->arrConfig['skin'].'/Service/ControlRoomList.html');




//        echo  "开始";
//       // $outarray  = DSSetRoomRate(63,40);
//        $outarray  = DSQueryRoom(0);
//        //$outarray  = DSSetUserRate(70220350,50,120,300);
//        print_r($outarray);
       // $outarray  = DSSetRoomRate(63,40);

    }



    public function getPagerRoom()
    {
        $strWhere = ' WHERE isShow=0 ';
        $KindID = Utility::isNumeric('KindID',$_POST);
        $RoomName = Utility::isNullOrEmpty('RoomName',$_POST);
        if($KindID) $strWhere .= ' AND KindID='.$KindID;
        if($RoomName) $strWhere .= ' AND RoomName like \'%'.Utility::utf8ToGb2312($RoomName).'%\'';
        $curPage = Utility::isNumeric('curPage',$_POST);
        $curPage = $curPage==0 ? 1 : $curPage;
        $arrParam['fields']='RoomID,KindID,RoomType,RoomName,MaxTableCount,MaxPlayerCount,TableSchemeId,isShow';
        $arrParam['tableName']='T_GameRoomInfo';
        $arrParam['where']=$strWhere;
        $arrParam['order']=' KindID ';
        $arrParam['pagesize']=50;

        $objCommonBLL = new CommonBLL(0);
        $iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);

        $Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
        $arrResult = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);

//        var_dump($RoomCtrl);
//        die;

        //获取游戏名称数组
        $arrGameKindName = $this->getGameKindName();
        if(is_array($arrResult) && count($arrResult)>0)
        {
            $iCount = 0;
            foreach ($arrResult as $val)
            {

                $RoomCtrl  = DSQueryRoom($arrResult[$iCount]['RoomID']);

                $arrResult[$iCount]['CtrlRatio'] =0;
                $arrResult[$iCount]['nInitStorage'] =0;
                $arrResult[$iCount]['nCurrentStorage'] =0;
                foreach ($RoomCtrl as $value)
                {

                    if($value['nServerID']==$val['RoomID']){
                        $arrResult[$iCount]['CtrlRatio'] =$value["nCtrlRatio"];
                        $arrResult[$iCount]['nInitStorage'] =$value["nInitStorage"]/1000;
                        $arrResult[$iCount]['nCurrentStorage'] =$value["nCurrentStorage"]/1000;
                    }
                }
                //print_r($arrRoom);

                $arrResult[$iCount]['GameKindIDName'] = $arrGameKindName[$val['KindID']]."(".$val['KindID'].")";
                $arrResult[$iCount]['RoomName']=Utility::gb2312ToUtf8($val['RoomName']);

                if(($val['RoomType']&1) == 1)
                    $arrResult[$iCount]['RoomTypeName'] = "积分房间";
                elseif(($val['RoomType']&2) == 2)
                    $arrResult[$iCount]['RoomTypeName'] = "金币房间";
                elseif(($val['RoomType']&4) == 4)
                    $arrResult[$iCount]['RoomTypeName'] = "比赛房间";
                elseif(($val['RoomType']&8) == 8)
                    $arrResult[$iCount]['RoomTypeName'] = "[体 验]";
                if(($val['RoomType']&32) == 32)
                    $arrResult[$iCount]['RoomTypeName'] = "道具房间";

                $iCount++;
            }
        }

        $arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'GameRoomList'=>$arrResult);
        Utility::assign($this->smarty,$arrTags);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/ControlRoomPage.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }

    /**
     * 将游戏种类数组整理成 KindID=>KIndName形式的新数组
     */
    public function getGameKindName()
    {
        //游戏种类列表
        $arrKindList = $this->objMasterBLL->getGameKindList(-1,-1);
        if(is_array($arrKindList) && count($arrKindList)>0)
        {
            $arrNewKindList = array();
            foreach($arrKindList as $v){
                $arrNewKindList[$v['KindID']] = $v['KindName'];
            }
            return $arrNewKindList;
        }
        return '';
    }



    public function ShowAddCtrlRoomHtml(){
        $RoomID = Utility::isNullOrEmpty('RoomID',$_POST);
        $RoomCtrl  = DSQueryRoom($RoomID);
        //print_r($RoomCtrl);


        $arrRoom =array('nServerID'=>$RoomID,"nCtrlRatio"=>0,"nInitStorage"=>0,"nCurrentStorage"=>0);
        if(is_array($RoomCtrl)){
            $arrRoom =$RoomCtrl[0];
        }
        $arrTags=array("Room"=>$arrRoom);
        //print_r($arrTags);
        Utility::assign($this->smarty,$arrTags);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/ControlRoomEdit.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }

    //编辑弹窗
    public function showEdit()
    {
        $RoomID = Utility::isNullOrEmpty('RoomId',$_POST);
        $RoomCtrl  = DSQueryRoom($RoomID);
        $roomInfo = $RoomCtrl[0];
        $storage = explode('#', $roomInfo['szStorageRatio']);
        $info = array_chunk($storage,2);

        $arrTags=array("storage"=>$info, "Room" => $roomInfo);
        //print_r($arrTags);
        Utility::assign($this->smarty,$arrTags);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/ControlRoomEdit.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }


    public function setStorage()
    {
        $RoomID = Utility::isNumeric('RoomId',$_REQUEST);
        $Ratio = $_REQUEST['Ratio'];
        $Storage = $_REQUEST['Storage'];
        $ratioInfo = explode(',', $Ratio);
        $storageInfo = explode(',', $Storage);

        if (!$RoomID || !$ratioInfo || !$storageInfo || count($storageInfo) != count($ratioInfo)) {
            echo -9999;
            exit;
        }
        $RoomCtrl  = DSQueryRoom($RoomID);
        if (!$RoomCtrl) {
            echo -1;
            exit;
        }

        sort($storageInfo);
        rsort($ratioInfo);

        $storageInfo = array_unique($storageInfo);
        $ratioInfo = array_unique($ratioInfo);

        if (count($storageInfo) != count($ratioInfo)) {
            echo -9999;
            exit;
        }

        $storageStr = '';
        for ($i=0; $i<count($storageInfo);$i++) {
            $storageStr .= $storageInfo[$i].'#'.$ratioInfo[$i].'#';
        }
        $storageStr = rtrim($storageStr, '#');


//        var_dump($RoomCtrl);
//        die;



//        var_dump($storageStr);
//        die;
        DSSetRoomRate($RoomID,$RoomCtrl[0]['nCtrlRatio'],$RoomCtrl[0]['nInitStorage'],$RoomCtrl[0]['nCurrentStorage'], $storageStr);
        echo 0;
    }

    //查看
    public function showInfo()
    {
        $RoomID = Utility::isNullOrEmpty('RoomId',$_POST);
        $RoomCtrl  = DSQueryRoom($RoomID);
        $roomInfo = $RoomCtrl[0];
//        var_dump($roomInfo);
//        die;
        $storage = explode('#', $roomInfo['szStorageRatio']);
        $info = array_chunk($storage,2);

        $arrTags=array("storage"=>$info, "Room" => $roomInfo);
        //print_r($arrTags);
        Utility::assign($this->smarty,$arrTags);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/ControlRoomShow.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }

    //设置初始库存
    public function setInitStorage()
    {
        $RoomID = Utility::isNullOrEmpty('RoomId',$_POST);
        $InitStorage = Utility::isNullOrEmpty('InitStorage',$_POST);
        $RoomCtrl  = DSQueryRoom($RoomID);
        DSSetRoomRate($RoomID,$RoomCtrl[0]['nCtrlRatio'],$InitStorage*1000,$RoomCtrl[0]['nCurrentStorage'], $RoomCtrl[0]['szStorageRatio']);
        echo json_encode(['code'=>0]);
    }

    //设置当前库存
    public function setCurrStorage()
    {
        $RoomID = Utility::isNullOrEmpty('RoomId',$_POST);
        $CurrStorage = Utility::isNullOrEmpty('CurrStorage',$_POST);
        $RoomCtrl  = DSQueryRoom($RoomID);
        DSSetRoomRate($RoomID,$RoomCtrl[0]['nCtrlRatio'],$RoomCtrl[0]['nInitStorage'],$CurrStorage*1000, $RoomCtrl[0]['szStorageRatio']);
        echo json_encode(['code'=>0]);
    }

//    public function sendctrl(){
//        $RoomID = Utility::isNumeric('RoomId',$_GET);
//        $Ratio = Utility::isNumeric('Ratio',$_GET);
//        $Storage = Utility::isNumeric('Storage',$_GET);
//        $CurrentStorage = Utility::isNumeric('CurrentStorage',$_GET);
//
//
//        if(empty($RoomID) || empty($Ratio) || empty($Storage) || empty($CurrentStorage)){
//            echo -9999;
//        }
//        DSSetRoomRate($RoomID,$Ratio,$Storage*1000,$CurrentStorage*1000);
//        echo 0;
//    }

}