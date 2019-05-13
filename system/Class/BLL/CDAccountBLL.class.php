<?php
require ROOT_PATH . 'Class/DAL/CDAccountDAL.class.php';

class CDAccountBLL
{
	private $objCDAccountDAL = NULL;
	public function __construct()
	{
		$this->objCDAccountDAL = new CDAccountDAL();  
	}
	
	/**获取用户手机号码
	 * 
	 */
    public function getUserPhone()
    {
    	return $this->objCDAccountDAL->getUserPhone();
    }


    public function getProxyId($RoleId)
    {
        return $this->objCDAccountDAL->getProxyId($RoleId);
    }


    public function setProxyId($RoleId,$ProxyId){

        return $this->objCDAccountDAL->setProxyId($RoleId,$ProxyId);
    }


}

?>