<?php
require_once('classes/top.class.php');
$testing = false;
$arr1 = array();
$arr2 = array();
$duration_type = array(1 => "3 day", 2 => "1 month", 3 => "3 month", 4 => "1 year");
$cancel_days_before_expire = array(1 => 0, 2 => 6, 3 => 13, 4 => 29);
$email1 = "payment@tmp-sn.de";
$email2 = "hs@mintnet.de";
$email3 = "ph@mintnet.de";
//$email1 = "phai@server.westkit.com";
//$email2 = "phai@server.westkit.com";
//$email3 = "phai@server.westkit.com";

$time_limit = 300; // time duration to be able to run this script.

$time = DBConnect::retrieve_value("SELECT value FROM config WHERE name='RECURRENT_TIME'");
if($time == null)
{
	DBConnect::execute("INSERT INTO config (id, name, value) VALUES(null, 'RECURRENT_TIME', '".time()."')");
}
elseif((time() - $time) > $time_limit)
{
	DBConnect::execute("UPDATE config SET value='".time()."' WHERE name='RECURRENT_TIME'");
}
elseif((time() - $time) < 300)
{
	echo "Can not run.";
	exit();
}

echo "<pre>Herzoase.com\n";

//================================== [Start] Update member SMS ===================================//
$sql = "UPDATE member SET sms = 0";
DBconnect::execute($sql);
//================================== [End] Update member SMS ===================================//


//========================== [Start] Search for member with reminder_date is today ==========================//
$sql = "SELECT ID, username FROM payment_log WHERE reminder_date = CURDATE() AND recall = '0'"; //and cancelled = '0' ??? 
$list = DBconnect::assoc_query_2D($sql);
if($list)
{
	echo "==================================================================================================================\n\n";
	echo "[Start] Search for member with reminder_date is today.\n\n";
	foreach($list as $log)
	{
		echo "<b>".$log['username']."</b> is cancelled due to reminder_date.\n";
		$member = DBConnect::assoc_query_1D("SELECT * FROM member WHERE username='{$log['username']}'");
		DBConnect::execute("UPDATE member SET isactive = 0 WHERE username = '{$log['username']}'");
		DBConnect::execute("UPDATE payment_log SET recall='1' WHERE ID='{$log['ID']}'");
		funcs::sendMemberCancelEmail30Days($member['id'], $log['ID'], $smarty);
		//funcs::sendAdminCancelEmail30Days($member['id'], $log['ID'], $smarty);
	}
	echo "\n[End] Search for member with reminder_date is today.\n\n";
}
//======================== [End] Search for member with reminder_date is today =============================//


