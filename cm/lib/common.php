<?php

require_once 'lib/dbo/config.php';

/**
 * pass an array with values and will set the key same as value
 */
function initSmartyOptionsArray($array, $index = null, $value = null)
{
	if ($index === null) {
		return array_combine($array, $array);
	}
	
	$return = array();
	
	foreach ($array as $a) {
		if (!isset($a[$index])) {
			throw new Exception("specified key not found in array");
		}	
		
		if ($value === null) {
			$return[$a[$index]] = $a;
		} elseif (!isset($a[$value])) {
			throw new Exception("specified value key not found in array");
		} else {
			$return[$a[$index]] = $a[$value];
		}
	}
	
	return $return;
}

function trimFields($fields = array(), $type = 'post')
{

}

function getAge($birthday)
{
	$birthDate = explode("-", $birthday);
	//get age from date or birthdate
	$age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0]))) > date("md")
			? ((date("Y") - $birthDate[0]) - 1)
			: (date("Y") - $birthDate[0]));
	return $age;
}

/**
 * 
 * Returns a random profile username based on the username passed to the function 
 * 
 * @param string $username
 * @return Ambigous <boolean, mixed>
 */
function getRandomStartProfile($username)
{
	$dbo_member = new dbo_member();
	$member = $dbo_member->getBasicMemberInfo($username);
	
	$gender = ($member['gender'] == 2) ? 1 : 2;
	
	$young = $dbo_member->getFakeMemberYoug($gender, $member['country'], $member['city'], $member['birthday']);	
	if ($young !== false) return $young;
	
	$older = $dbo_member->getFakeMemberOld($gender, $member['country'], $member['city'], $member['birthday']);
	if ($older !== false) return $older;
	
	$state = $dbo_member->getFakeMemberState($gender, $member['country'], $member['state'], $member['city']);
	if ($state !== false) return $state;
	
	$country = $dbo_member->getFakeMemberCountry($gender, $member['country']);
	if ($country !== false) return $country;
	
	return $dbo_member->getSupportName();
}

function getChoiceCountry()
{
	return array(array('id' => 1, 'name' => 'Germany'));
	if($_SESSION['lang'] == 'eng')
		$select_field = "name";
	else
		$select_field = "name_de";

	$sql = "SELECT id, $select_field AS name FROM xml_countries WHERE status = 1 OR id = 23 ORDER BY priority ASC";
	$country = DBconnect::assoc_query_2D($sql);

	return $country;
}

function getZodiac( $birthdate )
{
	$zodiac = "";
	 
	list ( $year, $month, $day ) = explode ( "-", $birthdate );
	 
	if     ( ( $month == 3 && $day > 20 ) || ( $month == 4 && $day < 20 ) ) {
		$zodiac = 3;
	}
	elseif ( ( $month == 4 && $day > 19 ) || ( $month == 5 && $day < 21 ) ) {
		$zodiac = 4;
	}
	elseif ( ( $month == 5 && $day > 20 ) || ( $month == 6 && $day < 21 ) ) {
		$zodiac = 5;
	}
	elseif ( ( $month == 6 && $day > 20 ) || ( $month == 7 && $day < 23 ) ) {
		$zodiac = 6;
	}
	elseif ( ( $month == 7 && $day > 22 ) || ( $month == 8 && $day < 23 ) ) {
		$zodiac = 7;
	}
	elseif ( ( $month == 8 && $day > 22 ) || ( $month == 9 && $day < 23 ) ) {
		$zodiac = 8;
	}
	elseif ( ( $month == 9 && $day > 22 ) || ( $month == 10 && $day < 23 ) ) {
		$zodiac = 9;
	}
	elseif ( ( $month == 10 && $day > 22 ) || ( $month == 11 && $day < 22 ) ) {
		$zodiac = 10;
	}
	elseif ( ( $month == 11 && $day > 21 ) || ( $month == 12 && $day < 22 ) ) {
		$zodiac = 11;
	}
	elseif ( ( $month == 12 && $day > 21 ) || ( $month == 1 && $day < 20 ) ) {
		$zodiac = 12;
	}
	elseif ( ( $month == 1 && $day > 19 ) || ( $month == 2 && $day < 19 ) ) {
		$zodiac = 1;
	}
	elseif ( ( $month == 2 && $day > 18 ) || ( $month == 3 && $day < 21 ) ) {
		$zodiac = 2;
	}

	return $zodiac;
}

