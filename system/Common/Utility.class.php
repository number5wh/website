<?php
/**
 * 工具类
 * @author xlj
 */
class Utility {



    ///分转为元，小数保留2位
    public static function  FormatMoney($param){
        return sprintf('%.2f',$param/1000);
    }

    /*
    $array:需要排序的数组
    $keys:需要根据某个key排序
    $sort:倒叙还是顺序
    */
    public static function arraySort($array, $keys, $sort = 'asc')
    {
        $newArr = $valArr = array();
        foreach ($array as $key => $value) {
            $valArr[$key] = $value[$keys];
        }
        ($sort == 'asc') ? asort($valArr) : arsort($valArr);//先利用keys对数组排序，目的是把目标数组的key排好序
        reset($valArr); //指针指向数组第一个值
        foreach ($valArr as $key => $value) {
            $newArr[$key] = $array[$key];
        }
        return $newArr;
    }


	/**
	 * 获取IP
	 */
	public static function getIP() {
		if (isset($_SERVER['HTTP_CLIENT_IP'])) {
			return $_SERVER['HTTP_CLIENT_IP'];
		} else {
			return $_SERVER['REMOTE_ADDR'];
		}
	}

	/**
	 * 获取count位数字随机数
	 * @param $icount
	 */
	public static function getRandomNumeral($icount) {

		$strRandom = "";
		for ($i = 0; $i < $icount; $i++) {
			srand((double) microtime() * 1000000);
			$rand_number = rand(0, 9);
			$strRandom .= $rand_number;
		}
		return $strRandom;
	}

	/**
	 * 替换模板标签
	 */
	public static function assign($smarty, $arrTags) {
		foreach ($arrTags as $key => $value) {
			$smarty->assign($key, $value);
		}
	}
	/**
	 * 编码转换gb2312转utf8
	 */
	public static function gb2312ToUtf8($str) {
		if (!empty($str)) {
			return iconv('GBK', 'utf-8//IGNORE', $str);
		} else {
			return $str;
		}

	}

	/**
	 * utf8转gb2312
	 * @param unknown_type $str
	 * @return string
	 */
	public static function utf8ToGb2312($str) {
		if (!empty($str)) {
			return iconv('utf-8', 'GBK//IGNORE', $str);
		} else {
			return $str;
		}

	}
	/**
	 * 对参数进行Null和空判断
	 * @param string $key	要判断的key
	 * @param string $obj	要判断的类型对象,$_GET,$_POST
	 */
	public static function isNullOrEmpty($key, $obj) {

		if (isset($obj[$key]) && !empty($obj[$key])) {
			return $obj[$key];
		}
		return FALSE;
	}

	/**
	 * 判断是否数字
	 * @param string $key	要判断的key
	 * @param string $obj	要判断的类型对象,$_GET,$_POST
	 */
	public static function isNumeric($key, $obj) {
		if (isset($obj[$key]) && is_numeric($obj[$key]) && $obj[$key] != '') {
			return $obj[$key];
		}
		return FALSE;
	}

	/**
	 * 显示信息提示框
	 * @param unknown_type $msg  文本内容
	 */
	public static function echoResultHtml($smarty, $btnValue, $click, $msg, $Refresh, $ClassName, $arrConfig) {
		$arrTags = array('Refresh' => $Refresh, 'ClassName' => $ClassName, 'resultMsg' => $msg, 'btnValue' => $btnValue, 'click' => $click);
		Utility::assign($smarty, $arrTags);
		$html = $smarty->fetch($arrConfig['skin'] . '/result.html');
		$html = str_replace("\r\n", '', $html);
		return $html;
	}

	/**
	 * 检查登陆
	 */
	public static function chkUserLogin($CFG) {
		$objSessioin = new Session($CFG['Session']['SessionLoginName']);
		$iAdminID = $objSessioin->get($CFG['SessionInfo']['AdminID']);
		$strAdminName = $objSessioin->get($CFG['SessionInfo']['UserName']);
		if ($iAdminID <= 0 || empty($strAdminName)) {
			header('Location:/');
			exit();
		} else {
			$objSessioin->set($CFG['SessionInfo']['LastOperateTime'], time());
			return $strAdminName;
		}
	}

	/**
	 * 多表分页类：简单的分页类设置
	 * @param $iRecordsCount 总记录数
	 * @param $pagesize	每页显示记录数
	 * @param $param	数组集
	 * @return Ambiguous
	 */
	public static function setSimplePages($iTotalPages, $iCurPage, $NextLogsID, $NextStartDate) {
		$Pages['TotalPage'] = $iTotalPages;
		$Pages['CurPage'] = ($iCurPage >= $iTotalPages ? $iTotalPages : $iCurPage);
		$Pages['PrevPage'] = ($iCurPage - 1) > 0 ? ($iCurPage - 1) : 1;
		$Pages['NextPage'] = ($iCurPage + 1) >= $iTotalPages ? $iTotalPages : ($iCurPage + 1);
		//$Pages['PrevLogsID'] = 1;

		//$Pages['PrevStartDate'] = $NextStartDate;
		$Pages['NextLogsID'] = $NextLogsID;
		$Pages['NextStartDate'] = $NextStartDate;
		return $Pages;
	}

