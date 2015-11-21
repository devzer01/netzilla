<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     modifier.username.php
 * Type:     modifier
 * Name:     username
 * Purpose:  conceal the admin username and return ADMIN_USERNAME_DISPLAY
 * -------------------------------------------------------------
 */
function smarty_modifier_username($string)
{
    if (strtolower($string) == strtolower(ADMIN_USERNAME)) return ADMIN_USERNAME_DISPLAY;
    return $string;
}

function smarty_modifier_smiley($msg)
{
	$needles = array();
	$replace = array();
	
	require_once 'lib/dbo/config.php';
	
	$dbo_config = new dbo_config();
	$smiles = $dbo_config->getSmilies();
	
	foreach ($smiles as $r) {
		$needles[] = "/" . preg_quote($r['text_version']) . "/";
		$replace[] = "<img src='" . URL_WEB . "/" . $r['image_path'] . "' height='35' width='35' />";
	}
	
	return preg_replace($needles, $replace, $msg);
}

function smarty_modifier_gift($gift)
{
	if ($gift == 0) return "";
	require_once 'lib/dbo/config.php';
	
	$dbo_config = new dbo_config();
	$gift_path = $dbo_config->getGift($gift);
	
	return '<img src="' . URL_WEB . "/" . $gift_path . '" height="100"/>';
}