function getDateTime()
{
	return date("Y-m-d H:i:s", time());
}

function isLoggedIn()
{
	return (isset($_SESSION['sess_id']));
}

function randomPassword($length)
{
	$char = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUWVXYZ";
	$char_arr = str_split($char);
	$pass = '';
	for($n=0;$n<$length;$n++)
	{
		$rand_char = rand(0,count($char_arr)-1);
		$pass .= $char_arr[$rand_char];
	}
	return $pass;
}

function removeEmailAddressFromText($text)
{
	return preg_replace('#[^\s]+@[^\s]+#', '[EMAIL BLOCKED]', $text);
}

function revealUsername($username)
{
	if (strtolower($username) == strtolower(ADMIN_USERNAME_DISPLAY)) return ADMIN_USERNAME;
	return $username;
}

/**
 * 
 * @param int $from sender id
 * @param string $to receiver username
 * @param string $subject
 * @param string $message
 * @param int $mtype
 * @param mixed $attachments
 * @param boolean $isfree
 * @param boolean $is_gift
 * @return boolean
 */
function sendMessage($from, $to, $subject, $message, $mtype, $attachments="", $isfree = false, $is_gift = false){
	
	require_once 'lib/dbo/message.php';
	
	if ($is_gift === 1) $isfree = true;
	if ($attachments == "") $attachments = array("coins" => 0 , 'gift' => 0);

	$subject = removeEmailAddressFromText($subject);
	$message = removeEmailAddressFromText($message);

	$dbo_member = new dbo_member();
	$username = $dbo_member->getUsername($from);
	$currentCoin = $dbo_member->getCoins($username);
	
	$dbo_config = new dbo_config();
	$minusEmail = $dbo_config->getCostEmail();
	if ($isfree) $minusEmail = 0;
	$userid = $dbo_member->getId($to);

	if ($userid === false) {
		throw new Exception("Recepient Not Found");
	}
	if ($currentCoin < $minusEmail) {
		throw new Exception("Not Enough Coins");
	}
	
	$dbo_member->deductCoin($username, $minusEmail);
	$dbo_member->addCoinLog($from, $userid, 'email', $minusEmail, $dbo_member->getCoins($username));
	
	
	if (isset($attachments['gift']) && $attachments['gift'] > 0) {
		$gift_cost = $dbo_config->getGiftCost($attachments['gift']);
		$dbo_member->deductCoin($username, $gift_cost);
		$dbo_member->addCoinLog($from, $userid, 'gift', $gift_cost, $dbo_member->getCoins($username));
	}
					
	if ($dbo_member->isFake($dbo_member->getUsername($userid))) {
		
		$data = $dbo_member->getPaymentData($username);
		
		if (isset($attachments['gift']) && $attachments['gift'] > 0) {
			$sticker_path = $dbo_config->getGiftPath($attachments['gift']);
			$full_path = APP_URL . $sticker_path;
			$message = "<img src='" . $full_path . "' />";
		}
		
		$msg = new stdClass();
		$msg->to = $userid;
		$msg->from = $from;
		$msg->msg = $message;
		$msg->subject = $subject;
		$msg->serverID = SERVER_ID;
		$msg->type = $data['type'];
		$msg->payment = $data['payment'];
		$msg->mtype = $mtype;
		$msg->attachment_coins = $attachments['coins'];
		
		$soap = new SoapClient(null, array('exceptions' => true, 'location' => SERVER_URL, 'uri' => 'urn://kontaktmarkt'));
		
		try {
			$ret = $soap->sendMessage($msg);
		} catch (Exception $e) {
			$dbo_queue = new dbo_queue();
			$dbo_queue->queueMessage($userid, $from, $subject, $message, $type, $mtype, $attachments['coins'], $attachments['gift'], SERVER_ID);
		}
		
		if ($mtype != 3 && $mtype != 5){
			if ($is_gift) $message = "Gift Sent";
			
			$dbo_message = new dbo_message();
			$dbo_message->addToOutbox($userid, $from, $subject, $message, $attachments['coins'], $attachments['gift']);
		}	

	} else {
		
		if ($is_gift) $message = "Gift Received";
		
		sendNotificationEmail($userid, $from, $subject, $message);
		
		$dbo_message = new dbo_message();
		$dbo_message->addToInbox($userid, $from, $subject, $message, $attachments['coins'], $attachments['gift']);
		
		if ($is_gift) $message = "Gift Sent";
		$dbo_message->addToOutbox($userid, $from, $subject, $message, $attachments['coins'], $attachments['gift']);
		
	}

	if (!$isfree) $dbo_member->setFirstMessage($username);
	$dbo_member->setLastFromTo($username, $to);

	return true;
}

