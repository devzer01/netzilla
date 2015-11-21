<?php
//check permission type//
$permission_lv = array(1);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

//delete
if(isset($_GET['del_id']))
{
	if(funcs::deleteCoinPackage($_GET['del_id']))
	{
		header("Location: ".$_SERVER['HTTP_REFERER']);
		exit;
	}
}
elseif(isset($_POST['currency_type']))
{
	//update
	funcs::updateConfigCurrency($_POST['currency_type']);
	header("Location: ".$_SERVER['HTTP_REFERER']);
	exit;
}
elseif(isset($_POST['price']) && isset($_POST['coin']))
{
	if($_POST['id']!="")
	{
		//edit
		$sql = "update coin_package set currency_price=$_POST[price], coin=$_POST[coin] where id=".$_POST[id];
		DBConnect::execute_q($sql);
	}
	else
	{
		//add
		if(!isset($_POST['from_signup_date']))
		{
			$sql = "insert into coin_package (id, currency_type, currency_price, coin) values('', 0, $_POST[price], $_POST[coin])";
		}
		else
		{
			$sql = "insert into coin_package (id, currency_type, currency_price, coin, from_signup_date) values('', 0, ".$_POST['price'].", ".$_POST['coin'].", '".$_POST['from_signup_date']."')";
		}
		DBConnect::execute_q($sql);
	}
}

//get currency name from config table
$confCurrency = funcs::getConfigCurrency();

//get all currency name
$recName = funcs::getCurrencyName();

//package
$result = funcs::getCurrency(SmartyPaginate::getCurrentIndex(),SmartyPaginate::getLimit());

SmartyPaginate::setTotal($result['total']);
SmartyPaginate::assign($smarty);

$smarty->assign('managepackage',$result['data']);

//send data to template//
// $smarty->assign('managepackage',$rePack);
$smarty->assign('confdata', $confCurrency);
$smarty->assign('currname',$recName);
//select template file//
$smarty->display('admin.tpl');
?> 