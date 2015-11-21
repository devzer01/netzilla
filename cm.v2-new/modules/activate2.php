<?php

$username = $_GET['username'];
$password = $_GET['password'];

if(isset($_GET['code']))
	$code = $_GET['code'];
else
	$code = 0;
	
if(isset($_GET['adv']))
	$adv = $_GET['adv'];
else
	$adv = 0;
	
$_SESSION['sess_password'] = $password;

$userExist = funcs::checkMember($username, $password, $code, $adv);
//$checkAdv = funcs::checkAdvertise($username);
//$checkValid = funcs::checkvalidated($username);

if ($userExist == 1 && funcs::checkvalidated($username) != '') {
	
	funcs::loginSite($username, $password);	//automatic login
	header("location: .");	//go to first page
}

elseif ($userExist == 1 && funcs::checkAdvertise($username) == 2) {
	
	$smarty->assign('text', funcs::getText($_SESSION['lang'], '$activate_ready'));	//show activate error
	$smarty->display('index.tpl');
}

elseif ($userExist == 1 && !funcs::checkvalidated($username) && $adv == funcs::checkAdvertise($username)) {	//check activate complete?
	
	funcs::activateMember2($username);
	
	if (!funcs::checkmobile($username)) {

		if ($adv == '1') {

			funcs::loginSite($username, $password);	//automatic login
			header("location: ?action=validCode2");	//go to first page
		}
		elseif ($adv == '2') {

			funcs::loginSite($username, $password);	//automatic login
			header("location: .");	//go to first page
		}
		else {

			$smarty->assign('text', funcs::getText($_SESSION['lang'], '$activate_ready'));	//show activate error
			$smarty->display('index.tpl');
		}
	}
				
	else {
			
		if ($adv == '1') {

			funcs::loginSite($username, $password);	//automatic login
			header("location: ?action=validCode2");	//go to first page
		}	
		elseif ($adv == '2') {
			
			funcs::loginSite($username, $password);	//automatic login
			header("location: .");	//go to first page
		}
		else {
			
			funcs::loginSite($username, $password);	//automatic login
			header("location: ?action=validCode");	//go to first page				
		}
	}		
}

else {

	$smarty->assign('text', funcs::getText($_SESSION['lang'], '$activate_alert'));	//show activate error
	//select template file//
	$smarty->display('index.tpl');
}


/*
$stats = '..1a..';
echo $stats;

file_put_contents('../status_test.txt',$stats);

echo <<<END_1
<script type="text/javascript">
var wert = 2;
alert("Ende " + wert);
</script>
END_1;
*/

?>