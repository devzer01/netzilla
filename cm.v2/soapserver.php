<?php
define("LANGUAGE", "eng");
require_once('classes/top.class.php');
//require_once('configs/eng.php');

define("PAYMENT_SERVER_IP", "188.132.221.114");
#set_time_limit(120);

//f?r SMSChatAdmin
//include_once('../global_soap/soapserver.php');

function getProfileFull($id)
{
	$profile = DBconnect::assoc_query_1D("SELECT id, username, forname, surname, picturepath, birthday,gender,signup_datetime,area,city,state,country,lookmen,lookwomen,lookpairs,minage,maxage,relationship,onenightstand,affair,friendship,picture_swapping,live_dating,role_playing, s_m, partner_exchange, voyeurism, height, weight, appearance, eyescolor, haircolor, beard, zodiac, civilstatus, sexuality, tattos, smoking, glasses, piercings, in_storno, description, coin, mobileno FROM member WHERE id=".$id);

	$fotoalbum = funcs::getAllFotoAlbum($id);
	$fotoalbum_text ="";
	if(is_array($fotoalbum) && count($fotoalbum))
	{
		foreach($fotoalbum as $img)
		{
			$fotoalbum_text .= '<a href="'.URL_WEB.UPLOAD_DIR.$img['picturepath'].'" target="_blank"><img src="'.URL_WEB.'thumbnails.php?w=100&file='.$img['picturepath'].'"/></a> ';
		}
	}

	if(!isset($profile['id']))
		$result=array();
	else
	{
		$result = array(
			array((($profile['picturepath']!='')?'img':''),(($profile['picturepath']!='')?'thumbnails.php?w=200&file='.$profile['picturepath']:'')),
			array((count($fotoalbum)?'Gallery':''),$fotoalbum_text),
			array('ProfileID',$profile['id']),
			array('Username',funcs::removeEncodingProbs($profile['username'])),
			array('Birthday',$profile['birthday'],'birthday',$profile['birthday']),
			array('Gender',(($profile['gender']==1)?'Man':'Lady'),'gender',array('Lady','Man'),$profile['gender']),
			array('Signup date/time',$profile['signup_datetime']),
			array('Coins',$profile['coin']),
			array('Mobile number',(($profile['mobileno']=="")?"No":"Yes")),
			array('<br><b>Address</b>',''),
			array('City',funcs::removeEncodingProbs(funcs::getAnswerCity('eng',$profile['city']))),
			array('State',funcs::removeEncodingProbs(funcs::getAnswerState('eng',$profile['state']))),
			array('Country',funcs::removeEncodingProbs(funcs::getAnswerCountry('eng',$profile['country']))),

			array('<br><b>Searching for</b>',''),
			array('Man',(($profile['lookmen']==1)?'<b>Yes</b>':'No'),'lookmen',array('No','Yes'),$profile['lookmen']),
			array('Lady',(($profile['lookwomen']==1)?'<b>Yes</b>':'No'),'lookwomen',array('No','Yes'),$profile['lookwomen']),

			array('Age from',$profile['minage'].' Years','minage',$profile['minage']),
			array('Age up to',$profile['maxage'].' Years','maxage',$profile['maxage']),

			array('Relationship',(($profile['relationship']==1)?'<b>Yes</b>':'No'),'relationship',array('No','Yes'),$profile['relationship']),
			array('One Night Stand',(($profile['onenightstand']==1)?'<b>Yes</b>':'No'),'onenightstand',array('No','Yes'),$profile['onenightstand']),
			array('Affair',(($profile['affair']==1)?'<b>Yes</b>':'No'),'affair',array('No','Yes'),$profile['affair']),
			array('Friendship',(($profile['friendship']==1)?'<b>Yes</b>':'No'),'friendship',array('No','Yes'),$profile['friendship']),
			array('Picture swapping',(($profile['picture_swapping']==1)?'<b>Yes</b>':'No'),'picture_swapping',array('No','Yes'),$profile['picture_swapping']),
			array('Live Dating',(($profile['live_dating']==1)?'<b>Yes</b>':'No'),'live_dating',array('No','Yes'),$profile['live_dating']),
			array('Role playing',(($profile['role_playing']==1)?'<b>Yes</b>':'No'),'role_playing',array('No','Yes'),$profile['role_playing']),

			array('SM',(($profile['s_m']==1)?'<b>Yes</b>':'No'),'s_m',array('No','Yes'),$profile['s_m']),
			array('Partner exchange',(($profile['partner_exchange']==1)?'<b>Yes</b>':'No'),'partner_exchange',array('No','Yes'),$profile['partner_exchange']),
			array('Voyeurism',(($profile['voyeurism']==1)?'<b>Yes</b>':'No'),'voyeurism',array('No','Yes'),$profile['voyeurism']),
			array('<br><b>Details</b>',''),
			array('Height',($profile['height']>0) ? funcs::getAnswerChoice('eng','', '$height', $profile['height']) : "",'height',eng::$height,$profile['height']),
			array('Weight',($profile['weight']>0) ? funcs::getAnswerChoice('eng','', '$weight', $profile['weight']) : "",'weight',eng::$weight,$profile['weight']),

			array('Appearance',getArrayElement(array('No comment','slender','normal','athletic','chubby','rounded'),$profile['appearance']),'appearance',array('No comment','slender','normal','athletic','chubby','rounded'),$profile['appearance']),
			array('Eye color',getArrayElement(array('No comment','brown','blue','green','other'),$profile['eyescolor']),'eyescolor',array('No comment','brown','blue','green','other'),$profile['eyescolor']),
			array('Hair color',getArrayElement(array('No comment','black','brown','blond','red','other'),$profile['haircolor']),'haircolor',array('No comment','black','brown','blond','red','other'),$profile['haircolor']),
			array('Beard',getArrayElement(array('No comment','none','little','beard','mustache'),$profile['beard']),'beard',array('No comment','none','little','beard','mustache'),$profile['beard']),
			array('Zodiac',getArrayElement(array('No comment','Aquarius','Pisces','Aries','Taurus','Gemini','Cancer','Lion','Virgo','Libra','Scorpio','Sagittarius','Capricorn'),$profile['zodiac']),'zodiac',array('No comment','Aquarius','Pisces','Aries','Taurus','Gemini','Cancer','Lion','Virgo','Libra','Scorpio','Sagittarius','Capricorn'),$profile['zodiac']),
			array('Civil status',getArrayElement(array('No comment','single','separated','divorced','widowed','married','relationship'),$profile['civilstatus']),'civilstatus',array('No comment','single','separated','divorced','widowed','married','relationship'),$profile['civilstatus']),

			array('Sexuality',getArrayElement(array('No comment','Homo','Hetero','Bisexual'),$profile['sexuality']),'sexuality',array('No comment','Homo','Hetero','Bisexual'),$profile['sexuality']),

			array('Tatoo',(($profile['tattos']==1)?'Yes':'No'),'tattos',array('No','Yes'),$profile['tattos']),
			array('Smoking',(($profile['smoking']==1)?'Yes':'No'),'smoking',array('No','Yes'),$profile['smoking']),
			array('Glasses',(($profile['glasses']==1)?'Yes':'No'),'glasses',array('No','Yes'),$profile['glasses']),
			array('Piercings',(($profile['piercings']==1)?'Yes':'No'),'piercings',array('No','Yes'),$profile['piercings']),
			array('Storno',(($profile['in_storno']==1)?'Yes':'No')),

			array('<br><b>Description</b>',''),
			array('edit',funcs::removeEncodingProbs($profile['description']),'description',$profile['description'])
		);
	}
	return $result;
}

function getProfileInfo($id)
{
        $profile = funcs::getProfile($id);
        $city = funcs::getAnswerCity('eng', $profile['city']);
        $area = funcs::getAnswerArea($profile['city']);
        $state = funcs::getAnswerState('eng', $profile['state']);
        $country = funcs::getAnswerCountry('eng', $profile['country']);

        if ($profile['mobileno'] == '')
                $mobileno = '000';
        else
                $mobileno = $profile['mobileno'];

        if ($profile['gender'] == 1)
                $gender = ($profile['lookmen']=='1')? 's' : 'm';
        elseif ($profile['gender'] == 2)
                $gender = ($profile['lookwomen']=='1')? 'l' : 'w';
        else
                $gender = 'u';

        $last_ip = DBConnect::retrieve_value("SELECT ip FROM pages_stat WHERE username='" . $profile['username'] . "' ORDER BY datetime DESC LIMIT 1");
        $ip = $last_ip ? $last_ip : $profile['ip_address'];

        $sql = "SELECT COUNT(id) FROM purchases_log WHERE user_id='" . $profile['id'] . "' AND purchase_finished=1 AND purchase_finished_date !='' AND purchase_finished_date !='0000-00-00 00:00:00'";
        $top_up = DBConnect::retrieve_value($sql);

        $array = array(
            'email' => $profile['email'],
            'nickname' => funcs::removeEncodingProbs($profile['username']),
            'alte' => $profile['birthday'],
            'sex' => $gender,
            'plz' => $area,
            'ort' => funcs::removeEncodingProbs($city),
            'region' => $state,
            'country' => $country,
            'fake' => $profile['fake'],
            'mobileNr' => $mobileno,
            'isScammer' => $profile['isScammer'],
            'ip' => $ip,
            'coin' => $profile['coin'],
            'top_up' => $top_up
        );
        //file_put_contents("soap-debug-getProfileInfo.txt",print_r($array,true));
        //file_put_contents("soap-debug-getProfileInfo.txt", $sql . " : '" . $top_up . "'\n\r", FILE_APPEND | LOCK_EX);
        return $array;
}

