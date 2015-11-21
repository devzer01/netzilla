<?php
//check permission type//
$permission_lv = array(1);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission
$site_configs_filename = "classes/site_configs.inc.php";

if(!defined("SMS_PROVIDER"))
{
	DBConnect::execute_q("INSERT INTO config (`name`, `value`) VALUES ('SMS_PROVIDER', 'smscountry')");
	define('SMS_PROVIDER', 'smscountry');
	if(file_exists($site_configs_filename))
	{
		unlink($site_configs_filename);
	}
}

if(isset($_POST['set_default']) && ($_POST['set_default']=="1"))
{
	if(isset($_POST['smscountry']) && ($_POST['smscountry']!=""))
	{
		DBConnect::execute_q("UPDATE config SET `value`='smscountry' WHERE `name`='SMS_PROVIDER'");
	}
	elseif(isset($_POST['clickatell']) && ($_POST['clickatell']!=""))
	{
		DBConnect::execute_q("UPDATE config SET `value`='clickatell' WHERE `name`='SMS_PROVIDER'");
	}
	elseif(isset($_POST['budgetsms']) && ($_POST['budgetsms']!=""))
	{
		DBConnect::execute_q("UPDATE config SET `value`='budgetsms' WHERE `name`='SMS_PROVIDER'");
	}

	if(file_exists($site_configs_filename))
	{
		unlink($site_configs_filename);
	}
	header("location: ?action=".$_GET['action']);
	exit;
}
$smarty->display('admin.tpl');
?> 