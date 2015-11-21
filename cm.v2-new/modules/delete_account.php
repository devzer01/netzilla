<?php
$permission_lv = array(4);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

if($_GET['confirm']==1)
{
	if($_SESSION['sess_id'])
	{
		DBConnect::execute("INSERT INTO delete_account (userid, delete_datetime) VALUES (".$_SESSION['sess_id'].", NOW())");
		funcs::logoutSite();
		header_remove("Location");
		header_remove("location");
		header('Location: '.$_SERVER['REQUEST_URI']);
	}
}
$smarty->display('index.tpl');
?>