<?php 

class Registry {
	
	private $_registry = array();
	
	private static $_instance = null;
	
	private function __construct()
	{
		
	}
	
	public function set($key, $value)
	{
		$this->_registry[$key] = $value;
	}
	
	public function get($key)
	{
		if (isset($this->_registry[$key])) return $this->_registry[$key];
		return null;
	}
	
	public static function getInstance()
	{
		if (self::$_instance == null) {
			self::$_instance = new Registry();
		}
		
		return self::$_instance;
	}
	
}