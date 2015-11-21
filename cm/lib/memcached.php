<?php

class nzmemcache {
	
	static $instance = null;
	
	/**
	 * 
	 * @var Memcached
	 */
	private $obj = null;
	
	private function __construct(){ 
    	$this->obj = new Memcached(); 
    } 

    public function connect($host , $port){ 
                $servers = $this->obj->getServerList(); 
                if(is_array($servers)) { 
                        foreach ($servers as $server) 
                                if($server['host'] == $host and $server['port'] == $port) 
                                        return true; 
                } 
    	return $this->obj->addServer($host , $port); 
    } 
    
    public function set($key, $val, $expire = 300)
    {
    	return $this->obj->set($key, $val, $expire);
    }
    
    public function get($key)
    {
    	return $this->obj->get($key);
    }
    
    public function getResultCode()
    {
    	return $this->obj->getResultCode();
    }
	
    /**
     * @return nzmemcache
     */
	public static function getInstance()
	{
		if (self::$instance === null) self::$instance = new nzmemcache();
		return self::$instance;
	}
}