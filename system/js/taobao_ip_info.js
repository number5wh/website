/**
 * Created by Administrator on 2015/12/4.
 */

(function($){
    $('.ip-info').each(function(i,n){
        var ip = $(n).attr("data-ip");
        if( ip == undefined || ip == ""){
            return ;
        }
        function get_sina_ip_info(){
            $.getScript('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip='+ip, function(_result){  
                data = remote_ip_info;
                var str= "";
                if(data.ret != 1){
                    str= $(n).html()+"(ip不可查)";
                }else{
                     str =$(n).html()+ "("+data.province+data.city+data.district+data.isp+")";
                }
                $(n).html(str);
                $(n).removeClass("ip-info");
             });
        }
        function get_138_ip_info(){
                $.ajax({
                    url:"http://test.ip138.com/query/?ip="+$(n).attr("data-ip")+"&datatype=jsonp",
                    dataType:'jsonp',
                    success:function(data){
                        //var data = eval("("+da+")");
                        var str= "";
                        if(data.ret != "ok"){
                            str= $(n).html()+"(ip不可查)";
                        }else{
                            data = data.data;
                             str =$(n).html()+ "("+data[1]+data[2]+data[3]+")";
                        }
                        $(n).html(str);
                        $(n).removeClass("ip-info");
                    }
              });
        }
        function get_server_sina_ip_info(){
                $.ajax({
                    //url:'http://ip.taobao.com/service/getIpInfo.php?ip='+$(n).attr("data-ip"),
                    url:"index.php?d=Service&c=ServiceRole&a=taobao_ip_info&ip="+$(n).attr("data-ip"),
                    dataType:'json',
                    success:function(data){
                        //var data = eval("("+da+")");
                        var str= "";
                        if(data.ret != 1){
                            str= $(n).html()+"(ip不可查)";
                        }else{
                             str =$(n).html()+ "("+data.province+data.city+data.district+data.isp+")";
                        }

                        $(n).html(str);
                        $(n).removeClass("ip-info");
                    }
                });
        }
        get_server_sina_ip_info();
    })
})(jQuery);