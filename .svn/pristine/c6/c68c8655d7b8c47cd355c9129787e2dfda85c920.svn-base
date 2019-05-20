<?php

/**
 * 浏览器友好的变量输出
 * @param mixed         $var 变量
 * @param boolean       $echo 是否输出 默认为true 如果为false 则返回输出字符串
 * @param string        $label 标签 默认为空
 * @param integer       $flags htmlspecialchars flags
 * @return void|string
 */
function dump($var, $echo = true, $label = null, $flags = ENT_SUBSTITUTE){
	$label = (null === $label) ? '' : rtrim($label) . ':';
	ob_start();
	var_dump($var);
	$output = ob_get_clean();
	$output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
	if (!extension_loaded('xdebug')) {
		$output = htmlspecialchars($output, $flags);
	}
	$output = '<pre>' . $label . $output . '</pre>';
	if ($echo) {
		header("Content-type: text/html; charset=utf-8");
		echo($output);
		return;
	} else {
		return $output;
	}
}

function https_post($url, $data, $i = 0) {
	$i++;
	$str = '';
	if ($data) {
		foreach ( $data as $key => $value ) {
		    if ($value != '') {
                $str .= $key . "=" . $value . "&";
            }
		}
	}
	$curl = curl_init ( $url ); // 启动一个CURL会话
	// 	curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, 0 ); // 对认证证书来源的检查
	// 	curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, 1 ); // 从证书中检查SSL加密算法是否存在

	if(stripos($url,"https://")!==FALSE){
		curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
	}    else    {
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,TRUE);
		curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,2);//严格校验
	}

	// curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt ( $curl, CURLOPT_FOLLOWLOCATION, 1 ); // 使用自动跳转
	curl_setopt ( $curl, CURLOPT_AUTOREFERER, 1 ); // 自动设置Referer
	if ($str) {
		curl_setopt ( $curl, CURLOPT_POSTFIELDS, $str ); // Post提交的数据包
	}
	curl_setopt ( $curl, CURLOPT_TIMEOUT, 20 ); // 设置超时限制防止死循环
	// curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
	curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 ); // 获取的信息以文件流的形式返回
	// curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	// curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	// curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$tmpInfo = curl_exec ($curl);
	$Err = curl_error($curl);
	if (false === $tmpInfo || !empty($Err)) {
		if($i == 1)
			return https_post ($url, $data, $i);
		curl_close ($curl);
		return $Err;
	}
	curl_close ($curl);
	return $tmpInfo;
}


/**
 * 获取签名
 * @param unknown $array
 * @param unknown $appKey
 * @return string
 */
function  getSign($array , $appKey) {
	if(isset($array['sign'])) {
		unset($array['sign']);
	}
	ksort($array);
	// $md5Str = http_build_query($array);
    $md5Str = '';
    foreach ($array as $key => $value) {
    	$md5Str .= $value;
    }
	$mySign = md5($md5Str.$appKey);
	return $mySign;
}


