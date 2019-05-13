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
class SysMenuAction extends PageBase
{
    private $objMasterBLL = null;
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		//Utility::chkUserLogin($this->arrConfig);
        $this->objMasterBLL = new MasterBLL();
	}
	public function index()
	{
        $this->smarty->display($this->arrConfig['skin'].'/Yunwei/SysMenuList.html');
	}

    public function getMenuList() {
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


        $arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'MenuList'=>$menuList);
        Utility::assign($this->smarty,$arrTags);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Yunwei/SysMenuListPage.html');
        //echo $html;
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }


    //设置菜单显示情况
    public function showMenu()
    {
        $id = Utility::isNumeric('id', $_POST);
        $status = Utility::isNumeric('status', $_POST);

        if (!$id || !in_array($status,[0,1])) {
            echo -1;
        } else {
            $ret = $this->objMasterBLL->showMenu($id, $status);

            echo $ret[0]['iResult'];
        }
    }

    //排序
    public function showOrder()
    {
        $id = Utility::isNumeric('id', $_POST);
        $order = Utility::isNumeric('order', $_POST);
        $arrTags = array('id' => $id, 'order' => $order);
        $send = array('Info'=>$arrTags);
        Utility::assign($this->smarty, $send);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Yunwei/SysMenuOrder.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }

    public function doOrder()
    {
        $id = Utility::isNumeric('id', $_POST);
        $order = Utility::isNumeric('order', $_POST);
        if (!$id || !$order) {
            return -1;
        } else {
            $ret = $this->objMasterBLL->doOrder($id, $order);
            echo $ret[0]['iResult'];
        }
    }

    //新增子菜单
    public function showAddMenu()
    {
        $id = Utility::isNumeric('id', $_POST);
        $name = Utility::isNullOrEmpty('name', $_POST);
        $arrTags = array('id' => $id,'name'=>$name);
        $send = array('Info'=>$arrTags);
        Utility::assign($this->smarty, $send);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Yunwei/SysMenuAdd1.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }

    public function doAdd1()
    {
        $id = Utility::isNumeric('id', $_POST);
        $controller = Utility::isNullOrEmpty('controler', $_POST);
        $subname = Utility::isNullOrEmpty('subname', $_POST);
        $order = Utility::isNumeric('order', $_POST);
        if (!$id || !$controller||!$subname) {
            return -1;
        } else {
            $ret = $this->objMasterBLL->addSubMenu($id, $subname, $controller, $order);
            echo $ret[0]['iResult'];
        }
    }

    //新增菜单目录
    public function showAddMenu2()
    {
        $top = $this->arrConfig['GroupName'];
        $arrTags = array('top' => $top);
        $send = array('Info'=>$arrTags);
        Utility::assign($this->smarty, $send);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Yunwei/SysMenuAdd2.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }

    public function doAdd2()
    {
        $group = Utility::isNullOrEmpty('group', $_POST);
        $groupname = $this->arrConfig['GroupName'][$group];
        $name = Utility::isNullOrEmpty('name', $_POST);
        $order = Utility::isNumeric('order', $_POST);
        if (!$group || !$groupname || !$name || !$order) {
            return -1;
        } else {
            $ret = $this->objMasterBLL->addSubMenu2($name, $group, $groupname, $order);
            echo $ret[0]['iResult'];
        }
    }


    //删除菜单
    public function deleteMenu()
    {
        $menuid = Utility::isNumeric('menuid', $_POST);
        if (!$menuid) {
            return -1;
        } else {
            $ret = $this->objMasterBLL->deleteMenu($menuid);
            echo $ret[0]['iResult'];
        }
    }

    //编辑菜单目录
    public function showEdit1()
    {
        $menuid = Utility::isNumeric('menuid', $_POST);
        $menuname = Utility::isNullOrEmpty('menuname', $_POST);
        $order = Utility::isNullOrEmpty('order', $_POST);
        $arrTags = array('menuid' => $menuid, 'menuname' => $menuname, 'order' => $order);
        $send = array('Info'=>$arrTags);
        Utility::assign($this->smarty, $send);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Yunwei/SysMenuEdit1.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }

    public function edit1()
    {
        $menuid = Utility::isNumeric('menuid', $_POST);
        $menuname = Utility::isNullOrEmpty('menuname', $_POST);

        if (!$menuid || !$menuname) {
            return -1;
        } else {
            $ret = $this->objMasterBLL->editMenu1($menuid, $menuname);
            echo $ret[0]['iResult'];
        }
    }

    //编辑子菜单
    public function showEdit2()
    {
        $menuid = Utility::isNumeric('menuid', $_POST);
        $menuname = Utility::isNullOrEmpty('menuname', $_POST);
        $controller = Utility::isNullOrEmpty('controller', $_POST);
        $arrTags = array('menuid' => $menuid, 'menuname' => $menuname, 'controller' => $controller);
        $send = array('Info'=>$arrTags);
        Utility::assign($this->smarty, $send);
        $html = $this->smarty->fetch($this->arrConfig['skin'].'/Yunwei/SysMenuEdit2.html');
        $html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
        echo $html;
    }

    public function edit2()
    {
        $menuid = Utility::isNumeric('menuid', $_POST);
        $menuname = Utility::isNullOrEmpty('menuname', $_POST);
        $controller = Utility::isNullOrEmpty('controller', $_POST);

        if (!$menuid || !$controller|| !$menuname) {
            return -1;
        } else {
            $ret = $this->objMasterBLL->editMenu2($menuid, $menuname, $controller);
            echo $ret[0]['iResult'];
        }
    }
}
?>