function sendBlackHawk($number, $message)
{
	$url = "http://" . BLACKHAWK_SERVER . ":" . BLACKHAWK_PORT . "/send/" . $number . "/" . urlencode($message);
	file_get_contents($url); 
}

//to(profileID) from(profileID) msg subject attach
function sendMessage($msg)
{
	$ret= 1;
	if(isset($msg->to) && DBConnect::retrieve_value("SELECT isactive FROM member WHERE id=".$msg->to))
	{
		$okay = funcs::sendfakeMessage($msg->from, $msg->to,$msg->subject,$msg->msg, $msg->mtype, $msg->attachment_coins);
		if ($okay != false)
		{
			if($msg->msg_type)
			{
				$dial_number = funcs::getMobileNo($msg->to);
				
				$username=DBConnect::retrieve_value("SELECT username FROM member WHERE id='".$msg->from."'");
				$blackhawk = DBConnect::retrieve_value("SELECT blackhawk FROM member WHERE id = '" . $msg->from . "'");
				
				if ($blackhawk == 1) {
					sendBlackHawk($dial_number, $username.": ".utf8_encode($msg->msg));	
				} else {
					sendSMSCode($dial_number, $username.": ".utf8_encode($msg->msg));
				}
				
				$sql = "INSERT INTO sms_trace
								SET to_id=".$msg->to.",
								from_id=".$msg->from.",
								message='".$msg->msg."',
								datetime='".funcs::getDateTime()."'";
				DBconnect::execute_q($sql);
				//$id = DBConnect::retrieve_value("SELECT id FROM sms_trace WHERE to_id=".$msg->to." AND from_id=".$msg->from." ORDER BY id DESC LIMIT 0, 1");
				// $sql = "DELETE FROM sms_trace WHERE (from_id=".$msg->from." AND id !=".$id.") OR (to_id=".$msg->to." AND id !=".$id.")";
				//DBconnect::execute_q($sql);
			}
			$ret = 1;
		}
	}
	file_put_contents("soap-debug-sendMessage.txt",print_r($msg,true));
	return $ret;
}

//to(profileID) from(profileID) msg subject attach
function sendAdminMessage($msg)
{
  $ret= 1;
  $fp = fopen('testsoap_admin.log', 'w');
  fprintf($fp, "admin message = %d,%s,%s\n", $msg['to'],$msg['subject'], $msg['msg']);
  if(isset($msg['to'])){
    $okay = funcs::admin_sendMessage($msg['to'],$msg['subject'], $msg['msg']);
   if ($okay != false)
      $ret = 1;
  }
  return $ret;
}

//to(profileID) from(profileID) msg subject attach
function payment($user)
{
  $ret= "User not found!";
  
  if(isset($user['userid'])){
   $okay = funcs::insertpayment($user['userid'],$user['membership'],$user['rate']);
   if ($okay != false)
      $ret = 1;
  }
  return $ret;
}

//to(profileID) from(profileID) msg subject attach
function getAboStatus($userid)
{  
  
  if(preg_match("/^\d+$/",$userid)){
   $abostatus = funcs::getPaymentData($userid);
   if ( isset($abostatus['userid']) && $abostatus['userid']>0 )   return $abostatus;
  }
  return false;
}

function getPaymentHistory($user){
   
  $ret= "User not found!";
  
  if(isset($user['userid'])){
   $okay = funcs::getPaymentHistory($user['userid']);
   if ($okay != false)
      $ret = 1;
  }
  return $ret;
}

function getArrayElement($a,$b){
   return $a[$b];
}

function simpleSearch($search1){
	file_put_contents("soap-debug-simpleSearch.txt",print_r($search1,true));
	if(isset($search1->country) && !is_numeric($search1->country))
	{
		if($id=DBConnect::retrieve_value("SELECT id FROM xml_countries WHERE name like '".$search1->country."%'"))
		{
			$search1->country = $id;
		}
	}

   $result = search::simpleSearch($search1);
   if($result){
      return $result;
   }
   else{
      return false;
   }
}

function getPaymentStatistic($from_date, $to_date)
{
   $result = search::getPaymentStatistic($from_date, $to_date);
   if($result){
      return $result;
   }
   else{
      return false;
   }
}

function getELVStatistic($from_date, $to_date)
{
   $result = search::getELVStatistic($from_date, $to_date);
   if($result){
      return $result;
   }
   else{
      return false;
   }
}

function getSMSStatistic($from_date, $to_date)
{
   $result = search::getSMSStatistic($from_date, $to_date);
   if($result){
      return $result;
   }
   else{
      return false;
   }
}

function getWebcamStatistic($from_date, $to_date)
{
   $result = search::getWebcamStatistic($from_date, $to_date);
   if($result){
      return $result;
   }
   else{
      return false;
   }
}

function getPaymentByType($type, $start, $offset)
{
   $result = search::getPaymentByType($type, $start, $offset);
   if($result){
      return $result;
   }
   else{
      return false;
   }
}

function addLonelyHeartSoap($userid, $target, $category, $headline, $message)
{
   $result = funcs::addLonelyHeartSoap($userid, $target, $category, $headline, $message);
   if($result){
      return $result;
   }
   else{
      return false;
   }
}

function setRundMail($id)
{
    if(preg_match("/^\d+$/",$id)){
        mysql_query("update member set rundmail=now() where id=$id");
        return 1;
    }

    return 0;
}

function setVariable($name,$val)
{
    return funcs::updateConfigs($name,$val);
}

function getVars()
{
    return DBconnect::sql_to_assoc("select name,value from config");# varshash
}

function setUserVariable($id,$name,$val)
{
    mysql_query("update member set $name='$val' where id=$id");
    return $val;
}

function debug($obj)
{
   file_put_contents("soap-debug-object.txt", print_r($obj, true).print_r($_REQUEST, true));
}

function addFakeProfile($obj)
{
	$id=0;

	//If duplicate name, delete it.
	if($id = DBConnect::retrieve_value("SELECT id FROM member WHERE username='".$obj['username']."'"))
	{
		$pic_old = DBConnect::retrieve_value("SELECT picturepath FROM member WHERE id=".$id);
		$thumbs = "thumbs/".$id."/".$pic_old;

		if(is_file($thumbs))
			unlink($thumbs);
	
		if($obj['replace']==1)
		{
			DBConnect::execute_q("DELETE FROM member WHERE id=".$id);

			//Add profile into the database
			$colnames = array_flip(DBconnect::get_col_names('member'));
			$member_post = array_intersect_key($obj, $colnames);
			DBconnect::assoc_insert_1D($member_post, 'member');
			$id=mysql_insert_id();
		}
	}
	else
	{
		//Add profile into the database
		$colnames = array_flip(DBconnect::get_col_names('member'));
		$member_post = array_intersect_key($obj, $colnames);
		DBconnect::assoc_insert_1D($member_post, 'member');
		$id=mysql_insert_id();
	}

	if($obj['pic']!='')
	{
		//Fetch image into server
		$thumbs = "thumbs/".$id;
		if(!is_dir($thumbs))
			mkdir($thumbs, 0777);
		copy($obj['pic'], $thumbs."/".str_replace("%20"," ",basename($obj['pic'])));

		//resizeImageWhiteBkg($thumbs."/".basename($obj['pic']), 90, 'width', 90, 120);

		DBConnect::execute_q("UPDATE member SET picturepath='".$id."/".basename($obj['pic'])."' WHERE id=".$id);
	}
	return true;
}


