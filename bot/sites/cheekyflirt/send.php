<?php
require_once 'funcs.php';
	$postUrl = 'http://www.cheekyflirt.com/account-sendMessage.php';
	//$postData = "action=_VERIFY&msgto=$receive_id&subject=$subject&rte1=$message";
	$message = "Hey there stranger! I think your cute... Care to chat and probably meet up? Possible take me to places where I've never been. :) I have a fetish for men! Sorry for being so straight forward, but I really do like them so much! Anyhow, I'm meeting a lot of them here at yourbuddy24.com and wanted you to be part of my collection... j/k. :) I just wanted to get your attention, my nickname is same as it is here.  It's just much easier for me to contact you from there. Really hope you would join me there. x";
	$new_message = funcs::replaceWord($message);
	$postData = "action=_VERIFY&msgto=7281223&subject=hiyaaaah&rte1=$new_message";

	$ch = curl_init();

	curl_setopt($ch,CURLOPT_URL, $postUrl);
	// curl_setopt($ch, CURLOPT_REFERER, 'http://www.myukdate.com/login.asp');
	curl_setopt($ch, CURLOPT_REFERER, $postUrl);
	curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

	curl_setopt($ch,CURLOPT_POST, 1);
	curl_setopt($ch,CURLOPT_POSTFIELDS, $postData);

	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_COOKIEFILE, 'c:\wamp\www\postdata\cheekyflirt\cookie.txt');
	curl_setopt ($ch, CURLOPT_COOKIEJAR, 'c:\wamp\www\postdata\cheekyflirt\cookie.txt');
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	$send = curl_exec($ch);
	curl_close($ch);
