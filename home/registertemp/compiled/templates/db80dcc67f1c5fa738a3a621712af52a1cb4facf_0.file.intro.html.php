<?php
/* Smarty version 3.1.31, created on 2018-07-14 00:31:01
  from "C:\website\home\Templates\default\intro.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5b48d3c55342c1_69528706',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'db80dcc67f1c5fa738a3a621712af52a1cb4facf' => 
    array (
      0 => 'C:\\website\\home\\Templates\\default\\intro.html',
      1 => 1531499454,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:public/header.tpl' => 1,
    'file:public/footer.tpl' => 1,
  ),
),false)) {
function content_5b48d3c55342c1_69528706 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>779游戏大厅-公司介绍</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/public.css">
    <link rel="stylesheet" href="css/prevent.css">

    <?php echo '<script'; ?>
 src="js/jquery.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="js/jquery.SuperSlide.2.1.1.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="js/bootstrap.min.js"><?php echo '</script'; ?>
>

</head>
<body>
     <?php $_smarty_tpl->_subTemplateRender("file:public/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

    <div class="clear"></div>
    <!-- 主体部分 -->
    <main>
        <div class="container">
            <div class="banner-prevent">
                <img src="images/company_banner.png" alt="">
            </div>
            <div class="prevent">
                <h3>公司介绍</h3>
                <p>北京呼之欲出科技有限公司</p>
                <p> 北京呼之欲出科技有限公司是由一批具有国内著名网络公司服务背景的网络精英所组成，年轻、富有活力，注定一开始就是一家成熟的网络科技公司，是一家专业从事网络技术、计算机技术涉及信息技术领域内的技术开发、技术转让、技术咨询计算机服务等内容的网络科技公司。 我们全心致力于网络事业的发展。公司涉及网络营销和软硬件开发的全程服务。行业涉及网络游戏开发、手机APP开发、互联网信息技术等，具有丰富的设计经验。我们以奉献网络科技而缩短人类之间沟通的距离，提升政府、企业的形象，为客户创造价值为自身的社会使命，以提供各种上网解决方案为中心开展业务工作。</p>
                <p>我们从市场的角度和客户的需求出发，帮助企业极其产品树立良好的视觉形象，拓展市场空间创造竞争优势，提升企业的无形资产。</p>
                <p>我们亦明知时代赋予设计师的伟大抱负和责任，须以前瞻性的眼光和精致的作品引导社会，提升民众的审美品味和生活品质，这也是设计在社会进展过程中的根本功能所在。</p>
                <p>我们遵循大师的教诲，追寻前辈的足迹，努力涉取传统文化精髓，借鉴并融合诸多美学元素和艺术语言，运用现代的演绎方式，赋予作品时代的美感和深刻的内涵。</p>
                 <p>官方电话：13225917145 </p>
                <!-- <p>客服QQ：  ---</p> -->
                <!-- <p>官方邮箱： ---.com</p> -->
                <!-- <p>联系地址： ---</p>  -->
            </div>
        </div>
    </main>
    <?php $_smarty_tpl->_subTemplateRender("file:public/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

</body>
</html><?php }
}