function isDescPendingApproval($username)
{
	require_once 'lib/dbo/member.php';
	$dbo_member = new dbo_member();
	return ($dbo_member->getPendingProfileDesc($username) !== false); 
}

function isPhotoPendingApproval($username)
{
	require_once 'lib/dbo/member.php';
	$dbo_member = new dbo_member();
	return ($dbo_member->getPendingProfilePicture($username) !== false);
}

function getProfileWithText($username)
{
	require_once 'lib/dbo/member.php';
	$dbo_member = new dbo_member();
	$member = $dbo_member->getFullMemberInfo($username);
	
	$member['age'] = getAge($member['birthday']);
	$member['gender_text'] = _("Male");
	if ($member['gender'] == 2) $member['gender_text'] = _("Female");

	return $member;
}

function getOnlineMembers()
{
	require_once 'lib/memcached.php';
	require_once 'lib/dbo/member.php';
	
	$m = nzmemcache::getInstance();
	$m->connect('localhost', 11211);
	
	$dbo_member = new dbo_member();
	
	$username = $_SESSION['sess_username'];
	$member = $dbo_member->getBasicMemberInfo($username);
	
	$total = 12;
	$gender = $member['gender'];
	
	$OnlineMembers = $m->get('OnlineMembers_' . $gender . '_' . SITE);
	if ($OnlineMembers === false || $m->getResultCode() != Memcached::RES_SUCCESS) {
	
		$OnlineRealMembers = $m->get('OnlineRealMembers_' . SITE);
		if ($OnlineRealMembers === false || $m->getResultCode() != Memcached::RES_SUCCESS) {
			$OnlineRealMembers = $dbo_member->getOnlineMembers();
			$m->set('OnlineRealMembers_' . SITE, $OnlineRealMembers);
		}
	
		$OnlineFakeFemaleMembers = $m->get('OnlineFakeFemaleMembers_' . SITE);
		if ($OnlineFakeFemaleMembers === false || $m->getResultCode() != Memcached::RES_SUCCESS) {
			$OnlineFakeFemaleMembers = $dbo_member->getFakeMembersByGender(GENDER_FEMALE);
			$m->set('OnlineFakeFemaleMembers_' . SITE, $OnlineFakeFemaleMembers);
		}
	
		$OnlineFakeMaleMembers = $m->get('OnlineFakeMaleMembers_' . SITE);
		if ($OnlineFakeMaleMembers === false || $m->getResultCode() != Memcached::RES_SUCCESS) {
			$OnlineFakeMaleMembers = $dbo_member->getFakeMembersByGender(GENDER_MALE);
			$m->set('OnlineFakeMaleMembers_' . SITE, $OnlineFakeMaleMembers);
		}
	
		if($gender=="1") $OnlineMembers = $OnlineFakeFemaleMembers;
		elseif ($gender == 0) $OnlineMembers = array_merge($OnlineFakeMaleMembers, $OnlineFakeFemaleMembers);
		else $OnlineMembers = $OnlineFakeMaleMembers;
	
	
		$OnlineMembers = array_merge($OnlineMembers, $OnlineRealMembers);
		$m->set('OnlineMembers_' . $gender . '_' . SITE, $OnlineMembers);
	}
	
	shuffle($OnlineMembers);
	return array_slice($OnlineMembers,0,$total);
}

