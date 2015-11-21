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
		
		$postUrl = 'http://www.flirthut.com/index.php';
		//$postData = "handler=login&action=do_login&username=pinkpooh&password=123456&login_0=Login";
		$postData = "handler=login&action=do_login&username=$username&password=$password&login_0=Login";
		
		$ch = curl_init();
		
		curl_setopt($ch,CURLOPT_URL, $postUrl);
//		curl_setopt($ch, CURLOPT_REFERER, 'http://www.cheekyflirt.com/index.php');
		curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $postData);
		
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
		curl_setopt ($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$loginData = curl_exec($ch);
		
		curl_close($ch);
// 		print_r($loginData);
	}
	
	function get_contents($start, $end, $page){
		if ($page>1)
		{
			$s_page = $page-1;
			$postUrl = "http://www.flirthut.com/index.php?handler=search&action=perform&search_type=advanced&src_id=&keyword=&age_from=$start&age_to=$end&gender=1&smoking=&drinking=&looking_for=&hair_color=&height=&d_status=&sexuality=0&distance=0&country=225&state=0&city=0&postal_code=&sort=1&online=0&with_photo=0&err_page=search&err_section=advanced&p=$s_page";
		}		
		else 
		{
			$postUrl = "http://www.flirthut.com/index.php?handler=search&action=perform&search_type=advanced&keyword=&age_from=$start&age_to=$end&gender=1&smoking=&drinking=&looking_for=&hair_color=&height=&d_status=&sexuality=0&distance=0&country=225&state=0&city=0&postal_code=&sort=1&search_advanced_0=Search";
		}
		
		$ch = curl_init();
		
		curl_setopt($ch,CURLOPT_URL, $postUrl);
		//curl_setopt($ch, CURLOPT_REFERER, $referUrl);
		curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		
		
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
		curl_setopt ($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$search = curl_exec($ch);
// 		print_r($search);
		curl_close($ch);
	
		return $search;	
	}
	
	function file_get_data($data)
	{
		$arr_data = explode(' ', $data);
		$arr_result = array();
		foreach($arr_data as $result)
		{
			if(strstr($result, 'href="index.php?handler=member_action&action=bookmark&per_id='))
			{
				$replace1 = str_replace('href="index.php?handler=member_action&action=bookmark&per_id=', '', $result);
				$replace2 = str_replace(array('\r','\t','\n','"',' '),'', $replace1);
				$replace3 = str_replace('>Bookmark</a></li>
				<li><a','',$replace2);
				$replace4 = str_replace('>Bookmark</a></li>
               <li><a','',$replace3);
				$replace5 = str_replace('">Bookmark</a></li>
               <li><a','',$replace4);
				$replace6 = str_replace('>Bookmark</a></li>
               <li><a','',$replace5);
				$replace7 = str_replace('>Bookmark</a></li>
               <li><a','',$replace6);

				array_push($arr_result, array("recipient"=> $replace7));
			}
		}
		return $arr_result;
	}
	
	function send_message($receive_id, $subject, $message)
	{
		$message = stripslashes($message);

		$postUrl = 'http://www.flirthut.com/index.php';
		$postData = "action=_VERIFY&msgto=$receive_id&subject=$subject&rte1=$message";
		
		$postData = "handler=mailbox&action=send&recipient=$receive_id&subject=$subject&message=$message&message_1=Send";

		$ch = curl_init();

		curl_setopt($ch,CURLOPT_URL, $postUrl);
		curl_setopt($ch, CURLOPT_REFERER, 'http://www.flirthut.com/index.php?page=mailbox&section=message&recipient=$receive_id');
		curl_setopt($ch, CURLOPT_REFERER, $postUrl);
		curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $postData);

		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
		curl_setopt ($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$send = curl_exec($ch);

		curl_close($ch);
	}

	function mb_unserialize($serial_str)
	{ 
		$out = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $serial_str ); 
		return unserialize($out); 
	}

	function logging($msg,$job_id)
	{
		if(!$job_id)
		$job_id="jobs";
		$log = "[".date("Y-m-d H:i:s")."] ".$msg."\n";
		
		file_put_contents("logs/".$job_id.".log", $log,FILE_APPEND);
	}
	
	function insertLog($username, $receiver)
	{
		$sql = "insert into flirthut (id, sender, name, send_date) values ('', '$username', '$receiver', now())";
		mysql_query($sql);
		
	}
	
	function insertLogPage($username, $page)
	{
		$sql = "insert into flirthut_page (id, name, page) values ('', '$username', '$page')";
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
		$sql = "select * from flirthut_user_profile where status = 'true'";
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
		$sql = "select max(page) as lastpage from flirthut_page";
		$query = mysql_query($sql);
		
		return mysql_fetch_assoc($query);
	}
	
	static function dbCheckSentUser($user)
	{
		$sql = "select name from flirthut where name = '$user'";
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
		$sql = "select id  from flirthut_user_profile where status = 'true'";
		$query = mysql_query($sql);
		
		$num = mysql_num_rows($query);

		return $num;
	}
}
?>