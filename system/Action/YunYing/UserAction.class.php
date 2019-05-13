<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/OperationLogsBLL.class.php';
require ROOT_PATH . 'Link/QueryOnlinePlayer.php';

class UserAction extends PageBase
{	
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);		
	}
	
	public function index()
	{		
		$arrParam['tableName']='T_Role';
		$arrParam['where']='';
		//$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['User']);
		$iRecordsCount = 11;//$objCommonBLL->getRecordsCount($arrParam);
        
        //在线玩家数统计
        $arrResult = DCQueryOnlinePlayer();
        $iTotalCount = "查询失败";
        if(is_array($arrResult) && isset($arrResult['iTotalCount'])){
            $iTotalCount = $arrResult['iTotalCount'];

        }
        $data['StartTime'] = date("Y-m-d");
        $data['EndTime'] = date("Y-m-d");
        //
		$arrTags=array('skin'=>$this->arrConfig['skin'],'ad'=>$data,'RecordsCount'=>$iRecordsCount,'OnlineCount'=>$iTotalCount);
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunYing/UserCount.html');
	}	 
	
	public function search()
	{		
		$strWhere = ' WHERE 1=1';
		$StartTime = Utility::isNullOrEmpty('StartTime', $_POST);
		$EndTime = Utility::isNullOrEmpty('EndTime', $_POST);
        $arrParam['StartDate'] = $StartTime;
        $arrParam['EndDate'] = $EndTime;
		$arrParam['tableName']='T_RegisterLogs_';
		$arrParam['where']=$strWhere;
		//$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['User']);
		//$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
		$objOperationLogsBLL = new OperationLogsBLL(0);
        $iRecordsCount = $objOperationLogsBLL->getRecordsCount($arrParam);
		echo $iRecordsCount;
	}
    public function RoomOnline(){
        $type = Utility::isNumeric('type', $_POST);
        $objMasterBLL = new MasterBLL();

        $arrResult = DCQueryOnlinePlayer();
        $RoomOnlineList = array();
        $UserTotalCount = 0;
        $RobotTotalCount = 0;
        $MobileTotalCount = 0;
        $IOSTotalCount =0;
        $AndroidTotalCount =0;
        $TotalCount = 0;
        if($type==1){
            if($arrResult['iRoomCount']>0){
                $RoomOnlineList = $arrResult['RoomOnlineInfoList'];
                foreach($RoomOnlineList as $key => $vo){
                    $RoomOnlineList[$key]['UpdateTime'] = date('Y-m-d H:i:s',$vo['iUpdateTime']);
                    $RoomOnlineList[$key]['UserCount'] = $vo['iOnLineCount'] - $vo['iRobotCount'];
                    $UserTotalCount += $RoomOnlineList[$key]['UserCount'];
                    $RobotTotalCount += $vo['iRobotCount'];
                    $MobileTotalCount += $vo['iMobileCount'];
                    $TotalCount += $vo['iOnLineCount'];
                    $IOSTotalCount += $vo['iIOSCount'];
                    $AndroidTotalCount += $vo['iAndroidCount'];
                    $RoomInfo = $objMasterBLL->getGameRoomInfo($vo['iRoomID']);
                    $RoomOnlineList[$key]['RoomName'] = $RoomInfo['RoomName'];
                }
            }
        }else{
            if($arrResult['iRoomCount']>0){
                $result = $arrResult['RoomOnlineInfoList'];
                $GameKind = $objMasterBLL->getGameKindList(-1, 0);
                foreach($result as $key => $vo){
                    $vo['UpdateTime'] = date('Y-m-d H:i:s',$vo['iUpdateTime']);
                    $vo['UserCount'] = $vo['iOnLineCount'] - $vo['iRobotCount'];
                    $UserTotalCount += $vo['UserCount'];
                    $RobotTotalCount += $vo['iRobotCount'];
                    $MobileTotalCount += $vo['iMobileCount'];
                    $TotalCount += $vo['iOnLineCount'];
                    $IOSTotalCount += $vo['iIOSCount'];
                    $AndroidTotalCount += $vo['iAndroidCount'];
                    $RoomInfo = $objMasterBLL->getGameRoomInfo($vo['iRoomID']);
                    $KindID = $RoomInfo['KindID'];
                    if(!isset($RoomOnlineList[$KindID])){
                        $RoomOnlineList[$KindID] = $vo;
                        $RoomOnlineList[$KindID]['iRoomID'] = $KindID;
                        foreach ($GameKind as $v){
                            if($v['KindID']==$KindID){
                                $RoomOnlineList[$KindID]['RoomName'] = $v['KindName'];
                                break;
                            }
                            
                        }
                    }else{
                        $RoomOnlineList[$KindID]['iOnLineCount'] += $vo['iOnLineCount'];
                        $RoomOnlineList[$KindID]['UserCount'] += $vo['UserCount'];
                        $RoomOnlineList[$KindID]['iRobotCount'] += $vo['iRobotCount'];
                        $RoomOnlineList[$KindID]['iMobileCount'] += $vo['iMobileCount'];
                        $RoomOnlineList[$KindID]['iUpdateTime'] = min($RoomOnlineList[$KindID]['iUpdateTime'],$vo['iUpdateTime']);
                        $RoomOnlineList[$KindID]['iIOSCount'] += $vo['iIOSCount'];
                        $RoomOnlineList[$KindID]['iAndroidCount']+=$vo['iAndroidCount'];
                    }
                }
            }
        }
        $arrTags = array("RoomOnlineList"=>$RoomOnlineList,"UserTotalCount"=>$UserTotalCount,
            "RobotTotalCount"=>$RobotTotalCount,"TotalCount"=>$TotalCount,"MobileTotalCount"=>$MobileTotalCount,'type'=>$type,"IOSTotalCount"=>$IOSTotalCount,"AndroidTotalCount"=>$AndroidTotalCount);

        Utility::assign($this->smarty,$arrTags);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/YunYing/RoomOnlineInfo.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }
}
?>