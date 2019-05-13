<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/OperationLogsBLL.class.php';
require ROOT_PATH . 'Link/QueryOnlinePlayer.php';

class GoldCoinAction extends PageBase {
    public function __construct() {
        $this->arrConfig = unserialize ( SYS_CONFIG );
        Utility::chkUserLogin ( $this->arrConfig );
    }
    
    
    
    public function index(){  
       
        $objMasterBLL = new MasterBLL();
        $arrResult = DCQueryOnlinePlayer();
        $RoomOnlineList = array();
        $UserTotalCount = 0;
        $RobotTotalCount = 0;
        $MobileTotalCount = 0;
        $IOSTotalCount =0;
        $AndroidTotalCount =0;
        $TotalCount = 0;
        
        $iTotalCount = "查询失败";
        if(is_array($arrResult) && isset($arrResult['iTotalCount'])){
            $iTotalCount = $arrResult['iTotalCount'];        
        }

        $onlineUser =0;
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
                    $onlineUser =$onlineUser+$vo['UserCount'];

                }
            }
        }
        
        $objMasterBLL = new MasterBLL ();
        $retdata = $objMasterBLL->getGameDataCount();
        $totalcoin =$retdata[0]['usercointotal'] +  $retdata[0]['banktotal'];
        $iusertotal =$retdata[0]['usercointotal'] + $retdata[0]['userbank'];
        $ivipusertotal =$retdata[0]['uservipcointotal'] + $retdata[0]['vipuserbank'];
        $totalcashout =$retdata[0]['cashout'];
        $totaltax =$retdata[0]['totaltax'];
        
        $arrTags = array("RoomOnlineList"=>$RoomOnlineList,"UserTotalCount"=>$UserTotalCount,
            "RobotTotalCount"=>$RobotTotalCount,"TotalCount"=>$TotalCount,"MobileTotalCount"=>$MobileTotalCount,
            "IOSTotalCount"=>$IOSTotalCount,"AndroidTotalCount"=>$AndroidTotalCount,"iTotalCount"=>$iTotalCount,'retdata'=>$retdata[0],
            'ivipusertotal'=>$ivipusertotal,
            'totalcoin'=>$totalcoin,
            'iusertotal'=>$iusertotal,
            "onlineUser"=>$onlineUser,
            "totalcashout"=>$totalcashout,
            "totaltax"=>$totaltax
        );
       
        
        Utility::assign($this->smarty,$arrTags);
        $this->smarty->display($this->arrConfig ['skin'] . '/YunYing/Goldcoinsum.html' );    
        
    }
    
    
    
    
    
    
    
    
}