//========================= [START] daily check for auto extent membership duration [12/09/2007]=====================
//================================== [Start] Check for membership that expired and not paid. ===================================//
$list = DBConnect::assoc_query_2D("SELECT * from member WHERE DATE(payment) = CURDATE() - INTERVAL 1 DAY AND (prolonging_payment_id <> 0 OR prolonging_payment_id <> null)");
if($list)
{
	echo "==================================================================================================================\n\n";
	echo "[Start] Check for membership that expired and not paid.\n\n";

	$cancelled_list = array();
	foreach($list as $member)
	{
		$sql = "SELECT t1.*, t2.id as member_id, t2.mobileno as member_mobileno, t2.email FROM payment_log t1, member t2 WHERE t1.username = t2.username AND t1.ID=".$member['prolonging_payment_id']." AND payment_complete = 0";

		$list = DBConnect::assoc_query_1D($sql);
		if($list)
		{
			funcs::sendMemberCancelEmail($member['id'], $member['prolonging_payment_id'], $smarty);
			array_push($cancelled_list, $list);

			echo "<b>".$member['username']."</b> expired on ".strftime("%Y-%m-%d",strtotime($member['payment'])).".";
			echo " Payment Id => ".$member['prolonging_payment_id']."\n";
		}
	}
	if(count($cancelled_list) > 0)
	{
		$subject = 'Mitgliedschaft ausgelaufen - keine Zahlung eingegangen';
		funcs::send_memberCancel_admin($smarty,$email2,$subject,$cancelled_list);
		funcs::send_memberCancel_admin($smarty,$email1,$subject,$cancelled_list);		
	}
	echo "\n[End] Check for membership that expired and not paid.\n\n";
}
//================================== [End] Check for membership that expired and not paid. ===================================//
//================================== [Start] Check for membership that will expire today. ===================================//
$list = getExpiredMembership(-1);
if($list)
{
	echo "==================================================================================================================\n\n";
	echo "[Start] Check for membership that will expire today.\n\n";
	foreach($list as $member)
	{
		if(!isCancelled($member['id']))
		{
			//Check for 3 days membership duration.
			if(($rate = checkForMembershipDurationType($member['payment_received'], '', $member['payment'])) == 1)
			{
				echo "<b>".$member['username']."</b> expired on ".$member['payment']." => current duration ".$duration_type[$rate].".";
				
				$rate = 2;
				$type = 2; // Set to Gold membership.
				$sql = "SELECT DATE(payment + INTERVAL ".$duration_type[$rate].") as new FROM ".TABLE_MEMBER." WHERE id=".$member['id'];
				$extend_to = DBConnect::retrieve_value($sql);
				echo " Extent to ".$extend_to.". Price = ";
				$pay = getPrice($type, $rate);
				echo $pay.".\n";

				$sql = "SELECT paid_via FROM payment_log WHERE username = '$member[username]' and payment_complete = '1' ORDER BY ID DESC LIMIT 1";
				$last_payment = DBConnect::retrieve_value($sql);

				if(!$testing)
				{
					if($last_payment == 4)
						$paid_via = 4;
					elseif($last_payment == 2 || $last_payment == 3)
						$paid_via = 3;
					else
						$paid_via = $last_payment;

					funcs::insertpayment($member['id'],$type,$rate,$paid_via,0);
					// Send new email to member.
					funcs::send_memberExtend_customer_3_days($smarty, $member['username'], $member['email'], $type, $extend_to, $pay);
				}

				if($last_payment == 4)
					array_push($arr2, getReportDetails($member['username'],$transaction_no));
				elseif($last_payment == 2 || $last_payment == 3)
					array_push($arr1, getReportDetails($member['username']));
			}
		}
		else
		{
			echo "<b>".$member['username']."</b> cancelled membership.\n";
		}
	}
	echo "\n[End] Check for membership that will expire today.\n\n";
}
//================================== [End] Check for membership that will expire today. ===================================//
//================================== [Start] Check for membership that will expire in 7 days. ===================================//
$list = getExpiredMembership(6);
if($list)
{
	echo "==================================================================================================================\n\n";
	echo "[Start] Check for membership that will expire in 7 days.\n\n";
	foreach($list as $member)
	{
		if(!isCancelled($member['id']))
		{
			//Check for NOT 3 days membership duration.
			if(($rate = checkForMembershipDurationType($member['payment_received'], '', $member['payment'])) != 1)
			{
				echo "<b>".$member['username']."</b> will expire on ".$member['payment']." => current duration ".$duration_type[$rate].".";
				
				$rate = ($rate == 4)?4:$rate+1;
				$type = $member['type'];
				$sql = "SELECT DATE(payment + INTERVAL ".$duration_type[$rate].") as new FROM ".TABLE_MEMBER." WHERE id=".$member['id'];
				$extend_to = DBConnect::retrieve_value($sql);
				echo " Extent to ".$extend_to.". Price = ";
				$pay = getPrice($type, $rate);
				echo $pay.".\n";

				$sql = "SELECT paid_via FROM payment_log WHERE username = '$member[username]' and payment_complete = '1' ORDER BY ID DESC LIMIT 1";
				$last_payment = DBConnect::retrieve_value($sql);

				if(!$testing)
				{
					if($last_payment == 4)
					{
						$paid_via = 4;
						
						$sql = "SELECT transaction_no FROM payment_log WHERE username = '$member[username]' and transaction_no != '' ORDER BY ID DESC LIMIT 1";						
						$transaction_no = DBConnect::retrieve_value($sql);				
						funcs::insertpayment($member['id'],$type,$rate,$paid_via,0);
						// Send new email to member.
						funcs::send_memberExtend_customer_paid_via_4($smarty, $member['username'], $member['email'], $type, $extend_to, $pay);
					}
					else
					{
						if($last_payment == 2 || $last_payment == 3)
							$paid_via = 3;
						else
							$paid_via = $last_payment;

						funcs::insertpayment($member['id'],$type,$rate,$paid_via,0);
						// Send new email to member.
						funcs::send_memberExtend_customer($smarty, $member['username'], $member['email'], $type, $extend_to, $pay);
					}
				}

				if($last_payment == 4){
					array_push($arr2, getReportDetails($member['username'],$transaction_no));	
					}					
				else
					array_push($arr1, getReportDetails($member['username']));
			}
		}
		else
		{
			echo $member['username']." cancelled membership.\n";
		}
	}
	echo "\n[End] Check for membership that will expire in 7 days.\n\n";
}
//================================== [End] Check for membership that will expire in 7 days. ===================================//
echo "==================================================================================================================\n";
//================================== [Start] Send report to admin. ===================================//