function receiveSMS2($sms)
{
	//file_put_contents("soap-debug-receiveSMS.txt",print_r($sms,true));
	$sms->number = str_replace("+","",$sms->number);
	$sender_id = DBConnect::retrieve_value("SELECT id FROM member WHERE mobileno='".$sms->number."'");
	if($sender_id)
	{
		$receiver_name = split(":",$sms->msg);
		$receiver_name[0] = trim($receiver_name[0]);
		$subject = funcs::getText($_SESSION['lang'], '$sms_subject');

		if(DBConnect::retrieve_value("SELECT id FROM member WHERE username='".$receiver_name[0]."'"))
		{
			funcs::sendMessageViaSMS($sender_id, $receiver_name[0], $subject, $receiver_name[1]);
		}
		elseif($username=DBConnect::retrieve_value("SELECT t1.username FROM member t1 LEFT JOIN message_inbox t2 ON t1.id=t2.from_id WHERE t2.to_id='".$sender_id."' ORDER BY t2.id DESC LIMIT 1"))
		{
			funcs::sendMessageViaSMS($sender_id, $username, $subject, $sms->msg);
		}
		elseif($username=DBConnect::retrieve_value("SELECT t1.username FROM member t1 LEFT JOIN message_inbox t2 ON t1.id=t2.from_id WHERE t2.from_id='".$sender_id."' ORDER BY t2.id DESC LIMIT 1"))
		{
			funcs::sendMessageViaSMS($sender_id, $username, $subject, $sms->msg);
		}
		return 1;
	}
	else
	{
		return "No mobile number!";
	}
}

function receiveMultiSMS($list)
{
	foreach($list as &$message)
	{
		$obj = new stdClass;
		$obj->number = $message['mobileNr'];
		$obj->msg = $message['sms_text'];
		$message['result'] = receiveSMS($obj);
	}
	return $list;
}

function receiveSMS($sms)
{
	//file_put_contents("soap-debug-receiveSMS.txt",print_r($sms,true));
	$sms->number = str_replace("+","",$sms->number);
	if(!empty($sms->number))
	{
		$sender_id = DBConnect::retrieve_value("SELECT id FROM member WHERE mobileno='".$sms->number."' AND mobileno!=''");
		if($sender_id)
		{
			if(!empty($sms->msg))
			{
				$arr_sms = split(":",$sms->msg);
				if(strpos($sms->msg, ":")!==false)
					$receiver_username = trim($arr_sms[0]);
				else
					$receiver_username = "____________";
				$subject = funcs::getText($_SESSION['lang'], '$sms_subject');

				if(DBConnect::retrieve_value("SELECT id FROM member WHERE username='".$receiver_username."'"))
				{
					if(funcs::sendMessageViaSMS($sender_id, $receiver_username, $subject, $sms->msg))
						return "1";
					else
						return "Can not send SMS, maybe no coin enough [1]";
				}
				elseif($receiver_username = DBConnect::retrieve_value("SELECT m.username FROM sms_trace s LEFT JOIN member m On s.from_id=m.id WHERE s.to_id='".$sender_id."' ORDER BY s.id DESC LIMIT 0, 1"))
				{
					if(funcs::sendMessageViaSMS($sender_id, $receiver_username, $subject, $sms->msg))
						return "1";
					else
						return "Can not send SMS, maybe no coin enough [2]";
				}
				elseif($receiver_username = DBConnect::retrieve_value("SELECT m.username FROM sms_trace s LEFT JOIN member m On s.to_id=m.id WHERE s.from_id='".$sender_id."' ORDER BY s.id DESC LIMIT 0, 1"))
				{
					if(funcs::sendMessageViaSMS($sender_id, $receiver_username, $subject, $sms->msg))
						return "1";
					else
						return "Can not send SMS, maybe no coin enough [3]";
				}
				elseif($receiver_username = DBConnect::retrieve_value("SELECT m.username FROM message_inbox i LEFT JOIN member m ON i.from_id=m.id WHERE i.to_id='".$sender_id."' AND i.msg_type=1 ORDER BY i.id DESC LIMIT 0, 1"))
				{
					if(funcs::sendMessageViaSMS($sender_id, $receiver_username, $subject, $sms->msg))
						return "1";
					else
						return "Can not send SMS, maybe no coin enough [2]";
				}
				elseif($receiver_username = DBConnect::retrieve_value("SELECT m.username FROM message_outbox o LEFT JOIN member m ON o.to_id=m.id WHERE o.from_id='".$sender_id."' ORDER BY o.id DESC LIMIT 0, 1"))
				{
					if(funcs::sendMessageViaSMS($sender_id, $receiver_username, $subject, $sms->msg))
						return "1";
					else
						return "Can not send SMS, maybe no coin enough [2]";
				}
				else
				{
					return "No receiver username!";
				}

				/*
				//if this sender send the second sms then will send reminder sms to them also
				if(DBConnect::retrieve_value("SELECT COUNT(*) AS total FROM sms_trace WHERE from_id ='".$sender_id."'")==2) 
				{
					$dial_number = funcs::getMobileNo($sender_id);
					$remind_message = funcs::getText($_SESSION['lang'], '$twice_sms_reminder');
					sendSMSCode($dial_number, 'System Admin'.": ".$remind_message);
				}*/
			}
			else
			{
				//Empty message return 1, so delete it from queue.
				return "1";
			}
		}
		else
		{
			return "No mobile number registered to this site! ".$sms->number;
		}
	}
	else
	{
		//Empty mobile number return 1, so delete it from queue.
		return "1";
	}
}

function getPackageFromTransactionID($transaction_id,$for_redirect)
{
	//if($_SERVER['REMOTE_ADDR']==PAYMENT_SERVER_IP)
	{
		if((DBConnect::retrieve_value("SELECT id FROM purchases_log WHERE id=".$transaction_id." AND purchase_finished=0")) || ($for_redirect==1))
		{
			if($result = DBConnect::assoc_query_1D("SELECT pl.*,m.username FROM purchases_log pl LEFT JOIN member m ON pl.user_id=m.id WHERE pl.id=".$transaction_id))
			{
				$result['user_id'] = $result['user_id'];
				$result['server_name'] = self_name;
				$result['server_url'] = URL_WEB;
				if($eudebit = DBConnect::retrieve_value("SELECT count(payment_type) FROM purchases_log WHERE payment_type='EUDebit' AND user_id='".$result['user_id']."'"))
				{
					$result["EUDebit"] = $eudebit;
				}
				return $result;
			}
			else
				return false;
		}
		else
			return false;
	}
}

function finishPurchase($transaction_id, $saleID, $payment_method, $payment_type, $postdata=array())
{
	//if($_SERVER['REMOTE_ADDR']==PAYMENT_SERVER_IP)
	{
		if(DBConnect::retrieve_value("SELECT id FROM purchases_log WHERE id=".$transaction_id." AND purchase_finished=0"))
		{
			//update transaction status
			DBConnect::execute_q("UPDATE purchases_log SET purchase_finished=1, reference_id='".$saleID."', payment_method='".$payment_method."', payment_type='".$payment_type."', purchase_finished_date=NOW(), postdata_serialized='".serialize($postdata)."', postdata_text='".print_r($postdata,true)."' WHERE id=".$transaction_id);

			//get transaction detail
			$detail = DBConnect::assoc_query_1D("SELECT * from purchases_log WHERE id=".$transaction_id);

			//update coin to member
			$sqlUpdateCoin = "UPDATE member SET coin = coin + ".$detail['coin_amount']." WHERE id = '".$detail['user_id']."'";
			DBconnect::execute($sqlUpdateCoin);

			//get username from user id
			$username = DBConnect::retrieve_value("SELECT username FROM member WHERE id='".$detail['user_id']."'");

			//get current coin value
			$coinVal = funcs::checkCoin($username);

			//insert coin log
			$sqlAddCoinLog = "INSERT INTO coin_log (member_id, send_to, coin_field, coin, coin_remain, log_date) VALUES ('0','".$detail['user_id']."','payment',".$detail['coin_amount'].",".$coinVal.", NOW())";
			DBconnect::execute($sqlAddCoinLog);

			//reset warning_sms
			$sqlResetWarningSMS = "DELETE FROM warning_sms WHERE userid=".$detail['user_id'];
			DBconnect::execute($sqlResetWarningSMS);
		}
		else
		{
			//Already completed the transaction.
		}
	}
}

