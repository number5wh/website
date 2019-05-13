<?php
/* Smarty version 3.1.31, created on 2018-07-04 22:11:55
  from "C:\website\home\Templates\default\Games\1090.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5b3cd5ab22ce53_77036448',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3b3e94b3d3ebd56aadcb5117920f6c9a7ecd50fa' => 
    array (
      0 => 'C:\\website\\home\\Templates\\default\\Games\\1090.tpl',
      1 => 1530522304,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5b3cd5ab22ce53_77036448 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <title>游戏介绍</title>
    <?php echo '<script'; ?>
 type="text/javascript" src="js/jquery-1.11.2.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 type="text/javascript" src="js/fun.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 type="text/javascript" src="js/common.js"><?php echo '</script'; ?>
>
    <link rel="stylesheet" type="text/css" href="css/style.css">

    <?php echo '<script'; ?>
 type="text/javascript">
        
        window.console = window.console || (function(){ 
            var c = {};  
                c.log = c.warn = c.debug = c.info = c.error = c.time = c.dir = c.profile = c.clear = c.exception = c.trace = c.assert = function(){}; 
            return c; 
        })();
        function changeTab(id)
        {
            console.log(id);
            for (i=1;i<=4;i++)
            {
                if (id==i)
                {
                    $("#lab_"+i).attr("src","/images/"+i+"_2.gif");
                    $("#con_"+i).show();
                }
                else
                {
                    $("#lab_"+i).attr("src","/images/"+i+"_1.gif");
                    $("#con_"+i).hide();
                }
            }
        }
        
    <?php echo '</script'; ?>
>
</head>
<body>

            <div class="detailsRight">
                <div class="commonProblem new">
                    <div class="main">
                        <div class="mainframe">
                            <div class="cBody">
                                <div class="cLabel"> <img src="/images/1_2.gif" id="lab_1" onclick="changeTab(1)" /> <img src="/images/2_1.gif" id="lab_2" onclick="changeTab(2)" /> <img src="/images/3_1.gif" id="lab_3" onclick="changeTab(3)" /> <img src="/images/4_1.gif" id="lab_4" onclick="changeTab(4)" /> </div>
                                <div class="cContent" id="con_1">
                                    <!--游戏简介-->
                                    <div class="cCbody"> </div>
                                <div class="cContent" id="con_2" style="display: none;">
                                </div>
                                <div class="cContent" id="con_3" style="display: none;">
                                </div>
                                <div class="cContent" id="con_4" style="display: none;">
                                    <!--游戏限额-->
                                    <div class="cCtitle">游戏限额</div>
                                    <div class="cCPading">
                                        <table width="100%" cellspacing="0" cellpadding="0">
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</body>
</html><?php }
}
