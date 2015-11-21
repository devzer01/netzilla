<?php 

/**
 * this file is a compilation of most used functions, it needs to be reorganized in a meaningful manner.
 * @author nick 18/11/13
 * @var unknown_type
 */

$random_contacts_female = DBconnect::assoc_query_2D("SELECT username, id, picturepath FROM member WHERE gender=2 AND isactive=1 AND fake=1 AND id>3 AND picturepath!='' ORDER BY RAND() LIMIT " . RANDOM_CONTACTS);
$random_contacts_male = DBconnect::assoc_query_2D("SELECT username, id, picturepath FROM member WHERE gender=1 AND isactive=1 AND fake=1 AND id>3 AND picturepath!='' ORDER BY RAND() LIMIT " . RANDOM_CONTACTS);

shuffle($random_contacts_female);
shuffle($random_contacts_male);

$random_contacts_male = array_slice($random_contacts_male,0, 5);
$random_contacts_female = array_slice($random_contacts_female, 0, 5);
$random_contacts =  array_merge($random_contacts_male, $random_contacts_female);
$smarty->assign("random_contacts", $random_contacts);

//newst members
if(isset($_SESSION['sess_id']))
	$profile = DBConnect::assoc_query_1D("SELECT gender FROM member WHERE id=".$_SESSION['sess_id']);
else
	$profile = array("gender" => 1);
if(isset($_GET['total']) && is_numeric($_GET['total']))
	$maxResult = $_GET['total'];
else
	$maxResult = 12;
if($profile['gender']==2)
{
	$male_amount = 0.6;
	$female_amount = 0.4;
}
else
{
	$male_amount = 0.4;
	$female_amount = 0.6;
}
	
$NewestMembersMale = DBConnect::assoc_query_2D("SELECT username, picturepath FROM member WHERE gender='1' AND picturepath!='' AND isactive=1 AND id>3 ORDER BY id DESC LIMIT ".($maxResult*2));

$NewestMembersFemale = DBConnect::assoc_query_2D("SELECT username, picturepath FROM member WHERE gender='2' AND picturepath!='' AND isactive=1 AND id>3 ORDER BY id DESC LIMIT ".($maxResult*2));

	
	
shuffle($NewestMembersMale);
shuffle($NewestMembersFemale);

$NewestMembersMale = array_slice($NewestMembersMale,0, round($maxResult*$male_amount));
$NewestMembersFemale = array_slice($NewestMembersFemale,0, round($maxResult*$female_amount));
$NewestMembers =  array_merge($NewestMembersMale, $NewestMembersFemale);
shuffle($NewestMembers);
$smarty->assign("newest_members", $NewestMembers);


$total = 12;
$OnlineRealMembers = DBConnect::assoc_query_2D("SELECT id, username, picturepath FROM member m LEFT JOIN member_session s ON m.id=s.member_id WHERE 1 AND m.isactive=1 AND m.id>3 AND picturepath!='' AND s.last_action_datetime>NOW()-INTERVAL 5 MINUTE ORDER BY RAND() LIMIT ".MAX_REAL_MEMBERS_ONLINE);

