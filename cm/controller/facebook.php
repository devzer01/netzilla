<?php 

$app->get('/username', function () use ($app, $smarty) {
	
	if (!isset($_SESSION['temp_user_id'])) $app->redirect(APP_PATH . "/");
	
	require_once 'lib/dbo/member.php';
	$dbo_member = new dbo_member();
	$profile = $dbo_member->getBasicMemberInfo($dbo_member->getUsername($_SESSION['temp_user_id']));
	
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
		if($dbo_member->isNewUsername($_SESSION['temp_user_id'], $usernames[$i])) unset($usernames[$i]);
		elseif((strlen($usernames[$i])<6) || (strlen($usernames[$i])>30)) unset($usernames[$i]);
	}
	
	$smarty->assign('usernames', $usernames);
	$smarty->display('public/facebook/username.tpl');
});

$app->post('/username', function () use ($app, $smarty) {
	
	$app->contentType('application/json');
	$username = $_POST['username'];
	require_once 'lib/dbo/member.php';
	$dbo_member = new dbo_member();
	
	if ($dbo_member->isNewUsername($_SESSION['temp_user_id'], $username)) {
		echo json_encode(array('status' => 1, 'error' => "Dieser Nickname ist bereits im Einsatz."));
		return;
	}
	
	if(preg_match('/[^a-z0-9ÄäÖöÜüß._]/i',$username))
	{
		echo json_encode(array('status' => 1, 'error' => "Dein Benutzername enthält ungültige Zeichen! Bitte wähle einen anderen Benutzernamen aus und versuche es erneut!"));
		return;
	}
	
	if((strlen($username)<6) || (strlen($username)>30))
	{
		echo json_encode(array('status' => 1, 'error' => "Der Benutzername muß mindestens 6 Zeichen lang sein."));
		return;
	}

	$dbo_member->setConfirmUsername($id, $username);
	$profile = $dbo_member->getBasicMemberInfo($dbo_member->getUsername($_SESSION['temp_user_id']));
	performLogin($username, $profile['password']);
	$_SESSION['USERNAME_CONFIRMED'] = 1;
	unset($_SESSION['temp_user_id']);
	$app->redirect(APP_PATH . "/profile");
});

$app->get("/error", function () use ($app, $smarty) {
	echo "Something went wrong";
});

