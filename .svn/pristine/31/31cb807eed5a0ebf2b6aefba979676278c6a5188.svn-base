<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/4
 * Time: 9:49
 */
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';

class AndroidVersionAction extends PageBase{

    public function __construct(){
        $this->arrConfig=unserialize(SYS_CONFIG);
        Utility::chkUserLogin($this->arrConfig);
        //$this->objMasterBLL = new MasterBLL();
    }

    public function index(){

        $this->smarty->display($this->arrConfig['skin'].'/YunWei/AndroidVersionList.html');
    }

    /**
     *获取分页
     */
    public function getPagerAndroidVersion()
    {
        $curPage = Utility::isNumeric('curPage', $_POST) ? $_POST['curPage'] : 1;

        $arrParam['fields'] = 'VerID,FileName,FileURL,LastUpdateTime,LowVersion,HighVersion,ServerIP';
        $arrParam['tableName'] = 'T_AndroidVersionDiff AS V LEFT JOIN T_GameServerInfo AS S ON V.ServerID= S.ServerID';
        $arrParam['where'] = ' WHERE 1=1';
        $arrParam['order'] = 'LastUpdateTime DESC';
        $arrParam['pagesize'] = 20;

        $objCommonBLL = new CommonBLL(0);
        $iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
        $Page = Utility::setPages($curPage, $iRecordsCount, $arrParam['pagesize']);
        $arrVersionList = $objCommonBLL->getPageList($arrParam, $Page['CurPage']);
        if (is_array($arrVersionList) && count($arrVersionList) > 0) {
            foreach($arrVersionList as $key => &$version){
                $version['iCount'] = ($curPage-1)*$arrParam['pagesize'] + $key + 1;
                $version['LowVersion'] = Utility::getVersion($version['LowVersion']);
                $version['HighVersion'] = Utility::getVersion($version['HighVersion']);
            }
        }
        $arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'VersionList'=>$arrVersionList);

        Utility::assign($this->smarty,$arrTags);

        $html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/AndroidVersionListPage.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }

    public function showAddAndroidVersionHtml(){
        $objMasterBLL = new MasterBLL();

        $VerID = Utility::isNumeric('VerID',$_POST);
        $arrServer = $objMasterBLL->getServerList(3,0);

        if($VerID) {
            $arrVersion = $objMasterBLL->getAndroidVersion($VerID);
            if(!empty($arrVersion)){
                $arrVersion['LowVersion'] = Utility::getVersion($arrVersion['LowVersion']);
                $arrVersion['HighVersion'] = Utility::getVersion($arrVersion['HighVersion']);
            }
        }
        else
            $arrVersion = array('FileName'=>'','FileURL'=>'','LowVersion'=>'','HighVersion'=>'','ServerID'=>'','FileCategory'=>1,'VerID'=>-1);
        $arrTags = array('ServerList'=>$arrServer,'skin'=>$this->arrConfig['skin'], 'GameVersion'=>$arrVersion);
        Utility::assign($this->smarty,$arrTags);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/AndroidVersionHtml.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }

    /**
     * 添加android版本差量更新
     */
    public function addAndroidVersion(){

        //var_dump($_REQUEST);exit;
        $VerID = Utility::isNullOrEmpty('VerID',$_POST);//判断是修改还是 新增， 如果是修改 前端是不允去 同时添加的
        $LowVersion = Utility::isNullOrEmpty('LowVersion',$_POST);
        $HighVersion = Utility::isNullOrEmpty('HighVersion',$_POST);
        $FileName = Utility::isNullOrEmpty('FileName',$_POST);
        $FileURL = Utility::isNullOrEmpty('FileURL',$_POST);
        $ServerID = Utility::isNullOrEmpty('ServerID',$_POST);
        $LocalPath = Utility::isNullOrEmpty('LocalPath',$_POST);
        $iResult = 0;

        $objMasterBLL = new MasterBLL();

        if($LowVersion && $HighVersion && $FileName && $FileURL){
            //
            $arrFileName = explode(',', $FileName);
            $arrFileURL = explode(',', $FileURL);
            $arrServerID = explode(',', $ServerID);
            $arrHighVersion = explode(',', $HighVersion);
            $arrLowVersion = explode(',',$LowVersion);
            $arrLocalPath = explode(',', $LocalPath);

            if(is_array($arrFileName) && is_array($arrFileURL) && is_array($arrServerID)
                && is_array($arrHighVersion) && is_array($arrLowVersion)){

                for($i=0; $i < count($arrFileName); $i++){
                    $isNumeric = false;
                    if(is_numeric(str_replace('.','',$arrLowVersion[$i]))
                        && is_numeric(str_replace('.','',$arrHighVersion[$i]))){
                        $isNumeric = true;
                    }

                    if(!empty($arrFileName[$i]) && $isNumeric){
                        $lowVer = Utility::ip2int($arrLowVersion[$i]);
                        $highVer = Utility::ip2int($arrHighVersion[$i]);
                        $Result = $objMasterBLL->addAndroidVersion($lowVer,$highVer,$arrFileName[$i],$arrFileURL[$i],
                            $arrServerID[$i],$VerID);
                        $iResult = !$Result ? $Result: $iResult;
                    }
                }
            }
        }
        if($iResult == 0)
            $msg = '版本发布成功';
        else
            $msg = '版本发布失败';

        echo $msg;
    }
}