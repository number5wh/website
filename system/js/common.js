var main={
	//框架主页,增加滑动门
	SetTabCurrent:function(obj){
		$(".tab li").removeClass('curTab');
		$(obj).addClass('curTab');
	},
	//框架主页,增加滑动门
	AddTab:function(title,iNum,PageUrl){
		var iFrameID = 'iframe_'+iNum;
		//整理数组,如果当前滑动门存在,则不新增,直接显示之前存在的滑动门
		iPos=main.ResetArray(iFrameID);
		if(iPos==false) return false;
		main.Reset();
		if(iPos>=0){
			main.ShowCurTab();
			return false;
		}		
		//新增滑动门
		$('.gTab ul').append('<li id="Tab_'+iFrameID+'" class="curTab menuTab" onclick="main.Show(\''+iFrameID+'\')" title="'+title+'" ondblclick="main.Close(\''+iFrameID+'\')"><span>'+title+'</span></li>');		
		$('.main').append('<iframe id="'+iFrameID+'" scrolling="auto" frameborder="0" style="width:auto;"src="'+PageUrl+'"></iframe>');
		evt.BindTabRightClick("#Tab_"+iFrameID+"  span","myMenuSubFolder",iFrameID);
		//初始化框架宽度和高度
		init.SetFrame(iFrameID);
	},
	//框架主页,显示当前滑动门
	Show:function(ID){
		//重置所有滑动门处于未选中状态
		main.Reset();
		//设置当前滑动门为选中状态
		$('#Tab_'+ID).addClass('curTab');
		$('#'+ID).show();
		//整理数组
		main.ResetArray(ID);
	},
	//框架主页,关闭当前滑动门
	Close:function(ID){
		$('#Tab_'+ID).remove();
		$('#'+ID).remove();
		//关闭当前滑动门,并从数组中清除
		arrTab.pop();
		//显示当前滑动门之前的一个滑动门的内容
		main.ShowCurTab();
	},
	CloseOthers:function(ID){
		$('#Tab_'+ID).remove();
		$('#'+ID).remove();
		var temp = arrTab.pop();
		var array = new Array(20);	
		while(temp!=ID)
			{
				array.push(temp);
				temp = arrTab.pop();
			}
	    while(array.length>0)
		    {
		   		temp = array.pop();
		   		if(temp != null)
		   			arrTab.push(temp);
		    }
	   main.ShowCurTab();
	},
	//框架主页,重置所有滑动门处于未选中状态
	Reset:function(){
		$('.gTab ul li').each(function(){
			$(this).removeClass('curTab');				
		});		
		$('.main iframe').each(function(){
			$(this).hide();				
		});
	},
	//框架主页,整理数组
	ResetArray:function(ID){
		//判断当前滑动门是否存在数组中,如果存在,则清除
		var iPos = $.inArray(ID,arrTab);
		if(iPos>=0)
			arrTab.splice(iPos,1);
		else{
			if(arrTab.length>=15){
				alert('最多只能同时打开15个选项卡,请双击关闭多余的选项卡');
				return false;
			}
		}
		//将当前滑动门对象ID放入数组
		arrTab.push(ID);
		return iPos;
	},
	//框架主页,显示当前滑动门之前显示的一个滑动门
	ShowCurTab:function(){
		if(arrTab.length>0){
			var NewID = arrTab[arrTab.length-1];
			$('#Tab_'+NewID).addClass('curTab');
			$('#'+NewID).show();
		}
	},
	//运维平台(MAP配置)
	AddRows:function(evt){
		if(evt!='' && $.trim($('#Hashlimit').val())==$('#Hashlimit').attr('curNum')) return false;
		var Row = $('.bdy table tr:eq(1)').html();
		if($('.NewRow').html()!=null) $('.NewRow').remove();
		for(var i=1;i<$('#Hashlimit').val();i++){
			$('.bdy table').append('<tr class="NewRow" id="R_'+i+'">'+Row+'</tr>');
		}		
	},
	//运维平台(游戏配置),重置添加游戏级别
	ResetGameKind:function(){
		$('#ID').val(0);
		$('#LevelType').val(0);
		$('#LevelID').val(0);
		$('#LevelName').val('');
		$('#LBound').val(0);
		$('#CellAmount').val(0);	
		$('#ClothesImage').val(0);	
	},	
	//客服平台(客服中心),角色详情=>修改操作,显示下拉菜单
	ShowDownMenu:function(){
		$('.downMenu').toggle();
	},
	//在父级页弹出窗口
	MsgBox:function(rst){
		window.parent.$('.gMain').append('<div id="MsgBoxID"></div>');
		window.parent.$('#MsgBoxID').wBox({drag:false,
					 html: rst
				    }).showBox();
	},
	//在当前页弹出窗口
	OpenBox:function(rst){
		$('#MsgBoxID').remove();
		$('body').append('<div id="MsgBoxID"></div>');
		$('#MsgBoxID').wBox({drag:false,
					 html: rst
				    }).showBox();
	},
	//关闭弹出窗口
	CloseMsgBox:function(Refresh,iFrameID){
		$('#wBox_overlay').remove();
		$('#MsgBoxID').remove();
		$('#wBox').remove();
		if(Refresh && iFrameID!=''){
			iFrameID = 'iframe_'+iFrameID;
			$('#'+iFrameID).attr('src',$('#'+iFrameID).attr('src'));
		}
	},
	CloseWin:function(id){
		$("#"+id).hide();
		$("#wBox_overlay").remove();
	},
	//初始化上传控件
	InitUploadControl:function(ID,UploadPath,UploadUrl){
        console.log(UploadUrl);
		$('#'+ID).uploadify({
			'formData':{
				'folder':UploadPath
			},
			'swf'      : '/images/common/uploadify.swf',
			'buttonImage':'/images/common/upload.gif',
			'width':'68',
			'height':'22',
			'queueID':'queue_'+ID,
			'queueSizeLimit':1,//只允许一次上传一张
			'uploader' : UploadUrl,			
			'fileTypeExts' : '*.jpg;*.png;*.gif', //控制可上传文件的扩展名，启用本项时需同时声明fileDesc
			'fileTypeDesc' : '上传图片', //出现在上传对话框中的文件类型描述
			//上传到服务器，服务器返回相应信息到data里   
			'onUploadSuccess':function(file, data, response){
				$('#ImgPath_'+ID).val(data);
			}
		});
	},
	//定时关闭弹窗
	CountDown:function(secs){ 
		if(--secs>0){
			setTimeout("main.CountDown("+secs+")",1000); 
		}else{
			main.CloseWin('myWindow');
		}   		
	}
};
var callback={
	//运维平台(服务器配置),显示添加游戏服务器弹出层
	ShowAddGameServerHtml:function(data){
		main.MsgBox(data);
	},
	//运维平台(服务器配置),添加游戏服务器
	AddGameServer:function(data){
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js	
		$('#AddServerMsg').html(data.Msg);
		if(data.iResult==0){
			if($('#ServerID').val()==0){
				$('#ServID').val(0);
				$('#ServerName').val('');
				$('#ServerIP').val('');
				$('#ServerPort').val('');
			}
			else{
				//alert($("#iframe_SpClass").contents().find("#Row_"+data.ClassInfo.ClassID).html());  
				var IframeID = $('li.curTab').attr('id').replace('Tab_','');
				$("#"+IframeID).contents().find('#Row_'+data.ServerInfo.ServID+' td:eq(1)').html(data.ServerInfo.ServerName);				
				$("#"+IframeID).contents().find('#Row_'+data.ServerInfo.ServID+' td:eq(2)').html(data.ServerInfo.ServerIP);
				$("#"+IframeID).contents().find('#Row_'+data.ServerInfo.ServID+' td:eq(3)').html(data.ServerInfo.ServerPort);
			}
		}	
	},
	//运维平台（服务器配置），添加游戏盾
	addGameDun:function(data){
		data=$.evalJSON(data);
		$('#AddGameDunMsg').html(data.Msg);
	},
	/*运维平台(服务器配置),删除游戏服务器
	DelGameServer:function(data){
		if(!isNaN(data)){
		   window.location.reload();
		}else{
		   main.MsgBox(data);
		}
	},*/
	//运维平台(游戏配置),服务器列表
	GetPagerServer:function(data){//alert(data);
		$('#ServerList').html(data);
		init.SetTableRows();//初始化鼠标移到表格行显示背景
		svr.BindEvent();//初始化绑定事件(GameRoomPage.html)
		//绑定分页
		var Param = '$(this).attr("pg"),"callback.GetPagerServer"';
		var Param1 = '$(this).val(),"callback.GetPagerServer"';
		evt.BindPageClick('','page.GetPagerServer',Param,Param1);
	},
	//运维平台（服务器配置），游戏盾列表
	GetPagerGameDun:function(data){
		$('#GameDunList').html(data);
		init.SetTableRows();
		svr.BindEvent();
		var Param = '$(this).attr("pg"),"callback.GetPagerGameDun"';
		var Param1 = '$(this).val(),"callback.GetPagerGameDun"';
		evt.BindPageClick('','page.GetPagerGameDun',Param,Param1);
	},
	//运维平台(游戏配置),游戏服务器列表
	GetPagerServerGame:function(data){//alert(data);
		$('#PageHtml').html(data);
		init.SetTableRows();//初始化鼠标移到表格行显示背景		
		svrGame.BindEvent();//初始化绑定事件(GameRoomPage.html)
		//绑定分页
		var Param = '$(this).attr("pg"),"callback.GetPagerServerGame"';
		var Param1 = '$(this).val(),"callback.GetPagerServerGame"';
		evt.BindPageClick('','page.GetPagerServerGame',Param,Param1);
	},
	//运维平台(机器人配置),机器人信息列表
	GetPagerRobotName:function(data){//alert(data);
		$('#PageHtml').html(data);
		init.SetTableRows();//初始化鼠标移到表格行显示背景		
		robotName.BindEvent();//初始化绑定事件(GameRoomPage.html)
		//绑定分页
		var Param = '$(this).attr("pg"),"callback.GetPagerRobotName"';
		var Param1 = '$(this).val(),"callback.GetPagerRobotName"';
		evt.BindPageClick('','page.GetPagerRobotName',Param,Param1);
	},
	//运维平台(机器人配置),机器人账号列表
	GetPagerRobotUser:function(data){//alert(data);
		$('#PageHtml').html(data);
		init.SetTableRows();//初始化鼠标移到表格行显示背景		
		robotUser.BindEvent();//初始化绑定事件(GameRoomPage.html)
		//绑定分页
		var Param = '$(this).attr("pg"),"callback.GetPagerRobotUser"';
		var Param1 = '$(this).val(),"callback.GetPagerRobotUser"';
		evt.BindPageClick('','page.GetPagerRobotUser',Param,Param1);
	},
	//运维平台(机器人配置),房间机器人列表
	GetPagerRoomRobot:function(data){//alert(data);
		$('#PageHtml').html(data);
		init.SetTableRows();//初始化鼠标移到表格行显示背景		
		roomRobot.BindEvent();//初始化绑定事件(GameRoomPage.html)
		//绑定分页
		var Param = '$(this).attr("pg"),"callback.GetPagerRoomRobot"';
		var Param1 = '$(this).val(),"callback.GetPagerRoomRobot"';
		evt.BindPageClick('','page.GetPagerRoomRobot',Param,Param1);
	},
    //运维平台(服务器配置),显示添加服务器HTML弹出层
    ShowAddServerHtml:function(data){
        main.MsgBox(data);
		window.parent.svrEdit.BindEvent($('#TabTag').val(),$('#ServID').val());
		var ServerIP = window.parent.$('#ServerIP').val();
		ServerIP = ServerIP.replace(/[,]+/g,'\r\n');
		window.parent.$('#ServerIP').val(ServerIP);
		if($('#ClassName').val()=='ServerDB') window.parent.$('#ServerIP').next().html('');
    },
    GetPagerWechatUser:function(data){//alert(data);
        $('#ServerList').html(data);
        init.SetTableRows();//初始化鼠标移到表格行显示背景
        vxuser.BindEvent();//初始化绑定事件(GameRoomPage.html)
        //绑定分页
        var Param = '$(this).attr("pg"),"callback.GetPagerWechatUser"';
        var Param1 = '$(this).val(),"callback.GetPagerWechatUser"';
        evt.BindPageClick('','page.GetPagerWechatUser',Param,Param1);
    },
    ShowAddOnlineDesHtml:function(data){
        main.MsgBox(data);
        window.parent.onlineEdit.BindEvent();
    },
    AddWeChatUser:function(data){
        switch(data){
            case '-1':
                $('#ResultMsg').html('操作失败');
                break;
            case '-9999':
                $('#ResultMsg').html('您提交的参数异常');
                break;
            default:
                $('#ResultMsg').html('操作成功');
                break;
        }
        $('#TypeID').val(1);
        $('#weixinname').val('');
        $('#noticetip').val('');
    },
	//运维平台（服务器配置），显示添加游戏盾HTML弹出层
	ShowAddGameDunHtml:function(data){
		main.MsgBox(data);
		window.parent.svrEdit.BindEvent();
	},
	//运维平台(机器人配置),显示添加机器人HTML弹出层
	ShowAddRobotNamePoolHtml:function(data){
		console.log(22345);
		main.MsgBox(data);
		window.parent.robotEdit.BindEvent($('#TabTag').val(),$('#NameID').val());
		if($('#ClassName').val()=='ServerDB') window.parent.$('#ServerIP').next().html('');
	},
	//运维平台(机器人配置),显示添加机器人账号HTML弹出层
	ShowAddRobotUserHtml:function(data){
		main.MsgBox(data);
		window.parent.robotEdit.BindEvent($('#TabTag').val(),$('#UserID').val());
	},
		//运维平台(机器人配置),显示批量删除机器人账号HTML弹出层
	ShowDelRobotUserHtml:function(data){
		main.MsgBox(data);
		window.parent.robotEdit.BindEvent($('#TabTag').val());
	},
	//运维平台(机器人配置),显示批量修改房间机器人账号HTML弹出层
	ShowEditRobotUserHtml:function(data){
		main.MsgBox(data);
		window.parent.robotEdit.BindEvent($('#TabTag').val(),$('#UserID').val());
	},
	//运维平台(机器人配置),显示添加房间机器人HTML弹出层
	ShowAddRoomRobotHtml:function(data){
		main.MsgBox(data);
		window.parent.robotEdit.BindEvent($('#TabTag').val(),$('#UserID').val());
	},
	//运维平台(服务器配置),添加服务器
	AddServer:function(data){	
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js	
		$('#AddServerMsg').html(data.Msg);
		if(data.iResult==0){
			if($('#ServerID').val()==0){
				$('#ServerID').val(0);
				$('#ServerName').val('');
				$('#ServerIP').val('');
				$('#LANServerIP').val('');
				$('#ServerPort').val('');
				$('#ProxyServerIP').val('');
				$('#ProxyServerPort').val('');
				$('#Intro').val('');
				$('#LoginName').val('');
				$('#LoginPwd').val('');
				$('#AppName').val('');	
				$('#ServPort').val(0);
			}
			else{
				//alert($("#iframe_SpClass").contents().find("#Row_"+data.ClassInfo.ClassID).html());  
				var ServerPort = '';
				var TypeName = '';
				var Target = '--';
				var IframeID = $('li.curTab').attr('id').replace('Tab_','');
				$("#"+IframeID).contents().find('#Row_'+data.ServerInfo.ServerID+' td:eq(1)').html(data.ServerInfo.ServerID);
				$("#"+IframeID).contents().find('#Row_'+data.ServerInfo.ServerID+' td:eq(2)').html(data.ServerInfo.ServerName);
				if($('#ClassName').val()=='ServerGame'||$('#ClassName').val()=='ServerRoom')
					$("#"+IframeID).contents().find('#Row_'+data.ServerInfo.ServerID+' td:eq(3)').html(data.ServerInfo.RoomCount);
				else
					$("#"+IframeID).contents().find('#Row_'+data.ServerInfo.ServerID+' td:eq(3)').html(data.ServerInfo.AppName);
				$("#"+IframeID).contents().find('#Row_'+data.ServerInfo.ServerID+' td:eq(4)').html(data.ServerInfo.ServerIP);
				if(data.ServerInfo.ServerPort!=false&&data.ServerInfo.LANServerIP!=false)
					ServerPort = ':'+data.ServerInfo.ServerPort;
				$("#"+IframeID).contents().find('#Row_'+data.ServerInfo.ServerID+' td:eq(5)').html(data.ServerInfo.LANServerIP+ServerPort);
				$("#"+IframeID).contents().find('#Row_'+data.ServerInfo.ServerID+' td:eq(6)').html(data.ServerInfo.Intro);
			}
		}		
	},
			//运维平台(服务器配置),批量修改房间服务器
	EditServer:function(data){	
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js	
		$('#AddServerMsg').html(data.Msg);
	},

    EditWechatUser:function(data){
        data=$.evalJSON(data);//字符串格式转为json对象,extend.js
        $('#AddServerMsg').html(data.Msg);
    },

	//运维平台(服务器配置),添加WEB服务器
	AddWebServer:function(data){	
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js	
		$('#AddServerMsg').html(data.Msg);
		if(data.iResult>0){
			if($('#ServerID').val()==0){
				$('#ServerID').val(0);
				$('#ServerName').val('');
				$('#ServerIP').val('');				
				$('#Intro').val('');			
			}
			else{
				var IframeID = $('li.curTab').attr('id').replace('Tab_','');
				$("#"+IframeID).contents().find('#Row_'+data.ServerInfo.ServerID+' td:eq(1)').html(data.ServerInfo.ServerName);
				$("#"+IframeID).contents().find('#Row_'+data.ServerInfo.ServerID+' td:eq(2)').html(data.ServerInfo.ServerType);				
				$("#"+IframeID).contents().find('#Row_'+data.ServerInfo.ServerID+' td:eq(3)').html(data.ServerInfo.ServerIP);
				$("#"+IframeID).contents().find('#Row_'+data.ServerInfo.ServerID+' td:eq(4)').html(data.ServerInfo.Intro);
				$("#"+IframeID).contents().find('#Row_'+data.ServerInfo.ServerID+' a:eq(1)').attr('type',data.ServerInfo.ServerTypeID);
			}
		}		
	},
    //运维平台(服务器配置),删除微信用户
    DelWeChatUser:function(data){
        data=$.evalJSON(data);//字符串格式转为json对象,extend.js
        if(data.iResult==0){
            //重载当前页
            var CurPage = $('.txtPage').val();
            page.GetPagerServer(CurPage,'callback.GetPagerServer');
        }else{
            main.MsgBox(data.Msg);
        }
    },
    TopWeChatUser:function(data){
        data=$.evalJSON(data);//字符串格式转为json对象,extend.js
        if(data.iResult==0){
            //重载当前页
           // var CurPage = $('.txtPage').val();
            window.location.reload();
           // page.GetPagerServer(CurPage,'callback.GetPagerServer');
        }else{
            main.MsgBox(data.Msg);
        }
    },
	//运维平台(服务器配置),删除服务器
	DelServer:function(data){
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js	
		if(data.iResult==0){
			//重载当前页
			var CurPage = $('.txtPage').val();
			page.GetPagerServer(CurPage,'callback.GetPagerServer');
		}else{
		   main.MsgBox(data.Msg);
		}
	},
	//运维平台（服务器配置），删除游戏盾
	DelGameDun:function(data){
		data=$.evalJSON(data);
		if(data.iResult==0){
			var CurPage = $('.txtPage').val();
			page.GetPagerGameDun(CurPage,'callback.GetPagerGameDun');
		}else{
			main.MsgBox(data.Msg);
		}
	},
    //删除记录
	DelPagerLogs:function(data)
	{
		    data=$.evalJSON(data);
			if(data.iResult==0)
				{
				 var EndTime = $('#EndTime').val();
				 var RoleID = $('#RoleID').val();
				 setting.Params = '&EndTime='+EndTime+'&RoleID='+RoleID;
				 setting.PageUrl ="/?d=YunYing&c=LoginWarnLogs&a=getPagerLogsList";
				 page.GetPage($('.txtPage').val(),'Sys.CallBackGetPagerLogs');
				}
			else
				alert('删除失败');
	},
	//运维平台(服务器配置),设置服务器禁用/启用状态
	SetServerLocked:function(data){
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js	
		if(data.iResult==0){
			var Locked = $('#Row_'+data.ServerID+' a:eq(0)').attr('locked');
			Locked = Math.abs(1-Locked);
			var HtmlLocked = '';
			if(Locked==1)
				HtmlLocked = '<span class="orange">启用服务</span>';
			else
				HtmlLocked = '禁用服务';
		   $('#Row_'+data.ServerID+' a:eq(0)').html(HtmlLocked);
		   $('#Row_'+data.ServerID+' a:eq(0)').attr('locked',Locked);
		}else{
		   main.MsgBox(data.Msg);
		}	
	},
	//运维平台(服务器配置),生成配置文件
	CreateFiles:function(data){
		main.MsgBox(data);
	},
	//运维平台(MAP配置),显示设置MAP弹出层
	ShowAddMapHtml:function(data){
		main.MsgBox(data);
	},	

	//运维平台(奖金池记录查询),显示设置彩蛋税率弹出层
	showEditRoomLuckyEggMoneyHtml:function(data){
		main.MsgBox(data);
	},	
	//运维平台(MAP配置),读取服务器列表
	/*GetServerList:function(data){
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js
		$('#'+data.RowID+' #ServerID').html(data.Option);
	},*/
	//运维平台(MAP配置),设置MAP表
	AddMap:function(data){	
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js
		if(data.iResult==0){			
		    if($('.NewRow').html()!=null) $('.NewRow').remove();
			$('#Name').val('');
			$('#Hashlimit').val(1);
			$('#R_0 #TypeName').val('');
		}
		$('#ResultMsg').html(data.msg);
		
	},
	//运维平台(奖金池记录查询),设置彩蛋税率
	editRoomLuckyEggMoney:function(data){	
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js
		$('#ResultMsg').html(data.msg);
		
	},
	//运维平台(游戏配置),显示添加游戏种类弹出层
	ShowAddGameKindHtml:function(data){
		main.MsgBox(data);
		window.parent.gke.BindAddGameKindClick();
	},
	//运维平台(游戏配置),添加游戏种类
	AddGameKind:function(data){
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js
		if(data.result==0){
			$('#KindName').val('');
			$('#KindID').val('');
			$('#ProcessName').val('');
			$('#ServerDLL').val('');
			$('#SortID').val('');
			$('#CustomField').val('');	
		}
		$('#AddGameKindMsg').html(data.msg);
		$('#KindName').next().html('*');
		$('#KindID').next().html('*');
		$('#ProcessName').next().html('*');
		$('#ServerDLL').next().html('*');
		$('#SortID').next().html('*');
				
	},
	//运维平台(游戏配置),删除游戏种类
	DelGameKind:function(data){
		if(!isNaN(data)){
		   window.location.reload();
		}else{
		   main.MsgBox(data);
		}		
	},
	//运维平台(游戏配置),设置游戏种类禁用/启用状态
	SetGameKindLocked:function(data){
		if(!isNaN(data)){
		   window.location.reload();
		}else{
		   main.MsgBox(data);
		}	
	},
	//运维平台(游戏配置),显示设置游戏级别弹出层
	ShowSetGameKindLevelHtml:function(data){
		main.MsgBox(data);
	},
	//运维平台(游戏配置),添加游戏级别
	AddGameLevel:function(data){
		$('#ResultMsg').html(data);
		$('#LevelName').val('');
		$('#LBound').val(0);
		$('#CellAmount').val(0);
	},
	//运维平台(游戏配置),删除游戏级别
	DelGameLevel:function(data){
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js
		if(data.iResult==-1)
			$('#ResultMsg').html('删除失败,请重试');
		else if(data.iResult==-9999)
			$('#ResultMsg').html('您提交的数据异常,请重试');
		else if(data.iResult==0){
			$('#ResultMsg').html('删除成功');
			var Callback = data.LevelType==1 ? 'callback.getPagerLevelScore' : 'callback.getPagerLevelHappyBean';
			page.GetGameKindLevel(data.LevelType,data.curPage,Callback);
		}		
	},
	//运维平台(账号权限管理),权限配置
	setRoleRight:function(data){
		if(data==0)
			$('#ResultMsg').html('配置成功');
		else {
			$('#ResultMsg').html('配置失败');
		}	
	},
	//运维平台(游戏配置),游戏积分级别分页显示
	getPagerLevelScore:function(data){
		$('#LevelScoreList').html(data);
		/*var Callback = 'callback.getPagerLevelScore';
		$('#LevelScoreList #LinkFirst').click(function(){
			page.GetGameKindLevel(1,1,Callback);
		});
		$('#LevelScoreList #LinkPrev').click(function(){
			page.GetGameKindLevel(1,$(this).attr('pg'),Callback);
		});
		$('#LevelScoreList #LinkNext').click(function(){
			page.GetGameKindLevel(1,$(this).attr('pg'),Callback);
		});
		$('#LevelScoreList #LinkLast').click(function(){
			page.GetGameKindLevel(1,$(this).attr('pg'),Callback);
		});
		$('#LevelScoreList .txtPage').blur(function(){
			page.GetGameKindLevel(1,$(this).val(),Callback);
		});*/
		//绑定分页
		var Param = '1,$(this).attr("pg"),"callback.getPagerLevelScore"';
		var Param1 = '1,$(this).val(),"callback.getPagerLevelScore"';
		evt.BindPageClick('#LevelScoreList ','page.GetGameKindLevel',Param,Param1);
		//绑定修改事件
		$('#LevelScoreList .GameLevelModi').each(function(){
			$(this).click(function(){
				$('#ID').val($(this).attr('levelid'));
				$('#LevelType').val($(this).attr('leveltype'));
				$('#LevelID').val($('#Level_'+$(this).attr('levelid') + ' td:eq(0)').html());
				$('#LevelName').val($('#Level_'+$(this).attr('levelid') + ' td:eq(1)').html());
				$('#LBound').val($('#Level_'+$(this).attr('levelid') + ' td:eq(2)').html());
				$('#CellAmount').val($('#Level_'+$(this).attr('levelid') + ' td:eq(3)').html());	
				$('#ClothesImage').val($('#Level_'+$(this).attr('levelid') + ' td:eq(4)').html());			
			});
		});
		//绑定删除事件
		$('#LevelScoreList .GameLevelDel').each(function(){
			$(this).click(function(){				
				setting.Params='ID='+$(this).attr('levelid')+'&curPage='+$('#LevelScoreList .txtPage').val()+'&LevelType='+$(this).attr('leveltype');
				if(confirm('删除后将无法恢复,确定删除?'))
					ajax.Request(setting.DelUrl,setting.Params,'callback.DelGameLevel');
			});	
		});
	},
	//运维平台(游戏配置),游戏金币级别分页显示
	getPagerLevelHappyBean:function(data){
		$('#LevelHappyBeanList').html(data);
		/*var Callback = 'callback.getPagerLevelHappyBean';
		$('#LevelHappyBeanList #LinkFirst').click(function(){
			page.GetGameKindLevel(2,1,Callback);
		});
		$('#LevelHappyBeanList #LinkPrev').click(function(){
			page.GetGameKindLevel(2,$(this).attr('pg'),Callback);
		});
		$('#LevelHappyBeanList #LinkNext').click(function(){
			page.GetGameKindLevel(2,$(this).attr('pg'),Callback);
		});
		$('#LevelHappyBeanList #LinkLast').click(function(){
			page.GetGameKindLevel(2,$(this).attr('pg'),Callback);
		});
		$('#LevelHappyBeanList .txtPage').blur(function(){
			page.GetGameKindLevel(2,$(this).val(),Callback);
		});
		*/
		//绑定分页
		var Param = '2,$(this).attr("pg"),"callback.getPagerLevelHappyBean"';
		var Param1 = '2,$(this).val(),"callback.getPagerLevelHappyBean"';
		evt.BindPageClick('#LevelHappyBeanList ','page.GetGameKindLevel',Param,Param1);
		
		//绑定修改事件
		$('#LevelHappyBeanList .GameLevelModi').each(function(){
			$(this).click(function(){
				$('#ID').val($(this).attr('levelid'));
				$('#LevelType').val($(this).attr('leveltype'));
				$('#LevelID').val($('#Level_'+$(this).attr('levelid') + ' td:eq(0)').html());
				$('#LevelName').val($('#Level_'+$(this).attr('levelid') + ' td:eq(1)').html());
				$('#LBound').val($('#Level_'+$(this).attr('levelid') + ' td:eq(2)').html());
				$('#CellAmount').val($('#Level_'+$(this).attr('levelid') + ' td:eq(3)').html());	
				$('#ClothesImage').val($('#Level_'+$(this).attr('levelid') + ' td:eq(4)').html());					
			});
		});
		//绑定删除事件
		$('#LevelHappyBeanList .GameLevelDel').each(function(){
			$(this).click(function(){				
				setting.Params='ID='+$(this).attr('levelid')+'&curPage='+$('#LevelHappyBeanList .txtPage').val()+'&LevelType='+$(this).attr('leveltype');
				if(confirm('删除后将无法恢复,确定删除?'))
					ajax.Request(setting.DelUrl,setting.Params,'callback.DelGameLevel');
			});	
		});
	},	
	//运维平台(游戏配置),显示设置游戏版本界面
	ShowAddGameVersionHtml:function(data){
		main.MsgBox(data);
	},
	UnLockUserBank:function(data){
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js
		if(data['Result'] == 0)
			alert('操作成功！');
		else
			alert('操作失败！');
	},

	SetBankWeChatCheck:function(data){
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js
		if(data['Result'] == 0)
			alert('操作成功！');
		else
			alert('操作失败！');
	},
	//运维平台(游戏配置),添加版本
	AddGameVersion:function(data){
		$('#ResultMsg').html(data);
	},
	//运维平台(游戏配置),删除版本
	DelGameVersion:function(data){
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js	
		if(data.iResult==0){
			$('#Row_'+data.VerID).remove();
			$('#ResultMsg').html('游戏版本删除成功');
		}
		else
			$('#ResultMsg').html('游戏版本删除失败');
	},
	//运维平台(游戏配置),显示添加房间弹出层
	ShowAddGameRoomHtml:function(data){
		main.MsgBox(data);
		evt.BindTabClick('Room_');		
		window.parent.$('#btnAddGameRoom').click(function(){
			window.parent.room.AddGameRoom();
		});
	},
	//运营平台(游戏配置),道具房间道具分类
	GetRoomSPClass:function(data){
		$('#SubClassID').html(data);
		gift.GetSpPublicList();
	},
	//运维平台(游戏配置),添加房间,删除掉落道具
	DelPresentRoomSp:function(data){
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js	
		if(data.iResult==0)
			$('#Sp_'+data.SpID).remove();
		else
			$('#ResultMsg').html('道具移除失败');
	},
	//运维平台(游戏配置),添加房间,读取游戏种类
	GetGameKind:function(data){
		$('#KindID').html(data);
		room.SetRoomProperty('KindID',$('#KindID').val());
	},
	//运维平台(游戏配置),添加房间,游戏自定义字段
	GetGameKindInfo:function(data){
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js	
		if(data.Option!=''){
			$('#RoomType').html(data.Option);
			room.SetRoomRule('RoomType',$('#RoomType').val());
		}
		if(data.CustomField!=''){
			$('#CustomFieldTitle').html(data.CustomField+':');
			$('.CustomField').show();
		}
		else{
			$('#CustomFieldTitle').html('&nbsp;');
			$('.CustomField').hide();
		}			
	},
	//运维平台(游戏配置),添加房间
	AddGameRoom:function(data){
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js
		$('#ResultMsg').html(data);
	},
	//运维平台(游戏配置),删除房间
	DelGameRoomInfo:function(data){
		main.MsgBox(data);
	},
	//运维平台(游戏配置),房间列表
	GetPagerRoom:function(data){//alert(data);
		$('#GameRoomList').html(data);
		init.SetTableRows();//初始化鼠标移到表格行显示背景		
		//rm.BindEvent();//初始化绑定事件(GameRoomPage.html)
		//绑定分页
		var Param = '$(this).attr("pg"),"callback.GetPagerRoom"';
		var Param1 = '$(this).val(),"callback.GetPagerRoom"';
		evt.BindPageClick('','page.GetPagerRoom',Param,Param1);
		//rm.BindEvent();
	},
    //运维平台(游戏配置),房间列表
    GetOnline:function(data){//alert(data);
        $('#PageList').html(data);
        init.SetTableRows();//初始化鼠标移到表格行显示背景
        //rm.BindEvent();//初始化绑定事件(GameRoomPage.html)
        //绑定分页
        var Param = '$(this).attr("pg"),"callback.GetOnline"';
        var Param1 = '$(this).val(),"callback.GetOnline"';
        evt.BindPageClick('','page.GetPage',Param,Param1);
        //rm.BindEvent();
    },
    //运维平台(游戏配置),房间列表
    GetPayRelation:function(data){//alert(data);
        $('#PageList').html(data);
        init.SetTableRows();//初始化鼠标移到表格行显示背景
        //rm.BindEvent();//初始化绑定事件(GameRoomPage.html)
        //绑定分页
        var Param = '$(this).attr("pg"),"callback.GetPayRelation"';
        var Param1 = '$(this).val(),"callback.GetPayRelation"';
        evt.BindPageClick('','page.GetPage',Param,Param1);
        rm.BindEvent();
    },
	//运维平台(游戏配置),显示游戏节点列表
	GetGameType:function(data){
		$('#'+setting.ObjID).after(data);		
		evt.BindNodeClick('.spanNode_'+setting.ObjID);
		evt.BindNodeRightButton('#GameNode span.folder','myMenuSubFolder');
		evt.BindNodeRightButton('#GameNode span.file','myMenuSubFolder');
	},
	//运维平台(游戏配置),显示添加游戏节点界面
	ShowAddGameNode:function(data){
		main.MsgBox(data);
	},	
	//运维平台(游戏配置),添加节点
	AddGameNode:function(data){
		$('#ResultMsg').html(data);
	},
	//运维平台(游戏配置),删除节点
	DelGameNode:function(data){
		if(!isNaN(data)){
			$('#'+setting.ObjID).parent().remove();
		}else{
		   main.MsgBox(data);
		}	
	},
	//运维平台(游戏配置),添加节点,选择游戏种类
	GetGameKindList:function(data){
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js	
		if(data.KindList!='')
			$('#KindID').html(data.KindList);
		if(data.RoomList!='')
			this.GetRoomList(data.RoomList);
			//$('#RoomList').html(data.RoomList);
	},
	//运维平台(游戏配置),添加节点,游戏房间
	GetRoomList:function(data){
		$('#RoomList').html(data);
	},
	//运维平台(游戏配置),大厅版本列表
	GetPagerGameHall:function(data){//alert(data);
		$('#GameHallList').html(data);
		init.SetTableRows();//初始化鼠标移到表格行显示背景		
		hall.BindEvent();//初始化绑定事件(GameRoomPage.html)
		//绑定分页
		var Param = '$(this).attr("pg"),"callback.GetPagerGameHall"';
		var Param1 = '$(this).val(),"callback.GetPagerGameHall"';
		evt.BindPageClick('','page.GetPagerGameHall',Param,Param1);
	},
    //运维平台(游戏配置),安卓版本
    GetPagerAndroidHall:function(data){//alert(data);
        $('#GameHallList').html(data);
        init.SetTableRows();//初始化鼠标移到表格行显示背景
        hall.BindEvent();//初始化绑定事件(GameRoomPage.html)
        //绑定分页
        var Param = '$(this).attr("pg"),"callback.GetPagerAndroidHall"';
        var Param1 = '$(this).val(),"callback.GetPagerAndroidHall"';
        evt.BindPageClick('','page.GetPagerAndroidHall',Param,Param1);
    },
	//运维平台(游戏配置),显示添加大厅版本页面
	ShowAddGameHallVersioniHtml:function(data){
		 main.MsgBox(data);
	},
    //运维平台(游戏配置),显示添加安卓版本页面
    ShowAddAndroidHallVersioniHtml:function(data){
        main.MsgBox(data);
    },
    //运维平台(游戏配置),显示添加安卓差量文件页面
    ShowAddAndroidVersioniHtml:function(data){
        main.MsgBox(data);
    },
    //运维平台(道具配置),显示添加道具分类页面
	ShowAddSpClassHtml:function(data){
		main.MsgBox(data);
	},
	//运维平台(道具配置),显示道具一级分类
	GetParentClassList:function(data){
		$('#ParentClassID').html(data);
	},	
	//运维平台(道具配置),道具类别分页
	GetPagerSpClass:function(data){//alert(data);
		$('#SpClassList').html(data);
		init.SetTableRows();//初始化鼠标移到表格行显示背景		
		spc.BindEvent();
		//绑定分页
		var Param = '$(this).attr("pg"),"callback.GetPagerSpClass"';
		var Param1 = '$(this).val(),"callback.GetPagerSpClass"';
		evt.BindPageClick('','page.GetPagerSpClass',Param,Param1);
	},
	//运维平台(道具配置),道具子类
	GetSubClassList:function(data){
		if(data.RowHtml!=''){
			$('#Row_'+data.ClassID).after(data.RowHtml);
			spc_sub.BindEvent();
			init.SetTableRows();//初始化鼠标移到表格行显示背景		
			$('#Row_'+data.ClassID+' .icongif').addClass('SpClassYesTop');
			$('#Row_'+data.ClassID+' .icongif').removeClass('SpClassYes');
			$('#Row_'+data.ClassID+' .icongif').unbind();
			$('#Row_'+data.ClassID+' .icongif').click(function(){
				$('.SubClass_'+data.ClassID).remove();		
				$('#Row_'+data.ClassID+' .icongif').addClass('SpClassYes');
				$('#Row_'+data.ClassID+' .icongif').removeClass('SpClassYesTop');
				$('#Row_'+data.ClassID+' .icongif').click(function(){
					spc.GetSubClassList(data.ClassID);
				});
			})
		}
	},
	//运维平台(道具配置),设置道具分类禁用/启用状态
	SetSpClassLocked:function(data){
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js	
		if(data.iResult>0){
			var Locked = $('#Row_'+data.ClassID+' a:eq(0)').attr('locked');
			Locked = Math.abs(1-Locked);
			var HtmlLocked = '';
			if(Locked==1)
				HtmlLocked = '<span class="orange">启用</span>';
			else
				HtmlLocked = '禁用';
		   $('#Row_'+data.ClassID+' a:eq(0)').html(HtmlLocked);
		   $('#Row_'+data.ClassID+' a:eq(0)').attr('locked',Locked);
		}else{
		   main.MsgBox(data.Msg);
		}	
	},
	//运维平台(道具配置),添加道具分类
	AddSpClass:function(data){
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js	
		$('#ResultMsg').html(data.Msg);
		if(data.iResult==0){
			if($('#ClassID').val()==0){
				$('#CateName').val('');
				$('#KeyID').val('');
				$('#Target').val(0);
			}
			else{
				//alert($("#iframe_SpClass").contents().find("#Row_"+data.ClassInfo.ClassID).html());   
				var TypeName = '';
				var Target = '--';
				var IframeID = $('li.curTab').attr('id').replace('Tab_','');
				$("#"+IframeID).contents().find('#Row_'+data.ClassInfo.ClassID+' td:eq(1)').html(data.ClassInfo.CateName);
				if(data.ClassInfo.TypeID==1)
					TypeName = '服装';
				else if(data.ClassInfo.TypeID==2)
					TypeName = '道具';
				else if(data.ClassInfo.TypeID==3)
					TypeName = '礼包';
				else
					TypeName = '事件';		
				if(data.ClassInfo.Target==1)
					Target = '有';
				$("#"+IframeID).contents().find('#Row_'+data.ClassInfo.ClassID+' td:eq(2)').html(TypeName);
				$("#"+IframeID).contents().find('#Row_'+data.ClassInfo.ClassID+' td:eq(3)').html(Target);
				$("#"+IframeID).contents().find('#Row_'+data.ClassInfo.ClassID+' td:eq(4)').html(data.ClassInfo.KeyID);
			}
		}		
	},
	//运维平台(道具配置),删除道具分类
	DelSpClass:function(data){
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js	
		if(data.iResult==0){
			if(data.ParentID==0){
				//重载当前页
				var CurPage = $('.txtPage').val();
				page.GetPagerSpClass(CurPage,'callback.GetPagerSpClass');
			}
			else
				$('#Row_'+data.ClassID).remove();
		}else{
		   main.MsgBox(data);
		}
	},
	//运维平台(道具配置),道具分页
	GetPagerSp:function(data){//alert(data);
		$('#SpList').html(data);
		init.SetTableRows();//初始化鼠标移到表格行显示背景		
		sp.BindEvent();//初始化绑定事件(GameRoomPage.html)
		//绑定分页
		var Param = '$(this).attr("pg"),"callback.GetPagerSp"';
		var Param1 = '$(this).val(),"callback.GetPagerSp"';
		evt.BindPageClick('','page.GetPagerSp',Param,Param1);
	},
	//运维平台(道具配置),事件分页
	GetPagerEvent:function(data){//alert(data);
		$('#EventList').html(data);
		init.SetTableRows();//初始化鼠标移到表格行显示背景		
		e.BindEvent();//初始化绑定事件(GameRoomPage.html)
		//绑定分页
		var Param = '$(this).attr("pg"),"callback.GetPagerEvent"';
		var Param1 = '$(this).val(),"callback.GetPagerEvent"';
		evt.BindPageClick('','page.GetPagerEvent',Param,Param1);
	},
	//运维平台(道具配置),显示添加事件表单
	ShowAddEventHtml:function(data){
		main.MsgBox(data);
		window.parent.e.BindEvent();
	},
	//运维平台(道具配置),读取子类
	GetSubClass:function(data){
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js
		$('#SubClassID').html(data.SubOption);
		if(data.SubClassID>0)			
			$('#SubClassID').show();
		else
			$('#SubClassID').hide();
	},
	//运维平台(道具配置),显示添加事件属性表单
	ShowAddEventDetailHtml:function(data){
		main.MsgBox(data);
		window.parent.ed.InitData();
		window.parent.ed.BindEvent();
	},
	//运维平台(道具配置),添加事件属性
	AddEventDetail:function(data){
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js
		$('#ResultMsg').html(data.Msg);
		if(data.iResult==0){
			var Row='<tr id="Row_'+data.iID+'"><td align="center" bgcolor="#FFFFFF">'+data.EvtID+'</td>';
			Row+='<td align="center" bgcolor="#FFFFFF">'+$('#ClassID').find("option:selected").text()+'</td>';
			Row+='<td align="center" bgcolor="#FFFFFF">'+$('#iNumber').val()+'</td><td align="center" bgcolor="#FFFFFF">'+$('#Probability').val()+'</td>';
			Row+='<td align="center" bgcolor="#FFFFFF"><a href="javascript:void(0)" class="del" id="'+data.iID+'" onclick="ed.DelEventDetail('+data.iID+')">删除</a>';
			Row+='</td></tr>';
			$('#list').append(Row);
			$('#list').show();
		}		
	},
	//运维平台(道具配置),删除事件属性
	DelEventDetail:function(data){
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js
		switch(data.iResult){
			case -1://失败
				$('#ResultMsg').html('事件属性删除失败');	
				break;			
			case -9999://参数异常
				$('#ResultMsg').html('删除失败,接收的参数异常');	
				break;
			default://成功
				$('#Row_'+data.ID).remove();	
				break;
		}
	},
	//运维平台(道具配置),添加事件属性,目录对象
	GetObjList:function(data){
		$('#BigClassID').remove();
		$('#SubClassID').remove();
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js
		$('#ObjID').html(data.ObjList);
		if(data.SubClass!='')
			$('#Row_EvtObj').prepend(data.SubClass);
		if(data.BigClass!='')
			$('#Row_EvtObj').prepend(data.BigClass);
	},
	//运维平台(道具配置),添加事件属性,读取子类
	GetBigClass:function(data){
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js
		$('#SubClassID').html(data.SubClass);
		$('#ObjID').html(data.ObjList);
	},
	//运维平台(道具配置),添加事件属性,读取子类下的道具
	GetSpList:function(data){
		$('#ObjID').html(data);
	},
	//运维平台(道具配置),添加事件
	AddEvent:function(data){
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js
		$('#ResultMsg').html(data.Msg);		
	},
	//运维平台(道具配置),禁用/启用事件
	SetEventLocked:function(data){
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js
		if(data.iResult==0){
			if(data.iLocked==0)
				$('#Row_'+data.EvtID+' a:eq(1)').html('下线');
			else if(data.iLocked==1)
				$('#Row_'+data.EvtID+' a:eq(1)').html('<font class="orange">发布</font>');
		}else{
		     main.MsgBox(data.Msg);
		}	
	},
	//运维平台(道具配置),删除事件
	DelEvent:function(data){
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js
		if(data.iResult==0){
			window.location.reload();
		}else{
		   main.MsgBox(data.Msg);
		}	
	},
	//运维平台(道具配置),设置公库道具禁用/启用状态
	SetSpPublicLocked:function(data){
		if(!isNaN(data)){
		   window.location.reload();
		}else{
		   main.MsgBox(data);
		}	
	},
	//运维平台(道具配置),删除道具
	DelSpPublic:function(data){
		if(!isNaN(data)){
		   window.location.reload();
		}else{
		   main.MsgBox(data);
		}
	},
	//运维平台(道具配置),道具分类
	GetSpClass:function(data){
		$('#ClassID').html(data);
		sp.ShowTargetSelect();
	},	
	ShowTargetSelect:function(data){
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js
		var strOption = '';	
		var strTitle = '';
		$('.RowImg').hide();
		$('#KeyID').val(data.KeyID);
		switch(data.KeyID){
			case 1001://服装卡
				$('.RowImg').show();				
			case 2002://稳赢不输卡
			case 2003://双倍积分卡
			case 2004://积分保险卡
			case 2006://黄钻卡
				strOption = '<option value="天">天</option><option value="小时">小时</option><option value="分钟">分钟</option>';
				strTitle = '有效时间:';
				$('#EffectiveType').val(1);
				break;
			case 2001://金币卡
				strOption = '<option value="金币">金币</option>';
				strTitle = '财富金额:';
				$('#EffectiveType').val(3);
				break;
			case 2005://漂白卡(只针对打宝房间有效)
			case 2008://负分清零卡
			case 2009://运势卡
			case 2011://打折加油卡
				strOption = '<option value="次">次</option>';	
				strTitle = '使用次数:';
				$('#EffectiveType').val(2);
				break;	
			case 2007://游戏积分卡
				strOption = '<option value="积分">积分</option>';
				strTitle = '财富金额:';
				$('#EffectiveType').val(3);
				break;		
			case 2010://体力补充卡
				strOption = '<option value="体力">体力</option>';
				strTitle = '增加体力:';
				$('#EffectiveType').val(3);
				break;	
			case 2012://充值卡(比赛奖品用)
				strOption = '<option value="RMB">RMB</option>';
				strTitle = '金额:';
				$('#EffectiveType').val(4);
				break;
			case 2013://兑换卡(比赛奖品用)
				strOption = '<option value="金币">金币</option>';
				strTitle = '财富金额:';
				$('#EffectiveType').val(4);
				break;
		}
		$('#Unit').html(strOption);
		$('#Unit').parent().prev().html(strTitle);
		sp.GetUnit();
		//应用目标游戏
		if(data.Display=='show'){
			sp.GetGameKind();
			$('#target').show();
		}
		else
			$('#target').hide();
	},
	//运维平台(道具配置),添加道具
	AddSpPublic:function(data){
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js	
		$('#ResultMsg').html(data.Msg);
		if(data.iResult==0 && $('#SpID').val()==0){
			$('#GoodsName').val('');
			$('#SpNumber').val('');
			$('#ResourceID').val(0);
			//$('#ClassID').val(0);
			$('#Sex').val(-1);
			$('#Level').val(0);
			$('#VipID').val(0);		
			$('#EffectiveType').val(1);
			//sp.GetUnit();
			$('#Number').val(0);
			$('#SortID').val(1);
			$('#IsRecommend').val(0);
			$('#Intro').val('');
			$('input[name="Place"]').each(function(){ 
				$(this).attr('checked','');
			});
		}
	},
	//运维平台(游戏配置),显示添加桌子类型弹出层
	ShowAddGameTableHtml:function(data){
		main.MsgBox(data);
	},
	//运维平台(游戏配置),显示添加桌子类型弹出层
	ShowAddGameTaskHtml:function(data){
		main.MsgBox(data);
	},
	//运维平台(游戏配置),显示签到信息弹出层
	ShowAddGameSignHtml:function(data){
		main.MsgBox(data);
	},
	//运维平台(游戏配置),显示添加桌子类型
	AddGameTable:function(data){
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js
		if(data.result==0){
			$('#TableSchemeID').val('');
			$('#SchemeName').val('');
			$('#TableID').val('');
			$('#LockBkID').val('');
			$('#GestureID').val('');
			$('#RunButtonID').val('');
			$('#TableDataID').val('');
			$('#ChairID').val('');
		}
		$('#AddGameTableMsg').html(data.msg);
		$('#TableSchemeID').next().html('*');
		$('#SchemeName').next().html('*');
		$('#TableID').next().html('*');
		$('#LockBkID').next().html('*');
		$('#GestureID').next().html('*');
		$('#RunButtonID').next().html('*');
		$('#TableDataID').next().html('*');
		$('#ChairID').next().html('*');
	},
	AddGameTask:function(data){
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js
		$('#AddGameTaskMsg').html(data.msg);

		$('#AddGameTableMsg').html(data.msg);
		$('#KindID').next().html('*');
		$('#GameCount').next().html('*');
		$('#RoomType').next().html('*');
		$('#AwardMoney').next().html('*');
	},
	//运维平台(游戏配置),删除桌子类型信息
	DelGameTableInfo:function(data){
		if(!isNaN(data)){
		   window.location.reload();
		}else{
		   main.MsgBox(data);
		}	
	},
		//运维平台(游戏配置),删除游戏任务
	DelGameTask:function(data){
		if(!isNaN(data)){
		   window.location.reload();
		}else{
		   main.MsgBox(data);
		}	
	},
			//运维平台(游戏配置),删除游戏签到信息
	DelGameSign:function(data){
		if(!isNaN(data)){
		   window.location.reload();
		}else{
		   main.MsgBox(data);
		}	
	},
	//运维平台(系统银行配置),显示添加银行账户表单
	ShowAddBankAccHtml:function(data){
		main.MsgBox(data);
		window.parent.bank.BindEvent();
	},
	//运维平台(系统银行配置),添加银行账户
	AddSysBankAccNo:function(data){
		if(data==0){
			$('#ResultMsg').html('银行账户设置成功');
			$('#AccNo').val('');
			$('#AccType').val(0);
		}
		else
			$('#ResultMsg').html('银行账户设置失败');
	},
	//运维平台(系统银行配置),系统扩容
	AddBankCapacity:function(data){
		$('#ResultMsg').html(data);
	},
	//运维平台(系统银行配置),弹出显示系统扩容界面
	ShowAddBankCapacityHtml:function(data){
		main.MsgBox(data);
		window.parent.bank.BindEvent();
	},
	//运维平台(系统银行配置),银行转账
	TransSysBankMoney:function(data){
		if(isNaN(data))
			main.MsgBox(data);
		else if(data==0)
			$('#ResultMsg').html('转账成功');
		else if(data==-8888)
			$('#ResultMsg').html('转账失败,转出账户跟转入账户不能一致');
		else if(data==-9999)
			$('#ResultMsg').html('转账失败,您传入的参数有误,请重试');
		else if(data==-4)
			$('#ResultMsg').html('转账失败,转出金额必须大于0');		
		else if(data==-3)
			$('#ResultMsg').html('转账失败,转出账户余额不足');
		else
			$('#ResultMsg').html('转账失败');
	},
	//运维平台(系统配置),系统配置
	AddSysConfig:function(data){
		$('#ResultMsg').html(data);	
	},
	//运维平台(系统配置),显示黄钻等级表单
	ShowAddVipLevelHtml:function(data){
		main.MsgBox(data);
		window.parent.vip.BindEvent();
	},
	//运维平台(系统配置),添加黄钻等级
	AddVipLevel:function(data){
		$('#ResultMsg').html(data);	
	},
	//运维平台(系统配置),删除黄钻等级
	DelVipLevel:function(data){
		if(data==0){
		   	window.location.reload();
		}else{
		   main.MsgBox(data);
		}
	},
	//运维平台(机器人配置),删除机器人信息
	DelRobotName:function(data){
		if(data==0){
		   	window.location.reload();
		}else{
		   main.MsgBox(data);
		}
	},
	//运维平台(机器人配置),删除机器人账号
	DelRobotUser:function(data){
		if(data==0){
		   	window.location.reload();
		}else{
		   main.MsgBox(data);
		}
	},
	//运维平台(机器人配置),删除房间机器人
	DelRoomRobot:function(data){
		if(data==0){
		   	window.location.reload();
		}else{
		   main.MsgBox(data);
		}
	},
	//运维平台(系统配置),显示游戏配置表单
	ShowAddGameConfigHtml:function(data){
		main.MsgBox(data);
		window.parent.cfg.BindEvent();
	},
	//运维平台(系统配置),显示充值折扣配置表单
	ShowAddCardChargeRateHtml:function(data){
		main.MsgBox(data);
		window.parent.cfg.BindEvent();
	},
	//运维平台(系统配置),添加游戏配置
	AddGameConfig:function(data){
		$('#ResultMsg').html(data);	
	},
	//运维平台(系统配置),添加充值折扣配置
	AddCardChargeRate:function(data){
		$('#ResultMsg').html(data);	
	},
	//运维平台(机器人配置),添加机器人配置
	AddRobotName:function(data){
		switch(data){
			case '1':
				$('#ResultMsg').html('机器人配置信息发布成功');
				break;
			case '0':
				$('#ResultMsg').html('机器人配置信息发布失败');
				break;
			default:
				$('#ResultMsg').html('编辑成功');
				break;
		}
	},
    AddOnlineDes:function(data){
        $('#AddOnlineDesMsg').html(data);       ;
    },
	//运维平台(机器人配置),添加机器人配置
	AddRobotUser:function(data){
		$('#AddRobotUserMsg').html(data);	
	},
	//运维平台(机器人配置),添加房间机器人配置
	AddRoomRobot:function(data){
		$('#AddRoomRobotMsg').html(data);	
	},
	//运维平台(系统配置),删除游戏配置
	DelGameConfig:function(data){
		if(data==0){
		   	window.location.reload();
		}else{
		   main.MsgBox(data);
		}
	},
	//运维平台(系统配置),显示角色等级表单
	ShowAddRoleLevelHtml:function(data){
		main.MsgBox(data);
		window.parent.lvl.BindEvent();
	},
	//运维平台(系统配置),添加角色等级
	AddRoleLevel:function(data){
		$('#ResultMsg').html(data);	
	},
	//运维平台(系统配置),删除角色等级
	DelRoleLevel:function(data){
		if(data==0){
		   	window.location.reload();
		}else{
		   main.MsgBox(data);
		}
	},	
	//运维平台(系统配置),角色等级列表
	GetPagerLevel:function(data){//alert(data);
		$('#LevelList').html(data);
		init.SetTableRows();//初始化鼠标移到表格行显示背景		
		lvl.BindEvent();//初始化绑定事件
		//绑定分页
		var Param = '$(this).attr("pg"),"callback.GetPagerLevel"';
		var Param1 = '$(this).val(),"callback.GetPagerLevel"';
		evt.BindPageClick('','page.GetPagerLevel',Param,Param1);
	},
	//运维平台(系统配置),显示运势级别表单
	ShowAddLuckyHtml:function(data){
		main.MsgBox(data);
		window.parent.lucky.BindEvent();
	},
	//运维平台(系统配置),添加运势级别
	AddLucky:function(data){
		$('#ResultMsg').html(data);	
	},
	//运维平台(系统配置),删除运势级别
	DelLucky:function(data){
		if(data==0){
		   	window.location.reload();
		}else{
		   main.MsgBox(data);
		}
	},	
	//运维平台(系统配置),添加运势级别
	AddLuckyProb:function(data){
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js
		if(data.iResult==0){
			var iNum = $('#btnAddLuckyProb').attr('num');		
			if(iNum==0)
				$('#Prob').append('<tr id="Row_'+data.iID+'"><td bgcolor="#FFFFFF" align="center">'+$('#DropNum').val()+'</td><td bgcolor="#FFFFFF" align="center">'+$('#Probability').val()+'</td><td bgcolor="#FFFFFF" align="center"><a href="javascript:void(0)" class="edit" num="'+data.iID+'" onclick="lucky.Edit('+data.iID+','+$('#DropNum').val()+','+$('#Probability').val()+')">修改</a>&nbsp;&nbsp;<a href="javascript:void(0)" class="del" num="'+data.iID+'" onclick="lucky.DEL('+data.iID+')">删除</a></td></tr>');
			else
				$('#Row_'+iNum).html('<td bgcolor="#FFFFFF" align="center">'+$('#DropNum').val()+'</td><td bgcolor="#FFFFFF" align="center">'+$('#Probability').val()+'</td><td bgcolor="#FFFFFF" align="center"><a href="javascript:void(0)" class="edit" num="'+iNum+'" onclick="lucky.Edit('+iNum+','+$('#DropNum').val()+','+$('#Probability').val()+')">修改</a>&nbsp;&nbsp;<a href="javascript:void(0)" class="del" num="'+iNum+'" onclick="lucky.DEL('+iNum+')">删除</a></td>');
		}
		$('#DropNum').val(0);
		$('#Probability').val(0);
		$('#btnAddLuckyProb').attr('num',0);	
		$('#ResultMsg').html(data.Msg);	
	},
	//运维平台(系统配置),删除运势掉落概率
	DelLuckyProb:function(data){
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js
		if(data.iResult==0){
		   	$('#Row_'+data.ID).remove();
		}else{
		   $('#ResultMsg').html('数据删除失败');	
		}
	},	
	//运维平台(系统配置),注册敏感词列表
	GetPagerSysConfine:function(data){//alert(data);
		$('#SysConfineList').html(data);
		init.SetTableRows();//初始化鼠标移到表格行显示背景		
		confine.BindEvent();//初始化绑定事件(GameRoomPage.html)
		//绑定分页
		var Param = '$(this).attr("pg"),"callback.GetPagerSysConfine"';
		var Param1 = '$(this).val(),"callback.GetPagerSysConfine"';
		evt.BindPageClick('','page.GetPagerSysConfine',Param,Param1);
	},
	//运维平台(系统配置),显示添加敏感词表单
	ShowAddSysConfineNameHtml:function(data){
		main.MsgBox(data);
		window.parent.confine.BindEvent();
	},
	//运维平台(系统配置),添加敏感词
	AddSysConfineName:function(data){
		$('#ResultMsg').html(data);	
	},
	//运维平台(系统配置),删除敏感词
	DelSysConfineName:function(data){
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js
		if(data.iResult==0){
		   	window.location.reload();
		}else{
		   $('#ResultMsg').html(data.Msg);	
		}
	},	
	//运维平台(广告管理),广告分页
	GetPagerAdPos:function(data){//alert(data);
		$('#AdPosList').html(data);
		init.SetTableRows();//初始化鼠标移到表格行显示背景		
		adPos.BindEvent();//初始化绑定事件(GameRoomPage.html)
		//绑定分页
		var Param = '$(this).attr("pg"),"callback.GetPagerAdPos"';
		var Param1 = '$(this).val(),"callback.GetPagerAdPos"';
		evt.BindPageClick('','page.GetPagerAdPos',Param,Param1);
	},
	//运维平台(新闻分类管理),新闻分类分页
	GetPagerNewsCategory:function(data){
		$('#AdPosList').html(data);
		init.SetTableRows();//初始化鼠标移到表格行显示背景		
		NewsCate.BindEvent();//初始化绑定事件(GameRoomPage.html)
		//绑定分页
		var Param = '$(this).attr("pg"),"callback.GetPagerNewsCategory"';
		var Param1 = '$(this).val(),"callback.GetPagerNewsCategory"';
		evt.BindPageClick('','page.GetPagerNewsCategory',Param,Param1);
	},
	//运维平台(新闻管理),新闻分页
	GetPagerNews:function(data){
		$('#AdList').html(data);
		init.SetTableRows();//初始化鼠标移到表格行显示背景		
		news.BindEvent();//初始化绑定事件(GameRoomPage.html)
		//绑定分页
		var Param = '$(this).attr("pg"),"callback.GetPagerNews"';
		var Param1 = '$(this).val(),"callback.GetPagerNews"';
		evt.BindPageClick('','page.GetPagerNews',Param,Param1);
	},
	//运维平台(广告管理),弹出添加广告位表单页
	ShowAddAdPosHtml:function(data){//alert(data);
		main.MsgBox(data);
		window.parent.adp.BindEvent();
	},
	//运维平台(广告管理),添加广告位
	AddAdPos:function(data){
		switch(data){
			case '-1':
				$('#ResultMsg').html('广告位发布失败');
				break;			
			case '-9999':
				$('#ResultMsg').html('您提交的参数异常');
				break;
			default:
				$('#ResultMsg').html('广告位发布成功');				
				break;
		}
		$('#PositionTypeID').val(1);
		$('#PositionName').val('');
		$('#PositionID').val(0);
		$('#PositionWidth').val(0);
		$('#PositionHeight').val(0);
		$('#Intro').val('');
	},
	//运维平台(广告管理),删除广告位
	DelAdPos:function(data){
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js	
		if(data.iResult==0){
		   	window.location.reload();
		}else{
		   main.MsgBox(data.Msg);
		}
	},
	//运维平台(广告管理),广告分页
	GetPagerAd:function(data){//alert(data);
		$('#AdList').html(data);
		init.SetTableRows();//初始化鼠标移到表格行显示背景		
		ad.BindEvent();//初始化绑定事件(GameRoomPage.html)
		//绑定分页
		var Param = '$(this).attr("pg"),"callback.GetPagerAd"';
		var Param1 = '$(this).val(),"callback.GetPagerAd"';
		evt.BindPageClick('','page.GetPagerAd',Param,Param1);
	},
    //运维平台(广告管理),广告分页
    GetPagerMsg:function(data){//alert(data);
        $('#MsgList').html(data);
        init.SetTableRows();//初始化鼠标移到表格行显示背景
        msg.BindEvent();//初始化绑定事件(GameRoomPage.html)
        //绑定分页
        var Param = '$(this).attr("pg"),"callback.GetPagerMsg"';
        var Param1 = '$(this).val(),"callback.GetPagerMsg"';
        evt.BindPageClick('','page.GetPagerMsg',Param,Param1);
    },
	//运维平台(广告管理),广告分页
	GetTaskList:function(data){//alert(data);
		$('#TaskList').html(data);
		init.SetTableRows();//初始化鼠标移到表格行显示背景
		msg.BindEvent();//初始化绑定事件(GameTaskListPage.html)
		//绑定分页
		var Param = '$(this).attr("pg"),"callback.GetTaskList"';
		var Param1 = '$(this).val(),"callback.GetTaskList"';
		evt.BindPageClick('','page.GetPagerMsg',Param,Param1);
	},
	RoleList:function(data){//alert(data);
		$('#RoleList').html(data);
		init.SetTableRows();//初始化鼠标移到表格行显示背景
		msg.BindEvent();//初始化绑定事件(GameTaskListPage.html)
		//绑定分页
		var Param = '$(this).attr("pg"),"callback.RoleList"';
		var Param1 = '$(this).val(),"callback.RoleList"';
		evt.BindPageClick('','page.GetPagerMsg',Param,Param1);
	},
	MenuList:function(data){//alert(data);
		$('#MenuList').html(data);
		init.SetTableRows();//初始化鼠标移到表格行显示背景
		msg.BindEvent();//初始化绑定事件(GameTaskListPage.html)
		//绑定分页
		var Param = '$(this).attr("pg"),"callback.RoleList"';
		var Param1 = '$(this).val(),"callback.RoleList"';
		evt.BindPageClick('','page.GetPagerMsg',Param,Param1);
	},
	//运维平台(广告管理),弹出添加广告表单页
	ShowAddAdHtml:function(data){//alert(data);
		main.MsgBox(data);
		window.parent.adEdit.BindEvent();
	},

    ShowAddMsgHtml:function(data){//alert(data);
        main.MsgBox(data);
        window.parent.msgEdit.BindEvent();
    },
    ShowAddCtrlRoomHtml:function(data){//alert(data);
        main.MsgBox(data);
        window.parent.ctrlEdit.BindEvent();
    },
	ShowHappyBeanEdit:function(data) {
		main.MsgBox(data);
		window.parent.ctrlEdit.BindEvent();
	},
	ShowEditClass:function(data) {
		main.MsgBox(data);
		window.parent.ctrlEdit.BindEvent();
	},
	ShowEditChannel:function(data) {
		main.MsgBox(data);
		window.parent.ctrlEdit.BindEvent();
	},
	ShowAddChannel:function(data) {
		main.MsgBox(data);
		window.parent.ctrlEdit.BindEvent();
	},
	ShowEditAmount:function(data) {
		main.MsgBox(data);
		window.parent.ctrlEdit.BindEvent();
	},
	ShowAddAmount:function(data) {
		main.MsgBox(data);
		window.parent.ctrlEdit.BindEvent();
	},
	ShowEditRelation:function(data) {
		main.MsgBox(data);
		window.parent.ctrlEdit.BindEvent();
	},
    ShowEditRoom:function(data) {
        main.MsgBox(data);
        window.parent.ctrlEdit.BindEvent();
    },
	ShowEditTask:function(data) {
		main.MsgBox(data);
		window.parent.ctrlEdit.BindEvent();
	},
	ShowEditRole:function(data) {
		main.MsgBox(data);
		window.parent.ctrlEdit.BindEvent();
	},
	ShowEditSet:function(data) {
		main.MsgBox(data);
		window.parent.ctrlEdit.BindEvent();
	},
	ShowEditMenu1:function(data) {
		main.MsgBox(data);
		window.parent.ctrlEdit.BindEvent();
	},
	ShowEditMenu2:function(data) {
		main.MsgBox(data);
		window.parent.ctrlEdit.BindEvent();
	},
	ShowSetRole:function(data) {
		main.MsgBox(data);
		window.parent.ctrlEdit.BindEvent();
	},
	ShowMenuOrder:function(data) {
		main.MsgBox(data);
		window.parent.ctrlEdit.BindEvent();
	},
	ShowAddMenu:function(data) {
		main.MsgBox(data);
		window.parent.ctrlEdit.BindEvent();
	},
	ShowAddMenu2:function(data) {
		main.MsgBox(data);
		window.parent.ctrlEdit.BindEvent();
	},
	ShowAddRole:function(data) {
		main.MsgBox(data);
		window.parent.ctrlEdit.BindEvent();
	},
	ShowAddRelation:function(data) {
		main.MsgBox(data);
		window.parent.ctrlEdit.BindEvent();
	},
	//运维平台(广告管理),弹出添加广告表单页
	ShowAddNewsHtml:function(data){//alert(data);
		main.MsgBox(data);
		window.parent.newEdit.BindEvent();
	},
	//运维平台(广告管理),读取广告位
	GetAdPosition:function(data){
		$('#PositionID').html(data);
	},
	//运维平台(广告管理),添加广告
	AddAd:function(data){
		switch(data){
			case '-1':
				$('#ResultMsg').html('广告位发布失败');
				break;			
			case '-9999':
				$('#ResultMsg').html('您提交的参数异常');
				break;
			default:
				$('#ResultMsg').html('广告位发布成功');				
				break;
		}
		if($('#AdID').val()==0){
			$('#AdName').val('');
			$('#ImgPath_FileUpload').val('');
			$('#LinkURL').val('http://');
			$('#StartTime').val('');
			$('#EndTime').val('');
			$('#SortID').val(0);
			$('#Intro').val('');
		}
	},
    AddMsg:function(data){
        switch(data){
            case '-1':
                $('#ResultMsg').html('跑马灯添加失败');
                break;
            case '-9999':
                $('#ResultMsg').html('您提交的参数异常');
                break;
            default:
                $('#ResultMsg').html('跑马灯添加成功');
                break;
        }
    },
	EditTask:function(data){
		switch(data){
			case '-1':
				$('#ResultMsg').html('编辑失败');
				break;
			case '-9999':
				$('#ResultMsg').html('编辑失败');
				break;
			default:
				$('#ResultMsg').html('编辑成功');
				main.CloseMsgBox(true,'GameTask');
				break;
		}
	},
    SetTaskStatus:function(data){
        switch(data){
            case '-1':
                alert('更新失败');
                break;
            case '-9999':
                alert('更新失败');
                break;
            default:
                alert('更新成功');
                window.location.reload();
                break;
        }
    },
	EditRole:function(data){
		switch(data){
			case '-1':
				$('#ResultMsg').html('编辑失败');
				break;
			case '-9999':
				$('#ResultMsg').html('编辑失败');
				break;
			default:
				$('#ResultMsg').html('编辑成功');
				main.CloseMsgBox(true,'SysRole');
				break;
		}
	},
	EditMenu1:function(data){
		switch(data){
			case '-1':
				$('#ResultMsg').html('编辑失败');
				break;
			case '-9999':
				$('#ResultMsg').html('编辑失败');
				break;
			default:
				$('#ResultMsg').html('编辑成功');
				main.CloseMsgBox(true,'SysMenu');
				break;
		}
	},
	SetAdminRole:function(data){
		switch(data){
			case '-1':
				$('#ResultMsg').html('编辑失败');
				break;
			case '-9999':
				$('#ResultMsg').html('编辑失败');
				break;
			default:
				$('#ResultMsg').html('编辑成功');
				main.CloseMsgBox(true,'SysUser');
				break;
		}
	},
	EditMenuOrder:function(data){
		switch(data){
			case '-1':
				$('#ResultMsg').html('编辑失败');
				break;
			case '-9999':
				$('#ResultMsg').html('编辑失败');
				break;
			default:
				$('#ResultMsg').html('编辑成功');
				main.CloseMsgBox(true,'SysMenu');
				break;
		}
	},
	DeleteRole:function(data){
		switch(data){
			case '-1':
				alert('删除失败');
				break;
			case '-9999':
				alert('删除失败');
				break;
			default:
				alert('删除成功');
				window.location.reload();
				break;
		}
	},
	DeleteMenu:function(data){
		switch(data){
			case '-1':
				alert('删除失败');
				break;
			case '-9999':
				alert('删除失败');
				break;
			default:
				alert('删除成功');
				window.location.reload();
				break;
		}
	},
	ShowMenu:function(data){
		switch(data){
			case '-1':
				alert('更新失败');
				break;
			case '-9999':
				alert('更新失败');
				break;
			default:
				alert('更新成功');
				window.location.reload();
				break;
		}
	},
	AddMsg2:function(data){
		switch(data){
			case '-9999':
				$('#ResultMsg').html('您提交的参数异常');
				break;
			default:
				$('#ResultMsg').html('公告修改成功');
				break;
		}
	},
    AddCtrlRool:function(data){
        switch(data){
            case '-1':
                $('#ResultMsg').html('库存设置失败');
                break;
            case '-9999':
                $('#ResultMsg').html('您提交的参数异常');
                break;
            default:
                $('#ResultMsg').html('设置成功');
                break;
        }
    },
	SetBankMoney:function(data){
		switch(data){
			case '1':
				$('#ResultMsg').html('设置成功');
				main.CloseMsgBox(true,'HappyBeanlist');
				//window.location.reload();
				break;
			default:
				$('#ResultMsg').html('设置失败');

				//window.location.reload();
				break;
		}
	},
	DeleteBankMoney:function(data){
		switch(data){
			case '1':
				alert('删除成功');
				window.location.reload();
				main.CloseMsgBox(true,'HappyBeanlist');
				break;
			default:
				alert('删除失败');

				//window.location.reload();
				break;
		}
	},

	EditBankMoney:function(data){
		switch(data){
			case '1':
				$('#ResultMsg').html('设置成功');
				main.CloseMsgBox(true,'HappyBeanlist');
				//window.location.reload();
				break;
			default:
				$('#ResultMsg').html('设置失败');


				//window.location.reload();
				break;
		}
	},
	SetClassRool:function(data){
		switch(data){
			case '-1':
				$('#ResultMsg').html('设置失败');
				break;
			case '1':
				$('#ResultMsg').html('设置失败');
				break;
			default:
				$('#ResultMsg').html('设置成功');
				main.CloseMsgBox(true,'GamePayClass');
				//window.location.reload();
				break;
		}
	},
	SetChannelRool:function(data){
		switch(data){
			case '-1':
				$('#ResultMsg').html('设置失败');
				break;
			case '1':
				$('#ResultMsg').html('设置失败');
				break;
			default:
				$('#ResultMsg').html('设置成功');
				main.CloseMsgBox(true,'GamePayChannel');
				//window.location.reload();
				break;
		}
	},
	AddChannelRool:function(data){
		switch(data){
			case '-1':
				$('#ResultMsg').html('设置失败');
				break;
			case '1':
				$('#ResultMsg').html('设置失败');
				break;
			default:
				$('#ResultMsg').html('设置成功');
				main.CloseMsgBox(true,'GamePayChannel');
				//window.location.reload();
				break;
		}
	},
	AddSysRoleRool:function(data){
		switch(data){
			case '-1':
				$('#ResultMsg').html('设置失败');
				break;
			case '9999':
				$('#ResultMsg').html('设置失败');
				break;
			default:
				$('#ResultMsg').html('设置成功');
				main.CloseMsgBox(true,'SysRole');
				//window.location.reload();
				break;
		}
	},
	AddSysMenuRool:function(data){
		switch(data){
			case '-1':
				$('#ResultMsg').html('设置失败');
				break;
			case '9999':
				$('#ResultMsg').html('设置失败');
				break;
			default:
				$('#ResultMsg').html('设置成功');
				main.CloseMsgBox(true,'SysMenu');
				//window.location.reload();
				break;
		}
	},
	AddAmountRool:function(data){
		switch(data){
			case '-1':
				$('#ResultMsg').html('新增失败');
				break;
			case '1':
				$('#ResultMsg').html('新增失败');
				break;
			default:
				$('#ResultMsg').html('新增成功');
				main.CloseMsgBox(true,'GamePayAmount');
				//window.location.reload();
				break;
		}
	},
	EditAmountRool:function(data){
		switch(data){
			case '-1':
				$('#ResultMsg').html('编辑失败');
				break;
			case '1':
				$('#ResultMsg').html('编辑失败');
				break;
			default:
				$('#ResultMsg').html('编辑成功');
				main.CloseMsgBox(true,'GamePayAmount');
				//window.location.reload();
				break;
		}
	},
	EditRelationRool:function(data){
		switch(data){
			case '-1':
				$('#ResultMsg').html('编辑失败');
				break;
			case '1':
				$('#ResultMsg').html('编辑失败');
				break;
			default:
				$('#ResultMsg').html('编辑成功');
				main.CloseMsgBox(true,'GamePayRelation');
				//window.location.reload();
				break;
		}
	},
	AddRelationRool:function(data){
		switch(data){
			case '-1':
				$('#ResultMsg').html('新增失败');
				break;
			case '1':
				$('#ResultMsg').html('新增失败');
				break;
			default:
				$('#ResultMsg').html('新增成功');
				main.CloseMsgBox(true,'GamePayRelation');
				//window.location.reload();
				break;
		}
	},
	//运维平台(广告管理),锁定广告
	SetAdLocked:function(data){
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js	
		if(data.iResult==0){
		   	window.location.reload();
		}else{
		   main.MsgBox(data.Msg);
		}
	},
    SetMsgLocked:function(data){
        data=$.evalJSON(data);//字符串格式转为json对象,extend.js
        if(data.iResult==0){
            window.location.reload();
        }else{
            alert("修改成功");
            window.location.reload();
        	//main.MsgBox(data.Msg);
        }
    },
    SetRankStatus:function(data){ //修改排行榜隐藏和显示
        data=$.evalJSON(data);//字符串格式转为json对象,extend.js
        if(data.iResult==0){
            window.location.reload();
        }else{
            alert("修改成功");
            $('#txt_'+roleid).html(data);
            //main.MsgBox(data.Msg);
        }
    },
    ShowRankEditHtml:function(data){//alert(data);//显示修改排行弹出对象
        main.MsgBox(data);
        window.parent.msg.BindEvent();
    },
	//运维平台(广告管理),删除广告
	DelAd:function(data){
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js	
		if(data.iResult==0){
		   	window.location.reload();
		}else{
		   main.MsgBox(data.Msg);
		}
	},
    DelMsg:function(data){
        data=$.evalJSON(data);//字符串格式转为json对象,extend.js
		console.log(data.iResult);
        if(data.iResult==0){
            window.location.reload();
        }else{
           // main.MsgBox(data.Msg);
			alert("删除成功");
            window.location.reload();
        }
    },
	//运维平台(新闻类别管理),添加新闻类别
	addNewsCategory:function(data){
		switch(data){
			case '-1':
				$('#ResultMsg').html('新闻类别发布失败');
				break;			
			case '-9999':
				$('#ResultMsg').html('您提交的参数异常');
				break;
			default:
				$('#ResultMsg').html('新闻类别发布成功');				
				break;
		}
		$('#CateName').val('');
	},
	//运维平台(新闻类别管理),弹出添加新闻类别表单页
	ShowAddNewsCateHtml:function(data){
		main.MsgBox(data);
		window.parent.NewsCate.BindEvent();
	},
	//运维平台(新闻类别管理),添加新闻类别
	AddNews:function(data){
		switch(data){
			case '-1':
				$('#ResultMsg').html('新闻发布失败');
				break;			
			case '-9999':
				$('#ResultMsg').html('您提交的参数异常');
				break;
			default:
				$('#ResultMsg').html('新闻发布成功');				
				break;
		}
		$('#NewsTitle').val('');
		$('#NewsContent').val('');
	},
	//运维平台(新闻管理),删除新闻
	DelNews:function(data){
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js	
		if(data.iResult==0){
		   	window.location.reload();
		}else{
		   main.MsgBox(data.Msg);
		}
	},
	//客服平台(角色信息)，基本信息
	GetRoleBaseInfo:function(data){
		$('#BaseInfoDetail').html(data);
	},
	//客服平台(角色信息)，加载修改操作
	GetModefyDetail:function(data){
		$('#ModifyDetailInfo').html(data);
	},
	//客服平台(角色信息),角色列表
	GetPagerRole:function(data){
		$('#RoleList').html(data);
		init.SetTableRows();//初始化鼠标移到表格行显示背景
		Role.BindEvent();
		//绑定分页
		var Param = '$(this).attr("pg"),"callback.GetPagerRole"';
		var Param1 = '$(this).val(),"callback.GetPagerRole"';
		evt.BindPageClick('','page.GetPagerRole',Param,Param1);		
	},
	//批量锁定角色
	LockPagerRole:function(data){
		alert(data);
	},
	//客服平台(角色信息),角色会员详细信息,VIP开通明细
	ShowVipDetail:function(data){
		$('#VipDetailList').html(data);
		init.SetTableRows();//初始化鼠标移到表格行显示背景	
		//evt.BindVipSararyDetailClick();	
		//绑定分页
		var Param = '$(this).attr("pg"),"callback.ShowVipDetail"';
		var Param1 = '$(this).val(),"callback.ShowVipDetail"';
		evt.BindPageClick('#RoleVipPage ','page.GetPagerVipDetail',Param,Param1);
	},
	//客服平台(角色信息),角色会员详细信息,日工资明细
	ShowSalaryDetail:function(data){
		main.MsgBox(data);
		//绑定分页
		//var Param = '$(this).attr("logsid"),$(this).attr("date"),"callback.GetPagerSalaryDetail"';
		//evt.BindPageClick('page.GetPagerSalaryDetail',Param);	
		window.parent.sd.GetPagerSalaryDetail();
	},
	//客服平台(角色信息),角色会员详细信息,日工资明细
	GetPagerSalaryDetail:function(data){
		$('#SalaryDetailList').html(data);			
		//绑定分页
		var Param = '$(this).attr("pg"),"callback.GetPagerSalaryDetail"';
		var Param1 = '$(this).val(),"callback.GetPagerSalaryDetail"';
		evt.BindPageClick('','page.GetPagerSalaryDetail',Param,Param1);
	},
	//客服平台(角色信息),角色会员详细信息,月礼包明细
	ShowMonthGiftPackage:function(data){
		main.MsgBox(data);
		window.parent.monthGift.GetPagerMonthDetail();
	},
	//客服平台(角色信息),角色会员详细信息,月礼包明细
	GetPageMonthGiftDetail:function(data){
		$('#MonthGiftDetailList').html(data);			
		//绑定分页
		var Param = '$(this).attr("pg"),"callback.GetPageMonthGiftDetail"';
		var Param1 = '$(this).val(),"callback.GetPageMonthGiftDetail"';
		evt.BindPageClick('','page.GetPageMonthGiftDetail',Param,Param1);
	},
	//客服平台（角色信息），加载银行资料内容
	GetBankInfoDetail:function(data){
		$('#BankInfoDetail').html(data);
	},
	//客服平台(角色信息),角色会员详细信息,游戏资料
	GetUserGameData:function(data){
		$('#UserGameData').html(data);
		init.SetTableRows();//初始化鼠标移到表格行显示背景
		if($('.ScoreDetail')){
			rgdp.GetUserGameDataDetail();//绑定普通房间成绩明细事件
		}else{
			rgds.GetPagerUserGameDataSp();	
		}
		//绑定分页
		var Param = '$(this).attr("pg"),"callback.GetUserGameData"';
		var Param1 = '$(this).val(),"callback.GetUserGameData"';
		evt.BindPageClick('#RGDSP ','page.GetPagerUserGameData',Param,Param1);
	},
	//客服平台(角色信息),角色会员详细信息,普通房间成绩明细
	GetUserGameDataDetail:function(data){
		main.OpenBox(data);
		rgdd.GetUserGameDataDetailPage();
	},
	//客服平台(角色信息),角色会员详细信息,普通房间成绩明细
	GetPagerUserGameDataDetail:function(data){
		$('#UseGameDataDetail').html(data);
		init.SetTableRows();//初始化鼠标移到表格行显示背景
		var Param = '$(this).attr("pg"),$(this).attr("logsid"),$(this).attr("date"),"callback.GetPagerUserGameDataDetail"';
		var Param1 = '$(this).val(),$(this).attr("logsid"),$(this).attr("date"),"callback.GetPagerUserGameDataDetail"';
		evt.BindSimplePage('#RGDDP','page.GetPagerUserGameDataDetail',Param,Param1);
	},
	//客服平台(角色信息),角色会员详细信息,普通房间游戏记录明细
	GetUserGameDetail:function(data){
		main.OpenBox(data);
		rgd.GetUserGameDetailPage();
	},
	//客服平台(角色信息),角色会员详细信息,普通房间游戏记录明细
	GetPagerUserGameDetail:function(data){
		$('#UseGameDetail').html(data);
		init.SetTableRows();//初始化鼠标移到表格行显示背景
		var Param = '$(this).attr("pg"),$(this).attr("logsid"),$(this).attr("date"),"callback.GetPagerUserGameDetail"';
		var Param1 = '$(this).val(),$(this).attr("logsid"),$(this).attr("date"),"callback.GetPagerUserGameDetail"';
		evt.BindSimplePage('#RGDP','page.GetPagerUserGameDetail',Param,Param1);
	},
	//客服平台(角色信息),角色会员详细信息,获取本局游戏其他玩家游戏记录
	GetUserGameAllPeopleInfo:function(data){
		main.OpenBox(data);
	},
	//客服平台(角色信息),角色会员详细信息,寻宝乐园房间记录明细
	GetPagerUserGameDataSp:function(data){
		if(data!=''){
			$('#GameDataSpPage').html(data);
			init.SetTableRows();//初始化鼠标移到表格行显示背景
			var Param = '$(this).attr("pg"),$(this).attr("logsid"),$(this).attr("date"),"callback.GetPagerUserGameDataSp"';
			var Param1 = '$(this).val(),$(this).attr("logsid"),$(this).attr("date"),"callback.GetPagerUserGameDataSp"';
			evt.BindSimplePage('#RGDSP','page.GetPagerUserGameDataSp',Param,Param1);
		}
	},	
	//客服平台(角色信息),角色会员详细信息,寻宝房间游戏明细分页
	GetUserGameDataSpDetailPage:function(data){
		$('#SpRoleGameDetailInfo').html(data);			
		//绑定分页
		var Param = '$(this).attr("pg"),"callback.GetUserGameDataSpDetailPage"';
		var Param1 = '$(this).val(),"callback.GetUserGameDataSpDetailPage"';
		evt.BindPageClick('','page.GetUserGameDataSpDetailPage',Param,Param1);
	},
	//客服平台(角色信息),角色会员详细信息,银行资料转账记录明细
	GetPageUserTransferRecords:function(data){
		if(data!=''){
			$('#TransferRecords').html(data);
			init.SetTableRows();//初始化鼠标移到表格行显示背景
			var Param = '$(this).attr("pg"),$(this).attr("logsid"),$(this).attr("date"),"callback.GetPageUserTransferRecords"';
			var Param1 = '$(this).val(),$(this).attr("logsid"),$(this).attr("date"),"callback.GetPageUserTransferRecords"';
			evt.BindSimplePage('#RTRP','page.GetPageUserTransferRecords',Param,Param1);
		}
	},
	//客服平台(角色信息),角色会员详细信息,充值记录分页
	GetPagerUserRechargeRecords:function(data){
		if(data!=''){
			$('#RechargeRecordsPage').html(data);
			init.SetTableRows();//初始化鼠标移到表格行显示背景
			var Param = '$(this).attr("pg"),"callback.GetPagerUserRechargeRecords"';
			var Param1 = '$(this).val(),"callback.GetPagerUserRechargeRecords"';
			evt.BindPageClick('#RechargeRecordsPages ','page.GetPagerUserRechargeRecords',Param,Param1);
		}
	},
	//客服平台(角色信息),角色会员详细信息,银行转账记录导出到execl
	DownloadUserBankRecordsExecl:function(data){
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js
		var width = 200;//进度条宽度			
		if(data.CurPage>0){			
			width=Math.ceil(data.CurPage/data.TotalPage*200)
			re.DownloadUserBankRecordsExecl(data.CurPage);
		}
		else{
			window.parent.$('#ProgressBarMsg').html('文件生成完成');
			window.parent.$('#btnDownload').click(function(){
				window.location.href = data.Http;
			});
			if(data.Http!='')
				window.location.href = data.Http;
		}		
		window.parent.$('#ProgressBar div').css('width',width);
	},
	//客服平台(角色信息),角色会员详细信息,充值记录导出到execl
	DownloadUserRechargeRecordsExecl:function(data){
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js
		var width = 200;//进度条宽度			
		if(data.CurPage>0){			
			width=Math.ceil(data.CurPage/data.TotalPage*200)
			re.DownloadUserRechargeRecordsExecl(data.CurPage);
		}
		else{
			window.parent.$('#ProgressBarMsg').html('文件生成完成');
			window.parent.$('#btnDownload').click(function(){
				window.location.href = data.Http;
			});
			if(data.Http!='')
				window.location.href = data.Http;
		}		
		window.parent.$('#ProgressBar div').css('width',width);
	},
	//客服平台(角色信息),道具资料->背包分页
	GetMySpInfo:function(data){
		$('#SpInfoDetail').html(data);
		init.SetTableRows();//初始化鼠标移到表格行显示背景
		var Param = '$(this).attr("pg"),"callback.GetMySpInfo"';
		var Param1 = '$(this).val(),"callback.GetMySpInfo"';
		evt.BindPageClick('#RoleSPPages ','page.GetPagerMySpInfo',Param,Param1);
	},	
	//客服平台(角色信息),道具资料->背包日志分页
	GetMyKnapsackLogs:function(data){
		if(data!=''){
			$('#SpInfoDetail').html(data);
			init.SetTableRows();//初始化鼠标移到表格行显示背景
			var Param = '$(this).attr("pg"),$(this).attr("logsid"),$(this).attr("date"),"callback.GetMyKnapsackLogs"';
			var Param1 = '$(this).val(),$(this).attr("logsid"),$(this).attr("date"),"callback.GetMyKnapsackLogs"';
			evt.BindSimplePage('#RMKL','page.GetPagerMyKnapsackLogs',Param,Param1);
		}
	},
	//客服平台(角色信息),角色日志->财富冻结日志分页
	GetTreasureFreezeLogs:function(data){
		$('#PlayerLogsDetail').html(data);
		init.SetTableRows();//初始化鼠标移到表格行显示背景
		var Param = '$(this).attr("pg"),"callback.GetTreasureFreezeLogs"';
		var Param1 = '$(this).val(),"callback.GetTreasureFreezeLogs"';
		evt.BindPageClick('#RoleLogsPages ','page.GetPagerTreasureFreezeLogs',Param,Param1);
	},
	//客服平台(角色信息),角色日志->锁号/封号日志分页
	GetLockAccountLogs:function(data){
		$('#PlayerLogsDetail').html(data);
		init.SetTableRows();//初始化鼠标移到表格行显示背景
		var Param = '$(this).attr("pg"),"callback.GetLockAccountLogs"';
		var Param1 = '$(this).val(),"callback.GetLockAccountLogs"';
		evt.BindPageClick('#RoleLogsPages ','page.GetPagerLockAccountLogs',Param,Param1);
	},
	//客服平台(角色信息),角色日志->操作日志分页
	GetPlayerLogsDetail:function(data){
		if(data!=''){
			$('#PlayerLogsDetail').html(data);
			init.SetTableRows();//初始化鼠标移到表格行显示背景
			var Param = '$(this).attr("pg"),$(this).attr("logsid"),$(this).attr("date"),"callback.GetPlayerLogsDetail"';
			var Param1 = '$(this).val(),$(this).attr("logsid"),$(this).attr("date"),"callback.GetPlayerLogsDetail"';
			evt.BindSimplePage('#RPLD','page.GetPagerPlayerLogsDetail',Param,Param1);
		}
	},
	//运营平台(道具发布),发布道具(列表)
	SetSpRelease:function(data){
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js	
		if(data.iResult==0){
		   	window.location.reload();
		}else{
		   main.MsgBox(data.Msg);
		}
	},
	//运营平台(道具发布),发布道具(详细信息)
	ReleaseSp:function(data){
		$('#ResultMsg').html(data);
	},
	//运营平台(道具发布),道具分类
	GetSpReleaseClass:function(data){
		$('#ClassID').html(data);
		sp.GetSpPublicList();
	},	
	//运营平台(道具发布),分类下的道具列表
	GetSpPubList:function(data){
		$('#SpList').html(data);
		sp.GetSpPublicInfo();
	},	
	//运营平台(道具发布),道具详细信息
	GetSpPublicInfo:function(data){
		if(data!=''){
			var Sex = '不限';
			var VipID = '不限';
			var strTitle = '';
			var arrSpInfo = Array();
			arrSpInfo = data.split('|@|');
			if(arrSpInfo[6]==0)
				Sex = '男';
			else if(arrSpInfo[6]==1)
				Sex = '女';
			if(arrSpInfo[8]==1) VipID='黄钻专用';
			switch (arrSpInfo[21]){
				case '1001'://服装卡
				case '2002'://稳赢不输卡
				case '2003'://双倍积分卡
				case '2004'://积分保险卡
				case '2006'://黄钻卡
					strTitle = '有效时间:';
					break;
				case '2001'://金币卡
					strTitle = '财富金额:';
					break;
				case '2005'://漂白卡(只针对打宝房间有效)
				case '2008'://负分清零卡
				case '2009'://运势卡
				case '2011'://打折加油卡
					strTitle = '使用次数:';
					break;	
				case '2007'://游戏积分卡
					strTitle = '财富金额:';
					break;		
				case '2010'://体力补充卡
					strTitle = '增加体力:';
					break;	
			}
			
			if(arrSpInfo[5]==0) $('#tdTarget').hide();
			if(arrSpInfo[21]!='1001') $('.SpImg').hide();//如果不是服装,不显示形象图
			$('#tdSpNumber').html(arrSpInfo[2]);
			$('#tdResourceID').html(arrSpInfo[3]);
			$('#tdImgPath').attr('src',arrSpInfo[16]);
			$('#tdImgPath1').attr('src',arrSpInfo[17]);
			$('#tdImgPath2').attr('src',arrSpInfo[18]);			
			$('#tdSex').html(Sex);
			$('#tdLevel').html(arrSpInfo[7]);
			$('#tdVipID').html(VipID);
			$('#tdPlace').html(arrSpInfo[14]);			
			$('#tdGameKind').html(arrSpInfo[22]);
			$('#tdTitle').html(arrSpInfo[24]);
			$('#tdTitle').html(strTitle);
			$('#tdNumber').html(arrSpInfo[12]+' '+arrSpInfo[11]);
			$('#tdIntro').html(arrSpInfo[13]);
		}
	},
	//运营平台(设置礼包),礼包列表
	GetPagerGiftPackage:function(data){//alert(data);
		$('#GiftPackageList').html(data);
		init.SetTableRows();//初始化鼠标移到表格行显示背景		
		gift.BindEvent();//初始化绑定事件(GameRoomPage.html)
		//绑定分页
		var Param = '$(this).attr("pg"),"callback.GetPagerGiftPackage"';
		var Param1 = '$(this).val(),"callback.GetPagerGiftPackage"';
		evt.BindPageClick('','page.GetPagerGiftPackage',Param,Param1);
	},
	//运营平台(设置礼包),道具分类
	GetStagePropertyClass:function(data){
		$('#ThirdClassID').remove();
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js
		$('#SubClassID').html(data.Option);
		if(data.SubOption!=''){
			$('#Col_Class').append(data.SubOption);
			$('#SubClassID').change(function(){
				gift.GetThirdClass();							 
			});
		}
		else
			$('#SubClassID').change(function(){
				gift.GetSpPublicList();							 
			});
		gift.GetSpPublicList();
	},
	//运营平台(设置礼包),道具分类
	GetThirdClass:function(data){		
		if(data!='')
			$('#ThirdClassID').html(data);	
		else
			$('#ThirdClassID').remove();
		gift.GetSpPublicList();
	},
	//运营平台(设置礼包),指定分类下的道具列表
	GetSpPublicList:function(data){
		if(data!=''){
			$('#SpList').html(data);
			evt.BindSpListClick();
		}
		else
			$('#SpList').html('该分类下暂无道具');
	},
	//运营平台(设置礼包),添加礼包
	AddGiftPackage:function(data){
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js	
		$('#ResultMsg').html(data.Msg);
		if(data.iResult==0 && $('#SpID').val()==0){
			$('#GoodsName').val('');
			$('#SpNumber').val('');
			$('#ResourceID').val(0);
			$('#ImgPath_FileUpload').val('');		
			$('#GiftSp').html('');		
			$('#Intro').val('');
			$('input[name="Place"]').each(function(){ 
				$(this).attr('checked','');
			});
		}
	},
	//运营平台(设置礼包),删除礼包
	DelGiftPackage:function(data){
		if(data==0){
		   	window.location.reload();
		}else{
		   main.MsgBox(data);
		}
	},
	//角色信息管理，比赛查询——加载查询条件
	GetGameSearchMode:function(data){
		$('#GameSearchModeDetail').html(data);
	},
	//角色信息管理，比赛查询——比赛汇总
	GetGameSummaryDetail:function(data){
		$('#GameSearchModeDetailList').html(data);
		init.SetTableRows();//初始化鼠标移到表格行显示背景

		//绑定分页
		var Param = '$(this).attr("pg"),"callback.GetGameSummaryDetail"';
		var Param1 = '$(this).val(),"callback.GetGameSummaryDetail"';
		evt.BindPageClick('#GameSummaryPage ','page.GetGameSummaryDetail',Param,Param1);
	},
	//角色信息管理，比赛查询——加载查询结果
	GetGameSearchResultList:function(data){
		$('#GameSearchModeDetailList').html(data);
		init.SetTableRows();//初始化鼠标移到表格行显示背景
		//M.BindEvent();//绑定列表页事件		
		//绑定分页
		var Param = '$(this).attr("pg"),"callback.GetGameSearchResultList"';
		var Param1 = '$(this).val(),"callback.GetGameSearchResultList"';
		evt.BindPageClick('','page.GetGameSearchResultList',Param,Param1);
	},
	//修改操作——解除锁定，处理财富，冻结财富，
	GetEditOperationResult:function(data){
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js
		//if(data.ShowType == 1){
			$('#ModifyDetail').html(data.Result);
		//}else{
		//	$("#errorMsg").html(data.Result);
			if(data.Status == 1){
				if(data.From == 'punishRole'){
					$("#PunishRole").attr('disabled',true);
					$("#userInfoBlockStatus").html('封号');
					$("#BlockRoleStatus").html('已封号');
					$("#UnPunishRole").attr('disabled',false);
					return;
				}	
				if(data.From == 'lockRole'){
					$("#LockRole").attr('disabled',true);
					$("#userInfoLockStatus").html('锁定');
					$("#LockRoleStatus").html('锁定');
					$("#UnLockRole").attr('disabled',false);
				}			
			} 
		//}
	},
	//修改操作——账户检查
	GetShowDelPlayer:function(data){
		data=$.evalJSON(data);
		$('#ModifyDetail').html(data.Result);
	},
	GetDelPlayer:function(data){
		data=$.evalJSON(data);
		if(data.Result==0)
			$('#ModifyDetail').html('该玩家账号已经成功删除');
		else if(data.Result==-2)
			$('#ModifyDetail').html('请在22:00到22:10分再进行此操作');
		else if(data.Result==-3)
			$('#ModifyDetail').html('该玩家金币数不为零,无法进行此操作.');
		else
			$('#ModifyDetail').html('该玩家账号删除失败');
	},
	//修改操作——积分检查
	GetShowAddHappyBean:function(data){
		data=$.evalJSON(data);
		$('#ModifyDetail').html(data.Result);
	},
	GetAddHappyBean:function(data){
		data=$.evalJSON(data);
		if(data.Result==0)
			$('#ModifyDetail').html('该操作执行成功');
		else if(data.Result==-2)
			$('#ModifyDetail').html('请在22:00到22:10分再进行此操作');
		else if(data.Result==-3)
			$('#ModifyDetail').html('该玩家金币数不为零,无法进行此操作.');
		else
			$('#ModifyDetail').html('该操作执行失败');
	},
	//修改操作——设置/取消IP段锁定控制
	GetSetIpLocked:function(data){
		data=$.evalJSON(data);
		if(data.iResult == 0){
			if(data.TitleID==1){
				$('#ModifyDetail').html('操作成功,该玩家已取消IP段控制功能');
				$('#LimitIP').val('设置IP段控制');
			}
			else{
				$('#ModifyDetail').html('操作成功,该玩家已设置为IP段控制功能');
				$('#LimitIP').val('取消IP段控制');
			}
		} 
		else
			$('#ModifyDetail').html('操作失败,请重试');
	},
	//编辑操作——锁定/解锁记录
	GetLockDetail:function(data){
		$('#EditOperateDetail').html(data);
		init.SetTableRows();//初始化鼠标移到表格行显示背景		
		//绑定分页
		var Param = '$(this).attr("pg"),"callback.GetLockDetail"';
		var Param1 = '$(this).val(),"callback.GetLockDetail"';
		evt.BindPageClick('#EditOperatePages ','page.GetLockDetailPageInfo',Param,Param1);
	},
	//修改操作——解除卡房
	GetShowInGameResult:function(data){
		data=$.evalJSON(data);
		$('#ModifyDetail').html(data.Result);		
	},
	//修改操作——解除卡房
	UpdateInGame:function(data){

		if(data==0) {
            $('#errorMsg').html('操作成功');
            alert("操作成功");
        }
		else {
            $('#errorMsg').html('操作失败');
            alert("操作失败");
        }
	},
	//修改操作——通告通行证注册手机
	UpdateMobilePhone:function(data){
		if(data==0)
			$('#errorMsg').html('操作成功');
		else
			$('#errorMsg').html('操作失败');
	},
	//编辑操作——封号/解封记录
	GetBlockDetail:function(data){
		$('#EditOperateDetail').html(data);
		init.SetTableRows();//初始化鼠标移到表格行显示背景		
		//绑定分页
		var Param = '$(this).attr("pg"),"callback.GetBlockDetail"';
		var Param1 = '$(this).val(),"callback.GetBlockDetail"';
		evt.BindPageClick('#EditOperatePages ','page.GetBlockDetailPageInfo',Param,Param1);
	},
	/* //编辑操作——解锁记录
	GetUnLockDetailInfo:function(data){
		$('#EditOperateDetailInfo').html(data);
		init.SetTableRows();//初始化鼠标移到表格行显示背景		
		//绑定分页
		var Param = '$(this).attr("pg"),"callback.GetUnLockDetailInfo"';
		var Param1 = '$(this).val(),"callback.GetUnLockDetailInfo"';
		evt.BindPageClick('#EditOperatePages ','page.GetUnLockDetailInfo',Param,Param1);
	},
	//编辑操作——锁定角色记录
	GetLockDetailInfo:function(data){
		$('#EditOperateDetailInfo').html(data);
		init.SetTableRows();//初始化鼠标移到表格行显示背景		
		//绑定分页
		var Param = '$(this).attr("pg"),"callback.GetLockDetailInfo"';
		var Param1 = '$(this).val(),"callback.GetLockDetailInfo"';
		evt.BindPageClick('#EditOperatePages ','page.GetLockDetailInfo',Param,Param1);
	}, */
	//角色信息管理，编辑操作——获取更新信息结果
	GetUpdateEditOperateResult:function(data){
		if(!data.result){
		//	data=$.evalJSON(data);//字符串格式转为json对象,extend.js
		//	$("#"+data.showId).html(data.remarks);
			alert("更新成功！");
		}else{
			alert("更新失败！");
		}
	},
	/* //编辑操作——显示解封记录
	GetUnBlockDetailInfo:function(data){
		$('#EditOperateBlockDetailInfo').html(data);
		init.SetTableRows();//初始化鼠标移到表格行显示背景		
		//绑定分页
		var Param = '$(this).attr("pg"),"callback.GetUnBlockDetailInfo"';
		var Param1 = '$(this).val(),"callback.GetUnBlockDetailInfo"';
		evt.BindPageClick('#EditOperatePages ','page.GetUnBlockDetailInfo',Param,Param1);
	},
	//编辑操作——显示封号记录
	GetBlockDetailInfo:function(data){
		$('#EditOperateBlockDetailInfo').html(data);
		init.SetTableRows();//初始化鼠标移到表格行显示背景		
		//绑定分页
		var Param = '$(this).attr("pg"),"callback.GetBlockDetailInfo"';
		var Param1 = '$(this).val(),"callback.GetBlockDetailInfo"';
		evt.BindPageClick('#EditOperatePages ','page.GetBlockDetailInfo',Param,Param1);
	}, */
	//编辑操作——显示冻结财富记录
	GetFreezeTrasureDetailInfo:function(data){
		$('#EditOperateDetail').html(data);
		init.SetTableRows();//初始化鼠标移到表格行显示背景		
		//绑定分页
		var Param = '$(this).attr("pg"),"callback.GetFreezeTrasureDetailInfo"';
		var Param1 = '$(this).val(),"callback.GetFreezeTrasureDetailInfo"';
		evt.BindPageClick('#EditOperatePages ','page.GetFreezeTrasureDetailInfo',Param,Param1);
	},
	//编辑操作——显示银行、背包申请解冻记录
	GetBankKnapsackUnFreezeDetailInfo:function(data){
		$('#EditOperateDetail').html(data);
		init.SetTableRows();//初始化鼠标移到表格行显示背景		
		//绑定分页
		var Param = '$(this).attr("pg"),"callback.GetBankKnapsackUnFreezeDetailInfo"';
		var Param1 = '$(this).val(),"callback.GetBankKnapsackUnFreezeDetailInfo"';
		evt.BindPageClick('#EditOperatePages ','page.GetBankKnapsackUnFreezeDetailInfo',Param,Param1);
	},
	//编辑操作——显示其他操作记录
	GetOtherEditOperateDetailInfo:function(data){
		$('#EditOperateDetail').html(data);
		init.SetTableRows();//初始化鼠标移到表格行显示背景		
		//绑定分页
		var Param = '$(this).attr("pg"),"callback.GetOtherEditOperateDetailInfo"';
		var Param1 = '$(this).val(),"callback.GetOtherEditOperateDetailInfo"';
		evt.BindPageClick('#EditOperatePages ','page.GetOtherEditOperateDetailInfo',Param,Param1);
    },
    //获取房间机器人
    GetRoomRobotInfoList:function(data){
        $('#PageList').html(data);
        init.SetTableRows();
        //绑定分页
        var Param= '$(this).attr("pg"),"callback.GetRoomRobotInfoList"';
        var Param1 = '$(this).val(),"callback.GetOtherEditOperateDetailInfo"';
        evt.BindPageClick('','page.GetPage',Param,Param1);
    },
    //激活房间操作--
    ActiveRoomRobot:function(data){
        main.MsgBox(data);
    },
    //获取超级用户
    GetSuperUserList:function(data){
        $('#SuperUserList').html(data);
        init.SetTableRows();
        //绑定分页
        var Param= '$(this).attr("pg"),"callback.GetSuperUserList"';
        var Param1 = '$(this).val(),"callback.GetSuperUserList"';
        evt.BindPageClick('#EditOperatePages ','page.GetPagerSuperUser',Param,Param1);
    },
    //添加超级用户回调
    AddSuperUser:function(data){
        main.MsgBox(data);
        /*data = $.evalJSON(data);
         if(data.iResult == 1){
         $('#tb_addApply input[type=\"text\"]').val('');
         $('#loginName').html('');
         $('#txt_remark').val('');
         $('#errorMsg').html(data.msg);
         }
         $('#errorMsg').html(data.msg);*/
    },
    DelSuperUser:function(data){
        main.OpenBox(data);
    },
    //批量添加机器人回调
    AddMulRobot:function(data){
        main.MsgBox(data);
    },
    ShowAddMulRobotHtml:function(data){
        main.OpenBox(data);
    },
    GetRoomUserOnline:function(data){
        $('#UserOnlineList').html(data);
        init.SetTableRows();
        //绑定分页
        var Param= '$(this).attr("pg"),"callback.GetRoomUserOnline"';
        var Param1 = '$(this).val(),"callback.GetRoomUserOnline"';
        evt.BindPageClick('','page.GetRoomUserOnline',Param,Param1);
    },
    GetRealCard:function(data){
        $('#RealCardList').html(data);
        init.SetTableRows();
        //绑定分页
        var Param= '$(this).attr("pg"),"callback.GetRealCard"';
        var Param1 = '$(this).val(),"callback.GetRealCard"';
        evt.BindPageClick('','page.GetRealCard',Param,Param1);
    },
    GetServiceRealCard:function(data){
        $('#RealCardList').html(data);
        init.SetTableRows();
        //绑定分页
        var Param= '$(this).attr("pg"),"callback.GetServiceRealCard"';
        var Param1 = '$(this).val(),"callback.GetServiceRealCard"';
        evt.BindPageClick('','page.GetServiceRealCard',Param,Param1);
    },
    GetAndroidVersion:function(data){
        $('#GameHallList').html(data);
        init.SetTableRows();
        androidVersion.BindEvent();//绑定编辑事件
        //绑定分页
        var Param= '$(this).attr("pg"),"callback.GetAndroidVersion"';
        var Param1 = '$(this).val(),"callback.GetAndroidVersion"';
        evt.BindPageClick('','page.GetAndroidVersion',Param,Param1);
    },
    AddAndroidVersion:function(data){
        $('#ResultMsg').html(data);
    },
    GetPagerWechatUser:function(data){//alert(data);
        $('#ServerList').html(data);
        init.SetTableRows();//初始化鼠标移到表格行显示背景
        vxuser.BindEvent();//初始化绑定事件(GameRoomPage.html)
        //绑定分页
        var Param = '$(this).attr("pg"),"callback.GetPagerWechatUser"';
        var Param1 = '$(this).val(),"callback.GetPagerWechatUser"';
        evt.BindPageClick('','page.GetPagerWechatUser',Param,Param1);
    },

    ShowAddWeChatUserHtml:function(data){
		console.log(12345);
        main.MsgBox(data);
        window.parent.vxuser.BindEvent();
    },
    AddWeChatUser:function(data){
        switch(data){
            case '-1':
                $('#ResultMsg').html('操作失败');
                break;
            case '-9999':
                $('#ResultMsg').html('您提交的参数异常');
                break;
            default:
                $('#ResultMsg').html('操作成功');
                break;
        }
        $('#TypeID').val(1);
        $('#weixinname').val('');
        $('#noticetip').val('');
    },

};
//分页
var page={

    //获取安卓差量版本；
    GetAndroidVersion:function(curPage,Callback){
        setting.Params='curPage='+curPage;
        ajax.RequestUrl(setting.PageUrl,setting.Params,Callback);
    },
    //Service
    GetServiceRealCard:function(curPage,Callback){
        setting.Params='curPage='+curPage+'&CardNo='+$('#CardNo').val()+'&RoleID='+$('#RoleID').val();
        ajax.RequestUrl(setting.PageUrl,setting.Params,Callback);
    },
    //Yunwei获取实卡分页
    GetRealCard:function(curPage,Callback){
        setting.Params='curPage='+curPage+'&state='+$('#RealCardStatus').val();
        ajax.RequestUrl(setting.PageUrl,setting.Params,Callback);
    },
    //获取房间在线玩家列表
    GetRoomUserOnline:function(curPage,Callback){
        setting.Params='curPage='+curPage+'&RoomID='+$('#UserOnlineList').attr("data-roomid");
        ajax.RequestUrl(setting.PageUrl,setting.Params,Callback);
    },
    //获取商人
    GetPagerSuperUser:function(curPage,Callback){
        setting.Params='curPage='+curPage+'&RoleID='+$('#RoleID').val();
        ajax.RequestUrl(setting.PageUrl,setting.Params,Callback);
    },
	//运维平台(游戏配置),游戏等级分页
	GetGameKindLevel:function(LevelType,curPage,Callback){
		setting.Params='LevelType='+LevelType+'&curPage='+curPage+'&KindID='+$('#KindID').html();
		ajax.RequestUrl(setting.PageUrl,setting.Params,Callback);
	},
	//运维平台(游戏配置),游戏房间分页
	GetPagerRoom:function(curPage,Callback){
		setting.Params='curPage='+curPage+'&KindID='+$('#KindID').val()+'&ServerID='+$('#ServerID').val()+'&RoomName='+encodeURIComponent($('#RoomName').val());
		ajax.RequestUrl(setting.PageUrl,setting.Params,Callback);
	},
	//运维平台(游戏配置),游戏服务器分页
	GetPagerServer:function(curPage,Callback){
		setting.Params='curPage='+curPage+'&ServID='+$('#ServID').val();
		ajax.RequestUrl(setting.PageUrl,setting.Params,Callback);
	},
	//运维平台（服务器配置），游戏盾分页
	GetPagerGameDun:function(curPage,Callback){
		setting.Params='curPage='+curPage;
		ajax.RequestUrl(setting.PageUrl,setting.Params,Callback);
	},
	//运维平台(游戏配置),游戏服务器分页
	GetPagerServerGame:function(curPage,Callback){
		setting.Params='curPage='+curPage;
		ajax.RequestUrl(setting.PageUrl,setting.Params,Callback);
	},

	//运维平台(机器人管理),机器人信息配置分页
	GetPagerRobotName:function(curPage,Callback){
		setting.Params='curPage='+curPage;
		ajax.RequestUrl(setting.PageUrl,setting.Params,Callback);
	},
	//运维平台(机器人管理),机器人账号配置分页
	GetPagerRobotUser:function(curPage,Callback){
		setting.Params='curPage='+curPage;
		ajax.RequestUrl(setting.PageUrl,setting.Params,Callback);
	},
		//运维平台(机器人管理),房间机器人配置分页
	GetPagerRoomRobot:function(curPage,Callback){
		setting.Params='curPage='+curPage;
		ajax.RequestUrl(setting.PageUrl,setting.Params,Callback);
	},
	//运维平台(道具事件配置),道具分页
	GetPagerSp:function(curPage,Callback){
		setting.Params='curPage='+curPage;
		ajax.RequestUrl(setting.PageUrl,setting.Params,Callback);
	},
	//运维平台(道具事件配置),事件分页
	GetPagerEvent:function(curPage,Callback){
		setting.Params='curPage='+curPage;
		ajax.RequestUrl(setting.PageUrl,setting.Params,Callback);
	},
	//运维平台(广告管理),广告位分页
	GetPagerAdPos:function(curPage,Callback){
		setting.Params='curPage='+curPage;
		ajax.RequestUrl(setting.PageUrl,setting.Params,Callback);
	},
	//运维平台(新闻分类管理),新闻分类分页
	GetPagerNewsCategory:function(curPage,Callback){
		setting.Params='curPage='+curPage;
		ajax.RequestUrl(setting.PageUrl,setting.Params,Callback);
	},
	//运维平台(广告管理),广告位分页
	GetPagerNews:function(curPage,Callback){
		setting.Params='curPage='+curPage;
		ajax.RequestUrl(setting.PageUrl,setting.Params,Callback);
	},
	//运维平台(广告管理),广告分页
	GetPagerAd:function(curPage,Callback){
		setting.Params='curPage='+curPage;
		ajax.RequestUrl(setting.PageUrl,setting.Params,Callback);
	},
    //运维平台(走马灯管理),分页
    GetPagerMsg:function(curPage,Callback){
        setting.Params='curPage='+curPage;
        ajax.RequestUrl(setting.PageUrl,setting.Params,Callback);
    },
	//运维平台(游戏配置),大厅版本分页
	GetPagerGameHall:function(curPage,Callback){
		setting.Params='curPage='+curPage;
		ajax.RequestUrl(setting.PageUrl,setting.Params,Callback);
	},
    //运维平台(游戏配置),安卓版本分页
    GetPagerAndroidHall:function(curPage,Callback){
        setting.Params='curPage='+curPage;
        ajax.RequestUrl(setting.PageUrl,setting.Params,Callback);
    },
	//运维平台(游戏配置),道具类别分页
	GetPagerSpClass:function(curPage,Callback){
		setting.Params='curPage='+curPage;
		ajax.RequestUrl(setting.PageUrl,setting.Params,Callback);
	},	
	//运维平台(系统配置),角色等级分页
	GetPagerLevel:function(curPage,Callback){
		setting.Params='curPage='+curPage;
		ajax.RequestUrl(setting.PageUrl,setting.Params,Callback);
	},
	//运维平台(系统配置),注册敏感词分页
	GetPagerSysConfine:function(curPage,Callback){
		setting.Params='curPage='+curPage;
		ajax.RequestUrl(setting.PageUrl,setting.Params,Callback);
	},
	//客服平台(角色信息),角色列表
	 GetPagerRole:function(curPage,Callback){
		var loginID = $("#LoginID").val();
		var safePhone = $("#SafePhone").val();
		var loginCode = $("#LoginCode").val();
		var CardNumber = $("#CardNumber").val();
		var LoginName = $("#LoginName").val();
		var LastLoginIP = $("#LastLoginIP").val();
		var QQ = $("#QQ").val();
		var MachineSerial = $("#MachineSerial").val();
		
		var Param = '';
		if(loginID)
			Param += "LoginID="+loginID;		
		if(safePhone.length == 11 && safePhone.match(/\d+/g))
			Param += "&SafePhone="+safePhone;		
		if(loginCode)
			Param += "&LoginCode="+loginCode;
		if(CardNumber)
			Param += "&CardNumber="+CardNumber;
		if(LoginName)
			Param += "&LoginName="+LoginName;
		if(LastLoginIP)
			Param += "&LastLoginIP="+LastLoginIP;
		if(QQ)
			Param += "&QQ="+QQ;
		if(MachineSerial)
			Param += "&MachineSerial="+MachineSerial;
			
		Param +='&curPage='+curPage;
		ajax.RequestUrl(setting.PageUrl, Param, Callback);
	}, 
	//客服平台（角色信息），VIP开通明细
	GetPagerVipDetail:function(curPage,Callback){
		setting.PageUrl = "/?d=Service&c=ServiceRole&a=showVipDetail";
		setting.Params='curPage='+curPage+'&RoleID='+$('#vipRoleID').val();
		ajax.RequestUrl(setting.PageUrl,setting.Params,Callback);
	},
	//客服平台（角色信息），日工资明细
	GetPagerSalaryDetail:function(curPage,Callback){
		//setting.Params='curPage='+curPage+'&RoleID='+$('#CurRoleID').val()+'&StartTime='+$('#StartTime').val()+'&EndTime='+$('#EndTime').val()+'&VipOpenTime='+encodeURIComponent($('#VipOpenTime').val());
		setting.Params='curPage='+curPage+'&RoleID='+$('#CurRoleID').val()+'&StartTime='+$('#EndTime').val()+'&EndTime='+$('#EndTime').val()+'&VipOpenTime='+encodeURIComponent($('#VipOpenTime').val());
		ajax.RequestUrl(setting.PageUrl,setting.Params,Callback);
	},
	//客服平台（角色信息），礼包明细
	GetPageMonthGiftDetail:function(curPage,Callback){
		setting.Params='curPage='+curPage+'&RoleID='+$('#CurRoleID').val()+'&StartTime='+$('#StartTime').val()+'&EndTime='+$('#EndTime').val();
		ajax.RequestUrl(setting.PageUrl,setting.Params,Callback);
	},
	//客服平台,角色信息->游戏资料分页列表
	GetPagerUserGameData:function(curPage,Callback){
		setting.Params = 'curPage='+curPage+'&Room=generic&RoleID='+RoleID;
		ajax.RequestUrl(setting.PageUrl,setting.Params,Callback);
	},
	//客服平台,角色信息->游戏资料->成绩明细分页列表
	GetPagerUserGameDataDetail:function(curPage,LogsID,DateTime,Callback){
		//setting.Params = 'curPage='+curPage+'&LogsID='+LogsID+'&RoleID='+$('#RoleID').val()+'&KindID='+$('#KindID').val()+'&StartTime='+$('#StartTime').val()+'&EndTime='+DateTime+'&RoomType='+$('#RoomType').val();
		setting.Params = 'curPage='+curPage+'&LogsID='+LogsID+'&RoleID='+$('#RoleID').val()+'&KindID='+$('#KindID').val()+'&StartTime='+DateTime+'&EndTime='+DateTime+'&RoomType='+$('#RoomType').val();
		ajax.RequestUrl(setting.PageUrl,setting.Params,Callback);
	},
	//客服平台,角色信息->游戏资料->游戏记录分页列表
	GetPagerUserGameDetail:function(curPage,LogsID,DateTime,Callback){
		//setting.Params = 'curPage='+curPage+'&LogsID='+LogsID+'&RoleID='+$('#RoleID').val()+'&KindID='+$('#KindID').val()+'&StartTime='+$('#StartTime').val()+'&EndTime='+DateTime+'&PlayResult='+$('#PlayResult').val()+'&RoomType='+$('#RoomType').val();
		setting.Params = 'curPage='+curPage+'&LogsID='+LogsID+'&RoleID='+$('#RoleID').val()+'&KindID='+$('#KindID').val()+'&StartTime='+DateTime+'&EndTime='+DateTime+'&PlayResult='+$('#PlayResult').val()+'&RoomType='+$('#RoomType').val()+'&Hour='+$('#Hour').val()+'&Minute='+$('#Minute').val();
		ajax.RequestUrl(setting.PageUrl,setting.Params,Callback);
	},	
	//客服平台,角色信息->游戏资料->寻宝乐园成绩明细分页列表
	GetPagerUserGameDataSp:function(curPage,LogsID,DateTime,Callback){
		setting.Params = 'curPage='+curPage+'&LogsID='+LogsID+'&RoleID='+$('#RoleID').val()+'&KindID='+$('#KindList').val()+'&StartTime='+$('#StartTime').val()+'&EndTime='+DateTime;
		ajax.RequestUrl(setting.PageUrl,setting.Params,Callback);
	},
	//客服平台,角色信息->游戏资料->寻宝乐园游戏记录分页列表
	GetUserGameDataSpDetailPage:function(curPage,Callback){
		setting.PageUrl = "/?d=Service&c=ServiceRole&a=getUserGameDataSpDetailPage";
		setting.Params = 'curPage='+curPage+"&RoleID="+$("#spRole1").val()+"&KindName="+$("#spKindName1").val()+"&KindID="+$("#spKindID1").val()+"&dTime="+$("#spDtime1").val();;
		ajax.RequestUrl(setting.PageUrl,setting.Params,Callback);
	},
	//客服平台，角色信息->银行资料->转账记录分页列表
	GetPageUserTransferRecords:function(curPage,LogsID,DateTime,Callback){
		setting.PageUrl = "/?d=Service&c=ServiceRole&a=getPageUserTransferRecords";
		var searchType = $.trim($("#SearchType").val());
		var transType = (searchType == 2?$.trim($("#TransType_2").val()):$.trim($("#TransType_1").val()));
		//setting.Params = 'curPage='+curPage+'&LogsID='+LogsID+'&RoleID='+$('#RoleID').val()+'&Stime='+$('#registertimeFrom1').val()+'&Etime='+DateTime+"&DCFlag="+$("#DCFlag").val()+"&SearchType="+searchType+"&TransType="+transType;
		setting.Params = 'curPage='+curPage+'&LogsID='+LogsID+'&RoleID='+$('#RoleID').val()+'&Stime='+DateTime+'&Etime='+DateTime+"&DCFlag="+$("#DCFlag").val()+"&SearchType="+searchType+"&TransType="+transType;
		ajax.RequestUrl(setting.PageUrl,setting.Params,Callback);
	},
	//客服平台,角色信息->充值记录分页列表
	GetPagerUserRechargeRecords:function(curPage,Callback){
		$('#ErrMsg').html('');
		var RechargeStartTime=$('#RechargeStartTime').val();
		var RechargeEndTime=$('#RechargeEndTime').val();
		if(RechargeStartTime=='' || RechargeEndTime=='' || RechargeStartTime>RechargeEndTime){
			$('#ErrMsg').html('请选择正确的日期范围');
			return false;
		}
		//setting.Params = 'curPage='+curPage+'&RoleID='+RoleID+'&RechargeStartTime='+RechargeStartTime+'&RechargeEndTime='+RechargeEndTime+'&RechargeType='+$('#RechargeType').val()+'&RechargeStatus='+$('#RechargeStatus').val()+'&OrderSerial='+$('#OrderSerial').val();
		setting.Params = 'curPage='+curPage+'&RoleID='+RoleID+'&RechargeStartTime='+RechargeEndTime+'&RechargeEndTime='+RechargeEndTime+'&RechargeType='+$('#RechargeType').val()+'&RechargeStatus='+$('#RechargeStatus').val()+'&OrderSerial='+$('#OrderSerial').val();
		ajax.RequestUrl("/?d=Service&c=ServiceRole&a=getPagerUserRechargeRecords",setting.Params,Callback);
	},
	//客服平台,角色信息->道具资料->背包,衣柜,使用中的道具分页
	GetPagerMySpInfo:function(curPage,Callback){
		setting.Params = 'curPage='+curPage+'&RoleID='+RoleID;
		ajax.RequestUrl(setting.PageUrl,setting.Params,Callback);
	},
	//客服平台,角色信息->道具资料->背包日志记录分页列表
	GetPagerMyKnapsackLogs:function(curPage,LogsID,DateTime,Callback){
		$('#LogMsg').html('');
		var LogStartTime = $('#LogStartTime').val();
		if(LogStartTime=='' || DateTime=='' || LogStartTime>DateTime){
			$('#LogMsg').html('请选择正确的日期范围');
			return false;
		}
		setting.Params = 'curPage='+curPage+'&LogsID='+LogsID+'&RoleID='+RoleID+'&LogsStartTime='+LogStartTime+'&LogsEndTime='+DateTime;
		ajax.RequestUrl(setting.PageUrl,setting.Params,Callback);
	},
	//客服平台,角色信息->角色日志->财富冻结日志记录分页列表
	GetPagerTreasureFreezeLogs:function(curPage,Callback){
		$('#LogsMsg').html('');
		var LogsStartTime = $('#LogsStartTime').val();
		var LogsEndTime = $('#LogsEndTime').val();
		if(LogsStartTime=='' || LogsEndTime=='' || LogsStartTime>LogsEndTime){
			$('#LogsMsg').html('请选择正确的日期范围');
			return false;
		}
		//setting.Params = 'curPage='+curPage+'&RoleID='+RoleID+'&OpStep='+$('#OpStep').val()+'&CaseSerial='+$('#txtCaseSerial').val()+'&LogsStartTime='+LogsStartTime+'&LogsEndTime='+LogsEndTime;
		setting.Params = 'curPage='+curPage+'&RoleID='+RoleID+'&OpStep='+$('#OpStep').val()+'&CaseSerial='+$('#txtCaseSerial').val()+'&LogsStartTime='+LogsEndTime+'&LogsEndTime='+LogsEndTime;
		ajax.RequestUrl("/?d=Service&c=ServiceRole&a=getTreasureFreezeLogs",setting.Params,Callback);
	},
	//客服平台,角色信息->角色日志->锁号/封号日志记录分页列表
	GetPagerLockAccountLogs:function(curPage,Callback){
		$('#LogsMsg').html('');
		var LogsStartTime = $('#LogsStartTime').val();
		var LogsEndTime = $('#LogsEndTime').val();
		if(LogsStartTime=='' || LogsEndTime=='' || LogsStartTime>LogsEndTime){
			$('#LogsMsg').html('请选择正确的日期范围');
			return false;
		} 
		//setting.Params = 'curPage='+curPage+'&RoleID='+RoleID+'&OpResult='+$('#OpResult').val()+'&CaseSerial='+$('#txtCaseSerial').val()+'&LogsStartTime='+LogsStartTime+'&LogsEndTime='+LogsEndTime;
		setting.Params = 'curPage='+curPage+'&RoleID='+RoleID+'&OpResult='+$('#OpResult').val()+'&CaseSerial='+$('#txtCaseSerial').val()+'&LogsStartTime='+LogsEndTime+'&LogsEndTime='+LogsEndTime;
		ajax.RequestUrl("/?d=Service&c=ServiceRole&a=getLockAccountLogs",setting.Params,Callback);
	},
	//客服平台,角色信息->角色日志->操作日志记录分页列表
	GetPagerPlayerLogsDetail:function(curPage,LogsID,DateTime,Callback){
		$('#LogsMsg').html('');
		var LogsStartTime = $('#LogsStartTime').val();		
		if(LogsStartTime=='' || DateTime=='' || LogsStartTime>DateTime){
			$('#LogsMsg').html('请选择正确的日期范围');
			return false;
		}
		//setting.Params = 'curPage='+curPage+'&LogsID='+LogsID+'&RoleID='+RoleID+'&LogsStartTime='+LogsStartTime+'&LogsEndTime='+DateTime+'&OpTypeID='+$('#OpTypeID').val();
		setting.Params = 'curPage='+curPage+'&LogsID='+LogsID+'&RoleID='+RoleID+'&LogsStartTime='+DateTime+'&LogsEndTime='+DateTime+'&OpTypeID='+$('#OpTypeID').val();
		ajax.RequestUrl(setting.PageUrl,setting.Params,Callback);
	},
	//运营平台(设置礼包),礼包分页
	GetPagerGiftPackage:function(curPage,Callback){
		setting.Params='curPage='+curPage;
		ajax.RequestUrl(setting.PageUrl,setting.Params,Callback);
	},
	//角色信息管理，比赛查询——比赛汇总
	GetGameSummaryDetail:function(curPage,Callback){
		setting.Params='curPage='+curPage+'&RoleID='+$('#matchRoleID').val();
		ajax.RequestUrl('/?d=Service&c=ServiceRole&a=getGameSummaryDetail',setting.Params,Callback);	
	},
	//角色信息管理，比赛查询——加载查询信息
	GetGameSearchResultList:function(curPage,Callback){
		setting.Params='curPage='+curPage+'&RoleID='+roleID+'&StartTime='+StartTime+'&EndTime='+EndTime+'&MatchTypeID='+MatchTypeID+'&RoomID='+RoomID;
		ajax.RequestUrl('/?d=Service&c=ServiceRole&a=getGameSearchModeResultList',setting.Params,Callback);	
	},
	//角色信息管理，编辑操作——锁定/解锁信息
	GetLockDetailPageInfo:function(curPage,Callback){
		setting.Params='curPage='+curPage+"&roleID="+$("#EditOperateRoleID").val();
		ajax.RequestUrl('/?d=Service&c=ServiceRole&a=showEditLockInfo',setting.Params,Callback);
	},
	//角色信息管理，编辑操作——封号/解封信息
	GetBlockDetailPageInfo:function(curPage,Callback){
		setting.Params='curPage='+curPage+"&roleID="+$("#EditOperateRoleID").val();;
		ajax.RequestUrl('/?d=Service&c=ServiceRole&a=showEditBlockInfo',setting.Params,Callback);
	},
	/* //角色信息管理，编辑操作——解锁信息
	GetUnLockDetailInfo:function(curPage,Callback){
		setting.Params='curPage='+curPage+"&roleID="+$("#EditOperateRoleID").val();;
		ajax.RequestUrl('/?d=Service&c=ServiceRole&a=EditOperateUnLockInfo',setting.Params,Callback);
	},
	//角色信息管理，编辑操作——锁定信息
	GetLockDetailInfo:function(curPage,Callback){
		setting.Params='curPage='+curPage+"&roleID="+$("#EditOperateRoleID").val();;
		ajax.RequestUrl('/?d=Service&c=ServiceRole&a=EditOperateLockInfo',setting.Params,Callback);
	},
	//角色信息管理，编辑操作——解封信息
	GetUnBlockDetailInfo:function(curPage,Callback){
		setting.Params='curPage='+curPage+"&roleID="+$("#EditOperateRoleID").val();;
		ajax.RequestUrl('/?d=Service&c=ServiceRole&a=EditOperateUnBlockInfo',setting.Params,Callback);
	},
	//角色信息管理，编辑操作——封号信息
	GetBlockDetailInfo:function(curPage,Callback){
		setting.Params='curPage='+curPage+"&roleID="+$("#EditOperateRoleID").val();;
		ajax.RequestUrl('/?d=Service&c=ServiceRole&a=EditOperateBlockInfo',setting.Params,Callback);
	}, */
	//角色信息管理，编辑操作——冻结财富记录
	GetFreezeTrasureDetailInfo:function(curPage,Callback){
		setting.Params='curPage='+curPage+"&roleID="+$("#EditOperateRoleID").val();
		ajax.RequestUrl('/?d=Service&c=ServiceRole&a=FreezeTreasureInfo',setting.Params,Callback);
	},
	//角色信息管理，编辑操作——银行、背包申请解冻记录
	GetBankKnapsackUnFreezeDetailInfo:function(curPage,Callback){
		setting.Params='curPage='+curPage+"&roleID="+$("#EditOperateRoleID").val();;
		ajax.RequestUrl('/?d=Service&c=ServiceRole&a=showBankKnapsackUnFreezeInfo',setting.Params,Callback);
	},
	//角色信息管理，编辑操作——其他操作记录
	GetOtherEditOperateDetailInfo:function(curPage,Callback){
		setting.Params='curPage='+curPage+"&roleID="+$("#EditOperateRoleID").val();
		ajax.RequestUrl('/?d=Service&c=ServiceRole&a=showOtherEditOperateInfo',setting.Params,Callback);
	},
	//复杂公共分页(首页,上一页,下一页,尾页)
	GetPage:function(CurPage,Callback){		
		ajax.RequestUrl(setting.PageUrl,'curPage='+CurPage+setting.Params,Callback);
	}
};
var sys={
	//运维平台(服务器配置),添加服务器
	AddServer:function(ClassName,ServID){
		$('#AddServerMsg').html('');
		$('#ServerIP').next().html('*');
		$('#ServerPort').next().html('*');
		$('#LoginName').next().html('*');
		$('#LoginPwd').next().html('*');
		$('#AppName').next().html('*');
		var ServerName = $.trim($('#ServerName').val());
		var ServerIP = $.trim($('#ServerIP').val());
		var LANServerIP = $.trim($('#LANServerIP').val());
		var ServerPort = $.trim($('#ServerPort').val());
		var Intro = $.trim($('#Intro').val());		
		var LoginName = $.trim($('#LoginName').val());
		var LoginPwd = $.trim($('#LoginPwd').val());
		var AppName = $.trim($('#AppName').val());
		var ServerID = $.trim($('#ServerID').val());
		var ServPort = $.trim($('#ServPort').val());
		//var ProxyServerIP = $.trim($('#ProxyServerIP').val());
		//var ProxyServerPort = $.trim($('#ProxyServerPort').val());
		if(isNaN(ServerID) || ServerID==''){
			$('#AddServerMsg').html('对不起,您提交的数据异常');
			return false;
		}
		if(isNaN(ServerPort) || ServerPort==''){
			$('#ServerPort').next().html('*请输入正确的游戏服务器端口');
			$('#ServerPort').focus();
			return false;
		}	
		if(isNaN(ServPort) || ServPort==''){
			$('#ServPort').next().html('*请输入正确的游戏服务器端口');
			$('#ServPort').focus();
			return false;
		}
		//如果是设置数据库服务器,则以下几项必填
		if(ClassName=='ServerDB'){
			if(LANServerIP==''){
				$('#LANServerIP').next().html('*请输入服务器内网IP地址');
				$('#LANServerIP').focus();
				return false;
			}	
			if(LoginName==''){
				$('#LoginName').next().html('*请输入数据库登陆名');
				$('#LoginName').focus();
				return false;
			}
			if(LoginPwd==''){
				$('#LoginPwd').next().html('*请输入数据库登陆密码');
				$('#LoginPwd').focus();
				return false;
			}
			if(AppName==''){
				$('#AppName').next().html('*请输入数据库名称');
				$('#AppName').focus();
				return false;
			}
		}		
		else{
			if(ServerIP==''){
				$('#ServerIP').next().html('*请输入服务器外网IP地址,格式:127.0.0.1:80');
				$('#ServerIP').focus();
				return false;
			}
		}
		if(ClassName=='ServerRoom'){
			ServID = $.trim($('#GameDunList').val());
		}
		//如果是设置数据库服务器,则以下几项必填
		/*if(ClassName=='ServerHall'){
			if(ProxyServerIP==''){
				$('#ProxyServerIP').next().html('*请输入服务器代理地址');
				$('#ProxyServerIP').focus();
				return false;
			}	
			if(ProxyServerPort==''){
				$('#ProxyServerPort').next().html('*请输入服务器代理端口');
				$('#ProxyServerPort').focus();
				return false;
			}			
		}	*/
		ServerIP = ServerIP.replace(/[\r\n]/g,',').replace(/[,]+/g,',');
		setting.Params = 'ServerName='+encodeURIComponent(ServerName)+'&ServerIP='+ServerIP+'&LANServerIP='+LANServerIP+'&ServerPort='+ServerPort+'&Intro='+encodeURIComponent(Intro)+'&LoginName='+encodeURIComponent(LoginName)+'&LoginPwd='+encodeURIComponent(LoginPwd)+'&AppName='+encodeURIComponent(AppName)+'&ServerID='+ServerID+'&ServPort='+ServPort+'&ServID='+ServID;//+'&ProxyServerIP='+ProxyServerIP+'&ProxyServerPort='+ProxyServerPort;
		ajax.Request(setting.Url,setting.Params,'callback.AddServer');
	},	
		EditServer:function(ClassName,ServID){
		$('#AddServerMsg').html('');
		$('#ServerIP').next().html('*');
		var ServerIP = $.trim($('#ServerIP').val());
		var ServerID = $.trim($('#ServerID').val());
		var PreServerIP = $.trim($('#PreServerIP').val());
		//var ProxyServerIP = $.trim($('#ProxyServerIP').val());
		//var ProxyServerPort = $.trim($('#ProxyServerPort').val());
		if(ServerID==''){
			$('#AddServerMsg').html('对不起,您提交的数据异常');
			return false;
		}

		//如果是设置数据库服务器,则以下几项必填
		if(ClassName=='ServerDB'){
		}		
		else{
			if(ServerIP==''){
				$('#ServerIP').next().html('*请输入服务器外网IP地址,格式:127.0.0.1:80');
				$('#ServerIP').focus();
				return false;
			}
			if(PreServerIP==''){
				$('#PreServerIP').html('*请输入服务器外网IP地址,格式:127.0.0.1:80');
				$('#PreServerIP').focus();
				return false;
			}
		}
		ServerIP = ServerIP.replace(/[\r\n]/g,',').replace(/[,]+/g,',');
		setting.Params = 'ServerIP='+ServerIP+'&PreServerIP='+PreServerIP+'&ServerID='+ServerID;//+'&ProxyServerIP='+ProxyServerIP+'&ProxyServerPort='+ProxyServerPort;
		ajax.Request(setting.Url,setting.Params,'callback.EditServer');
	},	
	AddWebServer:function(){
		$('#AddServerMsg').html('');
		$('#ServerIP').next().html('*');
		var ServerName = $.trim($('#ServerName').val());
		var ServerIP = $.trim($('#ServerIP').val());
		var Intro = $.trim($('#Intro').val());		
		var ServerType = $.trim($('#ServerType').val());
		var ServerID = $.trim($('#ServerID').val());			
		if(isNaN(ServerID) || ServerID=='' || isNaN(ServerType) || ServerType==''){
			$('#AddServerMsg').html('对不起,您提交的数据异常');
			return false;
		}
		if(ServerIP==''){
			$('#ServerIP').next().html('*请输入服务器外网IP地址,格式:127.0.0.1:80');
			$('#ServerIP').focus();
			return false;
		}
		
		ServerIP = ServerIP.replace(/[\r\n]/g,',').replace(/[,]+/g,',');
		setting.Params = 'ServerName='+encodeURIComponent(ServerName)+'&ServerIP='+ServerIP+'&Intro='+encodeURIComponent(Intro)+'&ServerType='+ServerType+'&ServerID='+ServerID;
		ajax.Request(setting.Url,setting.Params,'callback.AddWebServer');
	},
	AddBankServer:function(){
		$('#AddServerMsg').html('');
		$('#ServerIP').next().html('*');
		var ServerName = $.trim($('#ServerName').val());
		var ServerIP = $.trim($('#ServerIP').val());
		var Intro = $.trim($('#Intro').val());		
		var ServerType = $.trim($('#ServerType').val());
		var ServerID = $.trim($('#ServerID').val());			
		if(isNaN(ServerID) || ServerID=='' || isNaN(ServerType) || ServerType==''){
			$('#AddServerMsg').html('对不起,您提交的数据异常');
			return false;
		}
		if(ServerIP==''){
			$('#ServerIP').next().html('*请输入服务器外网IP地址,格式:127.0.0.1:80');
			$('#ServerIP').focus();
			return false;
		}
		
		ServerIP = ServerIP.replace(/[\r\n]/g,',').replace(/[,]+/g,',');
		setting.Params = 'ServerName='+encodeURIComponent(ServerName)+'&ServerIP='+ServerIP+'&Intro='+encodeURIComponent(Intro)+'&ServerType='+ServerType+'&ServerID='+ServerID;
		ajax.Request(setting.Url,setting.Params,'callback.AddWebServer');
	},
	//运维平台(游戏配置),添加游戏种类
	AddGameKind:function(){
		$('#AddGameKindMsg').html('');
		var KindName = $.trim($('#KindName').val());
		var KindID = $.trim($('#KindID').val());
		var ProcessName = $.trim($('#ProcessName').val());
		var ServerDLL = $.trim($('#ServerDLL').val());
		var ClassID = $.trim($('#ClassID').val());		
		var CustomField = $.trim($('#CustomField').val());
		var PayTypeID = 0;
		var SysBank =  $.trim($('#SysBank1').val());
		var RobotBank =  $.trim($('#RobotBank').val());
		$('input[name="PayTypeID"]:checked').each(function(){ 
			PayTypeID += parseInt($(this).val()); 
		}); 
		if(KindName==''){
			$('#KindName').next().html('*请输入游戏种类名称');
			$('#KindName').focus();
			return false;
		}
		if(isNaN(KindID) || KindID==''){
			$('#KindID').next().html('*请输入正确的游戏种类ID');
			$('#KindID').focus();
			return false;
		}
		if(ProcessName==''){
			$('#ProcessName').next().html('*请输入进程名称');
			$('#ProcessName').focus();
			return false;
		}
		if(ServerDLL==''){
			$('#ServerDLL').next().html('*请输入服务端动态库文件名');
			$('#ServerDLL').focus();
			return false;
		}
		setting.Params = 'KindName='+encodeURIComponent(KindName)+'&KindID='+KindID+'&ProcessName='+ProcessName+'&ServerDLL='+ServerDLL+'&ClassID='+ClassID+'&CustomField='+encodeURIComponent(CustomField)+'&PayTypeID='+PayTypeID+'&SysBank='+SysBank+'&RobotBank='+RobotBank;
		ajax.Request(setting.Url,setting.Params,'callback.AddGameKind');
	},
	//运维平台(游戏配置),添加游戏级别
	AddGameLevel:function(){
		$('#ResultMsg').html('');
		var ID = $('#ID').val();
		var LevelType = $('#LevelType').val();
		var LevelID = $('#LevelID').val();
		var LevelName = $.trim($('#LevelName').val());
		var LBound = $('#LBound').val();
		var CellAmount = $('#CellAmount').val();
		var KindID = $('#KindID').html();
		var ClothesImage = $('#ClothesImage').val();
		if(LevelType==0){
			$('#ResultMsg').html('请选择级别类型');
			return false;
		}
		if(LevelID==0){
			$('#ResultMsg').html('请选择级别等级');
			return false;
		}
		if(LevelName==0){
			$('#ResultMsg').html('请输入级别名称');
			return false;
		}
		if(isNaN(LBound) || LBound==''){
			$('#ResultMsg').html('请输入级别对应有效下限额');
			return false;
		}
		if(isNaN(CellAmount) || CellAmount==''){
			$('#ResultMsg').html('请输入有效对局额');
			return false;
		}
		if(isNaN(ClothesImage ) || ClothesImage ==''){
			$('#ResultMsg').html('请输入有效衣服图片编号');
			return false;
		}
		if(isNaN(KindID) || KindID=='' || isNaN(ID) || ID==''){
			$('#ResultMsg').html('您提交的数据异常,请退出重试');
			return false;
		}
		setting.Params = 'LevelType='+LevelType+'&LevelID='+LevelID+'&LevelName='+encodeURIComponent(LevelName)+'&LBound='+LBound+'&CellAmount='+CellAmount+'&KindID='+KindID+'&ID='+ID+'&ClothesImage='+ClothesImage ;
		ajax.RequestUrl(setting.Url,setting.Params,'callback.AddGameLevel');
	},
	//运维平台(游戏配置),添加版本
	AddGameVersion:function(){
		var KindID = $('#KindID').val();
		var VerID= $('#VerID').val();
		var FileName = $.trim($('#FileName').val());
		var FileURL = $.trim($('#FileURL').val());
		var FileCategory = $('#FileCategory').val();
		var ServerID = $('#ServerID').val();
		var Version = $('#GameVersion').val();
		var LocalPath = $('#LocalPath').val();
		if(isNaN(KindID) || KindID=='' || isNaN(VerID) || VerID==''){
			$('#ResultMsg').html('您提交的数据异常,请重试');
			return false;
		}
		if(FileName==''){
			$('#ResultMsg').html('请输入文件名');
			return false;
		}
		if(FileURL==''){
			$('#ResultMsg').html('请输入下载路径');
			return false;
		}
		else{
			var First = FileURL.substring(0,1);
			var Last = FileURL.substring(FileURL.length-1,FileURL.length);
			if(First.replace('\\','/')!='/' || Last.replace('\\','/')!='/'){
				$('#ResultMsg').html('请输入正确的下载路径,格式如:/Games/Folder/');
				return false;	
			}
		}
		if(isNaN(FileCategory) || FileCategory==''){
			$('#ResultMsg').html('请选择安装类型');
			return false;
		}
		if(isNaN(ServerID) || ServerID==''){
			$('#ResultMsg').html('请选择下载服务器地址');
			return false;
		}
		if(Version==''){
			$('#ResultMsg').html('请输入版本号1');
			return false;
		}		
		setting.Params='KindID='+KindID+'&FileName='+encodeURIComponent(FileName)+'&FileURL='+FileURL+'&FileCategory='+FileCategory+'&ServerID='+ServerID+'&Version='+encodeURIComponent(Version)+'&VerID='+VerID+'&LocalPath='+LocalPath;		
		ajax.RequestUrl(setting.Url,setting.Params,'callback.AddGameVersion');
	},
	//运维平台(游戏配置),添加大厅版本
	AddGameHallVersion:function(){
		var FileName = '';
		var FileURL = ''
		var FileCategory = '';
		var ServerID = '';
		var Version = '';
		var LocalPath = '';
		$('.FileName').each(function(){
			var TmpFileName = $(this).val();
			if(TmpFileName==''){
				$('#ResultMsg').html('请输入文件名');
				return false;
			}
			FileName += TmpFileName + ',';
		});
		$('.FileURL').each(function(){
			var TmpFileURL = $(this).val();
			if(TmpFileURL==''){
				$('#ResultMsg').html('请输入下载路径');
				return false;
			}	
			else{
				var First = TmpFileURL.substring(0,1);
				var Last = TmpFileURL.substring(TmpFileURL.length-1,TmpFileURL.length);
				if(First.replace('\\','/')!='/' || Last.replace('\\','/')!='/'){
					$('#ResultMsg').html('请输入正确的下载路径,格式如:/Games/Folder/');
					return false;	
				}
			}
			FileURL += TmpFileURL + ',';
		});
		$('.FileCategory').each(function(){
			var TmpFileCategory = $(this).val();
			if(isNaN(TmpFileCategory) || TmpFileCategory==''){
				$('#ResultMsg').html('请选择安装类型');
				return false;
			}
			FileCategory += TmpFileCategory + ',';
		});
		$('.ServerID').each(function(){
			var TmpServerID = $(this).val();
			if(isNaN(TmpServerID) || TmpServerID==''){
				$('#ResultMsg').html('请选择下载服务器地址');
				return false;
			}
			ServerID += TmpServerID + ',';
		});
		$('.GameVersion').each(function(){
			var TmpVersion = $(this).val();
			if(TmpVersion==''){
				$('#ResultMsg').html('请输入版本号1');
				return false;
			}	
			Version += TmpVersion + ',';
		});
		$('.LocalPath').each(function(){
			var TmpLocalPath = $(this).val();
			LocalPath += TmpLocalPath + ',';
		});
		
		var KindID = $('#KindID').val();
		var VerID= $('#VerID').val();		
		if(isNaN(KindID) || KindID=='' || isNaN(VerID) || VerID==''){
			$('#ResultMsg').html('您提交的数据异常,请重试11');
			return false;
		}			
		setting.Params='KindID='+KindID+'&FileName='+encodeURIComponent(FileName)+'&FileURL='+FileURL+'&FileCategory='+FileCategory+'&ServerID='+ServerID+'&Version='+encodeURIComponent(Version)+'&VerID='+VerID+'&LocalPath='+LocalPath;		
		ajax.RequestUrl(setting.Url,setting.Params,'callback.AddGameVersion');
	},
    //运维平台(游戏配置),添加大厅版本
    AddAndroidHallVersion:function(){
        var FileName = '';
        var FileURL = ''
        var FileCategory = '';
        var ServerID = '';
        var Version = '';
        var LocalPath = '';
        $('.FileName').each(function(){
            var TmpFileName = $(this).val();
            if(TmpFileName==''){
                $('#ResultMsg').html('请输入文件名');
                return false;
            }
            FileName += TmpFileName + ',';
        });
        $('.FileURL').each(function(){
            var TmpFileURL = $(this).val();
            if(TmpFileURL==''){
                $('#ResultMsg').html('请输入下载路径');
                return false;
            }
            else{
                var First = TmpFileURL.substring(0,1);
                var Last = TmpFileURL.substring(TmpFileURL.length-1,TmpFileURL.length);
                if(First.replace('\\','/')!='/' || Last.replace('\\','/')!='/'){
                    $('#ResultMsg').html('请输入正确的下载路径,格式如:/Games/Folder/');
                    return false;
                }
            }
            FileURL += TmpFileURL + ',';
        });
        $('.FileCategory').each(function(){
            var TmpFileCategory = $(this).val();
            if(isNaN(TmpFileCategory) || TmpFileCategory==''){
                $('#ResultMsg').html('请选择安装类型');
                return false;
            }
            FileCategory += TmpFileCategory + ',';
        });
        $('.ServerID').each(function(){
            var TmpServerID = $(this).val();
            if(isNaN(TmpServerID) || TmpServerID==''){
                $('#ResultMsg').html('请选择下载服务器地址');
                return false;
            }
            ServerID += TmpServerID + ',';
        });
        $('.GameVersion').each(function(){
            var TmpVersion = $(this).val();
            if(TmpVersion==''){
                $('#ResultMsg').html('请输入版本号1');
                return false;
            }
            Version += TmpVersion + ',';
        });
        $('.LocalPath').each(function(){
            var TmpLocalPath = $(this).val();
            LocalPath += TmpLocalPath + ',';
        });

        var KindID = $('#KindID').val();
        var VerID= $('#VerID').val();
        if(isNaN(KindID) || KindID=='' || isNaN(VerID) || VerID==''){
            $('#ResultMsg').html('您提交的数据异常,请重试11');
            return false;
        }
        setting.Params='KindID='+KindID+'&FileName='+encodeURIComponent(FileName)+'&FileURL='+FileURL+'&FileCategory='+FileCategory+'&ServerID='+ServerID+'&Version='+encodeURIComponent(Version)+'&VerID='+VerID+'&LocalPath='+LocalPath;
        ajax.RequestUrl(setting.Url,setting.Params,'callback.AddGameVersion');
    },
    //运维平台(游戏配置),安卓差量文件
    AddAndroidVersion:function(){
        var FileName = '';
        var FileURL = ''
        var FileCategory = '';
        var ServerID = '';
        var LowVersion = '';
        var HighVersion = '';
        var LocalPath = '';
        $('.FileName').each(function(){
            var TmpFileName = $(this).val();
            if(TmpFileName==''){
                $('#ResultMsg').html('请输入文件名');
                return false;
            }
            FileName += TmpFileName + ',';
        });
        $('.FileURL').each(function(){
            var TmpFileURL = $(this).val();
            if(TmpFileURL==''){
                $('#ResultMsg').html('请输入下载路径');
                return false;
            }
            else{
                var First = TmpFileURL.substring(0,1);
                var Last = TmpFileURL.substring(TmpFileURL.length-1,TmpFileURL.length);
                if(First.replace('\\','/')!='/' || Last.replace('\\','/')!='/'){
                    $('#ResultMsg').html('请输入正确的下载路径,格式如:/Games/Folder/');
                    return false;
                }
            }
            FileURL += TmpFileURL + ',';
        });
        /*$('.FileCategory').each(function(){
            var TmpFileCategory = $(this).val();
            if(isNaN(TmpFileCategory) || TmpFileCategory==''){
                $('#ResultMsg').html('请选择安装类型');
                return false;
            }
            FileCategory += TmpFileCategory + ',';
        });*/
        $('.ServerID').each(function(){
            var TmpServerID = $(this).val();
            if(isNaN(TmpServerID) || TmpServerID==''){
                $('#ResultMsg').html('请选择下载服务器地址');
                return false;
            }
            ServerID += TmpServerID + ',';
        });
        $('.LowVersion').each(function(){
            var TmpVersion = $(this).val();
            if(TmpVersion==''){
                $('#ResultMsg').html('请输入版本号1');
                return false;
            }
            LowVersion += TmpVersion + ',';
        });
        $('.HighVersion').each(function(){
            var TmpVersion = $(this).val();
            if(TmpVersion==''){
                $('#ResultMsg').html('请输入版本号1');
                return false;
            }
            HighVersion += TmpVersion + ',';
        });
        /*$('.GameVersion').each(function(){
            var TmpVersion = $(this).val();
            if(TmpVersion==''){
                $('#ResultMsg').html('请输入版本号1');
                return false;
            }
            Version += TmpVersion + ',';
        });*/
        $('.LocalPath').each(function(){
            var TmpLocalPath = $(this).val();
            LocalPath += TmpLocalPath + ',';
        });

        //var KindID = $('#KindID').val();
        var VerID= $('#VerID').val();
        if( isNaN(VerID) || VerID==''){
            $('#ResultMsg').html('您提交的数据异常,请重试11');
            return false;
        }
        setting.Params='VerID='+VerID+'&FileName='+encodeURIComponent(FileName)+'&FileURL='+FileURL+'&ServerID='+ServerID+'&LowVersion='+encodeURIComponent(LowVersion)+'&HighVersion='+encodeURIComponent(HighVersion)+'&VerID='+VerID+'&LocalPath='+LocalPath;
        ajax.RequestUrl(setting.Url,setting.Params,'callback.AddAndroidVersion');
    },
	/////////////////////////////////
	//运维平台(游戏配置),添加手机端版本
	AddGameMobileVersion:function(){
		var FileName = '';
		var FileURL = ''
		var FileCategory = '';
		var ServerID = '';
		var Version = '';
		var LocalPath = '';
		FileName = $('#FileName').val();
		if(FileName==''){
			$('#ResultMsg').html('请输入文件名');
			return false;
		}
		FileURL = $('#FileURL').val();
		if(FileURL==''){
			$('#ResultMsg').html('请输入下载路径');
			return false;
		}	
		else{
			var First = FileURL.substring(0,1);
			var Last = FileURL.substring(FileURL.length-1,FileURL.length);
			if(First.replace('\\','/')!='/' || Last.replace('\\','/')!='/'){
				$('#ResultMsg').html('请输入正确的下载路径,格式如:/Games/Folder/');
				return false;	
			}
		}			
		FileCategory = $('#FileCategory').val();
		if(isNaN(FileCategory) || FileCategory==''){
			$('#ResultMsg').html('请选择安装类型');
			return false;
		}			
		ServerID = $('#ServerID').val();
		if(isNaN(ServerID) || ServerID==''){
			$('#ResultMsg').html('请选择下载服务器地址');
			return false;
		}
		Version = $('#GameVersion').val();
		if(Version==''){
			$('#ResultMsg').html('请输入版本号');
			return false;
		}	
		LocalPath = $('#LocalPath').val();
		var KindID = $('#KindID').val();
		var VerID= $('#VerID').val();		
		if(isNaN(KindID) || KindID=='' || isNaN(VerID) || VerID==''){
			$('#ResultMsg').html('您提交的数据异常,请重试');
			return false;
		}			
		setting.Params='KindID='+KindID+'&FileName='+encodeURIComponent(FileName)+'&FileURL='+FileURL+'&FileCategory='+FileCategory+'&ServerID='+ServerID+'&Version='+encodeURIComponent(Version)+'&VerID='+VerID+'&LocalPath='+LocalPath;		
		ajax.RequestUrl(setting.Url,setting.Params,'callback.AddGameVersion');
	},
	//运维平台(游戏配置),添加版本
	AddGameTable:function(hrefUrl){
		var TableSchemeID = $('#TableSchemeID').val();
		var SchemeName = $.trim($('#SchemeName').val());
		var TableID = $.trim($('#TableID').val());
		var LockBkID = $.trim($('#LockBkID').val());
		var GestureID = $('#GestureID').val();
		var RunButtonID = $('#RunButtonID').val();
		var TableDataID = $('#TableDataID').val();
		var ChairID = $('#ChairID').val();
		var pattern = /^\d+$/;
		
		if(!TableSchemeID){
			$('#TableSchemeID').next().html('*请输入桌子规格ID');
			$('#TableSchemeID').focus();
			return false;
		}
		if(SchemeName==''){
			$('#SchemeName').next().html('*请输入桌子名称');
			$('#SchemeName').focus();
			return false;
		}
		if(TableID=='' || !pattern.test(TableID)){			
			$('#TableID').next().html('*请正确输入数字格式的桌子ID');
			$('#TableID').focus();
			return false;
		}
		if(LockBkID=='' || !pattern.test(LockBkID)){
			$('#LockBkID').next().html('*请正确输入数字格式的桌子锁定图片ID');
			$('#LockBkID').focus();
			return false;
		}
		if(GestureID=='' || !pattern.test(GestureID)){
			$('#GestureID').next().html('*请正确输入数字格式的玩家手势ID');
			$('#GestureID').focus();
			return false;
		}
		if(RunButtonID=='' || !pattern.test(RunButtonID)){
			$('#RunButtonID').next().html('*请正确输入数字格式的启动按钮ID');
			$('#RunButtonID').focus();
			return false;
		}
		if(TableDataID=='' || !pattern.test(TableDataID)){
			$('#TableDataID').next().html('*请正确输入数字格式的桌子数据文件ID');
			$('#TableDataID').focus();
			return false;
		}	
		if(ChairID=='' || !pattern.test(ChairID)){
			$('#ChairID').next().html('*请正确输入数字格式的椅子ID');
			$('#ChairID').focus();
			return false;
		}	
		setting.Params="TableSchemeID="+TableSchemeID+'&SchemeName='+encodeURIComponent(SchemeName)+'&TableID='+TableID+'&LockBkID='+LockBkID+'&GestureID='+GestureID+'&RunButtonID='+RunButtonID+'&TableDataID='+TableDataID+'&ChairID='+ChairID;		
		ajax.RequestUrl(hrefUrl,setting.Params,'callback.AddGameTable');
	},
	//运维平台(游戏配置),添加版本
	AddGameTask:function(hrefUrl){
		var TaskID= $('#TaskSortID').val();
		var KindID = $.trim($('#KindID').val());
		var RoomType= $.trim($('#RoomType').val());
		var GameCount = $.trim($('#GameCount').val());
		var AwardMoney = $('#AwardMoney').val();
		if(TaskID==''||TaskID==0){
			$('#TaskSortID').next().html('*请输入正确的任务ID');
			$('#TaskSortID').focus();
			return false;
		}
		if(KindID==''){
			$('#KindID').next().html('*请选择游戏种类');
			$('#KindID').focus();
			return false;
		}
		if(RoomType==''){			
			$('#RoomType').next().html('*请选择房间类型');
			$('#RoomType').focus();
			return false;
		}
		if(GameCount=='' ){
			$('#GameCount').next().html('*请正确输入数字格式的游戏局数');
			$('#GameCount').focus();
			return false;
		}
		if(AwardMoney=='' ){
			$('#AwardMoney').next().html('*请正确输入数字格式的奖励金币数');
			$('#AwardMoney').focus();
			return false;
		}
		setting.Params="TaskID="+TaskID+'&KindID='+KindID+'&RoomType='+RoomType+'&GameCount='+GameCount+'&AwardMoney='+AwardMoney;
		ajax.RequestUrl(hrefUrl,setting.Params,'callback.AddGameTask');
	},
	//运维平台(游戏配置),添加签到设置
	AddGameSign:function(hrefUrl){
		var KindID = $.trim($('#KindID').val());
		var RoomType= $.trim($('#RoomType').val());
		var SignType = $.trim($('#SignType').val());
		var SignValue = $.trim($('#SignValue').val());
		var SignAward = $('#SignAward').val();
		var PhoneExtra = $.trim($('#PhoneExtra').val());
		if(KindID==''){
			$('#KindID').next().html('*请选择游戏种类');
			$('#KindID').focus();
			return false;
		}
		if(RoomType==''){			
			$('#RoomType').next().html('*请选择房间类型');
			$('#RoomType').focus();
			return false;
		}
		if(SignType=='' ){
			$('#SignType').next().html('*请选择签到类型');
			$('#SignType').focus();
			return false;
		}
		if(SignValue=='' ){
			$('#SignValue').next().html('*请正确输入要求游戏值');
			$('#SignValue').focus();
			return false;
		}
		if(SignAward=='' ){
			$('#SignAward').next().html('*请正确输入奖励金币数');
			$('#SignAward').focus();
			return false;
		}
		if(PhoneExtra=='' ){
			$('#PhoneExtra').next().html('*请正确输入手机端额外奖励值');
			$('#PhoneExtra').focus();
			return false;
		}
		setting.Params='KindID='+KindID+'&RoomType='+RoomType+'&SignType='+SignType+'&SignValue='+SignValue+'&SignAward='+SignAward+'&PhoneExtra='+PhoneExtra;
		ajax.RequestUrl(hrefUrl,setting.Params,'callback.AddGameTask');
	},
};