$time = time();
if(($time-(5*60))>ONLINE_MEMBERS_DATETIME)
{
	$num_profiles_to_removed = round(($time - ONLINE_MEMBERS_DATETIME)/(5*60));

	$OnlineFakeMaleMembersOld = unserialize(DBConnect::retrieve_value("SELECT long_value FROM config WHERE name='ONLINE_MEMBERS_MALE'"));

	$OnlineFakeFemaleMembersOld = unserialize(DBConnect::retrieve_value("SELECT long_value FROM config WHERE name='ONLINE_MEMBERS_FEMALE'"));

	$count = $total;
	$male_id_arr = array();
	if(is_array($OnlineFakeMaleMembersOld))
	{
		//shuffle($OnlineFakeMaleMembersOld);
		for($i=0; $i<$num_profiles_to_removed; $i++)
		{
		if(is_array($OnlineFakeMaleMembersOld))
			array_shift($OnlineFakeMaleMembersOld);
			else
			break;
		}
		$count = $total - count($OnlineFakeMaleMembersOld);
		foreach($OnlineFakeMaleMembersOld as $item)
		{
		array_push($male_id_arr, $item['id']);
		}
		}

		if($count>0)
		{
		if(count($male_id_arr))
			$sql_male_id = " AND id NOT IN (".implode(',', $male_id_arr).") ";
			$OnlineFakeMaleMembers = DBConnect::assoc_query_2D("SELECT id, username, picturepath FROM member WHERE 1 $sql_male_id AND gender=1 AND isactive=1 AND fake=1 AND picturepath!='' AND id>3 ORDER BY RAND() LIMIT ".$count);

			if(is_array($OnlineFakeMaleMembersOld))
			$OnlineFakeMaleMembers = array_merge($OnlineFakeMaleMembers, $OnlineFakeMaleMembersOld);
		}
		else
		{
			$OnlineFakeMaleMembers = $OnlineFakeMaleMembersOld;
		}

			$count = $total;
			$female_id_arr = array();
			if(is_array($OnlineFakeFemaleMembersOld))
			{
			//shuffle($OnlineFakeFemaleMembersOld);
			for($i=0; $i<$num_profiles_to_removed; $i++)
				{
			if(is_array($OnlineFakeMaleMembersOld))
				array_shift($OnlineFakeFemaleMembersOld);
				else
				break;
			}
			$count = $total - count($OnlineFakeFemaleMembersOld);
			foreach($OnlineFakeFemaleMembersOld as $item)
			{
			array_push($female_id_arr, $item['id']);
			}
			}

			if($count>0)
			{
			if(count($female_id_arr))
				$sql_female_id = " AND id NOT IN (".implode(',', $female_id_arr).") ";

				$OnlineFakeFemaleMembers = DBConnect::assoc_query_2D("SELECT id, username, picturepath FROM member WHERE 1 $sql_female_id AND gender=2 AND isactive=1 AND fake=1 AND picturepath!='' AND id>3 ORDER BY RAND() LIMIT ".$count);
				if(is_array($OnlineFakeFemaleMembersOld))
				$OnlineFakeFemaleMembers = array_merge($OnlineFakeFemaleMembers, $OnlineFakeFemaleMembersOld);
			}
			else
			{
			$OnlineFakeFemaleMembers = $OnlineFakeFemaleMembersOld;
			}

			DBConnect::execute_q("UPDATE config SET value='".time()."' WHERE name='ONLINE_MEMBERS_DATETIME'");
			DBConnect::execute_q("UPDATE config SET long_value='".serialize($OnlineFakeMaleMembers)."' WHERE name='ONLINE_MEMBERS_MALE'");
			DBConnect::execute_q("UPDATE config SET long_value='".serialize($OnlineFakeFemaleMembers)."' WHERE name='ONLINE_MEMBERS_FEMALE'");
}
else
{
$OnlineFakeMaleMembers = unserialize(DBConnect::retrieve_value("SELECT long_value FROM config WHERE name='ONLINE_MEMBERS_MALE'"));

$OnlineFakeFemaleMembers = unserialize(DBConnect::retrieve_value("SELECT long_value FROM config WHERE name='ONLINE_MEMBERS_FEMALE'"));
			}

			if(isset($_SESSION['sess_id']) && ($_SESSION['sess_id']))
			{
					$gender = DBConnect::retrieve_value("SELECT gender FROM member WHERE id=".$_SESSION['sess_id']);
					if($gender=="1")
				$OnlineMembers = $OnlineFakeFemaleMembers;
				else
				$OnlineMembers = $OnlineFakeMaleMembers;
			}
			else
			{
			$OnlineMembers = array_merge($OnlineFakeMaleMembers, $OnlineFakeFemaleMembers);
			}

			if(is_array($OnlineRealMembers) && count($OnlineRealMembers)) $OnlineMembers = array_merge($OnlineMembers, $OnlineRealMembers);
			shuffle($OnlineMembers);
$smarty->assign("online_members", array_slice($OnlineMembers,0,$total));



if (!isset($_SESSION['sess_id'])) return; 

//only execute this code when a user is logged in;
$userid = $_SESSION['sess_id'];

$profile = funcs::getProfile($userid);	//get profile data

$searchGender = ($profile['gender'] == 1) ? 2 : 1;

$random_contacts = DBconnect::assoc_query_2D("SELECT username, id, picturepath FROM member WHERE gender=" . $searchGender . " AND isactive=1 AND fake=1 AND id>3 AND picturepath!='' ORDER BY RAND() LIMIT " . RANDOM_CONTACTS);
$smarty->assign("random_contacts", $random_contacts);

