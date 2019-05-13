var evt={
	//绑定选项卡事件(父级层)
	BindTabClick:function(obj){
		var iCount = 0;
		window.parent.$('.tab ul li').each(function(){
			iCount++;
			var id=obj+iCount;
			$(this).attr('tabid',id);
			$(this).click(function(){
				$(this).parents('.tab').children('.RoomPro').hide();
				$(this).siblings('li').removeClass('curTab');
				$(this).addClass('curTab');
				window.parent.$('#'+$(this).attr('tabid')).show();
			});
		});
	},
	//多表查询分页事件
	BindSimplePage:function(obj,PageFunction,Param,Param1){
		$(obj+' #LinkPrev').click(function(){
			if(parseInt($(this).attr('cp'))>1)
				eval(PageFunction+'('+Param+')');
		});
		$(obj+' #LinkNext').click(function(){
			if(parseInt($(this).attr('cp'))<parseInt($(this).attr('tp')))
				eval(PageFunction+'('+Param+')');
		});
		$('.txtPage').blur(function(){
			eval(PageFunction+'('+Param1+')');
		});	
	},	
	//分页事件
	BindPageClick:function(obj,PageFunction,Param,Param1){
		$(obj+'#LinkFirst').click(function(){
			if(parseInt($(obj+'.txtPage').val())>1)
				eval(PageFunction+'('+Param+')');
		});
		$(obj+'#LinkPrev').click(function(){
			if(parseInt($(obj+'.txtPage').val())>1)
				eval(PageFunction+'('+Param+')');
		});
		$(obj+'#LinkNext').click(function(){
			if(parseInt($(obj+'.txtPage').val())<parseInt($(obj+'#TotalPage').html()))
				eval(PageFunction+'('+Param+')');
		});
		$(obj+'#LinkLast').click(function(){
			if(parseInt($(obj+'.txtPage').val())<parseInt($(obj+'#TotalPage').html()))
				eval(PageFunction+'('+Param+')');
		});
		$(obj+'.txtPage').blur(function(){
			eval(PageFunction+'('+Param1+')');
		});	
	},
	//绑定游戏节点事件
	BindNodeClick:function(obj){
		$('#GameNode '+obj).click(function(){//alert($('.subNode_'+setting.ObjID).html());		
			setting.ObjID = $(this).attr('id');
			if($('.subNode_'+setting.ObjID).html()!=null)
				$('.subNode_'+setting.ObjID).remove();
			else
				ajax.RequestUrl(setting.NodeUrl,'TypeID='+setting.ObjID,'callback.GetGameType');
		});	
	},
	//绑定游戏节点右键事件
	BindNodeRightButton:function(obj,menu){
		$(obj).contextMenu(menu, 
     	{
			bindings: 
			{
				'addSubNode': function(t) {
					setting.Params='Action=Add&ParentNode='+encodeURIComponent(setting.NodeName)+'&TypeID='+setting.ObjID;
					ajax.RequestUrl(setting.Url,setting.Params,'callback.ShowAddGameNode');
				},
				'copy': function(t){
					setting.Params='Action=Copy&ParentNode='+encodeURIComponent(setting.NodeName)+'&TypeID='+setting.ObjID;
				    ajax.RequestUrl(setting.CopyUrl,setting.Params,'callback.ShowAddGameNode');
				},
				'modi': function(t) {
					setting.Params='Action=Modi&ParentNode='+encodeURIComponent(setting.NodeName)+'&TypeID='+setting.ObjID;
					ajax.RequestUrl(setting.Url,setting.Params,'callback.ShowAddGameNode');
				},
				'del': function(t) {
					setting.Params='TypeID='+setting.ObjID;
					if(confirm('删除后将无法恢复,确定删除?'))
						ajax.RequestUrl(setting.DelUrl,setting.Params,'callback.DelGameNode');
				}
			}
		});	
	},
		//绑定游戏节点右键事件
	BindTabRightClick:function(obj,menu,id){
		$(obj).contextMenu(menu, 
     	{
			bindings: 
			{
				'close': function(t) {
					main.Close(id);
				},
				'closeAll': function(t) {
					$(".menuTab").each(function(index, el) {
						obj_id = $(this).attr("id");
						obj_id = obj_id.replace("Tab_","");
						main.Close(obj_id);
					});
				},
				'closeOthers': function(t) {
					$(".menuTab").each(function(index, el) {
						obj_id = $(this).attr("id");
						obj_id = obj_id.replace("Tab_","");
						if(obj_id != id){
							main.CloseOthers(obj_id);
						}
					});
				}
			}
		});	
	},
	//绑定客服中心->角色信息管理->角色详细信息->查看明细(开通明细)->明细(日工资明细)里的点击事件
	/*BindVipSararyDetailClick:function(){
		$('.SalaryDetail').each(function(){
			$(this).click(function(){
				re.ShowSalaryDetail();
			});	
		});	
	},*/
	//绑定产品运营->设置礼包->添加礼包->选择道具
	BindSpListClick:function(){
		$('#SpList div').each(function(){
			$(this).click(function(){
				gift.GetCurSp($(this).attr('TypeID'),$(this).attr('id'),$(this).html());
			});	
		});	
	}
	//绑定onchange事件
	/*BindChange:function(obj,CallbackFunction){
		$(obj).change(function(){			
			eval(CallbackFunction+'("'+$(this).val()+'")');
		});
	}*/
}




