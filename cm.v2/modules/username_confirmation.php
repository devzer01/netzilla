<?php
if(USERNAME_CONFIRMED)
{
	header("location: .");
}
else
{
	$profile = DBConnect::assoc_query_1D("SELECT * FROM member WHERE id=".$_SESSION['sess_id']);
	$usernames = array(
						$profile['username'],
						$profile['facebook_username'],
						$profile['forname'],
						$profile['forname'].date("Y", strtotime($profile['birthday'])),
						$profile['surname'],
						$profile['surname'].date("Y", strtotime($profile['birthday'])),
						$profile['forname']."_".$profile['surname'],
						$profile['surname']."_".$profile['forname']
					);
	$total = count($usernames);
	for($i=0; $i<$total; $i++)
	{
		$usernames[$i] = strtolower($usernames[$i]);
		if(DBConnect::retrieve_value("SELECT 1 FROM member WHERE username='".$usernames[$i]."' AND username!='".$profile['username']."'"))
		{
			unset($usernames[$i]);
		}
		elseif((strlen($usernames[$i])<6) || (strlen($usernames[$i])>30))
		{
			unset($usernames[$i]);
		}
	}

	$usernames = array_unique($usernames);

	if(isset($_POST['username']))
	{
		if($_POST['username']!="")
		{
			$username = $_POST['username'];
		}
		else
		{
			$username = $_POST['username2'];
		}

		if(DBConnect::retrieve_value("SELECT 1 FROM member WHERE username='".$username."' AND username!='".$_SESSION['sess_username']."'"))
		{
			$smarty->assign('error_message', "Dieser Nickname ist bereits im Einsatz.");
		}
		elseif(preg_match('/[^a-z0-9ÄäÖöÜüß._]/i',$username))
		{
			$smarty->assign('error_message', funcs::getText($_SESSION['lang'], '$err_usrname_format'));
		}
		elseif((strlen($username)<6) || (strlen($username)>30))
		{
			$smarty->assign('error_message', "Der Benutzername muß mindestens 6 Zeichen lang sein.");
		}
		else
		{
			DBConnect::execute_q("UPDATE member SET username='".$username."', username_confirmed=1 WHERE id=".$_SESSION['sess_id']);
			funcs::logoutSite();
			funcs::loginSite($username, $profile['password'],'1');
			$_SESSION['USERNAME_CONFIRMED'] = 1;
			header("location: ?action=profile");
			exit;
		}
	}

	$smarty->assign('usernames', $usernames);
	$smarty->display('index.tpl');
}
?>