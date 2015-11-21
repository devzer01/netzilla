<?php
require_once 'include/dbconnect2.php';

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
		
		$postUrl = 'http://www.cheekyflirt.com/login.php';
		//$postData = "username=pinkpooh, password=123456,Submit2=login";
		$postData = "username=$username&password=$password&Submit2=login";
		
		$ch = curl_init();
		
		curl_setopt($ch,CURLOPT_URL, $postUrl);
		curl_setopt($ch, CURLOPT_REFERER, 'http://www.cheekyflirt.com/index.php');
		curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $postData);
		
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_COOKIEFILE, 'c:\wamp\www\postdata\cheekyflirt\cookie2.txt');
		curl_setopt ($ch, CURLOPT_COOKIEJAR, 'c:\wamp\www\postdata\cheekyflirt\cookie2.txt');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$loginData = curl_exec($ch);
		
		curl_close($ch);
		return $loginData;
// 		print_r($loginData);
	}
	
	function get_contents($start, $end, $page){
		if ($page>=1)
		{
			$postUrl = "http://www.cheekyflirt.com/users.php?gender=M&sexuality=&fromAge=18&toAge=30&country=5&country_area=&online=&images=&videoProfile=&signedUp=&miles=&username=&page=$page";
			$referUrl = "http://www.myukdate.com/saved_search.php?profileme=search&type=uk&country=GBR&gender[1]=1&age_start=$start&age_end=$end&frmlocation=0&frmDays=0&coreg_zone=search_quick";
			$referUrl .= "&coreg_phrases[952]=Pick+out+the+best-looking+guys+who+have+personals+on+Flirt.com!";
		}
		
		else 
		{
			$postUrl = "http://www.cheekyflirt.com/users.php?gender=M&sexuality=&fromAge=18&toAge=30&country=5&country_area=&online=&images=&videoProfile=&signedUp=&miles=&username=";
			$postUrl .= "&coreg_phrases[952]=Pick+out+the+best-looking+guys+who+have+personals+on+Flirt.com!";
			$referUrl = "http://www.myukdate.com/searchf.asp";
		}
		
		$ch = curl_init();
		
		curl_setopt($ch,CURLOPT_URL, $postUrl);
		//curl_setopt($ch, CURLOPT_REFERER, $referUrl);
		curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		
		
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_COOKIEFILE, 'c:\wamp\www\postdata\cheekyflirt\cookie2.txt');
		curl_setopt ($ch, CURLOPT_COOKIEJAR, 'c:\wamp\www\postdata\cheekyflirt\cookie2.txt');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$search = curl_exec($ch);
// 		echo $case.'<br>';
// 		print_r($search);
		curl_close($ch);
	
		return $search;	
	}
	
	function file_get_data($data)
	{
		$arrStr = explode(' ', $data);
		$newArr = array();
		foreach ($arrStr as $key=>$val)
		{
			if(strstr($val, 'href="profile-home.php?userid='))
			{	
				$replace1 = str_replace('href="profile-home.php?userid=', '', $val);
				$replace2 = (int)str_replace('"', '', $replace1);
				if($replace2 != '')
				{
					array_push($newArr, array('recipient'=>$replace2));
				}
			}

			if(strstr($val,'class="username">'))
			{
				$new_val = str_replace('class="username">', "", $val);				
			}
			//array_push($newArr, array('recipient'=>$replace2, 'recipient_name'=>$new_val));
		}
		//$result = array_unique($newArr);

		return $newArr;
	}
	
	function send_message($receive_id, $subject, $message)
	{
		
		$postUrl = 'http://www.cheekyflirt.com/account-sendMessage.php';
		$postData = "action=_VERIFY&msgto=$receive_id&subject=$subject&rte1=$message";
		
		$ch = curl_init();
	
		curl_setopt($ch,CURLOPT_URL, $postUrl);
		// curl_setopt($ch, CURLOPT_REFERER, 'http://www.myukdate.com/login.asp');
		curl_setopt($ch, CURLOPT_REFERER, $postUrl);
		curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $postData);
		
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_COOKIEFILE, 'c:\wamp\www\postdata\cheekyflirt\cookie2.txt');
		curl_setopt ($ch, CURLOPT_COOKIEJAR, 'c:\wamp\www\postdata\cheekyflirt\cookie2.txt');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$send = curl_exec($ch);
		
		curl_close($ch);
	}

	function insert_daily_log($log_file, $data)
	{
			$log_open = fopen($log_file, 'a+');
			fputs($log_open, "$data \n");
			fclose($log_open);
	}
	
	function insertLog($username, $receiver)
	{
		$sql = "insert into cheekyflirt (id, sender, name, send_date) values ('', '$username', '$receiver', now())";
		mysql_query($sql);
		
	}
	
	function insertLogPage($username, $page)
	{
		$sql = "insert into cheekyflirt_page (id, name, page) values ('', '$username', '$page')";
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
		$sql = "select * from cheekyflirt_user_profile where status = 'true'";
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
		$sql = "select max(page) as lastpage from cheekyflirt_page";
		$query = mysql_query($sql);
		
		return mysql_fetch_assoc($query);
	}
	
	static function dbCheckSentUser($user)
	{
		$sql = "select name from cheekyflirt where name = '$user'";
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
		$sql = "select id  from cheekyflirt_user_profile where status = 'true'";
		$query = mysql_query($sql);
		
		$num = mysql_num_rows($query);

		return $num;
	}
}
?>