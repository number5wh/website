/**
 * Created by Administrator on 2015/12/4.
 */
(function($){
    $('.tel-info').each(function(i,n){
        var tel = $(n).attr("data-tel");
        if( tel == undefined || tel == ""){
            return ;
        }
        $.ajax({
            //url:'http://ip.taobao.com/service/getIpInfo.php?ip='+$(n).attr("data-ip"),
            url:"index.php?d=Service&c=ServiceRole&a=taobao_tel_segment&tel="+$(n).attr("data-tel"),
            dataType:'json',
            success:function(data){
                //var data = eval("("+da+")");
                var str= "";
                if(data.ret != 1){
                    str= $(n).html()+"(手机不可查)";
                }else{
                     str =$(n).html()+ "("+data.province+','+data.carrier+")";
                }

                $(n).html(str);
                $(n).removeClass("ip-info");
            }
        })
    })
})(jQuery);