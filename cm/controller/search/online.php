<?php 

$members = getOnlineMembers();

$smarty->assign('members', $members);
$smarty->display('private/searchresults.tpl');