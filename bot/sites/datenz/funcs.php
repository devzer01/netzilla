<?php
require_once("XMLParser.php");
class funcs
{
	static function getCookiePath($username)
	{
		return dirname($_SERVER['SCRIPT_FILENAME'])."/cookies/".$username.".txt";
	}

	static function memberlogin($username, $password, $loginURL, $loginRefererURL)
	{
		$cookie_path = self::getCookiePath($username);
		$postData = array(	"Username" => $username,
							"Password" => $password,
							"Submit" => "Log In"
							);
		/*echo "<pre>";
		print_r($postData);
		echo "</pre>";
		die("Log in");*/
		
		$postData = http_build_query($postData);
		$need_login = true;

		// Check cookie file
		if(!file_exists($cookie_path))
		{
			self::savelog("No cookie file for profile '$username'");
			$need_login = true;
		}
		else
		{
			if(self::isCookieValid($username))
			{
				self::savelog("Cookie for profile '$username' is not expired, no login needed.");
				$need_login = false;
			}
			else
			{
				self::savelog("Cookie for profile '$username' is expired, perform login.");
				$need_login = true;
			}
		}
		if($need_login)
		{
			for($count_login = 1; $count_login <= 3; $count_login++)
			{
				self::savelog("Logging in with profile: ".$username);
				$ch = curl_init();
				
				curl_setopt($ch, CURLOPT_URL, $loginURL);
				curl_setopt($ch, CURLOPT_REFERER, $loginRefererURL);
				curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: www.datenz.com', 'Origin: http://www.datenz.com'));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_TIMEOUT,30); 
			
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
				
				//curl_setopt($ch, CURLOPT_HEADER, 1);
				curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
				curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
				$result = curl_exec($ch);
				//echo "<div style='border:solid 1px #F00'>".$result."</div>"; //die('<br/>Log in result');
				//file_put_contents("logging/".ID."_".$username.".log", $result);
					
				curl_close($ch);

				$cookie = self::parse_curl_cookie($cookie_path);
				//echo $_SERVER["REQUEST_URI"];
				
				if(strpos($result, $username)!==false) //if($cookie['sid']!="")//
				{
					self::savelog("Logged in with profile: ".$username);
					return true;
				}
				else
				{
					self::savelog("Log in failed with profile: ".$username);
					self::savelog("Log in failed $count_login times.");
					if($count_login==3)
					{
						self::savelog("User ".$username." tried to login 3 times. This username would be deleted.");
						$sql = "UPDATE user_profiles set status = 'false' WHERE username='".$username."' AND site_id=29 LIMIT 1";						
						DBConnect::execute_q($sql);
						funcs::savelog("Couldn't login.");
						funcs::savelog("FINISHED");

						exit;
					}
				}
			}
		}
		else
			return true;
	}

