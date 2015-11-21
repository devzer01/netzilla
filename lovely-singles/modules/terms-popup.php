<?php
if($_SESSION['lang']=="eng")
	$name = "terms";
else
	$name = "terms-2";

$content=DBConnect::retrieve_value("SELECT detail from contents WHERE name='$name'");
$smarty->assign("content",$content);
$smarty->display('terms-popup.tpl');
?>