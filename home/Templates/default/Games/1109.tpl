<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>779游戏大厅-游戏介绍</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/public.css">
    <link rel="stylesheet" href="css/game.css">

    <script src="js/jquery.min.js"></script>
    <script src="js/jquery.SuperSlide.2.1.1.js"></script>
    <script src="js/bootstrap.min.js"></script>

</head>
<body>
    {include file="public/header.tpl"}
    <div class="clear"></div>
    <!-- 主体部分 -->
    <main>
        <div class="container">
            <div class="game-title clearfix">
                <div class="game-icon fl">
                    <img src="images/games/1109.png" alt="">
                </div>
                <div class="fr">
                    <h3>飞禽走兽</h3>
                    <p>飞禽走兽是一款最新的网络休闲游戏，游戏以9种动物押分为蓝本，通过丰富的森林世界，为玩家带来一个不一样的森林冒险之旅，让玩家在休闲娱乐的同时展示自己无限魅力于网络，超越了传统街机的枯燥乏味。</p>
                </div>
            </div>
            <h4>游戏浏览</h4>
            <div class="game-scroll">
                <div class="hd">
                    <a class="next"><img src="images/icon/zuo-icon.png" alt=""></a>
                    <a class="prev"><img src="images/icon/you-icon.png" alt=""></a>
                </div>
                <div class="bd">
                    <ul class="gameList">
                        <li>
                            <img src="images/1109/screen.jpg" alt="">
                        </li>
                    </ul>
                </div>
            </div>
            <h4>游戏说明</h4>
            <div class="explain">
            <h5>【游戏规则】</h5>
                <p>　1．玩家进入游戏之后，可以开始选择下注金币的额度。</p>
                <p>　2．玩家会有25秒的进行各种动物的押分，每个动物有不同的奖励比例。</p>
                <p>　3．每种动物分别对应着飞禽和走兽俩种动物类型。玩家可以在飞禽和走兽上面押分，如果压中，则获得对应的游戏倍数奖励。</p>
                <p>　4．游戏除了提供玩家8种动物的押分之外，还提供了金鲨银鲨的押分。玩家可以通过对齐押分，获得更高倍数的收益哦。</p>
        </div>
    </main>
    {include file="public/footer.tpl"}
    <script>
        // 游戏滑动
        {literal}
            jQuery(".game-scroll").slide({titCell:".hd ul",mainCell:".bd ul",autoPage:true,effect:"left",autoPlay:true,vis:2,trigger:"click"});
        {/literal}
    </script>
</body>
</html>