	/**
	 * 设置分页属性
	 * @param $curPage 当前页
	 * @param $iRecordsCount 总记录数
	 * @param $pagesize 每天显示记录数
	 */
	public static function setPages($curPage, $iRecordsCount, $pagesize) {
		$iTotalPages = ceil($iRecordsCount / $pagesize);
		$Page['PrevPage'] = $curPage > 1 ? $curPage - 1 : 1;
		$Page['NextPage'] = $curPage < $iTotalPages ? $curPage + 1 : $iTotalPages;
		$Page['TotalPage'] = $iTotalPages;
		$Page['CurPage'] = $curPage > $iTotalPages ? $iTotalPages : $curPage;
		$Page['PageSize'] = $pagesize;
		$Page['RecordsCount'] = $iRecordsCount;
		return $Page;
	}
	/**
	 * 还原版本号
	 * @param unknown_type $iVersion 计算前的版本号
	 */
	public static function getVersion($iVersion) {
		$strVer = '';
		for ($i = 3; $i >= 0; $i--) {
			$iVer = intval($iVersion / pow(256, $i));
			$iVersion = intval($iVersion - $iVer * pow(256, $i));
			$strVer .= $iVer . '.';
		}
		$strVer = empty($strVer) ? '' : substr($strVer, 0, strlen($strVer) - 1);
		return $strVer;
	}
	/**
	 * 加密
	 * @param $string 加密字符串
	 */
	public static function mcryptEncrypt($arrConfig, $string) {
		    return base64_encode($string);		
// 			$key=$arrConfig['EncryptKey'];
// 			$crypttext = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key),$string,MCRYPT_MODE_CBC,md5(md5($key))));
// 			$encrypted =trim(Utility::safe_base64Encode($crypttext));//对特殊字符进行处
// 			return $encrypted;
		
	}
	/**
	 * 解密
	 * @param $string 解密字符串
	 */
	public static function mcryptDecrypt($arrConfig, $string) {
		     return base64_decode($string);
		
// 			$key=$arrConfig['EncryptKey'];
// 			$crypttexttb=Utility::safe_base64Decode($string);//对特殊字符解析
// 			$decryptedtb = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256,md5($key),base64_decode($crypttexttb),MCRYPT_MODE_CBC,md5(md5($key))),"\0");
// 			return $decryptedtb;
		
	}
	/**
	 * 处理特殊字符
	 */
	public static function safe_base64Encode($string) {
		$data = base64_encode($string);
		$data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
		return $data;
	}
	/**
	 * 解析特殊字符
	 */
	public static function safe_base64Decode($string) {
		$data = str_replace(array('-', '_'), array('+', '/'), $string);
		$mod4 = strlen($data) % 4;
		if ($mod4) {
			$data .= substr('====', $mod4);
		}
		return base64_decode($data);
	}
	public static function ip2int($ip) {
		//我们先把ip分为四段,$ip1,$ip2,$ip3,$ip4
		list($ip1, $ip2, $ip3, $ip4) = explode(".", $ip);
		//然后第一段乘以256的三次方，第二段乘以256的平方，第三段乘以256
		//这即是我们得到的值
		return $ip1 * pow(256, 3) + $ip2 * pow(256, 2) + $ip3 * 256 + $ip4;
	}
	public static function arrReplaceKey($arr, $keyMap) {
		$brr = array();
		foreach ($arr as $key => $val) {
			if (isset($keyMap[$key])) {
				$brr[$keyMap[$key]] = $val;
			} else {
				$brr[$key] = $val;
			}
		}
		return $brr;
	}
	public static function arrListReplaceKey(&$arr, $keyMap) {
		if (empty($arr)) {
			return null;
		}

		foreach ($arr as $key => $val) {
			$arr[$key] = self::arrReplaceKey($val, $keyMap);
		}
	}

