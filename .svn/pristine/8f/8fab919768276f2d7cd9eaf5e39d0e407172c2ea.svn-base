<?php
header('Content-Type:text/html;charset=utf-8');
//session_start();
//设置时间偏移
date_default_timezone_set('PRC');
/* 取得当前站点所在的根目录 */
define('ROOT_PATH', str_replace('Include/Init.inc.php', '', str_replace('\\', '/', __FILE__)));
//配置文件
$SysConfig = require 'Config.inc.php';
define('SYS_CONFIG', serialize($SysConfig));
require_once ROOT_PATH . 'Common/Utility.class.php';
?>