function getNewestMembers()
{
	require_once 'lib/memcached.php';
	require_once 'lib/dbo/member.php';
	
	$m = nzmemcache::getInstance();
	$m->connect('localhost', 11211);
	
	$dbo_member = new dbo_member();
	
	$username = $_SESSION['sess_username'];
	$member = $dbo_member->getBasicMemberInfo($username);
	
	$male_amount = 0.4;
	$female_amount = 0.6;
	
	if($member['gender'] == 2)
	{
		$male_amount = 0.6;
		$female_amount = 0.4;
	}
	
	$maxResult = 12;
	
	
	$NewestMembersMale = $m->get('NewestMembersMale_' . SITE);
	if ($NewestMembersMale === false || $m->getResultCode() != Memcached::RES_SUCCESS) {
		$NewestMembersMale = $dbo_member->getMembersByGender(GENDER_MALE);
		$m->set('NewestMembersMale_' . SITE, $NewestMembersMale);
	}
	
	$NewestMembersFemale = $m->get('NewestMembersFemale_' . SITE);
	if ($NewestMembersFemale === false || $m->getResultCode() != Memcached::RES_SUCCESS) {
		$NewestMembersFemale = $dbo_member->getMembersByGender(GENDER_FEMALE);
		$m->set('NewestMembersFemale_' . SITE, $NewestMembersFemale);
	}
	
	shuffle($NewestMembersMale);
	shuffle($NewestMembersFemale);
	
	$NewestMembersMale = array_slice($NewestMembersMale,0, round($maxResult*$male_amount));
	$NewestMembersFemale = array_slice($NewestMembersFemale,0, round($maxResult*$female_amount));
	$NewestMembers =  array_merge($NewestMembersMale, $NewestMembersFemale);
	shuffle($NewestMembers);
	return array_slice($NewestMembers, 0, $maxResult);
}

function sendNotificationEmail($userid, $from, $subject, $message, $ses = false) 
{

	$dbo_member = new dbo_member();
	
	$smarty = basesmarty::getInstance();
	$receiver = $dbo_member->getUsername($userid);
	$sender = $dbo_member->getBasicMemberInfo($dbo_member->getUsername($from));

	if ($sender === false) return false;
	
	$gender = ($sender['gender'] == 1) ? _("Male") : _("Female");
	$age = date_diff(date_create($sender['birthday']), date_create('now'))->y;
	$user = $sender['username'];
	if ($from == 1) $user = ADMIN_USERNAME_DISPLAY;
		
	$smarty->assign('username', $receiver);
	$smarty->assign('user', $user);
	$smarty->assign('age', $age);
	$smarty->assign('gender', $gender);
	$smarty->assign('city', $sender['city']);
	$smarty->assign('subj', $subject);
	$smarty->assign('mess', $message);

	$picture_path = 'default.jpg';
	if($sender['picturepath'] != ""){
		$picture_path = $sender['picturepath'];
	}
	$smarty->assign('picturepath', $picture_path);
	
	$message = $smarty->fetch('email/email_message.tpl');
	
	$subj = "Flirt48.net - Du hast eine Nachricht " . $user . " auf Flirt48.net erhalten!";

	require_once 'lib/dbo/queue.php';
	$dbo_queue = new dbo_queue();
	$dbo_queue->queueEmail($dbo_member->getEmail($receiver), $subj, $message, '', '');
}

