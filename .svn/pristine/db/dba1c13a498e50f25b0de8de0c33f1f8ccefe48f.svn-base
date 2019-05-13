$(document).ready(function() {
	$('.label1').bind("click", function(){
   		var radioId = $(this).attr('name');
    	$('.label1').removeClass('check') && $(this).addClass('check');
    	$(".yxdk").removeAttr('checked') && $('#' + radioId).attr('checked', 'checked');

 	});

 	$('.label2').bind("click", function(){
   		var radioId = $(this).attr('name');
    	$('.label2').removeClass('check') && $(this).addClass('check');
    	$(".czk").removeAttr('checked') && $('#' + radioId).attr('checked', 'checked');
 	});

 	$('.label3').bind("click", function(){
   		var radioId = $(this).attr('name');
    	$('.label3').removeClass('check') && $(this).addClass('check');
    	$(".wyzf").removeAttr('checked') && $('#' + radioId).attr('checked', 'checked');
 	});

 	$('.label4').bind("click", function(){
   		var radioId = $(this).attr('name');
    	$('.label4').removeClass('check') && $(this).addClass('check');
    	$(".type").removeAttr('checked') && $('#' + radioId).attr('checked', 'checked');
 	});

 	$('.span1').bind("click", function(){
 		var checkboxId = $(this).attr('name');
    $('.span1').toggleClass('check');


    if($(".span1").hasClass('check')){
    		$('#' + checkboxId).attr('checked', 'checked');
        $('#chk_Agreement').attr('checked','checked');
		}else {
			$('#' + checkboxId).removeAttr('checked')

        $('#chk_Agreement').attr('checked',false);
		}
 	});

  $('.tgy1').bind("click", function(){
    var checkboxId = $(this).attr('name');
      $('.tgy1').toggleClass('check');
      if($(".tgy1").hasClass('check')){
        $('#' + checkboxId).attr('checked', 'checked');
        $(".tgyOne").addClass("tgyShow");
    }else {
      $('#' + checkboxId).removeAttr('checked')
      $(".tgyOne").removeClass("tgyShow");
    }
  });


  $('.tgy2').bind("click", function(){
    var checkboxId = $(this).attr('name');
      $('.tgy2').toggleClass('check');
      if($(".tgy2").hasClass('check')){
        $('#' + checkboxId).attr('checked', 'checked');
        $(".tgyTwo").addClass("tgyShow");
    }else {
      $('#' + checkboxId).removeAttr('checked')
      $(".tgyTwo").removeClass("tgyShow");
    }
  });


  $("#txt_SecutiryPhone").focus(function(){
    if($(this).val()=="请填写真实号码，用于登录验证和密码找回"){
      $(this).val("");
    }
  }).blur(function(){
    if($(this).val()==""){
      $(this).val("请填写真实号码，用于登录验证和密码找回");
    }
  });

  $("#txt_QQ").focus(function(){
    if($(this).val()=="请填写真实QQ号，没有QQ号可填写123456"){
      $(this).val("");
    }
  }).blur(function(){
    if($(this).val()==""){
      $(this).val("请填写真实QQ号，没有QQ号可填写123456");
    }
  });


  var allHeight = $(document.body).outerHeight(true);
  $(".login").height(allHeight);

  $(".userLogin").bind('click', function() {
      $(".login").addClass("loginShow");
      $(".loginMain").addClass("loginShow");
  });

  $(".login").bind('click', function() {
      $(".login").removeClass("loginShow");
      $(".loginMain").removeClass("loginShow");
  });

  $(".subMenu_Pic img").bind("mouseover",function(){
    $(".subMenu_Intro").fadeIn();
  });
  $(".subMenu_Intro").bind("mouseout",function(){
    $(".subMenu_Intro").fadeOut();
  });

  $(".subMenu_Pic2 img").bind("mouseover",function(){
    $(".subMenu_Intro2").fadeIn();
  });
  $(".subMenu_Intro2").bind("mouseout",function(){
    $(".subMenu_Intro2").fadeOut();
  });
});