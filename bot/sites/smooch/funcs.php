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

		$postUrl = 'http://www.smooch.com/free-online-dating/asp/entryProc.asp';
// 		$postData = 'username=yumi18&password=Yumiyumee18&ENTER=ENTER&showadHighResolution=1';
		$postData = "username=$username&password=$password&ENTER=ENTER&showadHighResolution=1";
		
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $postUrl);
		curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		
		curl_setopt($ch,CURLOPT_POST,4);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$postData);
		
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
		curl_setopt ($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$loginData = curl_exec($ch);
		
		curl_close($ch);
		
// 		print_r($loginData);
	}
	
	function get_contents($url, $username, $password){
		funcs::memberLogin($username, $password);
	
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 1);
		curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		
// 		curl_setopt($ch,CURLOPT_POST,4);
// 		curl_setopt($ch,CURLOPT_POSTFIELDS,$postData);
		
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
		curl_setopt ($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$buffer = curl_exec($ch);
		
		curl_close($ch);
	
		return $buffer;
	}
	
	function insertData($site, $content)
	{
		$slashContent = addslashes($content);
		$sql = "insert into fetchdata (id, site, data) values('', '$site', '$slashContent')";
		mysql_query($sql);
	
		return null;
	}
	
	function db_get_content()
	{
		$sql = "select * from fetchdata where site = '$url' limit 1";
		$query = mysql_query($sql);
	
		return mysql_fetch_assoc($query);
	}
	
	static function getIdUsername($text)
	{
		$arrStr = explode(' ', $text);
		
		$newArr = array();
		foreach ($arrStr as $key=>$val)
		{
			if(strstr($val, "HREF='MailSelect"))
			{
				$replace1 = str_replace("'", '', $val);
				$replace2 = str_replace("HREF=MailSelect.asp?anc=0&NUM=", '', $replace1);
				$valArray = explode('&UN=',$replace2);
				array_push($newArr, $valArray);
		
			}
		
		}
		
		return $newArr;
	}
	
	static function dbCheckSentUser($user)
	{
		$sql = "select name from smooch where name = '$user'";
		$query = mysql_query($sql);
	
		$result = mysql_fetch_assoc($query);
	
		return $result;
	}
	
	static function sendMessage($subject, $message, $senderId, $senderName, $senderPass, $receiveId, $receiveName)
	{
		/************************************login *************************************/
		funcs::memberLogin($senderName, $senderPass);
		// 		print_r($loginData);
		/************************************end login *********************************/
	
		/************************************post message *********************************/
		//user: manday2012 , id: 4170243
		//user: yumi18, id:4167582
		$ch = curl_init();
			
		$postUrl = 'http://www.smooch.com/asp/MailWriteProc.asp';
		$postMessage = "Title=$subject&message_area=$message&RecipientUsername=$receiveName&RecipientID=$receiveId&SenderID=$senderId&SenderUsername=$senderName";
			
		curl_setopt($ch,CURLOPT_URL, $postUrl);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_REFERER, "http://www.smooch.com/asp/MailWrite.asp?NUM=$receiveId&UN=$receiveName");
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,30);
			
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
	
	function insertLog($username, $receiver)
	{
		$sql = "insert into smooch (id, sender, name, send_date) values ('', '$username', '$receiver', now())";
		mysql_query($sql);
		
	}
	
	function insertLogPage($username, $page)
	{
		$sql = "insert into smooch_page (id, name, page) values ('', '$username', '$page')";
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
		$sql = "select * from smooch_user_profile where status='true' and usergroup = 1";
		$query = mysql_query($sql);
		
		$arr= array();
		while ($result = mysql_fetch_assoc($query))
		{
			array_push($arr, array("id"=>$result['id'], "userid"=>$result['userid'], "username"=>$result['username'], "password"=>$result['password']));
		}
		
		return $arr;
	}
	
	static function db_get_last_page()
	{
		$sql = "select max(page) as lastpage from smooch_page";
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

}
?>