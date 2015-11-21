<?php

class dbo {
	
	/**
	 * 
	 * @var PDO
	 */
	protected $dbo = null;
	
	public function __construct()
	{
		$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
		$this->dbo = new PDO("mysql:host=10.0.0.2;dbname=flirt48.net", "root", "tyZB[Tp.zsX^u", $options);
		$this->dbo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	
	public function __destruct()
	{
		$this->dbo = null;
	}
	
}
