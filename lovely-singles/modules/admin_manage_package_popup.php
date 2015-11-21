<?php
$permission_lv = array(1);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

if(isset($_POST['price']) && isset($_POST['coin']))

{
	if($_POST['id']!="")
	{
		//edit
		$sql = "update coin_package set currency_price=$_POST[price], coin=$_POST[coin] where id=".$_POST[id];
		
		if(funcs::executeCurrency($sql)) {
			$smarty->assign('execute_status','completed');
		}
		else {
			$smarty->assign('execute_status','incompleted');
		}
	}
	else
	{
		//add
		$sql = "insert into coin_package (id, currency_type, currency_price, coin) values('', $_POST[type], $_POST[price], $_POST[coin])";
		
		if (funcs::executeCurrency($sql)) {
			$smarty->assign('execute_status','completed');
		}
		else {
			$smarty->assign('execute_status','incompleted');
		}
	}
	
	
	
}
elseif($_GET['package_id'])
{
	$package_rate = funcs::getExchangeRate($_GET['package_id']);
	$smarty->assign('package_rate', $package_rate);
}
else
{
	//
}

$smarty->display('admin_manage_package_popup.tpl');
?>