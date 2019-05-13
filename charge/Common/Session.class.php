<?php
/**
 * SESSION操作
 * @author xuluojiong
 */
class Session 
{
    //SESSION名
	private $strSessionName = '';
	//SESSIONID
	private $strSessionID   = '';
	/**
	 * 构造函数
	 * @param $sessionName	SESSION名称
	 * @param $sessionID	SESSIONID
	 */
	function __construct($strSessionName,$strSessionID='') 
	{	    
		$this->strSessionName = $strSessionName;
		$this->strSessionID = $strSessionID;
		
		//如果当前环境已经启动session,并且与要启动session同名而且sessionID也相同，则不用再启动
		if(isset($_SESSION))
		{
		    if(session_name()==$strSessionName && session_id()==$this->strSessionID)
		    {
        		if(empty($this->strSessionID))
        		{
        		    $this->strSessionID = session_id();
        		}
		    }
		    else 
		    {
		        //var_dump($_SESSION);
    		    session_commit();
        		
        		//设置SESSION名
        		session_name($this->strSessionName);
        		
        		//设置SESSIONID
        		if(!empty($this->strSessionID))
        		{
        			session_id($this->strSessionID);
        		}
        		
        		//启动SESSION,因为已经关闭了上一个SESSION，所以这里启动时不用判断
        		session_start();
                //var_dump('xxxx');
        		if(empty($this->strSessionID))
        		{
        		    $this->strSessionID = session_id();
        		}
		    }
		}
		else
		{
        		//设置SESSIONID
        		if(!empty($this->strSessionID))
        		{
        		    //设置SESSION名
        		    session_name($this->strSessionName);
        		    
        			session_id($this->strSessionID);
        		}
        		
        		//启动SESSION,因为已经关闭了上一个SESSION，所以这里启动时不用判断
        		session_start();
        		if(empty($this->strSessionID))
        		{
        		    $this->strSessionID = session_id();
        		}
		}
	}
	
	/**
	 * 切换SESSION空间
	 */
	private function changeSessionArea()
	{
	    if(isset($_SESSION) && session_name()==$this->strSessionName)
		{
		    return;
		}
	    session_commit();
	    session_id($this->strSessionID);
	    session_name($this->strSessionName);
	    session_start();
	}
	
	/**
	 * 通过key取SESSION值
	 * @param string $key	session的key
	 * @return string key所对应的值
	 */
	public function get($strKey)
	{
	    //切换SESSION空间为当前设定值
	    $this->changeSessionArea();
	    if(empty($strKey))
	    {
	        return $_SESSION;
	    }
	    if(!isset($_SESSION[$strKey]))
	        return FALSE;
        return $_SESSION[$strKey];
	}
	
	/**
	 * 通过key取SESSION值(对象)
	 * @param string $key	session的key
	 * @return string key所对应的对象
	 */
	public function getObject($strKey)
	{
	    //切换SESSION空间为当前设定值
	    $this->changeSessionArea();
	    if(empty($strKey))
	    {
	        return $_SESSION;
	    }
	    if(!isset($_SESSION[$strKey]))
	        return FALSE;
        return unserialize($_SESSION[$strKey]);
	}
	
	/**
	 * 提供key，将值存入key所对应的SESSION值
	 * @param string $key	键
	 * @param mixed $val	值
	 */
	public function set($strKey,$strVal)
	{
	    //切换SESSION空间为当前设定值
	    $this->changeSessionArea();
		$_SESSION[$strKey] = $strVal;
	}
	
	/**
	 * 提供key，将值(对象)存入key所对应的SESSION值
	 * @param string $key	键
	 * @param mixed $val	值（对象）
	 */
	public function setObject($strKey,$objVal)
	{
	    //切换SESSION空间为当前设定值
	    $this->changeSessionArea();
		$_SESSION[$strKey] = serialize($objVal);
	}
	
	/**
	 * 产生一个新的SESSIONID,并写入原SESSION
	 * @param string $newSessionName		新SESSION名称
	 * @param string $rawSessionName		原SESSION名称
	 * @param string $strNewSessionIdKey	产生的新SESSIONID在原SESSION中的名字
	 */
	public function newSessionID($strNewSessionName,$strRawSessionName,$strNewSessionIdKey='newSessionID')
	{
		//保存原SESSIONID
		$strOldSessionID = session_id();
		session_commit();
		
		//生成新SESSIONID
		session_name($strNewSessionName);
		session_id('xxxx');
		session_start();
		session_regenerate_id();
		$strNewSessionID =session_id();
		session_destroy();
		session_commit();
		
		//还原原来的SESSION
		session_name($strRawSessionName);
		session_id($strOldSessionID);
		session_start();
		
		//保存新SESSIONID
		$this->set($strNewSessionIdKey, $strNewSessionID);
	}
	
	/**
	 * 获取SESSIONID
	 * @return 当前SESSIONID
	 */
	public function getSessionID()
	{
		return $this->strSessionID;
	}
	
	/**
	 * 获取SESSIONName
	 */
	public function getSessionName()
	{
	    return $this->strSessionName;
	}
	
	/**
	 * 开始SESSION
	 */
	public static function startSession()
	{
	    if(!isset($_SESSION))
	    {
	        if(isset($_GET['sid']) && !empty($_GET['sid']))
	        {
	            $strSessionID = $_GET['sid']; 
	            session_id($strSessionID);
	        }
	        session_start();
	    }
	}
}