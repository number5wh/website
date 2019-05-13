<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/11/24
 * Time: 20:54
 */
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/DataChangeLogsBLL.class.php';

require_once ROOT_PATH . 'Link/GetRoleBaseInfo.php';
class ColorEggAction extends PageBase {
    private $objMatchBLL = null;
    public function __construct()
    {
        $this->arrConfig=unserialize(SYS_CONFIG);
        Utility::chkUserLogin($this->arrConfig);
    }


    function index(){
        $arrResult = null;
        $objMasterBLL = new MasterBLL();

        $objMasterBLL = new MasterBLL(0);

        $roomInfoList = $objMasterBLL->getGameRoomInfoList();

        $arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'ColorEggList'=>$arrResult['arrGameRateList'],'EndTime'=>date('Y-m-d'),'RoomInfoList'=>$roomInfoList);
        Utility::assign($this->smarty,$arrTags);

        $this->smarty->display($this->arrConfig['skin'].'/YunYing/ColorEggList.html');
    }

    /**彩蛋分页
     *
     */
    function getPagerColorEgg($pagesize){
        $loginID = Utility::isNumeric('LoginID',$_REQUEST);
        $EndTime = Utility::isNullOrEmpty('EndTime',$_REQUEST);
        $RoomID = Utility::isNumeric('RoomID',$_REQUEST);
        $curPage = Utility::isNumeric('curPage',$_REQUEST);
        $curPage = $curPage <= 0 ? 1 : $curPage;

        $strWhere = ' WHERE 1=1 ';//彩蛋

        if($RoomID){
            $strWhere .= "AND ServerID = {$RoomID}";
        }
        if($loginID){
            $strWhere .= "AND RoleID = {$loginID}";
        }
        $arrParam['fields']='* ';
        $arrParam['tableName']='T_LuckyEggMoney_'.date('Ymd',strtotime($EndTime));
        $arrParam['where']=$strWhere;
        $arrParam['order']=" BuyTime DESC ";
        $arrParam['pagesize']= $pagesize;



        $objCommonBLL = new CommonBLL($this->arrConfig['MapType']['DataChangeLogs']);
        $objMasterBLL = new MasterBLL(0);
        $iRecordsCount = $objCommonBLL->getRecordsCountSelect($arrParam);

        //var_dump($iRecordsCount);
        $Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
        $arrColorEggList = array();
        if($iRecordsCount>0)
            $arrColorEggList = $objCommonBLL->getPageListSelect($arrParam,$Page['CurPage']);

        //var_dump($arrColorEggList);
        foreach($arrColorEggList as $key => &$val){

            $roomInfo = $objMasterBLL->getGameRoomInfo($val['ServerID']);
            //var_dump($roomInfo);
            $val['RoomName'] = $roomInfo['RoomName'];
            $val['RoleName'] = Utility::gb2312ToUtf8($val['RoleName']);
            $val['BuyTime'] = date('Y-m-d H:i:s',strtotime($val['BuyTime']));
            $val['Money'] = Utility::FormatMoney($val['Money']);
        }
        unset($val);

        $Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
        //var_dump($arrColorEggList);
        return array('arrColorEggList'=>$arrColorEggList,'Page'=>$Page);
    }

    /**
     *
     */
    public function getPagerColorEggList(){
        $arrResult = $this->getPagerColorEgg(20);
        $arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'ColorEggList'=>$arrResult['arrColorEggList']);
        Utility::assign($this->smarty,$arrTags);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/YunYing/ColorEggListPage.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }

}