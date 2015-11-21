<?php 

$members = getNewestMembers();
$smarty->assign('members', $members);
$smarty->display('private/searchresults.tpl');