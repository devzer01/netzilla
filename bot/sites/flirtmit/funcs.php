<?php
require_once 'include/dbconnect.php';

class funcs
{
	static function memberlogin($username, $password)
	{
		if(!file_exists("cookie.txt"))
		{
			$cookie = fopen('cookie.txt', "w");
			fclose($cookie);
		}else {
			$cookie = fopen('cookie.txt', "w");
			fwrite($cookie, '1');
			fclose($cookie);
		}
		
		$postUrl = 'http://www.flirtmit.ch/alpha/login/login.php';
		//$postData = "redirect=&Nickname=Sarah83&Password=takeiton&go=1&Submit=Login";
		$postData = "redirect=&Nickname=$username&Password=$password&go=1&Submit=Login";
		
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
// 		print_r($loginData);
	}
	
	function get_contents($age_start, $age_end, $page){
		if($page=='')
		{
			$url = "http://www.flirtmit.ch/alpha/mitglieder/suche.php";
			$refer_url = "http://www.flirtmit.ch/alpha/start/start_ein.php";
			$post_data = "search[altervon]=$age_start&search[alterbis]=$age_end&search[geschlecht]=1&search[LandID]=-1&search[BLandID]=&Hi=1&suchen=1&regio=suchen+%C2%BB";
			
		}
		else
		{
			$new_page = ($page-1) * 10;
			$url = "http://www.flirtmit.ch/alpha/mitglieder/suche.php?suchen=true&start=$new_page&search[altervon]=$age_start&search[alterbis]=$age_end&search[geschlecht]=1&search[LandID]=-1&search[BLandID]=&";
			$refer_url = "http://www.flirtmit.ch/alpha/mitglieder/suche.php";
			
		}

		$ch = curl_init();
		
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_REFERER, $refer_url);
		curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		if($page=='')
		{
			curl_setopt($ch,CURLOPT_POST, 1);
			curl_setopt($ch,CURLOPT_POSTFIELDS, $post_data);
		}
		
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_COOKIEFILE, 'c:\wamp\www\postdata\flirtmit\cookie.txt');
		curl_setopt ($ch, CURLOPT_COOKIEJAR, 'c:\wamp\www\postdata\flirtmit\cookie.txt');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$search = curl_exec($ch);
 		//print_r($search);
		curl_close($ch);
	
		return $search;	
	}
	
	function file_get_data($data)
	{
		$arr_data = explode(' ', $data);
		$arr_result = array();
		foreach($arr_data as $result)
		{
			if(strstr($result, 'href="/alpha/mitglieder/profil.php?RID='))
			{
				if(!strstr($result,'span'))
				{
					$replace = str_replace('href="/alpha/mitglieder/profil.php?RID=','',$result);
					$arr_replace = explode('&',$replace);
					array_push($arr_result, array('recipient'=>$arr_replace[0]));
				}
				//
				
			}
		}
		return $arr_result;
	}
	
	function send_message($receive_id, $subject, $message)
	{
		
		date_default_timezone_set('Europe/Berlin');
		setlocale(LC_ALL, "de_DE", "de_DE@euro", "deu", "deu_deu", "german");
		$german_time =  strftime('%H:%M:%S');

		$url = 'http://www.flirtmit.ch/alpha/mitglieder/profilpopup/form.php';
		$refer_url = 'http://www.flirtmit.ch/alpha/mitglieder/profilpopup/form.php?RID=125189&N=ein-freund';
		
		$postData = "Sub=$subject&Mail=$message&N=Hyper007&Hid=$german_time&RID=$receive_id&absenden=absenden+%C2%BB";

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
	}
	
	function insertLog($username, $receiver)
	{
		$sql = "insert into flirtmit (id, sender, name, send_date) values ('', '$username', '$receiver', now())";
		mysql_query($sql);
		
	}
	
	function insertLogPage($username, $page)
	{
		$sql = "insert into flirtmit_page (id, name, page) values ('', '$username', '$page')";
		mysql_query($sql);
				
	}
	
	static function replaceWord($text)
	{
		$search = array(' ', ',', "'");
		$replace = array('+', '%2C', '%27');
		
		return str_replace($search, $replace, $text);
	}
	
	function db_get_loginprofile()
	{
		$sql = "select * from flirtmit_user_profile where status = 'true'";
		$query = mysql_query($sql);
		
		$arr= array();
		while ($result = mysql_fetch_assoc($query))
		{
			array_push($arr, array("id"=>$result['id'], "username"=>$result['username'], "password"=>$result['password']));
		}
		
		return $arr;
	}
	
	static function db_get_last_page()
	{
		$sql = "select max(page) as lastpage from flirtmit_page";
		$query = mysql_query($sql);
		
		return mysql_fetch_assoc($query);
	}
	
	static function dbCheckSentUser($user)
	{
		$sql = "select name from flirtmit where name = '$user'";
		$query = mysql_query($sql);
	
		$result = mysql_fetch_assoc($query);
	
		return $result;
	}

	static function db_get_message()
	{
		$sql = "select * from message";
		$query = mysql_query($sql);
		$arr = array();
		while ($result = mysql_fetch_assoc($query))
		{
			array_push($arr, array("id"=>$result['id'], "text_message"=>$result['text_message']));
		}

		return $arr;
	}

	static function db_count_profile()
	{
		$sql = "select id  from flirtmit_user_profile where status = 'true'";
		$query = mysql_query($sql);
		
		$num = mysql_num_rows($query);

		return $num;
	}
}
?>