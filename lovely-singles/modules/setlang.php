<?php
session_start();
if(isset($_REQUEST['lang']) && $_REQUEST['lang']!=""){
	
	if(isset($_SESSION['lang'])){
		session_unregister('lang');
	}

	$_SESSION['lang'] = $_REQUEST['lang'];
	
}else{
	if(!isset($_SESSION['lang'])){
		$_SESSION['lang'] = 'ger';
	}
}

?>