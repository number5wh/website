<?php
//php缓存目录
if (!file_exists(ROOT_PATH.'registertemp/caches'))
{
    @mkdir(ROOT_PATH.'registertemp/caches', 0777);
    @chmod(ROOT_PATH.'registertemp/caches', 0777);
}
if (!file_exists(ROOT_PATH.'registertemp/compiled'))
{
    @mkdir(ROOT_PATH.'registertemp/compiled', 0777);
    @chmod(ROOT_PATH.'registertemp/compiled', 0777);
}
if (!file_exists(ROOT_PATH.'registertemp/compiled/templates'))
{
    @mkdir(ROOT_PATH.'registertemp/compiled/templates', 0777);
    @chmod(ROOT_PATH.'registertemp/compiled/templates', 0777);
}
//载入smarty模板引擎
require ROOT_PATH . 'Common/Smarty/SmartyBC.class.php';
$smarty = new SmartyBC;
$CFG=unserialize(SYS_CONFIG);
$smarty->template_dir   = ROOT_PATH . $CFG['template_dir'];
$smarty->cache_dir      = ROOT_PATH . 'registertemp/caches/';
$smarty->compile_dir    = ROOT_PATH . 'registertemp/compiled/templates/';
$smarty->plugins_dir    = ROOT_PATH . 'Common/Smarty/plugins/';
$smarty->compile_check  = true;//当模板修改时候，缓存文件将会重建
$smarty->debugging		= false;//弹出变量窗口
$smarty->caching        = false;//是否缓存
?>