if(!$testing)
{
	if(count($arr1) > 0)
	{
		$subject = 'Herzoase Mitgliedschaft verlängert - Info';
		funcs::send_memberExtend_admin($smarty,$email1,$subject,$arr1);
		echo "\nEmail an $email1";
	}

	if(count($arr2) > 0)
	{
		$subject = 'Herzoase Mitgliedschaft verlängert - ELV Buchung vornehmen';
		funcs::send_memberExtend_admin($smarty,$email2,$subject,$arr2);
		echo "\nEmail an $email2";
	}
}
//================================== [End] Send report to admin. ===================================//

//========================= [END] daily check for auto extent membership duration [20/7/02007]=====================




//========================= [START] daily check for expiring memberships [20/7/2550]=====================
$sql = "select id FROM ".TABLE_MEMBER." WHERE type in (2,3) AND UNIX_TIMESTAMP(payment) > 0 AND UNIX_TIMESTAMP(payment) - UNIX_TIMESTAMP(now()) <= 259200";
$list = DBConnect::assoc_query_2D($sql);

if(is_array($list))
{
	if(count($list) > 0)
	{
		foreach($list as $member)
		{
			$to_id = funcs::randomStartProfile($member['id']);
			funcs::sendMessage($member['id'],$to_id,'Abo läuft aus','Abo läuft aus',4);
		}
	}
}
else
{
	echo "Es laufen keine Mitgliedschaften aus!";
}
//========================= [END] daily check for expiring memberships [20/7/2550]=====================


//================================== [Start] Check for expired membership and set to Bronze. ===================================//
$sql = "UPDATE ".TABLE_MEMBER." SET type = 4 WHERE DATE(payment) < CURDATE() AND type IN (2,3)";
if(!$testing)
	DBConnect::execute($sql);
else
	echo $sql."\n";
//================================== [End] Check for expired membership and set to Bronze. ===================================//


//===================== [Start] Search for active member with isactive_datetime before deactivation_period ==========//
//===================== [Start] Search for member with payment(_complete)-entry after isactive_datetime ==========//
$query = "select m.id as uid, m.username, p.id as pay_id from member m, payment_log p 
            where m.username = p.username
			  and m.isactive_datetime != '0000-00-00 00:00:00'
              and m.isactive_datetime != ''
			  and m.isactive_datetime < p.payday
              and p.payment_complete = '1'
              or (m.isactive_datetime != '0000-00-00 00:00:00'
              	and m.isactive_datetime != ''
            	and m.signin_datetime > m.isactive_datetime)
              group by m.id";

$list = DBconnect::assoc_query_2D($query);
if ($list) {
    foreach ($list as $key) {
        DBConnect::execute("update member set isactive = '1' AND isactive_datetime = '0000-00-00 00:00:00' where id = '".$key['id']."'");
    }
}

$query = "select deactivation_period from config;";
$deactperiod = DBConnect::assoc_query_1D($query);
//$deactperiod = 30;

$query = "select id, username
    from member
    where isactive = '1'
      and isactive_datetime < '".date("Y-m-d H:i:s", time()-$deactperiod*24*60*60)."';";

$list = DBconnect::assoc_query_2D($query);
if($list)
{
	echo "==============================================================================================================<br><br>\n\n";
	echo "[Start] Search for active member with isactive_datetime before deactivation_period.<br>\n\n";
	foreach($list as $log)
	{
		echo "<b>".$log['username']."</b> is cancelled due to deactivation_period.<br>\n";
		DBConnect::execute("update member set isactive=0 AND validation_code='".funcs::randomPassword(6)."' where id = '{$log['id']}'");
	   
		include_once("./libs/nusoap.php");
			$message_assoc_array= array('profileID'=>$id,'serverID'=>SERVER_ID);
			$parameters = array('msg'=>$message_assoc_array);
			$soapclient = new soapclient(SERVER_URL);
			$array = $soapclient->call('deleteprofile',$parameters);
	
	}
	echo "\n[End] Search for active member with isactive_datetime before deactivation_period.<br><br>\n\n";
}
else echo "Kein Eintrag";

//===================== [End] Search for active member with isactive_datetime before deactivation_period ==========//


echo "</pre>";










