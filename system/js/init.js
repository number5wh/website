var init={
	//绑定鼠标移到表格行或移开表格行显示的背景色
	SetTableRows:function(){
		$("tr").mouseover(function(){
			$(this).children().css('background-color','#DEF1FC');						   
		});
		$("tr").mouseout(function(){
			$(this).children().css('background-color','');						   
		});
	}
}




