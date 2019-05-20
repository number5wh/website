<?php
require ROOT_PATH . 'Class/DAL/DataCenterDAL.class.php';
class DataCenterBLL
{
    private $objDataCenterDAL = NULL;
    private $download_server_type = 3;   //下载站 服务器类型
    public function __construct()
    {
        $this->objDataCenterDAL = new DataCenterDAL();
    }
    
    /**
     * 获取服务器列表
     * @param  int ServerType     服务器类型
     * @param  int Locked        状态
     * 
     * */
    public function getServerList($CurTime,$ServerType){
        return $this->objDataCenterDAL->DCGetServerList($CurTime, $ServerType);
    }
    /**
     * 获取服务器列表
     * @param  int ServerType     服务器类型
     * @param  int Locked        状态
     *
     * */
    public function getGameServerInfoList($ServerType,$Locked){
        $ServerList = $this->getServerList(time(), $ServerType);
        $arrReturn = array();
        foreach ($ServerList as $val){
            if($val['Locked']==$Locked){
                $arrReturn[] = $val;
            }
        }
        return $arrReturn;
    }
    
    /***
     * 获取更新版本文件
     * @param int VerType  版本类型(1:游戏种类版本,2:大厅版本,3:道具版本)
     * @param int Version   文件最新版本
     * @param int KindID   游戏种类
     * 
     * */
    public function getGameVersion($CurTime){
        $ServerList = $this->getServerList($CurTime, $this->download_server_type);
        $ServerIPList = array();   //ServerID=>IP映射数组
        foreach ($ServerList as $val){
            $ServerIPList[$val['ServerID']] = $val['ServerIP'];
        }
        $arrReturn = $this->objDataCenterDAL->DCGetGameVersion($CurTime);
        foreach ($arrReturn as $key => $val){
            $arrReturn[$key]['ServerIP'] = $ServerIPList[$val['ServerID']];
        }
        return $arrReturn;
    }
     
     /***
     * 获取更新版本文件
     * @param int VerType  版本类型(1:游戏种类版本,2:大厅版本,3:道具版本)
     * @param int Version   文件最新版本
     * @param int KindID   游戏种类
     * 
     * */
    
    public function Download($VerType,$Version,$KindID ){
        $GameVersionList = $this->getGameVersion(time());
        $arrReturn = array();
        foreach ($GameVersionList as  $val){
            if($val['VerType']==$VerType && $val['KindID'] == $KindID && $val['Version']>$Version){
                $arrVersion = array();
                $arrVersion['FileName']=$val['FileName'];
                $arrVersion['FileURL']=$val['FileURL'];
                $arrVersion['FileCategory']=$val['FileCategory'];
                $arrVersion['ServerIP']=$val['ServerIP'];
                $arrVersion['Version']=$val['Version'];
                $arrVersion['LocalPath'] = $val['LocalPath'];
                $arrReturn[] = $arrVersion;
            }
        }
        return $arrReturn;
    }
    
    /***
     * 获取安卓版本更新信息
     * @param $cur  int 当前版本号
     * @param $new int 当前版本的下一个版本号
     * 
     * */
    public function getAndroidVersionDiff($cur,$new){
        $list = $this->objDataCenterDAL->DCGetAndroidVersion();   //获取安卓版本信息
        $vect = array();
        //建图
        foreach($list as $key => $val){
            $vect[$val['LowVersion']][] = $val;
        }
        $queue = array();
        array_push($queue,$cur);
        
        $path = array();
        $dis = array();
        $dis[$cur] = 1;
        
        while(count($queue)){
            $x = array_pop($queue);
            if(!isset($vect[$x])) continue;
            foreach($vect[$x] as $key =>$val){
                $to = $val['HighVersion'];
                if(empty($dis[$to])){
                    $dis[$to] = $dis[$x] + 1;
                    $path[$to] = $val;
                    array_push($queue,$to);
                }
            }
        }
        if(!empty($dis[$new])){
            $t = $new;
            $data = [];
            while($t != $cur && $path[$t]){
                $data[] = $path[$t];
                $t = $path[$t]['LowVersion'];
            }
            return $data;
        }
        return false;
    }
    /**
     * 获取游戏盾开关信息
     */
    public function GetYouXiDunInfo(){
        return $this->objDataCenterDAL->DCGetYouXiDunInfo();
    }
}
?>