$sql = "SELECT username, id, picturepath FROM member WHERE gender=2 AND isactive=1 AND fake=1 AND id>3 ORDER BY RAND() LIMIT 1";
$rs = mysql_query($sql);
$smarty->assign('random_profile', mysql_fetch_assoc($rs));


$phototemp = DBConnect::retrieve_value("SELECT picturepath FROM phototemp WHERE userid=".$userid." AND status=1 ORDER BY id DESC LIMIT 1");
if($phototemp && ($profile['picturepath']==''))
{
	$profile['picturepath'] = $phototemp;
	$profile['approval'] = 1;
}
$profile['gender'] = funcs::getAnswerChoice($_SESSION['lang'],'', '$gender', $profile['gender']);
$profile['country'] = funcs::getAnswerCountry($_SESSION['lang'], $profile['country']);
$profile['state'] = funcs::getAnswerState($_SESSION['lang'], $profile['state']);
$profile['city'] = funcs::getAnswerCity($_SESSION['lang'], $profile['city']);

$smarty->assign('memberProfile', $profile);

//Get new messages's contact
$recent_contacts = DBconnect::assoc_query_2D("SELECT m.username, m.id, m.picturepath, i.datetime, i.status FROM message_inbox i LEFT JOIN member m ON i.from_id=m.id WHERE i.to_id=".$_SESSION['sess_id']." AND m.username IS NOT NULL AND m.id>3 GROUP BY m.username ORDER BY i.datetime DESC LIMIT " . RECENT_CONTACTS );
$smarty->assign("recent_contacts", $recent_contacts);

//favorites 
$favorites = DBConnect::assoc_query_2D("SELECT m.username, m.picturepath FROM favorite f LEFT JOIN member m ON f.child_id=m.id WHERE m.isactive=1 AND f.parent_id=".$_SESSION['sess_id']." ORDER BY f.id DESC");
$smarty->assign('favorites', $favorites);

$member = DBConnect::assoc_query_1D("SELECT * FROM member WHERE id = " . $userid);
$smarty->assign('sitemember', $member);


// User Gifts
$user_id = $_SESSION['sess_id'];
$group = "";

if (isset($_GET['user_id']) && $_GET['user_id'] > 0) {
	$user_id = $_GET['user_id'];
	$group = " GROUP BY g.id";
} 

if (isset($_GET['username']) && trim($_GET['username']) != "") {
	$user_id = funcs::getUserid($_GET['username']);
	$group = " GROUP BY g.id";
}

$sql = "SELECT mg.*, g.*, ms.username AS sender FROM member_gift AS mg "
     . "LEFT JOIN gift AS g ON g.id = mg.gift_id "
     . "LEFT JOIN member AS ms ON ms.id = mg.sender_id "
     . " WHERE mg.member_id = " . $user_id . " " . $group;

$rs = mysql_query($sql);

$gifts = array();

if($rs)
{
	while ($r = mysql_fetch_assoc($rs)) {
		$gifts[] = $r; 
	}

	$smarty->assign('gifts', $gifts);
}

/**
 * All gifts
 */
$gifts = array();
$sql = "SELECT * FROM gift WHERE status = 1";
$rs = mysql_query($sql);
if($rs)
{
	while ($r = mysql_fetch_assoc($rs)) {
		$gifts[] = $r;
	}
	$smarty->assign('list_gifts', $gifts);
}

/**
 * User's gifts
 */
$sql = "SELECT mg.*, g.*, ms.username AS sender, ms.picturepath, COUNT( * ) AS times FROM `member_gift` AS mg "
     . "LEFT JOIN `gift` AS g ON g.id = mg.gift_id "
     . "LEFT JOIN `member` AS ms ON ms.id = mg.sender_id "
     . "WHERE mg.member_id = " . $user_id . " GROUP BY g.id, mg.sender_id "
     . "ORDER BY `mg`.`created` DESC,`times` DESC LIMIT 6";
$rs = mysql_query($sql);
if($rs)
{
	$gifts = array();
	$aaa = array();
	while ($r = mysql_fetch_assoc($rs)) {
		if(!in_array($r['gift_id'], $aaa)){
			$aaa[] = $r['gift_id'];
			$gifts[] = $r; 
		}
	}
	$smarty->assign('user_gifts', $gifts);
}

$already_topup = DBConnect::retrieve_value("SELECT 1 FROM purchases_log WHERE user_id = ".$own_id." AND purchase_finished=1 LIMIT 1");
$smarty->assign('already_topup', $already_topup);