function performLogin($username, $password)
{
	require_once 'lib/dbo/member.php';

	$dbo_member = new dbo_member();
	$member = $dbo_member->loginSite($username, $password);

	if ($member === false) {
		return 0;
	}

	if (strtolower($username) == strtolower(ADMIN_USERNAME)) {
		if (!in_array($_SERVER['REMOTE_ADDR'], unserialize(ADMIN_ACL_LIST))) {
			return 0;
		}
	}

	if ($member['isactive'] == 1) {
		setLoginSession($member);

		$first = 0;

		if (($member['signin_datetime'] == '0000-00-00 00:00:00') && ($member['fake'] == 0)) {
			$first = 1;
			$profile = getRandomStartProfile($member['username']);
			sendMessage($member['id'], $profile, "New registration", "New registration", 3, "", true);
		}
		$_SESSION['sess_first'] = $first;

		$dbo_member->setSignIn($member['username']);
		return 1;
	}

	if ($member['isactive'] == 0) {
		$_SESSION['temp_member_id'] = $member['id'];
		return 2;
	}
}

function setLoginSession($member)
{
	$_SESSION['sess'] = session_id();
	$_SESSION['sess_id'] = $member['id'];
	$_SESSION['gender'] = $member['gender'];
	$_SESSION['tcheck'] = $member['tcheck'];
	$status = $member['type'];
	$_SESSION['sess_permission'] = $status;
	$_SESSION['sess_username'] = $member['username'];

	switch($status){
		case 1:
			$_SESSION['sess_admin'] = 1;
			$_SESSION['sess_mem'] = 1;
			$_SESSION['sess_smalladmin'] = 1;
			$_SESSION['sess_useradmin'] = 1;
			$_SESSION['payment_admin'] = 1;
			$_SESSION['sess_profileadmin'] = 1;
			break;
		case  2:
			$_SESSION['sess_admin'] = 0;
			$_SESSION['sess_mem'] = 1;
			$_SESSION['sess_smalladmin'] = 0;
			$_SESSION['sess_useradmin'] = 0;
			$_SESSION['payment_admin'] = 0;
			$_SESSION['sess_profileadmin'] = 0;
			break;
		case  3:
			$_SESSION['sess_admin'] = 0;
			$_SESSION['sess_mem'] = 1;
			$_SESSION['sess_smalladmin'] = 0;
			$_SESSION['sess_useradmin'] = 0;
			$_SESSION['payment_admin'] = 0;
			$_SESSION['sess_profileadmin'] = 0;
			break;
		case  4:
			$_SESSION['sess_admin'] = 0;
			$_SESSION['sess_mem'] = 1;
			$_SESSION['sess_smalladmin'] = 0;
			$_SESSION['sess_useradmin'] = 0;
			$_SESSION['payment_admin'] = 0;
			$_SESSION['sess_profileadmin'] = 0;
			break;
		case  5:
			$_SESSION['sess_admin'] = 0;
			$_SESSION['sess_mem'] = 0;
			$_SESSION['sess_smalladmin'] = 0;
			$_SESSION['sess_useradmin'] = 0;
			$_SESSION['payment_admin'] = 0;
			$_SESSION['sess_profileadmin'] = 0;
			break;
		case 7:
			$_SESSION['sess_admin'] = 0;
			$_SESSION['sess_mem'] = 1;
			$_SESSION['sess_smalladmin'] = 0;
			$_SESSION['sess_useradmin'] = 0;
			$_SESSION['payment_admin'] = 0;
			$_SESSION['sess_profileadmin'] = 1;
			break;
		case 8:
			$_SESSION['sess_admin'] = 0;
			$_SESSION['sess_mem'] = 1;
			$_SESSION['sess_smalladmin'] = 0;
			$_SESSION['sess_useradmin'] = 1;
			$_SESSION['payment_admin'] = 0;
			$_SESSION['sess_profileadmin'] = 0;
			break;
		case  9:
			$_SESSION['sess_admin'] = 0;
			$_SESSION['sess_mem'] = 1;
			$_SESSION['sess_smalladmin'] = 1;
			$_SESSION['sess_useradmin'] = 1;
			$_SESSION['payment_admin'] = 1;
			$_SESSION['sess_profileadmin'] = 0;
			break;
	}

	if(!defined("USERNAME_CONFIRMED")) define("USERNAME_CONFIRMED" , $member['username_confirmed']);
}