function getDailyToupUsername($args)
{
	if(isset($args['fromdate']) && ($args['fromdate']!=''))
		$fromdate = $args['fromdate'];
	else
		$fromdate = date("Y-m-d");
	
	if(isset($args['todate']) && ($args['todate']!=''))
		$todate = $args['todate'];
	else
		$todate = date("Y-m-d");
	
	
	$statistics = array();
	$statistics['usernames'] = json_encode(DBConnect::assoc_query_2D("SELECT m.username AS username, pl.purchase_finished_date AS created, pl.id AS payment_id FROM member AS m LEFT OUTER JOIN purchases_log AS pl ON pl.user_id = m.id WHERE DATE(pl.purchase_finished_date)>='".$fromdate."' AND DATE(pl.purchase_finished_date)<='".$todate."' AND m.fake=0 AND pl.purchase_finished = 1"));
	$statistics['google'] = DBConnect::retrieve_value("SELECT GROUP_CONCAT(m.username) AS topups FROM member AS m LEFT OUTER JOIN purchases_log AS pl ON pl.user_id = m.id WHERE DATE(pl.purchase_datetime)>='".$fromdate."' AND DATE(pl.purchase_datetime)<='".$todate."' AND m.fake=0 AND pl.purchase_finished = 1 AND m.ref IS NOT NULL");
	
	return $statistics;	
}

function getCountStatistics($args)
{
	if(isset($args['fromdate']) && ($args['fromdate']!=''))
		$fromdate = $args['fromdate'];
	else
		$fromdate = date("Y-m-d");

	if(isset($args['todate']) && ($args['todate']!=''))
		$todate = $args['todate'];
	else
		$todate = date("Y-m-d");

	$statistics = array();
	$statistics['currentTime'] = date("Y-m-d H:i:s")." GMT ".date("O");
	$statistics['currency'] = DBConnect::retrieve_value("SELECT value FROM config WHERE name='CURRENCY'");
	$statistics['newMemberTotal'] = DBConnect::retrieve_value("SELECT count(*) FROM member WHERE DATE(signup_datetime)>='".$fromdate."' AND DATE(signup_datetime)<='".$todate."' AND fake=0");
	$statistics['newMemberActive'] = DBConnect::retrieve_value("SELECT count(*) FROM member WHERE DATE(signup_datetime)>='".$fromdate."' AND DATE(signup_datetime)<='".$todate."' AND fake=0 AND isactive=1");
	$statistics['newMemberBlocked'] = DBConnect::retrieve_value("SELECT count(*) FROM member WHERE DATE(signup_datetime)>='".$fromdate."' AND DATE(signup_datetime)<='".$todate."' AND fake=0 AND isactive=0 AND isactive_datetime IS NOT NULL AND isactive_datetime!='0000-00-00 00:00:00'");
	$statistics['newMobileTotal'] = DBConnect::retrieve_value("SELECT count(*) FROM member WHERE DATE(signup_datetime)>='".$fromdate."' AND DATE(signup_datetime)<='".$todate."' AND fake=0 AND mobile = 1");
	$statistics['newMobileActive'] = DBConnect::retrieve_value("SELECT count(*) FROM member WHERE DATE(signup_datetime)>='".$fromdate."' AND DATE(signup_datetime)<='".$todate."' AND fake=0 AND mobile = 1 AND isactive=1");
	$statistics['newMemberVerifiedMobile'] = DBConnect::retrieve_value("SELECT count(*) FROM member WHERE DATE(signup_datetime)>='".$fromdate."' AND DATE(signup_datetime)<='".$todate."' AND fake=0 AND isactive=1 and mobileno!=''");

	$statistics['newPaymentTotal'] = DBConnect::retrieve_value("SELECT count(*) FROM purchases_log WHERE purchase_finished=1 AND DATE(purchase_finished_date)>='".$fromdate."' AND DATE(purchase_finished_date)<='".$todate."'");
	$statistics['newPaymentTotalAmount'] = DBConnect::retrieve_value("SELECT SUM(price) FROM purchases_log WHERE purchase_finished=1 AND DATE(purchase_finished_date)>='".$fromdate."' AND DATE(purchase_finished_date)<='".$todate."'");
	$statistics['newPaymentTotal']=$statistics['newPaymentTotal']>0?$statistics['newPaymentTotal']." (".$statistics['newPaymentTotalAmount']." ".$statistics['currency'].")":0;
	$statistics['refSignups'] = DBConnect::retrieve_value("SELECT count(*) FROM member WHERE DATE(signup_datetime)>='".$fromdate."' AND DATE(signup_datetime)<='".$todate."' AND ref IS NOT NULL AND fake=0 AND isactive=1");
	$statistics['refSignupsPayment'] = DBConnect::retrieve_value("SELECT SUM(pl.price) FROM member AS m LEFT OUTER JOIN purchases_log AS pl ON pl.user_id = m.id WHERE DATE(pl.purchase_datetime)>='".$fromdate."' AND DATE(pl.purchase_datetime)<='".$todate."' AND m.ref IS NOT NULL AND m.fake=0 AND pl.purchase_finished = 1");
	$statistics['refSignupsPaymentCount'] = DBConnect::retrieve_value("SELECT COUNT(*) as cnt FROM member AS m LEFT OUTER JOIN purchases_log AS pl ON pl.user_id = m.id WHERE DATE(pl.purchase_datetime)>='".$fromdate."' AND DATE(pl.purchase_datetime)<='".$todate."' AND m.ref IS NOT NULL AND m.fake=0 AND pl.purchase_finished = 1 ");
	$statistics['refSignupsPaymentAttempt'] = DBConnect::retrieve_value("SELECT SUM(pl.price) FROM member AS m LEFT OUTER JOIN purchases_log AS pl ON pl.user_id = m.id WHERE DATE(pl.purchase_datetime)>='".$fromdate."' AND DATE(pl.purchase_datetime)<='".$todate."' AND m.ref IS NOT NULL AND m.fake=0 AND pl.purchase_finished = 0");
	$statistics['refSignupsPaymentAttemptCount'] = DBConnect::retrieve_value("SELECT COUNT(*) AS cnt FROM member AS m LEFT OUTER JOIN purchases_log AS pl ON pl.user_id = m.id WHERE DATE(pl.purchase_datetime)>='".$fromdate."' AND DATE(pl.purchase_datetime)<='".$todate."' AND m.ref IS NOT NULL AND m.fake=0 AND pl.purchase_finished = 0 ");
	$statistics['signupRef'] = json_encode(DBConnect::assoc_query_2D("SELECT COUNT(*) AS cnt, ref, mobile FROM member WHERE DATE(signup_datetime)>='".$fromdate."' AND DATE(signup_datetime)<='".$todate."' AND ref IS NOT NULL  AND isactive =1 GROUP BY ref, mobile"));
	
	$payments_arr = array("Verotel", "Paysafecard", "Ukash", "CCBill", "Paypal", "Paymentwall");

	foreach($payments_arr as $payment)
	{
		$statistics['newPayment'.$payment] = DBConnect::retrieve_value("SELECT count(*) FROM purchases_log WHERE purchase_finished=1 AND DATE(purchase_finished_date)>='".$fromdate."' and payment_method='".$payment."' AND DATE(purchase_finished_date)<='".$todate."'");
		$statistics['newPayment'.$payment.'Amount'] = DBConnect::retrieve_value("SELECT SUM(price) FROM purchases_log WHERE purchase_finished=1 AND DATE(purchase_finished_date)>='".$fromdate."' and payment_method='".$payment."' AND DATE(purchase_finished_date)<='".$todate."'");
		$statistics['newPayment'.$payment]=$statistics['newPayment'.$payment]>0?$statistics['newPayment'.$payment]." (".$statistics['newPayment'.$payment.'Amount']." ".$statistics['currency'].")":0;
	}

	$payment = "Worldpay";
	$statistics['newPayment'.$payment] = DBConnect::retrieve_value("SELECT count(*) FROM purchases_log WHERE purchase_finished=1 AND DATE(purchase_finished_date)>='".$fromdate."' and payment_method='".$payment."' AND payment_type != 'Paypal' AND DATE(purchase_finished_date)<='".$todate."'");
	$statistics['newPayment'.$payment.'Amount'] = DBConnect::retrieve_value("SELECT SUM(price) FROM purchases_log WHERE purchase_finished=1 AND DATE(purchase_finished_date)>='".$fromdate."' and payment_method='".$payment."' AND payment_type != 'Paypal' AND DATE(purchase_finished_date)<='".$todate."'");
	$statistics['newPayment'.$payment]=$statistics['newPayment'.$payment]>0?$statistics['newPayment'.$payment]." (".$statistics['newPayment'.$payment.'Amount']." ".$statistics['currency'].")":0;

	$statistics['newPayment'.$payment."Paypal"] = DBConnect::retrieve_value("SELECT count(*) FROM purchases_log WHERE purchase_finished=1 AND DATE(purchase_finished_date)>='".$fromdate."' and payment_method='".$payment."' AND payment_type = 'Paypal' AND DATE(purchase_finished_date)<='".$todate."'");
	$statistics['newPayment'.$payment.'PaypalAmount'] = DBConnect::retrieve_value("SELECT SUM(price) FROM purchases_log WHERE purchase_finished=1 AND DATE(purchase_finished_date)>='".$fromdate."' and payment_method='".$payment."' AND payment_type = 'Paypal' AND DATE(purchase_finished_date)<='".$todate."'");
	$statistics['newPayment'.$payment."Paypal"]=$statistics['newPayment'.$payment."Paypal"]>0?$statistics['newPayment'.$payment."Paypal"]." (".$statistics['newPayment'.$payment.'PaypalAmount']." ".$statistics['currency'].")":0;

	return $statistics;
}

