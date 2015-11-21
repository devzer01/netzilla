<?php
require_once('libs/Smarty.class.php');

class smarty_web extends Smarty
{
	function smarty_web()
	{		
		new Smarty();
		if(isset($_SESSION['mobile_version']) && ($_SESSION['mobile_version']==1) && file_exists(SITE.'templates_mobile'))
		{
			$this->config_dir    =  SITE.'configs';
			$this->template_dir	 =  SITE.'templates_mobile';
			$this->compile_dir   =  SITE.'templates_mobile_c';
		}
		else
		{
			$this->config_dir    =  SITE.'configs';
			$this->template_dir	 =  SITE.'templates';
			$this->compile_dir   =  SITE.'templates_c';
		}
	}
}
?>