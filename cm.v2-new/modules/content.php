<?php
$content=DBConnect::retrieve_value("SELECT detail from contents WHERE name='".$_GET['action']."'");
$smarty->assign("content",$content);
$smarty->display('index.tpl');
?>