	static function getViewState($username, $loginRefererURL)
	{
		$cookie_path = self::getCookiePath($username);

		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $loginRefererURL);
		curl_setopt($ch, CURLOPT_REFERER, $loginRefererURL);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,30); 
		
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch);
		curl_close($ch);

		$result=substr($result,strpos($result,"id=\"__VIEWSTATE\""));
		$result=str_replace("id=\"__VIEWSTATE\" value=\"","", $result);
		$result=trim(substr($result,0,strpos($result,"\" />")));
		return $result;
	}

	static function isCookieValid($username)
	{
		$cookie_path = self::getCookiePath($username);
		$cookie = self::parse_curl_cookie($cookie_path);
		if(isset($cookie['Username']))
			return true;
		else
			return false;
		/*$diff = (int)$cookie['Username']['expired']-time();
		if($diff > (1*60*60))
		{
			return true;
		}
		else
		{
			return false;
		}*/
	}

	static function getSearchResult($username, $searchURL, $searchReferer, $searchData, $page)
	{
		$viewstate = self::getViewState($username, $searchReferer);
		$cookie_path = self::getCookiePath($username);

		if($page>2)
		{
			$searchURL		= "http://www.datenz.com/member/user_list.asp?page=". $page;
			$searchReferer	= "http://www.datenz.com/member/user_list.asp=". $page-1;
		}
		elseif($page==2)
		{
			$searchReferer = "http://www.datenz.com/member/user_list.asp";
			$searchURL = "http://www.datenz.com/member/user_list.asp?page=2";
		}

		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $searchURL);
		curl_setopt($ch, CURLOPT_REFERER, $searchReferer);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: www.datenz.com', 'Origin: http://www.datenz.com'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); 

		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $searchData);

		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch); 
		//echo "<div style='border:solid 1px #F00'>".$result."</div>"; die('<br/>Search result');
		curl_close($ch);

		return self::getMembersFromSearchResult($username, $page, utf8_encode($result));
	}

	static function getMembersFromSearchResult($username, $page, $content)
	{
		//$content=file_get_contents("sample-search-content.html");
		//Find NEXT url
		$content = substr($content, strpos($content, '<table width=100% cellspacing=0 cellpadding=0>'));
		$content = substr($content, 0, strpos($content, "<form action='/member/user_list.asp'>"));
		$content = str_replace("&", "&amp;", $content);
		$content = str_replace("/files/", "http://www.datenz.com/files/", $content); 
		$content = str_replace("&gt;", "", $content); 

		$tidy_config = array(	'clean' => true,
								'output-xhtml' => true,
								'show-body-only' => true,
								'wrap' => 0,
								'indent' => true,
								'indent-spaces' => 4
                     );
		$content = tidy_parse_string($content, $tidy_config, 'UTF8');
		$content->cleanRepair( );
		$content = str_replace("&nbsp;"," ",$content);
		
		$xml="<?xml version='1.0' standalone='yes' ?><members>".$content."</members>";
		
		//echo $xml; die;
		$parser = new XMLParser($xml);//--
		$parser->Parse();//--
		//echo $parser->GenerateXML(); //die();
		/*
		<table width="100%" cellspacing="0" cellpadding="0">
		<tr valign="top" height="130">
			<td class="pic" width="100" valign="middle" align="center">
				<a href="/member/user_profile.asp?user_id=6637">
					<img src="/files/photo_Male.gif" border="0" width="80" />
				</a>
			</td>*/

		$list = array();
		//tagData tagParents tagChildren tagAttrs tagName tagAttrs['class']

		if(isset($parser->document->table))
		{
			foreach($parser->document->table as $table)
			{
				for($i=0; $i<20; $i=$i+2)
				{
					$user_id = str_replace("/member/user_profile.asp?user_id=","",$table->tr[$i]->td[1]->table[0]->tr[0]->td[0]->table[0]->tr[0]->td[0]->a[0]->tagAttrs['href']);
					$user_name = $table->tr[$i]->td[1]->table[0]->tr[0]->td[0]->table[0]->tr[0]->td[0]->a[0]->tagData;
					$user_img = $table->tr[$i]->td[0]->a[0]->img[0]->tagAttrs['src'];
					//echo "-".$user_id." : ".$user_name." : ".$user_img."<br/>";
					if($user_id!="" && $user_name!="" && $user_img!="")
						array_push($list,array('userid' => $user_id, 'username' => $user_name, 'pic' => $user_img));
				}
			}
		}

		/*echo "<pre>";
		print_r($list);
		echo "</pre>";
		die();*/

		$searchData = array();
		return array($list, $searchData);
	}

	static function saveMembers($list, $post)
	{
		foreach($list as $member)
		{
			//$sql = "INSERT INTO datenz_member (username, userid, gender, pic, country, created_datetime) VALUES ('".$member['username']."', '".$member['userid']."', '".$post['Gender_Look']."', '".$member['pic']."', '".$post['country']."', NOW())";
			//funcs::savelog($sql);
			DBConnect::execute_q("INSERT INTO datenz_member (username, userid, gender, pic, country, created_datetime) VALUES ('".$member['username']."', '".$member['userid']."', '".$post['Gender_Look']."', '".$member['pic']."', '".$post['country']."', NOW())");
		}
	}

	static function getMembers($post, $amount)
	{
		$sql = "SELECT username FROM datenz_member WHERE gender='".$post['Gender_Look']."' LIMIT ".$amount;
		return DBConnect::assoc_query_2D($sql);
	}

	static function getNextMember($post)
	{
		$sql = "SELECT username,userid FROM datenz_member WHERE gender='".$post['Gender_Look']."' AND username NOT IN (SELECT to_username FROM datenz_sent_messages) AND ((id-1)%6)=(".BOT_ID."-1) ORDER BY id ASC LIMIT 1";
		//echo $sql;
		/*self::savelog($sql);
		self::savelog("FINISHED");
		self::savelog("2-".$post[data][User][Gender]."-<br/>");
		self::savelog("3-".$post['data[User][Gender]']."-<br/>");*/

		//$member = DBConnect::assoc_query_1D($sql);
		//file_put_contents("logs/".ID."_post.log",print_r($member,true));

		return DBConnect::assoc_query_1D($sql);
	}

	static function sendMessage($from, $username, $userid, $subject, $message, $sendMessageURL, $sendMessageReferer)
	{
		$cookie_path = self::getCookiePath($from);
		$sendMessageReferer .= "?parent_id=0&username=".$username."&receiver_id=".$userid;
		//echo $sendMessageURL; die();
		//$viewstate = self::getViewState($from, $sendMessageReferer);
/*receiver_id:6637
parent_id:0
subject:Hey!!!
message:It's would be nice if we can have a chat. What do you think?*/
		$postData = array(	'receiver_id' => $userid,
							'parent_id' => '0',
							'subject' => $subject,
							'message' => $message
							);
		$sendMessagePostData = http_build_query($postData);

		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $sendMessageURL);
		curl_setopt($ch, CURLOPT_REFERER, $sendMessageReferer);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: www.datenz.com', 'Origin: http://www.datenz.com'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_TIMEOUT,30);
		
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $sendMessagePostData);

		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

		$result = curl_exec($ch);
		file_put_contents("sending/".ID."_".$from."_send_to_".$username.".log", $result);
		//echo $result; //die("<br/>Sent Message");
		curl_close($ch);

		if(strpos($result, "Check your Email by clicking on the 'Inbox' button below. You can also see what Email's you have sent out by clicking on the 'Outbox' button.")>-1)
		{
			funcs::savelog("Sent message to ".$username);
			DBConnect::execute_q("INSERT INTO datenz_sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".$username."','".$from."','".addslashes($subject)."','".addslashes($message)."',NOW())");
			return true;
		}
		else
		{
			funcs::savelog("Sending message to ".$username." failed");
			DBConnect::execute_q("DELETE FROM datenz_member WHERE username='".$username."'");
			return false;
		}
	}
	
	static function checkInboxMessage($username, $inboxURL, $from)
	{
		$content = self::checkInboxPage($username, $inboxURL, $from);
		return self::getInboxMessagesFromInboxPage($content);
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

	static function checkInboxPage($username, $inboxURL, $from)
	{
		funcs::savelog("Receiving inbox page");
		$cookie_path = self::getCookiePath($username);
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $inboxURL);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch);
		funcs::savelog("Receiving of inbox page done");
		curl_close($ch);
		return $result;
	}

	static function getInboxMessagesFromInboxPage($content)
	{
		$list = array();
		$content = substr($content, strpos($content, '<div class="text">'));
		$content = trim(substr($content, 0, strrpos($content, '<div class="message-navigation">')));
		$content = str_replace("’", "'", $content);
		$content = str_replace("“", "\"", $content);
		$content = str_replace("´", "\"", $content);

		//Hack for some invalid XML
		$content = str_replace("\"></span>", "\"/></span>", $content);
		$content = str_replace('<span class="bottom-bg"><span>&nbsp;</span></span>', "", $content);
		$xml="<?xml version='1.0' standalone='yes' ?>".$content;
		//file_put_contents("xml-".$username."-".$page.".txt", $xml);

		$parser = new XMLParser($xml);
		$parser->Parse();

		if(isset($parser->document->div))
		{
			foreach($parser->document->div as $message)
			{
				if(isset($message->ul[0]->li[3]))
				{
					array_push($list, array(	"username"=>$message->ul[0]->li[3]->span[0]->a[0]->tagData,
												"subject"=>$message->ul[0]->li[2]->span[0]->a[0]->tagData,
												"url"=>$message->ul[0]->li[2]->span[0]->a[0]->tagAttrs['href']
											)
								);
				}
			}
		}
		return $list;
	}

	static function randomText($message)
	{
		$list = array(
						" your "	=> array(" ur "),
						"I'm "		=> array("Im ","im ", "I am ","i'm "),
						" for "		=> array(" 4 "),
						" to "		=> array(" 2 "),
						"."			=> array("..","...","!"),
						"..."		=> array("..","."),
						"you "		=> array("u "),
						"are "		=> array("r "),
						"?"			=> array("?!?"),
						" "			=> array("  ","   "),
						" you're "	=> array(" u're "),
						"!"			=> array(".","..","..."),
						" be "		=> array(" b ")
					);
		if(rand(0,1))
		{
			foreach($list as $key => $words)
			{
				if(rand(0,1))
				{
					$message=str_replace($key, $words[rand(0,count($words)-1)], $message);
				}
			}
		}
		return $message;
	}

	static function savelog($msg)
	{
		$time=date("Y-m-d H:i:s");
		$scrollScript = "<script>window.scrollTo(0, document.body.scrollHeight);</script>";

		echo "[$time] $msg<br/>\r\n".$scrollScript;
		ob_end_flush();
		ob_flush();
		flush();
		ob_start();

		file_put_contents("logs/".ID."_latest.log","[$time] $msg");
		file_put_contents("logs/".ID.".log","[$time] $msg\r\n",FILE_APPEND);

		if(file_exists("logs/".ID."_command.log"))
		{
			$txt_command = file_get_contents("logs/".ID."_command.log");
			if($txt_command == "STOP")
			{
				file_put_contents("logs/".ID."_latest.log","[$time] Force stop");
				file_put_contents("logs/".ID.".log","[$time] Force stop\r\n",FILE_APPEND);
				unlink("logs/".ID."_command.log");
				exit;
			}
		}

		if(file_exists("logs/".ID."_run_count.log"))
		{
			$txt_count = file_get_contents("logs/".ID."_run_count.log");
			if($txt_count != RUN_COUNT)
			{
				exit;
			}
		}

	}

	static function parse_curl_cookie($cookie_file)
    {
		if(file_exists($cookie_file))
		{
			$cookie = file_get_contents($cookie_file);
			$cookie = str_replace("\r\n", "\n", $cookie);
			$cookie = str_replace("\r", "\n", $cookie);
			$lines = explode("\n", $cookie);
			$result = array();
			foreach($lines as $line)
			{
				if(strpos($line,"www.4ppl.com")>-1)
				{
					$contents = explode("\t", $line);
					$result[$contents[5]]=array("value"=>$contents[6],"expired"=>$contents[4]);
				}
			}
			return $result;
		}
		else
		{
			return false;
		}
    }

	function mb_unserialize($serial_str)
	{ 
		$out = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $serial_str ); 
		return unserialize($out); 
	}

	function getCountExistingUser()
	{
		$sql = "SELECT COUNT(id) AS total FROM datenz_member";
		return DBConnect::assoc_query_1D($sql);
	}

}
?>