function updateWeight($arr)
{
	$return = "";
	foreach($arr as $val)
	{
		DBConnect::execute_q("UPDATE member SET weight = ".$val['weight']." WHERE id=".$val['id']);
		$return .= "UPDATE ID ".$val['id']."<br/>";
	}
	return $return;
}

function getAllPayments($args = array())
{
	if(isset($args['fromdate']) && ($args['fromdate']!=''))
		$fromdate = $args['fromdate'];
	else
		$fromdate = date("Y-m-d");

	if(isset($args['todate']) && ($args['todate']!=''))
		$todate = $args['todate'];
	else
		$todate = date("Y-m-d");

	$payments = DBConnect::assoc_query_2D("SELECT m.username, m.signup_datetime, l.price, l.currency, l.purchase_finished_date, l.payment_method, l.payment_type, m.agent FROM purchases_log l LEFT JOIN member m ON l.user_id = m.id WHERE l.purchase_finished=1 AND l.purchase_finished_date !='' AND DATE(l.purchase_finished_date)>='".$fromdate."' AND DATE(l.purchase_finished_date)<='".$todate."' ORDER BY l.purchase_finished_date");

	$last_payment = array();
	foreach($payments as $key => $val)
	{
		if(!(in_array($val['username'],$last_payment)))
			$last_payment[$val['username']] = DBConnect::retrieve_value("SELECT MAX(purchase_finished_date) FROM purchases_log l LEFT JOIN member m ON l.user_id = m.id WHERE l.purchase_finished=1 AND l.purchase_finished_date !='' AND m.username='".$val['username']."' LIMIT 1");

		$payments[$key]['last_topup'] = $last_payment[$val['username']];
	}

	return $payments;
}

function getAllFirstPayments($args)
{
	if(isset($args['fromdate']) && ($args['fromdate']!=''))
		$fromdate = $args['fromdate'];
	else
		$fromdate = date("Y-m-d");

	if(isset($args['todate']) && ($args['todate']!=''))
		$todate = $args['todate'];
	else
		$todate = date("Y-m-d");

	$list = DBConnect::row_retrieve_2D_Conv_1D("SELECT MIN(id) FROM purchases_log WHERE purchase_finished=1 AND purchase_finished_date !='' AND purchase_finished_date !='0000-00-00 00:00:00' GROUP BY user_id ORDER BY purchase_finished_date");

	return DBConnect::assoc_query_2D("SELECT m.username, l.price, l.currency, l.purchase_finished_date, l.payment_method, l.payment_type, m.agent, IF((isactive_datetime!='0000-00-00 00:00:00' AND isactive_datetime IS NOT NULL), DATEDIFF(l.purchase_finished_date, isactive_datetime), DATEDIFF(l.purchase_finished_date, signup_datetime)) as daysAfterRegistered FROM purchases_log l LEFT JOIN member m ON l.user_id = m.id WHERE l.id IN (".join(",", $list).") AND DATE(l.purchase_finished_date)>='".$fromdate."' AND DATE(l.purchase_finished_date)<='".$todate."'");
}

function getCountPayments($args, $expect_times, $expect_topup)
{
	if(isset($args['fromdate']) && ($args['fromdate']!=''))
		$fromdate = $args['fromdate'];
	else
		$fromdate = date("Y-m-d");

	if(isset($args['todate']) && ($args['todate']!=''))
		$todate = $args['todate'];
	else
		$todate = date("Y-m-d");

	$return = array();
	$newRegistrations = getNewRegistrationNames($args);
	$return['newRegistrationNames'] = $newRegistrations;
	$return['totalNewRegistered'] = count($newRegistrations);

	//"m.username, SUM(l.price) AS price, l.currency, COUNT(l.id) AS amount";

	$sql1 = "SELECT m.username FROM purchases_log l LEFT JOIN member m ON l.user_id = m.id WHERE l.purchase_finished=1 AND l.purchase_finished_date !='' AND purchase_finished_date !='0000-00-00 00:00:00' AND m.username IN('".join("','",$newRegistrations)."') GROUP BY m.username ";
	$sql2 = "SELECT m.username FROM purchases_log l LEFT JOIN member m ON l.user_id = m.id WHERE l.purchase_finished=1 AND l.purchase_finished_date !='' AND purchase_finished_date !='0000-00-00 00:00:00' AND DATE(l.purchase_finished_date)>='".$fromdate."' AND DATE(l.purchase_finished_date)<='".$todate."' AND m.username IN('".join("','",$newRegistrations)."') GROUP BY m.username ";
	//$sql3 = "SELECT m.username FROM purchases_log l LEFT JOIN member m ON l.user_id = m.id WHERE l.purchase_finished=1 AND l.purchase_finished_date !='' AND purchase_finished_date !='0000-00-00 00:00:00' AND DATE(l.purchase_finished_date)>='".$fromdate."' AND DATE(l.purchase_finished_date)<='".$todate."' ";

	$return['times_allpayment']['Registered'] = ($return['totalNewRegistered']>0)? "100,".$return['totalNewRegistered']:"0,0";
	$return['times_period']['Registered'] = ($return['totalNewRegistered']>0)? "100,".$return['totalNewRegistered']:"0,0";
	//$return['times_allpeople']['Registered'] = ($return['totalNewRegistered']>0)? "100,".$return['totalNewRegistered']:"0,0";
	foreach($expect_times as $val)
	{
		$ext = "HAVING COUNT(l.id)>=".$val['operator'];
		$ext2 = " AND l.id IN (SELECT MIN(id) FROM purchases_log WHERE purchase_finished=1 AND purchase_finished_date !='' AND purchase_finished_date !='0000-00-00 00:00:00' GROUP BY user_id HAVING COUNT(id) = ".$val['operator']." ORDER BY purchase_finished_date) GROUP BY m.username";

		if($newRegistrations>0)
		{
			$db_result = DBConnect::assoc_query_2D($sql1.$ext);
			$return['times_allpayment'][$val['label']] = ($return['totalNewRegistered']>0)? round((count($db_result)*100)/$return['totalNewRegistered'],2).",".count($db_result):"0,0";
			$db_result2 = DBConnect::assoc_query_2D($sql2.$ext);
			$return['times_period'][$val['label']] = ($return['totalNewRegistered']>0)? round((count($db_result2)*100)/$return['totalNewRegistered'],2).",".count($db_result2):"0,0";
		}
		//$db_result3 = DBConnect::assoc_query_2D($sql3.$ext2);
		//$return['times_allpeople'][$val['label']] = ($return['totalNewRegistered']>0)? round((count($db_result3)*100)/$return['totalNewRegistered'],2).",".count($db_result3):"0,0";
		//$return['sql'][$val['label']] = $sql3.$ext2;
	}


	$sql1 = "SELECT m.username FROM purchases_log l LEFT JOIN member m ON l.user_id = m.id WHERE l.purchase_finished=1 AND l.purchase_finished_date !='' AND purchase_finished_date !='0000-00-00 00:00:00' AND m.username IN('".join("','",$newRegistrations)."') GROUP BY m.username ";
	$sql2 = "SELECT m.username FROM purchases_log l LEFT JOIN member m ON l.user_id = m.id WHERE l.purchase_finished=1 AND l.purchase_finished_date !='' AND purchase_finished_date !='0000-00-00 00:00:00' AND DATE(l.purchase_finished_date)>='".$fromdate."' AND DATE(l.purchase_finished_date)<='".$todate."' AND m.username IN('".join("','",$newRegistrations)."') GROUP BY m.username ";
	//$sql3 = "SELECT m.username FROM purchases_log l LEFT JOIN member m ON l.user_id = m.id WHERE l.purchase_finished=1 AND l.purchase_finished_date !='' AND purchase_finished_date !='0000-00-00 00:00:00' AND DATE(l.purchase_finished_date)>='".$fromdate."' AND DATE(l.purchase_finished_date)<='".$todate."' ";

	$return['topup_allpayment']['Registered'] = $return['times_allpayment']['Registered']; //($return['totalNewRegistered']>0)? "100,".$return['totalNewRegistered']:"0,0";
	$return['topup_period']['Registered'] = $return['times_period']['Registered']; //($return['totalNewRegistered']>0)? "100,".$return['totalNewRegistered']:"0,0";
	//$return['topup_allpeople']['Registered'] = $return['times_allpeople']['Registered']; //($return['totalNewRegistered']>0)? "100,".$return['totalNewRegistered']:"0,0";
	foreach($expect_topup as $val)
	{
		if(isset($val['operator2'])&&($val['operator2']!=""))
			$ext = "HAVING SUM(l.price)>=".$val['operator1']." AND SUM(l.price)<=".$val['operator2'];
		else
			$ext = "HAVING SUM(l.price)>=".$val['operator1'];

		//$ext2 = " AND l.id IN (SELECT MIN(id) FROM purchases_log WHERE purchase_finished=1 AND purchase_finished_date !='' AND purchase_finished_date !='0000-00-00 00:00:00' GROUP BY user_id HAVING COUNT(id) = ".$val['operator']." ORDER BY purchase_finished_date) GROUP BY m.username";

		if($newRegistrations>0)
		{
			$db_result = DBConnect::assoc_query_2D($sql1.$ext);
			$return['topup_allpayment'][$val['label']] = ($return['totalNewRegistered']>0)? round((count($db_result)*100)/$return['totalNewRegistered'],2).",".count($db_result):"0,0";
			$db_result2 = DBConnect::assoc_query_2D($sql2.$ext);
			$return['topup_period'][$val['label']] = ($return['totalNewRegistered']>0)? round((count($db_result2)*100)/$return['totalNewRegistered'],2).",".count($db_result2):"0,0";
		}
		//$db_result3 = DBConnect::assoc_query_2D($sql3.$ext);
		//$return['topup_allpeople'][$val['label']] = ($return['totalNewRegistered']>0)? round((count($db_result3)*100)/$return['totalNewRegistered'],2).",".count($db_result3):"0,0";
		//$return['sql'][$val['label']] = $sql3.$ext2;
	}

	/*$list =  DBConnect::row_retrieve_2D_Conv_1D("SELECT l.user_id FROM `purchases_log` l WHERE l.purchase_finished=1 AND l.purchase_finished_date !='' AND l.purchase_finished_date !='0000-00-00 00:00:00' AND DATE(l.purchase_finished_date)>='2013-01-01' AND DATE(l.purchase_finished_date)<='2013-08-01' GROUP BY l.user_id ORDER BY `l`.`user_id` ASC");

	$return['times_allpeople']['First'] = DBConnect::assoc_query_2D("SELECT user_id, COUNT(id) FROM `purchases_log` WHERE purchase_finished=1 AND purchase_finished_date !='' AND purchase_finished_date !='0000-00-00 00:00:00' user_id IN (".$list.") GROUP BY user_id HAVING COUNT(id)>=1 ORDER BY `user_id` ASC");
	//getAllFirstPayments();*/

	return $return;
}

