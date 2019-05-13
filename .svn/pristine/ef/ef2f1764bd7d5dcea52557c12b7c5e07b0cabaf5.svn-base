<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/8
 * Time: 16:42
 */
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';

class TestAction extends PageBase
{
    private $objMasterBLL = null;

    public function __construct()
    {
        $this->arrConfig = unserialize(SYS_CONFIG);
    }

    public function index()
    {
        $this->smarty->display($this->arrConfig['skin'] . '/Test/generate.html');
    }

    public function generate(){
        $json = $_REQUEST['json'];

        echo Utility::mcryptEncrypt($this->arrConfig,$json);
    }
}