$app->get("/", function () use ($app, $smarty) {
	
	if($_SESSION['state'] && ($_SESSION['state'] !== $_REQUEST['state'])) {
		$app->redirect(APP_PATH . "/facebook/error");
		return;
	}
	
	if (isset($_REQUEST['error'])) {
		$app->redirect(APP_PATH . "/facebook/error");
		return;
	}
	
	$code = $_REQUEST["code"];
	
	$token_url = "https://graph.facebook.com/oauth/access_token?client_id=". APP_ID ."&redirect_uri=". urlencode(MY_URL) ."?action=fblogin&client_secret=". APP_SECRET ."&code=". $code;	
	$response = file_get_contents($token_url);
	$params = null;
	parse_str($response, $params);
	
	$graph_url = "https://graph.facebook.com/me?access_token=".$params['access_token'];
	
	$user = json_decode(file_get_contents($graph_url));
	
	if(($user->id == "") || ($user->email == ""))
	{
		$app->redirect(APP_PATH . "/facebook/error");
		return;
	}
	
	$age_limit = strtotime(' -18 year', time());
	if(strtotime($user->birthday) > $age_limit)
	{
		$app->redirect(APP_PATH . "/facebook/error");
		//echo("Failed, you're too young to register. <meta http-equiv='refresh' content='5;url=http://www.".$domain."/'>");
		return;
	}
	
	require_once 'lib/dbo/member.php';
	$dbo_member = new dbo_member();
	$member = $dbo_member->getMemberByFacebookId($user->id);
	
	if ($member !== false && $member['isactive'] == 1) {
		performLogin($member['username'], $member['password']);
		$app->redirect(APP_PATH . "/profile/edit");
		return;
	} elseif ($member !== false && $member['isactive'] == 0) {
		$app->redirect(APP_PATH . "/facebook/error");
		return;
	}
	
	$member = $dbo_member->getMemberByEmail($user->email);
	
	if ($member !== false && $member['isactive'] == 1) {
		$dbo_member->setFacebookParams($member['username'], $user->id, $user->username, $params['access_token']);
		performLogin($member['username'], $member['password']);
		$app->redirect(APP_PATH . "/profile/edit");
		return;
	} elseif ($member !== false && $member['isactive'] == 0) {
		$app->redirect(APP_PATH . "/facebook/error");
	}
	
	
	$ext = "";
	while($dbo_member->isUsername($user->username.$ext))
	{
		$ext = "_" . rand(10,99);
	}
	
	$username = $user->username.$ext;
	$password = randomPassword(12);
	$validation_code = randomPassword(6, "number");
				
	$mobile = 1;
	$gender = GENDER_MALE;
	if ($user->gender == "female") $gender = GENDER_FEMALE;
	
	$country = 0; $state = 0; $city = 0;
	if ($user->location) list($country, $state, $city) = getCountryStateCityFromLocation($user->location->name);
	elseif ($user->hometown) list($country, $state, $city) = getCountryStateCityFromLocation($user->hometown->name);

	$userid = $dbo_member->addMember($username, $password, $user->email, date("Y-m-d", strtotime($user->birthday)), $gender, $country);
	$dbo_member->setFirstLast($username, $user->first_name, $user->last_name);
	$dbo_member->setFacebookParams($username, $user->id, $user->username, $params['access_token']);
	$dbo_member->setDescription($username, $user->bio);
	$dbo_member->setGeoGraphy($username, $country, $state, $city);
	
	$image_url = "http://graph.facebook.com/".$user->id."/picture?type=large";
	$headers = get_headers($image_url, 1);
	$content_length = $headers["Content-Length"];
	if($content_length>2048)
	{
		$uploaddir = UPLOAD_DIR . "/" . $userid;
		if(!is_dir($uploaddir))	mkdir($uploaddir, 0777); //create my user id directory
		$filename = time().'.jpg';
		copy($image_url, $uploaddir. "/" . $filename);
		$picturepath = $userid ."/" . $filename;
	
		if(PHOTO_APPROVAL == 1){
			$dbo_member->setPendingProfilePic($username, $picturepath);
		}
	}
	$_SESSION['temp_user_id'] = $userid;
	$app->redirect(APP_PATH . "/facebook/username");
});

function getCountryStateCityFromLocation($location)
{
	
	require_once "lib/dbo/config.php";
	$dbo_config = new dbo_config();
	$countries = $dbo_config->getCountryList();
	
	$cnt = 0; $state = 0; $city = 0;
	
	foreach ($countries as $country) {
		if(strpos($location, $country['name'])!==false) {
			$cnt = $country['id'];
			break;
		}
	}
	
	if ($cnt !== 0) {
		$states = $dbo_config->getStateList($cnt);
		
		foreach ($states as $s) {
			if(strpos($location, $s['name'])!==false)
			{
				$state = $s['id'];
				break;
			}
		}
		
		if ($state !== 0) {
			$cities = $dbo_config->getCityList($state);
			foreach ($cities as $c) {
				if (strpos($location, $c['name']) !== false) {
					$city = $c['id'];
					break;
				}
			}
		}
	}
	
	return array($cnt, $state, $city);
}

function facebookGetInstalledPermissions($access_token)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,'https://graph.facebook.com/me/permissions?access_token='.$access_token);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  //to suppress the curl output
	$result = curl_exec($ch);
	curl_close ($ch);

	return json_decode($result);
}

function facebookPostOnUserWall($args)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,'https://graph.facebook.com/me/feed');
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  //to suppress the curl output
	$result = json_decode(curl_exec($ch));
	curl_close ($ch);
}