//ajax请求
var ajax={
	//JSON格式,RequestHttp:请求地址, UrlParams:参数, Callback:回调函数
	Request:function(RequestHttp,UrlParams,CallbackFunction){
		$.ajax({
			type: "POST",			
			url: RequestHttp,
			data: UrlParams,
			dataType:"json",
			cache:false,			
			success: function(data){
				data=$.toJSON(data);//json对象转为字符串格式,extend.js
				data = data.replace(/[\']/g,"\\'").replace(/[\"]/g,'\\"');				
				eval(CallbackFunction+'(\''+data+'\')');
			}
		});	
	},
	//默认格式,RequestHttp:请求地址, UrlParams:参数, Callback:回调函数
	RequestUrl:function(RequestHttp,UrlParams,CallbackFunction){
		$.ajax({
			type: "POST",			
			url: RequestHttp,
			data: UrlParams,
			cache:false,			
			success: function(data){
				data = data.replace(/[\']/g,"\\'");
				//alert(CallbackFunction+'(\''+data+'\')');
				eval(CallbackFunction+'(\''+data+'\')');
			}
		});
	},
	//默认格式,RequestHttp:请求地址, UrlParams:参数, Callback:回调函数
	RequestCallBack:function(RequestHttp,UrlParams,CallbackFunction){
		$.ajax({
			type: "POST",			
			url: RequestHttp,
			data: UrlParams,
			cache:false,			
			success: function(data){
				if(CallbackFunction){
					CallbackFunction(data);
				}
			}
		});	
	},
	//默认格式,RequestHttp:请求地址, UrlParams:参数, Callback:回调函数
	RequestJsonCallBack:function(RequestHttp,UrlParams,CallbackFunction){
		$.ajax({
			type: "POST",			
			url: RequestHttp,
			data: UrlParams,
			cache:false,	
			dataType:"json",
			success: function(data){
				if(CallbackFunction){
					CallbackFunction(data);
				}
			}
		});	
	},
	//请求数据显示
	RequestData:function(RequestHttp,UrlParams,targetID,flag){
		$.ajax({
			type: "POST",			
			url: RequestHttp,
			data: UrlParams,
			cache:false,			
			success: function(data){
				if(flag == 1)
					$(targetID).html(data);
				else if(flag == 2)
					$(targetID).val(data);
				else if(flag == 3)
					return '';
				else if(flag == 4)
					main.MsgBox(data);
				else
					$(targetID).wBox({drag:false, html:data}).showBox();				
			}
		});
	}
};

//设置参数
var setting={
	NodeName:'',//节点名称
	ObjID:'',	//对象ID
	Params:'',	//参数
	PageUrl:'',	//分页地址
	LoginUrl:'',//登陆地址
	DelUrl:'',	//删除游戏级别
	ReqUrl:'',
	NodeUrl:'',	//节点URL
	Url:'',
	PrevPage:1,	//上一页
	NextPage:1,	//下一页
	LastPage:1,	//尾页
	FunName:'',	//函数名称
	IsOver:false,//是否结束
	FileName:''  //文件名
};
//登陆
var login={
	CheckLogin:function(){
		var UserName = $.trim($('#UserName').val());
		var UserPwd = $.trim($('#UserPwd').val());
		var ChkCode = $.trim($('#ChkCode').val());
		var bindaccout =$.trim($('#bindaccout').val());
		if(UserName==''){
			//$('#UserName').next().html('请输入用户名');
			alert('请输入用户名');
			$('#UserName').focus();
			return false;
		}
		if(UserPwd==''){
			//$('#UserPwd').next().html('请输入用户密码');
			alert('请输入用户密码');
			$('#UserName').focus();
			return false;
		}
		if(bindaccout==''){
			//$('#msg').html('请输入验证码');
			alert('请输入绑定 手机或邮箱地址');
			$('#bindaccout').focus();
			return false;
		}
		if(ChkCode==''){
			//$('#msg').html('请输入验证码');
			alert('请输入验证码');
			$('#UserName').focus();
			return false;
		}
		
		setting.Params = 'UserName='+encodeURIComponent(UserName)+'&UserPwd='+UserPwd+'&ChkCode='+ChkCode+"&bindaccount="+ bindaccout;
		ajax.Request(setting.LoginUrl,setting.Params,'login.Callback');
	},
	Callback:function(data){
		data=$.evalJSON(data);//字符串格式转为json对象,extend.js
		$('.orange').html('');
		switch(data.iStatus){
			case -1:
			case -5:
			case -7:
				//$('#UserName').next().html(data.strMsg);
				alert(data.strMsg);
				$('#vCode').attr('src', "/Common/ChkCode.class.php?" + Math.random());
				break;
			case -2:
			case -6:
				//$('#UserPwd').next().html(data.strMsg);
				alert(data.strMsg);
				$('#vCode').attr('src', "/Common/ChkCode.class.php?" + Math.random());
				break;
			case -3:
			case -4:
				//$('#msg').html(data.strMsg);
				alert(data.strMsg);
				$('#vCode').attr('src', "/Common/ChkCode.class.php?" + Math.random());
				break;
			default:
				window.location.href=setting.Url;
				break;
		}
	}
};

//textarea控制输入字数，兼容ie6,7,8
var textarea_maxlen = {  
	isMax:function (area, areaLen){	
		var textarea = 	$("#"+area);	
		var max_length = textarea.attr("maxLength");	
		if(textarea.val().length > max_length){	  
			textarea.val(textarea.val().substring(0, max_length));	
		}
		$("#"+areaLen).html(textarea.val().length);
	},  
	disabledRightMouse:function (){	
		document.oncontextmenu = function (){ 
			return false; 
		}  
	},  
	enabledRightMouse:function (){	
		document.oncontextmenu = null;  
	}
};


//通用cookie操作
var cookieCommon={
    //设置
    c_set:function(c_name,value,expires,path,domain,secure){
        var d=new Date();
        if(expires!=null && expires>=0)  d.setTime(d.getTime() + ( expires * 1000 )); //expires 秒
        document.cookie=c_name+ "=" +escape(value)+((expires==null) ? "" : ";expires="+d.toUTCString())+((path == null) ? "" : ("; path=" + path))+((domain == null) ? "" : ("; domain=" + domain))+((secure == true) ? "; secure" : "");
        return true;
    },
    //获取
    c_get:function(c_name){
        if (document.cookie.length>0)
        {
            c_start=document.cookie.indexOf(c_name + "=");
            if (c_start!=-1)
            {
                c_start=c_start + c_name.length+1;
                c_end=document.cookie.indexOf(";",c_start);
                if (c_end==-1)
                    c_end=document.cookie.length;
                return unescape(document.cookie.substring(c_start,c_end));
            }
        }
        return null;
    },
    //删除
    c_del:function(name){
        var expi = new Date();
        expi.setTime(expi.getTime() - 1);
        var cval=this.c_get(name);
        if(cval!=null)
        {
            document.cookie= name + "="+cval+";expires="+expi.toGMTString();
            return true;
        }
        return false;
    }    
};

var textFun = {		
	//清除编辑器html格式的空格换行等
	Trim:function(str) {
	    var data = new String(str);
	    data = data.replace(/<img.*?>/ig, "img");
	    data = data.replace(/<embed.*?>/ig, "embed");
	    data = data.replace(/<.*?>/ig, "");
	    data = data.replace(/&nbsp;/ig, "");
	    data = data.replace(/\r\n|\n|\r/ig, "");
	    data = data.replace(/ /g, "");
	    return data;
	},
	//计数字符长度
	countLength:function(str) {
		var a = 0; //预期计数：中文2字节，英文1字节 
	    var i = 0; //循环计数 
	    var html = "";//提示信息
	    for (i=0;i<str.length;i++){
	       if (str.charCodeAt(i)>255){
	           a+=2; 
	        }
	        else{
	           a++; 
	        }
	    }
	    return a;
	}
}

//带时间的日期格式判断正则表达式
var dateRegx = /^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2})$/;//YYYY-MM-DD
var dateTimeRegx = /^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$/;//YYYY-MM-DD HH:mm:ss