/**
* Get list of expired membership.
* @param integer $days The days that membership already expired.
* @return the array of membership informations, otherwise false.
* @uses getExpiredMembership()
*/
function getExpiredMembership($days)
{
	if($days >= 0)
	{
		$sql = "SELECT id, username,type,email, DATE(payment) as payment, DATE(payment_received) as payment_received FROM ".TABLE_MEMBER." WHERE (DATE(payment) = (CURDATE() + INTERVAL $days DAY)) AND (DATE(payment_received) <> '0000-00-00') AND type IN (2,3)";
	}
	else
	{
		$days = $days * (-1);
		$sql = "SELECT id, username,type,email, DATE(payment) as payment, DATE(payment_received) as payment_received FROM ".TABLE_MEMBER." WHERE DATE(payment) = CURDATE() - INTERVAL $days DAY AND DATE(payment_received) <> '0000-00-00' AND type IN (2,3)";
	}
	$list = DBConnect::assoc_query_2D($sql);

	if(is_array($list) && (count($list) > 0))
		return $list;
	else
		return false;
}

/**
* Get type of membership duration.
* @param date $start The date that membership start.
* @param end $start The date that membership end.
* @return the integer of membership duration type.
* @uses checkForMembershipDurationType()
*/
function checkForMembershipDurationType($start1, $start2, $end)
{
	if($start2 != '')
	{
		$temp = funcs::dateDiff("-", $start1, $start2);
		if($temp > 0)
			$start = $start1;
		else
			$start = $start2;
	}
	else
		$start = $start1;
	echo $duration = funcs::dateDiff("-", $end, $start);
	if(($duration > 1) && ($duration < 5))
		return 1;
	elseif($duration < 40)
		return 2;
	elseif($duration < 100)
		return 3;
	else
		return 4;
}

/**
* Get list of report details.
* @param string $username The username of member.
* @return the array of payment details.
* @uses getReportDetails()
*/
function getReportDetails($username, $transaction_no='')
{
	$sql = "SELECT p.*,m.ID as m_id,m.username as m_username,m.mobileno as m_mobileno FROM payment_log as p INNER JOIN member as m ON m.username = p.username WHERE m.username = '".$username."' ORDER BY ID DESC LIMIT 1";
	$list = DBConnect::assoc_query_1D($sql);

	if(empty($transaction_no)) $transaction_no = '---';
	
	if(is_array($list) && (count($list) > 0)){
		$transaction_no_array = array('former_transaction_no' => $transaction_no);
		$result = array_merge($list, $transaction_no_array);	
		return $result;
	}
	else
		return false;

}

/**
* check if already cancelled membership.
* @param integer $user_id The member id.
* @return the boolean of membership cancelled.
* @uses isCancelled()
*/
function isCancelled($user_id)
{
	global $cancel_days_before_expire;
	$info = funcs::getPaymentHistory($user_id);
	if(($info['reminder_date'] == '0000-00-00') || ($info['reminder_date'] == null))
	{
		if($info['cancelled_date'] == '0000-00-00 00:00:00')
			return false;
		else
		{
			$rate = checkForMembershipDurationType($info['payday'], $info['old_paid_until'], $info['new_paid_until']);
			// check for cancelled before. Base on the membership duration.
			$duration = funcs::dateDiff("-", $info['new_paid_until'], $info['cancelled_date']);
			if(($rate == 1) && ($duration >= $cancel_days_before_expire[1]))
				return true;
			elseif(($rate == 2) && ($duration >= $cancel_days_before_expire[2]))
				return true;
			elseif(($rate == 3) && ($duration >= $cancel_days_before_expire[3]))
				return true;
			elseif(($rate == 4) && ($duration >= $cancel_days_before_expire[4]))
				return true;
			else
				return false;
		}
	}
	else
		return true;
}

function getPrice($type, $rate)
{
	if($type == 2)
	{
		if($rate == 2)
			return GOLD_DURATION_PRICE_2;
		elseif($rate == 3)
			return GOLD_DURATION_PRICE_3;
		elseif($rate == 4)
			return GOLD_DURATION_PRICE_4;
		else
			return 0;
	}
	elseif($type == 3)
	{
		if($rate == 1)
			return SILVER_DURATION_PRICE_1;
		elseif($rate == 2)
			return SILVER_DURATION_PRICE_2;
		elseif($rate == 3)
			return SILVER_DURATION_PRICE_3;
		elseif($rate == 4)
			return SILVER_DURATION_PRICE_4;
		else
			return 0;
	}
	else
		return 0;
}
?>