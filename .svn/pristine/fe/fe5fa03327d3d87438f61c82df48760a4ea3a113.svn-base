<?php

/**
 * @desc DALCache
 */
class DALCache
{
    /**
     * Resources of the opend memcached connections
     * @var array [memcache objects]
     */
    public $arrMemObjects = array();

    /**
     * the count of Memcache Object
     * @var int
     */
    private $iMemCount = 0;

    /**
     * Services list
     * @var array('host'=>'port')
     */
    public static $arrServers = array();

    /**
     * Quantity of servers used
     * @var int
     */
    public static $iInstance = 0;
    
    
    public static $objFileCacheInstance = NULL;

    /**
     * Singleton to call from all other functions
     */
    public static function getInstance(MemcacheServerInfo $serverInfos)
    {
        //Write here where from to get the servers list from, like
        // global $servers
        //self::$arrServers = $arrServerList;

        self::$iInstance || self::$iInstance = new DALCache($serverInfos);
        return self::$iInstance;
    }
    
    /**
     * 得到附件缓存操作对象
     * @param MemcacheServerInfo $serverInfos	缓存服务器信息对象
     */
    public static function getFileCacheInstance(MemcacheServerInfo $serverInfos)
    {
        self::$objFileCacheInstance || self::$objFileCacheInstance = new DALCache($serverInfos);
        return self::$objFileCacheInstance;
    }

    /**
     * Accepts the 2-d array with details of memcached servers
     *
     * @param array $servers
     */
    private function __construct(MemcacheServerInfo $serverInfos)
    {
        if (!$serverInfos->getServers())
        {
            trigger_error('No memcache servers to connect', E_USER_WARNING);
        }

        $MemcacheServerInfos = $serverInfos->getServers();
        //var_dump($MemcacheServerInfos);
        foreach ($MemcacheServerInfos as $server => $port)
        {
            $objMemcache = new Memcache;
            if ($objMemcache->pconnect($server, $port))
            {
                $objMemcache->strServer = $server;
                $objMemcache->iPort = $port;
                $this->arrMemObjects[] = $objMemcache;
            }
        }

        $this->iMemCount = count($this->arrMemObjects);

        if (!$this->iMemCount)
        {
            $this->arrMemObjects[0] = null;
        }
    }
    /**
     * Returns the resource for the memcache connection
     *
     * @param string $key
     * @return object memcache
     */
    private function getMemcacheLink($key)
    {
        $obj = null;
        $itval = 0;
        if ($this->iMemCount < 2)
        {
            //no servers choice
            $obj = $this->arrMemObjects[0];
        }
        else
        {
            $itval = (crc32($key) & 0x7fffffff) % $this->iMemCount;
            $obj = $this->arrMemObjects[$itval];
        }
        if(FALSE && $obj->getVersion()===FALSE)
        {
            $conn = new Memcache;
            if ($conn->pconnect($obj->strServer, $obj->iPort ,5))
            {
                $this->arrMemObjects[$itval]=$conn;
                $obj = $conn;
            }
        }
        return $obj;
    }

    /**
     * Clear the cache
     *
     * @return void
     */
    public function flush()
    {
        $iServerNum = $this->iMemCount;
        
        for ($i = 0; $i < $iServerNum; ++$i)
        {
            $this->arrMemObjects[$i]->flush();
        }
    }

    /**
     * Returns the value stored in the memory by it's key
     *
     * @param string $key
     * @return mix
     */
    public function get($key)
    {
        if($this->getMemcacheLink($key))
            return $this->getMemcacheLink($key)->get($key);
        else
            return false;
    }

    /**
     * Store the value in the memcache memory (overwrite if key exists)
     *
     * @param string $key
     * @param mix $var
     * @param bool $compress
     * @param int $expire (seconds before item expires)
     * @return bool
     */
    public function set($key, $var, $compress = 0, $expire = 0)
    {
        if($this->getMemcacheLink($key))
            return $this->getMemcacheLink($key)->set($key, $var, $compress ? MEMCACHE_COMPRESSED : null, $expire);
        else
            return false;
    }
    /**
     * Set the value in memcache if the value does not exist; returns FALSE if value exists
     *
     * @param sting $key
     * @param mix $var
     * @param bool $compress
     * @param int $expire
     * @return bool
     */
    public function add($key, $var, $compress = 0, $expire = 0)
    {
        if($this->getMemcacheLink($key))
            return $this->getMemcacheLink($key)->add($key, $var, $compress ? MEMCACHE_COMPRESSED : null, $expire);
        else
            return false;
    }

    /**
     * Replace an existing value
     *
     * @param string $key
     * @param mix $var
     * @param bool $compress
     * @param int $expire
     * @return bool
     */
    public function replace($key, $var, $compress = 0, $expire = 0)
    {
        if($this->getMemcacheLink($key))
            return $this->getMemcacheLink($key)->replace($key, $var, $compress ? MEMCACHE_COMPRESSED : null, $expire);
        else
            return false;
    }
    /**
     * Delete a record or set a timeout
     *
     * @param string $key
     * @param int $timeout
     * @return bool
     */
    public function delete($key, $timeout = 0)
    {
        if($this->getMemcacheLink($key))
            return $this->getMemcacheLink($key)->delete($key, $timeout);
        else
            return false;
    }
    /**
     * Increment an existing integer value
     *
     * @param string $key
     * @param mix $value
     * @return bool
     */
    public function increment($key, $value = 1)
    {
        if($this->getMemcacheLink($key))
            return $this->getMemcacheLink($key)->increment($key, $value);
        else
            return false;
    }

    /**
     * Decrement an existing value
     *
     * @param string $key
     * @param mix $value
     * @return bool
     */
    public function decrement($key, $value = 1)
    {
        if($this->getMemcacheLink($key))
            return $this->getMemcacheLink($key)->decrement($key, $value);
        else
            return false;
    }

}

/**
 * 
 * 缓存服务器信息类
 * @author wangkai
 *
 */
class MemcacheServerInfo
{
    private $arrServers = array();

    //添加一个Memcache服务器
    public function addServer($strServerAddr, $strServerPort)
    {
        $this->arrServers = array_merge($this->arrServers, array($strServerAddr => $strServerPort));
    }

    //得到Memcache服务器集合
    public function getServers()
    {
        if (empty($this->arrServers))
            return false;
        return $this->arrServers;
    }
}

?>