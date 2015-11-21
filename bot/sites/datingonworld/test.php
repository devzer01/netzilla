<?php
ob_start();
set_time_limit(0);
date_default_timezone_set("Asia/Bangkok");
ignore_user_abort(true);

require_once('DBconnect.php');
require_once('funcs.php');
require_once('config.php');
/*
$mId = funcs::getSentUser('Nikkigreen69');

//funcs::savelog("Clear messages in sent box");
$arrdata = funcs::getSentUser('Nikkigreen69');
funcs::deleteSentMessage('Nikkigreen69', $arrdata);
*/

/*
//COOKIE
$cookie = file_get_contents('cookies/MissJonelyn.txt');
$arr_cookie = explode('#HttpOnly_.single123.com	TRUE	/	FALSE	',$cookie);
$expire = substr($arr_cookie[1], 0, 10);
$diff = (int)$expire-time();

$test = funcs::isCookieValid('MissJonelyn');
echo $test;
*/


//CHECK RECIPIENT MESSAGE
//http://www.single123.com/account/messages/inbox/read/655227/
$loginURL = "http://www.single123.com/account/login/";
$loginRefererURL = "http://www.single123.com/";
$sendMessageURL = "http://www.single123.com/account/messages/compose/";

$receiverUsername = "jackharris11";
$receiverPassword = "poppy111";
$receiverInboxURL = "http://www.single123.com/account/messages/";

//funcs::memberlogin($receiverUsername, $receiverPassword,$loginURL,$loginRefererURL);
//$arr = funcs::messageRecipient($receiverUsername);
//foreach($arr as $m)
//{
//	echo $m."<BR>";
//}

$arr_message = funcs::messageRecipient($receiverUsername);
		$countInbox = count($arr_message);

		if($countInbox>=6)
		{
			foreach($arr_message as $message_id)
			{
				$message_id = str_replace(array('message_id[', ']'),'',$message_id);
				$cookie_path = funcs::getCookiePath($receiverUsername);
				$ch = curl_init();
				
				curl_setopt($ch, CURLOPT_URL, "http://www.single123.com/account/messages/inbox/read/".$message_id."/");
				curl_setopt($ch, CURLOPT_REFERER, 'http://www.single123.com/account/messages/');
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
				

				$a = strpos($result, '<h2 class="inner"><a href="http://www.single123.com/account/messages/inbox/read/');
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
					$sql = "INSERT INTO `bot`.`single123_receive_message` (`id`, `sender`, `recipient`, `subject`, `message`, `receivedate`) VALUES (NULL, '".$sender."', '".$receiverUsername."', '".$subject."', '".$message."', '".$receive_date."')";
					 
					if(mysql_query($sql))
					{
						
						//self::savelog("INSERT INBOX DATAS TO DATABASE ID : $message_id");
						$id = funcs::deleteRecipientMsg($receiverUsername, $message_id);
						echo 'delete message'.$id."<br>";
					}
					

				}				
				
			}//foreach

		}//if countInbox

?>