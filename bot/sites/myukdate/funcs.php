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
		
		$postUrl = 'http://www.myukdate.com/login.html';
		$postData = "txtLogin=$username&frmPassword=$password&x=44&y=3";
		
		$ch = curl_init();
		
		curl_setopt($ch,CURLOPT_URL, $postUrl);
		curl_setopt($ch, CURLOPT_REFERER, 'http://www.myukdate.com/login.asp');
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
		if ($page==1)
		{
			$postUrl = "http://www.myukdate.com/profile.get.php?pg=1";
			$referUrl = "http://www.myukdate.com/saved_search.php?profileme=search&type=uk&country=GBR&gender[1]=1&age_start=$start&age_end=$end&frmlocation=0&frmDays=0&coreg_zone=search_quick";
			$referUrl .= "&coreg_phrases[952]=Pick+out+the+best-looking+guys+who+have+personals+on+Flirt.com!";$case = 1;
		}
		elseif($page>=2)
		{
			$ref_page = $page-1;
			$postUrl = "http://www.myukdate.com/profile.get.php?pg=$page";
			$referUrl = "http://www.myukdate.com/profile.get.php?pg=$ref_page";
			
			$postUrl1 = "http://www.myukdate.com/saved_search.php?profileme=search&type=uk&country=GBR&gender[1]=1&age_start=$start&age_end=$end&frmlocation=0&frmDays=0&coreg_zone=search_quick";
			$postUrl1 .= "&coreg_phrases[952]=Pick+out+the+best-looking+guys+who+have+personals+on+Flirt.com!";
			$referUrl1 = "http://www.myukdate.com/searchf.asp";
			
			$ch1 = curl_init();
			curl_setopt($ch1,CURLOPT_URL, $postUrl1);
			curl_setopt($ch1, CURLOPT_REFERER, $referUrl1);
			curl_setopt($ch1,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
			curl_setopt ($ch1, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch1, CURLOPT_HEADER, 1);
			curl_setopt($ch1, CURLOPT_COOKIEFILE, 'cookie.txt');
			curl_setopt ($ch1, CURLOPT_COOKIEJAR, 'cookie.txt');
			curl_setopt($ch1, CURLOPT_FOLLOWLOCATION, TRUE);
			$search = curl_exec($ch1);
			curl_close($ch1);
			
// 			$postUrl2 = "http://www.myukdate.com/profile.get.php?pg=1";
// 			$referUrl2 = "http://www.myukdate.com/saved_search.php?profileme=search&type=uk&country=GBR&gender[1]=1&age_start=$start&age_end=$end&frmlocation=0&frmDays=0&coreg_zone=search_quick";
// 			$referUrl2 .= "&coreg_phrases[952]=Pick+out+the+best-looking+guys+who+have+personals+on+Flirt.com!";			
// 			$ch2 = curl_init();			
// 			curl_setopt($ch2,CURLOPT_URL, $postUrl2);
// 			curl_setopt($ch2, CURLOPT_REFERER, $referUrl2);
// 			curl_setopt($ch2,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
// 			curl_setopt ($ch2, CURLOPT_RETURNTRANSFER, 1);
// 			curl_setopt($ch2, CURLOPT_HEADER, 1);
// 			curl_setopt($ch2, CURLOPT_COOKIEFILE, 'c:\wamp\www\postdata\myukdate\cookie.txt');
// 			curl_setopt ($ch2, CURLOPT_COOKIEJAR, 'c:\wamp\www\postdata\myukdate\cookie.txt');
// 			curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, TRUE);
// 			$search2 = curl_exec($ch2);
// 			curl_close($ch2);
			
			$case=2;
		}
		else 
		{
			$postUrl = "http://www.myukdate.com/saved_search.php?profileme=search&type=uk&country=GBR&gender[1]=1&age_start=$start&age_end=$end&frmlocation=0&frmDays=0&coreg_zone=search_quick";
			$postUrl .= "&coreg_phrases[952]=Pick+out+the+best-looking+guys+who+have+personals+on+Flirt.com!";
			$referUrl = "http://www.myukdate.com/searchf.asp";	$case=3;
		}
		
		$ch = curl_init();
		
		curl_setopt($ch,CURLOPT_URL, $postUrl);
		curl_setopt($ch, CURLOPT_REFERER, $referUrl);
		curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		
		
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
		curl_setopt ($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
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
			if (!isset($token))
			{
				if (strstr($val, '&token='))
				{
		
					$new_val = str_replace('"', '', $val);
					$find_token = explode('&token=', $new_val);
					if (isset($find_token[1]))
					{
						$token = $find_token[1];
					}
				}
			}
		
		
			if(strstr($val, 'href="/review/profile/'))
			{
		
				if(strstr($val, '<img'))
				{
					$val = '';
				}
		
				$replace1 = str_replace('href="/review/profile/', '', $val);
				$replace2 = explode('.html">', $replace1);
		
				array_push($newArr, array('recipient'=>$replace2));
		
			}
		
		}
		
		$arrResult = array();
		foreach ($newArr as $val)
		{
			foreach ($val as $val2)
			{
				if(isset($val2[1]))
				{	
					$id = $val2[0];
					$name = str_replace('</a>', '', $val2[1]);
					array_push($arrResult, array('id'=>$id, 'name'=>$name, 'token'=>$token));
				}
			}
		}
		
		return $arrResult;
	}
	
	function send_message($token, $receive_id, $receive_name, $subject, $message)
	{
		$message = stripslashes($message);
		$postUrl = 'http://www.myukdate.com/mailbox/write/-/'.$receive_id.'.html';
		$postData = "token=$token&frmTo=$receive_id&to=$receive_name&frmSubject=$subject&frmMessage=$message&sendmsg.x=37&sendmsg.y=14";
		
		//echo "<pre>";
		//echo "token: $token<br>  receiveid: $receive_id<br>  receivenam: $receive_name<br> subject: //$subject<br>message:$message<br>";
		//echo $postUrl."<br>";		
		//echo $postData."<br>";
		//echo "</pre>";

		$ch = curl_init();
	
		curl_setopt($ch,CURLOPT_URL, $postUrl);
		// curl_setopt($ch, CURLOPT_REFERER, 'http://www.myukdate.com/login.asp');
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
		if($send) {
			return true;
		}
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

		file_put_contents("logs/".$job_id."_latest.log", $log);
	}
	
	function insertLog($username, $receiver)
	{
		$sql = "insert into myukdate (id, sender, name, send_date) values ('', '$username', '$receiver', now())";
		mysql_query($sql);
		
	}
	
	function insertLogPage($username, $page)
	{
		$sql = "insert into myukdate_page (id, name, page) values ('', '$username', $page)";
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
		$sql = "select * from myukdate_user_profile where status = 'true' and usergroup = 1";
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
		$sql = "select max(page) as lastpage from myukdate_page";
		$query = mysql_query($sql);
		
		return mysql_fetch_assoc($query);
	}
	
	static function dbCheckSentUser($user)
	{
		$sql = "select name from myukdate where name = '$user'";
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

	static function sleep($time)
	{
		$sleep_time = $time;
		while($sleep_time>=60)
		{
			if($sleep_time!=$time)
			{
				$txt_time = funcs::secondToTextTime($sleep_time);
				funcs::savelog("Still sleeping [".$txt_time." left]");
			}
			sleep(60);
			$sleep_time-=60;

		}
		sleep($sleep_time);
	}

	static function secondToTextTime($seconds)
	{
		$h = (int)($seconds / 3600);
		$m = (int)(($seconds - $h*3600) / 60);
		$s = (int)($seconds - $h*3600 - $m*60);
		return (($h)?(($h<10)?("0".$h):$h):"00").":".(($m)?(($m<10)?("0".$m):$m):"00").":".(($s)?(($s<10)?("0".$s):$s):"00");
	}

	static function checkRunningTime($start_time, $end_time)
	{
		$current_time = strtotime(date('Y-m-d H:i:s'));
		$before_start = (strtotime($start_time)-strtotime(date('Y-m-d H:i:s')));
		if($before_start>0)
		{
			funcs::savelog("Start time is : ".$start_time);
			funcs::sleep($before_start);
		}
		elseif(strtotime($end_time)<$current_time)
		{
			funcs::savelog("End time at : ".date('Y-m-d H:i:s',$current_time));
			funcs::savelog("FINISHED");
			exit;
		}
	}
}
?>