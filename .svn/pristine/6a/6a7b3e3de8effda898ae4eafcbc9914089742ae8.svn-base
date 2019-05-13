<?php
require ROOT_PATH . 'Class/DAL/DataChangeLogsDAL.class.php';
class DataChangeLogsBLL
{
	private $objDataChangeLogsDAL = NULL;
	public function __construct($iRoleID=0)
    {
        $this->objDataChangeLogsDAL = new DataChangeLogsDAL($iRoleID);
    }
    
    /**
	 * 总记录数
	 * @param $arrParam
	 */
	function getRecordsCount($arrParam)
	{
		return $this->objDataChangeLogsDAL->getRecordsCount($arrParam);
	}
	/**
	 * 分页
	 * @param $arrParam
	 * @param $flag 是否设置缓存
	 */
	function getPageList($arrParam,$flag=0)
	{
		return $this->objDataChangeLogsDAL->getPageList($arrParam, $flag);
	}
    /**
     * 返回同一桌游戏各玩家输赢信息
     * @param $SerialNumber	BIGINT,
     * @param $DateTime	CHAR(8)--日期,如:20120401
     */
    function getSelectGameResult($SerialNumber, $DateTime,$ServeID,$LogTime)
    {
        return $this->objDataChangeLogsDAL->getSelectGameResult($SerialNumber, $DateTime,$ServeID,$LogTime);
    }

    /**
     * 读取上个月玩家某种游戏输赢数据
     * @param unknown_type $RoleID
     * @param unknown_type $KindID
     * @return array
     * 原来是UserGameDataLogs 由于表名变了  所以模型也改下名字 mlgt
     */
    public function getUserGameChangeLogs($RoleID,$KindID,$Date)
    {
        return $this->objDataChangeLogsDAL->getUserGameChangeLogs($RoleID,$KindID,$Date);
    }
    
 
    /**
     * 添加房间机器人配置记录
     * @param int RoomID  房间ID
     * @param int KindID  游戏种类ID
     * @param int RoomType 房间类型
     * @return array
     * 
     * */
    public function addRobotGameMoney($RoomID,$KindID,$RoomType){
        return $this->objDataChangeLogsDAL->addRobotGameMoney($RoomID,$KindID,$RoomType);
        
    }

    /**
     * 插入财富冻结日志
     * @RoleID			INT,			--角色ID
     * @CaseSerial		BIGINT,			--案件编号,可重复
     * @iMoney			BIGINT,			--冻结数量(金币)
     * //@iFwMoney		BIGINT,			--冻结数量(龙币)
     * @Step			TINYINT,		--状态,0:冻结,1:申请返还,2:已返还3:拒绝
     * @LoginName		VARCHAR(16),	--返还给玩家昵称
     * @SysUserName		VARCHAR(16),	--冻结操作人
     * @Remarks			VARCHAR(256)	--备注
     */
    function insertLockMoneyLogs($iRoleID, $iCaseSerial, $iMoney, /*$iFwMoney,*/ $Step, $LoginName, $SysUserName, $Remarks)
    {
        return $this->objDataChangeLogsDAL->insertLockMoneyLogs($iRoleID, $iCaseSerial, $iMoney, /*$iFwMoney,*/ $Step, $LoginName, $SysUserName, $Remarks);
    }

    /**
     *
     */
    function getUserVipDetailCount($params){
        return $this->objDataChangeLogsDAL->getUserVipDetailCount($params);
    }

    /**
     *
     */
    function getUserVipDetailList($params,$flag=0){
        return $this->objDataChangeLogsDAL->getUserVipDetailList($params,$flag);
    }

    /**清零游戏排行
     * @param $RoleID
     * @param $KindID
     * @param $RoomType
     * @return mixed
     */
    function setUserGameDataTotalMoney($RoleID,$KindID,$RoomType){
        return $this->objDataChangeLogsDAL->setUserGameDataTotalMoney($RoleID,$KindID,$RoomType);
    }

    /**
     * @param $RoleID
     * @param $KindID 为0 则返回全部
     */
    function getUserGameRankInfo($RoleID,$KindID){
        return $this->objDataChangeLogsDAL->getUserGameRankInfo($RoleID,$KindID);
    }
    /*
     * @param $AddTime 查询日期
     */
    function getActiveUserNumber($AddTime){
        return $this->objDataChangeLogsDAL->getActiveUserNumber($AddTime);
    }
    
    
    function getUserDayOut($RoldId,$startDate,$endDate,$changetype){
        return $this->objDataChangeLogsDAL->getUserDayOut($RoldId,$startDate,$endDate,$changetype);
    }


/*
 *获取在线用户信息
 */
    function getOnlineUserInfo($RoleID,$KindID){
        return $this->objDataChangeLogsDAL->getOlineUser($RoleID,$KindID);
    }


    function exitsTable($tablename){
        return $this->objDataChangeLogsDAL->exitsTable($tablename);
    }


    function getRankStatus($roleId){
        return $this->objDataChangeLogsDAL->getRankStatus($roleId);
    }

    

    function setRankStatus($roleId,$state){
        return $this->objDataChangeLogsDAL->setRankStatus($roleId,$state);
    }

    function setNickName($roleId, $nickname) {
        return $this->objDataChangeLogsDAL->setNickName($roleId,$nickname);
    }

    function addBankMoneyTop($roleid, $nickname, $totalmoney, $headurl) {
        return $this->objDataChangeLogsDAL->addBankMoneyTop($roleid, $nickname, $totalmoney, $headurl);
    }
    function editBankMoneyTop($roleid, $nickname, $totalmoney, $headurl) {
        return $this->objDataChangeLogsDAL->editBankMoneyTop($roleid, $nickname, $totalmoney, $headurl);
    }
    function deleteBankMoneyTop($roleid) {
        return $this->objDataChangeLogsDAL->deleteBankMoneyTop($roleid);
    }
}
?>