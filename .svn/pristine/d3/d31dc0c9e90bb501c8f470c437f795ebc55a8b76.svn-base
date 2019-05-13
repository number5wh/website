<?php
require ROOT_PATH . 'Class/DAL/SafetyServiceDAL.class.php';
class SafetyServiceBLL
{
    private $objSafetyServiceDAL = NULL;
    
    public function __construct()
    {
        $this->objSafetyServiceDAL = new SafetyServiceDAL();
    }
    /**
     * 
     */
    public function GetHomeCaijinMsg()
    {
    	return $this->objSafetyServiceDAL->DCGetHomeCaijinMsg();
    }
    
}
?>