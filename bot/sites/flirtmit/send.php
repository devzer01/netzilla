<?php
require_once 'funcs.php';
	date_default_timezone_set('Europe/Berlin');
	setlocale(LC_ALL, "de_DE", "de_DE@euro", "deu", "deu_deu", "german");
	$german_time =  strftime('%H:%M:%S');

	$url = 'http://www.flirtmit.ch/alpha/mitglieder/profilpopup/form.php';
	$refer_url = 'http://www.flirtmit.ch/alpha/mitglieder/profilpopup/form.php?RID=125189&N=ein-freund';
	
	//$message = "Hey there stranger! I think your cute... Care to chat and probably meet up? Possible take me to places where I've never been. :) I have a fetish for men! Sorry for being so straight forward, but I really do like them so much! Anyhow, I'm meeting a lot of them here at yourbuddy24.com and wanted you to be part of my collection... j/k. :) I just wanted to get your attention, my nickname is same as it is here.  It's just much easier for me to contact you from there. Really hope you would join me there. x";
	//$new_message = funcs::replaceWord($message);
	$postData = "Sub=Hallo&Mail=Hallo%2C+wie+geht+es+dir%3F&N=Hyper007&Hid=$german_time&RID=125189&absenden=absenden+%C2%BB";

	$ch = curl_init();

	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_REFERER, $refer_url);
	curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

	curl_setopt($ch,CURLOPT_POST, 1);
	curl_setopt($ch,CURLOPT_POSTFIELDS, $postData);

	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_COOKIEFILE, 'c:\wamp\www\postdata\flirtmit\cookie.txt');
	curl_setopt ($ch, CURLOPT_COOKIEJAR, 'c:\wamp\www\postdata\flirtmit\cookie.txt');
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	$send = curl_exec($ch);
	curl_close($ch);


