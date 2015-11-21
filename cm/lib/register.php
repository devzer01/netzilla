<?php 

function registerValidate()
{
	$_POST['phone_number'] = trim($_POST['phone_number']);
	$_POST['username'] = trim($_POST['username']);
}

function registerMember($username, $password)
{
	
	
	$save[TABLE_MEMBER_BIRTHDAY] = $_POST['year'].'-'.$_POST['month'].'-'.$_POST['date'];
	
	//$save['zodiac'] = funcs::getZodiac($_POST['month'].'-'.$_POST['date']);
	$save[TABLE_MEMBER_SIGNUP_DATETIME] = funcs::getDateTime();
	$save['lookmen'] = $save['gender']=="1"?"0":"1";
	$save['lookwomen'] = $save['gender']=="1"?"1":"0";
	
	if (isset($_SESSION['ref'])) {
		$save['ref'] = $_SESSION['ref'];
	}
	
	if (!isset($save['ref']) && isset($_COOKIE['ref'])) {
		$save['ref'] = $_COOKIE['ref'];
	}
	
	//reffered_by_member_id
	if (isset($_SESSION['token'])) {
		$save['refby_member_id'] = funcs::decryptToken($_SESSION['token']);
	}
	
	switch($_GET['type'])
	{
		case 'membership':
		default:
			$save[TABLE_MEMBER_ISACTIVE] = 0;
			$save[TABLE_MEMBER_STATUS] = 4;	//fix status
			$save[TABLE_MEMBER_VALIDATION] = funcs::randomPassword(6);
			break;
	}
}