<?php
$result = DBConnect::assoc_query_2D("SELECT id, username, picturepath FROM ".TABLE_MEMBER." WHERE isactive = 1 ORDER BY signup_datetime DESC LIMIT ".NEWEST_MEMBERS_LIMIT);

$smarty->assign("NewestMembers", $result);
$smarty->display('index.tpl');
?>