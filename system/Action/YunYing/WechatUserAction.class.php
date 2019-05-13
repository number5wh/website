<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Common/Zip.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';


class WechatUserAction extends PageBase
{
    private $objMasterBLL = null;

    public function __construct()
    {
        $this->arrConfig = unserialize(SYS_CONFIG);
        Utility::chkUserLogin($this->arrConfig);
        $this->objMasterBLL = new MasterBLL();
    }


    public function index(){
       return $this->smarty->display($this->arrConfig['skin'].'/Service/WechatUserList.html');
    }



    public function getPagerServer()
    {
       
        $curPage = Utility::isNumeric('curPage',$_POST);
        $curPage = $curPage<=0 ? 1 : $curPage;
        $arrParam['fields']='id,type,weixinname,noticetip';
        $arrParam['tableName']='T_WeiXinProxy';
        $arrParam['where']=' ';
        $arrParam['order']='type';
        $arrParam['pagesize']=20;

        $objCommonBLL = new CommonBLL(0);
        $iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
        $Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
        $arrServerList = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);
		//print_r($arrServerList); exit();
        if(is_array($arrServerList) && count($arrServerList)>0)
        {
            $iCount = 1;
            foreach($arrServerList as $key => $val)
            {
				$arrServerList[$key]['typename']='推广员';
                if($arrServerList[$key]['type']==1){
                    $arrServerList[$key]['typename']='推广员';
                }
				else if($arrServerList[$key]['type']==2)
				{
					$arrServerList[$key]['typename']='充值客服';
				}
				else if($arrServerList[$key]['type']==3)
				{
					$arrServerList[$key]['typename']="App客服";
				}				
				$arrServerList[$key]['noticetip']=Utility::gb2312ToUtf8($arrServerList[$key]['noticetip']);
            }
        }

        $arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'WechatList'=>$arrServerList);		
        Utility::assign($this->smarty,$arrTags);		
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/WechatUserListPage.html');
		//print($html);exit();
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }


    public function DelUser(){
        $iResult = -9999;
        $WechatID = Utility::isNumeric('pid',$_POST);
        if($WechatID && $WechatID>0)
        {
            $iResult = $this->objMasterBLL->delWechatUser($WechatID);
            if($iResult==0)
                $html='';
            else
                $html=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'ServerGame\')','删除失败,请重试','false','ServerGame',$this->arrConfig);
        }
        else
            $html=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'ServerGame\')','对不起,您提交的数据异常,请重试','false','ServerGame',$this->arrConfig);
        echo json_encode(array('Msg'=>$html,'iResult'=>$iResult));

    }


    public function ShowAddWeChatUserHtml()
    {

        $ID = Utility::isNumeric('tid',$_POST);
        if($ID){
            $arrWechatList = $this->objMasterBLL->getWechatId($ID);
            if(is_array($arrWechatList) && count($arrWechatList)>0)
            {
                $arrConfigInfo['ID']=$arrWechatList[0]['ID'];
                $arrConfigInfo['TypeId']=$arrWechatList[0]['Type'];
                $arrConfigInfo['WeiXinName']=Utility::gb2312ToUtf8($arrWechatList[0]['WeiXinName']);
                $arrConfigInfo['noticetip']=Utility::gb2312ToUtf8($arrWechatList[0]['NoticeTip']);
            }
        }else{
            $arrConfigInfo=array('$TypeID'=>0);
        }
        $arrTags = array('ConfigInfo'=>$arrConfigInfo);
        Utility::assign($this->smarty,$arrTags);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/WechatUserEdit.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;

    }



    public function addWechatUser()
    {
        $iResult = -9999;
        $arrParams['id'] = Utility::isNumeric('id',$_POST);
        $arrParams['TypeID'] = Utility::isNumeric('TypeID',$_POST);
        $arrParams['weixinname'] = Utility::isNullOrEmpty('weixinname',$_POST);
        $arrParams['noticetip'] = Utility::isNullOrEmpty('noticetip',$_POST);
        if($arrParams['TypeID'] && $arrParams['weixinname'])
            $iResult = $this->objMasterBLL->addWechatUser($arrParams);
        echo $iResult;
    }


}


?>
