<?php
		$postUrl = 'http://www.flirtmit.ch/alpha/login/login.php';
		$postData = "redirect=&Nickname=Sarah83&Password=takeiton&go=1&Submit=Login";
		//$postData = "redirect=&Nickname=$username&Password=$password&go=1&Submit=Login";
		
		$ch = curl_init();
		
		curl_setopt($ch,CURLOPT_URL, $postUrl);
		curl_setopt($ch, CURLOPT_REFERER, 'http://www.flirtmit.ch/');
		curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $postData);
		
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_COOKIEFILE, 'c:\wamp\www\postdata\flirtmit\cookie.txt');
		curl_setopt ($ch, CURLOPT_COOKIEJAR, 'c:\wamp\www\postdata\flirtmit\cookie.txt');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$loginData = curl_exec($ch);
		
		curl_close($ch);
 		print_r($loginData);