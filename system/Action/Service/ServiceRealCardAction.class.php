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

class ServiceRealCardAction extends PageBase{

    public function __construct(){
        $this->arrConfig = unserialize(SYS_CONFIG);
        $this->strLoginedUser = Utility::chkUserLogin($this->arrConfig);

    }

    public function index(){
        $arrTags = array("RealCardStatus"=>$this->arrConfig['RealCardStatus']);
        Utility::assign($this->smarty,$arrTags);
        $this->smarty->display($this->arrConfig['skin'].'/Service/RealCardEdit.html');
    }

    /**
     * @param int $pagesize
     */
    private function getRealCardList($RoleID,$CardNo,$curPage,$pagesize = 10){

        $arrParams['fields'] = ' CardNo,CardPass,Money,State,RoleID,CreateTime,RechargeTime,UpdateTime ';
        $arrParams['tableName'] = ' T_RechargeCard';
        $arrParams['where'] = ' Where 1 = 1 ';
        $arrParams['order'] = ' CreateTime desc';
        $arrParams['pagesize'] = $pagesize;
        $objCommonBLL = new CommonBLL(0);


        $arrStatus = $this->arrConfig['RealCardStatus'];

        if($RoleID !== null && is_numeric($RoleID)){
            $arrParams['where'] .= " AND RoleID = $RoleID ";
        }
        if(!empty($CardNo)){
            $arrParams['where'] .= " AND CardNo = '$CardNo'";
        }
        //var_dump($arrParams);
        $arrStatusMap = Utility::array_column($arrStatus,'name','value');
        $list = array();
        $iRecordsCount = $objCommonBLL->getRecordsCount($arrParams);
        if($iRecordsCount > 0){
            $list = $objCommonBLL->getPageList($arrParams,$curPage);

            foreach($list as &$val){
                if(array_key_exists($val['State'],$arrStatusMap)){
                    $val['StateTips'] = $arrStatusMap[$val['State']];
                }else{
                    $val['StateTips'] = '';
                }
            }
            unset($val);
        }
        $Page = Utility::setPages($curPage,$iRecordsCount,$pagesize);

        //$list = array(array("CardID"=>1));
        return array("arrRealCardList"=>$list,"page"=>$Page);
    }

    /**
     *
     */
    public function getRealCardListPager(){
        //var_dump($_POST);
        $curPage = Utility::isNullOrEmpty('curPage',$_REQUEST) ? $_REQUEST['curPage']:1;
        $RoleID = is_numeric($_REQUEST['RoleID'])? $_REQUEST['RoleID']:null;
        $CardNo = Utility::isNullOrEmpty('CardNo',$_REQUEST);
        //var_dump($_REQUEST);
        //var_dump($state);
        $pagesize = 20;
        $ret = $this->getRealCardList($RoleID,$CardNo,$curPage,$pagesize);

        $arrTag = array("RealCardList"=>$ret['arrRealCardList'],"Page"=>$ret['page'],"skin"=>$this->arrConfig['skin']);
        Utility::assign($this->smarty,$arrTag);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Service/RealCardList.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }
}