$(function(){
	// 默认充钱为10
	var RMB = 10;
	$(".list-btn").click(function(){
		$(this).addClass("active").siblings().removeClass("active");
	})
	$(".a-num2").click(function(){
		$(".a-num2").addClass("active");
		$(".a-num1").removeClass("active");
		$(".form1 .zh").hide();
		$(".form1 .bh").show();
	})
	$(".a-num1").click(function(){
		$(".a-num1").addClass("active");
		$(".a-num2").removeClass("active");
		$(".form1 .bh").hide();
		$(".form1 .zh").show();
	})
	$(".a-num-type2").click(function(){
		$(this).addClass("active").siblings().removeClass("active");
		$(".type2").show();
		$(".type1").hide();
		RMB = 30;
		// console.log(RMB);
	})
	$(".a-num-type1").click(function(){
		$(this).addClass("active").siblings().removeClass("active");
		$(".type1").show();
		$(".type2").hide();
		RMB = $(".type1 .clearfix .active").attr("RMB");
		// console.log(RMB);
	})
	
	$(".clearfix .a-num").click(function(){
		$(this).addClass("active").siblings().removeClass("active");
		// 获取到金额
		RMB = $(this).attr("RMB");
		// console.log(RMB);
	})

})