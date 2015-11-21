<?php
require_once('classes/top.class.php');
$testing = false;
$arr1 = array();
$arr2 = array();
$duration_type = array(1 => "3 day", 2 => "1 month", 3 => "3 month", 4 => "1 year");
$cancel_days_before_expire = array(1 => 0, 2 => 7, 3 => 14, 4 => 30);

//========================= [START] daily check for auto extent membership duration [12/09/2007]=====================
echo "<pre>";

//================================== [Start] Check for membership that will expire. ===================================//
for($i=1; $i<7; $i++)
{
	$list = getExpiredMembership($i);
	if($list)
	{
		echo "[Start] Check for membership that will expire in 7 days.\n\n";
		foreach($list as $member)
		{
			if(!isCancelled($member['id']))
			{
				//Check for NOT 3 days membership duration.
				if(($rate = checkForMembershipDurationType($member['payment_received'], $member['payment'])) != 1)
				{
					echo $member['username']." will expire on ".$member['payment']." => current duration ".$duration_type[$rate].".";
					
					$rate = ($rate == 4)?4:$rate+1;
					$type = $member['type'];
					$sql = "SELECT DATE(payment + INTERVAL ".$duration_type[$rate].") as new FROM ".TABLE_MEMBER." WHERE id=".$member['id'];
					$extend_to = DBConnect::retrieve_value($sql);
					echo " Extent to ".$extend_to.". Price = ";
					$pay = getPrice($type, $rate);
					echo $pay.".\n";

					echo $pay;

					$sql = "SELECT paid_via FROM payment_log WHERE username = '$member[username]' and payment_complete = '1' ORDER BY ID DESC LIMIT 1";
					$last_payment = DBConnect::retrieve_value($sql);

					if(!$testing)
					{
						if($last_payment == 4)
						{
							funcs::insertpayment($member['id'],$type,$rate,0,0);
							// Send new email to member.
							funcs::send_memberExtend_customer_paid_via_4($smarty, $member['username'], $member['email'], $type, $extend_to, $pay);
						}
						else
						{
							funcs::insertpayment($member['id'],$type,$rate,0,0);
							// Send new email to member.
							funcs::send_memberExtend_customer($smarty, $member['username'], $member['email'], $type, $extend_to, $pay);
						}
					}

					if($last_payment == 2 || $last_payment == 3)
						array_push($arr1, getReportDetails($member['username']));
					//elseif($last_payment == 4)
					else
						array_push($arr2, getReportDetails($member['username']));
				}
			}
			else
			{
				echo $member['username']." cancelled membership.\n";
			}
		}
		echo "\n[End] Check for membership that will expire.\n\n";
	}
}
//================================== [End] Check for membership that will expire ===================================//
echo "==================================================================================================================\n";
//================================== [Start] Send report to admin. ===================================//
$email1 = "payment@tmp-sn.de";
$email2 = "hs@mintnet.de";
//$email1 = "phai@server.westkit.com";
//$email2 = "phai@server.westkit.com";

if(count($arr1) > 0)
{
	funcs::send_memberExtend_admin($smarty,$email1,'',$arr1);
	echo "\nEmail an $email1";
}

if(count($arr2) > 0)
{
	funcs::send_memberExtend_admin($smarty,$email2,'',$arr2);
	echo "\nEmail an $email1";
}
//================================== [End] Send report to admin. ===================================//

echo "</pre>";

//========================= [END] daily check for auto extent membership duration [20/7/02007]=====================















/**
* Get list of expired membership.
* @param integer $days The days that membership already expired.
* @return the array of membership informations, otherwise false.
* @uses getExpiredMembership()
*/
function getExpiredMembership($days)
{
	$sql = "SELECT id, username,type,email, DATE(payment) as payment, DATE(payment_received) as payment_received FROM ".TABLE_MEMBER." WHERE";
	if($days === null)
		$sql .= " DATE(payment) < CURDATE()";
	else
		$sql .= " (DATE(payment) = (CURDATE() + INTERVAL $days DAY)) AND (DATE(payment_received) <> '0000-00-00')";
	$sql .= " AND type IN (2,3)";
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
function checkForMembershipDurationType($start, $end)
{
	$duration = funcs::dateDiff("-", $end, $start);
	if(($duration > 1) && ($duration < 4))
		return 1;
	elseif($duration < 32)
		return 2;
	elseif($duration < 93)
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
function getReportDetails($username)
{
	$sql = "SELECT p.*,m.ID as m_id,m.username as m_username,m.mobileno as m_mobileno FROM payment_log as p INNER JOIN member as m ON m.username = p.username WHERE m.username = '".$username."' ORDER BY ID DESC LIMIT 1";
	$list = DBConnect::assoc_query_1D($sql);

	if(is_array($list) && (count($list) > 0))
		return $list;
	else
		return false;
}

/**
* check if already calcelled membership.
* @param integer $user_id The member id.
* @return the boolean of membership cancelled.
* @uses isCancelled()
*/
function isCancelled($user_id)
{
	global $cancel_days_before_expire;
	$info = funcs::getPaymentHistory($user_id);
	if($info['cancelled'] == 0)
		return false;
	else
	{
		$rate = checkForMembershipDurationType($info['payday'], $info['new_paid_until']);
		// check for cancelled before. Base on the membership duration.
		$duration = funcs::dateDiff("-", $info['new_paid_until'], $info['cancelled_date']);
		if(($rate == 2) && ($duration >= $cancel_days_before_expire[2]))
			return true;
		elseif(($rate == 3) && ($duration >= $cancel_days_before_expire[3]))
			return true;
		elseif(($rate == 4) && ($duration >= $cancel_days_before_expire[4]))
			return true;
		else
			return false;
	}
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