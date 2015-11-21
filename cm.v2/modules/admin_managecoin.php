<?php
//check permission type//
$permission_lv = array(1);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

$data = funcs::getCoinData();

$_POST['coinsms'] = isset($_POST['coinsms'])?trim($_POST['coinsms']):"";
$_POST['coinemail'] = isset($_POST['coinemail'])?trim($_POST['coinemail']):"";
$_POST['freecoins'] = isset($_POST['freecoins'])?trim($_POST['freecoins']):"";
$_POST['coinVerifyMobile'] = isset($_POST['coinVerifyMobile'])?trim($_POST['coinVerifyMobile']):"";

if($_POST['coinsms']!="" && $_POST['coinemail']!="" && $_POST['freecoins']!="" && $_POST['coinVerifyMobile']!="")
{
	if($_POST['freecoins']>100)
		$_POST['freecoins'] = 100;

	funcs::updateCoinPoint($_POST['coinsms'], $_POST['coinemail'], $_POST['freecoins'], $_POST['coinVerifyMobile']);
	header("Location: ".$_SERVER['HTTP_REFERER']);
	exit;
}

//send data to template//
$smarty->assign('managecoin',$data);
//select template file//
$smarty->display('admin.tpl');
?> 