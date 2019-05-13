<?php
require_once __DIR__.'/DALBase.php';

class CDAccountDAL extends DALBase
{
	public function __construct()
	{
		parent::__construct();
		$this->arrConfig=unserialize(SYS_CONFIG);
		parent::initDBObj(0,$this->arrConfig['MapType']['CDAccount'],true);
	}
	
	/**获取用户手机号码
	 *
	 */
	public function getUserPhone()
	{
		$arrResults=$this->objCDAccountDB->fetchAllAssoc("P_Accounts_SelectMobile","");
		return $arrResults;
	}


    public function getProxyId($RoleId)
    {
        $params = array(
            array($RoleId, SQLSRV_PARAM_IN)
        );
        $arrReturns = $this->objCDAccountDB->fetchAssoc("P_Accounts_SelectAgent", $params);
        return $arrReturns;
    }


    public function setProxyId($RoleId,$ProxyId)
    {
        $params = array(
            array($RoleId, SQLSRV_PARAM_IN),
            array($ProxyId, SQLSRV_PARAM_IN)
        );
        $arrReturns = $this->objCDAccountDB->fetchAssoc("P_Accounts_UpdateAgent", $params);
        return $arrReturns;
    }
}