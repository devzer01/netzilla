<?php
//check permission type//
$permission_lv = array(1);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

if (isset($_GET['sub_action']) && $_GET['sub_action'] == 'upload') {
	
	$sql = "INSERT INTO gift_category (name) VALUES ('" . mysql_real_escape_string($_POST['name']) . "')";
	mysql_query($sql);
}

$cats = array();

$rs = mysql_query("SELECT * FROM gift_category ");
while ($r = mysql_fetch_assoc($rs)) {
	$cats[] = $r;
}
$smarty->assign('cats', $cats);

//select template file//
$smarty->display('admin.tpl');
?>