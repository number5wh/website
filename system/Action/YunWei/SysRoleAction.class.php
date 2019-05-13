<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/OperationLogsBLL.class.php';

/**
 * Class SysRoleAction
 * 角色
 */
class SysRoleAction extends PageBase
{
    private $objMasterBLL = null;
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);
        $this->objMasterBLL = new MasterBLL();
	}
	public function index()
	{
        $this->smarty->display($this->arrConfig['skin'].'/Yunwei/SysRoleList.html');
	}

    public function getRoleList() {
        $strWhere = ' ';
        $arrUserList = null;
        $curPage = Utility::isNumeric('curPage', $_POST);
        $curPage = $curPage <= 0 ? 1 : $curPage;

        $arrParam['fields'] = 'Id,RoleName,Descript,Rules';
        $arrParam['tableName'] = 'T_AdminRole';
        $arrParam['where'] = $strWhere;
        $arrParam['order'] = ' ';
        $arrParam['pagesize'] = 20;
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
                $val['RoleName'] = Utility::gb2312ToUtf8($val['RoleName']);
                $val['Descript'] = Utility::gb2312ToUtf8($val['Descript']);
            }
            unset($val);
        }

        $arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'RoleList'=>$list);
        Utility::assign($this->smarty,$arrTags);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Yunwei/SysRoleListPage.html');
        //echo $html;
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }


    public function showAdd()
    {
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Yunwei/SysRoleAdd.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }

    public function addRole()
    {
        $rolename = Utility::isNullOrEmpty('rolename', $_POST);
        $descript = Utility::isNullOrEmpty('descript', $_POST);


        if (!$rolename || !$descript) {
            echo -1;
        } else {
            $ret = $this->objMasterBLL->addRole($rolename, $descript);
            echo $ret[0]['iResult'];
        }
    }

    public function showEdit()
    {
        $roleid = Utility::isNumeric('roleid', $_POST);
        $rolename = Utility::isNullOrEmpty('rolename', $_POST);
        $descript = Utility::isNullOrEmpty('descript', $_POST);
        $arrTags = array('roleid' => $roleid, 'rolename' => $rolename, 'descript' => $descript);
        $send = array('Info'=>$arrTags);
        Utility::assign($this->smarty, $send);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Yunwei/SysRoleEdit.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }
    
    public function editRole()
    {
        $roleid = Utility::isNumeric('roleid', $_POST);
        $rolename = Utility::isNullOrEmpty('rolename', $_POST);
        $descript = Utility::isNullOrEmpty('descript', $_POST);


        if (!$roleid || !$rolename || !$descript) {
            echo -1;
        } else {
            $ret = $this->objMasterBLL->editRole($roleid, $rolename, $descript);
            echo $ret[0]['iResult'];
        }
    }

    public function deleteRole()
    {

        $roleid = Utility::isNumeric('roleid', $_POST);
        if (!$roleid) {
            echo -1;
        } else {
            $ret = $this->objMasterBLL->deleteRole($roleid);
            echo $ret[0]['iResult'];
        }
    }

    //设置菜单
    public function showSet()
    {
        $roleid = Utility::isNumeric('roleid', $_POST);
        $strWhere = ' ';
        $arrUserList = null;
        $curPage = Utility::isNumeric('curPage', $_POST);
        $curPage = $curPage <= 0 ? 1 : $curPage;

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

        $menuInfo = [];
        if ($roleid) {
            $menuInfo = $this->objMasterBLL->getMenuById($roleid);
            $menuInfo = $menuInfo[0];
            $menuInfo['Rules'] = explode(',',$menuInfo['Rules']);
        }

        $arrTags = array('Menulist' => $menuList, 'Roleid' => $roleid, 'SelfMenu' => $menuInfo['Rules']);
        $send = array('Info'=>$arrTags);

        Utility::assign($this->smarty, $send);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Yunwei/SysRoleMenu.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }

    public function editSet()
    {//找不到存储过程 另外需要获取角色菜单
        $roleid = Utility::isNumeric('roleid', $_POST);
        $set = Utility::isNullOrEmpty('set', $_POST);
        if (!$roleid || !$set) {
            echo -1;
        } else {
            $ret = $this->objMasterBLL->setRoleMenu($roleid, $set);
            echo $ret[0]['iResult'];
        }
    }


}
?>