<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/12/31
 * Time: 14:26
 */
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
/**
 * Class RealCardFormAction 实卡数据统计
 */
class RealCardFormAction extends PageBase{
    public function __construct(){
        $this->arrConfig = unserialize(SYS_CONFIG);
        Utility::chkUserLogin($this->arrConfig);
    }

    public function index()
    {
        $DateTime = date('Y-m-d');
        $arrTags=array('skin'=>$this->arrConfig['skin'],'DateTime'=>$DateTime);
        Utility::assign($this->smarty,$arrTags);
    
        $this->smarty->display($this->arrConfig['skin'].'/YunWei/RealCard.html');
    }
    public function getRealCardFormList(){
        $StartTime = Utility::isNullOrEmpty('StartTime',$_POST) ? $_POST['StartTime'] : date('Y-m-d');
        $EndTime = Utility::isNullOrEmpty('EndTime',$_POST) ? $_POST['EndTime'] : date('Y-m-d');
        $list = $this->tongji($StartTime,$EndTime);
        $arrTotal = array();
        $arrTotal['TotalMoney'] = 0;
        $arrTotal['TotalNum'] = 0;
        $arrStatus = $this->arrConfig['RealCardStatus'];
        $arrStatusMap = Utility::array_column($arrStatus,'name','value');
        if(is_array($list) && count($list)){
            foreach($list as &$val){
                $val['StateTips'] = $arrStatusMap[$val['State']];
                $arrTotal['TotalMoney'] += $val['TotalMoney'];
                $arrTotal['TotalNum'] += $val['TotalNum'];
            }
            unset($val);
        }

        $arrTags = array("RealCardStatus"=>$this->arrConfig['RealCardStatus'],"FormList"=>$list,"arrTotal"=>$arrTotal);



        Utility::assign($this->smarty,$arrTags);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/RealCardForm.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;  
    }

    private function tongji($StartTime,$EndTime){
        $objMasterBLL = new MasterBLL();
        $list = $objMasterBLL->summaryRechargeCard($StartTime,$EndTime);
        //var_dump($list);
        return $list;
    }
}