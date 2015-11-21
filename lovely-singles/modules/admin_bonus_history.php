<?php
//check permission type//
$permission_lv = array(1);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

if(!isset($_GET['next']))
	SmartyPaginate::reset();

//smarty paging
SmartyPaginate::connect();
SmartyPaginate::setLimit(MESSAGE_RECORD_LIMIT); //smarty paging set records per page
SmartyPaginate::setPageLimit(MESSAGE_PAGE_LIMIT); //smarty paging set limit pages show
SmartyPaginate::setUrl("?action=".$_GET['action']); //smarty paging set URL

if(!isset($_GET['next']))
	SmartyPaginate::setCurrentItem(1); //go to first record

$result = funcs::getBonusHistory(SmartyPaginate::getCurrentIndex(),SmartyPaginate::getLimit());

SmartyPaginate::setTotal($result['total']);
SmartyPaginate::assign($smarty);

$smarty->assign('userrec',$result['data']);
$smarty->assign('period',$_GET['r']);


$smarty->display('admin.tpl');
?>