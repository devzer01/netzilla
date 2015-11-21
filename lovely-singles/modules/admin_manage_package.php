<?php
//check permission type//
$permission_lv = array(1);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission


//smarty paging
SmartyPaginate::connect();
SmartyPaginate::setLimit(MESSAGE_RECORD_LIMIT); //smarty paging set records per page
SmartyPaginate::setPageLimit(MESSAGE_PAGE_LIMIT); //smarty paging set limit pages show
SmartyPaginate::setUrl("?action=".$_GET['action']); //smarty paging set URL

//delete
if(isset($_GET['del_id']))
{
	if(funcs::deleteCoinPackage($_GET['del_id']))
	{
		header("Location: ".$_SERVER['HTTP_REFERER']);
		exit;
	}
}

//get currency name from config table
$confCurrency = funcs::getConfigCurrency();

//update config currency
if(isset($_POST['currency_type']))
{
	//update
	funcs::updateConfigCurrency($_POST['currency_type']);
	header("Location: ".$_SERVER['HTTP_REFERER']);
	exit;
}	

//get all currency name
$recName = funcs::getCurrencyName();

//package
$result = funcs::getCurrency(SmartyPaginate::getCurrentIndex(),SmartyPaginate::getLimit());

SmartyPaginate::setTotal($result['total']);
SmartyPaginate::assign($smarty);

$smarty->assign('managepackage',$result['data']);
$smarty->assign('period',$_GET['r']);



//send data to template//
$smarty->assign('type_box', array(1 => "Admin", 2 => "VIP", 3 => "Premium", 4 => "Standard", 9 => "StudiAdmin"));
// $smarty->assign('managepackage',$rePack);
$smarty->assign('confdata', $confCurrency);
$smarty->assign('currname',$recName);
//select template file//
$smarty->display('admin.tpl');
?> 