<?php
//check permission type//
$permission_lv = array(1);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

if (isset($_GET['sub_action']) && $_GET['sub_action'] == 'upload') {
	$text_version = $_POST['text_version'];
	$file_name = $_FILES['emoticon']['name'];
	$tmp_name = $_FILES['emoticon']['tmp_name'];
	move_uploaded_file($tmp_name, EMOTICON_PATH . "/" . $file_name);
	
	$doc_root = preg_quote($_SERVER['DOCUMENT_ROOT']);
	$relative_path = preg_replace("#{$doc_root}/#", "", EMOTICON_PATH);
	
	$sql = "INSERT INTO emoticon (text_version, image_path, active) VALUES ('" . mysql_real_escape_string($text_version) . "', '" . mysql_real_escape_string($relative_path . "/" . $file_name) . "', 1)";
	mysql_query($sql);
} elseif (isset($_GET['sub_action']) && $_GET['sub_action'] == 'del') {
	$sql = "DELETE FROM emoticon WHERE id = " . mysql_real_escape_string($_GET['id']);
	mysql_query($sql);
}


$emoticons = array();

$rs = mysql_query("SELECT id, text_version, image_path, active FROM emoticon");
while ($r = mysql_fetch_assoc($rs)) {
	$emoticons[] = $r;
}
$smarty->assign('emoticons', $emoticons);

//select template file//
$smarty->display('admin.tpl');
?>