<?php
require_once ROOT_PATH . 'Common/PageBase.class.php';
/**
 * 首页
 * @author xuluojiong
 */
class HelpCenterAction extends PageBase
{
	private $objMasterBLL;
	private $objPassAccountBLL;	
	private $skin=null;
	private $template_dir=null;
	private $smarty;	
	private $CFG=null;
	
	//当前分页
	private $pageids;
	private $param = array();
	public function __construct()
	{		
		global $smarty;
		$this->CFG=unserialize(SYS_CONFIG);
		$this->skin=$this->CFG['skin'];		
		$this->template_dir=$this->CFG['template_dir'];
		$this->smarty = $smarty;
	    Utility::assign( array('url'=>$this->CFG['URL']));
		
	}

    /**
     * 帮助中心
     */
    public function index(){
        $this->smarty->display($this->skin.'/helpCenter.html');
    }
    
    public function problems(){
        $id = $_GET['id'];
        $this->smarty->display($this->skin.'/problems/'.$id.'.html');
    }
	
}
?>