<?php
$code = $_REQUEST["code"];

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

if($_GET['testPost'])
{
	$access_token = DBConnect::retrieve_value("SELECT facebook_token FROM member WHERE id=".$_SESSION['sess_id']);
	if($access_token)
	{
		$permissions = facebookGetInstalledPermissions($access_token);
		if($permissions->data[0]->publish_actions)
		{
			$user = DBConnect::assoc_query_1D("SELECT username, forname, surname FROM member WHERE id=".$_SESSION['sess_id']);
			$args=array(
				'access_token' => $access_token,
				"name"=>$user['username']." start using ".ucfirst($domain),
				"picture"=>"http://www.".$domain."/sites/".$domain."/images/cm-theme/facebook-128.jpg",
				/*"link"=>"www.".$domain,
				"message"=>$user['forname']." ".$user['surname']." start using ".ucfirst($domain),
				"picture"=>"http://www.".$domain."/sites/".$domain."/images/cm-theme/facebook-128.jpg",
				"name"=> ucfirst($domain),
				"caption"=>$user['forname']." ".$user['surname']." start using ".ucfirst($domain),
				"description"=>$user['forname']." ".$user['surname']." start using ".ucfirst($domain),
				"source"=>"www.".$domain*/
			);
			facebookPostOnUserWall($args);
		}
		else
		{
			echo "No publish permission";
		}
	}
}
elseif(empty($code))
{
	$_SESSION['state'] = md5(uniqid(rand(), TRUE));
	header('location: '.FACEBOOK_LOGIN_URL.$_SESSION['state']);
}
else
{
	if($_SESSION['state'] && ($_SESSION['state'] === $_REQUEST['state']))
	{
		if($_REQUEST['error'])
		{
			header("location: .");
		}
		else
		{
			$token_url = "https://graph.facebook.com/oauth/access_token?client_id=". APP_ID ."&redirect_uri=". urlencode(MY_URL) ."?action=fblogin&client_secret=". APP_SECRET ."&code=". $code;

			$response = file_get_contents($token_url);
			$params = null;
			parse_str($response, $params);

			$graph_url = "https://graph.facebook.com/me?access_token=".$params['access_token'];

			$user = json_decode(file_get_contents($graph_url));

			if(($user->id != "") && ($user->email != ""))
			{
				if($userProfile = DBConnect::assoc_query_1D("SELECT username, password FROM member WHERE facebook_id='".$user->id."' AND isactive=1 LIMIT 1"))
				{
					DBConnect::execute_q("UPDATE member SET facebook_token='".$params['access_token']."' WHERE facebook_id='".$user->id."'");
					funcs::loginSite($userProfile['username'], $userProfile['password'],'1');
					header("location:?action=profile#editprofile");
				}
				elseif($userProfile = DBConnect::assoc_query_1D("SELECT username, password FROM member WHERE facebook_id='".$user->id."' AND isactive=0 LIMIT 1"))
				{
					header("location: .");
				}
				elseif($userProfile = DBConnect::assoc_query_1D("SELECT username, password FROM member WHERE email='".$user->email."' AND isactive=1 LIMIT 1"))
				{
					DBConnect::execute_q("UPDATE member SET facebook_id='".$user->id."', facebook_token='".$params['access_token']."' WHERE email='".$user->email."'");
					funcs::loginSite($userProfile['username'], $userProfile['password'],'1');
					$args=array(
						'access_token' => $params['access_token'],
						"name"=>$username." start using ".ucfirst($domain),
						"picture"=>"http://www.".$domain."/sites/".$domain."/images/cm-theme/facebook-128.jpg",
					);
					facebookPostOnUserWall($args);

					header("location: .");
				}
				elseif($userProfile = DBConnect::assoc_query_1D("SELECT username, password FROM member WHERE email='".$user->email."' AND isactive=0 LIMIT 1"))
				{
					header("location: .");
				}
				else
				{
					$ext = "";
					while(DBConnect::assoc_query_1D("SELECT 1 FROM member WHERE username='".$user->username.$ext."' LIMIT 1"))
					{
						$ext = "_".randomPassword(2, "number");
					}
					$username = $user->username.$ext;
					$password = randomPassword(12);
					$validation_code = randomPassword(6, "number");
					
					$mobile = 0;
					if (isset($_SESSION['deviceType']) && $_SESSION['deviceType'] == 'phone') {
						$mobile = 1;
					}
					
					$ref = '';
					
					if (isset($_SESSION['ref'])) {
						$ref = $_SESSION['ref'];
					}
					
					if (trim($ref) == '' && isset($_COOKIE['ref'])) {
						$ref = $_COOKIE['ref'];
					}
					
					$save = array(
									"username" => $username,
									"password" => $password,
									"forname" => $user->first_name,
									"surname" => $user->last_name,
									"validation_code" => $validation_code,
									"birthday" => date("Y-m-d", strtotime($user->birthday)),
									"gender" => ($user->gender=="male")?1:2,
									"email" => $user->email,
									"facebook_id" => $user->id,
									"facebook_username" => $user->username,
									"facebook_token" => $params['access_token'],
									"username_confirmed" => 0,
									"isactive" => 0,
									"type" => 4,
									"ref" => $ref,
									"signup_datetime" => funcs::getDateTime(),
									"lookmen" => ($user->gender=="male")?"0":"1",
									"lookwomen" => ($user->gender=="male")?"1":"0",
									"mobile" => $mobile,
									"description" => ($user->bio)?addslashes($user->bio):""
								);
					if($user->location)
					{
						$countries = DBConnect::assoc_query_2D("SELECT id, name FROM xml_countries WHERE status=1");
						foreach($countries as $country)
						{
							if(strpos($user->location->name, $country['name'])!==false)
							{
								$save['country'] = $country['id'];
							}
						}

						if($save['country'])
						{
							$states = DBConnect::assoc_query_2D("SELECT id, name FROM xml_states WHERE status=1 and parent=".$save['country']);
							foreach($states as $state)
							{
								if(strpos($user->location->name, $state['name'])!==false)
								{
									$save['state'] = $state['id'];
								}
							}

							if($save['state'])
							{
								$cities = DBConnect::assoc_query_2D("SELECT id, name FROM xml_cities WHERE status=1 and parent=".$save['state']);
								foreach($cities as $city)
								{
									if(strpos($user->location->name, $city['name'])!==false)
									{
										$save['city'] = $city['id'];
									}
								}
							}
						}
					}
					elseif($user->hometown)
					{
						$countries = DBConnect::assoc_query_2D("SELECT id, name FROM xml_countries WHERE status=1");
						foreach($countries as $country)
						{
							if(strpos($user->hometown->name, $country['name'])!==false)
							{
								$save['country'] = $country['id'];
							}
						}

						if($save['country'])
						{
							$states = DBConnect::assoc_query_2D("SELECT id, name FROM xml_states WHERE status=1 and parent=".$save['country']);
							foreach($states as $state)
							{
								if(strpos($user->hometown->name, $state['name'])!==false)
								{
									$save['state'] = $state['id'];
								}
							}

							if($save['state'])
							{
								$cities = DBConnect::assoc_query_2D("SELECT id, name FROM xml_cities WHERE status=1 and parent=".$save['state']);
								foreach($cities as $city)
								{
									if(strpos($user->hometown->name, $city['name'])!==false)
									{
										$save['citiy'] = $city['id'];
									}
								}
							}
						}
					}
					$colnames = array_flip(DBconnect::get_col_names('member'));
					$member_post = array_intersect_key($save, $colnames);

					DBconnect::assoc_insert_1D($member_post, 'member');
					$userid = mysql_insert_id();

					$image_url = "http://graph.facebook.com/".$user->id."/picture?type=large";
					$headers = get_headers($image_url, 1);
					$content_length = $headers["Content-Length"];
					if($content_length>2048)
					{
						$uploaddir = UPLOAD_DIR.$userid.'/';
						if(!is_dir($uploaddir))	//check have my user id directory
							mkdir($uploaddir, 0777); //create my user id directory
						
						$filename = time().'.jpg';
						copy($image_url, $uploaddir.$filename);
						$picturepath = $userid."/".$filename;

						if(PHOTO_APPROVAL == 1){
								funcs::updatePhotoToTemp($userid, $picturepath);
						}
					}

					$subject = funcs::getText($_SESSION['lang'], '$email_testmember_subject');	//get subject message
					$message = funcs::getMessageEmail_Forgot($smarty,$username, $password, "facebook");	//get message
					funcs::sendMail($user->email, $subject, $message, MAIL_FROM);

					$args=array(
						'access_token' => $params['access_token'],
						"name"=>$username." start using ".ucfirst($domain),
						"picture"=>"http://www.".$domain."/sites/".$domain."/images/cm-theme/facebook-128.jpg",
					);
					
					facebookPostOnUserWall($args);

					header("location: ". MY_URL."?action=activate&username=".$username."&password=".$password."&code=".$validation_code);
				}
			}
			else
			{
				echo("Failed, facebook id and email are needed.");
			}
		}
	}
	else
	{
		echo("The state does not match. You may be a victim of CSRF.");
	}	
}

function randomPassword($num, $type="alphabet") {
	if(is_numeric($num))
	{
		if($num < 1)
			$num = 8;
	}
	else
	{
		$num = 8;
	}

	if($type == "alphabet")
	    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
	else
	    $alphabet = "0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < $num; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}
?>