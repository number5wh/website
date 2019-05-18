<?php
require_once __DIR__.'/DALBase.php';

include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SvrPtl/Socket.php';
include_once ROOT_PATH.'Common/SvrPtl/PHPStream.php';;
include_once ROOT_PATH.'Common/SePtlDCToOW.php';
include_once ROOT_PATH.'Common/SePtlOWToDC.php';

class IndexServiceDAL extends DALBase
{

    public function __construct()
    {
        parent::__construct();
    }
    /*
     * 获取活动信息
     */
    public function getPagerHappyBeanSortTop100(){
        $roleid = $_GET['roleid'];
        $strSelectKeySys = "getPagerHappyBeanSortTop100".$roleid;
        $arrReturns = $this->objMemcache->get($strSelectKeySys);
        $arrReturns = [];
        if(!$arrReturns){
            $ch = curl_init('http://172.23.3.15:8099/?d=Game&c=HappyBean&a=getPagerHappyBeanSortTop100&roleid='.$roleid);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $output = curl_exec($ch);
            curl_close($ch);
            if ($output) {//
                $arrReturns = $output;
                $this->objMemcache->set($strSelectKeySys,$arrReturns,0,600);
            } else {
                $arrReturns = "[]";
            }
        }
        return $arrReturns;
    }

    public function getMsgInfo(){
        $strSelectKeySys = "getMsgInfo";
        $arrReturns = $this->objMemcache->get($strSelectKeySys);
        if(!$arrReturns){
            $ch = curl_init('http://172.23.3.15:8099/?d=Game&c=GamePost&a=getMsgInfo');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $output = curl_exec($ch);
            curl_close($ch);
            if ($output) {//
                $arrReturns = $output;
                $this->objMemcache->set($strSelectKeySys,$arrReturns,0,600);
            } else {
                $arrReturns = "[]";
            }
        }
        return $arrReturns;
    }


    public function getEmailInfo(){
        $params = (array)json_decode ( file_get_contents ( 'php://input' ) ,true);
        $roleid = $params["roleid"];
        if(empty($roleid)){
            $roleid=0;
        }
        $strSelectKeySys = "getEmailInfo111".$roleid;
        $arrReturns = $this->objMemcache->get($strSelectKeySys);
        if(!$arrReturns){
            $ch = curl_init('http://172.23.3.15:8099/?d=Game&c=GamePost&a=getEmailInfo&roleid='.$roleid);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $output = curl_exec($ch);
            curl_close($ch);
            if ($output) {//
                $arrReturns = $output;
                $this->objMemcache->set($strSelectKeySys,$arrReturns,0,600);
            } else {
                $arrReturns = "[]";
            }
        }
        return $arrReturns;
    }
}
?>