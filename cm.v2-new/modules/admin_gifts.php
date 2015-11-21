<?php
//check permission type//
$permission_lv = array(1);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

if (isset($_GET['sub_action']) && $_GET['sub_action'] == 'upload') {
	$coins = $_POST['coins'];
	$file_name = $_FILES['gift']['name'];
	$gift_category_id = mysql_real_escape_string($_POST['gift_category_id']);
	$tmp_name = $_FILES['gift']['tmp_name'];
	move_uploaded_file($tmp_name, GIFT_PATH . "/" . $file_name);
	
	$doc_root = preg_quote($_SERVER['DOCUMENT_ROOT']);
	$relative_path = preg_replace("#{$doc_root}/#", "", GIFT_PATH);
	
	$sql = "INSERT INTO gift (gift_category_id, coins, image_path, created, status) VALUES (" . $gift_category_id . ",'" . mysql_real_escape_string($coins) . "', '" . mysql_real_escape_string($relative_path . "/" . $file_name) . "', NOW(), 1)";
	mysql_query($sql);
} elseif (isset($_GET['sub_action']) && $_GET['sub_action'] == 'del') {
	$sql = "DELETE FROM gift WHERE id = " . mysql_real_escape_string($_GET['id']);
	mysql_query($sql);
} elseif (isset($_GET['sub_action']) && $_GET['sub_action'] == 'update_coins') {
	$sql = "UPDATE gift SET coins = " . $_POST['coins'] . " WHERE id = " . mysql_real_escape_string($_POST['gift_id']);
	mysql_query($sql);
	header("Content-Type: application/json");
	echo json_encode(array('status' => 0));
	exit;
}

//update_coins

$gifts = array();

$rs = mysql_query("SELECT id, coins, image_path, created FROM gift");
while ($r = mysql_fetch_assoc($rs)) {
	$gifts[] = $r;
}
$smarty->assign('gifts', $gifts);

$gift_categories = array();
$rs = mysql_query("SELECT id, name FROM gift_category");
while ($r = mysql_fetch_assoc($rs)) {
	$gift_categories[$r['id']] = $r['name'];
}

$smarty->assign('gift_categories', $gift_categories);


//select template file//
$smarty->display('admin.tpl');