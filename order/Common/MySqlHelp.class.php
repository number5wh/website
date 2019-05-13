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

function print_stack_trace($arg1,$arg2)
{
    $array =debug_backtrace();
  //print_r($array);//信息很齐全
   unset($array[0]);
   foreach($array as $row)
    {
       $html .=$row['file'].':'.$row['line'].'行,调用方法:'.$row['function']."(";
	   
   foreach($row['args'] as $key => $value)
    {
	   if($key != 0)
       $html .=",";
       $html .="[arg".$key."](";
       $html .=serialize($value);
       $html .=")";
    }
       $html .= ")"."\n";
	
    }
    return$html;
}

class MySqlHelp
{
	private $conn = null;
	private static $ObjInstance = null;
	
	/**
	* 私有化构造函数，防止外界实例化对象  
	*/
	private function __construct(MySqlServerInfo $serverInfoObj)
	{
		$serverName = $serverInfoObj->strDBHost; 
		if(!empty($serverInfoObj->strDBPort)) $serverName .= ','.$serverInfoObj->strDBPort;
		$connectionInfo = array("UID"=>$serverInfoObj->strDBUser,
								"PWD"=>$serverInfoObj->strDBPwd,
								"Database"=>$serverInfoObj->strDBName,
								"ReturnDatesAsStrings"=> true); 

		
		$this->conn = sqlsrv_connect($serverName, $connectionInfo);  

		/* $fp = fopen('Logs/'.date('Y-m-d').".txt", "a+");
		if($fp)   
		    if($this->conn)
		       fwrite($fp,date('Y-m-d H:i:s',time())."connect result:".$this->conn." \n");
		    else {
		       $errors = sqlsrv_errors();
		       foreach ($errors as $k => $v){
		           $error[$k]['sqlstate'] = $v['SQLSTATE'];
		           $error[$k]['code'] = $v['code'];
		           $error[$k]['message'] = Utility::utf8ToGb2312($v['message']);
		            
		       }
		       fwrite($fp,date('Y-m-d H:i:s',time())."connect result:".json_encode($error)." \n");
		    }
		fclose($fp); */
		if($this->conn === false ) 
		{  
	
		
		$fp = fopen('Logs/'.date('Y-m-d').".txt", "a+");
		if($fp)
		    fwrite($fp,date('Y-m-d H:i:s',time())." SQLBackTrace: \n".print_stack_trace()." \n");
		fclose($fp);
	
			echo "Could not connect.\n";
			die(print_r(sqlsrv_errors(), true));  
		} 
		else
		{
		
	
		}		
	}
	
	public function —__destruct(){
	    sqlsrv_close($this->conn);
	}
	/**
	* 数据库配置
	*/
	public static function getInstance(MySqlServerInfo $serverInfoObj,$strKey)
	{
		
		if (!isset(self::$ObjInstance[$strKey]) || self::$ObjInstance[$strKey] == null)
		{
			self::$ObjInstance[$strKey] = new MySqlHelp($serverInfoObj);
			
			
		}
		
		return self::$ObjInstance[$strKey];
	}	
	/**
	* master数据库配置
	*/
	public static function getMasterInstance(MySqlServerInfo $serverInfoObj)
	{
		
		
		if (!isset(self::$ObjInstance['M']) || self::$ObjInstance['M'] == null)
		{
			self::$ObjInstance['M'] = new MySqlHelp($serverInfoObj);
			
			
		}

		return self::$ObjInstance['M'];
	}
	
	/**
	* 返回多条记录集
	* @param unknown_type $cmdText 存储过程名称
	* @param unknown_type $params  参数(数组)
	*/
	public function fetchAllAssoc($cmdText,$params='')
	{
		$stmt = $this->query($cmdText,$params);	
		$arr = array();
		while($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC))
		{
			$arr[]=$row;
		}	
		if(count($arr)>0)
			return $arr;
		else 
			return false;
	}
	
	/**
	* 返回一条记录集
	* @param unknown_type $cmdText 存储过程名称
	* @param unknown_type $params  参数(数组)
	*/
	public function fetchAssoc($cmdText,$params='')
	{
		$stmt = $this->query($cmdText,$params);	    
		$row=sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC);
		if(is_array($row) && count($row)>0)
			return $row;
		else
			return false;
	}
	
	/**
	 * 执行存储过程
	 * @param unknown_type $cmdText 存储过程名称
	 * @param unknown_type $params  参数(数组)
	 */
	private function query($cmdText,$params)
	{
		$wenhao = '';
		if(is_array($params) && count($params)>0)
		{			
			$iCount = count($params);
			for ($i=0;$i<$iCount;$i++)
				$wenhao .= '?,';
			if($wenhao!='') $wenhao = substr($wenhao, 0,strlen($wenhao)-1);
		}	
		
		$tsql_callSP = "{call $cmdText($wenhao)}"; 
		if(empty($wenhao))
			$stmt = sqlsrv_query($this->conn, $tsql_callSP);
		else
			$stmt = sqlsrv_query($this->conn, $tsql_callSP, $params);		
		if( $stmt === false )	
		{	
			echo "Error in executing statement 3.\n";	
			die( print_r( sqlsrv_errors(), true));	
		} 		
		return $stmt;
			
		//return false;
	}
}

class MySqlServerInfo
{
	public $strDBHost = '127.0.0.1';
	public $strDBPort = '3306';
	public $strDBUser = 'root';
	public $strDBPwd = '123456';
	public $strDBName = 'masterdb';
	public $strDBCharset = 'utf8';
}
?>