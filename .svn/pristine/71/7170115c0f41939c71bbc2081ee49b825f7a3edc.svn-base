<?php
/* Smarty version 3.1.31, created on 2018-06-29 16:03:24
  from "C:\website\home\Templates\default\Games\intro.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5b35e7cc590ce6_94901171',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '182e01fc264dbd3f52a7e429e54463bfe5bd5474' => 
    array (
      0 => 'C:\\website\\home\\Templates\\default\\Games\\intro.tpl',
      1 => 1528184699,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:public/header.tpl' => 1,
    'file:public/leftMenu.tpl' => 1,
    'file:public/footer.tpl' => 1,
  ),
),false)) {
function content_5b35e7cc590ce6_94901171 (Smarty_Internal_Template $_smarty_tpl) {
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
<?php $_smarty_tpl->_subTemplateRender("file:public/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<div id="main">
    <div class="content">
        <div class="details h">
            <?php $_smarty_tpl->_subTemplateRender("file:public/leftMenu.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

            <iframe  width="70%" align="center" id="win" name="win" onload="Javascript:SetWinHeight(this)" frameborder="0" scrolling="no" style="border: 1px solid #00a0e9;" src="/?n=games&a=client_game_intro&id=<?php echo $_smarty_tpl->tpl_vars['KindID']->value;?>
"></iframe>
            <div style="clear: both"></div>

        </div>
    </div>
</div>
<?php $_smarty_tpl->_subTemplateRender("file:public/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

</body>
<?php echo '<script'; ?>
 type="text/javascript">

function reinitIframe(){
var iframe = document.getElementById("win");
try{
iframe.height =  iframe.contentWindow.document.documentElement.scrollHeight;
}catch (ex){}
}
window.setInterval("reinitIframe()", 200);

<?php echo '</script'; ?>
>
</html><?php }
}
