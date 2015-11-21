<?php
if($_SESSION['lang']=="eng")
	$name = "policy";
else
	$name = "policy-2";

$content=DBConnect::retrieve_value("SELECT detail from contents WHERE name='$name'");
$smarty->assign("content",$content);
$smarty->display('policy-popup.tpl');
?>


<a href="JavaScript:newPopup('http://www.quackit.com/html/html_help.cfm');"><?php echo funcs::getText($_SESSION['lang'], '$plc_popup1');?></a>
