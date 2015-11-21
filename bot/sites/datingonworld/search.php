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

	

		$url = "http://www.datingonworld.com/search/";
		$post_data = "type_id=members&gender2=2&gender1=2&age_from=18&age_to=90&country=&state=&state_2=&city=&uszip=&dist=&online_
only=0&pictures_only=0&display_type=0&search_save=&submit=Submit&issearch=1";
	
		//$post_data = "";

		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 5.1; rv:12.0) Gecko/20100101 Firefox/12.0');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_REFERER, 'http://www.datingonworld.com/search/');
		if(isset($post_data))
		{
			curl_setopt($ch,CURLOPT_POST,1);
			curl_setopt($ch,CURLOPT_POSTFIELDS,$post_data);
		}
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_COOKIEFILE, 'c:\wamp\www\postdata\datingonworld\cookies\cookie.txt');
		curl_setopt($ch, CURLOPT_COOKIEJAR, 'c:\wamp\www\postdata\datingonworld\cookies\cookie.txt');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

		$search = curl_exec($ch);

		curl_close($ch);
/*
print_r($search);
exit;
*/

$_SESSION['key'] = trim(funcs::getMembersFromSearchResult($search,1));

if($_SESSION['key']!='')
{
	for($i=1; $i<=2000; $i++)
	{
		$url = "http://www.datingonworld.com/search/".$_SESSION['key']."/$i";
		if($i>1)
		{
			$page_refer = $i - 1;
		}else
		{
			$page_refer = '';
		}
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 5.1; rv:12.0) Gecko/20100101 Firefox/12.0');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_REFERER, "http://www.datingonworld.com/search/".$_SESSION['key']."/$page_refer");		
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_COOKIEFILE, 'c:\wamp\www\postdata\datingonworld\cookies\cookie.txt');
		curl_setopt($ch, CURLOPT_COOKIEJAR, 'c:\wamp\www\postdata\datingonworld\cookies\cookie.txt');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

		$search = curl_exec($ch);

		curl_close($ch);
		//echo "http://www.datingonworld.com/search/$key/$page_refer";exit;
		//echo $search;exit;

		echo "search page: $i";
		flush_buffers();		

		funcs::getMembersFromSearchResult($search,$i);
		
	}
}

?>