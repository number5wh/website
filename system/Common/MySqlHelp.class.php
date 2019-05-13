<?php
/*//参数需要以如下数组方式赋值并标明类型，SQLSRV_PARAM_IN是输入类型，SQLSRV_PARAM_OUT是输出类型。注意要按照存储过程定义的顺序赋值
$params = array(
array($Passport, SQLSRV_PARAM_IN),
array($GameType, SQLSRV_PARAM_IN),
array($IP, SQLSRV_PARAM_IN),
array($PassType, SQLSRV_PARAM_IN),
array($ms, SQLSRV_PARAM_IN)
);
$c=MySqlHelp::getInstance($serverInfoObj,'a');
$ab=$c->fetchAllAssoc('Proc_GetRoleInfo',$params);
print_r($ab);
 */
class MySqlHelp {
	private $link = 'sqlsrv'; //mssql
	private $conn = null;
	private static $ObjInstance = null;
	private $serInfo = null;
	/**
	 * 私有化构造函数，防止外界实例化对象
	 */
	private function __construct(MySqlServerInfo $serverInfoObj) {
		$this->serInfo = $serverInfoObj;
		if ($this->link == 'sqlsrv') {
			$serverName = $serverInfoObj->strDBHost;
			if (!empty($serverInfoObj->strDBPort)) {
				$serverName .= ',' . $serverInfoObj->strDBPort;
			}

			$connectionInfo = array("UID" => $serverInfoObj->strDBUser,
				"PWD" => $serverInfoObj->strDBPwd,
				"Database" => $serverInfoObj->strDBName,
				"ReturnDatesAsStrings" => true);
			$this->conn = sqlsrv_connect($serverName, $connectionInfo);

			if ($this->conn === false) {
				error_log("Could not connect. $serverName\n", 3, "log.txt");
				error_log(json_decode(sqlsrv_errors()) . "\n", 3, "log.txt");

				echo "Could not connect. $serverName\n";
				print_r($connectionInfo);
				die(print_r(sqlsrv_errors(), true));
			}
		} else {
			$serverName = $serverInfoObj->strDBHost . ':' . $serverInfoObj->strDBPort;
			$this->conn = mssql_connect($serverName, $serverInfoObj->strDBUser, $serverInfoObj->strDBPwd);
			if ($this->conn) {
				mssql_select_db($serverInfoObj->strDBName, $this->conn);
			} else {
				echo "Could not connect. $serverName\n";
				error_log("Could not connect.\n", 3, "log.txt");
				error_log(json_decode(sqlsrv_errors()) . "\n", 3, "log.txt");
			}

		}
	}
	/**
	 * 数据库配置
	 */
	public static function getInstance(MySqlServerInfo $serverInfoObj, $strKey) {
		if (!isset(self::$ObjInstance[$strKey]) || self::$ObjInstance[$strKey] == null) {
			self::$ObjInstance[$strKey] = new MySqlHelp($serverInfoObj);
		}
		return self::$ObjInstance[$strKey];
	}
	/**
	 * master数据库配置
	 */
	public static function getMasterInstance(MySqlServerInfo $serverInfoObj) {
		if (!isset(self::$ObjInstance['M']) || self::$ObjInstance['M'] == null) {
			self::$ObjInstance['M'] = new MySqlHelp($serverInfoObj);
		}
		return self::$ObjInstance['M'];
	}

	/**
	 * 返回多条记录集
	 * @param unknown_type $cmdText 存储过程名称
	 * @param unknown_type $params  参数(数组)
	 */
	public function fetchAllAssoc($cmdText, $params = '') {
		$arr = array();
		if ($this->link == 'sqlsrv') {
			$stmt = $this->query($cmdText, $params);
			while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
				$arr[] = $row;
			}

		} else {
			$stmt = $this->query1($cmdText, $params);
			while ($row = mssql_fetch_assoc($stmt)) {
				$arr[] = $row;
			}

		}
		if (count($arr) > 0) {
			return $arr;
		} else {
			return false;
		}

	}

	/**
	 * 返回一条记录集
	 * @param unknown_type $cmdText 存储过程名称
	 * @param unknown_type $params  参数(数组)
	 */
	public function fetchAssoc($cmdText, $params = '') {
		if ($this->link == 'sqlsrv') {
			$stmt = $this->query($cmdText, $params);
			$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
		} else {
			$stmt = $this->query1($cmdText, $params);
			$row = mssql_fetch_assoc($stmt);
		}
		if (is_array($row) && count($row) > 0) {
			return $row;
		} else {
			return false;
		}

	}

	/**
	 * 执行存储过程
	 * @param unknown_type $cmdText 存储过程名称
	 * @param unknown_type $params  参数(数组)
	 */
	private function query($cmdText, $params) {
		// error_log("query\n", 3, "log.txt");
		// error_log("cmdText: $cmdText\n", 3, "log.txt");
		// error_log("params:\n", 3, "log.txt");
		// error_log(json_encode($params).".\n", 3, "log.txt");

		$wenhao = '';
		if (is_array($params) && count($params) > 0) {
			$iCount = count($params);
			for ($i = 0; $i < $iCount; $i++) {
				$wenhao .= '?,';
			}

			if ($wenhao != '') {
				$wenhao = substr($wenhao, 0, strlen($wenhao) - 1);
			}

		}

		$tsql_callSP = "{call $cmdText($wenhao)}"; //var_dump($params);var_dump($this->conn);
		if (empty($wenhao)) {
			$stmt = sqlsrv_query($this->conn, $tsql_callSP);
		} else {
			$stmt = sqlsrv_query($this->conn, $tsql_callSP, $params);
		}

		if ($stmt === false) {

			error_log("Error in executing statement 3.\n", 3, "log.txt");
			error_log(json_decode(sqlsrv_errors()) . "\n", 3, "log.txt");
			echo "Error in executing statement 3.\n";
			$arrState =sqlsrv_errors();
			//print_r($arrState);
			$errmsg =$arrState[0]['message'];
			die(Utility::gb2312ToUtf8($errmsg));
		}
		return $stmt;

		//return false;
	}

	/**
	 * 执行存储过程
	 * @param unknown_type $cmdText 存储过程名称
	 * @param unknown_type $params  参数(数组)
	 */
	private function query1($cmdText, $params) {
		// error_log("query1\n", 3, "log.txt");
		// error_log("cmdText: $cmdText\n", 3, "log.txt");
		// error_log("params:\n", 3, "log.txt");
		// error_log(json_encode($params).".\n", 3, "log.txt");

		$wenhao = '';
		if (is_array($params) && count($params) > 0) {
			$iCount = count($params);
			foreach ($params as $val) {
				$wenhao .= "'" . $val[0] . "',";
			}
			if ($wenhao != '') {
				$wenhao = substr($wenhao, 0, strlen($wenhao) - 1);
			}

		}

		$tsql_callSP = "exec $cmdText $wenhao";

		$stmt = mssql_query($tsql_callSP, $this->conn);

		if ($stmt === false) {
			error_log("Error in executing statement 3.\n", 3, "log.txt");
			error_log($this->conn . "\n", 3, "log.txt");
			echo "Error in executing statement 3.\n";
			print_r(mssql_errors($this->conn));exit;
		}
		return $stmt;

		//return false;
	}
}

class MySqlServerInfo {
	public $strDBHost = '127.0.0.1';
	public $strDBPort = '1433';
	public $strDBUser = 'sa';
	public $strDBPwd = 'MTIzNDU2';
	public $strDBName = 'OM_MasterDB';
	public $strDBCharset = 'utf8';
}
?>