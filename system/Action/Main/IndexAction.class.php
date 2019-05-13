<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/PayLogsBLL.class.php';

class IndexAction extends PageBase
{	
	private $objMasterBLL = null;
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);
	}
	public function index()
	{
        $objSessioinLogin = new Session($this->arrConfig['Session']['SessionLoginName']);
        $UserName = $objSessioinLogin->get($this->arrConfig['SessionInfo']['UserName']);
        $DeptID = $objSessioinLogin->get($this->arrConfig['SessionInfo']['DeptID']);
        $AdminRole = $objSessioinLogin->get($this->arrConfig['SessionInfo']['AdminRole']);

        $objmsterBll = new MasterBLL();

        $menuInfo = $objmsterBll->getMenuById($AdminRole);
        $menuInfo = $menuInfo[0];
        $selfMenu = explode(',',$menuInfo['Rules']);
        $menuList = $this->getMenu();
        $reviewnum = $objmsterBll->getReviewNum();
        //print_r($reviewnum);

        $sp = '';
        foreach ($menuList as $m1) {
            $sp.='<div class="gfTit f2"><a class="clsFd f1"></a><a class="gfName" href="">'.$m1['val'].'</a></div>';
            $sp.='<ul id="'.$m1['key'].'" class="filetree">';
            foreach ($m1['sublist'] as $m11) {
                if ($m11['isShow'] == 1 && in_array($m11['Id'], $selfMenu)) {
                    if ($m11['Id'] == 19) {//玩家转出管理
                        $sp.='<li  class="closed"><span class="folder">'.$m11['MenuName'].'<b style="color:red" >('.$reviewnum[0]["total"].')</b></span><ul>';
                    } else {
                        $sp.='<li  class="closed"><span class="folder">'.$m11['MenuName'].'</span><ul>';
                    }

                    foreach ($m11['sublist'] as $m111) {
                        if ($m111['isShow'] == 1 && in_array($m111['Id'], $selfMenu)) {
                            $sp .= '<li><span class="file"><a href="javascript:void(0)" name="' . $m111['Controler'] . '">' . $m111['MenuName'] . '</a></span></li>';
                        }
                    }
                    $sp.='</ul></li>';
                }
            }
            $sp.='</ul>';
        }



        $arrTags = array('UserName'=>$UserName,'content' => $sp, 'DeptID'=>$DeptID,'AutoLogoutCheckTime'=>$this->arrConfig['AutoLogoutCheckTime']);
        Utility::assign($this->smarty,$arrTags);
        $this->smarty->display($this->arrConfig['skin'].'/index.html');
	}


    public function getMenu()
    {
        $strWhere = ' ';
        $arrUserList = null;
        $curPage = 1;

        $arrParam['fields'] = 'Id,MenuName,ParentId,Controler,GroupName,GroupCode,OrderId, isShow';
        $arrParam['tableName'] = 'T_Menu';
        $arrParam['where'] = $strWhere;
        $arrParam['order'] = ' ParentId ASC, OrderId ASC';
        $arrParam['pagesize'] = 100;
        //$arrParam['function'] = 'HappyBeanSort';
        $objCommonBLL = new CommonBLL(0);


        $iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
        $list = [];
        $Page = Utility::setPages($curPage, $iRecordsCount, $arrParam['pagesize']);
        if ($iRecordsCount > 0) {
            $list = $objCommonBLL->getPageList($arrParam, $curPage);
        }

        //var_dump($arrUserList);
        if ($list) {
            foreach ($list as &$val) {
                $val['MenuName'] = Utility::gb2312ToUtf8($val['MenuName']);
                $val['GroupName'] = Utility::gb2312ToUtf8($val['GroupName']);
            }
            unset($val);
        }


        $menuList = [];
        foreach ($this->arrConfig['GroupName'] as $k1 => $v1) {
            $menuList[] = [
                'key' => $k1,
                'val' => $v1,
                'sublist' => []
            ];
        }

        foreach ($menuList as  &$a) {
            foreach ($list as $v) {
                if ($v['GroupCode'] == $a['key'] && $v['ParentId'] == 0) {
                    $a['sublist'][] = $v;
                }
            }
        }
        unset($a);
        foreach ($menuList as  &$b) {
            if ($b['sublist']) {
                foreach ($b['sublist'] as &$c) {
                    $c['sublist'] = [];
                    foreach ($list as $v) {
                        if ($v['ParentId'] == $c['Id']) {
                            $c['sublist'][] = $v;
                        }
                    }
                }
                unset($c);
            }
        }
        unset($b);
        return $menuList;
	}

	public function main()
	{
        $objDataChangeBLL = new MasterBLL();
        $ret = $objDataChangeBLL->getHomedata();

        $arrTags = array('skin' => $this->arrConfig['skin'], 'data' => $ret[0]);
        Utility::assign($this->smarty, $arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/main.html');
	}


	public function getReview(){
        $objmsterBll = new MasterBLL();
        $reviewnum = $objmsterBll->getReviewNum();
        echo $reviewnum[0]['total'];
    }

    //注册数据折线图
    public function regData()
    {
        $objDataChangeBLL = new MasterBLL();
        $ret = $objDataChangeBLL->getRegData();
        $dates = $numbers = [];
        foreach ($ret as $v) {
            $dates[] = $v['regdate'];
            $numbers[] = $v['number'];
        }
        echo json_encode(['code' => 0, 'numbers' => $numbers, 'dates' => $dates]);
    }

    //订单数据折线图
    public function orderData()
    {
        $objPayLogsBLL = new PayLogsBLL(0);
        $list = $objPayLogsBLL->orderData();
        $dates = $numbers = [];
        foreach ($list as $v) {
            $dates[] = $v['date'];
            $numbers[] = $v['totalfee'];
        }
        echo json_encode(['code' => 0, 'numbers' => $numbers, 'dates' => $dates]);
    }
	
}
?>