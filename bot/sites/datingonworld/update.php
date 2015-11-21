<?php
ob_start();
session_start();
date_default_timezone_set("Asia/Bangkok");
require_once('DBconnect.php');
require_once 'funcs.php';
require_once 'config.php';
function flush_buffers()
{
	echo "<br/><script>window.scrollTo(0, document.body.scrollHeight);</script>";

	ob_end_flush();
	ob_flush();
	flush();
	ob_start();
}

$db_user = funcs::dbGetNoIdMember();

foreach($db_user as $user)
{
	//echo "id: $user[id]   username: $user[username] <br>";
	
		$url = "http://www.datingonworld.com/$user[username]";
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 5.1; rv:12.0) Gecko/20100101 Firefox/12.0');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		//curl_setopt($ch, CURLOPT_REFERER, "");		
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_COOKIEFILE, 'c:\wamp\www\postdata\datingonworld\cookies\cookie.txt');
		curl_setopt($ch, CURLOPT_COOKIEJAR, 'c:\wamp\www\postdata\datingonworld\cookies\cookie.txt');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

		$content = curl_exec($ch);

		curl_close($ch);

		$userid = funcs::getIdUpdate($content);

		if(funcs::updateMember($user['id'], $userid, $user['username']))
		{
			echo "UPDATE:: id: $user[id] || name: $user[username] || userid: $userid";
			flush_buffers();
		}
		else
		{
			echo "couldn't update";
			flush_buffers();
		}
		
		
}
?>