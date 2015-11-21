<?php
	
require_once('libs/Smarty.class.php');

class smarty_web extends Smarty
{
	function smarty_web()
	{		
		new Smarty();
		$config_dir      =  'configs';
		$template_dir	 =  'templates';
		$compile_dir     =  'templates_c';			
	}
}

?>