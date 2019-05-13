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
        if(getenv('HTTP_CLIENT_IP')) { 
			$onlineip = getenv('HTTP_CLIENT_IP'); 
		} elseif(getenv('HTTP_X_FORWARDED_FOR')) { 
			$onlineip = getenv('HTTP_X_FORWARDED_FOR'); 
		} elseif(getenv('REMOTE_ADDR')) { 
			$onlineip = getenv('REMOTE_ADDR'); 
		} else { 
			$onlineip = $HTTP_SERVER_VARS['REMOTE_ADDR']; 
		} 
		return $onlineip;
    }
    
	/**
	 * 替换模板标签
	 */
	public static function assign($arrTags)
	{
		global $smarty;
		foreach($arrTags as $key => $value)
		{
			$smarty->assign($key,$value);
		}
	}
	/**
	 * 编码转换gb2312转utf8
	 */
	public static function gb2312ToUtf8($str)
	{
		if(!empty($str))
			return iconv('gb2312','utf-8//IGNORE',$str);
		else 
			return $str;
	}
	
	/**
	 * utf8转gb2312
	 * @param unknown_type $str
	 * @return string
	 */
	public static function utf8ToGb2312($str)
	{
		if(!empty($str))
			return iconv('utf-8','gb2312//IGNORE',$str);
		else 
			return $str;
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
     * 计算页面数
     */
    public static function pageCount($iRecordsCount,$iPageSize)
    {
    	return ceil($iRecordsCount/$iPageSize);
    }
	/**
	 * 输出信息
	 * @param $num
	 */
	public static function output($msg)
	{
		echo($msg);
		exit();
	} 
	/**
	 * 检查登陆
	 * @param $strTmpSelectKey
	 * @param $objTmpMemcache
	 
	public static function chkUserLogin($strTmpSelectKey,$objTmpMemcache)
	{
		$CFG = unserialize(SYS_CONFIG);		
    	$cookieName = $CFG['Cookies']['CookiesName'];
    	//判断cookie是否存在
    	if(isset($_COOKIE[$cookieName]) && !empty($_COOKIE[$cookieName]))
    	{
    		$arrCookie = explode('_',$_COOKIE[$cookieName]);
    		if(is_array($arrCookie) && count($arrCookie)>0)
    		{
    			//从缓存里读取sessionid
	    		$strTmpSelectKey .= md5($arrCookie[0]) . 'UserLoginSession';	    		
				$sid = $objTmpMemcache->get($strTmpSelectKey);
				if($sid)
				{
					//验证memecahce里的值跟cookie里的值是事一致
					if($arrCookie[0].'_'.$sid != $_COOKIE[$cookieName])
						Utility::output('身份验证失败,请重新登录.');	
					else 
					{	//验证成功,返回RoleID
						$objSessioin = new Session($CFG['Session']['SessionLoginName'],$sid);
						$iRoleID = $objSessioin->get($CFG['SessionInfo']['RoleID']);
						return $iRoleID;
					}
				}
				else 
					Utility::output('身份验证失败,请重新登录.');
    		}
    		else 
    			Utility::output('身份验证失败,请重新登录.');
    	}
    	else 
    		Utility::output('身份验证失败,请重新登录.');
	} */
	/**
	 * 检查登陆
	 * @param $strTmpSelectKey
	 * @param $objTmpMemcache
	 */
	public static function chkUserLogin($arrParams,$times=0)
	{		
		$CFG = unserialize(SYS_CONFIG);
		$iNum = count($CFG['DcConfig']);
		if($times<$iNum)
		{			
			$arr=array($CFG['DcConfig'][$times]['HOST'], $CFG['DcConfig'][$times]['PORT'],$arrParams['RoleID'],Utility::getIP(),$arrParams['Auth'],'');
			$com = new COM("PHPItfc.PHPInterface") or die("无法建立COM组件");
			$res = $com->DcAuth($arr);
			if($res=='SUCCESS')
				return $arrParams['RoleID'];
			else if($res=='FAILED')
				Utility::output('身份验证失败,请重新登录.');
			else 
			{
				$times++;
				Utility::chkUserLogin($arrParams,$times);
			}				
		}
		else 
			Utility::output('身份验证失败,请重新登录.');
	}  
	/**
	 * 验证手机
	 * @param unknown_type $mobile 手机号
	 */
	public static function chkMobile($mobile)
	{
		if(strlen($mobile)==11)
 			return (preg_match("/(13\d{1}|15[012356789]|18[056789])\d{8}$/",$mobile)) ? true : false;
		else		
			return false;
	}
	/**
	 * 随机数
	 * @param $min 随机数最小值范围
	 * @param $max 随机数最大值范围
	 */
	public static  function getRand($min,$max)
	{
		return rand($min,$max);
	}
	/**
	 * 密码输入面板
	 */
	public static function getRandomNum()
	{
		$arrNumber = array(0,1,2,3,4,5,6,7,8,9);
		shuffle($arrNumber);
		//$_SESSION["arrRandNum"] = $arrNumber;	
		return $arrNumber;
	}
	/**
	 * 通过坐标得到相应位置的密码
	 * @param unknown_type $arrPwd 密码组
	 * @param unknown_type $strPwdCoordinate 坐标
	 */
	public static function getPwd($arrPwd,$strPwdCoordinate)
	{
		$strPwd = "";		
		$arrPass = str_split($strPwdCoordinate);
		foreach ($arrPass as $key)
		{
			$strPwd .= $arrPwd[$key];
		}		
		return $strPwd;
	}
	/**
	 * 解密
	 * @param $string 解密字符串
	 */
	public static function mcryptDecrypt($arrConfig,$string)
	{
		return base64_decode($string);
		/*$key=$arrConfig['EncryptKey'];
		$crypttexttb=Utility::safe_base64Decode($string);//对特殊字符解析
		$decryptedtb = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256,md5($key),base64_decode($crypttexttb),MCRYPT_MODE_CBC,md5(md5($key))),"\0");
		return $decryptedtb;*/
	}
	
	/**
	 * 检查密码强度
	 * @param $password
	 */
	public static function getPwdStrong($password)
	{
		$strStrong ="弱";
		
		$len = strlen(self::utf8ToGb2312($password));
		
		$matchCount = 0;
		if(preg_match("/[a-z]/", $password)){
			$matchCount++;
		}
		if(preg_match("/[A-Z]/", $password)){
			$matchCount++;
		}
		if(preg_match("/[0-9]/", $password)){
			$matchCount++;
		}
		if(preg_match("/[^A-Za-z0-9]/", $password)){
			$matchCount++;
		}
		
		if($len >= 8 && $matchCount >= 2){
			$strStrong ="中";
		}
		if($len >= 10 && $matchCount >= 4){
			$strStrong ="强";
		}
		
		return $strStrong;
	}
	
	/**
	 * 解析特殊字符
	 */
	public static function safe_base64Decode($string){
		$data = str_replace(array('-','_'),array('+','/'),$string);
		$mod4 = strlen($data) % 4;
		if ($mod4) {	
			$data .= substr('====', $mod4);	
		}
		return base64_decode($data);
	}
	/**
	 * 获取数据
	 *
	 * */
	public static function request($must_fields = null) {
	    $params = (array)json_decode ( file_get_contents ( 'php://input' ) ,true);
	    if (count($must_fields) > 0) {
	        foreach($must_fields as $field){
	            if(!isset($params[$field])){
	                Utility::response(-1,"param ".$field." is not set ");
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
	/**
	 * @param $file_name string 日志文件名
	 * @param $log_info  string 日志信息名称
	 * @param $log_data string 日志内容
	 *
	 * **/
	public static function Log($file_name,$log_info,$log_data){
	    $log_path = dirname($_SERVER["DOCUMENT_ROOT"])."/logs/".$file_name."/";
	    if(!file_exists($log_path))
	        mkdir($log_path,0777,true);
	    $log_name = date('Y-m-d').".txt";
	    error_log(date('Y-m-d H:i:s')." ".$log_info.":".$log_data."\r\n",3,$log_path.$log_name);
	}


    public static  function MakeSign($parmater,$key)
    {
        //签名步骤一：按字典序排序参数
        ksort($parmater);
        $string =Utility::ToUrlParams($parmater);
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=".$key;
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }


    public static  function ToUrlParams($parmater)
    {
        $buff = "";
        foreach ($parmater as $k => $v)
        {
            if($k != "sign" && $v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }


    public static  function postCurl($xml, $url, $useCert = 0, $second = 30){
        //初始化curl
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        //如果有配置代理这里就设置代理
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        if($useCert == true){
            //设置证书
            //使用证书：cert 与 key 分别属于两个.pem文件
            curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLCERT, WxPayConfig::SSLCERT_PATH);
            curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLKEY, WxPayConfig::SSLKEY_PATH);
        }
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //运行curl
        $rdata = curl_exec($ch);
        //返回结果
        if($rdata){
            curl_close($ch);
            return $rdata;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
        }
    }
    public static function getDevice(){
        $agent = $_SERVER['HTTP_USER_AGENT'];
        if(true == preg_match("/.+Windows.+/", $agent)){
            return "web";
        }elseif(true == preg_match("/.+Macintosh.+/", $agent)){
            return "mac";
        }elseif(true == preg_match("/.+iPad.+/", $agent)){
            return "iPad";
        }elseif(true == preg_match("/.+iPhone.+/", $agent)){
            return "iPhone";
        }elseif(true == preg_match("/.+Android.+/", $agent)){
            return "Android";
        }else{
            return "none";
        }
    }
	
}
?>