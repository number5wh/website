<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/12/31
 * Time: 13:43
 */
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';

class ServiceRealCardGetAction extends PageBase{

    public function __construct(){
        $this->arrConfig = unserialize(SYS_CONFIG);
        $this->strLoginedUser = Utility::chkUserLogin($this->arrConfig);

    }

    public function index(){
        $this->smarty->display($this->arrConfig['skin'].'/Service/RealCardGetEdit.html');
    }

    /**
     * @param int $pagesize
     */
    private function getRealCardList($Mobile,$CardNo,$curPage,$pagesize = 10){

        $arrParams['fields'] = ' CardNo,CardPass,Mobile,CreateTime,GetTime ';
        $arrParams['tableName'] = ' T_TestCard';
        $arrParams['where'] = ' Where 1 = 1 ';
        $arrParams['order'] = ' CreateTime desc';
        $arrParams['pagesize'] = $pagesize;
        $objCommonBLL = new CommonBLL(15);


        $arrStatus = $this->arrConfig['RealCardStatus'];
        if(!empty($CardNo)){
            $arrParams['where'] .= " AND CardNo = '$CardNo'";
        }else   if($Mobile !== null){
            $arrParams['where'] .= " AND Mobile = '$Mobile' ";
        }else{
            $Page = Utility::setPages($curPage,0,$pagesize);
            //$list = array(array("CardID"=>1));
            return array("arrRealCardList"=>null,"page"=>$Page);
        }

        $arrStatusMap = Utility::array_column($arrStatus,'name','value');
        $list = array();
        $iRecordsCount = $objCommonBLL->getRecordsCount($arrParams);
        if($iRecordsCount > 0){
            $list = $objCommonBLL->getPageList($arrParams,$curPage);
        }
        $Page = Utility::setPages($curPage,$iRecordsCount,$pagesize);
        //$list = array(array("CardID"=>1));
        return array("arrRealCardList"=>$list,"page"=>$Page);
    }

    /**
     *
     */
    public function getRealCardListPager(){
        $curPage = Utility::isNullOrEmpty('curPage',$_REQUEST) ? $_REQUEST['curPage']:1;
        $Mobile = Utility::isNullOrEmpty('RoleID',$_REQUEST)? $_REQUEST['RoleID']:null;
        $CardNo = Utility::isNullOrEmpty('CardNo',$_REQUEST);
        //var_dump($_REQUEST);
        //var_dump($state);
        $pagesize = 20;
        $ret = $this->getRealCardList($Mobile,$CardNo,$curPage,$pagesize);

        $arrTag = array("RealCardList"=>$ret['arrRealCardList'],"Page"=>$ret['page'],"skin"=>$this->arrConfig['skin']);
        Utility::assign($this->smarty,$arrTag);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/RealCardGetList.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }
}