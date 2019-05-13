/**
 * Created by Administrator on 2015/12/4.
 */
(function($){
    $('.idcard-info').each(function(i,n){
        var tel = $(n).attr("data-idcard");
        if( tel == undefined || tel == ""){
            return ;
        }
        $.ajax({
            //url:'http://ip.taobao.com/service/getIpInfo.php?ip='+$(n).attr("data-ip"),
            url:"index.php?d=Service&c=ServiceRole&a=card_segment&idcard="+$(n).attr("data-idcard"),
            dataType:'json',
            success:function(data){
                //var data = eval("("+da+")");
                var str= "";
                if(data){
                     str =$(n).html()+ "("+data.DQ+")";
                }else{
                    str= $(n).html()+"(身份证号不可查)";
                }

                $(n).html(str);
                $(n).removeClass("ip-info");
            }
        })
    })
})(jQuery);