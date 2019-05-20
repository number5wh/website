<?php
require_once ROOT_PATH . 'Common/DALCache.class.php';
/**
 * 操作操作类的基类
 */
class DALBase
{
	protected $arrConfig = null;
	
    protected $objMemcache = null;	
    
	//客户端登陆
	protected $strSelectKeyDown = "SelectKeyDown_";
	//后台
	protected $strSelectKey = "SelectKey_";
    protected $strSelectKeySys = "SelectKeySys_";
    protected $strSelectAllKeySys = "SelectAllKeySys_";
    protected $strSelectPageKeySys = "SelectPageKeySys_";
    
    //接口
    protected $strSelectKeyDC = "SelectKeyDC_";
    protected $strSelectKeyAS = "SelectKeyAS_";
    protected $strSelectKeyOS = "SelectKeyOS_";
    
	//创建缓存实例
    public function __construct()
    {    
        $this->objMemcache = $this->getMemcacheObj();
    }
    
    
    /**
     * 得到缓存操作类
     */
    public function getMemcacheObj()
    {
        $arrSysConfig = unserialize(SYS_CONFIG);

        $objServerInfos = new MemcacheServerInfo();
        foreach ($arrSysConfig['MEMCACHED'] as $memCacheds)
        {
            $objServerInfos->addServer(key($memCacheds),current($memCacheds));
        }
        return DALCache::getInstance($objServerInfos);
    }


    /**
     * 添加分页数据到缓存
     */
    public function setPageData2Cache($strKeyMain,$strKeySlave,$arrValue,$iCompress=0, $iExpire=0)
    {
        $arrKeys = $this->objMemcache->get($strKeyMain);//SelectPageKey_GroupStructureDAL228
        if($arrKeys)
        {
            //加入新的key
            $arrKeys[] = $strKeySlave;
            $this->objMemcache->set($strKeyMain,$arrKeys,$iCompress,$iExpire);
        }
        else
        {
            //加入新的key
            $this->objMemcache->set($strKeyMain,array($strKeySlave),$iCompress,$iExpire);
        }
        //加入新的缓存
        $this->objMemcache->set($strKeySlave,$arrValue,$iCompress,$iExpire);
    }


    /**
     * 删除当前所有分页缓存
     *
     * @param mixed $strKeyMain
     * @return
     */
    public function delPageDataFromCache($strKeyMain)
    {
        $arrKeys = $this->objMemcache->get($strKeyMain);
        if($arrKeys)
        {
            foreach ($arrKeys as $strKey)
            {
                $this->objMemcache->delete($strKey);
                /*if(!$this->objMemcache->delete($strKey))
                {
                    return FALSE;
                }*/
            }
        }
        return $this->objMemcache->delete($strKeyMain);
    }
    
    public  function arrReplaceKey($arr,$keyMap){
        $brr = array();
        foreach($arr as $key => $val){
            if(isset($keyMap[$key])){
                $brr[$keyMap[$key]] = $val;
            }else{
                $brr[$key] = $val;
            }
        }
        return $brr;
    }
    

    public function arrListReplaceKey(&$arr,$keyMap){
        if(empty($arr))
            return null;
        foreach($arr as $key => $val){
            $arr[$key] = self::arrReplaceKey($val,$keyMap);
        }
        return $arr;
    }
}
//启动session
//session_start();