function getPaymentAverage($args)
{
	if(isset($args['fromdate']) && ($args['fromdate']!=''))
		$fromdate = $args['fromdate'];
	else
		$fromdate = date("Y-m-d");

	if(isset($args['todate']) && ($args['todate']!=''))
		$todate = $args['todate'];
	else
		$todate = date("Y-m-d");
	
	$now = date("Y-m-d");

	$registered = DBConnect::retrieve_value("SELECT count(id) FROM `member` WHERE DATE(signup_datetime)>='".$fromdate."' AND DATE(signup_datetime)<='".$todate."' AND isactive=1");
	$registered = ($registered>0)?$registered:0;


	$period_customer = DBConnect::retrieve_value("SELECT count(id) FROM `member` WHERE DATE(signup_datetime)>='".$fromdate."' AND DATE(signup_datetime)<='".$todate."' AND id in (SELECT user_id FROM purchases_log WHERE purchase_finished=1 AND DATE(purchase_finished_date)>='".$fromdate."' AND DATE(purchase_finished_date)<='".$todate."')");//count(getAllFirstPayments($args));
	$period_customer = ($period_customer>0)?$period_customer:0;

	$period_percentage = ($period_customer>0)? round((($period_customer*100)/$registered),2):0;

	$period_amount = DBConnect::retrieve_value("SELECT SUM(p.price) FROM purchases_log p LEFT JOIN member m ON p.user_id=m.id WHERE p.purchase_finished=1 AND DATE(p.purchase_finished_date)>='".$fromdate."' AND DATE(p.purchase_finished_date)<='".$todate."' AND DATE(m.signup_datetime)>='".$fromdate."' AND DATE(m.signup_datetime)<='".$todate."'");
	$period_amount = ($period_amount>0)?$period_amount:0;

	$period_everage = ($period_customer>0)? round(($period_amount/$period_customer),2):0;

	$now_amount = DBConnect::retrieve_value("SELECT SUM(p.price) FROM purchases_log p LEFT JOIN member m ON p.user_id=m.id WHERE p.purchase_finished=1 AND DATE(p.purchase_finished_date)>='".$fromdate."' AND DATE(p.purchase_finished_date)<='".$now."' AND DATE(m.signup_datetime)>='".$fromdate."' AND DATE(m.signup_datetime)<='".$todate."'");
	$now_amount = ($now_amount>0)?$now_amount:0;

	$now_everage = ($period_customer>0)? round(($now_amount/$period_customer),2):0;

	//////////////////

	$period_customer2 = count(getAllFirstPayments($args));
	$period_customer2 = ($period_customer2>0)?$period_customer2:0;

	$period_percentage2 = ($period_customer2>0)? round((($period_customer2*100)/$registered),2):0;

	$period_amount2 = DBConnect::retrieve_value("SELECT SUM(p.price) FROM purchases_log p LEFT JOIN member m ON p.user_id=m.id WHERE p.purchase_finished=1 AND DATE(p.purchase_finished_date)>='".$fromdate."' AND DATE(p.purchase_finished_date)<='".$todate."' AND DATE(m.signup_datetime)>='".$fromdate."' AND DATE(m.signup_datetime)<='".$todate."'");
	$period_amount2 = ($period_amount2>0)?$period_amount2:0;

	$period_everage2 = ($period_customer2>0)? round(($period_amount2/$period_customer2),2):0;

	$now_amount2 = DBConnect::retrieve_value("SELECT SUM(p.price) FROM purchases_log p LEFT JOIN member m ON p.user_id=m.id WHERE p.purchase_finished=1 AND DATE(p.purchase_finished_date)>='".$fromdate."' AND DATE(p.purchase_finished_date)<='".$now."' AND DATE(m.signup_datetime)>='".$fromdate."' AND DATE(m.signup_datetime)<='".$todate."'");
	$now_amount2 = ($now_amount2>0)?$now_amount2:0;

	$now_everage2 = ($period_customer2>0)? round(($now_amount2/$period_customer2),2):0;

	////////////////

	$period_customer3 = DBConnect::retrieve_value("SELECT count(id) FROM `member` WHERE DATE(signup_datetime)>='".$fromdate."' AND DATE(signup_datetime)<='".$todate."' AND id in (SELECT user_id FROM purchases_log WHERE purchase_finished=1 AND DATE(purchase_finished_date)>='".$fromdate."' AND DATE(purchase_finished_date)<='".$now."')");//count(getAllFirstPayments($args));
	$period_customer3 = ($period_customer3>0)?$period_customer3:0;

	$period_percentage3 = ($period_customer3>0)? round((($period_customer3*100)/$registered),2):0;

	$period_amount3 = DBConnect::retrieve_value("SELECT SUM(p.price) FROM purchases_log p LEFT JOIN member m ON p.user_id=m.id WHERE p.purchase_finished=1 AND DATE(p.purchase_finished_date)>='".$fromdate."' AND DATE(p.purchase_finished_date)<='".$now."' AND DATE(m.signup_datetime)>='".$fromdate."' AND DATE(m.signup_datetime)<='".$todate."'");
	$period_amount3 = ($period_amount3>0)?$period_amount3:0;

	$period_everage3 = ($period_customer3>0)? round(($period_amount3/$period_customer3),2):0;


	$return = array(
		'registered' => $registered,
		'period_customer' => $period_customer,
		'period_customer2' => $period_customer2,
		'period_customer3' => $period_customer3,
		'period_amount' => $period_amount,
		'period_amount2' => $period_amount2,
		'period_amount3' => $period_amount3,
		'period_everage' => $period_everage,
		'period_everage2' => $period_everage2,
		'period_everage3' => $period_everage3,
		'period_percentage' => $period_percentage,
		'period_percentage2' => $period_percentage2,
		'period_percentage3' => $period_percentage3,
		'now_amount' => $now_amount,
		'now_amount2' => $now_amount2,
		'now_everage' => $now_everage,
		'now_everage2' => $now_everage2
	);
	return $return;
}

