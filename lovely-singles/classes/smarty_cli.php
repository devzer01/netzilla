<?php
require_once('libs/Smarty.class.php');

class smarty_web extends Smarty
{
	function smarty_web()
	{		
		parent::__construct();
		$this->config_dir    =  BASE_DIR . '/configs';
		$this->template_dir	 =  BASE_DIR . '/templates';
		$this->compile_dir   =  BASE_DIR . '/templates_c';			
	}
}
?>