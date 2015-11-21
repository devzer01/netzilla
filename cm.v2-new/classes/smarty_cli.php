<?php
require_once('libs/Smarty.class.php');

class smarty_web extends Smarty
{
	function smarty_web()
	{		
		parent::__construct();
		$this->config_dir    =  BASE_DIR . SITE.'configs';
		$this->template_dir	 =  BASE_DIR . SITE.'templates';
		$this->compile_dir   =  BASE_DIR . SITE.'templates_c';			
	}
}
?>