<?php
//check permission type//
$permission_lv = array(1);	//define type permission can open this page.
funcs::checkPermission(&$smarty, $permission_lv);	//check permission

if((isset($_GET['user'])) && (isset($_GET['r'])))
{
	if(!isset($_GET['next']))
		SmartyPaginate::reset();

	//smarty paging
	SmartyPaginate::connect();
	SmartyPaginate::setLimit(SEARCH_RESULTS_PER_PAGE); //smarty paging set records per page
	SmartyPaginate::setPageLimit(SEARCH_RESULTS_TOTAL_PAGES); //smarty paging set limit pages show
	SmartyPaginate::setUrl("?action=".$_GET['action']."&user=".$_GET['user']."&r=".$_GET['r']); //smarty paging set URL

	if(!isset($_GET['next']))
		SmartyPaginate::setCurrentItem(1); //go to first record


		$smarty->assign('user',$_GET['user']); 

		$userid = funcs::getUserid($_GET['user']);
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
		}
		$result = funcs::getCoinLog($userid,$days_before,SmartyPaginate::getCurrentIndex(),SmartyPaginate::getLimit());

	SmartyPaginate::setTotal($result['total']);
	SmartyPaginate::assign($smarty);

	$smarty->assign('type_box', array(1 => "Admin", 2 => "VIP", 3 => "Premium", 4 => "Standard"));
	$smarty->assign('userrec',$result['data']);
	$smarty->assign('period',$_GET['r']);
}
else
{
	$smarty->assign('text', funcs::getText($_SESSION['lang'], '$coin_statistics'));
}

$smarty->display('admin.tpl');
?>