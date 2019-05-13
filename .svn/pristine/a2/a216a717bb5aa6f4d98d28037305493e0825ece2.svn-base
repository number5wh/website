<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <title>游戏介绍</title>
    <script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="js/fun.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <link rel="stylesheet" type="text/css" href="css/style.css">

    <script type="text/javascript">
        {literal}
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
        {/literal}
    </script>

</head>
<body>
{include file="public/header.tpl"}
<div id="main">
    <div class="content">
        <div class="details h">
            {include file="public/leftMenu.tpl"}
            <iframe  width="70%" align="center" id="win" name="win" onload="Javascript:SetWinHeight(this)" frameborder="0" scrolling="no" style="border: 1px solid #00a0e9;" src="/?n=games&a=client_game_intro&id={$KindID}"></iframe>
            <div style="clear: both"></div>

        </div>
    </div>
</div>
{include file="public/footer.tpl"}
</body>
<script type="text/javascript">
{literal}
function reinitIframe(){
var iframe = document.getElementById("win");
try{
iframe.height =  iframe.contentWindow.document.documentElement.scrollHeight;
}catch (ex){}
}
window.setInterval("reinitIframe()", 200);
{/literal}
</script>
</html>