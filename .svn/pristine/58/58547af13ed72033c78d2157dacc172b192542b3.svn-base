
// banner	
jQuery(".slideBox").slide({mainCell:".bd ul",effect:"fold",autoPlay:true,delayTime:1000});
// 最新活动向上滚动
jQuery(".txtMarquee-top").slide({mainCell:".bd ul",autoPlay:true,effect:"topMarquee",vis:1,interTime:60});
// 游戏滑动
jQuery(".game-picScroll").slide({titCell:".hd ul",mainCell:".bd ul",autoPage:true,effect:"left",autoPlay:true,vis:5,trigger:"click"});

// 左侧收展
$(".fixed-box3").click(function(){
	if($('.toggle-icon').hasClass('shou')){
		$(".fixed-fl").animate({left: '-176px'}, "slow"); 
		$(".toggle-icon").attr("src","images/icon/zhan-icon.png");
		$(".toggle-icon").addClass("zhan").removeClass("shou");
	}else{
		$(".fixed-fl").animate({left: '0px'}, "slow"); 
		$(".toggle-icon").attr("src","images/icon/shou-icon.png");
		$(".toggle-icon").addClass("shou").removeClass("zhan");
	}
})