<?php
/*
ob_start();
set_time_limit(0);
date_default_timezone_set("Asia/Bangkok");
ignore_user_abort(true);
*/
require_once('funcs.php');

$loginURL = "http://www.datingonworld.com/account/login/";
$post_data = "username=Aaliye&password=thtl19&login=Login&islogin=1";
$loginRefererURL = "http://www.datingonworld.com/";
$cookie_path = "c:\wamp\www\postdata\datingonworld\cookies\Aaliye.txt";

$url = "http://www.datingonworld.com/account/login/";
$post_data = "username=Aaliye&password=thtl19&login=Login&islogin=1";
$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_REFERER, 'http://www.datingonworld.com/');
		if(isset($post_data))
		{
			curl_setopt($ch,CURLOPT_POST,1);
			curl_setopt($ch,CURLOPT_POSTFIELDS,$post_data);
		}
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_COOKIEFILE, 'c:\wamp\www\postdata\datingonworld\cookies\cookie.txt');
		curl_setopt($ch, CURLOPT_COOKIEJAR, 'c:\wamp\www\postdata\datingonworld\cookies\cookie.txt');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

		$login = curl_exec($ch);

		curl_close($ch);
print_r($login);
/*
$ch = curl_init();
				
				curl_setopt($ch,CURLOPT_URL, $loginURL);
				curl_setopt($ch, CURLOPT_REFERER, $loginRefererURL);
				curl_setopt($ch,CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
				curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch,CURLOPT_TIMEOUT,30); 
			
				curl_setopt($ch,CURLOPT_POST, 1);
				curl_setopt($ch,CURLOPT_POSTFIELDS, $post_data);
				
				curl_setopt($ch, CURLOPT_HEADER, 1);
				//curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
				curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
				$result = curl_exec($ch);
				
				curl_close($ch);
				print_r($result);
*/
/*
if(funcs::memberlogin('Aaliye', 'thtl19','http://www.datingonworld.com/account/login/','http://www.datingonworld.com/'))
{
	echo "logged in";
}
else
{
	echo "can't login";
}
*/
//print_r($login);

//	funcs::isCookieValid('monalisa69');
?>