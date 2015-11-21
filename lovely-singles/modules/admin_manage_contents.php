<?php
//check permission type//
$permission_lv = array(1);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

if(!in_array($_GET['page'], array('terms','terms-2','imprint','policy','policy-2')))
{
	header("location: ?action=admin_manage_contents&page=terms");
}
else
{
	if($_POST['content'])
	{
		$content=mysql_real_escape_string($_POST['content']);
		if(DBConnect::retrieve_value("SELECT detail FROM contents WHERE name='".$_GET['page']."'"))
			DBConnect::execute_q("UPDATE contents SET detail='".$content."' WHERE name='".$_GET['page']."'");
		else
			DBConnect::execute_q("INSERT INTO contents (name, detail) VALUES ('".$_GET['page']."','".$content."')");
		header("location: ?action=admin_manage_contents&page=".$_GET['page']);
	}
	else
	{
		$content=DBConnect::retrieve_value("SELECT detail from contents WHERE name='".$_GET['page']."'");
		//select template file//
		$smarty->assign("content",$content);
		$smarty->assign("submenu", funcs::getText($_SESSION['lang'], '$admin_manage_contents'));
		$smarty->display('admin.tpl');
	}
}
?> 