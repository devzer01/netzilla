<?php
//check permission type//
$permission_lv = array(1);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

$period = array('today','week','month');
$days_before = 1;
if(!in_array($_GET['r'], $period))
{
	header("location: ?action=admin_coin_statistics&r=today");
}
else
{
	switch($_GET['r'])
	{
		case 'month':
			$days_before = 30;
		break;
		case 'week':
			$days_before = 7;
		break;
		default :
			$days_before = 1;
			break;
	}
}

if(!isset($_GET['next']))
	SmartyPaginate::reset();

//smarty paging
SmartyPaginate::connect();
SmartyPaginate::setLimit(SEARCH_RESULTS_PER_PAGE); //smarty paging set records per page
SmartyPaginate::setPageLimit(SEARCH_RESULTS_TOTAL_PAGES); //smarty paging set limit pages show
SmartyPaginate::setUrl("?action=".$_GET['action']."&r=".$_GET['r']); //smarty paging set URL

if(!isset($_GET['next']))
	SmartyPaginate::setCurrentItem(1); //go to first record

$result = funcs::getTopSpendCoinUser($days_before,SmartyPaginate::getCurrentIndex(),SmartyPaginate::getLimit());

SmartyPaginate::setTotal($result['total']);
SmartyPaginate::assign($smarty);

$smarty->assign('userrec',$result['data']);
$smarty->assign('period',$_GET['r']);

$smarty->display('admin.tpl');
?>