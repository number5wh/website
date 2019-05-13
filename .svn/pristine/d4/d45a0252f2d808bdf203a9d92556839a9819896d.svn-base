<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/5/4
 * Time: 14:14
 */


class HttpApi {
    public static function test(){
        self::curl_get("www.baidu.com");
    }
    public static function curl_post($url,$furl){
        $field = $furl;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $field);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_HEADER,0);
    
        $json = curl_exec($ch);

        $error = curl_errno($ch);
        if($error){
            return false;
        }else{
            return $json;
        }
    }
    public static function curl_get($url){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_HEADER,0);
        $ret = curl_exec($ch);
        $error = curl_error($ch);
        if($error){
            //MyLog::log(1,"Error","http get failed");
            return false;
        }else{
            return $ret;
        }
    }
}