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
		$postData = array(
							'username' => $username,
							'password' => $password,
							'login' => 'Login',
							'islogin' => '1'
						  );
		//"username=".$username."&password=".$password."&login=Login&islogin=1";

		$need_login = true;

		/*// Check cookie file
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
		}*/

		
		
		if(!(self::isLoggedIn($username)))
		{
			// count try to login
			for($count_login=1; $count_login<=3; $count_login++)
			{
				self::savelog("Logging in with profile: ".$username);
				$ch = curl_init();
				
				curl_setopt($ch, CURLOPT_URL, $loginURL);
				curl_setopt($ch, CURLOPT_REFERER, $loginRefererURL);
				curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: www.datingonworld.com', 'Origin: http://www.datingonworld.com'));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_TIMEOUT,30); 
			
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
				
				curl_setopt($ch, CURLOPT_HEADER, 1);
				//curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
				curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
				$result = curl_exec($ch);
				curl_close($ch);

				//echo $result;
				//die('-----<br/>Logged In');

				/*$cookie = self::parse_curl_cookie($cookie_path);
				if($cookie!="")
				{
					self::savelog("Logged in with profile: ".$username);
					return true;
				}
				else
				{
					self::savelog("Log in failed with profile: ".$username);
					//return false;
				}*/
				if(strpos($result, "Logout")!==false)//If logged in
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
						$sql = "UPDATE user_profiles set status = 'false' WHERE username='".$username."' AND site_id=23 LIMIT 1";						
						DBConnect::execute_q($sql);
						funcs::savelog("Couldn't login.");
						funcs::savelog("FINISHED");

						exit;
					}
				}

				

			}//end for count login
		}
		else
			return true;
	}

	static function isLoggedIn($username)
	{
		self::savelog("Checking login status for profile: ".$username);
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, "http://www.datingonworld.com/");
		curl_setopt($ch, CURLOPT_REFERER, "http://www.datingonworld.com/");
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: www.datingonworld.com', 'Origin: http://www.datingonworld.com'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,30); 
	
		curl_setopt($ch, CURLOPT_HEADER, 1);
		//curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		//curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$indexpage = curl_exec($ch);
		curl_close($ch);
		
		/*echo "<br/>---------<br/>";
		echo $indexpage;
		echo "<br/>---------<br/>";*/

		if(strpos($indexpage, "Logout")!==false)//If logged in
			$loggedin = true;
		else
			$loggedin = false;

		return $loggedin;
	}

	static function isCookieValid($username)
	{
		/*
		$cookie_path = self::getCookiePath($username);
		$cookie = self::parse_curl_cookie($cookie_path);
		$rp_cookie = str_replace('www.datingonworld.com','',$cookie);
		
		$expire = substr($rp_cookie, 0, 10);		
		$diff = (int)$expire-time();
		*/
		/*
		if($diff > (1*60*60))
		{
			return true;
		}
		else
		{
			return false;
		}
		*/
		//return true for site datingonworld only
		return true;
	}
	
	static function getMembersFromSearchResult($content,$count)
	{ 
		/*
		if($count==2)
		{
			echo $content;exit;
		}
		*/
		$header = substr($content, strpos($content, 'HTTP/'), strpos($content, 'charset=utf-8') - strpos($content, 'HTTP/'));
		$key = substr($header, strpos($header,'location: http://www.datingonworld.com/search/'), strpos($header,'Content-Length: 0')- strpos($header,'location: http://www.datingonworld.com/search/'));
		
		$key = str_replace(array('location: http://www.datingonworld.com/search/','/',' '),'',$key);
		
		$a = strpos($content,'<div class="outter page_search_results">');
		$b = strpos($content, '<div id="footer_wrap">');
		$c = $b - $a;

		$content = substr($content,strpos($content,'<div class="dataitem odd ">'),$b - strpos($content, '<div class="dataitem odd ">'));		
		$content = substr($content,0,strpos($content,'<div class="clear"></div>'));
	
		$content = str_replace(array('&nbsp;', '<table class="plain">', '</table>','<tbody>','</tbody>','<td class="data">','<td>','</td>','<tr>','</tr>'),'',$content);
		$content = str_replace('<br>','<br />',$content);
		$content = str_replace('"><br />', '" /><br />', $content);
		$content = substr($content,0,-9);
	
		
			$xml="<?xml version='1.0' standalone='yes' ?><members>".$content."</members>";
		
		$parser = new XMLParser($xml);
		$parser->Parse();
		
		//$arr_memberInfo = array();

		if(isset($parser->document->div))
		{
			
				
			
			foreach($parser->document->div as $member)
			{
				$member_id = explode('/', $member->div[0]->a[0]->img[0]->tagAttrs['src']);
				if(isset($member_id[9]))
				{
					$user_id = $member_id[9];
				}
				else
				{
					$user_id = "";
				}
				
				//echo "userid: " . $user_id . "  name: ".$member->div[1]->tagChildren[0]->tagChildren[0]->tagData . "  img: " . $member->div[0]->a[0]->img[0]->tagAttrs['src'] . "  etc::".$member->div[1]->dl[0]->dd[0]->tagData . "<br>";
				self::saveMember($user_id, $member->div[1]->tagChildren[0]->tagChildren[0]->tagData, $member->div[0]->a[0]->img[0]->tagAttrs['src'], $member->div[1]->dl[0]->dd[0]->tagData);
				
			}
			if($count==1)
			{
				return $key;
			}
		}
		else
		{	
			/*
			echo "no search result";
			echo $content;
			*/
		}

	}

	
	static function getIdUpdate($content)
	{
		$content = substr($content, strpos($content,'action="http://www.datingonworld.com/member/'), strpos($content, '/guestbook/">') - strpos($content,'action="http://www.datingonworld.com/member/'));
		$content = str_replace('action="http://www.datingonworld.com/member/', '', $content);
		
		$userid = trim($content);
		
		return $userid;
	}


	static function saveMembers($list,$post)
	{
		if(count($list)>0)
		{
			self::savelog("Saving to database");
			foreach($list as $member)
			{
				DBConnect::execute_q("INSERT INTO datingonworld_member (username, gender, age, pic, country, created_datetime) VALUES ('".$member['username']."', '".$post["ctl00\$cphContent\$dropGender"]."','".$member['age']."', '".$member['pic']."', '".$member["ctl00\$cphContent\$dropCountry"]."', NOW())");
			}
			self::savelog("Saving done");
		}
	}

	static function getMembers($post,$amount)
	{
		$sql = "SELECT username FROM datingonworld_member WHERE gender='".$post["ctl00\$cphContent\$dropCountry"]."' LIMIT ".$amount;
		return DBConnect::assoc_query_2D($sql);
	}

	static function getNextMember($post)
	{
		$sql = "SELECT userid,username FROM datingonworld_member WHERE gender='".$post["targettedGender"]."' and username NOT IN (SELECT to_username FROM datingonworld_sent_messages) AND ((id-1)%6)=(".BOT_ID."-1) ORDER BY id ASC LIMIT 1";
		return DBConnect::assoc_query_1D($sql);
	}


	static function sendMessage($from, $toId, $toName, $subject, $message)
	{
		//$toId = "52110";
		//$toName = "jackharris11";
		$cookie_path = self::getCookiePath($from);
		$sendMessagePostData = "subject=".$subject."&body=".$message."&submit=Send&ismessage=1";
		$sendMessageURL = "http://www.datingonworld.com/account/messages/compose/".$toId."/";
		$sendMessageReferer = $sendMessageURL;
		//self::savelog('Send URL '.$sendMessageURL);

		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $sendMessageURL);
		curl_setopt($ch, CURLOPT_REFERER, $sendMessageReferer);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,30); 
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $sendMessagePostData);

		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch);
		//echo $result;
		curl_close($ch);

		
		file_put_contents("sending/".$from."-".$toName.".txt",$result);
		
		if(strpos($result, "Message has been successfully sent")>-1)
		{
			funcs::savelog("Sent message to ".$toName);
			
			DBConnect::execute_q("INSERT INTO datingonworld_sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".$toName."','".$from."','".addslashes($subject)."','".addslashes($message)."',NOW())");
			return true;
		}
		else
		{
			
			funcs::savelog("Sending message to ".$toName." failed");
			DBConnect::execute_q("DELETE FROM datingonworld_member WHERE username='".$toName."'");
			//DBConnect::execute_q("UPDATE datingonworld_member set member_status ='false' where username='".$to."'");
			return false;
		}
	}


	static function saveMember($id, $name, $pic, $age)
	{
		$sql = "INSERT INTO `bot`.`datingonworld_member` (`id`, `userid`, `username`, `gender`, `targettedGender`, `country`, `pic`, `age`, `created_datetime`, `member_status`) VALUES (NULL, '".$id."', '".$name."', '', '', '', '".$pic."', '".$age."', NOW(), 'true');";
		echo $sql;echo "<br>";
		mysql_query($sql);
	}

	static function dbGetNoIdMember()
	{
		$sql = "SELECT id, username FROM `bot`.`datingonworld_member` WHERE userid=0 ";
		$query = mysql_query($sql);

		//$arr_result = array();
		while($result=mysql_fetch_array($query))
		{
			$arr_result[] = $result;
		}

		return $arr_result;
	}

	static function updateMember($id, $userid, $name)
	{
		$sql = "UPDATE `bot`.`datingonworld_member` SET userid='".$userid."' WHERE id = '".$id."' AND username = '".$name."'";
		if(mysql_query($sql))
		{
			return true;
		}else
		{
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
		$content = substr($content,strpos($content,'<div class="text">'));
		$content = trim(substr($content,0,strrpos($content,'<div class="message-navigation">')));
		$content = str_replace("’","'",$content);
		$content = str_replace("“","\"",$content);
		$content = str_replace("´","\"",$content);

		//Hack for some invalid XML
		$content = str_replace("\"></span>","\"/></span>",$content);
		$content = str_replace('<span class="bottom-bg"><span>&nbsp;</span></span>',"",$content);
		$xml="<?xml version='1.0' standalone='yes' ?>".$content;
		//file_put_contents("xml-".$username."-".$page.".txt",$xml);

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
						//"."			=> array("..","...","!"),
						"..."		=> array("..","."),
						"you "		=> array("u "),
						"are "		=> array("r "),
						"?"			=> array("?!?"),
						//" "			=> array("  ","   "),
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
					$message=str_replace($key,$words[rand(0,count($words)-1)],$message);
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
				exit;
			}
		}
	}


	static function parse_curl_cookie($cookie_file)
    {
		if(file_exists($cookie_file))
		{
			$cookie = file_get_contents($cookie_file);
			$cookie = str_replace("\r\n","\n",$cookie);
			$cookie = str_replace("\r","\n",$cookie);
			$lines = explode("\n",$cookie);
			
			if($lines[6]!='')
			{
				return $lines[6];
			}
			else
			{
				return false;
			}
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


	static function countMember()
	{
		$sql = "SELECT COUNT(*) FROM datingonworld_member WHERE userid=0";
		$query = mysql_query($sql);
		$num = mysql_num_rows($query);

		return $num;
	}


	static function getSentUser($username)
	{
		$cookie_path = self::getCookiePath($username);
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, "http://www.datingonworld.com/account/messages/sent/");
		curl_setopt($ch, CURLOPT_REFERER, 'http://www.datingonworld.com/account/messages/sent/1/');
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_TIMEOUT,30); 
		//curl_setopt($ch,CURLOPT_POST, 1);
		//curl_setopt($ch,CURLOPT_POSTFIELDS, $sendMessagePostData);

		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch);
		curl_close($ch);
		$arr_data = array();
		$arr_content = explode(' ',$result);
		foreach($arr_content as $datas)
		{
			if(strstr($datas, 'name="message_id'))
			{
				$messageid = str_replace(array('name="','"'),'',$datas);
				array_push($arr_data, $messageid);
			}
		}

		return $arr_data;
	}

	static function messageRecipient($username)
	{
		$cookie_path = self::getCookiePath($username);
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, "http://www.datingonworld.com/account/messages/");
		curl_setopt($ch, CURLOPT_REFERER, 'http://www.datingonworld.com/account/messages/inbox/1/');
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_TIMEOUT,30); 
		//curl_setopt($ch,CURLOPT_POST, 1);
		//curl_setopt($ch,CURLOPT_POSTFIELDS, $sendMessagePostData);

		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch);
		curl_close($ch);
		
		$arr_data = array();
		$arr_content = explode(' ',$result);
		foreach($arr_content as $datas)
		{
			if(strstr($datas, 'name="message_id'))
			{
				$messageid = str_replace(array('name="','"'),'',$datas);
				array_push($arr_data, $messageid);
			}
		}

		return $arr_data;

	}


	static function saveMessageRecipient($username)
	{
		$arr_message = self::messageRecipient($username);
		$countInbox = count($arr_message);

		if($countInbox>=6)
		{
			foreach($arr_message as $message_id)
			{
				$message_id = str_replace(array('message_id[', ']'),'',$message_id);
				$cookie_path = self::getCookiePath($username);
				$ch = curl_init();
				
				curl_setopt($ch, CURLOPT_URL, "http://www.datingonworld.com/account/messages/inbox/read/".$message_id."/");
				curl_setopt($ch, CURLOPT_REFERER, 'http://www.datingonworld.com/account/messages/');
				curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch,CURLOPT_TIMEOUT,30); 
				//curl_setopt($ch,CURLOPT_POST, 1);
				//curl_setopt($ch,CURLOPT_POSTFIELDS, $sendMessagePostData);

				curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
				curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
				$result = curl_exec($ch);
				curl_close($ch);

				$sender1 = substr($result, strpos($result, '<li>Sender:'), 130);
				$sender = substr($sender1, strpos($sender1, 'Sender: '), strpos($sender1, '</a></li>') - strpos($sender1, 'Sender: '));
				$sender = substr($sender, strpos($sender,'">'));
				$sender = str_replace('">', '', $sender);

				$receive_date = substr($sender1, strpos($sender1, '<li>Sent on'), strpos($sender1, '</li>') - strpos($sender1, '<li>Sent on'));
				$receive_date = str_replace(array('<','li','/','>'),'',$receive_date);
				

				$a = strpos($result, '<h2 class="inner"><a href="http://www.datingonworld.com/account/messages/inbox/read/');
				$subject = substr($result, $a, strpos($result, '</a></h2>') - $a);
				$subject = substr($subject, strpos($subject, '/">'));
				$subject = str_replace(array('"','/','>'),'',$subject);
				$subject = mysql_real_escape_string($subject);

				$b = strpos($result, '<div class="entry">');
				$message = substr($result, $b, strpos($result, '<form method="post" name="message" id="privatemessageform"') - $b);
				$message = str_replace('<div class="entry">','', $message);
				$message = mysql_real_escape_string($message);

				if($subject != '' and $message != '')
				{
					$sql = "INSERT INTO `bot`.`datingonworld_receive_message` (`id`, `sender`, `recipient`, `subject`, `message`, `receivedate`) VALUES (NULL, '".$sender."', '".$username."', '".$subject."', '".$message."', '".$receive_date."')";
					
					if(mysql_query($sql))
					{
						
						self::savelog("INSERT INBOX DATAS TO DATABASE ID : $message_id");
						self::deleteRecipientMsg($username, $message_id);
					}

				}				
				
			}//foreach

		}//if countInbox		
		
	}

	static function deleteRecipientMsg($username, $message_id)
	{
		
		self::savelog("DELETE MESSAGE FROM INBOX MESSAGE ID: $messsage_id");
		$cookie_path = self::getCookiePath($username);
		$sendMessagePostData = "message_id[".$message_id."]=1";
		$ch = curl_init();


		
		curl_setopt($ch, CURLOPT_URL, "http://www.datingonworld.com/account/messages/inbox/delete/1/");
		curl_setopt($ch, CURLOPT_REFERER, 'http://www.datingonworld.com/account/messages/inbox/read/'.$message_id.'/');
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_TIMEOUT,30); 
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $sendMessagePostData);

		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch);
		curl_close($ch);
	}
	
	static function deleteSentMessage($username, $arrMessage)
	{
		$cookie_path = self::getCookiePath($username);
		$sendMessagePostData = "check_all_messages=on";
		foreach($arrMessage as $message)
		{
			$sendMessagePostData .= "&".$message."=on";
		}

		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, "http://www.datingonworld.com/account/messages/sent/delete/1/");
		curl_setopt($ch, CURLOPT_REFERER, 'http://www.datingonworld.com/account/messages/sent/');
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,30); 
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $sendMessagePostData);

		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch);
		curl_close($ch);
	}
}
?>