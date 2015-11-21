<?php
// unlink('c:\wamp\www\postdata\myukdate\cookie.txt');
if(!file_exists("cookie.txt"))
{
	$cookie = fopen('cookie.txt', "w");
	fclose($cookie);
}else {
	$cookie = fopen('cookie.txt', "w");
	fwrite($cookie, '1');
	fclose($cookie);
}
$postUrl = 'http://www.myukdate.com/login.html';
$postData = "txtLogin=21cindy&frmPassword=thtl2a&x=44&y=3";
// $postData = "txtLogin=manday&frmPassword=123456&x=44&y=3";

$ch = curl_init();

curl_setopt($ch,CURLOPT_URL, $postUrl);
curl_setopt($ch, CURLOPT_REFERER, 'http://www.myukdate.com/login.asp');
curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

curl_setopt($ch,CURLOPT_POST, 1);
curl_setopt($ch,CURLOPT_POSTFIELDS, $postData);

curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_COOKIEFILE, 'c:\wamp\www\postdata\myukdate\cookie.txt');
curl_setopt ($ch, CURLOPT_COOKIEJAR, 'c:\wamp\www\postdata\myukdate\cookie.txt');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
$loginData = curl_exec($ch);

curl_close($ch);

print_r($loginData);