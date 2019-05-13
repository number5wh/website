<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/10/26
 * Time: 13:42
 */
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Common/Zip.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';


require ROOT_PATH . 'Link/QueryRoleList.php';
require ROOT_PATH . 'Link/OMRegisterAccount.php';

class RobotCreatorAction extends PageBase{
    private $objMasterBLL = null;
    public function __construct()
    {
        $this->arrConfig=unserialize(SYS_CONFIG);
        Utility::chkUserLogin($this->arrConfig);
        $this->objMasterBLL = new MasterBLL();

    }
    public function index(){
        $this->smarty->display($this->arrConfig['skin'].'/YunWei/RobotCreator.html');
    }

    /**添加的html页面
     *
     */
    public function showRobotCreatorHtml(){
        /*$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/RoomRobotEdit.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;*/
        $objMasterBLL = new MasterBLL(0);
        $RoomInfo = $objMasterBLL->getGameRoomInfoList();
        $arrTags = array("RoomInfo"=>$RoomInfo);
        Utility::assign($this->smarty,$arrTags);
        $this->smarty->display($this->arrConfig['skin'].'/YunWei/RobotCreatorEdit.html');
    }

    /**批量注册添加机器人
     *
     */
    public function addMulRobot(){

        $NumM = Utility::isNumeric('NumM',$_REQUEST);
        $NumN = Utility::isNumeric('NumN',$_REQUEST);

        $arrParams['RoomID']= Utility::isNumeric('RoomID',$_REQUEST);
        $arrParams['ServiceTime']= Utility::isNumeric('ServiceTime',$_REQUEST);
        $arrParams['MinTakeScore']= Utility::isNumeric('MinTakeScore',$_REQUEST);
        $arrParams['MaxTakeScore']= Utility::isNumeric('MaxTakeScore',$_REQUEST);
        $arrParams['MinPlayDraw']= Utility::isNumeric('MinPlayDraw',$_REQUEST);
        $arrParams['MaxPlayDraw']= Utility::isNumeric('MaxPlayDraw',$_REQUEST);
        $arrParams['MinReposeTime']= Utility::isNumeric('MinReposeTime',$_REQUEST);
        $arrParams['MaxReposeTime']= Utility::isNumeric('MaxReposeTime',$_REQUEST);
        $arrParams['ServiceGender']= Utility::isNumeric('ServiceGender',$_REQUEST);
        $username = "robot";
        $password = "123456";
        $ip = "127.0.0.1";
        $name = "123";
        $ID_NUMBER = "330000000000000000";
        $mobile = "13000000000";
        $QQ = "111111111";
        if($NumM > $NumN){
            exit("参数错误");
        }
        $data = array();
        for($i = $NumM; $i < $NumN; $i++){
            $iResult = OmASRegisterAccount($username.$i,md5($password),$ip,Utility::utf8ToGb2312($name),$ID_NUMBER,$mobile,$QQ);
            //$iResult['iResult'] = 0;
            //var_dump($iResult);

            if($iResult['iResult']==0){
                $dataArr= array('username'=>$username.$i);
                $temp = ASQueryRoleList($username.$i,4);

                //var_dump($temp);
                if(isset($temp['RoleInfoList']) && count($temp['RoleInfoList']>0)) {
                    $arrPassSec = $temp['RoleInfoList'][0];
                    $LoginID = $arrPassSec['iLoginID'];
                    $arrParams['UserID'] = $LoginID;
                    //echo $LoginID;
                    $iResult = $this->objMasterBLL->addRobotUser($arrParams);
                    $dataArr['add_to_user_robot'] = $iResult;
                    //var_dump($iResult);
                }
                $data[] = $dataArr;
            }
        }
        $resultMsg = "共有".count($data)."个机器人注册成功";
        $arrTags = array('RobotRegisterList'=>$data,'resultMsg'=>$resultMsg,'click'=>'main.CloseMsgBox','btnValue'=>'确定');
        Utility::assign($this->smarty,$arrTags);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/msgbox.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }
}