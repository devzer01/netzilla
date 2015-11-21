<?php

require_once 'modifier.username.php';

class basesmarty {
	
	
	/**
	 * 
	 * @var Smarty
	 */
	private static $instance = null;

	private function __construct()
	{
		
	}
	
	/**
	 * 
	 * @return Smarty
	 */
	public static function getInstance()
	{
		if (self::$instance === null) {
			self::$instance = new Smarty();
			self::$instance->setTemplateDir('templates/');
			self::$instance->setCompileDir('templates_c/');
			self::$instance->setConfigDir('configs/');
			self::$instance->setCacheDir('cache/');
			self::$instance->registerPlugin('modifier', 'username', 'smarty_modifier_username');
			self::$instance->registerPlugin('modifier', 'smiley', 'smarty_modifier_smiley');
			self::$instance->registerPlugin('modifier', 'gift', 'smarty_modifier_gift');
		}
		return self::$instance;
	}
	
}