<?php
require_once __DIR__.'/DALBase.php';

class MsgDAL extends DALBase
{
	public function __construct()
	{
		parent::__construct();	
		$this->arrConfig=unserialize(SYS_CONFIG);
		parent::initDBObj(0,$this->arrConfig['MapType']['Msg'],true);
	}

	/**
	 * 插入短信数据
	 * @TypeID		TINYINT,		--1:角色,2:通行证号
	 * @RoleID		VARCHAR(50),	--对象ID
	 * @Mobile		VARCHAR(1000),	--手机号
	 * @MsgContent	VARCHAR(10),	--短信内容
	 * @MsgType	TINYINT			--短信类型,1:找回登录密码,2:找回交易密码,3:主机解绑,5:手机认证,6:邮箱认证,7:开通安全验证,
								--		   8:关闭安全验证,9:修改安全手机,10:取回背包密码,11:动态密保开通,12:动态密保关闭,13:手机密令开通,
								--		   14:手机密令关闭,15:客户端登陆手机认证,16:手机密令停用,17:动态密保停用,18:修改安全问题,
								--		   19: 安装数字证书,20: 取消数字证书,21:删除数字证书,22:重置通行证登陆密码,23:重置银行密码,
								--		   24:重置背包密码
	 * Return: 大于0:成功,-1:失败,-2:数据库异常,-3:同一手机同一类型短信在5分钟内重复发送
	 */
	public function insertShortMessage($TypeID, $RoleID, $Mobile, $MsgContent, $MsgType)
	{
		$params = array(array($TypeID, SQLSRV_PARAM_IN),
						array($RoleID, SQLSRV_PARAM_IN),
						array($Mobile, SQLSRV_PARAM_IN),
						array($MsgContent, SQLSRV_PARAM_IN),
						array($MsgType, SQLSRV_PARAM_IN)
						);	
		$arrReturns = $this->objMsgDB->fetchAssoc("Proc_ShortMessage_InsertNoCount", $params);
		return $arrReturns;
	}
	/**
	 * 添加公告
	 * @ID		
	 * @Title	--公告标题
	 * @Content	--公告内容
	 * @TypeID	--公告类型,1:比赛公告
	 * Return: 0:成功,-1:失败
	 */
	public function addNotice($ID,$Title,$Content,$TypeID)
	{
		$iResult = -1;
		$params = array(array($ID, SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($Title), SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($Content), SQLSRV_PARAM_IN),
						array($TypeID, SQLSRV_PARAM_IN)
						);	
		$arrReturns = $this->objMsgDB->fetchAssoc("Proc_Notice_Insert", $params);
		if(is_array($arrReturns)) $iResult = $arrReturns['iResult'];
		return $iResult;
	}
	/**
	 * 读取公告详情
	 * @param @ID
	 * @return Array
	 * @author xlj
	 */
	public function getNoticeInfo($ID)
	{
		$params = array(array($ID, SQLSRV_PARAM_IN));	
		$arrReturns = $this->objMsgDB->fetchAssoc("Proc_Notice_SelectInfo", $params);
		if(is_array($arrReturns))
			$arrReturns['Title'] = Utility::gb2312ToUtf8($arrReturns['Title']);
		else 
			$arrReturns = null;
		return $arrReturns;
	}
	
}