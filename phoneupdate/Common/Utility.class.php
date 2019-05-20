<?php
/**
 * 工具类
 * @author xlj
 */
class Utility
{	
	/**
	 * 获取IP
	 */
	public static function getIP()
    {
        if(isset($_SERVER['HTTP_CLIENT_IP']))
        {
             return $_SERVER['HTTP_CLIENT_IP'];
        }
        else
        {
            return $_SERVER['REMOTE_ADDR'];
        }
    }
    
	/**
     * 对参数进行Null和空判断
     * @param string $key	要判断的key
     * @param string $obj	要判断的类型对象,$_GET,$_POST
     */
    public static function isNullOrEmpty($key,$obj)
    {
        if(isset($obj[$key]) && !empty($obj[$key]))
        {
            return $obj[$key];
        }
        return FALSE;
    }
    
	/**
     * 判断是否数字
     * @param string $key	要判断的key
     * @param string $obj	要判断的类型对象,$_GET,$_POST
     */
    public static function isNumeric($key,$obj)
    {
        if(isset($obj[$key]) && is_numeric($obj[$key]) && $obj[$key]!='')
        {
            return intval($obj[$key]);
        }
        return FALSE;
    }
    /**
     * 输出信息
     * @param unknown_type $msg
     */
	public static function output($msg)
	{
		echo($msg);
		exit();
	}
    /**
     * 解密
     * @param $string 解密字符串
     */
    public static function mcryptDecrypt($arrConfig,$string)
    {
        return base64_decode($string);
        /*
        $key=$arrConfig['EncryptKey'];
        $crypttexttb=Utility::safe_base64Decode($string);//对特殊字符解析
        $decryptedtb = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256,md5($key),base64_decode($crypttexttb),MCRYPT_MODE_CBC,md5(md5($key))),"\0");
        return $decryptedtb;
        */
    }

    public static function request($must_fields = null) {
        $params = (array)json_decode ( file_get_contents ( 'php://input' ) ,true);
        if (count($must_fields) > 0) {
            foreach($must_fields as $field){
                if(!isset($params[$field])){
                    self::response(-1,"param ".$field." is not set ");
                }
            }
        }
        return $params;
    }
    /**
     *  打包返回结果
     */
    public static function response($code, $message, $data = null, $defaultData = null) {
        $response = array('code' => $code, 'message' => $message);
        if ($data !== null) {
            if (is_object($data) || is_array($data)) {
                $response ['data'] = $data;
                $response ['data'] = json_encode($data);
            }
            else  {
                $response ['data'] = (string)$data;
            }
        } else {
            if($defaultData !== null){
                $response['data'] = json_encode($defaultData);
            }
        }

        // 返回结果
        echo json_encode ( $response ); exit;
    }
}
?>