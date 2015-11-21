<?php
$postUrl = 'http://www.flirthut.com/index.php';
		$postData = "handler=login&action=do_login&username=pinkpooh&password=123456&login_0=Login";
		//$postData = "handler=login&action=do_login&username=pinkpooh&password=123456&login_0=Login";
		
		$ch = curl_init();
		
		curl_setopt($ch,CURLOPT_URL, $postUrl);
//		curl_setopt($ch, CURLOPT_REFERER, 'http://www.cheekyflirt.com/index.php');
		curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $postData);
		
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_COOKIEFILE, 'c:\wamp\www\postdata\flirthut\cookie.txt');
		curl_setopt ($ch, CURLOPT_COOKIEJAR, 'c:\wamp\www\postdata\flirthut\cookie.txt');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$loginData = curl_exec($ch);
		
		curl_close($ch);
 		print_r($loginData);