function getCountDailyPayment($args, $expect_times)
{
	if(isset($args['fromdate']) && ($args['fromdate']!=''))
		$fromdate = $args['fromdate'];
	else
		$fromdate = date("Y-m-d");

	if(isset($args['todate']) && ($args['todate']!=''))
		$todate = $args['todate'];
	else
		$todate = date("Y-m-d");

	$arr_registered = removeDimension(DBConnect::assoc_query_2D("SELECT DATE(signup_datetime) as date, count(signup_datetime) as num FROM `member` WHERE DATE(signup_datetime)>='".$fromdate."' AND DATE(signup_datetime)<='".$todate."' AND isactive=1 GROUP BY DATE(signup_datetime)"), $fromdate, $todate);
	$return['Registered'] = $arr_registered;

	foreach($expect_times as $val)
	{
		$ext = "HAVING COUNT(l.id)>=".$val['operator'];
		$sql = "SELECT DATE(l.purchase_finished_date) AS date, COUNT(m.username) AS num FROM purchases_log l LEFT JOIN member m ON l.user_id = m.id WHERE l.purchase_finished=1 AND l.purchase_finished_date !='' AND purchase_finished_date !='0000-00-00 00:00:00' AND DATE(l.purchase_finished_date)>='".$fromdate."' AND DATE(l.purchase_finished_date)<='".$todate."' GROUP BY DATE(purchase_finished_date) ";

		$return[$val['label']] = removeDimension(DBConnect::assoc_query_2D($sql.$ext), $fromdate, $todate);
	}
	return $return;
}

function removeDimension($arr, $fromdate, $todate)
{
	$return = array();
	foreach($arr as $arr_val)
	{
		$return[$arr_val['date']] = $arr_val['num'];
	}

	$start = strtotime($fromdate);
	$end = strtotime($todate);
	$cur = $start;
	while($cur<=$end)
	{
		$curdate = date('Y-m-d',$cur);
		if(!array_key_exists($curdate, $return))
			$return[$curdate] = 0;
		$cur += 86400;
	}
	ksort($return);
	return($return);
}

function getAllCPDetails($args)
{
	if(isset($args['fromdate']) && ($args['fromdate']!=''))
		$fromdate = $args['fromdate'];
	else
		$fromdate = date("Y-m-d");

	if(isset($args['todate']) && ($args['todate']!=''))
		$todate = $args['todate'];
	else
		$todate = date("Y-m-d");

	//return DBConnect::assoc_query_2D("SELECT username, signup_datetime, agent, agent_profile_username FROM member WHERE fake=0 AND agent !='' AND DATE(signup_datetime)>='".$fromdate."' AND DATE(signup_datetime)<='".$todate."' ORDER BY signup_datetime");
	return DBConnect::assoc_query_2D("SELECT username, signup_datetime, agent, agent_profile_username FROM member WHERE fake=0 AND isactive=1 AND DATE(signup_datetime)>='".$fromdate."' AND DATE(signup_datetime)<='".$todate."' ORDER BY agent, signup_datetime");
}

function blockMember($id)
{
	DBconnect::execute_q("UPDATE member SET isactive = 0 WHERE id=".$id);
}

function getNewRegistrations($args)
{
	if(isset($args['fromdate']) && ($args['fromdate']!=''))
		$fromdate = $args['fromdate'];
	else
		$fromdate = date("Y-m-d");

	if(isset($args['todate']) && ($args['todate']!=''))
		$todate = $args['todate'];
	else
		$todate = date("Y-m-d");

	$total = DBConnect::assoc_query_2D("SELECT DATE(signup_datetime) as date, count(signup_datetime) as num FROM `member` WHERE DATE(signup_datetime)>='".$fromdate."' AND DATE(signup_datetime)<='".$todate."' GROUP BY DATE(signup_datetime)");
	$activated = DBConnect::assoc_query_2D("SELECT DATE(signup_datetime) as date, count(signup_datetime) as num FROM `member` WHERE DATE(signup_datetime)>='".$fromdate."' AND DATE(signup_datetime)<='".$todate."' AND isactive=1 GROUP BY DATE(signup_datetime)");
	$verified = DBConnect::assoc_query_2D("SELECT DATE(signup_datetime) as date, count(signup_datetime) as num FROM `member` WHERE DATE(signup_datetime)>='".$fromdate."' AND DATE(signup_datetime)<='".$todate."' AND isactive=1 AND mobileno!='' GROUP BY DATE(signup_datetime)");
	return array("total"=>$total, "activated"=>$activated, "verified"=>$verified);
}

function getNewRegistrationNames($args)
{
	$return = array();
	if(isset($args['fromdate']) && ($args['fromdate']!=''))
		$fromdate = $args['fromdate'];
	else
		$fromdate = date("Y-m-d");

	if(isset($args['todate']) && ($args['todate']!=''))
		$todate = $args['todate'];
	else
		$todate = date("Y-m-d");

	$members = DBConnect::assoc_query_2D("SELECT username FROM `member` WHERE DATE(signup_datetime)>='".$fromdate."' AND DATE(signup_datetime)<='".$todate."' AND isactive=1");
	foreach($members as $member)
	{
		array_push($return,$member['username']);
	}
	return $return;
}

function getAllRegistrations()
{
	return DBConnect::retrieve_value("SELECT COUNT(id) FROM `member` WHERE isactive=1");
}

function getMemberActivitiesStat($intervalMinutes)
{
	// Get no. of user
	/*SELECT ROUND(UNIX_TIMESTAMP(datetime)/(5 * 60)) AS timekey, count(DISTINCT username) FROM pages_stat GROUP BY timekey*/
	$result = array();
	$result['online'] = DBConnect::assoc_query_2D("SELECT ROUND(UNIX_TIMESTAMP(datetime)/(".$intervalMinutes." * 60)) AS timekey, count(DISTINCT username) as num FROM pages_stat WHERE username!='' AND datetime >= NOW()-INTERVAL 24 HOUR GROUP BY timekey");
	$result['activated'] = DBConnect::assoc_query_2D("SELECT ROUND(UNIX_TIMESTAMP(signup_datetime)/(".$intervalMinutes." * 60)) AS timekey, count(*) as num FROM member WHERE isactive=1 AND signup_datetime >= NOW()-INTERVAL 24 HOUR GROUP BY timekey");
	$result['payment'] = DBConnect::assoc_query_2D("SELECT ROUND(UNIX_TIMESTAMP(purchase_finished_date)/(".$intervalMinutes." * 60)) AS timekey, count(*) as num FROM purchases_log WHERE purchase_finished=1 AND purchase_finished_date >= NOW()-INTERVAL 24 HOUR GROUP BY timekey");

	return $result;
}

function getCountStatisticsFranchisee($franchisee, $fromdate, $todate)
{
	if($fromdate == "")
	{
		$fromdate = date("Y-m-d");
	}
	if($todate == "")
	{
		$todate = date("Y-m-d");
	}
	$statistics = array();
	$statistics['members'] = DBConnect::retrieve_value("SELECT count(*) FROM member WHERE DATE(franchisee_datetime)>='".$fromdate."' AND DATE(franchisee_datetime)<='".$todate."' AND fake=0 AND franchisee='".$franchisee."'");
	$statistics['payment'] = DBConnect::retrieve_value("SELECT SUM(price) FROM purchases_log l LEFT JOIN member m ON l.user_id=m.id WHERE l.purchase_finished=1 AND DATE(l.purchase_finished_date)>=)>='".$fromdate."' AND DATE(l.purchase_finished_date)<='".$todate."' AND m.franchisee='".$franchisee."'");
	$statistics['payment'] = ($today['payment']?$today['payment']:0)." GBP";

	return $statistics;
}

function getSMSHistory($args)
{
	if(isset($args['fromdate']) && ($args['fromdate']!=''))
		$fromdate = $args['fromdate'];
	else
		$fromdate = date("Y-m-d");

	if(isset($args['todate']) && ($args['todate']!=''))
		$todate = $args['todate'];
	else
		$todate = date("Y-m-d");

	return DBConnect::assoc_query_2D("SELECT * FROM sms_log WHERE DATE(sent_datetime)>='".$fromdate."' AND DATE(sent_datetime)<='".$todate."' ORDER BY sent_datetime DESC");
}

