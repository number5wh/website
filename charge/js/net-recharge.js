$(function(){
	// 默认充钱为10
	var RMB = 10;
	$(".list-btn").click(function(){
		$(this).addClass("active").siblings().removeClass("active");
	})
	$(".a-num2").click(function(){
		$(".a-num2").addClass("active");
		$(".a-num1").removeClass("active");
		$("#LoginType").val("2");
		R.Counter()
		$(".form1 .zh").hide();
		$(".form1 .bh").show();
	})
	$(".a-num1").click(function(){
		$(".a-num1").addClass("active");
		$(".a-num2").removeClass("active");
		$("#LoginType").val("1");
		$(".form1 .bh").hide();
		$(".form1 .zh").show();
	})
	$(".a-num-type2").click(function(){
		$(this).addClass("active").siblings().removeClass("active");
		//$("#zmb").val("2");		
		//R.Counter()
		$(".type2").show();
		$(".type1").hide();
		RMB = 30;
		$("#Price").val(RMB);
		$("#payType").val(2);
		// console.log(RMB);
	})
	$(".a-num-type1").click(function(){
		$(this).addClass("active").siblings().removeClass("active");
		//$("#zmb").val("1");
		R.Counter()
		$(".type1").show();
		$(".type2").hide();
		RMB = $(".type1 .clearfix .active").attr("RMB");
		$("#Price").val(RMB);
		$("#payType").val(1);
		// console.log(RMB);
	})
	
	$(".clearfix .a-num").click(function(){
		$(this).addClass("active").siblings().removeClass("active");
		// 获取到金额
		RMB = $(this).attr("RMB");
		$("#Price").val(RMB);
		R.Counter()		
	})

})