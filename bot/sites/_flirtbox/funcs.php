<?php
require_once 'include/dbconnect.php';
class funcs
{
	static function memberlogin($username, $password, $proxy)
	{
// 		$proxy = "188.165.90.231:3128";
	    $url = 'http://www.flirtbox.co.uk/login.php?';
		$postUrl = 'http://www.flirtbox.co.uk/modules.php?name=Your_Account';
// 		$postData = 'op=login&uname=manday2012&pass=123456';
		$postData = "op=login&uname=$username&pass=$password";

// 		test send 3 pages of search result
// 		$proxy = "81.163.36.67:8080";
// 		$postData = 'op=login&uname=russky2012&pass=123456';
		
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $postUrl);
		curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
// 		proxy
// 		curl_setopt($ch, CURLOPT_PROXY, $proxy);
		curl_setopt($ch,CURLOPT_POST,4);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$postData);
		
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
		curl_setopt ($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$loginData = curl_exec($ch); //this cookie
		
		
		curl_close($ch);
// 		print_r($loginData);
	}
	
	
	static function dbCheckSentUser($sender,$name)
	{
// 		$sql = "select name from log_flirtbox where sender = '$sender' and name = '$name'";
		$sql = "select name from log_flirtbox where name = '$name'";
		$query = mysql_query($sql);
	
		$result = mysql_fetch_assoc($query);
		
		return $result;
	}
	
	static function sendMessage($username, $password, $touser, $subject, $message, $proxy)
	{	
		/************************************login *************************************/
		funcs::memberLogin($username, $password, $proxy);
		// 		print_r($loginData);	
		/************************************end login *********************************/
	
		/************************************post message *********************************/
// 		$proxy = "188.165.90.231:3128";
		$ch = curl_init();
			
		$postUrl = 'http://www.flirtbox.co.uk/reply.php';
		$postMessage = "subject=$subject&message=$message&to_user=$touser&smile=on&image=icon2.gif&submit=submit&msg_id=";
			
		curl_setopt($ch,CURLOPT_URL, $postUrl);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_REFERER, "http://www.flirtbox.co.uk/reply.php?send=1&uname=$touser");
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,30);

		// 		proxy
// 		curl_setopt($ch, CURLOPT_PROXY, $proxy);
		curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $postMessage);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		//need to sendd cookie
		curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
		curl_setopt ($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		// 			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3");
			
			
		$postMessage = curl_exec($ch);			
// 		print_r($postMessage);
			
		curl_close($ch);
		/************************************end post message ******************************/	
	}
		
	function get_contents($url, $username, $password ,$proxy){
		funcs::memberLogin($username, $password, $proxy);

// 		$proxy = "188.165.90.231:3128";
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 1);
		// 		proxy
// 		curl_setopt($ch, CURLOPT_PROXY, $proxy);

		curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
		curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		
		$buffer = curl_exec($ch);
		curl_close($ch);
