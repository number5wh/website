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
	}	
};
var callback={
	SetRechargeDomain:function(data){
		if(data!='')
			$('.rechargeDomain').attr('href','http://'+data+'/recharge.php');
	},
	SetRegisterDomain:function(data){
		if(data!=''){
			$('.registerDomain').attr('href','http://'+data);
			if($('.registerDomain1').html()!=null)
				$('.registerDomain1').attr('href','http://'+data+'/?a=findPassword');
			if($('.registerDomain2').html()!=null)
				$('.registerDomain2').attr('href','http://'+data+'/?a=editPassword');
		}
	}
};
var ip={
	GetRechargeDomain:function(){
		var ip='115.238.187.233:8012,115.238.187.233:8012,115.238.187.233:8012';
		var ArrIP = ip.split(',');
		var rnd=Math.floor(Math.random()*ArrIP.length);
		callback.SetRechargeDomain(ArrIP[rnd]);
	},
	GetRegisterDomain:function(){
		var ip='115.238.187.233:8010';
		var ArrIP = ip.split(',');
		var rnd=Math.floor(Math.random()*ArrIP.length);
		callback.SetRegisterDomain(ArrIP[rnd]);
	},
	GetDownloadDomain:function(){
		var ip='115.238.246.169:80,115.238.246.169:80,115.238.246.169:80';
		var ArrIP = ip.split(',');
		var rnd=Math.floor(Math.random()*ArrIP.length);
		callback.SetDownloadDomain(ArrIP[rnd]);
	}
}

