var arrTab = new Array('desktop');
$(document).ready(function(){	
	//运维平台树结构
	$('#YunWei').treeview();
	//客服中心树结构
	$('#Service').treeview();
	//产品运营树结构
	$('#YunYing').treeview();
	//绑定左侧菜单事件
	init.BindClick('YunWei');
	init.BindClick('Service');
	init.BindClick('YunYing');
	//设置框架宽度和高度
	init.SetFrame('desktop');
});

var init={
	//设置框架宽度和高度
	SetFrame:function(id){
		var width = $(window).width();	
		if(width>1440)
			width = 1440;
		var height = $(window).height();
		$('#'+id).width(width-195);
		$('#'+id).height(height-75);
		$('.gFpage').height(height-75);
	},
	//绑定左侧菜单的click事件
	BindClick:function(id){
		$('#'+id+' a').each(function(){
			$(this).attr('id',$(this).attr('name'));
			$(this).click(function(){
				//var Path = id+'/';
				//var Name = $(this).attr("name");
				//var PageUrl = Path+Name+".class.php";
				
				var PageUrl = '/?d='+id+'&c='+$(this).attr('name');
				main.AddTab($(this).html(),$(this).attr('id'),PageUrl);				   
			});					   
		});	
	}
}