	/**
	 * @param $arr
	 * @param $column
	 * @param $index [optional]
	 * @return array
	 */
	public static function array_column($arr, $column, /*default null*/ $index = null) {
		$brr = array();
		if ($index === null) {
			foreach ($arr as $val) {
				if (isset($val[$column])) {
					$brr[] = $val[$column];
				}
			}

		} else {
			foreach ($arr as $val) {
				if (isset($val[$column])) {
					$brr[$val[$index]] = $val[$column];
				}
			}

		}
		return $brr;
	}
	public static function array_combine($arrKey, $arrValue) {
		$retArr = array();
		foreach ($arrKey as $key => $val) {
			$retArr[$val] = $arrValue[$key];
		}
		return $retArr;
	}
	public static function getPageNum($tot, $pageSize) {
		if ($tot == 0) {
			return 0;
		}

		return intval($tot / $pageSize) + ($tot % $pageSize == 0 ? 0 : 1);
	}
	static $time;
	public static function setMicroTime() {
		self::$time = microtime();
	}
	public static function getMicroTime($debug = "test") {
		echo $debug . (microtime() - self::$time) . '<br/>';
	}
	/**
	 * 获取IP地址
	 *
	 * */
	public static function getIPDistrict($IP) {
		//$IP = '115.238.244.230';
		//$ch = curl_init('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=' . $IP);
		$ch = curl_init('http://ip.taobao.com//service/getIpInfo.php?ip=' . $IP);
		curl_setopt($ch, CURLOPT_POSTFIELDS, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$ret = curl_exec($ch);
		//$ret = file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip='.$IP);
		return json_decode($ret, true);
	}

    public static function getIPDistrict2($IP) {

        $memcache_obj = memcache_pconnect('127.0.0.1', 11211);

        $val =$memcache_obj->get($IP);
        if($val){
            return $val;
        }
        else {
            $datatype = 'txt';
            $header = array('token:42271a8c516e77ae4753f4504dc296fe');
            $url = 'http://api.ip138.com/query/?ip=' . $IP . '&datatype=' . $datatype;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
            $handles = curl_exec($ch);
            curl_close($ch);
            $memcache_obj->set($IP,$handles);
            return str_replace($IP, "", $handles);
        }
        //return json_decode($ret, true);
    }


    public static function getIPDistrict3($IP) {
        $url ="http://ip.tool.chinaz.com/ajaxsync.aspx?at=ipbatch&callback=jQuery111309519704937041047_1545096913002";
        $strpost ="ip=".$IP;
        $ret =Utility::postCurl($strpost,$url);
        $start = stripos($ret,"location");
        $str =str_replace("'}])","",substr($ret,$start+10,strlen($ret)));
        return $str;
        //return json_decode($ret, true);
    }
	/***
		     *
		     * 获取手机号码归属地
		     *
	*/
	public static function getTelSegment($tel) {
		$ch = curl_init('https://tcc.taobao.com/cc/json/mobile_tel_segment.htm?tel=' . $tel);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // ignore the certificate verification
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true); // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回
		$ret = curl_exec($ch);
		curl_close($ch);
		$arrResult = array();
		if ($ret == false) {
			$arrResult['ret'] = 0;
		} else {
			$arrResult['ret'] = 1;
		}
		$ret = self::gb2312ToUtf8($ret);
		$result = array();
		$qian = array(" ", "　", "\t", "\n", "\r");
		$ret = str_replace($qian, '', $ret);
		preg_match("/(?:\{)(.*)(?:\})/i", $ret, $result);
		$result = $result[1];
		$result = explode(',', $result);

		foreach ($result as $ey => $val) {
			$val = explode(':', $val);
			$arrResult[$val[0]] = trim($val[1], "'");
		}
		return $arrResult;
	}

	public static function buildSortor($key = 'value', $asc = true) {
		return function ($a, $b) use ($key, $asc) {
			if ($a[$key] == $b[$key]) {
				return 0;
			}
			$val = $asc ? 1 : -1;
			return $a[$key] > $b[$key] ? $val : -$val;
		};
	}

	/**
	 * 秒数转成时分秒
	 */
	public static function dataformat($num) {
		$hour = floor($num / 3600);
		$minute = floor(($num - 3600 * $hour) / 60);
		$second = floor((($num - 3600 * $hour) - 60 * $minute) % 60);
		return $hour . '时' . $minute . '分' . $second . '秒';
	}
	/**
	 * @param $file_name string 日志文件名
	 * @param $log_info  string 日志信息名称
	 * @param $log_data string 日志内容
	 *
	 * **/
	public static function Log($file_name, $log_info, $log_data) {
		$log_path = dirname($_SERVER["DOCUMENT_ROOT"]) . "/logs/" . $file_name . "/";
		if (!file_exists($log_path)) {
			mkdir($log_path, 0777, true);
		}

		$log_name = date('Y-m-d') . ".txt";
		error_log(date('Y-m-d H:i:s') . " " . floor(microtime() * 1000) . " " . $log_info . ":" . $log_data . "\r\n", 3, $log_path . $log_name);
	}
	/**
	 *检测字符串是否是ip
	 * **/
	public static function testIp($s) {
		$ip = explode(".", $s);
		for ($i = 0; $i < count($ip); $i++) {
			if ($ip[$i] > 255) {
				return 0;
			}

		}
		return preg_match("/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/", $s);
	}
	/**
	 * 检测字符串是否是ip段
	 */
	public static function testIpRand($s) {
		$ipRand = explode("-", $s);
		if (count($ipRand) != 2) {
			return 0;
		}

		if (Utility::testIp($ipRand[0]) && Utility::testIp($ipRand[1])) {
			return 1;
		}

		return 0;
	}


    function postCurl($xml, $url, $useCert = 0, $second = 30){
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
}
?>