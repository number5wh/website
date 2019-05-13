<?php
header("Content-type: text/html; charset=utf-8");
require('MyAES.php');
class wt{
    function send_kumo($arr,$mishi,$url){
        ksort($arr);
        foreach($arr as $key=>$value)
        {
            $arrStr[]=$key.'='.$value;
        }
        $sign = $this->sign($arrStr,$mishi);
        $xml = "<xml>";
        foreach($arr as $key=>$v){
            $xml .= '<'.$key.'>'.$v.'</'.$key.'>';
        }
        $xml = $xml.'<sign>'.$sign.'</sign></xml>';
        $ReData = $this->requestData($xml,$url);

        return $ReData;
    }
//自律性，时间管理，
	function sign($arrStr,$mishi)
	{
		$str='';
		sort($arrStr);
		foreach($arrStr as $v) {
			$str .= $v.'&';
		}
		$str .= 'key='.$mishi;
		return strtoupper(md5($str));
	}

    function requestData($data,$url)
    {
        $MyAES = new MyAES();
        $jiaRes = $MyAES->desEncryptStr($data,"1102130405061708");
        $header[] = "Content-type: text/xml;charset=UTF-8";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jiaRes);
        $aa = curl_exec($ch);
        if(curl_errno($ch)){
            print curl_error($ch);
        }
        curl_close($ch);
        $resultData = $MyAES->DesDecryptStr($aa,"1102130405061708");
        return $resultData;
    }
}
?>