function addCoinsPaypal($post)
{
	if(isset($post['id']))
	{
		$id = $post['id'];
		$transaction = DBConnect::assoc_query_1D("SELECT * FROM purchases_log WHERE id='".$id."' AND purchase_finished=0");
		$error = "";
		if($transaction)
		{
			$username = DBConnect::retrieve_value("SELECT username FROM member WHERE id=".$transaction['user_id']);
			DBConnect::execute_q("UPDATE member SET coin=coin+".$transaction['coin_amount']." WHERE username='".$username."'");

			//get current coin value
			$coinVal = funcs::checkCoin($username);

			//insert coin log
			$sqlAddCoinLog = "INSERT INTO coin_log (member_id, send_to, coin_field, coin, coin_remain, log_date) VALUES ('0','".$transaction['user_id']."','payment',".$transaction['coin_amount'].",".$coinVal.", NOW())";
			DBconnect::execute($sqlAddCoinLog);

			//reset warning_sms
			$sqlResetWarningSMS = "DELETE FROM warning_sms WHERE userid=".$user_id;
			DBconnect::execute($sqlResetWarningSMS);

			$currency = DBConnect::retrieve_value("SELECT value FROM config WHERE name='CURRENCY'");
			DBConnect::execute_q("UPDATE purchases_log SET purchase_finished=1, payment_method='Paypal', payment_type='Worldpay Manual', purchase_finished_date=purchase_datetime WHERE id=".$id);

			$error = "You've finished add coins to ".$username;
		}
		else
		{
			$error = "Please enter valid transaction id.";
		}
	}
	else
	{
		$error = "No transaction ID.";
	}
	return $error;
}

function getMemberPayments($username)
{
	$sql = "SELECT count(*) FROM purchases_log l LEFT JOIN member m ON l.user_id=m.id WHERE m.username like '".$username."' AND l.purchase_finished=1";
	$count = DBConnect::retrieve_value($sql);
	return $count;
}

function insertProfile($data)
{
	$result = "";
	if(is_array($data) && ($data['username'])!="")
	{
		if(!DBConnect::retrieve_value("SELECT id FROM member WHERE username='".$data['username']."'"))
		{
			$clean_save = array();
			foreach($data as $key => $val)
			{
				$clean_save[$key] = funcs::check_input($val);
			}
			//get column names
			$colnames = array_flip(DBconnect::get_col_names(TABLE_MEMBER));
			//delete everything that is not in the database
			$member_post = array_intersect_key($clean_save, $colnames);
			//create the member and get the id from the creation
			if(DBconnect::assoc_insert_1D($member_post, TABLE_MEMBER))
			{
				if($data['picturepath'] != "")
				{
					$id = mysql_insert_id();
					$path = SITE."thumbs/".$id;
					if(!file_exists($path))
					{
						mkdir($path, 0777);
					}
					$pic = $data['server']."thumbs/".$data['picturepath'];
					copy($pic, $path."/".basename($data['picturepath']));
					$picturepath = $id."/".basename($data['picturepath']);
					DBConnect::execute_q("UPDATE member SET picturepath='".$picturepath."' WHERE id=".$id);
				}
				$result = "FINISHED";
			}
			else
			{
				$result = "Unable to insert profile to database.";
			}
		}
		else
		{
			$result = "Duplicate username.";
		}
	}
	return $result;
}

function getAllSucceedPayments($from_datetime=NULL)
{
	$from_datetime_sql = ($from_datetime == NULL)?"":"AND purchase_finished_date>'".$from_datetime."'";
	return DBConnect::assoc_query_2D("SELECT * FROM purchases_log WHERE purchase_finished=1 ".$from_datetime_sql." ORDER BY purchase_datetime ASC");
}




















/**
 * This function resizes the main image by keeping aspect, the "left over"
 * areas will be colored white.
 *
 * @param string $fileName The file to work with.
 * @param int $constraint The constraining amount.
 * @param string $flag The dimenstion to use 'width' or 'height'.
 * @param int $tot_width The width of the total area, including white areas
 * @param int $tot_height The height of the total area, including white areas
 * @return boolean true on success, false otherwise
 */
function resizeImageWhiteBkg($fileName, $constraint, $flag, $tot_width, $tot_height){
	$background = imagecreatetruecolor($tot_width, $tot_height);
	$white = imagecolorallocate($background, 255, 255, 255);
	imagefill($background, 0, 0, $white);
	if(!resizeImage($fileName, $constraint, $flag))
		return false;
	list($front_width, $front_height, $type) = getimagesize($fileName);
	$front_image = imagecreatefromjpeg($fileName);
	$bkg_xpos = round(($tot_width - $front_width) / 2);
	$bkg_ypos = round(($tot_height - $front_height) / 2);
	imagecopy($background, $front_image, $bkg_xpos, $bkg_ypos, 0, 0, $front_width, $front_height);
	imagejpeg($background, $fileName, 100);
	return true;
}

/**
* Resizes images by using a constraint on one side. 
* 
* If I for instance set the flag variable to "width" and the constraint 
* variable to 200 when I call the function on an image that is 600*400 the
* result will be an image with the resolution 200*133.
*
* @param string $fileName The path to the file to use.
* @param int $constraint The length to resize one of the sides to.
* @param string $flag The side to use, width or height.
* @return bool Returns true on success.
*/
function resizeImage($fileName, $constraint, $flag){
	//we retrieve the info from the current image
	list($orig_width, $orig_height, $type) = getimagesize($fileName);
  	$new_width	='';
  	$new_height	='';
	if($flag == "width"){
  		if($orig_width > $constraint){
	   		$new_height=round(( $constraint * $orig_height) / $orig_width);
	   		$new_width = $constraint;
  		}else{
  			$new_height = $orig_height;
  			$new_width = $orig_width;
  		}
  	}else{
  		if($orig_height > $constraint){
	   		$new_width=round(( $constraint * $orig_width) / $orig_height);
   			$new_height = $constraint;
  		}else{
  			$new_height = $orig_height;
  			$new_width = $orig_width;
  		}
	}		

    //we create a new image template
    $image_p = imagecreatetruecolor($new_width, $new_height);
    //we create a variable that will hold the new image
    $image = null;
    //only the three first of all the possible formats are supported, the original image is loaded if it is one of them
	switch($type){
	     case 1: //GIF
			$image = imagecreatefromgif($fileName);
			break;
	     case 2: //JPEG
	      	$image = imagecreatefromjpeg($fileName);
	      	break;
	     case 3: //PNG
	      	$image = imagecreatefrompng($fileName);
	      	break;
	     default;
			exec("convert ".$fileName." ".$fileName.".jpg");
			exec("rm ".$fileName);
			exec("mv ".$fileName.".jpg ".$fileName);
	      	$image = imagecreatefromjpeg($fileName);
	     	break;
	}
	//we copy the resized image from the original into the new one and save the result as a jpeg   
	imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $orig_width, $orig_height);
	imagejpeg($image_p, $fileName, 100);
	return true;
}

















#create server
$server = new SoapServer(NULL, array('uri' => "urn://kontaktmarkt", 'encoding'=>'UTF-8'));

#add functions
$server->addFunction('getProfileFull');
$server->addFunction('getProfileInfo');
$server->addFunction('sendMessage');
$server->addFunction('sendAdminMessage');
$server->addFunction('payment');
$server->addFunction('getAboStatus');
$server->addFunction('getPaymentHistory');
$server->addFunction('getArrayElement');
$server->addFunction('simpleSearch');
$server->addFunction('getPaymentStatistic');
$server->addFunction('getELVStatistic');
$server->addFunction('getSMSStatistic');
$server->addFunction('getWebcamStatistic');
$server->addFunction('getPaymentByType');
$server->addFunction('addLonelyHeartSoap');
$server->addFunction('setRundMail');
$server->addFunction('setVariable');
$server->addFunction('getVars');
$server->addFunction('setUserVariable');
$server->addFunction('debug');
$server->addFunction('addFakeProfile');
$server->addFunction('receiveSMS');
$server->addFunction('getPackageFromTransactionID');
$server->addFunction('finishPurchase');
$server->addFunction('getCountStatistics');
$server->addFunction('getDailyToupUsername');
$server->addFunction('updateWeight');
$server->addFunction('getAllPayments');
$server->addFunction('getAllFirstPayments');
$server->addFunction('getCountPayments');//Noi
$server->addFunction('getPaymentAverage');//Noi
$server->addFunction('getCountDailyPayment');//Noi
$server->addFunction('getAllCPDetails');
$server->addFunction('blockMember');
$server->addFunction('getNewRegistrations');
$server->addFunction('getNewRegistrationNames');//Noi
$server->addFunction('getAllRegistrations');//Noi
$server->addFunction('getMemberActivitiesStat');
$server->addFunction('getSMSHistory');
$server->addFunction('addCoinsPaypal');
$server->addFunction('getMemberPayments');
$server->addFunction('insertProfile');
$server->addFunction('receiveMultiSMS');
$server->addFunction('getAllSucceedPayments');

#start server
$server->handle();
session_destroy();
?>