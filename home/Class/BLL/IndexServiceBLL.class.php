<?php
require ROOT_PATH . 'Class/DAL/IndexServiceDAL.class.php';
class IndexServiceBLL
{
    private $serviceDAL = NULL;
    
    public function __construct()
    {
        $this->serviceDAL = new IndexServiceDAL();
    }
    /**
     * 
     */
    public function getPagerHappyBeanSortTop100()
    {
    	return $this->serviceDAL->getPagerHappyBeanSortTop100();
    }

    public function getMsgInfo()
    {
        return $this->serviceDAL->getMsgInfo();
    }
    
}
?>