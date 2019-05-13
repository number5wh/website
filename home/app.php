<?php

require 'Include/Init.inc.php';
require 'Include/Smarty.inc.php';
require_once ROOT_PATH . 'Common/Session.class.php';
require_once 'Action/Index.class.php';

$app = new IndexAction();
$app->app_load();
?>