// print_r($buffer);
		return $buffer;
	}
	
	function logout()
	{
		$logout_url = "http://www.flirtbox.co.uk/logout.php";
		
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $logout_url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 1);
		
		crul_exec($ch);
		
		return null;
	}
	
	function insertData($site, $content)
	{
		$slashContent = addslashes($content);
		$sql = "insert into fetchdata (id, site, data) values('', '$site', '$slashContent')";
		mysql_query($sql);
	
		return null;
	}
	
	function insertSearch($sender, $url, $htmlcontent)
	{
		$content = addslashes($htmlcontent);	
		$sql = "insert into search (`id`, `sender`, `url`, `texthtml`) values (``, `".$sender."`,`".$url."`, `".$content."`)";
		
		if(mysql_query($sql)){
			echo 'insert done<br>';
		}else {
			echo mysql_error();
		}
		
	}
	
	function db_get_htmlcontent($sender, $url)
	{
		$sql = "select texthtml from search where sender = '$sender' and url = '$url' order by id desc limit 1";
		$query = mysql_query($sql);
		
		$result = mysql_fetch_assoc($query);
		
		return $result['texthtml'];
	}
	
	function db_get_content()
	{
		$sql = "select * from fetchdata where site = '$url' limit 1";
		$query = mysql_query($sql);
		
		return mysql_fetch_assoc($query);
	}
	
	function insertLog($sender, $username)
	{
		$sql = "insert into log_flirtbox (id, sender, name, send_date) values ('', '$sender', '$username', now())";
		if(mysql_query($sql))
		{
			return true;
		}
		else 
		{
			return false;
		}
	}
	
	function insertUserLog($sender, $page)
	{
		$sql = "insert into log_flirtbox_user (id, user, page) values ('', '$sender', '$page')";
		if(mysql_query($sql))
		{
			return true;
		}
		else 
		{
			return false;
		}
	}
	
	function getSentLog($user)
	{
		$sql = "select page from log_flirtbox_user where user = '$user'";
		$query = mysql_query($sql);
		
// 		$result = mysql_fetch_array($query);
		
		$result = array();
		while ($data = mysql_fetch_array($query))
		{
			array_push($result,$data['page']);
		}
		
		return $result;
	}
	
	static function db_get_proxy()
	{
		$sql = "select * from proxy";
		$query = mysql_query($sql);
		
		$result = array();
		while ($data = mysql_fetch_array($query))
		{
// 			array_push($result, $data['proxy']);
			array_push($result,array('proxy'=>$data['proxy'], 'country'=>$data['country']));
		}
		
		return $result;
	}
	
	function checkproxy($proxy)
	{
		$postUrl = "http://whatismyipaddress.com/";
	
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $postUrl);
		curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	
		curl_setopt($ch, CURLOPT_PROXY, $proxy);
		$data = curl_exec($ch);
		curl_close($ch);
	
		$strFileName = "proxy.txt";
		$objFopen = fopen($strFileName, 'w');
		fwrite($objFopen, $data);
		fclose($objFopen);
	
		$textData = file_get_contents($strFileName);
	
		$arrData = explode(" ", $textData);
	
	
		foreach ($arrData as $eachData)
		{
			// 	print_r($eachData);
			if(strstr($eachData, "Country:"))
			{
				// 		echo $eachData;
				$newArr = explode(":", $eachData);
				// 		print_r($newArr);
			}
		}
		return $newArr[3];
	}
	
	function db_get_userdata()
	{
		$sql = "select id, username from flirtbox_user_profile";
		$query = @mysql_query($sql);
		$arr = array();
		while ($result = mysql_fetch_assoc($query))
		{
			array_push($arr, array('id'=>$result[id],'username'=>$result[username]));
		}
		
		return $arr;
	}
	
	function db_get_user($id)
	{
		$sql = "select username, password from flirtbox_user_profile where id = $id";
		$query = @mysql_query($sql);
		
		return @mysql_fetch_assoc($query);
	}
	
	function db_get_userlist()
	{
		$sql = "select username, password from flirtbox_user_profile";
		$query = @mysql_query($sql);
		$arr = array();
		
		while ($result = mysql_fetch_assoc($query)) {
			array_push($arr, array('username'=>$result[username], 'password'=>$result[password]));
		}
		
		return $arr;
	}
	
	function db_insert_profile($username, $password)
	{
		$sql = "insert into flirtbox_user_profile (id, name, password) values ('','$username', '$password')";
		
		mysql_query($sql);
	}
	
	function db_get_last_page($user)
	{
// 		$sql = "select max(page) as lastpage from log_flirtbox_page where name = '$user'";
		$sql = "select max(page) as lastpage from log_flirtbox_page";
		
		$query = @mysql_query($sql);
		
		return @mysql_fetch_assoc($query);
	}
	
	function db_insert_log_page($user, $page)
	{
		$sql = "insert into log_flirtbox_page (id, name, page) values ('', '$user', '$page')";
		mysql_query($sql);
	}
	
	function db_count_sender()
	{
		$sql = "select count(*) as count_sender from flirtbox_user_profile";
		$query = @mysql_query($sql);
		
		return @mysql_fetch_assoc($query);
	}
	
	function db_get_loginprofile()
	{
		$sql = "select * from flirtbox_user_profile";
		$query = @mysql_query($sql);
		
		$arr= array();
		while ($result = mysql_fetch_assoc($query))
		{
			array_push($arr, array("id"=>$result[id], "username"=>$result[username], "password"=>$result[password]));
		}
		
		return $arr;
	}
	
}
?>